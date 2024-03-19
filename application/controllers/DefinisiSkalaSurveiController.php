<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DefinisiSkalaSurveiController extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		if (!$this->ion_auth->logged_in()) {
			$this->session->set_flashdata('message_warning', 'You must be an admin to view this page');
			redirect('auth', 'refresh');
		}

		$this->load->library('form_validation');
		$this->load->model('DefinisiSkalaSurvei_model', 'Models');
	}

	public function index($id1, $id2)
	{

		$url = $this->uri->uri_string();
		$this->session->set_userdata('urlback', $url);

		$this->data = [];
		$this->data['title'] = 'Pendefinisian Range Nilai Interval';
		$this->data['profiles'] = $this->_get_data_profile($id1, $id2);

		$slug = $this->uri->segment(2);
		$manage_survey = $this->db->get_where('manage_survey', array('slug' => "$slug"))->row();
		$table_identity = $manage_survey->table_identity;

		$this->data['definisi_skala'] = $this->db->get("definisi_skala_$table_identity");

		return view('definisi_skala_survei/index', $this->data);
	}

	public function ajax_list()
	{
		$slug = $this->uri->segment(2);

		$get_identity = $this->db->get_where('manage_survey', array('slug' => "$slug"))->row();
		$table_identity = $get_identity->table_identity;

		$list = $this->Models->get_datatables($table_identity);
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $value->range_atas;
			$row[] = $value->range_bawah;
			$row[] = $value->mutu;
			$row[] = $value->kategori;
			$row[] = '<button type="button" class="btn btn-light-primary btn-sm shadow" data-toggle="modal" data-target="#edit' . $value->id . '"><i class="fa fa-edit"></i> Edit</button>';

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->Models->count_all($table_identity),
			"recordsFiltered" => $this->Models->count_filtered($table_identity),
			"data" => $data,
		);

		echo json_encode($output);
	}



	// public function add($id1, $id2)
	// {
	// 	$this->data = [];
	// 	$this->data['title'] = 'Custom Profil Responden';
	// 	$this->data['profiles'] = $this->_get_data_profile($id1, $id2);

	// 	$this->db->select('');
	// 	$this->db->from('manage_survey');
	// 	$this->db->where('manage_survey.slug', $this->uri->segment(2));
	// 	$table_identity = $this->db->get()->row()->table_identity;

	// 	$this->form_validation->set_rules('id', 'ID', 'trim|required');

	// 	if ($this->form_validation->run() == FALSE) {

	// 		return view('definisi_skala_survei/form_add', $this->data);
	// 	} else {

	// 		$input 	= $this->input->post(NULL, TRUE);
	// 		$pilihan_jawaban = $input['pilihan_jawaban'];
	// 		var_dump($pilihan_jawaban);

	// 		// $result = array();
	// 		// foreach ($_POST['pilihan_jawaban'] as $key => $val) {
	// 		// 	$result[] = array(
	// 		// 		'id_profil_responden' => $id_profil_responden,
	// 		// 		'nama_kategori_profil_responden' => $pilihan_jawaban[$key]
	// 		// 	);
	// 		// }

	// 		// if ($input['opsi_lainnya'] == 1) {
	// 		// 	$result[] = array(
	// 		// 		'id_profil_responden' => $id_profil_responden,
	// 		// 		'nama_kategori_profil_responden' => 'Lainnya'
	// 		// 	);
	// 		// }
	// 		// $this->db->insert_batch('kategori_profil_responden_' . $table_identity, $result);
	// 	}
	// }



	public function add($id1, $id2)
	{
		$this->data['title'] 		= 'Buat Range Nilai Interval';
		$this->data['profiles'] = $this->_get_data_profile($id1, $id2);

		$this->db->select('*');
		$this->db->from('manage_survey');
		$this->db->where('manage_survey.slug', $this->uri->segment(2));
		$manage_survey = $this->db->get()->row();

		$this->form_validation->set_rules('id', 'ID', 'trim|required');

		if ($this->form_validation->run() == FALSE) {


			return view('definisi_skala_survei/form_add', $this->data);
		} else {

			$this->db->query("TRUNCATE definisi_skala_$manage_survey->table_identity");


			$input 	= $this->input->post(NULL, TRUE);
			$result = array();
			foreach ($_POST['range_atas'] as $key => $val) {
				$result[] = array(
					'range_atas' 	=> $input['range_atas'][$key],
					'range_bawah' 	=> $input['range_bawah'][$key],
					'mutu' 	=> $input['mutu'][$key],
					'kategori' 	=> $input['kategori'][$key],
					'skala_likert' => $manage_survey->skala_likert == 5 ? 5 : 4
				);
			}
			$this->db->insert_batch('definisi_skala_' . $manage_survey->table_identity, $result);

			//DELETE PILIHAN JAWABAN YANG KOSONG
			$this->db->query("DELETE FROM definisi_skala_$manage_survey->table_identity WHERE range_atas = '' && range_bawah = '' && mutu = '' && kategori = ''");



			$this->session->set_flashdata('message_success', 'Berhasil menambah data');
			redirect(base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2) . '/definisi-skala', 'refresh');
		}
	}


	public function edit()
	{
		$this->db->select('*');
		$this->db->from('manage_survey');
		$this->db->where('manage_survey.slug', $this->uri->segment(2));
		$manage_survey = $this->db->get()->row();

		$input 	= $this->input->post(NULL, TRUE);
		$object = [
			// 'range_atas' 	=> $input['batas_atas'],
			// 'range_bawah' 	=> $input['batas_bawah'],
			'mutu' 	=> $input['mutu'],
			'kategori' 	=> $input['kategori']
		];

		$this->db->where('id', $input['id']);
		$this->db->update('definisi_skala_' . $manage_survey->table_identity, $object);

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

/* End of file PertanyaanKualitatifController.php */
/* Location: ./application/controllers/PertanyaanKualitatifController.php */