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
            <table class="">
                <tr>
                    <td>
                        <div class="card border-secondary" data-aos="fade-up" id="kt_blockui_content"
                            style="font-size: 16px; font-family:Arial, Helvetica, sans-serif;">

                            @if($manage_survey->img_benner == '')
                            <img class="card-img-top" src="{{ base_url() }}assets/img/site/page/banner-survey.jpg"
                                alt="new image" />
                            @else
                            <img class="card-img-top shadow"
                                src="{{ base_url() }}assets/klien/benner_survei/{{$manage_survey->img_benner}}"
                                alt="new image">
                            @endif

                            <div class="card-header text-center">
                                <h4><b>PERTANYAAN KUALITATIF</b> -
                                    @include('include_backend/partials_backend/_tanggal_survei')</h4>
                            </div>
                        </div>


                        @php
                        $no = 1;
                        @endphp

                        @foreach ($kualitatif as $row)
                        @if($row->is_active == 1)

                        <div class="card mb-4 mt-4" data-aos="fade-up"
                            style="border-left: 5px solid #FFA800; font-size: 16px; font-family:Arial, Helvetica, sans-serif;">
                            <div class="text-center mt-2">
                                <div class="dropdown">
                                    <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-bars"></i>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" data-toggle="modal" title="Detail Klien"
                                            onclick="showedit('<?php echo $row->id ?>')" href="#modal_edit">Edit</a>

                                        <a class="dropdown-item" href="javascript:void(0)"
                                            title="Hapus Pertanyaan Kualitatif"
                                            onclick="delete_pertanyaan_kualitatif('<?php echo $row->id ?>')">Hapus</a>
                                    </div>
                                </div>
                                <hr>
                            </div>

                            <div class="card-body">
                                <table class="table table-borderless" border="0">
                                    <tr>
                                        <td width="4%" valign="top">{{ $no++ }}.</td>
                                        <td><?php echo $row->isi_pertanyaan ?></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>
                                            <textarea type="text" rows="7" class="form-control"
                                                id="isi_jawaban_kualitatif" name="isi_jawaban_kualitatif[]" value=""
                                                placeholder="Masukkan jawaban pertanyaan kualitatif pada bidang ini.."></textarea>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        @else

                        <div class="card mb-4 mt-4 bg-light" data-aos="fade-up"
                            style="border-left: 5px solid #FFA800; font-size: 16px; font-family:Arial, Helvetica, sans-serif;">
                            <div class="text-center mt-2">
                                <div class="dropdown">
                                    <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-bars"></i>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" data-toggle="modal" title="Detail Klien"
                                            onclick="showedit('<?php echo $row->id ?>')" href="#modal_edit">Edit</a>

                                        <a class="dropdown-item" href="javascript:void(0)"
                                            title="Hapus Pertanyaan Kualitatif"
                                            onclick="delete_pertanyaan_kualitatif('<?php echo $row->id ?>')">Hapus</a>
                                    </div>
                                </div>
                                <hr>
                            </div>

                            <div class="card-body">
                                <table class="table table-borderless" border="0">
                                    <tr>
                                        <td width="4%" valign="top">{{ $no++ }}.</td>
                                        <td><?php echo $row->isi_pertanyaan ?></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>
                                            <textarea type="text" rows="7" class="form-control"
                                                id="isi_jawaban_kualitatif" name="isi_jawaban_kualitatif[]" value=""
                                                placeholder="Masukkan jawaban pertanyaan kualitatif pada bidang ini.."
                                                disabled></textarea>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        @endif

                        @endforeach



                        <table class="table table-borderless">
                            <tr>
                                <td class="text-left">
                                    {!! anchor($url_back, '<i class="fa fa-arrow-left"></i>
                                    Kembali',
                                    ['class' => 'btn btn-secondary btn-lg font-weight-bold shadow']) !!}
                                </td>
                                <td class="text-right">
                                    <a class="btn btn-warning btn-lg font-weight-bold shadow"
                                        href="<?php echo $url_next ?>">Selanjutnya<i class="fa fa-arrow-right"></i></a>
                                </td>
                            </tr>
                        </table>
                    </td>

                    <td valign="top">
                        <div class="col-sm-1 sticky_button_edit">
                            <div class="btn-group-vertical mr-2" role="group" aria-label="First group"
                                data-aos="fade-up" style="box-shadow: 5px 5px 5px rgba(0, 0, 0, 0.19);">

                                <button type="button" class="btn btn-white" data-toggle="modal"
                                    data-target="#tambah"><span data-toggle="tooltip" data-placement="right"
                                        title="Tambah Pertanyaan Kualitatif"><i class="fa fa-plus"></i></span>
                                </button>

                                <a type="button" class="btn btn-white"
                                    href="<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/preview-form-survei/pertanyaan-kualitatif' ?>"
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

@include("form_survei/pertanyaan_kualitatif/modal_tambah_kualitatif")


<!-- ======================================= EDIT PERTANYAAN KUALITATIF ========================================== -->
<div class="modal fade" id="modal_edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border border-warning">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="exampleModalLabel">Edit Pertanyaan Kualitatif</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body" id="bodyModalEdit">
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

<script>
ClassicEditor
    .create(document.querySelector('#isi_pertanyaan'))
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
function showedit(id) {
    $('#bodyModalEdit').html(
        "<div class='text-center'><img src='{{ base_url() }}assets/img/ajax/ajax-loader-big.gif'></div>");

    $.ajax({
        type: "post",
        url: "<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/form-survei/detail-edit-pertanyaan-kualitatif/' ?>" +
            id,
        // data: "id=" + id,
        dataType: "text",
        success: function(response) {

            // $('.modal-title').text('Edit Pertanyaan Unsur');
            $('#bodyModalEdit').empty();
            $('#bodyModalEdit').append(response);
        }
    });
}
</script>


<script>
function delete_pertanyaan_kualitatif(id_pertanyaan_kualitatif) {
    if (confirm('Are you sure delete this data?')) {
        $.ajax({
            url: "<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/pertanyaan-kualitatif/delete/' ?>" +
                id_pertanyaan_kualitatif,
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