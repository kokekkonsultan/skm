@extends('include_backend/template_backend')

@php 
	$ci = get_instance();
@endphp

@section('style')
	<link rel="stylesheet" href="{{ base_url() }}assets/vendor/ckeditor/ckeditor.css">
@endsection

@section('content')

<div class="container">

	<div class="card">
		<div class="card-header">
			Balas Kontak Masuk
		</div>
		<div class="card-body">

<table class="table table-bordered">
	<tr>
		<th>Nama</th>
		<td>{{ $data_inbox->conNama }}</td>
	</tr>
	<tr>
		<th>Organisasi</th>
		<td>{{ $data_inbox->conOrganisasi }}</td>
	</tr>
	<tr>
		<th>Email</th>
		<td>{{ $data_inbox->conEmail }}</td>
	</tr>
	<tr>
		<th>Telepon</th>
		<td>{{ $data_inbox->conTelp }}</td>
	</tr>
	<tr>
		<th>Keperluan</th>
		<td>{{ $data_inbox->conSubject }}</td>
	</tr>
	<tr>
		<th>Isi Pesan</th>
		<td>{!! $data_inbox->conMessage !!}</td>
	</tr>
</table>

<br>
<hr>
<br>
			
	{!! form_open($form_action, ['id' => 'confirmation_form']) !!}
	<input type="hidden" name="id_contact" value="{{ $data_inbox->conId }}">
	<input type="hidden" name="email_contact" value="{{ $data_inbox->conEmail }}">
	<div class="mb-3">
	  <label for="conSubject" class="form-label fw-bold">Dikirim ke</label>
	  <div class="font-weight-bold text-danger">
	  	{{ $data_inbox->conEmail }}
	  </div>
	</div>
	<div class="mb-3">
	  <label for="conSubject" class="form-label fw-bold">Perihal *</label>
	  {!! form_input($conSubject) !!}
	</div>
	<div class="mb-3">
	  <label for="conMessage" class="form-label fw-bold">Isi pesan balasan *</label>
	  {!! form_textarea($conMessage) !!}
	</div>
	
	
	<div class="text-end mt-5">
		<br><br><br>
		{!! anchor(base_url().'inbox', 'Kembali', ['class' => "btn btn-light-primary font-weight-bold"]) !!}
		<button type="submit" class="btn btn-primary font-weight-bold shadow" id="sendConfirm">Kirimkan Pesan Balasan</button>
	</div>
	
	{!! form_close() !!}

		</div>
	</div>
	

</div>

@endsection

@section('javascript')
	<script src="{{ base_url() }}assets/vendor/ckeditor/ckeditor.js"></script>
	<script>
		CKEDITOR.replace('content', {
    toolbar: [
        ['Bold', 'Italic', 'Underline', 'Strike', 'TextColor', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink', '-','Format','Font','FontSize', '-', 'Source']
    ],
    height: 400,
    enterMode: 1,
    shiftEnterMode: 2
});
	</script>

<script>
$(document).ready(function(){

	$('#confirmation_form').on('submit', function(event){

		event.preventDefault();

		$.ajax({
		url:       "{{ base_url() }}inbox/validate-message",
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


				let timerInterval
				Swal.fire({
					icon: "success",
				  title: 'Sukses',
				  html: 'Pesan balasan anda berhasil dikirim.',
				  confirmButtonText: 'Oke, saya mengerti',
				  allowOutsideClick: false,
				  timer: 2000,
				  onBeforeOpen: () => {
				    
				  },
				  onClose: () => {
				    clearInterval(timerInterval)
				  }
				}).then((result) => {
				  if (
				    result.dismiss === Swal.DismissReason.timer
				  ) {
					window.location.href = "{{ base_url() }}inbox";
				  }
				});


			}

			if(data.error){
				$('#error_captcha').html(data.error);
				$("#form-contact").removeClass("bg-blur");
				document.getElementById("confirmation_form").style.cursor = "default";
				$('#sendConfirm').attr('disabled', false);

				Swal.fire({
				  icon: "error",
				  title: 'Error',
				  text: data.message,
				  allowOutsideClick: false
				});

		    }
			
		}

		});

	});



});

</script>
@endsection