@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')

@endsection

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header bg-secondary font-weight-bold">
            {{ $title }}
        </div>
        <div class="card-body">
            @php
            echo form_open($form_action);
            @endphp
            @php
            echo validation_errors();
            @endphp

            <div class="form-group row">
                @php
                echo form_label('Nama Lengkap *', '', ['class' => 'col-sm-2 col-form-label']);
                @endphp
                <div class="col-sm-10">
                    @php
                    echo form_input($nama_lengkap);
                    @endphp
                </div>
            </div>

            <div class="form-group row">
                @php
                echo form_label('Alamat *', '', ['class' => 'col-sm-2 col-form-label']);
                @endphp
                <div class="col-sm-10">
                    @php
                    echo form_input($alamat);
                    @endphp
                </div>
            </div>

            <div class="form-group row">
                @php
                echo form_label('Email *', '', ['class' => 'col-sm-2 col-form-label']);
                @endphp
                <div class="col-sm-10">
                    @php
                    echo form_input($email);
                    @endphp
                </div>
            </div>

            <div class="form-group row">
                @php
                echo form_label('Whatsapp *', '', ['class' => 'col-sm-2 col-form-label']);
                @endphp
                <div class="col-sm-10">
                    @php
                    echo form_input($whatsapp);
                    @endphp
                </div>
            </div>

            <div class="form-group row">
                @php
                echo form_label('Keterangan *', '', ['class' => 'col-sm-2 col-form-label']);
                @endphp
                <div class="col-sm-10">
                    @php
                    echo form_input($keterangan);
                    @endphp
                </div>
            </div>


            <div class="text-right mt-3 mb-3">
                @php
                echo anchor(base_url().'prospek-surveyor', 'Batal', ['class'=>'btn btn-light-primary
                font-weight-bold']);
                @endphp
                <button type="submit" class="btn btn-primary font-weight-bold">Simpan</button>
            </div>

            @php
            echo form_close();
            @endphp

        </div>
    </div>
</div>
@endsection

@section('javascript')

@endsection