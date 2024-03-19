

<?php
$ci = get_instance();
?>

<?php $__env->startSection('style'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="container-fluid">
    <?php echo $__env->make("include_backend/partials_no_aside/_inc_menu_repository", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="row justify-content-md-center mt-5">
        <div class="col-md-3">
            <?php echo $__env->make('manage_survey/menu_data_survey', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <div class="col-md-9">
            <div class="card" data-aos="fade-down">
                <div class="card-header bg-secondary">
                    <h5><?php echo e($title); ?></h5>
                </div>
                <div class="card-body">
                    <span class="text-danger"><?php echo validation_errors(); ?></span>
                    <br>
                    <?php echo form_open(base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/pertanyaan-unsur/edit/' . $ci->uri->segment(5)); ?>
                    <?php
                    echo validation_errors();
                    ?>


                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label font-weight-bold">Unsur Pelayanan <span
                                style="color: red;">*</span></label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <div class="input-group-prepend"><span
                                        class="input-group-text"><?php echo $nomor_unsur ?></span></div>
                                <?php
                                echo form_input($nama_unsur_pelayanan);
                                ?>
                                <small>
                                    Menurut Permenpan dan RB, unsur SKM terbagi 9 unsur antara lain: 1) Persyaratan 2)
                                    Sistem, Mekanisme, dan Prosedur 3) Waktu Penyelesaian 4) Biaya/Tarif 5) Produk
                                    Spesifikasi Jenis Pelayanan 6) Kompetensi Pelaksana 7) Perilaku Pelaksana 8)
                                    Penanganan Pengaduan, Saran dan Masukan 9) Sarana dan prasarana
                                </small>
                            </div>
                        </div>
                    </div>

                    <?php if($unsur_turunan == 1): ?>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label font-weight-bold">Isi Pertanyaan <span
                                style="color: red;">*</span>
                        </label>
                        <div class="col-sm-9">
                            <?php
                            echo form_textarea($isi_pertanyaan_unsur);
                            ?>
                        </div>
                    </div>

                    <br>

                    <?php $__currentLoopData = $nama_kategori_unsur; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <input type="text" class="form-control" id="id_kategori" name="id_kategori[]"
                        value="<?php echo $row->id; ?>" hidden>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label font-weight-bold">Pilihan Jawaban
                            <span style="color: red;">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="nama_kategori_unsur_pelayanan"
                                name="nama_kategori_unsur_pelayanan[]"
                                value="<?php echo $row->nama_kategori_unsur_pelayanan; ?>" required>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    

                    <hr class="mb-5">

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label
                        font-weight-bold">Wajib Diisi <span style="color: red;">*</span></label>
                        <div class="col-sm-9">
                            <select class="form-control" id="is_required" name="is_required"
                                value="<?php echo set_value('is_required'); ?>">
                                <option>Please Select</option>
                                <option value='1' <?php if ($pertanyaan_unsur->is_required == "1") {
                                                        echo "selected";
                                                    } ?>>Aktif</option>
                                <option value='2' <?php if ($pertanyaan_unsur->is_required == "2") {
                                                        echo "selected";
                                                    } ?>>Tidak Aktif</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label
                        font-weight-bold">Alasan <span style="color: red;">*</span></label>
                        <div class="col-sm-9">
                            <select class="form-control" id="is_alasan" name="is_alasan"
                                value="<?php echo set_value('is_alasan'); ?>">
                                <option>Please Select</option>
                                <option value='1' <?php if ($pertanyaan_unsur->is_alasan == "1") {
                                                        echo "selected";
                                                    } ?>>Aktif</option>
                                <option value='2' <?php if ($pertanyaan_unsur->is_alasan == "2") {
                                                        echo "selected";
                                                    } ?>>Tidak Aktif</option>
                            </select>
                        </div>
                    </div>

                    <?php else: ?>
                        <input type="hidden" id="is_required" name="is_required" value="<?php echo $pertanyaan_unsur->is_required; ?>">
                        <input type="hidden" id="is_alasan" name="is_alasan" value="<?php echo $pertanyaan_unsur->is_alasan; ?>">

                    <?php endif; ?>

                    <div class="text-right">
                        <?php
                        echo
                        anchor(base_url().$ci->session->userdata('username').'/'.$ci->uri->segment(2).'/pertanyaan-unsur',
                        'Cancel', ['class' => 'btn btn-light-primary font-weight-bold'])
                        ?>
                        <?php echo form_submit('submit', 'Update', ['class' => 'btn btn-primary font-weight-bold']); ?>
                    </div>

                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('javascript'); ?>


<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
<script type="text/javascript">
$(function() {
    $(":radio.custom").click(function() {
        $("#id_parent").hide()
        if ($(this).val() == "1") {
            $("#id_parent").show();
        } else {
            $("#id_parent").hidden();
        }
    });
});
</script>

<script type="text/javascript">
$(function() {
    $(":radio.jawaban").click(function() {
        $("#4_jawaban").hide()
        if ($(this).val() == "Custom") {
            $("#4_jawaban").show();
        } else {
            $("#4_jawaban").hide();
        }

        $("#2_jawaban").hide()
        if ($(this).val() == "Default") {
            $("#2_jawaban").show();
        } else {
            $("#2_jawaban").hide();
        }
    });
});
</script>

<script src="https://cdn.ckeditor.com/ckeditor5/34.2.0/classic/ckeditor.js"></script>
<script>
ClassicEditor
    .create(document.querySelector('#isi_pertanyaan_unsur'))
    .then(editor => {
        console.log(editor);
    })
    .catch(error => {
        console.error(error);
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('include_backend/template_backend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\IT\Documents\Htdocs MAMP\mk_skm\application\views/pertanyaan_unsur_survei/edit.blade.php ENDPATH**/ ?>