@extends('include_frontend/template_frontend')

@php
$ci = get_instance();
@endphp

@section('style')

@endsection

@section('content')
<div class="main-content wow fadeIn" id="top" data-wow-duration="1s" data-wow-delay="0.5s">
    <div id="about" class="about-us section">
        <div class="row">
            @foreach ($posts as $value)
            <div class="col-lg-6">
                <div class="box-item">
                    <h4><a href="{{ base_url() }}article/post/{{ $value->slug }}">{{ $value->title }}</a></h4>
                    <p>{!! word_limiter($value->content_value, 10); !!}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@section('javascript')

@endsection