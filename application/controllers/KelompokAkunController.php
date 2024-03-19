<?php
defined('BASEPATH') or exit('No direct script access allowed');

class KelompokAkunController extends Client_Controller
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
		$this->data['title'] = "Kelompok Akun";


		$this->db->select('*');
		$this->db->from('klasifikasi_periode');
		$this->db->where('id_user', $this->session->userdata('user_id'));
		$klasifikasi_metode = $this->db->get();
		$this->data['klasifikasi_periode'] = $klasifikasi_metode;

		return view('kelompok_akun/index', $this->data);
	}

	public function detail()
	{
		$this->data = [];
		$this->data['title'] = 'Kelompok Akun';

		$table_identity = $this->uri->segment(1);

		$this->db->select('*');
		$this->db->from('kelompok_anak_induk_' . $table_identity);
		$kelompok_anak_induk = $this->db->get();
		$this->data['kelompok_anak_induk_'] = $kelompok_anak_induk;

		return view('kelompok_akun/detail', $this->data);
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
			$row[] = implode("", $data_anak);
			
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



	public function add()
	{
		$this->data = [];
		$this->data['title'] = "Tambah Kelompok Akun";

		$table_identity = $this->uri->segment(1);
		$this->data['table_identity'] = $table_identity;

		$this->form_validation->set_rules('nama_kelompok', 'Nama Kelompok', 'trim|required');

		if ($this->form_validation->run() == FALSE) {

			return view('kelompok_akun/add', $this->data);

		} else {

			$input 	= $this->input->post(NULL, TRUE);
			$object = [
				'nama_kelompok' 	=> $input['nama_kelompok'],
				'id_objek' 	=> serialize($input['id_survei'])
				// 'id_object_user' 	=> $id_object_user
			];
			$this->db->insert('kelompok_anak_induk_' . $table_identity, $object);


				$this->session->set_flashdata('message_success', 'Berhasil menambah data');
				redirect(base_url() . $this->uri->segment(1) . '/kelompok-akun', 'refresh');
		}
	}

	public function edit($table_identity, $id)
	{
		$this->data = [];
		$this->data['title'] = "Edit Kelompok Akun";


		$this->data['table_identity'] = $table_identity;

		$this->form_validation->set_rules('nama_kelompok', 'Nama Kelompok', 'trim|required');

		if ($this->form_validation->run() == FALSE) {

			return view('kelompok_akun/edit', $this->data);

		} else {

			$input 	= $this->input->post(NULL, TRUE);
			$object = [
				'nama_kelompok' 	=> $input['nama_kelompok'],
				'id_objek' 	=> serialize($input['id_survei'])
				// 'id_object_user' 	=> $id_object_user
			];
			// var_dump($object);
			$this->db->where('id', $id);
			$this->db->update('kelompok_anak_induk_' . $table_identity, $object);
			
			$this->session->set_flashdata('message_success', 'Berhasil mengubah data');
			redirect(base_url() . $this->uri->segment(1) . '/kelompok-akun', 'refresh');
		}
	}


	public function delete($table_identity, $id)
	{
		$this->db->delete('kelompok_anak_induk_' . $table_identity, array('id' => $id));

		echo json_encode(array("status" => TRUE));
	}

}

/* End of file KelompokAkunController.php */
/* Location: ./application/controllers/KelompokAkunController.php */