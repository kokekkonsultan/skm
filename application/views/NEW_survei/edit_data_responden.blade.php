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
            <li id="personal"><strong>Pertanyaan Survei</strong></li>
            @if ($status_saran == 1)
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


                @if ($manage_survey->img_benner == '')
                <img class="card-img-top" src="{{ base_url() }}assets/img/site/page/banner-survey.jpg"
                    alt="new image" />
                @else
                <img class="card-img-top shadow"
                    src="{{ base_url() }}assets/klien/benner_survei/{{ $manage_survey->img_benner }}" alt="new image">
                @endif


                <div class="card-header text-center">
                    <h4><b>DATA RESPONDEN</b> - @include('include_backend/partials_backend/_tanggal_survei')</h4>
                </div>



                <form
                    action="<?php echo base_url() . 'survei/' . $ci->uri->segment(2) . '/data-responden/' . $ci->uri->segment(4) . '/update'; ?>"
                    class="form_responden" method="POST">


                    <div class="card-body">
                        <span style="color: red; font-style: italic;">{!! validation_errors() !!}</span>
                        <div class="form-group">
                            <label class="font-weight-bold">Nama Lengkap <span class="text-danger">*</span></label>
                            {!! form_input($nama_lengkap) !!}
                        </div>

                        </br>


                        @foreach ($profil_responden->result() as $row)
                        @php
                        $nama_alias = $row->nama_alias;
                        @endphp

                        <div class="form-group">
                            <label class="font-weight-bold"><?php echo $row->nama_profil_responden; ?> <span
                                    class="text-danger">*</span></label>

                            @if ($row->jenis_isian == 2)
                            <input class="form-control" type="<?php echo $row->type_data; ?>"
                                name="<?php echo $row->nama_alias; ?>" placeholder="Masukkan data anda ..."
                                value="<?php echo $responden->$nama_alias; ?>" required>
                            @else
                            <select class="form-control" name="<?php echo $row->nama_alias; ?>" required>
                                <option value="">Please Select</option>

                                @foreach ($kategori_profil_responden->result() as $value)
                                @if ($value->id_profil_responden == $row->id)
                                <option value="<?php echo $value->id; ?>"
                                    <?php echo $responden->$nama_alias == $value->id ? 'selected' : ''; ?>>
                                    <?php echo $value->nama_kategori_profil_responden; ?></option>
                                @endif
                                @endforeach

                            </select>
                            @endif

                        </div>
                        </br>
                        @endforeach
                    </div>
                    <div class="card-footer">
                        <table class="table table-borderless">
                            <tr>
                                <td class="text-right">
                                    <button type="submit"
                                        class="btn btn-warning btn-lg font-weight-bold shadow tombolSave"
                                        onclick="preventBack()">Selanjutnya <i class="fa fa-arrow-right"></i></button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </form>
            </div>


            <br><br>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script>
$('.form_responden').submit(function(e) {

    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        dataType: 'json',
        data: $(this).serialize(),
        cache: false,
        beforeSend: function() {
            $('.tombolCancel').attr('disabled', 'disabled');
            $('.tombolSave').attr('disabled', 'disabled');
            $('.tombolSave').html('<i class="fa fa-spin fa-spinner"></i> Sedang diproses');

            KTApp.block('#kt_blockui_content', {
                overlayColor: '#FFA800',
                state: 'primary',
                message: 'Processing...'
            });

            setTimeout(function() {
                KTApp.unblock('#kt_blockui_content');
            }, 500);

        },
        complete: function() {
            $('.tombolCancel').removeAttr('disabled');
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
                    window.location.href =
                        "<?php echo base_url() . 'survei/' . $ci->uri->segment(2) . '/pertanyaan/' . $ci->uri->segment(4) . '/edit'; ?>";
                }, 500);
            }
        }
    })
    return false;
});
</script>
@endsection