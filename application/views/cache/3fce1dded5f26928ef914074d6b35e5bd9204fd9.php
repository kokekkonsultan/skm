<?php
$ci = get_instance();
?>

<?php
$message_success = $ci->session->flashdata('message_success');
?>
<?php if(!empty($message_success)): ?>
<div class="alert alert-custom alert-notice alert-light-success fade show" role="alert">
    <div class="alert-icon"><i class="flaticon-warning"></i></div>
    <div class="alert-text"><?php
        echo $message_success
        ?></div>
    <div class="alert-close">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true"><i class="ki ki-close"></i></span>
        </button>
    </div>
</div>
<?php endif; ?>

<?php
$message_info = $ci->session->flashdata('message_info');
?>
<?php if(!empty($message_info)): ?>
<div class="alert alert-custom alert-notice alert-light-info fade show" role="alert">
    <div class="alert-icon"><i class="flaticon-warning"></i></div>
    <div class="alert-text"><?php
        echo $message_info
        ?></div>
    <div class="alert-close">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true"><i class="ki ki-close"></i></span>
        </button>
    </div>
</div>
<?php endif; ?>

<?php
$message_warning = $ci->session->flashdata('message_warning');
?>
<?php if(!empty($message_warning)): ?>
<div class="alert alert-custom alert-notice alert-light-warning fade show" role="alert">
    <div class="alert-icon"><i class="flaticon-warning"></i></div>
    <div class="alert-text"><?php
        echo $message_warning
        ?></div>
    <div class="alert-close">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true"><i class="ki ki-close"></i></span>
        </button>
    </div>
</div>
<?php endif; ?>

<?php
$message_danger = $ci->session->flashdata('message_danger');
?>
<?php if(!empty($message_danger)): ?>
<div class="alert alert-custom alert-notice alert-light-danger fade show" role="alert">
    <div class="alert-icon"><i class="flaticon-warning"></i></div>
    <div class="alert-text"><?php
        echo $message_danger
        ?></div>
    <div class="alert-close">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true"><i class="ki ki-close"></i></span>
        </button>
    </div>
</div>
<?php endif; ?>

<?php if(!empty($message_data_success)): ?>
<div class="alert alert-custom alert-notice alert-light-success fade show" role="alert">
    <div class="alert-icon"><i class="flaticon-warning"></i></div>
    <div class="alert-text"><?php echo e($message_data_success); ?></div>
    <div class="alert-close">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true"><i class="ki ki-close"></i></span>
        </button>
    </div>
</div>
<?php endif; ?>

<?php if(!empty($message_data_info)): ?>
<div class="alert alert-custom alert-notice alert-light-info fade show" role="alert">
    <div class="alert-icon"><i class="flaticon-warning"></i></div>
    <div class="alert-text"><?php echo e($message_data_info); ?></div>
    <div class="alert-close">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true"><i class="ki ki-close"></i></span>
        </button>
    </div>
</div>
<?php endif; ?>

<?php if(!empty($message_data_warning)): ?>
<div class="alert alert-custom alert-notice alert-light-warning fade show" role="alert">
    <div class="alert-icon"><i class="flaticon-warning"></i></div>
    <div class="alert-text"><?php echo e($message_data_warning); ?></div>
    <div class="alert-close">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true"><i class="ki ki-close"></i></span>
        </button>
    </div>
</div>
<?php endif; ?>

<?php if(!empty($message_data_danger)): ?>
<div class="alert alert-custom alert-notice alert-light-danger fade show" role="alert">
    <div class="alert-icon"><i class="flaticon-warning"></i></div>
    <div class="alert-text"><?php echo e($message_data_danger); ?></div>
    <div class="alert-close">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true"><i class="ki ki-close"></i></span>
        </button>
    </div>
</div>
<?php endif; ?><?php /**PATH C:\Users\IT\Documents\Htdocs MAMP\mk_skm\application\views/include_backend/partials_no_aside/_message.blade.php ENDPATH**/ ?>