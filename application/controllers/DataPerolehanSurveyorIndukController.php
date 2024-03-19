<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DataPerolehanSurveyorIndukController extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->library('ion_auth');

		if (!$this->ion_auth->logged_in()) {
			$this->session->set_flashdata('message_warning', 'You must be logged in to access this page');
			redirect('auth', 'refresh');
		}
	}


	public function index()
	{
		$this->data = [];
		$this->data['title'] = 'Data Perolehan Surveyor';

		$user = $this->ion_auth->user()->row();
		// $this->db->select('*');
		// $this->db->from('users');
		// $this->db->where('users.id =' . $user);
		// $surveyor = $this->db->get()->row();
		// //$this->data['data_user'] = $surveyor;

		// $this->db->select('users.*');
		// $this->db->from('users');
		// $this->db->join('manage_survey', 'users.id = manage_survey.id_user');
		// $this->db->join('surveyor_induk', 'manage_survey.id = surveyor_induk.id_manage_survey');
		// $this->db->where('users.id_parent_induk =' . $surveyor->is_parent);
		// $this->db->where('surveyor_induk.id_user =' . $user);
		// $this->db->group_by('users.username'); 
		// $users =  $this->db->get();
		// $this->data['user_anak'] = $users;

		$this->data['user_anak'] = $this->db->query("SELECT manage_survey.id_user AS id_user,
		(SELECT first_name FROM users WHERE id = manage_survey.id_user) AS first_name,
		(SELECT last_name FROM users WHERE id = manage_survey.id_user) AS last_name
		FROM surveyor_induk
		JOIN manage_survey ON manage_survey.id = surveyor_induk.id_manage_survey
		WHERE surveyor_induk.id_user = $user->id
		GROUP BY manage_survey.id_user");
		

		return view('data_perolehan_surveyor_induk/index', $this->data);
	}

	public function proses()
	{

		$user = $this->ion_auth->user()->row();
		$akun_anak = $_GET['akun_anak'];

		if ($akun_anak) {

			$this->data['manage_survey'] =  $this->db->query("SELECT *
			FROM surveyor_induk
			JOIN manage_survey ON manage_survey.id = surveyor_induk.id_manage_survey
			WHERE surveyor_induk.id_user = $user->id && manage_survey.id_user = $akun_anak");

			return view('data_perolehan_surveyor_induk/detail_akun', $this->data);
		} else {
			echo '<div class="text-center mt-5">Silahkan pilih instansi terlebih dahulu</div>';
		}
	}

	public function modal_detail($id)
	{
		$this->data = [];

		$this->data['manage_survey'] = $this->db->query("SELECT *
		FROM manage_survey WHERE id = $id")->row();

		$this->data['table_identity'] = $this->data['manage_survey']->table_identity;
		$table_identity = $this->data['table_identity'];

		//PANGGIL PROFIL RESPONDEN
		$this->data['profil_responden'] = $this->db->query("SELECT * FROM profil_responden_$table_identity ORDER BY IF(urutan != '',urutan,id) ASC")->result();


		return view('data_perolehan_surveyor_induk/modal_detail', $this->data);
	}

}

/* End of file PerolehanSurveyorController.php */
/* Location: ./application/controllers/PerolehanSurveyorController.php */