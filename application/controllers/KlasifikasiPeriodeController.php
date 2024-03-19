<?php
defined('BASEPATH') or exit('No direct script access allowed');

class KlasifikasiPeriodeController extends Client_Controller
{

	public function __construct()
	{
		parent::__construct();

		if (!$this->ion_auth->logged_in()) {
			$this->session->set_flashdata('message_warning', 'You must be an admin to view this page');
			redirect('auth', 'refresh');
		}

		$this->load->library('form_validation');
		$this->load->model('KlasifikasiPeriode_model');
	}

	public function index()
	{
		$this->data = [];
		$this->data['title'] = 'Klasifikasi Metode Validasi';


		// $this->db->query("ALTER TABLE survey ADD id_surveyor_induk INT");
		// foreach ($this->db->get("manage_survey")->result() as $row) {
		// 	$this->db->query("ALTER TABLE survey_$row->table_identity ADD id_surveyor_induk INT");
		// }

		return view('klasifikasi_periode/index', $this->data);
	}

	public function ajax_list()
	{
		$list = $this->KlasifikasiPeriode_model->get_datatables();
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $value->label_klasifikasi;
			$row[] = '<span class="badge badge-dark">' . $value->table_identity . '</span>';

			$row[] = '<a class="btn btn-primary btn-sm font-weight-bold" data-toggle="modal" data-target="#edit_' . $value->id . '"><i class="fa fa-edit"></i> Edit</a>';

			$row[] = '<a class="btn btn-primary btn-sm font-weight-bold shadow" href="javascript:void(0)" title="Hapus ' . $value->label_klasifikasi . '" onclick="delete_data(' . "'" . $value->id . "'" . ')"><i class="fa fa-trash"></i> Delete</a>';



			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->KlasifikasiPeriode_model->count_all(),
			"recordsFiltered" => $this->KlasifikasiPeriode_model->count_filtered(),
			"data" => $data,
		);

		echo json_encode($output);
	}




	public function add()
	{
		$input 	= $this->input->post(NULL, TRUE);

		$object = [
			'label_klasifikasi' 	=> $input['label_klasifikasi'],
			'id_user' 	=> $this->session->userdata('user_id'),
			'created_at' => date("Y/m/d H:i:s")
		];
		$this->db->insert('klasifikasi_periode', $object);

		$insert_id = $this->db->insert_id();
		$table_identity = 'idk' . $insert_id;
		$this->db->query("UPDATE klasifikasi_periode SET table_identity = '$table_identity' WHERE id = $insert_id");

		$this->db->query("CREATE TABLE kelompok_anak_induk_$table_identity LIKE kelompok_anak_induk");

		$pesan = 'Data berhasil disimpan';
		$msg = ['sukses' => $pesan];
		echo json_encode($msg);
	}


	public function edit()
	{
		$input 	= $this->input->post(NULL, TRUE);
		$object = [
			'label_klasifikasi' 	=> $input['label_klasifikasi']
		];
		$this->db->where('id', $input['id']);
		$this->db->update('klasifikasi_periode', $object);

		$pesan = 'Data berhasil disimpan';
		$msg = ['sukses' => $pesan];
		echo json_encode($msg);
	}


	public function delete($id)
	{
		$current = $this->db->get_where('klasifikasi_periode', ['id' => $id])->row();

		$this->load->dbforge();
		$this->dbforge->drop_table('kelompok_anak_induk_' . $current->table_identity, true);

		$this->db->delete('klasifikasi_periode', array('id' => $id));

		echo json_encode(array("status" => TRUE));
	}
}

/* End of file KlasifikasiPeriodeController.php */
/* Location: ./application/controllers/KlasifikasiPeriodeController.php */