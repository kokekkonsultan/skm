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
    @include("include_backend/partials_no_aside/_inc_menu_repository")

    <div class="row mt-5">
        <div class="col-md-3">
            @include('manage_survey/menu_data_survey')
        </div>
        <div class="col-md-9">

            <div class="card card-custom card-sticky" data-aos="fade-down">
                @include('include_backend/partials_backend/_message')
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
                        <p>Dengan scan barcode dapat mempermudah responden untuk menuju ke link survei. Anda bisa memberika informasi scan barcode melalui cetak tulisan yang bisa ditempelkan, agar bisa menjangkau responden yang berada di tempat umum. Tersedia pilihan yang bisa anda gunakan untuk mencetak scan barcode.</p>
                    	Pilih desain yang anda inginkan:
                    </div>
                    
                    <div class="row">
                    	<div class="col-md-6">
                    		<a href="{{ base_url() }}{{ $ci->session->userdata('username') }}/{{ $ci->uri->segment(2) }}/scan-barcode/do?bg=light" target="_blank" title="Pilih desain dengan latar belakang terang">
                    		<div class="text-center shadow card-menu" style="border: 1px solid #333333; padding: 10px; border-radius: 10px;">
                    			<h5 class="text-dark">Latar Belakang Terang</h5>
                    			<img src="{{ base_url() }}assets/img/bg-scan/small-background-light.jpg" alt="" width="200px;" style="border: 1px solid #f3f3f3;">
                    		</div>
                    		</a>
                    	</div>

                    	<div class="col-md-6">
                    		<a href="{{ base_url() }}{{ $ci->session->userdata('username') }}/{{ $ci->uri->segment(2) }}/scan-barcode/do?bg=dark" target="_blank" title="Pilih desain dengan latar belakang gelap">
                    		<div class="text-center shadow card-menu" style="border: 1px solid #333333; padding: 10px; border-radius: 10px;">
                    			<h5 class="text-dark">Latar Belakang Gelap</h5>
                    			<img src="{{ base_url() }}assets/img/bg-scan/small-background-dark.jpg" alt="" width="200px;" style="border: 1px solid #f3f3f3;">
                    		</div>
                    		</a>
                    	</div>
                    </div>
                </div>
            </div>

            <div class="card mt-5" data-aos="fade-down">
                <div class="card-header font-weight-bold">
                    Custom
                </div>
                <div class="card-body">
                    <p>
                        Anda juga bisa membuat desain scan barcode sendiri. Dibawah ini disediakan Qrcode yang bisa anda letakkan di desain anda.
                    </p>

                    <form action="{{ base_url() }}{{ $ci->session->userdata('username') }}/{{ $ci->uri->segment(2) }}/scan-barcode/get" method="POST">
                      <div class="mb-3">
                        <label for="lbl_1" class="form-label">Link Survei Anda</label>
                        {!! form_error('link') !!}
                        <span class="text-danger">{{ base_url() }}survei/{{ $ci->uri->segment(2) }}</span>
                        <input type="hidden" name="link" class="form-control" id="lbl_1" aria-describedby="help_1" value="{{ base_url() }}survei/{{ $ci->uri->segment(2) }}">
                      </div>
                      <div class="mb-3 form-check">
                        <input type="checkbox" name="with_logo" class="form-check-input" id="lbl_2">
                        <label class="form-check-label" for="lbl_2">Sertakan Logo pada QrCode</label>
                      </div>
                      <button type="submit" class="btn btn-primary fw-bold">Request QrCode</button>
                    </form>

                </div>
            </div>

            @if ($ci->session->userdata('qr_result'))
            <div class="card mt-5 mb-5" data-aos="fade-down">
                <div class="card-body text-center">
                    <img src="{{ $ci->session->userdata('qr_result') }}" style="width: 200px;" />
                    <br><br>
                    <p>Link : {{ $ci->session->userdata('qr_link') }}</p>
                    <br><br>
                    {!! anchor(base_url().$ci->session->userdata('username').'/'.$ci->uri->segment(2).'/scan-barcode/download', 'Download QR Code', ['class' => 'btn btn-primary']) !!} {!! anchor(base_url().$ci->session->userdata('username').'/'.$ci->uri->segment(2).'/scan-barcode/clear-data', 'Delete', ['class' => 'btn btn-danger']) !!}
                </div>
            </div>
            @endif

        </div>
    </div>

</div>
@endsection

@section('javascript')
<script>
    $('.card-menu').hover(
       function(){ $(this).addClass('border-menu shadow') },
       function(){ $(this).removeClass('border-menu shadow') }
)
</script>
@endsection