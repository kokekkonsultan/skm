<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{!! (isset($title)) ? $title : "SurveiKu" !!}</title>
	<link rel="shortcut icon" type="image/png" href="{{ base_url() }}assets/img/site/content/favicon.png"/>
    <link href="{{ base_url() }}assets/vendor/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="{{ base_url() }}assets/vendor/aos/aos.css" rel="stylesheet">
	<link href="{{ base_url() }}assets/vendor/sweetalert/sweetalert2.min.css" rel="stylesheet">
	<link href="{{ base_url() }}assets/vendor/sweetalert/all.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
	<link href="{{ base_url() }}assets/vendor/sweetalert/animate.min.css" rel="stylesheet">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Exo+2:wght@400;700&family=Nunito+Sans:wght@200;300;400&display=swap" rel="stylesheet">
	<style>
		body{
			background-color: #E5E5E5;
		}

		.card-no-border{
			border: 0px;
		}

		footer {
			position: fixed;
			/* height: 100px; */
			bottom: 0;
			width: 100%;
		}

		@media all and (min-width: 992px) {
			.nav .nav-item .dropdown-menu{  display:block; opacity: 0;  visibility: hidden; transition:.3s; margin-top:0;  }
			.nav .nav-item:hover .nav-link{ color: #fff;  }
			.nav .dropdown-menu.fade-down{ top:80%; transform: rotateX(-75deg); transform-origin: 0% 0%; }
			.nav .dropdown-menu.fade-up{ top:180%;  }
			.nav .nav-item:hover .dropdown-menu{ transition: .3s; opacity:1; visibility:visible; top:100%; transform: rotateX(0deg); }
		}

		.container-main {
			margin-top: -230px;
		}

		.card-header, .alert {
			font-family: 'Exo 2', sans-serif;
		}

		h2, h3, h4, h5{
			font-family: 'Exo 2', sans-serif;
		}

		.btn-rounded {
			border-radius: 18px;
		}

		.header-custom {
			font-family: 'Exo 2', sans-serif; 
			height:400px; 
			border-radius: 0px 0px 20% 20%;
		}
	</style>
	
	@yield('style')
  </head>
  <body>
	
    @yield('content')

	  <div class="modal fade" id="modalChoice" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content rounded-3 shadow">
			  <div class="modal-body p-4 text-center">
				<h5 class="mb-0">Apakah anda akan keluar?</h5>
				<p class="mb-0">Pastikan data anda sudah disimpan.</p>
			  </div>
			  <div class="modal-footer flex-nowrap p-0">
				<button type="button" class="btn btn-lg btn-link fs-6 text-decoration-none col-6 m-0 rounded-0 border-end" onclick="user_logout()"><strong>Yes, sign out</strong></button>
				<button type="button" class="btn btn-lg btn-link fs-6 text-decoration-none col-6 m-0 rounded-0" data-bs-dismiss="modal">No thanks</button>
			  </div>
			</div>
		  </div>
	  </div>

	<script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
	<script src="{{ base_url() }}assets/vendor/sweetalert/sweetalert2.min.js"></script>
	<script src="{{ base_url() }}assets/vendor/aos/aos.js"></script>
	<script>
		function user_logout() {
			$.ajax({
                    url: "{{ base_url() }}auth/logout",
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

                            window.location.href = "{{ base_url() }}auth/login";

                        } else {

                        }


                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert('Error logout');
                    }
                });
		}
	</script>
	<script>
		AOS.init();
	</script>
	@yield('javascript')
  </body>
</html>
