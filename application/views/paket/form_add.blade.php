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
                echo form_label('Nama Paket *', '', ['class' => 'col-sm-2 col-form-label']);
                @endphp
                <div class="col-sm-10">
                    @php
                    echo form_input($nama_paket);
                    @endphp
                </div>
            </div>

            <div class="form-group row">
                @php
                echo form_label('Deskripsi Paket *', '', ['class' => 'col-sm-2 col-form-label']);
                @endphp
                <div class="col-sm-10">
                    @php
                    echo form_input($deskripsi_paket);
                    @endphp
                </div>
            </div>

            <div class="form-group row">
                @php
                echo form_label('Panjang Hari *', '', ['class' => 'col-sm-2 col-form-label']);
                @endphp
                <div class="col-sm-10">
                    <div class="input-group">
                    @php
                    echo form_input($panjang_hari);
                    @endphp
                    <div class="input-group-append"><span class="input-group-text">Hari</span></div>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                @php
                echo form_label('Harga Paket *', '', ['class' => 'col-sm-2 col-form-label']);
                @endphp
                <div class="col-sm-10">
                    <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text">Rp.</span></div>
                    @php
                    echo form_input($harga_paket);
                    @endphp
                    </div>
                </div>
            </div>

            <div class="text-right mt-3 mb-3">
                @php
                echo anchor(base_url().'paket', 'Batal', ['class'=>'btn btn-light-primary font-weight-bold shadow-lg']);
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