<?php
defined('BASEPATH') or exit('No direct script access allowed');

class LayananSurveiController extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		if (!$this->ion_auth->logged_in()) {
			$this->session->set_flashdata('message_warning', 'You must be an admin to view this page');
			redirect('auth', 'refresh');
		}

		$this->load->library('form_validation');
		// $this->load->model('DefinisiSkalaSurvei_model', 'Models');
		$this->load->model('LayananSurvei_model', 'models');
	}

	public function index($id1, $id2)
	{
		$this->data = [];
		$this->data['title'] = 'Jenis Pelayanan';
		$this->data['profiles'] = $this->_get_data_profile($id1, $id2);

		$slug = $this->uri->segment(2);
		$manage_survey = $this->db->get_where('manage_survey', array('slug' => "$slug"))->row();
		$table_identity = $manage_survey->table_identity;

		$this->db->select("layanan_survei_$table_identity.*, kategori_layanan_survei_$table_identity.nama_kategori_layanan");
		$this->db->from("layanan_survei_$table_identity");
		$this->db->join("kategori_layanan_survei_$table_identity", "kategori_layanan_survei_$table_identity.id = layanan_survei_$table_identity.id_kategori_layanan", "left");
		$this->db->order_by("layanan_survei_$table_identity.urutan asc");
		$query = $this->db->get();
		$this->data['layanan'] = $query;
		// $this->data['layanan'] = $this->db->get("layanan_survei_$table_identity");

		$this->db->from("kategori_layanan_survei_$table_identity");
		$this->db->where("is_active", 1);
		$this->db->order_by("urutan asc");
		$query_cat = $this->db->get();
		$this->data['kategori_layanan'] = $query_cat;
		// $this->data['kategori_layanan'] = $this->db->get("kategori_layanan_survei_$table_identity");




		return view('layanan_survei/index', $this->data);
	}


	public function ajax_list()
	{
		$slug = $this->uri->segment(2);
		$get_identity = $this->db->get_where('manage_survey', array('slug' => "$slug"))->row();
		$table_identity = $get_identity->table_identity;

		$profiles = $this->_get_data_profile($this->uri->segment(1), $this->uri->segment(2));

		$layanan_survei = $this->db->get("layanan_survei_$table_identity")->num_rows();

		$list = $this->models->get_datatables($table_identity);
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			$no++;
			$row = array();

			for ($i = 1; $i <= $layanan_survei; ++$i) {
				$selected = $no == $i ? 'selected' : '';
				$urutan[$no][] = '<option value="' . $i . '"' . $selected . '>' . $i . '</option>';
			}

			($value->is_active == 1) ? $aktif = 'info' : $aktif = 'danger';
			($value->is_active == 1) ? $gunakan = 'Digunakan' : $gunakan = 'Tidak digunakan';

			$row[] = '<input value="' . $value->id . '" name="id[]" hidden>
			<select name="urutan[]" class="form-control-sm">'
				. implode("<br>", $urutan[$no]) .
				'</select>';

			// $row[] = $no;
			$row[] =  $value->nama_layanan;
			if ($profiles->is_kategori_layanan_survei == 1) {
				$row[] = $value->nama_kategori_layanan;
			}

			$row[] = '<span class="badge badge-' . $aktif . '">' . $gunakan . '</span>';

			if ($profiles->is_question == 1) {
				$row[] = '<a class="btn btn-light-primary btn-sm" data-toggle="modal" data-target="#edit_' . $value->id . '"><i class="fa fa-edit"></i> Edit</a> <a class="btn btn-light-primary btn-sm font-weight-bold shadow" href="javascript:void(0)" title="Hapus ' . $value->nama_layanan . '" onclick="delete_data(' . $value->id . ')"><i class="fa fa-trash"></i> Delete</a>';
			} else {
				$row[] = '<a class="btn btn-light-primary btn-sm" data-toggle="modal" data-target="#edit_' . $value->id . '"><i class="fa fa-edit"></i> Edit</a>';
			}


			$data[] = $row;
		}
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->models->count_all($table_identity),
			"recordsFiltered" => $this->models->count_filtered($table_identity),
			"data" => $data,
		);
		echo json_encode($output);
	}


	public function add()
	{
		$this->db->select('*');
		$this->db->from('manage_survey');
		$this->db->where('manage_survey.slug', $this->uri->segment(2));
		$manage_survey = $this->db->get()->row();

		$urutan = $this->db->get("layanan_survei_$manage_survey->table_identity")->num_rows() + 1;

		$input 	= $this->input->post(NULL, TRUE);
		$object = [
			'id_kategori_layanan' 	=> $input['id_kategori_layanan'],
			'nama_layanan' 	=> $input['nama_layanan'],
			'urutan' 	=> $urutan,
			// 'is_active' 	=> $input['is_active']
			'is_active' 	=> 1
		];
		$this->db->insert('layanan_survei_' . $manage_survey->table_identity, $object);

		$pesan = 'Data berhasil disimpan';
		$msg = ['sukses' => $pesan];
		echo json_encode($msg);
	}


	public function edit()
	{
		$this->db->select('*');
		$this->db->from('manage_survey');
		$this->db->where('manage_survey.slug', $this->uri->segment(2));
		$manage_survey = $this->db->get()->row();

		$input 	= $this->input->post(NULL, TRUE);
		$object = [
			'id_kategori_layanan' 	=> $input['id_kategori_layanan'],
			'nama_layanan' 	=> $input['nama_layanan'],
			'is_active' 	=> $input['is_active']
		];

		$this->db->where('id', $input['id']);
		$this->db->update('layanan_survei_' . $manage_survey->table_identity, $object);

		$pesan = 'Data berhasil disimpan';
		$msg = ['sukses' => $pesan];
		echo json_encode($msg);
	}


	public function delete()
	{
		$this->db->select('*');
		$this->db->from('manage_survey');
		$this->db->where('manage_survey.slug', $this->uri->segment(2));
		$manage_survey = $this->db->get()->row();

		$this->db->where('id', $this->uri->segment(5));
		$this->db->delete('layanan_survei_' . $manage_survey->table_identity);

		echo json_encode(array("status" => TRUE));
	}


	public function update_urutan()
	{
		$manage_survey = $this->db->get_where('manage_survey', array('slug' => $this->uri->segment(2)))->row();
		$table_identity = $manage_survey->table_identity;

		foreach ($_POST['id'] as $key => $val) {
			$id = (int)$_POST['id'][$key];
			$urutan = $_POST['urutan'][$key];
			$this->db->query("UPDATE layanan_survei_$table_identity SET urutan=$urutan WHERE id=$id");
		}

		$pesan = 'Data berhasil disimpan';
		$msg = ['sukses' => $pesan];
		echo json_encode($msg);
	}


	public function activate()
	{
		$manage_survey = $this->db->get_where('manage_survey', array('slug' => $this->uri->segment(2)))->row();
		$table_identity = $manage_survey->table_identity;

		$input 	= $this->input->post(NULL, TRUE);
		$is_kategori_layanan_survei = (isset($input['is_kategori_layanan_survei'])) ? $input['is_kategori_layanan_survei'] : 0;
		$object = [
			'is_kategori_layanan_survei' 	=> $is_kategori_layanan_survei,
		];

		$this->db->where('manage_survey.slug', $this->uri->segment(2));
		$this->db->update('manage_survey', $object);

		if($is_kategori_layanan_survei==0){
			$this->db->truncate("kategori_layanan_survei_$table_identity");
			$this->db->truncate("layanan_survei_$table_identity");
		}

		$pesan = 'Data berhasil disimpan';
		$msg = ['sukses' => $pesan];
		echo json_encode($msg);
	}


	public function grafik($id1, $id2)
	{
		$this->data = [];
		$this->data['title'] = 'Grafik Jenis Pelayanan';
		$this->data['profiles'] = $this->_get_data_profile($id1, $id2);


		$this->db->select('*');
		$this->db->from('manage_survey');
		$this->db->where('manage_survey.slug', $this->uri->segment(2));
		$manage_survey = $this->db->get()->row();
		$this->data['table_identity'] = $manage_survey->table_identity;


		if ($this->db->get_where('survey_' . $manage_survey->table_identity, array('is_submit' => 1))->num_rows() == 0) {
			$this->data['pesan'] = 'Survei belum dimulai atau belum ada responden !';
			return view('not_questions/index', $this->data);
		}

		return view('layanan_survei/grafik', $this->data);
	}


	public function _get_data_profile($id1, $id2)
	{
		$this->db->select('*');
		$this->db->from('users');
		$this->db->join('users_groups', 'users.id = users_groups.user_id');
		$this->db->where('users.username', $this->session->userdata('username'));
		$data_user = $this->db->get()->row();
		$user_identity = 'drs' . $data_user->is_parent;

		$this->db->select('*');
		if ($data_user->group_id == 2) {
			$this->db->from('users');
			$this->db->join('manage_survey', 'manage_survey.id_user = users.id');
		} else {
			$this->db->from('manage_survey');
			$this->db->join("supervisor_$user_identity", "manage_survey.id_berlangganan = supervisor_$user_identity.id_berlangganan");
			$this->db->join("users", "supervisor_$user_identity.id_user = users.id");
		}
		$this->db->where('users.username', $id1);
		$this->db->where('manage_survey.slug', $id2);
		$profiles = $this->db->get();

		if ($profiles->num_rows() == 0) {
			// echo 'Survey tidak ditemukan atau sudah dihapus !';
			// exit();
			show_404();
		}
		return $profiles->row();
	}

	public function update_chart($id1, $id2)
	{
		$mode = $_POST['mode'];
		$id = $_POST['nilai_id'];

		if ($mode == 'true') {
			$object = [
				'is_chart_layanan_survei' => 1
			];
			$this->db->where('slug', $id2);
			$this->db->update('manage_survey', $object);


			$message = 'Chart menampilkan semua data layanan!';
			$success = 'Enabled';
			echo json_encode(array('message' => $message, '$success' => $success));
		} else if ($mode == 'false') {
			$object = [
				'is_chart_layanan_survei' => 2
			];
			$this->db->where('slug', $id2);
			$this->db->update('manage_survey', $object);


			$message = 'Chart hanya menampilkan data layanan yang bernilai!';
			$success = 'Disabled';
			echo json_encode(array('message' => $message, 'success' => $success));
		}
	}
}

/* End of file PertanyaanKualitatifController.php */
/* Location: ./application/controllers/PertanyaanKualitatifController.php */
