<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AllDataSurveiController extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		if (!$this->ion_auth->logged_in()) {
			$this->session->set_flashdata('message_warning', 'You must be an admin to view this page');
			redirect('auth', 'refresh');
		}

		$this->load->model('AllDataSurvei_model', 'models');
	}

	public function index()
	{
		$this->data = [];
		$this->data['title'] = 'Semua Data Survei';


		return view('all_data_survei/index', $this->data);
	}


	public function ajax_list()
	{
		$list = $this->models->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $value) {

			if ($value->is_privacy == 1) {
				$color = 'success';
				$status = '<span class="badge badge-success" width="40%">Public</span>';
			} else {
				$color = 'danger';
				$status = '<span class="badge badge-danger" width="40%">Private</span>';
			};

			$no++;
			$row = array();
			// $row[] = $no;
			$row[] = '<a href="' . base_url() . 'data-survey-klien/do/' . $value->table_identity . '" title="" class="text-' . $color . '">
			    <div class="card card-body mb-5 shadow  wave wave-animate-slow wave-' . $color . '">
			        <div class="d-flex align-items-center">
			            <span class="bullet bullet-bar bg-' . $color . ' align-self-stretch"></span>
			            <label
			                class="checkbox checkbox-lg checkbox-light-' . $color . ' checkbox-inline flex-shrink-0 m-0 mx-4">
			                <input type="checkbox" value="1" disabled>
			                <span></span>
			            </label>
			            <div class="d-flex flex-column flex-grow-1">
			                <div class="row">
			                    <div class="col sm-10">
			                        <strong style="font-size: 17px;">' . $value->survey_name . '</strong><br>
			                        <span class="text-dark">Organisasi yang disurvei : <b>' . $value->organisasi . '</b></span><br />
			                    </div>
			                    <div class="col sm-2 text-right">' . $status . '
			                        <div class="mt-3 text-dark font-weight-bold" style="font-size: 11px;">
									Periode Survei : ' . date('d-m-Y', strtotime($value->survey_start)) . ' s/d ' . date('d-m-Y', strtotime($value->survey_end)) . '
			                        </div>

			                    </div>
			                </div>
			            </div>
			        </div>
			    </div>
			</a>';
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

/* End of file PertanyaanKualitatifController.php */
/* Location: ./application/controllers/PertanyaanKualitatifController.php */