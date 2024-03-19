<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PublikasiController extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->load->helper('text');

		$this->data = [];
		$this->data['title'] = 'Publikasi';

		$this->data['manage_survey'] = $this->db->get_where('manage_survey', array('is_publikasi' => 1));

		return view('publikasi/index', $this->data);
	}

	public function publikasi_link_survei()
	{
		$this->load->helper('text');

		$this->data = [];
		$this->data['title'] = 'Publikasi Link Survei';

		$this->data['manage_survey'] = $this->db->get_where('manage_survey', array('is_publikasi_link_survei' => 1));

		return view('publikasi/form_publikasi_link', $this->data);
	}
}

/* End of file ArticleController.php */
/* Location: ./application/controllers/ArticleController.php */