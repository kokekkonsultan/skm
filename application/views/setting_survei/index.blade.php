@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')

@endsection

@section('content')

<div class="container-fluid">

    @include("include_backend/partials_no_aside/_inc_menu_repository")
    <div class="row mt-5">
        <div class="col-md-3">
            @include('manage_survey/menu_data_survey')
        </div>
        <div class="col-md-9">
            <div class="row justify-content-md-center">
                <div class="col col-lg-12" data-aos="fade-down">
                    @include('setting_survei/menu_settings')
                    <div class="card border-primary mt-5">
                        <div class="card-body">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                                        aria-controls="home" aria-selected="true">Atur periode</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                                        aria-controls="profile" aria-selected="false">Tunda periode</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="tampilan-tab" data-toggle="tab" href="#tampilan" role="tab"
                                        aria-controls="tampilan" aria-selected="false">Lihat tampilan</a>
                                </li>
                            </ul>

                            <br>

                            <!-- TANGGAL SURVEY -->
                            <div class=" tab-content" id="myTabContent">

                                <div class="tab-pane fade show active" id="home" role="tabpanel"
                                    aria-labelledby="home-tab">

                                    <form
                                        action="<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/setting-survei/periode' ?>"
                                        class="form_periode">

                                        {{-- <div class="alert alert-custom alert-notice alert-light-primary fade show"
                                            role="alert">
                                            <div class="alert-icon"><i class="flaticon-warning"></i></div>
                                            <div class="alert-text">Atur periode digunakan untuk mengatur tanggal survei
                                                dibuka dan tanggal survei ditutup. Jika tanggal survei dikosongkan, maka
                                                survei akan ditutup.</div>
                                            <div class="alert-close">
                                                <button type="button" class="close" data-dismiss="alert"
                                                    aria-label="Close">
                                                    <span aria-hidden="true"><i class="ki ki-close"></i></span>
                                                </button>
                                            </div>
                                        </div> --}}

										<div class="alert alert-secondary" role="alert">
											<i class="flaticon-exclamation-1"></i> Atur periode digunakan untuk mengatur tanggal survei dibuka dan tanggal survei ditutup.
										</div>

                                        </br>

                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label font-weight-bold">Tanggal Survei
                                                Dibuka</label>
                                            <div class="col-sm-8">
                                                @php
                                                echo form_input($survey_start);
                                                @endphp
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label font-weight-bold">Tanggal Survei
                                                Ditutup</label>
                                            <div class="col-sm-8">
                                                @php
                                                echo form_input($survey_end);
                                                @endphp
                                            </div>
                                        </div>

                                        {{-- <div class="form-check">
                                            <label><input class="form-check-input" type="checkbox" value="1"
                                                    name="hapus_periode">
                                                <div class="form-check-label font-weight-bold" style="color: red;">
                                                    Hapus Periode
                                                </div>
                                            </label>
                                        </div>
                                        <span class="font-italic">(Jika di centang maka periode akan di
                                            kosongkan)</span> --}}

                                        <br>

                                        <div class="text-right">
                                            <button type="submit"
                                                class="btn btn-primary font-weight-bold tombolSimpanPeriode"
                                                <?php echo $manage_survey->is_survey_close == 1 ? 'disabled' : '' ?>>Update</button>
                                        </div>
                                    </form>

                                </div>

                                <!-- TUNDA SURVEY -->
                                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">

									<div class="alert alert-secondary" role="alert">
										<i class="flaticon-exclamation-1"></i> Tunda periode digunakan jika anda ingin menunda survei sementara waktu.
									</div>

                                    <form
                                        action="<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/setting-survei/tunda' ?>"
                                        class="form_tunda">

                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label font-weight-bold">Tunda
                                                Periode</label>
                                            <div class="col-sm-8">
                                                <select class="form-control" id="is_privacy" name="is_privacy"
                                                    value="<?php echo set_value('is_privacy'); ?>">
                                                    <option value="1" <?php if ($manage_survey->is_privacy == "1") {
                                                                            echo "selected";
                                                                        } ?>>Tidak</option>
                                                    <option value="2" <?php if ($manage_survey->is_privacy == "2") {
                                                                            echo "selected";
                                                                        } ?>>Ya</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label font-weight-bold">Deskripsi Penundaan</label>
											<div class="col-sm-8">
												<div class="alert alert-secondary" role="alert">
													<i class="flaticon-exclamation-1"></i> Anda bisa mengisi bidang dibawah ini untuk memberikan informasi terkait kenapa Anda melakukan penundaan survei
												</div>
                                            @php
                                            echo form_textarea($deskripsi_tunda);
                                            @endphp
											</div>
                                        </div>

                                        </br>

                                        <div class="text-right">
                                            <button type="submit"
                                                class="btn btn-primary font-weight-bold tombolSimpanTunda"
                                                <?php echo $manage_survey->is_survey_close == 1 ? 'disabled' : '' ?>>Update</button>
                                        </div>

                                    </form>
                                </div>




                                <!-- TAMPILAN -->
                                <div class="tab-pane fade" id="tampilan" role="tabpanel" aria-labelledby="tampilan-tab">

                                    <div class="text-left">

                                        <?php
                                        echo anchor(base_url() . 'survey/' . $ci->uri->segment(2) . '/survey-hold', '<i class="fas fa-solid fa-eye"></i> Lihat Tampilan Survei di Tunda', ['class' => 'btn btn-light-primary font-weight-bold', 'target' => '_blank']);
                                        ?>

                                        <br> <br>

                                        <?php
                                        echo anchor(base_url() . 'survey/' . $ci->uri->segment(2) . '/unopened', '<i class="fas fa-solid fa-eye"></i> Lihat Tampilan Survei Belum Dibuka', ['class' => 'btn btn-light-primary font-weight-bold', 'target' => '_blank']);
                                        ?>

                                        <br> <br>

                                        <?php
                                        echo anchor(base_url() . 'survey/' . $ci->uri->segment(2) . '/survey-end', '<i class="fas fa-solid fa-eye"></i> Lihat Tampilan Survei Sudah Berakhir', ['class' => 'btn btn-light-primary font-weight-bold', 'target' => '_blank']);
                                        ?>

                                        <br> <br>

                                        <?php
                                        echo anchor(base_url() . 'survey/' . $ci->uri->segment(2) . '/survey-not-question', '<i class="fas fa-solid fa-eye"></i> Lihat Tampilan Pertanyaan Belum Lengkap', ['class' => 'btn btn-light-primary font-weight-bold', 'target' => '_blank']);
                                        ?>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection

@section('javascript')
<script>
$('.form_periode').submit(function(e) {

    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        dataType: 'json',
        data: $(this).serialize(),
        cache: false,
        beforeSend: function() {
            $('.tombolSimpanPeriode').attr('disabled', 'disabled');
            $('.tombolSimpanPeriode').html('<i class="fa fa-spin fa-spinner"></i> Sedang diproses');

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
            $('.tombolSimpanPeriode').removeAttr('disabled');
            $('.tombolSimpanPeriode').html('Update');
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
                }, 2500);
            }
        }
    })
    return false;
});


$('.form_tunda').submit(function(e) {

    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        dataType: 'json',
        data: $(this).serialize(),
        cache: false,
        beforeSend: function() {
            $('.tombolSimpanTunda').attr('disabled', 'disabled');
            $('.tombolSimpanTunda').html('<i class="fa fa-spin fa-spinner"></i> Sedang diproses');

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
            $('.tombolSimpanTunda').removeAttr('disabled');
            $('.tombolSimpanTunda').html('Update');
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
                }, 2500);
            }
        }
    })
    return false;

});
</script>
@endsection
