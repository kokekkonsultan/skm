<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Draf Kuesioner</title>
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

        header {
            position: fixed;
        }

        footer {
            position: fixed;
        }

        input[type=checkbox] {
            display: inline;
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
                                <img src="<?php echo base_url(); ?>assets/klien/foto_profile/<?php echo $user->foto_profile ?>" height="75" alt="">
                            <?php endif; ?>
                        </td>
                        <td class="text-right" style="border: 1px solid white;">
                            <div style="font-size:14px; font-weight:bold; padding-left: 8px;">
                                <?php echo $title_2 ?>
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
                rangka meningkatkan kepuasan masyarakat, Saudara dipercaya menjadi responden pada kegiatan survei
                ini.<br>
                Atas kesediaan Saudara kami sampaikan terima kasih dan penghargaan sedalam-dalamnya.</td>
        </tr>
    </table>



    <!-- PROFIL RESPONDEN -->
    <table style="width: 100%; table-layout: fixed">
        <tr>
            <td colspan="2" style="text-align:left; font-size: 11px; background-color: black; color:white; height:15px;">
                <b>DATA RESPONDEN</b> (Berikan tanda silang (x) sesuai jawaban Saudara pada kolom yang tersedia)
            </td>
        </tr>

        <tr style="font-size: 11px;">
            <td width=" 30%" style="height:15px;">Nama Lengkap</td>
            <td width="70%"></td>
        </tr>
    </table>


        <?php
        foreach ($profil_responden as $get_profil) {
            if($get_profil->total_kategori > 35) { ?>


        <?php } else { ?>


        <?php } ?>
         <table style="width: 100%; table-layout: fixed">
            <tr style="font-size: 11px;">
                <td width="30%" style="height:15px;" valign="top"> <?php echo $get_profil->nama_profil_responden ?></td>
                <td width="70%">

                    <?php if ($get_profil->jenis_isian == 1) { ?>
                        
                        <table style="border: 1px solid white; font-size: 11px; width: 100%;">
                        <?php
                            $kategori_profil = $this->db->query("SELECT * FROM kategori_profil_responden_$manage_survey->table_identity WHERE id_profil_responden = $get_profil->id LIMIT 34");

                            foreach ($kategori_profil->result() as $value) { ?>

                                <tr>
                                    <td width="4%" style="padding: 0px; border: 1px solid white;"><input type="checkbox"></td>
                                    <td style="padding: 0px; border: 1px solid white;"><?php echo $value->nama_kategori_profil_responden ?></td>
                                </tr>

                                <?php } ?>
                            </table>

                    <?php } ?>

                </td>
            </tr>
            </table>
        <?php
        } ?>

        <!-- <tr style="font-size: 11px;">
            <td width=" 30%" style="height:15px;">Waktu Isi Survei</td>
            <td width="70%">
                <input type="checkbox" checked="checked" /><span>yes</span>
            </td>
        </tr> -->
   








    <table style="width: 100%;">
        <tr>
            <td colspan="2" style="text-align:left; font-size: 11px; background-color: black; color:white;">
                <b>A. PENILAIAN TERHADAP UNSUR-UNSUR KEPUASAN MASYARAKAT</b>
            </td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:left; font-size: 11px; background-color: black; color:white;">
                Berikan tanda silang (x) sesuai jawaban Saudara dan berikan alasan jika jawaban Saudara negatif(Tidak
                atau Kurang Baik)
            </td>
        </tr>
    </table>


    <!-- PERTANYAAN UNSUR -->
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


    <table width="100%">
        <tr>
            <td rowspan="2" width="5%" style="text-align:center; font-size: 11px;">
                U1
            </td>
            <td width="32%" rowspan="2" style="text-align:left; font-size: 11px;">
                Tes
            </td>
            <td width="10%" style="background-color:#C7C6C1; text-align:center; font-size: 11px;">
              Tidak Baik
            </td>
            <td width="10%" style="background-color:#C7C6C1; text-align:center; font-size: 11px;">
              Kurang Baik
            </td>
            <td width="10%" style="background-color:#C7C6C1; text-align:center; font-size: 11px;">
            Baik
            </td>
            <td width="10%" style="background-color:#C7C6C1; text-align:center; font-size: 11px;">
                     Sangat Baik
            </td>
            <td width="23%" rowspan="2" style="text-align:left; font-size: 11px;"></td>
        </tr>

        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
    </table>


</body>

</html>