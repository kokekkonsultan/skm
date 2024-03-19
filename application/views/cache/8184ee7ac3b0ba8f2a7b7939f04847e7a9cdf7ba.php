

<?php
$ci = get_instance();
?>

<?php $__env->startSection('style'); ?>
<link rel="dns-prefetch" href="//fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>


<div class="container mt-5 mb-5" style="font-family: nunito;">
    <div class="text-center" data-aos="fade-up">
        <div id="progressbar" class="mb-5">
            <li class="active" id="account"><strong>Data Responden</strong></li>
            <li class="active" id="personal"><strong>Pertanyaan Survei</strong></li>
            <?php if($status_saran == 1): ?>
            <li id="payment"><strong>Saran</strong></li>
            <?php endif; ?>
            <li id="completed"><strong>Completed</strong></li>
        </div>
    </div>
    <br>
    <br>
    <div class="row">
        <div class="col-md-8 offset-md-2" style="font-size: 16px; font-family:arial, helvetica, sans-serif;">
            <div class="card shadow mb-4 mt-4" id="kt_blockui_content" data-aos="fade-up">

                <?php if($judul->img_benner == ''): ?>
                <img class="card-img-top" src="<?php echo e(base_url()); ?>assets/img/site/page/banner-survey.jpg"
                    alt="new image" />
                <?php else: ?>
                <img class="card-img-top shadow"
                    src="<?php echo e(base_url()); ?>assets/klien/benner_survei/<?php echo e($manage_survey->img_benner); ?>" alt="new image">
                <?php endif; ?>
                <div class="card-header text-center">
                    <h3 class="mt-5" style="font-family: 'Exo 2', sans-serif;"><b>PERTANYAAN HARAPAN</b></h3>
					<?php echo $__env->make('include_backend/partials_backend/_tanggal_survei', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
                <div class="card-body">

                    <form action="<?php echo base_url() . 'survei/' . $ci->uri->segment(2) . '/add-pertanyaan-harapan/' .
                                        $ci->uri->segment(4) ?>" class="form_survei" method="POST">


                        <?php
                        $i = 1;
                        ?>
                        <?php $__currentLoopData = $pertanyaan_unsur->result(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <input type="hidden" name="id_pertanyaan_unsur[<?php echo e($i); ?>]"
                            value="<?php echo e($row->id_pertanyaan_unsur); ?>">

                            <?php if($row->skor_unsur == 0): ?>

                            <input class="" value="0" name="jawaban_pertanyaan_harapan[<?php echo e($i); ?>]" hidden>

                            <?php else: ?>
                            <table class="table table-borderless mt-5 mb-5" width="100%" border="0">
                                <tr>
                                    <td width="5%" valign="top">H<?php echo $row->nomor_harapan; ?><b class="text-danger">*</b>.</td>
                                    <td width="95%"><?php echo $row->isi_pertanyaan_unsur ?></td>
                                </tr>

                                <tr>
                                    <td width="5%"></td>
                                    <td style="font-weight: bold;" width="95%">

                                        <?php $__currentLoopData = $jawaban_pertanyaan_harapan->result(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($value->id_pertanyaan_unsur_pelayanan == $row->id_pertanyaan_unsur): ?>

                                        <div class="radio-inline mb-2">
                                            <label class="radio radio-outline radio-success radio-lg"
                                                style="font-size: 16px;">
                                                <input type="radio" name="jawaban_pertanyaan_harapan[<?php echo e($i); ?>]"
                                                    value="<?php echo e($value->nomor_tingkat_kepentingan); ?>"
                                                    <?php echo $value->nomor_tingkat_kepentingan == $value->skor_jawaban ? 'checked' : '' ?>
                                                    required><span></span>
                                                <?php echo e($value->nama_tingkat_kepentingan); ?>

                                            </label>
                                        </div>

                                        <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                    </td>
                                </tr>
                            </table>
                            <br>
                            <?php endif; ?>

                        

                        <?php
                        $i++;
                        ?>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>


                <div class="card-footer">
                    <table class="table table-borderless">
                        <tr>
                            <td class="text-left">
                                <?php if($ci->uri->segment(5) == 'edit'): ?>
                                <?php echo anchor(base_url().'survei/'. $ci->uri->segment(2) . '/pertanyaan/' .
                                $ci->uri->segment(4) . '/edit', '<i class="fa fa-arrow-left"></i> Kembali', ['class' => 'btn
                                btn-secondary btn-lg font-weight-bold
                                shadow tombolCancel']); ?>

                                <?php else: ?>
                                <?php echo anchor(base_url().'survei/'. $ci->uri->segment(2) . '/pertanyaan/' .
                                $ci->uri->segment(4), '<i class="fa fa-arrow-left"></i> Kembali', ['class' => 'btn
                                btn-secondary btn-lg font-weight-bold
                                shadow tombolCancel']); ?>

                                <?php endif; ?>
                            </td>
                            <td class="text-right">
                                <button type="submit"
                                    class="btn btn-warning btn-lg font-weight-bold shadow tombolSave">Selanjutnya
                                    <i class="fa fa-arrow-right"></i></button>
                            </td>
                        </tr>
                    </table>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>


<?php $__env->stopSection(); ?>

<?php $__env->startSection('javascript'); ?>
<script>
$('.form_survei').submit(function(e) {

    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        dataType: 'json',
        data: $(this).serialize(),
        cache: false,
        beforeSend: function() {
            $('.tombolCancel').attr('disabled', 'disabled');
            $('.tombolSave').attr('disabled', 'disabled');
            $('.tombolSave').html('<i class="fa fa-spin fa-spinner"></i> Sedang diproses');

            KTApp.block('#kt_blockui_content', {
                overlayColor: '#FFA800',
                state: 'primary',
                message: 'Processing...'
            });

            setTimeout(function() {
                KTApp.unblock('#kt_blockui_content');
            }, 1000);

        },
        complete: function() {
            $('.tombolCancel').removeAttr('disabled');
            $('.tombolSave').removeAttr('disabled');
            $('.tombolSave').html('Selanjutnya <i class="fa fa-arrow-right"></i>');
        },

        error: function(e) {
            Swal.fire(
                'Error !',
                e,
                'error'
            )
        },

        success: function(data) {
            if (data.validasi) {
                $('.pesan').fadeIn();
                $('.pesan').html(data.validasi);
            }
            if (data.sukses) {
                // toastr["success"]('Data berhasil disimpan');

                setTimeout(function() {
                    window.location.href = "<?php echo $url_next ?>";
                }, 500);
            }
        }
    })
    return false;
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('include_backend/_template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\IT\Documents\Htdocs MAMP\mk_skm\application\views/survei/form_pertanyaan_harapan.blade.php ENDPATH**/ ?>