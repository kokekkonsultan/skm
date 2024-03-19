@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')
<style>
.outer-box {
    font-family: arial;
    font-size: 24px;
    width: 580px;
    height: 114px;
    padding: 2px;
}

.box-edge-logo {
    font-family: arial;
    font-size: 14px;
    width: 110px;
    height: 110px;
    padding: 8px;
    float: left;
    text-align: center;
}

.box-edge-text {
    font-family: arial;
    font-size: 14px;
    width: 466px;
    height: 110px;
    padding: 8px;
    float: left;
}

.box-title {
    font-size: 18px;
    font-weight: bold;
}

.box-desc {
    font-size: 12px;
}
</style>
@endsection

@section('content')

<div class="container-fluid">

    @include("include_backend/partials_no_aside/_inc_menu_repository")
    @include('include_backend/partials_backend/_message')
    <div class="row mt-5">
        <div class="col-md-3">
            @include('manage_survey/menu_data_survey')
        </div>
        <div class="col-md-9">
            <div class="row justify-content-md-center">
                <div class="col col-lg-12" data-aos="fade-down">
                    @include('setting_survei/menu_settings')<br>
                    <div class="card border-primary mb-5">
                        <div class="card-body">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                                        aria-controls="home" aria-selected="true">TAMPILAN LOGO SURVEI</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                                        aria-controls="profile" aria-selected="false">TAMPILAN PEMBUKA SURVEI</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="saran-tab" data-toggle="tab" href="#saran" role="tab"
                                        aria-controls="saran" aria-selected="false">FORM SARAN</a>
                                </li>
                            </ul>

                            <br>


                            <!-- LOGO -->
                            <div class=" tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="home" role="tabpanel"
                                    aria-labelledby="home-tab">

                                    <div class="text-right mb-5">
                                        <button class=" btn btn-primary btn-sm" type="button" data-toggle="collapse"
                                            data-target="#collapseExample" aria-expanded="false"
                                            aria-controls="collapseExample">
                                            <i class="fa fa-edit"></i> Edit Header Survei
                                        </button>
                                    </div>

                                    <div class="collapse" id="collapseExample">
                                        <div class="card shadow">

                                            <div class="card-body">
                                                <form
                                                    action="<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/setting-survei/update-header' ?>"
                                                    class="form_header">

                                                    <div class="form-group">
                                                        <textarea name="title" id="editor1" value=""
                                                            class="form-control"
                                                            required> <?php echo $manage_survey->title_header_survey ?></textarea>
                                                    </div>

                                                    <div class="text-right">
                                                        <button class="btn btn-secondary btn-sm" type="button"
                                                            data-toggle="collapse" data-target="#collapseExample"
                                                            aria-expanded="false" aria-controls="collapseExample">
                                                            Close
                                                        </button>
                                                        <button type="submit"
                                                            class="btn btn-light-primary btn-sm font-weight-bold tombolSimpanHeader">Update</button>
                                                    </div>
                                                </form>

                                            </div>
                                        </div>
                                        <br>
                                    </div>

                                    <nav class="navbar navbar-light bg-white shadow">
                                        <div class="outer-box">
                                            <div class="box-edge-logo">
                                                <?php if ($data_user->foto_profile == NULL) { ?>
                                                <img src="<?php echo base_url(); ?>assets/klien/foto_profile/200px.jpg"
                                                    width="100%" class="" alt="">
                                                <?php } else { ?>
                                                <img src="<?php echo base_url(); ?>assets/klien/foto_profile/<?php echo $data_user->foto_profile ?>"
                                                    width="100%" class="" alt="">
                                                <?php  } ?>

                                            </div>
                                            <div class="box-edge-text">
                                                <div class="box-title">
                                                    SURVEI KEPUASAN MASYARAKAT
                                                </div>
                                                <div class="box-desc">
                                                    <?php echo $manage_survey->title_header_survey ?>
                                                </div>
                                            </div>
                                        </div>
                                    </nav>

                                </div>

                                <!-- DESKRIPSI -->
                                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">

                                    <form
                                        action="<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/setting-survei/update-display' ?>"
                                        class="form_pembuka">

                                        <div class="form-group">
                                            <textarea name="deskripsi" id="editor" value="" class="form-control"
                                                required> <?php echo $manage_survey->deskripsi_opening_survey ?></textarea>
                                        </div>

                                        <div class="text-right">
                                            <button type="submit"
                                                class="btn btn-primary font-weight-bold tombolSimpanPembuka">Update
                                                Deskripsi</button>
                                        </div>
                                    </form>
                                </div>

                                <!-- DESKRIPSI -->
                                <div class="tab-pane fade" id="saran" role="tabpanel" aria-labelledby="saran-tab">

                                    <div class="alert alert-custom alert-notice alert-light-primary fade show"
                                        role="alert">
                                        <div class="alert-icon"><i class="flaticon-warning"></i></div>
                                        <div class="alert-text">Jika diaktifkan, maka akan ditampilkan form saran
                                            survey.</div>
                                        <div class="alert-close">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true"><i class="ki ki-close"></i></span>
                                            </button>
                                        </div>
                                    </div>

                                    <br>

                                    <form
                                        action="<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/setting-survei/update-saran' ?>"
                                        class="form_saran">

                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label font-weight-bold">Aktifkan Form
                                                Saran</label>
                                            <div class="col-sm-9">
                                                <select class="form-control" name="is_saran"
                                                    value="<?php echo set_value('is_saran'); ?>" autofocus>
                                                    <option value="1" <?php if ($manage_survey->is_saran == "1") {
                                                                            echo "selected";
                                                                        } ?>>Ya</option>
                                                    <option value="2" <?php if ($manage_survey->is_saran == "2") {
                                                                            echo "selected";
                                                                        } ?>>Tidak</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label font-weight-bold">Judul Form
                                                Saran</label>
                                            <div class="col-sm-9">
                                                <textarea class="form-control" name="judul_form_saran" value=""
                                                    rows="3"><?php echo $manage_survey->judul_form_saran ?></textarea>
                                            </div>
                                        </div>

                                        <div class="text-right">
                                            <button type="submit"
                                                class="btn btn-primary font-weight-bold tombolSimpanSaran">Update Form
                                                Saran</button>
                                        </div>
                                    </form>

                                </div>



                            </div>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


</div>
@endsection

@section('javascript')

<script src="https://cdn.ckeditor.com/ckeditor5/34.2.0/classic/ckeditor.js"></script>

<script>
ClassicEditor
    .create(document.querySelector('#editor'))
    .then(editor => {
        console.log(editor);
    })
    .catch(error => {
        console.error(error);
    });
</script>

<script>
ClassicEditor
    .create(document.querySelector('#editor1'))
    .then(editor => {
        console.log(editor1);
    })
    .catch(error => {
        console.error(error);
    });
</script>

<script>
$('.form_pembuka').submit(function(e) {

    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        dataType: 'json',
        data: $(this).serialize(),
        cache: false,
        beforeSend: function() {
            $('.tombolSimpanPembuka').attr('disabled', 'disabled');
            $('.tombolSimpanPembuka').html('<i class="fa fa-spin fa-spinner"></i> Sedang diproses');

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
            $('.tombolSimpanPembuka').removeAttr('disabled');
            $('.tombolSimpanPembuka').html('Update Deskripsi');
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
            }
        }
    })
    return false;
});

$('.form_header').submit(function(e) {

    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        dataType: 'json',
        data: $(this).serialize(),
        cache: false,
        beforeSend: function() {
            $('.tombolSimpanHeader').attr('disabled', 'disabled');
            $('.tombolSimpanHeader').html('<i class="fa fa-spin fa-spinner"></i> Sedang diproses');

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
            $('.tombolSimpanHeader').removeAttr('disabled');
            $('.tombolSimpanHeader').html('Update');
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

$('.form_saran').submit(function(e) {
    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        dataType: 'json',
        data: $(this).serialize(),
        cache: false,
        beforeSend: function() {
            $('.tombolSimpanSaran').attr('disabled', 'disabled');
            $('.tombolSimpanSaran').html('<i class="fa fa-spin fa-spinner"></i> Sedang diproses');

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
            $('.tombolSimpanSaran').removeAttr('disabled');
            $('.tombolSimpanSaran').html('Update Form Saran');
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
            }
        }
    })
    return false;
});
</script>

@endsection