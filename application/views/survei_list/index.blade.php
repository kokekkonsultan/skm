@php
$ci = get_instance();
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <base href="">
    <meta charset="utf-8" />
    <title>{{ $title . ' - ' . strtoupper($penayang_survei->nama_label)}}</title>
    <meta name="description"content="Survei Kepuasan Masyarakat" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="canonical" href="https://survei-kepuasan.com" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <link href="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/fullcalendar/fullcalendar.bundle.css" rel="stylesheet"
        type="text/css" />
    <link href="{{ TEMPLATE_BACKEND_PATH }}plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
    <link href="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/prismjs/prismjs.bundle.css" rel="stylesheet"
        type="text/css" />
    <link href="{{ TEMPLATE_BACKEND_PATH }}css/style.bundle.css" rel="stylesheet" type="text/css" />
    <link href="{{ TEMPLATE_BACKEND_PATH }}css/themes/layout/header/base/light.css" rel="stylesheet" type="text/css" />
    <link href="{{ TEMPLATE_BACKEND_PATH }}css/themes/layout/header/menu/light.css" rel="stylesheet" type="text/css" />
    <link href="{{ TEMPLATE_BACKEND_PATH }}css/themes/layout/brand/dark.css" rel="stylesheet" type="text/css" />
    <link href="{{ TEMPLATE_BACKEND_PATH }}css/themes/layout/aside/dark.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ VENDOR_PATH }}aos/aos.css">
    <link rel="shortcut icon" href="{{ base_url() }}assets/img/site/logo/favicon.ico" />
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <style>
    body {
        background-image: url("{{ base_url() }}assets/img/bg/main-bg.jpg");
        padding-right: 0 !important;
        background-repeat: repeat;
    }


    .outer-box {
        font-size: 24px;
        width: 100%;
        height: 100px;
        padding: 2px;
    }

    .box-edge-logo {
        font-size: 14px;
        width: 110px;
        height: 110px;
        padding: 8px;
        float: left;
        text-align: center;
    }

    .box-edge-text {
        font-size: 14px;
        width: 92%;
        height: 110px;
        padding: 8px;
        /* float: left; */
    }


    .box-title {
        font-size: 15px;
        font-weight: bold;
    }

    .box-desc {
        margin-top: 5px;
        font-size: 12px;
        /* font-weight: bold; */
    }

    .conatiner-btn {
        width: 100%;
        margin-top: 10px;
        margin-bottom: 50px;
    }

    .btn-left {
        float: left;
        width: 50%;
    }

    .btn-right {
        float: left;
        width: 50%;
        text-align: right;
    }

    @media screen and (max-width: 600px) {
        .outer-box {
            float: none;
            margin: 0 auto;
            height: 225px;
        }

        .box-edge-logo {
            float: none;
            margin: 0 auto;
            text-align: center;
        }

        .box-edge-text {
            float: none;
            margin: 0 auto;
            text-align: center;
        }
    }

    @media screen and (max-width: 992px) {
        .outer-box {
            float: none;
            margin: 0 auto;
            height: 225px;
        }

        .box-edge-logo {
            float: none;
            margin: 0 auto;
            text-align: center;
        }

        .box-edge-text {
            float: none;
            margin: 0 auto;
            text-align: center;
        }
    }
    </style>
</head>

<body id="kt_body"
    class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-fixed aside-minimize-hoverable">


    <br>
    <br>
    <br>

    <!-- CONTENT -->
    <div class="container mt-5 mb-5" style="font-family: nunito;">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card shadow" data-aos="fade-up">
                    @if($penayang_survei->img_benner == '')
                    <img class="card-img-top" src="{{ base_url() }}assets/img/site/page/banner-survey.jpg"
                        alt="new image" />
                    @else
                    <img class="card-img-top shadow"
                        src="{{ base_url() }}assets/klien/benner_penayang/{{$penayang_survei->img_benner}}"
                        alt="new image">
                    @endif

                    <div class="card-body">
                        <div class="mb-8">
                            {!! $penayang_survei->kata_pembuka !!}
                        </div>



                        @if($penayang_survei->jenis_penayang == 1)
                        <div class="row mt-5 mb-5">
                            @foreach($manage_survey->result() as $row)
                            <div class="col-sm-6 col-lg-6 mb-3">
                                <a class="card card-body mb-3 h-100 text-warning shadow wave wave-animate-slow wave-warning"
                                    href="<?php echo base_url() . 'survei/' . $row->slug ?>">
                                    <div class="text-center font-weight-bold">
                                        <i class="fa fa-link mb-3 text-warning" style="font-size: 20px;"></i><br>
                                        <strong style="font-size: 13px;"><?php echo $row->survey_name ?></strong>
                                        <br>
                                        <small class="text-dark"><b>Periode Survei :
                                                <?php echo date("d-m-Y", strtotime($row->survey_start)) . ' s/d ' . date("d-m-Y", strtotime($row->survey_end)) ?></b></small>
                                    </div>
                                </a>
                            </div>
                            @endforeach
                        </div>
                        @else

                        <div class="mt-5 mb-5">
                            @foreach($manage_survey->result() as $value)
                            <a href="<?php echo base_url() . 'survei/' . $value->slug ?>" title="" class="text-warning">
                                <div class="card card-body mb-5 shadow  wave wave-animate-slow wave-warning">
                                    <div class="d-flex align-items-center">
                                        <span class="bullet bullet-bar bg-warning align-self-stretch"></span>
                                        <label
                                            class="checkbox checkbox-lg checkbox-light-warning checkbox-inline flex-shrink-0 m-0 mx-4">
                                            <input type="checkbox" value="1" disabled>
                                            <span></span>
                                        </label>
                                        <div class="d-flex flex-column flex-grow-1">
                                            <div class="row">
                                                <div class="col sm-12">
                                                    <strong
                                                        style="font-size: 15px;"><?php echo $value->survey_name ?></strong><br>
                                                    <span class="text-dark" style="font-size: 11px;"><b>Periode Survei :
                                                            <?php echo date("d-m-Y", strtotime($value->survey_start)) . ' s/d ' . date("d-m-Y", strtotime($value->survey_end)) ?></b></span><br />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            @endforeach
                        </div>
                        @endif


                        <div class="mt-8">
                            {!! $penayang_survei->kata_penutup !!}
                        </div>

                    </div>
                </div>
                <br>
                <br>
            </div>
        </div>
    </div>





    <script src="{{ VENDOR_PATH }}jquery/jquery-3.6.0.min.js"></script>
    <script src="{{ TEMPLATE_BACKEND_PATH }}plugins/global/plugins.bundle.js"></script>
    <script src="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/prismjs/prismjs.bundle.js"></script>
    <script src="{{ TEMPLATE_BACKEND_PATH }}js/scripts.bundle.js"></script>
    <script src="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/fullcalendar/fullcalendar.bundle.js"></script>
    <script src="{{ TEMPLATE_BACKEND_PATH }}js/pages/widgets.js"></script>
    <script src="{{ VENDOR_PATH }}aos/aos.js"></script>
    <script>
    AOS.init();
    </script>

    <script>
    var KTAppSettings = {
        "breakpoints": {
            "sm": 576,
            "md": 768,
            "lg": 992,
            "xl": 1200,
            "xxl": 1400
        },
        "colors": {
            "theme": {
                "base": {
                    "white": "#ffffff",
                    "primary": "#3699FF",
                    "secondary": "#E5EAEE",
                    "success": "#1BC5BD",
                    "info": "#8950FC",
                    "warning": "#FFA800",
                    "danger": "#F64E60",
                    "light": "#E4E6EF",
                    "dark": "#181C32"
                },
                "lig  ht": {
                    "white": "#ffffff",
                    "primary": "#E1F0FF",
                    "secondary": "#EBEDF3",
                    "success": "#C9F7F5",
                    "info": "#EEE5FF",
                    "warning": "#FFF4DE",
                    "danger": "#FFE2E5",
                    "light": "#F3F6F9",
                    "dark": "#D6D6E0"
                },
                "inv erse": {
                    "white": "#ffffff",
                    "primary": "#ffffff",
                    "secondary": "#3F4254",
                    "success": "#ffffff",
                    "info": "#ffffff",
                    "warning": "#ffffff",
                    "danger": "#ffffff",
                    "light": "#464E5F",
                    "dark": "#ffffff"
                }
            },
            "gra   y": {
                "gray-100": "#F3F6F9",
                "gray-200": "#EBEDF3",
                "gray-300": "#E4E6EF",
                "gray-400": "#D1D3E0",
                "gray-500": "#B5B5C3",
                "gray-600": "#7E8299",
                "gray-700": "#5E6278",
                "gray-800": "#3F4254",
                "gray-900": "#181C32"
            }
        },
        "font-family": "Poppins"
    };
    </script>
    <script src="{{ base_url() }}assets/themes/metronic/assets/plugins/global/plugins.bundle.js"></script>
    <script src="{{ base_url() }}assets/themes/metronic/assets/plugins/custom/prismjs/prismjs.bundle.js"></script>
    <script src="{{ base_url() }}assets/themes/metronic/assets/js/scripts.bundle.js"></script>
    <script src="{{ base_url() }}assets/plugins/wow/wow.min.js"></script>
    <script>
    new WOW().init();
    </script>
</body>

</html>