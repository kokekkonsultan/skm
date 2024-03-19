@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')

@endsection

@section('content')

<div class="container-fluid">
    @include("include_backend/partials_no_aside/_inc_menu_repository")

    <div class="row justify-content-md-center mt-5">
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
                    <?php echo form_open(base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/pertanyaan-unsur/edit/' . $ci->uri->segment(5)); ?>
                    @php
                    echo validation_errors();
                    @endphp


                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label font-weight-bold">Unsur Pelayanan <span
                                style="color: red;">*</span></label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <div class="input-group-prepend"><span
                                        class="input-group-text"><?php echo $nomor_unsur ?></span></div>
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

                    @if($unsur_turunan == 1)
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label font-weight-bold">Isi Pertanyaan <span
                                style="color: red;">*</span>
                        </label>
                        <div class="col-sm-9">
                            @php
                            echo form_textarea($isi_pertanyaan_unsur);
                            @endphp
                        </div>
                    </div>

                    <br>

                    @foreach ($nama_kategori_unsur as $row)
                    <input type="text" class="form-control" id="id_kategori" name="id_kategori[]"
                        value="<?php echo $row->id; ?>" hidden>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label font-weight-bold">Pilihan Jawaban
                            <span style="color: red;">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="nama_kategori_unsur_pelayanan"
                                name="nama_kategori_unsur_pelayanan[]"
                                value="<?php echo $row->nama_kategori_unsur_pelayanan; ?>" required>
                        </div>
                    </div>
                    @endforeach

                    @endif

                    <div class="text-right">
                        @php
                        echo
                        anchor(base_url().$ci->session->userdata('username').'/'.$ci->uri->segment(2).'/pertanyaan-unsur',
                        'Cancel', ['class' => 'btn btn-light-primary font-weight-bold'])
                        @endphp
                        <?php echo form_submit('submit', 'Update', ['class' => 'btn btn-primary font-weight-bold']); ?>
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
        if ($(this).val() == "Custom") {
            $("#4_jawaban").show();
        } else {
            $("#4_jawaban").hide();
        }

        $("#2_jawaban").hide()
        if ($(this).val() == "Default") {
            $("#2_jawaban").show();
        } else {
            $("#2_jawaban").hide();
        }
    });
});
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