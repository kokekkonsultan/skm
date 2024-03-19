<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ProfilRespondenSurveiController extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->library('ion_auth');

		if (!$this->ion_auth->logged_in()) {
			$this->session->set_flashdata('message_warning', 'You must be logged in to access this page');
			redirect('auth', 'refresh');
		}

		$this->load->library('form_validation');
		$this->load->model('ProfilRespondenSurvei_model', 'models');
	}

	public function index($id1, $id2)
	{
		$this->data = [];
		$this->data['title'] = 'Profil Responden';
		$this->data['profiles'] = $this->_get_data_profile($id1, $id2);

		$manage_survey = $this->db->get_where('manage_survey', array('slug' => $id2))->row();
		$table_identity = $manage_survey->table_identity;
		$this->data['is_question'] = $manage_survey->is_question;

		$this->data['profil_default'] = $this->db->query("SELECT *
		FROM profil_responden
		WHERE NOT EXISTS (SELECT * FROM profil_responden_$table_identity WHERE profil_responden.nama_profil_responden = profil_responden_$table_identity.nama_profil_responden)");

		return view('profil_responden_survei/index', $this->data);
	}

	public function ajax_list()
	{
		$slug = $this->uri->segment(2);
		$get_identity = $this->db->get_where('manage_survey', array('slug' => "$slug"))->row();
		$table_identity = $get_identity->table_identity;

		$kategori_profil = $this->db->get('kategori_profil_responden_' . $table_identity);

		$list = $this->models->get_datatables($table_identity);
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			$pilihan = [];
			foreach ($kategori_profil->result() as $get) {
				if ($get->id_profil_responden == $value->id) {
					$pilihan[] =  '<label><input type="radio">&ensp;' . $get->nama_kategori_profil_responden . '&emsp;</label>';
				}
			}
			$jawaban = implode("<br>", $pilihan);

			$no++;
			$row = array();
			$row[] = $no;
			$row[] =  $value->nama_profil_responden;
			$row[] = $jawaban;
			$row[] = anchor($this->session->userdata('username') . '/' . $this->uri->segment(2) . '/profil-responden-survei/edit/' . $value->id, '<i class="fa fa-edit"></i> Edit', ['class' => 'btn btn-light-primary btn-sm font-weight-bold shadow']);
			if ($get_identity->is_question == 1) {
				$row[] = '<a class="btn btn-light-primary btn-sm font-weight-bold shadow" href="javascript:void(0)" title="Hapus ' . $value->nama_profil_responden . '" onclick="delete_data(' . "'" . $value->id . "'" . ')"><i class="fa fa-trash"></i> Delete</a>';
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

	public function add_default()
	{
		$this->data = [];
		$this->data['title'] = 'Profil Responden';

		$manage_survey = $this->db->get_where('manage_survey', array('slug' => $this->uri->segment(2)))->row();
		$table_identity = $manage_survey->table_identity;

		$check = $this->input->post('check_list[]');
		$kode = [];
		foreach ($check as $row) {
			$kode[] = $row;
		}
		$id = implode(",", $kode);

		$this->db->query("INSERT INTO profil_responden_$table_identity SELECT id, nama_profil_responden, jenis_isian, is_default, type_data FROM profil_responden WHERE id IN ($id)");

		$this->db->query("INSERT INTO kategori_profil_responden_$table_identity (id_profil_responden, nama_kategori_profil_responden) SELECT id_profil_responden, nama_kategori_profil_responden FROM kategori_profil_responden WHERE id_profil_responden IN ($id)");

		//BUAT COLUMN BARU DI TABEL RESPONDEN
		$data_profil = $this->db->query("SELECT *,  REPLACE(LOWER(nama_profil_responden), ' ', '_') AS nama_alias, IF(type_data != '' ,'VARCHAR (255)','INT') AS type_data_db FROM profil_responden_$table_identity WHERE id IN ($id)")->result();

		foreach ($data_profil as $row) {
			$this->db->query("ALTER TABLE responden_$table_identity ADD $row->nama_alias $row->type_data_db");
		}

		$this->session->set_flashdata('message_success', 'Berhasil menambah data');
		redirect(base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2) . '/profil-responden-survei', 'refresh');
	}

	public function add_custom($id1, $id2)
	{
		$this->data = [];
		$this->data['title'] = 'Custom Profil Responden';
		$this->data['profiles'] = $this->_get_data_profile($id1, $id2);

		$this->db->select('');
		$this->db->from('manage_survey');
		$this->db->where('manage_survey.slug', $this->uri->segment(2));
		$table_identity_manage_survey = $this->db->get()->row()->table_identity;

		$this->form_validation->set_rules('nama_profil_responden', 'Nama Profil Responden', 'trim|required');
		$this->form_validation->set_rules('jenis_jawaban', 'Jenis Isian', 'trim|required');

		$this->data['nama_profil_responden'] = [
			'name' 		=> 'nama_profil_responden',
			'id'		=> 'nama_profil_responden',
			'type'		=> 'text',
			'value'		=>	$this->form_validation->set_value('nama_profil_responden'),
			'class'		=> 'form-control',
			'autofocus' => 'autofocus'
		];

		if ($this->form_validation->run() == FALSE) {

			return view('profil_responden_survei/form_add', $this->data);
		} else {

			$input 	= $this->input->post(NULL, TRUE);

			$profil = $this->db->get('profil_responden')->num_rows();
			$profil_survei = $this->db->get_where('profil_responden_' . $table_identity_manage_survey, array('is_default' => 2));

			$nama_profil_responden = $input['nama_profil_responden'];

			$nama_alias = preg_replace('/\s+/', '_', strtolower($nama_profil_responden));

			$cek_nama = $this->db->query("SELECT * FROM (SELECT *, REPLACE(LOWER(nama_profil_responden), ' ', '_') AS nama_alias FROM profil_responden_$table_identity_manage_survey) AS profil_responden_$table_identity_manage_survey WHERE nama_alias = '$nama_alias'");

			if ($cek_nama->num_rows() != 0) {
				$this->session->set_flashdata('message_danger', 'Mohon maaf Nama Profil Responden yang anda isikan sudah ada!');
				redirect(base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2) . '/profil-responden-survei', 'refresh');
			}

			//CEK PERTANYAAN CUSTOM CUDAH ADA APA BELUM
			if ($profil_survei->num_rows() == 0) {
				$cek_id = $profil + 1;
			} else {
				$cek_id = '';
			};

			//CEK TYPE JENIS JAWABAN
			if ($input['jenis_jawaban'] == 2) {
				$cek_type_data = $input['type_data'];
			} else {
				$cek_type_data = '';
			};

			$data = [
				'id' => $cek_id,
				'nama_profil_responden' => $input['nama_profil_responden'],
				'jenis_isian' => $input['jenis_jawaban'],
				'is_default' => 2,
				'type_data' => $cek_type_data
			];
			$this->db->insert('profil_responden_' . $table_identity_manage_survey, $data);

			$id_profil_responden = $this->db->insert_id();

			if ($input['jenis_jawaban'] == '1') {

				$id_profil_responden = $this->db->insert_id();
				$pilihan_jawaban = $input['pilihan_jawaban'];

				$result = array();
				foreach ($_POST['pilihan_jawaban'] as $key => $val) {
					$result[] = array(
						'id_profil_responden' => $id_profil_responden,
						'nama_kategori_profil_responden' => $pilihan_jawaban[$key]
					);
				}
				$this->db->insert_batch('kategori_profil_responden_' . $table_identity_manage_survey, $result);
			}

			$data_profil = $this->db->query("SELECT *,  REPLACE(LOWER(nama_profil_responden), ' ', '_') AS nama_alias, IF(type_data != '' ,'VARCHAR (255)','INT') AS type_data_db FROM profil_responden_$table_identity_manage_survey WHERE id = $id_profil_responden")->row();

			$this->db->query("ALTER TABLE responden_$table_identity_manage_survey ADD $data_profil->nama_alias $data_profil->type_data_db");

			$this->session->set_flashdata('message_success', 'Berhasil menambah data');
			redirect(base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2) . '/profil-responden-survei', 'refresh');
		}
	}

	public function edit($id1, $id2)
	{
		$this->data = [];
		$this->data['title'] = 'Edit Profil Responden';
		$this->data['profiles'] = $this->_get_data_profile($id1, $id2);

		$this->db->select('');
		$this->db->from('manage_survey');
		$this->db->where('manage_survey.slug', $this->uri->segment(2));
		$table_identity_manage_survey = $this->db->get()->row()->table_identity;

		$this->data['profil_responden'] = $this->db->query("SELECT *,  REPLACE(LOWER(nama_profil_responden), ' ', '_') AS nama_alias, IF(type_data != '' ,'VARCHAR (255)','INT') AS type_data_db FROM profil_responden_$table_identity_manage_survey WHERE id =" . $this->uri->segment(5))->row();
		$profil_responden = $this->data['profil_responden'];

		$this->data['kategori_profil_responden'] = $this->db->get_where('kategori_profil_responden_' . $table_identity_manage_survey, array('id_profil_responden' => $this->uri->segment(5)));

		$this->form_validation->set_rules('nama_profil_responden', 'Nama Profil Responden', 'trim|required');

		if ($this->form_validation->run() == FALSE) {

			$this->data['nama_profil_responden'] = [
				'name' 		=> 'nama_profil_responden',
				'id'		=> 'nama_profil_responden',
				'type'		=> 'text',
				'value'		=>	$this->form_validation->set_value('nama_profil_responden', $profil_responden->nama_profil_responden),
				'class'		=> 'form-control',
				'autofocus' => 'autofocus'
			];

			$this->data['jenis_isian'] = [
				'name' 		=> 'jenis_isian',
				'type'		=> 'hidden',
				'value'		=>	$this->form_validation->set_value('jenis_isian', $profil_responden->jenis_isian)
			];

			return view('profil_responden_survei/form_edit', $this->data);
		} else {

			$input 	= $this->input->post(NULL, TRUE);

			//CEK TYPE DATA
			if ($input['type_data'] == '') {
				$cek_type_data = 'INT';
			} else {
				$cek_type_data = 'VARCHAR (255)';
			};

			$new_nama_profil_responden =  preg_replace('/\s+/', '_', strtolower($input['nama_profil_responden']));
			$this->db->query("ALTER TABLE responden_$table_identity_manage_survey CHANGE $profil_responden->nama_alias $new_nama_profil_responden $cek_type_data");

			$data = [
				'nama_profil_responden' 	=> $input['nama_profil_responden'],
				'type_data' => $input['type_data']
			];
			$this->db->where('id', $this->uri->segment(5));
			$this->db->update('profil_responden_' . $table_identity_manage_survey, $data);

			if ($this->input->post('jenis_isian') == '1') {

				$id = $input['id_kategori'];
				$pertanyaan_ganda = $input['jawaban'];

				for ($i = 0; $i < sizeof($id); $i++) {
					$kategori = array(
						'nama_kategori_profil_responden' => ($pertanyaan_ganda[$i])
					);
					$this->db->where('id', $id[$i]);
					$this->db->update('kategori_profil_responden_' . $table_identity_manage_survey, $kategori);
				}
			}

			$this->session->set_flashdata('message_success', 'Berhasil mengubah data');
			redirect(base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2) . '/profil-responden-survei', 'refresh');
		}
	}

	public function delete($id = NULL)
	{
		$this->db->select('');
		$this->db->from('manage_survey');
		$this->db->where('manage_survey.slug', $this->uri->segment(2));
		$table_identity_manage_survey = $this->db->get()->row()->table_identity;

		$profil_responden = $this->db->get_where('profil_responden_' . $table_identity_manage_survey, array('id' => $this->uri->segment('5')))->row();
		$nama_alias = preg_replace('/\s+/', '_', strtolower($profil_responden->nama_profil_responden));

		$this->db->query("ALTER TABLE responden_$table_identity_manage_survey DROP COLUMN $nama_alias");

		$this->db->where('id_profil_responden', $this->uri->segment('5'));
		$this->db->delete('kategori_profil_responden_' . $table_identity_manage_survey);

		$this->db->where('id', $this->uri->segment('5'));
		$this->db->delete('profil_responden_' . $table_identity_manage_survey);

		echo json_encode(array("status" => TRUE));
	}

	public function _get_data_profile($id1, $id2)
	{
		$this->db->select('users.username, manage_survey.survey_name, manage_survey.slug, manage_survey.description, manage_survey.is_privacy, manage_survey.table_identity, manage_survey.id_jenis_pelayanan, manage_survey.atribut_pertanyaan_survey');
		$this->db->from('users');
		$this->db->join('manage_survey', 'manage_survey.id_user = users.id');
		$this->db->where('users.username', $id1);
		$this->db->where('manage_survey.slug', $id2);
		$profiles = $this->db->get();

		if ($profiles->num_rows() == 0) {
			echo 'Survey tidak ditemukan atau sudah dihapus !';
			exit();
		}
		return $profiles->row();
	}
}