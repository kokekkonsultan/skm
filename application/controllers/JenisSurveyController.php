<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class JenisSurveyController extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->library('ion_auth');
        $this->load->model('JenisSurvey_model');
		
		if (!$this->ion_auth->logged_in())
        {
        	$this->session->set_flashdata('message_warning', 'You must be logged in to access this page');
            redirect('auth','refresh');
        }
        $this->load->library('form_validation');
	}

	public function index()
	{
        // $this->data['users'] = $this->ion_auth->users()->result();

        $this->data['jenis_survey'] = $this->JenisSurvey_model->getAllJenisSurvey();
		
		$this->data['title'] = 'Jenis Survey';

		return view('jenis_survey/index', $this->data);
	}

	public function create()
	{
        $this->data = [];
        $this->data['title'] = 'Create Survey Type';
        $this->data['subtitle'] = 'Please enter the survey types information below.';
		return view('jenis_survey/create', $this->data);
	}

	public function insert()
    {
        $this->JenisSurvey_model->insert();
    }

	public function edit($id)
    {
        $this->data = [];
        $this->data['title'] = 'Edit Survey Type';
        $this->data['subtitle'] = 'Please enter the survey types information below.';
        $this->data['form_action'] = base_url().'jenissurvey/edit/'.$id;
        $this->data['jenis_survey']= $this->JenisSurvey_model->getJenisSurveyById($id);
        

        $this->form_validation->set_rules('nama_jenis_survey', 'nama jenis survey', 'required');
        $this->form_validation->set_rules('slug', 'Slug', 'required');


        // $this->data['nama_jenis_survey'] = [
        //     'name'  => 'nama_jenis_survey',
        //     'id'    => 'nama_jenis_survey',
        //     'type'  => 'text',
        //     'value' => $this->form_validation->set_value('nama_jenis_survey', $data->nama_jenis_survey),
        //     'class' => 'form-control',
        // ];
        
        if ($this->form_validation->run() == false) {

            return view('jenis_survey/edit', $this->data);
            
        } else {
            
            $input = $this->input->post(null, true);
            $query = $this->JenisSurvey_model->update($id, $input);
            
            if ($query == true) {

                redirect(base_url().'JenisSurvey','refresh');

            } else {
                return view('jenis_survey/edit', $this->data);
            }
        }

    }


	public function delete($id)
    {
        $this->JenisSurvey_model->delete($id, 'jenis_survey');
        redirect('jenissurvey', 'refresh');
    }


}

/* End of file DashboardController.php */
/* Location: ./application/controllers/DashboardController.php */