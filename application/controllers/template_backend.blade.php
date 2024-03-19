@php
$ci = get_instance();
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <base href="">
    <meta charset="utf-8" />
    <title></title>
    <meta name="description" content="Surveiku." />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="canonical" href="https://www.surveiku.com" />
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
    <link rel="shortcut icon" type="image/png" href="{{ base_url() }}assets/img/site/content/favicon.png"/>
    <style>
    .ajax-loader {
        border: 3px solid #f3f3f3;
        /* Light grey */
        border-top: 3px solid Crimson;
        /* Blue */
        border-radius: 50%;
        width: 30px;
        height: 30px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    @media (min-width: 992px) {
        .header-fixed.subheader-fixed.subheader-enabled .wrapper {
            padding-top: 80px;
        }
    }
    </style>
    @yield('style')
</head>


@if ($ci->session->userdata('aside_minimize') == 1)
@php
$body = "aside-minimize";
@endphp
@else
@php
$body = "";
@endphp
@endif

@php
$cek_user_id = $ci->session->userdata('user_id');
$cek_user_now = $ci->ion_auth->user($cek_user_id)->row();
if ($ci->ion_auth->in_group('client_induk')){
    $cek_data_langganan = $ci->db->query("SELECT tanggal_mulai, tanggal_selesai FROM berlangganan WHERE id_user = '$cek_user_now->id' AND id_produk = '9' ORDER BY id DESC")->row();
}else{
    $cek_data_langganan = $ci->db->query("SELECT tanggal_mulai, tanggal_selesai FROM berlangganan WHERE id_user = '$cek_user_now->id_parent_induk' AND id_produk = '9' ORDER BY id DESC")->row();
}
if ((strtotime($cek_data_langganan->tanggal_mulai) <= time() AND time() >= strtotime($cek_data_langganan->tanggal_selesai))) {
    redirect('http://192.168.1.60:2241/', 'refresh');
}
@endphp

<body id="kt_body"
    class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-fixed aside-minimize-hoverable {{ $body }}">

    <div id="kt_header_mobile" class="header-mobile align-items-center header-mobile-fixed">
        <a href="{{ base_url() }}dashboard">
            <img alt="Logo" src="{{ base_url() }}assets/img/site/logo/logo-dark2.png" />
        </a>
        <div class="d-flex align-items-center">
            <button class="btn p-0 burger-icon burger-icon-left" id="kt_aside_mobile_toggle">
                <span></span>
            </button>
            <button class="btn p-0 burger-icon ml-4" id="kt_header_mobile_toggle">
                <span></span>
            </button>
            <button class="btn btn-hover-text-primary p-0 ml-2" id="kt_header_mobile_topbar_toggle">
                <span class="svg-icon svg-icon-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                        height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <polygon points="0 0 24 0 24 24 0 24" />
                            <path
                                d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z"
                                fill="#000000" fill-rule="nonzero" opacity="0.3" />
                            <path
                                d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z"
                                fill="#000000" fill-rule="nonzero" />
                        </g>
                    </svg>
                </span>
            </button>
        </div>
    </div>
    <div class="d-flex flex-column flex-root">
        <div class="d-flex flex-row flex-column-fluid page">
            <div class="aside aside-left aside-fixed d-flex flex-column flex-row-auto" id="kt_aside">
                <div class="brand flex-column-auto" id="kt_brand">
                    <a href="{{ base_url() }}dashboard" class="brand-logo">
                        <img alt="Logo" src="{{ base_url() }}assets/img/site/logo/logo-dark2.png" />
                    </a>
                    <button class="brand-toggle btn btn-sm px-0" type="button" id="kt_aside_toggle"
                        onclick="aside_set(<?php echo $ci->session->userdata('aside_minimize') ?>)">
                        <span class="svg-icon svg-icon svg-icon-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <polygon points="0 0 24 0 24 24 0 24" />
                                    <path
                                        d="M5.29288961,6.70710318 C4.90236532,6.31657888 4.90236532,5.68341391 5.29288961,5.29288961 C5.68341391,4.90236532 6.31657888,4.90236532 6.70710318,5.29288961 L12.7071032,11.2928896 C13.0856821,11.6714686 13.0989277,12.281055 12.7371505,12.675721 L7.23715054,18.675721 C6.86395813,19.08284 6.23139076,19.1103429 5.82427177,18.7371505 C5.41715278,18.3639581 5.38964985,17.7313908 5.76284226,17.3242718 L10.6158586,12.0300721 L5.29288961,6.70710318 Z"
                                        fill="#000000" fill-rule="nonzero"
                                        transform="translate(8.999997, 11.999999) scale(-1, 1) translate(-8.999997, -11.999999)" />
                                    <path
                                        d="M10.7071009,15.7071068 C10.3165766,16.0976311 9.68341162,16.0976311 9.29288733,15.7071068 C8.90236304,15.3165825 8.90236304,14.6834175 9.29288733,14.2928932 L15.2928873,8.29289322 C15.6714663,7.91431428 16.2810527,7.90106866 16.6757187,8.26284586 L22.6757187,13.7628459 C23.0828377,14.1360383 23.1103407,14.7686056 22.7371482,15.1757246 C22.3639558,15.5828436 21.7313885,15.6103465 21.3242695,15.2371541 L16.0300699,10.3841378 L10.7071009,15.7071068 Z"
                                        fill="#000000" fill-rule="nonzero" opacity="0.3"
                                        transform="translate(15.999997, 11.999999) scale(-1, 1) rotate(-270.000000) translate(-15.999997, -11.999999)" />
                                </g>
                            </svg>
                        </span>
                    </button>
                </div>

                @include('include_backend/partials_backend/_aside_menu')

            </div>
            <div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">
                @include('include_backend/partials_backend/_header')
                <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
                    {{-- @include('include_backend/partials_backend/_subheader') --}}


                    <div class="container-fluid">@include('include_backend/partials_backend/_message')</div>
                    <div class="d-flex flex-column-fluid">
                        @yield('content')
                    </div>

                </div>
                @include('include_backend/partials_backend/_footer')
            </div>

        </div>

    </div>

    @include('include_backend/partials_backend/_user_panel')
    @include('include_backend/partials_backend/_scrool_top')



    <div id="kt_demo_panel" class="offcanvas offcanvas-right p-10">

    </div>

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
                "light": {
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
                "inverse": {
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
            "gray": {
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

    <script src="{{ TEMPLATE_BACKEND_PATH }}plugins/global/plugins.bundle.js"></script>
    <script src="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/prismjs/prismjs.bundle.js"></script>
    <script src="{{ TEMPLATE_BACKEND_PATH }}js/scripts.bundle.js"></script>
    <script src="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/fullcalendar/fullcalendar.bundle.js"></script>
    <script src="{{ TEMPLATE_BACKEND_PATH }}js/pages/widgets.js"></script>
    <script src="{{ VENDOR_PATH }}aos/aos.js"></script>
    <script>
    var txt = "{{ $title }}";
    var speed = 300;
    var refresh = null;

    function action() {
        document.title = txt;
        txt = txt.substring(1, txt.length) + txt.charAt(0);
        refresh = setTimeout("action()", speed);
    }
    action();
    </script>
    <script>
    function user_logout() {
        Swal.fire({
            title: 'Apakah anda akan logout ?',
            icon: 'question',
            showCancelButton: true,
            cancelButtonText: 'Batal',
            confirmButtonColor: 'RoyalBlue',
            cancelButtonColor: 'Wheat',
            confirmButtonText: 'Ya',
            allowOutsideClick: false
        }).then((result) => {
            if (result.value) {

                $.ajax({
                    url: "{{ base_url() }}user-logout",
                    type: "POST",
                    dataType: "JSON",
                    beforeSend: function() {
                        Swal.fire({
                            title: 'Memproses..',
                            html: 'Mohon tunggu sebentar. Sistem sedang melakukan request anda.',
                            allowOutsideClick: false,
                            onOpen: () => {
                                swal.showLoading()
                            }
                        });
                    },
                    success: function(data) {
                        if (data.status) {

                            window.location.href = "{{ base_url() }}Auth/login";

                        } else {

                        }


                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert('Error logout');
                    }
                });

            }
        });

    }
    </script>
    @yield('javascript')

    <script>
    function aside_set(id) {
        if (id == 2) {
            document.getElementById('photo_profile').style.display = 'none';
        }

        $.ajax({
            type: 'POST',
            dataType: 'JSON',
            url: "{{ base_url() }}auth/update-aside/" + id,
            beforeSend: function() {

            },
            success: function(data) {
                // var data = eval(data);
                message = data.message;
                success = data.success;
                toastr["success"](message);
                window.setTimeout(function() {
                    location.reload()
                }, 800);
            }
        });
    }
    </script>
    <script>
    AOS.init();
    </script>
</body>

</html>
