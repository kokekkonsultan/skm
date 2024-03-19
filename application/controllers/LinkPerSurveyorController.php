<?php
defined('BASEPATH') or exit('No direct script access allowed');

class LinkPerSurveyorController extends CI_Controller
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
	}

	public function proses()
	{
		$this->data = [];
		$this->data['title'] = 'Link Surveyor';

		$user = $this->ion_auth->user()->row()->id;
		// var_dump($user);

		$this->db->select('id_manage_survey, surveyor.uuid AS uuid');
		$this->db->from('users');
		$this->db->join('surveyor', 'users.id = surveyor.id_user');
		$this->db->where('users.id =' . $user);
		$surveyor = $this->db->get()->row();
		$this->data['data_surveyor'] = $surveyor;


		$this->db->select('*');
		$this->db->from('manage_survey');
		$this->db->where("table_identity = 'cst$surveyor->id_manage_survey'");
		$this->data['manage_survey'] = $this->db->get()->row();
		// var_dump($this->data['manage_survey']);


		return view('link_per_surveyor/index', $this->data);
	}
}

/* End of file PerolehanSurveyorController.php */
/* Location: ./application/controllers/PerolehanSurveyorController.php */