

<?php
$ci = get_instance();
?>

<?php $__env->startSection('style'); ?>
<link href="<?php echo e(TEMPLATE_BACKEND_PATH); ?>plugins/custom/datatables/datatables.bundle.css" rel="stylesheet"
    type="text/css" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="container-fluid">
    <?php echo $__env->make("include_backend/partials_no_aside/_inc_menu_repository", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="row mt-5">
        <div class="col-md-3">
            <?php echo $__env->make('manage_survey/menu_data_survey', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <div class="col-md-9">

            <div class="card card-custom bgi-no-repeat gutter-b"
                style="height: 150px; background-color: #1c2840; background-position: calc(100% + 0.5rem) 100%; background-size: 100% auto; background-image: url(/assets/img/banner/taieri.svg)"
                data-aos="fade-down">
                <div class="card-body d-flex align-items-center">
                    <div>
                        <h3 class="text-white font-weight-bolder line-height-lg mb-5">
                            <?php echo e(strtoupper($title)); ?>

                        </h3>

                        <a class="btn btn-secondary font-weight-bold"
                            href="<?php echo e(base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/pertanyaan-unsur'); ?>"><i
                                class="fa fa-arrow-left"></i> Kembali</a>

                    </div>

                </div>
            </div>



            <!----------------------------------------------------------------------------------------------------------------------------->
            <!----------------------------------------------------- START TERBUKA ATAS ---------------------------------------------------->
            <!----------------------------------------------------------------------------------------------------------------------------->

            <?php $__currentLoopData = $ci->db->query("SELECT *, perincian_pertanyaan_terbuka_$table_identity.id AS id_perincian
            FROM pertanyaan_terbuka_$table_identity
            JOIN perincian_pertanyaan_terbuka_$table_identity ON pertanyaan_terbuka_$table_identity.id =
            perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka
            WHERE is_letak_pertanyaan = 1")->result(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pt_a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>


            <div class="card  shadow mb-5" style="border-left: 5px solid #cefc03;">
                <!-- <div class="text-center mt-2">
        <div class="dropdown">
            <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-bars"></i>
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" data-toggle="modal" data-target="#edit">Edit</a>

                <a class="dropdown-item" href="javascript:void(0)" title="Hapus" onclick="tes()">Hapus</a>
            </div>
        </div>
    </div> -->


                <div class="card-body">


                    <table class="table table-bordered" style="font-size: 14px;">
                        <tr>
                            <th width="4%"><?php echo e($pt_a->nomor_pertanyaan_terbuka); ?>.</th>
                            <th colspan="2"><?php echo $pt_a->isi_pertanyaan_terbuka; ?></th>
                        </tr>


                        <?php if($pt_a->id_jenis_pilihan_jawaban == 1): ?>
                        <?php $__currentLoopData = $ci->db->query("SELECT * FROM isi_pertanyaan_ganda_$table_identity WHERE
                        id_perincian_pertanyaan_terbuka = $pt_a->id_perincian")->result(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ipg_a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td></td>
                            <td>
                                <div class="radio-inline" style="font-size: 14px;">
                                    <label class="radio radio-outline radio-success">
                                        <input type="radio" name="<?php echo e($pt_a->nomor_pertanyaan_terbuka); ?>"><span></span>
                                        <?php echo e($ipg_a->pertanyaan_ganda); ?>

                                    </label>
                                </div>
                            </td>


                            <td width="35%">
                                <select class="form-control form-control-sm" name="is_next_step[]"
                                    id="idt_<?php echo e($ipg_a->id); ?>">
                                    <?php
                                    $terbuka_a = $ci->db->query("SELECT * FROM pertanyaan_terbuka_$table_identity
                                    WHERE is_letak_pertanyaan = 1 && SUBSTR(nomor_pertanyaan_terbuka,2) >
                                    SUBSTR('$pt_a->nomor_pertanyaan_terbuka', 2)");

                                    $last_row_a = $terbuka_a->last_row();
                                    $number_next = substr($last_row_a->nomor_pertanyaan_terbuka, 1) + 1;
                                    ?>

                                    <?php $__currentLoopData = $terbuka_a->result(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subpt_a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($subpt_a->nomor_pertanyaan_terbuka); ?>"
                                        <?php echo $ipg_a->is_next_step == $subpt_a->nomor_pertanyaan_terbuka ? 'selected' : '' ?>>
                                        Lanjutkan
                                        Ke <?php echo e($subpt_a->nomor_pertanyaan_terbuka); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                    <option value="T<?php echo e($number_next); ?>"
                                        <?php echo $ipg_a->is_next_step == 'T' . $number_next ? 'selected' : '' ?>>
                                        Lanjutkan
                                        Ke
                                        Pertanyaan Unsur Berikutnya
                                    </option>

                                </select>
                            </td>

                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        <?php else: ?>
                        <tr>
                            <td></td>
                            <td>
                                <textarea class="form-control" placeholder="Masukkan jawaban anda..."></textarea>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <!----------------------------------------------------------------------------------------------------------------------------->
            <!----------------------------------------------------- END TERBUKA ATAS ------------------------------------------------------>
            <!----------------------------------------------------------------------------------------------------------------------------->





            <!----------------------------------------------------------------------------------------------------------------------------->
            <!------------------------------------------------------- START UNSUR --------------------------------------------------------->
            <!----------------------------------------------------------------------------------------------------------------------------->

            <?php $__currentLoopData = $pertanyaan_unsur->result(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

            <div class="card card-body mb-5 mt-5" style="border-left: 5px solid #0091ff;">
                <table class="table table-bordered" style="font-size: 14px;">
                    <tr>
                        <th width="4%"><?php echo e($row->nomor_unsur); ?>.</th>
                        <th colspan="2"><?php echo $row->isi_pertanyaan_unsur; ?>

                        </th>
                    </tr>

                    <?php $__currentLoopData = $ci->db->query("SELECT * FROM kategori_unsur_pelayanan_$table_identity WHERE
                    id_unsur_pelayanan
                    =
                    $row->id_unsur_pelayanan")->result(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td></td>
                        <td>
                            <div class="radio-inline" style="font-size: 14px;">
                                <label class="radio radio-outline radio-success">
                                    <input type="radio" name="<?php echo e($row->nomor_unsur); ?>"><span></span>
                                    <?php echo e($value->nama_kategori_unsur_pelayanan); ?>

                                </label>
                            </div>
                        </td>

                        <td width="35%">
                            <select class="form-control form-control-sm" name="is_next_step[]" id="idu_<?php echo e($value->id); ?>">
                                <?php
                                $pertanyaan_terbuka = $ci->db->get_where("pertanyaan_terbuka_$table_identity",
                                array('id_unsur_pelayanan' => $row->id_unsur_pelayanan));

                                $last_row = $pertanyaan_terbuka->last_row();
                                $number_next = substr($last_row->nomor_pertanyaan_terbuka, 1) + 1;
                                ?>

                                <?php $__currentLoopData = $pertanyaan_terbuka->result(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $get): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($get->nomor_pertanyaan_terbuka); ?>"
                                    <?php echo $value->is_next_step == $get->nomor_pertanyaan_terbuka ? 'selected' : '' ?>>
                                    Lanjutkan
                                    Ke <?php echo e($get->nomor_pertanyaan_terbuka); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                <option value="T<?php echo e($number_next); ?>"
                                    <?php echo $value->is_next_step == 'T' . $number_next ? 'selected' : '' ?>>Lanjutkan
                                    Ke
                                    Pertanyaan Unsur Berikutnya</option>
                            </select>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </table>
            </div>


            <!----------------------------------------------------------------------------------------------------------------------------->
            <!----------------------------------------------------- START TERBUKA --------------------------------------------------------->
            <!----------------------------------------------------------------------------------------------------------------------------->

            <?php $__currentLoopData = $ci->db->query("SELECT *, perincian_pertanyaan_terbuka_$table_identity.id AS id_perincian
            FROM pertanyaan_terbuka_$table_identity
            JOIN perincian_pertanyaan_terbuka_$table_identity ON pertanyaan_terbuka_$table_identity.id =
            perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka
            WHERE id_unsur_pelayanan = $row->id_unsur_pelayanan")->result(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>


            <div class="card card-body shadow mb-5" style="border-left: 5px solid #bb00ff;">
                <table class="table table-bordered" style="font-size: 14px;">
                    <tr>
                        <th width="4%"><?php echo e($pt->nomor_pertanyaan_terbuka); ?>.</th>
                        <th colspan="2"><?php echo $pt->isi_pertanyaan_terbuka; ?></th>
                    </tr>


                    <?php if($pt->id_jenis_pilihan_jawaban == 1): ?>
                    <?php $__currentLoopData = $ci->db->query("SELECT * FROM isi_pertanyaan_ganda_$table_identity WHERE
                    id_perincian_pertanyaan_terbuka = $pt->id_perincian")->result(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ipg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td></td>
                        <td>
                            <div class="radio-inline" style="font-size: 14px;">
                                <label class="radio radio-outline radio-success">
                                    <input type="radio" name="<?php echo e($pt->nomor_pertanyaan_terbuka); ?>"><span></span>
                                    <?php echo e($ipg->pertanyaan_ganda); ?>

                                </label>
                            </div>
                        </td>


                        <td width="35%">
                            <select class="form-control form-control-sm" name="is_next_step[]" id="idt_<?php echo e($ipg->id); ?>">
                                <?php
                                $terbuka_u = $ci->db->query("SELECT * FROM pertanyaan_terbuka_$table_identity
                                WHERE id_unsur_pelayanan = $row->id_unsur_pelayanan &&
                                SUBSTR(nomor_pertanyaan_terbuka,2) >
                                SUBSTR('$pt->nomor_pertanyaan_terbuka', 2)");

                                $last_row = $terbuka_u->last_row();
                                $number_next = substr($last_row->nomor_pertanyaan_terbuka, 1) + 1;
                                ?>

                                <?php $__currentLoopData = $terbuka_u->result(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pt_u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($pt_u->nomor_pertanyaan_terbuka); ?>"
                                    <?php echo $ipg->is_next_step == $pt_u->nomor_pertanyaan_terbuka ? 'selected' : '' ?>>
                                    Lanjutkan
                                    Ke <?php echo e($pt_u->nomor_pertanyaan_terbuka); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                <option value="T<?php echo e($number_next); ?>"
                                    <?php echo $ipg->is_next_step == 'T' . $number_next ? 'selected' : '' ?>>Lanjutkan
                                    Ke
                                    Pertanyaan
                                    Unsur Berikutnya
                                </option>

                            </select>
                        </td>

                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <?php else: ?>
                    <tr>
                        <td></td>
                        <td>
                            <textarea class="form-control" placeholder="Masukkan jawaban anda..."></textarea>
                        </td>
                    </tr>
                    <?php endif; ?>
                </table>

            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <!----------------------------------------------------------------------------------------------------------------------------->
            <!----------------------------------------------------- END TERBUKA ----------------------------------------------------------->
            <!----------------------------------------------------------------------------------------------------------------------------->

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <!----------------------------------------------------------------------------------------------------------------------------->
            <!------------------------------------------------------- END UNSUR ----------------------------------------------------------->
            <!----------------------------------------------------------------------------------------------------------------------------->





            <!----------------------------------------------------------------------------------------------------------------------------->
            <!----------------------------------------------------- START TERBUKA BAWAH --------------------------------------------------->
            <!----------------------------------------------------------------------------------------------------------------------------->

            <?php $__currentLoopData = $ci->db->query("SELECT *, perincian_pertanyaan_terbuka_$table_identity.id AS id_perincian
            FROM pertanyaan_terbuka_$table_identity
            JOIN perincian_pertanyaan_terbuka_$table_identity ON pertanyaan_terbuka_$table_identity.id =
            perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka
            WHERE is_letak_pertanyaan = 2")->result(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pt_b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>


            <div class="card card-body shadow mb-5" style="border-left: 5px solid #ff0088;">
                <table class="table table-bordered" style="font-size: 14px;">
                    <tr>
                        <th width="4%"><?php echo e($pt_b->nomor_pertanyaan_terbuka); ?>.</th>
                        <th colspan="2"><?php echo $pt_b->isi_pertanyaan_terbuka; ?></th>
                    </tr>


                    <?php if($pt_b->id_jenis_pilihan_jawaban == 1): ?>
                    <?php $__currentLoopData = $ci->db->query("SELECT * FROM isi_pertanyaan_ganda_$table_identity WHERE
                    id_perincian_pertanyaan_terbuka = $pt_b->id_perincian")->result(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ipg_b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td></td>
                        <td>
                            <div class="radio-inline" style="font-size: 14px;">
                                <label class="radio radio-outline radio-success">
                                    <input type="radio" name="<?php echo e($pt_b->nomor_pertanyaan_terbuka); ?>"><span></span>
                                    <?php echo e($ipg_b->pertanyaan_ganda); ?>

                                </label>
                            </div>
                        </td>

                        <td width="35%">
                            <select class="form-control form-control-sm" name="is_next_step[]" id="idt_<?php echo e($ipg_b->id); ?>">
                                <?php
                                $terbuka_b = $ci->db->query("SELECT * FROM pertanyaan_terbuka_$table_identity
                                WHERE is_letak_pertanyaan = 2 && SUBSTR(nomor_pertanyaan_terbuka,2) >
                                SUBSTR('$pt_b->nomor_pertanyaan_terbuka', 2)");

                                $last_row_b = $terbuka_b->last_row();
                                $number_next = substr($last_row_b->nomor_pertanyaan_terbuka, 1) + 1;
                                ?>

                                <?php $__currentLoopData = $terbuka_b->result(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subpt_b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($subpt_b->nomor_pertanyaan_terbuka); ?>"
                                    <?php echo $ipg_b->is_next_step == $subpt_b->nomor_pertanyaan_terbuka ? 'selected' : '' ?>>
                                    Lanjutkan
                                    Ke <?php echo e($subpt_b->nomor_pertanyaan_terbuka); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                <option value="T<?php echo e($number_next); ?>"
                                    <?php echo $ipg_b->is_next_step == 'T' . $number_next ? 'selected' : '' ?>>Lanjutkan
                                    Ke
                                    Pertanyaan Unsur Berikutnya
                                </option>

                            </select>
                        </td>

                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <?php else: ?>
                    <tr>
                        <td></td>
                        <td>
                            <textarea class="form-control" placeholder="Masukkan jawaban anda..."></textarea>
                        </td>
                    </tr>
                    <?php endif; ?>
                </table>

            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <!----------------------------------------------------------------------------------------------------------------------------->
            <!----------------------------------------------------- END TERBUKA BAWAH ----------------------------------------------------->
            <!----------------------------------------------------------------------------------------------------------------------------->

        </div>
    </div>

</div>


<?php $__env->stopSection(); ?>

<?php $__env->startSection('javascript'); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<?php $__currentLoopData = $ci->db->get("kategori_unsur_pelayanan_$table_identity")->result(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<script>
$(function() {
    $("#idu_<?php echo e($kup->id); ?>").change(function() {
        var is_next_step = $("#idu_<?php echo e($kup->id); ?>").val();
        $.ajax({
            url: "<?php echo e(base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/update-alur-unsur/' . $kup->id . '/'); ?>" +
                is_next_step,
            type: 'POST',
            dataType: 'json',
            data: $(this).serialize(),
            cache: false,
            error: function(e) {
                Swal.fire(
                    'Error !',
                    e,
                    'error'
                )
            },
            success: function(data) {
                if (data.sukses) {
                    toastr["success"]('Berhasil diubah');
                }
            }
        })
        return false;
    });
});
</script>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


<?php $__currentLoopData = $ci->db->get("isi_pertanyaan_ganda_$table_identity")->result(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ipg_js): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<script>
$(function() {
    $("#idt_<?php echo e($ipg_js->id); ?>").change(function() {
        var is_next_step = $("#idt_<?php echo e($ipg_js->id); ?>").val();
        $.ajax({
            url: "<?php echo e(base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/update-alur-terbuka/' . $ipg_js->id . '/'); ?>" +
                is_next_step,
            type: 'POST',
            dataType: 'json',
            data: $(this).serialize(),
            cache: false,
            error: function(e) {
                Swal.fire(
                    'Error !',
                    e,
                    'error'
                )
            },
            success: function(data) {
                if (data.sukses) {
                    toastr["success"]('Berhasil diubah');
                }
            }
        })
        return false;
    });
});
</script>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('include_backend/template_backend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\IT\Documents\Htdocs MAMP\mk_skm\application\views/alur_pertanyaan_lompat/index.blade.php ENDPATH**/ ?>