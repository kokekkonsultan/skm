<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PilihanJawabanPertanyaanController extends CI_Controller
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
		$this->data['title'] = "Pilihan Jawaban Pertanyaan";


		return view('pilihan_jawaban_pertanyaan/index', $this->data);
	}

	public function ajax_list()
	{
		$this->load->model('PilihanJawabanPertanyaan_model', 'models');

		$list = $this->models->get_datatables();
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $value->pilihan_1;
			$row[] = $value->pilihan_2;
			$row[] = $value->pilihan_3;
			$row[] = $value->pilihan_4;

			$row[] = '<button type="button" class="btn btn-light-primary btn-sm font-weight-bold shadow" data-toggle="modal"
			data-target="#edit_' . $value->id . '"><i class="fa fa-edit"></i> Edit</button>';

			$row[] = '<a class="btn btn-light-primary btn-sm font-weight-bold shadow" href="javascript:void(0)" title="Hapus ' . $value->id . '" onclick="delete_data(' . "'" . $value->id . "'" . ')"><i class="fa fa-trash"></i> Delete</a>';

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->models->count_all(),
			"recordsFiltered" => $this->models->count_filtered(),
			"data" => $data,
		);

		echo json_encode($output);
	}

	public function add()
	{

		$input 	= $this->input->post(NULL, TRUE);

		$object = [
			'pilihan_1' 	=> $input['pilihan_1'],
			'pilihan_2' 	=> $input['pilihan_2'],
			'pilihan_3' 	=> $input['pilihan_3'],
			'pilihan_4' 	=> $input['pilihan_4']
		];
		$this->db->insert('pilihan_jawaban_pertanyaan_harapan', $object);


		$pesan = 'Data berhasil disimpan';
		$msg = ['sukses' => $pesan];
		echo json_encode($msg);
	}

	public function edit()
	{
		$input 	= $this->input->post(NULL, TRUE);
		$object = [
			'pilihan_1' 	=> $input['pilihan_1'],
			'pilihan_2' 	=> $input['pilihan_2'],
			'pilihan_3' 	=> $input['pilihan_3'],
			'pilihan_4' 	=> $input['pilihan_4']
		];
		$this->db->where('id', $input['id']);
		$this->db->update('pilihan_jawaban_pertanyaan_harapan', $object);


		$pesan = 'Data berhasil disimpan';
		$msg = ['sukses' => $pesan];
		echo json_encode($msg);
	}

	public function delete($id)
	{
		$this->db->where('id', $id);
		$this->db->delete('pilihan_jawaban_pertanyaan_harapan');

		echo json_encode(array("status" => TRUE));
	}
}

/* End of file KlasifikasiSurveyController.php */
/* Location: ./application/controllers/KlasifikasiSurveyController.php */