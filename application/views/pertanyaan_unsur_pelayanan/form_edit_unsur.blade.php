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
            @php
            echo form_open($form_action);
            @endphp
            @php
            echo validation_errors();
            @endphp


            <div class="form-group row">
                {!! form_label('<b>Unsur Pelayanan</b> <span style="color: red;">*</span>', '', ['class' => 'col-sm-2 col-form-label']) !!}
                <div class="col-sm-10">
                    
                    <div class="input-group">
                     <div class="input-group-prepend"><span class="input-group-text">{{ $nomor_unsur }}</span></div>
                     {!! form_input($nama_unsur_pelayanan) !!}
                    </div>
                    
                </div>
            </div>

            <div class="text-right mt-3 mb-3">
                @php
                echo anchor($ci->session->userdata('urlback_second'), 'Batal', ['class'=>'btn btn-light-primary font-weight-bold shadow']);
                @endphp
                <button type="submit" name="submit" value="simpan" class="btn btn-primary font-weight-bold shadow">Simpan</button>
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