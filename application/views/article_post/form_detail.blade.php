@php 
	$ci = get_instance();
@endphp

<div>
	<img src="{{ base_url() }}assets/img/article/medium/{{ $article->main_picture }}" alt="">
</div>
<h2 class="mt-5 text-center">{{ $article->title }}</h2>
<p>
	{!! $article->content_value !!}
</p>