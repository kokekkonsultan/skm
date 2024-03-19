@php 
	$ci = get_instance();
@endphp


<table class="table table-striped">
	<tr>
		<th>Nama Paket</th>
		<td>{{ $paket->nama_paket }}</td>
	</tr>
	<tr>
		<th>Deskripsi Paket</th>
		<td>{!! $paket->deskripsi_paket !!}</td>
	</tr>
	<tr>
		<th>Tenggang Waktu</th>
		<td>{{ $paket->panjang_hari }} hari</td>
	</tr>
	<tr>
		<th>Jumlah User</th>
		<td>{{ $paket->jumlah_user }} Pengguna</td>
	</tr>
	<tr>
		<th>Jumlah Kuesioner</th>
		<td>{{ $paket->jumlah_kuesioner }} Kuesioner</td>
	</tr>
	<tr>
		<th>harga</th>
		<td>Rp. {{ $paket->harga_paket }}</td>
	</tr>
</table>