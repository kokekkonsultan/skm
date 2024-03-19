@extends('include_frontend/template_frontend')

@php
$ci = get_instance();
@endphp

@section('style')

@endsection

@section('content')

  <div class="main-banner wow fadeIn" id="top" data-wow-duration="1s" data-wow-delay="0.5s">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="row">
            <div class="col-lg-6 align-self-center">
              <div class="left-content show-up header-text wow fadeInLeft" data-wow-duration="1s" data-wow-delay="1s">
                <div class="row">
                  <div class="col-lg-12">
                    <h2 class="text-dark">Survei Kepuasan Masyarakat</h2>
                    <p class="text-dark">Membantu organisasi anda dalam melakukan Survei Kepuasan Masyarakat secara online Pada Unit Penyelenggara Pelayanan Publik.</p>
                  </div>
                  <div class="col-lg-12">
                    <div class="white-button first-button scroll-to-section">
                      <a href="{{ base_url() }}article/post/survei-kepuasan-masyarakat-berbasis-web">Baca Selengkapnya</a>
                    </div>
                    <div class="white-button first-button scroll-to-section">
                      <a href="{{ base_url() }}contact">Hubungi Kami</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="right-image wow fadeInRight" data-wow-duration="1s" data-wow-delay="0.5s">
                <img src="{{ base_url() }}assets/themes/chain/assets/images/slider-dec.png" alt="">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div id="services" class="services section">
    <div class="container">
      <div class="row">
        <div class="col-lg-8 offset-lg-2">
          <div class="section-heading  wow fadeInDown" data-wow-duration="1s" data-wow-delay="0.5s">
            <h4><em>E-SKM</em> - Layanan Survei Kepuasan Masyarakat.</h4>
            <img src="{{ base_url() }}assets/themes/chain/assets/images/heading-line-dec.png" alt="">
            <p>Menyediakan layanan survei kepuasan masyarakat berbasis web bagi institusi pemerintah dan perusahaan swasta.</p>
          </div>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row">
        <div class="col-lg-3">
          <div class="service-item first-service">
            <div class="icon"></div>
            <h4>Metode Survei</h4>
            <p>Metode survei yang efektif dan efisien karena berbasis web.</p>
            <div class="text-button">
              <a href="{{ base_url() }}">Baca Selengkapnya <i class="fa fa-arrow-right"></i></a>
            </div>
          </div>
        </div>
        <div class="col-lg-3">
          <div class="service-item second-service">
            <div class="icon"></div>
            <h4>Pertanyaan Survei</h4>
            <p>Admin dapat menambah dan mengurangi pertanyaan survei. Survei dilengkapi dengan data kualitatif untuk mempermudah pengambilan kesimpulan dan tindakan perbaikan.</p>
            <div class="text-button">
              <a href="{{ base_url() }}">Baca Selengkapnya <i class="fa fa-arrow-right"></i></a>
            </div>
          </div>
        </div>
        <div class="col-lg-3">
          <div class="service-item third-service">
            <div class="icon"></div>
            <h4>Laporan</h4>
            <p>Perhitungan survei, grafik, pelaporan otomatis.</p>
            <div class="text-button">
              <a href="{{ base_url() }}">Baca Selengkapnya <i class="fa fa-arrow-right"></i></a>
            </div>
          </div>
        </div>
        <div class="col-lg-3">
          <div class="service-item fourth-service">
            <div class="icon"></div>
            <h4>Pemeliharaan Aplikasi</h4>
            <p>Bebas pemeliharaan dan pengembangan aplikasi.</p>
            <div class="text-button">
              <a href="{{ base_url() }}">Baca Selengkapnya <i class="fa fa-arrow-right"></i></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div id="about" class="about-us section">
    <div class="container">
      <div class="row">
        <div class="col-lg-6 align-self-center">
          <div class="section-heading">
            <h4><em>Dasar Hukum & Sasaran</em> Survei Kepuasan Masyarakat</h4>
            <img src="{{ base_url() }}assets/themes/chain/assets/images/heading-line-dec.png" alt="">
            <p>Salah satu upaya untuk meningkatkan kualitas pelayanan publik sebagaimana diamanatkan dalam Undang-undang Nomor 25 Tahun 2009 tentang Pelayanan Publik adalah perlunya disusun studi mengenai kepuasan masyarakat sebagai evaluasi terhadap kinerja pelaksanaan pelayanan publik secara berkala dan berkelanjutan. Peraturan Menteri Pendayagunaan Aparatur Negara dan Reformasi Birokrasi Nomor 14 Tahun 2017 tentang Pedoman Penyusunan Survei Kepuasan Masyarakat Unit Penyelenggara Pelayanan Publik, merupakan solusi bagi unit penyelenggara pelayanan publik dalam merencanakan anggaran kegiatan Survei Kepuasan Masyarakat. Peraturan ini merupakan acuan bagi seluruh unit penyelenggara pelayanan publik dalam melakukan evaluasi kepuasan atas kinerja penyelenggaraan pelayanan publik.</p>
            <p>
            <p>Sasaran dari survei kepuasan masyarakat berbasis web ini adalah:</p>
              <ol>
                <li>Meningkatkan kemampuan Sumberdaya Manusia dalam melaksanakan Survei Kepuasan Masyarakat yang sistematis dan efektif sesuai dengan Peraturan Menteri Pendayagunaan Aparatur Negara dan Reformasi Birokrasi Nomor 14 Tahun 2017 tentang Pedoman Penyusunan Survei Kepuasan Masyarakat Unit Penyelenggara Pelayanan Publik.</li>
                <li>Meningkatkan kemampuan Sumberdaya Manusia dalam mengoperasikan Software Sistem Informasi Survei Kepuasan Masyarakat Berbasis Web.</li>
                <li>Meningkatkan efektifitas dan efisiensi pekerjaan dari manual/berbasis kertas menjadi cara kerja yang berbasis teknologi informasi berupa aplikasi web.</li>
              </ol>
            </p>
            <p>
              Penyusunan Survei Kepuasan Masyarakat dilaksanakan berdasarkan dasar hukum dan peraturan perundangan yang digunakan yakni, sebagai berikut:
            </p>
          </div>
          <div class="row">
            <div class="col-lg-6">
              <div class="box-item">
                <h4><a href="#">Undang-Undang RI Nomor 28 Tahun 1999</a></h4>
                <p>Penyelenggaraan Pemerintahan yang Bersih dan Bebas KKN</p>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="box-item">
                <h4><a href="#">Undang-Undang RI Nomor 25 Tahun 2009</a></h4>
                <p>Pelayanan Publik (Lembaran Negara RI Tahun 2009 Nomor 112 & 5038)</p>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="box-item">
                <h4><a href="#">Instruksi Presiden Republik Indonesia Nomor 1 Tahun 1995</a></h4>
                <p>Perbaikan & Peningkatan Mutu Pelayanan</p>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="box-item">
                <h4><a href="#">Instruksi Presiden Republik Indonesia Nomor 5 Tahun 2004</a></h4>
                <p>Percepatan Pemberantasan Korupsi</p>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="box-item">
                <h4><a href="#">Kemenpan Nomor. 63/KEP/M.PAN/7/2003</a></h4>
                <p>Pedoman Umum Penyelenggaraan Pelayanan Publik</p>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="box-item">
                <h4><a href="#">Permenpanrb Nomor 14 Tahun 2017</a></h4>
                <p>Pedoman SKM terhadap Penyelenggaraan Pelayanan Publik</p>
              </div>
            </div>
            <div class="col-lg-12">
              <p></p>
              <div class="gradient-button">
                <a href="{{ base_url() }}contact">Mulai Mendaftar</a>
              </div>
              <span></span>
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="right-image">
            <img src="{{ base_url() }}assets/themes/chain/assets/images/about-right-dec.png" alt="">
          </div>
        </div>
      </div>
    </div>
  </div>

  <div id="clients" class="the-clients">
    <div class="container">
      <div class="row">
        <div class="col-lg-8 offset-lg-2">
          <div class="section-heading">
            <h4><em>Unsur</em> E-SKM</h4>
            <img src="{{ base_url() }}assets/themes/chain/assets/images/heading-line-dec.png" alt="">
            <p>Pada dasarnya Kuesioner Survei Kepuasan Masyarakat disusun berdasarkan prinsip pelayanan sebagaimana telah ditetapkan dalam Peraturan Menteri Pendayagunaan Aparatur Negara dan Reformasi Birokrasi Republik Indonesia Nomor 14 Tahun 2017 tentang Pedoman Penyusunan Survei Kepuasan Masyarakat Unit Penyelenggara Pelayanan Publik terdiri dari pertanyaan yang mencangkup 9 (sembilan) unsur pelayanan</p>
          </div>
        </div>
        <div class="col-lg-12">
          <div class="naccs">
            <div class="grid">
              <div class="row">
                <div class="col-lg-7 align-self-center">
                  <div class="menu">
                    <div class="first-thumb active">
                      <div class="thumb">
                        <div class="row">
                          <div class="col-lg-8 col-sm-8 col-12">
                            <h4><b>Persyaratan</b></h4>
                            <!-- <span class="date"></span> -->
                          </div>
                          <div class="col-lg-4 col-sm-4 col-12">
                              
                          </div>
                        </div>
                      </div>
                    </div>
                    <div>
                      <div class="thumb">
                        <div class="row">
                          <div class="col-lg-8 col-sm-8 col-12">
                          <h4><b>Sistem, Mekanisme, dan Prosedur</b></h4>
                            <!-- <span class="date"></span> -->
                          </div>
                          <div class="col-lg-4 col-sm-4 col-12">
                              
                          </div>
                        </div>
                      </div>
                    </div>
                    <div>
                      <div class="thumb">
                        <div class="row">
                          <div class="col-lg-8 col-sm-8 col-12">
                            <h4><b>Waktu Penyelesaian</b></h4>
                            <!-- <span class="date"></span> -->
                          </div>
                          <div class="col-lg-4 col-sm-4 col-12">
                              
                          </div>
                        </div>
                      </div>
                    </div>
                    <div>
                      <div class="thumb">
                        <div class="row">
                          <div class="col-lg-8 col-sm-8 col-12">
                            <h4><b>Biaya/Tarif</b></h4>
                            <!-- <span class="date"></span> -->
                          </div>
                          <div class="col-lg-4 col-sm-4 col-12">
                          </div>
                        </div>
                      </div>
                    </div>
                    <div>
                      <div class="thumb">
                        <div class="row">
                          <div class="col-lg-8 col-sm-8 col-12">
                            <h4><b>Produk Spesifikasi dan Jenis Pelayanan</b></h4>
                            <!-- <span class="date"></span> -->
                          </div>
                          <div class="col-lg-4 col-sm-4 col-12">
                          </div>
                        </div>
                      </div>
                    </div>
                    <div>
                      <div class="thumb">
                        <div class="row">
                          <div class="col-lg-8 col-sm-8 col-12">
                            <h4><b>Kompetensi Pelaksana</b></h4>
                            <!-- <span class="date"></span> -->
                          </div>
                          <div class="col-lg-4 col-sm-4 col-12">
                          </div>
                        </div>
                      </div>
                    </div>
                    <div>
                      <div class="thumb">
                        <div class="row">
                          <div class="col-lg-8 col-sm-8 col-12">
                            <h4><b>Perilaku Pelaksana</b></h4>
                            <!-- <span class="date"></span> -->
                          </div>
                          <div class="col-lg-4 col-sm-4 col-12">
                          </div>
                        </div>
                      </div>
                    </div>
                    <div>
                      <div class="thumb">
                        <div class="row">
                          <div class="col-lg-8 col-sm-8 col-12">
                            <h4><b>Penanganan Pengaduan, Saran, dan Masukan</b></h4>
                            <!-- <span class="date"></span> -->
                          </div>
                          <div class="col-lg-4 col-sm-4 col-12">
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="last-thumb">
                      <div class="thumb">
                        <div class="row">
                          <div class="col-lg-8 col-sm-8 col-12">
                            <h4><b>Sarana dan Prasarana</b></b></h4>
                            <!-- <span class="date"></span> -->
                          </div>
                          <div class="col-lg-4 col-sm-4 col-12">
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div> 
                <div class="col-lg-5">
                  <ul class="nacc">
                    <li class="active">
                      <div>
                        <div class="thumb">
                          <div class="row">
                            <div class="col-lg-12">
                              <div class="client-content">
                                <img src="{{ base_url() }}assets/themes/chain/assets/images/quote.png" alt="">
                                <p>“Syarat yang harus dipenuhi dalam pengurusan suatu jenis pelayanan, baik persyaratan teknis maupun administratif”</p>
                              </div>
                              <div class="down-content">
                                
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </li>
                    <li>
                      <div>
                        <div class="thumb">
                          <div class="row">
                            <div class="col-lg-12">
                              <div class="client-content">
                                <img src="{{ base_url() }}assets/themes/chain/assets/images/quote.png" alt="">
                                <p>“Tata cara pelayanan yang dibakukan bagi pemberi dan penerima pelayanan, termasuk pengaduan.”</p>
                              </div>
                              <div class="down-content">
                                
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </li>
                    <li>
                      <div>
                        <div class="thumb">
                          <div class="row">
                            <div class="col-lg-12">
                              <div class="client-content">
                                <img src="{{ base_url() }}assets/themes/chain/assets/images/quote.png" alt="">
                                <p>“Jangka waktu yang diperlukan untuk menyelesaikan seluruh proses pelayanan dari setiap jenis pelayanan”</p>
                              </div>
                              <div class="down-content">
                                
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </li>
                    <li>
                      <div>
                        <div class="thumb">
                          <div class="row">
                            <div class="col-lg-12">
                              <div class="client-content">
                                <img src="{{ base_url() }}assets/themes/chain/assets/images/quote.png" alt="">
                                <p>“Ongkos yang dikenakan kepada penerima layanan dalam mengurus dan/atau memperoleh pelayanan dari penyelenggara yang besarnya ditetapkan berdasarkan kesepakatan antara penyelenggara dan masyarakat.”</p>
                              </div>
                              <div class="down-content">
                                
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </li>
                    <li>
                      <div>
                        <div class="thumb">
                          <div class="row">
                            <div class="col-lg-12">
                              <div class="client-content">
                                <img src="{{ base_url() }}assets/themes/chain/assets/images/quote.png" alt="">
                                <p>“Hasil pelayanan yang diberikan dan diterima sesuai dengan ketentuan yang telah ditetapkan. Produk pelayanan ini merupakan hasil dari setiap spesifikasi jenis pelayanan.”</p>
                              </div>
                              <div class="down-content">
                                
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </li>

                    <li>
                      <div>
                        <div class="thumb">
                          <div class="row">
                            <div class="col-lg-12">
                              <div class="client-content">
                                <img src="{{ base_url() }}assets/themes/chain/assets/images/quote.png" alt="">
                                <p>“Kemampuan yang harus dimiliki oleh pelaksana meliputi pengetahuan, keahlian, keterampilan, dan pengalaman.”</p>
                              </div>
                              <div class="down-content">
                                
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </li>
                    <li>
                      <div>
                        <div class="thumb">
                          <div class="row">
                            <div class="col-lg-12">
                              <div class="client-content">
                                <img src="{{ base_url() }}assets/themes/chain/assets/images/quote.png" alt="">
                                <p>“Sikap petugas dalam memberikan pelayanan.”</p>
                              </div>
                              <div class="down-content">
                                
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </li>
                    <li>
                      <div>
                        <div class="thumb">
                          <div class="row">
                            <div class="col-lg-12">
                              <div class="client-content">
                                <img src="{{ base_url() }}assets/themes/chain/assets/images/quote.png" alt="">
                                <p>“Tata cara pelaksanaan penanganan pengaduan dan tindak lanjut.”</p>
                              </div>
                              <div class="down-content">
                                
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </li>
                    <li>
                      <div>
                        <div class="thumb">
                          <div class="row">
                            <div class="col-lg-12">
                              <div class="client-content">
                                <img src="{{ base_url() }}assets/themes/chain/assets/images/quote.png" alt="">
                                <p>“Sarana adalah segala sesuatu yang dapat dipakai sebagai alat dalam mencapai maksud dan tujuan. Sementara Prasarana adalah segala sesuatu yang meru-pakan penunjang utama terselenggaranya suatu proses (usaha, pembangunan, proyek). Sarana digunakan untuk benda yang bergerak (komputer, mesin) dan prasa-rana untuk benda yang tidak bergerak (gedung).”</p>
                              </div>
                              <div class="down-content">
                                
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </li>

                  </ul>
                </div>          
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div id="pricing" class="pricing-tables">
    <div class="container">
      <div class="row">
        <div class="col-lg-8 offset-lg-2">
          <div class="section-heading">
            <h4><em>Harga Paket</em> E-SKM</h4>
            <img src="{{ base_url() }}assets/themes/chain/assets/images/heading-line-dec.png" alt="">
            <p>Kami menawarkan beberapa paket sesuai kebutuhan klien, berikut ini adalah paket yang populer.</p>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="pricing-item-regular">
            <span class="price"></span>
            <h4>Paket 2</h4>
            <div class="icon">
              <img src="{{ base_url() }}assets/themes/chain/assets/images/pricing-table-01.png" alt="">
            </div>
            <ul>
              <li>3 Kuesioner</li>
              <li>1 Pengguna</li>
              <li>1 Tahun</li>
            </ul>
            <div class="border-button">
              <a href="{{ base_url() }}contact">Beli Paket Ini Sekarang</a>
            </div>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="pricing-item-pro">
            <span class="price"></span>
            <h4>Paket 1</h4>
            <div class="icon">
              <img src="{{ base_url() }}assets/themes/chain/assets/images/pricing-table-01.png" alt="">
            </div>
            <ul>
              <li>1 Kuesioner</li>
              <li>1 Pengguna</li>
              <li>1 Tahun</li>
            </ul>
            <div class="border-button">
              <a href="{{ base_url() }}contact">Beli Paket Ini Sekarang</a>
            </div>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="pricing-item-regular">
            <span class="price"></span>
            <h4>Paket 3</h4>
            <div class="icon">
              <img src="{{ base_url() }}assets/themes/chain/assets/images/pricing-table-01.png" alt="">
            </div>
            <ul>
              <li>6 Kuesioner</li>
              <li>1 Pengguna</li>
              <li>1 Tahun</li>
            </ul>
            <div class="border-button">
              <a href="{{ base_url() }}contact">Beli Paket Ini Sekarang</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div> 



{{-- <section class="py-0">
  <div class="swiper theme-slider min-vh-100" data-swiper='{"loop":true,"allowTouchMove":false,"autoplay":{"delay":5000},"effect":"fade","speed":800}'>
    <div class="swiper-wrapper">

      @foreach ($banner->result() as $value)
      <div class="swiper-slide" data-zanim-timeline="{}">
        <div class="bg-holder" style="background-image:url({{ base_url() }}assets/img/banner/{{ $value->banner_file }});"></div>
        <div class="container">
          <div class="row min-vh-100 py-8 align-items-center" data-inertia='{"weight":1.5}'>
            <div class="col-sm-8 col-lg-7 px-5 px-sm-3">
              <div class="overflow-hidden">
                <h1 class="fs-4 fs-md-5 lh-1" data-zanim-xs='{"delay":0}'>{{ $value->banner_title }}</h1>
              </div>
              <div class="overflow-hidden">
                <p class="text-primary pt-4 mb-5 fs-1 fs-md-2 lh-xs" data-zanim-xs='{"delay":0.1}'>{{ $value->banner_description }}</p>
              </div>
              <div class="overflow-hidden">
                <div data-zanim-xs='{"delay":0.2}'>
                  @if ($value->button_read_more_active == '1')
                  <a class="btn btn-primary me-3 mt-3" href="{{ $value->read_more_link }}">Baca selengkapnya<span class="fas fa-chevron-right ms-2"></span></a>
                  @endif
                  @if ($value->button_contact_active == '1')
                  <a class="btn btn-warning mt-3" href="{{ $value->contact_link }}">Kontak Kami<span class="fas fa-chevron-right ms-2"></span></a>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      @endforeach

    </div>
    <div class="swiper-nav">
      <div class="swiper-button-prev"><span class="fas fa-chevron-left"></span></div>
      <div class="swiper-button-next"><span class="fas fa-chevron-right"></span></div>
    </div>
  </div>
</section> --}}

{{-- <section class="bg-white text-center">
  <div class="container">
    <div class="row justify-content-center text-center">
      <div class="col-10 col-md-6">
        <h3 class="fs-2 fs-lg-3">{{ $home_config->website_title }}</h3>
        <p class="px-lg-4 mt-3">{{ $home_config->website_description }}</p>
        <hr class="short" data-zanim-xs='{"from":{"opacity":0,"width":0},"to":{"opacity":1,"width":"4.20873rem"},"duration":0.8}' data-zanim-trigger="scroll" />
      </div>
    </div>
  </div>
</section>


<section class="bg-100">
  <div class="container">
    <div class="text-center mb-6">
      <h3 class="fs-2 fs-md-3">{{ $home_config->website_object_title }}</h3>
      <hr class="short" data-zanim-xs='{"from":{"opacity":0,"width":0},"to":{"opacity":1,"width":"4.20873rem"},"duration":0.8}' data-zanim-trigger="scroll" />
    </div>
    <div class="row g-0 position-relative mb-4 mb-lg-0">
      <div class="col-lg-6 py-3 py-lg-0 mb-0 position-relative" style="min-height:400px;">
        <div class="bg-holder rounded-ts-lg rounded-te-lg rounded-lg-te-0  " style="background-image:url({{ base_url() }}assets/themes/elixir/v3.0.0/assets/img/our_1.jpg);"></div>
      </div>
      <div class="col-lg-6 px-lg-5 py-lg-6 p-4 my-lg-0 bg-white rounded-bs-lg rounded-lg-bs-0 rounded-be-lg rounded-lg-be-0 rounded-lg-te-lg ">
        <div class="elixir-caret d-none d-lg-block"></div>
        <div class="d-flex align-items-center h-100">
          <div data-zanim-timeline="{}" data-zanim-trigger="scroll">
            {!! $home_config->website_object_1 !!}
            <div class="overflow-hidden">
              
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row g-0 position-relative mb-4 mb-lg-0">
      <div class="col-lg-6 py-3 py-lg-0 mb-0 position-relative order-lg-2" style="min-height:400px;">
        <div class="bg-holder rounded-ts-lg rounded-te-lg rounded-lg-te-0  rounded-lg-ts-0" style="background-image:url({{ base_url() }}assets/themes/elixir/v3.0.0/assets/img/our_3.jpg);"></div>
      </div>
      <div class="col-lg-6 px-lg-5 py-lg-6 p-4 my-lg-0 bg-white rounded-bs-lg rounded-lg-bs-0 rounded-be-lg  rounded-lg-be-0">
        <div class="elixir-caret d-none d-lg-block"></div>
        <div class="d-flex align-items-center h-100">
          <div data-zanim-timeline="{}" data-zanim-trigger="scroll">
            {!! $home_config->website_object_2 !!}
            <div class="overflow-hidden">
              <div data-zanim-xs='{"delay":0.2}'><a class="d-flex align-items-center" href="{{ base_url() }}services/sistem-manajemen-anti-penyuapan-iso-37001">Baca Selengkapnya<div class="overflow-hidden ms-2"><span class="d-inline-block" data-zanim-xs='{"from":{"opacity":0,"x":-30},"to":{"opacity":1,"x":0},"delay":0.8}'>&xrarr;</span></div></a>
          </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row g-0 position-relative mb-4 mb-lg-0">
      <div class="col-lg-6 py-3 py-lg-0 mb-0 position-relative" style="min-height:400px;">
        <div class="bg-holder rounded-ts-lg rounded-te-lg rounded-lg-te-0 rounded-lg-ts-0 rounded-bs-0 rounded-lg-bs-lg " style="background-image:url({{ base_url() }}assets/themes/elixir/v3.0.0/assets/img/our_2.jpg);"></div>
      </div>
      <div class="col-lg-6 px-lg-5 py-lg-6 p-4 my-lg-0 bg-white rounded-bs-lg rounded-lg-bs-0 rounded-be-lg  ">
        <div class="elixir-caret d-none d-lg-block"></div>
        <div class="d-flex align-items-center h-100">
          <div data-zanim-timeline="{}" data-zanim-trigger="scroll">
            {!! $home_config->website_object_3 !!}
            <div class="overflow-hidden">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="bg-white">
  <div class="container">
    <div class="row">
      <div class="col-md-6">
        <h4>Tentang & Dasar Hukum E-SKM</h4>
        <p>
          SURVEI KEPUASAN MASYARAKAT (SKM) adalah data dan informasi tentang tingkat kepuasan masyarakat yang diperoleh dari hasil pengukuran secara kuantitatif dan kualitatif atas pendapat masyarakat dalam memperoleh pelayanan dari aparatur penyelenggara pelayanan publik. Survei Kepuasan Masyarakat merupakan tolok ukur untuk menilai tingkat kualitas pelayanan yang diberikan oleh Unit Pelayanan publik.
        </p>
        <p>
          Berdasarkan Permenpan No. 14 Tahun 2017, Survei Kepuasan Masyarakat adalah pengukuran secara komprehensif kegiatan tentang tingkat kepuasan masyarakat yang diperoleh dari hasil pengukuran atas pendapat masyarakat. Melalui survei ini diharapkan mendorong partisipasi masyarakat sebagai pengguna layanan dalam menilai kinerja penyelenggara pelayanan serta mendorong penyelenggara pelayanan publik untuk meningkatkan kualitas pelayanan dan melakukan pengembangan melalui inovasi-inovasi pelayanan publik
        </p>
        
      </div>
      <div class="col-md-6">
        <p>
          Penyusunan Survei Kepuasan Masyarakat dilaksanakan berdasarkan dasar hukum dan peraturan perundangan yang digunakan yakni, sebagai berikut:
        </p>

        <div class="row">
          <div class="col-md-6">
            <div class="card card-body mt-3 shadow">
              <h5>Undang-Undang RI Nomor 28 Tahun 1999</h5>
              <span>
                Penyelenggaraan Pemerintahan yang Bersih dan Bebas KKN
              </span>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card card-body mt-3 shadow">
              <h5>
                Undang-Undang RI Nomor 25 Tahun 2009
              </h5>
              <span>
                Pelayanan Publik (Lembaran Negara RI Tahun 2009 Nomor 112 & 5038)
              </span>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card card-body mt-3 shadow">
              <h5>
                Instruksi Presiden Republik Indonesia Nomor 1 Tahun 1995
              </h5>
              <span>
                Perbaikan & Peningkatan Mutu Pelayanan
              </span>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card card-body mt-3 shadow">
              <h5>
                Instruksi Presiden Republik Indonesia Nomor 5 Tahun 2004
              </h5>
              <span>
                Percepatan Pemberantasan Korupsi
              </span>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card card-body mt-3 shadow">
              <h5>
                Kemenpan Nomor. 63/KEP/M.PAN/7/2003
              </h5>
              <span>
                Pedoman Umum Penyelenggaraan Pelayanan Publik
              </span>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card card-body mt-3 shadow">
              <h5>
                Permenpanrb Nomor 14 Tahun 2017
              </h5>
              <span>
                Pedoman SKM terhadap Penyelenggaraan Pelayanan Publik
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


<section class="bg-white">
  <div class="container">
sd
  </div>
</section> --}}
{{-- <section class="bg-white text-center" id="content">
  <div class="container">
    <div class="row justify-content-center text-center">
      <div class="col-10 col-md-6">
        <h3 class="fs-2 fs-lg-3">Validasi Sertifikat :</h3>
        <p class="px-lg-4 mt-3">Di situs web ini anda dapat melakukan validasi sertifikat terkait survey kepuasan masyarakat dengan menggunakan Nomor Sertifikat yang sudah terdaftar.</p>
        <hr class="short" data-zanim-xs='{"from":{"opacity":0,"width":0},"to":{"opacity":1,"width":"4.20873rem"},"duration":0.8}' data-zanim-trigger="scroll" />


        <div class="wow fadeInDown">
          <ol>

            <form method="POST">
              <div class="input-group mb">
                <input type="text" class="form-control" placeholder="Masukkan nomor sertifikat ..." name="keyword" id="keyword" autocomplete="off">
                <button class="btn btn-primary" type="submit" id="butsave"><i class="fa fa-search"></i>Search</button>
              </div>
            </form>
            <br>

          </ol>
        </div>

      </div>
    </div>
  </div>
</section>
 --}}

@endsection

@section('javascript')
<script src="{{ base_url() }}assets/vendor/jquery/jquery-3.6.0.min.js"></script>
<script>
function myFunction() {
  const element = document.getElementById("content");
  element.scrollIntoView();
}

  $(document).ready(function() {
    $('#butsave').on('click', function() {
      var keyword = $('#keyword').val();
      console.log(keyword);


      if (keyword != "") {
        $("#butsave").attr("disabled", "disabled");
        $.ajax({
          url: "<?php echo base_url("cari"); ?>",
          type: "POST",
          data: {
            type: 1,
            keyword: keyword
          },
          cache: false,
          success: function(dataResult) {
            var dataResult = JSON.parse(dataResult);
            console.log(dataResult);
            if (dataResult.statusCode == 500) {

              $("#butsave").removeAttr("disabled");
              // $('#fupForm').find('input:text').val('');
              // $("#danger").show();

              alert('Nomor sertifikat yang anda masukkan salah atau belum terdaftar!');

            } else if (dataResult.uuid != null || dataResult.uuid != '') {

              $("#butsave").removeAttr("disabled");
              window.open("<?php echo base_url() .  'validasi-sertifikat/' ?>" +
                dataResult.uuid, '_blank');
            }
          }
        });
      } else {
        alert('Silahkan masukkan nomor sertifikat anda!');
      }
    });
  });
</script>
@endsection