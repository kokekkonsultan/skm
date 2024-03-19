<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DuplikatSurveiController extends CI_Controller
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
		$this->data['title'] = "Duplikat Survei";

		return view('duplikat_survei/index', $this->data);
	}

	public function proses()
	{
        $this->load->library('uuid');
		// $id_survei = $this->input->post('id_survei');
		// $id_user = $this->input->post('id_user');

		// var_dump($id_survei);
		// var_dump($id_user);

		foreach($this->input->post('id_user') as $row){
			
			$id_survei = implode(", ", $this->input->post('id_survei'));
			foreach($this->db->query("SELECT * FROM manage_survey WHERE id IN ($id_survei)")->result() as $key => $value){

				$object = [
					'uuid' => $this->uuid->v4(),
					'survey_name' => $value->survey_name,
					'organisasi' => $value->organisasi,
					'id_template' => 3,
					'id_user' => $row, //$berlangganan->id_user,
					'survey_start' => $value->survey_start,
					'survey_end' => $value->survey_end,
					'survey_year' => $value->survey_year,
					'description' => '',
					'is_privacy' => 1,
					'slug' => 1,
					'id_sampling' => $value->id_sampling,
					'jumlah_populasi' => $value->jumlah_populasi,
					'deskripsi_tunda' => 'Mohon maaf, survei ditunda dan akan dilanjutkan kembali pada',
					'is_question' => '1',
					'jumlah_sampling' => $value->jumlah_sampling,
					'id_jenis_pelayanan' => $value->id_jenis_pelayanan,
					'created_at' => date("Y/m/d H:i:s"),
					'title_header_survey' => serialize(array("SURVEI KEPUASAN MASYARAKAT", $this->input->post('organisasi'))),
					//'id_berlangganan' => 0, //$berlangganan->id
					'atribut_pertanyaan_survey' => $value->atribut_pertanyaan_survey,
					'skala_likert' => $value->skala_likert,
					'is_saran' => 1,
					'judul_form_saran' => 'Saran / Opini Anda',
					'is_layanan_survei' => 1,
					'is_kategori_layanan_survei' => $value->is_kategori_layanan_survei,
					'is_chart_layanan_survei' => 1,

					'deskripsi_opening_survey' => '<p>Dalam rangka meningkatkan kepuasan masyarakat, Saudara dipercaya menjadi responden pada kegiatan survei ini. Atas kesediaan Saudara memberikan pendapat kami sampaikan terima kasih dan penghargaan sedalam-dalamnya.</p>',
		
					'deskripsi_konfirmasi_survei' => 'Kuesioner Anda sudah diisi, silahkan klik tombol SUBMIT Kuesioner untuk mengakhiri survey.',
		
					'deskripsi_selesai_survei' => 'Terima kasih atas kesediaan Saudara untuk mengisi kuesioner',
		
					'template_email_prospek' => '<p>Kami Tim Survey Kepuasan Masyarakat ${1},</p>
					<p>memohon kepada Bapak/ Ibu, untuk mengisi Kuesioner ${2} dengan link berikut ini ${3}. Mohon diisi sebelum tanggal ${4}. Atas kesedian dan partisipasinya kami ucapkan Terima Kasih.</p>',
		
					'template_whatsapp_prospek' => '<p>Kami Tim Survei Kepuasan Masyarakat ${1}, memohon kepada Bapak/ Ibu, untuk mengisi Kuesioner ${2} dengan link berikut ini ${3}. Mohon diisi sebelum tanggal ${4}. Atas kesedian dan partisipasinya kami ucapkan Terima Kasih.</p>',
		
				];
				$this->db->insert('manage_survey', $object);
				// var_dump($object);
		
				$insert_id = $this->db->insert_id();
				$old_table_identity = $value->table_identity;
				$new_table_identity = "cst" . $insert_id;
				$last_object = [
					'slug' => $value->slug . '-' . $insert_id,
					'table_identity' => $new_table_identity
				];
				$this->db->where('id', $insert_id);
				$this->db->update('manage_survey', $last_object);



				$this->db->query("CREATE TABLE kategori_layanan_survei_$new_table_identity AS SELECT * FROM kategori_layanan_survei_$old_table_identity");
				$this->db->query("CREATE TABLE layanan_survei_$new_table_identity AS SELECT * FROM layanan_survei_$old_table_identity");
				$this->db->query("CREATE TABLE responden_$new_table_identity LIKE responden_$old_table_identity");
				$this->db->query("CREATE TABLE trash_responden_cst$insert_id LIKE responden_$old_table_identity");


				$this->db->query("CREATE TABLE survey_$new_table_identity LIKE survey");
				$this->db->query("CREATE TABLE jawaban_pertanyaan_unsur_$new_table_identity LIKE jawaban_pertanyaan_unsur");
				$this->db->query("CREATE TABLE jawaban_pertanyaan_terbuka_$new_table_identity LIKE jawaban_pertanyaan_terbuka");
				$this->db->query("CREATE TABLE pertanyaan_kualitatif_$new_table_identity LIKE pertanyaan_kualitatif");
				$this->db->query("CREATE TABLE jawaban_pertanyaan_kualitatif_$new_table_identity LIKE jawaban_pertanyaan_kualitatif");
				$this->db->query("CREATE TABLE jawaban_pertanyaan_harapan_$new_table_identity LIKE jawaban_pertanyaan_harapan");
				$this->db->query("CREATE TABLE log_survey_$new_table_identity LIKE log_survey");
				$this->db->query("CREATE TABLE log_report_$new_table_identity LIKE log_report");

				$this->db->query("CREATE TABLE profil_responden_$new_table_identity LIKE profil_responden");
				$this->db->query("CREATE TABLE kategori_profil_responden_$new_table_identity LIKE kategori_profil_responden");

				$this->db->query("CREATE TABLE analisa_$new_table_identity LIKE analisa");
				$this->db->query("CREATE TABLE definisi_skala_$new_table_identity AS SELECT * FROM definisi_skala WHERE skala_likert = $value->skala_likert");

				$this->db->query("CREATE TABLE trash_survey_$new_table_identity LIKE survey");
				$this->db->query("CREATE TABLE trash_jawaban_pertanyaan_unsur_$new_table_identity LIKE jawaban_pertanyaan_unsur");
				$this->db->query("CREATE TABLE trash_jawaban_pertanyaan_terbuka_$new_table_identity LIKE jawaban_pertanyaan_terbuka");
				$this->db->query("CREATE TABLE trash_jawaban_pertanyaan_kualitatif_$new_table_identity LIKE jawaban_pertanyaan_kualitatif");
				$this->db->query("CREATE TABLE trash_jawaban_pertanyaan_harapan_$new_table_identity LIKE jawaban_pertanyaan_harapan");

				 $this->db->query("CREATE TABLE data_prospek_survey_$new_table_identity LIKE data_prospek_survey");

				$this->db->query("INSERT INTO profil_responden_$new_table_identity SELECT * FROM profil_responden_$old_table_identity");
				$this->db->query("INSERT INTO kategori_profil_responden_$new_table_identity SELECT * FROM kategori_profil_responden_$old_table_identity");

				$this->db->query("CREATE TABLE unsur_pelayanan_$new_table_identity LIKE unsur_pelayanan_$old_table_identity");
				$this->db->query("INSERT INTO unsur_pelayanan_$new_table_identity SELECT * FROM unsur_pelayanan_$old_table_identity");

				$this->db->query("CREATE TABLE pertanyaan_unsur_pelayanan_$new_table_identity LIKE pertanyaan_unsur_pelayanan_$old_table_identity");
				$this->db->query("INSERT INTO pertanyaan_unsur_pelayanan_$new_table_identity SELECT * FROM pertanyaan_unsur_pelayanan_$old_table_identity");

				$this->db->query("CREATE TABLE kategori_unsur_pelayanan_$new_table_identity LIKE kategori_unsur_pelayanan_$old_table_identity");
				$this->db->query("INSERT INTO kategori_unsur_pelayanan_$new_table_identity SELECT * FROM kategori_unsur_pelayanan_$old_table_identity");

				$this->db->query("CREATE TABLE nilai_tingkat_kepentingan_$new_table_identity LIKE nilai_tingkat_kepentingan_$old_table_identity");
				$this->db->query("INSERT INTO nilai_tingkat_kepentingan_$new_table_identity SELECT * FROM nilai_tingkat_kepentingan_$old_table_identity");

				$this->db->query("CREATE TABLE pertanyaan_terbuka_$new_table_identity LIKE pertanyaan_terbuka_$old_table_identity");
				$this->db->query("INSERT INTO pertanyaan_terbuka_$new_table_identity SELECT * FROM pertanyaan_terbuka_$old_table_identity");

				$this->db->query("CREATE TABLE perincian_pertanyaan_terbuka_$new_table_identity LIKE perincian_pertanyaan_terbuka_$old_table_identity");
				$this->db->query("INSERT INTO perincian_pertanyaan_terbuka_$new_table_identity SELECT * FROM perincian_pertanyaan_terbuka_$old_table_identity");

				$this->db->query("CREATE TABLE isi_pertanyaan_ganda_$new_table_identity LIKE isi_pertanyaan_ganda_$old_table_identity");
				$this->db->query("INSERT INTO isi_pertanyaan_ganda_$new_table_identity SELECT * FROM isi_pertanyaan_ganda_$old_table_identity");
				

				$this->db->query("
				CREATE TRIGGER log_app_$new_table_identity AFTER INSERT ON responden_$new_table_identity
				FOR EACH ROW BEGIN 
				INSERT INTO log_survey_$new_table_identity(log_value, log_time) VALUES(CONCAT(NEW.uuid, ', sudah mengisi survei'), DATE_ADD(NOW(), INTERVAL 13 HOUR));		
				END");

			}
			
		}

		// redirect(base_url() . 'duplikat-survei');

		$pesan = 'Data berhasil disimpan';
        $msg = ['sukses' => $pesan];
        echo json_encode($msg);
	}

}

/* End of file KlasifikasiSurveyController.php */
/* Location: ./application/controllers/KlasifikasiSurveyController.php */