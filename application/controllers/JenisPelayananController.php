<?php
defined('BASEPATH') or exit('No direct script access allowed');

class JenisPelayananController extends CI_Controller
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
		$this->data['title'] = "Jenis Pelayanan";

		return view('jenis_pelayanan/index', $this->data);
	}

	public function ajax_list()
	{
		$this->load->model('KlasifikasiJenisPelayananSurvei_model');

		$list = $this->KlasifikasiJenisPelayananSurvei_model->get_datatables();
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			$jumlah = $this->db->get_where('jenis_pelayanan', ['id_klasifikasi_survei' => $value->id])->num_rows();

			$no++;
			$row = array();
			$row[] = '<a href="' . base_url() . 'jenis-pelayanan/list/' . $value->id . '"><div class="card" style="background-color: Linen;"><div class="card-body font-weight-bold shadow text-dark">' . $value->nama_klasifikasi_survei . ' (' . $jumlah . ')</div></div></a>';

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->KlasifikasiJenisPelayananSurvei_model->count_all(),
			"recordsFiltered" => $this->KlasifikasiJenisPelayananSurvei_model->count_filtered(),
			"data" => $data,
		);

		echo json_encode($output);
	}

	public function list_jenis_pelayanan($id)
	{
		$url = $this->uri->uri_string();
		$this->session->set_userdata('urlback', $url);

		$this->data = [];
		$this->data['title'] = "Jenis Pelayanan";

		return view('jenis_pelayanan/list_jenis_pelayanan', $this->data);
	}

	public function ajax_list_jenis_pelayanan()
	{
		$this->load->model('JenisPelayanan_model');

		$list = $this->JenisPelayanan_model->get_datatables();
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $value->nama_klasifikasi_survei;
			$row[] = '<b>' . $value->nama_jenis_pelayanan_responden . '</b>';
			$row[] = anchor('jenis-pelayanan/edit/' . $value->id_jenis_pelayanan . '/' . $value->id_klasifikasi_survei, 'Edit', ['class' => 'btn btn-light-primary btn-sm font-weight-bold shadow']);
			$row[] = '<a class="btn btn-light-primary btn-sm font-weight-bold shadow" href="javascript:void(0)" title="Hapus ' . $value->nama_jenis_pelayanan_responden . '" onclick="delete_data(' . "'" . $value->id_jenis_pelayanan . "'" . ')">Delete</a>';

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->JenisPelayanan_model->count_all(),
			"recordsFiltered" => $this->JenisPelayanan_model->count_filtered(),
			"data" => $data,
		);

		echo json_encode($output);
	}

	public function add($id = NULL)
	{
		$this->load->model('JenisPelayanan_model');

		$this->data = array();
		$this->data['title'] 		= 'Tambah Jenis Pelayanan';
		$this->data['form_action'] 	= 'jenis-pelayanan/add/' . $id;

		// $this->form_validation->set_rules('id_klasifikasi_survei', 'Id Klasifikasi Survei', 'trim|required');
		$this->form_validation->set_rules('nama_jenis_pelayanan_responden', 'Nama Jenis Pelayanan', 'trim|required');

		if ($this->form_validation->run() == FALSE) {

			// $this->data['id_klasifikasi_survei'] = [
			// 	'name' 		=> 'id_klasifikasi_survei',
			// 	'id' 		=> 'id_klasifikasi_survei',
			// 	'options' 	=> $this->JenisPelayanan_model->dropdown_klasifikasi_survei(),
			// 	'selected' 	=> $this->form_validation->set_value('id_klasifikasi_survei'),
			// 	'class' 	=> "form-control",
			// ];

			$this->data['nama_jenis_pelayanan_responden'] = [
				'name' 		=> 'nama_jenis_pelayanan_responden',
				'id'		=> 'nama_jenis_pelayanan_responden',
				'type'		=> 'text',
				'value'		=>	$this->form_validation->set_value('nama_jenis_pelayanan_responden'),
				'class'		=> 'form-control',
			];

			return view('jenis_pelayanan/form_add', $this->data);
		} else {

			$input 	= $this->input->post(NULL, TRUE);

			$object = [
				'nama_jenis_pelayanan_responden' => $input['nama_jenis_pelayanan_responden'],
				'id_klasifikasi_survei' => $id,
				// 'id_klasifikasi_survei' 	=> $input['id_klasifikasi_survei']
			];

			$this->db->insert('jenis_pelayanan', $object);

			if ($this->db->affected_rows() > 0) {

				$this->session->set_flashdata('message_success', 'Berhasil menambah data');
				redirect($this->session->userdata('urlback'), 'refresh');
			} else {

				$this->data['message_data_danger'] = "Gagal menambah data";
				return view('jenis_pelayanan/form_add', $this->data);
			}
		}
	}

	public function edit($id1 = NULL, $id2 = NULL)
	{
		$this->data = array();
		$this->data['title'] = 'Edit Jenis Pelayanan';
		$this->data['form_action'] = "jenis-pelayanan/edit/$id1/$id2";

		$search_data = $this->db->get_where('jenis_pelayanan', ['id' => $id1]);

		if ($search_data->num_rows() == 0) {

			$this->session->set_flashdata('message_danger', 'Data tidak ditemukan');
			redirect($this->session->userdata('urlback'), 'refresh');
		}

		$current = $search_data->row();

		$this->data['nama_jenis_pelayanan_responden'] = [
			'name' 		=> 'nama_jenis_pelayanan_responden',
			'id'		=> 'nama_jenis_pelayanan_responden',
			'type'		=> 'text',
			'value'		=>	$this->form_validation->set_value('nama_jenis_pelayanan_responden', $current->nama_jenis_pelayanan_responden),
			'class'		=> 'form-control',
		];

		// $this->load->model('JenisPelayanan_model');

		// $this->data['id_klasifikasi_survei'] = [
		// 	'name' 		=> 'id_klasifikasi_survei',
		// 	'id' 		=> 'id_klasifikasi_survei',
		// 	'options' 	=> $this->JenisPelayanan_model->dropdown_klasifikasi_survei(),
		// 	'selected' 	=> $this->form_validation->set_value('id_klasifikasi_survei', $current->id_klasifikasi_survei),
		// 	'class' 	=> "form-control",
		// ];

		// $this->form_validation->set_rules('id_klasifikasi_survei', 'Id Klasifikasi Survei', 'trim|required');
		$this->form_validation->set_rules('nama_jenis_pelayanan_responden', 'Nama Jenis Pelayanan Responden', 'trim|required');

		if ($this->form_validation->run() == FALSE) {

			return view('jenis_pelayanan/form_edit', $this->data);
		} else {

			$input 	= $this->input->post(NULL, TRUE);

			$this->load->helper('slug');

			$object = [
				// 'id_klasifikasi_survei' 	=> $input['id_klasifikasi_survei'],
				'id_klasifikasi_survei' => $id2,
				'nama_jenis_pelayanan_responden' => $input['nama_jenis_pelayanan_responden']
			];

			$this->db->where('id', $id1);
			$this->db->update('jenis_pelayanan', $object);

			if ($this->db->affected_rows() > 0) {

				$this->session->set_flashdata('message_success', 'Berhasil mengubah klasifikasi survei');
				redirect($this->session->userdata('urlback'), 'refresh');
			} else {

				$this->data['message_data_danger'] = "Gagal mengubah klasifikasi survei";
				return view('jenis_pelayanan/form_edit', $this->data);
			}
		}
	}

	public function delete($id = NULL)
	{
		$search_data = $this->db->get_where('jenis_pelayanan', ['id' => $id]);

		if ($search_data->num_rows() == 0) {

			echo json_encode(array("status" => FALSE));
		}

		$current = $search_data->row();

		$this->db->where('id', $current->id);
		$this->db->delete('jenis_pelayanan');

		echo json_encode(array("status" => TRUE));
	}
}

/* End of file JenisPelayananController.php */
/* Location: ./application/controllers/JenisPelayananController.php */