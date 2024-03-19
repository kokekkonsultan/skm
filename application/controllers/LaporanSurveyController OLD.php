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

        $this->_get_kuadran($table_identity);

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

        //PROFIL RESPONDEN
        $this->data['profil_responden'] = $this->db->query("SELECT * FROM profil_responden_$table_identity WHERE jenis_isian = 1");

        //PENDEFINISIAN SKALA LIKERT
        $this->data['skala_likert'] = 100 / ($this->data['manage_survey']->skala_likert == 5 ? 5 : 4);
        $this->data['definisi_skala'] = $this->db->query("SELECT * FROM definisi_skala_$table_identity ORDER BY id DESC");

        //SARAN
        $this->data['saran_res'] = $this->db->query("SELECT * FROM responden_$table_identity JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id_responden WHERE is_active = 1 && is_submit = 1 && saran != ''");

        $this->data['profil_urutan'] = $this->db->query("SELECT GROUP_CONCAT(nama_profil_responden) AS nama FROM profil_responden_$table_identity")->row()->nama;


        //ANALISA
        $this->db->select("*");
        $this->db->from("analisa_$table_identity");
        $this->db->join("unsur_pelayanan_$table_identity", "unsur_pelayanan_$table_identity.id = analisa_$table_identity.id_unsur_pelayanan");
        $this->data['analisa'] = $this->db->get();


        $jawaban_ganda = $this->db->query("SELECT *,
            (SELECT COUNT(*) FROM survey_$table_identity
            JOIN jawaban_pertanyaan_terbuka_$table_identity ON survey_$table_identity.id_responden = jawaban_pertanyaan_terbuka_$table_identity.id_responden
            WHERE survey_$table_identity.is_submit = 1 && jawaban_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka = perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka && jawaban_pertanyaan_terbuka_$table_identity.jawaban = isi_pertanyaan_ganda_$table_identity.pertanyaan_ganda) AS perolehan,

            (SELECT COUNT(*) FROM survey_$table_identity WHERE is_submit = 1) AS jumlah_survei

            FROM isi_pertanyaan_ganda_$table_identity
            JOIN perincian_pertanyaan_terbuka_$table_identity ON isi_pertanyaan_ganda_$table_identity.id_perincian_pertanyaan_terbuka = perincian_pertanyaan_terbuka_$table_identity.id
            WHERE perincian_pertanyaan_terbuka_$table_identity.id_jenis_pilihan_jawaban = 1");

        $jawaban_isian = $this->db->query("SELECT *
            FROM jawaban_pertanyaan_terbuka_$table_identity
            JOIN pertanyaan_terbuka_$table_identity ON pertanyaan_terbuka_$table_identity.id = jawaban_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka
            JOIN perincian_pertanyaan_terbuka_$table_identity ON pertanyaan_terbuka_$table_identity.id = perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka
            JOIN survey_$table_identity ON jawaban_pertanyaan_terbuka_$table_identity.id_responden = survey_$table_identity.id_responden
            WHERE id_jenis_pilihan_jawaban = 2 && survey_$table_identity.is_submit = 1");




        $this->_get_data_laporan($table_identity, $this->data['skala_likert']);
        $this->_get_unsur_tertinggi_terendah($table_identity);
        $this->_get_chart_unsur($this->data['manage_survey'], $this->data['manage_survey']->skala_likert, $this->data['definisi_skala'], $this->data['atribut_pertanyaan']);
        $this->_get_rekap_tambahan_atas($table_identity, $jawaban_ganda, $jawaban_isian, $this->data['atribut_pertanyaan']);
        $this->_get_rekap_tambahan_bawah($table_identity, $jawaban_ganda, $jawaban_isian, $this->data['atribut_pertanyaan']);
        $this->_get_rekap_alasan_jawaban($table_identity);



        if (in_array(1, $this->data['atribut_pertanyaan'])) {
            $this->_get_kuadran_laporan($table_identity);
        }

        if (in_array(3, $this->data['atribut_pertanyaan'])) {
            $this->_get_rekap_kualitatif($table_identity);
        }



        $this->load->library('pdfgenerator');
        $this->data['title_pdf'] = 'Laporan';
        $file_pdf = 'Laporan';
        $paper = 'A4';
        $orientation = "potrait";
        // $this->load->view('laporan_survey/cetak', $this->data);
        $html = $this->load->view('laporan_survey/cetak', $this->data, true);
        $this->pdfgenerator->generate($html, $file_pdf, $paper, $orientation);
    }





    
    public function _get_kuadran_laporan($table_identity)
    {

        //Unsur Prioritas Perbaikan 
        $nilai_per_unsur_asc = $this->db->query("SELECT IF(id_parent = 0,unsur_pelayanan_$table_identity.id, unsur_pelayanan_$table_identity.id_parent) AS id_sub, (SUM(skor_jawaban)/COUNT(DISTINCT id_responden)) AS rata_rata,  (COUNT(id_parent)/COUNT(DISTINCT id_responden)) AS colspan, ((SUM(skor_jawaban)/COUNT(DISTINCT id_responden))/(COUNT(id_parent)/COUNT(DISTINCT id_responden))) AS nilai_per_unsur, (SELECT nomor_unsur FROM unsur_pelayanan_$table_identity WHERE id_sub = unsur_pelayanan_$table_identity.id) as nomor_unsur, (SELECT nama_unsur_pelayanan FROM unsur_pelayanan_$table_identity WHERE id_sub = unsur_pelayanan_$table_identity.id) as nama_unsur_pelayanan
        FROM jawaban_pertanyaan_harapan_$table_identity
        JOIN pertanyaan_unsur_pelayanan_$table_identity ON jawaban_pertanyaan_harapan_$table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id
        JOIN unsur_pelayanan_$table_identity ON pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan = unsur_pelayanan_$table_identity.id
        GROUP BY id_sub
        ORDER BY nilai_per_unsur ASC
        LIMIT 3");

        $asc_h = [];
        foreach ($nilai_per_unsur_asc->result() as $value) {
            $asc_h[] = $value->nomor_unsur . '. ' . $value->nama_unsur_pelayanan;
        }
        $this->data['asc_harapan'] = implode("<br>", $asc_h);


        //JUDUL PERSEPSI
        $this->db->select("unsur_pelayanan_$table_identity.nomor_unsur AS nomor,
		SUBSTRING(nomor_unsur, 2, 4) AS nomor_harapan, nama_unsur_pelayanan");
        $this->db->from("unsur_pelayanan_$table_identity");
        $this->db->where('id_parent = 0');
        $this->data['persepsi'] = $this->db->get();
        $jumlah_unsur = $this->data['persepsi']->num_rows();


        $object_unsur = $this->data['nilai_per_unsur'];

        $nilai_unsur = 0;
        foreach ($object_unsur->result() as $values) {
            $nilai_unsur += $values->nilai_per_unsur;
        }

        //NILAI PER HARAPAN
        $this->db->select("((SUM(skor_jawaban)/COUNT(DISTINCT survey_$table_identity.id_responden))/(COUNT(id_parent)/COUNT(DISTINCT survey_$table_identity.id_responden))) AS nilai_per_unsur");
        $this->db->from("jawaban_pertanyaan_harapan_$table_identity");
        $this->db->join("pertanyaan_unsur_pelayanan_$table_identity", "jawaban_pertanyaan_harapan_$table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id");
        $this->db->join("unsur_pelayanan_$table_identity", "pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan = unsur_pelayanan_$table_identity.id");
        $this->db->join("survey_$table_identity", "jawaban_pertanyaan_harapan_$table_identity.id_responden = survey_$table_identity.id_responden");
        $this->db->where("survey_$table_identity.is_submit = 1");
        $this->db->group_by("IF(id_parent = 0,unsur_pelayanan_$table_identity.id, unsur_pelayanan_$table_identity.id_parent)");
        $object_harapan = $this->db->get();
        $this->data['nilai_per_unsur_harapan'] = $object_harapan;

        $nilai_harapan = 0;
        foreach ($object_harapan->result() as $rows) {
            $nilai_harapan += $rows->nilai_per_unsur;
        }

        $total_rata_unsur = $nilai_unsur / $jumlah_unsur;
        $total_rata_harapan = $nilai_harapan / $jumlah_unsur;


        $this->data['kuadran_unsur'] =  $this->db->query("SELECT *,
		(CASE
			WHEN kup.skor_unsur <= $total_rata_unsur && kup.skor_harapan >= $total_rata_harapan
					THEN 1
			WHEN kup.skor_unsur >= $total_rata_unsur && kup.skor_harapan >= $total_rata_harapan
					THEN 2
				WHEN kup.skor_unsur <= $total_rata_unsur && kup.skor_harapan <= $total_rata_harapan
					THEN 3
				WHEN kup.skor_unsur >= $total_rata_unsur && kup.skor_harapan <= $total_rata_harapan
					THEN 4
			ELSE 0
		END) AS kuadran
		
		FROM (SELECT IF(id_parent = 0,unsur_pelayanan_$table_identity.id, unsur_pelayanan_$table_identity.id_parent) AS id_sub, (SELECT nomor_unsur FROM unsur_pelayanan_$table_identity WHERE id_sub = unsur_pelayanan_$table_identity.id) AS nomor_unsur, (SELECT nama_unsur_pelayanan FROM unsur_pelayanan_$table_identity WHERE id_sub = unsur_pelayanan_$table_identity.id) AS nama_unsur_pelayanan, 
		
		(SUM((SELECT SUM(skor_jawaban) FROM jawaban_pertanyaan_unsur_$table_identity JOIN survey_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden WHERE is_submit = 1 && pertanyaan_unsur_pelayanan_$table_identity.id = jawaban_pertanyaan_unsur_$table_identity.id_pertanyaan_unsur))/(SELECT COUNT(IF(skor_jawaban != 0, 1, NULL)) FROM jawaban_pertanyaan_unsur_$table_identity 
		JOIN survey_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden
		WHERE pertanyaan_unsur_pelayanan_$table_identity.id = jawaban_pertanyaan_unsur_$table_identity.id_pertanyaan_unsur && survey_$table_identity.is_submit = 1)/COUNT(id_parent)) AS skor_unsur,
		
		(SUM((SELECT SUM(skor_jawaban) FROM jawaban_pertanyaan_harapan_$table_identity JOIN survey_$table_identity ON jawaban_pertanyaan_harapan_$table_identity.id_responden = survey_$table_identity.id_responden WHERE is_submit = 1 && pertanyaan_unsur_pelayanan_$table_identity.id = jawaban_pertanyaan_harapan_$table_identity.id_pertanyaan_unsur))/(SELECT COUNT(IF(skor_jawaban != 0, 1, NULL)) FROM jawaban_pertanyaan_unsur_$table_identity 
		JOIN survey_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden
		WHERE pertanyaan_unsur_pelayanan_$table_identity.id = jawaban_pertanyaan_unsur_$table_identity.id_pertanyaan_unsur && survey_$table_identity.is_submit = 1)/COUNT(id_parent)) AS skor_harapan
		
		FROM pertanyaan_unsur_pelayanan_$table_identity
		JOIN unsur_pelayanan_$table_identity ON pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan = unsur_pelayanan_$table_identity.id
		GROUP BY id_sub) AS kup");

        $this->data['total_rata_unsur'] = $total_rata_unsur;
    }



    public function _get_rekap_tambahan_atas($table_identity, $jawaban_ganda, $jawaban_isian, $atribut_pertanyaan)
    {
        if (in_array(2, $atribut_pertanyaan)) {
            
            $pertanyaan_tambahan_atas = $this->db->query("SELECT *,
            (SELECT DISTINCT dengan_isian_lainnya FROM isi_pertanyaan_ganda_$table_identity WHERE isi_pertanyaan_ganda_$table_identity.id_perincian_pertanyaan_terbuka = perincian_pertanyaan_terbuka_$table_identity.id) AS is_lainnya,
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
            'Lainnya') / (SELECT COUNT(*) FROM survey_$table_identity JOIN responden_$table_identity ON survey_$table_identity.id_responden = responden_$table_identity.id JOIN jawaban_pertanyaan_terbuka_$table_identity ON responden_$table_identity.id = jawaban_pertanyaan_terbuka_$table_identity.id_responden WHERE is_submit = 1 && jawaban_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka = perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka && jawaban_pertanyaan_terbuka_$table_identity.jawaban != '' )) * 100) AS persentase
            
            FROM pertanyaan_terbuka_$table_identity
            JOIN perincian_pertanyaan_terbuka_$table_identity ON pertanyaan_terbuka_$table_identity.id = perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka
            WHERE is_letak_pertanyaan = 1");

            if ($pertanyaan_tambahan_atas->num_rows() > 0) {
                $get_rekap_tambahan = [];
                foreach ($pertanyaan_tambahan_atas->result() as $row) {

                    if ($row->id_jenis_pilihan_jawaban == 1) {

                        $nt = 1;
                        $add_table_terbuka = [];
                        foreach ($jawaban_ganda->result() as $value) {
                            if ($value->id_pertanyaan_terbuka == $row->id_pertanyaan_terbuka) {

                                $add_table_terbuka[] = '
                            <tr>
                                <td class="td-th-list" width="6%">' . $nt++ . '</td>
                                <td class="td-th-list">' . $value->pertanyaan_ganda . '</td>
                                <td class="td-th-list">' . $value->perolehan . '</td>
                                <td class="td-th-list">' . str_replace('.', ',', ROUND(($value->perolehan / $value->jumlah_survei) * 100, 2)) . ' %</td>
                            </tr>';
                            }
                        }
                        $t_terbuka = implode(" ", $add_table_terbuka);

                        if ($row->is_lainnya == 1) {
                            $add_table_terbuka_lainnya = '
                            <tr>
                                <th class="td-th-list" width="6%">' . $nt++ . '</th>
                                <td class="td-th-list">Lainnya</td>
                                <td class="td-th-list">' . $row->perolehan . '</td>
                                <td class="td-th-list">' . str_replace('.', ',', ROUND($row->persentase, 2)) . ' %</td>
                            </tr>';
                        } else {
                            $add_table_terbuka_lainnya = '';
                        }

                        $get_terbuka_pilihan = '
                    <table style="width: 100%; margin-left: auto; margin-right: auto;" class="table-list">
                        <tr style="background-color:#E4E6EF;">
                            <th class="td-th-list" width="6%">No</th>
                            <th class="td-th-list">Kelompok</th>
                            <th class="td-th-list">Jumlah</th>
                            <th class="td-th-list">Persentase</th>
                        </tr>' . $t_terbuka . ' ' . $add_table_terbuka_lainnya .
                            '</table>
                    ';
                    } else {
                        $ns = 1;
                        $add_table_terbuka_isian = [];
                        foreach ($jawaban_isian->result() as $get) {
                            if ($get->id_pertanyaan_terbuka == $row->id_pertanyaan_terbuka) {
                                $add_table_terbuka_isian[] = '
                            <tr>
                                <td class="td-th-list" width="6%">' . $ns++ . '</th>
                                <td class="td-th-list" style="text-align: left;">' . $get->jawaban . '</td>
                            </tr>';
                            }
                        }
                        $t_isian = implode(" ", $add_table_terbuka_isian);

                        $get_terbuka_pilihan = '<table style="width: 100%; margin-left: auto; margin-right: auto;" class="table-list">
                        <tr style="background-color:#E4E6EF;">
                            <th class="td-th-list" width="6%">No</th>
                            <th class="td-th-list">Jawaban</th>
                        </tr>' . $t_isian .
                            '</table>';
                    }

                    $get_rekap_tambahan[] = '<li><div><b>' . $row->nomor_pertanyaan_terbuka . '. ' . $row->nama_pertanyaan_terbuka . '</b></div><br>' . $get_terbuka_pilihan .
                        '<br><br></li>';
                }
                $this->data['html_rekap_tambahan_atas'] = implode(" ", $get_rekap_tambahan);
            } else {
                $this->data['html_rekap_tambahan_atas'] = '';
            }
        } else {
            $this->data['html_rekap_tambahan_atas'] = '';
        }
        // var_dump($this->data['html_rekap_tambahan_atas']);
    }





    public function _get_rekap_tambahan_bawah($table_identity, $jawaban_ganda, $jawaban_isian, $atribut_pertanyaan)
    {

        if (in_array(2, $atribut_pertanyaan)) {
            
            $pertanyaan_tambahan_bawah = $this->db->query("SELECT *,
        (SELECT DISTINCT dengan_isian_lainnya FROM isi_pertanyaan_ganda_$table_identity WHERE isi_pertanyaan_ganda_$table_identity.id_perincian_pertanyaan_terbuka = perincian_pertanyaan_terbuka_$table_identity.id) AS is_lainnya,
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
		'Lainnya') / (SELECT COUNT(*) FROM survey_$table_identity JOIN responden_$table_identity ON survey_$table_identity.id_responden = responden_$table_identity.id JOIN jawaban_pertanyaan_terbuka_$table_identity ON responden_$table_identity.id = jawaban_pertanyaan_terbuka_$table_identity.id_responden WHERE is_submit = 1 && jawaban_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka = perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka && jawaban_pertanyaan_terbuka_$table_identity.jawaban != '' )) * 100) AS persentase
        
        FROM pertanyaan_terbuka_$table_identity
        JOIN perincian_pertanyaan_terbuka_$table_identity ON pertanyaan_terbuka_$table_identity.id = perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka
        WHERE is_letak_pertanyaan = 2");


            if ($pertanyaan_tambahan_bawah->num_rows() > 0) {
                $get_rekap_tambahan = [];
                foreach ($pertanyaan_tambahan_bawah->result() as $row) {

                    if ($row->id_jenis_pilihan_jawaban == 1) {

                        $nt = 1;
                        $add_table_terbuka = [];
                        foreach ($jawaban_ganda->result() as $value) {
                            if ($value->id_pertanyaan_terbuka == $row->id_pertanyaan_terbuka) {

                                $add_table_terbuka[] = '
                        <tr>
                            <td class="td-th-list" width="6%">' . $nt++ . '</td>
                            <td class="td-th-list">' . $value->pertanyaan_ganda . '</td>
                            <td class="td-th-list">' . $value->perolehan . '</td>
                            <td class="td-th-list">' . str_replace('.', ',', ROUND(($value->perolehan / $value->jumlah_survei) * 100, 2)) . ' %</td>
                        </tr>';
                            }
                        }
                        $t_terbuka = implode(" ", $add_table_terbuka);

                        if ($row->is_lainnya == 1) {
                            $add_table_terbuka_lainnya = '
                        <tr>
                            <td class="td-th-list" width="6%">' . $nt++ . '</td>
                            <td class="td-th-list">Lainnya</td>
                            <td class="td-th-list">' . $row->perolehan . '</td>
                            <td class="td-th-list">' . str_replace('.', ',', ROUND($row->persentase, 2)) . ' %</td>
                        </tr>';
                        } else {
                            $add_table_terbuka_lainnya = '';
                        }

                        $get_terbuka_pilihan = '
                <table style="width: 100%; margin-left: auto; margin-right: auto;" class="table-list">
                    <tr style="background-color:#E4E6EF;">
                        <th class="td-th-list" width="6%">No</th>
                        <th class="td-th-list">Kelompok</th>
                        <th class="td-th-list">Jumlah</th>
                        <th class="td-th-list">Persentase</th>
                    </tr>' . $t_terbuka . ' ' . $add_table_terbuka_lainnya .
                            '</table>
                ';
                    } else {
                        $ns = 1;
                        $add_table_terbuka_isian = [];
                        foreach ($jawaban_isian->result() as $get) {
                            if ($get->id_pertanyaan_terbuka == $row->id_pertanyaan_terbuka) {
                                $add_table_terbuka_isian[] = '
                        <tr>
                            <td class="td-th-list" width="6%">' . $ns++ . '</th>
                            <td class="td-th-list" style="text-align: left;">' . $get->jawaban . '</td>
                        </tr>';
                            }
                        }
                        $t_isian = implode(" ", $add_table_terbuka_isian);

                        $get_terbuka_pilihan = '<table style="width: 100%; margin-left: auto; margin-right: auto;" class="table-list">
                     <tr style="background-color:#E4E6EF;">
                        <th class="td-th-list" width="6%">No</th>
                        <th class="td-th-list">Jawaban</th>
                    </tr>' . $t_isian .
                            '</table>';
                    }

                    $get_rekap_tambahan[] = '<li><div><b>' . $row->nomor_pertanyaan_terbuka . '. ' . $row->nama_pertanyaan_terbuka . '</b></div><br>' . $get_terbuka_pilihan .
                        '<br><br></li>';
                }
                $this->data['html_rekap_tambahan_bawah'] = implode(" ", $get_rekap_tambahan);
            } else {
                $this->data['html_rekap_tambahan_bawah'] = '';
            }
        } else {
            $this->data['html_rekap_tambahan_bawah'] = '';
        }
        // var_dump($this->data['html_rekap_tambahan_bawah']);
    }



    public function _get_chart_unsur($manage_survey, $skala_likert, $definisi_skala, $atribut_pertanyaan)
    {
        // $this->data['skala_likert'] = 100 / ($this->data['manage_survey']->skala_likert == 5 ? 5 : 4);
        // $this->data['definisi_skala'] = $this->db->query("SELECT * FROM definisi_skala_$table_identity ORDER BY id DESC");

        $table_identity = $manage_survey->table_identity;


        $unsur_pelayanan = $this->db->query("SELECT *, unsur_pelayanan_$table_identity.id AS id_unsur_pelayanan, (SELECT isi_pertanyaan_unsur FROM pertanyaan_unsur_pelayanan_$table_identity WHERE id_unsur_pelayanan = unsur_pelayanan_$table_identity.id) as isi_pertanyaan_unsur
        FROM unsur_pelayanan_$table_identity
        WHERE id_parent = 0 ");

        $get_pilihan_jawaban = $this->db->query("SELECT *, (SELECT COUNT(skor_jawaban) FROM jawaban_pertanyaan_unsur_$table_identity
        JOIN survey_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden WHERE jawaban_pertanyaan_unsur_$table_identity.id_pertanyaan_unsur = kategori_unsur_pelayanan_$table_identity.id_pertanyaan_unsur && kategori_unsur_pelayanan_$table_identity.nomor_kategori_unsur_pelayanan = jawaban_pertanyaan_unsur_$table_identity.skor_jawaban && is_submit = 1) AS perolehan,
        (SELECT COUNT(IF(skor_jawaban != 0, 1, NULL))
 		FROM jawaban_pertanyaan_unsur_$table_identity JOIN survey_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden WHERE jawaban_pertanyaan_unsur_$table_identity.id_pertanyaan_unsur = kategori_unsur_pelayanan_$table_identity.id_pertanyaan_unsur && is_submit = 1) AS jumlah_pengisi
        FROM kategori_unsur_pelayanan_$table_identity");


        $rekap_turunan_unsur = $this->db->query("SELECT *, pertanyaan_unsur_pelayanan_$table_identity.id AS id_pertanyaan_unsur_pelayanan,
        (SELECT COUNT(skor_jawaban)
        FROM jawaban_pertanyaan_unsur_$table_identity
        JOIN survey_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden
        WHERE is_submit = 1 && id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id AND skor_jawaban = 1) AS perolehan_1,
        (SELECT COUNT(skor_jawaban)
        FROM jawaban_pertanyaan_unsur_$table_identity
        JOIN survey_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden
        WHERE is_submit = 1 && id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id AND skor_jawaban = 2) AS perolehan_2,
        (SELECT COUNT(skor_jawaban)
        FROM jawaban_pertanyaan_unsur_$table_identity
        JOIN survey_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden
        WHERE is_submit = 1 && id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id AND skor_jawaban = 3) AS perolehan_3,
        (SELECT COUNT(skor_jawaban)
        FROM jawaban_pertanyaan_unsur_$table_identity
        JOIN survey_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden
        WHERE is_submit = 1 && id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id AND skor_jawaban = 4) AS perolehan_4,
        (SELECT COUNT(skor_jawaban)
        FROM jawaban_pertanyaan_unsur_$table_identity
        JOIN survey_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden
        WHERE is_submit = 1 && id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id AND skor_jawaban = 5) AS perolehan_5,
        (SELECT COUNT(IF(skor_jawaban != 0, 1, NULL))
 		FROM jawaban_pertanyaan_unsur_$table_identity JOIN survey_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden WHERE jawaban_pertanyaan_unsur_$table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id && is_submit = 1) AS jumlah_pengisi,
        (SELECT AVG(skor_jawaban)
        FROM jawaban_pertanyaan_unsur_$table_identity
        JOIN survey_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden
        WHERE is_submit = 1 && id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id) AS rata_rata
        FROM unsur_pelayanan_$table_identity
        JOIN pertanyaan_unsur_pelayanan_$table_identity ON pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan = unsur_pelayanan_$table_identity.id");

        $no_img = $this->db->get_where("profil_responden_$table_identity", array('jenis_isian' => 1))->num_rows() + 2;
        $no_tabel = $this->db->get_where("profil_responden_$table_identity", array('is_lainnya' => 1))->num_rows() + 2;

        
        foreach ($unsur_pelayanan->result() as $key => $row) {


        if (in_array(2, $atribut_pertanyaan)) {
            
            $pertanyaan_tambahan[$key] = $this->db->query("SELECT *,
            (SELECT DISTINCT dengan_isian_lainnya FROM isi_pertanyaan_ganda_$table_identity WHERE isi_pertanyaan_ganda_$table_identity.id_perincian_pertanyaan_terbuka = perincian_pertanyaan_terbuka_$table_identity.id) AS is_lainnya,
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
            'Lainnya') / (SELECT COUNT(*) FROM survey_$table_identity JOIN responden_$table_identity ON survey_$table_identity.id_responden = responden_$table_identity.id JOIN jawaban_pertanyaan_terbuka_$table_identity ON responden_$table_identity.id = jawaban_pertanyaan_terbuka_$table_identity.id_responden WHERE is_submit = 1 && jawaban_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka = perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka && jawaban_pertanyaan_terbuka_$table_identity.jawaban != '' )) * 100) AS persentase

            FROM pertanyaan_terbuka_$table_identity
            JOIN perincian_pertanyaan_terbuka_$table_identity ON pertanyaan_terbuka_$table_identity.id = perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka
            WHERE id_unsur_pelayanan = $row->id_unsur_pelayanan");

                



                //CEK JIKA SURVEI KHUSUS
                if ($manage_survey->id_jenis_pelayanan == 0 && $table_identity == 'cst358' && $row->id_unsur_pelayanan == 305) {

                    $thead_t = [];
                    foreach ($pertanyaan_tambahan[$key]->result() as $pt) {
                        $thead_t[] = '<th class="td-th-list">' . $pt->nama_pertanyaan_terbuka . '</th>';
                    }


                    $tr_t_body = [];
                    $no_t = 1;
                    foreach($this->db->get_where("survey_$table_identity", array('is_submit' => 1))->result() as $svy){

                        
                        $tbody_t = [];
                        foreach($this->db->get_where("jawaban_pertanyaan_terbuka_$table_identity", array('id_responden' => $svy->id_responden))->result() as $jpt){
                            $tbody_t[] = '<td class="td-th-list">' . $jpt->jawaban . '</td>';
                        }

                        $tr_t_body[] = '<tr><td class="td-th-list">' . $no_t++ . '</td>'  . implode(" ", $tbody_t) . '</tr>';
                    }

                
                    $html_rekap_tambahan[$key] = '<br><div style="font-size:13px; text-align:center;">Responden yang Menjawab Biaya/Tarif Tidak Sesuai dengan Ketentuan</div>
                    <table style="width: 100%; margin-left: auto; margin-right: auto;" class="table-list">
                    <tr style="background-color:#E4E6EF;"><th class="td-th-list">No</th>' . implode(" ", $thead_t) . '</tr>' . implode(" ", $tr_t_body) . '</table><br><br>';



                } else {
        

                    if ($pertanyaan_tambahan[$key]->num_rows() > 0) {
                        $get_rekap_tambahan[$key] = [];
                        foreach ($pertanyaan_tambahan[$key]->result() as $pt) {
                            if ($pt->id_jenis_pilihan_jawaban == 1) {

                                $nt = 1;
                                $add_table_terbuka = [];

                                foreach ($this->db->query("SELECT *,
                                (SELECT COUNT(*) FROM survey_$table_identity
                                JOIN jawaban_pertanyaan_terbuka_$table_identity ON survey_$table_identity.id_responden = jawaban_pertanyaan_terbuka_$table_identity.id_responden
                                WHERE survey_$table_identity.is_submit = 1 && jawaban_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka = perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka && jawaban_pertanyaan_terbuka_$table_identity.jawaban = isi_pertanyaan_ganda_$table_identity.pertanyaan_ganda) AS perolehan,
                    
                                (SELECT COUNT(*) FROM survey_$table_identity WHERE is_submit = 1) AS jumlah_survei
                    
                                FROM isi_pertanyaan_ganda_$table_identity
                                JOIN perincian_pertanyaan_terbuka_$table_identity ON isi_pertanyaan_ganda_$table_identity.id_perincian_pertanyaan_terbuka = perincian_pertanyaan_terbuka_$table_identity.id
                                WHERE perincian_pertanyaan_terbuka_$table_identity.id_jenis_pilihan_jawaban = 1")->result() as $value) {

                                    if ($value->id_pertanyaan_terbuka == $pt->id_pertanyaan_terbuka) {

                                        $add_table_terbuka[] = '
                                    <tr>
                                        <td class="td-th-list" width="6%">' . $nt++ . '</td>
                                        <td class="td-th-list">' . $value->pertanyaan_ganda . '</td>
                                        <td class="td-th-list">' . $value->perolehan . '</td>
                                        <td class="td-th-list">' . str_replace('.', ',', ROUND(($value->perolehan / $value->jumlah_survei) * 100, 2)) . ' %</td>
                                    </tr>';
                                    }
                                }
                                $t_terbuka = implode(" ", $add_table_terbuka);

                                if ($pt->is_lainnya == 1) {
                                    $add_table_terbuka_lainnya = '
                                    <tr>
                                        <td class="td-th-list" width="6%">' . $nt++ . '</td>
                                        <td class="td-th-list">Lainnya</td>
                                        <td class="td-th-list">' . $pt->perolehan . '</td>
                                        <td class="td-th-list">' . str_replace('.', ',', ROUND($pt->persentase, 2)) . ' %</td>
                                    </tr>';
                                } else {
                                    $add_table_terbuka_lainnya = '';
                                }

                                $get_terbuka_pilihan = '
                                <table style="width: 100%; margin-left: auto; margin-right: auto;" class="table-list">
                                <tr style="background-color:#E4E6EF;">
                                    <th class="td-th-list" width="6%">No</th>
                                    <th class="td-th-list">Kelompok</th>
                                    <th class="td-th-list">Jumlah</th>
                                    <th class="td-th-list">Persentase</th>
                                </tr>' . $t_terbuka . ' ' . $add_table_terbuka_lainnya .
                                    '</table>
                            ';
                            } else {
                                
                                $ns = 1;
                                $add_table_terbuka_isian = [];

                                foreach ($this->db->query("SELECT *
                                FROM jawaban_pertanyaan_terbuka_$table_identity
                                JOIN pertanyaan_terbuka_$table_identity ON pertanyaan_terbuka_$table_identity.id = jawaban_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka
                                JOIN perincian_pertanyaan_terbuka_$table_identity ON pertanyaan_terbuka_$table_identity.id = perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka
                                JOIN survey_$table_identity ON jawaban_pertanyaan_terbuka_$table_identity.id_responden = survey_$table_identity.id_responden
                                WHERE id_jenis_pilihan_jawaban = 2 && survey_$table_identity.is_submit = 1")->result() as $get) {
                                    
                                    if ($get->id_pertanyaan_terbuka == $pt->id_pertanyaan_terbuka) {
                                        $add_table_terbuka_isian[] = '
                                    <tr>
                                        <td class="td-th-list" width="6%">' . $ns++ . '</th>
                                        <td class="td-th-list" style="text-align: left;">' . $get->jawaban . '</td>
                                    </tr>';
                                    }
                                }
                                $t_isian = implode(" ", $add_table_terbuka_isian);

                                $get_terbuka_pilihan = '<table style="width: 100%; margin-left: auto; margin-right: auto;" class="table-list">
                                    <tr style="background-color:#E4E6EF;">
                                        <th class="td-th-list" width="6%">No</th>
                                        <th class="td-th-list">Jawaban</th>
                                    </tr>' . $t_isian .
                                    '</table>';
                            }

                            $get_rekap_tambahan[$key][] = '<li><div><b>' . $pt->nomor_pertanyaan_terbuka . '. ' . $pt->nama_pertanyaan_terbuka . '</b></div><br>' . $get_terbuka_pilihan .
                                '<br><br></li>';
                        }
                        $html_rekap_tambahan[$key] = implode(" ", $get_rekap_tambahan[$key]);
                    } else {
                        $html_rekap_tambahan[$key] = '';
                    }



                    
                }
            } else {
                $html_rekap_tambahan[$key] = '';
            }











            $cek_sub = $this->db->get_where("unsur_pelayanan_$table_identity", ['id_parent' => $row->id_unsur_pelayanan]);

            // UNSUR YANG TIDAK MEMILIKI TURUNAN
            if ($cek_sub->num_rows() == 0) {
                $no = 1;
                $t_perolehan = 0;
                $t_persentase = 0;
                $add_table = [];
                $nama_kategori_unsur_pelayanan = [];
                $persentase = [];
                foreach ($get_pilihan_jawaban->result() as $value) {
                    if ($value->id_unsur_pelayanan == $row->id_unsur_pelayanan) {

                        $nama_kategori_unsur_pelayanan[] = '%27' . $value->nama_kategori_unsur_pelayanan . '+=+' . ROUND(($value->perolehan / $value->jumlah_pengisi) * 100, 2) . '%25%27';
                        $persentase[] = ROUND(($value->perolehan / $value->jumlah_pengisi) * 100, 2);

                        $add_table[] = '<tr>
                                            <td class="td-th-list">' . $no++ . '</td>
                                            <td class="td-th-list">' . $value->nama_kategori_unsur_pelayanan . '</td>
                                            <td class="td-th-list">' . $value->perolehan . '</td>
                                            <td class="td-th-list">
                                                ' . ROUND(($value->perolehan / $value->jumlah_pengisi) * 100, 2) . ' %
                                            </td>
                                        </tr>';
                        $t_perolehan += $value->perolehan;
                        $t_persentase += ($value->perolehan / $value->jumlah_pengisi) * 100;
                    }
                }
                $get_table = implode(" ", $add_table);
                $get_persentase = implode(",", $persentase);
                $get_nama_kategori = implode(", ", $nama_kategori_unsur_pelayanan);



                $alasan = $this->db->query("SELECT *
                FROM jawaban_pertanyaan_unsur_$table_identity
                JOIN pertanyaan_unsur_pelayanan_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id
                JOIN survey_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden
                WHERE is_submit = 1 && id_unsur_pelayanan = $row->id && alasan_pilih_jawaban != ''
                && jawaban_pertanyaan_unsur_$table_identity.is_active = 1
                ");



                if($alasan->num_rows() > 0){
                    $val_alasan = [];
                    foreach($alasan->result() as $get){
                        $val_alasan[] = '<li>' . $get->alasan_pilih_jawaban . '</li>';
                    }
                    $data_alasan = '
                    <tr>
                        <td style="text-align: left; padding-top:1em;">
                        Alasan yang diberikan responden pada unsur ' . $row->nama_unsur_pelayanan . ':

                        <ul>' . implode(" ", $val_alasan).'</ul>
                        </td>
                    </tr>';
                } else {
                    $data_alasan = '';
                }


                $html5[] = '<table style="width: 100%; padding-left: 2em;" class="table-list">
                    <tr>
                        <td>
                            <div style="text-align: left; font-weight:bold; padding-top:1em;">' . $row->nomor_unsur . '. ' . $row->nama_unsur_pelayanan. '</div>
                            <br>
                            <div style="outline: dashed 1px black;">
                                <img src="https://quickchart.io/chart?c={%20type:%20%27horizontalBar%27,%20data:%20{%20labels:%20[' . $get_nama_kategori . '],%20datasets:%20[{%20label:%20%27Dataset%201%27,%20backgroundColor:%20%27rgb(255,%20159,%2064)%27,%20stack:%20%27Stack%200%27,%20data:%20[' . $get_persentase . '],%20},%20],%20},%20options:%20{%20title:%20{%20display:%20false,%20text:%20%27Chart.js%20Bar%20Chart%20-%20Stacked%27%20},%20legend:%20{%20display:%20false%20},%20plugins:%20{%20roundedBars:%20true%20},%20responsive:%20true,%20},%20}"
                                    alt="" width="70%">
                            </div>
                            <br>
                            Gambar ' . $no_img++ . '. Grafik Unsur ' . $row->nama_unsur_pelayanan . '
                        </td>
                    </tr>

                    <tr>
                        <td style="padding-left: 2em;">
                            <br>
                            <div style="text-align: center;">Tabel ' . $no_tabel .'. Persentase Responden pada Unsur ' . $row->nama_unsur_pelayanan . '</div>
                                <table style="width: 90%; margin-left: auto; margin-right: auto;" class="table-list">
                                            <tr style="background-color:#E4E6EF;">
                                                <th class="td-th-list">No</th>
                                                <th class="td-th-list">Kategori</th>
                                                <th class="td-th-list">Jumlah</th>
                                                <th class="td-th-list">Persentase</th>
                                            </tr>' . $get_table . '
                                            <tr>
                                                <td class="td-th-list" style="text-align: center;" colspan="2"><b>TOTAL</b></td>
                                                <td class="td-th-list">' .  $t_perolehan . '</td>
                                                <td class="td-th-list">' . str_replace('.', ',', $t_persentase) . ' %</td>
                                            </tr>
                                            </table>
                        </td>
                    </tr>
                </table>
                <table style="width: 100%; padding-left: 2em;" class="table-list">' .$data_alasan.'</table>
                ' . $html_rekap_tambahan[$key];

                $html5[] = '';

                //UNSUR MEMILIKI TURUNAN
            } else {

                $html_turunan = [];
                $sub_no_table = 1;
                foreach ($cek_sub->result() as $get) {

                    $s = 1;
                    $add_table_turunan = [];
                    $t_perolehan_turunan = 0;
                    $t_persentase_turunan = 0;
                    foreach ($get_pilihan_jawaban->result() as $value) {
                        if ($value->id_unsur_pelayanan == $get->id) {

                            $add_table_turunan[] = '<tr>
                                            <td class="td-th-list">' . $s++ . '</td>
                                            <td class="td-th-list">' . $value->nama_kategori_unsur_pelayanan . '</td>
                                            <td class="td-th-list">' . $value->perolehan . '</td>
                                            <td class="td-th-list">
                                                ' . ROUND(($value->perolehan / $value->jumlah_pengisi) * 100, 2) . ' %
                                            </td>
                                        </tr>';

                            $t_perolehan_turunan += $value->perolehan;
                            $t_persentase_turunan += ($value->perolehan / $value->jumlah_pengisi) * 100;
                        }
                    }
                    $get_table_turunan = implode(" ", $add_table_turunan);


                    $alasan = $this->db->query("SELECT *
                    FROM jawaban_pertanyaan_unsur_$table_identity
                    JOIN pertanyaan_unsur_pelayanan_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id
                    JOIN survey_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden
                    WHERE is_submit = 1 && id_unsur_pelayanan = $get->id && alasan_pilih_jawaban != '' && jawaban_pertanyaan_unsur_$table_identity.is_active = 1
                    ");


                    if($alasan->num_rows() > 0){
                        $val_alasan = [];
                        foreach($alasan->result() as $val){
                            $val_alasan[] = '<li>' . $val->alasan_pilih_jawaban . '</li>';
                        }
                        $data_alasan = '
                        <div style="font-size:13px;">Alasan yang diberikan responden pada unsur ' . $get->nama_unsur_pelayanan . ':</div>
                        <ul style="font-size:13px;">' . implode(" ", $val_alasan) . '</ul>';
                    } else {
                        $data_alasan = '';
                    }
                

                    $html_turunan[] = '
                        <li>
                            <div style="font-size:13px;"><b>' . $get->nomor_unsur . '. ' . $get->nama_unsur_pelayanan . '</b></div>
                            <div style="text-align: center; font-size:13px; padding-top:1em;">Tabel ' . $no_tabel . '.' .  $sub_no_table++ . '. Persentase Responden pada Unsur ' . $get->nama_unsur_pelayanan . '</div>
                            <table style="width: 90%; margin-left: auto; margin-right: auto;" class="table-list">
                                <tr style="background-color:#E4E6EF;">
                                    <th class="td-th-list">No</th>
                                    <th class="td-th-list">Kategori</th>
                                    <th class="td-th-list">Jumlah</th>
                                    <th class="td-th-list">Persentase</th>
                                </tr>' . $get_table_turunan . '
                                <tr>
                                    <td class="td-th-list" style="text-align: center;" colspan="2"><b>TOTAL</b></td>
                                    <td class="td-th-list">' .  $t_perolehan_turunan . '</td>
                                    <td class="td-th-list">' . str_replace('.', ',', $t_persentase_turunan) . ' %</td>
                                </tr>
                            </table>
                            <br>' .$data_alasan.'</li>';
                }
                $get_html_turunan = implode(" ", $html_turunan);


                $pilihan_jawaban_turunan = $this->db->query("SELECT * FROM kategori_unsur_pelayanan_$table_identity
                JOIN unsur_pelayanan_$table_identity ON kategori_unsur_pelayanan_$table_identity.id_unsur_pelayanan = unsur_pelayanan_$table_identity.id WHERE id_parent = $row->id_unsur_pelayanan
                GROUP BY nomor_kategori_unsur_pelayanan")->result_array();

                if ($skala_likert == 5) {
                    $skala_5 = '<th class="td-th-list">' . $pilihan_jawaban_turunan[4]['nama_kategori_unsur_pelayanan'] . '</th>';
                    $cols = 5;
                } else {
                    $skala_5 = '';
                    $cols = 4;
                };


                $add_table_kesimpulan = [];
                $jum_persentase_1 = 0;
                $jum_persentase_2 = 0;
                $jum_persentase_3 = 0;
                $jum_persentase_4 = 0;
                $jum_persentase_5 = 0;
                $jum_indeks = 0;
                $j = 0;
                foreach ($rekap_turunan_unsur->result() as $obj) {
                    if ($obj->id_parent == $row->id_unsur_pelayanan) {

                        $predikat = $obj->rata_rata * ($skala_likert == 5 ? 20 : 25);
                        foreach ($definisi_skala->result() as $val) {
                            if ($predikat <= $val->range_bawah && $predikat >= $val->range_atas) {
                                $k_kategori = $val->kategori;
                            }
                        }
                        if ($predikat <= 0) {
                            $k_kategori = 'NULL';
                        }


                        if ($skala_likert == 5) {
                            $k_skala_5 = '<td class="td-th-list">' . ROUND(($obj->perolehan_5 / $obj->jumlah_pengisi) * 100, 2) . ' %</td>';
                        } else {
                            $k_skala_5 = '';
                        }



                        $add_table_kesimpulan[] = '<tr>
                                            <td class="td-th-list">' . $obj->nomor_unsur . '. ' . $obj->nama_unsur_pelayanan . '</th>
                                            <td class="td-th-list">' . ROUND(($obj->perolehan_1 / $obj->jumlah_pengisi) * 100, 2) . ' %</td>
                                            <td class="td-th-list">' . ROUND(($obj->perolehan_2 / $obj->jumlah_pengisi) * 100, 2) . ' %</td>
                                            <td class="td-th-list">' . ROUND(($obj->perolehan_3 / $obj->jumlah_pengisi) * 100, 2) . ' %</td>
                                            <td class="td-th-list">' . ROUND(($obj->perolehan_4 / $obj->jumlah_pengisi) * 100, 2) . ' %</td>'
                            . $k_skala_5 .
                            '<td class="td-th-list">' . ROUND($obj->rata_rata, 2) . '</td>
                                            <td class="td-th-list">
                                                ' . $k_kategori . '</td>
                                        </tr>';


                        $jum_persentase_1 += ($obj->perolehan_1 / $obj->jumlah_pengisi) * 100;
                        $jum_persentase_2 += ($obj->perolehan_2 / $obj->jumlah_pengisi) * 100;
                        $jum_persentase_3 += ($obj->perolehan_3 / $obj->jumlah_pengisi) * 100;
                        $jum_persentase_4 += ($obj->perolehan_4 / $obj->jumlah_pengisi) * 100;
                        $jum_persentase_5 += ($obj->perolehan_5 / $obj->jumlah_pengisi) * 100;
                        $jum_indeks += $obj->rata_rata;
                        $j++;
                    }
                }
                $get_html_table_kesimpulan = implode(" ", $add_table_kesimpulan);


                if ($skala_likert == 5) {
                    $jum_5 = '<th class="td-th-list">' . ROUND($jum_persentase_5 / $j, 2) . '%</th>';

                    $nama_chart = [
                        $pilihan_jawaban_turunan[0]['nama_kategori_unsur_pelayanan'] . '+=+' . ROUND($jum_persentase_1 / $j, 2) . '%25%27',
                        $pilihan_jawaban_turunan[1]['nama_kategori_unsur_pelayanan'] . '+=+' . ROUND($jum_persentase_2 / $j, 2) . '%25%27',
                        $pilihan_jawaban_turunan[2]['nama_kategori_unsur_pelayanan'] . '+=+' . ROUND($jum_persentase_3 / $j, 2) . '%25%27',
                        $pilihan_jawaban_turunan[3]['nama_kategori_unsur_pelayanan'] . '+=+' . ROUND($jum_persentase_4 / $j, 2) . '%25%27',
                        $pilihan_jawaban_turunan[4]['nama_kategori_unsur_pelayanan'] . '+=+' . ROUND($jum_persentase_5 / $j, 2) . '%25%27'
                    ];

                    $persen_chart = [ ROUND($jum_persentase_1 / $j, 2),  ROUND($jum_persentase_2 / $j, 2),  ROUND($jum_persentase_3 / $j, 2),  ROUND($jum_persentase_4 / $j, 2),  ROUND($jum_persentase_5 / $j, 2)];
                } else {
                    $jum_5 =  '';
                    $nama_chart = [
                    '%27' . $pilihan_jawaban_turunan[0]['nama_kategori_unsur_pelayanan'] . '+=+' . ROUND($jum_persentase_1 / $j, 2) . '%25%27',
                    '%27' . $pilihan_jawaban_turunan[1]['nama_kategori_unsur_pelayanan'] . '+=+' . ROUND($jum_persentase_2 / $j, 2) . '%25%27',
                    '%27' . $pilihan_jawaban_turunan[2]['nama_kategori_unsur_pelayanan'] . '+=+' . ROUND($jum_persentase_3 / $j, 2) . '%25%27',
                    '%27' . $pilihan_jawaban_turunan[3]['nama_kategori_unsur_pelayanan'] . '+=+' . ROUND($jum_persentase_4 / $j, 2) . '%25%27'
                    ];

                    $persen_chart = [ROUND($jum_persentase_1 / $j, 2),  ROUND($jum_persentase_2 / $j, 2),  ROUND($jum_persentase_3 / $j, 2),  ROUND($jum_persentase_4 / $j, 2)];
                }
                $get_series = implode(",", $persen_chart);
                $get_nama_opsi = implode(", ", $nama_chart);



                $rata_predikat = ($jum_indeks / $j) * ($skala_likert == 5 ? 20 : 25);
                foreach ($definisi_skala->result() as $val) {
                    if ($rata_predikat <= $val->range_bawah && $rata_predikat >= $val->range_atas) {
                        $rata_kategori = $val->kategori;
                    }
                }
                if ($rata_predikat <= 0) {
                    $rata_kategori = 'NULL';
                }


                $html5[] = '<table style="width: 100%;" class="table-list">
                    <tr>
                        <td>
                            <div style="text-align: left; font-weight:bold; padding-top:1em;">' . $row->nomor_unsur . '. ' . $row->nama_unsur_pelayanan. '</div>
                            <br>
                            <div style="outline: dashed 1px black;">
                                <img src="https://quickchart.io/chart?c={%20type:%20%27horizontalBar%27,%20data:%20{%20labels:%20[' . $get_nama_opsi . '],%20datasets:%20[{%20label:%20%27Dataset%201%27,%20backgroundColor:%20%27rgb(255,%20159,%2064)%27,%20stack:%20%27Stack%200%27,%20data:%20[' . $get_series . '],%20},%20],%20},%20options:%20{%20title:%20{%20display:%20false,%20text:%20%27Chart.js%20Bar%20Chart%20-%20Stacked%27%20},%20legend:%20{%20display:%20false%20},%20plugins:%20{%20roundedBars:%20true%20},%20responsive:%20true,%20},%20}"
                                    alt="" width="70%">
                            </div>
                            <br>
                            Gambar ' . $no_img++ . '. Grafik Unsur ' . $row->nama_unsur_pelayanan . '
                        </td>
                    </tr>

                    <tr>
                        <td style="padding-left: 2em;">
                            <br>
                            <div style="text-align: center;">Tabel ' . $no_tabel . '. Persentase Responden pada Unsur ' . $row->nama_unsur_pelayanan . '</div>
                            <table style="width: 100%; margin-left: auto; margin-right: auto;" class="table-list">
                                        <tr style="background-color:#E4E6EF;">
                                            <th class="td-th-list" rowspan="2">Unsur</th>
                                            <th class="td-th-list" colspan="' . $cols . '">Persentase</th>
                                            <th class="td-th-list" rowspan="2">Indeks</th>
                                            <th class="td-th-list" rowspan="2">Predikat</th>
                                        </tr>
            
                                        <tr style="background-color:#E4E6EF;">
                                            <th class="td-th-list">' . $pilihan_jawaban_turunan[0]['nama_kategori_unsur_pelayanan'] . '</th>
                                                <th class="td-th-list">' . $pilihan_jawaban_turunan[1]['nama_kategori_unsur_pelayanan'] . '</th>
                                                <th class="td-th-list">' . $pilihan_jawaban_turunan[2]['nama_kategori_unsur_pelayanan'] . '</th>
                                                <th class="td-th-list">' . $pilihan_jawaban_turunan[3]['nama_kategori_unsur_pelayanan'] . '</th>'
                                .  $skala_5 .
                                '</tr>' . $get_html_table_kesimpulan . '
                                        <tr style="background-color:#E4E6EF;">
                                            <th class="td-th-list">Rata-rata</th>
                                            <th class="td-th-list">' . ROUND($jum_persentase_1 / $j, 2) . '%</th>
                                            <th class="td-th-list">' . ROUND($jum_persentase_2 / $j, 2) . '%</th>
                                            <th class="td-th-list">' . ROUND($jum_persentase_3 / $j, 2) . '%</th>
                                            <th class="td-th-list">' . ROUND($jum_persentase_4 / $j, 2) . '%</th>' . $jum_5 .
                                '<th class="td-th-list">' .  ROUND($jum_indeks/$j, 2) . '</th>
                                            <th class="td-th-list">' .  $rata_kategori . '</th>
                                        </tr>
                                        </table>
                        </td>
                    </tr>
                </table>
                
                <ul>' . $get_html_turunan .'</ul>' . $html_rekap_tambahan[$key];
                        //$get_html_turunan
            }

            $no_tabel++;
            $this->data['get_html'] = implode(" ", $html5);

        }



    }




    public function _get_rekap_kualitatif($table_identity)
    {
        foreach ($this->db->get("pertanyaan_kualitatif_$table_identity")->result() as $value) {

            $jawaban_kualitatif = $this->db->query("SELECT * FROM responden_$table_identity JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id_responden JOIN jawaban_pertanyaan_kualitatif_$table_identity ON responden_$table_identity.id = jawaban_pertanyaan_kualitatif_$table_identity.id_responden
            WHERE is_submit = 1 && id_pertanyaan_kualitatif = $value->id");

            $kl = 1;
            $add_table_kualitatif = [];
            foreach ($jawaban_kualitatif->result() as $row) {
                $add_table_kualitatif[] = '
                <tr>
                    <td class="td-th-list" width="6%">' . $kl++ . '</th>
                    <td class="td-th-list" style="text-align: left;">' . $row->isi_jawaban_kualitatif . '</td>
                </tr>';
            }
            $get_kualitatif = implode(" ", $add_table_kualitatif);


            $get_rekap_kualitatif[] = '<li style="font-family: Arial, Helvetica, sans-serif; font-size: 12px;">' . $value->isi_pertanyaan .
                '<table style="width: 100%; margin-left: auto; margin-right: auto;" class="table-list">
                     <tr style="background-color:#E4E6EF;">
                        <th class="td-th-list" width="6%">No</th>
                        <th class="td-th-list">Jawaban</th>
                    </tr>' . $get_kualitatif .
                '</table></li>';
        }

        $this->data['html_rekap_kualitatif'] = implode(" ", $get_rekap_kualitatif);
    }





    public function _get_rekap_alasan_jawaban($table_identity)
    {
        $this->db->select("*, pertanyaan_unsur_pelayanan_$table_identity.id AS id_pertanyaan_unsur, (SELECT nomor_unsur FROM unsur_pelayanan_$table_identity WHERE id_unsur_pelayanan = unsur_pelayanan_$table_identity.id) AS nomor_unsur");
        $this->db->from("pertanyaan_unsur_pelayanan_$table_identity");
        $unsur = $this->db->get();


        $get_rekap_alasan = [];
        foreach ($unsur->result() as $value) {
            $this->db->select("*");
            $this->db->from("jawaban_pertanyaan_unsur_$table_identity");
            $this->db->join("responden_$table_identity", "responden_$table_identity.id = jawaban_pertanyaan_unsur_$table_identity.id_responden");
            $this->db->join("survey_$table_identity", "responden_$table_identity.id = survey_$table_identity.id_responden");
            $this->db->where("survey_$table_identity.is_submit", 1);
            $this->db->where("jawaban_pertanyaan_unsur_$table_identity.id_pertanyaan_unsur", $value->id_pertanyaan_unsur);
            $this->db->where("jawaban_pertanyaan_unsur_$table_identity.is_active", 1);
            $this->db->where("jawaban_pertanyaan_unsur_$table_identity.alasan_pilih_jawaban !=", "");
            $jawaban_p_unsur = $this->db->get();


            $q = 1;
            $add_table_alasan = [];
            foreach ($jawaban_p_unsur->result() as $values) {
                $add_table_alasan[] = '<tr>
                    <td class="td-th-list" width="6%">' . $q++ . '</td>
                    <td class="td-th-list" style="text-align: left;">' . $values->alasan_pilih_jawaban . '</td>
                    </tr>';
            }
            $get_table_alasan = implode(" ", $add_table_alasan);

            $get_rekap_alasan[] = '<li style="font-family: Arial, Helvetica, sans-serif; font-size: 12px;">' . $value->nomor_unsur . ' ' . $value->isi_pertanyaan_unsur .
                ' <table style="width: 100%; margin-left: auto; margin-right: auto;" class="table-list">
                    <tr style="background-color:#E4E6EF;">
                        <th class="td-th-list" width="6%">No</th>
                        <th class="td-th-list">Alasan Jawaban</th>
                    </tr>' . $get_table_alasan . '
            </table>
            <br>
            <br>
            </li>';
        }
        $this->data['html_rekap_alasan'] = implode(" ", $get_rekap_alasan);
    }




    public function _get_data_laporan($table_identity, $skala_likert)
    {
        $this->data['survey'] = $this->db->get_where("survey_$table_identity", array("is_submit", 1));

        //HASIL SURVEI KEPUASAN MASYARAKAT Bar Chart Nilai SKM Per Unsur Pelayanan
        $this->data['nilai_per_unsur'] = $this->db->query("SELECT IF(id_parent = 0, unsur_pelayanan_$table_identity.id, unsur_pelayanan_$table_identity.id_parent) AS id_sub, 
		(SUM(skor_jawaban)/COUNT(DISTINCT survey_$table_identity.id_responden)) AS rata_rata, 
		(COUNT(id_parent)/COUNT(DISTINCT survey_$table_identity.id_responden)) AS colspan, 
		((SUM(skor_jawaban)/COUNT(DISTINCT survey_$table_identity.id_responden))/(COUNT(id_parent)/COUNT(DISTINCT survey_$table_identity.id_responden))) AS nilai_per_unsur, 
		(SELECT nomor_unsur FROM unsur_pelayanan_$table_identity WHERE id_sub = unsur_pelayanan_$table_identity.id) as nomor_unsur, 
		(SELECT nama_unsur_pelayanan FROM unsur_pelayanan_$table_identity WHERE id_sub = unsur_pelayanan_$table_identity.id) as nama_unsur_pelayanan, unsur_pelayanan_$table_identity.id AS id_unsur
		
		FROM jawaban_pertanyaan_unsur_$table_identity 
		JOIN pertanyaan_unsur_pelayanan_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id 
		JOIN unsur_pelayanan_$table_identity ON pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan = unsur_pelayanan_$table_identity.id
		JOIN survey_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden
		WHERE survey_$table_identity.is_submit = 1 AND jawaban_pertanyaan_unsur_$table_identity.skor_jawaban != '0.0'
		GROUP BY id_sub
		ORDER BY unsur_pelayanan_$table_identity.id");

        // $this->data['nama_per_unsur'] = $this->db->query("SELECT GROUP_CONCAT(nomor_unsur ORDER BY unsur_pelayanan_$table_identity.id DESC SEPARATOR '|') AS nomor_unsur FROM unsur_pelayanan_$table_identity WHERE id_parent = 0")->row()->nomor_unsur;

        $bobot_per_unsur = [];
        foreach ($this->data['nilai_per_unsur']->result() as $value) {
            $nama_per_unsur[] = "'" . str_replace(' ', '+', $value->nomor_unsur) . "'";// . "+=+" . ROUND($value->nilai_per_unsur, 3) . "'";
            $bobot_per_unsur[] = ROUND($value->nilai_per_unsur,3);
        }
        $this->data['nama_per_unsur'] = implode(", ", $nama_per_unsur);
        $this->data['bobot_per_unsur'] = implode(", ", $bobot_per_unsur);


        //NILAI INDEX
        $this->db->select("nama_unsur_pelayanan, IF(id_parent = 0,unsur_pelayanan_$table_identity.id, unsur_pelayanan_$table_identity.id_parent) AS id_sub, (SUM(skor_jawaban)/COUNT(DISTINCT survey_$table_identity.id_responden)) AS rata_rata,  (COUNT(id_parent)/COUNT(DISTINCT survey_$table_identity.id_responden)) AS colspan, ((SUM(skor_jawaban)/COUNT(DISTINCT survey_$table_identity.id_responden))/(COUNT(id_parent)/COUNT(DISTINCT survey_$table_identity.id_responden))) AS nilai, (((SUM(skor_jawaban)/COUNT(DISTINCT survey_$table_identity.id_responden))/(COUNT(id_parent)/COUNT(DISTINCT survey_$table_identity.id_responden)))/(SELECT COUNT(id) FROM unsur_pelayanan_$table_identity WHERE id_parent = 0)) AS rata_rata_bobot");
        $this->db->from('jawaban_pertanyaan_unsur_' . $table_identity);
        $this->db->join("pertanyaan_unsur_pelayanan_$table_identity", "jawaban_pertanyaan_unsur_$table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id");
        $this->db->join("unsur_pelayanan_$table_identity", "pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan = unsur_pelayanan_$table_identity.id");
        $this->db->join("survey_$table_identity", "jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden");
        $this->db->where("survey_$table_identity.is_submit = 1");
        $this->db->where("jawaban_pertanyaan_unsur_$table_identity.skor_jawaban != '0.0'");
        $this->db->group_by('id_sub');
        $rata_rata_bobot = $this->db->get();

        foreach ($rata_rata_bobot->result() as $rata_rata_bobot) {
            $nilai_bobot[] = $rata_rata_bobot->rata_rata_bobot;
            $ikm_nilai_tertimbang = array_sum($nilai_bobot);
            $ikm = ROUND($ikm_nilai_tertimbang * $skala_likert, 10);
        }

        foreach ($this->db->query("SELECT * FROM definisi_skala_$table_identity ORDER BY id DESC")->result() as $obj) {
            if ($ikm <= $obj->range_bawah && $ikm >= $obj->range_atas) {
                $this->data['ketegori'] = $obj->kategori;
                $this->data['mutu_pelayanan'] = $obj->mutu;
            }
        }
        if ($ikm <= 0) {
            $this->data['ketegori'] = 'NULL';
            $this->data['mutu_pelayanan'] = 'NULL';
        }

        // if ($ikm <= 100 && $ikm >= 88.31) {
        //     $this->data['index'] = 'Sangat Baik';
        //     $this->data['mutu_pelayanan'] = 'A';
        // } elseif ($ikm <= 88.40 && $ikm >= 76.61) {
        //     $this->data['index'] = 'Baik';
        //     $this->data['mutu_pelayanan'] = 'B';
        // } elseif ($ikm <= 76.60 && $ikm >= 65) {
        //     $this->data['index'] = 'Kurang Baik';
        //     $this->data['mutu_pelayanan'] = 'C';
        // } elseif ($ikm <= 64.99 && $ikm >= 25) {
        //     $this->data['index'] = 'Tidak Baik';
        //     $this->data['mutu_pelayanan'] = 'D';
        // } else {
        //     $this->data['index'] = 'NULL';
        //     $this->data['mutu_pelayanan'] = 'NULL';
        // }
        $this->data['nilai_tertimbang'] = $ikm_nilai_tertimbang;
        $this->data['nilai_skm'] = $ikm;


        //GRAFIK UNSUR
        // $this->db->select("*, unsur_pelayanan_$table_identity.id AS id_unsur_pelayanan");
        // $this->db->from("unsur_pelayanan_$table_identity");
        // $this->db->where(['id_parent' => 0]);
        // $this->data['unsur_pelayanan'] = $this->db->get();
    }



    public function _get_unsur_tertinggi_terendah($table_identity)
    {
        //UNSUR TERENDAH DAN TERTINGGI
        $nilai_per_unsur_desc = $this->db->query("SELECT IF(id_parent = 0,unsur_pelayanan_$table_identity.id, unsur_pelayanan_$table_identity.id_parent) AS id_sub, (SUM(skor_jawaban)/COUNT(DISTINCT id_responden)) AS rata_rata,  (COUNT(id_parent)/COUNT(DISTINCT id_responden)) AS colspan, ((SUM(skor_jawaban)/COUNT(DISTINCT id_responden))/(COUNT(id_parent)/COUNT(DISTINCT id_responden))) AS nilai_per_unsur, (SELECT nomor_unsur FROM unsur_pelayanan_$table_identity WHERE id_sub = unsur_pelayanan_$table_identity.id) as nomor_unsur, (SELECT nama_unsur_pelayanan FROM unsur_pelayanan_$table_identity WHERE id_sub = unsur_pelayanan_$table_identity.id) as nama_unsur_pelayanan
        FROM jawaban_pertanyaan_unsur_$table_identity
        JOIN pertanyaan_unsur_pelayanan_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id
        JOIN unsur_pelayanan_$table_identity ON pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan = unsur_pelayanan_$table_identity.id
        GROUP BY id_sub
        ORDER BY nilai_per_unsur DESC
        LIMIT 3");

        $nilai_per_unsur_asc = $this->db->query("SELECT IF(id_parent = 0,unsur_pelayanan_$table_identity.id, unsur_pelayanan_$table_identity.id_parent) AS id_sub, (SUM(skor_jawaban)/COUNT(DISTINCT id_responden)) AS rata_rata,  (COUNT(id_parent)/COUNT(DISTINCT id_responden)) AS colspan, ((SUM(skor_jawaban)/COUNT(DISTINCT id_responden))/(COUNT(id_parent)/COUNT(DISTINCT id_responden))) AS nilai_per_unsur, (SELECT nomor_unsur FROM unsur_pelayanan_$table_identity WHERE id_sub = unsur_pelayanan_$table_identity.id) as nomor_unsur, (SELECT nama_unsur_pelayanan FROM unsur_pelayanan_$table_identity WHERE id_sub = unsur_pelayanan_$table_identity.id) as nama_unsur_pelayanan
        FROM jawaban_pertanyaan_unsur_$table_identity
        JOIN pertanyaan_unsur_pelayanan_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id
        JOIN unsur_pelayanan_$table_identity ON pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan = unsur_pelayanan_$table_identity.id
        GROUP BY id_sub
        ORDER BY nilai_per_unsur ASC
        LIMIT 3");

        $asc = [];
        foreach ($nilai_per_unsur_asc->result() as $value) {
            $asc[] = $value->nomor_unsur . '. ' . $value->nama_unsur_pelayanan;
        }
        $this->data['asc'] = implode("<br>", $asc);

        $desc = [];
        foreach ($nilai_per_unsur_desc->result() as $get) {
            $desc[] = $get->nomor_unsur . '. ' . $get->nama_unsur_pelayanan;
        }
        $this->data['desc'] = implode("<br>", $desc);
    }




    public function _get_kuadran($table_identity)
    {
        $this->db->select('COUNT(id) AS jumlah_unsur');
        $this->db->from('unsur_pelayanan_' . $table_identity);
        $this->db->where('id_parent = 0');
        $jumlah_unsur = $this->db->get()->row()->jumlah_unsur;
        $this->data['jumlah_unsur'] = $jumlah_unsur;

        //SKOR JAWABAN UNSUR
        $this->db->select('*');
        $this->db->from('jawaban_pertanyaan_unsur_' . $table_identity);
        $this->db->join("survey_$table_identity", "jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden");
        $this->db->where("survey_$table_identity.is_submit = 1");
        $this->data['skor'] = $this->db->get();

        //NILAI PER UNSUR
        $this->db->select("IF(id_parent = 0,unsur_pelayanan_$table_identity.id, unsur_pelayanan_$table_identity.id_parent) AS id_sub, ((SUM(skor_jawaban)/COUNT(DISTINCT survey_$table_identity.id_responden))/(COUNT(id_parent)/COUNT(DISTINCT survey_$table_identity.id_responden))) AS nilai_per_unsur, (SELECT nomor_unsur FROM unsur_pelayanan_$table_identity WHERE id_sub = unsur_pelayanan_$table_identity.id) as nomor_unsur, (SELECT nama_unsur_pelayanan FROM unsur_pelayanan_$table_identity WHERE id_sub = unsur_pelayanan_$table_identity.id) as nama_unsur_pelayanan");
        $this->db->from('jawaban_pertanyaan_unsur_' . $table_identity);
        $this->db->join("pertanyaan_unsur_pelayanan_$table_identity", "jawaban_pertanyaan_unsur_$table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id");
        $this->db->join("unsur_pelayanan_$table_identity", "pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan = unsur_pelayanan_$table_identity.id");
        $this->db->join("survey_$table_identity", "jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden");
        $this->db->where("survey_$table_identity.is_submit = 1");
        $this->db->where("jawaban_pertanyaan_unsur_$table_identity.skor_jawaban != '0.0'");
        $this->db->group_by('id_sub');
        $object_unsur = $this->db->get();
        $this->data['nilai_per_unsur'] = $object_unsur;


        $nilai_unsur = 0;
        foreach ($object_unsur->result() as $values) {
            $nilai_unsur += $values->nilai_per_unsur;
        }

        //NILAI PER HARAPAN
        $this->db->select("((SUM(skor_jawaban)/COUNT(DISTINCT survey_$table_identity.id_responden))/(COUNT(id_parent)/COUNT(DISTINCT survey_$table_identity.id_responden))) AS nilai_per_unsur");
        $this->db->from("jawaban_pertanyaan_harapan_$table_identity");
        $this->db->join("pertanyaan_unsur_pelayanan_$table_identity", "jawaban_pertanyaan_harapan_$table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id");
        $this->db->join("unsur_pelayanan_$table_identity", "pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan = unsur_pelayanan_$table_identity.id");
        $this->db->join("survey_$table_identity", "jawaban_pertanyaan_harapan_$table_identity.id_responden = survey_$table_identity.id_responden");
        $this->db->where("survey_$table_identity.is_submit = 1");
        $this->db->group_by("IF(id_parent = 0,unsur_pelayanan_$table_identity.id, unsur_pelayanan_$table_identity.id_parent)");
        $object_harapan = $this->db->get();
        $this->data['nilai_per_unsur_harapan'] = $object_harapan;

        $nilai_harapan = 0;
        foreach ($object_harapan->result() as $rows) {
            $nilai_harapan += $rows->nilai_per_unsur;
        }


        $query =  $this->db->query("SELECT nama_unsur_pelayanan, IF(id_parent = 0,unsur_pelayanan_$table_identity.id, unsur_pelayanan_$table_identity.id_parent) AS id_sub,

		ROUND((SUM(
		(SELECT SUM(skor_jawaban) FROM jawaban_pertanyaan_unsur_$table_identity JOIN responden_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = responden_$table_identity.id
JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
WHERE is_submit = 1 && pertanyaan_unsur_pelayanan_$table_identity.id = jawaban_pertanyaan_unsur_$table_identity.id_pertanyaan_unsur))/(SELECT COUNT(survey_$table_identity.id_responden) FROM jawaban_pertanyaan_unsur_$table_identity 
		JOIN survey_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden
		WHERE pertanyaan_unsur_pelayanan_$table_identity.id = jawaban_pertanyaan_unsur_$table_identity.id_pertanyaan_unsur && survey_$table_identity.is_submit = 1)/COUNT(id_parent)),2) AS skor_unsur,
		
		ROUND((SUM(
		(SELECT SUM(skor_jawaban) FROM jawaban_pertanyaan_harapan_$table_identity JOIN responden_$table_identity ON jawaban_pertanyaan_harapan_$table_identity.id_responden = responden_$table_identity.id
JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
WHERE is_submit = 1 && pertanyaan_unsur_pelayanan_$table_identity.id = jawaban_pertanyaan_harapan_$table_identity.id_pertanyaan_unsur))/(SELECT COUNT(survey_$table_identity.id_responden) FROM jawaban_pertanyaan_harapan_$table_identity 
		JOIN survey_$table_identity ON jawaban_pertanyaan_harapan_$table_identity.id_responden = survey_$table_identity.id_responden
		WHERE pertanyaan_unsur_pelayanan_$table_identity.id = jawaban_pertanyaan_harapan_$table_identity.id_pertanyaan_unsur && survey_$table_identity.is_submit = 1)/COUNT(id_parent)),2) AS skor_harapan,
		
		IF(is_sub_unsur_pelayanan = 1,SUBSTR(nomor_unsur,1, 3), nomor_unsur) AS nomor
		
		FROM pertanyaan_unsur_pelayanan_$table_identity
		JOIN unsur_pelayanan_$table_identity ON pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan = unsur_pelayanan_$table_identity.id
		GROUP BY id_sub");
        $this->data['grafik'] = $query;
        // var_dump($this->data['grafik']->result());


        if ($this->data['skor']->num_rows() > 0) {
            $this->data['skor'] = $this->data['skor'];
            $this->data['total_rata_unsur'] = $nilai_unsur / $jumlah_unsur;
            $this->data['total_rata_harapan'] = $nilai_harapan / $jumlah_unsur;
        } else {
            $this->data['pesan'] = 'survei belum dimulai atau belum ada responden !';
            return view('not_questions/index', $this->data);
            exit();
        }
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