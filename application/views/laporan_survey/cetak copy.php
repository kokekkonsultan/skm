<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $manage_survey->uuid ?></title>

    <style>
        /* @page {
        margin: 0.2in 0.5in 0.2in 0.5in;
    } */

        /* body {
        padding: .4in;
    } */

        @page {
            margin: 100px 20px;
        }

        .content-paragraph {
            text-indent: 5%;
            text-align: justify;
            text-justify: inter-word;
            line-height: 1.5;
            margin-left: 76px;
            margin-right: 76px;

        }

        .content-list {
            text-indent: 10%;
            text-align: justify;
            text-justify: inter-word;
            line-height: 1.5;

        }

        .page-session {
            page-break-after: always;
            /* font-family: Calibri, sans-serif; */
            font-family: Arial, Helvetica, sans-serif;
            margin: 0.2in 0.5in 0.2in 0.5in;
        }

        .page-session:last-child {
            page-break-after: never;
        }

        .table-list {
            border-collapse: collapse;
            font-family: sans-serif;
            text-align: center;
            font-family: Arial, Helvetica, sans-serif;
        }

        table,
        th,
        td {
            font-size: 13px;
            padding: 3px;
            font-family: Arial, Helvetica, sans-serif;
        }

        li {
            line-height: 1.5;
            text-align: justify;
            font-family: Arial, Helvetica, sans-serif;
        }

        .td-th-list {
            border: 1px solid black;
            height: 20px;
            font-family: Arial, Helvetica, sans-serif;
        }

        header {
            position: fixed;
            top: -90px;
            left: 0px;
            right: 0px;
            /* background-color: lightblue; */
            height: 50px;
            font-family: Arial, Helvetica, sans-serif;
        }

        footer {
            position: fixed;
            bottom: -60px;
            left: 0px;
            right: 0px;
            /* background-color: lightblue; */
            height: 50px;
            font-family: Arial, Helvetica, sans-serif;
        }

        footer .page:after {
            content: counter(page, decimal);
        }
    </style>
</head>

<body>
    <!-- COVER -->
    <div class="page-session">
        <div style="text-align:center;">
            <br>

            <?php if ($profiles->foto_profile != '' || $profiles->foto_profile != null) { ?>
                <img src="<?= base_url() . 'assets/klien/foto_profile/' . $profiles->foto_profile ?>" alt="Logo" width="250" class="center">
            <?php } else { ?>
                <img src="<?=  base_url() ?>assets/klien/foto_profile/200px.jpg" alt="Logo" width="250" class="center">
            <?php } ?>



            <br>
            <br>
            <br>
            <br>


            <div style="font-size:25px; font-weight:bold;">
                LAPORAN<br>SURVEI KEPUASAN MASYARAKAT<br>(SKM)
            </div>
            <br>
            <br>
            <br>
            <div style="font-size:20px; font-weight:bold;">
                <?= strtoupper($manage_survey->organisasi) ?>
                <br>
                <?= strtoupper($profiles->company) ?>
            </div>
            <br>
            <br>


            <?php
            $bulan = array(
                1 =>   'JANUARI',
                'FEBRUARI',
                'MARET',
                'APRIL',
                'MEI',
                'JUNI',
                'JULI',
                'AGUSTUS',
                'SEPTEMBER',
                'OKTOBER',
                'NOVENBER',
                'DESEMBER'
            );
            $month_start = $bulan[(int)date("m", strtotime($manage_survey->survey_start))];
            $month_end = $bulan[(int)date("m", strtotime($manage_survey->survey_end))];
            $year_start = date("Y", strtotime($manage_survey->survey_end));
            $year_end = date("Y", strtotime($manage_survey->survey_end));

            if ($month_start == $month_end) {
                $periode =  $month_end . ' ' . $year_end;
            } else {
                $periode =  $month_start . ' - ' . $month_end . ' ' . $year_end;
            }
            ?>



            <div style="font-size:18px; font-weight:bold;">
                PERIODE <?= $periode ?>
            </div>

        </div>
    </div>

    <header>
        <table style="width: 90%; margin-left: auto; margin-right: auto;" class="table-list">
            <tr>
                <td style="width: 10%;">
                    <?php if ($profiles->foto_profile != '' || $profiles->foto_profile != null) { ?>
                        <img src="<?=  base_url() . 'assets/klien/foto_profile/' . $profiles->foto_profile ?>" alt="Logo" width="70">
                    <?php } else { ?>
                        <img src="<?=  base_url() . 'assets/klien/foto_profile/200px.jpg' ?>" alt="Logo" width="70">
                    <?php } ?>
                </td>
                <td>
                    <div style="color:#DE2226; font-size:16px;">
                        <b>L A P O R A N</b>
                    </div>
                    SURVEI KEPUASAN MASYARAKAT
                    <br>
                    <?= strtoupper($manage_survey->organisasi) ?>
                </td>
            </tr>
        </table>
        <hr>
    </header>

    <footer>
        <hr>
        <table style="width: 90%; margin-left: auto; margin-right: auto;">
            <tr>
                <td align="left">SKM <?= $manage_survey->survey_year ?>
                    <br>
                    <b>Generate by <a target="_blank" href="https://surveiku.com/" style="color:black;">SurveiKu.com</a></b>
                </td>
                <td align="right"><p class="page"></p></td>
            </tr>
        </table>
    </footer>


    <main>

        <!--============================================== BAB I =================================================== -->
        <div class="page-session">
            <table style="width: 100%;">
                <tr>
                    <td style="text-align: center; font-size:18px; font-weight: bold;">
                        BAB I
                        <br>
                        PENDAHULUAN
                        <br>
                        <br>
                    </td>
                </tr>

                <tr>
                    <td><span style="font-weight: bold; ">1. Latar Belakang</span></td>
                </tr>
                <tr>
                    <td class="content-paragraph">
                        Seiring dengan kemajuan teknologi dan tuntutan masyarakat dalam hal pelayanan, maka unit
                        penyelenggara pelayanan publik dituntut untuk memenuhi harapan masyarakat dalam melakukan
                        pelayanan.
                    </td>
                </tr>
                <tr>
                    <td class="content-paragraph">
                        Pelayanan publik yang dilakukan oleh aparatur pemerintah saat ini dirasakan belum memenuhi
                        harapan masyarakat. Hal ini dapat diketahui dari berbagai keluhan masyarakat yang disampaikan
                        melalui media massa dan jejaring sosial. Tentunya keluhan tersebut jika tidak ditangani akan
                        memberikan dampak buruk terhadap pemerintah. Lebih jauh lagi adalah dapat menimbulkan
                        ketidakpercayaan dari masyarakat.
                    </td>
                </tr>
                <tr>
                    <td class="content-paragraph">
                        Salah satu upaya yang harus dilakukan dalam perbaikan pelayanan publik adalah melakukan survei
                        kepuasan masyarakat kepada pengguna layanan dengan mengukur kepuasan masyarakat pengguna
                        layanan.
                        <br>
                        <br>
                    </td>
                </tr>


                <tr>
                    <td><span style="font-weight: bold;">2. Tujuan</span></td>
                </tr>
                <tr>
                    <td class="content-paragraph">Kegiatan Survei Kepuasan Masyarakat terhadap pelayanan publik
                        bertujuan untuk mendapatkan feedback/umpan balik atas kinerja pelayanan yang diberikan kepada
                        masyarakat guna perbaikan dan peningkatan kinerja pelayanan secara berkesinambungan.
                        <br>
                        <br>
                    </td>
                </tr>
            </table>


            <table style="width: 100%;">
                <tr>
                    <td><span style="font-weight: bold;">3. Metodologi</span></td>
                </tr>
                <tr>
                    <td style="padding-left:2.5em;">
                        <li>Populasi<br>
                            Populasi dari kegiatan Survei Kepuasan Masyarakat adalah penyelenggara pelayanan publik,
                            yaitu instansi pemerintah pusat dan pemerintah daerah, termasuk BUMN / BUMD dan BHMN
                            menyesuaikan dengan lingkup yang akan disurvei.
                        </li>
                    </td>
                </tr>

                <tr>
                    <td style="padding-left:2.5em;">
                        <li>Sampel<br>
                            Sampel kegiatan Survei Kepuasan Masyarakat ditentukan dengan menggunakan perhitungan
                            Krejcie and Morgan sebagai berikut:

                        </li>
                    </td>
                </tr>

                <tr>
                    <td style="padding-left:2.7em;"><b>Rumus Krejcie</b>
                        <div style="text-align:center;">
                            <img src="<?= base_url() . 'assets/img/site/rumus_krejcie.png' ?>" alt="rumus krejcie" width="50%">
                        </div>

                    </td>
                </tr>

                <tr>
                    <td style="padding-left:2.7em;">Keterangan :
                        <div style="padding-left:4em;">
                            <table style="width: 100%;">
                                <tr>
                                    <td width="7%">&nbsp;S</td>
                                    <td width="5%">:</td>
                                    <td>Jumlah sampel</td>
                                </tr>
                                <tr>
                                    <td width="7%"><img src="<?= base_url() . 'assets/img/site/lamda.png' ?>" alt="rumus krejcie" width="60%"></td>
                                    <td width="5%">:</td>
                                    <td>Lamda (faktor pengali) dengan dk = 1,<br>
                                        (taraf kesalahan yang digunakan 5%, sehingga nilai lamba
                                        3,841)
                                    </td>
                                </tr>
                                <tr>
                                    <td width="7%">&nbsp;N</td>
                                    <td width="5%">:</td>
                                    <td>Populasi sebanyak
                                        <?= $manage_survey->jumlah_populasi ?></td>
                                </tr>
                                <tr>
                                    <td width="7%">&nbsp;P</td>
                                    <td width="5%">:</td>
                                    <td>Q = 0,5 (populasi menyebar normal)</td>
                                </tr>
                                <tr>
                                    <td width="7%">&nbsp;d</td>
                                    <td width="5%">:</td>
                                    <td>0,05</td>
                                </tr>
                            </table>
                        </div>
                        <div>Sehingga dari perhitungan di atas, jumlah responden minimal yang harus
                            diperoleh adalah
                            <?= $manage_survey->jumlah_sampling ?> responden.</div>

                        <br>
                    </td>
                </tr>



                <tr>
                    <td style="padding-left:2.5em;">
                        <li>Responden<br>
                            Responden adalah penerima pelayanan publik yang pada saat pencacahan sedang berada di
                            lokasi unit pelayanan, atau yang pernah menerima pelayanan dari aparatur penyelenggara
                            pelayanan publik.
                        </li>
                        <br>
                        <br>
                    </td>
                </tr>

                <tr>
                    <td><span style="font-weight: bold;">4. Tim Survei Kepuasan Masyarakat</span></td>
                </tr>
                <tr>
                    <td style="padding-left:1.5em;">Survei Kepuasan Masyarakat ini dilakukan oleh Tim Survei Kepuasan
                        Masyarakat yang telah ditetapkan.
                        <br>
                        <br>
                    </td>
                </tr>

                <tr>
                    <td><span style="font-weight: bold;">5. Jadwal Survei Kepuasan Masyarakat</span></td>
                </tr>
                <tr>
                    <td style="padding-left:1.5em;">Jadwal Survei Kepuasan Masyarakat dilakukan sesuai dengan jadwal
                        yang telah ditentukan.
                    </td>
                </tr>
            </table>
        </div>





        <!--============================================== BAB II =================================================== -->
        <div class="page-session">
            <table style="width: 100%;" class="">
                <tr>
                    <td style="text-align: center; font-size:18px; font-weight: bold;">
                        BAB II
                        <br>
                        ANALISIS
                        <br>
                        <br>
                    </td>
                </tr>


                <tr>
                    <td style=" font-weight: bold;">1. Jenis Pelayanan</td>
                </tr>


                <?php if($manage_survey->is_layanan_survei != 0) { ?>
                <tr>
                    <td style="padding-left:1em;">Berikut merupakan jenis layanan yang diperoleh dari Survei Kepuasan
                        Masyarakat:</td>
                </tr>

                <tr>
                    <td>
                        <table style="width: 90%; margin-left: auto; margin-right: auto;" class="table-list">
                            <tr style="background-color:#E4E6EF;">
                                <th class="td-th-list">No</th>
                                <th class="td-th-list">Jenis Pelayanan</th>
                                <th class="td-th-list">Jumlah</th>
                                <th class="td-th-list">Persentase</th>
                            </tr>


                            <?php
                            $layanan = $this->db->query("SELECT *
                            FROM (SELECT *,
                            (SELECT nama_kategori_layanan FROM kategori_layanan_survei_$table_identity WHERE id = layanan_survei_$table_identity.id_kategori_layanan) AS nama_kategori_layanan,
                            (SELECT COUNT(*) FROM survey_$table_identity WHERE is_submit = 1) AS total_survei,
                            (SELECT COUNT(*) FROM responden_$table_identity JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id_responden WHERE layanan_survei_$table_identity.id = responden_$table_identity.id_layanan_survei && is_submit = 1) AS perolehan
                            FROM layanan_survei_$table_identity
                            WHERE is_active = 1
                            ) ls_$table_identity
                            WHERE perolehan != 0
                            ORDER BY urutan ASC");


                            $no = 1;
                            foreach ($layanan->result() as $row) {
                                $perolehan[] = $row->perolehan;
                                $total_perolehan = array_sum($perolehan);

                                $persentase[] = ($row->perolehan / $row->total_survei) * 100;
                                $total_persentase  = array_sum($persentase);
                                ?>
                                <tr>

                                    <td class="td-th-list"><?= $no++ ?></td>
                                    <td class="td-th-list"><?= $row->nama_layanan ?></td>
                                    <td class="td-th-list"><?= $row->perolehan ?></td>
                                    <td class="td-th-list"><?= ROUND(($row->perolehan / $row->total_survei) * 100, 2) ?> %
                                    </td>
                                </tr>
                            <?php } ?>


                            <tr>
                                <th class="td-th-list" colspan="2">TOTAL</th>
                                <th class="td-th-list"><?= $total_perolehan ?></th>
                                <th class="td-th-list"><?= ROUND($total_persentase) ?> %</th>
                            </tr>

                        </table>
                    </td>
                </tr>
                <?php } else { ?>


                    <tr>
                        <td>
                            <div style="text-align: center;"><i>Survei tidak menggunakan jenis pelayanan.</i>
                            </div>
                        </td>
                    </tr>

                    <?php } ?>
            </table>

            <br>


            <table style="width: 100%;" class="">
                <tr>
                    <td style=" font-weight: bold;">2. Profil Responden</td>
                </tr>
                <tr>
                    <td style="padding-left:1em;">Berikut merupakan karakteristik responden yang diperoleh dari Survei
                        Kepuasan Masyarakat (SKM):</td>
                </tr>
            </table>


            <table style="width: 100%;" class="table-list">

                <?php
                $a = 1;
                $b = 1;
                foreach ($profil_responden->result() as $row) {
                    $kategori_profil_responden = $this->db->query("SELECT *, (SELECT COUNT(*) FROM responden_$table_identity JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id_responden WHERE kategori_profil_responden_$table_identity.id = responden_$table_identity.$row->nama_alias && is_submit = 1) AS perolehan, ROUND((((SELECT COUNT(*) FROM responden_$table_identity JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id_responden WHERE kategori_profil_responden_$table_identity.id = responden_$table_identity.$row->nama_alias && is_submit = 1) / (SELECT COUNT(*) FROM survey_$table_identity WHERE is_submit = 1)) * 100), 2) AS persentase
                     FROM kategori_profil_responden_$table_identity
                     WHERE id_profil_responden = $row->id");
                    ?>

                    <tr>
                        <td>
                            <ul style="font-size: 13px;">
                                <li><b><?= $row->nama_profil_responden ?></b>
                                    <br>
                                    <br>

                                    <?php
                                    $jumlah = [];
                                    $nama_kelompok = [];
                                    $jumlah_persentase = [];
                                    foreach ($kategori_profil_responden->result() as $kpr) {
                                        $jumlah[] = $kpr->perolehan;
                                        $nama_kelompok[] = "'" . str_replace(' ', '+', $kpr->nama_kategori_profil_responden) . "+=+" . ROUND($kpr->persentase, 2) . "%25'";
                                        $jumlah_persentase[] = $kpr->persentase;
                                    }
                                    $total_rekap_responden = implode(",", $jumlah);
                                    $kelompok_rekap_responden = implode(", ", $nama_kelompok);
                                    $persentase_kelompok = implode(",", $jumlah_persentase);
                                    ?>


                                    <div style="text-align: center;">
                                        <div style="outline: dashed 1px black;">
                                            <img src="https://quickchart.io/chart?c={ type: 'horizontalBar', data: { labels: [
                                                                                                                            <?= $kelompok_rekap_responden ?>], datasets: [{ label: 'Dataset 1', backgroundColor: 'rgb(255, 159, 64)', stack: 'Stack 0', data: [<?= $persentase_kelompok ?>], }, ], }, options: { title: { display: false, text: 'Chart.js Bar Chart - Stacked' }, legend: { display: false }, plugins: { roundedBars: true }, responsive: true, }, }" alt="" width="70%">
                                        </div>
                                        <br>
                                        Gambar <?= $a++ ?>. Persentase Responden Berdasarkan
                                        <?= $row->nama_profil_responden ?>
                                    </div>


                                    <?php
                                    //CEK APAKAH ADA PROFIL LAINNYA ATAU TIDAK
                                    if ($row->is_lainnya == 1) {
                                        $lainnya = $row->nama_alias . '_lainnya';
                                        $cek_lainnya = $this->db->query("SELECT *
                                    FROM responden_$table_identity
                                    JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id_responden
                                    WHERE is_submit = 1 && responden_$table_identity.$lainnya != ''");

                                        if ($cek_lainnya->num_rows() > 0) {
                                            ?>
                                            <br>
                                            <br>
                                            <div style="text-align: center;">Tabel <?= $b++ ?>. Persentase Responden pada
                                                <?= $row->nama_profil_responden ?> Lainnya</div>
                                            <table style="width: 100%; margin-left: auto; margin-right: auto;" class="table-list">
                                                <tr style="background-color:#E4E6EF;">
                                                    <th class="td-th-list">No</th>
                                                    <th class="td-th-list"><?= $row->nama_profil_responden ?> Lainnya</th>
                                                </tr>


                                                <?php
                                                $c = 1;
                                                $profil_lainnya = $this->db->query("SELECT *
                                        FROM responden_$table_identity
                                        JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id_responden
                                        WHERE is_submit = 1");

                                                foreach ($profil_lainnya->result() as $value) {
                                                    if ($value->$lainnya != '') {
                                                        ?>
                                                        <tr>
                                                            <td class="td-th-list"><?= $c++ ?></td>
                                                            <td class="td-th-list"><?= $value->$lainnya ?></td>
                                                        </tr>
                                                    <?php }
                                            } ?>

                                            </table>
                                        <?php }
                                } ?>

                                </li>
                            </ul>
                        </td>
                    </tr>
                <?php } ?>

            </table>



            <table style="width: 100%;" class="">
                <tr>
                    <td style=" font-weight: bold;">3. Nilai Indeks Kepuasan Masyarakat</td>
                </tr>
                <tr>
                    <td class="content-paragraph" style="padding-left: 1em;">
                        Hasil Survei Kepuasan Masyarakat <?= $manage_survey->organisasi ?> mendapatkan nilai
                        Indeks Kepuasan Masyarakat (IKM) sebesar <b><?= round($nilai_skm, 2) ?></b>, dengan mutu
                        pelayanan <b><?= $ketegori ?></b>. Nilai Indeks Kepuasan Masyarakat (IKM) tersebut
                        didapat dari nilai rata-rata seluruh unsur pada tabel berikut
                    </td>
                </tr>

                <tr>
                    <td style="padding-left: 1em;">
                        <br>
                        <?php $table_next_1 = $this->db->get_where("profil_responden_$table_identity", array('is_lainnya' => 1))->num_rows() + 1; ?>
                        <div style="text-align: center;">Tabel <?= $table_next_1 ?>. Nilai Unsur
                            <?= $manage_survey->organisasi ?></div>

                        <table style="width: 90%; margin-left: auto; margin-right: auto;" class="table-list">
                            <tr style="background-color:#E4E6EF;">
                                <th class="td-th-list">No</th>
                                <th class="td-th-list">Unsur</th>
                                <th class="td-th-list">Nilai Indeks</th>
                                <th class="td-th-list">Predikat</th>
                            </tr>


                            <?php
                            $no = 1;
                            foreach ($nilai_per_unsur->result() as $row) {
                                $indeks = ROUND($row->nilai_per_unsur * $skala_likert, 10);
                                foreach ($definisi_skala->result() as $obj) {
                                    if ($indeks <= $obj->range_bawah && $indeks >= $obj->range_atas) {
                                        $ktg = $obj->kategori;
                                    }
                                }
                                if ($indeks <= 0) {
                                    $ktg = 'NULL';
                                }
                                ?>
                                <tr>
                                    <td class="td-th-list">
                                        <?= $no++ ?>
                                    </td>
                                    <td class="td-th-list" style="text-align: left;">
                                        <?= $row->nomor_unsur . '. ' . $row->nama_unsur_pelayanan ?>
                                    </td>
                                    <td class="td-th-list">
                                        <?= ROUND($row->nilai_per_unsur, 3) ?>
                                    </td>
                                    <td class="td-th-list">
                                        <?= $ktg ?>
                                    </td>
                                </tr>
                            <?php } ?>

                            <tr>
                                <td class="td-th-list" colspan="2"><b>Nilai Indeks Kepuasan Masyarakat</b></td>
                                <td class="td-th-list"><b><?= round($nilai_tertimbang, 3) ?></b></td>
                                <td class="td-th-list"><?= $ketegori ?></td>
                            </tr>
                            <tr>
                                <td class="td-th-list" colspan="2"><b>Nilai Konversi</b></td>
                                <td class="td-th-list"><b><?= round($nilai_skm, 2) ?></b></td>
                                <td class="td-th-list"><b><?= $ketegori ?></b></td>
                            </tr>

                        </table>
                    </td>
                </tr>

                <tr>
                    <td class="content-paragraph" style="padding-left: 1em;">
                    Nilai unsur Survei Kepuasan Masyarakat pada<?= $manage_survey->organisasi ?> dapat dilihat pada gambar di bawah ini.
                        <br>
                        <br>
                    </td>
                </tr>

                <tr>
                    <td style="text-align: center; padding-left: 1em;">
                        <div style="outline: dashed 1px black;">
                            <img src="https://quickchart.io/chart?c={ type: 'horizontalBar', data: { labels: [<?= $nama_per_unsur ?>], datasets: [{ label: 'Dataset 1', backgroundColor: 'rgb(255, 159, 64)', stack: 'Stack 0', data: [<?= $bobot_per_unsur ?>], }, ], }, options: { title: { display: false, text: 'Chart.js Bar Chart - Stacked' }, legend: { display: false }, plugins: { roundedBars: true, datalabels: { anchor: 'center', align: 'center', color: 'white', font: { weight: 'normal', }, }, }, responsive: true, }, }" alt="" width="70%">
                        </div>
                        <br>
                        <?php $gambar_next_1 = $profil_responden->num_rows() + 1; ?>
                        Gambar <?= $gambar_next_1 ?>. Grafik Unsur <?= $manage_survey->organisasi ?>
                    </td>
                </tr>
            </table>




            <table style="width: 100%;" class="">
                <tr>
                    <td style=" font-weight: bold;">4. Pembahasan Unsur</td>
                </tr>
                <tr>
                    <td class="content-paragraph" style="padding-left: 1em;">
                        Unsur yang dipakai dalam Survei Kepuasan Masyarakat dapat dijadikan sebagai acuan untuk
                        mengetahui kondisi Unit Pelayanan Publik pada <?= $manage_survey->organisasi ?> yang
                        nantinya dijadikan suatu pedoman perbaikan kinerja. Pada pembahasan ini akan dijelaskan terkait
                        persentase jawaban pada masing-masing unsur dalam Survei Kepuasan Masyarakat.
                    </td>
                </tr>
            </table>

            <div style="font-size: 13px;"><?= $html_rekap_tambahan_atas . $get_html . $html_rekap_tambahan_bawah ?></div>


            <?php if (in_array(1, $atribut_pertanyaan)) { ?>
                <table style="width: 100%;" class="">
                    <tr>
                        <td style=" font-weight: bold;">5. Diagram Persepsi dan Harapan</td>
                    </tr>
                    <tr>
                        <td style="text-align: center; padding-left: 1em;">
                            <div style="outline: dashed 1px black;">
                                <img src="<?= base_url() . '/assets/klien/img_kuadran/kuadran-' . $manage_survey->table_identity . '.png' ?>" alt="" width="80%;">
                            </div>


                        </td>
                    </tr>
                </table>

                <table style="width: 90%; margin-left: auto; margin-right: auto;" class="table-list">
                    <tr>
                        <th class="td-th-list" width="30%" style="vertical-align: middle; background-color:#E4E6EF;">KUADRAN
                            I</th>
                        <td class="td-th-list" style="text-align: left;">
                            <ul>
                                <?php foreach ($kuadran_unsur->result() as $row) { ?>
                                    <?php if ($row->kuadran == 1) { ?>
                                        <li><?= $row->nomor_unsur . '. ' . $row->nama_unsur_pelayanan ?></li>
                                    <?php } ?>
                                <?php } ?>
                            </ul>
                        </td>
                    </tr>

                    <tr>
                        <th class="td-th-list" width="30%" style="vertical-align: middle; background-color:#E4E6EF;">KUADRAN
                            II</th>
                        <td class="td-th-list" style="text-align: left;">
                            <ul>
                                <?php foreach ($kuadran_unsur->result() as $row) { ?>
                                    <?php if ($row->kuadran == 2) { ?>
                                        <li><?= $row->nomor_unsur . '. ' . $row->nama_unsur_pelayanan ?></li>
                                    <?php } ?>
                                <?php } ?>
                            </ul>
                        </td>
                    </tr>

                    <tr>
                        <th class="td-th-list" width="30%" style="vertical-align: middle; background-color:#E4E6EF;">KUADRAN
                            III</th>
                        <td class="td-th-list" style="text-align: left;">
                            <ul>
                                <?php foreach ($kuadran_unsur->result() as $row) { ?>
                                    <?php if ($row->kuadran == 3) { ?>
                                        <li><?= $row->nomor_unsur . '. ' . $row->nama_unsur_pelayanan ?></li>
                                    <?php } ?>
                                <?php } ?>
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <th class="td-th-list" width="30%" style="vertical-align: middle; background-color:#E4E6EF;">KUADRAN
                            VI</th>
                        <td class="td-th-list" style="text-align: left;">
                            <ul>
                                <?php foreach ($kuadran_unsur->result() as $row) { ?>
                                    <?php if ($row->kuadran == 4) { ?>
                                        <li><?= $row->nomor_unsur . '. ' . $row->nama_unsur_pelayanan ?></li>
                                    <?php } ?>
                                <?php } ?>
                            </ul>
                        </td>
                    </tr>
                </table>
            <?php } ?>


            <br>


            <table style="width: 100%;" class="">
                <tr>
                    <td style=" font-weight: bold;">6. Saran Responden</td>
                </tr>
                <tr>
                    <td class="content-paragraph" style="padding-left: 1em;">
                        Saran responden mengenai Survei Kepuasan Masyarakat pada
                        <?= $manage_survey->organisasi ?> sebagai berikut:</td>
                </tr>
            </table>



            <table style="width: 100%; margin-left: auto; margin-right: auto; padding-left: 1em;" class="table-list">
                <?php
                $table_next_2 = $this->db->get_where("unsur_pelayanan_$table_identity", array('id_parent' => 0))->num_rows() + $table_next_1 + 1;
                $saran = $this->db->query("SELECT * FROM survey_$table_identity WHERE is_submit = 1 && saran != '' && is_active = 1");
                ?>

                <!-- CEK APAKAH ADA SARAN YANG DIISIKAN -->
                <?php if ($saran->num_rows() > 0) { ?>
                    <tr>
                        <td colspan="2">
                            <div style="text-align: center;">Tabel <?= $table_next_2 ?>. Saran Masukan Responden
                            </div>
                        </td>
                    </tr>

                    <tr style="background-color:#E4E6EF;">
                        <th class="td-th-list">No</th>
                        <th class="td-th-list">Isi Saran</th>
                    </tr>

                    <?php
                    //LOOPING SARAN
                    $d = 1;
                    foreach ($saran->result() as $row) { ?>
                        <tr>
                            <td class="td-th-list" width="5%"><?= $d++ ?></td>
                            <td class="td-th-list" style="text-align:left;"><?= $row->saran ?></td>
                        </tr>
                    <?php } ?>

                    <!-- JIKA TIDAK ADA SARAN -->
                <?php } else { ?>

                    <tr>
                        <td colspan="2">
                            <div style="text-align: center;"><i>Tidak ada saran dan masukan yang di dapat dalam survei.</i>
                            </div>
                        </td>
                    </tr>

                <?php } ?>

            </table>
        </div>




        <!--============================================== BAB III =================================================== -->
        <div class="page-session">
            <table style="width: 100%;" class="">
                <tr>
                    <td style="text-align: center; font-size:18px; font-weight: bold;" colspan="3">
                        BAB III
                        <br>
                        PENUTUP
                        <br>
                        <br>
                    </td>
                </tr>

                <tr>
                    <td><span style="font-weight: bold;" colspan="3">1. Kesimpulan</span></td>
                </tr>

                <tr>
                    <td style="padding-left:1em;" colspan="3">Berdasarkan hasil Survei Kepuasan Masyarakat pada <?= $manage_survey->organisasi ?> diperoleh hasil sebagai berikut:</td>
                </tr>


                <tr>
                    <td style="padding-left:1em;" width="31%" valign>Nilai IKM</td>
                    <td width="4%">:</td>
                    <td><?= round($nilai_tertimbang, 3) ?></td>
                </tr>
                <tr>
                    <td style="padding-left:1em;" width="31%">Nilai Konversi</td>
                    <td width="4%">:</td>
                    <td><?= round($nilai_skm, 2) ?></td>
                </tr>
                <tr>
                    <td style="padding-left:1em;" width="31%">Mutu Pelayanan</td>
                    <td width="4%">:</td>
                    <td><?= $ketegori ?></td>
                </tr>

                <tr>
                    <td style="padding-left:1em;" width="31%" valign="top">Unsur Tertinggi</td>
                    <td width="4%" valign="top">:</td>
                    <td style="line-height: 1.5;"><?= $desc ?></td>
                </tr>
                <tr>
                    <td style="padding-left:1em;" width="31%" valign="top">Unsur Terendah</td>
                    <td width="4%" valign="top">:</td>
                    <td style="line-height: 1.5;"><?= $asc ?></td>
                </tr>

                <?php if (in_array(1, $atribut_pertanyaan)) { ?>
                <tr>
                    <td style="padding-left:1em;" width="31%" valign="top">Unsur Prioritas Perbaikan</td>
                    <td width="4%" valign="top">:</td>
                    <td style="line-height: 1.5;"><?= $asc_harapan ?></td>
                </tr>
                <?php } ?>
            </table>



            <table style="width: 100%; padding-top:1em;">
                <tr>
                    <td><span style="font-weight: bold; ">2. Rekomendasi</span></td>
                </tr>
            </table>

            <?php if($analisa->num_rows() > 0 ) { ?>
            <?php foreach($analisa->result() as $value) { ?>
            <div style="outline: dashed 1px black; padding-left:1em;">
            <table style="width: 100%;" class="">
                    <tr>
                        <td width="31%"><?= in_array(1, $atribut_pertanyaan) ? 'Unsur Prioritas Perbaikan' : 'Unsur Terendah' ?></td>
                        <td width="4%">:</td>
                        <td><?= $value->nomor_unsur . '. ' . $value->nama_unsur_pelayanan ?></td>
                    </tr>

                    <tr>
                        <td width="31%">Faktor yang Mempengaruhi</td>
                        <td width="4%">:</td>
                        <td><?= $value->faktor_penyebab ?></td>
                    </tr>

                    <tr>
                        <td width="31%">Rencana Tindak Lanjut</td>
                        <td width="4%">:</td>
                        <td><?= $value->rencana_perbaikan ?></td>
                    </tr>

                    <tr>
                        <td width="31%">Target Waktu</td>
                        <td width="4%">:</td>
                        <td><?= $value->waktu ?></td>
                    </tr>

                    <tr>
                        <td width="31%">PIC</td>
                        <td width="4%">:</td>
                        <td><?= $value->penanggung_jawab?></td>
                    </tr>
            </table>
            </div>
            <br>
            <?php } ?>

            <?php } else { ?>

            <div style="text-align:center; font-size:13px;"><i>Belum ada data rekomendasi.</i></div>

            <?php } ?>

        </div>


    </main>


</body>
</html>