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
			@php
				echo $table
			@endphp
		</div>
	</div>
</div>

@endsection

@section('javascript')

@endsection