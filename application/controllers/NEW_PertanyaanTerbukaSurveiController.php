<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PertanyaanTerbukaSurveiController extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		if (!$this->ion_auth->logged_in()) {
			$this->session->set_flashdata('message_warning', 'You must be an admin to view this page');
			redirect('auth', 'refresh');
		}

		$this->load->library('form_validation');
		$this->load->model('PertanyaanTerbukaSurvei_model');
	}

	public function index($id1 = NULL, $id2 = NULL)
	{
		$this->data = [];
		$this->data['title'] = "Pertanyaan Tambahan";

		$this->data['profiles'] = $this->_get_data_profile($id1, $id2);

		$this->db->select('');
		$this->db->from('manage_survey');
		$this->db->where('manage_survey.slug', $this->uri->segment(2));
		$manage_survey = $this->db->get()->row();

		$this->data['is_question'] = $manage_survey->is_question;


		return view('pertanyaan_terbuka_survei/index', $this->data);
	}

	public function ajax_list()
	{
		$slug = $this->uri->segment(2);

		$get_identity = $this->db->get_where('manage_survey', array('slug' => "$slug"))->row();
		$table_identity = $get_identity->table_identity;

		$list = $this->PertanyaanTerbukaSurvei_model->get_datatables($table_identity);
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			if ($value->is_letak_pertanyaan == 1) {
				$letak_pertanyaan = '<b class="text-primary">Paling Atas</b>';
			} elseif ($value->is_letak_pertanyaan == 2) {
				$letak_pertanyaan = '<b class="text-primary">Paling Bawah</b>';
			} elseif ($value->is_letak_pertanyaan == 3) {
				$letak_pertanyaan = '<b class="text-primary">Pertanyaan Pembuka Survei</b>';
			} else {
				$letak_pertanyaan = '<b class="text-primary">Dibawah Unsur</b> ' . $value->nomor_unsur . '. ' . $value->nama_unsur_pelayanan;
			}


			if ($get_identity->is_question == 1) {
				$btn_delete = '<hr><a class="btn btn-light-primary btn-sm font-weight-bold" href="javascript:void(0)" title="Hapus ' . $value->nama_pertanyaan_terbuka . '" onclick="delete_pertanyaan_terbuka(' . "'" . $value->id_pertanyaan_terbuka . "'" . ')"><i class="fa fa-trash"></i> Delete</a>';

				$btn_detail = '<a class="btn btn-dark btn-sm" data-toggle="modal"  onclick="showdetailalur(' . $value->id_pertanyaan_terbuka . ')" href="#modal_detail_alur"><i class="fa fa-random"></i> Alur Pengisian</a>';
			} else {
				$btn_delete = '';
				$btn_detail = '';
			}

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = '<b>' . $value->nomor_pertanyaan_terbuka . '. ' . $value->nama_pertanyaan_terbuka . '</b><br>' . $value->isi_pertanyaan_terbuka . '<hr>' . $letak_pertanyaan;

			if ($value->id_jenis_pilihan_jawaban == 1) {
				$pilihan = [];
				foreach ($this->db->get_where("isi_pertanyaan_ganda_$table_identity", array('id_perincian_pertanyaan_terbuka' => $value->id_perincian_pertanyaan_terbuka))->result() as $get) {
					$pilihan[] = '<label><input type="radio">&ensp;' . $get->pertanyaan_ganda . '&emsp;</label>';
				};

				if ($value->dengan_isian_lainnya == 1) {
					$row[] = implode("<br>", $pilihan) . '<br><label><input type="radio">&ensp;Lainnya&emsp;</label><br>' . $btn_detail;
				} else {
					$row[] = implode("<br>", $pilihan) . '<br>' . $btn_detail;
				};
			} else {
				$row[] = '';
			}

			$row[] = anchor($this->session->userdata('username') . '/' . $this->uri->segment(2) . '/pertanyaan-terbuka/edit/' . $value->id_pertanyaan_terbuka, '<i class="fa fa-edit"></i> Edit', ['class' => 'btn btn-light-primary btn-sm font-weight-bold']) . $btn_delete;



			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->PertanyaanTerbukaSurvei_model->count_all($table_identity),
			"recordsFiltered" => $this->PertanyaanTerbukaSurvei_model->count_filtered($table_identity),
			"data" => $data,
		);

		echo json_encode($output);
	}

	public function add($id1 = NULL, $id2 = NULL)
	{
		$this->data = [];
		$this->data['title'] = "Tambah Pertanyaan Tambahan";

		$this->data['profiles'] = $this->_get_data_profile($id1, $id2);

		$this->db->select('');
		$this->db->from('manage_survey');
		$this->db->where('manage_survey.slug', $this->uri->segment(2));
		$table_identity_manage_survey = $this->db->get()->row()->table_identity;

		$this->db->select('(COUNT(nomor_pertanyaan_terbuka)+1) AS nomor_terbuka');
		$this->db->from('pertanyaan_terbuka_' . $table_identity_manage_survey);
		$nomor_pertanyaan_terbuka = $this->db->get()->row()->nomor_terbuka;

		$this->form_validation->set_rules('nama_pertanyaan_terbuka', 'Nama Pertanyaan Terbuka', 'trim|required');
		$this->form_validation->set_rules('isi_pertanyaan_terbuka', 'Isi Pertanyaan Terbuka', 'trim|required');
		// $this->form_validation->set_rules('is_required', 'Status Pengisian Pertanyaan', 'trim|required');

		$this->data['id_unsur_pelayanan'] = [
			'name' 		=> 'id_unsur_pelayanan',
			'id' 		=> 'id_unsur_pelayanan',
			'options' 	=> $this->PertanyaanTerbukaSurvei_model->dropdown_unsur_pelayanan(),
			'selected' 	=> $this->form_validation->set_value('id_unsur_pelayanan'),
			'class' 	=> "form-control",
			// 'autofocus' => 'autofocus',
			'required' => 'required'
		];

		$this->data['nama_pertanyaan_terbuka'] = [
			'name' 		=> 'nama_pertanyaan_terbuka',
			'id'		=> 'nama_pertanyaan_terbuka',
			'type'		=> 'text',
			'value'		=>	$this->form_validation->set_value('nama_pertanyaan_terbuka'),
			'class'		=> 'form-control',
			'required' => 'required'
		];



		$this->data['isi_pertanyaan_terbuka'] = [
			'name' 		=> 'isi_pertanyaan_terbuka',
			'id'		=> 'isi_pertanyaan_terbuka',
			'type'		=> 'text',
			'value'		=>	$this->form_validation->set_value('isi_pertanyaann_terbuka'),
			'class'		=> 'form-control',
			'rows' 		=> '3'
		];

		$this->data['jumlah_tambahan'] = ($this->db->get("pertanyaan_terbuka_$table_identity_manage_survey")->num_rows()) + 1;

		if ($this->form_validation->run() == FALSE) {

			return view('pertanyaan_terbuka_survei/add', $this->data);
		} else {

			$input 	= $this->input->post(NULL, TRUE);

			if ($input['is_required'] == 1) {
				$is_required = 1;
			} else {
				$is_required = NULL;
			}

			if ($this->uri->segment(5) == 1) {
				$id_unsur_pelayanan = $input['id_unsur_pelayanan'];
				$cek_parent = $this->db->query("SELECT * FROM unsur_pelayanan_$table_identity_manage_survey WHERE id_parent = $id_unsur_pelayanan ORDER BY id ASC");

				if ($cek_parent->num_rows() > 0) {
					$id_unsur_parent = $cek_parent->last_row()->id;
				} else {
					$id_unsur_parent = $input['id_unsur_pelayanan'];
				}

				$data = [
					'id_unsur_pelayanan' 	=> $id_unsur_parent,
					'nomor_pertanyaan_terbuka' 	=> 'T' . $nomor_pertanyaan_terbuka,
					'nama_pertanyaan_terbuka' 	=> $input['nama_pertanyaan_terbuka'],
					'is_required' =>  $is_required
				];
			} else {
				$data = [
					'is_letak_pertanyaan' 	=> $input['is_letak_pertanyaan_tambahan'],
					'nomor_pertanyaan_terbuka' 	=> 'T' . $nomor_pertanyaan_terbuka,
					'nama_pertanyaan_terbuka' 	=> $input['nama_pertanyaan_terbuka'],
					'is_required' =>  $is_required
				];
			}
			// var_dump($data);
			$this->db->insert('pertanyaan_terbuka_' . $table_identity_manage_survey, $data);


			$id_pertanyaan_terbuka = $this->db->insert_id();
			if ($this->input->post('jenis_jawaban') == '2') {
				$object = [
					'isi_pertanyaan_terbuka' 	=> $input['isi_pertanyaan_terbuka'],
					'id_pertanyaan_terbuka' 	=> $id_pertanyaan_terbuka,
					'id_jenis_pilihan_jawaban' 	=> $input['jenis_jawaban']
				];
				$this->db->insert('perincian_pertanyaan_terbuka_' . $table_identity_manage_survey, $object);
			} else {

				$object = [
					'isi_pertanyaan_terbuka' 	=> $input['isi_pertanyaan_terbuka'],
					'id_pertanyaan_terbuka' 	=> $id_pertanyaan_terbuka,
					'id_jenis_pilihan_jawaban' 	=> $input['jenis_jawaban']
				];
				// var_dump($object);
				$this->db->insert('perincian_pertanyaan_terbuka_' . $table_identity_manage_survey, $object);

				$id_perincian_pertanyaan_terbuka = $this->db->insert_id();

				if (isset($_POST['opsi_pilihan_jawaban'])) {
					$opsi_pilihan_jawaban = $input['opsi_pilihan_jawaban'];
				} else {
					$opsi_pilihan_jawaban =  2;
				}


				$result = array();
				foreach ($_POST['pilihan_jawaban'] as $key => $val) {

					$result[] = array(
						'id_perincian_pertanyaan_terbuka' => $id_perincian_pertanyaan_terbuka,
						'pertanyaan_ganda' => $_POST['pilihan_jawaban'][$key],
						'dengan_isian_lainnya' => $opsi_pilihan_jawaban
					);
				}
				$this->db->insert_batch('isi_pertanyaan_ganda_' . $table_identity_manage_survey, $result);

				//DELETE PILIHAN JAWABAN YANG KOSONG
				$this->db->query("DELETE FROM isi_pertanyaan_ganda_$table_identity_manage_survey WHERE id_perincian_pertanyaan_terbuka = $id_perincian_pertanyaan_terbuka && pertanyaan_ganda = ''");
			}

			if ($this->db->affected_rows() > 0) {
				$this->session->set_flashdata('message_success', 'Berhasil menambah data');
				redirect(base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2) . '/pertanyaan-terbuka', 'refresh');
			} else {

				$this->data['message_data_danger'] = "Gagal menambah data";
				return view('pertanyaan_terbuka_survei/add', $this->data);
			}
		}
	}

	public function edit($id1 = NULL, $id2 = NULL)
	{
		$this->data = [];
		$this->data['title'] = "Edit Pertanyaan Tambahan";

		$this->data['profiles'] = $this->_get_data_profile($id1, $id2);

		$this->db->select('');
		$this->db->from('manage_survey');
		$this->db->where('manage_survey.slug', $this->uri->segment(2));
		$table_identity_manage_survey = $this->db->get()->row()->table_identity;

		$this->db->select("*, pertanyaan_terbuka_$table_identity_manage_survey.id AS id_pertanyaan_terbuka");
		$this->db->from('pertanyaan_terbuka_' . $table_identity_manage_survey);
		$this->db->join("perincian_pertanyaan_terbuka_$table_identity_manage_survey", "pertanyaan_terbuka_$table_identity_manage_survey.id = perincian_pertanyaan_terbuka_$table_identity_manage_survey.id_pertanyaan_terbuka");
		$this->db->where("pertanyaan_terbuka_$table_identity_manage_survey.id =", $this->uri->segment(5));
		$this->data['search_data'] = $this->db->get();

		if ($this->data['search_data']->num_rows() == 0) {
			$this->session->set_flashdata('message_danger', 'Data tidak ditemukan');
			redirect($this->session->userdata('urlback'), 'refresh');
		}
		$this->data['current'] = $this->data['search_data']->row();

		// $this->data['perincian'] = $this->db->get_where('perincian_pertanyaan_terbuka_' . $table_identity_manage_survey, ['id_pertanyaan_terbuka' => $this->uri->segment(5)])->row();
		// var_dump($this->data['perincian']);

		$id_pertanyaan_terbuka = $this->uri->segment(5);

		$query = $this->db->query("SELECT * , isi_pertanyaan_ganda_$table_identity_manage_survey.id AS id_isi_pertanyaan_ganda
		FROM isi_pertanyaan_ganda_$table_identity_manage_survey
		JOIN perincian_pertanyaan_terbuka_$table_identity_manage_survey ON isi_pertanyaan_ganda_$table_identity_manage_survey.id_perincian_pertanyaan_terbuka = perincian_pertanyaan_terbuka_$table_identity_manage_survey.id
		WHERE id_pertanyaan_terbuka = '$id_pertanyaan_terbuka'");
		$this->data['pilihan_jawaban'] = $query->result();

		// var_dump($this->data['pilihan_jawaban']);

		$this->form_validation->set_rules('nama_pertanyaan_terbuka', 'Nama Pertanyaan Terbuka', 'trim|required');
		$this->form_validation->set_rules('isi_pertanyaan_terbuka', 'Isi Pertanyaan Terbuka', 'trim|required');

		$this->data['nama_pertanyaan_terbuka'] = [
			'name' 		=> 'nama_pertanyaan_terbuka',
			'id'		=> 'nama_pertanyaan_terbuka',
			'type'		=> 'text',
			'value'		=>	$this->form_validation->set_value('nama_pertanyaan_terbuka', $this->data['current']->nama_pertanyaan_terbuka),
			'class'		=> 'form-control',
			'autofocus' => 'autofocus',
			'required' => 'required'
		];


		// $this->data['id_unsur_pelayanan'] = [
		// 	'value'		=>	$this->form_validation->set_value('id_unsur_pelayanan', $this->data['current']->nomor_unsur . '. ' . $this->data['current']->nama_unsur_pelayanan),
		// 	'class' 	=> "form-control",
		// 	'autofocus' => 'autofocus',
		// 	'disabled' => 'disabled'
		// ];

		$this->data['isi_pertanyaan_terbuka'] = [
			'name' 		=> 'isi_pertanyaan_terbuka',
			'id'		=> 'isi_pertanyaan_terbuka',
			'type'		=> 'text',
			'value'		=>	$this->form_validation->set_value('isi_pertanyaann_terbuka', $this->data['current']->isi_pertanyaan_terbuka),
			'class'		=> 'form-control',
			'rows' 		=> '3',
			'required' => 'required'
		];

		if ($this->form_validation->run() == FALSE) {

			return view('pertanyaan_terbuka_survei/edit', $this->data);
		} else {

			$input 	= $this->input->post(NULL, TRUE);

			if ($input['is_required'] == 1) {
				$is_required = 1;
			} else {
				$is_required = NULL;
			}

			$data = [
				'nama_pertanyaan_terbuka' 	=> $input['nama_pertanyaan_terbuka'],
				'is_required' =>  $is_required
			];

			$this->db->where('id', $id_pertanyaan_terbuka);
			$this->db->update('pertanyaan_terbuka_' . $table_identity_manage_survey, $data);
			// var_dump($data);

			$object = [
				'isi_pertanyaan_terbuka' 	=> $input['isi_pertanyaan_terbuka']
			];

			$this->db->where('id_pertanyaan_terbuka', $id_pertanyaan_terbuka);
			$this->db->update('perincian_pertanyaan_terbuka_' . $table_identity_manage_survey, $object);

			if ($this->input->post('id_jenis_jawaban') == 1) {

				$id = $input['id_kategori'];
				$pertanyaan_ganda = $input['pertanyaan_ganda'];

				for ($i = 0; $i < sizeof($id); $i++) {
					$kategori = array(
						'id' => $id[$i],
						'pertanyaan_ganda' => ($pertanyaan_ganda[$i])
					);
					$this->db->where('id', $id[$i]);
					$this->db->update('isi_pertanyaan_ganda_' . $table_identity_manage_survey, $kategori);
				}
				// var_dump($kategori);
			}
			$this->session->set_flashdata('message_success', 'Berhasil mengubah data');
			redirect(base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2) . '/pertanyaan-terbuka', 'refresh');
		}
	}

	public function delete($id = NULL)
	{
		$this->db->select('');
		$this->db->from('manage_survey');
		$this->db->where('manage_survey.slug', $this->uri->segment(2));
		$table_identity_manage_survey = $this->db->get()->row()->table_identity;

		$id_pertanyaan_terbuka = $this->uri->segment('5');

		$query = $this->db->query("SELECT *, perincian_pertanyaan_terbuka_$table_identity_manage_survey.id AS id_perincian_pertanyaan_terbuka
		FROM pertanyaan_terbuka_$table_identity_manage_survey
		JOIN perincian_pertanyaan_terbuka_$table_identity_manage_survey ON pertanyaan_terbuka_$table_identity_manage_survey.id = perincian_pertanyaan_terbuka_$table_identity_manage_survey.id_pertanyaan_terbuka
		WHERE id_pertanyaan_terbuka = $id_pertanyaan_terbuka
		");
		$current = $query->row();
		// var_dump($current);

		$this->db->where('id_perincian_pertanyaan_terbuka', $current->id_perincian_pertanyaan_terbuka);
		$this->db->delete('isi_pertanyaan_ganda_' . $table_identity_manage_survey);

		$this->db->where('id', $current->id_pertanyaan_terbuka);
		$this->db->delete('perincian_pertanyaan_terbuka_' . $table_identity_manage_survey);

		$this->db->where('id', $current->id_pertanyaan_terbuka);
		$this->db->delete('pertanyaan_terbuka_' . $table_identity_manage_survey);

		echo json_encode(array("status" => TRUE));
	}


	public function detail_alur()
	{
		$this->data = [];
		$this->data['title'] = "Detail Alur";

		$id_pertanyaan_terbuka = $this->uri->segment(5);

		$this->data['manage_survey'] = $this->db->get_where('manage_survey', array('slug' => $this->uri->segment(2)))->row();
		$table_identity = $this->data['manage_survey']->table_identity;


		$this->data['kategori_terbuka'] = $this->db->query("SELECT *, isi_pertanyaan_ganda_$table_identity.id AS id_kategori, (SELECT nomor_pertanyaan_terbuka FROM pertanyaan_terbuka_$table_identity WHERE id_pertanyaan_terbuka = pertanyaan_terbuka_$table_identity.id) AS nomor_pertanyaan_terbuka
		FROM isi_pertanyaan_ganda_$table_identity
		JOIN perincian_pertanyaan_terbuka_$table_identity ON isi_pertanyaan_ganda_$table_identity.id_perincian_pertanyaan_terbuka = perincian_pertanyaan_terbuka_$table_identity.id
		WHERE id_pertanyaan_terbuka = $id_pertanyaan_terbuka");
		// var_dump($this->data['kategori_terbuka']->result());


		return view('pertanyaan_terbuka_survei/detail_alur', $this->data);
	}


	public function update_detail_alur()
	{
		$manage_survey = $this->db->get_where('manage_survey', array('slug' => $this->uri->segment(2)))->row();
		$table_identity = $manage_survey->table_identity;


		$input = $this->input->post(NULL, TRUE);
		$id = $input['id_kategori'];
		$is_next_step = $input['is_next_step'];
		for ($i = 0; $i < sizeof($id); $i++) {
			$kategori = array(
				'id' => $id[$i],
				'is_next_step' => $is_next_step[$i]
			);
			$this->db->where('id', $id[$i]);
			$this->db->update('isi_pertanyaan_ganda_' . $table_identity, $kategori);
		}

		$pesan = 'Data berhasil disimpan';
		$msg = ['sukses' => $pesan];
		echo json_encode($msg);
	}


	public function _get_data_profile($id1, $id2)
	{
		$this->db->select('*');
		$this->db->from('users');
		$this->db->join('users_groups', 'users.id = users_groups.user_id');
		$this->db->where('users.username', $this->session->userdata('username'));
		$data_user = $this->db->get()->row();
		$user_identity = 'drs' . $data_user->is_parent;

		$this->db->select('users.username, manage_survey.survey_name, is_question, manage_survey.slug, manage_survey.description, manage_survey.is_privacy, manage_survey.table_identity, manage_survey.id_jenis_pelayanan, manage_survey.atribut_pertanyaan_survey');
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
}

/* End of file PertanyaanTerbukaSurveiController.php */
/* Location: ./application/controllers/PertanyaanTerbukaSurveiController.php */