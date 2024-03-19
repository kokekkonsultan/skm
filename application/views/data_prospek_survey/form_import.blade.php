@extends('include_backend/template_backend')

@php 
	$ci = get_instance();
@endphp

@section('style')

@endsection

@section('content')
<div class="container mt-5">

	<div class="card">
		<div class="card-header">
			Form Import
		</div>
		<div class="card-body">

			<div class="mb-5">
				<a href="{{ base_url() }}{{ $ci->session->userdata('username'); }}/{{ $ci->uri->segment(2) }}/data-prospek-survey/download-template" target="_blank">Download file excel template</a>
				<div class="mt-5">
					Silahkan anda download file template diatas, kemuadian anda isi sesuai kolom yang disediakan. Pastikan anda mengikuti format pengisian yang ada. Setelah anda isi lakukan proses upload pada form dibawah ini.
				</div>
			</div>
			<br><br>
			<form method="post" action="{{ base_url() }}{{ $ci->session->userdata('username'); }}/{{ $ci->uri->segment(2) }}/data-prospek-survey/preview" enctype="multipart/form-data">

			<p>
				<label for="" class="font-weight-bold">Pilih file excel yang sudah anda isi <span class="text-danger">*</span></label><br>
				<input type="file" name="file" required>
			</p>
			<button type="submit" name="preview" class="btn btn-primary font-weight-bold">Upload dan preview data excel</button>
			<a href="{{ base_url() }}{{ $ci->session->userdata('username'); }}/{{ $ci->uri->segment(2) }}/data-prospek-survey" class="btn btn-secondary font-weight-bold">Batal</a>

			</form>

		</div>
	</div>

</div>
@endsection

@section('javascript')

@endsection