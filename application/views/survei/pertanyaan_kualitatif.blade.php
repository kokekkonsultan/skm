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
            <li id="completed"><strong>Completed</strong></li>
        </div>
    </div>
    <br>
    <br>
    <div class="row">
        <div class="col-md-8 offset-md-2" style="font-size: 16px; font-family:arial, helvetica, sans-serif;">
            <div class="card shadow mb-4 mt-4" id="kt_blockui_content" data-aos="fade-up">
                @if($judul->img_benner == '')
                <img class="card-img-top" src="{{ base_url() }}assets/img/site/page/banner-survey.jpg"
                    alt="new image" />
                @else
                <img class="card-img-top shadow"
                    src="{{ base_url() }}assets/klien/benner_survei/{{$manage_survey->img_benner}}" alt="new image">
                @endif
                <div class="card-header text-center">
                    <h3 class="mt-5" style="font-family: 'Exo 2', sans-serif;"><b>PERTANYAAN KUALITATIF</b></h3>
					@include('include_backend/partials_backend/_tanggal_survei')
                </div>
                <div class="card-body">

                    <form action="<?php echo base_url() . 'survei/' . $ci->uri->segment(2) . '/add-kualitatif/' .
                                        $ci->uri->segment(4) ?>" class="form_survei" method="POST">

                        </br>

                        @php
                        $no = 1;
                        @endphp

                        @foreach ($kualitatif as $row)

                        <input type="text" name="id_kualitatif[]" value="<?php echo $row->id; ?>" hidden>

                        <table class="table table-borderless" border="0">
                            <tr>
                                <td width="4%" valign="top">{{ $no++ }}.</td>
                                <td><?php echo $row->isi_pertanyaan ?></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                                    <textarea type="text" rows="7" class="form-control jawaban-kualitatif" id="isi_jawaban_kualitatif"
                                        name="isi_jawaban_kualitatif[]" value="{{ $row->isi_jawaban_kualitatif }}"
                                        placeholder="Masukkan jawaban pertanyaan kualitatif pada bidang ini.." autofocus
                                        required>{{ $row->isi_jawaban_kualitatif }}</textarea>

                                    <small id="text_isi_jawaban_kualitatif" class="text-danger">**Pengisian alasan hanya dapat menggunakan tanda baca
                                        (.) titik dan (,) koma</small>
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
                                <?php
                                if($ci->uri->segment(5) == 'edit'){
                                    $is_edit = '/edit';
                                } else {
                                    $is_edit = '';
                                };
                                
                                
                                if (in_array(1, $atribut_pertanyaan)) {
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

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@section('javascript')
<script>
$('.jawaban-kualitatif').keyup(function(){
    var textboxes = document.getElementsByClassName("jawaban-kualitatif");
    for(var i = 0; i < textboxes.length; i++){
        var textbox = textboxes[i].value;
        var result = !!textbox.match(/[-:;!?"'()/{}<>@#$%^&*_+=|`~]/)
        if(result){
            //alert("Pengisian alasan hanya dapat menggunakan tanda baca(.) titik dan (,) koma");
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Pengisian alasan hanya dapat menggunakan tanda baca(.) titik dan (,) koma !',
                confirmButtonColor: '#8950FC',
                confirmButtonText: 'Baik, saya mengerti',
            })
            textboxes[i].focus();
        }
    }
});


$('.form_survei').submit(function(e) {

    var textboxes = document.getElementsByClassName("jawaban-kualitatif");
    for(var i = 0; i < textboxes.length; i++){
        var textbox = textboxes[i].value;
        var result = !!textbox.match(/[-:;!?"'()/{}<>@#$%^&*_+=|`~]/)
        if(result){
            //alert("Pengisian alasan hanya dapat menggunakan tanda baca(.) titik dan (,) koma");
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Pengisian alasan hanya dapat menggunakan tanda baca(.) titik dan (,) koma !',
                confirmButtonColor: '#8950FC',
                confirmButtonText: 'Baik, saya mengerti',
            })
            return false;
        }
    }

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
                    window.location.href = "<?php echo $url_next ?>";
                }, 500);
            }
        }
    })
    return false;
});
</script>
@endsection