<?php
defined('BASEPATH') or exit('No direct script access allowed');

class KategoriLayananSurveiController extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		if (!$this->ion_auth->logged_in()) {
			$this->session->set_flashdata('message_warning', 'You must be an admin to view this page');
			redirect('auth', 'refresh');
		}

		$this->load->library('form_validation');
		// $this->load->model('DefinisiSkalaSurvei_model', 'Models');
	}

	public function index($id1, $id2)
	{
		$this->data = [];
		$this->data['title'] = 'Kategori Pelayanan Survei';
		$this->data['profiles'] = $this->_get_data_profile($id1, $id2);

		$slug = $this->uri->segment(2);
		$manage_survey = $this->db->get_where('manage_survey', array('slug' => "$slug"))->row();
		$table_identity = $manage_survey->table_identity;

		$this->db->from("kategori_layanan_survei_$table_identity");
		$this->db->order_by("urutan asc");
		$query = $this->db->get(); 
		$this->data['kategori_layanan'] = $query;
		// $this->data['kategori_layanan'] = $this->db->get("kategori_layanan_survei_$table_identity");

		return view('kategori_layanan_survei/index', $this->data);
	}


	public function add()
	{
		$this->db->select('*');
		$this->db->from('manage_survey');
		$this->db->where('manage_survey.slug', $this->uri->segment(2));
		$manage_survey = $this->db->get()->row();

		$urutan = $this->db->get("kategori_layanan_survei_$manage_survey->table_identity")->num_rows() + 1;

		$input 	= $this->input->post(NULL, TRUE);
		$object = [
			'nama_kategori_layanan' 	=> $input['nama_kategori_layanan'],
			'urutan' 	=> $urutan,
			// 'is_active' 	=> $input['is_active']
			'is_active' 	=> 1
		];
		$this->db->insert('kategori_layanan_survei_' . $manage_survey->table_identity, $object);

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
			'nama_kategori_layanan' 	=> $input['nama_kategori_layanan'],
			'is_active' 	=> $input['is_active']
		];

		$this->db->where('id', $input['id']);
		$this->db->update('kategori_layanan_survei_' . $manage_survey->table_identity, $object);

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
		$this->db->delete('kategori_layanan_survei_' . $manage_survey->table_identity);

		echo json_encode(array("status" => TRUE));
	}


	public function update_urutan()
    {
        $manage_survey = $this->db->get_where('manage_survey', array('slug' => $this->uri->segment(2)))->row();
        $table_identity = $manage_survey->table_identity;

        foreach ($_POST['id'] as $key => $val) {
            $id = (int)$_POST['id'][$key];
            $urutan = $_POST['urutan'][$key];
            $this->db->query("UPDATE kategori_layanan_survei_$table_identity SET urutan=$urutan WHERE id=$id");
        }

        $pesan = 'Data berhasil disimpan';
        $msg = ['sukses' => $pesan];
        echo json_encode($msg);
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
			echo 'Survey tidak ditemukan atau sudah dihapus !';
			// exit();
			//show_404();
		}
		return $profiles->row();
	}

	
}

/* End of file KategoriLayananSurveiController.php */
/* Location: ./application/controllers/KategoriLayananSurveiController.php */
