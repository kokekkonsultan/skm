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
                <div class="col-sm-6 text-left">
                    @php
                    echo anchor(base_url().'profil-responden-kuesioner', '<i class="fa fa-arrow-left"></i> Kembali',
                    ['class'=>'btn
                    btn-light-primary btn-sm
                    font-weight-bold']);
                    @endphp
                </div>
                <div class="col-sm-6 text-right">
                    <a class="btn
                    btn-primary
                    btn-sm font-weight-bold" href="" data-toggle="modal"
                        data-target="#klasifikasi<?php echo $ci->uri->segment(3) ?>"><i class="fa fa-plus"></i>
                        Tambah Profile
                        Kuesioner</a>



                    <!-- @php
                    echo anchor(base_url().'profil-responden-kuesioner/add', '<i class="fa fa-plus"></i> Tambah Profile
                    Kuesioner', ['class' => 'btn
                    btn-primary
                    btn-sm font-weight-bold'])
                    @endphp -->
                </div>
            </div>

            <br>

            <div class="form-group row">
                @php
                echo form_label('<b>Klasifikasi Survei</b> <span style="color: red;">*</span>', '', ['class' =>
                'col-sm-2 col-form-label']);
                @endphp
                <div class="col-sm-10">
                    @php
                    echo form_input($id_klasifikasi_survei);
                    @endphp
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label"><b>Profil Responden </b></label>
                <div class="col-sm-10">

                    <table class="table table-bordered">
                        <tr class="bg-secondary">
                            <td class="font-weight-bold text-center" width="6%">No</td>
                            <td class="font-weight-bold">Nama Profil</td>
                            <td></td>
                        </tr>


                        <?php
                        $no = 1;
                        foreach ($profil_responden->result() as $row) {
                        ?>
                        <tr>
                            <td class="text-center"><?php echo $no++ ?></td>
                            <td><?php echo $row->nama_mst_profil_responden ?></td>
                            <td>
                                <a class="badge badge-primary"
                                    href="<?php echo base_url() . 'profil-responden-kuesioner/delete/' . $row->id_profil ?>"
                                    onclick="return confirm('Are you sure delete this data?')"><i class="fa fa-trash"
                                        style="color: white; font-size:12px;"></i>
                                    Delete</a>
                            </td>
                        </tr>
                        <?php
                        }
                        ?>
                    </table>
                </div>
            </div>

            @php
            echo form_close();
            @endphp

        </div>
    </div>
</div>
@include("profil_responden_kuesioner/form_add")

@endsection

@section('javascript')
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
@endsection