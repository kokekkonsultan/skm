<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DataPerolehanSurveyorController extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->library('ion_auth');

		if (!$this->ion_auth->logged_in()) {
			$this->session->set_flashdata('message_warning', 'You must be logged in to access this page');
			redirect('auth', 'refresh');
		}
		$this->load->model('DataPerolehanSurveyor_model', 'models');
	}

	public function index()
	{
		$this->data = [];
		$this->data['title'] = 'Data Perolehan Surveyor';

		$user = $this->ion_auth->user()->row()->id;

		$manage_survey = $this->db->query("SELECT *, surveyor.uuid AS uuid_surveyor
        FROM surveyor
        JOIN manage_survey ON surveyor.id_manage_survey = manage_survey.id
        WHERE surveyor.id_user = $user")->row();
		$table_identity = $manage_survey->table_identity;

		//PANGGIL PROFIL RESPONDEN
		$this->data['profil'] = $this->db->query("SELECT * FROM profil_responden_$table_identity")->result();

		//PANGGIL UNSUR
		$this->data['unsur']  = $this->db->query("SELECT *, SUBSTR(nomor_unsur, 2) AS kode_alasan FROM unsur_pelayanan_$table_identity JOIN pertanyaan_unsur_pelayanan_$table_identity ON unsur_pelayanan_$table_identity.id = pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan");

		return view('data_perolehan_surveyor/index', $this->data);
	}

	public function ajax_list()
	{
		$user = $this->ion_auth->user()->row()->id;

		$manage_survey = $this->db->query("SELECT *, surveyor.uuid AS uuid_surveyor
        FROM surveyor
        JOIN manage_survey ON surveyor.id_manage_survey = manage_survey.id
        WHERE surveyor.id_user = $user")->row();
		$table_identity = $manage_survey->table_identity;

		//PANGGIL PROFIL RESPONDEN
		$profil_responden = $this->db->query("SELECT * FROM profil_responden_$table_identity")->result();

		$list = $this->models->get_datatables($profil_responden, $table_identity, $user);
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			$jawaban_unsur = $this->db->get("jawaban_pertanyaan_unsur_$table_identity");

			if ($value->is_submit == 1) {
				$status = '<span class="badge badge-primary">Valid</span>';
			} else {
				$status = '<span class="badge badge-danger">Tidak Valid</span>';
			}

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $status;
			$row[] = anchor($manage_survey->slug . '/hasil-survei/' . $value->uuid_responden, '<i class="fas fa-file-pdf text-danger"></i>', ['target' => '_blank']);
			$row[] = '<b>' . $value->kode_surveyor . '</b>--' . $value->first_name . ' ' . $value->last_name;
			// $row[] = $value->nama_lengkap;

			foreach ($profil_responden as $get) {
				$profil = $get->nama_alias;
				$row[] =  str_word_count($value->$profil) > 5 ? substr($value->$profil, 0, 50) . ' [...]' : $value->$profil;
			}

			foreach ($jawaban_unsur->result() as $get_unsur) {
				if ($get_unsur->id_responden == $value->id_responden) {
					$row[] = $get_unsur->skor_jawaban;
				}
			}

			foreach ($jawaban_unsur->result() as $get_unsur) {
				if ($get_unsur->id_responden == $value->id_responden) {
					$row[] = $get_unsur->alasan_pilih_jawaban;
				}
			}

			$row[] = $value->saran;
			$row[] = date("d-m-Y", strtotime($value->waktu_isi));

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->models->count_all($profil_responden, $table_identity, $user),
			"recordsFiltered" => $this->models->count_filtered($profil_responden, $table_identity, $user),
			"data" => $data,
		);

		echo json_encode($output);
	}
}

/* End of file PerolehanSurveyorController.php */
/* Location: ./application/controllers/PerolehanSurveyorController.php */