@php
$ci = get_instance();
@endphp
<!DOCTYPE html>
<html lang="en-US" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
    <link rel="manifest" href="{{base_url()}}assets/themes/elixir/v3.0.0/assets/img/favicons/manifest.json">
    <meta name="msapplication-TileImage" content="{{base_url()}}assets/themes/elixir/v3.0.0/assets/img/favicons/mstile-150x150.png">
    <meta name="theme-color" content="#ffffff">
    <link href="{{base_url()}}assets/themes/elixir/v3.0.0/assets/css/theme.css" rel="stylesheet" />

    <style type="text/css">
        .bg-holder {
            background: url('<?= base_url('assets/') ?>themes/img/hero-bg.png') no-repeat center center fixed;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            background-size: cover;
            -o-background-size: cover;
        }
    </style>
    @yield('style')
</head>

<body>

    <main class="main" id="top">
        <section class="py-0" id="home">
            <div class="bg-holder"></div>
            @yield('content')
        </section>

        <section class="py-4 bg-1000">

            <div class="container">
                <div class="row flex-center">
                    <div class="col-auto">
                        <p class="lh-lg mb-0 fw-semi-bold text-light">&copy; Copyright {{ date("Y") }}</p>
                    </div>
                </div>
            </div>

        </section>

    </main>

    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="{{base_url()}}assets/themes/elixir/v3.0.0/vendors/@popperjs/popper.min.js"></script>
    <script src="{{base_url()}}assets/themes/elixir/v3.0.0/vendors/bootstrap/bootstrap.min.js"></script>
    <script src="{{base_url()}}assets/themes/elixir/v3.0.0/vendors/is/is.min.js"></script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=window.scroll"></script>
    <script src="{{base_url()}}assets/themes/elixir/v3.0.0/assets/js/theme.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400&amp;display=swap" rel="stylesheet">
    @yield('javascript')

</body>

</html>