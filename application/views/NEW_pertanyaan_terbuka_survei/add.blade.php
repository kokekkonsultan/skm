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

            <div class="card" data-aos="fade-down">
                <div class="card-header bg-secondary">
                    <h5>{{ $title }}</h5>
                </div>
                <div class="card-body">

                    <?php echo form_open(base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/pertanyaan-terbuka/add/' . $ci->uri->segment(5)); ?>

                    <span class="text-danger"><?php echo validation_errors(); ?></span>

                    @if($ci->uri->segment(5) == 1)
                    <div class="form-group row">
                        @php
                        echo form_label('Unsur Pelayanan Dari <span style="color:red;">*</span>', '', ['class' =>
                        'col-sm-3
                        col-form-label
                        font-weight-bold']);
                        @endphp
                        <div class="col-sm-9">
                            @php
                            echo form_dropdown($id_unsur_pelayanan);
                            @endphp
                        </div>
                    </div>

                    @elseif($ci->uri->segment(5) == 2)
                    <div class="form-group row mt-5">
                        <label class="col-sm-3 col-form-label font-weight-bold">Letak Pertanyaan <span
                                style="color: red;">*</span></label>
                        <div class="col-sm-9">
                            <select class="form-control" name="is_letak_pertanyaan_tambahan"
                                id="is_letak_pertanyaan_tambahan" required>
                                <option value="">Please Select</option>
                                <option value="1">Paling Awal</option>
                                <option value="2">Paling Akhir</option>
                            </select>
                        </div>
                    </div>
                    @else

                    <div class="form-group row mt-5">
                        <label class="col-sm-3 col-form-label font-weight-bold">Letak Pertanyaan <span
                                style="color: red;">*</span></label>
                        <div class="col-sm-9">
                            <input name="is_letak_pertanyaan_tambahan" value="3" hidden>
                            <input class="form-control" placeholder="Pembuka Survei" disabled>
                        </div>
                    </div>
                    @endif


                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label font-weight-bold">Nama Pertanyaan <span
                                style="color:red;">*</span></label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <div class="input-group-prepend"><span
                                        class="input-group-text font-weight-bold">T<?php echo $jumlah_tambahan ?></span>
                                </div>
                                @php
                                echo form_input($nama_pertanyaan_terbuka);
                                @endphp
                            </div>
                        </div>
                    </div>



                    <div class="form-group row">
                        @php
                        echo form_label('Isi Pertanyaan <span style="color:red;">*</span>', '', ['class' =>
                        'col-sm-3 col-form-label
                        font-weight-bold']);
                        @endphp
                        <div class="col-sm-9">
                            @php
                            echo form_textarea($isi_pertanyaan_terbuka);
                            @endphp
                        </div>
                    </div>

                    <!-- <div class="form-group row">
                        <label class="col-sm-3 col-form-label font-weight-bold">Pilihan Jawaban <span
                                style="color:red;">*</span></label>
                        <div class="col-sm-9">
                            <label>
                                <input type="radio" name="jenis_jawaban" value="2" class="pilihan" required>
                                Jawaban Singkat
                            </label>
                            <hr>
                            <label>
                                <input type="radio" name="jenis_jawaban" value="1" class="pilihan">
                                Dengan Pilihan Ganda
                            </label>
                        </div>
                    </div> -->



                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label font-weight-bold">Pilihan Jawaban <span
                                style="color:red;">*</span></label>
                        <div class="col-9 col-form-label">
                            <div class="radio-inline">
                                <label class="radio radio-md">
                                    <input type="radio" name="jenis_jawaban" value="2" class="pilihan" required>
                                    <span></span>
                                    Jawaban Singkat
                                </label>
                                <label class="radio radio-md">
                                    <input type="radio" name="jenis_jawaban" value="1" class="pilihan">
                                    <span></span>
                                    Dengan Pilihan Ganda
                                </label>
                            </div>
                            <!-- <span class="form-text text-muted">Pilih YA jika pertanyaan tersebut menggunakan pilihan
                                jawaban Lainnya.</span> -->
                        </div>
                    </div>


                    <div name="opsi_1" id="opsi_1" style="display:none">
                        <div class="form-group fieldGroup">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label"></label>
                                <div class="col-sm-8">
                                    <input type="text" name="pilihan_jawaban[]" class="form-control"
                                        placeholder="Masukkan Pilihan Jawaban . . .">
                                </div>
                                <div class="input-group-addon col-sm-1">
                                    <a href="javascript:void(0)" class="btn btn-light-success addMore"><i
                                            class="fas fa-plus"></i></a>
                                </div>
                            </div>
                        </div>

                        <div class="form-group fieldGroupCopy" style="display:none">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label"></label>
                                <div class="col-sm-8">
                                    <input type="text" name="pilihan_jawaban[]" class="form-control"
                                        placeholder="Masukkan Pilihan Jawaban . . .">
                                </div>
                                <div class="input-group-addon col-sm-1">
                                    <a href="javascript:void(0)" class="btn btn-light-danger remove"><i
                                            class="fas fa-trash"></i></a>
                                </div>
                            </div>
                        </div>

                        <!-- <div class="form-group row">
                            <label class="col-sm-3 col-form-label fw-bold font-weight-bold">Dengan Isian Lainnya
                                <span style="color:red;">*</span></label>
                            <div class="col-sm-9">
                                <label>
                                    <input type="radio" name="opsi_pilihan_jawaban" value="1"> Ya
                                </label>
                                <hr>
                                <label>
                                    <input type="radio" name="opsi_pilihan_jawaban" value="2"> Tidak
                                </label>
                            </div>
                        </div> -->

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label fw-bold font-weight-bold">Dengan Isian Lainnya
                                <span style="color:red;">*</span></label>
                            <div class="col-9 col-form-label">
                                <div class="radio-inline">
                                    <label class="radio radio-md">
                                        <input type="radio" name="opsi_pilihan_jawaban" value="1">
                                        <span></span>
                                        Ya
                                    </label>
                                    <label class="radio radio-md">
                                        <input type="radio" name="opsi_pilihan_jawaban" value="2">
                                        <span></span>
                                        Tidak
                                    </label>
                                </div>
                                <span class="form-text text-muted">Pilih YA jika pertanyaan tersebut menggunakan pilihan
                                    jawaban Lainnya.</span>
                            </div>
                        </div>

                    </div>



                    <!-- <div class="form-group row">
                        <label class="col-sm-3 col-form-label font-weight-bold">Status Pengisian Pertanyaan <span
                                style="color:red;">*</span></label>
                        <div class="col-9">
                            <span class="switch switch-outline switch-icon switch-success">
                                <label>
                                    <input type="checkbox" name="status_pengisian" value="" />
                                    <span></span>
                                </label>
                            </span>
                            <span class="form-text text-muted">Aktifkan jika pertanyaan tersebut wajib untuk
                                diisi.</span>
                        </div>
                    </div> -->



                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label font-weight-bold">Status Pengisian Pertanyaan <span
                                style="color:red;">*</span></label>
                        <div class="col-9 col-form-label">
                            <div class="radio-inline">
                                <label class="radio radio-md">
                                    <input type="radio" name="is_required" value="">
                                    <span></span>
                                    Wajib di Isi
                                </label>
                                <label class="radio radio-md">
                                    <input type="radio" name="is_required" value="1" required>
                                    <span></span>
                                    Tidak Wajib di Isi
                                </label>
                            </div>
                            <span class="form-text text-muted">Status pengisian pertanyaan ini digunakan untuk
                                mendefinisikan wajib atau tidaknya pertanyaan diisi.</span>
                        </div>
                    </div>
                    <br>



                    <div class="text-right">
                        @php
                        echo
                        anchor(base_url().$ci->session->userdata('username').'/'.$ci->uri->segment(2).'/pertanyaan-terbuka',
                        'Batal', ['class' => 'btn btn-light-primary font-weight-bold'])
                        @endphp
                        <?php echo form_submit('submit', 'Simpan', ['class' => 'btn btn-primary font-weight-bold']); ?>
                    </div>

                    <?php echo form_close(); ?>
                </div>
            </div>

        </div>
    </div>

</div>

@endsection

@section ('javascript')
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js">
</script>

<!-- <script type="text/javascript">
$(function() {
    $(":radio.custom").click(function() {
        $("#melekat_pada_unsur").hide();
        $("#tidak_melekat_pada_unsur").hide();
        $("#pertanyaan_lainnya").hide();
        if ($(this).val() == "1") {
            $("#is_letak_pertanyaan_tambahan").removeAttr('required');
            $("#id_unsur_pelayanan").prop('required', true);
            $("#melekat_pada_unsur").show();
            $("#pertanyaan_lainnya").show();
            $("#tidak_melekat_pada_unsur").hidden();
        } else {
            $("#id_unsur_pelayanan").removeAttr('required');
            $("#is_letak_pertanyaan_tambahan").prop('required', true);
            $("#tidak_melekat_pada_unsur").show();
            $("#pertanyaan_lainnya").show();
            $("#melekat_pada_unsur").hidden();
        }
    });
});
</script> -->

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

<script src="https://cdn.ckeditor.com/ckeditor5/34.2.0/classic/ckeditor.js"></script>
<script>
ClassicEditor
    .create(document.querySelector('#isi_pertanyaan_terbuka'))
    .then(editor => {
        console.log(editor);
    })
    .catch(error => {
        console.error(error);
    });
</script>
@endsection