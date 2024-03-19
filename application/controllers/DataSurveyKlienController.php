<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DataSurveyKlienController extends CI_Controller
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
		$this->data['title'] = "Data Survey Klien";

		return view('data_survey_klien/index', $this->data);
	}

	public function ajax_list()
	{
		$this->load->model('AuthKlien_model');

		$list = $this->AuthKlien_model->get_datatables();
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			$no++;
			$row = array();
			$row[] = '<a href="' . base_url() . 'data-survey-klien/detail/' . $value->user_id . '" title="">
			<div class="card shadow">
				<div class="card-body">
					<div class="row">
						<div class="col-md-6">' .
				$value->first_name . ' ' . $value->last_name .
				'</div>
						<div class="col-md-6 text-right">
							<i class="fa fa-arrow-right text-primary"></i>
						</div>
					</div>
				</div>
			</div>
		</a>';

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->AuthKlien_model->count_all(),
			"recordsFiltered" => $this->AuthKlien_model->count_filtered(),
			"data" => $data,
		);

		echo json_encode($output);
	}

	public function detail_survey($id = NULL)
	{
		$this->data = [];
		$this->data['title'] = "Detail Survey Klien";

		return view('data_survey_klien/detail_survey_klien', $this->data);
	}

	public function ajax_list_detail()
	{
		$this->load->model('DetailSurveyKlien_model');

		$id_users = $this->uri->segment(3);

		$list = $this->DetailSurveyKlien_model->get_datatables($id_users);
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			if ($value->is_privacy == 1) {
				$status = '<span class="badge badge-primary" width="40%">Public</span>';
			} else {
				$status = '<span class="badge badge-warning" width="40%">Private</span>';
			};

			$no++;
			$row = array();
			$row[] = $no;

			$row[] = '<a href="' . base_url() . 'data-survey-klien/do/' . $value->table_identity . '" title="">
			<div class="card mb-5 shadow" style="background-color: SeaShell;">
				<div class="card-body">
					<div class="row">
						<div class="col sm-10">
							<strong style="font-size: 17px;">' . $value->survey_name . '</strong><br>
							<span class="text-dark">Organisasi yang disurvei : <b>' . $value->organisasi . '</b></span><br/>
							<!--span class="text-dark">Klasifikasi survei : <b></b></span><br/>
							<span class="text-dark">Jenis pelayanan : <b></b></span-->
						</div>
						<div class="col sm-2 text-right">' . $status . '
							<div class="mt-3 text-dark font-weight-bold" style="font-size: 11px;">
								Periode Survei : ' . date('d-m-Y', strtotime($value->survey_start)) . ' s/d ' . date('d-m-Y', strtotime($value->survey_end)) . '
							</div>

						</div>
					</div>
					<!--small class="text-secondary">' . $value->description . '</small><br-->
					
				</div>
			</div>
		</a>';
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->DetailSurveyKlien_model->count_all($id_users),
			"recordsFiltered" => $this->DetailSurveyKlien_model->count_filtered($id_users),
			"data" => $data,
		);

		echo json_encode($output);
	}

	public function get_detail_survey($id = NULL)
	{
		$this->data = [];
		$this->data['title'] = "Detail Survey Klien";

		$this->db->select('*');
		$this->db->from('manage_survey');
		$this->db->join('users', 'users.id = manage_survey.id_user');
		$this->db->join('klasifikasi_survei', 'klasifikasi_survei.id = users.id_klasifikasi_survei', 'left');
		$this->db->join('jenis_pelayanan', 'jenis_pelayanan.id = manage_survey.id_jenis_pelayanan', 'left');
		$this->db->join('sampling', 'sampling.id = manage_survey.id_sampling');
		$this->db->where('manage_survey.table_identity', $id);
		$this->data['manage_survey'] = $this->db->get()->row();


		//JUMLAH KUISIONER
		$this->db->select('COUNT(id) AS id');
		$this->db->from('survey_' . $this->data['manage_survey']->table_identity);
		$this->db->where("is_submit = 1");
		$this->data['jumlah_kuisioner'] = $this->db->get()->row()->id;
		$jumlah_kuisioner = $this->data['jumlah_kuisioner'];

		$this->data['sampling_belum'] = $this->data['manage_survey']->jumlah_sampling - $jumlah_kuisioner;

		return view('data_survey_klien/get_detail_survey_klien', $this->data);
	}

	public function profil_responden()
	{
		$this->data = [];
		$this->data['title'] = 'Profil Responden';

		$manage_survey = $this->db->get_where('manage_survey', array('table_identity' => $this->uri->segment(3)))->row();
		$table_identity = $manage_survey->table_identity;

		$this->data['profil_default'] = $this->db->query("SELECT *
		FROM profil_responden
		WHERE NOT EXISTS (SELECT * FROM profil_responden_$table_identity WHERE profil_responden.nama_profil_responden = profil_responden_$table_identity.nama_profil_responden)");

		return view('data_survey_klien/detail_profil_responden', $this->data);
	}

	public function ajax_list_profil_responden()
	{
		$this->load->model('ProfilRespondenSurvei_model');

		$manage_survey = $this->db->get_where('manage_survey', array('table_identity' => $this->uri->segment(3)))->row();
		$table_identity = $manage_survey->table_identity;

		$kategori_profil = $this->db->get('kategori_profil_responden_' . $table_identity);

		$list = $this->ProfilRespondenSurvei_model->get_datatables($table_identity);
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			$pilihan = [];
			foreach ($kategori_profil->result() as $get) {
				if ($get->id_profil_responden == $value->id) {
					$pilihan[] =  '<label><input type="radio">&ensp;' . $get->nama_kategori_profil_responden . '&emsp;</label>';
				}
			}
			$jawaban = implode("<br>", $pilihan);

			$no++;
			$row = array();
			$row[] = $no;
			$row[] =  $value->nama_profil_responden;
			$row[] = $jawaban;

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->ProfilRespondenSurvei_model->count_all($table_identity),
			"recordsFiltered" => $this->ProfilRespondenSurvei_model->count_filtered($table_identity),
			"data" => $data,
		);

		echo json_encode($output);
	}


	// public function unsur_pelayanan($id = NULL)
	// {
	// 	$this->data = [];
	// 	$this->data['title'] = "Unsur Pelayanan";

	// 	return view('data_survey_klien/detail_unsur_pelayanan', $this->data);
	// }

	// public function ajax_list_unsur()
	// {
	// 	$this->load->model('UnsurPelayananSurvey_model', 'models');

	// 	$table_identity = $this->uri->segment(3);

	// 	$list = $this->models->get_datatables($table_identity);
	// 	$data = array();
	// 	$no = $_POST['start'];

	// 	foreach ($list as $value) {

	// 		$no++;
	// 		$row = array();
	// 		$row[] = $no;
	// 		$row[] =  $value->nomor_unsur;
	// 		$row[] = $value->nama_unsur_pelayanan;

	// 		$data[] = $row;
	// 	}

	// 	$output = array(
	// 		"draw" => $_POST['draw'],
	// 		"recordsTotal" => $this->models->count_all($table_identity),
	// 		"recordsFiltered" => $this->models->count_filtered($table_identity),
	// 		"data" => $data,
	// 	);

	// 	echo json_encode($output);
	// }



	public function pertanyaan_unsur($id = NULL)
	{
		$this->data = [];
		$this->data['title'] = "Pertanyaan Unsur";

		return view('data_survey_klien/detail_pertanyaan_unsur', $this->data);
	}

	public function ajax_list_pertanyaan_unsur()
	{
		$this->load->model('PertanyaanUnsurSurvei_model');

		$table_identity = $this->uri->segment(3);

		$list = $this->PertanyaanUnsurSurvei_model->get_datatables($table_identity);
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			if ($value->pilihan == 2) {
				$jawaban = '<label><input type="radio">&ensp;' . $value->pilihan_1 . '&emsp;</label><label><input type="radio">&ensp;' . $value->pilihan_2 . '&emsp;</label><label><input type="radio">&ensp;' . $value->pilihan_3 . '&emsp;</label><label><input type="radio">&ensp;' . $value->pilihan_4 . '&emsp;</label>';
			} else if ($value->pilihan == 1) {
				$jawaban = '<label><input type="radio">&ensp;' . $value->pilihan_1 . '&emsp;</label><label><input type="radio">&ensp;' . $value->pilihan_4 . '&emsp;</label>';
			} else {
				$jawaban = '<span class="text-danger">Unsur ini memiliki Sub.</span>';
			}

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = '<b>' . $value->nomor_unsur . '. ' . $value->nama_unsur_pelayanan . '</b>';
			$row[] = $value->isi_pertanyaan_unsur;
			$row[] = $jawaban;

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->PertanyaanUnsurSurvei_model->count_all($table_identity),
			"recordsFiltered" => $this->PertanyaanUnsurSurvei_model->count_filtered($table_identity),
			"data" => $data,
		);

		echo json_encode($output);
	}


	public function pertanyaan_harapan($id = NULL)
	{
		$this->data = [];
		$this->data['title'] = "Pertanyaan Harapan";

		return view('data_survey_klien/detail_pertanyaan_harapan', $this->data);
	}

	public function ajax_list_pertanyaan_harapan()
	{
		$this->load->model('PertanyaanHarapanSurvei_model');

		$table_identity = $this->uri->segment(3);

		$list = $this->PertanyaanHarapanSurvei_model->get_datatables($table_identity);
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			$jawaban = '<label><input type="radio">&ensp;' . $value->pilihan_1 . '&emsp;</label><label><input type="radio">&ensp;' . $value->pilihan_2 . '&emsp;</label><label><input type="radio">&ensp;' . $value->pilihan_3 . '&emsp;</label><label><input type="radio">&ensp;' . $value->pilihan_4 . '&emsp;</label>';

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = '<b>' . $value->nomor_unsur . '. ' . $value->nama_unsur_pelayanan . '</b>';
			$row[] = $value->isi_pertanyaan_unsur;
			$row[] = $jawaban;

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->PertanyaanHarapanSurvei_model->count_all($table_identity),
			"recordsFiltered" => $this->PertanyaanHarapanSurvei_model->count_filtered($table_identity),
			"data" => $data,
		);

		echo json_encode($output);
	}


	public function pertanyaan_tambahan($id = NULL)
	{
		$this->data = [];
		$this->data['title'] = "Pertanyaan Tambahan";

		return view('data_survey_klien/detail_pertanyaan_tambahan', $this->data);
	}

	public function ajax_list_pertanyaan_tambahan()
	{
		$this->load->model('PertanyaanTerbukaSurvei_model');

		$table_identity = $this->uri->segment(3);

		$list = $this->PertanyaanTerbukaSurvei_model->get_datatables($table_identity);
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			if ($value->nomor_unsur == '') {
				$letak_pertanyaan = '<b>' . $value->letak_pertanyaan . '</b>';
			} else {
				$letak_pertanyaan = 'Dibawah Unsur <br><b>' . $value->nomor_unsur . '. ' . $value->nama_unsur_pelayanan . '</b>';
			}

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = '<b>' . $value->nomor_pertanyaan_terbuka . '. ' . $value->nama_pertanyaan_terbuka . '</b><br>' . $value->isi_pertanyaan_terbuka;

			if ($value->id_jenis_pilihan_jawaban == 1) {
				$pilihan = [];
				foreach ($this->db->get_where("isi_pertanyaan_ganda_$table_identity", array('id_perincian_pertanyaan_terbuka' => $value->id_perincian_pertanyaan_terbuka))->result() as $get) {
					$pilihan[] = '<label><input type="radio">&ensp;' . $get->pertanyaan_ganda . '&emsp;</label>';
				};

				if ($value->dengan_isian_lainnya == 1) {
					$row[] = implode("<br>", $pilihan) . '<br><label><input type="radio">&ensp;Lainnya&emsp;</label>';
				} else {
					$row[] = implode("<br>", $pilihan);
				};
			} else {
				$row[] = '';
			}

			$row[] = $letak_pertanyaan;


			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->PertanyaanTerbukaSurvei_model->count_all($table_identity),
			"recordsFiltered" => $this->PertanyaanTerbukaSurvei_model->count_filtered($table_identity),
			"data" => $data,
		);

		echo json_encode($output);
	}


	public function pertanyaan_kualitatif($id = NULL)
	{
		$this->data = [];
		$this->data['title'] = "Pertanyaan Kualitatif";

		return view('data_survey_klien/detail_pertanyaan_kualitatif', $this->data);
	}

	public function ajax_list_pertanyaan_kualitatif()
	{
		$this->load->model('PertanyaanKualitatif_model');

		$table_identity = $this->uri->segment(3);

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


	public function form_survei()
	{
		$url = $this->uri->uri_string();
		$this->session->set_userdata('urlback', $url);

		$this->data = [];
		$this->data['title'] = 'Form Survei';

		$this->data['manage_survey'] = $this->db->get_where('manage_survey', array('table_identity' => $this->uri->segment(3)))->row();
		$this->data['data_user'] = $this->db->get_where('users', array('id' => $this->data['manage_survey']->id_user))->row();
		$this->data['atribut_pertanyaan_survey'] = unserialize($this->data['manage_survey']->atribut_pertanyaan_survey);

		return view('data_survey_klien/detail_form_survei', $this->data);
	}


	public function data_surveyor()
	{
		$this->data = [];
		$this->data['title'] = "Data Surveyor";

		$this->data['manage_survey'] = $this->db->get_where('manage_survey', ['table_identity' => $this->uri->segment(3)])->row();
		$id_manage_survey = $this->data['manage_survey']->id;

		$this->db->select('*, surveyor.uuid AS uuid_surveyor');
		$this->db->from('surveyor');
		$this->db->join('users', 'surveyor.id_user = users.id');
		$this->db->where('surveyor.id_manage_survey', $id_manage_survey);
		$this->data['surveyor'] = $this->db->get();


		return view('data_survey_klien/detail_data_surveyor', $this->data);
	}


	public function ajax_list_data_surveyor()
	{
		$this->load->model('DataSurveyor_model');

		$get_identity = $this->db->get_where('manage_survey', ['table_identity' => $this->uri->segment(3)])->row();
		$id_manage_survey = $get_identity->id;

		$list = $this->DataSurveyor_model->get_datatables($id_manage_survey);
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = '<a class="btn btn-light-info btn-sm font-weight-bold shadow" data-toggle="modal" data-target="#detail' . $value->id_user . ' "><i class="fa fa-info-circle"></i>Detail</a>';
			$row[] = $value->kode_surveyor;
			$row[] = $value->first_name . ' ' . $value->last_name;
			$row[] = '<span class="badge badge-danger">' . $value->total_survey . '</span>';

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->DataSurveyor_model->count_all($id_manage_survey),
			"recordsFiltered" => $this->DataSurveyor_model->count_filtered($id_manage_survey),
			"data" => $data,
		);

		echo json_encode($output);
	}


	public function link_survey($id = NULL)
	{
		$this->data = [];
		$this->data['title'] = "Link Survey";

		$this->db->select('*');
		$this->db->from('manage_survey');
		$this->db->where('table_identity', $this->uri->segment(3));
		$this->data['manage_survey'] = $this->db->get()->row();


		return view('data_survey_klien/detail_link_survey', $this->data);
	}


	public function scan_barcode()
	{
		$this->data = [];
		$this->data['title'] = "Scan Barcode";

		$this->data['manage_survey'] = $this->db->get_where('manage_survey', array('table_identity' => $this->uri->segment(3)))->row();

		return view('data_survey_klien/detail_scan_barcode', $this->data);
	}


	public function data_prospek_survey($id1 = NULL, $id2 = NULL)
	{
		$this->data = [];
		$this->data['title'] = "Data Prospek Survei";

		return view('data_survey_klien/detail_data_prospek_survey', $this->data);
	}

	public function ajax_list_data_prospek_survey()
	{
		$this->load->model('DataProspekSurvey_model');
		$table_identity = $this->uri->segment(3);

		$list = $this->DataProspekSurvey_model->get_datatables($table_identity);
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $value->nama_lengkap;
			$row[] = $value->alamat;
			$row[] = $value->telepon;
			$row[] = $value->email;
			$row[] = $value->keterangan;

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->DataProspekSurvey_model->count_all($table_identity),
			"recordsFiltered" => $this->DataProspekSurvey_model->count_filtered($table_identity),
			"data" => $data,
		);

		echo json_encode($output);
	}

	// public function data_perolehan_surveyor($id = NULL)
	// {
	// 	$this->data = [];
	// 	$this->data['title'] = "Data Perolehan Surveyor";

	// 	$this->db->select('manage_survey.id');
	// 	$this->db->from('manage_survey');
	// 	$this->db->where('table_identity', $this->uri->segment(3));
	// 	$id_manage_survey = $this->db->get()->row()->id;

	// 	$this->db->select("users.id AS user_id, users.first_name, users.last_name, surveyor.kode_surveyor, surveyor.uuid, (SELECT COUNT(survey_cst$id_manage_survey.id) FROM survey_cst$id_manage_survey WHERE survey_cst$id_manage_survey.id_surveyor = surveyor.id) AS total_survey, table_identity");
	// 	$this->db->from('surveyor');
	// 	$this->db->join('users', 'users.id = surveyor.id_user');
	// 	$this->db->join('manage_survey', 'manage_survey.id = surveyor.id_manage_survey');
	// 	$this->db->where('surveyor.id_manage_survey', $id_manage_survey);
	// 	$this->data['get_data'] = $this->db->get();


	// 	return view('data_survey_klien/detail_data_perolehan_surveyor', $this->data);
	// }




	public function data_perolehan_survey()
	{
		$this->data = [];
		$this->data['title'] = "Data Perolehan Survei";
		$table_identity = $this->uri->segment(3);

		//PANGGIL PROFIL RESPONDEN
		$this->data['profil'] = $this->db->query("SELECT * FROM profil_responden_$table_identity")->result();

		//PANGGIL PROFIL RESPONDEN UNTUK FILTER
		$this->data['profil_responden_filter'] = $this->db->query("SELECT * FROM profil_responden_$table_identity WHERE jenis_isian = 1");

		//LOAD KATEGORI PROFIL RESPONDEN JIKA PILIHAN GANDA
		$this->data['kategori_profil_responden'] = $this->db->get('kategori_profil_responden_' . $table_identity);

		return view('data_survey_klien/detail_data_perolehan_survey', $this->data);
	}

	public function ajax_list_data_perolehan_survey()
	{
		$this->load->model('DataPerolehanSurvei_model');

		$table_identity = $this->uri->segment(3);
		$manage_survey = $this->db->get_where('manage_survey', array('table_identity' => $this->uri->segment(3)))->row();

		//PANGGIL PROFIL RESPONDEN
		$profil_responden = $this->db->query("SELECT * FROM profil_responden_$table_identity")->result();

		$list = $this->DataPerolehanSurvei_model->get_datatables($table_identity, $profil_responden);
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

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
			$row[] = $value->nama_lengkap;

			foreach ($profil_responden as $get) {
				$profil = $get->nama_alias;
				$row[] = $value->$profil;
			}

			$row[] = date("d-m-Y", strtotime($value->waktu_isi));

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->DataPerolehanSurvei_model->count_all($table_identity, $profil_responden),
			"recordsFiltered" => $this->DataPerolehanSurvei_model->count_filtered($table_identity, $profil_responden),
			"data" => $data,
		);

		echo json_encode($output);
	}


	public function olah_data()
	{
		$this->data = [];
		$this->data['title'] = "Olah Data";

		$this->db->select('*');
		$this->db->from('manage_survey');
		$this->db->where('table_identity', $this->uri->segment(3));
		$manage_survey = $this->db->get()->row();
		$this->data['atribut_pertanyaan'] = unserialize($manage_survey->atribut_pertanyaan_survey);
		// var_dump($manage_survey);

		$this->data['unsur'] = $this->db->query("SELECT *, SUBSTR(nomor_unsur,2) AS nomor_harapan
		FROM unsur_pelayanan_$manage_survey->table_identity
		JOIN pertanyaan_unsur_pelayanan_$manage_survey->table_identity ON unsur_pelayanan_$manage_survey->table_identity.id = pertanyaan_unsur_pelayanan_$manage_survey->table_identity.id_unsur_pelayanan
		");

		//TOTAL
		$this->data['total'] = $this->db->query("SELECT SUM(skor_jawaban) AS sum_skor_jawaban
		FROM jawaban_pertanyaan_unsur_$manage_survey->table_identity
		JOIN responden_$manage_survey->table_identity ON jawaban_pertanyaan_unsur_$manage_survey->table_identity.id_responden = responden_$manage_survey->table_identity.id
		JOIN survey_$manage_survey->table_identity ON responden_$manage_survey->table_identity.id = survey_$manage_survey->table_identity.id
		WHERE is_submit = 1
		GROUP BY id_pertanyaan_unsur");

		//TOTAL HARAPAN
		$this->data['total_harapan'] = $this->db->query("SELECT SUM(skor_jawaban) AS sum_skor_jawaban
		FROM jawaban_pertanyaan_harapan_$manage_survey->table_identity
		JOIN responden_$manage_survey->table_identity ON jawaban_pertanyaan_harapan_$manage_survey->table_identity.id_responden = responden_$manage_survey->table_identity.id
		JOIN survey_$manage_survey->table_identity ON responden_$manage_survey->table_identity.id = survey_$manage_survey->table_identity.id
		WHERE is_submit = 1
		GROUP BY id_pertanyaan_unsur");

		//JUMLAH KUISIONER
		$this->db->select('COUNT(id) AS id');
		$this->db->from('survey_' . $manage_survey->table_identity);
		$this->db->where("is_submit = 1");
		$this->data['jumlah_kuisioner'] = $this->db->get()->row()->id;

		if ($this->data['jumlah_kuisioner'] == 0) {
			$this->data['pesan'] = 'survei belum dimulai atau belum ada responden !';
			return view('not_questions/index', $this->data);
		}


		//RATA-RATA
		$this->db->select("(SUM(skor_jawaban)/COUNT(DISTINCT jawaban_pertanyaan_unsur_$manage_survey->table_identity.id_responden)) AS rata_rata");
		$this->db->from('jawaban_pertanyaan_unsur_' . $manage_survey->table_identity);
		$this->db->join("survey_$manage_survey->table_identity", "jawaban_pertanyaan_unsur_$manage_survey->table_identity.id_responden = survey_$manage_survey->table_identity.id_responden");
		$this->db->where("survey_$manage_survey->table_identity.is_submit = 1");
		$this->db->group_by('id_pertanyaan_unsur');
		$this->data['rata_rata'] = $this->db->get();

		//RATA-RATA HARAPAN
		$this->db->select("(SUM(skor_jawaban)/COUNT(DISTINCT survey_$manage_survey->table_identity.id_responden)) AS rata_rata");
		$this->db->from('jawaban_pertanyaan_harapan_' . $manage_survey->table_identity);
		$this->db->join("survey_$manage_survey->table_identity", "jawaban_pertanyaan_harapan_$manage_survey->table_identity.id_responden = survey_$manage_survey->table_identity.id_responden");
		$this->db->where("survey_$manage_survey->table_identity.is_submit = 1");
		$this->db->group_by('id_pertanyaan_unsur');
		$this->data['rata_rata_harapan'] = $this->db->get();

		//NILAI PER UNSUR
		$this->db->select("nama_unsur_pelayanan, IF(id_parent = 0,unsur_pelayanan_$manage_survey->table_identity.id, unsur_pelayanan_$manage_survey->table_identity.id_parent) AS id_sub, (SUM(skor_jawaban)/COUNT(DISTINCT survey_$manage_survey->table_identity.id_responden)) AS rata_rata,  (COUNT(id_parent)/COUNT(DISTINCT survey_$manage_survey->table_identity.id_responden)) AS colspan, ((SUM(skor_jawaban)/COUNT(DISTINCT survey_$manage_survey->table_identity.id_responden))/(COUNT(id_parent)/COUNT(DISTINCT survey_$manage_survey->table_identity.id_responden))) AS nilai_per_unsur");
		$this->db->from('jawaban_pertanyaan_unsur_' . $manage_survey->table_identity);
		$this->db->join("pertanyaan_unsur_pelayanan_$manage_survey->table_identity", "jawaban_pertanyaan_unsur_$manage_survey->table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$manage_survey->table_identity.id");
		$this->db->join("unsur_pelayanan_$manage_survey->table_identity", "pertanyaan_unsur_pelayanan_$manage_survey->table_identity.id_unsur_pelayanan = unsur_pelayanan_$manage_survey->table_identity.id");
		$this->db->join("survey_$manage_survey->table_identity", "jawaban_pertanyaan_unsur_$manage_survey->table_identity.id_responden = survey_$manage_survey->table_identity.id_responden");
		$this->db->where("survey_$manage_survey->table_identity.is_submit = 1");
		$this->db->group_by('id_sub');
		$this->data['nilai_per_unsur'] = $this->db->get();

		//NILAI PER HARAPAN
		$this->db->select("nama_unsur_pelayanan, IF(id_parent = 0,unsur_pelayanan_$manage_survey->table_identity.id, unsur_pelayanan_$manage_survey->table_identity.id_parent) AS id_sub, (SUM(skor_jawaban)/COUNT(DISTINCT survey_$manage_survey->table_identity.id_responden)) AS rata_rata,  (COUNT(id_parent)/COUNT(DISTINCT survey_$manage_survey->table_identity.id_responden)) AS colspan, ((SUM(skor_jawaban)/COUNT(DISTINCT survey_$manage_survey->table_identity.id_responden))/(COUNT(id_parent)/COUNT(DISTINCT survey_$manage_survey->table_identity.id_responden))) AS nilai_per_unsur");
		$this->db->from("jawaban_pertanyaan_harapan_$manage_survey->table_identity");
		$this->db->join("pertanyaan_unsur_pelayanan_$manage_survey->table_identity", "jawaban_pertanyaan_harapan_$manage_survey->table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$manage_survey->table_identity.id");
		$this->db->join("unsur_pelayanan_$manage_survey->table_identity", "pertanyaan_unsur_pelayanan_$manage_survey->table_identity.id_unsur_pelayanan = unsur_pelayanan_$manage_survey->table_identity.id");
		$this->db->join("survey_$manage_survey->table_identity", "jawaban_pertanyaan_harapan_$manage_survey->table_identity.id_responden = survey_$manage_survey->table_identity.id_responden");
		$this->db->where("survey_$manage_survey->table_identity.is_submit = 1");
		$this->db->group_by('id_sub');
		$this->data['nilai_per_unsur_harapan'] = $this->db->get();

		//RATA-RATA BOBOT
		$this->db->select("nama_unsur_pelayanan, IF(id_parent = 0,unsur_pelayanan_$manage_survey->table_identity.id, unsur_pelayanan_$manage_survey->table_identity.id_parent) AS id_sub, (SUM(skor_jawaban)/COUNT(DISTINCT survey_$manage_survey->table_identity.id_responden)) AS rata_rata,  (COUNT(id_parent)/COUNT(DISTINCT survey_$manage_survey->table_identity.id_responden)) AS colspan, ((SUM(skor_jawaban)/COUNT(DISTINCT survey_$manage_survey->table_identity.id_responden))/(COUNT(id_parent)/COUNT(DISTINCT survey_$manage_survey->table_identity.id_responden))) AS nilai, (((SUM(skor_jawaban)/COUNT(DISTINCT survey_$manage_survey->table_identity.id_responden))/(COUNT(id_parent)/COUNT(DISTINCT survey_$manage_survey->table_identity.id_responden)))/(SELECT COUNT(id) FROM unsur_pelayanan_$manage_survey->table_identity WHERE id_parent = 0)) AS rata_rata_bobot");
		$this->db->from('jawaban_pertanyaan_unsur_' . $manage_survey->table_identity);
		$this->db->join("pertanyaan_unsur_pelayanan_$manage_survey->table_identity", "jawaban_pertanyaan_unsur_$manage_survey->table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$manage_survey->table_identity.id");
		$this->db->join("unsur_pelayanan_$manage_survey->table_identity", "pertanyaan_unsur_pelayanan_$manage_survey->table_identity.id_unsur_pelayanan = unsur_pelayanan_$manage_survey->table_identity.id");
		$this->db->join("survey_$manage_survey->table_identity", "jawaban_pertanyaan_unsur_$manage_survey->table_identity.id_responden = survey_$manage_survey->table_identity.id_responden");
		$this->db->where("survey_$manage_survey->table_identity.is_submit = 1");
		$this->db->group_by('id_sub');
		$this->data['rata_rata_bobot'] = $this->db->get();

		//TERTIMBANG
		$this->db->select("IF(id_parent = 0,unsur_pelayanan_$manage_survey->table_identity.id, unsur_pelayanan_$manage_survey->table_identity.id_parent) AS id_sub,  (COUNT(id_parent)/COUNT(DISTINCT survey_$manage_survey->table_identity.id_responden)) AS colspan, (((SUM(skor_jawaban)/COUNT(DISTINCT survey_$manage_survey->table_identity.id_responden))/(COUNT(id_parent)/COUNT(DISTINCT survey_$manage_survey->table_identity.id_responden)))) AS tertimbang, ((((SUM(skor_jawaban)/COUNT(DISTINCT survey_$manage_survey->table_identity.id_responden))/(COUNT(id_parent)/COUNT(DISTINCT survey_$manage_survey->table_identity.id_responden))))*25) AS skm");
		$this->db->from('jawaban_pertanyaan_unsur_' . $manage_survey->table_identity);
		$this->db->join("pertanyaan_unsur_pelayanan_$manage_survey->table_identity", "jawaban_pertanyaan_unsur_$manage_survey->table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$manage_survey->table_identity.id");
		$this->db->join("unsur_pelayanan_$manage_survey->table_identity", "pertanyaan_unsur_pelayanan_$manage_survey->table_identity.id_unsur_pelayanan = unsur_pelayanan_$manage_survey->table_identity.id");
		$this->db->join("survey_$manage_survey->table_identity", "jawaban_pertanyaan_unsur_$manage_survey->table_identity.id_responden = survey_$manage_survey->table_identity.id_responden");
		$this->db->where("survey_$manage_survey->table_identity.is_submit = 1");
		$this->data['tertimbang'] = $this->db->get()->row();

		return view('data_survey_klien/detail_olah_data', $this->data);
	}

	public function ajax_list_olah_data()
	{
		$this->load->model('OlahData_model');

		$get_identity = $this->db->get_where('manage_survey', ['table_identity' => $this->uri->segment(3)])->row();
		$table_identity = $get_identity->table_identity;

		$jawaban_unsur = $this->db->get("jawaban_pertanyaan_unsur_$table_identity");

		$list = $this->OlahData_model->get_datatables($table_identity);
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			if ($value->is_submit == 1) {
				$status = '<span class="badge badge-primary">Valid</span>';
			} else {
				$status = '<span class="badge badge-danger">Tidak Valid</span>';
			}

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $status;
			$row[] = '<b>' . $value->kode_surveyor . '</b>--' . $value->first_name . ' ' . $value->last_name;
			$row[] = $value->nama_lengkap;

			foreach ($jawaban_unsur->result() as $get_unsur) {
				if ($get_unsur->id_responden == $value->id_responden) {
					$row[] = $get_unsur->skor_jawaban;
				}
			}
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->OlahData_model->count_all($table_identity),
			"recordsFiltered" => $this->OlahData_model->count_filtered($table_identity),
			"data" => $data,
		);
		echo json_encode($output);
	}

	public function chart_visualisasi()
	{
		$this->data = [];
		$this->data['title'] = "Chart dan Visualisasi";

		// get tabel identity
		$this->db->select('*');
		$this->db->from('manage_survey');
		$this->db->where('table_identity', $this->uri->segment(3));
		$manage_survey = $this->db->get()->row();
		$this->data['table_identity'] = $manage_survey->table_identity;

		$this->db->select("*, unsur_pelayanan_$manage_survey->table_identity.id AS id_unsur_pelayanan");
		$this->db->from("unsur_pelayanan_$manage_survey->table_identity");
		$this->db->where(['id_parent' => 0]);
		$this->data['unsur_pelayanan'] = $this->db->get();

		//NILAI PER UNSUR
		$this->db->select("nama_unsur_pelayanan, IF(id_parent = 0,unsur_pelayanan_$manage_survey->table_identity.id, unsur_pelayanan_$manage_survey->table_identity.id_parent) AS id_sub, (SUM(skor_jawaban)/COUNT(DISTINCT survey_$manage_survey->table_identity.id_responden)) AS rata_rata,  (COUNT(id_parent)/COUNT(DISTINCT survey_$manage_survey->table_identity.id_responden)) AS colspan, ((SUM(skor_jawaban)/COUNT(DISTINCT survey_$manage_survey->table_identity.id_responden))/(COUNT(id_parent)/COUNT(DISTINCT survey_$manage_survey->table_identity.id_responden))) AS nilai_per_unsur");
		$this->db->from('jawaban_pertanyaan_unsur_' . $manage_survey->table_identity);
		$this->db->join("pertanyaan_unsur_pelayanan_$manage_survey->table_identity", "jawaban_pertanyaan_unsur_$manage_survey->table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$manage_survey->table_identity.id");
		$this->db->join("unsur_pelayanan_$manage_survey->table_identity", "pertanyaan_unsur_pelayanan_$manage_survey->table_identity.id_unsur_pelayanan = unsur_pelayanan_$manage_survey->table_identity.id");
		$this->db->join("survey_$manage_survey->table_identity", "jawaban_pertanyaan_unsur_$manage_survey->table_identity.id_responden = survey_$manage_survey->table_identity.id_responden");
		$this->db->where("survey_$manage_survey->table_identity.is_submit = 1");
		$this->db->group_by('id_sub');
		$this->data['nilai_per_unsur'] = $this->db->get();

		if ($this->db->get_where('survey_' . $this->data['table_identity'], array('is_submit' => 1))->num_rows() == 0) {
			$this->data['pesan'] = 'survei belum dimulai atau belum ada responden !';
			return view('not_questions/index', $this->data);
		}

		return view('data_survey_klien/detail_chart_visualisasi', $this->data);
	}


	public function kuadran($id = NULL)
	{
		$this->data = [];
		$this->data['title'] = "Kuadran";

		$this->db->select('manage_survey.id AS id_manage_survey, manage_survey.table_identity AS table_identity, manage_survey.id_jenis_pelayanan AS id_jenis_pelayanan');
		$this->db->from('manage_survey');
		$this->db->where('table_identity', $this->uri->segment(3));
		$manage_survey = $this->db->get()->row();

		//SKOR JAWABAN UNSUR
		$this->db->select('*');
		$this->db->from('jawaban_pertanyaan_unsur_' . $manage_survey->table_identity);
		$this->db->join("survey_$manage_survey->table_identity", "jawaban_pertanyaan_unsur_$manage_survey->table_identity.id_responden = survey_$manage_survey->table_identity.id_responden");
		$this->db->where("survey_$manage_survey->table_identity.is_submit = 1");
		$this->data['skor'] = $this->db->get();

		//JUDUL PERSEPSI
		$this->db->select("unsur_pelayanan_$manage_survey->table_identity.nomor_unsur AS nomor,
		SUBSTRING(nomor_unsur, 2, 4) AS nomor_harapan, nama_unsur_pelayanan");
		$this->db->from("unsur_pelayanan_$manage_survey->table_identity");
		$this->db->where('id_parent = 0');
		$this->data['persepsi'] = $this->db->get();

		$this->data['jumlah_unsur'] = $this->data['persepsi']->num_rows();
		$this->data['colspan_unsur'] = ($this->data['jumlah_unsur'] + 1);
		// var_dump($this->data['persepsi']->result());

		//NILAI PER UNSUR
		$this->db->select("IF(id_parent = 0,unsur_pelayanan_$manage_survey->table_identity.id, unsur_pelayanan_$manage_survey->table_identity.id_parent) AS id_sub, ROUND(((SUM(skor_jawaban)/COUNT(DISTINCT survey_$manage_survey->table_identity.id_responden))/(COUNT(id_parent)/COUNT(DISTINCT survey_$manage_survey->table_identity.id_responden))), 2) AS nilai_per_unsur");
		$this->db->from('jawaban_pertanyaan_unsur_' . $manage_survey->table_identity);
		$this->db->join("pertanyaan_unsur_pelayanan_$manage_survey->table_identity", "jawaban_pertanyaan_unsur_$manage_survey->table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$manage_survey->table_identity.id");
		$this->db->join("unsur_pelayanan_$manage_survey->table_identity", "pertanyaan_unsur_pelayanan_$manage_survey->table_identity.id_unsur_pelayanan = unsur_pelayanan_$manage_survey->table_identity.id");
		$this->db->join("survey_$manage_survey->table_identity", "jawaban_pertanyaan_unsur_$manage_survey->table_identity.id_responden = survey_$manage_survey->table_identity.id_responden");
		$this->db->where("survey_$manage_survey->table_identity.is_submit = 1");
		$this->db->group_by('id_sub');
		$object_unsur = $this->db->get();
		$this->data['nilai_per_unsur'] = $object_unsur;

		$nilai_unsur = 0;
		foreach ($object_unsur->result() as $values) {
			$nilai_unsur += $values->nilai_per_unsur;
		}

		//NILAI PER HARAPAN
		$this->db->select("ROUND(((SUM(skor_jawaban)/COUNT(DISTINCT survey_$manage_survey->table_identity.id_responden))/(COUNT(id_parent)/COUNT(DISTINCT survey_$manage_survey->table_identity.id_responden))), 2) AS nilai_per_unsur");
		$this->db->from("jawaban_pertanyaan_harapan_$manage_survey->table_identity");
		$this->db->join("pertanyaan_unsur_pelayanan_$manage_survey->table_identity", "jawaban_pertanyaan_harapan_$manage_survey->table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$manage_survey->table_identity.id");
		$this->db->join("unsur_pelayanan_$manage_survey->table_identity", "pertanyaan_unsur_pelayanan_$manage_survey->table_identity.id_unsur_pelayanan = unsur_pelayanan_$manage_survey->table_identity.id");
		$this->db->join("survey_$manage_survey->table_identity", "jawaban_pertanyaan_harapan_$manage_survey->table_identity.id_responden = survey_$manage_survey->table_identity.id_responden");
		$this->db->where("survey_$manage_survey->table_identity.is_submit = 1");
		$this->db->group_by("IF(id_parent = 0,unsur_pelayanan_$manage_survey->table_identity.id, unsur_pelayanan_$manage_survey->table_identity.id_parent)");
		$object_harapan = $this->db->get();
		$this->data['nilai_per_unsur_harapan'] = $object_harapan;

		$nilai_harapan = 0;
		foreach ($object_harapan->result() as $rows) {
			$nilai_harapan += $rows->nilai_per_unsur;
		}

		$query =  $this->db->query("SELECT nama_unsur_pelayanan, IF(id_parent = 0,unsur_pelayanan_$manage_survey->table_identity.id, unsur_pelayanan_$manage_survey->table_identity.id_parent) AS id_sub,
		ROUND((SUM((SELECT SUM(skor_jawaban) FROM jawaban_pertanyaan_unsur_$manage_survey->table_identity JOIN survey_$manage_survey->table_identity ON jawaban_pertanyaan_unsur_$manage_survey->table_identity.id_responden = survey_$manage_survey->table_identity.id_responden WHERE is_submit = 1 && pertanyaan_unsur_pelayanan_$manage_survey->table_identity.id = jawaban_pertanyaan_unsur_$manage_survey->table_identity.id_pertanyaan_unsur))/(SELECT COUNT(survey_$manage_survey->table_identity.id_responden) FROM jawaban_pertanyaan_unsur_$manage_survey->table_identity 
				JOIN survey_$manage_survey->table_identity ON jawaban_pertanyaan_unsur_$manage_survey->table_identity.id_responden = survey_$manage_survey->table_identity.id_responden
				WHERE pertanyaan_unsur_pelayanan_$manage_survey->table_identity.id = jawaban_pertanyaan_unsur_$manage_survey->table_identity.id_pertanyaan_unsur && survey_$manage_survey->table_identity.is_submit = 1)/COUNT(id_parent)),2) AS skor_unsur,
				
				ROUND((SUM(
				(SELECT SUM(skor_jawaban) FROM jawaban_pertanyaan_harapan_$manage_survey->table_identity JOIN survey_$manage_survey->table_identity ON jawaban_pertanyaan_harapan_$manage_survey->table_identity.id_responden = survey_$manage_survey->table_identity.id_responden WHERE is_submit = 1 && pertanyaan_unsur_pelayanan_$manage_survey->table_identity.id = jawaban_pertanyaan_harapan_$manage_survey->table_identity.id_pertanyaan_unsur))/(SELECT COUNT(survey_$manage_survey->table_identity.id_responden) FROM jawaban_pertanyaan_unsur_$manage_survey->table_identity 
				JOIN survey_$manage_survey->table_identity ON jawaban_pertanyaan_unsur_$manage_survey->table_identity.id_responden = survey_$manage_survey->table_identity.id_responden
				WHERE pertanyaan_unsur_pelayanan_$manage_survey->table_identity.id = jawaban_pertanyaan_unsur_$manage_survey->table_identity.id_pertanyaan_unsur && survey_$manage_survey->table_identity.is_submit = 1)/COUNT(id_parent)), 2) AS skor_harapan,
				
				IF(is_sub_unsur_pelayanan = 1,SUBSTR(nomor_unsur,1, 3), nomor_unsur) AS nomor
				
				FROM pertanyaan_unsur_pelayanan_$manage_survey->table_identity
				JOIN unsur_pelayanan_$manage_survey->table_identity ON pertanyaan_unsur_pelayanan_$manage_survey->table_identity.id_unsur_pelayanan = unsur_pelayanan_$manage_survey->table_identity.id
				GROUP BY id_sub");
		$this->data['grafik'] = $query;
		// var_dump($this->data['grafik']->result());


		if ($this->data['skor']->num_rows() > 0) {
			$this->data['skor'] = $this->data['skor'];
			$this->data['total_rata_unsur'] = $nilai_unsur / $this->data['jumlah_unsur'];
			$this->data['total_rata_harapan'] = $nilai_harapan / $this->data['jumlah_unsur'];
		} else {
			$this->data['pesan'] = 'survei belum dimulai atau belum ada responden !';
			return view('not_questions/index', $this->data);
			exit();
		}

		return view('data_survey_klien/detail_kuadran', $this->data);
	}

	public function rekap_responden($id = NULL)
	{
		$this->data = [];
		$this->data['title'] = "Rekap Responden";

		$this->db->select('*');
		$this->db->from('manage_survey');
		$this->db->where('table_identity', $this->uri->segment(3));
		$manage_survey = $this->db->get()->row();
		$this->data['table_identity'] = $manage_survey->table_identity;


		$profil_responden = $this->db->query("SELECT * FROM profil_responden_" . $this->data['table_identity'] . " WHERE jenis_isian = 1");

		if ($profil_responden->num_rows() == 0) {
			$this->data['pesan'] = 'Profil responden survei anda tidak memiliki data yang bisa di olah.';
			return view('not_questions/index', $this->data);
		}
		$this->data['profil_responden'] = $profil_responden->result();


		return view('data_survey_klien/detail_rekap_responden', $this->data);
	}


	public function alasan_jawaban($id = NULL)
	{
		$this->data = [];
		$this->data['title'] = "Alasan Jawaban";

		return view('data_survey_klien/detail_alasan_jawaban', $this->data);
	}

	public function ajax_list_alasan()
	{
		$this->load->model('PertanyaanUnsurSurvei_model');

		$table_identity = $this->uri->segment(3);

		$list = $this->PertanyaanUnsurSurvei_model->get_datatables($table_identity);
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {


			$no++;
			$row = array();
			$row[] = $no;
			$row[] = '<b>' . $value->nomor_unsur . '. ' . $value->nama_unsur_pelayanan . '</b><br>' . $value->isi_pertanyaan_unsur;
			$row[] = '<a class="btn btn-light-danger" disabled>' . $value->jumlah_alasan . '</a>';
			$row[] = anchor('data-survey-klien/alasan-jawaban/' . $this->uri->segment(3) . '/' . $value->id_pertanyaan_unsur, 'Detail Alasan Jawaban <i class="fa fa-arrow-right"></i>', ['class' => 'btn btn-light-primary btn-sm font-weight-bold shadow']);

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->PertanyaanUnsurSurvei_model->count_all($table_identity),
			"recordsFiltered" => $this->PertanyaanUnsurSurvei_model->count_filtered($table_identity),
			"data" => $data,
		);

		echo json_encode($output);
	}

	public function detail_alasan($id = NULL)
	{
		$this->data = [];
		$this->data['title'] = "Detail Alasan Jawaban";

		$table_identity = $this->uri->segment(3);

		$this->db->select("*");
		$this->db->from('pertanyaan_unsur_pelayanan_' . $table_identity);
		$this->db->join("unsur_pelayanan_$table_identity", "pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan = unsur_pelayanan_$table_identity.id");
		$this->db->where("pertanyaan_unsur_pelayanan_$table_identity.id", $this->uri->segment(4));
		$this->data['pertanyaan'] = $this->db->get()->row();

		return view('data_survey_klien/detail_alasan_detail', $this->data);
	}

	public function ajax_list_detail_alasan()
	{
		$this->load->model('Alasan_model');
		$id_pertanyaan_unsur = $this->uri->segment(4);
		$table_identity = $this->uri->segment(3);

		$list = $this->Alasan_model->get_datatables($table_identity, $id_pertanyaan_unsur);
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			if ($value->is_active == 1) {
				$status = '<span class="badge badge-primary">Di Tampilkan</span>';
			} else {
				$status = '<span class="badge badge-danger">Tidak di Tampilkan</span>';
			}

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $value->nama_lengkap;
			$row[] = $value->bobot;
			$row[] = $value->alasan_pilih_jawaban;
			$row[] = $status;

			$data[] = $row;
		}
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->Alasan_model->count_all($table_identity, $id_pertanyaan_unsur),
			"recordsFiltered" => $this->Alasan_model->count_filtered($table_identity, $id_pertanyaan_unsur),
			"data" => $data,
		);
		echo json_encode($output);
	}


	public function rekap_harapan()
	{
		$this->data = [];
		$this->data['title'] = "Rekap Pertanyaan Harapan";

		return view('data_survey_klien/detail_rekap_harapan', $this->data);
	}

	public function ajax_list_rekap_harapan()
	{
		$this->load->model('PertanyaanUnsurSurvei_model');
		$table_identity = $this->uri->segment(3);

		$list = $this->PertanyaanUnsurSurvei_model->get_datatables($table_identity);
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = '<b>' . $value->nomor_unsur . '. ' . $value->nama_unsur_pelayanan . '</b><br>' . $value->isi_pertanyaan_unsur;

			$row[] = anchor('data-survey-klien/rekap-harapan/' . $this->uri->segment(3) . '/' . $value->id_pertanyaan_unsur, 'Detail <i class="fa fa-arrow-right"></i>', ['class' => 'btn btn-light-primary btn-sm font-weight-bold shadow']);

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->PertanyaanUnsurSurvei_model->count_all($table_identity),
			"recordsFiltered" => $this->PertanyaanUnsurSurvei_model->count_filtered($table_identity),
			"data" => $data,
		);

		echo json_encode($output);
	}


	public function detail_rekap_harapan()
	{
		$this->data = [];
		$this->data['title'] = "Detail Jawaban Pertanyaan Harapan";

		$get_identity = $this->db->get_where('manage_survey', array('table_identity' => $this->uri->segment(3)))->row();
		$table_identity = $get_identity->table_identity;

		$this->db->select("*");
		$this->db->from('pertanyaan_unsur_pelayanan_' . $table_identity);
		$this->db->join("unsur_pelayanan_$table_identity", "pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan = unsur_pelayanan_$table_identity.id");
		$this->db->where("pertanyaan_unsur_pelayanan_$table_identity.id", $this->uri->segment(4));
		$this->data['pertanyaan'] = $this->db->get()->row();
		// var_dump($this->data['pertanyaan']);

		return view('data_survey_klien/detail_rekap_harapan_detail', $this->data);
	}

	public function ajax_list_detail_rekap_harapan()
	{
		$this->load->model('RekapPertanyaanHarapan_model');
		$table_identity = $this->uri->segment(3);
		$id_pertanyaan_unsur = $this->uri->segment(4);

		$list = $this->RekapPertanyaanHarapan_model->get_datatables($table_identity, $id_pertanyaan_unsur);
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $value->nama_responden;
			$row[] = $value->nama_tingkat_kepentingan;

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->RekapPertanyaanHarapan_model->count_all($table_identity, $id_pertanyaan_unsur),
			"recordsFiltered" => $this->RekapPertanyaanHarapan_model->count_filtered($table_identity, $id_pertanyaan_unsur),
			"data" => $data,
		);

		echo json_encode($output);
	}


	public function rekap_tambahan()
	{
		$this->data = [];
		$this->data['title'] = "Rekapitulasi Pertanyaan Tambahan";

		$get_identity = $this->db->get_where('manage_survey', array('table_identity' => $this->uri->segment(3)))->row();
		$table_identity = $get_identity->table_identity;

		$cek_survey = $this->db->get_where("survey_$table_identity", array('is_submit', 1));
		if ($cek_survey->num_rows() == 0) {
			$this->data['pesan'] = 'survei belum dimulai atau belum ada responden !';
			return view('not_questions/index', $this->data);
		}

		$this->data['pertanyaan_tambahan'] = $this->db->query("SELECT *, (SELECT DISTINCT dengan_isian_lainnya FROM isi_pertanyaan_ganda_$table_identity WHERE isi_pertanyaan_ganda_$table_identity.id_perincian_pertanyaan_terbuka = perincian_pertanyaan_terbuka_$table_identity.id) AS is_lainnya,

		(SELECT COUNT(*) FROM responden_$table_identity
		JOIN jawaban_pertanyaan_terbuka_$table_identity ON responden_$table_identity.id =
		jawaban_pertanyaan_terbuka_$table_identity.id_responden
		JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id_responden
		WHERE survey_$table_identity.is_submit = 1 && jawaban_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka =
		perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka && jawaban_pertanyaan_terbuka_$table_identity.jawaban =
		'Lainnya') AS perolehan,
		
		(((SELECT COUNT(*) FROM responden_$table_identity
		JOIN jawaban_pertanyaan_terbuka_$table_identity ON responden_$table_identity.id =
		jawaban_pertanyaan_terbuka_$table_identity.id_responden
		JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id_responden
		WHERE survey_$table_identity.is_submit = 1 && jawaban_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka =
		perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka && jawaban_pertanyaan_terbuka_$table_identity.jawaban =
		'Lainnya') / (SELECT COUNT(*) FROM survey_$table_identity WHERE is_submit =
		1)) * 100) AS persentase

		FROM pertanyaan_terbuka_$table_identity
		JOIN perincian_pertanyaan_terbuka_$table_identity ON pertanyaan_terbuka_$table_identity.id = perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka");

		//--------------------------------------------------------------------------------------------------------

		$this->data['jawaban_ganda'] = $this->db->query("SELECT *, (SELECT COUNT(*) FROM responden_$table_identity
		JOIN jawaban_pertanyaan_terbuka_$table_identity ON responden_$table_identity.id =
		jawaban_pertanyaan_terbuka_$table_identity.id_responden
		JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id_responden
		WHERE survey_$table_identity.is_submit = 1 && jawaban_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka =
		perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka && jawaban_pertanyaan_terbuka_$table_identity.jawaban =
		isi_pertanyaan_ganda_$table_identity.pertanyaan_ganda) AS perolehan,

		(((SELECT COUNT(*) FROM responden_$table_identity
		JOIN jawaban_pertanyaan_terbuka_$table_identity ON responden_$table_identity.id =
		jawaban_pertanyaan_terbuka_$table_identity.id_responden
		JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id_responden
		WHERE survey_$table_identity.is_submit = 1 && jawaban_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka =
		perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka && jawaban_pertanyaan_terbuka_$table_identity.jawaban =
		isi_pertanyaan_ganda_$table_identity.pertanyaan_ganda) / (SELECT COUNT(*) FROM survey_$table_identity WHERE is_submit =
		1)) * 100) AS persentase

		FROM isi_pertanyaan_ganda_$table_identity
		JOIN perincian_pertanyaan_terbuka_$table_identity ON isi_pertanyaan_ganda_$table_identity.id_perincian_pertanyaan_terbuka
		= perincian_pertanyaan_terbuka_$table_identity.id
		WHERE perincian_pertanyaan_terbuka_$table_identity.id_jenis_pilihan_jawaban = 1");

		//--------------------------------------------------------------------------------------------------------

		$this->data['jawaban_isian'] = $this->db->query("SELECT *
		FROM jawaban_pertanyaan_terbuka_$table_identity
		JOIN pertanyaan_terbuka_$table_identity ON pertanyaan_terbuka_$table_identity.id = jawaban_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka
		JOIN perincian_pertanyaan_terbuka_$table_identity ON pertanyaan_terbuka_$table_identity.id = perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka
		JOIN responden_$table_identity ON jawaban_pertanyaan_terbuka_$table_identity.id_responden = responden_$table_identity.id
		JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id_responden
		WHERE id_jenis_pilihan_jawaban = 2 && survey_$table_identity.is_submit = 1");


		return view('data_survey_klien/detail_rekap_tambahan', $this->data);
	}


	public function jawaban_pertanyaan_kualitatif($id = NULL)
	{
		$this->data = [];
		$this->data['title'] = "Jawaban Pertanyaan Kualitatif";


		return view('data_survey_klien/detail_jawaban_pertanyaan_kualitatif', $this->data);
	}

	public function ajax_list_jawaban_kualitatif()
	{
		$this->load->model('PertanyaanKualitatif_model');

		$table_identity = $this->uri->segment(3);

		$list = $this->PertanyaanKualitatif_model->get_datatables($table_identity);
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $value->isi_pertanyaan;
			$row[] = anchor('data-survey-klien/jawaban-pertanyaan-kualitatif/' . $this->uri->segment(3) . '/' . $value->id, 'Detail Jawaban Kualitatif <i class="fa fa-arrow-right"></i>', ['class' => 'btn btn-light-primary btn-sm font-weight-bold shadow']);


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


	public function detail_jawaban_kualitatif($id = NULL)
	{
		$this->data = [];
		$this->data['title'] = "Detail Jawaban Pertanyaan Kualitatif";

		$table_identity = $this->uri->segment(3);
		$id_pertanyaan_kualitatif = $this->uri->segment(4);

		$this->data['pertanyaan'] = $this->db->get_where("pertanyaan_kualitatif_$table_identity", array('id' => $id_pertanyaan_kualitatif))->row();

		return view('data_survey_klien/detail_jawaban_kualitatif_detail', $this->data);
	}

	public function ajax_list_detail_jawaban_kualitatif()
	{
		$this->load->model('JawabanKualitatif_model');

		$id_pertanyaan_kualitatif = $this->uri->segment(4);
		$table_identity = $this->uri->segment(3);

		$list = $this->JawabanKualitatif_model->get_datatables($table_identity, $id_pertanyaan_kualitatif);
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $value->nama_lengkap;
			$row[] = $value->isi_jawaban_kualitatif;

			$data[] = $row;
		}
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->JawabanKualitatif_model->count_all($table_identity, $id_pertanyaan_kualitatif),
			"recordsFiltered" => $this->JawabanKualitatif_model->count_filtered($table_identity, $id_pertanyaan_kualitatif),
			"data" => $data,
		);
		echo json_encode($output);
	}


	public function inovasi_saran($id = NULL)
	{
		$this->data = [];
		$this->data['title'] = "Inovasi Saran";

		return view('data_survey_klien/detail_inovasi_saran', $this->data);
	}

	public function ajax_list_inovasi_saran()
	{
		$this->load->model('InovasiSaran_model');
		$table_identity = $this->uri->segment(3);

		$list = $this->InovasiSaran_model->get_datatables($table_identity);
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $value->nama_lengkap;
			$row[] = $value->saran;

			$data[] = $row;
		}
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->InovasiSaran_model->count_all($table_identity),
			"recordsFiltered" => $this->InovasiSaran_model->count_filtered($table_identity),
			"data" => $data,
		);
		echo json_encode($output);
	}



	public function draft_kuesioner($id = NULL)
	{
		$this->data = [];
		$this->data['title'] = "Draft Kuesioner";

		// get tabel identity
		$this->db->select('*');
		$this->db->from('manage_survey');
		$this->db->where('table_identity', $this->uri->segment(3));
		$manage_survey = $this->db->get()->row();
		$this->data['title_header_survey'] = $manage_survey->title_header_survey;

		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('id=' . $manage_survey->id_user);
		$user = $this->db->get()->row();
		$this->data['user'] = $user;

		$this->data['profil_responden'] = $this->db->query("SELECT * FROM profil_responden_$manage_survey->table_identity")->result();

		$this->data['pertanyaan'] = $this->db->query("SELECT *, (SELECT nama_kategori_unsur_pelayanan FROM kategori_unsur_pelayanan_$manage_survey->table_identity WHERE id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$manage_survey->table_identity.id && nomor_kategori_unsur_pelayanan = 1 ) AS pilihan_1,
        (SELECT nama_kategori_unsur_pelayanan FROM kategori_unsur_pelayanan_$manage_survey->table_identity WHERE id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$manage_survey->table_identity.id && nomor_kategori_unsur_pelayanan = 2 ) AS pilihan_2,
        (SELECT nama_kategori_unsur_pelayanan FROM kategori_unsur_pelayanan_$manage_survey->table_identity WHERE id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$manage_survey->table_identity.id && nomor_kategori_unsur_pelayanan = 3 ) AS pilihan_3,
        (SELECT nama_kategori_unsur_pelayanan FROM kategori_unsur_pelayanan_$manage_survey->table_identity WHERE id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$manage_survey->table_identity.id && nomor_kategori_unsur_pelayanan = 4 ) AS pilihan_4
        FROM pertanyaan_unsur_pelayanan_$manage_survey->table_identity
        JOIN unsur_pelayanan_$manage_survey->table_identity ON pertanyaan_unsur_pelayanan_$manage_survey->table_identity.id_unsur_pelayanan = unsur_pelayanan_$manage_survey->table_identity.id");

		$this->db->select("*");
		$this->db->from("pertanyaan_kualitatif_$manage_survey->table_identity");
		$this->db->where('is_active = 1');
		$this->data['pertanyaan_kualitatif'] = $this->db->get();

		$this->db->select("id_pertanyaan_unsur, nama_kategori_unsur_pelayanan, nomor_kategori_unsur_pelayanan, jenis_pilihan_jawaban");
		$this->db->from("kategori_unsur_pelayanan_$manage_survey->table_identity");
		$this->db->join("pertanyaan_unsur_pelayanan_$manage_survey->table_identity", "kategori_unsur_pelayanan_$manage_survey->table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$manage_survey->table_identity.id");
		$this->db->join("unsur_pelayanan_$manage_survey->table_identity", "pertanyaan_unsur_pelayanan_$manage_survey->table_identity.id_unsur_pelayanan = unsur_pelayanan_$manage_survey->table_identity.id");
		$this->data['jawaban'] = $this->db->get();

		$this->db->select('id_pertanyaan_unsur, alasan_pilih_jawaban');
		$this->db->from('jawaban_pertanyaan_unsur_' . $manage_survey->table_identity);
		$this->db->where('id_responden', $this->uri->segment(5));
		$this->data['alasan'] = $this->db->get();

		return view('data_survey_klien/detail_draft_kuesioner', $this->data);
	}


	public function analisa_survei()
	{

		$this->data = [];
		$this->data['title'] = 'Analisa Survei';

		return view('data_survey_klien/detail_analisa_survei', $this->data);
	}


	public function ajax_list_analisa_survei()
	{
		$this->load->model('AnalisaSurvei_model');
		$table_identity = $this->uri->segment(3);

		$list = $this->AnalisaSurvei_model->get_datatables($table_identity);
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $value->nomor_unsur . '. ' . $value->nama_unsur_pelayanan;
			$row[] = $value->saran_masukan;
			$row[] = $value->rencana_perbaikan;
			$row[] = $value->waktu;
			$row[] = $value->faktor_penyebab;
			$row[] = $value->kegiatan;
			$row[] = $value->penanggung_jawab;

			$data[] = $row;
		}
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->AnalisaSurvei_model->count_all($table_identity),
			"recordsFiltered" => $this->AnalisaSurvei_model->count_filtered($table_identity),
			"data" => $data,
		);
		echo json_encode($output);
	}



	public function e_sertifikat()
	{
		$this->data = [];
		$this->data['title'] = "E-Sertifikat";

		$this->db->select("*, manage_survey.id AS id_manage_survey, manage_survey.table_identity AS table_identity, manage_survey.id_jenis_pelayanan AS id_jenis_pelayanan, DATE_FORMAT(survey_start, '%M') AS survey_mulai, DATE_FORMAT(survey_end, '%M %Y') AS survey_selesai");
		$this->db->from('manage_survey');
		$this->db->where('table_identity', $this->uri->segment(3));
		$manage_survey = $this->db->get()->row();
		$this->data['manage_survey'] = $manage_survey;


		return view('data_survey_klien/detail_e_sertifikat', $this->data);
	}



	public function restore()
	{
		$this->data = [];
		$this->data['title'] = "Restore";

		$this->db->select("*, manage_survey.id AS id_manage_survey, manage_survey.table_identity AS table_identity, manage_survey.id_jenis_pelayanan AS id_jenis_pelayanan, DATE_FORMAT(survey_start, '%M') AS survey_mulai, DATE_FORMAT(survey_end, '%M %Y') AS survey_selesai");
		$this->db->from('manage_survey');
		$this->db->where('table_identity', $this->uri->segment(3));
		$manage_survey = $this->db->get()->row();
		$this->data['manage_survey'] = $manage_survey;


		return view('data_survey_klien/detail_restore', $this->data);
	}


	public function update_restore()
	{
		$this->db->select("*");
		$this->db->from('manage_survey');
		$this->db->where('table_identity', $this->uri->segment(3));
		$manage_survey = $this->db->get()->row();
		$table_identity = $manage_survey->table_identity;
		$atribut_pertanyaan = unserialize($manage_survey->atribut_pertanyaan_survey);

		if ($manage_survey->is_survey_close == 1) {

			$this->db->query("DELETE FROM responden_$table_identity");
			$this->db->query("INSERT INTO responden_$table_identity SELECT * FROM origin_responden_$table_identity");

			$this->db->query("DELETE FROM survey_$table_identity");
			$this->db->query("INSERT INTO survey_$table_identity SELECT * FROM origin_survey_$table_identity");

			$this->db->query("DELETE FROM jawaban_pertanyaan_unsur_$table_identity");
			$this->db->query("INSERT INTO jawaban_pertanyaan_unsur_$table_identity SELECT * FROM origin_jawaban_pertanyaan_unsur_$table_identity");

			if (in_array(3, $atribut_pertanyaan)) {
				$this->db->query("DELETE FROM jawaban_pertanyaan_kualitatif_$table_identity");
				$this->db->query("INSERT INTO jawaban_pertanyaan_kualitatif_$table_identity SELECT * FROM origin_jawaban_pertanyaan_kualitatif_$table_identity");
			}

			if (in_array(2, $atribut_pertanyaan)) {
				$this->db->query("DELETE FROM jawaban_pertanyaan_terbuka_$table_identity");
				$this->db->query("INSERT INTO jawaban_pertanyaan_terbuka_$table_identity SELECT * FROM origin_jawaban_pertanyaan_terbuka_$table_identity");
			}

			if (in_array(1, $atribut_pertanyaan)) {
				$this->db->query("DELETE FROM jawaban_pertanyaan_harapan_$table_identity");
				$this->db->query("INSERT INTO jawaban_pertanyaan_harapan_$table_identity SELECT * FROM origin_jawaban_pertanyaan_harapan_$table_identity");
			}

			$this->db->query("DROP TABLE koreksi_responden_$table_identity");
			$this->db->query("DROP TABLE origin_responden_$table_identity");
			$this->db->query("DROP TABLE koreksi_survey_$table_identity");
			$this->db->query("DROP TABLE origin_survey_$table_identity");
			$this->db->query("DROP TABLE koreksi_jawaban_pertanyaan_unsur_$table_identity");
			$this->db->query("DROP TABLE origin_jawaban_pertanyaan_unsur_$table_identity");
			$this->db->query("DROP TABLE koreksi_jawaban_pertanyaan_kualitatif_$table_identity");
			$this->db->query("DROP TABLE origin_jawaban_pertanyaan_kualitatif_$table_identity");
			$this->db->query("DROP TABLE koreksi_jawaban_pertanyaan_terbuka_$table_identity");
			$this->db->query("DROP TABLE origin_jawaban_pertanyaan_terbuka_$table_identity");
			$this->db->query("DROP TABLE koreksi_jawaban_pertanyaan_harapan_$table_identity");
			$this->db->query("DROP TABLE origin_jawaban_pertanyaan_harapan_$table_identity");

			$object = [
				'is_origin_backup' => NULL,
				'is_koreksi' => NULL,
				'is_survey_close' => NULL
			];
			$this->db->where('id', $manage_survey->id);
			$this->db->update('manage_survey', $object);
		}


		$pesan = 'Data berhasil disimpan';
		$msg = ['sukses' => $pesan];
		echo json_encode($msg);
	}



	public function data_sampah()
	{
		$this->data = [];
		$this->data['title'] = "Data Perolehan Survei";
		$table_identity = $this->uri->segment(3);

		//PANGGIL PROFIL RESPONDEN
		$this->data['profil'] = $this->db->query("SELECT * FROM profil_responden_$table_identity ORDER BY IF(urutan != '',urutan,id) ASC")->result();

		//PANGGIL PROFIL RESPONDEN UNTUK FILTER
		$this->data['profil_responden_filter'] = $this->db->query("SELECT * FROM profil_responden_$table_identity WHERE jenis_isian = 1");

		//LOAD KATEGORI PROFIL RESPONDEN JIKA PILIHAN GANDA
		$this->data['kategori_profil_responden'] = $this->db->get('kategori_profil_responden_' . $table_identity);

		return view('data_survey_klien/detail_data_sampah', $this->data);
	}

	public function ajax_list_data_sampah()
	{
		$this->load->model('DataSampah_model');

		$table_identity = $this->uri->segment(3);

		//PANGGIL PROFIL RESPONDEN
		$profil_responden = $this->db->query("SELECT * FROM profil_responden_$table_identity")->result();

		$list = $this->DataSampah_model->get_datatables($table_identity, $profil_responden);
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			if ($value->is_submit == 1) {
				$status = '<span class="badge badge-primary">Valid</span>';
			} else {
				$status = '<span class="badge badge-danger">Tidak Valid</span>';
			}

			$no++;
			$row = array();
			$row[] = '<div class="checkbox-list"><label class="checkbox"><input type="checkbox" name="restore_list[]" value="' . $value->id_responden . '" class="child"><span></span>' . $no . '</label></div>';

			$row[] = date("H:i:s d-m-Y", strtotime($value->deleted_at));
			$row[] = $status;
			$row[] = $value->nama_lengkap;

			foreach ($profil_responden as $get) {
				$profil = $get->nama_alias;
				$row[] = $value->$profil;
			}
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->DataSampah_model->count_all($table_identity, $profil_responden),
			"recordsFiltered" => $this->DataSampah_model->count_filtered($table_identity, $profil_responden),
			"data" => $data,
		);

		echo json_encode($output);
	}

	public function restore_data_sampah()
	{
		$table_identity = $this->uri->segment(3);
		$manage_survey = $this->db->get_where('manage_survey', ['table_identity' => "$table_identity"])->row();
		$atribut_pertanyaan = unserialize($manage_survey->atribut_pertanyaan_survey);

		if ($this->input->post('restore_list[]') != NULL) {
			$id = implode(", ", $this->input->post('restore_list[]'));

			//PINDAH KE TABEL SAMPAH
			$this->db->query("INSERT INTO responden_$table_identity SELECT * FROM trash_responden_$table_identity WHERE id IN ($id)");
			$this->db->query("INSERT INTO survey_$table_identity SELECT * FROM trash_survey_$table_identity WHERE id_responden IN ($id)");
			$this->db->query("INSERT INTO jawaban_pertanyaan_unsur_$table_identity SELECT * FROM trash_jawaban_pertanyaan_unsur_$table_identity WHERE id_responden IN ($id)");
			if (in_array(1, $atribut_pertanyaan)) {
				$this->db->query("INSERT INTO jawaban_pertanyaan_harapan_$table_identity SELECT * FROM trash_jawaban_pertanyaan_harapan_$table_identity WHERE id_responden IN ($id)");
			}
			if (in_array(2, $atribut_pertanyaan)) {
				$this->db->query("INSERT INTO jawaban_pertanyaan_terbuka_$table_identity SELECT * FROM trash_jawaban_pertanyaan_terbuka_$table_identity WHERE id_responden IN ($id)");
			}
			if (in_array(3, $atribut_pertanyaan)) {
				$this->db->query("INSERT INTO jawaban_pertanyaan_kualitatif_$table_identity SELECT * FROM trash_jawaban_pertanyaan_kualitatif_$table_identity WHERE id_responden IN ($id)");
			}


			$this->db->query("DELETE FROM trash_jawaban_pertanyaan_kualitatif_$table_identity WHERE id_responden IN ($id)");
			$this->db->query("DELETE FROM trash_jawaban_pertanyaan_terbuka_$table_identity WHERE id_responden IN ($id)");
			$this->db->query("DELETE FROM trash_jawaban_pertanyaan_unsur_$table_identity WHERE id_responden IN ($id)");
			$this->db->query("DELETE FROM trash_jawaban_pertanyaan_harapan_$table_identity WHERE id_responden IN ($id)");
			$this->db->query("DELETE FROM trash_survey_$table_identity WHERE id_responden IN ($id)");
			$this->db->query("DELETE FROM trash_responden_$table_identity WHERE id IN ($id)");
		}

		$pesan = 'Data berhasil disimpan';
		$msg = ['sukses' => $pesan];
		echo json_encode($msg);
	}
}

/* End of file DataSurveyKlienController.php */
/* Location: ./application/controllers/DataSurveyKlienController.php */