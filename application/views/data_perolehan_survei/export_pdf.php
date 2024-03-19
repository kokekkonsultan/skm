<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Record All <?php echo $manage_survey->survey_name ?></title>
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


<?php foreach ($responden->result() as $row) { ?>

    <body>
        <div style="font-weight: bold; text-transform:uppercase; font-family:Arial, Helvetica, sans-serif; font-size:12px;">

            <?php if ($user->foto_profile == NULL) : ?>
                <img src="<?php echo base_url() ?>assets/klien/foto_profile/200px.jpg" width="50" height="50" alt="" style="float:left; margin:0px 8px 8px 10px;">
            <?php else : ?>
                <img src="<?php echo base_url(); ?>assets/klien/foto_profile/<?php echo $user->foto_profile ?>" width="50" height="50" alt="" style="float:left; margin:0px 8px 8px 10px;">
            <?php endif; ?>

            <div style="margin: auto; padding: 1px;"> SURVEI KEPUASAN MASYARAKAT<br>
                <?php echo $manage_survey->organisasi ?>
                <br><?php echo $user->company ?>
            </div>
            <br>
            <hr>
        </div>
        <br>
        <div style="text-align:center; font-size: 12px; font-family:Arial, Helvetica, sans-serif;">Dalam rangka meningkatkan
            kepuasan masyarakat, Saudara dipercaya
            menjadi responden pada kegiatan
            survei ini.<br>
            Atas kesediaan Saudara kami sampaikan terima kasih dan penghargaan sedalam-dalamnya.</div>

        <br>

        <table style="width: 100%;">
            <tr>
                <td colspan="2" style="text-align:left; font-size: 11px; background-color: black; color:white; height:15px;">
                    DATA RESPONDEN
                </td>
            </tr>
            <tr style="font-size: 11px;">
                <td width="30%" style="height:15px;">Nama</td>
                <td width="70%" style="height:15px;"><?php echo $row->nama_lengkap ?></td>
            </tr>
            <tr style="font-size: 11px;">
                <td width=" 30%" style="height:15px;">No Hp.</td>
                <td width="70%" style="height:15px;"><?php echo $row->handphone ?></td>
            </tr>
            <tr style="font-size: 11px;">
                <td width=" 30%" style="height:15px;">Alamat</td>
                <td width="70%" style="height:15px;"><?php echo $row->alamat_responden ?></td>
            </tr>

            <?php if ($row->jenis_kelamin_responden != NULL) : ?>
                <tr style="font-size: 11px;">
                    <td width="30%" style="height:15px;">Jenis Kelamin</td>
                    <td width="70%" style="height:15px;"><?php echo $row->jenis_kelamin_responden ?></td>
                </tr>
            <?php endif; ?>

            <?php if ($row->umur_responden != NULL) : ?>
                <tr style="font-size: 11px;">
                    <td width=" 30%" style="height:15px;">Umur</td>
                    <td width="70%" style="height:15px;"><?php echo $row->umur_responden ?></td>
                </tr>
            <?php endif; ?>

            <?php if ($row->nama_pendidikan_terakhir_responden != NULL) : ?>
                <tr style="font-size: 11px;">
                    <td width=" 30%" style="height:15px;">Pendidikan Terakhir</td>
                    <td width="70%" style="height:15px;"><?php echo $row->nama_pendidikan_terakhir_responden ?></td>
                </tr>
            <?php endif; ?>

            <?php if ($row->nama_pekerjaan_utama_responden != NULL) : ?>
                <tr style="font-size: 11px;">
                    <td width=" 30%" style="height:15px;">Pekerjaan Utama</td>
                    <td width="70%" style="height:15px;"><?php echo $row->nama_pekerjaan_utama_responden ?></td>
                </tr>
            <?php endif; ?>

            <?php if ($row->pekerjaan_lainnya != NULL) : ?>
                <tr style="font-size: 11px;">
                    <td width=" 30%" style="height:15px;">Pekerjaan Lainnya</td>
                    <td width="70%"><?php echo $row->pekerjaan_lainnya ?></td>
                </tr>
            <?php endif; ?>

            <?php if ($row->nama_pembiayaan_responden != NULL) : ?>
                <tr style="font-size: 11px;">
                    <td width=" 30%" style="height:15px;">Pembiayaan</td>
                    <td width="70%"><?php echo $row->nama_pembiayaan_responden ?></td>
                </tr>
            <?php endif; ?>

            <?php if ($row->nama_status_responden != NULL) : ?>
                <tr style="font-size: 11px;">
                    <td width=" 30%" style="height:15px;">Status Responden</td>
                    <td width="70%"><?php echo $row->nama_status_responden ?></td>
                </tr>
            <?php endif; ?>

            <?php if ($row->banyak_kunjungan != NULL) : ?>
                <tr style="font-size: 11px;">
                    <td width=" 30%" style="height:15px;">Jumlah Kunjungan</td>
                    <td width="70%"><?php echo $row->banyak_kunjungan ?></td>
                </tr>
            <?php endif; ?>

            <?php if ($row->nama_pelayanan != NULL) : ?>
                <tr style="font-size: 11px;">
                    <td width=" 30%" style="height:15px;">Jenis Pelayanan</td>
                    <td width="70%"><?php echo $row->nama_pelayanan ?> Tahun</td>
                </tr>
            <?php endif; ?>

            <?php if ($row->lama_bekerja != 0) : ?>
                <tr style="font-size: 11px;">
                    <td width=" 30%" style="height:15px;">Lama Bekerja</td>
                    <td width="70%"><?php echo $row->lama_bekerja ?> Tahun</td>
                </tr>
            <?php endif; ?>


            <tr style="font-size: 11px;">
                <td width=" 30%" style="height:15px;">Waktu Isi Survei</td>
                <td width="70%"> <?php echo date("d-m-Y", strtotime($row->created_at)) ?></td>

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
            foreach ($pertanyaan_unsur->result() as $value) {
            ?>
                <?php if ($row->id_responden == $value->id_responden) { ?>

                    <tr height="5%">
                        <td rowspan="2">
                            <?php echo $value->nomor_unsur ?>
                        </td>
                        <td width="32%" rowspan="2" style="text-align:left;">
                            <?php echo $value->isi_pertanyaan_unsur ?>
                        </td>

                        <td width="10%" style="background-color:#C7C6C1"><?php echo $value->pilihan_1 ?></td>
                        <td width="10%" style="background-color:#C7C6C1"><?php echo $value->pilihan_2 ?></td>
                        <td width="10%" style="background-color:#C7C6C1"><?php echo $value->pilihan_3 ?></td>
                        <td width="10%" style="background-color:#C7C6C1"><?php echo $value->pilihan_4 ?></td>

                        <td width="23%" rowspan="2"><?php echo $value->alasan_pilih_jawaban ?></td>
                    </tr>

                    <tr height="95%">
                        <?php if ($value->skor_jawaban == '1') : ?>

                            <th>X</th>
                            <th></th>
                            <th></th>
                            <th></th>

                        <?php elseif ($value->skor_jawaban == '2') : ?>
                            <th></th>
                            <th>X</th>
                            <th></th>
                            <th></th>

                        <?php elseif ($value->skor_jawaban == '3') : ?>
                            <th></th>
                            <th></th>
                            <th>X</th>
                            <th></th>

                        <?php elseif ($value->skor_jawaban == '4') : ?>
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
                <?php } ?>

            <?php
            } ?>

            <tr>
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
                <?php if ($row->id_responden == $get->id_responden) { ?>

                    <tr style="font-size: 11px;">
                        <td width=5%> <?php echo $no++ ?></td>
                        <td style="height:15px;"> <?php echo $get->isi_pertanyaan ?></td>
                        <td colspan="5"><?php echo $get->isi_jawaban_kualitatif ?></td>
                    </tr>

                <?php } ?>
            <?php } ?>


            <tr>
                <td colspan="7" style="text-align:left;">
                    <b>SARAN :</b>
                    <br />
                    <?php if ($row->saran != 'NULL') : ?>
                        <?php echo $row->saran ?>
                    <?php endif; ?>
                    <br />
                    <br />
                    <br />
                </td>
            <tr>
                <td colspan="7" style="text-align:center;">
                    Terima kasih atas kesediaan Saudara mengisi kuesioner tersebut di atas.<br>
                    Saran dan penilaian Saudara memberikan konstribusi yang sangat berarti bagi peningkatan kepuasan
                    masyarakat.
                </td>
            </tr>
        </table>

    </body>
<?php } ?>

</html>