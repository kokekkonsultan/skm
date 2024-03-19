<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class OrganisasiController extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		if (!$this->ion_auth->logged_in())
        {
        	$this->session->set_flashdata('message_warning', 'You must be an admin to view this page');
            redirect('auth','refresh');
        }
		
		$this->load->model('Organisasi_model', 'models');
	}

	public function index()
	{
		$this->data = [];
		$this->data['title'] = "Organisasi";

		return view('organisasi/index', $this->data);
	}

	public function ajax_list()
	{
		$list = $this->models->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $value) {

			$no++;
			$row = array();

			$row[] = $no;
			$row[] = $value->nama_organisasi;
			$row[] = anchor('url', 'linkname', []);
			$row[] = '';
		
			$data[] = $row;
		}

		$output = array(
					"draw" 				=> $_POST['draw'],
					"recordsTotal" 		=> $this->models->count_all(),
					"recordsFiltered" 	=> $this->models->count_filtered(),
					"data" 				=> $data,
				);

		echo json_encode($output);
	}

}

/* End of file OrganisasiController.php */
/* Location: ./application/controllers/OrganisasiController.php */