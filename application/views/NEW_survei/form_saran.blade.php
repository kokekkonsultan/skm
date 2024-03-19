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
        <div class="col-md-8 offset-md-2" style="font-size: 16px; font-family:arial, helvetica, sans-serif;">
            <div class="card shadow mb-4 mt-4" id="kt_blockui_content" data-aos="fade-up"
                style="border-left: 5px solid #FFA800;">

                @if($judul->img_benner == '')
                <img class="card-img-top" src="{{ base_url() }}assets/img/site/page/banner-survey.jpg"
                    alt="new image" />
                @else
                <img class="card-img-top shadow"
                    src="{{ base_url() }}assets/klien/benner_survei/{{$manage_survey->img_benner}}" alt="new image">
                @endif
                <div class="card-header text-center">
                    <h4><b>SARAN</b> - @include('include_backend/partials_backend/_tanggal_survei')</h4>
                </div>
                <div class="card-body">

                    <form
                        action="<?php echo base_url() . 'survei/' . $ci->uri->segment(2) . '/add-saran/' . $ci->uri->segment(4) ?>"
                        class="form_survei" method="POST">

                        {!! validation_errors() !!}

                        <div>
                            <label
                                style="font-size: 14px; text-transform: capitalize;"><?php echo $manage_survey->judul_form_saran ?></label>
                            <br />
                            {!! form_textarea($saran) !!}

                            <small class="text-danger">** Pengisian form saran hanya dapat menggunakan tanda baca (.)
                                titik dan (,) koma</small>
                        </div>



                </div>
                <div class="card-footer">
                    <table class="table table-borderless">
                        <tr>
                            <td class="text-left">
                                <?php
                                if ($ci->uri->segment(5) == 'edit') {
                                    $is_edit = '/edit';
                                } else {
                                    $is_edit = '';
                                };

                                if (in_array(3, $atribut_pertanyaan)) {
                                    echo anchor(base_url() . 'survei/' . $ci->uri->segment(2) . '/pertanyaan-kualitatif/' . $ci->uri->segment(4) . $is_edit, '<i class="fa fa-arrow-left"></i> Kembali', ['class' => 'btn btn-secondary btn-lg font-weight-bold shadow tombolCancel']);
                                } else if (in_array(1, $atribut_pertanyaan)) {

                                    echo anchor(base_url() . 'survei/' . $ci->uri->segment(2) . '/pertanyaan-harapan/' . $ci->uri->segment(4) . $is_edit, '<i class="fa fa-arrow-left"></i> Kembali', ['class' => 'btn btn-secondary btn-lg font-weight-bold shadow tombolCancel']);
                                } else {

                                    echo anchor(base_url() . 'survei/' . $ci->uri->segment(2) . '/pertanyaan/' . $ci->uri->segment(4) . $is_edit, '<i class="fa fa-arrow-left"></i> Kembali', ['class' => 'btn btn-secondary btn-lg font-weight-bold shadow tombolCancel']);
                                } ?>
                            </td>
                            <td class="text-right">
                                <button type="submit"
                                    class="btn btn-warning btn-lg font-weight-bold shadow tombolSave">Selanjutnya
                                    <i class="fa fa-arrow-right"></i></button>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
@endsection



@section('javascript')
<script>
$('#saran').keyup(validateTextarea);

function validateTextarea() {
    var errorMsg = "Harap sesuaikan dengan format yang diminta.";
    var textarea = this;
    var pattern = new RegExp($(textarea).attr('pattern'));
    // check each line of text
    $.each($(this).val().split("\n"), function() {
        // check if the line matches the pattern
        var hasError = !this.match(pattern);
        if (typeof textarea.setCustomValidity === 'function') {
            textarea.setCustomValidity(hasError ? errorMsg : '');
        } else {
            // Not supported by the browser, fallback to manual error display...
            $(textarea).toggleClass('error', !!hasError);
            $(textarea).toggleClass('ok', !hasError);
            if (hasError) {
                $(textarea).attr('title', errorMsg);
            } else {
                $(textarea).removeAttr('title');
            }
        }
        return !hasError;
    });
}



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
                    window.location.href =
                        "<?php echo base_url() . 'survei/' . $ci->uri->segment(2) . '/add-konfirmasi/' . $ci->uri->segment(4) ?>";
                }, 500);
            }
        }
    })
    return false;
});
</script>
@endsection