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

                    <?php echo form_open(base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/pertanyaan-unsur-survey/edit/' . $ci->uri->segment(5)); ?>
                    @php
                    echo validation_errors();
                    @endphp


                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label font-weight-bold">Unsur Pelayanan Dari <span style="color: red;">*</span>
                        </label>
                        <div class="col-sm-10">
                            @php
                            echo form_input($id_unsur_pelayanan);
                            @endphp
                        </div>
                    </div>


                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label font-weight-bold">Isi Pertanyaan <span style="color: red;">*</span>
                        </label>
                        <div class="col-sm-10">
                            @php
                            echo form_textarea($isi_pertanyaan_unsur);
                            @endphp
                        </div>
                    </div>

                    <br>

                    <?php
                    $no = 1;
                    foreach ($nama_kategori_unsur as $row) {
                    ?>
                        <input type="text" class="form-control" id="id_kategori" name="id_kategori[]" value="<?php echo $row->id; ?>" hidden>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label font-weight-bold">Pilihan Jawaban <?php echo $no++; ?>
                            </label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="nama_kategori_unsur_pelayanan" name="nama_kategori_unsur_pelayanan[]" value="<?php echo $row->nama_kategori_unsur_pelayanan; ?>">
                            </div>
                        </div>
                    <?php
                    }
                    ?>

                    <div class="text-right">
                        @php
                        echo
                        anchor(base_url().$ci->session->userdata('username').'/'.$ci->uri->segment(2).'/pertanyaan-survey',
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


@endsection