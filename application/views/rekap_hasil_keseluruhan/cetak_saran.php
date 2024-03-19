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
    
    <div style="font-weight: bold; font-size:16px; width:100%; text-align:center; font-family:Arial, Helvetica, sans-serif">
        <ins>REKAP SARAN</ins>
    </div>

    <br>


    <table width="100%" style="font-family: Times New Roman; font-size: 12px;">
                        <tr style="text-align:center; background-color:#E4E6EF;">
                            <th width="4%">No</th>
                            <th>Saran</th>
                        </tr>

                        <?php
                        $no = 1;
                        foreach ($saran->result() as $value) { ?>
                                <tr>
                                    <td style="text-align:center;"><?php echo $no++ ?></td>
                                    <td><?php echo $value->saran ?></td>
                                </tr>
                        <?php } ?>
                    </table>

</body>

</html>