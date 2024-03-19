<?php
defined('BASEPATH') or exit('No direct script access allowed');

class KlasifikasiSurveyController extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->library('ion_auth');

		if (!$this->ion_auth->logged_in()) {
			$this->session->set_flashdata('message_warning', 'You must be logged in to access this page');
			redirect('auth', 'refresh');
		}

		$this->load->library('form_validation');
	}

	public function index()
	{
		$this->data = [];
		$this->data['title'] = "Klasifikasi Survey";

		return view('klasifikasi_survei/index', $this->data);
	}

	public function ajax_list()
	{
		$this->load->model('KlasifikasiSurvey_model');

		$list = $this->KlasifikasiSurvey_model->get_datatables();
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			$jumlah = $this->db->get_where('jenis_pelayanan', ['id_klasifikasi_survei' => $value->id])->num_rows();

			$no++;
			$row = array();
			// $row[] = $no;
			$row[] = '<div class="card card-body shadow" style="background-color: SeaShell;">
					<div class="row">
					<div class="col-xl-9">
					<a href="' . base_url() . 'jenis-pelayanan/list/' . $value->id . '" title="" class="text-primary">
						<div class="timeline timeline-1">
							<div class="timeline-item">
								<span class="svg-icon svg-icon-primary svg-icon-3x">
									<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
										<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
											<rect x="0" y="0" width="24" height="24"></rect>
											<path d="M2.56066017,10.6819805 L4.68198052,8.56066017 C5.26776695,7.97487373 6.21751442,7.97487373 6.80330086,8.56066017 L8.9246212,10.6819805 C9.51040764,11.267767 9.51040764,12.2175144 8.9246212,12.8033009 L6.80330086,14.9246212 C6.21751442,15.5104076 5.26776695,15.5104076 4.68198052,14.9246212 L2.56066017,12.8033009 C1.97487373,12.2175144 1.97487373,11.267767 2.56066017,10.6819805 Z M14.5606602,10.6819805 L16.6819805,8.56066017 C17.267767,7.97487373 18.2175144,7.97487373 18.8033009,8.56066017 L20.9246212,10.6819805 C21.5104076,11.267767 21.5104076,12.2175144 20.9246212,12.8033009 L18.8033009,14.9246212 C18.2175144,15.5104076 17.267767,15.5104076 16.6819805,14.9246212 L14.5606602,12.8033009 C13.9748737,12.2175144 13.9748737,11.267767 14.5606602,10.6819805 Z" fill="#000000" opacity="0.3"></path>
											<path d="M8.56066017,16.6819805 L10.6819805,14.5606602 C11.267767,13.9748737 12.2175144,13.9748737 12.8033009,14.5606602 L14.9246212,16.6819805 C15.5104076,17.267767 15.5104076,18.2175144 14.9246212,18.8033009 L12.8033009,20.9246212 C12.2175144,21.5104076 11.267767,21.5104076 10.6819805,20.9246212 L8.56066017,18.8033009 C7.97487373,18.2175144 7.97487373,17.267767 8.56066017,16.6819805 Z M8.56066017,4.68198052 L10.6819805,2.56066017 C11.267767,1.97487373 12.2175144,1.97487373 12.8033009,2.56066017 L14.9246212,4.68198052 C15.5104076,5.26776695 15.5104076,6.21751442 14.9246212,6.80330086 L12.8033009,8.9246212 C12.2175144,9.51040764 11.267767,9.51040764 10.6819805,8.9246212 L8.56066017,6.80330086 C7.97487373,6.21751442 7.97487373,5.26776695 8.56066017,4.68198052 Z" fill="#000000"></path>
										</g>
									</svg>
								</span>
								<strong style="font-size: 15px; padding-left:25px;">' . $value->nama_klasifikasi_survei . ' (' . $jumlah . ')</strong>
							</div>
						</div>
						</a>
					</div>
					<div class="col-xl-3 text-right">' . anchor('klasifikasi-survei/edit/' . $value->id, '<i class="fa fa-edit"></i> Edit', ['class' => 'btn btn-light-primary btn-sm font-weight-bold shadow']) .
				'&nbsp&nbsp<a class="btn btn-light-primary btn-sm font-weight-bold shadow" href="javascript:void(0)" title="Hapus ' . $value->nama_klasifikasi_survei . '" onclick="delete_data(' . "'" . $value->id . "'" . ')"><i class="fa fa-trash"></i> Delete</a>
					</div>
				
				</div>
			</div>';




			// $row[] = anchor('klasifikasi-survei/edit/' . $value->id, 'Edit', ['class' => 'btn btn-light-primary btn-sm font-weight-bold shadow']);
			// $row[] = '<a class="btn btn-light-primary btn-sm font-weight-bold shadow" href="javascript:void(0)" title="Hapus ' . $value->nama_klasifikasi_survei . '" onclick="delete_data(' . "'" . $value->id . "'" . ')">Delete</a>';




			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->KlasifikasiSurvey_model->count_all(),
			"recordsFiltered" => $this->KlasifikasiSurvey_model->count_filtered(),
			"data" => $data,
		);

		echo json_encode($output);
	}

	public function add()
	{
		$this->data = array();
		$this->data['title'] 		= 'Tambah Klasifikasi Survei';
		$this->data['form_action'] 	= 'klasifikasi-survei/add';

		$this->form_validation->set_rules('nama_klasifikasi_survei', 'Nama Klasifikasi Survei', 'trim|required');

		if ($this->form_validation->run() == FALSE) {

			$this->data['nama_klasifikasi_survei'] = [
				'name' 		=> 'nama_klasifikasi_survei',
				'id'		=> 'nama_klasifikasi_survei',
				'type'		=> 'text',
				'value'		=>	$this->form_validation->set_value('nama_klasifikasi_survei'),
				'class'		=> 'form-control',
			];

			return view('klasifikasi_survei/form_add', $this->data);
		} else {

			$input 	= $this->input->post(NULL, TRUE);

			$this->load->helper('slug');

			$object = [
				'nama_klasifikasi_survei' 	=> $input['nama_klasifikasi_survei'],
				'slug' => slug($input['nama_klasifikasi_survei'])
			];

			$this->db->insert('klasifikasi_survei', $object);



			if ($this->db->affected_rows() > 0) {

				$this->session->set_flashdata('message_success', 'Berhasil menambah data');
				redirect(base_url() . 'klasifikasi-survei', 'refresh');
			} else {

				$this->data['message_data_danger'] = "Gagal menambah data";
				return view('klasifikasi_survei/form_add', $this->data);
			}
		}
	}

	public function edit($id = NULL)
	{
		$this->data = array();
		$this->data['title'] = 'Edit Klasifikasi Survei';
		$this->data['form_action'] = 'klasifikasi-survei/edit/' . $id;

		$search_data = $this->db->get_where('klasifikasi_survei', ['id' => $id]);

		if ($search_data->num_rows() == 0) {

			$this->session->set_flashdata('message_danger', 'Data tidak ditemukan');
			redirect($this->session->userdata('urlback'), 'refresh');
		}

		$current = $search_data->row();

		$this->data['nama_klasifikasi_survei'] = [
			'name' 		=> 'nama_klasifikasi_survei',
			'id'		=> 'nama_klasifikasi_survei',
			'type'		=> 'text',
			'value'		=>	$this->form_validation->set_value('nama_klasifikasi_survei', $current->nama_klasifikasi_survei),
			'class'		=> 'form-control',
		];

		$this->form_validation->set_rules('nama_klasifikasi_survei', 'Nama Klasifikasi Survei', 'trim|required');

		if ($this->form_validation->run() == FALSE) {

			return view('klasifikasi_survei/form_edit', $this->data);
		} else {

			$input 	= $this->input->post(NULL, TRUE);

			$this->load->helper('slug');

			$object = [
				'nama_klasifikasi_survei' 	=> $input['nama_klasifikasi_survei'],
				'slug' => slug($input['nama_klasifikasi_survei'])
			];

			$this->db->where('id', $id);
			$this->db->update('klasifikasi_survei', $object);

			if ($this->db->affected_rows() > 0) {

				$this->session->set_flashdata('message_success', 'Berhasil mengubah klasifikasi survei');
				redirect(base_url() . 'klasifikasi-survei', 'refresh');
			} else {

				$this->data['message_data_danger'] = "Gagal mengubah klasifikasi survei";
				return view('klasifikasi_survei/form_edit', $this->data);
			}
		}
	}

	public function delete($id = NULL)
	{
		$search_data = $this->db->get_where('klasifikasi_survei', ['id' => $id]);

		if ($search_data->num_rows() == 0) {

			echo json_encode(array("status" => FALSE));
		}

		$current = $search_data->row();

		$this->db->where('id_klasifikasi_survey', $current->id);
		$this->db->delete('profil_responden_kuesioner');

		$this->db->where('id', $current->id);
		$this->db->delete('klasifikasi_survei');

		echo json_encode(array("status" => TRUE));
	}
}

/* End of file KlasifikasiSurveyController.php */
/* Location: ./application/controllers/KlasifikasiSurveyController.php */