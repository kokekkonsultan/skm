@php
	$ci = get_instance();
@endphp
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title></title>
	<link rel="stylesheet" href="">
	<link rel="shortcut icon" type="image/png" href="{{ base_url() }}assets/img/favicon.png"/>
	<meta name="theme-color" content="#ffffff">
    <script src="{{ TEMPLATE_FRONTEND_PATH }}vendors/overlayscrollbars/OverlayScrollbars.min.js"></script>

    <link href="{{ TEMPLATE_FRONTEND_PATH }}vendors/hamburgers/hamburgers.min.css" rel="stylesheet">
    <link href="{{ TEMPLATE_FRONTEND_PATH }}vendors/loaders.css/loaders.min.css" rel="stylesheet">
    <link href="{{ TEMPLATE_FRONTEND_PATH }}assets/css/theme.min.css" rel="stylesheet" />
    <link href="{{ TEMPLATE_FRONTEND_PATH }}assets/css/user.min.css" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&amp;family=Open+Sans:wght@300;400;600;700;800&amp;display=swap" rel="stylesheet">
	@yield('style')
</head>
<body>

	@yield('content')
	
	<script src="{{ TEMPLATE_FRONTEND_PATH }}vendors/popper/popper.min.js"></script>
    <script src="{{ TEMPLATE_FRONTEND_PATH }}vendors/bootstrap/bootstrap.min.js"></script>
    <script src="{{ TEMPLATE_FRONTEND_PATH }}vendors/is/is.min.js"></script>
    <script src="{{ TEMPLATE_FRONTEND_PATH }}vendors/fontawesome/all.min.js"></script>
    <script src="{{ TEMPLATE_FRONTEND_PATH }}vendors/lodash/lodash.min.js"></script>
    <script src="{{ TEMPLATE_FRONTEND_PATH }}vendors/imagesloaded/imagesloaded.pkgd.min.js"></script>
    <script src="{{ TEMPLATE_FRONTEND_PATH }}vendors/gsap/gsap.js"></script>
    <script src="{{ TEMPLATE_FRONTEND_PATH }}vendors/gsap/customEase.js"></script>
    <script src="{{ TEMPLATE_FRONTEND_PATH }}assets/js/theme.js"></script>
	@yield('javascript')
</body>
</html>