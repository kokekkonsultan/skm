@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')

@endsection

@section('content')

<div class="container">
    <div class="row justify-content-md-center">
        <div class="col col-lg-6 mt-5">

            <div class="card">
                <div class="card-body">

                    <h1><?php echo lang('edit_user_heading'); ?></h1>
                    <p><?php echo lang('edit_user_subheading'); ?></p>

                    <div id="infoMessage"><?php echo $message; ?></div>

                    <?php echo form_open(uri_string()); ?>

                    <p>
                        <?php echo lang('edit_user_fname_label', 'first_name'); ?> <br />
                        <?php echo form_input($first_name); ?>
                    </p>

                    <p>
                        <?php echo lang('edit_user_lname_label', 'last_name'); ?> <br />
                        <?php echo form_input($last_name); ?>
                    </p>

                    <p>
                        <?php echo lang('edit_user_company_label', 'company'); ?> <br />
                        <?php echo form_input($company); ?>
                    </p>

                    <p>
                        <?php echo lang('edit_user_phone_label', 'phone'); ?> <br />
                        <?php echo form_input($phone); ?>
                    </p>

                    <p>
                        <?php echo lang('edit_user_password_label', 'password'); ?> <br />
                        <?php echo form_input($password); ?>
                    </p>

                    <p>
                        <?php echo lang('edit_user_password_confirm_label', 'password_confirm'); ?><br />
                        <?php echo form_input($password_confirm); ?>
                    </p>

                    <?php echo form_hidden('id', $user->id); ?>
                    <?php echo form_hidden($csrf); ?>

                    <p>
                        @php
                        echo anchor(base_url().'auth', 'Cancel', ['class' => 'btn btn-secondary'])
                        @endphp
                        <?php echo form_submit('submit', lang('edit_user_submit_btn'), ['class' => 'btn btn-primary']); ?>
                    </p>

                    <?php echo form_close(); ?>

                </div>
            </div>

        </div>
    </div>


</div>

@endsection

@section('javascript')

@endsection