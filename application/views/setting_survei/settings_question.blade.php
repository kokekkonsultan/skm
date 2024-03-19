@extends('include_backend/template_no_aside')

@php
$ci = get_instance();
@endphp

@section('style')

@endsection

@section('content')

<div class="container-fluid">

    @include("include_backend/partials_no_aside/_inc_menu_repository")
    <br>

    <div class="row justify-content-md-center">
        <div class="col col-lg-10">
            @include('setting_survei/menu_settings')<br>
            @include('include_template/partials/_inc_menu_settings')
            <br>
            <div class="card border-primary mb-3" data-aos="fade-down">
                <div class="card-body">
                    <div class="alert alert-secondary" role="alert">
                        A simple secondary alert—check it out!
                    </div>

                    <?php echo form_open(base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/settings-question'); ?>

                    </br>

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label font-weight-bold">Status Pertanyaan</label>
                        <div class="col-sm-8">
                            <select class="form-control" id="is_question" name="is_question" value="<?php echo set_value('is_question'); ?>">
                                <option value="1" <?php if ($current->is_question == "1") {
                                                        echo "selected";
                                                    } ?>>Bisa di Ubah</option>
                                <option value="2" <?php if ($current->is_question == "2") {
                                                        echo "selected";
                                                    } ?>>Tidak Bisa di Ubah</option>
                            </select>
                        </div>
                    </div>
                    <br>

                    <div class="text-right">
                        <?php
                        echo anchor(base_url() . 'survey/' . $ci->uri->segment(2) . '/survey-not-question', '<i class="fas fa-solid fa-eye"></i> Lihat Tampilan Survey Jika Status Pertanyaan di Tutup', ['class' => 'btn btn-light-primary font-weight-bold', 'target' => '_blank']);
                        ?>

                        <?php echo form_submit('submit', 'Update', ['class' => 'btn btn-light-primary font-weight-bold']); ?>
                    </div>

                    <?php echo form_close(); ?>



                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')

@endsection