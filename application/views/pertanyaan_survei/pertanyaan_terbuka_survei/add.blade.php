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

                    <?php echo form_open(base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/pertanyaan-terbuka-survey/add'); ?>

                    </br>

                    <div class="form-group row">
                        @php
                        echo form_label('Unsur Pelayanan Dari <span style="color:red;">*</span>', '', ['class' =>
                        'col-sm-2
                        col-form-label
                        font-weight-bold']);
                        @endphp
                        <div class="col-sm-10">
                            @php
                            echo form_dropdown($id_unsur_pelayanan);
                            @endphp
                        </div>
                    </div>

                    <div class="form-group row">
                        @php
                        echo form_label('Nama Pertanyaan Terbuka <span style="color:red;">*</span>', '', ['class' =>
                        'col-sm-2 col-form-label
                        font-weight-bold']);
                        @endphp
                        <div class="col-sm-10">
                            @php
                            echo form_input($nama_pertanyaan_terbuka);
                            @endphp
                        </div>
                    </div>

                    <div class="form-group row">
                        @php
                        echo form_label('Isi Pertanyaan Terbuka <span style="color:red;">*</span>', '', ['class' =>
                        'col-sm-2 col-form-label
                        font-weight-bold']);
                        @endphp
                        <div class="col-sm-10">
                            @php
                            echo form_textarea($isi_pertanyaan_terbuka);
                            @endphp
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label font-weight-bold">Pilihan Jawaban <span style="color:red;">*</span></label>
                        <div class="col-sm-10">
                            <label>
                                <input type="radio" name="jenis_jawaban" value="2" class="pilihan">
                                Jawaban Singkat
                            </label>
                            <hr>
                            <label>
                                <input type="radio" name="jenis_jawaban" value="1" class="pilihan">
                                Dengan Pilihan Ganda
                            </label>
                        </div>
                        </label>
                    </div>


                    <div name="opsi_1" id="opsi_1" style="display:none">
                        <div class="form-group fieldGroup">
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label"></label>
                                <div class="col-sm-9">
                                    <input type="text" name="pilihan_jawaban[]" class="form-control" placeholder="Masukkan Pilihan Jawaban . . ." />
                                </div>
                                <div class="input-group-addon col-sm-1">
                                    <a href="javascript:void(0)" class="btn btn-light-success addMore"><i class="fas fa-plus"></i></a>
                                </div>
                            </div>
                        </div>

                        <div class="form-group fieldGroupCopy">
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label"></label>
                                <div class="col-sm-9">
                                    <input type="text" name="pilihan_jawaban[]" class="form-control" placeholder="Masukkan Pilihan Jawaban . . ." />
                                </div>
                                <div class="input-group-addon col-sm-1">
                                    <a href="javascript:void(0)" class="btn btn-light-danger remove"><i class="fas fa-trash"></i></a>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label fw-bold font-weight-bold">Dengan Isian </br>
                                Lainnya
                                *</label>
                            <div class="col-sm-10">
                                <label>
                                    <input type="radio" name="opsi_pilihan_jawaban" value="1"> Ya
                                </label>
                                <hr>
                                <label>
                                    <input type="radio" name="opsi_pilihan_jawaban" value="2"> Tidak
                                </label>
                            </div>
                            </label>
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

@section ('javascript')


@endsection