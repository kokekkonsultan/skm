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
                        <div class="col-xl-2"></div>
                        <div class="col-xl-8">
                            <div class="my-5">

                                <div class="text-center">

                                    <div class="mt-10 mb-10">
                                        Anda bisa menggunakan link survei untuk dibagikan kepada responden di
                                        bawah ini.
                                    </div>

                                    <div class='input-group'>
                                        <input type='text' class='form-control' id='kt_clipboard_1' value="{{ base_url() }}survei/{{ $manage_survey->slug }}" placeholder='Type some value to copy' />
                                        <div class='input-group-append'>
                                            <a href='javascript:void(0)' class='btn btn-light-primary font-weight-bold shadow' data-clipboard='true' data-clipboard-target='#kt_clipboard_1'><i class='la la-copy'></i> Copy Link</a>
                                        </div>
                                    </div>

                                    <div class="mt-10 mb-10">
                                        Atau gunakan tombol dibawah ini.
                                    </div>

                                    @php
                                    echo anchor(base_url().'survei/'. $manage_survey->slug , '<i class="fas fa-globe"></i> Menuju Link Survey', ['class' => 'btn
                                    btn-primary font-weight-bold btn-block shadow-lg', 'target' => '_blank']);
                                    @endphp
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