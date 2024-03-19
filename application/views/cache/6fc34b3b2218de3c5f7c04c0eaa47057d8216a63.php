

<?php $__env->startSection('style'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>


<div class="container position-relative">
    <div class="row align-items-center py-8">
        <div class="col-md-5 col-lg-6 order-md-1 text-center text-md-end"><img class="img-fluid"
                src="<?= base_url('assets/') ?>themes/img/not-found.png" width="350" alt="" /></div>
        <div class="col-md-7 col-lg-6 text-center text-md-start">
            <h1 class="mb-4 display-3 fw-bold lh-sm">404 NOT FOUND</h1>

        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('javascript'); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('include_frontend/template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\IT\Documents\Htdocs MAMP\mk_skm\application\views/app_error/form_not_found.blade.php ENDPATH**/ ?>