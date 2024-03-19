<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ProfilRespondenKuesionerController extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->library('ion_auth');

		if (!$this->ion_auth->logged_in()) {
			$this->session->set_flashdata('message_warning', 'You must be logged in to access this page');
			redirect('auth', 'refresh');
		}
		$this->load->model('ProfilRespondenKuesioner_model');
		$this->load->library('form_validation');
	}

	public function index()
	{
		$this->data = [];
		$this->data['title'] = "Profil Responden Kuesioner";

		return view('profil_responden_kuesioner/index', $this->data);
	}

	public function ajax_list()
	{
		$list = $this->ProfilRespondenKuesioner_model->get_datatables();
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = '<b>' . $value->nama_klasifikasi_survei . '</b>';
			$row[] = anchor('profil-responden-kuesioner/detail/' . $value->id, 'Detail Profil Responden &nbsp <i class="fa fa-arrow-right"></i>', ['class' => 'btn btn-light-primary btn-sm font-weight-bold shadow']);


			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->ProfilRespondenKuesioner_model->count_all(),
			"recordsFiltered" => $this->ProfilRespondenKuesioner_model->count_filtered(),
			"data" => $data,
		);

		echo json_encode($output);
	}

	public function detail($id = NULL)
	{
		$this->data = array();
		$this->data['title'] = 'Detail Profil Responden Kuesioner';
		$this->data['form_action'] 	= 'profil-responden-kuesioner/detail/' . $id;

		$this->data['profil_responden'] = $this->db->query('SELECT *, profil_responden_kuesioner.is_active AS status, profil_responden_kuesioner.id AS id_profil
		FROM profil_responden_kuesioner
		JOIN mst_profil_responden_kuesioner ON profil_responden_kuesioner.id_mst_profil_responden = mst_profil_responden_kuesioner.id
		WHERE id_klasifikasi_survey =' . $this->uri->segment(3) .
			' ORDER BY  id_profil DESC');

		$search_data = $this->db->get_where('klasifikasi_survei', ['id' => $id]);
		$this->data['klasifikasi_survey'] = $search_data->result();

		if ($search_data->num_rows() == 0) {

			$this->session->set_flashdata('message_danger', 'Data tidak ditemukan');
			redirect($this->session->userdata('urlback'), 'refresh');
		}
		$current = $search_data->row();
		$this->data['nama_klasifikasi_survey'] = $current->nama_klasifikasi_survei;

		$this->data['mst_profil'] = $this->db->query("SELECT * FROM mst_profil_responden_kuesioner WHERE NOT EXISTS (SELECT * FROM profil_responden_kuesioner WHERE mst_profil_responden_kuesioner.id = profil_responden_kuesioner.id_mst_profil_responden && id_klasifikasi_survey = $id)");

		$this->data['id_klasifikasi_survei'] = [
			'name' 		=> 'id_klasifikasi_survei',
			'id' 		=> 'id_klasifikasi_survei',
			'value' 	=> $this->form_validation->set_value('id_klasifikasi_survei', $current->nama_klasifikasi_survei),
			'class' 	=> "form-control",
			'disabled' => 	'disabled'
		];


		$this->form_validation->set_rules('id_klasifikasi_survei', 'Id Klasifikasi Survei', 'trim|required');
		if ($this->form_validation->run() == FALSE) {
			return view('profil_responden_kuesioner/form_detail', $this->data);
		} else {
			$check = $this->input->post('check_list[]');
			$id_klasifikasi_survey = $this->input->post('id_klasifikasi_survei');

			foreach ($check as $object) {
				$this->db->insert('profil_responden_kuesioner', (array(
					'id_mst_profil_responden' => $object, 'id_klasifikasi_survey' => $id_klasifikasi_survey, 'is_active' => '1'
				)));
			}

			redirect(base_url() . 'profil-responden-kuesioner/detail/' . $id, 'refresh');
		}
	}

	public function delete($id = NULL)
	{
		$search_data = $this->db->get_where('profil_responden_kuesioner', ['id' => $id]);

		$current = $search_data->row();

		$this->db->where('id', $current->id);
		$this->db->delete('profil_responden_kuesioner');

		redirect(base_url() . 'profil-responden-kuesioner/detail/' . $current->id_klasifikasi_survey);
	}
}

/* End of file UnsurPelayananController.php */
/* Location: ./application/controllers/UnsurPelayananController.php */