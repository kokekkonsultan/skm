<?php
defined('BASEPATH') or exit('No direct script access allowed');

class LinkSurveySurveyorIndukController extends CI_Controller
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
		$this->data['title'] = 'Link Survey';

		$user = $this->ion_auth->user()->row()->id;
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('users.id =' . $user);
		$surveyor = $this->db->get()->row();
		//$this->data['data_user'] = $surveyor;

		$this->db->select('users.*');
		$this->db->from('users');
		$this->db->join('manage_survey', 'users.id = manage_survey.id_user');
		$this->db->join('surveyor_induk', 'manage_survey.id = surveyor_induk.id_manage_survey');
		$this->db->where('users.id_parent_induk =' . $surveyor->is_parent);
		$this->db->where('surveyor_induk.id_user =' . $user);
		$this->db->group_by('users.username'); 
		$users =  $this->db->get();
		$this->data['user_anak'] = $users;
		

		return view('link_survey_surveyor_induk/index', $this->data);
	}

	public function proses()
	{
		$akun_anak = $_GET['akun_anak'];
		$user = $this->ion_auth->user()->row()->id;

		if ($akun_anak) {

			$this->db->select('*');
			$this->db->from('manage_survey');
			$this->db->join('surveyor_induk', 'manage_survey.id = surveyor_induk.id_manage_survey');
			$this->db->where('manage_survey.id_user', $akun_anak);
			$this->db->where('surveyor_induk.id_user', $user);
			$this->db->group_by('manage_survey.uuid'); 
			$this->data['manage_survey'] = $this->db->get();

			return view('link_survey_surveyor_induk/detail_link', $this->data);
		} else {
			echo '<div class="text-center mt-5">Silahkan pilih instansi terlebih dahulu</div>';
		}
	}

}

/* End of file PerolehanSurveyorController.php */
/* Location: ./application/controllers/PerolehanSurveyorController.php */