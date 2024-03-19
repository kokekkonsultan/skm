<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SERTIFIKAT E-SKM</title>
    <style>
    @page {
        margin: 0in;
    }

    body {
        /* font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif; */
        background-image: url("<?php echo base_url() ?>assets/files/sertifikat/<?php echo $model_sertifikat ?>");
        background-position: top left right bottom;
        background-repeat: no-repeat;
        background-size: 100%;
        width: 100%;
        height: 100%;
    }
    </style>
</head>

<body>
    <div style="text-align: center; font-family:Arial, Helvetica, sans-serif; color:#3b3b3b;">
        <br>

        <table style="width: 100%; text-align:center; margin-top:55px; margin-left:5px;">
            <tr style="font-weight: bold;">
                <td>
                    <?php if ($user->foto_profile == NULL) : ?>
                    <img src="<?php echo base_url() ?>assets/klien/foto_profile/200px.jpg" height="100" alt="" />
                    <?php else : ?>
                    <img src="<?php echo base_url(); ?>assets/klien/foto_profile/<?php echo $user->foto_profile ?>"
                        height="100" alt="" />
                    <?php endif; ?>
                </td>
            </tr>
        </table>

        <table style="width: 100%; text-align:center;">
            <tr>
                <td style="font-size: 23px; font-weight:bold;">
                    INDEKS KEPUASAN MASYARAKAT (IKM)
                </td>
            </tr>
            <tr>
                <td style="font-size: 12px; ">
                    No : <?php echo $manage_survey->nomor_sertifikat ?>
                </td>
            </tr>
            <tr>
                <td style="font-size: 18px; font-weight:bold; text-transform: uppercase;">
                    <?php echo $manage_survey->organisasi ?>
                </td>
            </tr>
            <!-- <tr>
                <td style="font-size: 15px; font-weight:bold; text-transform: uppercase;">
                    <?php echo $user->company ?>
                </td>
            </tr> -->
            <tr>
                <td style="font-size: 14px; font-weight:bold; text-transform: uppercase;">
                    <?php echo $periode ?> TAHUN <?php echo $manage_survey->survey_year ?>
                </td>
            </tr>
        </table>

        <br><br>

        <table style="width: 70%; margin-left: auto;
  margin-right: auto;">

            <tr style="font-size: 13px;">
                <th style="width:20%;">NILAI IKM KONVERSI</th>
                <th style="width:2%;"></th>
                <th style="width:33%;">NAMA LAYANAN</th>
            </tr>

            <tr style="padding-top: 20px; padding-bottom: 20px;">
                <th
                    style="border: 1px black solid; width:20%; text-align:center;  padding-right: 20px; padding-left: 20px; text-align:center;">

                    <div style="font-size: 60px;"><?php echo ROUND($ikm, 3) ?></div>
                    <div style="font-size: 15px;">Kinerja Unit Pelayanan :
                        <?php
                        foreach ($definisi_skala->result() as $obj) {
                            if ($ikm <= $obj->range_bawah && $ikm >= $obj->range_atas) {
                                echo  $obj->kategori;
                            }
                        }
                        if ($ikm <= 0) {
                            echo  'NULL';
                        }

                        // if ($ikm <= 100 && $ikm >= 88.31) {
                        //     echo 'SANGAT BAIK';
                        // } elseif ($ikm <= 88.40 && $ikm >= 76.61) {
                        //     echo 'BAIK';
                        // } elseif ($ikm <= 76.60 && $ikm >= 65) {
                        //     echo 'KURANG BAIK';
                        // } elseif ($ikm <= 64.99 && $ikm >= 25) {
                        //     echo 'TIDAK BAIK';
                        // } else {
                        //     echo 'NULL';
                        // }
                        ?>
                    </div>
                </th>

                <th style="width:2%;"></th>

                <td style="border: 1px black solid; width:28%; font-size:11px; padding-right: 10px;">
                    <ol>
                        <div class="mb-3"><b>JUMLAH RESPONDEN :</b> <?php echo $jumlah_kuisioner ?> Orang</div>

                        <?php foreach ($profil->result() as $row) { ?>

                        <div class="mb-3">
                            <div style="text-transform: uppercase;"><b><?php echo $row->nama_profil_responden ?></b>
                            </div>
                            <ul>
                                <?php
                                        $kategori_profil_responden = $this->db->query("SELECT *, (SELECT COUNT(*) FROM responden_$table_identity JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id_responden WHERE kategori_profil_responden_$table_identity.id = responden_$table_identity.$row->nama_alias && is_submit = 1) AS perolehan
                                    FROM kategori_profil_responden_$table_identity");

                                        foreach ($kategori_profil_responden->result() as $value) {
                                            ?>
                                <?php if ($value->id_profil_responden == $row->id) { ?>

                                <li><?php echo $value->nama_kategori_profil_responden ?> :
                                    <?php echo $value->perolehan ?> Orang</li>

                                <?php } ?>
                                <?php } ?>
                            </ul>
                        </div>

                        <?php } ?>


                        <div><b>PERIODE SURVEY :</b>
                            <?php echo date("d-m-Y", strtotime($manage_survey->survey_start)) ?> s/d
                            <?php echo date("d-m-Y", strtotime($manage_survey->survey_end)) ?></div>

                    </ol>

                </td>
            </tr>
        </table>

        <table style="width: 100%; font-size: 11px; text-align:center;">
            <tr style="padding-bottom: 10px;">
                <td>
                    <br>
                    TERIMAKASIH ATAS PENILAIAN YANG TELAH ANDA BERIKAN
                </td>
            </tr>
            <tr>
                <td>
                    MASUKAN ANDA SANGAT BERMAFAAT UNTUK KEMAJUAN UNIT KAMI AGAR TERUS MEMPERBAIKI
                </td>
            </tr>
            <tr>
                <td>
                    DAN MENINGKATKAN KUALITAS PELAYANAN BAGI MASYARAKAT
                </td>
            </tr>
        </table>

        <br>



        <table style="width: 100%; font-size: 12px; text-align:center; font-weight:bold;">
            <tr>
                <td width="80%">
                    <br><br>
                    Mengetahui,<br>
                    <?php echo $jabatan ?>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <?php echo $nama ?>
                </td>

                <td width="20%" style="padding-right: 120px;" rowspan="1">
                    <div>
                        <img src="<?php echo $qr_code ?>" height="80" alt="">

                    </div>
                </td>

                <!-- <td width="40%">
                    Pelaksana,<br>
                    PT.KOKEK Surabaya<br>
                    Direktur
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    Johny Yulfan, S.T., M.Si.
                </td> -->
            </tr>
        </table>

    </div>

</body>

</html>