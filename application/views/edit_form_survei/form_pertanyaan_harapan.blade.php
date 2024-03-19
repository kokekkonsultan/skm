@extends('include_backend/_template')

@php
$ci = get_instance();
@endphp

@section('style')
<link rel="dns-prefetch" href="//fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
@endsection

@section('content')


<div class="container mt-5 mb-5" style="font-family: nunito;">
    <div class="text-center" data-aos="fade-up">
        <div id="progressbar" class="mb-5">
            <li class="active" id="account"><strong>Data Responden</strong></li>
            <li class="active" id="personal"><strong>Pertanyaan Survei</strong></li>
            @if($status_saran == 1)
            <li id="payment"><strong>Saran</strong></li>
            @endif
            <li id="confirm"><strong>Konfirmasi</strong></li>
            <li id="completed"><strong>Completed</strong></li>
        </div>
    </div>
    <br>
    <br>
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card shadow" data-aos="fade-up" id="kt_blockui_content">

                @if($manage_survey->img_benner == '')
                <img class="card-img-top" src="{{ base_url() }}assets/img/site/page/banner-survey.jpg"
                    alt="new image" />
                @else
                <img class="card-img-top shadow"
                    src="{{ base_url() }}assets/klien/benner_survei/{{$manage_survey->img_benner}}" alt="new image">
                @endif

                <div class="card-header text-center">
                    <h4><b>PERTANYAAN HARAPAN</b> - @include('include_backend/partials_backend/_tanggal_survei')</h4>
                </div>
                <div class="card-body">



                    <form>


                        @php
                        $i = 1;
                        @endphp

                        @foreach ($pertanyaan_unsur->result() as $row)

                        <input type="hidden" name="id_pertanyaan_unsur[{{ $i }}]"
                            value="{{ $row->id_pertanyaan_unsur }}">

                        <table class="table table-borderless" width="100%" border="0"
                            style="font-size: 14px; text-transform: capitalize;">
                            <tr>
                                <td width="4%" valign="top">H{{ $row->nomor_harapan }}.</td>
                                <td><?php echo $row->isi_pertanyaan_unsur ?></td>
                            </tr>

                            <tr>
                                <td></td>
                                <td style="font-weight: bold;">

                                    @foreach ($jawaban_pertanyaan_harapan->result() as $value)

                                    @if ($value->id_pertanyaan_unsur_pelayanan == $row->id_pertanyaan_unsur)

                                    <div class="radio-inline mb-2">
                                        <label class="radio radio-outline radio-success radio-lg">
                                            <input type="radio" name="jawaban_pertanyaan_harapan[{{ $i }}]" value=""
                                                required><span></span>
                                            {{ $value->nama_tingkat_kepentingan }}
                                        </label>
                                    </div>

                                    @endif

                                    @endforeach

                                </td>
                            </tr>
                        </table>

                        <br>
                        {{-- <hr> --}}
                        <br>

                        @php
                        $i++;
                        @endphp

                        @endforeach



                </div>
                <div class="card-footer">
                    <table class="table table-borderless">
                        <tr>
                            <td class="text-left">
                                {!! anchor(base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2)
                                . '/form-survei/pertanyaan', '<i class="fa fa-arrow-left"></i>
                                Kembali',
                                ['class' => 'btn btn-secondary btn-lg font-weight-bold shadow']) !!}
                            </td>
                            <td class="text-right">
                                <a class="btn btn-warning btn-lg font-weight-bold shadow"
                                    href="<?php echo $url_next ?>">Selanjutnya
                                    <i class="fa fa-arrow-right"></i></a>
                            </td>
                        </tr>
                    </table>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>


@endsection

@section('javascript')

@endsection