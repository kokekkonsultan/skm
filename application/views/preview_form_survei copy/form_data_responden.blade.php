@extends('include_backend/_template')

@php
$ci = get_instance();
@endphp

@section('style')
<!-- <link rel="dns-prefetch" href="//fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet"> -->
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
            <div class="card shadow mb-4 mt-4" data-aos="fade-up"
                style="border-left: 5px solid #FFA800; font-size: 16px; font-family:arial, helvetica, sans-serif;">

                @if($manage_survey->img_benner == '')
                <img class="card-img-top" src="{{ base_url() }}assets/img/site/page/banner-survey.jpg"
                    alt="new image" />
                @else
                <img class="card-img-top shadow"
                    src="{{ base_url() }}assets/klien/benner_survei/{{$manage_survey->img_benner}}" alt="new image">
                @endif

                <div class="card-header text-center">
                    <h4><b>DATA RESPONDEN</b> - @include('include_backend/partials_backend/_tanggal_survei')</h4>
                </div>
                <div class="card-body">

                    <form>

                        <span style="color: red; font-style: italic;">{!! validation_errors() !!}</span>

                        <div class="form-group">
                            <label class="font-weight-bold">Nama Lengkap <span class="text-danger">*</span></label>
                            @php
                            echo form_input($nama_lengkap);
                            @endphp
                        </div>

                        </br>

                        <?php
                        foreach ($profil_responden->result() as $row) {
                        ?>

                        <div class="form-group">
                            <label class="font-weight-bold"><?php echo $row->nama_profil_responden ?> <span
                                    class="text-danger">*</span></label>

                            <?php if ($row->jenis_isian == 2) { ?>

                            <input class="form-control" type="<?php echo $row->type_data ?>"
                                name="<?php echo $row->nama_alias ?>" placeholder="Masukkan data anda ..." required>

                            <?php } else { ?>

                            <select class="form-control" name="<?php echo $row->nama_alias ?>" required>
                                <option value="">Please Select</option>

                                <?php
                                        foreach ($kategori_profil_responden->result() as $value) {
                                        ?>

                                <?php if ($value->id_profil_responden == $row->id) { ?>

                                <option value="<?php echo $value->id ?>">
                                    <?php echo $value->nama_kategori_profil_responden ?></option>

                                <?php } ?>

                                <?php } ?>

                            </select>

                            <?php } ?>
                        </div>
                        </br>

                        <?php } ?>



                </div>
                <div class="card-footer">
                    <table class="table table-borderless">
                        <tr>
                            <td class="text-left">
                                {!! anchor(base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2)
                                . '/preview-form-survei/opening', '<i class="fa fa-arrow-left"></i>
                                Kembali',
                                ['class' => 'btn btn-secondary btn-lg font-weight-bold shadow']) !!}
                            </td>
                            <td class="text-right">
                                <a class="btn btn-warning btn-lg font-weight-bold shadow"
                                    href="<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/preview-form-survei/pertanyaan' ?>">Selanjutnya
                                    <i class="fa fa-arrow-right"></i></a>
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type='text/javascript'>
$(window).load(function() {
    $("#pekerjaan").change(function() {
        console.log($("#pekerjaan option:selected").val());
        if ($("#pekerjaan option:selected").val() == '6') {
            $('#pekerjaan_lainnya').prop('hidden', false);
        } else {
            $('#pekerjaan_lainnya').prop('hidden', 'true');
        }
    });
});
</script>
@endsection