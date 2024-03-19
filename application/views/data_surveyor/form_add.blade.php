@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')

@endsection

@section('content')

<div class="container-fluid">
    @include("include_backend/partials_no_aside/_inc_menu_repository")

    <div class="row mt-5">
        <div class="col-md-3">
            @include('manage_survey/menu_data_survey')
        </div>
        <div class="col-md-9">

            <div class="card" data-aos="fade-down">
                <div class="card-header bg-secondary">
                    <h3>{{ $title }}</h3>
                    <div> Please enter the user's information below.</div>
                </div>
                <div class="card-body">
                    <br>

                    <div id="infoMessage"><?php echo $message; ?></div>

                    <?php echo form_open(base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/data-surveyor/add'); ?>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">Nama Depan <span style="color: red;">*</span></label>
                            <?php echo form_input($first_name); ?>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">Nama Belakang <span style="color: red;">*</span></label>
                            <?php echo form_input($last_name); ?>
                        </div>
                    </div>

                    <div class="form-row">
                        <?php
                        if ($identity_column !== 'email') {
                            echo '<div class="form-group col-md-6">';
                            echo '<label class="font-weight-bold">Username <span style="color: red;">*</span></label>';
                            echo form_error('identity');
                            echo form_input($identity);
                            echo '</div>';
                        }
                        ?>

                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">Kode Surveyor <span style="color: red;">*</span></label>
                            <?php echo form_input($kode_surveyor); ?>
                        </div>
                    </div>

                    <div>
                        <label class="font-weight-bold">Organisasi <span style="color: red;">*</span></label>
                        <?php echo form_input($company); ?>
                    </div>

                    <br>

                    <div>
                        <label class="font-weight-bold">Email <span style="color: red;">*</span></label>
                        <?php echo form_input($email); ?>
                    </div>

                    <br>

                    <div>
                        <label class="font-weight-bold">Phone <span style="color: red;">*</span></label>
                        <?php echo form_input($phone); ?>
                    </div>

                    <br>

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

                    <br>




                    <div class="text-right">
                        @php
                        echo
                        anchor(base_url().$ci->session->userdata('username').'/'.$ci->uri->segment(2).'/data-surveyor',
                        'Cancel', ['class' => 'btn btn-light-primary font-weight-bold'])
                        @endphp
                        <?php echo form_submit('submit', 'Create Surveyor', ['class' => 'btn btn-primary font-weight-bold']); ?>
                    </div>

                    <?php echo form_close(); ?>
                </div>
            </div>

        </div>
    </div>

</div>

@endsection

@section('javascript')

@endsection