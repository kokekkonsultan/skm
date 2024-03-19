<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PertanyaanKualitatifController extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		if (!$this->ion_auth->logged_in()) {
			$this->session->set_flashdata('message_warning', 'You must be an admin to view this page');
			redirect('auth', 'refresh');
		}

		$this->load->library('form_validation');
		$this->load->model('PertanyaanKualitatif_model');
	}

	public function index($id1, $id2)
	{

		$url = $this->uri->uri_string();
		$this->session->set_userdata('urlback', $url);

		$this->data = [];
		$this->data['title'] = 'Pertanyaan Kualitatif';
		$this->data['profiles'] = $this->_get_data_profile($id1, $id2);

		$this->db->select('manage_survey.id AS id_manage_survey, manage_survey.table_identity AS table_identity, is_question');
		$this->db->from('manage_survey');
		$this->db->where('manage_survey.slug', $this->uri->segment(2));
		$manage_survey = $this->db->get()->row();
		// var_dump($id_manage_survey);

		$this->data['is_question'] = $manage_survey->is_question;


		return view('pertanyaan_kualitatif/index', $this->data);
	}

	public function ajax_list()
	{
		$slug = $this->uri->segment(2);

		$get_identity = $this->db->get_where('manage_survey', array('slug' => "$slug"))->row();
		$table_identity = $get_identity->table_identity;

		$list = $this->PertanyaanKualitatif_model->get_datatables($table_identity);
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			if ($value->is_active == 1) {
				$status = '<span class="badge badge-primary">Aktif</span>';
			} else {
				$status = '<span class="badge badge-danger">Tidak Aktif</span>';
			}

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $value->isi_pertanyaan;
			$row[] = $status;
			$row[] = anchor($this->session->userdata('username') . '/' . $this->uri->segment(2) . '/pertanyaan-kualitatif/edit/' . $value->id, '<i class="fa fa-edit"></i> Edit', ['class' => 'btn btn-light-primary btn-sm font-weight-bold shadow']);

			if ($get_identity->is_question == 1) {
				$row[] = '<a class="btn btn-light-primary btn-sm font-weight-bold shadow" href="javascript:void(0)" title="Hapus ' . $value->isi_pertanyaan . '" onclick="delete_pertanyaan_kualitatif(' . "'" . $value->id . "'" . ')"><i class="fa fa-trash"></i> Delete</a>';
			}


			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->PertanyaanKualitatif_model->count_all($table_identity),
			"recordsFiltered" => $this->PertanyaanKualitatif_model->count_filtered($table_identity),
			"data" => $data,
		);

		echo json_encode($output);
	}

	public function add_pertanyaan_kualitatif($id1, $id2)
	{
		$this->data = [];
		$this->data['title'] = "Tambah Pertanyaan Kualitatif";

		$this->data['profiles'] = $this->_get_data_profile($id1, $id2);

		$this->db->select('manage_survey.id AS id_manage_survey, manage_survey.table_identity AS table_identity');
		$this->db->from('manage_survey');
		$this->db->where('manage_survey.slug', $this->uri->segment(2));
		$manage_survey = $this->db->get()->row();

		$this->form_validation->set_rules('isi_pertanyaan', 'Isi Pertanyaan', 'trim|required');

		if ($this->form_validation->run() == FALSE) {

			return view('pertanyaan_kualitatif/add', $this->data);
		} else {

			$input 	= $this->input->post(NULL, TRUE);

			$object = [
				'isi_pertanyaan' 	=> $input['isi_pertanyaan'],
				'is_active' 	=> $input['is_active']
			];
			// var_dump($object);

			$this->db->insert('pertanyaan_kualitatif_' . $manage_survey->table_identity, $object);

			if ($this->db->affected_rows() > 0) {

				$this->session->set_flashdata('message_success', 'Berhasil menambah data');
				redirect(base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2) . '/pertanyaan-kualitatif', 'refresh');
			} else {

				$this->data['message_data_danger'] = "Gagal menambah data";
				return view('pertanyaan_kualitatif/add', $this->data);
			}
		}
	}

	public function edit_pertanyaan_kualitatif($id1, $id2)
	{
		$this->data = [];
		$this->data['title'] = "Edit Pertanyaan Kualitatif";

		$this->data['profiles'] = $this->_get_data_profile($id1, $id2);


		$this->db->select('manage_survey.id AS id_manage_survey, manage_survey.table_identity AS table_identity');
		$this->db->from('manage_survey');
		$this->db->where('manage_survey.slug', $this->uri->segment(2));
		$manage_survey = $this->db->get()->row();

		$this->data['kualitatif'] = $this->db->get_where('pertanyaan_kualitatif_' . $manage_survey->table_identity, ['id' => $this->uri->segment(5)])->row();

		$this->form_validation->set_rules('isi_pertanyaan', 'Isi Pertanyaan', 'trim|required');

		if ($this->form_validation->run() == FALSE) {

			return view('pertanyaan_kualitatif/edit', $this->data);
		} else {

			$input 	= $this->input->post(NULL, TRUE);

			$object = [
				'isi_pertanyaan' 	=> $input['isi_pertanyaan'],
				'is_active' 	=> $input['is_active']
			];

			$this->db->where('id', $this->uri->segment(5));
			$this->db->update('pertanyaan_kualitatif_' . $manage_survey->table_identity, $object);

			if ($this->db->affected_rows() > 0) {

				$this->session->set_flashdata('message_success', 'Berhasil mengubah data');
				redirect(base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2) . '/pertanyaan-kualitatif', 'refresh');
			}
		}
	}

	public function delete_pertanyaan_kualitatif()
	{
		// $this->data['profiles'] = $this->_get_data_profile($id1, $id2);
		$this->db->select('manage_survey.id AS id_manage_survey, manage_survey.table_identity AS table_identity');
		$this->db->from('manage_survey');
		$this->db->where('manage_survey.slug', $this->uri->segment(2));
		$manage_survey = $this->db->get()->row();

		$this->db->delete('pertanyaan_kualitatif_' . $manage_survey->table_identity, array('id' => $this->uri->segment(5)));

		echo json_encode(array("status" => TRUE));
	}

	public function _get_data_profile($id1, $id2)
	{
		$this->db->select('*');
		$this->db->from('users');
		$this->db->join('users_groups', 'users.id = users_groups.user_id');
		$this->db->where('users.username', $this->session->userdata('username'));
		$data_user = $this->db->get()->row();
		$user_identity = 'drs' . $data_user->is_parent;

		$this->db->select('users.username, manage_survey.survey_name, is_question, manage_survey.slug, manage_survey.description, manage_survey.is_privacy, manage_survey.table_identity, manage_survey.id_jenis_pelayanan, manage_survey.atribut_pertanyaan_survey');
		if ($data_user->group_id == 2) {
			$this->db->from('users');
			$this->db->join('manage_survey', 'manage_survey.id_user = users.id');
		} else {
			$this->db->from('manage_survey');
			$this->db->join("supervisor_$user_identity", "manage_survey.id_berlangganan = supervisor_$user_identity.id_berlangganan");
			$this->db->join("users", "supervisor_$user_identity.id_user = users.id");
		}
		$this->db->where('users.username', $id1);
		$this->db->where('manage_survey.slug', $id2);
		$profiles = $this->db->get();

		if ($profiles->num_rows() == 0) {
			// echo 'Survey tidak ditemukan atau sudah dihapus !';
			// exit();
			show_404();
		}
		return $profiles->row();
	}
}

/* End of file PertanyaanKualitatifController.php */
/* Location: ./application/controllers/PertanyaanKualitatifController.php */