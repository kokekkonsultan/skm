@extends('include_backend/template_no_aside')

@php
$ci = get_instance();
@endphp

@section('style')

@endsection

@section('content')

<div class="container-fluid">
    @include("include_backend/partials_no_aside/_inc_menu_repository")

    <div class="row justify-content-md-center">
        <div class="col col-lg-10">
            <div class="card mt-5">
                <div class="card-header bg-secondary">
                    <h3>{{ $title }}</h3>
                    <div> Please enter the user's information below.</div>
                </div>
                <div class="card-body">
                    <br>

                    <?php echo form_open(base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/pertanyaan-unsur-survey/add'); ?>


                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label font-weight-bold">Unsur Pelayanan <span style="color: red;">*</span></label>
                        <div class="col-sm-10">
                            <select class="form-control" name="id_unsur_pelayanan" id="id_unsur_pelayanan" autofocus>
                                <option value="">Please Select</option>
                                <?php
                                foreach ($unsur_pelayanan->result() as $d) {
                                ?>
                                    <option value="<?php echo $d->id ?>">
                                        <?php echo $d->nomor_unsur ?>. <?php echo $d->nama_unsur_pelayanan ?></option>

                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        </label>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label font-weight-bold">Pertanyaan Unsur <span style="color: red;">*</span></label>
                        <div class="col-sm-10">
                            @php
                            echo form_textarea($isi_pertanyaan_unsur);
                            @endphp
                        </div>
                        </label>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label font-weight-bold">Pilihan Jawaban <span style="color: red;">*</span></label>
                        <div class="col-sm-10">
                            <div>
                                <label><input type="radio" name="jenis_pilihan_jawaban" id="default" value="1" class="jawaban">
                                    &nbsp 2
                                    Pilihan
                                    Jawaban</label><br>
                            </div>
                            <div>
                                <label><input type="radio" name="jenis_pilihan_jawaban" id="custom" value="2" class="jawaban">
                                    &nbsp 4
                                    Pilihan
                                    Jawaban</label><br>
                            </div>
                        </div>
                        </label>
                    </div>


                    <div name="2_jawaban" id="2_jawaban" style="display:none">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Pilihan Jawaban
                                1</label>
                            <div class="col-sm-10">
                                @php
                                echo form_input($pilihan_jawaban_1);
                                @endphp
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Pilihan Jawaban
                                2</label>
                            <div class="col-sm-10">
                                @php
                                echo form_input($pilihan_jawaban_2);
                                @endphp
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
                            <label class="col-sm-2 col-form-label">Pilihan Jawaban
                                1</label>
                            <div class="col-sm-10">
                                <input class="form-control" list="data_mahasiswa" type="text" name="pilihan_jawaban[]" id="id" placeholder="Masukkan pilihan jawaban anda .." onchange="return autofill();" autofocus autocomplete='off'>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Pilihan Jawaban
                                2</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="pilihan_jawaban[]" id="pilihan_2" </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Pilihan Jawaban
                                3</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="pilihan_jawaban[]" id="pilihan_3">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Pilihan Jawaban
                                4</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="pilihan_jawaban[]" id="pilihan_4">
                            </div>
                        </div>
                    </div>

                    <div class="text-right">
                        @php
                        echo
                        anchor(base_url().$ci->session->userdata('username').'/'.$ci->uri->segment(2).'/pertanyaan-survey',
                        'Cancel', ['class' => 'btn btn-light-primary font-weight-bold'])
                        @endphp
                        <?php echo form_submit('submit', 'Create', ['class' => 'btn btn-primary font-weight-bold']); ?>
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
            $("#id_parent").hide()
            if ($(this).val() == "1") {
                $("#id_parent").show();
            } else {
                $("#id_parent").hidden();
            }
        });
    });
</script>

<script type="text/javascript">
    $(function() {
        $(":radio.jawaban").click(function() {
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
@endsection