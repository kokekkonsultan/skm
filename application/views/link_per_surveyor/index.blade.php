@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')
<link href="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.css"
    rel="stylesheet" type="text/css" />
@endsection

@section('content')

<div class="container-fluid">
    <div class="card">
        <div class="card-header bg-secondary font-weight-bold">
            {{ $title }}
        </div>
        <div class="card-body">
            <div class="text-center font-weight-bold mt-5">
                Bagikan link dibawah ini kepada responden untuk dilakukan pengisian.
            </div>
            <br>
            <div class='input-group'>
                <input class='form-control' id='kt_clipboard_1'
                    value="{{ base_url() }}survei/{{ $manage_survey->slug }}/{{ $data_surveyor->uuid }}" readonly />
                <div class='input-group-append'>
                    <a href='javascript:void(0)' class='btn btn-light-primary' data-clipboard='true'
                        data-clipboard-target='#kt_clipboard_1'><i class='la la-copy'></i> <strong>Copy
                            Link</strong></a>
                </div>
            </div>

            <br>
            <div class="text-center font-weight-bold mt-5">
                Atau gunakan tombol dibawah ini.
            </div>

            <br>

            <div class="text-center">
                <a class="btn btn-primary font-weight-bold shadow btn-block"
                    href="{{ base_url() }}survei/{{ $manage_survey->slug }}/{{ $data_surveyor->uuid }}"
                    target="_blank"><i class="fas fa-link"></i>Link Survei</a>
            </div>
            <br>
            <br>
        </div>
    </div>
</div>

@endsection

@section('javascript')
<script>
"use strict";
// Class definition

var KTClipboardDemo = function() {

    // Private functions
    var demos = function() {
        // basic example
        new ClipboardJS('[data-clipboard=true]').on('success', function(e) {
            e.clearSelection();
            // alert('Copied!');
            toastr["success"]('Link berhasil dicopy, Silahkan paste di browser anda sekarang.');
        });
    }

    return {
        // public functions
        init: function() {
            demos();
        }
    };
}();

jQuery(document).ready(function() {
    KTClipboardDemo.init();
});
</script>
@endsection