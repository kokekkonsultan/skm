@extends('include_backend/template_backend')

@php 
	$ci = get_instance();
@endphp

@section('style')

@endsection

@section('content')

<div class="container">
	<div class="card">
		<div class="card-header bg-secondary font-weight-bold">
			{{ $title }}
		</div>
		<div class="card-body">
			<div class="text-right">
			@php
				// echo anchor(base_url().'berlangganan/add', 'Tambah Berlangganan Paket', ['class' => 'btn btn-primary font-weight-bold shadow-lg mb-5']);
			@endphp
			</div>
			@php
				echo $table
			@endphp
		</div>
	</div>
</div>

@endsection

@section('javascript')

@endsection