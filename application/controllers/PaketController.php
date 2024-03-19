<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PaketController extends CI_Controller
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
		$this->data['title'] = 'Paket';

		$this->data['nama_paket'] = [
			'name' 		=> 'nama_paket',
			'id'		=> 'nama_paket',
			'type'		=> 'text',
			'value'		=>	$this->form_validation->set_value('nama_paket'),
			'class'		=> 'form-control',
			'required'	=> 'required',
			'placeholder' => 'Nama Paket',
		];

		$this->data['deskripsi_paket'] = [
			'name'	=> 'deskripsi_paket',
			'id'	=> 'deskripsi_paket',
			'type'	=> 'text',
			'value'	=> $this->form_validation->set_value('deskripsi_paket'),
			'class'	=> 'ckeditor',
			'required'	=> 'required',
		];

		$this->data['deskripsi_paket_trial'] = [
			'name'	=> 'deskripsi_paket_trial',
			'id'	=> 'deskripsi_paket_trial',
			'type'	=> 'text',
			'value'	=> $this->form_validation->set_value('deskripsi_paket_trial'),
			'class'	=> 'ckeditor',
			'required'	=> 'required',
		];

		$this->data['deskripsi_paket_edit'] = [
			'name'	=> 'deskripsi_paket_edit',
			'id'	=> 'deskripsi_paket_edit',
			'type'	=> 'text',
			'value'	=> $this->form_validation->set_value('deskripsi_paket_edit'),
			'class'	=> 'ckeditor',
			'required'	=> 'required',
		];

		$this->data['jumlah_user'] = [
			'name' 		=> 'jumlah_user',
			'id'		=> 'jumlah_user',
			'type'		=> 'number',
			'value'		=>	$this->form_validation->set_value('jumlah_user'),
			'class'		=> 'form-control',
			'required'	=> 'required',
			'placeholder' => 'Jumlah User',
		];

		$this->data['jumlah_kuesioner'] = [
			'name' 		=> 'jumlah_kuesioner',
			'id'		=> 'jumlah_kuesioner',
			'type'		=> 'number',
			'value'		=>	$this->form_validation->set_value('jumlah_kuesioner'),
			'class'		=> 'form-control',
			'required'	=> 'required',
			'placeholder' => 'Jumlah Kuesioner',
		];

		$this->data['panjang_hari'] = [
			'name' 		=> 'panjang_hari',
			'id'		=> 'panjang_hari',
			'type'		=> 'number',
			'value'		=>	$this->form_validation->set_value('panjang_hari'),
			'class'		=> 'form-control',
			'required'	=> 'required',
			'placeholder' => 'Panjang Hari',
		];

		$this->data['harga_paket'] = [
			'name' 		=> 'harga_paket',
			'id'		=> 'harga_paket',
			'type'		=> 'number',
			'value'		=>	$this->form_validation->set_value('harga_paket'),
			'class'		=> 'form-control',
			'required'	=> 'required',
			'placeholder' => 'Harga Paket',
		];

		return view('paket/index', $this->data);
	}

	public function ajax_list()
	{
		$this->load->model('Paket_model');

		$list = $this->Paket_model->get_datatables();
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			$checked = ($value->is_active == 1) ? "checked" : "";

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $value->nama_paket;
			$row[] = $value->deskripsi_paket;
			$row[] = ($value->harga_paket != '') ? number_format($value->harga_paket,2,',','.') : '0';
			$row[] =
				'
				<span class="switch switch-sm">
					<label>
						<input value="' . $value->id . '" type="checkbox" name="setting_value" class="toggle_dash" ' . $checked . ' />
						<span></span>
					</label>
				</span>
				';
			$row[] = '<a class="text-primary" href="javascript:void(0)" title="Edit" onclick="edit_data(' . "'" . $value->id . "'" . ')">Edit</a>';
			$row[] = '<a class="text-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data(' . "'" . $value->id . "'" . ')">Delete</a>';
			$row[] = '<a class="text-primary" href="javascript:void(0)" title="Detail" onclick="showDetail(' . "'" . $value->id . "'" . ')">Detail</a>';

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->Paket_model->count_all(),
			"recordsFiltered" => $this->Paket_model->count_filtered(),
			"data" => $data,
		);

		echo json_encode($output);
	}

	public function update_status_aktif()
	{
		$mode = $_POST['mode'];
		$id = $_POST['nilai_id'];

		if ($mode == 'true') //Jika mode bernilai true berarti tombol status enable
		{
			$object = [
				'is_active' => '1'
			];
			$this->db->where('id', $id);
			$this->db->update('paket', $object);

			$message = 'Paket berhasil diaktifkan';
			$success = 'Enabled';
			echo json_encode(array('message' => $message, '$success' => $success));
		} else if ($mode == 'false') //Jika mode bernilai false berarti tombol status disable
		{
			$object = [
				'is_active' => '0'
			];
			$this->db->where('id', $id);
			$this->db->update('paket', $object);

			$message = 'Paket berhasil dinonaktifkan';
			$success = 'Disabled';
			echo json_encode(array('message' => $message, 'success' => $success));
		}
	}

	public function update_status_aktif_value()
	{
		$mode = $_POST['mode'];
		$id = $_POST['nilai_id'];

		if ($mode == 'true') //Jika mode bernilai true berarti tombol status enable
		{
			$object = [
				'is_active' => '1'
			];
			$this->db->where('id', $id);
			$this->db->update('paket', $object);

			$message = 'Paket berhasil diaktifkan';
			$success = 'Enabled';
			echo json_encode(array('message' => $message, '$success' => $success));
		} else if ($mode == 'false') //Jika mode bernilai false berarti tombol status disable
		{
			$object = [
				'is_active' => '0'
			];
			$this->db->where('id', $id);
			$this->db->update('paket', $object);

			$message = 'Paket berhasil dinonaktifkan';
			$success = 'Disabled';
			echo json_encode(array('message' => $message, 'success' => $success));
		}
	}

	public function trial_ajax_list()
	{
		$this->load->model('TrialPaket_model');

		$list = $this->TrialPaket_model->get_datatables();
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			$checked = ($value->is_active == 1) ? "checked" : "";

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $value->nama_paket;
			$row[] = $value->deskripsi_paket;
			$row[] =
				'
				<span class="switch switch-sm">
					<label>
						<input value="' . $value->id . '" type="checkbox" name="setting_value" class="toggle_dash" ' . $checked . ' />
						<span></span>
					</label>
				</span>
				';
			$row[] = '<a class="text-primary" href="javascript:void(0)" title="Edit" onclick="edit_data(' . "'" . $value->id . "'" . ')">Edit</a>';
			$row[] = '<a class="text-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data(' . "'" . $value->id . "'" . ')">Delete</a>';
			$row[] = '<a class="text-primary" href="javascript:void(0)" title="Detail" onclick="showDetail(' . "'" . $value->id . "'" . ')">Detail</a>';

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->TrialPaket_model->count_all(),
			"recordsFiltered" => $this->TrialPaket_model->count_filtered(),
			"data" => $data,
		);

		echo json_encode($output);
	}

	public function ajax_add()
	{
		$this->_validate_add();

		$data = [
			'nama_paket' 	=> $this->input->post('nama_paket'),
			'deskripsi_paket' 	=> $this->input->post('deskripsi_paket', FALSE),
			'panjang_hari' 	=> $this->input->post('panjang_hari'),
			'harga_paket' 	=> $this->input->post('harga_paket'),
			'jumlah_user' 	=> $this->input->post('jumlah_user'),
			'jumlah_kuesioner' 	=> $this->input->post('jumlah_kuesioner'),
			'is_active' => '1',
			'is_trial' => '0'
		];

		$this->db->insert('paket', $data);

		echo json_encode(array("status" => TRUE));
	}

	public function ajax_add_trial()
	{
		$this->_validate_add();

		$data = [
			'nama_paket' 	=> $this->input->post('nama_paket'),
			'deskripsi_paket' 	=> $this->input->post('instansiasi_add_deskripsi', FALSE),
			'panjang_hari' 	=> $this->input->post('panjang_hari'),
			'harga_paket' 	=> $this->input->post('harga_paket'),
			'jumlah_user' 	=> $this->input->post('jumlah_user'),
			'jumlah_kuesioner' 	=> $this->input->post('jumlah_kuesioner'),
			'is_active' => '1',
			'is_trial' => '1'
		];

		$this->db->insert('paket', $data);

		echo json_encode(array("status" => TRUE));
	}

	private function _validate_add()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		$this->form_validation->set_rules('nama_paket', 'Nama Paket', 'trim|required');
		$this->form_validation->set_rules('jumlah_user', 'Jumlah User', 'trim|required');
		$this->form_validation->set_rules('jumlah_kuesioner', 'Jumlah Kuesioner', 'trim|required');
		$this->form_validation->set_rules('panjang_hari', 'Panjang Hari', 'trim|required');
		$this->form_validation->set_rules('harga_paket', 'Harga Paket', 'trim|required');

		$this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');


		if ($this->form_validation->run() == FALSE) {
			$data['inputerror'][] = 'nama_paket';
			$data['error_string'][] = form_error('nama_paket');
			$data['status'] = FALSE;

			$data['inputerror'][] = 'jumlah_user';
			$data['error_string'][] = form_error('jumlah_user');
			$data['status'] = FALSE;

			$data['inputerror'][] = 'jumlah_kuesioner';
			$data['error_string'][] = form_error('jumlah_kuesioner');
			$data['status'] = FALSE;

			$data['inputerror'][] = 'panjang_hari';
			$data['error_string'][] = form_error('panjang_hari');
			$data['status'] = FALSE;

			$data['inputerror'][] = 'harga_paket';
			$data['error_string'][] = form_error('harga_paket');
			$data['status'] = FALSE;
		}


		if ($data['status'] === FALSE) {
			echo json_encode($data);
			exit();
		}
	}

	public function ajax_delete($id)
	{
		$cek_paket = $this->db->get_where('berlangganan', ['id_paket' => $id]);

		if ($cek_paket->num_rows() == 0) {

			$this->db->where('id', $id);
			$this->db->delete('paket');

			echo json_encode(array("status" => TRUE));
		}
	}

	public function ajax_edit($id)
	{

		$data = $this->db->get_where('paket', ['id' => $id])->row();

		echo json_encode($data);
	}

	public function ajax_update()
	{
		$this->_validate_edit();

		$data = array(
			'nama_paket' 	=> $this->input->post('nama_paket'),
			'deskripsi_paket' 	=> $this->input->post('instansiasi_deskripsi', FALSE),
			'panjang_hari' 	=> $this->input->post('panjang_hari'),
			'harga_paket' 	=> $this->input->post('harga_paket'),
			'jumlah_user' 	=> $this->input->post('jumlah_user'),
			'jumlah_kuesioner' 	=> $this->input->post('jumlah_kuesioner'),
		);

		$this->db->where('id', $this->input->post('id'));
		$this->db->update('paket', $data);

		echo json_encode(array("status" => TRUE));
	}

	private function _validate_edit()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		$this->form_validation->set_rules('nama_paket', 'Nama Paket', 'trim|required');
		$this->form_validation->set_rules('jumlah_user', 'Jumlah User', 'trim|required');
		$this->form_validation->set_rules('jumlah_kuesioner', 'Jumlah Kuesioner', 'trim|required');
		$this->form_validation->set_rules('panjang_hari', 'Panjang Hari', 'trim|required');
		$this->form_validation->set_rules('harga_paket', 'Harga Paket', 'trim|required');

		$this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');


		if ($this->form_validation->run() == FALSE) {
			$data['inputerror'][] = 'nama_paket';
			$data['error_string'][] = form_error('nama_paket');
			$data['status'] = FALSE;

			$data['inputerror'][] = 'jumlah_user';
			$data['error_string'][] = form_error('jumlah_user');
			$data['status'] = FALSE;

			$data['inputerror'][] = 'jumlah_kuesioner';
			$data['error_string'][] = form_error('jumlah_kuesioner');
			$data['status'] = FALSE;

			$data['inputerror'][] = 'panjang_hari';
			$data['error_string'][] = form_error('panjang_hari');
			$data['status'] = FALSE;

			$data['inputerror'][] = 'harga_paket';
			$data['error_string'][] = form_error('harga_paket');
			$data['status'] = FALSE;
		}


		if ($data['status'] === FALSE) {
			echo json_encode($data);
			exit();
		}
	}

	public function get_detail()
	{
		$id = $this->input->post('id');
		$this->data = [];
		$this->data['title'] = 'Detail Paket';
		$this->data['id'] = $id;
		$this->data['post'] = $this->db->get_where('paket', ['id' => $id])->row();

		return view('paket/form_detail', $this->data);
	}




	public function add()
	{
		$this->data = array();
		$this->data['title'] 		= 'Tambah Paket Baru';
		$this->data['form_action'] 	= 'paket/add';

		$this->form_validation->set_rules('nama_paket', 'Nama Paket', 'trim|required');
		$this->form_validation->set_rules('deskripsi_paket', 'Deskripsi Paket', 'trim|required');
		$this->form_validation->set_rules('panjang_hari', 'Panjang Hari', 'trim|required');
		$this->form_validation->set_rules('harga_paket', 'Harga Paket', 'trim|required');

		if ($this->form_validation->run() == FALSE) {

			$this->data['nama_paket'] = [
				'name' 		=> 'nama_paket',
				'id'		=> 'nama_paket',
				'type'		=> 'text',
				'value'		=>	$this->form_validation->set_value('nama_paket'),
				'class'		=> 'form-control',
				'required'	=> 'required'
			];

			$this->data['deskripsi_paket'] = [
				'name' 		=> 'deskripsi_paket',
				'id'		=> 'deskripsi_paket',
				'type'		=> 'text',
				'value'		=>	$this->form_validation->set_value('deskripsi_paket'),
				'class'		=> 'form-control',
				'required'	=> 'required'
			];

			$this->data['panjang_hari'] = [
				'name' 		=> 'panjang_hari',
				'id'		=> 'panjang_hari',
				'type'		=> 'number',
				'value'		=>	$this->form_validation->set_value('panjang_hari'),
				'class'		=> 'form-control',
				'required'	=> 'required',
			];

			$this->data['harga_paket'] = [
				'name' 		=> 'harga_paket',
				'id'		=> 'harga_paket',
				'type'		=> 'number',
				'value'		=>	$this->form_validation->set_value('harga_paket'),
				'class'		=> 'form-control',
				'required'	=> 'required'
			];

			return view('paket/form_add', $this->data);
		} else {

			$input 	= $this->input->post(NULL, TRUE);

			$object = [
				'nama_paket' 	=> $input['nama_paket'],
				'deskripsi_paket' => $input['deskripsi_paket'],
				'panjang_hari' => $input['panjang_hari'],
				'harga_paket' => $input['harga_paket'],
			];

			$this->db->insert('paket', $object);



			if ($this->db->affected_rows() > 0) {

				$this->session->set_flashdata('message_success', 'Berhasil menambah data');
				redirect(base_url() . 'paket', 'refresh');
			} else {

				$this->data['message_data_danger'] = "Gagal menambah data";
				return view('paket/form_add', $this->data);
			}
		}
	}

	public function edit($id = NULL)
	{
		$this->data = array();
		$this->data['title'] = 'Edit Paket';
		$this->data['form_action'] = 'paket/edit/' . $id;

		$search_data = $this->db->get_where('paket', ['id' => $id]);

		if ($search_data->num_rows() == 0) {

			$this->session->set_flashdata('message_danger', 'Data tidak ditemukan');
			redirect($this->session->userdata('urlback'), 'refresh');
		}

		$current = $search_data->row();

		$this->data['nama_paket'] = [
			'name' 		=> 'nama_paket',
			'id'		=> 'nama_paket',
			'type'		=> 'text',
			'value'		=>	$this->form_validation->set_value('nama_paket', $current->nama_paket),
			'class'		=> 'form-control',
		];

		$this->data['deskripsi_paket'] = [
			'name' 		=> 'deskripsi_paket',
			'id'		=> 'deskripsi_paket',
			'type'		=> 'text',
			'value'		=>	$this->form_validation->set_value('deskripsi_paket', $current->deskripsi_paket),
			'class'		=> 'form-control',
			'required'	=> 'required'
		];

		$this->data['panjang_hari'] = [
			'name' 		=> 'panjang_hari',
			'id'		=> 'panjang_hari',
			'type'		=> 'number',
			'value'		=>	$this->form_validation->set_value('panjang_hari', $current->panjang_hari),
			'class'		=> 'form-control',
			'required'	=> 'required',
		];

		$this->data['harga_paket'] = [
			'name' 		=> 'harga_paket',
			'id'		=> 'harga_paket',
			'type'		=> 'number',
			'value'		=>	$this->form_validation->set_value('harga_paket', $current->harga_paket),
			'class'		=> 'form-control',
			'required'	=> 'required'
		];

		$this->form_validation->set_rules('nama_paket', 'Nama Paket', 'trim|required');
		$this->form_validation->set_rules('deskripsi_paket', 'Deskripsi Paket', 'trim|required');
		$this->form_validation->set_rules('panjang_hari', 'Panjang Hari', 'trim|required');
		$this->form_validation->set_rules('harga_paket', 'Harga Paket', 'trim|required');

		if ($this->form_validation->run() == FALSE) {

			return view('paket/form_edit', $this->data);
		} else {

			$input 	= $this->input->post(NULL, TRUE);

			$object = [
				'nama_paket' 	=> $input['nama_paket'],
				'deskripsi_paket' => $input['deskripsi_paket'],
				'panjang_hari' => $input['panjang_hari'],
				'harga_paket' => $input['harga_paket'],
			];

			$this->db->where('id', $id);
			$this->db->update('paket', $object);

			if ($this->db->affected_rows() > 0) {

				$this->session->set_flashdata('message_success', 'Berhasil mengubah data');
				redirect(base_url() . 'paket', 'refresh');
			} else {

				$this->data['message_data_danger'] = "Gagal mengubah data";
				return view('paket/form_edit', $this->data);
			}
		}
	}

	public function delete($id = NULL)
	{
		$cek_paket = $this->db->get_where('berlangganan', ['id_paket' => $id]);

		if ($cek_paket->num_rows() == 0) {

			$this->db->where('id', $id);
			$this->db->delete('paket');

			echo json_encode(array("status" => TRUE));
		}
	}
}

/* End of file PaketController.php */
/* Location: ./application/controllers/PaketController.php */