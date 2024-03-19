<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TemplateController extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->data = [];
		$this->data['title'] = '';

		return view('template_survey/index', $this->data);
	}
}

/* End of file TemplateController.php */
/* Location: ./application/controllers/TemplateController.php */