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
                    <h4><b>PERTANYAAN UNSUR</b> - @include('include_backend/partials_backend/_tanggal_survei')</h4>
                </div>

                <form>

                    <div class="card-body ml-5 mr-5">

                        @php
                        $i = 1;
                        @endphp
                        {{-- Looping Pertanyaan Unsur --}}
                        @foreach ($pertanyaan_unsur->result() as $row)

                        <table class="table table-borderless" width="100%" border="0" style="font-size: 14px;">
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
                                        <label class="radio radio-outline radio-success radio-lg">
                                            <input type="radio" name="jawaban_pertanyaan_unsur[{{ $i }}]"
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
                                    <textarea class="form-control" type="text" name="alasan_pertanyaan_unsur[<?= $i ?>]"
                                        id="{{$row->id_pertanyaan_unsur}}" placeholder="Berikan alasan jawaban anda ..."
                                        style="display: none;"></textarea>

                                </td>
                            </tr>

                            @php
                            $n = 1;
                            @endphp

                            <tr>
                                <td colspan="2">
                                    {{-- <hr> --}}
                                </td>
                            </tr>

                            {{-- Looping Pertanyaan Terbuka --}}
                            @foreach ($pertanyaan_terbuka->result() as $row_terbuka)

                            @if ($row_terbuka->id_unsur_pelayanan == $row->id_unsur_pelayanan)

                            <input type="hidden" name="id_pertanyaan_terbuka[<?= $n ?>]"
                                value="{{$row_terbuka->id_pertanyaan_terbuka}}">

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
                                        <label class="radio radio-outline radio-success radio-lg">
                                            <input type="radio" name="jawaban_pertanyaan_terbuka[<?= $n ?>]" value=""
                                                required><span></span>
                                            <?php echo $value_terbuka->pertanyaan_ganda; ?>
                                        </label>
                                    </div>

                                    @endif
                                    @endforeach

                                    @if ($row_terbuka->dengan_isian_lainnya == 1)
                                    <div class="radio-inline mb-2">
                                        <label class="radio radio-outline radio-success radio-lg">
                                            <input type="radio" name="jawaban_pertanyaan_terbuka[<?= $n ?>]"
                                                value="Lainnya"><span></span> Lainnya</label>
                                    </div>
                                    <br>
                                    @endif

                                    @if ($row_terbuka->id_jenis_pilihan_jawaban == 2)
                                    <input class="form-control" type="text" name="jawaban_pertanyaan_terbuka[<?= $n ?>]"
                                        placeholder="Masukkan Jawaban Anda ..." value=""></input>
                                    @endif
                                </td>
                            </tr>

                            @endif

                            @php
                            $n++;
                            @endphp

                            @endforeach
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
                                    {!! anchor(base_url() . $ci->session->userdata('username') . '/' .
                                    $ci->uri->segment(2)
                                    . '/form-survei/data-responden', '<i class="fa fa-arrow-left"></i>
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