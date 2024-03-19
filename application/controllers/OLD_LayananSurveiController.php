<?php
defined('BASEPATH') or exit('No direct script access allowed');

class LayananSurveiController extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		if (!$this->ion_auth->logged_in()) {
			$this->session->set_flashdata('message_warning', 'You must be an admin to view this page');
			redirect('auth', 'refresh');
		}

		$this->load->library('form_validation');
		$this->load->model('DefinisiSkalaSurvei_model', 'Models');
	}

	public function index($id1, $id2)
	{
		$this->data = [];
		$this->data['title'] = 'Jenis Pelayanan Survei';
		$this->data['profiles'] = $this->_get_data_profile($id1, $id2);

		$slug = $this->uri->segment(2);
		$manage_survey = $this->db->get_where('manage_survey', array('slug' => "$slug"))->row();
		$table_identity = $manage_survey->table_identity;

		$this->data['layanan'] = $this->db->get("layanan_survei_$table_identity");

		return view('layanan_survei/index', $this->data);
	}


	public function add()
	{
		$this->db->select('*');
		$this->db->from('manage_survey');
		$this->db->where('manage_survey.slug', $this->uri->segment(2));
		$manage_survey = $this->db->get()->row();

		$input 	= $this->input->post(NULL, TRUE);
		$object = [
			'nama_layanan' 	=> $input['nama_layanan'],
			'is_active' 	=> $input['is_active']
		];
		$this->db->insert('layanan_survei_' . $manage_survey->table_identity, $object);

		$pesan = 'Data berhasil disimpan';
		$msg = ['sukses' => $pesan];
		echo json_encode($msg);
	}


	public function edit()
	{
		$this->db->select('*');
		$this->db->from('manage_survey');
		$this->db->where('manage_survey.slug', $this->uri->segment(2));
		$manage_survey = $this->db->get()->row();

		$input 	= $this->input->post(NULL, TRUE);
		$object = [
			'nama_layanan' 	=> $input['nama_layanan'],
			'is_active' 	=> $input['is_active']
		];

		$this->db->where('id', $input['id']);
		$this->db->update('layanan_survei_' . $manage_survey->table_identity, $object);

		$pesan = 'Data berhasil disimpan';
		$msg = ['sukses' => $pesan];
		echo json_encode($msg);
	}


	public function delete()
	{
		$this->db->select('*');
		$this->db->from('manage_survey');
		$this->db->where('manage_survey.slug', $this->uri->segment(2));
		$manage_survey = $this->db->get()->row();

		$this->db->where('id', $this->uri->segment(5));
		$this->db->delete('layanan_survei_' . $manage_survey->table_identity);

		echo json_encode(array("status" => TRUE));
	}



	

	public function grafik($id1, $id2)
	{
		$this->data = [];
		$this->data['title'] = 'Grafik Jenis Pelayanan Survei';
		$this->data['profiles'] = $this->_get_data_profile($id1, $id2);


		$this->db->select('*');
		$this->db->from('manage_survey');
		$this->db->where('manage_survey.slug', $this->uri->segment(2));
		$manage_survey = $this->db->get()->row();
		$this->data['table_identity'] = $manage_survey->table_identity;


		if ($this->db->get_where('survey_' . $manage_survey->table_identity, array('is_submit' => 1))->num_rows() == 0) {
			$this->data['pesan'] = 'Survei belum dimulai atau belum ada responden !';
			return view('not_questions/index', $this->data);
		}

		return view('layanan_survei/grafik', $this->data);

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
