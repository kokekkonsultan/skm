@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')
<link href="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
@endsection

@section('content')

<div class="container">
	<div class="card">
		<div class="card-header">
			Rekap Surveyor
		</div>
		<div class="card-body">

			<div class="row">
				<div class="col-md-6">
					@php
					$user_surveyor = $ci->db->get_where('users', ['is_parent' => $data_user->id]);
					@endphp
					<label for="" class="font-weight-bold">Pilih Akun Surveyor Induk</label>
					<select name="akun_surveyor" id="akun_surveyor" class="form-control kt_select2_1">
						<option value="">Please Select</option>
						@foreach ($user_surveyor->result() as $value)
							<option value="{{ $value->id }}">{{ $value->first_name }} {{ $value->last_name }}</option>
						@endforeach
					</select>
				</div>
				<div class="col-md-6">
					@php
					$user_anak = $ci->db->get_where('users', ['id_parent_induk' => $data_user->id]);
					@endphp
					<label for="" class="font-weight-bold">Pilih Instansi</label>
					<select name="akun_anak" id="akun_anak" class="form-control kt_select2_2">
						<option value="">Pilih Akun Surveyor Induk Terlebih Dahulu</option>
						@foreach ($user_anak->result() as $value)
							<!-- <option value="{{ $value->id }}">{{ $value->first_name }} {{ $value->last_name }}</option> -->
						@endforeach
					</select>
					<div id="progress-akun-anak"></div>

				</div>
			</div>
		
			{{--<select name="" id="">
				<option value="">Pilih Akun Surveyor Induk</option>
				@foreach ($data_surveyor_induk as $value)
					<option value=""></option>
				@endforeach
			</select>--}}

			<div id="progress-table"></div>

		</div>
	</div>
</div>
@endsection

@section('javascript')
<script src="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.js"></script>
<script>
"use strict";
// Class definition

	$('.kt_select2_1').select2({
	placeholder: "Pilih Akun Surveyor Induk"
	});

	$('.kt_select2_2').select2({
	placeholder: "Pilih Instansi"
	//placeholder: "Pilih Akun Surveyor Induk Terlebih Dahulu"
	});

	$(document).ready(function(){
		$("#akun_surveyor").change(function(){
			loadDataUnit()
		});
		$("#akun_anak").change(function(){
			loadData()
		});
	});

	function loadDataUnit()
	{
		var akun_surveyor = $("#akun_surveyor").val();
		$.ajax({
		url:"{{ base_url() . 'rekap-surveyor-induk/unit-satuan-kerja' }}",
		data:"akun_surveyor=" + akun_surveyor,
		success: function(html)
		{
			//$("#progress-akun-anak").html(html);
			$("#akun_anak").html(html);
			$("#progress-table").html('<div align="center" style="padding-top:20px; ">Silahkan pilih instansi terlebih dahulu</div>');
		}
		});
	}

	function loadData()
	{
		var akun_surveyor = $("#akun_surveyor").val();
		var akun_anak = $("#akun_anak").val();
		$.ajax({
		url:"{{ base_url() . 'rekap-surveyor-induk/proses' }}",
		data:"akun_surveyor=" + akun_surveyor + "&akun_anak=" + akun_anak,
		success: function(html)
		{
			$("#progress-table").html(html);
		}
		});
	}

</script>
@endsection
