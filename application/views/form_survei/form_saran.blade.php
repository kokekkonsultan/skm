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
            <li class="active" id="payment"><strong>Saran</strong></li>
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
                        <div class="card shadow mb-4" data-aos="fade-up" style="border-left: 5px solid #FFA800;">

                            @if($manage_survey->img_benner == '')
                            <img class="card-img-top" src="{{ base_url() }}assets/img/site/page/banner-survey.jpg"
                                alt="new image" />
                            @else
                            <img class="card-img-top shadow"
                                src="{{ base_url() }}assets/klien/benner_survei/{{$manage_survey->img_benner}}"
                                alt="new image">
                            @endif

                            <div class="card-header text-center">
                                <h4><b>SARAN</b> - @include('include_backend/partials_backend/_tanggal_survei')</h4>
                            </div>
                            <div class="card-body">

                                <div>
                                    <label
                                        style="font-size: 14px; text-transform: capitalize;"><?php echo $manage_survey->judul_form_saran ?></label>
                                    <br />
                                    {!! form_textarea($saran) !!}
                                </div>

                            </div>
                            <div class="card-footer">
                                <table class="table table-borderless">
                                    <tr>
                                        <td class="text-left">
                                            {!! anchor($url_back, '<i class="fa fa-arrow-left"></i>
                                            Kembali',
                                            ['class' => 'btn btn-secondary btn-lg font-weight-bold shadow']) !!}
                                        </td>
                                        <td class="text-right">
                                            <a class="btn btn-warning btn-lg font-weight-bold shadow"
                                                href="<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/form-survei/konfirmasi' ?>">Selanjutnya
                                                <i class="fa fa-arrow-right"></i></a>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </td>

                    <td valign="top">
                        <div class="col-sm-1 sticky_button_edit">
                            <div class="btn-group-vertical mr-2" role="group" aria-label="First group"
                                data-aos="fade-up" style="box-shadow: 5px 5px 5px rgba(0, 0, 0, 0.19);">

                                <button type="button" class="btn btn-white" data-toggle="modal"
                                    data-target="#deskripsi"><span data-toggle="tooltip" data-placement="right"
                                        title="Ubah Deskripsi"><i class="fa fa-edit"></i></span>
                                </button>

                                <a type="button" class="btn btn-white"
                                    href="<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/preview-form-survei/saran' ?>"
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


<div class="modal fade" id="deskripsi" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border border-warning">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="exampleModalLabel">Edit Deskripsi</h5>
            </div>
            <div class="modal-body">
                <form
                    action="<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/form-survei/update-saran' ?>"
                    class="form_pembuka">

                    <input name="is_saran" value="<?php echo $manage_survey->is_saran ?>" hidden>

                    <div class="form-group row mb-5 mt-5">
                        <label class="col-sm-3 col-form-label
                        font-weight-bold">Judul Form Saran <span style="color: red;">*</span></label>
                        <div class="col-sm-9">
                            <textarea class="form-control" type="text" name="judul_form_saran" rows="2" required
                                autofocus><?php echo $manage_survey->judul_form_saran ?></textarea>
                        </div>
                    </div>

                    <div class="text-right mt-5">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                        <button type="submit"
                            class="btn btn-primary btn-sm font-weight-bold tombolSimpanPembuka">Update</button>
                    </div>
                </form>
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
            $('.tombolSimpanPembuka').html('Update');
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