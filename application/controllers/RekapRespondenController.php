<?php
defined('BASEPATH') or exit('No direct script access allowed');

class RekapRespondenController extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		if (!$this->ion_auth->logged_in()) {
			$this->session->set_flashdata('message_warning', 'You must be an admin to view this page');
			redirect('auth', 'refresh');
		}
	}

	public function index($id1 = NULL, $id2 = NULL)
	{
		$this->data = [];
		$this->data['title'] = "Profil Responden";

		$this->data['profiles'] = $this->_get_data_profile($id1, $id2);

		$manage_survey = $this->db->get_where('manage_survey', array('slug' => $this->uri->segment(2)))->row();
		$table_identity = $manage_survey->table_identity;


		$profil_responden = $this->db->query("SELECT *, (SELECT COUNT(id) FROM kategori_profil_responden_$table_identity WHERE id_profil_responden = profil_responden_$table_identity.id) AS jumlah_pilihan
		FROM profil_responden_$table_identity
		WHERE jenis_isian = 1");

		if ($profil_responden->num_rows() == 0) {
			$this->data['pesan'] = 'Profil responden survei anda tidak memiliki data yang bisa di olah.';
			return view('not_questions/index', $this->data);
		}
		$this->data['profil_responden'] = $profil_responden->result();


		if ($this->db->get_where('survey_' . $table_identity, array('is_submit' => 1))->num_rows() == 0) {
			$this->data['pesan'] = 'Survei belum dimulai atau belum ada responden !';
			return view('not_questions/index', $this->data);
		}

		return view('rekap_responden/index', $this->data);
	}


	public function convert_chart()
	{
		$slug = $this->uri->segment(2);
		$manage_survey = $this->db->get_where('manage_survey', array('slug' => $this->uri->segment(2)))->row();

		$id = $this->uri->segment(5);
		$img = $_POST['imgBase64'];
		$img = str_replace('data:image/png;base64,', '', $img);
		$img = str_replace(' ', '+', $img);
		$fileData = base64_decode($img);
		// $fileName = 'assets/klien/img_rekap_responden/chart_' . $id . '.png';

		$fileName = 'assets/klien/survei/' . $manage_survey->table_identity . '/chart_profil_responden/' . $id . '.png';
		file_put_contents($fileName, $fileData);

		// $data = [
		// 	'atribut_kuadran' => serialize(array('kuadran-' . $manage_survey->table_identity . '.png', date("d/m/Y")))
		// ];
		// $this->db->where('slug', $slug);
		// $this->db->update('manage_survey', $data);

		$msg = ['sukses' => 'Data berhasil disimpan'];
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
			// echo 'Survey tidak ditemukan atau sudah dihapus !';
			// exit();
			show_404();
		}
		return $profiles->row();
	}
}

/* End of file RekapRespondenController.php */
/* Location: ./application/controllers/RekapRespondenController.php */