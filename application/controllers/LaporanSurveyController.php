<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Writer\Word2007;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\Element\Chart;
use PhpOffice\PhpWord\Element\Field;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\SimpleType\TblWidth;


class LaporanSurveyController extends CI_Controller
{

    function  __construct()
    {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message_warning', 'You must be an admin to view this page');
            redirect('auth', 'refresh');
        }
        $this->load->helper('url');
    }

    public function index($id1, $id2)
    {
        $this->data = [];
        $this->data['title'] = "Laporan Survey";
        $this->data['profiles'] = $this->_get_data_profile($id1, $id2);

        $this->data['query'] = $this->db->get_where('manage_survey', array('slug' => $id2))->row();
        $this->data['atribut_pertanyaan'] =  unserialize($this->data['query']->atribut_pertanyaan_survey);
        $table_identity = $this->data['query']->table_identity;

        $cek_survey = $this->db->get_where("survey_$table_identity", array('is_submit' => 1))->num_rows();

        if (date("Y-m-d") < $this->data['query']->survey_end) {
            $this->data['pesan'] = 'Halaman ini hanya bisa dikelola jika periode survei sudah diselesai atau survei sudah ditutup.';
            return view('not_questions/index', $this->data);
        }

        if ($cek_survey == 0) {
            $this->data['pesan'] = 'survei belum dimulai atau belum ada responden !';
            return view('not_questions/index', $this->data);
        }

        if ($this->data['query']->atribut_kuadran != null) {
            $atribut_kuadran = unserialize($this->data['query']->atribut_kuadran);
            $this->data['nama_file'] = $atribut_kuadran[0];
            $this->data['tgl_convert'] = $atribut_kuadran[1];
        } else {
            $this->data['nama_file'] = '';
            $this->data['tgl_convert'] = '';
        }

        // $this->_get_kuadran($table_identity);

        return view('laporan_survey/index', $this->data);
    }


    public function cetak($id1, $id2)
    {
        $this->data = [];
        $this->data['title'] = "Laporan";
        $this->data['profiles'] = $this->_get_data_profile($id1, $id2);

        $this->data['manage_survey'] = $this->db->get_where('manage_survey', array('slug' => $id2))->row();
        $table_identity = $this->data['manage_survey']->table_identity;
        $this->data['table_identity'] = $this->data['manage_survey']->table_identity;
        $this->data['atribut_pertanyaan'] =  unserialize($this->data['manage_survey']->atribut_pertanyaan_survey);

        // //PROFIL RESPONDEN
        // $this->data['profil_responden'] = $this->db->query("SELECT * FROM profil_responden_$table_identity WHERE jenis_isian = 1");

        // //PENDEFINISIAN SKALA LIKERT
        // $this->data['skala_likert'] = 100 / ($this->data['manage_survey']->skala_likert == 5 ? 5 : 4);
        // $this->data['definisi_skala'] = $this->db->query("SELECT * FROM definisi_skala_$table_identity ORDER BY id DESC");

        // //SARAN
        // $this->data['saran_res'] = $this->db->query("SELECT * FROM responden_$table_identity JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id_responden WHERE is_active = 1 && is_submit = 1 && saran != ''");

        // $this->data['profil_urutan'] = $this->db->query("SELECT GROUP_CONCAT(nama_profil_responden) AS nama FROM profil_responden_$table_identity")->row()->nama;


        // //ANALISA
        // $this->db->select("*");
        // $this->db->from("analisa_$table_identity");
        // $this->db->join("unsur_pelayanan_$table_identity", "unsur_pelayanan_$table_identity.id = analisa_$table_identity.id_unsur_pelayanan");
        // $this->data['analisa'] = $this->db->get();


        // $jawaban_ganda = $this->db->query("SELECT *,
        //     (SELECT COUNT(*) FROM survey_$table_identity
        //     JOIN jawaban_pertanyaan_terbuka_$table_identity ON survey_$table_identity.id_responden = jawaban_pertanyaan_terbuka_$table_identity.id_responden
        //     WHERE survey_$table_identity.is_submit = 1 && jawaban_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka = perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka && jawaban_pertanyaan_terbuka_$table_identity.jawaban = isi_pertanyaan_ganda_$table_identity.pertanyaan_ganda) AS perolehan,

        //     (SELECT COUNT(*) FROM survey_$table_identity WHERE is_submit = 1) AS jumlah_survei

        //     FROM isi_pertanyaan_ganda_$table_identity
        //     JOIN perincian_pertanyaan_terbuka_$table_identity ON isi_pertanyaan_ganda_$table_identity.id_perincian_pertanyaan_terbuka = perincian_pertanyaan_terbuka_$table_identity.id
        //     WHERE perincian_pertanyaan_terbuka_$table_identity.id_jenis_pilihan_jawaban = 1");

        // $jawaban_isian = $this->db->query("SELECT *
        //     FROM jawaban_pertanyaan_terbuka_$table_identity
        //     JOIN pertanyaan_terbuka_$table_identity ON pertanyaan_terbuka_$table_identity.id = jawaban_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka
        //     JOIN perincian_pertanyaan_terbuka_$table_identity ON pertanyaan_terbuka_$table_identity.id = perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka
        //     JOIN survey_$table_identity ON jawaban_pertanyaan_terbuka_$table_identity.id_responden = survey_$table_identity.id_responden
        //     WHERE id_jenis_pilihan_jawaban = 2 && survey_$table_identity.is_submit = 1");




        // $this->_get_data_laporan($table_identity, $this->data['skala_likert']);
        // $this->_get_unsur_tertinggi_terendah($table_identity);
        // $this->_get_chart_unsur($this->data['manage_survey'], $this->data['manage_survey']->skala_likert, $this->data['definisi_skala'], $this->data['atribut_pertanyaan']);
        // $this->_get_rekap_tambahan_atas($table_identity, $jawaban_ganda, $jawaban_isian, $this->data['atribut_pertanyaan']);
        // $this->_get_rekap_tambahan_bawah($table_identity, $jawaban_ganda, $jawaban_isian, $this->data['atribut_pertanyaan']);
        // $this->_get_rekap_alasan_jawaban($table_identity);



        // if (in_array(1, $this->data['atribut_pertanyaan'])) {
        //     $this->_get_kuadran_laporan($table_identity);
        // }

        // if (in_array(3, $this->data['atribut_pertanyaan'])) {
        //     $this->_get_rekap_kualitatif($table_identity);
        // }



        $this->data['no_Bab1'] = 1;
        $this->data['no_Bab2'] = 1;
        $this->data['no_Bab3'] = 1;
        $this->data['no_gambar'] = 1;
        $this->data['no_table'] = 1;


        $this->load->library('pdfgenerator');
        $this->data['title_pdf'] = 'Laporan';
        $file_pdf = 'Laporan';
        $paper = 'A4';
        $orientation = "potrait";
        // $this->load->view('laporan_survey/cetak', $this->data);
        $html = $this->load->view('laporan_survey/cetak', $this->data, true);
        $this->pdfgenerator->generate($html, $file_pdf, $paper, $orientation);
    }



    public function _get_data_profile($id1, $id2)
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->join('users_groups', 'users.id = users_groups.user_id');
        $this->db->where('users.username', $this->session->userdata('username'));
        $data_user = $this->db->get()->row();
        $user_identity = 'drs' . $data_user->is_parent;

        $this->db->select('*');
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