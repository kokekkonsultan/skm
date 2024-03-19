@extends('include_frontend/template_frontend')

@php 
	$ci = get_instance();
@endphp

@section('style')

@endsection

@section('content')

<div class="main-content wow fadeIn" id="top" data-wow-duration="1s" data-wow-delay="0.5s">
    <section class="bg-100">
      <div class="container">
        <div class="overflow-hidden mb-4" data-zanim-timeline="{}" data-zanim-trigger="scroll">
          <h4 data-zanim-xs='{"delay":0.1}'>Bergabunglah Dengan Kami Sebagai Reseller</h4>
        </div>
        <div class="row">
          <div class="col-lg-8">
            <div class="card mb-6"> <img class="card-img-top" src="{{ base_url() }}assets/img/site/page/afiliasi-model.jpg" alt="new image" />
              <div class="card-body p-5">
                
                {!! $content->content_page !!}

                <div class="text-center mt-5">
                  <div class="gradient-button">
                    <a href="{{ base_url() }}form-pendaftaran-reseller" class="">Form Pendaftaran Reseller</a>
                  </div>
                </div>

              </div>
            </div>
            
          </div>
          <div class="col-lg-4 text-center ms-auto mt-5 mt-lg-0">
            
          </div>
        </div>
      </div>
    </section>
</div>

@endsection

@section('javascript')

@endsection