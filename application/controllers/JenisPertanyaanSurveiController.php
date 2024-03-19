<?php
defined('BASEPATH') or exit('No direct script access allowed');

class JenisPertanyaanSurveiController extends CI_Controller{

	public $ion_auth;
	public $session;
	public $uri;
	public $db;
	private $data;

	public function __construct()
	{
		parent::__construct();

		if (!$this->ion_auth->logged_in()) {
			$this->session->set_flashdata('message_warning', 'You must be an admin to view this page');
			redirect('auth', 'refresh');
		}

	}

	public function index($id1, $id2)
	{
		$url = $this->uri->uri_string();
		$this->session->set_userdata('urlback', $url);

		$this->data = [];
		$this->data['title'] = 'Jenis Pertanyaan Survei';
		$this->data['profiles'] = $this->_get_data_profile($id1, $id2);

		$slug = $this->uri->segment('2');

		$this->db->select('*');
		$this->db->from('manage_survey');
		$this->db->where("slug = '$slug'");
		$this->data['manage_survey'] = $this->db->get()->row();

		$this->data['id_manage_survey'] = $this->data['manage_survey']->id;
		$this->data['atribut_pertanyaan_survey'] = unserialize($this->data['manage_survey']->atribut_pertanyaan_survey);

		return view('jenis_pertanyaan_survei/index', $this->data);
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