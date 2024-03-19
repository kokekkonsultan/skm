@extends('include_backend/template_backend')

@php 
	$ci = get_instance();
@endphp

@section('style')

@endsection

@section('content')

<div class="container">
	<div class="card">
		<div class="card-header">
			Data yang anda upload
		</div>
		<div class="card-body">
			
			<div class="mb-5">
				{!! $table !!}
			</div>
			<br>
			<p>Jika data yang ditampilkan diatas sesuai yang anda inginkan, anda bisa langsung menekan tombol dibawah ini untuk menyimpan.</p>
			<form method="post" action="{{ base_url() }}{{ $ci->session->userdata('username'); }}/{{ $ci->uri->segment(2) }}/data-prospek-survey/proses">
			<input type="hidden" name="namafile" value="{{ $nama_file_baru }}">
			<button type="submit" name="import" class="btn btn-primary font-weight-bold">Simpan data ini</button>

		</div>
	</div>

</div>
@endsection

@section('javascript')

@endsection