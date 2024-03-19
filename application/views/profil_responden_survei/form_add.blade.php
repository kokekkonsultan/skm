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
            @include('include_backend/partials_backend/_message')
            <div class="card" data-aos="fade-down">
                <div class="card-header bg-secondary">
                    <h5>{{ $title }}</h5>
                </div>
                <div class="card-body">
                    <?php echo form_open(base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/profil-responden-survei/add-custom'); ?>

                    <span style="color: red; font-style: italic;"><?php echo validation_errors() ?></span>
                    <br>
                    <div class="form-group row">
                        @php
                        echo form_label('Nama Profil Responden <span style="color:red;">*</span>', '', ['class' =>
                        'col-sm-3 col-form-label
                        font-weight-bold']);
                        @endphp
                        <div class="col-sm-9">
                            @php
                            echo form_input($nama_profil_responden);
                            @endphp
                        </div>
                    </div>

                    <!-- <div class="form-group row">
                        <label class="col-sm-3 col-form-label font-weight-bold">Jenis Jawaban <span
                                style="color:red;">*</span></label>
                        <div class="col-sm-9">
                            <label>
                                <input type="radio" name="jenis_jawaban" id="default" value="2" class="jawaban">
                                Jawaban Singkat
                            </label>
                            <hr>
                            <label>
                                <input type="radio" name="jenis_jawaban" id="custom" value="1" class="jawaban">
                                Dengan Pilihan Ganda
                            </label>
                        </div>
                    </div> -->

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label font-weight-bold">Jenis Jawaban <span
                                style="color:red;">*</span></label>
                        <div class="col-9 col-form-label">
                            <div class="radio-inline">
                                <label class="radio radio-md">
                                    <input type="radio" name="jenis_jawaban" id="default" value="2" class="jawaban">
                                    <span></span>
                                    Jawaban Singkat
                                </label>
                            </div>
                            <!-- <span class="form-text text-muted">Pilih jika profil bertipe isian.</span> -->
                            <hr>
                            <div class="radio-inline">
                                <label class="radio radio-md">
                                    <input type="radio" name="jenis_jawaban" id="custom" value="1" class="jawaban">
                                    <span></span>
                                    Dengan Pilihan Ganda
                                </label>
                            </div>
                            <!-- <span class="form-text text-muted">Pilih jika profil bertipe pilihan.</span> -->
                        </div>
                    </div>


                    <div class="form-group row" id="inputan" style="display:none">
                        <label class="col-sm-3 col-form-label font-weight-bold">Pilih Type Data <span
                                style="color:red;">*</span></label>
                        <div class="col-sm-9">
                            <select class="form-control" name="type_data" id="type_data">
                                <option value="">Please Select</option>
                                <option value="text">TEXT</option>
                                <option value="number">NUMBER</option>
                            </select>
                        </div>
                    </div>



                    <div name="pilgan" id="pilgan" style="display:none">

                        <h5 class="text-primary">Pilihan Jawaban</h5>
                        <hr class="mb-5">

                        <div class="control-group after-add-more">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label font-weight-bold">Pilihan Jawaban <span
                                        style="color:red;">*</span></label>
                                <div class="col-sm-8">
                                    <input type="text" name="pilihan_jawaban[]" class="form-control"
                                        placeholder="Masukkan Pilihan Jawaban . . .">
                                </div>
                                <div class="input-group-addon col-sm-1">
                                    <button class="btn btn-success add-more" type="button">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- class hide membuat form disembunyikan  -->
                        <!-- hide adalah fungsi bootstrap 3, klo bootstrap 4 pake invisible  -->
                        <div class="copy" style="display:none">
                            <div class="control-group row mb-7">
                                <label class="col-sm-3 col-form-label font-weight-bold">Pilihan Jawaban <span
                                        style="color:red;">*</span></label>
                                <div class="col-sm-8">
                                    <input type="text" name="pilihan_jawaban[]" class="form-control"
                                        placeholder="Masukkan Pilihan Jawaban . . .">
                                </div>
                                <div class="input-group-addon col-sm-1">
                                    <button class="btn btn-danger remove" type="button">
                                        <i class="fas fa-trash"></i></button>
                                </div>
                            </div>
                        </div>


                        <br>


                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label font-weight-bold">Dengan Isian Lainnya
                                <span style="color:red;">*</span></label>
                            <div class="col-9 col-form-label">
                                <div class="radio-inline">
                                    <label class="radio radio-md">
                                        <input type="radio" name="opsi_lainnya" class="opsi_lainnya" value="1">
                                        <span></span>
                                        Ya
                                    </label>
                                    <label class="radio radio-md">
                                        <input type="radio" name="opsi_lainnya" class="opsi_lainnya" value="">
                                        <span></span>
                                        Tidak
                                    </label>
                                </div>
                                <span class="form-text text-muted">Jika profil yang anda tanyakan menggunakan
                                    pilihan jawaban lainnya maka pilih <b>Ya</b> jika tidak maka pilih
                                    <b>Tidak</b>.</span>
                            </div>
                        </div>

                    </div>


                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label font-weight-bold">Wajib Diisi <span
                                style="color:red;">*</span></label>
                        <div class="col-sm-9">
                            <select class="form-control" name="is_required" id="is_required" required>
                                <option value="">Please Select</option>
                                <option value="1">Ya</option>
                                <option value="2">Tidak</option>
                            </select>
                        </div>
                    </div>

                    
                    <br>
                    <div class="text-right">
                        @php
                        echo
                        anchor(base_url().$ci->session->userdata('username').'/'.$ci->uri->segment(2).'/profil-responden-survei',
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
<!-- <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js">
</script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<script type="text/javascript">
$(document).ready(function() {

    var maxGroup = 50;

    $(".add-more").click(function() {
        if ($('body').find('.after-add-more').length < maxGroup) {
            var html = '<div class="control-group after-add-more">' + $(".copy").html() +
                '</div>';
            $('body').find('.after-add-more:last').after(html);
        } else {
            alert('Maximum ' + maxGroup + ' groups are allowed.');
        }
    });

    // saat tombol remove dklik control group akan dihapus 
    $("body").on("click", ".remove", function() {
        $(this).parents(".control-group").remove();
    });

});
</script>

<script type="text/javascript">
$(function() {
    $(":radio.jawaban").click(function() {

        if ($(this).val() == 1) {
            $("#type_data").removeAttr('required');
            $(".opsi_lainnya").prop('required', true);
            $("#inputan").hide();
            $("#pilgan").show();

        } else {
            $(".opsi_lainnya").removeAttr('required');
            $("#type_data").prop('required', true);
            $("#pilgan").hide();
            $("#inputan").show();
        }
    });
});
</script>


@endsection