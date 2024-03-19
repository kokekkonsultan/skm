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

                    <?php echo form_open(base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/pertanyaan-kualitatif-survey/edit/' . $ci->uri->segment(5)); ?>

                    <p>
                        <label>Pertanyaan :</label>
                        <textarea class="form-control" type="text" name="isi_pertanyaan" id="isi_pertanyaan"><?php echo $kualitatif->isi_pertanyaan ?></textarea>
                    </p>

                    <p>
                        <label>Status :</label>
                        <select class="form-control" id="is_active" name="is_active" value="<?php echo set_value('is_active'); ?>">
                            <option>Please Select</option>
                            <option value='1' <?php if ($kualitatif->is_active == "1") {
                                                    echo "selected";
                                                } ?>>Aktif</option>
                            <option value='2' <?php if ($kualitatif->is_active == "2") {
                                                    echo "selected";
                                                } ?>>Tidak Aktif</option>
                        </select>

                    </p>
                    </br>
                    <p>
                        @php
                        echo
                        anchor(base_url().$ci->session->userdata('username').'/'.$ci->uri->segment(2).'/pertanyaan-survey',
                        'Cancel', ['class' => 'btn btn-light-primary font-weight-bold'])
                        @endphp
                        <?php echo form_submit('submit', 'Update', ['class' => 'btn btn-primary font-weight-bold']); ?>
                    </p>

                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section ('javascript')

@endsection