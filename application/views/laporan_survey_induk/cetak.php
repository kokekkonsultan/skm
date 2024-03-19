<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan
        <?php echo $manage_survey->survey_name ?>
    </title>

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
            text-indent: 10%;
            text-align: justify;
            text-justify: inter-word;
            line-height: 1.5;
            margin-left: 76px;
            margin-right: 76px;
            font-size: 13px;
        }

        .content-list {
            text-indent: 10%;
            text-align: justify;
            text-justify: inter-word;
            line-height: 1.5;
            font-size: 13px;
        }

        .page-session {
            page-break-after: always;
            font-family: Calibri, sans-serif;
            margin: 0.2in 0.5in 0.2in 0.5in;
        }

        .page-session:last-child {
            page-break-after: never;
        }

        .table-list {
            border-collapse: collapse;
            font-family: sans-serif;
            font-size: 13px;
            text-align: center;
        }

        .td-th-list {
            border: 1px solid black;
            height: 20px;
        }

        header {
            position: fixed;
            top: -90px;
            left: 0px;
            right: 0px;
            /* background-color: lightblue; */
            height: 50px;
        }

        footer {
            position: fixed;
            bottom: -60px;
            left: 0px;
            right: 0px;
            /* background-color: lightblue; */
            height: 50px;
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
            <img src="<?php echo base_url() . 'assets/klien/foto_profile/' . $profiles->foto_profile ?>" alt="Logo" width="250" class="center">
            <?php } else { ?>
            <img src="<?php echo base_url() . 'assets/klien/foto_profile/200px.jpg' ?>" alt="Logo" width="250" class="center">
            <?php } ?>
 


            <br>
            <br>
            <br>
            <br>

            <h1>LAPORAN</h1>
            <br>
            <h2>Survei Kepuasan Masyarakat</h2>
            <h3><?php echo $manage_survey->organisasi ?></h3>
            <h4>Periode <?php echo date("d-m-Y", strtotime($manage_survey->survey_start)) ?> - <?php echo date("d-m-Y", strtotime($manage_survey->survey_end)) ?></h4>

            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>



            <span><?php echo strtoupper($manage_survey->organisasi) ?></span>
            <br>
            <span><?php echo $manage_survey->alamat ?></span>
            <br>
            <span><?php echo $manage_survey->no_tlpn ?></span>
            <br>
            <span><?php echo $manage_survey->email ?></span>

        </div>
    </div>

    <header>
        <table style="width: 90%; margin-left: auto; margin-right: auto;" class="table-list">
            <tr>
                <td style="width: 10%;">
                    <?php if ($profiles->foto_profile != '' || $profiles->foto_profile != null) { ?>
                    <img src="<?php echo base_url() . 'assets/klien/foto_profile/' . $profiles->foto_profile ?>" alt="Logo" width="70">
                    <?php } else { ?>
                    <img src="<?php echo base_url() . 'assets/klien/foto_profile/200px.jpg' ?>" alt="Logo" width="70">
                    <?php } ?>
                </td>
                <td>
                    <div style="color:#DE2226; font-size:16px;">
                        <b>L A P O R A N</b>
                    </div>
                    Survei Kepuasan Masyarakat
                </td>
            </tr>
        </table>
        <hr>
    </header>

    <footer>
        <div style="text-align:center;">
            <hr>
            <?php echo strtoupper($manage_survey->organisasi) ?> -
            <?php echo date("Y") ?>
            <br>
            <p class="page"></p>
        </div>
    </footer>

    <main>

        <!-- PROFIL ORGANISASI -->
        <div class="page-session">
            <table style="width: 100%;">
                <tr>
                    <td style="text-align: center; font-size:18px; font-weight: bold;">
                        PROFIL ORGANISASI
                        <br>
                        <br>
                    </td>
                </tr>
                <tr>
                    <td class="content-paragraph">
                        Survei Kepuasan Masyarakat di
                        <?php echo $manage_survey->organisasi ?> dilaksanakan pada seluruh
                        layanan. Survei ini mendapat respon positif dari masyarakat yang mengharapkan adanya perbaikan
                        kinerja pelayanan. Berikut merupakan profil organisasi unit penyelenggara pelayanan publik.
                    </td>
                </tr>
            </table>

            <table style="width: 100%;">
            <tr>
                    <th class="content-list" width="20%">Nama Instansi</th>
                    <td class="content-list" width="5%">:</td>
                    <td class="content-list" width="75%">
                        <?php echo $manage_survey->organisasi ?>
                    </td>
                </tr>
                <tr>
                    <th class="content-list" width="20%">Alamat</th>
                    <td class="content-list" width="5%">:</td>
                    <td class="content-list" width="75%">
                        <?php echo $manage_survey->alamat ?>
                    </td>
                </tr>
                <tr>
                    <th class="content-list" width="20%">No.Telp/Fax</th>
                    <td class="content-list" width="5%">:</td>
                    <td class="content-list" width="75%">
                        <?php echo $manage_survey->no_tlpn ?>
                    </td>
                </tr>
                <tr>
                    <th class="content-list" width="20%">Email</th>
                    <td class="content-list" width="5%">:</td>
                    <td class="content-list" width="75%">
                        <?php echo $manage_survey->email ?>
                    </td>
                </tr>
                <tr>
                    <th class="content-list" width="20%">Visi</th>
                    <td class="content-list" width="5%">:</td>
                    <td class="content-list" width="75%">
                        <?php echo $manage_survey->visi ?>
                    </td>
                </tr>
                <tr>
                    <th class="content-list" width="20%">Misi</th>
                    <td class="content-list" width="5%">:</td>
                    <td class="content-list" width="75%">
                        <?php echo $manage_survey->misi ?>
                    </td>
                </tr>
            </table>
        </div>

        <div class="page-session">
            <table style="width: 100%;">
                <tr>
                    <td style="text-align: center; font-size:18px; font-weight: bold;">
                        HASIL SURVEI KEPUASAN MASYARAKAT
                        <br>
                        <br>
                    </td>
                </tr>
                <tr>
                    <td class="content-paragraph">
                        Hasil Survei Kepuasan Masyarakat
                        <?php echo $manage_survey->organisasi ?> Periode
                        <?php echo date("d-m-Y", strtotime($manage_survey->survey_start)) ?> s/d
                        <?php echo date("d-m-Y", strtotime($manage_survey->survey_end)) ?> dengan
                        total
                        <?php echo $survey->num_rows() ?> responden seperti pada tabel 1 menghasilkan Indeks
                        Kepuasan Masyarakat (IKM)
                        sebesar
                        <?php echo round($nilai_tertimbang, 3) ?>. Dengan demikian pelayanan publik pada
                        <?php echo $manage_survey->organisasi ?> berada pada
                        kategori
                        <?php echo $ketegori ?> atau
                        dengan nilai konversi IKM sebesar
                        <?php echo round($nilai_skm, 2) ?>.
                    </td>
                </tr>
            </table>

            <br>

            <table style="width: 95%; margin-left: auto; margin-right: auto;" class="table-list">
                <tr>
                    <td colspan="5" style="text-align: center;">Tabel 1. Nilai IKM</td>
                </tr>
                <tr style="background-color:#E4E6EF;">
                    <th class="td-th-list">No</th>
                    <th class="td-th-list">Unit Pelayanan</th>
                    <th class="td-th-list">Nilai IKM</th>
                    <th class="td-th-list">Nilai Konversi</th>
                    <th class="td-th-list">Mutu Pelayanan</th>
                </tr>
                <tr>
                    <td class="td-th-list">1</td>
                    <td class="td-th-list" style="text-align: left;">
                        <?php echo $manage_survey->organisasi ?>
                    </td>
                    <td class="td-th-list">
                        <?php echo round($nilai_tertimbang, 3) ?>
                    </td>
                    <td class="td-th-list">
                        <?php echo round($nilai_skm, 2) ?>
                    </td>
                    <td class="td-th-list">
                        <?php echo $mutu_pelayanan ?>
                    </td>
                </tr>
            </table>

            <br>

            <table style="width: 100%;">
                <tr>
                    <td class="content-paragraph">
                        Hasil SKM tersebut di atas, terdiri dari
                        <?php echo $nilai_per_unsur->num_rows() ?> unsur
                        pelayanan, sebagaimana tersebut dalam tabel 2
                        di bawah ini.
                    </td>
                </tr>
            </table>

            <br>

            <table style="width: 90%; margin-left: auto; margin-right: auto;" class="table-list">
                <tr>
                    <td colspan="4" style="text-align: center;">Tabel 2. Nilai SKM Per Unsur Pelayanan</td>
                </tr>
                <tr style="background-color:#E4E6EF;">
                    <th class="td-th-list">No</th>
                    <th class="td-th-list">Unsur</th>
                    <th class="td-th-list">Indeks</th>
                    <th class="td-th-list">Kategori</th>
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

                    // if ($indeks >= 25 &&  $indeks <= 64.99) {
                    //     $ktg = "Tidak Baik";
                    // } elseif ($indeks >= 65 &&  $indeks <= 76.60) {
                    //     $ktg = "Kurang Baik";
                    // } elseif ($indeks >= 76.61 &&  $indeks <= 88.30) {
                    //     $ktg = "Baik";
                    // } elseif ($indeks >= 88.31 &&  $indeks <= 100) {
                    //     $ktg = "Sangat Baik";
                    // } else {
                    //     $ktg = "NULL";
                    // };
                    ?>
                <tr>
                    <td class="td-th-list">
                        <?php echo $no++ ?>
                    </td>
                    <td class="td-th-list" style="text-align: left;">
                        <?php echo $row->nomor_unsur . '. ' . $row->nama_unsur_pelayanan ?>
                    </td>
                    <td class="td-th-list">
                        <?php echo ROUND($row->nilai_per_unsur, 3) ?>
                    </td>
                    <td class="td-th-list">
                        <?php echo $ktg ?>
                    </td>
                </tr>
                <?php 
            } ?>
            </table>

            <br>
            <br>

            <table style="width: 90%; margin-left: auto; margin-right: auto;" class="table-list">
                <tr>
                    <td style="text-align: center;">Gambar 1. Bar Chart Nilai SKM Per Unsur Pelayanan</td>
                </tr>
                <tr>
                    <td>
                        <img src="https://image-charts.com/chart?chbh=20&chbr=10&chd=t:<?php echo $bobot_per_unsur ?>&chs=500x250&cht=bhs&chxr=1,0,5&chxt=y,x&chxl=0%3A|<?php echo $nama_per_unsur ?>&chco=00A5C6" alt="">
                    </td>
                </tr>
            </table>

            <br>



            <table style="width: 90%; margin-left: auto; margin-right: auto;" class="table-list">
                <tr>
                    <td colspan="3" style="text-align: center;">Tabel 3. Ringkasan Hasil Survei Kepuasan Masyarakat</td>
                </tr>
                <tr style="background-color:#E4E6EF;">
                    <th class="td-th-list" width="6%">No</th>
                    <th class="td-th-list">Kesimpulan</th>
                    <th class="td-th-list">Keterangan</th>
                </tr>
                <tr>
                    <td class="td-th-list">1</td>
                    <td class="td-th-list" style="text-align: left;">Nilai IKM</td>
                    <td class="td-th-list">
                        <?php echo round($nilai_tertimbang, 3) ?>
                    </td>
                </tr>
                <tr>
                    <td class="td-th-list">2</td>
                    <td class="td-th-list" style="text-align: left;">Nilai IKM Konversi</td>
                    <td class="td-th-list">
                        <?php echo round($nilai_skm, 2) ?>
                    </td>
                </tr>
                <tr>
                    <td class="td-th-list">3</td>
                    <td class="td-th-list" style="text-align: left;">Kualitas Pelayanan</td>
                    <td class="td-th-list">
                        <?php echo $ketegori ?>
                    </td>
                </tr>
                <tr>
                    <td class="td-th-list">4</td>
                    <td class="td-th-list" style="text-align: left;">Unsur Tertinggi</td>
                    <td class="td-th-list">
                        <?php echo $desc ?>
                    </td>
                </tr>
                <tr>
                    <td class="td-th-list">5</td>
                    <td class="td-th-list" style="text-align: left;">Unsur Terendah</td>
                    <td class="td-th-list">
                        <?php echo $asc ?>
                    </td>
                </tr>
            </table>
        </div>

        <div class="page-session">
            <table style="width: 100%;">
                <tr>
                    <td style="text-align: center; font-size:18px; font-weight: bold;">
                        KARAKTERISTIK RESPONDEN
                        <br>
                        <br>
                    </td>
                </tr>
                <tr>
                    <td class="content-paragraph">
                        Responden merupakan pihak yang dipakai sebagai sampel dalam sebuah penelitian. Karakteristik
                        responden akan mempengaruhi teknik sampling yang digunakan dalam penelitian. Responden dipilih
                        secara acak yang ditentukan sesuai dengan karakteristik di
                        <?php echo $manage_survey->organisasi ?> dan diambil
                        jumlah minimal responden yang telah ditetapkan. Peran responden ialah memberikan tanggapan dan
                        informasi terkait data yang dibutuhkan oleh peneliti, serta memberikan masukan kepada peneliti,
                        baik secara langsung maupun tidak langsung.
                    </td>
                </tr>
                <tr>
                    <td class="content-paragraph">
                        Secara umum responden dibagi dalam karakteristik <?php echo $profil_urutan ?> dll.
                        Secara rinci dapat dilihat pada pie chart dan tabel dibawah ini.
                    </td>
                </tr>
            </table>


            <ul style="font-size: 13px;">

                <?php foreach ($profil_responden->result() as $row) {
                    $kategori_profil_responden = $this->db->query("SELECT *, (SELECT COUNT(*) FROM responden_$table_identity JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id_responden WHERE kategori_profil_responden_$table_identity.id = responden_$table_identity.$row->nama_alias && is_submit = 1) AS perolehan, ROUND((((SELECT COUNT(*) FROM responden_$table_identity JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id_responden WHERE kategori_profil_responden_$table_identity.id = responden_$table_identity.$row->nama_alias && is_submit = 1) / (SELECT COUNT(*) FROM survey_$table_identity WHERE is_submit = 1)) * 100), 2) AS persentase
                    FROM kategori_profil_responden_$table_identity
                    WHERE id_profil_responden = $row->id");
                    ?>



                <li style="font-size: 13px;">
                    <b>
                        <?php echo $row->nama_profil_responden ?></b>
                    <br><br>

                    <?php
                    $jumlah = [];
                    $nama_kelompok = [];
                    $jumlah_persentase = [];
                    foreach ($kategori_profil_responden->result() as $kpr) {
                        $jumlah[] = $kpr->perolehan;
                        $nama_kelompok[] = str_replace(' ', '+', $kpr->nama_kategori_profil_responden) . '+=+' . $kpr->persentase . '%'; //'%27' . str_replace(' ', '+', $kpr->nama_kategori_profil_responden) . '%27';
                        $jumlah_persentase[] = $kpr->persentase;
                    }
                    $total_rekap_responden = implode(",", $jumlah);
                    $kelompok_rekap_responden = implode("|", $nama_kelompok);
                    $persentase_kelompok = implode(",", $jumlah_persentase);
                    ?>

                    <table style="width: 80%; margin-left: auto; margin-right: auto;" class="table-list">
                        <tr>
                            <td style="text-align: center;">
                                <?php if ($kategori_profil_responden->num_rows() < 7) { ?>

                                <img src="https://image-charts.com/chart?chd=t:<?php echo $persentase_kelompok ?>&chdlp=b&chdl=<?php echo $kelompok_rekap_responden ?>&chf=ps0-0%2Clg%2C45%2Cfc3dd6%2C0.2%2Cfc3d3d7C%2C1%7Cps0-1%2Clg%2C45%2C2b4fc4%2C0.2%2C32c9c47C%2C1%7Cps0-2%2Clg%2C45%2CEA469E%2C0.2%2C03A9F47C%2C1%7Cps0-3%2Clg%2C45%2Cfacc00%2C0.2%2Cffca477C%2C1%7Cps0-4%2Clg%2C45%2Cf2fa05%2C0.2%2C2fa36f7C%2C1%7Cps0-4%2Clg%2C45%2C098d9c%2C0.2%2C840ccf7C%2C1&chs=500x200&cht=pc&chxt=x%2Cy" alt="" width="400px;">

                                <?php 
                            } else { ?>

                                <img src="https://image-charts.com/chart?chbh=20&chbr=10&chd=t:<?php echo $total_rekap_responden ?>&chs=600x300&cht=bhs&chxr=1,0,100&chxt=y,x&chxl=0%3A|<?php echo $kelompok_rekap_responden ?>&chco=57a8e6" alt="" width="400px;">

                                <?php 
                            } ?>

                            </td>
                        </tr>
                    </table>

                    <br>
                    <br>

                    <table style="width: 80%; margin-left: auto; margin-right: auto;" class="table-list">
                        <tr style="background-color:#E4E6EF;">
                            <th class="td-th-list">No</th>
                            <th class="td-th-list">Kelompok</th>
                            <th class="td-th-list">Jumlah</th>
                            <th class="td-th-list">Persentase</th>
                        </tr>

                        <?php
                        $i = 1;
                        foreach ($kategori_profil_responden->result() as $value) { ?>
                        <tr>
                            <td class="td-th-list">
                                <?php echo $i++ ?>
                            </td>
                            <td class="td-th-list" style="text-align: left;">
                                <?php echo $value->nama_kategori_profil_responden ?>
                            </td>
                            <td class="td-th-list">
                                <?php echo $value->perolehan ?>
                            </td>
                            <td class="td-th-list">
                                <?php echo $value->persentase ?>%</td>
                        </tr>
                        <?php 
                    } ?>
                    </table>
                    <br>
                    <br>
                    <br>
                </li>
                <?php 
            } ?>
            </ul>

        </div>



        <div class="page-session">
            <table style="width: 100%;">
                <tr>
                    <td style="text-align: center; font-size:18px; font-weight: bold;">
                        CHART UNSUR SKM
                        <br>
                        <br>
                    </td>
                </tr>
            </table>

            <ul style="font-size: 13px;">
                <?php echo $get_html ?>
            </ul>

        </div>


        <div class="page-session">
            <table style="width: 100%;">
                <tr>
                    <td style="text-align: center; font-size:18px; font-weight: bold;">
                        REKAPITULASI ALASAN JAWABAN PERTANYAAN UNSUR
                        <br>
                        <br>
                    </td>
                </tr>
            </table>

            <ul style="font-size: 13px;">
                <?php echo $html_rekap_alasan ?>
            </ul>

        </div>


        <?php if (in_array(2, $atribut_pertanyaan)) { ?>
        <div class="page-session">
            <table style="width: 100%;">
                <tr>
                    <td style="text-align: center; font-size:18px; font-weight: bold;">
                        REKAPITULASI PERTANYAAN TAMBAHAN
                        <br>
                        <br>
                    </td>
                </tr>
            </table>

            <ul style="font-size: 13px;">
                <?php echo $html_rekap_tambahan ?>
            </ul>
        </div>
        <?php } ?>


        <?php if (in_array(3, $atribut_pertanyaan)) { ?>
        <div class="page-session">
            <table style="width: 100%;">
                <tr>
                    <td style="text-align: center; font-size:18px; font-weight: bold;">
                        REKAPITULASI PERTANYAAN KUALITATIF
                        <br>
                        <br>
                    </td>
                </tr>
            </table>

            <ol style="font-size: 13px;">
                <?php echo $html_rekap_kualitatif ?>
            </ol>
        </div>
        <?php } ?>


        <?php if($manage_survey->is_saran == 1) { ?>
        <div class="page-session">
            <table style="width: 100%;">
                <tr>
                    <td style="text-align: center; font-size:18px; font-weight: bold;">
                        REKAPITULASI SARAN / OPINI RESPONDEN
                        <br>
                        <br>
                    </td>
                </tr>
            </table>

            <table style="width: 100%; margin-left: auto; margin-right: auto;" class="table-list">
                <tr style="background-color:#E4E6EF;">
                    <th class="td-th-list">No</th>
                    <th class="td-th-list">Saran</th>
                </tr>

                <?php
                $n = 1;
                foreach ($saran_res->result() as $row) { ?>
                <tr>
                    <td class="td-th-list" width="6%">
                        <?php echo $n++ ?>
                    </td>
                    <td class="td-th-list" style="text-align: left;">
                        <?php echo $row->saran ?>
                    </td>
                </tr>
                <?php 
            } ?>
            </table>
        </div>
        <?php } ?>



        <?php if (in_array(1, $atribut_pertanyaan)) { ?>
        <div class="page-session">
            <table style="width: 100%;">
                <tr>
                    <td style="text-align: center; font-size:18px; font-weight: bold;">
                        KUADRAN UNSUR SKM
                        <br>
                        <br>
                    </td>
                </tr>

                <tr>
                    <td style="text-align: center;">
                        <img src="<?php echo base_url() ?>/assets/klien/img_kuadran/kuadran-<?php echo $manage_survey->table_identity ?>.png" alt="" width="600px;">
                    </td>
                </tr>
            </table>

            <br>

            <table style="width: 90%; margin-left: auto; margin-right: auto;" class="table-list">
                <tr>
                    <th class="td-th-list" width="30%" style="vertical-align: middle; background-color:#E4E6EF;">KUADRAN I</th>
                    <td class="td-th-list" style="text-align: left;">
                    <ul>
                        <?php foreach($kuadran_unsur->result() as $row) { ?>
                        <?php if($row->kuadran == 1) { ?>
                        <li><?php echo $row->nomor_unsur . '. ' . $row->nama_unsur_pelayanan ?></li>
                        <?php } ?>
                        <?php } ?>
                        </ul>
                    </td>
                </tr>

                <tr>
                    <th class="td-th-list" width="30%" style="vertical-align: middle; background-color:#E4E6EF;">KUADRAN II</th>
                    <td class="td-th-list" style="text-align: left;">
                    <ul>
                        <?php foreach($kuadran_unsur->result() as $row) { ?>
                        <?php if($row->kuadran == 2) { ?>
                        <li><?php echo $row->nomor_unsur . '. ' . $row->nama_unsur_pelayanan ?></li>
                        <?php } ?>
                        <?php } ?>
                        </ul>
                    </td>
                </tr>

                <tr>
                    <th class="td-th-list" width="30%" style="vertical-align: middle; background-color:#E4E6EF;">KUADRAN III</th>
                    <td class="td-th-list" style="text-align: left;">
                    <ul>
                        <?php foreach($kuadran_unsur->result() as $row) { ?>
                        <?php if($row->kuadran == 3) { ?>
                        <li><?php echo $row->nomor_unsur . '. ' . $row->nama_unsur_pelayanan ?></li>
                        <?php } ?>
                        <?php } ?>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <th class="td-th-list" width="30%" style="vertical-align: middle; background-color:#E4E6EF;">KUADRAN VI</th>
                    <td class="td-th-list" style="text-align: left;">
                    <ul>
                        <?php foreach($kuadran_unsur->result() as $row) { ?>
                        <?php if($row->kuadran == 4) { ?>
                        <li><?php echo $row->nomor_unsur . '. ' . $row->nama_unsur_pelayanan ?></li>
                        <?php } ?>
                        <?php } ?>
                        </ul>
                    </td>
                </tr>
            </table>

            <br>

            <table style="width: 90%; margin-left: auto; margin-right: auto;" class="table-list">
                <tr>
                    <th class="td-th-list" rowspan="2"></th>
                    <th class="td-th-list" colspan="<?php echo $persepsi->num_rows() ?>" style="background-color:#E4E6EF;">PERSEPSI</th>
                </tr>
                <tr >

                    <?php foreach ($persepsi->result() as $object) { ?>
                        <th class="td-th-list" style="background-color:#E4E6EF;"><b><?php echo $object->nomor ?></b></th>
                    <?php } ?>
                </tr>

                <tr>
                    <th class="td-th-list">Rata-Rata per Unsur</th>
                    <?php foreach ($nilai_per_unsur->result() as $nilai_per_unsur) { ?>
                        <td class="td-th-list"><?php echo ROUND($nilai_per_unsur->nilai_per_unsur, 2) ?></td>
                    <?php } ?>
                </tr>
                
                <tr>
                    <th class="td-th-list">Rata-Rata Akhir</th>
                    <th class="td-th-list" colspan="<?php echo $persepsi->num_rows() ?>"><?php echo ROUND($total_rata_unsur, 2) ?></th>
                </tr>
            </table>
        </div>
        <?php } ?>



        <div class="page-session">
            <table style="width: 100%;">
                <tr>
                    <td style="text-align: center; font-size:18px; font-weight: bold;">
                       ANALISA
                        <br>
                        <br>
                    </td>
                </tr>
            </table>

            <br>

            <table style="width: 100%; margin-left: auto; margin-right: auto;" class="table-list">
                <tr style="background-color:#E4E6EF;">
                    <th class="td-th-list">Unsur</th>
                    <th class="td-th-list">Saran dan Masukan</th>
                    <th class="td-th-list">Rencana Perbaikan</th>
                    <th class="td-th-list">Waktu</th>
                    <th class="td-th-list">Faktor Penyebab</th>
                    <th class="td-th-list">Kegiatan</th>
                    <th class="td-th-list">Penanggung Jawab</th>
                </tr>


                <?php foreach($analisa->result() as $value) { ?>
                <tr>
                    <td class="td-th-list"><?php echo $value->nomor_unsur ?></td>
                    <td class="td-th-list"><?php echo $value->saran_masukan?></td>
                    <td class="td-th-list"><?php echo $value->rencana_perbaikan ?></td>
                    <td class="td-th-list"><?php echo $value->waktu ?></td>
                    <td class="td-th-list"><?php echo $value->faktor_penyebab ?></td>
                    <td class="td-th-list"><?php echo $value->kegiatan ?></td>
                    <td class="td-th-list"><?php echo $value->penanggung_jawab?></td>
                </tr>
                <?php } ?>
                
            </table>

        </div>



        <!-- <div class="page-session">page2</div> -->
    </main>


</body>

</html> 