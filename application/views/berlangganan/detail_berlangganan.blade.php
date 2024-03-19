@php 
	$ci = get_instance();
	$ci->load->helper('form');
@endphp

<div class="mb-3">
	<h5 class="text-primary">Data Klien</h5>
</div>
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
	<h5 class="text-primary">Paket yang terakhir kali dibeli</h5>
</div>
<table width="100%" class="table table-bordered">
	<tr>
		<th width="40%">Klasifikasi Survey</th>
		<td width="60%">
			{{ $pelanggan->nama_klasifikasi_survei }}
		</td>
	</tr>
	<tr>
		<th>Nama Paket</th>
		<td>{{ $pelanggan->nama_paket }}</td>
	</tr>
	<tr>
		<th>Panjang Hari</th>
		<td>{{ $pelanggan->panjang_hari }} Hari</td>
	</tr>
	<tr>
		<th>Harga Paket</th>
		<td>Rp. {{ number_format($pelanggan->harga_paket,2,',','.') }}</td>
	</tr>
	<tr>
		<th>Metode Pembayaran</th>
		<td>{{ $pelanggan->nama_metode_pembayaran }}</td>
	</tr>
	<tr>
		<th>Tanggal Aktif</th>
		<td>{{ date('d-m-Y', strtotime($pelanggan->tanggal_mulai)) }}</td>
	</tr>
	<tr>
		<th>Tanggal Kedaluarsa</th>
		<td>{{ date('d-m-Y', strtotime($pelanggan->tanggal_selesai)) }}</td>
	</tr>
	<tr>
		<th>Kode Lisensi</th>
		<td>{{ $pelanggan->kode_lisensi }}</td>
	</tr>
</table>

<div class="mt-5">
	@php
		echo form_open(base_url().'berlangganan/get-send-email', ['id' => 'confirmation_form']);
	@endphp


	<div class="text-right">
		<div>
			<label><input type="checkbox" name="is_email" value="1" checked="checked"> Kirimkan informasi</label>
		</div>
		<p>Pastikan email sudah benar dan aktif !</p>
		<button class="btn btn-light-primary font-weight-bold" id="custom-close" data-dismiss="modal" aria-hidden="true">Batal</button>
		<button type="submit" id="sendConfirm" class="btn btn-primary font-weight-bold" >Kirim Informasi Ke {{ $pelanggan->email }}</button>
	</div>

	@php
		echo form_close();
	@endphp

	@php
		// echo $_SESSION['input']['id_klien'];
	@endphp
	
</div>

<script>
$(document).ready(function(){
	// $('#loading_registration').hide();

	$('#confirmation_form').on('submit', function(event){
	
	event.preventDefault();
	
		$.ajax({
			url:"{{ base_url() }}berlangganan/get-send-email",
			method:"POST",
			data:$(this).serialize(),
			dataType:"json",
			beforeSend:function(){
				$('#sendConfirm').attr('disabled', 'disabled');

				Swal.fire({
					title: 'Memproses data',
                    html: 'Mohon tunggu sebentar. Sistem sedang menyiapkan data dan mengirim email kepada klien.',
                    onOpen: () => {
                      swal.showLoading()
                    }
				});
			},
			success:function(data){
				if(data.error){
					if(data.name_error != ''){
						$('#name_error').html(data.name_error);
					}
				}
			    
			    if(data.success){

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
						}
					);
			     
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