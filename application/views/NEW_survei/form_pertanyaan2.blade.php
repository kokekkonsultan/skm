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
        <div class="col-md-8 offset-md-2" style="font-size: 16px; font-family:arial, helvetica, sans-serif;">
            <div class="card shadow mb-4 mt-4" id="kt_blockui_content" data-aos="fade-up"
                style="border-left: 5px solid #FFA800;">


                @if($judul->img_benner == '')
                <img class="card-img-top" src="{{ base_url() }}assets/img/site/page/banner-survey.jpg"
                    alt="new image" />
                @else
                <img class="card-img-top shadow"
                    src="{{ base_url() }}assets/klien/benner_survei/{{$manage_survey->img_benner}}" alt="new image">
                @endif
                <div class="card-header text-center">
                    <h4><b>PERTANYAAN UNSUR</b> - @include('include_backend/partials_backend/_tanggal_survei')</h4>
                </div>

                <form action="<?php echo base_url() . 'survei/' . $ci->uri->segment(2) . '/add_pertanyaan/' .
                                    $ci->uri->segment(4) ?>" class="form_survei" method="POST">

                    <div class="card-body ml-5 mr-5">



                        {{-- Looping Pertanyaan Terbuka Paling Atas --}}
                        @php
                        $a = 1;
                        @endphp

                        @foreach ($pertanyaan_terbuka_atas->result() as $row_terbuka_atas)

                        <div class="mt-10 mb-10">
                            <input type="hidden" name="id_pertanyaan_terbuka[<?= $a ?>]"
                                value="{{$row_terbuka_atas->id_pertanyaan_terbuka}}">

                            <table class="table table-borderless" width="100%" border="0">
                                <tr>
                                    <td width="5%" valign="top">{{$row_terbuka_atas->nomor_pertanyaan_terbuka}}.</td>
                                    <td><?php echo $row_terbuka_atas->isi_pertanyaan_terbuka ?></td>
                                </tr>
                                <tr>
                                    <td width="5%"></td>
                                    <td style="font-weight:bold;" width="95%">

                                        @foreach ($jawaban_pertanyaan_terbuka->result() as $value_terbuka_atas)

                                        @if ($value_terbuka_atas->id_perincian_pertanyaan_terbuka ==
                                        $row_terbuka_atas->id_perincian_pertanyaan_terbuka)

                                        <div class="radio-inline mb-2">
                                            <label class="radio radio-outline radio-success radio-lg"
                                                style="font-size: 16px;">
                                                <input type="radio" name="jawaban_pertanyaan_terbuka[<?= $a ?>]"
                                                    value="<?php echo $value_terbuka_atas->pertanyaan_ganda; ?>"
                                                    <?php echo $value_terbuka_atas->pertanyaan_ganda == $row_terbuka_atas->jawaban ? 'checked' : '' ?>
                                                    required><span></span>
                                                <?php echo $value_terbuka_atas->pertanyaan_ganda; ?>
                                            </label>
                                        </div>

                                        @endif
                                        @endforeach

                                        @if ($row_terbuka_atas->dengan_isian_lainnya == 1)
                                        <div class="radio-inline mb-2">
                                            <label class="radio radio-outline radio-success radio-lg"
                                                style="font-size: 16px;">
                                                <input type="radio" name="jawaban_pertanyaan_terbuka[<?= $a ?>]"
                                                    value="Lainnya"
                                                    <?php echo $row_terbuka_atas->jawaban == 'Lainnya' ? 'checked' : '' ?>><span></span>Lainnya</label>
                                        </div>
                                        <br>
                                        @endif

                                        @if ($row_terbuka_atas->id_jenis_pilihan_jawaban == 2)
                                        <input class="form-control" type="text"
                                            name="jawaban_pertanyaan_terbuka[<?= $a ?>]"
                                            placeholder="Masukkan Jawaban Anda ..."
                                            value="<?php echo $row_terbuka_atas->jawaban ?>" required></input>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>

                        @php
                        $a++;
                        @endphp

                        @endforeach





                        @php
                        $i = 1;
                        @endphp
                        {{-- Looping Pertanyaan Unsur --}}
                        @foreach ($pertanyaan_unsur->result() as $row)

                        <div class="mt-10 mb-10">
                            <input type="hidden" name="id_pertanyaan_unsur[{{ $i }}]"
                                value="{{ $row->id_pertanyaan_unsur }}">
                            <table class="table table-borderless" width="100%" border="0">

                                <tr>
                                    <td width="5%" valign="top">{{ $row->nomor }}.</td>
                                    <td width="95%"><?php echo $row->isi_pertanyaan_unsur ?></td>
                                </tr>


                                <tr>
                                    <td width="5%"></td>
                                    <td style="font-weight:bold;" width="95%">

                                        {{-- Looping Pilihan Jawaban --}}
                                        @foreach ($jawaban_pertanyaan_unsur->result() as $value)

                                        @if ($value->id_pertanyaan_unsur == $row->id_pertanyaan_unsur)

                                        <div class="radio-inline mb-2">
                                            <label class="radio radio-outline radio-success radio-lg"
                                                style="font-size: 16px;">
                                                <input type="radio" name="jawaban_pertanyaan_unsur[{{ $i }}]"
                                                    value="{{$value->nomor_kategori_unsur_pelayanan}}"
                                                    class="{{$value->id_pertanyaan_unsur}}"
                                                    <?php echo $value->nomor_kategori_unsur_pelayanan == $row->skor_jawaban ? 'checked' : '' ?>
                                                    required><span></span> {{$value->nama_kategori_unsur_pelayanan}}
                                            </label>
                                        </div>

                                        @endif
                                        @endforeach

                                    </td>
                                </tr>

                                <tr>
                                    <td width="5%"></td>
                                    <td width="95%">

                                        <textarea class="form-control" type="text"
                                            name="alasan_pertanyaan_unsur[<?= $i ?>]" id="{{$row->id_pertanyaan_unsur}}"
                                            placeholder="Berikan alasan jawaban anda ..."
                                            <?php echo $row->skor_jawaban == 1 || $row->skor_jawaban == 2 ? 'required' : 'style="display:none"' ?>><?php echo $row->alasan_jawaban ?></textarea>

                                    </td>
                                </tr>
                            </table>
                        </div>


                        <div id="display_terbuka_<?php echo $row->id_pertanyaan_unsur ?>">
                            <hr>

                            {{-- Looping Pertanyaan Terbuka --}}
                            @php
                            $n = $pertanyaan_terbuka_atas->num_rows() + 1;
                            @endphp

                            @foreach ($pertanyaan_terbuka->result() as $row_terbuka)

                            @if ($row_terbuka->id_unsur_pelayanan == $row->id_unsur_pelayanan)

                            <div class=" mt-10 mb-10">
                                <input type="hidden" name="id_pertanyaan_terbuka[<?= $n ?>]"
                                    value="{{$row_terbuka->id_pertanyaan_terbuka}}">
                                <table class="table table-borderless" width="100%" border="0">

                                    <tr>
                                        <td width="5%" valign="top">{{$row_terbuka->nomor_pertanyaan_terbuka}}.</td>
                                        <td width="95%"><?php echo $row_terbuka->isi_pertanyaan_terbuka ?></td>
                                    </tr>

                                    <tr>
                                        <td width="5%"></td>
                                        <td style="font-weight:bold;" width="95%">

                                            @foreach ($jawaban_pertanyaan_terbuka->result() as $value_terbuka)

                                            @if ($value_terbuka->id_perincian_pertanyaan_terbuka ==
                                            $row_terbuka->id_perincian_pertanyaan_terbuka)

                                            <div class="radio-inline mb-2">
                                                <label class="radio radio-outline radio-success radio-lg"
                                                    style="font-size: 16px;">
                                                    <input type="radio" name="jawaban_pertanyaan_terbuka[<?= $n ?>]"
                                                        value="<?php echo $value_terbuka->pertanyaan_ganda; ?>"
                                                        <?php echo $value_terbuka->pertanyaan_ganda == $row_terbuka->jawaban ? 'checked' : '' ?>
                                                        required><span></span>
                                                    <?php echo $value_terbuka->pertanyaan_ganda; ?>
                                                </label>
                                            </div>

                                            @endif
                                            @endforeach

                                            @if ($row_terbuka->dengan_isian_lainnya == 1)
                                            <div class="radio-inline mb-2">
                                                <label class="radio radio-outline radio-success radio-lg"
                                                    style="font-size: 16px;">
                                                    <input type="radio" name="jawaban_pertanyaan_terbuka[<?= $n ?>]"
                                                        value="Lainnya"
                                                        <?php echo $row_terbuka->jawaban == 'Lainnya' ? 'checked' : '' ?>><span></span>
                                                    Lainnya</label>
                                            </div>
                                            <br>
                                            @endif

                                            @if ($row_terbuka->id_jenis_pilihan_jawaban == 2)
                                            <input class="form-control" type="text"
                                                name="jawaban_pertanyaan_terbuka[<?= $n ?>]"
                                                placeholder="Masukkan Jawaban Anda ..."
                                                value="<?php echo $row_terbuka->jawaban ?>" required></input>
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

                            <hr>
                        </div>

                        @php
                        $i++;
                        @endphp
                        @endforeach





                        {{-- Looping Pertanyaan Terbuka Paling Bawah --}}
                        @php
                        $b = $pertanyaan_terbuka_atas->num_rows() + $pertanyaan_terbuka->num_rows() + 1;
                        @endphp

                        @foreach ($pertanyaan_terbuka_bawah->result() as $row_terbuka_bawah)

                        <div class="mt-10 mb-10">
                            <input type="hidden" name="id_pertanyaan_terbuka[<?= $b ?>]"
                                value="{{$row_terbuka_bawah->id_pertanyaan_terbuka}}">

                            <table class="table table-borderless" width="100%" border="0">
                                <tr>
                                    <td width="5%" valign="top">{{$row_terbuka_bawah->nomor_pertanyaan_terbuka}}.</td>
                                    <td width="95%"><?php echo $row_terbuka_bawah->isi_pertanyaan_terbuka ?></td>
                                </tr>

                                <tr>
                                    <td width="5%"></td>
                                    <td style="font-weight:bold;" width="95%">

                                        @foreach ($jawaban_pertanyaan_terbuka->result() as $value_terbuka_bawah)

                                        @if ($value_terbuka_bawah->id_perincian_pertanyaan_terbuka ==
                                        $row_terbuka_bawah->id_perincian_pertanyaan_terbuka)

                                        <div class="radio-inline mb-2">
                                            <label class="radio radio-outline radio-success radio-lg"
                                                style="font-size: 16px;">
                                                <input type="radio" name="jawaban_pertanyaan_terbuka[<?= $b ?>]"
                                                    value="<?php echo $value_terbuka_bawah->pertanyaan_ganda; ?>"
                                                    <?php echo $value_terbuka_bawah->pertanyaan_ganda == $row_terbuka_bawah->jawaban ? 'checked' : '' ?>
                                                    required><span></span>
                                                <?php echo $value_terbuka_bawah->pertanyaan_ganda; ?>
                                            </label>
                                        </div>

                                        @endif
                                        @endforeach

                                        @if ($row_terbuka_bawah->dengan_isian_lainnya == 1)
                                        <div class="radio-inline mb-2">
                                            <label class="radio radio-outline radio-success radio-lg"
                                                style="font-size: 16px;">
                                                <input type="radio" name="jawaban_pertanyaan_terbuka[<?= $b ?>]"
                                                    value="Lainnya"
                                                    <?php echo $row_terbuka_bawah->jawaban == 'Lainnya' ? 'checked' : '' ?>><span></span>
                                                Lainnya</label>
                                        </div>
                                        <br>
                                        @endif

                                        @if ($row_terbuka_bawah->id_jenis_pilihan_jawaban == 2)
                                        <input class="form-control" type="text"
                                            name="jawaban_pertanyaan_terbuka[<?= $b ?>]"
                                            placeholder="Masukkan Jawaban Anda ..."
                                            value="<?php echo $row_terbuka_bawah->jawaban ?>" required></input>
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
                        <div class="text-right">
                            <button type="submit"
                                class="btn btn-warning btn-lg font-weight-bold shadow-lg tombolSave">Selanjutnya <i
                                    class="fa fa-arrow-right"></i></button>
                        </div>
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


<?php //foreach ($ci->db->get_where("kategori_unsur_pelayanan_$manage_survey->table_identity", array('id_pertanyaan_unsur' => $pr->id_pertanyaan_unsur))->result() as $val) { } 
?>


<script type="text/javascript">
$(function() {
    $(":radio.<?php echo $pr->id_pertanyaan_unsur; ?>").click(function() {
        $("#<?php echo $pr->id_pertanyaan_unsur; ?>").hide();
        // $("#display_terbuka_<?php echo $pr->id_pertanyaan_unsur; ?>").hide();

        if ($(this).val() == 1) {

            $("#<?php echo $pr->id_pertanyaan_unsur; ?>").show().prop('required', true);
            // $("#display_terbuka_<?php echo $pr->id_pertanyaan_unsur; ?>").hidden();

        } else if ($(this).val() == 2) {
            $("#<?php echo $pr->id_pertanyaan_unsur; ?>").show().prop('required', true);
            // $("#display_terbuka_<?php echo $pr->id_pertanyaan_unsur; ?>").hidden();

        } else if ($(this).val() == 3) {
            // $("#display_terbuka_<?php echo $pr->id_pertanyaan_unsur; ?>").show();
            $("#<?php echo $pr->id_pertanyaan_unsur; ?>").removeAttr('required').hidden();

        } else {

            // $("#display_terbuka_<?php echo $pr->id_pertanyaan_unsur; ?>").show();
            $("#<?php echo $pr->id_pertanyaan_unsur; ?>").removeAttr('required').hidden();
        }
    });
});

function cekdata(area, id) {
    var val = area.value;

    if (val == '') {
        $("#<?php echo $pr->id_pertanyaan_unsur; ?>").prop('required', true);
    } else {
        document.getElementById("<?php echo $pr->id_pertanyaan_unsur; ?>").required = false;
    }
}
</script>
@endforeach



<script>
$('.form_survei').submit(function(e) {

    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        dataType: 'json',
        data: $(this).serialize(),
        cache: false,
        beforeSend: function() {
            $('.tombolSave').attr('disabled', 'disabled');
            $('.tombolSave').html('<i class="fa fa-spin fa-spinner"></i> Sedang diproses');

            KTApp.block('#kt_blockui_content', {
                overlayColor: '#FFA800',
                state: 'primary',
                message: 'Processing...'
            });

            setTimeout(function() {
                KTApp.unblock('#kt_blockui_content');
            }, 1000);

        },
        complete: function() {
            $('.tombolSave').removeAttr('disabled');
            $('.tombolSave').html('Selanjutnya <i class="fa fa-arrow-right"></i>');
        },

        error: function(e) {
            Swal.fire(
                'Error !',
                e,
                'error'
            )
        },

        success: function(data) {
            if (data.validasi) {
                $('.pesan').fadeIn();
                $('.pesan').html(data.validasi);
            }
            if (data.sukses) {
                // toastr["success"]('Data berhasil disimpan');

                setTimeout(function() {
                    window.location.href = data.url_next;
                }, 500);
            }
        }
    })
    return false;
});
</script>
@endsection