@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')

@endsection

@section('content')
<div class="container">
  
    {!! form_open($form_action) !!}
    {!! validation_errors() !!}

    <div class="card" data-aos="fade-down" data-aos-delay="300">
        <div class="card-header bg-secondary font-weight-bold">
            Pertanyaan
        </div>
        <div class="card-body">


           <div class="form-group row">
                {!! form_label('<b>Unsur Pelayanan</b> <span style="color: red;">*</span>', '', ['class' => 'col-sm-2 col-form-label']) !!}
                <div class="col-sm-10">
                    {!! form_dropdown($id_unsur_pelayanan) !!}
                </div>
            </div>

            <div class="form-group row">
                {!! form_label('<b>Isi Pertanyaan Unsur</b> <span style="color: red;">*</span>', '', ['class' =>
                'col-sm-2 col-form-label']) !!}
                <div class="col-sm-10">
                    {!! form_textarea($isi_pertanyaan_unsur) !!}
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label fw-bold"><b>Pilihan Jawaban</b> <span
                        style="color: red;">*</span></label>
                <div class="col-sm-10">
                    <div>
                        <label><input type="radio" name="jenis_pilihan_jawaban" id="default" value="1" class="custom">
                            &nbsp 2
                            Pilihan Jawaban</label><br>
                    </div>
                    <div>
                        <label><input type="radio" name="jenis_pilihan_jawaban" id="custom" value="2" class="custom">
                            &nbsp 4
                            Pilihan Jawaban</label><br>
                    </div>
                </div>
                </label>
            </div>

            <div name="2_jawaban" id="2_jawaban" style="display:none">
                <div class="form-group row">
                    {!! form_label('<b>Pilihan Jawaban 1</b> <span style="color: red;">*</span>', '', ['class' =>
                    'col-sm-2 col-form-label']) !!}
                    <div class="col-sm-10">
                        {!! form_input($pilihan_jawaban_1) !!}
                    </div>
                </div>

                <div class="form-group row">
                    {!! form_label('<b>Pilihan Jawaban 2</b> <span style="color: red;">*</span>', '', ['class' =>
                    'col-sm-2 col-form-label']) !!}
                    <div class="col-sm-10">
                        {!! form_input($pilihan_jawaban_2) !!}
                    </div>
                </div>
            </div>

            <datalist id="data_mahasiswa">
                <?php
                foreach ($pilihan->result() as $d) {
                    echo "<option value='$d->id'>$d->pilihan_1</option>";
                }
                ?>
            </datalist>


            <div name="4_jawaban" id="4_jawaban" style="display:none">
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label"><b>Pilihan Jawaban 1</b> <span
                            style="color: red;">*</span></label>
                    <div class="col-sm-10">
                        <input class="form-control" list="data_mahasiswa" type="text" name="pilihan_jawaban[]" id="id"
                            placeholder="Masukkan pilihan jawaban anda .." onchange="return autofill();" autofocus
                            autocomplete='off'>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label"><b>Pilihan Jawaban 2</b> <span
                            style="color: red;">*</span></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="pilihan_jawaban[]" id="pilihan_2" </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label"><b>Pilihan Jawaban 3</b> <span
                            style="color: red;">*</span></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="pilihan_jawaban[]" id="pilihan_3">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label"><b>Pilihan Jawaban 4</b> <span
                            style="color: red;">*</span></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="pilihan_jawaban[]" id="pilihan_4">
                    </div>
                </div>

            </div>

            <div class="text-right mt-3 mb-3">
                {!! anchor($ci->session->userdata('urlback_second'), 'Batal', ['class'=>'btn btn-light-primary
                font-weight-bold shadow']) !!}
                <button type="submit" class="btn btn-primary font-weight-bold shadow">Simpan</button>
            </div>
        </div>
    </div>
    {!! form_close() !!}
</div>
@endsection

@section('javascript')

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
<script type="text/javascript">
$(function() {
    $(":radio.custom").click(function() {
        $("#4_jawaban").hide()
        if ($(this).val() == "2") {
            $("#4_jawaban").show();
        } else {
            $("#4_jawaban").hide();
        }

        $("#2_jawaban").hide()
        if ($(this).val() == "1") {
            $("#2_jawaban").show();
        } else {
            $("#2_jawaban").hide();
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
            });
        }
    });
}
</script>


<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
<script type="text/javascript">
$(function() {
    $(":radio.customisasi").click(function() {
        $("#id_parent").hide()
        if ($(this).val() == "1") {
            $("#id_parent").show();
        } else {
            $("#id_parent").hidden();
        }
    });
});
</script>

@endsection