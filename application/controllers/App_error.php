<?php
defined('BASEPATH') or exit('No direct script access allowed');

class App_error extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->helper('view');
	}

	public function index()
	{
	}

	public function deny_access()
	{
		echo 'Akses Ditolak !';
	}

	public function not_found()
	{
		$this->data = [];

		$this->data['title'] = "404 Not Found";

		return view('app_error/form_not_found', $this->data);
	}


	public function service()
	{
		$this->data = [];

		$this->data['title'] = "SISTEM SEDANG DALAM PEMELIHARAAN";

		return view('app_error/form_service', $this->data);
	}
}

/* End of file App_error.php */
/* Location: ./application/controllers/App_error.php */