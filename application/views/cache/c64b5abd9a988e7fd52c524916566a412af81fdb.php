

<?php
$ci = get_instance();
?>


<?php $__env->startSection('style'); ?>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <?php echo $__env->make("include_backend/partials_no_aside/_inc_menu_repository", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="row mt-5">
        <div class="col-md-3">
            <?php echo $__env->make('manage_survey/menu_data_survey', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <div class="col-md-9">


        <img class="card-img-top" src="<?php echo e(base_url()); ?>assets/img/banner/laporan-img.jpg" alt="new image">

        <div class=" card mb-5 mt-5" data-aos="fade-down">
            <div class="card-body">
                <p>
                    Setelah aktivitas survei selesai dan data sudah terkumpul maka Anda dapat mendownload
                    laporan SKM. Gunakan tombol dibawah ini untuk mendownload laporan SKM.
                    Anda.
                </p>

                <br>

                <div class="card-deck">
                    <a href="<?php echo e(base_url()); ?><?php echo e($ci->session->userdata('username')); ?>/<?php echo e($ci->uri->segment(2)); ?>/laporan-survey/download-docx"
                    target="_blank" class="card card-body border border-primary text-primary shadow wave wave-animate-slow wave-primary">
                        <div class="text-center font-weight-bold">
                            <i class="fa fa-file-word text-primary" style="font-size: 30px;"></i><br>
                            <h6 class="mt-3">Download Laporan SKM format .docx</h6>
                        </div>
                    </a>

                    <a href="<?php echo e(base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/laporan-survey/cetak'); ?>"
                        class="card card-body text-danger border border-danger shadow wave wave-animate-slow wave-danger"
                        target="_blank">
                        <div class="text-center font-weight-bold">
                            <i class="fa fa-file-pdf text-danger" style="font-size: 30px;"></i><br>
                            <h6 class="mt-3">Download Laporan SKM format .pdf</h5>
                        </div>
                    </a>
                </div>

            </div>
        </div>

        </div>
    </div>
</div>


<?php $__env->stopSection(); ?>

<?php $__env->startSection('javascript'); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('include_backend/template_backend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\IT\Documents\Htdocs MAMP\mk_skm\application\views/laporan_survey/index.blade.php ENDPATH**/ ?>