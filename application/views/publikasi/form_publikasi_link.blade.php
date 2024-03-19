@extends('include_frontend/template_frontend')

@php
$ci = get_instance();
@endphp

@section('style')

@endsection

@section('content')
<div class="main-content wow fadeIn" id="top" data-wow-duration="1s" data-wow-delay="0.5s">
    <div id="about" class="about-us section" style="padding-top: 0px;">

        <!-- <div class="card-deck row">
            @foreach ($manage_survey->result() as $value)
            <div class="card col-6 md-3 box-item" style="border-radius: 25px;">
                <a href="<?php echo base_url() . 'survei/' . $value->slug ?>" target="_blank">
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
            @endforeach
        </div> -->

        @foreach ($manage_survey->result() as $value)
        <div class="box-item" style="border-radius: 25px;">
            <a href="<?php echo base_url() . 'survei/' . $value->slug ?>" target="_blank">
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
        @endforeach
    </div>


</div>
@endsection

@section('javascript')

@endsection