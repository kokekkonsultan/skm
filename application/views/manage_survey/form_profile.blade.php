@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')

@endsection

@section('content')
<div class=" container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="text-center">
                <i class="flaticon-profile-1 icon-10x"></i>
            </div>
            @include('include_backend/partials_backend/_inc_profile')
        </div>
        <div class="col-md-12 mt-5">
            {{-- @include('include_template.partials._inc_menu_profile')
            <br> --}}

            <div id="response-list-survey">
                <div align="center">
                    <div class="ajax-loader"></div>
                </div>
            </div>

            <!-- <div id="response-list-activity">
                <div align="center"><br><br>
                    <div class="ajax-loader"></div>
                </div>
            </div> -->

            <div id="response-list-campaign">
                <div align="center"><br><br>
                    <div class="ajax-loader"></div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection

@section('javascript')
<script src="{{ TEMPLATE_BACKEND_PATH }}js/pages/features/charts/apexcharts.js"></script>
<!-- Notif -->
{{-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url() . 'assets/sweetalert/sweetalert2.all.min.js' ?>"></script> --}}

<script>
$(document).ready(function() {
    var flash = $('#flash').data('flash');
    console.log(flash);
    if (flash) {
        Swal.fire({
            title: 'Success!',
            text: 'Profil Berhasil di Ubah!',
            icon: 'success'
        })
    }
});

$(function() {

    $.ajax({
        type: "GET",
        url: "{{ base_url() }}{{ $ci->session->userdata('username') }}/overview/list-survey",
        dataType: "html",
        success: function(response) {
            $("#response-list-survey").html(response);
        }

    });

});

$(function() {

    $.ajax({
        type: "GET",
        url: "{{ base_url() }}{{ $ci->session->userdata('username') }}/overview/list-activity",
        dataType: "html",
        success: function(response) {
            $("#response-list-activity").html(response);
        }

    });

});

$(function() {

    $.ajax({
        type: "GET",
        url: "{{ base_url() }}{{ $ci->session->userdata('username') }}/overview/list-campaign",
        dataType: "html",
        success: function(response) {
            $("#response-list-campaign").html(response);
        }

    });

});
</script>

@endsection