@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')

@endsection

@section('content')

<div class="container">
    <div class="row justify-content-md-center">
        <div class="col col-lg-8 mt-5">

            {!! form_open("pengguna-klien-induk/edit/" . $ci->uri->segment(3)) !!}

            <div class="card card-body mb-5">
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

                </div>
            </div>



            <div class="card mt-5">
                <!-- <div class="card-header font-weight-bold">
                    Cakupan
                </div> -->
                <div class="card-body">

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label font-weight-bold">Cakupan Induk
                            <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <div class="checkbox-list">
                                @foreach($ci->db->query("SELECT * FROM users
                                JOIN users_groups ON users.id = users_groups.user_id
                                WHERE group_id = 2")->result() as $row)
                                <label class="checkbox">
                                    <input type="checkbox" name="cakupan[]" value="{{$row->user_id}}" <?php echo in_array($row->user_id, $data_anak) ? 'checked' : '' ?>>
                                    <span></span> {{$row->first_name . ' ' . $row->last_name}}
                                </label>
                                @endforeach

                            </div>
                        </div>
                    </div>

                </div>
            </div>



            <div class="text-right mt-5 mb-5">
                {!! anchor(base_url().'pengguna-klien-induk', 'Cancel', ['class' => 'btn btn-light-primary
                font-weight-bold
                shadow-lg']) !!}
                {!! form_submit('submit', lang('edit_user_submit_btn'), ['class' => 'btn btn-primary font-weight-bold
                shadow-lg']); !!}
            </div>

            {!! form_close(); !!}

        </div>
    </div>


</div>

@endsection

@section('javascript')
@endsection