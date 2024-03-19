@php
$ci = get_instance();
$ci->load->helper('form');
@endphp

<table width="100%" class="table table-bordered">
	<tr>
		<th width="40%">Nama Lengkap</th>
		<td width="60%">
			{{ $pelanggan->first_name }} {{ $pelanggan->last_name }}
		</td>
	</tr>
	<tr>
		<th>Username</th>
		<td>{{ $pelanggan->username }}</td>
	</tr>
	<tr>
		<th>Password Awal</th>
		<td>{{ $pelanggan->re_password }}</td>
	</tr>
	<tr>
		<th>Email</th>
		<td>{{ $pelanggan->email }}</td>
	</tr>
	<tr>
		<th>HP</th>
		<td>{{ $pelanggan->phone }}</td>
	</tr>
</table>

<div class="mt-5">
	@php
	echo form_open(base_url().'pengguna-klien/get-send-email', ['id' => 'confirmation_form']);
	@endphp


	<!-- <div class="text-right">
		<div>
			<label><input type="checkbox" name="is_email" value="1" checked="checked"> Kirimkan informasi</label>
		</div>
		<p>Pastikan email sudah benar dan aktif !</p>
		<button class="btn btn-light-primary font-weight-bold" id="custom-close" data-dismiss="modal" aria-hidden="true">Batal</button>
		<button type="submit" id="sendConfirm" class="btn btn-primary font-weight-bold">Kirim Informasi Ke {{ $pelanggan->email }}</button>
	</div> -->

	@php
	echo form_close();
	@endphp
	{{-- @php
		echo anchor('url', '<i class="fas fa-paper-plane"></i> Informasikan akun ke klien', ['class' => 'btn btn-light-primary font-weight-bold shadow-lg btn-block']);
	@endphp --}}

</div>

<script>
	$(document).ready(function() {
		// $('#loading_registration').hide();

		$('#confirmation_form').on('submit', function(event) {

			event.preventDefault();

			$.ajax({
				url: "{{ base_url() }}pengguna-klien/get-send-email",
				method: "POST",
				data: $(this).serialize(),
				dataType: "json",
				beforeSend: function() {
					$('#sendConfirm').attr('disabled', 'disabled');

					Swal.fire({
						title: 'Memproses data',
						html: 'Mohon tunggu sebentar. Sistem sedang menyiapkan data dan mengirim email kepada klien.',
						onOpen: () => {
							swal.showLoading()
						}
					});
				},
				success: function(data) {
					if (data.error) {
						if (data.name_error != '') {
							$('#name_error').html(data.name_error);
						}
					}

					if (data.success) {

						// $('#success_message').html(data.success);
						// $('#name_error').html('');
						$('#confirmation_form')[0].reset();

						// $('#main_form').hide();
						// $('#reg_footer').hide();
						// $('#loading_registration').show();

						// $('#modal_userDetail').modal('hide');
						$("#custom-close").click();


						Swal.fire({
							type: "success",
							title: "Informasi",
							text: "Berhasil menyampaikan informasi berlangganan melalui email",
							confirmButtonText: "Ya",
						});

						/*setTimeout(function(){  
							$('#modal_userDetail').modal('hide');
						}, 1000);*/
					}

					$('#sendConfirm').attr('disabled', true);
					table.ajax.reload();



				}
			})
		});
	});
</script>