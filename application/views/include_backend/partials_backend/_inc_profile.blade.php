{{-- <div class="text-center">

    <?php if ($data_user->foto_profile == NULL) : ?>
        <img src="<?php echo base_url() ?>assets/klien/foto_profile/200px.jpg" class="rounded" alt="foto-profile" style="width:200px; height:200px; border-radius:50%; overflow:hidden;">
    <?php else : ?>
        <img src="<?php echo base_url() ?>assets/klien/foto_profile/<?php echo $data_user->foto_profile ?>" class="rounded" alt="foto-profile" style="width:200px; height:200px; border:5px solid #ffffff; border-radius:50%; overflow:hidden;">
    <?php endif; ?>



</div> --}}
<div class="text-center mt-3 mb-5">
    <h1>{{ $data_user->company }}</h1>
    <h5>{{ $data_user->first_name }} {{ $data_user->last_name }} <a href="{{ base_url() }}profile"><i class="fas fa-user-edit text-primary"></i></a></h5>
</div>