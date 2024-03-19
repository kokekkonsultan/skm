@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')

@endsection

@section('content')

<div class="container">

    <h5>Jenis Kelamin</h5>
    <div class="card mb-5">
        <div class="card-body">
            @php
            echo $table_jenis_kelamin;
            @endphp
        </div>
    </div>

    <h5>Umur</h5>
    <div class="card mb-5">
        <div class="card-body">
            @php
            echo $table_umur;
            @endphp
        </div>
    </div>

    <h5>Pendidikan Terakhir</h5>
    <div class="card mb-5">
        <div class="card-body">
            @php
            echo $table_pendidikan_akhir;
            @endphp
        </div>
    </div>

    <h5>Pekerjaan Utama</h5>
    <div class="card mb-5">
        <div class="card-body">
            @php
            echo $table_pekerjaan_utama;
            @endphp
        </div>
    </div>

    <h5>Pembiayaan</h5>
    <div class="card mb-5">
        <div class="card-body">
            @php
            echo $table_pembiayaan;
            @endphp
        </div>
    </div>

    <h5>Status Responden</h5>
    <div class="card mb-5">
        <div class="card-body">
            @php
            echo $table_status_responden;
            @endphp
        </div>
    </div>

    <h5>Jenis Pelayanan</h5>
    <div class="card mb-5">
        <div class="card-body">
            @php
            echo $table_jenis_pelayanan;
            @endphp
        </div>
    </div>
</div>

@endsection

@section('javascript')

@endsection