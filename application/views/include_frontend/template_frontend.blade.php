@php
$ci = get_instance();
$website_config = $ci->db->get_where('website_configuration', ['id' => 1])->row();
@endphp
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ (isset($meta_title)) ? $meta_title : $title.' | '.$website_config->meta_title }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="robots" content="index, follow">
    <meta name="description"
        content="{{ (isset($meta_description)) ? $meta_description : $website_config->meta_description }}">
    <meta name="keywords" content="{{ (isset($meta_keywords)) ? $meta_keywords : $website_config->meta_keywords }}">
    <meta http-equiv="Copyright" content="{{ $website_config->meta_copyright }}">
    <meta name="author" content="{{ $website_config->meta_author }}">
    <meta http-equiv="imagetoolbar" content="no">
    <meta name="language" content="{{ $website_config->meta_language }}">
    <meta name="revisit-after" content="7">
    <meta name="webcrawlers" content="all">
    <meta name="rating" content="general">
    <meta name="spiders" content="all">
    <meta name="googlebot" content="index,follow" />
    <link rel="shortcut icon" type="image/png" href="{{ base_url() }}assets/img/favicon.png" />
    <meta name="msapplication-TileImage"
        content="https://cdn.jsdelivr.net/gh/lefi-andri/elixir/assets/img/favicons/mstile-150x150.png">
    <meta name="theme-color" content="#ffffff">
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="">
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&amp;family=Open+Sans:wght@300;400;600;700;800&amp;display=swap"
        rel="stylesheet">
    <link href="{{ base_url() }}assets/themes/chain/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css"
        integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ base_url() }}assets/themes/chain/assets/css/templatemo-chain-app-dev.css">
    <link rel="stylesheet" href="{{ base_url() }}assets/themes/chain/assets/css/animated.css">
    <link rel="stylesheet" href="{{ base_url() }}assets/themes/chain/assets/css/owl.css">
    <link rel="stylesheet" href="{{ base_url() }}assets/vendor/aos/aos.css">
    @yield('style')
</head>

<body>
    <div id="js-preloader" class="js-preloader">
        <div class="preloader-inner">
            <span class="dot"></span>
            <div class="dots">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </div>
    
    <header class="header-area header-sticky wow slideInDown" data-wow-duration="0.75s" data-wow-delay="0s">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav class="main-nav">
                        <a href="{{ base_url() }}" class="logo">
                            <img src="{{ base_url() }}assets/img/site/logo/logo-e-skm.png" alt="SurveiKu" style="width: 50%;">
                        </a>
                        <ul class="nav">
                            <li class="scroll-to-section"><a href="https://surveiku.com/">Home</a></li>
                            
                            <li>
                                <div class="gradient-button"><a href="{{ base_url() }}auth"><i
                                            class="fa fa-sign-in-alt"></i> Log In</a></div>
                            </li>
                        </ul>
                        <a class='menu-trigger'>
                            <span>Menu</span>
                        </a>
                    </nav>
                </div>
            </div>
        </div>
    </header>

    
    {{-- @include('include_frontend/partials_frontend/_header') --}}

    @yield('content')

    <footer id="newsletter">
        <div class="container">
            <div class="row">
                <!-- <div class="col-lg-8 offset-lg-2">
                    <div class="section-heading">
                        <h4>Hubungi kami untuk mendapatkan informasi &amp; penawaran terbaik</h4>
                    </div>
                </div>
                <div class="col-lg-6 offset-lg-3">
                    <form id="search" action="{{ base_url() }}contact" method="GET">
                        <div class="row">
                            <div class="col-lg-6 col-sm-6">
                                <fieldset>
                                    <input type="email" name="email_address" class="email"
                                        placeholder="Alamat Email Anda..." autocomplete="on" required>
                                </fieldset>
                            </div>
                            <div class="col-lg-6 col-sm-6">
                                <fieldset>
                                    <button type="submit" class="main-button">Hubungi Sekarang <i
                                            class="fa fa-angle-right"></i></button>
                                </fieldset>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                    <div class="footer-widget">
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="footer-widget">
                        <h4>About Us</h4>
                        <ul>
                            <li><a href="{{ base_url() }}">Home</a></li>
                            <li><a href="{{ base_url() }}article/post/survei-kepuasan-masyarakat-berbasis-web">Tentang
                                    E-SKM</a></li>
                            <li><a href="{{ base_url() }}article/post/unsur-unsur-survei-kepuasan-masyarakat">Unsur
                                    Survei</a></li>
                        </ul>
                        <ul>
                            <li><a href="{{ base_url() }}contact">Hubungi Kami</a></li>
                            <li><a href="{{ base_url() }}reseller-area">Reseller</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="footer-widget">
                        <h4>Useful Links</h4>
                        <ul>
                            <li><a href="{{ base_url() }}article">Artikel</a></li>
                            <li><a href="{{ base_url() }}publikasi-link-survei">Survei</a></li>
                            <li><a href="{{ base_url() }}publikasi">Publikasi</a></li>
                        </ul>
                        <ul>

                        </ul>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="footer-widget">
                    </div>
                </div> -->
                <div class="col-lg-12">
                <p><i class="bx bx-copyright"></i>

                    <div class="copyright-text">
                        <p>Copyright © {{ date('Y') }} SurveiKu. All Rights Reserved.
                            <br>Power by <a href="https://www.kokek.com" title="PT. KOKEK - Kokek Konsultan">kokek.com</a>.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>


    {{-- @include('include_frontend/partials_frontend/_footer') --}}
    <script src="{{ base_url() }}assets/themes/chain/vendor/jquery/jquery.min.js"></script>
    <script src="{{ base_url() }}assets/themes/chain/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="{{ base_url() }}assets/themes/chain/assets/js/owl-carousel.js"></script>
    <script src="{{ base_url() }}assets/themes/chain/assets/js/animation.js"></script>
    <script src="{{ base_url() }}assets/themes/chain/assets/js/imagesloaded.js"></script>
    <script src="{{ base_url() }}assets/themes/chain/assets/js/popup.js"></script>
    <script src="{{ base_url() }}assets/themes/chain/assets/js/custom.js"></script>
    <script src="{{ base_url() }}assets/vendor/aos/aos.js"></script>
    <script>
    AOS.init();
    </script>
    @yield('javascript')
</body>

</html>