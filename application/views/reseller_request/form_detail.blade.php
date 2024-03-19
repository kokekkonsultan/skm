@php 
	$ci = get_instance();
@endphp

<table class="table">
	<tr>
		<th>Nama Lengkap</th>
		<td>
			{{ $data_resller_request->full_name }}
		</td>
	</tr>
	<tr>
		<th>Profesi atau jabatan anda bekerja</th>
		<td>
			{{ $data_resller_request->profession }}
		</td>
	</tr>
	<tr>
		<th>Organisasi atau tempat anda bekerja</th>
		<td>
			{{ $data_resller_request->organization }}
		</td>
	</tr>
	<tr>
		<th>Email</th>
		<td>
			{{ $data_resller_request->email }}
		</td>
	</tr>
	<tr>
		<th>Whatsapp</th>
		<td>
			{{ $data_resller_request->whatsapp }}
		</td>
	</tr>
	<tr>
		<th>Alasan mengikuti program reseller</th>
		<td>
			{{ $data_resller_request->reason }}
		</td>
	</tr>
</table>