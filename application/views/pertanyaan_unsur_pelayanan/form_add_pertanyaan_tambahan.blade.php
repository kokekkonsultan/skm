@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')

@endsection

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header bg-secondary font-weight-bold">
            {{ $title }}
        </div>
        <div class="card-body">
            @php
            echo form_open($form_action);
            @endphp
            @php
            echo validation_errors();
            @endphp

            <div class="form-group row">
                @php
                echo form_label('<b>Unsur Pelayanan Dari</b> <span style="color: red;">*</span>', '', ['class' =>
                'col-sm-2 col-form-label']);
                @endphp
                <div class="col-sm-10">
                    @php
                    echo form_dropdown($id_unsur_pelayanan);
                    @endphp
                </div>
            </div>

            <div class="form-group row">
                @php
                echo form_label('<b>Nama Pertanyaan Tambahan</b> <span style="color: red;">*</span>', '', ['class' =>
                'col-sm-2 col-form-label']);
                @endphp
                <div class="col-sm-10">
                    @php
                    echo form_input($nama_pertanyaan_terbuka);
                    @endphp
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label"><b>Isi Pertanyaan</b> <span style="color: red;">*</span></label>
                <div class="col-sm-10">
                    <textarea type="text" name="isi_pertanyaan_terbuka" class="form-control" required="required"
                        placeholder="Masukkan Isi Pertanyaan .."></textarea><br>
                </div>
            </div>



            <div class="form-group row">
                <label class="col-sm-2 col-form-label fw-bold"><b>Pilihan Jawaban</b> <span
                        style="color: red;">*</span></label>
                <div class="col-sm-10">
                    <div>
                        <input type="radio" name="jenis_jawaban" value="2" class="pilihan">
                        Jawaban Singkat
                    </div>
                    <hr>
                    <div>
                        <input type="radio" name="jenis_jawaban" value="1" class="pilihan">
                        Dengan Pilihan Ganda
                    </div>
                </div>
                </label>
            </div>

            <div name="opsi_1" id="opsi_1" style="display:none">
                <div class="form-group fieldGroup">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label"></label>
                        <div class="col-sm-9">
                            <input type="text" name="pilihan_jawaban[]" class="form-control"
                                placeholder="Masukkan Pilihan Jawaban . . ." />
                        </div>
                        <div class="input-group-addon col-sm-1">
                            <a href="javascript:void(0)" class="btn btn-light-success addMore"><i
                                    class="fas fa-plus"></i></a>
                        </div>
                    </div>
                </div>

                <div class="form-group fieldGroupCopy">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label"></label>
                        <div class="col-sm-9">
                            <input type="text" name="pilihan_jawaban[]" class="form-control"
                                placeholder="Masukkan Pilihan Jawaban . . ." />
                        </div>
                        <div class="input-group-addon col-sm-1">
                            <a href="javascript:void(0)" class="btn btn-light-danger remove"><i
                                    class="fas fa-trash"></i></a>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label fw-bold font-weight-bold"><b>Dengan Isian Lainnya</b> <span
                            style="color: red;">*</span></label>
                    <div class="col-sm-10">
                        <div>
                            <input type="radio" name="opsi_pilihan_jawaban" value="1"> Ya
                        </div>
                        <hr>
                        <div>
                            <input type="radio" name="opsi_pilihan_jawaban" value="2"> Tidak
                        </div>
                    </div>
                    </label>
                </div>
            </div>

            <div class="text-right mt-3 mb-3">
                @php
                echo anchor($ci->session->userdata('urlback_second'), 'Batal', ['class'=>'btn btn-light-primary
                font-weight-bold shadow']);
                @endphp
                <button type="submit" class="btn btn-primary font-weight-bold">Simpan</button>
            </div>

            @php
            echo form_close();
            @endphp

        </div>
    </div>
</div>
@endsection

@section('javascript')

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js">
</script>

<script type="text/javascript">
$(function() {
    $(":radio.pilihan").click(function() {

        $("#opsi_1").hide()
        if ($(this).val() == "1") {
            $("#opsi_1").show();
        } else {
            $("#opsi_1").hide();
        }
    });
});
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
<script>
$(document).ready(function() {
    // membatasi jumlah inputan
    var maxGroup = 10;

    //melakukan proses multiple input 
    $(".addMore").click(function() {
        if ($('body').find('.fieldGroup').length < maxGroup) {
            var fieldHTML = '<div class="form-group fieldGroup">' + $(".fieldGroupCopy").html() +
                '</div>';
            $('body').find('.fieldGroup:last').after(fieldHTML);
        } else {
            alert('Maximum ' + maxGroup + ' groups are allowed.');
        }
    });

    //remove fields group
    $("body").on("click", ".remove", function() {
        $(this).parents(".fieldGroup").remove();
    });
});
</script>
@endsection