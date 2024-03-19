@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')
<style>
.border-menu {
    border-color: #304EC0 !important;
    background-color: #f3f3f3;
}
</style>
@endsection

@section('content')
<div class="container-fluid">

    <div class="row mt-5">
        <div class="col-md-3">
            @include('data_survey_klien/menu_data_survey_klien')
        </div>
        <div class="col-md-9">

            <div class="card card-custom card-sticky" data-aos="fade-down">
                <div class="card-header">
                    <div class="card-title">
                        {{$title}}
                    </div>
                    <div class="card-toolbar">

                    </div>
                </div>

                <div class="card-body">
                    <?php echo $ci->session->set_flashdata('message_success') ?>

                    <div class="mb-5">
                        <p>Dengan scan barcode dapat mempermudah responden untuk menuju ke link survei. Anda bisa
                            memberika informasi scan barcode melalui cetak tulisan yang bisa ditempelkan, agar bisa
                            menjangkau responden yang berada di tempat umum. Tersedia pilihan yang bisa anda gunakan
                            untuk mencetak scan barcode.</p>
                        Pilih desain yang anda inginkan:
                    </div>

                    <div class="row">

                        <div class="col-md-6">
                            <a href="{{ base_url() }}{{ $ci->session->userdata('username') }}/{{ $manage_survey->slug }}/scan-barcode/do?bg=light"
                                target="_blank" title="Pilih desain dengan latar belakang terang">
                                <div class="text-center shadow card-menu"
                                    style="border: 1px solid #333333; padding: 10px; border-radius: 10px;">
                                    <h5 class="text-dark">Latar Belakang Terang</h5>
                                    <img src="{{ base_url() }}assets/img/bg-scan/small-background-light.jpg" alt=""
                                        width="200px;" style="border: 1px solid #f3f3f3;">
                                </div>
                            </a>
                        </div>

                        <div class="col-md-6">
                            <a href="{{ base_url() }}{{ $ci->session->userdata('username') }}/{{ $manage_survey->slug }}/scan-barcode/do?bg=dark"
                                target="_blank" title="Pilih desain dengan latar belakang gelap">
                                <div class="text-center shadow card-menu"
                                    style="border: 1px solid #333333; padding: 10px; border-radius: 10px;">
                                    <h5 class="text-dark">Latar Belakang Gelap</h5>
                                    <img src="{{ base_url() }}assets/img/bg-scan/small-background-dark.jpg" alt=""
                                        width="200px;" style="border: 1px solid #f3f3f3;">
                                </div>
                            </a>
                        </div>

                    </div>
                </div>
            </div>


        </div>
    </div>

</div>
@endsection

@section('javascript')
<script>
$('.card-menu').hover(
    function() {
        $(this).addClass('border-menu shadow')
    },
    function() {
        $(this).removeClass('border-menu shadow')
    }
)
</script>
@endsection