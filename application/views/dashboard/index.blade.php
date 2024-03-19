@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')
<link href="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.css" rel="stylesheet"
    type="text/css" />
@endsection

@section('content')

<div class="container-fluid">



    @php
    $user_id = $ci->session->userdata('user_id');
    $user_now = $ci->ion_auth->user($user_id)->row();
    @endphp

    <div class="card card-custom card-stretch-half gutter-b overflow-hidden">
                <div class="card-body d-flex p-0">
                    <div class="flex-grow-1 p-12 card-rounded bgi-no-repeat d-flex flex-column justify-content-center align-items-start"
                        style="background-color: #1B283F; background-position: right bottom; background-size: 100% auto; background-image:url(/assets/img/banner/rhone-2.svg)">
                        <h1 class="font-weight-bolder text-white mb-2">Halo, {{ $user_now->first_name }}
                            {{ $user_now->last_name }}
                        </h1>
                        <div class="font-size-h6 text-white mb-8">Selamat memulai aktifitas anda kembali</div>

                    </div>
                </div>
            </div>


    @php
    $group = 'admin';
    @endphp
    @if ($ci->ion_auth->in_group($group))

    @endif


    @php
    $group = 'client_induk';
    @endphp
    @if ($ci->ion_auth->in_group($group))

    <span class="mb-5">
        <div id="chart-survei-induk">
            <div align="center">
                <img src="{{ base_url() }}assets/img/ajax/preloader.gif" alt="" width="80px">
            </div>
        </div>
    </span>

    <span class="m-5 mb-5">
        <div id="tabel-survei-induk">
            <div align="center">
                <img src="{{ base_url() }}assets/img/ajax/preloader.gif" alt="" width="80px">
            </div>
        </div>
    </span>
    @endif





    @php
    $group = 'client';
    @endphp
    @if ($ci->ion_auth->in_group($group))

    <span class="mb-5">
    <div id="chart-survei">
                <div align="center">
                    <img src="{{ base_url() }}assets/img/ajax/preloader.gif" alt="" width="80px">
                </div>
            </div>
    </span>

    <span class="mb-5">
        <div id="tabel-survei">
            <div align="center">
                <img src="{{ base_url() }}assets/img/ajax/preloader.gif" alt="" width="80px">
            </div>
        </div>
    </span>

    <!-- <div class="row">
        <div class="col-xl-12">
        
        </div>

        <div class="col-xl-4">

            <div id="response-list-activity">
                <div align="center"><br><br>
                    <img src="{{ base_url() }}assets/img/ajax/preloader.gif" alt="" width="80px">
                </div>
            </div>

        </div>
    </div> -->

    <!-- <span class="m-5 mb-5">
        <div id="jumlah-survei">
            <div align="center">
                <img src="{{ base_url() }}assets/img/ajax/preloader.gif" alt="" width="80px">
            </div>
        </div>
    </span> -->
    @endif




</div>

@endsection

@section('javascript')

<script src="https://cdn.fusioncharts.com/fusioncharts/latest/fusioncharts.js"></script>
<script src="{{ base_url() }}assets/vendor/fusioncharts-suite-xt/js/themes/fusioncharts.theme.accessibility.js">
</script>
<script src="{{ base_url() }}assets/vendor/fusioncharts-suite-xt/js/themes/fusioncharts.theme.candy.js"></script>
<script src="{{ base_url() }}assets/vendor/fusioncharts-suite-xt/js/themes/fusioncharts.theme.carbon.js"></script>
<script src="{{ base_url() }}assets/vendor/fusioncharts-suite-xt/js/themes/fusioncharts.theme.fint.js"></script>
<script src="{{ base_url() }}assets/vendor/fusioncharts-suite-xt/js/themes/fusioncharts.theme.fusion.js"></script>
<script src="{{ base_url() }}assets/vendor/fusioncharts-suite-xt/js/themes/fusioncharts.theme.gammel.js"></script>
<script src="{{ base_url() }}assets/vendor/fusioncharts-suite-xt/js/themes/fusioncharts.theme.ocean.js"></script>
<script src="{{ base_url() }}assets/vendor/fusioncharts-suite-xt/js/themes/fusioncharts.theme.umber.js"></script>
<script src="{{ base_url() }}assets/vendor/fusioncharts-suite-xt/js/themes/fusioncharts.theme.zune.js"></script>


<style type="text/css">
[pointer-events="bounding-box"] {
    display: none
}
</style>



<script src="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.js"></script>
<script src="{{ base_url() }}assets/themes/metronic/assets/js/pages/features/charts/apexcharts.js"></script>

<script>
jQuery(function($) {
    $('#selMenu').on('change', function() {
        var url = "{{ $ci->session->userdata('username') }}/" + $(this).val();
        if (url) {
            window.location = url;
        }
        return false;
    });
});


$(document).ready(function() {
    $("#selMenu").select2({
        placeholder: 'Cari yang anda perlukan disini',
        theme: "bootstrap4",
        // minimumInputLength: 5,
        multiple: false,
        // separator: '|',
        allowClear: true,
        ajax: {
            url: "{{ base_url() }}get-menu",
            type: "post",
            dataType: 'json',
            delay: 250,
            cache: true,
            data: function(params) {
                return {
                    searchTerm: params.term, // search term
                    //type: 'public'
                    page: params.page || 1
                };
            },
            processResults: function(data, params) {
                console.log(data);
                var page = params.page || 1;
                return {
                    results: data,
                    "pagination": {
                        more: (page * 10) <= data[0].total_count
                    }
                };
            },
        },
    });
});

$(function() {
    $.ajax({
        type: "GET",
        url: "{{ base_url() }}dashboard/jumlah-survei",
        dataType: "html",
        success: function(response) {
            $("#jumlah-survei").html(response);
        },
    });
});

$(function() {
    $.ajax({
        type: "GET",
        url: "{{ base_url() }}{{ $ci->session->userdata('username') }}/dashboard/chart-survei",
        dataType: "html",
        success: function(response) {
            $("#chart-survei").html(response);
        },
    });

});

$(function() {

    $.ajax({
        type: "GET",
        url: "{{ base_url() }}{{ $ci->session->userdata('username') }}/dashboard/tabel-survei",
        dataType: "html",
        success: function(response) {
            $("#tabel-survei").html(response);
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
        url: "{{ base_url() }}dashboard/chart-survei-induk",
        dataType: "html",
        success: function(response) {
            $("#chart-survei-induk").html(response);
        },
        // error: function(data) {
        //     alert("Error Request Found");
        // }
    });

});

$(function() {

    $.ajax({
        type: "GET",
        url: "{{ base_url() }}dashboard/tabel-survei-induk",
        dataType: "html",
        success: function(response) {
            $("#tabel-survei-induk").html(response);
        },
        // error: function(data) {
        //     alert("Error Request Found");
        // }
    });

});
</script>


@php
$group = 'admin';
@endphp
@if ($ci->ion_auth->in_group($group))

@endif

@php
$group = 'client';
@endphp
@if ($ci->ion_auth->in_group($group))

@endif

<script src="{{ TEMPLATE_BACKEND_PATH }}js/pages/features/charts/apexcharts.js"></script>

{{-- <script>
       var options = {
          series: [{
          name: 'Sales',
          data: [4, 3, 10, 9, 29, 19, 22, 9, 12, 7, 19, 5, 13, 9, 17, 2, 7, 5]
        }],
          chart: {
          height: 350,
          type: 'line',
        },
        forecastDataPoints: {
          count: 7
        },
        stroke: {
          width: 5,
          curve: 'smooth'
        },
        xaxis: {
          type: 'datetime',
          categories: ['1/11/2000', '2/11/2000', '3/11/2000', '4/11/2000', '5/11/2000', '6/11/2000', '7/11/2000', '8/11/2000', '9/11/2000', '10/11/2000', '11/11/2000', '12/11/2000', '1/11/2001', '2/11/2001', '3/11/2001','4/11/2001' ,'5/11/2001' ,'6/11/2001'],
          tickAmount: 10,
          labels: {
            formatter: function(value, timestamp, opts) {
              return opts.dateFormatter(new Date(timestamp), 'dd MMM')
            }
          }
        },
        title: {
          text: 'Forecast',
          align: 'left',
          style: {
            fontSize: "16px",
            color: '#666'
          }
        },
        fill: {
          type: 'gradient',
          gradient: {
            shade: 'dark',
            gradientToColors: [ '#FDD835'],
            shadeIntensity: 1,
            type: 'horizontal',
            opacityFrom: 1,
            opacityTo: 1,
            stops: [0, 100, 100, 100]
          },
        },
        yaxis: {
          min: -10,
          max: 40
        }
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
</script> --}}
@endsection
