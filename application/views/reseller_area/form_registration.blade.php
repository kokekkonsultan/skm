@extends('include_frontend/template_frontend')

@php 
	$ci = get_instance();
@endphp

@section('style')
<link rel=stylesheet href="{{ VENDOR_PATH }}/sweetalert2/sweetalert2.min.css">
@endsection

@section('content')

<div class="main-content wow fadeIn" id="top" data-wow-duration="1s" data-wow-delay="0.5s">
      <section class="bg-100">
        <div class="container">
          <div class="overflow-hidden mb-4" data-zanim-timeline="{}" data-zanim-trigger="scroll">
            <h4 data-zanim-xs='{"delay":0.1}'>Form Pendaftaran Reseller</h4>
          </div>
          <div class="row">
            <div class="col-lg-8">
              <div class="card mb-6"> <img class="card-img-top" src="{{ base_url() }}assets/img/site/page/registration-model.jpg" alt="new image" />
                <div class="card-body p-5">
                  
                  <p class="text-center">
											Silahkan lengkapi form dibawah ini untuk mengikuti program reseller. Kami akan menghubungi anda kembali setelah kami meninjau permintaan anda, maksimal 3x24 jam pada hari kerja.
										</p><br><br>

										<h4>Isi formulir pendaftaran</h4><br>

									{!! form_open($form_action, ['id' => 'confirmation_form']) !!}
									<div class="mb-3">
									  <label for="" class="form-label font-weight-bold">Nama Lengkap <span class="text-danger">*</span></label>
									  {!! form_input($full_name); !!}
									</div>
									<div class="mb-3">
									  <label for="" class="form-label font-weight-bold">Profesi atau jabatan anda bekerja <span class="text-danger">*</span></label>
									  {!! form_input($profession); !!}
									</div>
									<div class="mb-3">
									  <label for="" class="form-label font-weight-bold">Organisasi atau tempat anda bekerja <span class="text-danger">*</span></label>
									  {!! form_input($organization); !!}
									</div>
									<div class="mb-3">
									  <label for="" class="form-label font-weight-bold">Email <span class="text-danger">*</span></label>
									  {!! form_input($email); !!}
									</div>
									<div class="mb-3">
									  <label for="" class="form-label font-weight-bold">Whatsapp <span class="text-danger">*</span></label>
									  {!! form_input($whatsapp); !!}
									</div>

									<div class="mb-3">
									  <label for="" class="form-label font-weight-bold">Alasan anda mengikuti program reseller <span class="text-danger">*</span></label>
									  {!! form_textarea($reason); !!}
									</div>

									<br>
									<div class="mt-5 mb-3">
										<div id="error_captcha"></div>
			    						<span id="captImg">{!! $gambar_captcha !!}</span> <a href="javascript:void(0);" class="refreshCaptcha" title="Ubah Captcha"><i class="fas fa-sync"></i></a>
									  <br>
									  <label for="kodeCaptcha" class="form-label font-weight-bold mt-5">Captcha *</label>
									  {!! form_input($kodeCaptcha) !!}
									  
									</div>
									

									
									<div class="text-end mt-5">
										<br><br>
										<p class="text-right">Dengan klik tombol dibawah ini, berarti anda telah mengerti program reseller.</p>
										<br>
										<button type="submit" class="btn btn_red btn-block font-weight-bold shadow btn-lg" id="sendConfirm">Kirim Permohonan</button>
									</div>
									<br><br>
									
									{!! form_close() !!}



                </div>
              </div>
              
            </div>
            <div class="col-lg-4 text-center ms-auto mt-5 mt-lg-0">
              
            </div>
          </div>
        </div>
      </section>
</div>


@endsection

@section('javascript')
<script src="{{ base_url() }}assets/vendor/jquery/jquery-3.6.0.min.js"></script>
<script src="{{ VENDOR_PATH }}/sweetalert2/sweetalert2.min.js"></script>

<script>
$(document).ready(function(){

	$('#confirmation_form').on('submit', function(event){

		event.preventDefault();


		$.ajax({
			url:       "{{ base_url() }}form-pendaftaran-reseller/validate-message",
			method:    "POST",
			data:      $(this).serialize(),
			dataType:  "json",

			beforeSend:function(){

				Swal.fire({
					title: 'Memproses data',
					html: 'Mohon tunggu sebentar. Sistem sedang melakukan request anda.',
					allowOutsideClick: false,
					onOpen: () => {
						swal.showLoading()
					}
				});

			},

			success:function(data){

				if(data.success){

							$('#confirmation_form')[0].reset();
							$('#message').html(data.success);

							$('#error_captcha').html('');
							reloadCaptcha();

							Swal.fire({
							  type: 'success',
							  icon: "success",
							  title: 'Data Permohonan anda berhasil dikirim',
							  text: 'Kami akan mereview data anda. Jika disetujui, kami akan menghubungi anda melalui kontak yang Anda cantumkan. Terima Kasih.',
							  allowOutsideClick: false
							});

						}

						if(data.error){
							$('#error_captcha').html(data.error);
							$("#form-contact").removeClass("bg-blur");
							document.getElementById("confirmation_form").style.cursor = "default";
							$('#sendConfirm').attr('disabled', false);
							reloadCaptcha();

							Swal.fire({
							  type: "error",
							  title: 'Error',
							  text: data.message,
							  allowOutsideClick: false
							});

					    }
				
			
			}

		});

	});



	$('.refreshCaptcha').on('click', function(){
      $.get("{{ base_url() }}form-pendaftaran-reseller/refresh-captcha", function(data){
        $('#captImg').html(data);
      });
    });


});

	function reloadCaptcha(){
		$.get("{{ base_url() }}form-pendaftaran-reseller/refresh-captcha", function(data){
			$('#captImg').html(data);
		});
	}
</script>
@endsection