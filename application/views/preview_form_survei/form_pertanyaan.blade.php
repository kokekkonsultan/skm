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
            <li id="completed"><strong>Completed</strong></li>
        </div>
    </div>
    <br>
    <br>
    <div class="row">
        <div class="col-md-8 offset-md-2" style="font-size: 16px;">
            <div class="card shadow mb-4 mt-4" data-aos="fade-up" style="font-family: 'Exo 2', sans-serif;">

                @if($manage_survey->img_benner == '')
                <img class="card-img-top" src="{{ base_url() }}assets/img/site/page/banner-survey.jpg" alt="new image" />
                @else
                <img class="card-img-top shadow" src="{{ base_url() }}assets/klien/benner_survei/{{$manage_survey->img_benner}}" alt="new image">
                @endif

                <div class="card-header text-center">
                    <h3 class="mt-5" style="font-family: 'Exo 2', sans-serif;"><b>PERTANYAAN UNSUR</b></h3>
                    @include('include_backend/partials_backend/_tanggal_survei')
                </div>

                <form>

                    <div class="card-body ml-5 mr-5">

                        <!-- Looping Pertanyaan Terbuka Paling Atas -->
                        @php
                        $a = 1;
                        @endphp
                        @foreach ($pertanyaan_terbuka_atas->result() as $row_terbuka_atas)

                        <div class="mt-10 mb-10">

                            <table class="table table-borderless" width="100%" border="0">
                                <tr>
                                    <td width="5%" valign="top">{!! $row_terbuka_atas->nomor_pertanyaan_terbuka !!}.
                                    </td>
                                    <td>{!! $row_terbuka_atas->isi_pertanyaan_terbuka !!}</td>
                                </tr>

                                <tr>
                                    <td width="5%"></td>
                                    <td style="font-weight:bold;" width="95%">

                                        @foreach ($jawaban_pertanyaan_terbuka->result() as $value_terbuka_atas)
                                        @if ($value_terbuka_atas->id_perincian_pertanyaan_terbuka ==
                                        $row_terbuka_atas->id_perincian_pertanyaan_terbuka)
                                        <div class="radio-inline mb-2">
                                            <label class="radio radio-outline radio-success radio-lg" style="font-size: 16px;">
                                                <input type="radio" name="jawaban_pertanyaan_terbuka[{{ $row_terbuka_atas->id_pertanyaan_terbuka }}]" value="{{ $value_terbuka_atas->pertanyaan_ganda; }}" class="terbuka_{{ $row_terbuka_atas->id_pertanyaan_terbuka }}">
                                                <span></span> {{ $value_terbuka_atas->pertanyaan_ganda }}
                                            </label>
                                        </div>
                                        @endif
                                        @endforeach



                                        @if ($row_terbuka_atas->dengan_isian_lainnya == 1)
                                        <div class="radio-inline mb-2">
                                            <label class="radio radio-outline radio-success radio-lg" style="font-size: 16px;">
                                                <input type="radio" name="jawaban_pertanyaan_terbuka[{{ $row_terbuka_atas->id_pertanyaan_terbuka }}]" value="Lainnya" class="terbuka_{{ $row_terbuka_atas->id_pertanyaan_terbuka }}""><span></span>Lainnya
                                            </label>
                                        </div>
                                        
                                        <input class=" form-control" name="jawaban_lainnya[{{ $row_terbuka_atas->id_pertanyaan_terbuka }}]" value="" pattern="^[a-zA-Z0-9.,\s]*$|^\w$" placeholder="Masukkan jawaban lainnya ..." id="terbuka_lainnya_{{ $row_terbuka_atas->id_pertanyaan_terbuka }}" style="display:none">

                                                <small id="text_terbuka_{{ $row_terbuka_atas->id_pertanyaan_terbuka }}" class="text-danger" style="display:none">**Pengisian form hanya
                                                    dapat menggunakan tanda baca
                                                    (.) titik dan (,) koma</small>
                                                <br>
                                                @endif


                                                @if ($row_terbuka_atas->id_jenis_pilihan_jawaban == 2)
                                                <textarea class="form-control" type="text" name="jawaban_pertanyaan_terbuka[{{ $row_terbuka_atas->id_pertanyaan_terbuka }}]" placeholder="Masukkan Jawaban Anda ..."></textarea>
                                                @endif
                                    </td>
                                </tr>
                            </table>
                        </div>

                        @php
                        $a++;
                        @endphp
                        @endforeach




                        <!-- Looping Pertanyaan Unsur -->
                        @php
                        $i = 1;
                        @endphp
                        @foreach ($pertanyaan_unsur->result() as $row)
                        @php
                        $is_required_u = $row->is_required == 1 ? '<b class="text-danger">*</b>' : '';
                        @endphp
                        <div class="mt-10 mb-10">
                            <table class="table table-borderless" width="100%" border="0">
                                <tr>
                                    <td width="5%" valign="top">{!! $row->nomor . '' . $is_required_u !!}.</td>
                                    <td width="95%">{!! $row->isi_pertanyaan_unsur !!}</td>
                                </tr>

                                <tr>
                                    <td width="5%"></td>
                                    <td style="font-weight:bold;" width="95%">


                                        {{-- Looping Pilihan Jawaban --}}
                                        @foreach ($jawaban_pertanyaan_unsur->result() as $value)
                                        @if ($value->id_pertanyaan_unsur == $row->id_pertanyaan_unsur)
                                        <div class="radio-inline mb-2">
                                            <label class="radio radio-outline radio-success radio-lg" style="font-size: 16px;">

                                                <input type="radio" name="jawaban_pertanyaan_unsur[{{ $i }}]" value="{{$value->nomor_kategori_unsur_pelayanan}}" class="unsur_{{$value->id_pertanyaan_unsur}}" required><span></span>
                                                {{$value->nama_kategori_unsur_pelayanan}}
                                            </label>
                                        </div>
                                        @endif
                                        @endforeach
                                    </td>
                                </tr>

                                <tr>
                                    <td width="5%"></td>
                                    <td width="95%">

                                        <textarea class="form-control form-alasan" type="text" name="alasan_pertanyaan_unsur[{{ $i }}]" id="input_alasan_{{$row->id_pertanyaan_unsur}}" placeholder="Berikan alasan jawaban anda ..." pattern="^[a-zA-Z0-9.,\s]*$|^\w$" style="display:none"></textarea>

                                        <small id="text_alasan_{{$row->id_pertanyaan_unsur}}" class="text-danger" style="display:none">**Pengisian alasan hanya dapat menggunakan tanda baca
                                            (.) titik dan (,) koma</small>
                                    </td>
                                </tr>
                            </table>
                        </div>


                        <div id="display_terbuka_{{ $row->id_pertanyaan_unsur }}">
                            <!-- Looping Pertanyaan Terbuka -->
                            @php
                            $n = $pertanyaan_terbuka_atas->num_rows() + 1;
                            @endphp

                            @foreach ($pertanyaan_terbuka->result() as $row_terbuka)
                            @if ($row_terbuka->id_unsur_pelayanan == $row->id_unsur_pelayanan)
                            <div class=" mt-10 mb-10">

                                <table class="table table-borderless" width="100%" border="0">
                                    <tr>
                                        <td width="5%" valign="top">{!! $row_terbuka->nomor_pertanyaan_terbuka !!}.</td>
                                        <td width="95%">{!! $row_terbuka->isi_pertanyaan_terbuka !!}</td>
                                    </tr>

                                    <tr>
                                        <td width="5%"></td>
                                        <td style="font-weight:bold;" width="95%">
                                            @foreach ($jawaban_pertanyaan_terbuka->result() as $value_terbuka)
                                            @if ($value_terbuka->id_perincian_pertanyaan_terbuka ==
                                            $row_terbuka->id_perincian_pertanyaan_terbuka)

                                            <div class="radio-inline mb-2">
                                                <label class="radio radio-outline radio-success radio-lg" style="font-size: 16px;">
                                                    <input type="radio" name="jawaban_pertanyaan_terbuka[{{ $row_terbuka->id_pertanyaan_terbuka }}]" value="{{ $value_terbuka->pertanyaan_ganda; }}" class="terbuka_{{ $row_terbuka->id_pertanyaan_terbuka }}">
                                                    <span></span> {{ $value_terbuka->pertanyaan_ganda }}
                                                </label>
                                            </div>
                                            @endif
                                            @endforeach


                                            @if ($row_terbuka->dengan_isian_lainnya == 1)
                                            <div class="radio-inline mb-2">
                                                <label class="radio radio-outline radio-success radio-lg" style="font-size: 16px;">

                                                    <input type="radio" name="jawaban_pertanyaan_terbuka[{{ $row_terbuka->id_pertanyaan_terbuka }}]" value="Lainnya" class="terbuka_{{ $row_terbuka->id_pertanyaan_terbuka }}">
                                                    <span></span> Lainnya
                                                </label>
                                            </div>

                                            <input class="form-control" name="jawaban_lainnya[{{ $row_terbuka->id_pertanyaan_terbuka }}]" value="" pattern="^[a-zA-Z0-9.,\s]*$|^\w$" placeholder="Masukkan jawaban lainnya ..." id="terbuka_lainnya_{{ $row_terbuka->id_pertanyaan_terbuka }}" style="display:none">

                                            <small id="text_terbuka_{{ $row_terbuka->id_pertanyaan_terbuka }}" class="text-danger" style="display:none">**Pengisian form hanya dapat
                                                menggunakan tanda baca
                                                (.) titik dan (,) koma</small>
                                            <br>
                                            @endif



                                            @if ($row_terbuka->id_jenis_pilihan_jawaban == 2)
                                            <textarea class="form-control" type="text" name="jawaban_pertanyaan_terbuka[{{ $row_terbuka->id_pertanyaan_terbuka }}]" placeholder="Masukkan Jawaban Anda ..."></textarea>

                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            @endif

                            @php
                            $n++;
                            @endphp
                            @endforeach
                        </div>


                        @php
                        $i++;
                        @endphp
                        @endforeach




                        <!-- Looping Pertanyaan Terbuka Paling Bawah -->
                        @php
                        $b = $pertanyaan_terbuka_atas->num_rows() + $pertanyaan_terbuka->num_rows() + 1;
                        @endphp
                        @foreach ($pertanyaan_terbuka_bawah->result() as $row_terbuka_bawah)
                        <div class="mt-10 mb-10">

                            <table class="table table-borderless" width="100%" border="0">
                                <tr>
                                    <td width="5%" valign="top">{!! $row_terbuka_bawah->nomor_pertanyaan_terbuka !!}.
                                    </td>
                                    <td width="95%">{!! $row_terbuka_bawah->isi_pertanyaan_terbuka !!}</td>
                                </tr>

                                <tr>
                                    <td width="5%"></td>
                                    <td style="font-weight:bold;" width="95%">
                                        @foreach ($jawaban_pertanyaan_terbuka->result() as $value_terbuka_bawah)
                                        @if ($value_terbuka_bawah->id_perincian_pertanyaan_terbuka ==
                                        $row_terbuka_bawah->id_perincian_pertanyaan_terbuka)

                                        <div class="radio-inline mb-2">
                                            <label class="radio radio-outline radio-success radio-lg" style="font-size: 16px;">

                                                <input type="radio" name="jawaban_pertanyaan_terbuka[{{ $row_terbuka_bawah->id_pertanyaan_terbuka }}]" value="{{ $value_terbuka_bawah->pertanyaan_ganda; }}" class="terbuka_{{ $value_terbuka_bawah->id_pertanyaan_terbuka }}">
                                                <span></span> {{ $value_terbuka_bawah->pertanyaan_ganda; }}
                                            </label>
                                        </div>
                                        @endif
                                        @endforeach

                                        @if ($row_terbuka_bawah->dengan_isian_lainnya == 1)
                                        <div class="radio-inline mb-2">
                                            <label class="radio radio-outline radio-success radio-lg" style="font-size: 16px;">

                                                <input type="radio" name="jawaban_pertanyaan_terbuka[{{ $row_terbuka_bawah->id_pertanyaan_terbuka }}]" value="Lainnya" class="terbuka_{{ $value_terbuka_bawah->id_pertanyaan_terbuka }}">
                                                <span></span> Lainnya
                                            </label>
                                        </div>

                                        <input class="form-control" name="jawaban_lainnya[{{ $row_terbuka_bawah->id_pertanyaan_terbuka }}]" value="" pattern="^[a-zA-Z0-9.,\s]*$|^\w$" placeholder="Masukkan jawaban lainnya ..." id="terbuka_lainnya_{{ $row_terbuka_bawah->id_pertanyaan_terbuka }}" style="display:none">

                                        <small id="text_terbuka_{{ $row_terbuka_bawah->id_pertanyaan_terbuka }}" class="text-danger" style="display:none">**Pengisian form hanya dapat
                                            menggunakan tanda baca
                                            (.) titik dan (,) koma</small>
                                        <br>
                                        @endif


                                        @if ($row_terbuka_bawah->id_jenis_pilihan_jawaban == 2)
                                        <textarea class="form-control" type="text" name="jawaban_pertanyaan_terbuka[{{ $row_terbuka_bawah->id_pertanyaan_terbuka }}]" placeholder="Masukkan Jawaban Anda ..."></textarea>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>

                        @php
                        $b++;
                        @endphp

                        @endforeach
                    </div>


                    <div class="card-footer">
                        <table class="table table-borderless">
                            <tr>
                                <td class="text-left">
                                    {!! anchor(base_url() . $ci->session->userdata('username') . '/' .
                                    $ci->uri->segment(2)
                                    . '/preview-form-survei/data-responden', '<i class="fa fa-arrow-left"></i>
                                    Kembali',
                                    ['class' => 'btn btn-secondary btn-lg font-weight-bold shadow']) !!}
                                </td>
                                <td class="text-right">
                                    <a class="btn btn-warning btn-lg font-weight-bold shadow" href="<?php echo $url_next ?>">Selanjutnya
                                        <i class="fa fa-arrow-right"></i></a>
                                </td>
                            </tr>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection



@section('javascript')

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>


@foreach ($pertanyaan_unsur->result() as $pr)
@if($pr->is_alasan == 1)
<script type="text/javascript">
    $(function() {
        $(":radio.unsur_<?= $pr->id_pertanyaan_unsur ?>").click(function() {
            $("#input_alasan_<?= $pr->id_pertanyaan_unsur ?>").hide();
            $("#text_alasan_<?= $pr->id_pertanyaan_unsur ?>").hide();

            if ($(this).val() == 1 || $(this).val() == 2) {
                $("#input_alasan_<?= $pr->id_pertanyaan_unsur ?>").prop('required', true).show();
                $("#text_alasan_<?= $pr->id_pertanyaan_unsur ?>").show();

            } else {
                $("#input_alasan_<?= $pr->id_pertanyaan_unsur ?>").removeAttr('required').hide();
                $("#text_alasan_<?= $pr->id_pertanyaan_unsur ?>").hide();

            }

        });

    });
</script>
@endif
@endforeach

@foreach ($ci->db->get("pertanyaan_terbuka_$manage_survey->table_identity")->result() as $pt)
<script type="text/javascript">
    $(function() {
        $(":radio.terbuka_<?= $pt->id ?>").click(function() {
            if ($(this).val() == 'Lainnya') {
                $("#terbuka_lainnya_<?= $pt->id ?>").prop('required', true).show();
                $("#text_terbuka_<?= $pt->id ?>").show();
            } else {
                $("#terbuka_lainnya_<?= $pt->id ?>").removeAttr('required').hide();
                $("#text_terbuka_<?= $pt->id ?>").hide();
            }

        });

    });
</script>
@endforeach


@endsection