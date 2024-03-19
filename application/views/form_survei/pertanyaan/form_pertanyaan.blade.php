@extends('include_backend/_template')

@php
$ci = get_instance();
@endphp

@section('style')
<link rel="dns-prefetch" href="//fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
<style>
.sticky_button_edit {
    position: -webkit-sticky;
    position: sticky;
    top: 30%;
}
</style>

<style>
.border-menu {
    border-color: #304EC0 !important;
    background-color: #f3f3f3;
}

.card-menu {
    cursor: pointer;
}
</style>
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
            <table>
                <tr>
                    <td style="font-size: 16px; font-family:Arial, Helvetica, sans-serif;">
                        <div class="card border-secondary" data-aos="fade-up" id="kt_blockui_content">

                            @if($manage_survey->img_benner == '')
                            <img class="card-img-top" src="{{ base_url() }}assets/img/site/page/banner-survey.jpg"
                                alt="new image" />
                            @else
                            <img class="card-img-top shadow"
                                src="{{ base_url() }}assets/klien/benner_survei/{{$manage_survey->img_benner}}"
                                alt="new image">
                            @endif

                            <div class="card-header text-center">
                                <h4><b>PERTANYAAN UNSUR</b> -
                                    @include('include_backend/partials_backend/_tanggal_survei')</h4>
                            </div>
                        </div>




                        {{-- Looping Pertanyaan Terbuka ATAS --}}
                        @foreach ($pertanyaan_terbuka_atas->result() as $row_terbuka_atas)

                        <div class="card mb-4 mt-4" data-aos="fade-up" style="border-left: 5px solid #FFA800;">
                            <div class="text-center mt-2">
                                <div class="dropdown">
                                    <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-bars"></i>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" data-toggle="modal" title="Detail Klien"
                                            data-backdrop="static" data-keyboard="false"
                                            onclick="showedittambahan('<?php echo $row_terbuka_atas->id_pertanyaan_terbuka ?>')"
                                            href="#modal_edit_tambahan">Edit</a>

                                        <a class="dropdown-item" href="javascript:void(0)" title="Hapus"
                                            onclick="delete_pertanyaan_terbuka('<?php echo $row_terbuka_atas->id_pertanyaan_terbuka ?>')">Hapus</a>
                                    </div>
                                </div>
                                <hr>
                            </div>

                            <div class="card-body">
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
                                                    <input type="radio" value="" required><span></span>
                                                    <?php echo $value_terbuka_atas->pertanyaan_ganda; ?>
                                                </label>
                                            </div>

                                            @endif
                                            @endforeach

                                            @if ($row_terbuka_atas->dengan_isian_lainnya == 1)
                                            <div class="radio-inline mb-2">
                                                <label class="radio radio-outline radio-success radio-lg"
                                                    style="font-size:16px">
                                                    <input type="radio" value="Lainnya"><span></span>
                                                    Lainnya</label>
                                            </div>
                                            <br>
                                            @endif

                                            @if ($row_terbuka_atas->id_jenis_pilihan_jawaban == 2)
                                            <input class="form-control" type="text"
                                                placeholder="Masukkan Jawaban Anda ..." value=""></input>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        @endforeach





                        {{-- Looping Pertanyaan Unsur --}}
                        @foreach ($pertanyaan_unsur->result() as $row)

                        <div class="card mb-4 mt-4" data-aos="fade-up" style="border-left: 5px solid #FFA800;">
                            <div class="text-center mt-2">
                                <div class="dropdown">
                                    <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-bars"></i>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" data-toggle="modal" title="Detail Klien"
                                            data-backdrop="static" data-keyboard="false"
                                            onclick="showeditunsur('<?php echo $row->id_unsur ?>')"
                                            href="#modal_edit">Edit</a>


                                        @php
                                        $cek_unsur = $ci->db->get_where("unsur_pelayanan_$table_identity",
                                        array('id_parent' => $row->id_unsur));
                                        @endphp

                                        @if ($cek_unsur->num_rows() == 0)
                                        <a class="dropdown-item" href="javascript:void(0)"
                                            title="Hapus <?php echo $row->nama_unsur_pelayanan ?>"
                                            onclick="delete_data('<?php echo $row->id_unsur ?>')">Hapus</a>
                                        @else
                                        <a class="dropdown-item" title="Hapus <?php echo $row->nama_unsur_pelayanan ?>"
                                            onclick="cek()">Hapus</a>
                                        @endif
                                    </div>
                                </div>
                                <hr>
                            </div>

                            <div class="card-body">
                                <table class="table table-borderless" width="100%" border="0">
                                    <tr>
                                        <td width="4%" valign="top"><b>{{ $row->nomor_unsur }}.</b></td>
                                        <td><b>{{ $row->nama_unsur_pelayanan}}</b><br><?php echo $row->isi_pertanyaan_unsur ?>
                                        </td>
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
                                                        class="pilihan_<?php echo $row->id_unsur ?>"><span></span>
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
                                                id="alasan_<?php echo $row->id_unsur ?>"
                                                placeholder="Berikan alasan jawaban anda ..."
                                                style="display:none"></textarea>

                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>


                        {{-- Looping Pertanyaan Terbuka --}}
                        @foreach ($pertanyaan_terbuka->result() as $row_terbuka)

                        @if ($row_terbuka->id_unsur_pelayanan == $row->id_unsur_pelayanan)
                        <div class="card mb-4 mt-4" data-aos="fade-up" style="border-left: 5px solid #FFA800;">
                            <div class="text-center mt-2">
                                <div class="dropdown">
                                    <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-bars"></i>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" data-toggle="modal" title="Detail Klien"
                                            data-backdrop="static" data-keyboard="false"
                                            onclick="showedittambahan('<?php echo $row_terbuka->id_pertanyaan_terbuka ?>')"
                                            href="#modal_edit_tambahan">Edit</a>
                                        <a class="dropdown-item" href="javascript:void(0)" title="Hapus"
                                            onclick="delete_pertanyaan_terbuka('<?php echo $row_terbuka->id_pertanyaan_terbuka ?>')">Hapus</a>
                                    </div>
                                </div>
                                <hr>
                            </div>

                            <div class="card-body">
                                <input type="hidden" value="{{$row_terbuka->id_pertanyaan_terbuka}}">
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
                                                    <input type="radio" value="" required><span></span>
                                                    <?php echo $value_terbuka->pertanyaan_ganda; ?>
                                                </label>
                                            </div>

                                            @endif
                                            @endforeach

                                            @if ($row_terbuka->dengan_isian_lainnya == 1)
                                            <div class="radio-inline mb-2">
                                                <label class="radio radio-outline radio-success radio-lg"
                                                    style="font-size:16px">
                                                    <input type="radio" value="Lainnya"><span></span>
                                                    Lainnya</label>
                                            </div>
                                            <br>
                                            @endif

                                            @if ($row_terbuka->id_jenis_pilihan_jawaban == 2)
                                            <input class="form-control" type="text"
                                                placeholder="Masukkan Jawaban Anda ..." value=""></input>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        @endif
                        @endforeach
                        @endforeach







                        {{-- Looping Pertanyaan Terbuka BAWAH --}}
                        @foreach ($pertanyaan_terbuka_bawah->result() as $row_terbuka_bawah)
                        <div class="card mb-4 mt-4" data-aos="fade-up" style="border-left: 5px solid #FFA800;">
                            <div class="text-center mt-2">
                                <div class="dropdown">
                                    <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-bars"></i>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" data-toggle="modal" title="Detail Klien"
                                            data-backdrop="static" data-keyboard="false"
                                            onclick="showedittambahan('<?php echo $row_terbuka_bawah->id_pertanyaan_terbuka ?>')"
                                            href="#modal_edit_tambahan">Edit</a>
                                        <a class="dropdown-item" href="javascript:void(0)" title="Hapus"
                                            onclick="delete_pertanyaan_terbuka('<?php echo $row_terbuka_bawah->id_pertanyaan_terbuka ?>')">Hapus</a>
                                    </div>
                                </div>
                                <hr>
                            </div>

                            <div class="card-body">
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
                                                    <input type="radio" value="" required><span></span>
                                                    <?php echo $value_terbuka_bawah->pertanyaan_ganda; ?>
                                                </label>
                                            </div>

                                            @endif
                                            @endforeach

                                            @if ($row_terbuka_bawah->dengan_isian_lainnya == 1)
                                            <div class="radio-inline mb-2">
                                                <label class="radio radio-outline radio-success radio-lg"
                                                    style="font-size:16px">
                                                    <input type="radio" value="Lainnya"><span></span>
                                                    Lainnya</label>
                                            </div>
                                            <br>
                                            @endif

                                            @if ($row_terbuka_bawah->id_jenis_pilihan_jawaban == 2)
                                            <input class="form-control" type="text"
                                                placeholder="Masukkan Jawaban Anda ..." value=""></input>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        @endforeach





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



                    </td>
                    <td valign="top">
                        <div class="col-sm-1 sticky_button_edit">
                            <div class="btn-group-vertical mr-2" role="group" aria-label="First group"
                                data-aos="fade-up" style="box-shadow: 5px 5px 5px rgba(0, 0, 0, 0.19);">

                                <button type="button" class="btn btn-white" data-toggle="modal" data-target="#tambah1"
                                    data-backdrop="static" data-keyboard="false">
                                    <span data-toggle="tooltip" data-placement="right"
                                        title="Tambah Pertanyaan Unsur Pelayanan"><i
                                            class="fa fa-check-square"></i></span>
                                </button>

                                <button type="button" class="btn btn-white" data-toggle="modal" data-target="#tambah2"
                                    data-backdrop="static" data-keyboard="false">
                                    <span data-toggle="tooltip" data-placement="right"
                                        title="Tambah Pertanyaan Sub Unsur Pelayanan"><i class="fa fa-list"></i></span>
                                </button>

                                <button type="button" class="btn btn-white" data-toggle="modal" data-target="#tambah3">
                                    <span data-toggle="tooltip" data-placement="right"
                                        title="Tambah Pertanyaan Tambahan"><i class="fa fa-indent"></i></span>
                                </button>

                                <a type="button" class="btn btn-white"
                                    href="<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/preview-form-survei/pertanyaan' ?>"
                                    target="_blank"><span data-toggle="tooltip" data-placement="right"
                                        title="Lihat Tampilan Form Survei"><i class="fa fa-eye"></i></span></a>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>


@include("form_survei/pertanyaan/modal_tambah_pertanyaan_unsur")
@include("form_survei/pertanyaan/modal_tambah_sub_pertanyaan_unsur")
@include("form_survei/pertanyaan/modal_tambah_pertanyaan_tambahan")



<!-- ======================================= EDIT PERTANYAAN UNSUR ========================================== -->
<div class="modal fade" id="modal_edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border border-warning">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="exampleModalLabel">Edit Unsur Pelayanan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body" id="bodyModalEditUnsur">
                <div align="center" id="loading_registration">
                    <img src="{{ base_url() }}assets/site/img/ajax-loader.gif" alt="">
                </div>
            </div>
        </div>
    </div>
</div>


<!-- ======================================= EDIT PERTANYAAN TAMBAHAN ========================================== -->
<div class="modal fade" id="modal_edit_tambahan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border border-warning">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="exampleModalLabel">Edit Pertanyaan Tambahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body" id="bodyModalEditTambahan">
                <div align="center" id="loading_registration">
                    <img src="{{ base_url() }}assets/site/img/ajax-loader.gif" alt="">
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@section('javascript')
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/34.2.0/classic/ckeditor.js"></script>


@foreach ($pertanyaan_unsur->result() as $row)
<script>
$(function() {
    $(":radio.pilihan_<?php echo $row->id_unsur; ?>")
        .click(
            function() {
                $("#alasan_<?php echo $row->id_unsur; ?>")
                    .hide()

                if ($(this).val() == 1) {
                    $("#alasan_<?php echo $row->id_unsur; ?>")
                        .show();
                } else if ($(this).val() == 2) {
                    $("#alasan_<?php echo $row->id_unsur; ?>")
                        .show();
                } else {
                    $("#alasan_<?php echo $row->id_unsur; ?>")
                        .hidden();
                }
            });
});
</script>
@endforeach



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



<script type="text/javascript">
$(function() {
    $(":radio.custom").click(function() {
        $("#dengan_sub_unsur").hide();
        $("#tanpa_sub_unsur").hide();
        if ($(this).val() == "1") {
            $("#jenis_pilihan_jawaban").removeAttr('required');
            $("#dengan_sub_unsur").show();
            $("#tanpa_sub_unsur").hidden();
        } else {
            $("#jenis_pilihan_jawaban").prop('required', true);
            $("#tanpa_sub_unsur").show();
            $("#dengan_sub_unsur").hidden();
        }
    });
});
</script>



<script type="text/javascript">
$(function() {
    $(":radio.jawaban").click(function() {
        $(".4_jawaban").hide()
        $(".2_jawaban").hide()
        if ($(this).val() == "2") {
            $(".pilihan_jawaban").removeAttr('required');
            $(".pilihan").prop('required', true);
            $(".4_jawaban").show();
            $(".2_jawaban").hide();
        } else {
            $(".pilihan").removeAttr('required');
            $(".pilihan_jawaban").prop('required', true);
            $(".2_jawaban").show();
            $(".4_jawaban").hide();
        }
    });
});
</script>


<script>
function autofill() {
    var id = document.getElementById('id').value;
    $.ajax({
        url: "<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/pertanyaan-harapan/cari' ?>",
        data: '&id=' + id,
        success: function(data) {
            var hasil = JSON.parse(data);

            $.each(hasil, function(key, val) {

                document.getElementById('id').value = val.pilihan_1;
                document.getElementById('pilihan_2').value = val.pilihan_2;
                document.getElementById('pilihan_3').value = val.pilihan_3;
                document.getElementById('pilihan_4').value = val.pilihan_4;
            });
        }
    });
}

function autofill_new() {
    var id = document.getElementById('pilihan_5').value;
    $.ajax({
        url: "<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/pertanyaan-harapan/cari' ?>",
        data: '&id=' + id,
        success: function(data) {
            var hasil = JSON.parse(data);

            $.each(hasil, function(key, val) {

                document.getElementById('pilihan_5').value = val.pilihan_1;
                document.getElementById('pilihan_6').value = val.pilihan_2;
                document.getElementById('pilihan_7').value = val.pilihan_3;
                document.getElementById('pilihan_8').value = val.pilihan_4;
            });
        }
    });
}
</script>

<script>
ClassicEditor
    .create(document.querySelector('#isi_pertanyaan_unsur'))
    .then(editor => {
        console.log(editor);
    })
    .catch(error => {
        console.error(error);
    });

ClassicEditor
    .create(document.querySelector('#isi_pertanyaan_terbuka'))
    .then(editor => {
        console.log(editor);
    })
    .catch(error => {
        console.error(error);
    });
</script>


<script>
$('.form_default').submit(function(e) {

    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        dataType: 'json',
        data: $(this).serialize(),
        cache: false,
        beforeSend: function() {
            $('.tombolDefault').attr('disabled', 'disabled');
            $('.tombolDefault').html(
                '<i class="fa fa-spin fa-spinner"></i> Sedang diproses');

            KTApp.block('#content_1', {
                overlayColor: '#000000',
                state: 'primary',
                message: 'Processing...'
            });

            setTimeout(function() {
                KTApp.unblock('#content_1');
            }, 1000);

        },
        complete: function() {
            $('.tombolDefault').removeAttr('disabled');
            $('.tombolDefault').html('Simpan');
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
                toastr["success"]('Data berhasil disimpan');
                window.setTimeout(function() {
                    location.reload()
                }, 1500);
            }
        }
    })
    return false;
});
</script>


<script>
function delete_data(id) {
    if (confirm('Are you sure delete this data?')) {
        $.ajax({
            url: "<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/pertanyaan-unsur/delete/' ?>" +
                id,
            type: "POST",
            dataType: "JSON",
            success: function(data) {
                if (data.status) {

                    Swal.fire(
                        'Informasi',
                        'Berhasil menghapus data',
                        'success'
                    );
                    window.setTimeout(function() {
                        location.reload()
                    }, 1500);

                } else {
                    Swal.fire(
                        'Informasi',
                        'Hak akses terbatasi. Bukan akun administrator.',
                        'warning'
                    );
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // toastr["warning"]('Error deleting data');

                alert('Error deleting data');
                window.setTimeout(function() {
                    location.reload()
                }, 1500);
            }
        });

    }
}
</script>


<script>
function showeditunsur(id) {
    $('#bodyModalEditUnsur').html(
        "<div class='text-center'><img src='{{ base_url() }}assets/img/ajax/ajax-loader-big.gif'></div>");

    $.ajax({
        type: "post",
        url: "<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/form-survei/detail-edit-pertanyaan-unsur/' ?>" +
            id,
        // data: "id=" + id,
        dataType: "text",
        success: function(response) {

            // $('.modal-title').text('Edit Pertanyaan Unsur');
            $('#bodyModalEditUnsur').empty();
            $('#bodyModalEditUnsur').append(response);
        }
    });
}
</script>


<script>
function cek() {
    Swal.fire({
        icon: 'warning',
        title: 'Informasi',
        text: 'Unsur tidak dapat dihapus karna masih terdapat sub unsur turunan di bawahnya. Silahkan hapus sub unsur turunan terlebih dahulu!',
        allowOutsideClick: false,
        confirmButtonColor: '#DD6B55',
        confirmButtonText: 'Ya, Saya mengerti !',
    });
}
</script>


<!-- PERTANYAAN TAMBAHAN -->
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
<script type="text/javascript">
$(function() {
    $(":radio.pilihan").click(function() {

        $("#opsi_1").hide()
        if ($(this).val() == "1") {
            $("#opsi_1").show();
        } else {
            $("#opsi_1").hide();
        }
    });
});
</script>


<script type="text/javascript">
$(function() {
    $(":radio.jenis_pertanyaan_tambahan").click(function() {
        $("#melekat_pada_unsur").hide();
        $("#tidak_melekat_pada_unsur").hide();
        $("#pertanyaan_lainnya").hide();
        if ($(this).val() == "1") {
            $("#is_letak_pertanyaan_tambahan").removeAttr('required');
            $("#id_unsur_pelayanan").prop('required', true);
            $("#melekat_pada_unsur").show();
            $("#pertanyaan_lainnya").show();
            $("#tidak_melekat_pada_unsur").hidden();
        } else {
            $("#id_unsur_pelayanan").removeAttr('required');
            $("#is_letak_pertanyaan_tambahan").prop('required', true);
            $("#tidak_melekat_pada_unsur").show();
            $("#pertanyaan_lainnya").show();
            $("#melekat_pada_unsur").hidden();
        }
    });
});
</script>


<script>
$(document).ready(function() {
    // membatasi jumlah inputan
    var maxGroup = 10;

    //melakukan proses multiple input 
    $(".addMore").click(function() {
        if ($('body').find('.fieldGroup').length < maxGroup) {
            var fieldHTML = '<div class="form-group fieldGroup">' + $(".fieldGroupCopy").html() +
                '</div>';
            $('body').find('.fieldGroup:last').after(fieldHTML);
        } else {
            alert('Maximum ' + maxGroup + ' groups are allowed.');
        }
    });

    //remove fields group
    $("body").on("click", ".remove", function() {
        $(this).parents(".fieldGroup").remove();
    });
});
</script>


<script>
function showedittambahan(id) {
    $('#bodyModalEditTambahan').html(
        "<div class='text-center'><img src='{{ base_url() }}assets/img/ajax/ajax-loader-big.gif'></div>");

    $.ajax({
        type: "post",
        url: "<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/form-survei/detail-edit-pertanyaan-tambahan/' ?>" +
            id,
        // data: "id=" + id,
        dataType: "text",
        success: function(response) {

            // $('.modal-title').text('Edit Pertanyaan Unsur');
            $('#bodyModalEditTambahan').empty();
            $('#bodyModalEditTambahan').append(response);
        }
    });
}
</script>


<script>
function delete_pertanyaan_terbuka(id_pertanyaan_terbuka) {
    if (confirm('Are you sure delete this data?')) {
        $.ajax({
            url: "<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/pertanyaan-terbuka-survey/delete/' ?>" +
                id_pertanyaan_terbuka,
            type: "POST",
            dataType: "JSON",
            success: function(data) {
                if (data.status) {

                    Swal.fire(
                        'Informasi',
                        'Berhasil menghapus data',
                        'success'
                    );
                    window.setTimeout(function() {
                        location.reload()
                    }, 1500);
                } else {
                    Swal.fire(
                        'Informasi',
                        'Hak akses terbatasi. Bukan akun administrator.',
                        'warning'
                    );
                }


            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error deleting data');
                window.setTimeout(function() {
                    location.reload()
                }, 1500);
            }
        });

    }
}
</script>

@endsection