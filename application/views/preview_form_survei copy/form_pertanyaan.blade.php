@extends('include_backend/_template')

@php
$ci = get_instance();
@endphp

@section('style')
<!-- <link rel="dns-prefetch" href="//fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet"> -->
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
        <div class="col-md-8 offset-md-2" style="font-size: 16px; font-family:Arial, Helvetica, sans-serif;">
            <div class="card shadow mb-4 mt-4" data-aos="fade-up" style="border-left: 5px solid #FFA800;">

                @if($manage_survey->img_benner == '')
                <img class="card-img-top" src="{{ base_url() }}assets/img/site/page/banner-survey.jpg"
                    alt="new image" />
                @else
                <img class="card-img-top shadow"
                    src="{{ base_url() }}assets/klien/benner_survei/{{$manage_survey->img_benner}}" alt="new image">
                @endif

                <div class="card-header text-center">
                    <h4><b>PERTANYAAN UNSUR</b> - @include('include_backend/partials_backend/_tanggal_survei')</h4>
                </div>

                <form>

                    <div class="card-body ml-5 mr-5">


                        {{-- Looping Pertanyaan Terbuka ATAS --}}
                        @foreach ($pertanyaan_terbuka_atas->result() as $row_terbuka_atas)

                        <table class="table table-borderless" width="100%" border="0">
                            <input type="hidden" value="{{$row_terbuka_atas->id_pertanyaan_terbuka}}">
                            <tr>
                                <td width="4%" valign="top">{{$row_terbuka_atas->nomor_pertanyaan_terbuka}}.
                                </td>
                                <td><?php echo $row_terbuka_atas->isi_pertanyaan_terbuka ?></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td style="font-weight:bold;">

                                    @foreach ($jawaban_pertanyaan_terbuka->result() as $value_terbuka_atas)

                                    @if ($value_terbuka_atas->id_perincian_pertanyaan_terbuka ==
                                    $row_terbuka_atas->id_perincian_pertanyaan_terbuka)

                                    <div class="radio-inline mb-2">
                                        <label class="radio radio-outline radio-success radio-lg"
                                            style="font-size:16px">
                                            <input type="radio" name="terbuka" value="" required><span></span>
                                            <?php echo $value_terbuka_atas->pertanyaan_ganda; ?>
                                        </label>
                                    </div>

                                    @endif
                                    @endforeach

                                    @if ($row_terbuka_atas->dengan_isian_lainnya == 1)
                                    <div class="radio-inline mb-2">
                                        <label class="radio radio-outline radio-success radio-lg"
                                            style="font-size:16px">
                                            <input type="radio" name="terbuka" value="Lainnya"><span></span>
                                            Lainnya</label>
                                    </div>
                                    <br>
                                    @endif

                                    @if ($row_terbuka_atas->id_jenis_pilihan_jawaban == 2)
                                    <input class="form-control" type="text" placeholder="Masukkan Jawaban Anda ..."
                                        value=""></input>
                                    @endif
                                </td>
                            </tr>
                        </table>
                        <br>
                        <br>
                        @endforeach







                        {{-- Looping Pertanyaan Unsur --}}
                        @foreach ($pertanyaan_unsur->result() as $row)

                        <table class="table table-borderless" width="100%" border="0">
                            <tr>
                                <td width="4%" valign="top">{{ $row->nomor }}.</td>
                                <td><?php echo $row->isi_pertanyaan_unsur ?></td>
                            </tr>


                            <tr>
                                <td></td>
                                <td style="font-weight:bold;">

                                    {{-- Looping Pilihan Jawaban --}}
                                    @foreach ($jawaban_pertanyaan_unsur->result() as $value)

                                    @if ($value->id_pertanyaan_unsur == $row->id_pertanyaan_unsur)

                                    <div class="radio-inline mb-2">
                                        <label class="radio radio-outline radio-success radio-lg"
                                            style="font-size:16px">
                                            <input type="radio" name="jawaban_pertanyaan_unsur[]"
                                                value="{{$value->nomor_kategori_unsur_pelayanan}}"
                                                class="{{$value->id_pertanyaan_unsur}}" required><span></span>
                                            {{$value->nama_kategori_unsur_pelayanan}}
                                        </label>
                                    </div>

                                    @endif
                                    @endforeach

                                </td>
                            </tr>

                            <tr>
                                <td></td>
                                <td>
                                    <textarea class="form-control" type="text" name="alasan_pertanyaan_unsur[]"
                                        id="{{$row->id_pertanyaan_unsur}}" placeholder="Berikan alasan jawaban anda ..."
                                        style="display: none;"></textarea>

                                </td>
                            </tr>
                        </table>

                        @php
                        @endphp


                        {{-- Looping Pertanyaan Terbuka --}}
                        @foreach ($pertanyaan_terbuka->result() as $row_terbuka)

                        @if ($row_terbuka->id_unsur_pelayanan == $row->id_unsur_pelayanan)
                        <table class="table table-borderless" width="100%" border="0">
                            <tr>
                                <td width="4%" valign="top">{{$row_terbuka->nomor_pertanyaan_terbuka}}.</td>
                                <td><?php echo $row_terbuka->isi_pertanyaan_terbuka ?></td>
                            </tr>

                            <tr>
                                <td></td>
                                <td style="font-weight:bold;">

                                    @foreach ($jawaban_pertanyaan_terbuka->result() as $value_terbuka)
                                    @if ($value_terbuka->id_perincian_pertanyaan_terbuka ==
                                    $row_terbuka->id_perincian_pertanyaan_terbuka)

                                    <div class="radio-inline mb-2">
                                        <label class="radio radio-outline radio-success radio-lg"
                                            style="font-size:16px">
                                            <input type="radio" name="jawaban_pertanyaan_terbuka[]" value=""
                                                required><span></span>
                                            <?php echo $value_terbuka->pertanyaan_ganda; ?>
                                        </label>
                                    </div>

                                    @endif
                                    @endforeach

                                    @if ($row_terbuka->dengan_isian_lainnya == 1)
                                    <div class="radio-inline mb-2">
                                        <label class="radio radio-outline radio-success radio-lg"
                                            style="font-size:16px">
                                            <input type="radio" name="jawaban_pertanyaan_terbuka[]"
                                                value="Lainnya"><span></span> Lainnya</label>
                                    </div>
                                    <br>
                                    @endif

                                    @if ($row_terbuka->id_jenis_pilihan_jawaban == 2)
                                    <input class="form-control" type="text" name="jawaban_pertanyaan_terbuka[]"
                                        placeholder="Masukkan Jawaban Anda ..." value=""></input>
                                    @endif
                                </td>
                            </tr>

                            @endif
                            @endforeach
                        </table>

                        <br>
                        <br>
                        @endforeach







                        {{-- Looping Pertanyaan Terbuka BAWAH --}}
                        @foreach ($pertanyaan_terbuka_bawah->result() as $row_terbuka_bawah)

                        <table class="table table-borderless" width="100%" border="0">

                            <input type="hidden" value="{{$row_terbuka_bawah->id_pertanyaan_terbuka}}">
                            <tr>
                                <td width="4%" valign="top">
                                    {{$row_terbuka_bawah->nomor_pertanyaan_terbuka}}.
                                </td>
                                <td><?php echo $row_terbuka_bawah->isi_pertanyaan_terbuka ?></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td style="font-weight:bold;">

                                    @foreach ($jawaban_pertanyaan_terbuka->result() as $value_terbuka_bawah)

                                    @if ($value_terbuka_bawah->id_perincian_pertanyaan_terbuka ==
                                    $row_terbuka_bawah->id_perincian_pertanyaan_terbuka)

                                    <div class="radio-inline mb-2">
                                        <label class="radio radio-outline radio-success radio-lg"
                                            style="font-size:16px">
                                            <input type="radio" name="terbuka" value="" required><span></span>
                                            <?php echo $value_terbuka_bawah->pertanyaan_ganda; ?>
                                        </label>
                                    </div>

                                    @endif
                                    @endforeach

                                    @if ($row_terbuka_bawah->dengan_isian_lainnya == 1)
                                    <div class="radio-inline mb-2">
                                        <label class="radio radio-outline radio-success radio-lg"
                                            style="font-size:16px">
                                            <input type="radio" name="terbuka" value="Lainnya"><span></span>
                                            Lainnya</label>
                                    </div>
                                    <br>
                                    @endif

                                    @if ($row_terbuka_bawah->id_jenis_pilihan_jawaban == 2)
                                    <input class="form-control" type="text" placeholder="Masukkan Jawaban Anda ..."
                                        value=""></input>
                                    @endif
                                </td>
                            </tr>
                        </table>
                        <br>
                        <br>
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
                                    <a class="btn btn-warning btn-lg font-weight-bold shadow"
                                        href="<?php echo $url_next ?>">Selanjutnya
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
<?php
foreach ($pertanyaan_unsur->result() as $pr) {
?>
<script type="text/javascript">
$(function() {
    $(":radio.<?php echo $pr->id_pertanyaan_unsur; ?>").click(function() {
        $("#<?php echo $pr->id_pertanyaan_unsur; ?>").hide()
        if ($(this).val() == "1") {
            $("#<?php echo $pr->id_pertanyaan_unsur; ?>").show().prop('required', true);
        } else if ($(this).val() == "2") {
            $("#<?php echo $pr->id_pertanyaan_unsur; ?>").show().prop('required', true);
        } else {
            $("#<?php echo $pr->id_pertanyaan_unsur; ?>").removeAttr('required').hidden();
        }
    });
});
</script>
<?php
}
?>
@endsection