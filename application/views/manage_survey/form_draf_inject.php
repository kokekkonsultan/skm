<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak</title>
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
        padding: 10px;
        font-size: 12px;
        font-family: 'Times New Roman', Times, serif;
    }
    </style>
</head>

<body>

    <div style="overflow-x:auto;">
        <table class="table table-bordered table-hover" cellspacing="0" width="100%">
            <thead>
                <tr style="background-color: yellow; text-align:center; font-weight:bold;">

                    <?php if ($surveyor->num_rows() > 0) { ?>
                    <th colspan="2">Surveyor</th>
                    <?php } ?>

                    <?php foreach ($profil_responden->result() as $row) { ?>
                    <th colspan="2"><?php echo $row->nama_profil_responden ?></th>
                    <?php } ?>
                </tr>
                <tr style="text-align:center; font-weight:bold; background-color: #e8e8e8;">

                     <?php if ($surveyor->num_rows() > 0) { ?>
                    <td>Id</td>
                    <td>Nama Surveyor</td>
                    <?php } ?>


                    <?php foreach ($profil_responden->result() as $row) { ?>
                    <td>Id</td>
                    <td>Kategori</td>
                    <?php } ?>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <?php if ($surveyor->num_rows() > 0) { ?>
                    <td>
                        <table>
                            <?php foreach ($surveyor->result() as $obj) { ?>
                            <tr style="text-align:center;">
                                <td><?php echo $obj->id_surveyor ?></td>
                            </tr>
                            <?php } ?>
                        </table>
                    </td>

                    <td>
                        <table>
                            <?php foreach ($surveyor->result() as $obj) { ?>
                            <tr>
                                <td><?php echo $obj->kode_surveyor . ' - ' . $obj->nama_depan . ' ' . $obj->nama_belakang ?></td>
                            </tr>
                            <?php } ?>
                        </table>
                    </td>
                    <?php } ?>




                    <?php foreach ($profil_responden->result() as $row) { ?>
                    <td>
                        <table>
                            <?php foreach ($kategori_profil_responden->result() as $value) { ?>
                            <?php if ($row->id == $value->id_profil_responden) { ?>
                            <tr style="text-align:center;">
                                <td><?php echo $value->id ?></td>
                            </tr>
                            <?php } ?>
                            <?php  } ?>
                        </table>
                    </td>
                    <td>
                        <table>
                            <?php foreach ($kategori_profil_responden->result() as $value) { ?>
                            <?php if ($row->id == $value->id_profil_responden) { ?>
                            <tr>
                                <td><?php echo $value->nama_kategori_profil_responden ?></td>
                            </tr>
                            <?php } ?>
                            <?php  } ?>
                        </table>
                    </td>
                    <?php } ?>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>