<?php

defined('BASEPATH') or exit('No direct script access allowed');



class ReportController extends CI_Controller

{
    public function __construct()
    {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message_warning', 'You must be an admin to view this page');
            redirect('auth', 'refresh');
        }
    }

    public function download_docx($username, $slug)
    {
        $this->data = [];
        $this->data['title'] = "Laporan Survei";

        $manage_survey = $this->db->get_where("manage_survey", ['slug' => $slug])->row();
        $users = $this->db->get_where("users", ['username' => $username])->row();
        $table_identity = $manage_survey->table_identity;
        $atribut_pertanyaan = unserialize($manage_survey->atribut_pertanyaan_survey);
        $img_profile = $users->foto_profile != '' ? $users->foto_profile : '200px.jpg';
        $skala_likert = 100 / ($manage_survey->skala_likert == '' ? $manage_survey->skala_likert : 4);
        $definisi_skala = $this->db->get("definisi_skala_$table_identity");


        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        PhpOffice\PhpWord\Settings::setDefaultFontSize(11);
        $paragraphStyleName = 'pStyle';
        $phpWord->addParagraphStyle($paragraphStyleName, array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 100));
        $section = $phpWord->addSection();



        // Add first page header
        $header = $section->addHeader();
        $header->firstPage();

        // Add header for all other pages
        $subHeader = $section->addHeader();
        $htmlHeader = '<table style="width: 100%;">
                            <tr>
                                <td width="10%"><img src="' . base_url() . 'assets/klien/foto_profile/' . $img_profile . '" height="50" alt="" /></td>
                                <td width="90%" align="center"><b style="font-size:13.5px; color:#DE2226;">L A P O R A N   A K H I R</b>
                                <br/><span style="font-size:11px;">SURVEI KEPUASAN MASYARAKAT <br/>' . strtoupper($manage_survey->organisasi) . '</span></td>
                            </tr>
                        </table>';
        \PhpOffice\PhpWord\Shared\Html::addHtml($subHeader, $htmlHeader, false, false);
        $subHeader->addLine(['weight' => 1, 'width' => 450, 'height' => 0]);
        //  //END ==========================================================================================================


        // // Add footer ==========================================================================================================
        $footer = $section->addFooter();
        $footer->addLine(['weight' => 1, 'width' => 450, 'height' => 0]);
        $tableFooter = $footer->addTable('tableFooter');
        $tableFooter->addRow();
        $tableFooter->addCell(8000)->addText('SKM ' . date('Y'), ['name' => 'Arial', 'size' => 9.5], ['spaceAfter' => 0]);
        $tableFooter->addCell(1000)->addPreserveText('{PAGE}', ['name' => 'Arial', 'size' => 9], ['spaceAfter' => 0, 'align' => 'right']);
        $tableFooter->addRow();
        $tableFooter->addCell(8000)->addText('Generate by SurveiKu.com', ['name' => 'Arial', 'size' => 9.5, 'bold' => true,], ['spaceAfter' => 0]);
        // // //END ==================================================================================================================



        // // CSS HTML ==========================================================================================================
        $content_paragraph = 'text-align: justify; text-indent: 30pt;';
        $no_table = 1;
        $no_gambar = 1;



        // HALAMAN COVER LAPORAN ===============================================================
        $bulan = array(1 =>  'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
        $split1 = explode('-', $manage_survey->survey_start);
        $split2 = explode('-', $manage_survey->survey_end);
        if ((int)$split1[0] != (int)$split2[0]) {
            $periode =  strtoupper($bulan[(int)$split1[1]] . ' ' . $split1[0] . ' - ' . $bulan[(int)$split2[1]] . ' ' . $split2[0]);
        } else {
            if ($bulan[(int)$split1[1]] == $bulan[(int)$split2[1]]) {
                $periode =  strtoupper($bulan[(int)$split2[1]] . ' ' . $split1[0]);
            } else {
                $periode =  strtoupper($bulan[(int)$split1[1]] . ' - ' . $bulan[(int)$split2[1]] . ' ' . $split1[0]);
            }
        }



        $section->addTextBreak(3);
        $htmlCover = '<table style="width: 100%;">
                        <tr>
                            <td align="center">
                                <img src="' . base_url() . 'assets/klien/foto_profile/' . $img_profile . '" height="150" alt="" />
                                <br/>
                                <br/>
                                <br/>
                                <br/>
                                <br/>
                            </td>
                        </tr>
                        <tr>
                            <td align="center" style="font-size:25px;"><b>LAPORAN</b></td>
                        </tr>
                        <tr>
                            <td align="center" style="font-size:25px;"><b>SURVEI KEPUASAN MASYARAKAT</b><br/></td>
                        </tr>
                        <tr>
                            <td align="center" style="font-size:20px;"><b>' . strtoupper($manage_survey->organisasi) . '</b><br/></td>
                        </tr>
                        <tr>
                            <td align="center" style="font-size:17px;"><b>PERIODE ' . $periode . '</b></td>
                        </tr>
                    </table>';
        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $htmlCover, false, false);
        // echo $htmlCover;

        $section->addPageBreak();
        // //END ==================================================================================================================




        //START BAB 1 ==============================================================================================================
        //===========================================================================================================================
        $no_Bab1 = 1;
        $htmlbab1 = '
        <table style="width: 100%; line-height: 1.3;">
            <tr>
                <td style="text-align:center; font-weight: bold; font-size:16px;">BAB I</td>
            </tr>
            <tr>
                <td style="text-align:center; font-weight: bold; font-size:16px;">PENDAHULUAN<br/><br/></td>
            </tr>
        </table>

        <table style="width: 100%; font-size:13.5px; line-height: 1.3;">
            <tr>
                <td width="5%"><b>' . $no_Bab1++ . '.</b></td>
                <td><b>Latar Belakang</b></td>
            </tr>
            <tr>
                <td width="5%"><b></b></td>
                <td width="95%">
                    <p style="' . $content_paragraph . '">Seiring dengan kemajuan teknologi dan tuntutan masyarakat dalam hal pelayanan, maka unit penyelenggara pelayanan publik dituntut untuk memenuhi harapan masyarakat dalam melakukan pelayanan.</p>

                    <p style="' . $content_paragraph . '">Pelayanan publik yang dilakukan oleh aparatur pemerintah saat ini dirasakan belum memenuhi harapan masyarakat. Hal ini dapat diketahui dari berbagai keluhan masyarakat yang disampaikan melalui media massa dan jejaring sosial. Tentunya keluhan tersebut jika tidak ditangani akan memberikan dampak buruk terhadap pemerintah. Lebih jauh lagi adalah dapat menimbulkan ketidakpercayaan dari masyarakat.</p>

                    <p style="' . $content_paragraph . '">Salah satu upaya yang harus dilakukan dalam perbaikan pelayanan publik adalah melakukan survei kepuasan masyarakat kepada pengguna layanan dengan mengukur kepuasan masyarakat pengguna layanan.</p>
                    <br/>
                </td>
            </tr>
        </table>


        <table style="width: 100%; font-size:13.5px; line-height: 1.3;">
            <tr>
                <td width="5%"><b>' . $no_Bab1++ . '.</b></td>
                <td><b>Tujuan</b></td>
            </tr>

            <tr>
                <td width="5%"></td>
                <td width="95%">
                    <p style="' . $content_paragraph . '">Kegiatan Survei Kepuasan Masyarakat terhadap pelayanan publik bertujuan untuk mendapatkan feedback/umpan balik atas kinerja pelayanan yang diberikan kepada masyarakat guna perbaikan dan peningkatan kinerja pelayanan secara berkesinambungan.</p>
                    <br/>
                </td>
            </tr>
        </table>


        <table style="width: 100%; font-size:13.5px; line-height: 1.3;">
            <tr>
                <td width="5%"><b>' . $no_Bab1++ . '.</b></td>
                <td><b>Metodologi</b></td>
            </tr>
        </table>


        <table style="width: 100%; font-size:13.5px; line-height: 1.3;">
            <tr>
                <td width="5%"></td>
                <td width="5%">3.1</td>
                <td>Populasi
                    <p style="text-align: justify;">Populasi dari kegiatan Survei Kepuasan Masyarakat adalah penyelenggara pelayanan publik, yaitu instansi pemerintah pusat dan pemerintah daerah, termasuk BUMN/BUMD dan BHMN menyesuaikan dengan lingkup yang akan disurvei.</p>
                </td>
            </tr>

            <tr>
                <td width="5%"></td>
                <td width="5%">3.2</td>
                <td>Sampel
                    <p style="text-align: justify;">Sampel kegiatan Survei Kepuasan Masyarakat ditentukan dengan menggunakan perhitungan Krejcie and Morgan sebagai berikut:</p>
                    <br/>

                    <b>Rumus Krejcie dan Morgan:</b>
                    <table width="50%" align="center" style="border: 1px #000 solid;">
                        <tr>
                            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;S = {λ². N. P. Q}/ {d² (N-1) + λ². P. Q</td>
                        </tr>
                    </table>


                    <table>
                        <tr>
                            <td width="25%"><b>Keterangan :</b></td>
                            <td width="75%">
                                <p>S = Jumlah sampel</p>
                                <p>λ² = Lamda (faktor pengali) dengan dk = 1, (taraf kesalahan yang digunakan 5%, sehingga nilai lamba 3,841)</p>
                                <p>N = Populasi</p>
                                <p>P = Q = 0,5 (populasi menyebar normal)</p>
                                <p>d = 0,05</p>
                            </td>
                        </tr>
                    </table>

                    <p style="text-align: justify;">Sehingga dari pelaksanaan survei yang dilakukan, jumlah responden yang diperoleh adalah ' .  $this->db->get_where("survey_$table_identity", ['is_submit' => 1])->num_rows() . ' responden.</p>
                </td>
            </tr>


            <tr>
                <td width="5%"></td>
                <td width="5%">3.3</td>
                <td>Responden
                    <p style="text-align: justify;">Responden adalah penerima pelayanan publik yang pada saat pencacahan sedang berada di lokasi unit pelayanan, atau yang pernah menerima pelayanan dari aparatur penyelenggara pelayanan publik.</p>
                </td>
            </tr>
        </table>


        <table style="width: 100%; font-size:13.5px; line-height: 1.3;">
            <tr>
                <td width="5%"><b>' . $no_Bab1++ . '.</b></td>
                <td><b>Tim Survei Kepuasan Masyarakat</b></td>
            </tr>

            <tr>
                <td width="5%"></td>
                <td width="95%">
                    <p style="text-align: justify;">Survei Kepuasan Masyarakat ini dilakukan oleh Tim Survei Kepuasan Masyarakat yang telah ditetapkan.</p>
                    <br/>
                </td>
            </tr>
        </table>



        <table style="width: 100%; font-size:13.5px; line-height: 1.3;">
            <tr>
                <td width="5%"><b>' . $no_Bab1++ . '.</b></td>
                <td><b>Jadwal Survei Kepuasan Masyarakat</b></td>
            </tr>

            <tr>
                <td width="5%"></td>
                <td width="95%">
                    <p style="text-align: justify;">Jadwal Survei Kepuasan Masyarakat dilakukan sesuai dengan jadwal yang telah ditentukan.</p>
                    <br/>
                </td>
            </tr>
        </table>
        ';
        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $htmlbab1, false, false);
        $section->addPageBreak();




        //START BAB 2 ==============================================================================================================
        //===========================================================================================================================
        $no_Bab2 = 1;
        $htmlbab2 = '
        <table style="width: 100%; line-height: 1.3;">
            <tr>
                <td style="text-align:center; font-weight: bold; font-size:16px;">BAB II</td>
            </tr>
            <tr>
                <td style="text-align:center; font-weight: bold; font-size:16px;">ANALISIS<br/><br/></td>
            </tr>
        </table>';
        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $htmlbab2, false, false);



        //Jenis Pelayanan ========================================================================
        if ($manage_survey->is_layanan_survei == 1) {
            $a = 1;
            foreach ($this->db->query("SELECT *,
            (SELECT nama_kategori_layanan FROM kategori_layanan_survei_$table_identity WHERE id = layanan_survei_$table_identity.id_kategori_layanan) AS nama_kategori_layanan,
            (SELECT COUNT(*) FROM survey_$table_identity WHERE is_submit = 1) AS jumlah_pengisi,
            (SELECT COUNT(*) FROM responden_$table_identity JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id_responden WHERE layanan_survei_$table_identity.id = responden_$table_identity.id_layanan_survei && is_submit = 1) AS perolehan
            FROM layanan_survei_$table_identity
            WHERE is_active = 1
            ORDER BY urutan ASC")->result() as $ls) {

                $array_perol_layanan[] = $ls->perolehan;
                $array_layanan[] = '<tr>
                                            <td width="5%" align="center">' . $a++ . '</td>
                                            <td width="55%">' . $ls->nama_layanan . '</td>
                                            <td width="20%" align="center">' . $ls->perolehan . '</td>
                                            <td width="20%" align="center">' . ROUND(($ls->perolehan / $ls->jumlah_pengisi) * 100, 2) . '%</td>
                                        </tr>';
            }

            $htmlbab2JenisPelayanan = '
            <table style="width: 100%; font-size:13.5px; line-height: 1.3;">
                <tr>
                    <td width="5%"><b>' . $no_Bab2++ . '.</b></td>
                    <td><b>Jenis Pelayanan</b></td>
                </tr>
                <tr>
                    <td width="5%"></td>
                    <td><p style="' . $content_paragraph . '">Berikut merupakan jenis layanan yang diperoleh dari Survei Kepuasan Masyarakat  pada ' . $manage_survey->organisasi . '</p></td>
                </tr>

                <tr>
                    <td width="5%"></td>
                    <td style="text-align:center;">Tabel ' . $no_table++ . '. Persentase Responden Berdasarkan Jenis Pelayanan
                        <table width="100%" align="center" style="font-size:13.5px; border: 1px #000 solid;">
                            <tr>
                                <th width="5%" align="center" style="font-weight: bold;">No</th>
                                <th width="55%" align="center" style="font-weight: bold;">Jenis Pelayanan</th>
                                <th width="20%" align="center" style="font-weight: bold;">Jumlah</th>
                                <th width="20%" align="center" style="font-weight: bold;">Persentase</th>
                            </tr>
                            ' . implode("", $array_layanan) . '
                            <tr>
                                <th width="60%" align="center" colspan="2" style="font-weight: bold;">TOTAL</th>
                                <th width="20%" align="center" style="font-weight: bold;">' . array_sum($array_perol_layanan) . '</th>
                                <th width="20%" align="center" style="font-weight: bold;">100%</th>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>';
            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $htmlbab2JenisPelayanan, false, false);
        }




        //START PROFIL RESPONDEN ===================================================================
        $htmlbab2ProfilResponden = '
        <table style="width: 100%; font-size:13.5px; line-height: 1.3;">
            <tr>
                <td width="5%"><b>' . $no_Bab2++ . '.</b></td>
                <td><b>Profil Responden</b></td>
            </tr>
            <tr>
                <td width="5%"></td>
                <td><p style="' . $content_paragraph . '">Berikut merupakan karakteristik responden yang diperoleh dari Survei Kepuasan Masyarakat pada ' . $manage_survey->organisasi . '</p></td>
            </tr>
        </table>';
        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $htmlbab2ProfilResponden, false, false);



        foreach ($this->db->query("SELECT *,
        (SELECT COUNT(id) FROM kategori_profil_responden_$table_identity WHERE id_profil_responden = profil_responden_$table_identity.id) AS jumlah_kategori
        FROM profil_responden_$table_identity WHERE jenis_isian = 1")->result() as $prf => $prores) {

            foreach ($this->db->query("SELECT *, (SELECT COUNT(*) FROM responden_$table_identity JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id_responden WHERE kategori_profil_responden_$table_identity.id = responden_$table_identity.$prores->nama_alias && is_submit = 1) AS perolehan,
            ROUND((((SELECT COUNT(*) FROM responden_$table_identity JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id_responden WHERE kategori_profil_responden_$table_identity.id = responden_$table_identity.$prores->nama_alias && is_submit = 1) / (SELECT COUNT(*) FROM survey_$table_identity WHERE is_submit = 1)) * 100), 2) AS persentase

            FROM kategori_profil_responden_$table_identity
            WHERE id_profil_responden = $prores->id")->result() as $kpr) {

                $nama_kelompok[$prf][] = '%27' . str_replace(' ', '+', $kpr->nama_kategori_profil_responden) . '%27';
                $jumlah_persentase[$prf][] = $kpr->persentase;
            }

            $htmlbab2ProfilResponden = '
            <table style="width: 100%; font-size:13.5px; line-height: 1.3;">
                <tr>
                    <td width="5%"></td>
                    <td width="5%"><b>' . ($no_Bab2 - 1) . '.' . ($prf + 1) . '</b></td>
                    <td><b>' . $prores->nama_profil_responden . '</b></td>
                </tr>
            </table>';
            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $htmlbab2ProfilResponden, false, false);


            $section->addImage("https://quickchart.io/chart?bg=white&h=" . (50 + ($prores->jumlah_kategori * 50)) . "&c={type:'horizontalBar',data:{labels:[" . implode(",", $nama_kelompok[$prf]) . "],datasets:[{backgroundColor:'rgb(79,129,189)',stack:'Stack0',data:[" . implode(",", $jumlah_persentase[$prf]) . "],},],},options:{layout:{padding:{right:50}},scales:{xAxes:[{ticks:{min:0,max:100},},]},title:{display:true,text:'" . str_replace(' ', '+', $prores->nama_profil_responden) . "'},legend:{display:false},responsive:true,plugins:{roundedBars:true,datalabels:{anchor:'end',align:'center',backgroundColor:'rgb(255,255,255)',borderColor:'rgb(79,129,189)',borderWidth:1,borderRadius:5,formatter:(value)%3D%3E%7Breturn%20value%2B'%25';},},},},}", array('width' => 300, 'ratio' => true, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));
            $section->addText('Gambar ' . $no_gambar++ . '. Persentase ' . $prores->nama_profil_responden, array('size' => 9.5), $paragraphStyleName);
            $section->addTextBreak();



            if ($prores->is_lainnya == 1) {
                $lainnya = $prores->nama_alias . '_lainnya';
                $cek_lainnya[$prf] = $this->db->query("SELECT *
                FROM responden_$table_identity
                JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id_responden
                WHERE is_submit = 1 && responden_$table_identity.$lainnya != ''");

                if ($cek_lainnya[$prf]->num_rows() > 0) {
                    foreach ($cek_lainnya[$prf]->result() as $cl) {
                        $array_cl[$prf][] = '<li>' . $cl->$lainnya . '</li>';
                    }
                } else {
                    $array_cl[$prf][] = '';
                }


                $htmlbab2ProfilResponden = '
                <table style="width: 100%; font-size:13.5px; line-height: 1.3;">
                    <tr>
                        <td width="5%"></td>
                        <td width="5%"></td>
                        <td><ul>' . implode("", $array_cl[$prf]) . '</ul><br/></td>
                    </tr>
                </table>';
                \PhpOffice\PhpWord\Shared\Html::addHtml($section, $htmlbab2ProfilResponden, false, false);
            }
        }



        //START Nilai Indeks Kepuasan Masyarakat ===================================================================
        $bab2nilaiIKM = $this->db->query("SELECT 
        IF(id_parent = 0, unsur_pelayanan_$table_identity.id, unsur_pelayanan_$table_identity.id_parent) AS id_sub,
        (SELECT nomor_unsur FROM unsur_pelayanan_$table_identity WHERE id_sub = unsur_pelayanan_$table_identity.id) AS nomor_unsur,
        (SELECT nama_unsur_pelayanan FROM unsur_pelayanan_$table_identity WHERE id_sub = unsur_pelayanan_$table_identity.id) AS nama_unsur_pelayanan,
        AVG(IF(jawaban_pertanyaan_unsur_$table_identity.skor_jawaban != 0, skor_jawaban, NULL)) AS rata_rata

        FROM jawaban_pertanyaan_unsur_$table_identity
        JOIN pertanyaan_unsur_pelayanan_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id
        JOIN unsur_pelayanan_$table_identity ON pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan = unsur_pelayanan_$table_identity.id
        JOIN survey_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden
        WHERE survey_$table_identity.is_submit = 1
        GROUP BY id_sub
        ORDER BY SUBSTR(nomor_unsur,2) + 0");


        $b = 1;
        foreach ($bab2nilaiIKM->result() as $n_ikm) {
            foreach ($definisi_skala->result() as $obj) {
                if (($n_ikm->rata_rata * $skala_likert) <= $obj->range_bawah && ($n_ikm->rata_rata * $skala_likert) >= $obj->range_atas) {
                    $ktgUnsur = $obj->kategori;
                }
            }
            if (($n_ikm->rata_rata * $skala_likert) <= 0) {
                $ktgUnsur = 'NULL';
            }

            $arrayNomorUnsur[] = '%27' . str_replace(' ', '+', $n_ikm->nomor_unsur) . '%27';
            $arrayRataRataNilaiIKM_1[] = ROUND($n_ikm->rata_rata, 3);
            $arrayRataRataNilaiIKM_2[] = $n_ikm->rata_rata;
            $nilaiIKM = array_sum($arrayRataRataNilaiIKM_2) / count($arrayRataRataNilaiIKM_2);
            $arrayNilaiIKM[] = '<tr>
                                            <td width="5%" align="center">' . $b++ . '</td>
                                            <td width="55%">' . $n_ikm->nomor_unsur . '. ' . $n_ikm->nama_unsur_pelayanan . '</td>
                                            <td width="20%" align="center">' . Round($n_ikm->rata_rata, 2) . '</td>
                                            <td width="20%" align="center">' . $ktgUnsur . '</td>
                                        </tr>';
        }

        foreach ($definisi_skala->result() as $obj) {
            if (($nilaiIKM * $skala_likert) <= $obj->range_bawah && ($nilaiIKM * $skala_likert) >= $obj->range_atas) {
                $ktgNilaiIKM = $obj->kategori;
            }
        }
        if (($nilaiIKM * $skala_likert) <= 0) {
            $ktgNilaiIKM = 'NULL';
        }


        $htmlbab2NilaiIKM = '
        <table style="width: 100%; font-size:13.5px; line-height: 1.3;">
            <tr>
                <td width="5%"><b>' . $no_Bab2++ . '.</b></td>
                <td><b>Nilai Indeks Kepuasan Masyarakat</b></td>
            </tr>
            <tr>
                <td width="5%"></td>
                <td><p style="' . $content_paragraph . '">Hasil Survei Kepuasan Masyarakat ' . $manage_survey->organisasi . ' mendapatkan nilai Indeks Kepuasan Masyarakat (IKM) sebesar <b>' . ROUND($nilaiIKM * $skala_likert, 2) . '</b>, dengan mutu pelayanan <b>' . $ktgNilaiIKM . '</b>. Nilai Indeks Kepuasan Masyarakat (IKM) tersebut didapat dari nilai rata-rata seluruh unsur pada tabel berikut.</p></td>
            </tr>
            <tr>
                    <td width="5%"></td>
                    <td style="text-align:center;">Tabel ' . $no_table++ . '. Nilai Unsur ' . $manage_survey->organisasi . '
                        <table width="100%" align="center" style="font-size:13.5px; border: 1px #000 solid;">
                            <tr>
                                <th width="5%" align="center" style="font-weight: bold;">No</th>
                                <th width="55%" align="center" style="font-weight: bold;">Unsur</th>
                                <th width="20%" align="center" style="font-weight: bold;">Nilai Indeks</th>
                                <th width="20%" align="center" style="font-weight: bold;">Mutu Pelayanan</th>
                            </tr>
                            ' . implode("", $arrayNilaiIKM) . '
                            <tr>
                                <th width="60%" align="center" colspan="2" style="font-weight: bold;">Nilai IKM</th>
                                <th width="20%" align="center" style="font-weight: bold;">' . ROUND($nilaiIKM, 3) . '</th>
                                <th width="20%" align="center" style="font-weight: bold;">' . $ktgNilaiIKM . '</th>
                            </tr>
                            <tr>
                                <th width="60%" align="center" colspan="2" style="font-weight: bold;">Nilai Konversi</th>
                                <th width="20%" align="center" style="font-weight: bold;">' . ROUND($nilaiIKM * $skala_likert, 2) . '</th>
                                <th width="20%" align="center" style="font-weight: bold;">' . $ktgNilaiIKM . '</th>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td width="5%"></td>
                    <td><p style="text-align: justify;">Nilai unsur Survei Kepuasan Masyarakat pada ' . $manage_survey->organisasi . ' dapat dilihat pada gambar di bawah ini.</p><br/></td>
                </tr>
        </table>';
        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $htmlbab2NilaiIKM, false, false);


        $section->addImage("https://quickchart.io/chart?bg=white&h=" . (50 + (count($arrayNomorUnsur) * 40)) . "&c={type:'horizontalBar',data:{labels:[" . implode(",", $arrayNomorUnsur) . "],datasets:[{label:'Dataset1',backgroundColor:'rgb(79,129,189)',stack:'Stack0',data:[" . implode(",", $arrayRataRataNilaiIKM_1) . "],},],},options:{title:{display:true,text:'Nilai+Unsur'},legend:{display:false},plugins:{roundedBars:true,datalabels:{anchor:'center',align:'center',color:'white',font:{weight:'normal',},},},responsive:true,},}", array('width' => 300, 'ratio' => true, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));
        $section->addText('Gambar ' . $no_gambar++ . '. Grafik Unsur ' . $manage_survey->organisasi, array('size' => 9.5), $paragraphStyleName);
        $section->addTextBreak();




        //START PEMBAHASAN UNSUR  ===================================================================
        $kategori_unsur = $this->db->query("SELECT *,
        (SELECT COUNT(IF(skor_jawaban != 0, 1, NULL)) FROM jawaban_pertanyaan_unsur_$table_identity JOIN survey_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden WHERE jawaban_pertanyaan_unsur_$table_identity.id_pertanyaan_unsur = kategori_unsur_pelayanan_$table_identity.id_pertanyaan_unsur && kategori_unsur_pelayanan_$table_identity.nomor_kategori_unsur_pelayanan = jawaban_pertanyaan_unsur_$table_identity.skor_jawaban && is_submit = 1) AS perolehan,

        (SELECT COUNT(IF(skor_jawaban != 0, 1, NULL)) FROM jawaban_pertanyaan_unsur_$table_identity JOIN survey_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden WHERE jawaban_pertanyaan_unsur_$table_identity.id_pertanyaan_unsur = kategori_unsur_pelayanan_$table_identity.id_pertanyaan_unsur && is_submit = 1) AS jumlah_pengisi

        FROM kategori_unsur_pelayanan_$table_identity");

        $alasan_unsur = $this->db->query("SELECT *
        FROM jawaban_pertanyaan_unsur_$table_identity
        JOIN pertanyaan_unsur_pelayanan_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id
        JOIN survey_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden
        WHERE is_submit = 1 && alasan_pilih_jawaban != '' && jawaban_pertanyaan_unsur_$table_identity.is_active = 1");


        $htmlbab2PembahasanUnsur = '
        <table style="width: 100%; font-size:13.5px; line-height: 1.3;">
            <tr>
                <td width="5%"><b>' . $no_Bab2++ . '.</b></td>
                <td><b>Pembahasan Unsur</b></td>
            </tr>
            <tr>
                <td width="5%"></td>
                <td><p style="' . $content_paragraph . '">Unsur yang dipakai dalam Survei Kepuasan Masyarakat dapat dijadikan sebagai acuan untuk mengetahui kondisi Unit Pelayanan Publik pada ' . $manage_survey->organisasi . ' yang nantinya dijadikan suatu pedoman perbaikan kinerja. Pada pembahasan ini akan dijelaskan terkait persentase jawaban pada masing-masing unsur dalam Survei Kepuasan Masyarakat.</p></td>
            </tr>
        </table>';
        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $htmlbab2PembahasanUnsur, false, false);


        foreach ($this->db->query("SELECT * FROM unsur_pelayanan_$table_identity WHERE id_parent = 0 ORDER BY SUBSTR(nomor_unsur,2) + 0")->result() as $up => $unsur) {

            $htmlbab2PembahasanUnsur = '
            <table style="width: 100%; font-size:13.5px; line-height: 1.3;">
                <tr>
                    <td width="5%"></td>
                    <td width="5%"><b>' . $unsur->nomor_unsur . '. </b></td>
                    <td><b>' . $unsur->nama_unsur_pelayanan . '</b></td>
                </tr>
            </table>';
            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $htmlbab2PembahasanUnsur, false, false);

            $cek_sub = $this->db->query("SELECT * FROM unsur_pelayanan_$table_identity WHERE id_parent = $unsur->id ORDER BY SUBSTR(nomor_unsur,4) + 0");

            // JIKA UNSUR TIDAK MEMILIKI TURUNAN ========================================
            if ($cek_sub->num_rows() == 0) {

                $c = 1;
                foreach ($kategori_unsur->result() as $kup) {
                    if ($kup->id_unsur_pelayanan == $unsur->id) {

                        $arrayTotalPerolehanKup[$up][] = $kup->perolehan;
                        $totalPerolehanKup[$up] = array_sum($arrayTotalPerolehanKup[$up]);
                        $arrayNamaKup[$up][] = '%27' . str_replace(' ', '+', $kup->nama_kategori_unsur_pelayanan) . '%27';
                        $arrayPersentaseKup[$up][] = ROUND(($kup->perolehan / $kup->jumlah_pengisi) * 100, 2);
                        $arrayPerolehanKup[$up][] = '<tr>
                                                    <td width="5%" align="center">' . $c++ . '</td>
                                                    <td width="55%">' . $kup->nama_kategori_unsur_pelayanan . '</td>
                                                    <td width="20%" align="center">' . $kup->perolehan . '</td>
                                                    <td width="20%" align="center">' . ROUND(($kup->perolehan / $kup->jumlah_pengisi) * 100, 2) . '%</td>
                                                </tr>';
                    }
                }

                //ALASAN UNSUR =================================================
                foreach ($alasan_unsur->result() as $a_unsur) {
                    if ($a_unsur->id_unsur_pelayanan == $unsur->id) {
                        $arrayAlasanUnsur[$up][] = '<li>' . $a_unsur->alasan_pilih_jawaban . '</li>';
                    } else {
                        $arrayAlasanUnsur[$up][] = '';
                    }
                }
                if (implode("", $arrayAlasanUnsur[$up]) != '') {
                    $alasanUnsur[$up] = '<p style="text-align:left;">Alasan yang diberikan responden pada unsur ' . $unsur->nama_unsur_pelayanan . '</p><ul>' . implode("", $arrayAlasanUnsur[$up]) . '</ul>';
                } else {
                    $alasanUnsur[$up] = '';
                }
                //END ALASAN UNSUR =================================================


                $section->addImage("https://quickchart.io/chart?bg=white&h=" . (50 + (count($arrayNamaKup[$up]) * 50)) . "&c={type:'horizontalBar',data:{labels:[" . implode(",", $arrayNamaKup[$up]) . "],datasets:[{backgroundColor:'rgb(79,129,189)',stack:'Stack0',data:[" . implode(",", $arrayPersentaseKup[$up]) . "],},],},options:{layout:{padding:{right:50}},scales:{xAxes:[{ticks:{min:0,max:100},},]},title:{display:true,text:'" . str_replace(' ', '+', $unsur->nama_unsur_pelayanan) . "'},legend:{display:false},responsive:true,plugins:{roundedBars:true,datalabels:{anchor:'end',align:'center',backgroundColor:'rgb(255,255,255)',borderColor:'rgb(79,129,189)',borderWidth:1,borderRadius:5,formatter:(value)%3D%3E%7Breturn%20value%2B'%25';},},},},}", array('width' => 300, 'ratio' => true, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));
                $section->addText('Gambar ' . $no_gambar++ . '. Grafik Unsur ' . $unsur->nama_unsur_pelayanan, array('size' => 9.5), $paragraphStyleName);
                $section->addTextBreak();

                $htmlbab2PembahasanUnsur = '
                <table style="width: 100%; font-size:13.5px; line-height: 1.3;">
                    <tr>
                        <td width="5%"></td>
                        <td width="5%"></td>
                        <td style="text-align:center;">Tabel ' . $no_table++ . '. Persentase Responden pada Unsur ' . $unsur->nama_unsur_pelayanan . '
                        <table width="100%" align="center" style="font-size:13.5px; border: 1px #000 solid;">
                            <tr>
                                <th width="5%" align="center" style="font-weight: bold;">No</th>
                                <th width="55%" align="center" style="font-weight: bold;">Kategori</th>
                                <th width="20%" align="center" style="font-weight: bold;">Jumlah</th>
                                <th width="20%" align="center" style="font-weight: bold;">Persentase</th>
                            </tr>
                                ' . implode("", $arrayPerolehanKup[$up]) . '
                            <tr>
                                <th width="60%" align="center" colspan="2" style="font-weight: bold;">TOTAL</th>
                                <th width="20%" align="center" style="font-weight: bold;">' . $totalPerolehanKup[$up] . '</th>
                                <th width="20%" align="center" style="font-weight: bold;">100%</th>
                            </tr>
                        </table>
                            ' . $alasanUnsur[$up] . '
                        </td>
                    </tr>
                </table>';
                \PhpOffice\PhpWord\Shared\Html::addHtml($section, $htmlbab2PembahasanUnsur, false, false);


                // JIKA UNSUR MEMILIKI TURUNAN ========================================
            } else {

                foreach ($cek_sub->result() as $sup => $subunsur) {

                    $htmlbab2PembahasanUnsur = '
                    <table style="width: 100%; font-size:13.5px; line-height: 1.3;">
                        <tr>
                            <td width="5%"></td>
                            <td width="5%"></td>
                            <td><b>' . $subunsur->nomor_unsur . '. ' . $subunsur->nama_unsur_pelayanan . '</b></td>
                        </tr>
                    </table>';
                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $htmlbab2PembahasanUnsur, false, false);


                    $d = 1;
                    foreach ($kategori_unsur->result() as $subkup) {
                        if ($subkup->id_unsur_pelayanan == $subunsur->id) {

                            $arrayTotalPerolehanSubKup[$up][$sup][] = $subkup->perolehan;
                            $totalPerolehanSubKup[$up][$sup] = array_sum($arrayTotalPerolehanSubKup[$up][$sup]);
                            $arrayNamaSubKup[$up][$sup][] = '%27' . str_replace(' ', '+', $subkup->nama_kategori_unsur_pelayanan) . '%27';
                            $arrayPersentaseSubKup[$up][$sup][] = ROUND(($subkup->perolehan / $subkup->jumlah_pengisi) * 100, 2);
                            $arrayPerolehanSubKup[$up][$sup][] = '<tr>
                                                    <td width="5%" align="center">' . $d++ . '</td>
                                                    <td width="55%">' . $subkup->nama_kategori_unsur_pelayanan . '</td>
                                                    <td width="20%" align="center">' . $subkup->perolehan . '</td>
                                                    <td width="20%" align="center">' . ROUND(($subkup->perolehan / $subkup->jumlah_pengisi) * 100, 2) . '%</td>
                                                </tr>';
                        }
                    }


                    //ALASAN UNSUR =================================================
                    foreach ($alasan_unsur->result() as $a_subunsur) {
                        if ($a_subunsur->id_unsur_pelayanan == $subunsur->id) {
                            $arrayAlasanSubUnsur[$up][$sup][] = '<li>' . $a_subunsur->alasan_pilih_jawaban . '</li>';
                        } else {
                            $arrayAlasanSubUnsur[$up][$sup][] = '';
                        }
                    }
                    if (implode("", $arrayAlasanSubUnsur[$up][$sup]) != '') {
                        $alasanSubUnsur[$up][$sup] = '<p style="text-align:left;">Alasan yang diberikan responden pada unsur ' . $subunsur->nama_unsur_pelayanan . '</p><ul>' . implode("", $arrayAlasanSubUnsur[$up][$sup]) . '</ul>';
                    } else {
                        $alasanSubUnsur[$up][$sup] = '';
                    }
                    //END ALASAN UNSUR =================================================


                    $section->addImage("https://quickchart.io/chart?bg=white&h=" . (50 + (count($arrayNamaSubKup[$up][$sup]) * 50)) . "&c={type:'horizontalBar',data:{labels:[" . implode(",", $arrayNamaSubKup[$up][$sup]) . "],datasets:[{backgroundColor:'rgb(79,129,189)',stack:'Stack0',data:[" . implode(",", $arrayPersentaseSubKup[$up][$sup]) . "],},],},options:{layout:{padding:{right:50}},scales:{xAxes:[{ticks:{min:0,max:100},},]},title:{display:true,text:'" . str_replace(' ', '+', $subunsur->nama_unsur_pelayanan) . "'},legend:{display:false},responsive:true,plugins:{roundedBars:true,datalabels:{anchor:'end',align:'center',backgroundColor:'rgb(255,255,255)',borderColor:'rgb(79,129,189)',borderWidth:1,borderRadius:5,formatter:(value)%3D%3E%7Breturn%20value%2B'%25';},},},},}", array('width' => 300, 'ratio' => true, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));
                    $section->addText('Gambar ' . $no_gambar++ . '. Grafik Unsur ' . $subunsur->nama_unsur_pelayanan, array('size' => 9.5), $paragraphStyleName);
                    $section->addTextBreak();


                    $htmlbab2PembahasanUnsur = '
                    <table style="width: 100%; font-size:13.5px; line-height: 1.3;">
                        <tr>
                            <td width="5%"></td>
                            <td width="5%"></td>
                            <td style="text-align:center;">Tabel ' . $no_table++ . '. Persentase Responden pada Unsur ' . $subunsur->nama_unsur_pelayanan . '
                            <table width="100%" align="center" style="font-size:13.5px; border: 1px #000 solid;">
                                <tr>
                                    <th width="5%" align="center" style="font-weight: bold;">No</th>
                                    <th width="55%" align="center" style="font-weight: bold;">Kategori</th>
                                    <th width="20%" align="center" style="font-weight: bold;">Jumlah</th>
                                    <th width="20%" align="center" style="font-weight: bold;">Persentase</th>
                                </tr>
                                ' . implode("", $arrayPerolehanSubKup[$up][$sup]) . '
                                <tr>
                                    <th width="60%" align="center" colspan="2" style="font-weight: bold;">TOTAL</th>
                                    <th width="20%" align="center" style="font-weight: bold;">' . $totalPerolehanSubKup[$up][$sup] . '</th>
                                    <th width="20%" align="center" style="font-weight: bold;">100%</th>
                                </tr>
                            </table>
                            ' . $alasanSubUnsur[$up][$sup] . '
                        </td>
                        </tr>
                    </table>';
                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $htmlbab2PembahasanUnsur, false, false);
                }
            }
        }


        //START PERTANYAAN TERBUKA ===================================================
        if (in_array(2, $atribut_pertanyaan)) {

            $pertanyaan_terbuka = $this->db->query("SELECT *, (SELECT DISTINCT dengan_isian_lainnya FROM isi_pertanyaan_ganda_$table_identity WHERE isi_pertanyaan_ganda_$table_identity.id_perincian_pertanyaan_terbuka = perincian_pertanyaan_terbuka_$table_identity.id) AS is_lainnya,

                (SELECT COUNT(*) FROM responden_$table_identity JOIN jawaban_pertanyaan_terbuka_$table_identity ON responden_$table_identity.id = jawaban_pertanyaan_terbuka_$table_identity.id_responden JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id_responden WHERE survey_$table_identity.is_submit = 1 && jawaban_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka = perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka && jawaban_pertanyaan_terbuka_$table_identity.jawaban = 'Lainnya') AS perolehan,
                
                (SELECT COUNT(*) FROM survey_$table_identity JOIN responden_$table_identity ON survey_$table_identity.id_responden = responden_$table_identity.id JOIN jawaban_pertanyaan_terbuka_$table_identity ON responden_$table_identity.id = jawaban_pertanyaan_terbuka_$table_identity.id_responden WHERE is_submit = 1 && jawaban_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka = perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka && jawaban_pertanyaan_terbuka_$table_identity.jawaban != '' ) AS jumlah_pengisi
                
                FROM pertanyaan_terbuka_$table_identity
                JOIN perincian_pertanyaan_terbuka_$table_identity ON pertanyaan_terbuka_$table_identity.id = perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka
                ORDER BY SUBSTR(nomor_pertanyaan_terbuka,2) + 0");


            $jawaban_ganda_terbuka = $this->db->query("SELECT *, 
                (SELECT COUNT(IF(jawaban != '', jawaban, NULL)) FROM survey_$table_identity JOIN jawaban_pertanyaan_terbuka_$table_identity ON survey_$table_identity.id_responden = jawaban_pertanyaan_terbuka_$table_identity.id_responden WHERE survey_$table_identity.is_submit = 1 && jawaban_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka = perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka && jawaban_pertanyaan_terbuka_$table_identity.jawaban = isi_pertanyaan_ganda_$table_identity.pertanyaan_ganda) AS perolehan,
                
                (SELECT COUNT(IF(jawaban != '', jawaban, NULL)) FROM survey_$table_identity JOIN jawaban_pertanyaan_terbuka_$table_identity ON survey_$table_identity.id_responden = jawaban_pertanyaan_terbuka_$table_identity.id_responden WHERE is_submit = 1 && jawaban_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka = perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka && jawaban_pertanyaan_terbuka_$table_identity.jawaban != '') AS jumlah_pengisi
                
                FROM isi_pertanyaan_ganda_$table_identity
                JOIN perincian_pertanyaan_terbuka_$table_identity ON isi_pertanyaan_ganda_$table_identity.id_perincian_pertanyaan_terbuka = perincian_pertanyaan_terbuka_$table_identity.id
                WHERE perincian_pertanyaan_terbuka_$table_identity.id_jenis_pilihan_jawaban = 1");


            $htmlbab2PertanyaanTambahan = '
                <table style="width: 100%; font-size:13.5px; line-height: 1.3;">
                    <tr>
                        <td width="5%"><b>' . $no_Bab2++ . '.</b></td>
                        <td><b>Pertanyaan Tambahan</b></td>
                    </tr>
                </table>';
            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $htmlbab2PertanyaanTambahan, false, false);


            foreach ($pertanyaan_terbuka->result() as $pt => $p_terbuka) {

                $htmlbab2PertanyaanTambahan = '
                    <table style="width: 100%; font-size:13.5px; line-height: 1.3;">
                        <tr>
                            <td width="5%"></td>
                            <td width="5%">' . $p_terbuka->nomor_pertanyaan_terbuka . '. </td>
                            <td>' . $p_terbuka->nama_pertanyaan_terbuka . '</td>
                        </tr>
                    </table>';
                \PhpOffice\PhpWord\Shared\Html::addHtml($section, $htmlbab2PertanyaanTambahan, false, false);


                if ($p_terbuka->id_jenis_pilihan_jawaban == 1) {
                    $e = 1;
                    foreach ($jawaban_ganda_terbuka->result() as $ipg) {
                        if ($ipg->id_pertanyaan_terbuka == $p_terbuka->id_pertanyaan_terbuka) {

                            $arrayTotalPerolehanIpg[$pt][] = $ipg->perolehan;
                            $totalPerolehanIpg[$pt] = array_sum($arrayTotalPerolehanIpg[$pt]);
                            $arrayPerolehanIpg[$pt][] = '<tr>
                                                                    <td width="5%" align="center">' . $e++ . '</td>
                                                                    <td width="55%">' . $ipg->pertanyaan_ganda . '</td>
                                                                    <td width="20%" align="center">' . $ipg->perolehan . '</td>
                                                                    <td width="20%" align="center">' . ROUND(($ipg->perolehan / $ipg->jumlah_pengisi) * 100, 2) . '%</td>
                                                                </tr>';
                        }
                    }


                    $htmlbab2PertanyaanTambahan = '
                        <table style="width: 100%; font-size:13.5px; line-height: 1.3;">
                            <tr>
                                <td width="5%"></td>
                                <td width="5%"></td>
                                <td style="text-align:center;">Tabel ' . $no_table++ . '. Persentase Responden pada ' . $p_terbuka->nama_pertanyaan_terbuka . '
                                <table width="100%" align="center" style="font-size:13.5px; border: 1px #000 solid;">
                                    <tr>
                                        <th width="5%" align="center" style="font-weight: bold;">No</th>
                                        <th width="55%" align="center" style="font-weight: bold;">Kategori</th>
                                        <th width="20%" align="center" style="font-weight: bold;">Jumlah</th>
                                        <th width="20%" align="center" style="font-weight: bold;">Persentase</th>
                                    </tr>
                                    ' . implode("", $arrayPerolehanIpg[$pt]) . '
                                    <tr>
                                        <th width="60%" align="center" colspan="2" style="font-weight: bold;">TOTAL</th>
                                        <th width="20%" align="center" style="font-weight: bold;">' . $totalPerolehanIpg[$pt] . '</th>
                                        <th width="20%" align="center" style="font-weight: bold;">100%</th>
                                    </tr>
                                </table>
                            </td>
                            </tr>
                        </table>';
                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $htmlbab2PertanyaanTambahan, false, false);
                } else {

                    $f = 1;
                    foreach ($this->db->query("SELECT * 
                        FROM jawaban_pertanyaan_terbuka_$table_identity
                        JOIN survey_$table_identity ON jawaban_pertanyaan_terbuka_$table_identity.id_responden = survey_$table_identity.id_responden
                        WHERE survey_$table_identity.is_submit = 1 && jawaban_pertanyaan_terbuka_$table_identity.jawaban != '' && id_pertanyaan_terbuka = $p_terbuka->id_pertanyaan_terbuka")->result() as $isian_terbuka) {

                        $arrayIsianTerbuka[$pt][] = '
                            <tr>
                                <td width="7%" align="center">' . $f++ . '</td>
                                <td width="93%">' . $isian_terbuka->jawaban . '</td>
                            </tr>';
                    }

                    $htmlbab2PertanyaanTambahan = '
                        <table style="width: 100%; font-size:13.5px; line-height: 1.3;">
                            <tr>
                                <td width="5%"></td>
                                <td width="5%"></td>
                                <td style="text-align:center;">Tabel ' . $no_table++ . '. Jawaban Responden pada ' . $p_terbuka->nama_pertanyaan_terbuka . '
                                <table width="100%" align="center" style="font-size:13.5px; border: 1px #000 solid;">
                                    <tr>
                                        <th width="7%" align="center" style="font-weight: bold;">No</th>
                                        <th width="93%" align="center" style="font-weight: bold;">Jawaban</th>
                                    </tr>
                                    ' . implode("", $arrayIsianTerbuka[$pt]) . '
                                </table>
                            </td>
                            </tr>
                        </table>';
                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $htmlbab2PertanyaanTambahan, false, false);
                }
            }
        }


        //START PERTANYAAN KUALITATIF ===================================================
        if (in_array(3, $atribut_pertanyaan)) {
            foreach ($this->db->query("SELECT * FROM pertanyaan_kualitatif_$table_identity WHERE is_active = 1")->result() as $pk => $p_kualitatif) {

                $htmlbab2PertanyaanKualitatif = '
                <table style="width: 100%; font-size:13.5px; line-height: 1.3;">
                    <tr>
                        <td width="5%"></td>
                        <td width="5%">K' . ($pk + 1) . '. </td>
                        <td>' . strip_tags($p_kualitatif->isi_pertanyaan) . '</td>
                    </tr>
                </table>';
                \PhpOffice\PhpWord\Shared\Html::addHtml($section, $htmlbab2PertanyaanKualitatif, false, false);


                $g = 1;
                foreach ($this->db->query("SELECT * 
                FROM jawaban_pertanyaan_kualitatif_$table_identity
                JOIN survey_$table_identity ON jawaban_pertanyaan_kualitatif_$table_identity.id_responden = survey_$table_identity.id_responden
                WHERE survey_$table_identity.is_submit = 1 && jawaban_pertanyaan_kualitatif_$table_identity.isi_jawaban_kualitatif != '' && id_pertanyaan_kualitatif = $p_kualitatif->id")->result() as $j_kualitatif) {

                    $arrayKualitatif[] = '
                    <tr>
                        <td width="7%" align="center">' . $g++ . '</td>
                        <td width="93%">' . $j_kualitatif->isi_jawaban_kualitatif . '</td>
                    </tr>';
                }

                $htmlbab2PertanyaanKualitatif = '
                <table style="width: 100%; font-size:13.5px; line-height: 1.3;">
                    <tr>
                        <td width="5%"></td>
                        <td width="5%"></td>
                        <td style="text-align:center;">Tabel ' . $no_table++ . '. Jawaban Responden pada K' . ($pk + 1) . '
                        <table width="100%" align="center" style="font-size:13.5px; border: 1px #000 solid;">
                            <tr>
                                <th width="7%" align="center" style="font-weight: bold;">No</th>
                                <th width="93%" align="center" style="font-weight: bold;">Jawaban</th>
                            </tr>
                            ' . implode("", $arrayKualitatif) . '
                        </table>
                    </td>
                    </tr>
                </table>';
                \PhpOffice\PhpWord\Shared\Html::addHtml($section, $htmlbab2PertanyaanKualitatif, false, false);
            }
            $section->addTextBreak();
        }





            //START KUADRAN CHART ================================================================
            if (in_array(1, $atribut_pertanyaan)) {
                // $kuadran = unserialize($manage_survey->atribut_kuadran);
                // if($kuadran[0] != ''){
                //     $img_kuadran = '<img src="' . base_url() . 'assets/klien/img_kuadran/' . $kuadran[0] . '" height="300" alt="" /><p>Gambar ' . $no_gambar++ . '. Diagram Persepsi dan Harapan</p>';
                // } else {
                //     $img_kuadran = '<i>Gambar belum digenerate!</i>';
                // }


                $indeksPersepsi = $this->db->query("SELECT AVG(rata_rata) AS rata_rata, MIN(rata_rata) AS nilai_terendah, MAX(rata_rata) AS nilai_tertinggi

                FROM ( SELECT IF(id_parent = 0, unsur_pelayanan_$table_identity.id, unsur_pelayanan_$table_identity.id_parent) AS id_sub,
                (SELECT nomor_unsur FROM unsur_pelayanan_$table_identity WHERE id_sub = unsur_pelayanan_$table_identity.id) AS nomor_unsur,
                AVG(IF(jawaban_pertanyaan_unsur_$table_identity.skor_jawaban != 0, skor_jawaban, NULL)) AS rata_rata

                FROM jawaban_pertanyaan_unsur_$table_identity
                JOIN pertanyaan_unsur_pelayanan_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id
                JOIN unsur_pelayanan_$table_identity ON pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan = unsur_pelayanan_$table_identity.id
                JOIN survey_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden
                WHERE survey_$table_identity.is_submit = 1
                GROUP BY id_sub ORDER BY SUBSTR(nomor_unsur,2) + 0 ) jpu_$table_identity")->row();



                $indeksHarapan = $this->db->query("SELECT AVG(rata_rata) AS rata_rata, MIN(rata_rata) AS nilai_terendah, MAX(rata_rata) AS nilai_tertinggi

                FROM (
                SELECT IF(id_parent = 0, unsur_pelayanan_$table_identity.id, unsur_pelayanan_$table_identity.id_parent) AS id_sub,
                (SELECT nomor_unsur FROM unsur_pelayanan_$table_identity WHERE id_sub = unsur_pelayanan_$table_identity.id) AS nomor_unsur,
                AVG(IF(jawaban_pertanyaan_harapan_$table_identity.skor_jawaban != 0, skor_jawaban, NULL)) AS rata_rata

                FROM jawaban_pertanyaan_harapan_$table_identity
                JOIN pertanyaan_unsur_pelayanan_$table_identity ON jawaban_pertanyaan_harapan_$table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id
                JOIN unsur_pelayanan_$table_identity ON pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan = unsur_pelayanan_$table_identity.id
                JOIN survey_$table_identity ON jawaban_pertanyaan_harapan_$table_identity.id_responden = survey_$table_identity.id_responden
                WHERE survey_$table_identity.is_submit = 1
                GROUP BY id_sub ORDER BY SUBSTR(nomor_unsur,2) + 0 ) jph_$table_identity")->row();


                $grafikKuadran =  $this->db->query("SELECT *,
                (CASE
                    WHEN kuadran_$table_identity.skor_persepsi <= $indeksPersepsi->rata_rata && kuadran_$table_identity.skor_harapan >= $indeksHarapan->rata_rata
                        THEN 1
                    WHEN kuadran_$table_identity.skor_persepsi >= $indeksPersepsi->rata_rata && kuadran_$table_identity.skor_harapan >= $indeksHarapan->rata_rata
                        THEN 2
                    WHEN kuadran_$table_identity.skor_persepsi <= $indeksPersepsi->rata_rata && kuadran_$table_identity.skor_harapan <= $indeksHarapan->rata_rata
                        THEN 3
                    WHEN kuadran_$table_identity.skor_persepsi >= $indeksPersepsi->rata_rata && kuadran_$table_identity.skor_harapan <= $indeksHarapan->rata_rata
                        THEN 4
                    ELSE 0
                END) AS kuadran

                FROM (
                SELECT nomor_unsur, nama_unsur_pelayanan,

                (SELECT AVG(IF(skor_jawaban != 0, skor_jawaban, NULL)) FROM jawaban_pertanyaan_unsur_$table_identity
                JOIN survey_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden
                JOIN pertanyaan_unsur_pelayanan_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id
                JOIN  unsur_pelayanan_$table_identity ON pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan = unsur_pelayanan_$table_identity.id
                WHERE is_submit = 1 && IF(id_parent = 0,unsur_pelayanan_$table_identity.id, unsur_pelayanan_$table_identity.id_parent) = up_$table_identity.id) AS skor_persepsi,

                (SELECT AVG(IF(skor_jawaban != 0, skor_jawaban, NULL)) FROM jawaban_pertanyaan_harapan_$table_identity
                JOIN survey_$table_identity ON jawaban_pertanyaan_harapan_$table_identity.id_responden = survey_$table_identity.id_responden
                JOIN pertanyaan_unsur_pelayanan_$table_identity ON jawaban_pertanyaan_harapan_$table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id
                JOIN  unsur_pelayanan_$table_identity ON pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan = unsur_pelayanan_$table_identity.id
                WHERE is_submit = 1 && IF(id_parent = 0,unsur_pelayanan_$table_identity.id, unsur_pelayanan_$table_identity.id_parent) = up_$table_identity.id) AS skor_harapan

                FROM unsur_pelayanan_$table_identity up_$table_identity WHERE id_parent = 0 ) AS kuadran_$table_identity");


                foreach ($grafikKuadran->result() as $gk) {
                    if ($gk->kuadran == 1) {
                        $arrayKuadran1[] = '<li>' . $gk->nomor_unsur . '. ' . $gk->nama_unsur_pelayanan . '</li>';
                    } elseif ($gk->kuadran == 2) {
                        $arrayKuadran2[] = '<li>' . $gk->nomor_unsur . '. ' . $gk->nama_unsur_pelayanan . '</li>';
                    } elseif ($gk->kuadran == 3) {
                        $arrayKuadran3[] = '<li>' . $gk->nomor_unsur . '. ' . $gk->nama_unsur_pelayanan . '</li>';
                    } else {
                        $arrayKuadran4[] = '<li>' . $gk->nomor_unsur . '. ' . $gk->nama_unsur_pelayanan . '</li>';
                    }

                    $arrayKuardan[] = "{label:'$gk->nomor_unsur',x:$gk->skor_persepsi,y:$gk->skor_harapan}";
                }


                $htmlbab2DiagramPersepsiHarapan  = '
                <table style="width: 100%; font-size:13.5px; line-height: 1.3;">
                    <tr>
                        <td width="5%"><b>' . $no_Bab2++ . '.</b></td>
                        <td><b>Diagram Persepsi dan Harapan</b></td>
                    </tr>
                </table>';
                \PhpOffice\PhpWord\Shared\Html::addHtml($section, $htmlbab2DiagramPersepsiHarapan, false, false);


                $section->addImage("https://quickchart.io/chart?v=3&c={type:'scatter',data:{datasets:[{label:'Data',data:[" . implode(",", $arrayKuardan) . "],backgroundColor:'blue',},],},options:{scales:{x:{max:" . ($indeksPersepsi->nilai_tertinggi + 0.2) . ",min:" . ($indeksPersepsi->nilai_terendah - 0.2) . ",},y:{max:" . ($indeksHarapan->nilai_tertinggi + 0.2) . ",min:" . ($indeksHarapan->nilai_terendah - 0.2) . ",},},plugins:{title:{display:true,text:'KUADRAN+CHART',},legend:{display:false,},datalabels:{display:true,align:'bottom',},annotation:{annotations:{LineX:{type:'line',xMin:" . $indeksPersepsi->rata_rata . ",xMax:" . $indeksPersepsi->rata_rata . ",borderColor:'red',borderWidth:1,},LineY:{type:'line',yMin:" . $indeksHarapan->rata_rata . ",yMax:" . $indeksHarapan->rata_rata . ",borderColor:'red',borderWidth:1,},Label1:{type:'label',xValue:" . ($indeksPersepsi->rata_rata - 0.2) . ",yValue:" . ($indeksHarapan->rata_rata + 0.2) . ",content:['Kuadran+I'],},Label2:{type:'label',xValue:(" . ($indeksPersepsi->rata_rata + 0.2) . "),yValue:" . ($indeksHarapan->rata_rata + 0.2) . ",content:['Kuadran+II'],},Label3:{type:'label',xValue:(" . ($indeksPersepsi->rata_rata - 0.2) . "),yValue:" . ($indeksHarapan->rata_rata - 0.2) . ",content:['Kuadran+III'],},Label4:{type:'label',xValue:(" . ($indeksPersepsi->rata_rata + 0.2) . "),yValue:" . ($indeksHarapan->rata_rata - 0.2) . ",content:['Kuadran+IV']}}}}}}", array('width' => 300, 'ratio' => true, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));
                $section->addText('Gambar ' . $no_gambar++ . '. Diagram Persepsi dan Harapan', array('size' => 9.5), $paragraphStyleName);
                $section->addTextBreak();



                $htmlbab2DiagramPersepsiHarapan  = '
                <table style="width: 100%; font-size:13.5px; line-height: 1.3;">
                    <tr>
                        <td width="5%"></td>
                        <td align="center">Tabel ' . $no_table++ . '. Kuadran Perbaikan Unsur
                            <table width="100%" align="center" style="font-size:13.5px; border: 1px #000 solid;">
                                <tr>
                                    <th width="15%" align="center" style="font-weight: bold;">Kuadran I</th>
                                    <td width="85%">
                                        <ul>
                                            ' . implode("", $arrayKuadran1) . '
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <th width="15%" align="center" style="font-weight: bold;">Kuadran II</th>
                                    <td width="85%">
                                        <ul>
                                            ' . implode("", $arrayKuadran2) . '
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <th width="15%" align="center" style="font-weight: bold;">Kuadran III</th>
                                    <td width="85%">
                                        <ul>
                                            ' . implode("", $arrayKuadran3) . '
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <th width="15%" align="center" style="font-weight: bold;">Kuadran IV</th>
                                    <td width="85%">
                                        <ul>
                                            ' . implode("", $arrayKuadran4) . '
                                        </ul>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>';
                \PhpOffice\PhpWord\Shared\Html::addHtml($section, $htmlbab2DiagramPersepsiHarapan, false, false);
                $section->addTextBreak();
            }




            //START SARAN =============================================================
            if ($manage_survey->is_saran == 1) {
                $h = 1;
                $saran = $this->db->query("SELECT * FROM survey_$table_identity WHERE is_submit = 1 && saran != ''");
                if ($saran->num_rows() > 0) {
                    foreach ($saran->result() as $sy) {
                        $arraySaran[] = '<tr>
                                            <td width="7%" align="center">' . $h++ . '</td>
                                            <td width="93%">' . $sy->saran . '</td>
                                        </tr>';
                    }
                } else {
                    $arraySaran[] = '';
                }


                $htmlbab2Saran  = '
                <table style="width: 100%; font-size:13.5px; line-height: 1.3;">
                    <tr>
                        <td width="5%"><b>' . $no_Bab2++ . '.</b></td>
                        <td><b>Saran Responden</b></td>
                    </tr>
                    <tr>
                        <td width="5%"></td>
                        <td><p style="' . $content_paragraph . '">Saran responden mengenai Survei Kepuasan Masyarakat pada ' . $manage_survey->organisasi . ' sebagai berikut:</p></td>
                    </tr>
                    <tr>
                        <td width="5%"></td>
                        <td>
                            <table width="100%" align="center" style="font-size:13.5px; border: 1px #000 solid;">
                                <tr>
                                    <th width="7%" align="center" style="font-weight: bold;">No</th>
                                    <th width="93%" align="center" style="font-weight: bold;">Saran</th>
                                </tr>
                                ' . implode("", $arraySaran) . '
                            </table>
                        </td>
                    </tr>
                </table>';
                \PhpOffice\PhpWord\Shared\Html::addHtml($section, $htmlbab2Saran, false, false);
            }
            $section->addPageBreak();



            //BAB 3 Penutup =========================================================================================================
            //========================================================================================================================

            $unsurTertinggi = $this->db->query("SELECT *
            FROM (
                SELECT IF(id_parent = 0, unsur_pelayanan_$table_identity.id, unsur_pelayanan_$table_identity.id_parent) AS id_sub,
                (SELECT nomor_unsur FROM unsur_pelayanan_$table_identity WHERE id_sub = unsur_pelayanan_$table_identity.id) AS nomor_unsur,
                (SELECT nama_unsur_pelayanan FROM unsur_pelayanan_$table_identity WHERE id_sub = unsur_pelayanan_$table_identity.id) AS nama_unsur_pelayanan,
                AVG(IF(jawaban_pertanyaan_unsur_$table_identity.skor_jawaban != 0, skor_jawaban, NULL)) AS rata_rata

                FROM jawaban_pertanyaan_unsur_$table_identity
                JOIN pertanyaan_unsur_pelayanan_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id
                JOIN unsur_pelayanan_$table_identity ON pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan = unsur_pelayanan_$table_identity.id
                JOIN survey_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden
                WHERE survey_$table_identity.is_submit = 1
                GROUP BY id_sub
                ORDER BY SUBSTR(nomor_unsur,2) + 0
            ) jpu_$table_identity ORDER BY rata_rata DESC")->result_array();


            $unsurTerendah = $this->db->query("SELECT *
            FROM (
                SELECT IF(id_parent = 0, unsur_pelayanan_$table_identity.id, unsur_pelayanan_$table_identity.id_parent) AS id_sub,
                (SELECT nomor_unsur FROM unsur_pelayanan_$table_identity WHERE id_sub = unsur_pelayanan_$table_identity.id) AS nomor_unsur,
                (SELECT nama_unsur_pelayanan FROM unsur_pelayanan_$table_identity WHERE id_sub = unsur_pelayanan_$table_identity.id) AS nama_unsur_pelayanan,
                AVG(IF(jawaban_pertanyaan_unsur_$table_identity.skor_jawaban != 0, skor_jawaban, NULL)) AS rata_rata

                FROM jawaban_pertanyaan_unsur_$table_identity
                JOIN pertanyaan_unsur_pelayanan_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id
                JOIN unsur_pelayanan_$table_identity ON pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan = unsur_pelayanan_$table_identity.id
                JOIN survey_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden
                WHERE survey_$table_identity.is_submit = 1
                GROUP BY id_sub
                ORDER BY SUBSTR(nomor_unsur,2) + 0
            ) jpu_$table_identity ORDER BY rata_rata ASC")->result_array();


        if (in_array(1, $atribut_pertanyaan)) {
            $harapanTerendah = $this->db->query("SELECT *
            FROM (
                SELECT IF(id_parent = 0, unsur_pelayanan_$table_identity.id, unsur_pelayanan_$table_identity.id_parent) AS id_sub,
                CONCAT('H', SUBSTR((SELECT nomor_unsur FROM unsur_pelayanan_$table_identity WHERE id_sub = unsur_pelayanan_$table_identity.id), 2)) AS nomor_harapan,
                (SELECT nama_unsur_pelayanan FROM unsur_pelayanan_$table_identity WHERE id_sub = unsur_pelayanan_$table_identity.id) AS nama_unsur_pelayanan,
                AVG(IF(jawaban_pertanyaan_harapan_$table_identity.skor_jawaban != 0, skor_jawaban, NULL)) AS rata_rata

                FROM jawaban_pertanyaan_harapan_$table_identity
                JOIN pertanyaan_unsur_pelayanan_$table_identity ON jawaban_pertanyaan_harapan_$table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id
                JOIN unsur_pelayanan_$table_identity ON pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan = unsur_pelayanan_$table_identity.id
                JOIN survey_$table_identity ON jawaban_pertanyaan_harapan_$table_identity.id_responden = survey_$table_identity.id_responden
                WHERE survey_$table_identity.is_submit = 1
                GROUP BY id_sub
                ORDER BY SUBSTR(nomor_unsur,2) + 0
            ) jpu_$table_identity ORDER BY rata_rata ASC")->result_array();

                $arrayHarapanTerendah = '<tr>
                    <td width="30%">Unsur Prioritas Perbaikan</td>
                    <td width="5%" align="center">:</td>
                    <td width="65%">
                        <ul>
                            <li>' . $harapanTerendah[0]['nomor_harapan'] . '. ' . $harapanTerendah[0]['nama_unsur_pelayanan'] . '</li>
                            <li>' . $harapanTerendah[1]['nomor_harapan'] . '. ' . $harapanTerendah[1]['nama_unsur_pelayanan'] . '</li>
                            <li>' . $harapanTerendah[2]['nomor_harapan'] . '. ' . $harapanTerendah[2]['nama_unsur_pelayanan'] . '</li>
                        </ul>
                    </td>
                </tr>';
            } else {
                $arrayHarapanTerendah = '';
            }


            $no_Bab3 = 1;
            $htmlbab3 = '
            <table style="width: 100%; line-height: 1.3;">
                <tr>
                    <td style="text-align:center; font-weight: bold; font-size:16px;">BAB III</td>
                </tr>
                <tr>
                    <td style="text-align:center; font-weight: bold; font-size:16px;">PENUTUP<br/><br/></td>
                </tr>
            </table>

            <table style="width: 100%; font-size:13.5px; line-height: 1.3;">
                <tr>
                    <td width="5%"><b>' . $no_Bab3++ . '.</b></td>
                    <td><b>Kesimpulan</b></td>
                </tr>
                <tr>
                    <td width="5%"></td>
                    <td><p style="text-align: justify;">Berdasarkan hasil Survei Kepuasan Masyarakat pada ' . $manage_survey->organisasi . ' diperoleh hasil sebagai berikut:</p></td>
                </tr>
                <tr>
                    <td width="5%"></td>
                    <td>
                        <table width="100%" align="center" style="font-size:13.5px;">
                            <tr>
                                <td width="30%">Nilai IKM</td>
                                <td width="5%" align="center">:</td>
                                <td width="65%">' . ROUND($nilaiIKM, 3) . '</td>
                            </tr>
                            <tr>
                                <td width="30%">Nilai Konversi</td>
                                <td width="5%" align="center">:</td>
                                <td width="65%">' . ROUND($nilaiIKM * $skala_likert, 2) . '</td>
                            </tr>
                            <tr>
                                <td width="30%">Mutu Pelayanan</td>
                                <td width="5%" align="center">:</td>
                                <td width="65%">' . $ktgNilaiIKM . '</td>
                            </tr>
                            <tr>
                                <td width="30%">Unsur Tertinggi</td>
                                <td width="5%" align="center">:</td>
                                <td width="65%">
                                    <ul>
                                        <li>' . $unsurTertinggi[0]['nomor_unsur'] . '. ' . $unsurTertinggi[0]['nama_unsur_pelayanan'] . '</li>
                                        <li>' . $unsurTertinggi[1]['nomor_unsur'] . '. ' . $unsurTertinggi[1]['nama_unsur_pelayanan'] . '</li>
                                        <li>' . $unsurTertinggi[2]['nomor_unsur'] . '. ' . $unsurTertinggi[2]['nama_unsur_pelayanan'] . '</li>
                                    </ul>
                                </td>
                            </tr>
                            <tr>
                                <td width="30%">Unsur Terendah</td>
                                <td width="5%" align="center">:</td>
                                <td width="65%">
                                    <ul>
                                        <li>' . $unsurTerendah[0]['nomor_unsur'] . '. ' . $unsurTerendah[0]['nama_unsur_pelayanan'] . '</li>
                                        <li>' . $unsurTerendah[1]['nomor_unsur'] . '. ' . $unsurTerendah[1]['nama_unsur_pelayanan'] . '</li>
                                        <li>' . $unsurTerendah[2]['nomor_unsur'] . '. ' . $unsurTerendah[2]['nama_unsur_pelayanan'] . '</li>
                                    </ul>
                                </td>
                            </tr>
                            ' . $arrayHarapanTerendah . '
                        </table>
                    </td>
                </tr>
            </table>';
            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $htmlbab3, false, false);


            $section->addTextBreak();

            $htmlbab3 = '
            <table style="width: 100%; font-size:13.5px; line-height: 1.3;">
                <tr>
                    <td width="5%"><b>' . $no_Bab3++ . '.</b></td>
                    <td><b>Rekomendasi</b></td>
                </tr>
                <tr>
                    <td width="5%"></td>
                    <td align="center"><i>Belum ada data rekomendasi.</i></td>
                </tr>
            </table>';
            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $htmlbab3, false, false);


        $filename = 'Laporan ' .  $manage_survey->survey_name;
        header('Content-Type: application/msword');
        header('Content-Disposition: attachment;filename="' . $filename . '.docx"');
        header('Cache-Control: max-age=0');
        $phpWord->save('php://output');
    }
}



/* End of file ReportController.php */
