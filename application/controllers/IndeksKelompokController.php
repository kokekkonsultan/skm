<?php
defined('BASEPATH') or exit('No direct script access allowed');

class IndeksKelompokController extends Client_Controller
{

	public function __construct()
	{
		parent::__construct();

		if (!$this->ion_auth->logged_in()) {
			$this->session->set_flashdata('message_warning', 'You must be an admin to view this page');
			redirect('auth', 'refresh');
		}

		$this->load->library('form_validation');
		$this->load->model('KelompokAkun_model');
	}

	public function index()
	{
		$this->data = [];
		$this->data['title'] = "Indeks Per Kelompok";


		$this->db->select('*');
		$this->db->from('klasifikasi_periode');
		$this->db->where('id_user', $this->session->userdata('user_id'));
		$klasifikasi_metode = $this->db->get();
		$this->data['klasifikasi_periode'] = $klasifikasi_metode;

		return view('indeks_kelompok/index', $this->data);
	}

	public function detail($table_identity)
	{
		$this->data = [];
		$this->data['title'] = 'Indeks Per Kelompok';

		$this->data['table_identity'] = $table_identity;

		return view('indeks_kelompok/detail', $this->data);
	}

	public function ajax_list()
	{
		$table_identity = $this->uri->segment(1);

		$list = $this->KelompokAkun_model->get_datatables($table_identity);
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {
			$no++;

			$id_manage_survey = implode(", ", unserialize($value->id_objek));
			$manage_survey = $this->db->query("SELECT *, (SELECT first_name FROM users WHERE id = manage_survey.id_user) AS first_name,
			(SELECT last_name FROM users WHERE id = manage_survey.id_user) AS last_name
			 FROM manage_survey WHERE id IN ($id_manage_survey)");
			
			$data_anak = [];
			foreach($this->db->query("SELECT *, (SELECT first_name FROM users WHERE id = manage_survey.id_user) AS first_name,
			(SELECT last_name FROM users WHERE id = manage_survey.id_user) AS last_name
			 FROM manage_survey WHERE id IN ($id_manage_survey)")->result() as $get){
				$data_anak[] = '<li>' . $get->survey_name . ' - <b>' . $get->first_name . ' ' . $get->last_name . '</b></li>';
			 }

			$row = array();
			$row[] = $no;
			$row[] = $value->nama_kelompok;
			$row[] = implode("", $data_anak);;
			
			$row[] = anchor($table_identity . '/kelompok-akun/edit/' . $value->id, '<i class="fa fa-edit"></i> Edit', ['class' => 'btn btn-light-primary btn-sm font-weight-bold shadow']);
			
			$row[] = '<a class="btn btn-light-primary btn-sm font-weight-bold shadow" href="javascript:void(0)" title="Hapus ' . $value->nama_kelompok . '" onclick="delete_data(' . "'" . $value->id . "'" . ')"><i class="fa fa-trash"></i> Delete</a>';
		


			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->KelompokAkun_model->count_all($table_identity),
			"recordsFiltered" => $this->KelompokAkun_model->count_filtered($table_identity),
			"data" => $data,
		);

		echo json_encode($output);
	}


}

/* End of file KelompokAkunController.php */
/* Location: ./application/controllers/KelompokAkunController.php */