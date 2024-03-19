@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')

@endsection

@section('content')
<div class="container">
    <div class="card" data-aos="fade-down">
        <div class="card-header bg-secondary font-weight-bold">
            {{ $title }}
        </div>
        <div class="card-body">
            {!! form_open($form_action) !!}
            {!! validation_errors() !!}

            {{-- <div class="form-group row">
                @php
                // echo form_label('<b>Klasifikasi Survey</b> <span style="color: red;">*</span>', '', ['class' => 'col-sm-2 col-form-label']);
                @endphp
                <div class="col-sm-10">
                    @php
                    // echo form_dropdown($id_klasifikasi_survei);
                    @endphp
                </div>
            </div> --}}

            <div class="form-group row">
                {!! form_label('<b>Jenis Pelayanan</b> <span style="color: red;">*</span>', '', ['class' => 'col-sm-2 col-form-label']) !!}
                <div class="col-sm-10">
                    {!! form_input($nama_jenis_pelayanan_responden) !!}
                </div>
            </div>

            <div class="text-right mt-3 mb-3">
                {!! anchor($ci->session->userdata('urlback'), 'Batal', ['class'=>'btn btn-light-primary font-weight-bold shadow']) !!}
                <button type="submit" class="btn btn-primary font-weight-bold shadow">Simpan</button>
            </div>

            {!! form_close() !!}

        </div>
    </div>
</div>
@endsection

@section('javascript')

@endsection