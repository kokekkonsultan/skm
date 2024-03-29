<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DrafKuesionerController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library('Pdf');
    }


    public function index($id1, $id2)
    {

        $this->data = [];
        $this->data['title'] = 'Detail Pertanyaan Unsur';

        // get tabel identity
        $this->db->select('*, manage_survey.id AS id_manage_survey');
        $this->db->from('manage_survey');
        $this->db->where('manage_survey.slug', $this->uri->segment(2));
        $this->data['manage_survey'] = $this->db->get()->row();
        $table_identity = $this->data['manage_survey']->table_identity;

        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('id=' . $this->data['manage_survey']->id_user);
        $this->data['user'] = $this->db->get()->row();

        $this->data['profil_responden'] = $this->db->query("SELECT *, (SELECT COUNT(id) FROM kategori_profil_responden_$table_identity WHERE id_profil_responden = profil_responden_$table_identity.id) AS total_kategori FROM profil_responden_$table_identity")->result();

        //PERTANYAAN UNSUR
        $this->data['pertanyaan_unsur'] = $this->db->query("SELECT *, (SELECT nama_kategori_unsur_pelayanan FROM kategori_unsur_pelayanan_$table_identity WHERE id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id && nomor_kategori_unsur_pelayanan = 1 ) AS pilihan_1,
        (SELECT nama_kategori_unsur_pelayanan FROM kategori_unsur_pelayanan_$table_identity WHERE id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id && nomor_kategori_unsur_pelayanan = 2 ) AS pilihan_2,
        (SELECT nama_kategori_unsur_pelayanan FROM kategori_unsur_pelayanan_$table_identity WHERE id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id && nomor_kategori_unsur_pelayanan = 3 ) AS pilihan_3,
        (SELECT nama_kategori_unsur_pelayanan FROM kategori_unsur_pelayanan_$table_identity WHERE id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id && nomor_kategori_unsur_pelayanan = 4 ) AS pilihan_4
        FROM pertanyaan_unsur_pelayanan_$table_identity
        JOIN unsur_pelayanan_$table_identity ON pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan = unsur_pelayanan_$table_identity.id");


        //PERTANYAAN HARAPAN
        $this->data['pertanyaan_harapan'] = $this->db->query("SELECT *, (SELECT nama_tingkat_kepentingan FROM nilai_tingkat_kepentingan_$table_identity WHERE id_pertanyaan_unsur_pelayanan = pertanyaan_unsur_pelayanan_$table_identity.id && nomor_tingkat_kepentingan = 1 ) AS pilihan_1,
        (SELECT nama_tingkat_kepentingan FROM nilai_tingkat_kepentingan_$table_identity WHERE id_pertanyaan_unsur_pelayanan = pertanyaan_unsur_pelayanan_$table_identity.id && nomor_tingkat_kepentingan = 2 ) AS pilihan_2,
        (SELECT nama_tingkat_kepentingan FROM nilai_tingkat_kepentingan_$table_identity WHERE id_pertanyaan_unsur_pelayanan = pertanyaan_unsur_pelayanan_$table_identity.id && nomor_tingkat_kepentingan = 3 ) AS pilihan_3,
        (SELECT nama_tingkat_kepentingan FROM nilai_tingkat_kepentingan_$table_identity WHERE id_pertanyaan_unsur_pelayanan = pertanyaan_unsur_pelayanan_$table_identity.id && nomor_tingkat_kepentingan = 4 ) AS pilihan_4, (SELECT nomor_unsur FROM unsur_pelayanan_$table_identity WHERE id_unsur_pelayanan = unsur_pelayanan_$table_identity.id) AS nomor_unsur
        FROM pertanyaan_unsur_pelayanan_$table_identity");


        //PERTANYAAN KUALITATIF
        $this->data['pertanyaan_kualitatif'] = $this->db->get_where("pertanyaan_kualitatif_$table_identity", array('is_active' => 1));

        if ($this->data['pertanyaan_unsur']->num_rows() > 0) {

            $this->load->library('pdfgenerator');
            $this->data['title_pdf'] = 'Draf Kuesioner';
            $file_pdf = 'Draf Kuesioner';

            $paper = 'A4';
            $orientation = "potrait";

            $html = $this->load->view('draf_kuesioner/cetak', $this->data, true);
            $this->pdfgenerator->generate($html, $file_pdf, $paper, $orientation);

            // $this->load->view('draf_kuesioner/cetak', $this->data);
        } else {
            $this->data['pesan'] = 'Pertanyaan Belum di Isi !';
            return view('not_questions/index', $this->data);
            exit();
        }
    }



    public function tcpdf()
    {
        //START QUERY
        $this->db->select('*');
        $this->db->from('manage_survey');
        $this->db->where('manage_survey.slug', $this->uri->segment(2));
        $manage_survey = $this->db->get()->row();
        $table_identity = $manage_survey->table_identity;
        $skala_likert = $manage_survey->skala_likert == 5 ? 5 : 4;

        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('id=' . $manage_survey->id_user);
        $user = $this->db->get()->row();



        //============================================= START NEW PDF BY TCPDF =============================================
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Hanif');
        $pdf->SetTitle('Draf Kuesioner ' . $manage_survey->survey_name);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);
        // $pdf->SetFont('dejavusans', '', 10);
        $pdf->AddPage('P', 'A4');





        //========================================  USER PROFIL =============================================
        if ($user->foto_profile == NULL) {
            $profil = '<img src="' . base_url() . 'assets/klien/foto_profile/200px.jpg" height="75" alt="">';
        } else {
            $profil = '<img src="' . base_url() . 'assets/klien/foto_profile/' . $user->foto_profile . '" height="75" alt="">';
        };

        $title_header = unserialize($manage_survey->title_header_survey);
        $title_1 = $title_header[0];
        $title_2 = $title_header[1];



        //========================================  PROFIL RESPONDEN =============================================
        $profil_responden = $this->db->query("SELECT * FROM profil_responden_$table_identity ORDER BY IF(urutan != '',urutan,id) ASC")->result();
        $nama_profil = [];
        foreach ($profil_responden as $get_profil) {

            if ($get_profil->jenis_isian == 1) {
                $kategori = [];
                foreach ($this->db->get_where("kategori_profil_responden_$table_identity", array('id_profil_responden' => $get_profil->id))->result() as $value) {
                    $kategori[] = '<li>' . $value->nama_kategori_profil_responden . '</li>';
                }
                $get_kategori = implode("", $kategori);
            } else {
                $get_kategori = '';
            };

            $nama_profil[] = '<tr style="font-size: 11px;"><td width="30%" style="height:15px;" valign="top">' . $get_profil->nama_profil_responden . ' </td><td width="70%"><ul style="list-style-type:img|png|3|3|' . base_url() . 'assets/img/site/vector/check.png">' . $get_kategori . '</ul></td></tr>';
        }
        $get_nama = implode("", $nama_profil);


        //CEK MENGGUNAKAN JENIS LAYANAN ATAU TIDAK
        if ($manage_survey->is_layanan_survei != 0) {

            //CEK MENGGUNAKAN KATEGORI LAYANAN ATAU TIDAK
            if ($manage_survey->is_kategori_layanan_survei == 1) {
                $kategori = $this->db->query("SELECT * FROM kategori_layanan_survei_$table_identity WHERE is_active = 1 ORDER BY urutan ASC");

                $get_lynn = [];
                foreach ($kategori->result() as $key => $value) {
                    $nama_layanan[$key] = [];
                    foreach ($this->db->get_where("layanan_survei_$table_identity", array('is_active' => 1, 'id_kategori_layanan' => $value->id))->result() as $row) {
                        $nama_layanan[$key][] = '<li>' . $row->nama_layanan . '</li>';
                    }

                    $get_lynn[] = '<table>
                    <tr>
                        <td width="25%">'  .  $value->nama_kategori_layanan . '</td>
                        <td width="75%">
                            <ul style="list-style-type:img|png|3|3|' . base_url() . 'assets/img/site/vector/check.png">'  . implode("", $nama_layanan[$key]) .  '</ul>
                        </td>
                    </tr>
                    </table>';
                }
                $get_layanan = implode("<br><br>", $get_lynn);
            } else {

                $nama_layanan = [];
                foreach ($this->db->get_where("layanan_survei_$table_identity", array('is_active' => 1))->result() as $row) {
                    $nama_layanan[] = '<li>' . $row->nama_layanan . '</li>';
                }
                $get_layanan = '<ul style="list-style-type:img|png|3|3|' . base_url() . 'assets/img/site/vector/check.png">' . implode("", $nama_layanan) . '</ul>';
            }


            $layanan_survei = '<tr style="font-size: 11px;">
            <td width=" 30%" style="height:15px;">Jenis Pelayanan yang diterima</td>
            <td width="70%">' . $get_layanan . '</td>
            </tr>';
        } else {
            $layanan_survei = '';
        }



        //CEK SKALA TERLEBIH DAHULU SEBELUM MEMBUAT JUDUL TABEL
        if ($skala_likert == 5) {
            $thead_tabel_unsur = '<table width="100%" style="font-size: 11px; text-align:center; background-color:#C7C6C1" border="1" cellpadding="3">
            <tr>
                <td rowspan="2" width="7%">No</td>
                <td rowspan="2" width="30%">PERTANYAAN</td>
                <td colspan="5" width="40%">PILIHAN JAWABAN</td>
                <td rowspan="2" width="23%">Berikan alasan jika pilihan jawaban: 1 atau 2
                </td>
            </tr>
            <tr>
                <td>1</td>
                <td>2</td>
                <td>3</td>
                <td>4</td>
                <td>5</td>
            </tr>
        </table>';

            $thead_tabel_harapan = '<table width="100%" style="font-size: 11px; text-align:center; background-color:#C7C6C1" border="1" cellpadding="3">
            <tr>
                <td rowspan="2" width="7%">No</td>
                <td rowspan="2" width="30%">PERTANYAAN</td>
                <td colspan="5" width="63%">PILIHAN JAWABAN</td>
            </tr>
            <tr>
                <td>1</td>
                <td>2</td>
                <td>3</td>
                <td>4</td>
                <td>5</td>
            </tr>
        </table>';
        } else {

            $thead_tabel_unsur = '<table width="100%" style="font-size: 11px; text-align:center; background-color:#C7C6C1" border="1" cellpadding="3">
            <tr>
                <td rowspan="2" width="7%">No</td>
                <td rowspan="2" width="30%">PERTANYAAN</td>
                <td colspan="4" width="40%">PILIHAN JAWABAN</td>
                <td rowspan="2" width="23%">Berikan alasan jika pilihan jawaban: 1 atau 2
                </td>
            </tr>
            <tr>
                <td>1</td>
                <td>2</td>
                <td>3</td>
                <td>4</td>
            </tr>
        </table>';

            $thead_tabel_harapan = '<table width="100%" style="font-size: 11px; text-align:center; background-color:#C7C6C1" border="1" cellpadding="3">
            <tr>
                <td rowspan="2" width="7%">No</td>
                <td rowspan="2" width="30%">PERTANYAAN</td>
                <td colspan="4" width="63%">PILIHAN JAWABAN</td>
            </tr>
            <tr>
                <td>1</td>
                <td>2</td>
                <td>3</td>
                <td>4</td>
            </tr>
        </table>';
        }





        //=================================== PERTANYAAN TERBUKA ATAS ==========================================
        if (in_array(2, unserialize($manage_survey->atribut_pertanyaan_survey))) {

            $pertanyaan_terbuka_atas = $this->db->query("SELECT *, perincian_pertanyaan_terbuka_$table_identity.id AS id_perincian_pertanyaan_terbuka, (SELECT DISTINCT(dengan_isian_lainnya) FROM isi_pertanyaan_ganda_$table_identity WHERE isi_pertanyaan_ganda_$table_identity.id_perincian_pertanyaan_terbuka = perincian_pertanyaan_terbuka_$table_identity.id) AS dengan_isian_lainnya
            FROM pertanyaan_terbuka_$table_identity
            JOIN perincian_pertanyaan_terbuka_$table_identity ON pertanyaan_terbuka_$table_identity.id = perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka
            WHERE pertanyaan_terbuka_$table_identity.is_letak_pertanyaan = 1");

            if ($pertanyaan_terbuka_atas->num_rows() > 0) {

                $per_terbuka_atas = [];
                foreach ($pertanyaan_terbuka_atas->result() as $value) {

                    if ($value->id_jenis_pilihan_jawaban == 2) {

                        $per_terbuka_atas[] = '
                    <table width="100%" style="font-size: 11px;" border="1" cellpadding="3">
                        <tr>
                            <td width="7%" style="text-align:center; font-size: 11px;">' . $value->nomor_pertanyaan_terbuka . '</td>
                            <td width="30%" style="text-align:left; font-size: 11px;">' . $value->isi_pertanyaan_terbuka . '</td>
                            <td width="40%"></td>
                            <td width="23%" style="text-align:left; font-size: 11px;"></td>
                        </tr>
                    </table>';
                    } else {

                        $pilihan_terbuka_atas = [];
                        foreach ($this->db->get_where("isi_pertanyaan_ganda_$table_identity", array('id_perincian_pertanyaan_terbuka' => $value->id_perincian_pertanyaan_terbuka))->result() as $get) {

                            $pilihan_terbuka_atas[] = '<tr>
                        <td width="4%"></td>
                        <td width="36%" style="background-color:#C7C6C1;">' . $get->pertanyaan_ganda . '</td>
                        </tr>';
                        }



                        if ($value->dengan_isian_lainnya == 1) {

                            $isi_terbuka_atas[$value->nomor_pertanyaan_terbuka] = $this->db->get_where("isi_pertanyaan_ganda_$table_identity", array('id_perincian_pertanyaan_terbuka' => $value->id_perincian_pertanyaan_terbuka))->num_rows() + 2;
                        } else {

                            $isi_terbuka_atas[$value->nomor_pertanyaan_terbuka] = $this->db->get_where("isi_pertanyaan_ganda_$table_identity", array('id_perincian_pertanyaan_terbuka' => $value->id_perincian_pertanyaan_terbuka))->num_rows() + 1;
                        }

                        $per_terbuka_atas[] = '
                        <table width="100%" style="font-size: 11px;" border="1" cellpadding="3">
                            <tr>
                                <td rowspan="' . $isi_terbuka_atas[$value->nomor_pertanyaan_terbuka] . '" width="7%" style="text-align:center; font-size: 11px;">' . $value->nomor_pertanyaan_terbuka . '</td>

                                <td width="30%" rowspan="' . $isi_terbuka_atas[$value->nomor_pertanyaan_terbuka] . '" style="text-align:left; font-size: 11px;">' . $value->isi_pertanyaan_terbuka . '</td>

                                <td colspan="2" width="40%"></td>
                                        
                                <td width="23%"rowspan="' . $isi_terbuka_atas[$value->nomor_pertanyaan_terbuka] . '" style="text-align:left; font-size: 11px;"></td>
                            </tr>' . implode("", $pilihan_terbuka_atas) . '
                    </table>';
                    }
                }
                $get_pertanyaan_terbuka_atas = implode("", $per_terbuka_atas);
            } else {
                $get_pertanyaan_terbuka_atas = '';
            }
        } else {
            $get_pertanyaan_terbuka_atas = '';
        };




        //============================================= PERTANYAAN UNSUR =============================================
        $pertanyaan_unsur = $this->db->query("SELECT *, (SELECT nama_kategori_unsur_pelayanan FROM kategori_unsur_pelayanan_$table_identity WHERE id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id && nomor_kategori_unsur_pelayanan = 1 ) AS pilihan_1,
        (SELECT nama_kategori_unsur_pelayanan FROM kategori_unsur_pelayanan_$table_identity WHERE id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id && nomor_kategori_unsur_pelayanan = 2 ) AS pilihan_2,
        (SELECT nama_kategori_unsur_pelayanan FROM kategori_unsur_pelayanan_$table_identity WHERE id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id && nomor_kategori_unsur_pelayanan = 3 ) AS pilihan_3,
        (SELECT nama_kategori_unsur_pelayanan FROM kategori_unsur_pelayanan_$table_identity WHERE id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id && nomor_kategori_unsur_pelayanan = 4 ) AS pilihan_4,
        (SELECT nama_kategori_unsur_pelayanan FROM kategori_unsur_pelayanan_$table_identity WHERE id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id && nomor_kategori_unsur_pelayanan = 5 ) AS pilihan_5
        FROM pertanyaan_unsur_pelayanan_$table_identity
        JOIN unsur_pelayanan_$table_identity ON pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan = unsur_pelayanan_$table_identity.id");

        $per_unsur = [];
        foreach ($pertanyaan_unsur->result() as $row) {


            if (in_array(2, unserialize($manage_survey->atribut_pertanyaan_survey))) {

                $pertanyaan_terbuka = $this->db->query("SELECT *, perincian_pertanyaan_terbuka_$table_identity.id AS id_perincian_pertanyaan_terbuka, (SELECT DISTINCT(dengan_isian_lainnya) FROM isi_pertanyaan_ganda_$table_identity WHERE isi_pertanyaan_ganda_$table_identity.id_perincian_pertanyaan_terbuka = perincian_pertanyaan_terbuka_$table_identity.id) AS dengan_isian_lainnya
            FROM pertanyaan_terbuka_$table_identity
            JOIN perincian_pertanyaan_terbuka_$table_identity ON pertanyaan_terbuka_$table_identity.id = perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka
            WHERE  id_unsur_pelayanan = $row->id_unsur_pelayanan");

                $per_terbuka = [];
                foreach ($pertanyaan_terbuka->result() as $value) {


                    if ($value->id_jenis_pilihan_jawaban == 2) {

                        $per_terbuka[] = '
                        <table width="100%" style="font-size: 11px;" border="1" cellpadding="3">
                            <tr>
                                <td width="7%" style="text-align:center; font-size: 11px;">' . $value->nomor_pertanyaan_terbuka . '</td>
                                <td width="30%" style="text-align:left; font-size: 11px;">' . $value->isi_pertanyaan_terbuka . '</td>
                                <td width="40%"></td>
                                <td width="23%" style="text-align:left; font-size: 11px;"></td>
                            </tr>
                        </table>
                    ';
                    } else {

                        $pilihan_terbuka = [];
                        foreach ($this->db->get_where("isi_pertanyaan_ganda_$table_identity", array('id_perincian_pertanyaan_terbuka' => $value->id_perincian_pertanyaan_terbuka))->result() as $get) {

                            $pilihan_terbuka[] = '<tr>
                            <td width="4%"></td>
                            <td width="36%" style="background-color:#C7C6C1;">' . $get->pertanyaan_ganda . '</td>
                            </tr>';
                        }

                        if ($value->dengan_isian_lainnya == 1) {

                            $isi[$value->nomor_pertanyaan_terbuka] = $this->db->get_where("isi_pertanyaan_ganda_$table_identity", array('id_perincian_pertanyaan_terbuka' => $value->id_perincian_pertanyaan_terbuka))->num_rows() + 2;
                        } else {

                            $isi[$value->nomor_pertanyaan_terbuka] = $this->db->get_where("isi_pertanyaan_ganda_$table_identity", array('id_perincian_pertanyaan_terbuka' => $value->id_perincian_pertanyaan_terbuka))->num_rows() + 1;
                        }


                        $per_terbuka[] = '
                        <table width="100%" style="font-size: 11px;" border="1" cellpadding="3">
                            <tr>
                                <td rowspan="' . $isi[$value->nomor_pertanyaan_terbuka] . '" width="7%" style="text-align:center; font-size: 11px;">' . $value->nomor_pertanyaan_terbuka . '</td>

                                <td width="30%" rowspan="' . $isi[$value->nomor_pertanyaan_terbuka] . '" style="text-align:left; font-size: 11px;">' . $value->isi_pertanyaan_terbuka . '</td>

                                <td colspan="2" width="40%"></td>
                                
                                <td width="23%"rowspan="' . $isi[$value->nomor_pertanyaan_terbuka] . '" style="text-align:left; font-size: 11px;"></td>
                            </tr>' . implode("", $pilihan_terbuka) . '
                        </table>
                    ';
                    }
                }
                $get_pertanyaan_terbuka = implode("", $per_terbuka);
            } else {
                $get_pertanyaan_terbuka = '';
            }



            //CEK SKALA TERLEBIH DAHULU
            if ($skala_likert == 5) {
                $pilihan_ke_2 = $row->pilihan_5;
                $width = 8;
                $pilihan_ke_5 = '<td width="8%" style="background-color:#C7C6C1; text-align:center; font-size: 11px;">' . $row->pilihan_5 . '</td>';
                $ke_5 = '<th></th>';
            } else {
                $pilihan_ke_2 = $row->pilihan_4;
                $width = 10;
                $pilihan_ke_5 = '';
                $ke_5 = '';
            }


            if ($row->jenis_pilihan_jawaban == 1) {

                $per_unsur[] = '
                <table width="100%" border="1" cellpadding="4">
                    <tr>
                        <td rowspan="2" width="7%" style="text-align:center; font-size: 11px;">' . $row->nomor_unsur . '</td>
                        <td width="30%" rowspan="2" style="text-align:left; font-size: 11px;">' . $row->isi_pertanyaan_unsur . '</td>
                        <td width="20%" style="background-color:#C7C6C1; text-align:center; font-size: 11px;">' . $row->pilihan_1 . '</td>
                        <td width="20%" style="background-color:#C7C6C1; text-align:center; font-size: 11px;">' . $pilihan_ke_2 . '</td>
                        <td width="23%" rowspan="2" style="text-align:left; font-size: 11px;"></td>
                    </tr>

                    <tr>
                        <th></th>
                        <th></th>
                    </tr>
                </table>
            ' . $get_pertanyaan_terbuka;
            } else {


                $per_unsur[] = '
            <table width="100%" border="1" cellpadding="4">
                <tr>
                    <td rowspan="2" width="7%" style="text-align:center; font-size: 11px;">' . $row->nomor_unsur . '</td>
                    <td width="30%" rowspan="2" style="text-align:left; font-size: 11px;">' . $row->isi_pertanyaan_unsur . '</td>
                    <td width="' . $width . '%" style="background-color:#C7C6C1; text-align:center; font-size: 11px;">' . $row->pilihan_1 . '</td>
                    <td width="' . $width . '%" style="background-color:#C7C6C1; text-align:center; font-size: 11px;">' . $row->pilihan_2 . '</td>
                    <td width="' . $width . '%" style="background-color:#C7C6C1; text-align:center; font-size: 11px;">' . $row->pilihan_3 . '</td>
                    <td width="' . $width . '%" style="background-color:#C7C6C1; text-align:center; font-size: 11px;">' . $row->pilihan_4 . '</td>' . $pilihan_ke_5 . '
                    <td width="23%" rowspan="2" style="text-align:left; font-size: 11px;"></td>
                </tr>

                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>'
                    . $ke_5 .
                    '</tr>
            </table>
            ' . $get_pertanyaan_terbuka;
            }
        }
        $get_pertanyaan_unsur = implode("", $per_unsur);





        //============================================= PERTANYAAN TERBUKA BAWAH =========================================
        if (in_array(2, unserialize($manage_survey->atribut_pertanyaan_survey))) {

            $pertanyaan_terbuka_bawah = $this->db->query("SELECT *, perincian_pertanyaan_terbuka_$table_identity.id AS id_perincian_pertanyaan_terbuka, (SELECT DISTINCT(dengan_isian_lainnya) FROM isi_pertanyaan_ganda_$table_identity WHERE isi_pertanyaan_ganda_$table_identity.id_perincian_pertanyaan_terbuka = perincian_pertanyaan_terbuka_$table_identity.id) AS dengan_isian_lainnya
            FROM pertanyaan_terbuka_$table_identity
            JOIN perincian_pertanyaan_terbuka_$table_identity ON pertanyaan_terbuka_$table_identity.id = perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka
            WHERE pertanyaan_terbuka_$table_identity.is_letak_pertanyaan = 2");

            if ($pertanyaan_terbuka_bawah->num_rows() > 0) {

                $per_terbuka_bawah = [];
                foreach ($pertanyaan_terbuka_bawah->result() as $value) {

                    if ($value->id_jenis_pilihan_jawaban == 2) {

                        $per_terbuka_bawah[] = '
                <table width="100%" style="font-size: 11px;" border="1" cellpadding="3">
                    <tr>
                        <td width="7%" style="text-align:center; font-size: 11px;">' . $value->nomor_pertanyaan_terbuka . '</td>
                        <td width="30%" style="text-align:left; font-size: 11px;">' . $value->isi_pertanyaan_terbuka . '</td>
                        <td width="40%"></td>
                        <td width="23%" style="text-align:left; font-size: 11px;"></td>
                    </tr>
                </table>';
                    } else {

                        $pilihan_terbuka_bawah = [];
                        foreach ($this->db->get_where("isi_pertanyaan_ganda_$table_identity", array('id_perincian_pertanyaan_terbuka' => $value->id_perincian_pertanyaan_terbuka))->result() as $get) {

                            $pilihan_terbuka_bawah[] = '<tr>
                    <td width="4%"></td>
                    <td width="36%" style="background-color:#C7C6C1;">' . $get->pertanyaan_ganda . '</td>
                    </tr>';
                        }



                        if ($value->dengan_isian_lainnya == 1) {

                            $isi_terbuka_bawah[$value->nomor_pertanyaan_terbuka] = $this->db->get_where("isi_pertanyaan_ganda_$table_identity", array('id_perincian_pertanyaan_terbuka' => $value->id_perincian_pertanyaan_terbuka))->num_rows() + 2;
                        } else {

                            $isi_terbuka_bawah[$value->nomor_pertanyaan_terbuka] = $this->db->get_where("isi_pertanyaan_ganda_$table_identity", array('id_perincian_pertanyaan_terbuka' => $value->id_perincian_pertanyaan_terbuka))->num_rows() + 1;
                        }

                        $per_terbuka_bawah[] = '
                    <table width="100%" style="font-size: 11px;" border="1" cellpadding="3">
                        <tr>
                            <td rowspan="' . $isi_terbuka_bawah[$value->nomor_pertanyaan_terbuka] . '" width="7%" style="text-align:center; font-size: 11px;">' . $value->nomor_pertanyaan_terbuka . '</td>

                            <td width="30%" rowspan="' . $isi_terbuka_bawah[$value->nomor_pertanyaan_terbuka] . '" style="text-align:left; font-size: 11px;">' . $value->isi_pertanyaan_terbuka . '</td>

                            <td colspan="2" width="40%"></td>
                                    
                            <td width="23%"rowspan="' . $isi_terbuka_bawah[$value->nomor_pertanyaan_terbuka] . '" style="text-align:left; font-size: 11px;"></td>
                        </tr>' . implode("", $pilihan_terbuka_bawah) . '
                </table>';
                    }
                }
                $get_pertanyaan_terbuka_bawah = implode("", $per_terbuka_bawah);
            } else {
                $get_pertanyaan_terbuka_bawah = '';
            }
        } else {
            $get_pertanyaan_terbuka_bawah = '';
        };







        //PERTANYAAN HARAPAN
        if (in_array(1, unserialize($manage_survey->atribut_pertanyaan_survey))) {

            $pertanyaan_harapan = $this->db->query("SELECT *, (SELECT nama_tingkat_kepentingan FROM nilai_tingkat_kepentingan_$table_identity WHERE id_pertanyaan_unsur_pelayanan = pertanyaan_unsur_pelayanan_$table_identity.id && nomor_tingkat_kepentingan = 1 ) AS pilihan_1,
         (SELECT nama_tingkat_kepentingan FROM nilai_tingkat_kepentingan_$table_identity WHERE id_pertanyaan_unsur_pelayanan = pertanyaan_unsur_pelayanan_$table_identity.id && nomor_tingkat_kepentingan = 2 ) AS pilihan_2,
         (SELECT nama_tingkat_kepentingan FROM nilai_tingkat_kepentingan_$table_identity WHERE id_pertanyaan_unsur_pelayanan = pertanyaan_unsur_pelayanan_$table_identity.id && nomor_tingkat_kepentingan = 3 ) AS pilihan_3,
         (SELECT nama_tingkat_kepentingan FROM nilai_tingkat_kepentingan_$table_identity WHERE id_pertanyaan_unsur_pelayanan = pertanyaan_unsur_pelayanan_$table_identity.id && nomor_tingkat_kepentingan = 4 ) AS pilihan_4, 
         (SELECT nama_tingkat_kepentingan FROM nilai_tingkat_kepentingan_$table_identity WHERE id_pertanyaan_unsur_pelayanan = pertanyaan_unsur_pelayanan_$table_identity.id && nomor_tingkat_kepentingan = 5 ) AS pilihan_5, (SELECT nomor_unsur FROM unsur_pelayanan_$table_identity WHERE id_unsur_pelayanan = unsur_pelayanan_$table_identity.id) AS nomor_unsur
         FROM pertanyaan_unsur_pelayanan_$table_identity");



            #CEK MODEL PILIHAN JAWABAN PERTANYAAN HARAPAN
            $per_harapan = [];
            if($manage_survey->is_model_pertanyaan_harapan == 2){

                $rev_thead_tabel_harapan = $thead_tabel_harapan;
                foreach ($pertanyaan_harapan->result() as $row) {
    
                        if ($skala_likert == 5) {
                            $width = 12.6;
                            $pilihan_ke_5 = '<td width="12.6%" style="background-color:#C7C6C1; text-align:center; font-size: 11px;">' . $row->pilihan_5 . '</td>';
                            $ke_5 = '<th></th>';
                        } else {
                            $width = 15.75;
                            $pilihan_ke_5 = '';
                            $ke_5 = '';
                        }
    
    
                    $per_harapan[] = '
                    <table width="100%" border="1" cellpadding="4">
                        <tr>
                            <td rowspan="2" width="7%" style="text-align:center; font-size: 11px;">H' . substr($row->nomor_unsur, 1) . '</td>
                            <td width="30%" rowspan="2" style="text-align:left; font-size: 11px;">' . $row->isi_pertanyaan_unsur . '</td>
                            <td width="' . $width . '%" style="background-color:#C7C6C1; text-align:center; font-size: 11px;">' . $row->pilihan_1 . '</td>
                            <td width="' . $width . '%" style="background-color:#C7C6C1; text-align:center; font-size: 11px;">' . $row->pilihan_2 . '</td>
                            <td width="' . $width . '%" style="background-color:#C7C6C1; text-align:center; font-size: 11px;">' . $row->pilihan_3 . '</td>
                            <td width="' . $width . '%" style="background-color:#C7C6C1; text-align:center; font-size: 11px;">' . $row->pilihan_4 . '</td>' . $pilihan_ke_5 . '
                        </tr>
    
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>'
                            . $ke_5 .
                            '</tr>
                    </table>';
                }
            } else {

                $bobot_harapan = $skala_likert == 5 ? 5 : 4;
                $rev_thead_tabel_harapan = '<table width="100%" style="font-size: 11px; text-align:center; background-color:#C7C6C1" border="1" cellpadding="3">
                <tr>
                    <td rowspan="2" width="7%">No</td>
                    <td rowspan="2" width="30%">PERTANYAAN</td>
                    <td colspan="2" width="63%">PILIHAN JAWABAN</td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>' . $bobot_harapan. '</td>
                </tr>
                </table>';


                foreach ($pertanyaan_harapan->result() as $row) {

                    $pilihan_2 = $skala_likert == 5 ? $row->pilihan_5 : $row->pilihan_4;
                    $per_harapan[] = '
                    <table width="100%" border="1" cellpadding="4">
                        <tr>
                            <td rowspan="2" width="7%" style="text-align:center; font-size: 11px;">H' . substr($row->nomor_unsur, 1) . '</td>
                            <td width="30%" rowspan="2" style="text-align:left; font-size: 11px;">' . $row->isi_pertanyaan_unsur . '</td>
                            <td width="31.5%" style="background-color:#C7C6C1; text-align:center; font-size: 11px;">' . $row->pilihan_1 . '</td>
                            <td width="31.5%" style="background-color:#C7C6C1; text-align:center; font-size: 11px;">' . $pilihan_2 . '</td>
                        </tr>
    
                        <tr>
                            <th></th>
                            <th></th>
                        </tr>
                    </table>';

                }
            }


            $get_pertanyaan_harapan = '<table style="width: 100%;" border="1" cellpadding="3">
            <tr>
                <td colspan="2" style="text-align:left; font-size: 11px; background-color: black; color:white;"><b>PENILAIAN HARAPAN TERHADAP UNSUR PELAYANAN BERDASARKAN TINGKAT KEPENTINGAN</b></td>
            </tr>

            <tr>
                <td colspan="2" style="text-align:left; font-size: 11px; background-color: black; color:white;">Berikan tanda silang (x) sesuai jawaban Saudara</td>
            </tr>
        </table>
        ' . $rev_thead_tabel_harapan . implode("", $per_harapan);
        } else {
            $get_pertanyaan_harapan = '';
        }






        // ======================================== PERTANYAAN KUALITATIF ======================================
        if (in_array(3, unserialize($manage_survey->atribut_pertanyaan_survey))) {

            $pertanyaan_kualitatif = $this->db->get_where("pertanyaan_kualitatif_$table_identity", array('is_active' => 1));
            $per_kualitatif = [];
            $no = 1;
            foreach ($pertanyaan_kualitatif->result() as $row) {
                $per_kualitatif[] = '
                <tr>
                    <td width="7%" style="text-align:center;">' . $no++ . '</td>
                    <td width="30%">' . $row->isi_pertanyaan . '</td>
                    <td width="63%"></td>
                </tr>
            ';
            }
            $get_pertanyaan_kualitatif = '<table style="width: 100%;" border="1" cellpadding="3">
            <tr>
                <td colspan="2" style="text-align:left; font-size: 11px; background-color: black; color:white;"><b>PENILAIAN KUALITATIF KEPUASAN MASYARAKAT</b></td>
            </tr>
    
            <tr>
                <td colspan="2" style="text-align:left; font-size: 11px; background-color: black; color:white;">Berikan jawaban sesuai dengan pendapat dan pengetahuan Saudara.</td>
            </tr>
        </table>
    
        <table width="100%" style="font-size: 11px; text-align:center; background-color:#C7C6C1" border="1" cellpadding="3">
            <tr>
                <td width="7%">No</td>
                <td width="30%">PERTANYAAN</td>
                <td width="63%">JAWABAN</td>
            </tr>
        </table>
    
        <table width="100%" style="font-size: 11px;" border="1" cellpadding="10">
            ' . implode("", $per_kualitatif) . '
        </table>';
        } else {
            $get_pertanyaan_kualitatif = '';
        }



        // =============================================== STATUS SARAN ================================================
        if ($manage_survey->is_saran == 1) {
            $is_saran = '<tr>
            <td colspan="2" style="text-align:left; font-size: 11px;"><b>' . $manage_survey->judul_form_saran . '</b>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
            </td>
        </tr>';
        } else {
            $is_saran = '';
        }







        // ============================================= GET HTML VIEW =============================================
        $html = '
        <table border="1" style="width: 100%;">
            <tr>
                <td>
                    <table border="0" style="width: 100%;" cellpadding="7">
                    <tr>
                        <td width="11%">' . $profil . '</td>
                        <td width="89%" style="font-size:12px; font-weight:bold;">' . strtoupper($title_1) . '<br>' . strtoupper($title_2) . '</td>
                    </tr>
                </table>
                </td>
            </tr>
        </table>
        

        <table  border="1" style="width: 100%;" cellpadding="7">
            <tr>
                <td style="text-align:center; font-size: 11px; font-family:Arial, Helvetica, sans-serif; height:35px;">Dalam
                    rangka meningkatkan kepuasan masyarakat, Saudara dipercaya menjadi responden pada kegiatan survei
                    ini.<br>
                    Atas kesediaan Saudara kami sampaikan terima kasih dan penghargaan sedalam-dalamnya.</td>
            </tr>
        </table>


        <table border="1" style="width: 100%;" cellpadding="3">
            <tr>
                <td style="font-size: 11px; background-color: black; color:white; height:15px;"><b>DATA RESPONDEN</b> (Berikan tanda silang (x) sesuai jawaban Saudara pada kolom yang tersedia)
                </td>
            </tr>
        </table>
        <table border="1" style="width: 100%;" cellpadding="4">' . $layanan_survei . $get_nama . '
        </table>
        
        
        <table style="width: 100%;" border="1" cellpadding="3">
            <tr>
                <td colspan="2" style="text-align:left; font-size: 11px; background-color: black; color:white;"><b>PENILAIAN TERHADAP UNSUR-UNSUR KEPUASAN MASYARAKAT</b></td>
            </tr>

            <tr>
                <td colspan="2" style="text-align:left; font-size: 11px; background-color: black; color:white;">Berikan tanda silang (x) sesuai jawaban Saudara<!-- dan berikan alasan jika jawaban Saudara negatif(Tidak
                    atau Kurang Baik)--></td>
            </tr>
        </table>' .

            $thead_tabel_unsur . $get_pertanyaan_terbuka_atas . $get_pertanyaan_unsur . $get_pertanyaan_terbuka_bawah .   $get_pertanyaan_harapan . $get_pertanyaan_kualitatif . '

        <table style="width: 100%;" border="1" cellpadding="5">' . $is_saran . '
            

            <tr>
                <td colspan="2" style="text-align:center; font-size: 11px;">Terima kasih atas kesediaan Saudara mengisi kuesioner tersebut di atas.<br>Saran dan penilaian Saudara memberikan konstribusi yang sangat berarti bagi peningkatan pelayanan.
                </td>
            </tr>
        </table>
    ';
        // // var_dump($html);
        $pdf->writeHTML($html, true, false, true, false, '');


        $pdf->lastPage();
        $pdf->Output('Draf Kuesioner ' . $manage_survey->survey_name . '.pdf', 'I');
    }

}
