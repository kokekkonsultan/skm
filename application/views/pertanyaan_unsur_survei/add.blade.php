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
                    <span class="text-danger"><?php echo validation_errors(); ?></span>
                    <br>
                    <?php echo form_open(base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/pertanyaan-unsur/add'); ?>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label font-weight-bold">Unsur Pelayanan <span
                                style="color: red;">*</span></label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <div class="input-group-prepend"><span
                                        class="input-group-text font-weight-bold">U<?php echo $jumlah_unsur ?></span>
                                </div>
                                @php
                                echo form_input($nama_unsur_pelayanan);
                                @endphp
                                <small>
                                    Menurut Permenpan dan RB, unsur SKM terbagi 9 unsur antara lain: 1) Persyaratan 2)
                                    Sistem, Mekanisme, dan Prosedur 3) Waktu Penyelesaian 4) Biaya/Tarif 5) Produk
                                    Spesifikasi Jenis Pelayanan 6) Kompetensi Pelaksana 7) Perilaku Pelaksana 8)
                                    Penanganan Pengaduan, Saran dan Masukan 9) Sarana dan prasarana
                                </small>
                            </div>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label font-weight-bold">Sub Unsur Pelayanan <span
                                style="color: red;">*</span></label>
                        <div class="col-9 col-form-label">
                            <div class="radio-inline">
                                <label class="radio radio">
                                    <input type="radio" name="is_sub_unsur_pelayanan" id="default" value="2"
                                        class="custom" required>
                                    <span></span> Tanpa Sub Unsur
                                </label>
                            </div>
                            <span class="form-text text-muted">Jenis Pertanyaan yang tidak memiliki turunan sub.</span>
                            <hr>
                            <div class="radio-inline">
                                <label class="radio radio">
                                    <input type="radio" name="is_sub_unsur_pelayanan" id="custom" value="1"
                                        class="custom">
                                    <span></span> Dengan Sub Unsur
                                </label>
                            </div>
                            <span class="form-text text-muted">Jenis Pertanyaan yang memiliki turunan sub unsur.</span>
                        </div>
                    </div>

                    <!-- //DENGAN SUB UNSUR -->
                    <div id="dengan_sub_unsur" style="display: none;">
                        <div class="alert alert-custom alert-notice alert-light-info fade show mb-10" role="alert">
                            <div class="alert-icon"><i class="flaticon-warning"></i></div>
                            <div class="alert-text"> <span>Silahkan <b>simpan</b> terlebih dahulu lalu anda akan
                                    diarahkan untuk mengisi form <b>Pertanyaan Sub Unsur Pelayanan</b> atau anda dapat
                                    melanjutkan
                                    malalui menu <b>Tambah Pertanyaan Sub Unsur Pelayanan</b></span></div>
                            <div class="alert-close">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true"><i class="ki ki-close"></i></span>
                                </button>
                            </div>
                        </div>
                    </div>


                    <!-- TANPA SUB UNSUR -->
                    <div id="tanpa_sub_unsur" style="display: none;">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label font-weight-bold">Pertanyaan Unsur <span
                                    style="color: red;">*</span></label>
                            <div class="col-sm-9">
                                @php
                                echo form_textarea($isi_pertanyaan_unsur);
                                @endphp
                            </div>
                            </label>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label font-weight-bold">Pilihan Jawaban <span
                                    style="color: red;">*</span></label>
                            <div class="col-sm-9">

                                <div class="radio-list">
                                    <label class="radio">
                                        <input type="radio" name="jenis_pilihan_jawaban" id="jenis_pilihan_jawaban"
                                            value="1" class="jawaban" required><span></span>2 Pilihan Jawaban
                                    </label>
                                    <label class="radio">
                                        <input type="radio" name="jenis_pilihan_jawaban" id="jenis_pilihan_jawaban"
                                            value="2" class="jawaban"><span></span>
                                        {{$manage_survey->skala_likert == 5 ? '5 Pilihan Jawaban' : '4 Pilihan Jawaban'}}
                                    </label>
                                </div>
                            </div>
                        </div>


                        <!-- PILIHAN JAWABAN 2 -->
                        <div name="2_jawaban" class="2_jawaban" style="display:none">
                            <br>
                            <h5 class="text-primary">Pilihan Jawaban</h5>
                            <hr class="mb-5">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Pilihan Jawaban 1 <span
                                        style="color: red;">*</span></label>
                                <div class="col-sm-9">
                                    @php
                                    echo form_input($pilihan_jawaban_1);
                                    @endphp
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Pilihan Jawaban 2 <span
                                        style="color: red;">*</span></label>
                                <div class="col-sm-9">
                                    @php
                                    echo form_input($pilihan_jawaban_2);
                                    @endphp
                                </div>
                            </div>
                        </div>

                        <!-- PILIHAN JAWABAN 4 -->
                        <div class="4_jawaban" name="4_jawaban" style="display:none">
                            <br>
                            <h5 class="text-primary">Pilihan Jawaban</h5>
                            <hr class="mb-5">

                            <datalist id="list_jawaban">
                                <?php
                                foreach ($pilihan->result() as $d) {
                                    echo "<option value='$d->id'>$d->pilihan_1</option>";
                                }
                                ?>
                            </datalist>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Pilihan Jawaban 1 <span
                                        style="color: red;">*</span></label>
                                <div class="col-sm-9">
                                    <input class="form-control pilihan" list="list_jawaban" type="text"
                                        name="pilihan_jawaban[]" id="id" placeholder="Masukkan pilihan jawaban anda .."
                                        onchange="return autofill();" autocomplete='off'>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Pilihan Jawaban 2 <span
                                        style="color: red;">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control pilihan" name="pilihan_jawaban[]"
                                        id="pilihan_2">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Pilihan Jawaban 3 <span
                                        style="color: red;">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control pilihan" name="pilihan_jawaban[]"
                                        id="pilihan_3">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Pilihan Jawaban 4 <span
                                        style="color: red;">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control pilihan" name="pilihan_jawaban[]"
                                        id="pilihan_4">
                                </div>
                            </div>

                            @if($manage_survey->skala_likert == 5)
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Pilihan Jawaban 5 <span
                                        style="color: red;">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control pilihan" name="pilihan_jawaban[]"
                                        id="pilihan_5">
                                </div>
                            </div>
                            @endif

                        </div>


                        <hr class="mb-5">

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label
                            font-weight-bold">Wajib Diisi <span style="color: red;">*</span></label>
                            <div class="col-sm-9">
                                <select class="form-control" id="is_required" name="is_required" required>
                                    <option value=''>Please Select</option>
                                    <option value='1'>Aktif</option>
                                    <option value='2'>Tidak Aktif</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label
                            font-weight-bold">Alasan <span style="color: red;">*</span></label>
                            <div class="col-sm-9">
                                <select class="form-control" id="is_alasan" name="is_alasan" required>
                                    <option value=''>Please Select</option>
                                    <option value='1'>Aktif</option>
                                    <option value='2'>Tidak Aktif</option>
                                </select>
                            </div>
                        </div>

                    </div>

                    

                    <div class="text-right">
                        @php
                        echo
                        anchor(base_url().$ci->session->userdata('username').'/'.$ci->uri->segment(2).'/pertanyaan-unsur',
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


<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
<script type="text/javascript">
$(function() {
    $(":radio.custom").click(function() {
        $("#dengan_sub_unsur").hide();
        $("#tanpa_sub_unsur").hide();
        if ($(this).val() == "1") {
            $("#jenis_pilihan_jawaban").removeAttr('required');
            $("#is_required").removeAttr('required');
            $("#is_alasan").removeAttr('required');
            $("#dengan_sub_unsur").show();
            $("#tanpa_sub_unsur").hidden();
        } else {
            $("#jenis_pilihan_jawaban").prop('required', true);
            $("#is_required").prop('required', true);
            $("#is_alasan").prop('required', true);
            $("#tanpa_sub_unsur").show();
            $("#dengan_sub_unsur").hidden();
        }
    });
});
</script>

<script type="text/javascript">
$(function() {
    $(":radio.jawaban").click(function() {
        $(".4_jawaban").hide()
        $(".2_jawaban").hide()
        if ($(this).val() == "2") {
            $(".pilihan_jawaban").removeAttr('required');
            $(".pilihan").prop('required', true);
            $(".4_jawaban").show();
            $(".2_jawaban").hide();
        } else {
            $(".pilihan").removeAttr('required');
            $(".pilihan_jawaban").prop('required', true);
            $(".2_jawaban").show();
            $(".4_jawaban").hide();
        }
    });
});
</script>


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
                document.getElementById('pilihan_5').value = val.pilihan_5;
            });
        }
    });
}
</script>

<script src="https://cdn.ckeditor.com/ckeditor5/34.2.0/classic/ckeditor.js"></script>
<script>
ClassicEditor
    .create(document.querySelector('#isi_pertanyaan_unsur'))
    .then(editor => {
        console.log(editor);
    })
    .catch(error => {
        console.error(error);
    });
</script>
@endsection