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
            <li id="personal"><strong>Pertanyaan Survei</strong></li>
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
                        <div class="card shadow" data-aos="fade-up" id="kt_blockui_content">

                            @if($manage_survey->img_benner == '')
                            <img class="card-img-top" src="{{ base_url() }}assets/img/site/page/banner-survey.jpg"
                                alt="new image" />
                            @else
                            <img class="card-img-top shadow"
                                src="{{ base_url() }}assets/klien/benner_survei/{{$manage_survey->img_benner}}"
                                alt="new image">
                            @endif

                            <div class="card-header text-center">
                                <h5><b>DATA RESPONDEN</b> - @include('include_backend/partials_backend/_tanggal_survei')
                                </h5>
                            </div>
                        </div>

                        <div class="card mb-4 mt-4" data-aos="fade-up" style="border-left: 5px solid #FFA800;">
                            <div class="card-body">

                                <span style="color: red; font-style: italic;">{!! validation_errors() !!}</span>

                                <div class="form-group">
                                    <label class="font-weight-bold">Nama Lengkap <span
                                            class="text-danger">*</span></label>
                                    @php
                                    echo form_input($nama_lengkap);
                                    @endphp
                                </div>
                            </div>
                        </div>


                        @foreach ($profil_responden->result() as $row)

                        <div class="card mb-4 mt-4" data-aos="fade-up" style="border-left: 5px solid #FFA800;">
                            <div class="text-center mt-2">
                                <div class="dropdown">
                                    <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-bars"></i>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" data-toggle="modal"
                                            data-target="#edit{{$row->id}}">Edit</a>

                                        <a class="dropdown-item" href="javascript:void(0)"
                                            title="Hapus <?php echo $row->nama_profil_responden ?>"
                                            onclick="delete_data('<?php echo $row->id ?>')">Hapus</a>
                                    </div>
                                </div>
                                <hr>
                            </div>

                            <div class="card-body">

                                <div class="form-group">
                                    <label class="font-weight-bold"><?php echo $row->nama_profil_responden ?>
                                        <span class="text-danger">*</span></label>

                                    @if ($row->jenis_isian == 2)

                                    <input class="form-control" type="<?php echo $row->type_data ?>"
                                        name="<?php echo $row->nama_alias ?>" placeholder="Masukkan data anda ..."
                                        required>

                                    @else

                                    <select class="form-control" name="<?php echo $row->nama_alias ?>" required>
                                        <option value="">Please Select</option>

                                        @foreach ($kategori_profil_responden->result() as $value)
                                        @if ($value->id_profil_responden == $row->id)

                                        <option value="<?php echo $value->id ?>">
                                            <?php echo $value->nama_kategori_profil_responden ?>
                                        </option>

                                        @endif
                                        @endforeach
                                    </select>

                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach



                        <table class="table table-borderless">
                            <tr>
                                <td class="text-left">
                                    {!! anchor(base_url() . $ci->session->userdata('username') . '/' .
                                    $ci->uri->segment(2)
                                    . '/form-survei/opening', '<i class="fa fa-arrow-left"></i>
                                    Kembali',
                                    ['class' => 'btn btn-secondary btn-lg font-weight-bold shadow']) !!}
                                </td>
                                <td class="text-right">
                                    <a class="btn btn-warning btn-lg font-weight-bold shadow"
                                        href="<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/form-survei/pertanyaan' ?>">Selanjutnya
                                        <i class="fa fa-arrow-right"></i></a>
                                </td>
                            </tr>
                        </table>

                    </td>
                    <td valign="top">

                        <div class="col-sm-1 sticky_button_edit">

                            <div class="btn-group-vertical mr-2" role="group" aria-label="First group"
                                data-aos="fade-up" style="box-shadow: 5px 5px 5px rgba(0, 0, 0, 0.19);">

                                <button type="button" class="btn btn-white" data-toggle="modal"
                                    data-target="#default"><span data-toggle="tooltip" data-placement="right"
                                        title="Tambah Profil dari Template"><i class="fa fa-history"></i></span>
                                </button>

                                <button type="button" class="btn btn-white" data-toggle="modal"
                                    data-target="#custom"><span data-toggle="tooltip" data-placement="right"
                                        title="Custom Profil"><i class="fa fa-plus"></i></span>
                                </button>

                                <a type="button" class="btn btn-white"
                                    href="<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/preview-form-survei/data-responden' ?>"
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


<!-- ======================================= DEFAULT ==================================================== -->
<div class="modal fade" id="default" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border border-warning">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="exampleModalLabel">Ambil dari Template</h5>
            </div>
            <div class="modal-body">

                <form
                    action="<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/profil-responden-survei/add-default' ?>"
                    class="form_default">

                    @if($profil_default->num_rows() > 0)
                    <div class="col">
                        <label>
                            <input class="form-check-input" type="checkbox" disabled checked>Nama
                            Lengkap</label>
                    </div>
                    @endif

                    @foreach ($profil_default->result() as $row)
                    <div class="col">
                        <label>
                            <input class="form-check-input" type="checkbox" value="<?php echo $row->id ?>"
                                name="check_list[]">
                            <?php echo $row->nama_profil_responden ?>
                        </label>
                    </div>
                    @endforeach

                    @if($profil_default->num_rows() > 0)
                    <div class=" text-right mt-3">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-sm tombolSimpanDefault">Simpan</button>
                    </div>
                    @else
                    <div class="text-center text-info">Semua Profil Default sudah digunakan!</div>
                    @endif

                </form>

            </div>
        </div>
    </div>
</div>


<!-- ======================================= CUSTOM ==================================================== -->
<div class="modal fade" id="custom" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border border-warning">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="exampleModalLabel">Custom Profil Responden</h5>
            </div>
            <div class="modal-body">

                <form
                    action="<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/form-survei/add-custom-data-responden' ?>"
                    class="form_custom">

                    <span style="color: red; font-style: italic;"><?php echo validation_errors() ?></span>
                    <br>
                    <div class="form-group row">
                        @php
                        echo form_label('Nama Profil Responden <span style="color:red;">*</span>', '', ['class' =>
                        'col-sm-3 col-form-label
                        font-weight-bold']);
                        @endphp
                        <div class="col-sm-9">
                            @php
                            echo form_input($nama_profil_responden);
                            @endphp
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label font-weight-bold">Jenis Jawaban <span
                                style="color:red;">*</span></label>
                        <div class="col-sm-9">
                            <label>
                                <input type="radio" name="jenis_jawaban" id="default" value="2" class="jawaban"
                                    required>
                                Jawaban Singkat
                            </label>
                            <hr>
                            <label>
                                <input type="radio" name="jenis_jawaban" id="custom" value="1" class="jawaban">
                                Dengan Pilihan Ganda
                            </label>
                        </div>
                    </div>


                    <div name="inputan" id="inputan" style="display:none">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label font-weight-bold">Pilih Type Data <span
                                    style="color:red;">*</span></label>
                            <div class="col-sm-9">
                                <label>
                                    <input type="radio" name="type_data" value="text">
                                    TEXT
                                </label>
                                <hr>
                                <label>
                                    <input type="radio" name="type_data" value="number">
                                    NUMBER
                                </label>
                            </div>
                        </div>
                    </div>


                    <div name="pilgan" id="pilgan" style="display:none">
                        <div class="control-group after-add-more">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label font-weight-bold">Pilihan Jawaban <span
                                        style="color:red;">*</span></label>
                                <div class="col-sm-8">
                                    <input type="text" name="pilihan_jawaban[]" class="form-control"
                                        placeholder="Masukkan Pilihan Jawaban . . .">
                                </div>
                                <div class="input-group-addon col-sm-1">
                                    <button class="btn btn-success add-more" type="button">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- class hide membuat form disembunyikan  -->
                        <!-- hide adalah fungsi bootstrap 3, klo bootstrap 4 pake invisible  -->
                        <div class="copy">
                            <div class="control-group row mb-7">
                                <label class="col-sm-3 col-form-label font-weight-bold">Pilihan Jawaban <span
                                        style="color:red;">*</span></label>
                                <div class="col-sm-8">
                                    <input type="text" name="pilihan_jawaban[]" class="form-control"
                                        placeholder="Masukkan Pilihan Jawaban . . .">
                                </div>
                                <div class="input-group-addon col-sm-1">
                                    <button class="btn btn-danger remove" type="button">
                                        <i class="fas fa-trash"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <br>

                    <div class="text-right">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-sm tombolSimpanCustom">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- ======================================= EDIT ==================================================== -->

@foreach($profil_responden->result() as $value)
<div class="modal fade" id="edit{{$value->id}}" tabindex="-1" role="dialog" aria-labelledby="ModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border border-warning">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="exampleModalLabel">Edit Profil Responden</h5>
            </div>
            <div class="modal-body">

                <form
                    action="{{base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/form-survei/edit-data-responden/' . $value->id}}"
                    class="form_edit" method="POST">

                    <input name="id" value="{{$value->id}}" hidden>
                    <input name="jenis_isian" value="{{$value->jenis_isian}}" hidden>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label font-weight-bold">Nama Profil <span
                                style="color:red;">*</span></label>
                        <div class="col-sm-9">
                            <input class="form-control" name="edit_nama_profil_responden"
                                value="{{$value->nama_profil_responden}}" required>
                        </div>
                    </div>

                    @if ($value->type_data != '')
                    <br>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label font-weight-bold">Type Data <span
                                style="color:red;">*</span></label>
                        <div class="col-sm-9">
                            <label>
                                <input type="radio" name="type_data" value="text" @if ($value->type_data == 'text')
                                checked
                                @endif>TEXT </label>
                            <hr>
                            <label>
                                <input type="radio" name="type_data" value="number" @if ($value->type_data == 'number')
                                checked
                                @endif>NUMBER</label>
                        </div>
                    </div>
                    @else
                    <input type="hidden" name="type_data" value="">
                    <hr>
                    <br>
                    @endif

                    @foreach ($kategori_profil_responden->result() as $row)
                    @if($row->id_profil_responden == $value->id)
                    <input type="hidden" id="id_kategori" name="id_kategori[]" value="<?php echo $row->id; ?>">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label font-weight-bold">Pilihan <span
                                style="color:red;">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="jawaban[]"
                                value="<?php echo $row->nama_kategori_profil_responden; ?>" required>
                        </div>
                    </div>
                    @endif
                    @endforeach


                    <div class=" text-right mt-3">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-sm tombolEdit">Simpan</button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>
@endforeach

@endsection

@section('javascript')
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/34.2.0/classic/ckeditor.js"></script>

<script>
$('.form_default').submit(function(e) {

    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        dataType: 'json',
        data: $(this).serialize(),
        cache: false,
        beforeSend: function() {
            $('.tombolSimpanDefault').attr('disabled', 'disabled');
            $('.tombolSimpanDefault').html(
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
            $('.tombolSimpanDefault').removeAttr('disabled');
            $('.tombolSimpanDefault').html('Simpan');
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<script type="text/javascript">
$(document).ready(function() {

    var maxGroup = 10;

    $(".add-more").click(function() {
        if ($('body').find('.after-add-more').length < maxGroup) {
            var html = '<div class="control-group after-add-more">' + $(".copy").html() +
                '</div>';
            $('body').find('.after-add-more:last').after(html);
        } else {
            alert('Maximum ' + maxGroup + ' groups are allowed.');
        }
    });

    // saat tombol remove dklik control group akan dihapus 
    $("body").on("click", ".remove", function() {
        $(this).parents(".control-group").remove();
    });

});
</script>

<script type="text/javascript">
$(function() {
    $(":radio.jawaban").click(function() {
        $("#inputan").hide()
        if ($(this).val() == "2") {
            $("#inputan").show().prop('required', true);
        } else {
            $("#inputan").removeAttr('required').hide();
        }

        $("#pilgan").hide()
        if ($(this).val() == "1") {
            $("#pilgan").show().prop('required', true);
        } else {
            $("#pilgan").removeAttr('required').hide();
        }
    });
});
</script>

<script>
$('.form_custom').submit(function(e) {
    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        dataType: 'json',
        data: $(this).serialize(),
        cache: false,
        beforeSend: function() {
            $('.tombolSimpanCustom').attr('disabled', 'disabled');
            $('.tombolSimpanCustom').html(
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
            $('.tombolSimpanCustom').removeAttr('disabled');
            $('.tombolSimpanCustom').html('Simpan');
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
            url: "<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/profil-responden-survei/delete/' ?>" +
                id,
            type: "POST",
            dataType: "JSON",
            success: function(data) {
                if (data.status) {
                    toastr["success"]('Data berhasil dihapus');
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
            }
        });
    }
}
</script>

<script>
$('.form_edit').submit(function(e) {

    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        dataType: 'json',
        data: $(this).serialize(),
        cache: false,
        beforeSend: function() {
            $('.tombolEdit').attr('disabled', 'disabled');
            $('.tombolEdit').html(
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
            $('.tombolEdit').removeAttr('disabled');
            $('.tombolEdit').html('Simpan');
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

@endsection