<?php
defined('BASEPATH') or exit('No direct script access allowed');

class HomeController extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
	}

	public function index()
	{
		// foreach ($this->db->get_where("manage_survey_copy1", array('id_sampling' => 0))->result() as $row) {

		// 	$insert_id = $row->id;
		// 	$tb_survey = 'survey_cst' . $insert_id;
		// 	$tb_responden = 'responden_cst' . $insert_id;
		// 	$tb_jawaban_pertanyaan_unsur = 'jawaban_pertanyaan_unsur_cst' . $insert_id;
		// 	$tb_jawaban_pertanyaan_terbuka = 'jawaban_pertanyaan_terbuka_cst' . $insert_id;
		// 	$tb_pertanyaan_kualitatif = 'pertanyaan_kualitatif_cst' . $insert_id;
		// 	$tb_jawaban_pertanyaan_kualitatif = 'jawaban_pertanyaan_kualitatif_cst' . $insert_id;
		// 	$tb_unsur_pelayanan = 'unsur_pelayanan_cst' . $insert_id;
		// 	$tb_pertanyaan_unsur_pelayanan = 'pertanyaan_unsur_pelayanan_cst' . $insert_id;
		// 	$tb_kategori_unsur_pelayanan = 'kategori_unsur_pelayanan_cst' . $insert_id;
		// 	$tb_pertanyaan_terbuka = 'pertanyaan_terbuka_cst' . $insert_id;
		// 	$tb_perincian_pertanyaan_terbuka = 'perincian_pertanyaan_terbuka_cst' . $insert_id;
		// 	$tb_isi_pertanyaan_ganda = 'isi_pertanyaan_ganda_cst' . $insert_id;
		// 	$tb_jawaban_pertanyaan_harapan = 'jawaban_pertanyaan_harapan_cst' . $insert_id;
		// 	$tb_nilai_tingkat_kepentingan = 'nilai_tingkat_kepentingan_cst' . $insert_id;
		// 	$tb_data_prospek_survey = 'data_prospek_survey_cst' . $insert_id;
		// 	$tb_log_survey = 'log_survey_cst' . $insert_id;
		// 	$tb_profil_responden = 'profil_responden_cst' . $insert_id;
		// 	$tb_kategori_profil_responden = 'kategori_profil_responden_cst' . $insert_id;
		// 	$tb_analisa = 'analisa_cst' . $insert_id;
		// 	$tb_definisi_skala = 'definisi_skala_cst' . $insert_id;
		// 	$log_report = 'log_report_cst' . $insert_id;

		

		// 	$this->db->query("CREATE TABLE $tb_survey LIKE survey");
		// 	$this->db->query("CREATE TABLE $tb_jawaban_pertanyaan_unsur LIKE jawaban_pertanyaan_unsur");
		// 	$this->db->query("CREATE TABLE $tb_jawaban_pertanyaan_terbuka LIKE jawaban_pertanyaan_terbuka");
		// 	$this->db->query("CREATE TABLE $tb_pertanyaan_kualitatif LIKE pertanyaan_kualitatif");
		// 	$this->db->query("CREATE TABLE $tb_jawaban_pertanyaan_kualitatif LIKE jawaban_pertanyaan_kualitatif");
		// 	$this->db->query("CREATE TABLE $tb_jawaban_pertanyaan_harapan LIKE jawaban_pertanyaan_harapan");
		// 	$this->db->query("CREATE TABLE $tb_log_survey LIKE log_survey");
		// 	$this->db->query("CREATE TABLE $log_report LIKE log_report");

		// 	$this->db->query("CREATE TABLE $tb_profil_responden LIKE profil_responden");
		// 	$this->db->query("CREATE TABLE $tb_kategori_profil_responden LIKE kategori_profil_responden");
		// 	$this->db->query("CREATE TABLE $tb_data_prospek_survey LIKE data_prospek_survey;");
		// 	$this->db->query("CREATE TABLE $tb_analisa LIKE analisa");
		// 	$this->db->query("CREATE TABLE $tb_definisi_skala AS SELECT * FROM definisi_skala WHERE skala_likert = 4");


		// 	$this->db->query("CREATE TABLE $tb_unsur_pelayanan LIKE unsur_pelayanan");
		// 	$this->db->query("CREATE TABLE $tb_pertanyaan_unsur_pelayanan LIKE pertanyaan_unsur_pelayanan");
		// 	$this->db->query("CREATE TABLE $tb_kategori_unsur_pelayanan LIKE kategori_unsur_pelayanan");
		// 	$this->db->query("CREATE TABLE $tb_nilai_tingkat_kepentingan LIKE nilai_tingkat_kepentingan");
		// 	$this->db->query("CREATE TABLE $tb_pertanyaan_terbuka LIKE pertanyaan_terbuka");
		// 	$this->db->query("CREATE TABLE $tb_isi_pertanyaan_ganda LIKE isi_pertanyaan_ganda");
		// 	$this->db->query("CREATE TABLE $tb_perincian_pertanyaan_terbuka LIKE perincian_pertanyaan_terbuka");
		// 	$this->db->query("CREATE TABLE kategori_layanan_survei_cst$insert_id AS SELECT * FROM kategori_layanan_survei");
		// 	$this->db->query("CREATE TABLE layanan_survei_cst$insert_id AS SELECT * FROM layanan_survei");


		// 	//BUAT TABEL UNTUK MENAMPUNG DATA RESPONDEN YANG DI HAPUS
		// 	$this->db->query("CREATE TABLE trash_survey_cst$insert_id LIKE survey");
		// 	$this->db->query("CREATE TABLE trash_jawaban_pertanyaan_unsur_cst$insert_id LIKE jawaban_pertanyaan_unsur");
		// 	$this->db->query("CREATE TABLE trash_jawaban_pertanyaan_terbuka_cst$insert_id LIKE jawaban_pertanyaan_terbuka");
		// 	$this->db->query("CREATE TABLE trash_jawaban_pertanyaan_kualitatif_cst$insert_id LIKE jawaban_pertanyaan_kualitatif");
		// 	$this->db->query("CREATE TABLE trash_jawaban_pertanyaan_harapan_cst$insert_id LIKE jawaban_pertanyaan_harapan");

		

		// 	//1
		// 	if($row->survey_name == 'Survei Efektivitas Penyuluhan Perpajakan'){

		// 		$idnt_tabel = 'cst485';

		// 		$this->db->query("CREATE TABLE $tb_responden LIKE responden_$idnt_tabel");
		// 		$this->db->query("CREATE TABLE trash_responden_cst$insert_id LIKE responden_$idnt_tabel");


		// 		$this->db->query("INSERT INTO $tb_profil_responden SELECT * FROM profil_responden_$idnt_tabel");
		// 		$this->db->query("INSERT INTO $tb_kategori_profil_responden SELECT * FROM kategori_profil_responden_$idnt_tabel");
		// 		$this->db->query("INSERT INTO $tb_unsur_pelayanan SELECT * FROM unsur_pelayanan_$idnt_tabel");
		// 		$this->db->query("INSERT INTO $tb_pertanyaan_unsur_pelayanan SELECT * FROM pertanyaan_unsur_pelayanan_$idnt_tabel");
		// 		$this->db->query("INSERT INTO $tb_kategori_unsur_pelayanan SELECT * FROM kategori_unsur_pelayanan_$idnt_tabel");
		// 		$this->db->query("INSERT INTO $tb_nilai_tingkat_kepentingan SELECT * FROM nilai_tingkat_kepentingan_$idnt_tabel");
		// 		$this->db->query("INSERT INTO $tb_pertanyaan_terbuka SELECT * FROM pertanyaan_terbuka_$idnt_tabel");
		// 		$this->db->query("INSERT INTO $tb_perincian_pertanyaan_terbuka SELECT * FROM perincian_pertanyaan_terbuka_$idnt_tabel");
		// 		$this->db->query("INSERT INTO $tb_isi_pertanyaan_ganda SELECT * FROM isi_pertanyaan_ganda_$idnt_tabel");
		// 		$this->db->query("INSERT INTO layanan_survei_cst$insert_id SELECT * FROM layanan_survei_$idnt_tabel");



		// 	//2
		// 	} elseif($row->survey_name == 'Survei Efektivitas Kehumasan Perpajakan') {
		// 		$idnt_tabel = 'cst483';


		// 		$this->db->query("CREATE TABLE $tb_responden LIKE responden_$idnt_tabel");
		// 		$this->db->query("CREATE TABLE trash_responden_cst$insert_id LIKE responden_$idnt_tabel");


		// 		$this->db->query("INSERT INTO $tb_profil_responden SELECT * FROM profil_responden_$idnt_tabel");
		// 		$this->db->query("INSERT INTO $tb_kategori_profil_responden SELECT * FROM kategori_profil_responden_$idnt_tabel");
		// 		$this->db->query("INSERT INTO $tb_unsur_pelayanan SELECT * FROM unsur_pelayanan_$idnt_tabel");
		// 		$this->db->query("INSERT INTO $tb_pertanyaan_unsur_pelayanan SELECT * FROM pertanyaan_unsur_pelayanan_$idnt_tabel");
		// 		$this->db->query("INSERT INTO $tb_kategori_unsur_pelayanan SELECT * FROM kategori_unsur_pelayanan_$idnt_tabel");
		// 		$this->db->query("INSERT INTO $tb_nilai_tingkat_kepentingan SELECT * FROM nilai_tingkat_kepentingan_$idnt_tabel");
		// 		$this->db->query("INSERT INTO $tb_pertanyaan_terbuka SELECT * FROM pertanyaan_terbuka_$idnt_tabel");
		// 		$this->db->query("INSERT INTO $tb_perincian_pertanyaan_terbuka SELECT * FROM perincian_pertanyaan_terbuka_$idnt_tabel");
		// 		$this->db->query("INSERT INTO $tb_isi_pertanyaan_ganda SELECT * FROM isi_pertanyaan_ganda_$idnt_tabel");
		// 		$this->db->query("INSERT INTO layanan_survei_cst$insert_id SELECT * FROM layanan_survei_$idnt_tabel");


		// 	//3
		// 	} elseif($row->survey_name == 'Survei Kepuasan Pengguna Layanan DJP Tatap Muka') {

		// 		$idnt_tabel = 'cst482';

		// 		$this->db->query("CREATE TABLE $tb_responden LIKE responden_$idnt_tabel");
		// 		$this->db->query("CREATE TABLE trash_responden_cst$insert_id LIKE responden_$idnt_tabel");


		// 		$this->db->query("INSERT INTO $tb_profil_responden SELECT * FROM profil_responden_$idnt_tabel");
		// 		$this->db->query("INSERT INTO $tb_kategori_profil_responden SELECT * FROM kategori_profil_responden_$idnt_tabel");
		// 		$this->db->query("INSERT INTO $tb_unsur_pelayanan SELECT * FROM unsur_pelayanan_$idnt_tabel");
		// 		$this->db->query("INSERT INTO $tb_pertanyaan_unsur_pelayanan SELECT * FROM pertanyaan_unsur_pelayanan_$idnt_tabel");
		// 		$this->db->query("INSERT INTO $tb_kategori_unsur_pelayanan SELECT * FROM kategori_unsur_pelayanan_$idnt_tabel");
		// 		$this->db->query("INSERT INTO $tb_nilai_tingkat_kepentingan SELECT * FROM nilai_tingkat_kepentingan_$idnt_tabel");
		// 		$this->db->query("INSERT INTO $tb_pertanyaan_terbuka SELECT * FROM pertanyaan_terbuka_$idnt_tabel");
		// 		$this->db->query("INSERT INTO $tb_perincian_pertanyaan_terbuka SELECT * FROM perincian_pertanyaan_terbuka_$idnt_tabel");
		// 		$this->db->query("INSERT INTO $tb_isi_pertanyaan_ganda SELECT * FROM isi_pertanyaan_ganda_$idnt_tabel");
		// 		$this->db->query("INSERT INTO layanan_survei_cst$insert_id SELECT * FROM layanan_survei_$idnt_tabel");


		// 	//4
		// 	} else {

		// 		$idnt_tabel = 'cst468';

		// 		$this->db->query("CREATE TABLE $tb_responden LIKE responden_$idnt_tabel");
		// 		$this->db->query("CREATE TABLE trash_responden_cst$insert_id LIKE responden_$idnt_tabel");


		// 		$this->db->query("INSERT INTO $tb_profil_responden SELECT * FROM profil_responden_$idnt_tabel");
		// 		$this->db->query("INSERT INTO $tb_kategori_profil_responden SELECT * FROM kategori_profil_responden_$idnt_tabel");
		// 		$this->db->query("INSERT INTO $tb_unsur_pelayanan SELECT * FROM unsur_pelayanan_$idnt_tabel");
		// 		$this->db->query("INSERT INTO $tb_pertanyaan_unsur_pelayanan SELECT * FROM pertanyaan_unsur_pelayanan_$idnt_tabel");
		// 		$this->db->query("INSERT INTO $tb_kategori_unsur_pelayanan SELECT * FROM kategori_unsur_pelayanan_$idnt_tabel");
		// 		$this->db->query("INSERT INTO $tb_nilai_tingkat_kepentingan SELECT * FROM nilai_tingkat_kepentingan_$idnt_tabel");
		// 		$this->db->query("INSERT INTO $tb_pertanyaan_terbuka SELECT * FROM pertanyaan_terbuka_$idnt_tabel");
		// 		$this->db->query("INSERT INTO $tb_perincian_pertanyaan_terbuka SELECT * FROM perincian_pertanyaan_terbuka_$idnt_tabel");
		// 		$this->db->query("INSERT INTO $tb_isi_pertanyaan_ganda SELECT * FROM isi_pertanyaan_ganda_$idnt_tabel");
		// 		$this->db->query("INSERT INTO layanan_survei_cst$insert_id SELECT * FROM layanan_survei_$idnt_tabel");

		// 	}

		// 	$this->db->query("
		// 	CREATE TRIGGER log_app_cst$insert_id AFTER INSERT ON responden_cst$insert_id
		// 	FOR EACH ROW BEGIN 
		// 	INSERT INTO log_survey_cst$insert_id(log_value, log_time) VALUES(CONCAT(NEW.uuid, ', sudah mengisi survei'), DATE_ADD(NOW(), INTERVAL 13 HOUR));		
		// 	END");


			
		// }
	}

	public function index2()
	{
		$this->data = [];
		$this->data['title'] = 'Home';
		$this->data['banner'] = $this->db->get_where('banner', ['is_show' => '1']);

		$this->data['home_config'] = $this->db->query("
			SELECT
			( SELECT constant_value FROM website_constant WHERE id = 1) AS website_title,
			( SELECT constant_value FROM website_constant WHERE id = 2) AS website_description,
			( SELECT constant_value FROM website_constant WHERE id = 3) AS website_object_title,
			( SELECT constant_value FROM website_constant WHERE id = 4) AS website_object_1,
			( SELECT constant_value FROM website_constant WHERE id = 5) AS website_object_2,
			( SELECT constant_value FROM website_constant WHERE id = 6) AS website_object_3,
			( SELECT constant_value FROM website_constant WHERE id = 7) AS website_object_4
			FROM
			website_constant LIMIT 1
			")->row();

		return view('home/index', $this->data);
	}

	public function cari()
	{
		$this->data = [];
		$this->data['title'] = 'Search';

		$keyword = $this->input->post('keyword');

		$query = $this->db->query("SELECT uuid FROM manage_survey WHERE nomor_sertifikat = '$keyword'")->row();
		if ($query == NULL) {
			echo json_encode(array("statusCode" => 500));
		} else if ($query->uuid != NULL) {
			echo json_encode($query);
		}
	}

	public function validasi_sertifikat()
	{
		$this->data = [];
		$this->data['title'] = 'Validasi Sertifikat';
		$this->load->library('ion_auth');
		$this->data['data_login'] = $this->ion_auth->logged_in();

		$uuid = $this->uri->segment(2);

		$this->db->select("*, users.id AS id_user, DATE_FORMAT(survey_start, '%d-%m-%Y') AS survey_mulai, DATE_FORMAT(survey_end, '%d-%m-%Y') AS survey_selesai, manage_survey.slug AS slug_manage_survey");
		$this->db->from('manage_survey');
		
		$this->db->join('users', 'manage_survey.id_user =  users.id', 'left');
		$this->db->join('jenis_pelayanan', 'manage_survey.id_jenis_pelayanan =  jenis_pelayanan.id', 'left');
		$this->db->join('klasifikasi_survei', 'klasifikasi_survei.id =  jenis_pelayanan.id_klasifikasi_survei', 'left');
		$this->db->join('sampling', 'manage_survey.id_sampling =  sampling.id');
		$this->db->where("manage_survey.uuid = '$uuid'");
		$manage_survey = $this->db->get()->row();
		$this->data['manage_survey'] = $manage_survey;


		//PENDEFINISIAN SKALA LIKERT
		$skala_likert = 100 / ($manage_survey->skala_likert == 5 ? 5 : 4);
		$this->data['definisi_skala'] = $this->db->query("SELECT * FROM definisi_skala_$manage_survey->table_identity ORDER BY id DESC");


		//RATA-RATA BOBOT
		$this->db->select("nama_unsur_pelayanan, IF(id_parent = 0,unsur_pelayanan_$manage_survey->table_identity.id, unsur_pelayanan_$manage_survey->table_identity.id_parent) AS id_sub, (SUM(skor_jawaban)/COUNT(DISTINCT survey_$manage_survey->table_identity.id_responden)) AS rata_rata,  (COUNT(id_parent)/COUNT(DISTINCT survey_$manage_survey->table_identity.id_responden)) AS colspan, ((SUM(skor_jawaban)/COUNT(DISTINCT survey_$manage_survey->table_identity.id_responden))/(COUNT(id_parent)/COUNT(DISTINCT survey_$manage_survey->table_identity.id_responden))) AS nilai, (((SUM(skor_jawaban)/COUNT(DISTINCT survey_$manage_survey->table_identity.id_responden))/(COUNT(id_parent)/COUNT(DISTINCT survey_$manage_survey->table_identity.id_responden)))/(SELECT COUNT(id) FROM unsur_pelayanan_$manage_survey->table_identity WHERE id_parent = 0)) AS rata_rata_bobot");
		$this->db->from('jawaban_pertanyaan_unsur_' . $manage_survey->table_identity);
		$this->db->join("pertanyaan_unsur_pelayanan_$manage_survey->table_identity", "jawaban_pertanyaan_unsur_$manage_survey->table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$manage_survey->table_identity.id");
		$this->db->join("unsur_pelayanan_$manage_survey->table_identity", "pertanyaan_unsur_pelayanan_$manage_survey->table_identity.id_unsur_pelayanan = unsur_pelayanan_$manage_survey->table_identity.id");
		$this->db->join("survey_$manage_survey->table_identity", "jawaban_pertanyaan_unsur_$manage_survey->table_identity.id_responden = survey_$manage_survey->table_identity.id_responden");
		$this->db->where("survey_$manage_survey->table_identity.is_submit = 1");
		$this->db->group_by('id_sub');
		$rata_rata_bobot = $this->db->get();

		foreach ($rata_rata_bobot->result() as $rata_rata_bobot) {
			$nilai_bobot[] = $rata_rata_bobot->rata_rata_bobot;
			$nilai_tertimbang = array_sum($nilai_bobot);
			$this->data['ikm'] = ROUND($nilai_tertimbang * $skala_likert, 10);
		}

		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('id = ', $manage_survey->id_user);
		$this->data['user'] = $this->db->get()->row();

		//TAMPILKAN PROFIL RESPONDEN
		$this->data['profil'] = $this->db->query("SELECT *, UPPER(nama_profil_responden) AS nama_profil FROM profil_responden_$manage_survey->table_identity WHERE jenis_isian = 1");

		//JUMLAH KUISIONER
		$this->db->select('COUNT(id) AS id');
		$this->db->from('survey_' . $manage_survey->table_identity);
		$this->db->where("is_submit = 1");
		$this->data['jumlah_kuisioner'] = $this->db->get()->row()->id;

		return view('home/validasi_sertifikat', $this->data);
	}

	public function about()
	{
		$this->data = [];
		$this->data['title'] = 'About';

		return view('home/about', $this->data);
	}

	public function team()
	{
		$this->data = [];
		$this->data['title'] = 'Team';

		return view('home/team', $this->data);
	}

	public function contact()
	{
		$this->data = [];
		$this->data['title'] = 'Contact';

		return view('home/contact', $this->data);
	}

	public function privacy()
	{
		$this->data = [];
		$this->data['title'] = 'Privacy';

		return view('home/privacy', $this->data);
	}

	public function legal()
	{
		$this->data = [];
		$this->data['title'] = 'Legal';

		return view('home/legal', $this->data);
	}
}

/* End of file HomeController.php */
/* Location: ./application/controllers/HomeController.php */
