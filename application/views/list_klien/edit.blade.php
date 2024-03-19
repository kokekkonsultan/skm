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
                    <br>
                    <?php echo form_open(base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/pertanyaan-kualitatif/edit/' . $ci->uri->segment(5)); ?>


                    <div class="row">
                        <label class="col-sm-3 col-form-label
                        font-weight-bold">Isi Pertanyaan <span style="color: red;">*</span></label>
                        <div class="col-sm-9">
                            <textarea class="form-control" type="text" name="isi_pertanyaan" rows="4" id="isi_pertanyaan" placeholder="Isikan Pertanyaan Kualitatif ..." autofocus required><?php echo $kualitatif->isi_pertanyaan ?></textarea>
                        </div>
                    </div>
                    <br>

                    <div class="row">
                        <label class="col-sm-3 col-form-label
                        font-weight-bold">Status Pertanyaan <span style="color: red;">*</span></label>
                        <div class="col-sm-9">
                            <select class="form-control" id="is_active" name="is_active" value="<?php echo set_value('is_active'); ?>">
                                <option>Please Select</option>
                                <option value='1' <?php if ($kualitatif->is_active == "1") {
                                                        echo "selected";
                                                    } ?>>Aktif</option>
                                <option value='2' <?php if ($kualitatif->is_active == "2") {
                                                        echo "selected";
                                                    } ?>>Tidak Aktif</option>
                            </select>
                        </div>
                    </div>
                    </br>
                    </br>

                    <div class="text-right">
                        @php
                        echo
                        anchor(base_url().$ci->session->userdata('username').'/'.$ci->uri->segment(2).'/pertanyaan-kualitatif',
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

@endsection