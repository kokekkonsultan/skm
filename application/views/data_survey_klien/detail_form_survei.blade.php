@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')
<link href="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.css" rel="stylesheet"
    type="text/css">

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

    <div class="row mt-5">
        <div class="col-md-3">
            @include('data_survey_klien/menu_data_survey_klien')
        </div>
        <div class="col-md-9">

            <div class="card card-custom card-sticky" data-aos="fade-down">
                <div class="card-header">
                    <div class="card-title">
                        Form Survei
                    </div>
                    <div class="card-toolbar">
                        <a class="btn btn-primary font-weight-bold" target="_blank"
                            href="<?php echo base_url() . $data_user->username . '/' . $manage_survey->slug . '/preview-form-survei/opening' ?>"><i
                                class="fas fa-solid fa-eye"></i> Lihat Tampilan Form Survei</a>
                    </div>
                </div>

                <div class="card-body">

                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                                aria-controls="home" aria-selected="true">TAMPILAN LOGO SURVEI</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                                aria-controls="profile" aria-selected="false">DESKRIPSI PEMBUKA SURVEI</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="saran-tab" data-toggle="tab" href="#saran" role="tab"
                                aria-controls="saran" aria-selected="false">TAMPILAN BENNER FORM SURVEI</a>
                        </li>
                    </ul>

                    <br>


                    <!-- LOGO -->
                    <div class=" tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">

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
                            <div class="form-group">
                                <textarea name="deskripsi" id="editor" value="" class="form-control" readonly>
                                    <?php echo $manage_survey->deskripsi_opening_survey ?></textarea>
                            </div>
                        </div>


                        <!-- HEADER SURVEI -->
                        <div class="tab-pane fade" id="saran" role="tabpanel" aria-labelledby="saran-tab">

                            @if($manage_survey->img_benner == '')
                            <img class="card-img-top" src="{{ base_url() }}assets/img/site/page/banner-survey.jpg"
                                alt="new image" />
                            @else
                            <img class="card-img-top shadow"
                                src="{{ base_url() }}assets/klien/benner_survei/{{$manage_survey->img_benner}}"
                                alt="new image">
                            @endif

                        </div>
                    </div>
                </div>
            </div>


            <!-- ==================================== SARAN ========================================== -->
            <div class="card card-custom card-sticky mt-5" data-aos="fade-down">
                <div class="card-header">
                    <div class="card-title">
                        Form Saran
                    </div>
                </div>
                <div class="card-body">
                    <div class="alert alert-custom alert-notice alert-light-primary fade show mb-7" role="alert">
                        <div class="alert-icon"><i class="flaticon-warning"></i></div>
                        <div class="alert-text">Jika diaktifkan, maka akan ditampilkan form saran
                            survey.</div>
                        <div class="alert-close">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true"><i class="ki ki-close"></i></span>
                            </button>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label font-weight-bold">Aktifkan Form
                            Saran</label>
                        <div class="col-sm-9">
                            <select class="form-control" name="is_saran" value="<?php echo set_value('is_saran'); ?>"
                                disabled>
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
                            <textarea class="form-control" name="judul_form_saran" value="" rows="3"
                                disabled><?php echo $manage_survey->judul_form_saran ?></textarea>
                        </div>
                    </div>

                </div>
            </div>


            <!-- ==================================== PERTANYAAN SURVEI ========================================== -->
            <div class="card border-primary mt-5" data-aos="fade-down">
                <div class="card-body">

                    <div class="alert alert-custom alert-notice alert-light-primary fade show mb-10" role="alert">
                        <div class="alert-icon"><i class="flaticon-warning"></i></div>
                        <div class="alert-text"> <span>Atribut Pertanyaan Survei digunakan untuk mengatur jenis
                                pertanyaan apa saja yang digunakan dalam survei. Mengubah Jenis Pertanyaan juga
                                akan menghapus semua data perolehan survei yang sudah masuk!</span>
                        </div>
                        <div class="alert-close">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true"><i class="ki ki-close"></i></span>
                            </button>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="recipient-name" class="col-sm-4 col-form-label font-weight-bold">Atribut
                            Pertanyaan Survei<span style="color:red;">*</span></label>

                        <div class="col-sm-8">

                            <input type="hidden" name="atribut_pertanyaan[]" value="0">
                            <label class="font-weight-bold"><input type="checkbox" checked disabled>
                                Pertanyaan Unsur</label><br>

                            <label><input type="checkbox" name="atribut_pertanyaan[]" value="1" @if (in_array(1,
                                    $atribut_pertanyaan_survey)) checked @endif disabled>
                                Pertanyaan Harapan</label><br>

                            <label><input type="checkbox" name="atribut_pertanyaan[]" value="2" @if (in_array(2,
                                    $atribut_pertanyaan_survey)) checked @endif disabled>
                                Pertanyaan Tambahan</label><br>

                            <label><input type="checkbox" name="atribut_pertanyaan[]" value="3" @if (in_array(3,
                                    $atribut_pertanyaan_survey)) checked @endif disabled>
                                Pertanyaan Kualitatif</label>
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
            $('.tombolSimpanPembuka').html(
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
            $('.tombolSimpanHeader').html(
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
            $('.tombolSimpanSaran').html(
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

<script type="text/javascript">
$('#uploadForm').submit(function(e) {
    e.preventDefault();

    var reader = new FileReader();
    reader.readAsDataURL(document.getElementById('profil').files[0]);

    var formdata = new FormData();
    formdata.append('file', document.getElementById('profil').files[0]);
    $.ajax({
        method: 'POST',
        contentType: false,
        cache: false,
        processData: false,
        data: formdata,
        dataType: 'json',
        url: "<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/form-survei/do-uploud' ?>",
        beforeSend: function() {
            $('.tombolUploud').attr('disabled', 'disabled');
            $('.tombolUploud').html('<i class="fa fa-spin fa-spinner"></i> Sedang diproses');

        },
        complete: function() {
            $('.tombolUploud').removeAttr('disabled');
            $('.tombolUploud').html('Uploud');
        },
        error: function(e) {
            Swal.fire(
                'Error !',
                e,
                'error'
            )
        },

        success: function(data) {
            if (data.error) {
                toastr["danger"]('Data gagal disimpan');
            } else {
                $('#uploadForm')[0].reset();
                toastr["success"]('Data berhasil disimpan');
                window.setTimeout(function() {
                    location.reload()
                }, 1000);
            }

        }
    });
});
</script>

<script>
$('.form_atribut_pertanyaan').submit(function(e) {

    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        dataType: 'json',
        data: $(this).serialize(),
        cache: false,
        beforeSend: function() {
            $('.tombolSimpanJenisPertanyaan').attr('disabled', 'disabled');
            $('.tombolSimpanJenisPertanyaan').html(
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
            $('.tombolSimpanJenisPertanyaan').removeAttr('disabled');
            $('.tombolSimpanJenisPertanyaan').html('Update Jenis Pertanyaan');
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
                }, 1000);
            }
        }
    })
    return false;

});
</script>
@endsection