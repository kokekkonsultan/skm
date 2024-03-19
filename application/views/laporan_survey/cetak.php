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
        text-align: center;
        font-family: Arial, Helvetica, sans-serif;
    }

    table,
    th,
    td {
        font-size: 12.5px;
        padding: 3px;
        font-family: Arial, Helvetica, sans-serif;
        vertical-align: top;
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
    <!-- <div class="page-session">
        <div style="text-align:center;">
            <br>

            <?php if ($profiles->foto_profile != '' || $profiles->foto_profile != null) { ?>
                <img src="<?= base_url() . 'assets/klien/foto_profile/' . $profiles->foto_profile ?>" alt="Logo" width="250" class="center">
            <?php } else { ?>
                <img src="<?= base_url() ?>assets/klien/foto_profile/200px.jpg" alt="Logo" width="250" class="center">
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
            $month_start = $bulan[(int) date("m", strtotime($manage_survey->survey_start))];
            $month_end = $bulan[(int) date("m", strtotime($manage_survey->survey_end))];
            $year_start = date("Y", strtotime($manage_survey->survey_end));
            $year_end = date("Y", strtotime($manage_survey->survey_end));

            if ($month_start == $month_end) {
                $periode =  $month_end . ' ' . $year_end;
            } else {
                $periode =  $month_start . ' - ' . $month_end . ' ' . $year_end;
            }
            ?>



            <div style="font-size:17px; font-weight:bold;">
                PERIODE <?= $periode ?>
            </div>

        </div>
    </div> -->

    <header>
        <table style="width: 90%; margin-left: auto; margin-right: auto;" class="table-list">
            <tr>
                <td style="width: 10%;">
                    <?php if ($profiles->foto_profile != '' || $profiles->foto_profile != null) { ?>
                    <img src="<?= base_url() . 'assets/klien/foto_profile/' . $profiles->foto_profile ?>" alt="Logo"
                        width="70">
                    <?php } else { ?>
                    <img src="<?= base_url() . 'assets/klien/foto_profile/200px.jpg' ?>" alt="Logo" width="70">
                    <?php } ?>
                </td>
                <td>
                    <div style="color:#DE2226; font-size:15px;">
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
                    <b>Generate by <a target="_blank" href="https://surveiku.com/"
                            style="color:black;">SurveiKu.com</a></b>
                </td>
                <td align="right">
                    <p class="page"></p>
                </td>
            </tr>
        </table>
    </footer>


    <main>

        <!--============================================== BAB I =================================================== -->
        <!-- <div class="page-session">
            <table style="width: 100%;">
                <tr>
                    <td style="text-align: center; font-size:16px; font-weight: bold;">
                        BAB I
                        <br>
                        PENDAHULUAN
                        <br>
                        <br>
                    </td>
                </tr>
            </table>

            <?php $this->load->view('laporan_survey/bab1'); ?>
        </div> -->



        <!--============================================== BAB II =================================================== -->
        <div class="page-session">
            <table style="width: 100%;">
                <tr>
                    <td style="text-align: center; font-size:16px; font-weight: bold;">
                        BAB II
                        <br>
                        ANALISIS
                        <br>
                        <br>
                    </td>
                </tr>
            </table>

            <?php //$this->load->view('laporan_survey/bab2'); ?>


            <table style="width: 100%;" class="">
                <tr>
                    <td width="3%"><b><?= $no_Bab2++ ?>.</b></td>
                    <td><b>Profil Responden</b></td>
                </tr>
                <tr>
                    <td></td>
                    <td class="content-paragraph">Berikut merupakan karakteristik responden yang diperoleh dari Survei
                        Kepuasan Masyarakat pada <?= $manage_survey->organisasi ?></td>
                </tr>
            </table>



            <?php
            $b = 1;
            $c = 1;
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
            ?>
            <table style="width: 100%;" class="">
                <tr>
                    <td width="3%"></td>
                    <td width="4%"><b><?= ($no_Bab2 - 1) . '.' . ($prf + 1) ?></b></td>
                    <td><b><?= $prores->nama_profil_responden ?></b></td>
                </tr>

                <tr>
                    <td colspan="3" align="center">
                        <div style="outline: dashed 1px black;">
                            <img src="https://quickchart.io/chart?c=%7Btype%3A'horizontalBar'%2Cdata%3A%7Blabels%3A%5B'18%20-%2025%20th'%2C'26%20-%2035%20th'%2C'36%20-%2045%20th'%2C'46%20-%2055%20th'%2C'56%20-%2065%20th'%2C'%3E%2065%20th'%5D%2Cdatasets%3A%5B%7BbackgroundColor%3A'rgb(79%2C129%2C189)'%2Cstack%3A'Stack0'%2Cdata%3A%5B48.64%2C22.79%2C15.31%2C11.56%2C1.70%2C0.00%5D%2C%7D%2C%5D%2C%7D%2Coptions%3A%7Blayout%3A%7Bpadding%3A%7Bright%3A50%7D%7D%2Cscales%3A%7BxAxes%3A%5B%7Bticks%3A%7Bmin%3A0%2Cmax%3A100%7D%2C%7D%2C%5D%7D%2Ctitle%3A%7Bdisplay%3Atrue%2Ctext%3A'Umur'%7D%2Clegend%3A%7Bdisplay%3Afalse%7D%2Cresponsive%3Atrue%2Cplugins%3A%7BroundedBars%3Atrue%2Cdatalabels%3A%7Banchor%3A'end'%2Calign%3A'center'%2CbackgroundColor%3A'rgb(255%2C255%2C255)'%2CborderColor%3A'rgb(79%2C129%2C189)'%2CborderWidth%3A1%2CborderRadius%3A5%2Cformatter%3A(value)%3D%3E%7Breturn%20value%3B%7D%2C%7D%2C%7D%2C%7D%2C%7D" alt="" width="70%">
                        </div>
                        <br>
                        Gambar <?= $no_gambar++ ?>. Persentase Responden Berdasarkan <?= $prores->nama_profil_responden ?>
                    </td>
                </tr>
            </table>

            
            <?php } ?>

        </div>






    </main>


</body>

</html>