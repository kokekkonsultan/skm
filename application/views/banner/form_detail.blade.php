@php 
	$ci = get_instance();
@endphp 

<div class="text-center">
	<img src="{{ base_url() }}assets/img/banner/medium/{{ $banner->banner_file }}" alt="">
</div>

<h2>
	{{ $banner->banner_title }}
</h2>
<h5>
	{{ $banner->banner_description }}
</h5>
<div class="mt-5">
	@if ($banner->button_read_more_active)
	<button type="button" class="btn btn-dark btn-lg font-weight-bold">Baca Selengkapnya ></button> 
	@endif
	@if ($banner->button_contact_active)
	<button type="button" class="btn btn-warning btn-lg font-weight-bold">Kontak Kami ></button>
	@endif
</div>