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
            @endphp
            @foreach ($query->result() as $value)
            <b>{{ $value->nama_pertanyaan_terbuka }}</b>
            <br>
            <div>{{ $value->isi_pertanyaan_terbuka }}</div>

            <ul>
                @php
                @endphp
                @foreach ($data->result() as $row)
                @if ($value->id_perincian_pertanyaan_terbuka == $row->id_perincian_pertanyaan_terbuka)
                <li>{{ $row->pertanyaan_ganda }}</li>
                @endif

                @php
                @endphp
                @endforeach
            </ul>

            @php
            @endphp
            @foreach ($lainnya->result() as $object)

            @if ($value->id_perincian_pertanyaan_terbuka == $object->id_perincian_pertanyaan_terbuka &&
            $object->lainnya != NULL)

            <div>{{ $object->lainnya }}...</div>

            @endif

            @php
            @endphp
            @endforeach

            <hr>

            @php
            @endphp
            @endforeach
        </div>
    </div>
</div>

@endsection

@section('javascript')

@endsection