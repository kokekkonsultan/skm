<!--Jenis Pelayanan =================================================== -->
<?php if($manage_survey->is_layanan_survei != 0) { ?>
<table style="width: 100%;">
    <tr>
        <td width="3%"><b><?= $no_Bab2++ ?>.</b></td>
        <td><b>Jenis Pelayanan</b></td>
    </tr>
    <tr>
        <td></td>
        <td class="content-paragraph">Berikut merupakan jenis layanan yang diperoleh dari Survei Kepuasan Masyarakat
            pada <?= $manage_survey->organisasi ?></td>
    </tr>

    <tr>
        <td></td>
        <td>
            <table style="width: 90%; margin-left: auto; margin-right: auto;" class="table-list">
                <tr style="background-color:#E4E6EF;">
                    <th class="td-th-list">No</th>
                    <th class="td-th-list">Jenis Pelayanan</th>
                    <th class="td-th-list">Jumlah</th>
                    <th class="td-th-list">Persentase</th>
                </tr>


                <?php
                            $layanan = $this->db->query("SELECT *
                            FROM (SELECT *,
                            (SELECT nama_kategori_layanan FROM kategori_layanan_survei_$table_identity WHERE id = layanan_survei_$table_identity.id_kategori_layanan) AS nama_kategori_layanan,
                            (SELECT COUNT(*) FROM survey_$table_identity WHERE is_submit = 1) AS total_survei,
                            (SELECT COUNT(*) FROM responden_$table_identity JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id_responden WHERE layanan_survei_$table_identity.id = responden_$table_identity.id_layanan_survei && is_submit = 1) AS perolehan
                            FROM layanan_survei_$table_identity
                            WHERE is_active = 1
                            ) ls_$table_identity
                            ORDER BY urutan ASC");

                            $a = 1;
                            foreach ($layanan->result() as $row) {
                                $perolehan[] = $row->perolehan;
                                $total_perolehan = array_sum($perolehan);

                                $persentase[] = ($row->perolehan / $row->total_survei) * 100;
                                $total_persentase  = array_sum($persentase);
                                ?>
                <tr>

                    <td class="td-th-list"><?= $a++ ?></td>
                    <td class="td-th-list"><?= $row->nama_layanan ?></td>
                    <td class="td-th-list"><?= $row->perolehan ?></td>
                    <td class="td-th-list"><?= ROUND(($row->perolehan / $row->total_survei) * 100, 2) ?>%
                    </td>
                </tr>
                <?php } ?>


                <tr>
                    <th class="td-th-list" colspan="2">TOTAL</th>
                    <th class="td-th-list"><?= $total_perolehan ?></th>
                    <th class="td-th-list"><?= ROUND($total_persentase) ?>%</th>
                </tr>

            </table>
        </td>
    </tr>

</table>
<?php } ?>

