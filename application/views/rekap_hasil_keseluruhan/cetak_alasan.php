<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Alasan</title>
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
    <!-- <div style="text-align:justify; width:75%; font-family:Arial, Helvetica, sans-serif;">

        <?php if ($user->foto_profile == NULL) : ?>
                    <img src="<?php echo base_url(); ?>assets/klien/foto_profile/200px.jpg" width="70" height="70" alt=""
                        style="float:left; margin:0px 8px 8px 10px;">
        <?php else : ?>
                    <img src="<?php echo base_url(); ?>assets/klien/foto_profile/<?php echo $user->foto_profile ?>" width="70"
                        height="70" alt="" style="float:left; margin:0px 8px 8px 10px;">
        <?php endif; ?>

        <div style="margin: auto; padding: 9px;">

            <div style="font-size: 13px; font-weight:bold;">SURVEI INDEKS KEBERDAYAAN KONSUMEN</div>
            <div style="font-size: 10px;"><?php echo $manage_survey->title_header_survey ?></div>
        </div>

    </div>
    <hr>
    <br> -->
    <div style="font-weight: bold; font-size:16px; width:100%; text-align:center; font-family:Arial, Helvetica, sans-serif">
        <ins>REKAP ALASAN JAWABAN</ins>
    </div>


    <ol style="font-size: 13px;">
        <?php foreach ($pertanyaan->result() as $row) { ?>

            <li><?php echo $row->isi_pertanyaan_unsur ?>
                <p>
                    <table width="100%" style="font-family: Times New Roman; font-size: 12px;">
                        <tr style="text-align:center; background-color:#E4E6EF;">
                            <th width="4%">No</th>
                            <th>Alasan Jawaban</th>
                        </tr>

                        <?php
                        $no = 1;
                        foreach ($alasan->result() as $value) { ?>
                            <?php if ($value->id_pertanyaan_unsur == $row->id) { ?>
                                <tr>
                                    <td style="text-align:center;"><?php echo $no++ ?></td>
                                    <td><?php echo $value->alasan_pilih_jawaban ?></td>
                                </tr>
                            <?php } ?>

                        <?php } ?>
                    </table>
                </p>
            </li>
            <br>
            <br>

        <?php } ?>
    </ol>

</body>

</html>