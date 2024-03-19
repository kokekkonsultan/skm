@extends('include_frontend/template_frontend')

@php
$ci = get_instance();
@endphp

@section('style')

@endsection

@section('content')
<div class="main-content wow fadeIn" id="top" data-wow-duration="1s" data-wow-delay="0.5s">
    <div id="about" class="about-us section">
        <div class="row">
            @foreach ($manage_survey->result() as $value)
            <div class="col-lg-6">
                <div class="box-item" style="border-radius: 25px;">
                    <a href="<?php echo base_url() . 'validasi-sertifikat/' . $value->uuid ?>" title="">
                        <div class="mt-2 mb-2">
                            <strong style="font-size: 20px;">{{$value->survey_name}}</strong><br>
                            <span class="text-dark">Organisasi yang disurvei : <b>{{$value->organisasi}}</b></span><br>
                            <div class="mt-3 text-dark font-weight-bold" style="font-size: 12px;">
                                Periode Survei : <b>{{date('d-m-Y', strtotime($value->survey_start))}} s/d
                                    {{date('d-m-Y', strtotime($value->survey_end))}}</b>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            @endforeach
        </div>
    </div>


</div>
@endsection

@section('javascript')

@endsection