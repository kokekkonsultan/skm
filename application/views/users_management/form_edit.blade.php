@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')

@endsection

@section('content')

<div class="container-fluid">
    <div class="card" data-aos="fade-down">
        <div class="card-header bg-secondary">
            <h5>{{ $title }}</h5>
            <span> Please enter the user's information below.</span>
        </div>
        <div class="card-body">

            <div class="tect-danger" id="infoMessage"><?php echo $message; ?></div>

            <?php echo form_open(base_url() . $ci->session->userdata('username') . '/users-management/list-users/' . $ci->uri->segment(4) . '/edit/' . $ci->uri->segment(6)); ?>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label class="font-weight-bold">First Name <span style="color: red;">*</span></label>
                    <?php echo form_input($first_name); ?>
                </div>
                <div class="form-group col-md-6">
                    <label class="font-weight-bold">Last Name <span style="color: red;">*</span></label>
                    <?php echo form_input($last_name); ?>
                </div>
            </div>

            <div class="form-row">

                <div class="form-group col-md-6">
                    <label class="font-weight-bold">Divisi <span style="color: red;">*</span></label>
                    <?php echo form_dropdown($id_divisi); ?>
                </div>

                <div class="form-group col-md-6">
                    <label class="font-weight-bold">Telepon / Whatsapp <span style="color: red;">*</span></label>
                    <?php echo form_input($phone); ?>
                </div>
            </div>

            <div class="form-group">
                <label class="font-weight-bold">Email <span style="color: red;">*</span></label>
                <?php echo form_input($email); ?>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label class="font-weight-bold">Password <span style="color: red;">*</span></label>
                    <?php echo form_input($password); ?>
                </div>
                <div class="form-group col-md-6">
                    <label class="font-weight-bold">Confirm Password <span style="color: red;">*</span></label>
                    <?php echo form_input($password_confirm); ?>
                </div>
            </div>

            <div class="text-right">
                @php
                echo
                anchor(base_url().$ci->session->userdata('username').'/'.$ci->uri->segment(2).'/list-users/'.$ci->uri->segment(4),
                'Cancel', ['class' => 'btn btn-light-primary font-weight-bold'])
                @endphp
                <?php echo form_submit('submit', 'Update Pengguna', ['class' => 'btn btn-primary font-weight-bold']); ?>
            </div>

            <?php echo form_close(); ?>
        </div>
    </div>

</div>

@endsection

@section('javascript')

@endsection