<?php
defined('BASEPATH') or exit('No direct script access allowed');

class UnsurSkmController extends CI_Controller
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
		$this->data['title'] = 'Unsur SKM';

		$this->load->library('table');

		$template = array(
			'table_open'            => '<table class="table table-bordered table-hover">',
			'table_close'           => '</table>'
		);

		$this->table->set_template($template);

		$this->table->set_heading('NO', 'Unsur SKM', 'Nilai Unsur SKM');

		$get_data = $this->db->get('unsur_skm');

		$no = 1;
		foreach ($get_data->result() as $value) {
			$this->table->add_row(
				$no++,
				$value->nama_unsur_skm,
				$value->nilai
			);
		}

		$this->data['table'] = $this->table->generate();

		return view('unsur_skm/index', $this->data);
	}
}

/* End of file UnsurSkmController.php */
/* Location: ./application/controllers/UnsurSkmController.php */