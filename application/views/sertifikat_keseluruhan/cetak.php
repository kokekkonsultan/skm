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
                <td style="font-size: 18px; font-weight:bold; text-transform: uppercase;">
                    <?php echo $user->company ?>
                </td>
            </tr>
            <tr>
                <td style="font-size: 14px; font-weight:bold; text-transform: uppercase;">
                    <?php echo $periode ?> TAHUN <?php echo $tahun ?>
                </td>
            </tr>
        </table>

        <br><br>

        <table style="width: 70%; margin-left: auto;
  margin-right: auto;">

            <tr style="font-size: 13px;">
                <th style="width:20%;">NILAI INDEKS</th>
                <th style="width:2%;"></th>
                <th style="width:33%;">SURVEI YANG MENJADI OBJECT</th>
            </tr>

            <tr style="padding-top: 20px; padding-bottom: 20px;">
                <th
                    style="border: 1px black solid; width:20%; text-align:center;  padding-right: 20px; padding-left: 20px; text-align:center;">

                    <div style="font-size: 60px;"><?php echo ROUND($induk->nilai_index,2) ?></div>
                    <div style="font-size: 13px;">MUTU PELAYANAN:
                        <?php
                        if ($induk->nilai_index <= 100 && $induk->nilai_index >= 88.31) {
                            echo 'SANGAT BAIK';
                        } elseif ($induk->nilai_index <= 88.40 && $induk->nilai_index >= 76.61) {
                            echo 'BAIK';
                        } elseif ($induk->nilai_index <= 76.60 && $induk->nilai_index >= 65) {
                            echo 'KURANG BAIK';
                        } elseif ($induk->nilai_index <= 64.99 && $induk->nilai_index >= 25) {
                            echo 'TIDAK BAIK';
                        } else {
                            echo 'NULL';
                        }
                        ?>
                    </div>
                </th>

                <th style="width:2%;"></th>

                <td style="border: 1px black solid; width:28%; font-size:11px; padding-right: 10px;">
                    <ul style="font-weight:bold;">
                    <?php foreach($this->db->query("SELECT * FROM manage_survey WHERE id IN ($induk->id_object_index)")->result() as $row) { ?>
                        <li><?php echo $row->survey_name . ' (' . $row->organisasi . ')' ?></li>
                    <?php } ?>


                    </ul>

                </td>
            </tr>
        </table>

        <br>
        <br>

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
                        <img src="https://image-charts.com/chart?chs=150x150&cht=qr&chl=https://skm.surveiku.com/&choe=UTF-8" height="80" alt="">
                    </div>
                </td>

            </tr>
        </table>

    </div>

</body>

</html>