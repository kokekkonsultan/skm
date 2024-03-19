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
            <!-- <span> Please enter the user's information below.</span> -->
        </div>
        <div class="card-body">

            <div class="tect-danger" id="infoMessage"><?php echo $message; ?></div>

            <?php echo form_open(base_url() . '/pengguna-surveyor/edit-surveyor/' . $ci->uri->segment(3)); ?>

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
                {{-- <div class="form-group col-md-6">
                    <label class="font-weight-bold">Kode Surveyor <span style="color: red;">*</span></label>
                    <?php echo form_input($kode_surveyor); ?>
                </div> --}}
                <div class="form-group col-md-6">
                    <label class="font-weight-bold">Email <span style="color: red;">*</span></label>
                    <?php echo form_input($email); ?>
                </div>
                <?php //echo form_input($email); ?>

                <div class="form-group col-md-6">
                    <label class="font-weight-bold">Organisasi <span style="color: red;">*</span></label>
                    <?php echo form_input($company); ?>
                </div>
            </div>

            

            <div class="form-group">
                <label class="font-weight-bold">Telepon <span style="color: red;">*</span></label>
                <?php echo form_input($phone); ?>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label class="font-weight-bold">Kata Sandi <span style="color: red;">*</span></label>
                    <?php echo form_input($password); ?>
                </div>
                <div class="form-group col-md-6">
                    <label class="font-weight-bold">Ulangi Kata Sandi <span style="color: red;">*</span></label>
                    <?php echo form_input($password_confirm); ?>
                </div>
            </div>


            <div class="form-group">
                <label class="font-weight-bold">Survei <span style="color: red;">*</span></label>
                @php
                $user_anak = $ci->db->get_where('users', ['id_parent_induk' => $data_user->id]);
                @endphp
                <div class="row">
                    @foreach($user_anak->result() as $row)
                    <div class="col-md-6">
                        <label class="font-weight-bold"><b>{{$row->first_name . ' ' . $row->last_name}}</b></label>
                        @foreach($ci->db->get_where('manage_survey', array('id_user' => $row->id))->result() as $value)
                        @php   
                        $data_survei = $ci->db->query("SELECT id_manage_survey FROM surveyor_induk WHERE id_user = '".$ci->uri->segment(3)."' AND id_manage_survey = '".$value->id."'")->num_rows();
                        //(isset($data_survei->id_manage_survey)) ? $id_manage_survey = $data_survei->id_manage_survey : $id_manage_survey = '';
                        @endphp
                        <label class="checkbox">
                            <input type="checkbox" name="id_manage_survey[]" value="{{$value->id}}" <?php echo ($data_survei > 0) ? 'checked' : ''; ?>>
                            <span></span> &nbsp; {{$value->survey_name}}
                        </label>
                        @endforeach
                        <br>
                    </div>
                    @endforeach
                </div>
            </div>


            <div class="text-right">
                @php
                echo
                anchor(base_url() .'/pengguna-surveyor',
                'Batal', ['class' => 'btn btn-light-primary font-weight-bold'])
                @endphp
                <?php echo form_submit('submit', 'Simpan', ['class' => 'btn btn-primary font-weight-bold']); ?>
            </div>

            <?php echo form_close(); ?>
        </div>
    </div>

</div>

@endsection

@section('javascript')

@endsection