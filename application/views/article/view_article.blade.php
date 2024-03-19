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
            <div data-zanim-xs='{"delay":0}'><a class="d-inline-block text-500" href="#">{{ $nama_penulis }}</a>, &nbsp;<a class="d-inline-block text-500" href="#">{{ $tanggal_post }}</a></div>
            <h4 data-zanim-xs='{"delay":0.1}'>{{ $article_data->title }}</h4>
          </div>
          <div class="row">
            <div class="col-lg-8">
              <div class="card mb-6"> <img class="card-img-top" src="{{ base_url() }}assets/img/article/{{ $article_data->main_picture }}" alt="new image" />
                <div class="card-body p-5">
                  
                  {!! $article_data->content_value !!}

                </div>
              </div>
              
            </div>
            <div class="col-lg-4 text-center ms-auto mt-5 mt-lg-0">
              <div class="px-2">
                <div class="card mb-5">
                  <div class="card-body p-5">
                    <div class="overflow-hidden" data-zanim-timeline="{}" data-zanim-trigger="scroll"><img class="rounded-circle" style="width: 107px;" data-zanim-xs='{"delay":0}' src="{{ base_url() }}assets/klien/foto_profile/{{ $foto_profile }}" alt="Author" />
                      <h5 class="text-capitalize mt-3 mb-0" data-zanim-xs='{"delay":0.1}'>{{ $nama_penulis }}</h5>
                      <p class="mb-0 mt-3" data-zanim-xs='{"delay":0.2}'>{{ $deskripsi_penulis }}</p>
                      <div class="pt-4" data-zanim-xs='{"delay":0.3}'><a class="d-inline-block" href="#!"><span class="fab fa-linkedin fs-2 mx-2 text-400"></span></a><a class="d-inline-block" href="#!"><span class="fab fa-twitter-square fs-2 mx-2 text-400"></span></a><a class="d-inline-block" href="#!"><span class="fab fa-facebook-square fs-2 mx-2 text-400"></span></a></div>
                    </div>
                  </div>
                </div>
                <div class="mb-5">
                  <h5 class="mb-4">Artikel Terkait</h5>
                  <div class="bg-white pb-5 rounded-3">
                    <div class="swiper news-slider pb-4" data-swiper='{"loop":true,"slidesPerView":1,"pagination":{"el":".swiper-pagination","type":"bullets","clickable":true}}'>
                      <div class="swiper-wrapper">
                        
                        @foreach ($artikel_terkait->result() as $value)
                        <div class="swiper-slide">
                          <div class="card"><a href="{{ base_url() }}article/post/{{ $value->slug }}"><img class="card-img-top" src="{{ base_url() }}assets/img/article/medium/{{ $value->main_picture }}" alt="Featured Image" /></a>
                            <div class="card-body" data-zanim-timeline="{}" data-zanim-trigger="scroll">
                              <div class="overflow-hidden"><a href="{{ base_url() }}article/post/{{ $value->slug }}">
                                  <h5 data-zanim-xs='{"delay":0}'>{{ $value->title }}</h5>
                                </a></div>
                              <div class="overflow-hidden">
                                <p class="text-500" data-zanim-xs='{"delay":0.1}'>{{ $value->alias_name_writter }}</p>
                              </div>
                              <div class="overflow-hidden">
                                <p class="mt-3" data-zanim-xs='{"delay":0.2}'>{!! word_limiter($value->content_value, 10); !!}</p>
                              </div>
                              <div class="overflow-hidden">
                                <div class="d-inline-block" data-zanim-xs='{"delay":0.3}'><a class="d-flex align-items-center" href="{{ base_url() }}article/post/{{ $value->slug }}">Learn More<div class="overflow-hidden ms-2" data-zanim-xs='{"from":{"opacity":0,"x":-30},"to":{"opacity":1,"x":0},"delay":0.8}'><span class="d-inline-block fw-medium">&xrarr;</span></div></a></div>
                              </div>
                            </div>
                          </div>
                        </div>
                        @endforeach


                      </div>
                      <div class="swiper-pagination"></div>
                    </div>
                  </div>
                </div>
                <div class="card">
                  <div class="card-body p-5">
                    <h5>Kategori</h5>
                    <ul class="nav tags mt-3 fs--1">
                    	@foreach ($all_category->result() as $value)
                    		<li><a class="btn btn_red m-1 p-2" href="#!">{{ $value->category_name }}</a></li>
                    	@endforeach
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div><!-- end of .container-->
      </section>


</div>

@endsection

@section('javascript')

@endsection