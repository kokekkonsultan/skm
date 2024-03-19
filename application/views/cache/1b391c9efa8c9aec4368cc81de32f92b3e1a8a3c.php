

<?php
$ci = get_instance();
?>

<?php $__env->startSection('style'); ?>

<link href="<?php echo e(TEMPLATE_BACKEND_PATH); ?>plugins/custom/datatables/datatables.bundle.css" rel="stylesheet"type="text/css" />



<style type="text/css">
[pointer-events="bounding-box"] {
    display: none
}

.dataTables_filter {
    display: none
}

.dataTables_length {
    display: none
}
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="container-fluid">
    <?php echo $__env->make("include_backend/partials_no_aside/_inc_menu_repository", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="row mt-5">
        <div class="col-md-3">
            <?php echo $__env->make('manage_survey/menu_data_survey', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <div class="col-md-9">


            <!-- START PIE CHART -->
            <?php $__currentLoopData = $unsur_pelayanan->result(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="card card-custom card-sticky mb-5" data-aos="fade-down">
                <div class="card-body">
                    <div id="pie_<?php echo e($row->id_unsur_pelayanan); ?>" class="d-flex justify-content-center"></div>

                    <?php
                    $cek_sub = $ci->db->get_where("unsur_pelayanan_$table_identity", ['id_parent' =>
                    $row->id_unsur_pelayanan]);
                    ?>

                    <?php if($cek_sub->num_rows() == 0): ?>

                    <!-- UNSUR YANG TIDAK MEMILIKI TURUNAN -->
                    <table class="table table-bordered example mt-5" style="width:100%">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Kategori</th>
                                <th class="text-center">Jumlah</th>
                                <th class="text-center">Persentase</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $t_perolehan = 0;
                            $t_persentase = 0;
                            ?>
                            <?php $__currentLoopData = $get_pilihan_jawaban->result(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($value->id_unsur_pelayanan == $row->id_unsur_pelayanan): ?>
                            <tr>
                                <td class="text-center"><?php echo e($no++); ?></td>
                                <td><?php echo e($value->nama_tingkat_kepentingan); ?></td>
                                <td class="text-center"><?php echo e($value->perolehan); ?></td>
                                <td class="text-center">
                                    <?php echo e(ROUND(($value->perolehan/$value->jumlah_pengisi) * 100, 2)); ?> %
                                </td>
                            </tr>
                            <?php
                            $t_perolehan += $value->perolehan;
                            $t_persentase += ($value->perolehan/$value->jumlah_pengisi) * 100;
                            ?>
                            <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                        <tfoot class="bg-light">
                            <tr>
                                <th class="text-center" colspan="2">TOTAL</th>
                                <th class="text-center"><?php echo e($t_perolehan); ?></th>
                                <th class="text-center"><?php echo e(ROUND($t_persentase)); ?> %</th>
                            </tr>
                        </tfoot>

                    </table>

                    <?php else: ?>

                    <!-- UNSUR YANG MEMILIKI TURUNAN -->
                    <div class="row mb-5">
                        <div class="col-xl-9 font-weight-bold font-size-h6">
                            Kesimpulan <?php echo e($row->nama_unsur_pelayanan); ?>

                        </div>
                        <div class="col-xl-3 text-right">
                            <a class="btn btn-primary btn-sm font-weight-bold shadow" data-toggle="collapse"
                                href="#collapseExample<?php echo e($row->id); ?>"><i class="fa fa-info-circle"></i> Lihat Detail
                                Sub
                                Unsur
                            </a>
                        </div>
                    </div>

                    <div class="collapse" id="collapseExample<?php echo e($row->id_unsur_pelayanan); ?>">
                        <?php $__currentLoopData = $cek_sub->result(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $get): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="card card-body mb-5">
                            <h5><?php echo e($get->nomor_unsur . '. ' . $get->nama_unsur_pelayanan); ?></h5>

                            <table class="table table-bordered example" style="width:100%">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Kategori</th>
                                        <th class="text-center">Jumlah</th>
                                        <th class="text-center">Persentase</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    $t_perolehan_turunan = 0;
                                    $t_persentase_turunan = 0;
                                    ?>
                                    <?php $__currentLoopData = $get_pilihan_jawaban->result(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($value->id_unsur_pelayanan == $get->id): ?>
                                    <tr>
                                        <td class="text-center"><?php echo e($no++); ?></td>
                                        <td><?php echo e($value->nama_tingkat_kepentingan); ?></td>
                                        <td class="text-center"><?php echo e($value->perolehan); ?></td>
                                        <td class="text-center">
                                            <?php echo e(ROUND(($value->perolehan/$value->jumlah_pengisi) * 100, 2)); ?> %
                                        </td>
                                    </tr>
                                    <?php
                                    $t_perolehan_turunan += $value->perolehan;
                                    $t_persentase_turunan += ($value->perolehan/$value->jumlah_pengisi) * 100;
                                    ?>
                                    <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                                <tfoot class="bg-light">
                                    <tr>
                                        <th class="text-center" colspan="2">TOTAL</th>
                                        <th class="text-center"><?php echo e($t_perolehan_turunan); ?></th>
                                        <th class="text-center"><?php echo e(ROUND($t_persentase_turunan)); ?> %</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <br>
                        <hr>
                        <hr>
                        <br>
                    </div>



                    <!-- RATA RATA UNSUR TURUNAN -->
                    <table class="table table-bordered example" style="width:100%">
                        <thead class="text-center bg-light">
                            <tr>
                                <th width="30%" rowspan="2">Unsur</th>
                                <th colspan="<?php echo e($manage_survey->skala_likert == 5 ? 5 : 4); ?>">Persentase
                                    Persepsi Responden</th>
                                <th width="10%" rowspan="2">Indeks</th>
                            </tr>
                            <tr>
                                <?php
                                $pilihan_jawaban_turunan = $ci->db->query("SELECT *
                                FROM nilai_tingkat_kepentingan_$table_identity
                                JOIN pertanyaan_unsur_pelayanan_$table_identity ON
                                nilai_tingkat_kepentingan_$table_identity.id_pertanyaan_unsur_pelayanan =
                                pertanyaan_unsur_pelayanan_$table_identity.id
                                JOIN unsur_pelayanan_$table_identity ON
                                pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan =
                                unsur_pelayanan_$table_identity.id
                                WHERE id_parent = $row->id_unsur_pelayanan
                                GROUP BY nomor_tingkat_kepentingan")->result_array();
                                ?>

                                <td><?php echo e($pilihan_jawaban_turunan[0]['nama_tingkat_kepentingan']); ?></td>
                                <td><?php echo e($pilihan_jawaban_turunan[1]['nama_tingkat_kepentingan']); ?></td>
                                <td><?php echo e($pilihan_jawaban_turunan[2]['nama_tingkat_kepentingan']); ?></td>
                                <td><?php echo e($pilihan_jawaban_turunan[3]['nama_tingkat_kepentingan']); ?></td>

                                <?php if($manage_survey->skala_likert == 5): ?>
                                <td><?php echo e($pilihan_jawaban_turunan[4]['nama_tingkat_kepentingan']); ?></td>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 0;
                            $jum_persentase_1 = 0;
                            $jum_persentase_2 = 0;
                            $jum_persentase_3 = 0;
                            $jum_persentase_4 = 0;
                            $jum_persentase_5 = 0;
                            $jum_indeks = 0;
                            ?>
                            <?php $__currentLoopData = $rekap_turunan_unsur->result(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $obj): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($obj->id_parent == $row->id_unsur_pelayanan): ?>
                            <tr>
                                <td><?php echo e($obj->nomor_unsur . '. ' . $obj->nama_unsur_pelayanan); ?></td>
                                <td class=" text-center"><?php echo e(ROUND(($obj->perolehan_1/$obj->jumlah_pengisi) * 100, 2)); ?>

                                    %
                                </td>
                                <td class="text-center"><?php echo e(ROUND(($obj->perolehan_2/$obj->jumlah_pengisi) * 100, 2)); ?>

                                    %
                                </td>
                                <td class="text-center"><?php echo e(ROUND(($obj->perolehan_3/$obj->jumlah_pengisi) * 100, 2)); ?>

                                    %
                                </td>
                                <td class="text-center"><?php echo e(ROUND(($obj->perolehan_4/$obj->jumlah_pengisi) * 100, 2)); ?>

                                    %
                                </td>

                                <?php if($manage_survey->skala_likert == 5): ?>
                                <td class="text-center"><?php echo e(ROUND(($obj->perolehan_5/$obj->jumlah_pengisi) * 100, 2)); ?>

                                    %
                                </td>
                                <?php endif; ?>

                                <td class="text-center"><?php echo e(ROUND($obj->rata_rata, 2)); ?></td>
                            </tr>

                            <?php
                            $jum_persentase_1 += ($obj->perolehan_1/$obj->jumlah_pengisi) * 100;
                            $jum_persentase_2 += ($obj->perolehan_2/$obj->jumlah_pengisi) * 100;
                            $jum_persentase_3 += ($obj->perolehan_3/$obj->jumlah_pengisi) * 100;
                            $jum_persentase_4 += ($obj->perolehan_4/$obj->jumlah_pengisi) * 100;
                            $jum_persentase_5 += ($obj->perolehan_5/$obj->jumlah_pengisi) * 100;
                            $jum_indeks += $obj->rata_rata;
                            $i++
                            ?>
                            <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>

                        <tfoot class="bg-light text-center">
                            <tr>
                                <th>RATA - RATA</th>
                                <th><?php echo e(ROUND($jum_persentase_1/$i,2)); ?> %</th>
                                <th><?php echo e(ROUND($jum_persentase_2/$i,2)); ?> %</th>
                                <th><?php echo e(ROUND($jum_persentase_3/$i,2)); ?> %</th>
                                <th><?php echo e(ROUND($jum_persentase_4/$i,2)); ?> %</th>

                                <?php if($manage_survey->skala_likert == 5): ?>
                                <th><?php echo e(ROUND($jum_persentase_5/$i,2)); ?> %</th>
                                <?php endif; ?>

                                <th><?php echo e(ROUND($jum_indeks/$i,2)); ?></th>
                            </tr>
                        </tfoot>
                    </table>


                    <?php endif; ?>

                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        </div>
    </div>
</div>


<?php $__env->stopSection(); ?>

<?php $__env->startSection('javascript'); ?>
<script src="<?php echo e(TEMPLATE_BACKEND_PATH); ?>plugins/custom/datatables/datatables.bundle.js"></script>
<script src="<?php echo e(base_url()); ?>assets/themes/metronic/assets/js/pages/features/charts/apexcharts.js"></script>
<script src="https://cdn.fusioncharts.com/fusioncharts/latest/fusioncharts.js"></script>
<script src="<?php echo e(base_url()); ?>assets/vendor/fusioncharts-suite-xt/js/themes/fusioncharts.theme.accessibility.js">
</script>
<script src="<?php echo e(base_url()); ?>assets/vendor/fusioncharts-suite-xt/js/themes/fusioncharts.theme.candy.js"></script>
<script src="<?php echo e(base_url()); ?>assets/vendor/fusioncharts-suite-xt/js/themes/fusioncharts.theme.carbon.js"></script>
<script src="<?php echo e(base_url()); ?>assets/vendor/fusioncharts-suite-xt/js/themes/fusioncharts.theme.fint.js"></script>
<script src="<?php echo e(base_url()); ?>assets/vendor/fusioncharts-suite-xt/js/themes/fusioncharts.theme.fusion.js"></script>
<script src="<?php echo e(base_url()); ?>assets/vendor/fusioncharts-suite-xt/js/themes/fusioncharts.theme.gammel.js"></script>
<script src="<?php echo e(base_url()); ?>assets/vendor/fusioncharts-suite-xt/js/themes/fusioncharts.theme.ocean.js"></script>
<script src="<?php echo e(base_url()); ?>assets/vendor/fusioncharts-suite-xt/js/themes/fusioncharts.theme.umber.js"></script>
<script src="<?php echo e(base_url()); ?>assets/vendor/fusioncharts-suite-xt/js/themes/fusioncharts.theme.zune.js"></script>
<script>
$(document).ready(function() {
    $('.example').DataTable();
});
</script>


<script>
FusionCharts.ready(function() {
    var myChart = new FusionCharts({
        type: "bar3d",
        renderAt: "chart",
        "width": "100%",
        "height": "90%",
        dataFormat: "json",
        dataSource: {
            chart: {
                caption: "Indeks Keseluruhan",
                // yaxisname: "Annual Income",
                showvalues: "1",
                "decimals": "3",
                theme: "umber",
                "bgColor": "#ffffff",
            },
            data: [<?php echo $get_data_chart ?>]
        }
    });
    myChart.render();
});
</script>



<?php $__currentLoopData = $unsur_pelayanan->result(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<?php
$cek_sub = $ci->db->get_where("unsur_pelayanan_$table_identity", ['id_parent' =>
$row->id_unsur_pelayanan]);
?>

<?php if($cek_sub->num_rows() == 0): ?>
<script>
FusionCharts.ready(function() {
    var myChart = new FusionCharts({
        "type": "pie3d",
        "renderAt": "pie_<?php echo e($row->id_unsur_pelayanan); ?>",
        "width": "100%",
        "height": "350",
        "dataFormat": "json",
        dataSource: {
            "chart": {
                "caption": "H<?php echo $row->nomor_harapan . '. ' . $row->nama_unsur_pelayanan ?>",
                subcaption: "<?php echo strip_tags($row->isi_pertanyaan_unsur) ?>",
                "enableSmartLabels": "1",
                "startingAngle": "0",
                "showPercentValues": "1",
                "decimals": "2",
                "useDataPlotColorForLabels": "1",
                "theme": "umber",
                "bgColor": "#ffffff"
            },
            "data": [
                <?php foreach ($get_pilihan_jawaban->result() as $value) {
                        if ($value->id_unsur_pelayanan == $row->id_unsur_pelayanan) { ?> {
                    label: "<?php echo $value->nama_tingkat_kepentingan . ' = ' . $value->perolehan ?>",
                    value: "<?php echo $value->perolehan ?>"
                },
                <?php }
                    } ?>
            ]
        }
    });
    myChart.render();
});
</script>
<?php else: ?>

<?php
$unsur_turunan = $ci->db->query("SELECT nama_tingkat_kepentingan, nomor_tingkat_kepentingan,
(SELECT COUNT(skor_jawaban) FROM jawaban_pertanyaan_harapan_$table_identity
JOIN survey_$table_identity ON jawaban_pertanyaan_harapan_$table_identity.id_responden =
survey_$table_identity.id_responden
JOIN pertanyaan_unsur_pelayanan_$table_identity ON pertanyaan_unsur_pelayanan_$table_identity.id =
jawaban_pertanyaan_harapan_$table_identity.id_pertanyaan_unsur
JOIN unsur_pelayanan_$table_identity ON pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan =
unsur_pelayanan_$table_identity.id
WHERE is_submit = 1 && unsur_pelayanan_$table_identity.id_parent = $row->id_unsur_pelayanan && skor_jawaban =
nilai_tingkat_kepentingan_$table_identity.nomor_tingkat_kepentingan) AS perolehan

FROM nilai_tingkat_kepentingan_$table_identity
JOIN pertanyaan_unsur_pelayanan_$table_identity ON
nilai_tingkat_kepentingan_$table_identity.id_pertanyaan_unsur_pelayanan = pertanyaan_unsur_pelayanan_$table_identity.id
JOIN unsur_pelayanan_$table_identity ON pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan =
unsur_pelayanan_$table_identity.id
WHERE id_parent = $row->id_unsur_pelayanan
GROUP BY nomor_tingkat_kepentingan")
?>

<script>
FusionCharts.ready(function() {
    var myChart = new FusionCharts({
        "type": "pie3d",
        "renderAt": "pie_<?php echo e($row->id_unsur_pelayanan); ?>",
        "width": "100%",
        "height": "350",
        "dataFormat": "json",
        dataSource: {
            "chart": {
                "caption": "H<?php echo $row->nomor_harapan . '. ' . $row->nama_unsur_pelayanan ?>",
                "enableSmartLabels": "1",
                "startingAngle": "0",
                "showPercentValues": "1",
                "decimals": "2",
                "useDataPlotColorForLabels": "1",
                "theme": "umber",
                "bgColor": "#ffffff"
            },
            "data": [
                <?php foreach ($unsur_turunan->result() as $obj) { ?> {
                    label: "<?php echo $obj->nama_tingkat_kepentingan . ' = ' . $obj->perolehan ?>",
                    value: "<?php echo $obj->perolehan ?>"
                },
                <?php } ?>
            ]
        }
    });
    myChart.render();
});
</script>
<?php endif; ?>


<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('include_backend/template_backend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\IT\Documents\Htdocs MAMP\mk_skm\application\views/grafik_harapan/index.blade.php ENDPATH**/ ?>