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
            <table>
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
                                <h4><b>PERTANYAAN UNSUR</b> -
                                    @include('include_backend/partials_backend/_tanggal_survei')</h4>
                            </div>
                        </div>

                        <div class="card mb-4 mt-4" data-aos="fade-up"
                            style="border-left: 5px solid #FFA800; font-size: 16px; font-family:Arial, Helvetica, sans-serif;">


                            <div class="card-body">

                                @foreach ($pertanyaan_harapan->result() as $row)
                                <table class="table table-borderless mt-3 mb-3" width="100%" border="0">

                                    <tr>
                                        <td width="4%" valign="top">H{{ $row->nomor_harapan }}.</td>
                                        <td><?php echo $row->isi_pertanyaan_unsur ?></td>
                                    </tr>

                                    <tr>
                                        <td></td>
                                        <td style="font-weight: bold;">

                                            <div class="radio-inline mb-2">
                                                <label class="radio radio-outline radio-success radio-lg"
                                                    style="font-size:16px">
                                                    <input type="radio" name="jawaban_pertanyaan_harapan[]" value=""
                                                        required><span></span>
                                                    {{ $row->pilihan_1 }}
                                                </label>
                                            </div>

                                            <div class="radio-inline mb-2">
                                                <label class="radio radio-outline radio-success radio-lg"
                                                    style="font-size:16px">
                                                    <input type="radio" name="jawaban_pertanyaan_harapan[]" value=""
                                                        required><span></span>
                                                    {{ $row->pilihan_2 }}
                                                </label>
                                            </div>

                                            <div class="radio-inline mb-2">
                                                <label class="radio radio-outline radio-success radio-lg"
                                                    style="font-size:16px">
                                                    <input type="radio" name="jawaban_pertanyaan_harapan[]" value=""
                                                        required><span></span>
                                                    {{ $row->pilihan_3 }}
                                                </label>
                                            </div>

                                            <div class="radio-inline mb-2">
                                                <label class="radio radio-outline radio-success radio-lg"
                                                    style="font-size:16px">
                                                    <input type="radio" name="jawaban_pertanyaan_harapan[]" value=""
                                                        required><span></span>
                                                    {{ $row->pilihan_4 }}
                                                </label>
                                            </div>


                                        </td>
                                    </tr>
                                </table>
                                <hr>
                                <hr>
                                <br>
                                @endforeach

                            </div>
                        </div>


                        <table class="table table-borderless">
                            <tr>
                                <td class="text-left">
                                    {!! anchor(base_url() . $ci->session->userdata('username') . '/' .
                                    $ci->uri->segment(2)
                                    . '/form-survei/pertanyaan', '<i class="fa fa-arrow-left"></i>
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

                                <button type="button" class="btn btn-white" data-toggle="modal"
                                    data-target="#exampleModal"><span data-toggle="tooltip" data-placement="right"
                                        title="Edit Jawaban Pertanyaan Harapan"><i class="fa fa-edit"></i></span>
                                </button>

                                <a type="button" class="btn btn-white"
                                    href="<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/preview-form-survei/pertanyaan-harapan' ?>"
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



<!-- ======================================= EDIT PERTANYAAN HARAPAN ========================================== -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border border-warning">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="exampleModalLongTitle">Edit Pilihan Jawaban</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form
                    action="{{base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/form-survei/edit-pertanyaan-harapan'}}"
                    class="form_default" method="POST">

                    @php
                    echo validation_errors();
                    @endphp

                    <datalist id="data_jawaban">
                        <?php
                        foreach ($pilihan_jawaban->result() as $d) {
                            echo "<option value='$d->id'>$d->pilihan_1</option>";
                        }
                        ?>
                    </datalist>

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label font-weight-bold">Pilihan Jawaban
                            1</label>
                        <div class="col-sm-8">
                            <input class="form-control" list="data_jawaban" type="text" name="pilihan_1" id="id"
                                placeholder="Masukkan Pilihan Jawaban ..." onchange="return autofill();" autofocus
                                autocomplete='off' required="required">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label font-weight-bold">Pilihan Jawaban
                            2</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="pilihan_2" id="pilihan_2" required="required">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label font-weight-bold">Pilihan Jawaban
                            3</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="pilihan_3" id="pilihan_3" required="required">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label font-weight-bold">Pilihan Jawaban
                            4</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="pilihan_4" id="pilihan_4" required="required">
                        </div>
                    </div>

                    <br>

                    <div class="text-right">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-sm tombolDefault">Ubah Pilihan Jawaban</button>
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
<script src="https://cdn.ckeditor.com/ckeditor5/34.2.0/classic/ckeditor.js"></script>

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
            $('.tombolDefault').html('Ubah Pilihan Jawaban');
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