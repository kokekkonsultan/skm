@php 
  $ci = get_instance();
@endphp
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title>{{ $title }}</title>
		<meta name="description" content="E-SKM" />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
		<link rel="canonical" href="https://www.survei-kepuasan.com" />
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
		<link href="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/fullcalendar/fullcalendar.bundle.css" rel="stylesheet" type="text/css" />
		<link href="{{ TEMPLATE_BACKEND_PATH }}plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
		<link href="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/prismjs/prismjs.bundle.css" rel="stylesheet" type="text/css" />
		<link href="{{ TEMPLATE_BACKEND_PATH }}css/style.bundle.css" rel="stylesheet" type="text/css" />
		<link href="{{ TEMPLATE_BACKEND_PATH }}css/themes/layout/header/base/light.css" rel="stylesheet" type="text/css" />
		<link href="{{ TEMPLATE_BACKEND_PATH }}css/themes/layout/header/menu/light.css" rel="stylesheet" type="text/css" />
		<link href="{{ TEMPLATE_BACKEND_PATH }}css/themes/layout/brand/dark.css" rel="stylesheet" type="text/css" />
		<link href="{{ TEMPLATE_BACKEND_PATH }}css/themes/layout/aside/dark.css" rel="stylesheet" type="text/css" />
		<link href="{{ base_url() }}assets/vendor/wow/animate.css" rel="stylesheet" type="text/css" />
		
	    <link href="{{ base_url() }}assets/vendor/smart-menu/css/sm-core-css.css" rel="stylesheet" type="text/css" />
	    <link href="{{ base_url() }}assets/vendor/smart-menu/css/sm-blue/sm-blue.css" rel="stylesheet" type="text/css" />
		<link rel="icon" type="image/png" href="{{ base_url() }}assets/img/site/logo/favicon.png">
		@yield('style')
	</head>
	@if (!$ci->uri->segment(1) or ($ci->uri->segment(1) == 'home'))
		@php
			$bgc = "background-color: #FFFFFF;";
		@endphp
	@else
		@php
			$bgc = "";
		@endphp
	@endif
	<body id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed page-loading" style="{{ $bgc }}">
		@include('include_backend/partials_no_aside/_kt_header_mobile')
		<div class="d-flex flex-column flex-root">
			<div class="d-flex flex-row flex-column-fluid page">
				<div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">
					@include('include_backend/partials_no_aside/_kt_header')
					<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
						@if (!$ci->uri->segment(1) or ($ci->uri->segment(1) == 'home'))
						
						@else
							{{-- @include('include_backend/partials_no_aside/_kt_subheader') --}}
						@endif
						<div class="d-flex flex-column-fluid">
							{{-- @include('include_backend/partials_no_aside/_message') --}}
							@yield('content')
						</div>
					</div>
					@include('include_backend/partials_no_aside/_footer')
				</div>
			</div>
		</div>
		@include('include_backend/partials_no_aside/_kt_quick_user')
		@include('include_backend/partials_no_aside/_kt_quick_cart')
		@include('include_backend/partials_no_aside/_kt_quick_panel')
		{{-- @include('include_backend/partials_no_aside/_modal-sticky') --}}
		@include('include_backend/partials_no_aside/_kt_scrolltop')
		{{-- @include('include_backend/partials_no_aside/_sticky-toolbar') --}}
		{{-- @include('include_backend/partials_no_aside/_kt_demo_panel') --}}

		<script>var KTAppSettings = { "breakpoints": { "sm": 576, "md": 768, "lg": 992, "xl": 1200, "xxl": 1400 }, "colors": { "theme": { "base": { "white": "#ffffff", "primary": "#3699FF", "secondary": "#E5EAEE", "success": "#1BC5BD", "info": "#8950FC", "warning": "#FFA800", "danger": "#F64E60", "light": "#E4E6EF", "dark": "#181C32" }, "light": { "white": "#ffffff", "primary": "#E1F0FF", "secondary": "#EBEDF3", "success": "#C9F7F5", "info": "#EEE5FF", "warning": "#FFF4DE", "danger": "#FFE2E5", "light": "#F3F6F9", "dark": "#D6D6E0" }, "inverse": { "white": "#ffffff", "primary": "#ffffff", "secondary": "#3F4254", "success": "#ffffff", "info": "#ffffff", "warning": "#ffffff", "danger": "#ffffff", "light": "#464E5F", "dark": "#ffffff" } }, "gray": { "gray-100": "#F3F6F9", "gray-200": "#EBEDF3", "gray-300": "#E4E6EF", "gray-400": "#D1D3E0", "gray-500": "#B5B5C3", "gray-600": "#7E8299", "gray-700": "#5E6278", "gray-800": "#3F4254", "gray-900": "#181C32" } }, "font-family": "Poppins" };</script>
		<script src="{{ TEMPLATE_BACKEND_PATH }}plugins/global/plugins.bundle.js"></script>
		<script src="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/prismjs/prismjs.bundle.js"></script>
		<script src="{{ TEMPLATE_BACKEND_PATH }}js/scripts.bundle.js"></script>
		<script src="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/fullcalendar/fullcalendar.bundle.js"></script>
		<script src="{{ TEMPLATE_BACKEND_PATH }}js/pages/widgets.js"></script>
		{{-- <script src="{{ base_url() }}assets/vendor/morris_chart/raphael-min.js"></script>
    	<script src="{{ base_url() }}assets/vendor/morris_chart/morris.min.js"></script> --}}
    	<script src="{{ base_url() }}assets/vendor/wow/wow.min.js"></script>
		<script>new WOW().init();</script>
    	{{-- <script type="text/javascript" src="{{ base_url() }}assets/vendor/smart-menu/libs/jquery/jquery.js"></script> --}}
    	<script type="text/javascript" src="{{ base_url() }}assets/vendor/smart-menu/jquery.smartmenus.js"></script>
		<script type="text/javascript">
			$(function() {
				$('#main-menu').smartmenus({
					subMenusSubOffsetX: 1,
					subMenusSubOffsetY: -8
				});
			});
		</script>
		@yield('javascript')
	</body>
</html>