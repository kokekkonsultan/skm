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
                    <?php echo form_open(base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/pertanyaan-terbuka/edit/' . $ci->uri->segment(5)); ?>
                    <span class="text-danger"><?php echo validation_errors(); ?></span>
                    </br>

                    <!-- <div class="form-group row">
                        @php
                        echo form_label('Unsur Pelayanan Dari <span style="color:red;">*</span>', '', ['class' =>
                        'col-sm-3 col-form-label
                        font-weight-bold']);
                        @endphp
                        <div class="col-sm-9">
                            @php
                            echo form_input($id_unsur_pelayanan);
                            @endphp
                        </div>
                    </div> -->

                    <div class="form-group row">
                        @php
                        echo form_label('Pertanyaan Tambahan <span style="color:red;">*</span>', '', ['class' =>
                        'col-sm-3 col-form-label
                        font-weight-bold']);
                        @endphp
                        <div class="col-sm-9">
                            <div class="input-group">
                                <div class="input-group-prepend"><span
                                        class="input-group-text"><?php echo $current->nomor_pertanyaan_terbuka ?></span>
                                </div>
                                @php
                                echo form_input($nama_pertanyaan_terbuka);
                                @endphp
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        @php
                        echo form_label('Isi Pertanyaan Tambahan <span style="color:red;">*</span>', '', ['class' =>
                        'col-sm-3 col-form-label
                        font-weight-bold']);
                        @endphp
                        <div class="col-sm-9">
                            @php
                            echo form_textarea($isi_pertanyaan_terbuka);
                            @endphp
                        </div>
                    </div>

                    <input type="text" name="id_jenis_jawaban" value="<?php echo $current->id_jenis_pilihan_jawaban; ?>"
                        hidden>

                    <?php
                    $no = 1;
                    foreach ($pilihan_jawaban as $row) {
                    ?>
                    <input type="hidden" class="form-control" id="id_kategori" name="id_kategori[]"
                        value="<?php echo $row->id_isi_pertanyaan_ganda; ?>">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label font-weight-bold">Pilihan Jawaban <?php echo $no++; ?>
                            <span style="color:red;">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="pertanyaan_ganda" name="pertanyaan_ganda[]"
                                value="<?php echo $row->pertanyaan_ganda; ?>" required>
                        </div>
                    </div>
                    <?php
                    }
                    ?>



                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label font-weight-bold">Status Pengisian Pertanyaan <span
                                style="color:red;">*</span></label>
                        <div class="col-9 col-form-label">
                            <div class="radio-inline">
                                <label class="radio radio-md">
                                    <input type="radio" name="is_required" value=""
                                        <?php echo $current->is_required == '' ? 'checked' : '' ?>>
                                    <span></span>
                                    Wajib di Isi
                                </label>
                                <label class="radio radio-md">
                                    <input type="radio" name="is_required" value="1"
                                        <?php echo $current->is_required == 1 ? 'checked' : '' ?> required>
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