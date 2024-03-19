

<?php
$ci = get_instance();
?>

<?php $__env->startSection('style'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class=" container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="text-center">
                <i class="flaticon-profile-1 icon-10x"></i>
            </div>
            <?php echo $__env->make('include_backend/partials_backend/_inc_profile', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <div class="col-md-12 mt-5">
            

            <div id="response-list-survey">
                <div align="center">
                    <div class="ajax-loader"></div>
                </div>
            </div>

            <!-- <div id="response-list-activity">
                <div align="center"><br><br>
                    <div class="ajax-loader"></div>
                </div>
            </div> -->

            <div id="response-list-campaign">
                <div align="center"><br><br>
                    <div class="ajax-loader"></div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('javascript'); ?>
<script src="<?php echo e(TEMPLATE_BACKEND_PATH); ?>js/pages/features/charts/apexcharts.js"></script>
<!-- Notif -->


<script>
$(document).ready(function() {
    var flash = $('#flash').data('flash');
    console.log(flash);
    if (flash) {
        Swal.fire({
            title: 'Success!',
            text: 'Profil Berhasil di Ubah!',
            icon: 'success'
        })
    }
});

$(function() {

    $.ajax({
        type: "GET",
        url: "<?php echo e(base_url()); ?><?php echo e($ci->session->userdata('username')); ?>/overview/list-survey",
        dataType: "html",
        success: function(response) {
            $("#response-list-survey").html(response);
        }

    });

});

$(function() {

    $.ajax({
        type: "GET",
        url: "<?php echo e(base_url()); ?><?php echo e($ci->session->userdata('username')); ?>/overview/list-activity",
        dataType: "html",
        success: function(response) {
            $("#response-list-activity").html(response);
        }

    });

});

$(function() {

    $.ajax({
        type: "GET",
        url: "<?php echo e(base_url()); ?><?php echo e($ci->session->userdata('username')); ?>/overview/list-campaign",
        dataType: "html",
        success: function(response) {
            $("#response-list-campaign").html(response);
        }

    });

});
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('include_backend/template_backend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\IT\Documents\Htdocs MAMP\mk_skm\application\views/manage_survey/form_profile.blade.php ENDPATH**/ ?>