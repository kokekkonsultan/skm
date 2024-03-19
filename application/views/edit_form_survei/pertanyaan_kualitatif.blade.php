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
        <div class="col-md-8 offset-md-2">
            <div class="card shadow" data-aos="fade-up" id="kt_blockui_content">

                @if($manage_survey->img_benner == '')
                <img class="card-img-top" src="{{ base_url() }}assets/img/site/page/banner-survey.jpg"
                    alt="new image" />
                @else
                <img class="card-img-top shadow"
                    src="{{ base_url() }}assets/klien/benner_survei/{{$manage_survey->img_benner}}" alt="new image">
                @endif

                <div class="card-header text-center">
                    <h4><b>PERTANYAAN KUALITATIF</b> - @include('include_backend/partials_backend/_tanggal_survei')</h4>
                </div>
                <div class="card-body">



                    <form>

                        </br>

                        @php
                        $no = 1;
                        @endphp

                        @foreach ($kualitatif as $row)


                        <table class="table table-borderless" border="0">
                            <tr style="font-size: 14px; text-transform: capitalize;">
                                <td width="4%" valign="top">{{ $no++ }}.</td>
                                <td><?php echo $row->isi_pertanyaan ?></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                                    <textarea type="text" rows="7" class="form-control" id="isi_jawaban_kualitatif"
                                        name="isi_jawaban_kualitatif[]" value=""
                                        placeholder="Masukkan jawaban pertanyaan kualitatif pada bidang ini.."
                                        autofocus></textarea>
                                </td>
                            </tr>
                        </table>

                        <br>

                        @endforeach



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
                                    href="<?php echo $url_next ?>">Selanjutnya<i class="fa fa-arrow-right"></i></a>
                            </td>
                        </tr>
                    </table>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@section('javascript')
<script>
$('.form_survei').submit(function(e) {

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
            }, 1000);

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
                    window.location.href = data.url_next;
                }, 500);
            }
        }
    })
    return false;
});
</script>
@endsection