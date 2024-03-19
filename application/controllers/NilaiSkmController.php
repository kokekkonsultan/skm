<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class NilaiSkmController extends CI_Controller {

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
		$this->data['title'] = 'Nilai SKM';

		$this->load->library('table');

		$template = array(
		        'table_open'            => '<table class="table table-bordered table-hover">',
		        'table_close'           => '</table>'
		);

		$this->table->set_template($template);

		$this->table->set_heading('NO', 'NILAI PERSEPSI', 'NILAI INTERVAL (NI)', 'NILAI INTERVAL KONVERSI (NIK)', 'MUTU PELAYANAN (x)', 'KINERJA UNIT PELAYANAN (y)');

		$get_data = $this->db->get('nilai_skm');

		$no = 1;
		foreach ($get_data->result() as $value) {
			$this->table->add_row(
				$no++, 
				$value->nilai_persepsi, 
				$value->nilai_interval_min.' - '.$value->nilai_interval_max, 
				$value->nilai_interval_konversi_min.' - '.$value->nilai_interval_konversi_max, 
				$value->mutu_pelayanan, 
				$value->kinerja_unit_pelayanan
			);
			
		}

		$this->data['table'] = $this->table->generate();

		return view('nilai_skm/index', $this->data);
	}

}

/* End of file NilaiSkmController.php */
/* Location: ./application/controllers/NilaiSkmController.php */