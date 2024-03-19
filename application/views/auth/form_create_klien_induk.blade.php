@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')

@endsection

@section('content')

<div class="container">

    <div class="row justify-content-md-center">
        <div class="col col-lg-9 mt-5">
            {!! form_open("pengguna-klien-induk/create"); !!}


            <div class="card">
                <div class="card-header font-weight-bold">
                    Data PIC Klien
                </div>
                <div class="card-body">
                    <div id="infoMessage text-danger">{!! $message; !!}</div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Nama Depan <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            {!! form_input($first_name); !!}
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Nama Belakang <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            {!! form_input($last_name); !!}
                        </div>
                    </div>

                    @if ($identity_column !== 'email')
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Username <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            {!! form_error('identity'); !!}
                            {!! form_input($identity); !!}
                        </div>
                    </div>
                    @endif

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Organisasi <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            {!! form_input($company); !!}
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Email <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            {!! form_input($email); !!}
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">HP <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            {!! form_input($phone); !!}
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Password <span class="text-danger">*</span></label>
                        <div class="col-sm-9">

                            <div class="input-group">
                                {!! form_input($password); !!}
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fa fa-eye"></i></span>
                                </div>
                            </div>

                            <a class="text-primary font-weight-bold mt-3 mb-5" data-toggle="modal" title="Generate Password" onclick="showuserdetail(1)" href="#exampleModal"><i class="fas fa-key text-primary"></i> Generate Password</a>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Ulangi Password <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            {!! form_input($password_confirm); !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-5">
                <div class="card-body">

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label font-weight-bold">Cakupan Induk
                            <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <div class="checkbox-list">
                                @foreach($ci->db->query("SELECT * FROM users
                                JOIN users_groups ON users.id = users_groups.user_id
                                WHERE group_id = 2 && users.id_parent_induk = 0")->result() as $row)
                                <label class="checkbox">
                                    <input type="checkbox" name="cakupan[]" value="{{$row->user_id}}">
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
                shadow-lg']); !!}
                {!! form_submit('submit', 'Create Klien', ['class' => 'btn btn-primary font-weight-bold shadow-lg']);
                !!}
            </div>

            {!! form_close(); !!}
        </div>
    </div>
</div>

@endsection

@section('javascript')

@endsection