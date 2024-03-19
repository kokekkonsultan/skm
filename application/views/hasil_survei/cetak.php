<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Record Survey - <?php echo $responden->nama_lengkap ?></title>
    <style>
    table {
        border-collapse: collapse;
        font-family: sans-serif;
        font-size: .8rem;
    }

    table,
    th,
    td {
        border: 1px solid black;
    }

    th,
    td {
        padding: 3px;
    }
    </style>
</head>

<body>

    <?php
    $title_header = unserialize($manage_survey->title_header_survey);
    $title_1 = $title_header[0];
    $title_2 = $title_header[1];
    ?>

    <table style="width: 100%;">
        <tr>
            <td width="50%">
                <table style="border: 1px solid white;">
                    <tr style="border: 1px solid white;">
                        <td width="10%" style="border: 1px solid white; padding-left: 8px;">
                            <?php if ($user->foto_profile == NULL) : ?>
                            <img src="<?php echo base_url() ?>assets/klien/foto_profile/200px.jpg" height="75" alt="">
                            <?php else : ?>
                            <img src="<?php echo base_url(); ?>assets/klien/foto_profile/<?php echo $user->foto_profile ?>"
                                height="75" alt="">
                            <?php endif; ?>
                        </td>
                        <td class="text-right" style="border: 1px solid white;">
                            <div style="font-size:14px; font-weight:bold; padding-left: 8px;">
                                <?php echo  $title_2 ?>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
            <td width="50%">
                <div style="font-weight:bold; margin: auto; padding: 10px;  font-size:15px; text-align: center;">
                    <?php echo $title_1 ?></div>
            </td>
        </tr>
    </table>

    <table style="width: 100%;">
        <tr>
            <td style="text-align:center; font-size: 11px; font-family:Arial, Helvetica, sans-serif; height:35px;">Dalam
                rangka
                meningkatkan
                kepuasan masyarakat, Saudara dipercaya
                menjadi responden pada kegiatan
                survei ini.<br>
                Atas kesediaan Saudara kami sampaikan terima kasih dan penghargaan sedalam-dalamnya.</td>
        </tr>
    </table>

    <table style="width: 100%;">
        <tr>
            <td colspan="2"
                style="text-align:left; font-size: 11px; background-color: black; color:white; height:15px;">
                DATA RESPONDEN
            </td>
        </tr>
        <tr style="font-size: 11px;">
            <td width="30%" style="height:15px;">Nama</td>
            <td width="70%" style="height:15px;"><?php echo $responden->nama_lengkap ?></td>
        </tr>

        <!-- MEMANGGIL DATA PROFIL RESPONDEN YANG DIBUAT -->
        <?php foreach ($profil_responden as $value) {
            $isi_profil = $value->nama_alias;
        ?>
        <tr style="font-size: 11px;">
            <td width=" 30%" style="height:15px;"><?php echo $value->nama_profil_responden ?></td>
            <td width="70%" style="height:15px;"><?php echo $responden->$isi_profil ?></td>
        </tr>

        <?php } ?>

        <tr style="font-size: 11px;">
            <td width=" 30%" style="height:15px;">Waktu Isi Survei</td>
            <td width="70%"> <?php echo date("d-m-Y", strtotime($responden->waktu_isi)) ?></td>
        </tr>
    </table>

    <table style="width: 100%;">
        <tr>
            <td colspan="2" style="text-align:left; font-size: 11px; background-color: black; color:white;">
                PENILAIAN TERHADAP UNSUR-UNSUR KEPUASAN MASYARAKAT
            </td>
        </tr>
    </table>

    <table width="100%" style="font-size: 11px; text-align:center; background-color:#C7C6C1">
        <tr>
            <td rowspan="2" width="5%">No</td>
            <td rowspan="2" width="32%">PERTANYAAN</td>
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
    </table>

    <table width="100%" style="font-size: 11px; text-align:center;">

        <?php
        foreach ($pertanyaan_unsur->result() as $row) {
        ?>
        <tr height="5%">
            <td rowspan="2">
                <?php echo $row->nomor_unsur ?>
            </td>
            <td width="32%" rowspan="2" style="text-align:left;">
                <?php echo $row->isi_pertanyaan_unsur ?>
            </td>

            <td width="10%" style="background-color:#C7C6C1"><?php echo $row->pilihan_1 ?></td>
            <td width="10%" style="background-color:#C7C6C1"><?php echo $row->pilihan_2 ?></td>
            <td width="10%" style="background-color:#C7C6C1"><?php echo $row->pilihan_3 ?></td>
            <td width="10%" style="background-color:#C7C6C1"><?php echo $row->pilihan_4 ?></td>

            <td width="23%" rowspan="2"><?php echo $row->alasan_jawaban ?></td>
        </tr>

        <tr height="95%">
            <?php if ($row->skor_jawaban == '1') : ?>

            <th>X</th>
            <th></th>
            <th></th>
            <th></th>

            <?php elseif ($row->skor_jawaban == '2') : ?>
            <th></th>
            <th>X</th>
            <th></th>
            <th></th>

            <?php elseif ($row->skor_jawaban == '3') : ?>
            <th></th>
            <th></th>
            <th>X</th>
            <th></th>

            <?php elseif ($row->skor_jawaban == '4') : ?>
            <th></th>
            <th></th>
            <th></th>
            <th>X</th>

            <?php else : ?>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <?php endif; ?>
        </tr>

        <?php
        } ?>

        <!-- <tr>
            <td colspan="7">.</td>
        </tr>

        <tr>
            <td colspan="7" style="text-align:left; font-size: 11px; background-color: black; color:white;">
                PERTANYAAN KUALITATIF
            </td>
        </tr>
        <tr style="font-size: 11px; text-align:center; background-color: #C7C6C1;">
            <td>No</td>
            <td style="height:15px;">PERTANYAAN</td>
            <td colspan="5">JAWABAN</td>
        </tr>

        <?php
        $no = 1;
        foreach ($jawaban_kualitatif->result() as $get) {
        ?>

            <tr style="font-size: 11px;">
                <td width=5%> <?php echo $no++ ?></td>
                <td style="height:15px;"> <?php echo $get->isi_pertanyaan ?></td>
                <td colspan="5"><?php echo $get->isi_jawaban_kualitatif ?></td>
            </tr>
        <?php } ?> -->

        <?php if ($manage_survey->is_saran == 1) { ?>
        <tr>
            <td colspan="7" style="text-align:left;">
                <b>SARAN :</b>
                <br />
                <?php echo $responden->saran ?>
                <br />
                <br />
                <br />
            </td>
        </tr>
        <?php } ?>

        <tr>
            <td colspan="7" style="text-align:center;">
                Terima kasih atas kesediaan Saudara mengisi kuesioner tersebut di atas.<br>Saran dan penilaian Saudara memberikan konstribusi yang sangat berarti bagi peningkatan kepuasan
                masyarakat.
            </td>
        </tr>
    </table>
</body>

</html>