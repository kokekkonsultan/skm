<!DOCTYPE html>
<html lang="en">

<head>

    @php
    $slug = $ci->uri->segment(2);
    $manage_survey = $ci->db->get_where("manage_survey", array('slug' => "$slug"))->row();
    $is_question = $manage_survey->is_question;

    $title_header = unserialize($manage_survey->title_header_survey);
    $title_1 = $title_header[0];
    $title_2 = $title_header[1];
    @endphp


    <base href="">
    <meta charset="utf-8" />
    <title>{{ $title }}</title>
    <meta name="description" content="{{$manage_survey->survey_name}}" />
    {{-- <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" /> --}}
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="canonical" href="https://keenthemes.com/metronic" />
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
    <link rel="icon" type="image/png" href="{{ base_url() }}assets/img/site/logo/favicon.png">
    @yield('style')
    <style>
    body {
        background-image: url("{{ base_url() }}assets/img/bg/main-bg.jpg");
        /* background-image: url("{{ base_url() }}assets/img/bg/bg-3.jpg");
      background-image: url("{{ TEMPLATE_BACKEND_PATH }}media/bg/bg-3.jpg"); */
        padding-right: 0 !important;
        background-repeat: repeat;
    }

    /* .modal-open {
        padding-right: 0px !important;
    }

    .modal {
        padding-right: 0px !important;
    }

    body:not(.modal-open) {
        padding-right: 0px !important;
    } */


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

    #progressbar {
        margin-bottom: 30px;
        overflow: hidden;
        color: lightgrey;
        /*Warna teks saat belum active*/
    }

    #progressbar .active {
        color: #2a3855
    }

    #progressbar li {
        list-style-type: none;
        font-size: 12px;
        float: left;
        position: relative
    }

    #progressbar #account:before {
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        content: "\f007";
    }

    #progressbar #personal:before {
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        content: "\f15c";
    }

    #progressbar #payment:before {
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        content: "\f27a";
    }

    #progressbar #confirm:before {
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        content: "\f06a";
    }

    #progressbar #completed:before {
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        content: "\f00c";
    }

    #progressbar li:before {
        width: 50px;
        height: 50px;
        line-height: 45px;
        display: block;
        font-size: 18px;
        color: #ffffff;
        background: lightgray;
        border-radius: 25%;
        margin: 0 auto 10px auto;
        padding: 2px
    }

    #progressbar li:after {
        content: '';
        width: 100%;
        height: 2px;
        background: lightgray;
        position: absolute;
        left: 0;
        top: 25px;
        z-index: -1
    }

    #progressbar li.active:before,
    #progressbar li.active:after {
        background: linear-gradient(#fdd83e, #fdd83e);
        /* color: #2a3855; */
    }
    </style>


    <style>
    .sticky-chat-button {
        position: fixed;
        background-color: white;
        border-radius: 40px;
        border: 4px solid #E4E6EF;
        bottom: 80px;
        right: 15%;
        height: 65px;
        width: 65px;
        overflow: hidden;
    }

    .sticky-chat-button span {
        display: inline-block;
        position: relative;
        text-align: center;
        height: 30px;
        width: 30px;
        padding: 15px;
    }
    </style>
</head>

<body id="kt_body"
    class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-fixed aside-minimize-hoverable">


    @if($ci->uri->segment(3) == 'preview-form-survei')
    <div class="" style="text-align: center; background-color: #FFA800; color: #FFFFFF; font-size:14px;">
        <b>PREVIEW FORM SURVEI</b>
    </div>

    @if($is_question == 1)
    <!-- <a class="sticky-chat-button shadow" data-aos="fade-up" data-toggle="tooltip" data-placement="right"
        title="Edit Form Survei"
        href="<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/form-survei/opening' ?>"
        target="_blank">
        <span>
            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-pencil-fill"
                viewBox="0 0 16 16">
                <path fill="grey"
                    d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z" />
            </svg>

        </span>
    </a> -->
    @endif


    @elseif($ci->uri->segment(3) == 'form-survei')
    <div class="" style="text-align: center; background-color: #AE0000; color: #FFFFFF; font-size:14px;">
        <b>EDIT FORM SURVEI</b>
    </div>


    @elseif($ci->uri->segment(5) == 'edit')
    <div class="" style="text-align: center; background-color: #AE0000; color: #FFFFFF; font-size:14px;">
        <b>EDIT HASIL SURVEI</b>
    </div>
    @endif


    @php
                $slug = $ci->uri->segment(2);

                $data_user = $ci->db->query("SELECT *, iF(is_saran = 1, '25%', '33.3%') AS style_saran
                FROM manage_survey
                JOIN users ON manage_survey.id_user = users.id
                WHERE slug = '$slug'")->row();
                $style_saran = $data_user->style_saran;
                @endphp

                @php
                $identitas_survey = $ci->db->query("
                SELECT *, DATE_FORMAT(survey_end, '%d %M %Y') AS survey_selesai, IF(CURDATE() > survey_end,1,NULL)
                AS
                survey_berakhir,
                IF(CURDATE() < survey_start ,1,NULL) AS survey_belum_mulai FROM manage_survey JOIN users ON
                    manage_survey.id_user=users.id WHERE slug='$slug' ")->row();
                @endphp

                <style>
                    #progressbar li {
                        width: <?php echo $style_saran ?>;
                    }
                </style>

    <!-- <nav class="navbar navbar-light bg-white shadow mb-5">
        <div class="outer-box">
            <div class="box-edge-logo">
                

                    @if ($data_user->foto_profile == NULL)
                    <img src=" <?php echo base_url(); ?>assets/klien/foto_profile/200px.jpg" width="90%" class=""
                    alt="">
                    @else
                    <img src="<?php echo base_url(); ?>assets/klien/foto_profile/<?php echo $data_user->foto_profile ?>"
                        width="90%" class="" alt="">
                    @endif
            </div>


            <div class="box-edge-text">
                <div class="row">
                    <div class="col-xl-6">
                        <div class="box-title">
                            {!! $title_1 !!}
                        </div>
                        <div class="box-desc">
                            {!! $title_2 !!}
                        </div>
                    </div>

                    <div class="col-xl-6 text-center">
                        @if ($ci->uri->segment(3) == 'form-survei')
                        <div class="btn-group dropright" role="group">
                            <button id="btnGroupDrop1" type="button"
                                class="btn btn-secondary btn-sm font-weight-bold dropdown-toggle" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-cogs"></i> Pengaturan
                            </button>
                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                <a class="dropdown-item" type="button" class="" data-toggle="modal"
                                    data-target="#logo_survei">Pengaturan Title Logo Survei</a>
                                <a class="dropdown-item" type="button" class="" data-toggle="modal"
                                    data-target="#form_saran">Pengaturan Form Saran</a>
                                <a class="dropdown-item" type="button" class="" data-toggle="modal"
                                    data-target="#form_pertanyaan">Pengaturan Pertanyaan Survei</a>
                            </div>
                        </div>


                        @else

                        <button type="button" class="btn btn-secondary btn-sm font-weight-bold" data-toggle="modal"
                            data-target="#informasi_survei">
                            <i class="fa fa-info-circle"></i> Informasi Survei
                        </button>

                        @endif
                    </div>

                </div>


            </div>


        </div>
    </nav> -->



    <div class="">
        @yield('content')




        <!-- MODAL INFORMASI SURVEI -->
        <div class="modal fade bd-example-modal-lg" id="informasi_survei" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content border border-warning">
                    <div class="modal-header bg-warning">
                        <h5 class="modal-title text-white" id="exampleModalLabel">Informasi Survei</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div style="font-family: Arial, Helvetica, sans-serif; font-size: 16px">
                            <ul>
                                <li>Sebelum anda melakukan Konfirmasi SUBMIT data, anda masih bisa mengubah jawaban
                                    kuesioner anda dengan menggunakan link dibawah ini :<br>

                                    <div style="border: 1px dotted #333333; padding: 5px;">
                                        <?php echo base_url() . 'survei/' . $ci->uri->segment(2) . '/' . $ci->uri->segment(3) . '/' . $ci->uri->segment(4) ?>
                                    </div>
                                </li>
                            </ul>

                        </div>


                        <div class="text-center mt-1">
                            atau
                            <br>


                            <form
                                action="<?php echo base_url() . 'survei/' . $ci->uri->segment(2) . '/download-link' ?>"
                                method="POST">
                                <input
                                    value="<?php echo base_url() . 'survei/' . $ci->uri->segment(2) . '/' . $ci->uri->segment(3) . '/' . $ci->uri->segment(4) ?>"
                                    name="link" hidden>


                                <button type="submit" target="_blank" class="btn btn-warning"><i
                                        class="fas fa-file-download"></i> Simpan Link</button>
                            </form>
                            <!-- <a href="<?php echo base_url() . 'survei/' . $ci->uri->segment(2) . '/download-link' ?>"
                                target="_blank" class="btn btn-warning"><i class="fas fa-file-download"></i> Simpan
                                Link</a> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- MODAL TEXT LOGO -->
        <div class="modal fade" id="logo_survei" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content border border-warning">
                    <div class="modal-header bg-warning">
                        <h5 class="modal-title" id="exampleModalLabel">Edit Text Logo</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form
                            action="<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/form-survei/update-header' ?>"
                            class="form_update" method="POST">

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label font-weight-bold">Title 1 <span
                                        style="color: red;">*</span></label>
                                <div class="col-sm-10">
                                    <textarea name="title[]" value="" class="form-control"
                                        required><?php echo $title_1 ?></textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label font-weight-bold">Title 2 <span
                                        style="color: red;">*</span></label>
                                <div class="col-sm-10">
                                    <textarea name="title[]" value="" class="form-control"
                                        required><?php echo $title_2 ?></textarea>
                                </div>
                            </div>

                            <div class="text-right">
                                <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">
                                    Close
                                </button>
                                <button type="submit"
                                    class="btn btn-primary btn-sm font-weight-bold tombolUpdate">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <!-- MODAL FORM SARAN -->
        <div class="modal fade" id="form_saran" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content border border-warning">
                    <div class="modal-header bg-warning">
                        <h5 class="modal-title" id="exampleModalLabel">Edit From Saran</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-custom alert-notice alert-light-primary fade show mb-7" role="alert">
                            <div class="alert-icon"><i class="flaticon-warning"></i></div>
                            <div class="alert-text">Jika diaktifkan, maka akan ditampilkan form saran
                                survey.</div>
                            <div class="alert-close">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true"><i class="ki ki-close"></i></span>
                                </button>
                            </div>
                        </div>

                        <form
                            action="<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/form-survei/update-saran' ?>"
                            class="form_update" method="POST">

                            <div class="form-group">
                                <label class="col-form-label font-weight-bold">Aktifkan Form Saran</label>
                                <select class="form-control" name="is_saran" id="is_saran"
                                    value="<?php echo set_value('is_saran'); ?>">
                                    <option value="1" <?php echo $manage_survey->is_saran == 1 ? 'selected' : '' ?>>Ya
                                    </option>
                                    <option value="2" <?php echo $manage_survey->is_saran == 2 ? 'selected' : '' ?>>
                                        Tidak
                                    </option>
                                </select>
                            </div>

                            <div id="judul_saran" <?php echo $manage_survey->is_saran == 1 ? '' : 'hidden' ?>>
                                <div class="form-group">
                                    <label class="col-form-label font-weight-bold">Judul Form Saran</label>
                                    <textarea class="form-control" name="judul_form_saran" id="judul_form_saran"
                                        value="" rows="3"><?php echo $manage_survey->judul_form_saran ?></textarea>
                                </div>
                            </div>

                            <div class="text-right">
                                <button class="btn btn-secondary btn-sm" type="button"
                                    data-dismiss="modal">Close</button>
                                <button type="submit"
                                    class="btn btn-primary btn-sm font-weight-bold tombolUpdate">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <!-- MODAL FORM Petrtanyaan -->
        <div class="modal fade" id="form_pertanyaan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content border border-warning">
                    <div class="modal-header bg-warning">
                        <h5 class="modal-title" id="exampleModalLabel">Edit Form Pertanyaan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="alert alert-custom alert-notice alert-light-primary fade show mb-10" role="alert">
                            <div class="alert-icon"><i class="flaticon-warning"></i></div>
                            <div class="alert-text"> <span>Atribut Pertanyaan Survei digunakan untuk mengatur jenis
                                    pertanyaan apa saja yang digunakan dalam survei. Mengubah Jenis Pertanyaan juga
                                    akan menghapus semua data perolehan survei yang sudah masuk!</span>
                            </div>
                            <div class="alert-close">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true"><i class="ki ki-close"></i></span>
                                </button>
                            </div>
                        </div>

                        <form
                            action="{{base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/setting-pertanyaan'}}"
                            class="form_update" method="POST">

                            <div class="form-group row">
                                <label for="recipient-name" class="col-sm-4 col-form-label font-weight-bold">Atribut
                                    Pertanyaan Survei<span style="color:red;">*</span></label>

                                <div class="col-sm-8">

                                    <input type="hidden" name="atribut_pertanyaan[]" value="0">
                                    <label class="font-weight-bold"><input type="checkbox" checked disabled>
                                        Pertanyaan Unsur</label><br>

                                    <label><input type="checkbox" name="atribut_pertanyaan[]" value="1"
                                            <?php echo in_array(1, unserialize($manage_survey->atribut_pertanyaan_survey)) ? 'checked' : '' ?>>
                                        Pertanyaan Harapan</label><br>

                                    <label><input type="checkbox" name="atribut_pertanyaan[]" value="2"
                                            <?php echo in_array(2, unserialize($manage_survey->atribut_pertanyaan_survey)) ? 'checked' : '' ?>>
                                        Pertanyaan Tambahan</label><br>

                                    <label><input type="checkbox" name="atribut_pertanyaan[]" value="3"
                                            <?php echo in_array(3, unserialize($manage_survey->atribut_pertanyaan_survey)) ? 'checked' : '' ?>>
                                        Pertanyaan Kualitatif</label>
                                </div>
                            </div>

                            <div class="text-right">
                                <button class="btn btn-secondary btn-sm" type="button"
                                    data-dismiss="modal">Close</button>
                                <button type="submit"
                                    class="btn btn-primary btn-sm font-weight-bold tombolUpdate">Update</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>


    @include('include_backend/partials_backend/_scrool_top')


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
    <script>
    $('.form_update').submit(function(e) {

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            dataType: 'json',
            data: $(this).serialize(),
            cache: false,
            beforeSend: function() {
                $('.tombolUpdate').attr('disabled', 'disabled');
                $('.tombolUpdate').html(
                    '<i class="fa fa-spin fa-spinner"></i> Sedang diproses');

                KTApp.block('#content_1', {
                    overlayColor: '#000000',
                    state: 'primary',
                    message: 'Processing...'
                });

                setTimeout(function() {
                    KTApp.unblock('#content_1');
                }, 1000);

            },
            complete: function() {
                $('.tombolUpdate').removeAttr('disabled');
                $('.tombolUpdate').html('Update');
            },
            error: function(e) {
                Swal.fire(
                    'Error !',
                    e,
                    'error'
                )
            },
            success: function(data) {
                if (data.validasi) {
                    $('.pesan').fadeIn();
                    $('.pesan').html(data.validasi);
                }
                if (data.sukses) {
                    toastr["success"]('Data berhasil disimpan');
                    window.location.href =
                        "<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/form-survei/opening' ?>";
                }
            }
        })
        return false;
    });
    </script>




    <script src="{{ VENDOR_PATH }}jquery/jquery-3.6.0.min.js"></script>
    <script src="{{ TEMPLATE_BACKEND_PATH }}plugins/global/plugins.bundle.js"></script>
    <script src="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/prismjs/prismjs.bundle.js"></script>
    <script src="{{ TEMPLATE_BACKEND_PATH }}js/scripts.bundle.js"></script>
    <script src="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/fullcalendar/fullcalendar.bundle.js"></script>
    <script src="{{ TEMPLATE_BACKEND_PATH }}js/pages/widgets.js"></script>
    <script src="{{ VENDOR_PATH }}aos/aos.js"></script>

    @yield('javascript')
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