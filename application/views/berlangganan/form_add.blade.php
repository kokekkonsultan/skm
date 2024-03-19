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
                echo form_label('Klien *', '', ['class' => 'col-sm-2 col-form-label']);
                @endphp
                <div class="col-sm-10">
                    @php
                    echo form_dropdown($id_user);
                    @endphp
                </div>
            </div>

            <div class="form-group row">
                @php
                echo form_label('Paket Langganan *', '', ['class' => 'col-sm-2 col-form-label']);
                @endphp
                <div class="col-sm-10">
                    @php
                    echo form_dropdown($id_paket);
                    @endphp
                </div>
            </div>

            <div class="form-group row">
                @php
                echo form_label('Metode Pembayaran *', '', ['class' => 'col-sm-2 col-form-label']);
                @endphp
                <div class="col-sm-10">
                    @php
                    echo form_dropdown($id_metode_pembayaran);
                    @endphp
                </div>
            </div>

            <div class="form-group row">
                @php
                echo form_label('Tanggal Mulai Berlangganan *', '', ['class' => 'col-sm-2 col-form-label']);
                @endphp
                <div class="col-sm-10">
                    @php
                    echo form_input($tanggal_mulai);
                    @endphp
                </div>
            </div>

            <div class="text-right mt-3 mb-3">
                @php
                echo anchor(base_url().'berlangganan', 'Batal', ['class'=>'btn btn-light-primary font-weight-bold shadow-lg']);
                @endphp
                <button type="submit" class="btn btn-primary font-weight-bold shadow-lg">Simpan</button>
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