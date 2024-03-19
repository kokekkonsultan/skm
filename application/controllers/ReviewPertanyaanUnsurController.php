<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ReviewPertanyaanUnsurController extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->library('ion_auth');

		if (!$this->ion_auth->logged_in()) {
			$this->session->set_flashdata('message_warning', 'You must be logged in to access this page');
			redirect('auth', 'refresh');
		}
	}

	public function index()
	{
		$this->data = [];
		$this->data['title'] = 'Review Pertanyaan';

		$this->load->library('table');

		$template = array(
			'table_open'            => '<table class="table table-bordered table-hover">',
			'table_close'           => '</table>'
		);

		$this->table->set_template($template);

		$this->table->set_heading('NO', 'JENIS PELAYANAN', '');

		$this->db->select('*, jenis_pelayanan.id AS id_jenis_pelayanan');
		$this->db->from('jenis_pelayanan');
		$this->db->join('klasifikasi_survei', 'klasifikasi_survei.id = jenis_pelayanan.id_klasifikasi_survei');
		$get_data = $this->db->get();

		$no = 1;
		foreach ($get_data->result() as $value) {
			$this->table->add_row(
				$no++,
				$value->nama_klasifikasi_survei . ' - ' . $value->nama_jenis_pelayanan_responden,
				anchor(base_url() . 'review-pertanyaan-unsur/detail-unsur/' . $value->id_jenis_pelayanan, '<i class="far fa-file"></i> Detail Pertanyaan', ['class' => 'btn btn-light-primary font-weight-bold', 'target' => '_blank'])
				// anchor(base_url() . 'review-pertanyaan-unsur/detail-terbuka/' . $value->id_jenis_pelayanan, '<i class="far fa-file"></i> Detail Pertanyaan Terbuka', ['class' => 'btn btn-light-primary font-weight-bold', 'target' => '_blank'])
			);
		}

		$this->data['table'] = $this->table->generate();

		return view('review_pertanyaan_unsur/index', $this->data);
	}

	public function detail_unsur($id = NULL)
	{
		$this->data = [];
		$this->data['title'] = 'Detail Pertanyaan Unsur';

		// $id_unsur = $this->uri->segment(3);

		//PERTANYAAN UNSUR
		$query = $this->db->query("SELECT pertanyaan_unsur_pelayanan .id_unsur_pelayanan AS id_unsur_pelayanan, pertanyaan_unsur_pelayanan .id AS id_pertanyaan_unsur, isi_pertanyaan_unsur, unsur_pelayanan .nomor_unsur AS nomor, SUBSTRING(nomor_unsur, 2, 4) AS nomor_harapan, nama_unsur_pelayanan
		FROM pertanyaan_unsur_pelayanan 
		JOIN unsur_pelayanan  ON unsur_pelayanan .id = pertanyaan_unsur_pelayanan .id_unsur_pelayanan
		WHERE id_jenis_pelayanan = $id
		ORDER BY pertanyaan_unsur_pelayanan .id ASC");
		$this->data['pertanyaan_unsur'] = $query;
		// var_dump($this->data['pertanyaan_unsur']->result());

		//JAWABAN PERTANYAAN UNSUR
		$this->data['jawaban_pertanyaan_unsur'] = $this->db->query("SELECT *
		FROM kategori_unsur_pelayanan 
		JOIN unsur_pelayanan  ON kategori_unsur_pelayanan .id_unsur_pelayanan = unsur_pelayanan .id
		WHERE id_jenis_pelayanan = $id");

		//PERTANYAAM TERBUKA
		$this->data['pertanyaan_terbuka'] = $this->db->query("SELECT DISTINCT IF(dengan_isian_lainnya = 1,'Lainnya',null) AS lainnya,
		perincian_pertanyaan_terbuka.id AS id_perincian_pertanyaan_terbuka, pertanyaan_terbuka.id_unsur_pelayanan AS id_unsur_pelayanan,  isi_pertanyaan_terbuka, nomor_pertanyaan_terbuka, id_jenis_pilihan_jawaban, nama_pertanyaan_terbuka
		FROM pertanyaan_terbuka
		JOIN unsur_pelayanan ON pertanyaan_terbuka.id_unsur_pelayanan = unsur_pelayanan.id
		JOIN perincian_pertanyaan_terbuka  ON pertanyaan_terbuka .id = perincian_pertanyaan_terbuka .id_pertanyaan_terbuka
		LEFT JOIN isi_pertanyaan_ganda  ON perincian_pertanyaan_terbuka .id = isi_pertanyaan_ganda .id_perincian_pertanyaan_terbuka
		WHERE id_jenis_pelayanan = $id
		ORDER BY pertanyaan_terbuka.id ASC
		");
		// var_dump($this->data['pertanyaan_terbuka']->result());

		//JAWABAN PERTANYAAN TERBUKA
		$this->data['jawaban_pertanyaan_terbuka'] = $this->db->query("SELECT perincian_pertanyaan_terbuka.id AS id_perincian_pertanyaan_terbuka, isi_pertanyaan_ganda.pertanyaan_ganda AS pertanyaan_ganda
		FROM isi_pertanyaan_ganda 
		JOIN perincian_pertanyaan_terbuka  ON isi_pertanyaan_ganda .id_perincian_pertanyaan_terbuka = perincian_pertanyaan_terbuka .id
		JOIN pertanyaan_terbuka ON perincian_pertanyaan_terbuka.id_pertanyaan_terbuka = pertanyaan_terbuka.id
		JOIN unsur_pelayanan ON pertanyaan_terbuka.id_unsur_pelayanan = unsur_pelayanan.id
		WHERE id_jenis_pelayanan = $id");

		// if ($query->num_rows() > 0) {
		// 	$this->data['query'] = $query;
		// } else {
		// 	echo 'Tidak ada pertanyaan atau pertanyaan belum diinput !';
		// 	exit();
		// }

		return view('review_pertanyaan_unsur/form_detail_pertanyaan_unsur', $this->data);
	}

	// public function detail_terbuka($id = NULL)
	// {
	// 	$this->data = [];
	// 	$this->data['title'] = 'Detail Pertanyaan Terbuka';

	// 	$query = $this->db->query("
	// 		SELECT perincian_pertanyaan_terbuka.id AS id_perincian_pertanyaan_terbuka, pertanyaan_terbuka.nama_pertanyaan_terbuka, perincian_pertanyaan_terbuka.isi_pertanyaan_terbuka

	// 		FROM pertanyaan_terbuka
	// 		JOIN unsur_pelayanan ON pertanyaan_terbuka.id_unsur_pelayanan = unsur_pelayanan.id
	// 		JOIN jenis_pelayanan ON unsur_pelayanan.id_jenis_pelayanan = jenis_pelayanan.id
	// 		JOIN perincian_pertanyaan_terbuka ON pertanyaan_terbuka.id = perincian_pertanyaan_terbuka.id_pertanyaan_terbuka
	// 		WHERE jenis_pelayanan.id = $id
	// 		ORDER BY pertanyaan_terbuka.id ASC
	// 	");

	// 	$data = $this->db->query("
	// 		SELECT id_perincian_pertanyaan_terbuka, pertanyaan_ganda, IF(isi_pertanyaan_ganda.dengan_isian_lainnya = 1,'Lainnya',null) AS lainnya
	// 		FROM isi_pertanyaan_ganda
	// 		JOIN perincian_pertanyaan_terbuka ON isi_pertanyaan_ganda.id_perincian_pertanyaan_terbuka = perincian_pertanyaan_terbuka.id
	// 		JOIN pertanyaan_terbuka ON perincian_pertanyaan_terbuka.id_pertanyaan_terbuka = pertanyaan_terbuka.id
	// 		JOIN unsur_pelayanan ON pertanyaan_terbuka.id_unsur_pelayanan = unsur_pelayanan.id
	// 		JOIN jenis_pelayanan ON unsur_pelayanan.id_jenis_pelayanan = jenis_pelayanan.id
	// 		WHERE jenis_pelayanan.id = $id
	// 	");
	// 	$this->data['data'] = $data;

	// 	$lainnya = $this->db->query("
	// 		SELECT DISTINCT id_perincian_pertanyaan_terbuka, IF(isi_pertanyaan_ganda.dengan_isian_lainnya = 1,'Lainnya',null) AS lainnya
	// 		FROM isi_pertanyaan_ganda
	// 		JOIN perincian_pertanyaan_terbuka ON isi_pertanyaan_ganda.id_perincian_pertanyaan_terbuka = perincian_pertanyaan_terbuka.id
	// 		JOIN pertanyaan_terbuka ON perincian_pertanyaan_terbuka.id_pertanyaan_terbuka = pertanyaan_terbuka.id
	// 		JOIN unsur_pelayanan ON pertanyaan_terbuka.id_unsur_pelayanan = unsur_pelayanan.id
	// 		JOIN jenis_pelayanan ON unsur_pelayanan.id_jenis_pelayanan = jenis_pelayanan.id
	// 		WHERE jenis_pelayanan.id = $id
	// 	");
	// 	$this->data['lainnya'] = $lainnya;
	// 	// var_dump($this->data['data']->result());

	// 	if ($query->num_rows() > 0) {
	// 		$this->data['query'] = $query;
	// 	} else {
	// 		echo 'Tidak ada pertanyaan atau pertanyaan belum diinput !';
	// 		exit();
	// 	}

	// 	return view('review_pertanyaan_unsur/form_detail_pertanyaan_terbuka', $this->data);
	// }
}

/* End of file ReviewPertanyaanUnsurController.php */
/* Location: ./application/controllers/ReviewPertanyaanUnsurController.php */