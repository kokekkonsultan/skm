<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Saran</title>
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


    <table style="width: 100%;" style="border: 1px solid white;">
        <tr style="border: 10px;">
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
                    <?php echo $title_1 ?><br>
                    <?php echo $title_2 ?>
                </div>
            </td>
        </tr>
    </table>
    <hr>
    <br>

    <div
        style="font-weight: bold; font-size:16px; width:100%; text-align:center; font-family:Arial, Helvetica, sans-serif">
        <ins>REKAP SARAN RESPONDEN</ins>
    </div>

    <br>
    <br>

    <table width="100%" style="font-family: Arial, Helvetica, sans-serif; font-size: 12px;">
        <tr style="text-align:center; background-color:#E4E6EF;">
            <td width="5%">No</td>
            <td width="20%">Responden</td>
            <td>Saran</td>
        </tr>

        <?php
        $no = 1;
        $re = 1;
        foreach ($saran->result() as $row) { ?>

        <tr style="font-size: 12px;">
            <td width=" 5%" style="text-align:center;"><?php echo $no++ ?></td>
            <td width="20%" style=" text-transform: capitalize;"><?php echo 'Responden ' . $re++ ?></td>
            <td style=" text-transform: capitalize;"><?php echo $row->saran ?></td>
            </td>
        </tr>
        <?php
        } ?>

    </table>

</body>

</html>