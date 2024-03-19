<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DashboardController extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->library('ion_auth');

		if (!$this->ion_auth->logged_in()) {
			$this->session->set_flashdata('message_warning', 'You must be logged in to access this page');
			redirect('auth', 'refresh');
		}
		$this->load->model('ManageSurvey_model', 'models');
	}

	public function index()
	{
		$this->data = [];
		$this->data['title'] = 'Dashboard';


		return view('dashboard/index', $this->data);
	}

	public function jumlah_survei()
	{
		$user_id = $this->session->userdata('user_id');

		$this->db->select('COUNT(id) AS jumlah_survei');
		$query = $this->db->get_where('manage_survey', ['id_user' => $user_id])->row();

		$this->data = [];
		$this->data['jumlah_survei'] = $query->jumlah_survei;


		return view('dashboard/jumlah_survei', $this->data);
		// echo json_encode($data);
	}

	public function prosedur_aplikasi()
	{
		$this->data = [];
		$this->data['title'] = 'Prosedur Penggunaan Aplikasi';


		return view('dashboard/prosedur_aplikasi', $this->data);
	}


	public function get_chart_survei()
	{
		$this->data = [];
		$this->data['title'] = 'Dashboard Chart';

		$manage_survey = $this->db->get_where("manage_survey", array('id_user' => $this->session->userdata('user_id')));

		$users_groups = $this->db->get_where("users_groups", array('user_id' => $this->session->userdata('user_id')))->row();

		if ($users_groups->group_id == 2) {
			$this->db->select('*, manage_survey.slug AS slug_manage_survey');
			$this->db->from('manage_survey');
			$this->db->where('id_user', $this->session->userdata('user_id'));
		} else {
			$data_user = $this->db->get_where("users", array('id' => $this->session->userdata('user_id')))->row();

			$this->db->select('*, manage_survey.slug AS slug_manage_survey');
			$this->db->from('manage_survey');
			$this->db->join("supervisor_drs$data_user->is_parent", "manage_survey.id_berlangganan = supervisor_drs$data_user->is_parent.id_berlangganan");
			$this->db->where("supervisor_drs$data_user->is_parent.id_user", $this->session->userdata('user_id'));
		}
		$this->db->order_by('manage_survey.id', 'DESC');
		$this->db->limit(10);
		$manage_survey = $this->db->get();

		if ($manage_survey->num_rows() > 0) {

			$nama_survei = [];
			$skor_akhir = [];
			$no = 1;
			foreach ($manage_survey->result() as $value) {

				$skala_likert = (100 / ($value->skala_likert == 5 ? 5 : 4));
				$this->data['tahun_awal'] = $value->survey_year;


				if ($this->db->get_where("survey_$value->table_identity", array('is_submit' => 1))->num_rows() > 0) {

					$nilai_per_unsur[$no] = $this->db->query("SELECT IF(id_parent = 0,unsur_pelayanan_$value->table_identity.id, unsur_pelayanan_$value->table_identity.id_parent) AS id_sub,
					((SUM(skor_jawaban)/COUNT(DISTINCT survey_$value->table_identity.id_responden))/(COUNT(id_parent)/COUNT(DISTINCT survey_$value->table_identity.id_responden))) AS nilai_per_unsur, (((SUM(skor_jawaban)/COUNT(DISTINCT survey_$value->table_identity.id_responden))/(COUNT(id_parent)/COUNT(DISTINCT survey_$value->table_identity.id_responden)))/(SELECT COUNT(id) FROM unsur_pelayanan_$value->table_identity WHERE id_parent = 0)) AS rata_rata_bobot

					FROM jawaban_pertanyaan_unsur_$value->table_identity
					JOIN pertanyaan_unsur_pelayanan_$value->table_identity ON jawaban_pertanyaan_unsur_$value->table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$value->table_identity.id
					JOIN unsur_pelayanan_$value->table_identity ON pertanyaan_unsur_pelayanan_$value->table_identity.id_unsur_pelayanan = unsur_pelayanan_$value->table_identity.id
					JOIN survey_$value->table_identity ON jawaban_pertanyaan_unsur_$value->table_identity.id_responden = survey_$value->table_identity.id_responden
					WHERE survey_$value->table_identity.is_submit = 1 AND jawaban_pertanyaan_unsur_$value->table_identity.skor_jawaban != '0.0'
					GROUP BY id_sub");

					$nilai_bobot[$no] = [];
					foreach ($nilai_per_unsur[$no]->result() as $get) {
						$nilai_bobot[$no][] = $get->rata_rata_bobot;
						$nilai_tertimbang[$no] = array_sum($nilai_bobot[$no]);
					}
					$nama_survei_lengkap[] = "'"  . $value->survey_name . "'";
					$nama_survei[] = str_word_count($value->survey_name) > 2 ? "'" .
						substr($value->survey_name, 0, 7) . " [...]'" : "'" . $value->survey_name . "'";

					$skor_akhir[] = ROUND($nilai_tertimbang[$no] * $skala_likert, 2);

					$new_chart[] = '{ label: "' . $value->survey_name . '", value: "' . ROUND($nilai_tertimbang[$no] * $skala_likert, 2) . '" }';
				} else {
					$nama_survei_lengkap[] = "'"  . $value->survey_name . "'";
					$nama_survei[] = str_word_count($value->survey_name) > 2 ? "'" .
						substr($value->survey_name, 0, 5) . " [...]'" : "'" . $value->survey_name . "'";
					$skor_akhir[] = 0;

					$new_chart[] = '{ label: "' . $value->survey_name . '", value: "0" }';
				};
				$no++;
			}
			$this->data['nama_survei_lengkap'] = implode(", ", $nama_survei_lengkap);
			$this->data['nama_survei'] = implode(", ", $nama_survei);
			$this->data['skor_akhir'] = implode(", ", $skor_akhir);

			$this->data['new_chart'] = implode(", ", $new_chart);
		} else {
			$this->data['nama_survei_lengkap'] = '';
			$this->data['nama_survei'] = '';
			$this->data['skor_akhir'] = 0;
		}
		return view("dashboard/chart_survei", $this->data);
	}

	public function get_tabel_survei()
	{
		$this->data = [];
		$this->data['title'] = 'Dashboard Tabel';

		return view("dashboard/tabel_survei", $this->data);
	}

	public function ajax_list_tabel_survei()
	{
		$this->db->select('*');
		$this->db->from('users');
		$this->db->join('users_groups', 'users.id = users_groups.user_id');
		$this->db->where('users.username', $this->session->userdata('username'));
		$data_user = $this->db->get()->row();

		$list = $this->models->get_datatables($data_user);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $value) {

			$no++;
			$row = array();
			$skala_likert = (100 / ($value->skala_likert == 5 ? 5 : 4));

			if ($this->db->get_where("survey_$value->table_identity", array('is_submit' => 1))->num_rows() > 0) {

				$nilai_per_unsur[$no] = $this->db->query("SELECT IF(id_parent = 0,unsur_pelayanan_$value->table_identity.id, unsur_pelayanan_$value->table_identity.id_parent) AS id_sub,
				((SUM(skor_jawaban)/COUNT(DISTINCT survey_$value->table_identity.id_responden))/(COUNT(id_parent)/COUNT(DISTINCT survey_$value->table_identity.id_responden))) AS nilai_per_unsur, (((SUM(skor_jawaban)/COUNT(DISTINCT survey_$value->table_identity.id_responden))/(COUNT(id_parent)/COUNT(DISTINCT survey_$value->table_identity.id_responden)))/(SELECT COUNT(id) FROM unsur_pelayanan_$value->table_identity WHERE id_parent = 0)) AS rata_rata_bobot
				
				FROM jawaban_pertanyaan_unsur_$value->table_identity
				JOIN pertanyaan_unsur_pelayanan_$value->table_identity ON jawaban_pertanyaan_unsur_$value->table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$value->table_identity.id
				JOIN unsur_pelayanan_$value->table_identity ON pertanyaan_unsur_pelayanan_$value->table_identity.id_unsur_pelayanan = unsur_pelayanan_$value->table_identity.id
				JOIN survey_$value->table_identity ON jawaban_pertanyaan_unsur_$value->table_identity.id_responden = survey_$value->table_identity.id_responden
				WHERE survey_$value->table_identity.is_submit = 1 AND jawaban_pertanyaan_unsur_$value->table_identity.skor_jawaban != '0.0'
				GROUP BY id_sub");

				$nilai_bobot[$no] = [];
				foreach ($nilai_per_unsur[$no]->result() as $get) {
					$nilai_bobot[$no][] = $get->rata_rata_bobot;
					$nilai_tertimbang[$no] = array_sum($nilai_bobot[$no]);
				}
				$skor_akhir[$no] = ROUND($nilai_tertimbang[$no] * $skala_likert, 2);
			} else {
				$skor_akhir[$no] = 0;
			};


			foreach ($this->db->query("SELECT * FROM definisi_skala_$value->table_identity ORDER BY id DESC")->result() as $obj) {
				if ($skor_akhir[$no] <= $obj->range_bawah && $skor_akhir[$no] >= $obj->range_atas) {
					$kualitas_pelayanan[$no] = $obj->kategori;
					$mutu_pelayanan[$no] = $obj->mutu;
				}
			}
			if ($skor_akhir[$no] <= 0) {
				$kualitas_pelayanan[$no] = '-'; //NULL
				$mutu_pelayanan[$no] = '-'; //NULL
			}

			// if ($skor_akhir[$no] <= 100 && $skor_akhir[$no] >= 88.31) {
			// 	$kualitas_pelayanan[$no] = 'Sangat Baik';
			// 	$mutu_pelayanan[$no] = 'A';
			// } elseif ($skor_akhir[$no] <= 88.40 && $skor_akhir[$no] >= 76.61) {
			// 	$kualitas_pelayanan[$no] = 'Baik';
			// 	$mutu_pelayanan[$no] = 'B';
			// } elseif ($skor_akhir[$no] <= 76.60 && $skor_akhir[$no] >= 65) {
			// 	$kualitas_pelayanan[$no] = 'Kurang Baik';
			// 	$mutu_pelayanan[$no] = 'C';
			// } elseif ($skor_akhir[$no] <= 64.99 && $skor_akhir[$no] >= 25) {
			// 	$kualitas_pelayanan[$no] = 'Tidak Baik';
			// 	$mutu_pelayanan[$no] = 'D';
			// } else {
			// 	$kualitas_pelayanan[$no] = 'NULL';
			// 	$mutu_pelayanan[$no] = 'NULL';
			// };



			$row[] = $no;
			$row[] = $value->survey_name;
			$row[] = $skor_akhir[$no];
			$row[] = $mutu_pelayanan[$no];
			$row[] = $kualitas_pelayanan[$no];
			$row[] = '<a class="btn btn-light-primary btn-sm" data-toggle="modal"
			onclick="showedit(' . $value->id . ')" href="#modal_detail"><i class="fa fa-info-circle"></i> Detail</a>';

			$data[] = $row;
		}

		$output = array(
			"draw" 				=> $_POST['draw'],
			"recordsTotal" 		=> $this->models->count_all($data_user),
			"recordsFiltered" 	=> $this->models->count_filtered($data_user),
			"data" 				=> $data,
		);

		echo json_encode($output);
	}

	public function get_detail_hasil_analisa()
	{

		$this->data = [];
		$id_manage_survey = $this->uri->segment(4);
		$this->data['id_manage_survey'] = $id_manage_survey;

		$this->data['manage_survey'] = $this->db->get_where('manage_survey', array('id' => $id_manage_survey))->row();


		return view('dashboard/detail_hasil_analisa', $this->data);
	}
}

/* End of file DashboardController.php */
/* Location: ./application/controllers/DashboardController.php */