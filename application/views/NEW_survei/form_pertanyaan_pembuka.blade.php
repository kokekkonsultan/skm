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
                    <h4><b>PERTANYAAN PEMBUKA</b> - @include('include_backend/partials_backend/_tanggal_survei')</h4>
                </div>

                <form action="<?php echo base_url() . 'survei/' . $ci->uri->segment(2) . '/add-pertanyaan-pembuka/' .
                                    $ci->uri->segment(4) ?>" class="form_survei" method="POST">

                    <div class="card-body ml-5 mr-5">


                        <!-- Looping Pertanyaan Pembuka -->
                        @php
                        $a = 1;
                        @endphp

                        @foreach ($pertanyaan_terbuka_pembuka->result() as $row_terbuka_pembuka)

                        @php
                        $jawaban =
                        $ci->db->get_where("jawaban_pertanyaan_terbuka_$manage_survey->table_identity",array('id_pertanyaan_terbuka'
                        => $row_terbuka_pembuka->id_pertanyaan_terbuka, 'id_responden'=> $id_res))->row();

                        if($jawaban->is_active == 1 && $jawaban->jawaban == ''){
                        $display = 'style="display: none;"';
                        $required = '';

                        } else if($jawaban->is_active == 1 && $jawaban->jawaban != '') {
                        $display = '';
                        $required = 'required';
                        } else {
                        $display = '';
                        $required = '';
                        };
                        @endphp


                        <div class="mt-5 mb-10" id="terbuka_display_{{$row_terbuka_pembuka->nomor_pertanyaan_terbuka}}"
                            <?php echo $display ?>>
                            <input type="hidden" name="id_pertanyaan_terbuka[<?= $a ?>]"
                                value="{{$row_terbuka_pembuka->id_pertanyaan_terbuka}}">

                            <table class="table table-borderless" width="100%" border="0">
                                <tr>
                                    <td width="5%" valign="top">{{$row_terbuka_pembuka->nomor_pertanyaan_terbuka}}.</td>
                                    <td><?php echo $row_terbuka_pembuka->isi_pertanyaan_terbuka ?></td>
                                </tr>
                                <tr>
                                    <td width="5%"></td>
                                    <td style="font-weight:bold;" width="95%">

                                        @foreach ($jawaban_pertanyaan_terbuka->result() as $value_terbuka_pembuka)

                                        @if ($value_terbuka_pembuka->id_perincian_pertanyaan_terbuka ==
                                        $row_terbuka_pembuka->id_perincian_pertanyaan_terbuka)

                                        <div class="radio-inline mb-2">
                                            <label class="radio radio-outline radio-success radio-lg"
                                                style="font-size: 16px;">

                                                <input type="radio" name="jawaban_pertanyaan_terbuka[<?= $a ?>]"
                                                    value="<?php echo $value_terbuka_pembuka->pertanyaan_ganda ?>"
                                                    <?php echo $value_terbuka_pembuka->pertanyaan_ganda == $row_terbuka_pembuka->jawaban ? 'checked' : '' ?>
                                                    class="terbuka_{{$row_terbuka_pembuka->nomor_pertanyaan_terbuka}}"
                                                    id="id_{{$row_terbuka_pembuka->nomor_pertanyaan_terbuka}}"
                                                    <?php echo $required ?>><span></span>
                                                <?php echo $value_terbuka_pembuka->pertanyaan_ganda; ?>
                                            </label>
                                        </div>

                                        @endif
                                        @endforeach

                                        @if ($row_terbuka_pembuka->dengan_isian_lainnya == 1)
                                        <div class="radio-inline mb-2">
                                            <label class="radio radio-outline radio-success radio-lg"
                                                style="font-size: 16px;">
                                                <input type="radio" name="jawaban_pertanyaan_terbuka[<?= $a ?>]"
                                                    value="Lainnya"
                                                    class="terbuka_{{$row_terbuka_pembuka->nomor_pertanyaan_terbuka}}"
                                                    id="id_{{$row_terbuka_pembuka->nomor_pertanyaan_terbuka}}"
                                                    <?php echo $row_terbuka_pembuka->jawaban == 'Lainnya' ? 'checked' : '' ?>
                                                    <?php echo $required ?>><span></span>Lainnya</label>
                                        </div>
                                        <textarea class="form-control mt-7" type="text"
                                            id="jawaban_lainnya_{{$row_terbuka_pembuka->nomor_pertanyaan_terbuka}}"
                                            name="jawaban_lainnya[<?= $a ?>]" placeholder="Masukkan Jawaban Anda ..."
                                            <?php echo $row_terbuka_pembuka->jawaban == 'Lainnya' ? 'required' : 'style="display:none"' ?>>{{$row_terbuka_pembuka->jawaban_lainnya}}</textarea>
                                        @endif

                                        @if ($row_terbuka_pembuka->id_jenis_pilihan_jawaban == 2)
                                        <textarea class="form-control" type="text"
                                            name="jawaban_pertanyaan_terbuka[<?= $a ?>]"
                                            placeholder="Masukkan Jawaban Anda ..."
                                            <?php echo $required ?>><?php echo $row_terbuka_pembuka->jawaban ?></textarea>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                            <hr>
                            <hr>
                        </div>
                        @php
                        $a++;
                        @endphp

                        @endforeach

                    </div>
                    <div class="card-footer">

                        <table class="table table-borderless">
                            <tr>
                                @if($ci->uri->segment(5) == 'edit')
                                <td class="text-left">
                                    <a class="btn btn-secondary btn-lg font-weight-bold shadow"
                                        href="<?php echo base_url() . 'survei/' . $ci->uri->segment(2) . '/data-responden/' . $ci->uri->segment(4) . '/edit' ?>"><i
                                            class="fa fa-arrow-left"></i> Kembali</a>
                                </td>
                                @endif

                                <td class="text-right">
                                    <button type="submit"
                                        class="btn btn-warning btn-lg font-weight-bold shadow-lg tombolSave">Selanjutnya
                                        <i class="fa fa-arrow-right"></i></button>
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


@foreach ($pertanyaan_terbuka_pembuka->result() as $val_terbuka_pembuka)

@php
$pilihan_jawaban_pembuka = $ci->db->query("SELECT *, (SELECT nomor_pertanyaan_terbuka FROM
pertanyaan_terbuka_$manage_survey->table_identity JOIN perincian_pertanyaan_terbuka_$manage_survey->table_identity ON
pertanyaan_terbuka_$manage_survey->table_identity.id =
perincian_pertanyaan_terbuka_$manage_survey->table_identity.id_pertanyaan_terbuka WHERE
isi_pertanyaan_ganda_$manage_survey->table_identity.id_perincian_pertanyaan_terbuka =
perincian_pertanyaan_terbuka_$manage_survey->table_identity.id) AS nomor_pertanyaan_terbuka,

IF(is_next_step != '', is_next_step, CONCAT('T',(SUBSTR((SELECT nomor_pertanyaan_terbuka FROM
pertanyaan_terbuka_$manage_survey->table_identity JOIN perincian_pertanyaan_terbuka_$manage_survey->table_identity ON
pertanyaan_terbuka_$manage_survey->table_identity.id =
perincian_pertanyaan_terbuka_$manage_survey->table_identity.id_pertanyaan_terbuka WHERE
isi_pertanyaan_ganda_$manage_survey->table_identity.id_perincian_pertanyaan_terbuka =
perincian_pertanyaan_terbuka_$manage_survey->table_identity.id),2) + 1))) AS hasil_if

FROM isi_pertanyaan_ganda_$manage_survey->table_identity WHERE id_perincian_pertanyaan_terbuka =
$val_terbuka_pembuka->id_perincian_pertanyaan_terbuka");
@endphp

<script type="text/javascript">
$(function() {
    $(":radio.terbuka_{{$val_terbuka_pembuka->nomor_pertanyaan_terbuka}}").click(function() {


        <?php
            //LOOP 1
            foreach ($pilihan_jawaban_pembuka->result() as $get_val_terbuka_pembuka) { ?>

        if ($(this).val() == '<?php echo $get_val_terbuka_pembuka->pertanyaan_ganda ?>') {



            <?php
                    //LOOP 2
                    foreach ($pertanyaan_terbuka_pembuka->result() as $val_js_pembuka) {

                        if (substr($val_js_pembuka->nomor_pertanyaan_terbuka, 1) < substr($val_terbuka_pembuka->nomor_pertanyaan_terbuka, 1)) {
                            $status = '';
                            $rq = '';
                        } else if ((substr($val_js_pembuka->nomor_pertanyaan_terbuka, 1) > substr($get_val_terbuka_pembuka->nomor_pertanyaan_terbuka, 1)) && (substr($val_js_pembuka->nomor_pertanyaan_terbuka, 1) < substr($get_val_terbuka_pembuka->hasil_if, 1))) {

                            $status = '.hide()';
                            $rq = ".removeAttr('required')";
                        } else {
                            $status = '.show()';
                            $rq = ".prop('required', true)";
                        };
                    ?>

            $(
                "#terbuka_display_<?php echo $val_js_pembuka->nomor_pertanyaan_terbuka ?>"
            ) <?php echo $status ?>;
            $("#id_<?php echo $val_js_pembuka->nomor_pertanyaan_terbuka ?>") <?php echo $rq ?>;

            $("#jawaban_lainnya_<?php echo $get_val_terbuka_pembuka->nomor_pertanyaan_terbuka ?>")
                .removeAttr('required').hide();

            <?php } ?>

        }



        if ($(this).val() == 'Lainnya') {
            $("#jawaban_lainnya_<?php echo $get_val_terbuka_pembuka->nomor_pertanyaan_terbuka ?>")
                .show()
                .prop('required', true);
        }

        <?php } ?>


    });
});
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
                    window.location.href = "<?php echo $url_next ?>";
                }, 500);
            }
        }
    })
    return false;
});
</script>
@endsection