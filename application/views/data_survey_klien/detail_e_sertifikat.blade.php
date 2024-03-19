@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')

@endsection

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">


            @include('data_survey_klien/menu_data_survey_klien')

        </div>
        <div class="col-md-9">
            <div class="card" data-aos="fade-down" data-aos-delay="300">
                <div class="card-header">
                    {{ $title }}
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header bg-secondary font-weight-bold">
                                    QR Code
                                </div>
                                <div class="card-body text-center">
                                    <img src="<?php echo base_url() ?>assets/klien/qr_code/<?php echo $manage_survey->qr_code ?>" height="130" alt="" class="shadow">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header bg-secondary font-weight-bold">
                                    Link Validasi
                                </div>
                                <div class="card-body">
                                    <div class='input-group'>
                                        <input type='text' class='form-control' id='kt_clipboard_1' value="{{ base_url() }}validasi-sertifikat/{{$manage_survey->uuid}}" placeholder='Type some value to copy' />
                                        <div class='input-group-append'>
                                            <a href='javascript:void(0)' class='btn btn-light-primary font-weight-bold shadow' data-clipboard='true' data-clipboard-target='#kt_clipboard_1'><i class='la la-copy'></i> Copy
                                                Link</a>
                                        </div>
                                    </div>

                                    <div class="mt-5 mb-5 text-center">
                                        Atau gunakan tombol dibawah ini.
                                    </div>
                                    <div class="text-center">
                                        <a class="btn btn-primary" href="{{ base_url() }}validasi-sertifikat/{{$manage_survey->uuid}}" target="_blank"><i class="fas fa-globe"></i>
                                            Link Validasi</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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