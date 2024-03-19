@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')

@endsection

@section('content')

<div class="container-fluid">
    <div class="card">
        <div class="card-header bg-light-primary font-weight-bold">
            Prosedur Penggunaan Aplikasi
        </div>
        <div class="card-body">
            <p>
                <i class="fas fa-info-circle"></i> Anda dapat mempelajari aplikasi ini melalui prosedur
                penggunaan aplikasi dibawah ini.
            </p>
            <object type="application/pdf" data="{{ base_url() }}assets/files/prosedur/modul-eskm-2022.pdf" width="100%" height="700">
            </object>
        </div>
    </div>
</div>

@endsection

@section('javascript')

@endsection