<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TemplatePertanyaanController extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		if (!$this->ion_auth->logged_in()) {
			$this->session->set_flashdata('message_warning', 'You must be an admin to view this page');
			redirect('auth', 'refresh');
		}
	}

	public function index()
	{
	}

	public function proses($id1 = NULL, $id2 = NULL)
	{
		$this->data = [];
		$this->data['title'] = "Template Pertanyaan";

		$this->data['profiles'] = $this->_get_data_profile($id1, $id2);
		$id_jenis_pelayanan = $this->data['profiles']->id_jenis_pelayanan;

		return view('template_pertanyaan/index', $this->data);
	}

	public function _get_data_profile($id1, $id2)
	{
		$this->db->select('users.username, manage_survey.survey_name, manage_survey.slug, manage_survey.description, manage_survey.is_privacy, manage_survey.table_identity, manage_survey.id_jenis_pelayanan, manage_survey.survey_start, manage_survey.survey_end, manage_survey.kuesioner_name, manage_survey.id AS id_manage_survey, manage_survey.logo_survey, jenis_pelayanan.nama_jenis_pelayanan_responden, klasifikasi_survei.nama_klasifikasi_survei, klasifikasi_survei.id AS id_klasifikasi_survei');
		$this->db->from('users');
		$this->db->join('manage_survey', 'manage_survey.id_user = users.id');
		$this->db->join('jenis_pelayanan', 'jenis_pelayanan.id = manage_survey.id_jenis_pelayanan');
		$this->db->join('klasifikasi_survei', 'klasifikasi_survei.id = jenis_pelayanan.id_klasifikasi_survei');
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

	public function get_detail()
	{

		$id = $this->input->post('id');

		if (empty($id)) {
			redirect(base_url(), 'refresh');
		}

		$query = $this->db->query("
SELECT 
unsur_pelayanan.nama_unsur_pelayanan,
pertanyaan_unsur_pelayanan.id AS id_pertanyaan_unsur_pelayanan,
unsur_pelayanan.id AS id_unsur_pelayanan,
pertanyaan_unsur_pelayanan.isi_pertanyaan_unsur,
unsur_pelayanan.jumlah_pilihan_jawaban,

( SELECT kategori_unsur_pelayanan.nama_kategori_unsur_pelayanan FROM kategori_unsur_pelayanan WHERE kategori_unsur_pelayanan.nomor_kategori_unsur_pelayanan = 1 AND kategori_unsur_pelayanan.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan.id ) AS pilihan_1,

( SELECT kategori_unsur_pelayanan.nama_kategori_unsur_pelayanan FROM kategori_unsur_pelayanan WHERE kategori_unsur_pelayanan.nomor_kategori_unsur_pelayanan = 2 AND kategori_unsur_pelayanan.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan.id ) AS pilihan_2,

( SELECT kategori_unsur_pelayanan.nama_kategori_unsur_pelayanan FROM kategori_unsur_pelayanan WHERE kategori_unsur_pelayanan.nomor_kategori_unsur_pelayanan = 3 AND kategori_unsur_pelayanan.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan.id ) AS pilihan_3,

( SELECT kategori_unsur_pelayanan.nama_kategori_unsur_pelayanan FROM kategori_unsur_pelayanan WHERE kategori_unsur_pelayanan.nomor_kategori_unsur_pelayanan = 4 AND kategori_unsur_pelayanan.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan.id ) AS pilihan_4

FROM pertanyaan_unsur_pelayanan
JOIN unsur_pelayanan ON unsur_pelayanan.id = pertanyaan_unsur_pelayanan.id_unsur_pelayanan
JOIN jenis_pelayanan ON jenis_pelayanan.id = unsur_pelayanan.id_jenis_pelayanan
WHERE jenis_pelayanan.id = $id
ORDER BY pertanyaan_unsur_pelayanan.id ASC
");


		$this->load->library('table');

		$template = array(
			'table_open'            => '<table class="table table-hover">',
			'table_close'           => '</table>'
		);

		$this->table->set_template($template);

		$this->table->set_heading('No', 'Pertanyaan', 'Pilihan Jawaban');

		$no = 1;
		foreach ($query->result() as $value) {

			$p_jawaban = '
				<label>
                    <input type="radio" name="opsi_' . $no . '"> ' . $value->pilihan_1 . '
                </label>';

			if ($value->jumlah_pilihan_jawaban == "Custom") {
				$p_jawaban .= '
                <label>
                    <input type="radio" name="opsi_' . $no . '"> ' . $value->pilihan_2 . '
                </label>
                <label>
                    <input type="radio" name="opsi_' . $no . '"> ' . $value->pilihan_3 . '
                </label>';
			}
			$p_jawaban .= '<label>
                    <input type="radio" name="opsi_' . $no . '"> ' . $value->pilihan_4 . '
                </label>
			';

			$this->table->add_row(
				$no++,
				$value->nama_unsur_pelayanan . '<br><b>' . $value->isi_pertanyaan_unsur . '</b>',
				$p_jawaban
			);
		}


		if ($query->num_rows() > 0) {
			echo $this->table->generate();
			echo '<br>';
			echo anchor('url', '<i class="fas fa-hand-point-up"></i> Pilih Pertanyaan', ['class' => 'btn btn-light-primary btn-block font-weight-bold mt-5']);
		} else {
			echo '<div class="font-weight-bold text-center">Template pertanyaan belum diisi secara lengkap</div>';
		}
	}
}

/* End of file TemplatePertanyaanController.php */
/* Location: ./application/controllers/TemplatePertanyaanController.php */