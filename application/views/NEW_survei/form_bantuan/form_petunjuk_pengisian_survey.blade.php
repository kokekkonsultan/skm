<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
    <!-- GOOGLE WEB FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet">
    <!-- BASE CSS -->
    <link href="{{ base_url() }}assets/themes/magnifica/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ base_url() }}assets/themes/magnifica/css/menu.css" rel="stylesheet">
    <link href="{{ base_url() }}assets/themes/magnifica/css/style.css" rel="stylesheet">
    <link href="{{ base_url() }}assets/themes/magnifica/css/vendors.css" rel="stylesheet">
    <!-- YOUR CUSTOM CSS -->
    <link href="{{ base_url() }}assets/themes/magnifica/css/custom.css" rel="stylesheet">
    <!-- MODERNIZR MENU -->
    <script src="{{ base_url() }}assets/themes/magnifica/js/modernizr.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" />

    <link href="<?= base_url('assets/') ?>survey/opening/assets/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
</head>

<body class="layout_2">

    @php
    $ci = get_instance();
    @endphp

    <div id="preloader">
        <div data-loader="circle-side"></div>
    </div><!-- /Preload -->

    <div id="loader_form">
        <div data-loader="circle-side-2"></div>
    </div><!-- /loader_form -->

    <header>
        <div class=" container-fluid">


            <img src="<?php echo base_url() ?>assets/img/site/logo/logo-dark2.png" alt="" height="50" class="d-none d-md-inline">
            <img src="<?php echo base_url() ?>assets/img/site/logo/logo-dark2.png" alt="" height="50" class="d-inline d-md-none">

            <br>
            <br>
            <div style="font-weight:bold; font-size:25px; color:#fcfcfc; text-shadow: 2px 2px black">PETUNJUK PENGISIAN SURVEY KEPUASAN MASYARAKAT</div>
            <br>
        </div>
    </header>


    <div class="container-fluid">
        <div id="form_container">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div id="wizard_container">
                        <div id="top-wizard">
                            <div id="progressbar"></div>
                        </div>
                        <!-- /top-wizard -->
                        <form id="wrapped" method="post">
                            <!-- Leave for security protection, read docs for details -->
                            <div id="middle-wizard">
                                <div class="step text-center">

                                    <div class="card shadow mt-5" style="border-radius: 10px;">
                                        <div class="card-body">
                                            Responden masuk ke Link Survei yang sudah diberikan
                                        </div>
                                    </div>
                                    <i class="fa fa-arrow-circle-down mt-3 mb-3" style="font-size: 35px; color:#304EC0; color:#304EC0;"></i>

                                    <div class="card shadow" style="border-radius: 10px;">
                                        <div class="card-body">
                                            Klik tombol <b>Ikuti Survei</b>
                                        </div>
                                    </div>

                                    <i class="fa fa-arrow-circle-down mt-3 mb-3" style="font-size: 35px; color:#304EC0;"></i>

                                    <div class="card shadow" style="border-radius: 10px;">
                                        <div class="card-body">
                                            Responden diharuskan Menginputkan data-data terkait pribadi terlebih dahulu
                                        </div>
                                    </div>

                                    <i class="fa fa-arrow-circle-down mt-3 mb-3" style="font-size: 35px; color:#304EC0;"></i>

                                    <div class="card shadow" style="border-radius: 10px;">
                                        <div class="card-body">
                                            Klik tombol <b>Selanjutnya</b> untuk masuk ke halaman pertanyaan
                                            dan muali mengisi
                                            survei
                                        </div>
                                    </div>

                                    <i class="fa fa-arrow-circle-down mt-3 mb-3" style="font-size: 35px; color:#304EC0;"></i>

                                    <div class="card shadow" style="border-radius: 10px;">
                                        <div class="card-body">
                                            Terdapat 3 Jenis Pertanyaan yang wajib diisi oleh Responden, yaitu
                                            Pertanyaan Unsur, Pertanyaan Harapan, dan Pertanyaan Kualitatif
                                        </div>
                                    </div>

                                    <i class="fa fa-arrow-circle-down mt-3 mb-3" style="font-size: 35px; color:#304EC0;"></i>

                                    <div class="card shadow" style="border-radius: 10px;">
                                        <div class="card-body">
                                            Jika semua pertanyaan sudah terisi maka akan tampil form konfirmasi</div>
                                    </div>

                                    <i class="fa fa-arrow-circle-down mt-3 mb-3" style="font-size: 35px; color:#304EC0;"></i>

                                    <div class="card shadow" style="border-radius: 10px;">
                                        <div class="card-body">
                                            Dan jika sudah yakin dengan jawaban, responden bisa mengklik tombol
                                            <b>Submit</b>
                                            untuk mengirim survey
                                        </div>
                                    </div>

                                    <i class="fa fa-arrow-circle-down mt-3 mb-3" style="font-size: 35px; color:#304EC0;"></i>

                                    <div class="card shadow" style="border-radius: 10px;">
                                        <div class="card-body">
                                            Namun jika ada jawaban yang belum yakin, Responden bisa kembali lagi ke
                                            halaman pertanyaan dengan cara mengklik tombol <b>Lengkapi
                                                Lagi
                                        </div>
                                    </div>


                                </div>
                                <!-- /step-->
                            </div>
                            <!-- /bottom-wizard -->
                        </form>
                    </div>
                    <!-- /Wizard container -->
                </div>
            </div><!-- /Row -->
        </div><!-- /Form_container -->
    </div>
    <!-- /container -->




    <script src="{{ base_url() }}assets/themes/magnifica/js/jquery-3.6.0.min.js"></script>
    <script src="{{ base_url() }}assets/themes/magnifica/js/common_scripts.min.js"></script>
    <script src="{{ base_url() }}assets/themes/magnifica/js/velocity.min.js"></script>
    <script src="{{ base_url() }}assets/themes/magnifica/js/common_functions.js"></script>
    <script src="{{ base_url() }}assets/themes/magnifica/js/wizard_without_branch.js"></script>
    @yield('javascript')
</body>

</html>