@extends('include_backend/_template')

@php
$ci = get_instance();
@endphp

@section('style')
<link rel="dns-prefetch" href="//fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
@endsection

@section('content')


<div class="container mt-5 mb-5" style="font-family: nunito;">
    <div class="text-center" data-aos="fade-up">
        <div id="progressbar" class="mb-5">
            <li class="active" id="account"><strong>Data Responden</strong></li>
            <li class="active" id="personal"><strong>Pertanyaan Survei</strong></li>
            @if($status_saran == 1)
            <li class="active" id="payment"><strong>Saran</strong></li>
            @endif
            <li class="active" id="confirm"><strong>Konfirmasi</strong></li>
            <li id="completed"><strong>Completed</strong></li>
        </div>
    </div>
    <br>
    <br>
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card shadow" data-aos="fade-up" id="kt_blockui_content">

                @if($manage_survey->img_benner == '')
                <img class="card-img-top" src="{{ base_url() }}assets/img/site/page/banner-survey.jpg"
                    alt="new image" />
                @else
                <img class="card-img-top shadow"
                    src="{{ base_url() }}assets/klien/benner_survei/{{$manage_survey->img_benner}}" alt="new image">
                @endif

                <div class="card-body">

                    {{-- @include('include_backend/partials_backend/_tanggal_survei') --}}

                    {!! form_open(base_url() . 'survei/' . $ci->uri->segment(2) . '/form-konfirmasi/' .
                    $ci->uri->segment(4)) !!}

                    </br>

                    <div style="font-size:16px; font-weight:bold;">
                        Kuesioner anda sudah diisi, silahkan klik tombol SUBMIT Kuesioner untuk mengakhiri survey.
                    </div>


                    </br>
                    </br>

                    </br>



                </div>
                <div class="card-footer">

                    <table class="table table-borderless">
                        <tr>
                            <td class="text-left">
                                {!! anchor($url_back, '<i class="fa fa-arrow-left"></i>
                                Lengkapi Kembali',
                                ['class' => 'btn btn-secondary btn-lg font-weight-bold shadow']) !!}
                            </td>
                            <td class="text-right">
                                <a class="btn btn-warning btn-lg font-weight-bold shadow"
                                    href="<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/form-survei/selesai' ?>">Submit
                                    Kuesioner <i class="fa fa-arrow-right"></i></a>
                            </td>
                        </tr>
                    </table>
                </div>
                {!! form_close() !!}
            </div>
        </div>
    </div>
</div>


@endsection

@section('javascript')

@endsection