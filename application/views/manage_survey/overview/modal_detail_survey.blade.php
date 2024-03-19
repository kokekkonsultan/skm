@php 
	$ci = get_instance();
@endphp

<div class="mb-5">
	Survei :
</div>
<table class="table table-striped">
	<tr>
		<th>Nama Survei</th>
		<td>{{ $survey->survey_name; }}</td>
	</tr>
	<tr>
		<th>Deskripsi</th>
		<td>{{ $survey->description; }}</td>
	</tr>
	<tr>
		<th>Periode Survei</th>
		<td>{{ date('d-m-Y', strtotime($survey->survey_start)); }} s/d {{ date('d-m-Y', strtotime($survey->survey_end)); }}</td>
	</tr>
	<tr>
		<th>Organisasi yang disurvei</th>
		<td>{{ $survey->organisasi; }}</td>
	</tr>
	<tr>
		<th>Metode Sampling</th>
		<td>{{ $survey->nama_sampling; }}</td>
	</tr>
	<tr>
		<th>Jumlah Populasi</th>
		<td>{{ $survey->jumlah_populasi; }} Responden</td>
	</tr>
	<tr>
		<th>Jumlah Sampling</th>
		<td>{{ $survey->jumlah_sampling; }} Responden</td>
	</tr>
</table>
<div class="text-right">
<a href="{{ base_url(); }}{{ $ci->session->userdata('username'); }}/{{ $survey->slug }}/do" title="" class="btn btn-primary font-weight-bold">Masuk ke kelola survei</a>
</div>
<br>
<hr>
<br>
<div class="mt-5 mb-5">
	Pemakaian Dari Paket :
</div>
<table class="table table-striped">
	<tr>
		<th>Nama Paket</th>
		<td>{{ $survey->nama_paket }}</td>
	</tr>
	<tr>
		<th>Deskripsi Paket</th>
		<td>{!! $survey->deskripsi_paket !!}</td>
	</tr>
	<tr>
		<th>Tenggang Waktu</th>
		<td>{{ $survey->panjang_hari }} hari</td>
	</tr>
	<tr>
		<th>Jumlah User</th>
		<td>{{ $survey->jumlah_user }} Pengguna</td>
	</tr>
	<tr>
		<th>Jumlah Kuesioner</th>
		<td>{{ $survey->jumlah_kuesioner }} Kuesioner</td>
	</tr>
</table>