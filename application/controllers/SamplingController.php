<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SamplingController extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
	}

	public function krejcie()
	{
		$this->data = [];
		$this->data['title'] = "Sampling Krejcie";
		$this->data['data_user'] = $this->ion_auth->user()->row();


		return view('sampling/form_krejcie', $this->data);
	}

	public function cochran()
	{
		$this->data = [];
		$this->data['title'] = "Sampling Cochran";
		$this->data['data_user'] = $this->ion_auth->user()->row();


		return view('sampling/form_cochran', $this->data);
	}

	public function slovin()
	{
		$this->data = [];
		$this->data['title'] = "Sampling Slovin";
		$this->data['data_user'] = $this->ion_auth->user()->row();


		return view('sampling/form_slovin', $this->data);
	}
}

/* End of file SamplingController.php */
/* Location: ./application/controllers/SamplingController.php */