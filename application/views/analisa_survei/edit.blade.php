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

                    <?php echo form_open(base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/analisa-survei/edit/' . $ci->uri->segment(5)); ?>

                    <span class="text-danger">{!! validation_errors() !!}</span>
                    <br>


                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label font-weight-bold">Faktor-faktor Yang Mempengaruhi <span style="color: red;">*</span></label>
                        <div class="col-sm-9">
                            @php
                            echo form_textarea($faktor_penyebab);
                            @endphp
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label font-weight-bold">Rencana TIndak Lanjut <span style="color: red;">*</span></label>
                        <div class="col-sm-9">
                            @php
                            echo form_textarea($rencana_perbaikan);
                            @endphp
                        </div>
                    </div>


                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label
                        font-weight-bold">Waktu <span style="color: red;">*</span></label>

                        <div class="input-group input-append date col-sm-9" id="datepicker" data-date="{{ date('m',strtotime(substr($waktu_analisa, 0, -5))) }}-{{ date('Y',strtotime(substr($waktu_analisa, 0, -2))) }}"
                            data-date-format="mm-yyyy">
                            @php
                            echo form_input($waktu);
                            @endphp
                            <span class="add-on"><i class="icon-th"></i></span>
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="la la-calendar-check-o"></i></span>
                            </div>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label
                        font-weight-bold">Penanggung Jawab <span style="color: red;">*</span></label>
                        <div class="col-sm-9">
                            @php
                            echo form_input($penanggung_jawab);
                            @endphp
                        </div>
                    </div>



                    <div class="text-right">
                        @php
                        echo
                        anchor(base_url().$ci->session->userdata('username').'/'.$ci->uri->segment(2).'/analisa-survei/'.$current->id_layanan_survei,
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
<script src="https://cdn.ckeditor.com/ckeditor5/34.2.0/classic/ckeditor.js"></script>
<script>
$("#datepicker").datepicker({
    format: "MM yyyy",
    viewMode: "months",
    minViewMode: "months"
});
</script>

<script>
ClassicEditor
    .create(document.querySelector('#saran_masukan'))
    .then(editor => {
        console.log(editor);
    })
    .catch(error => {
        console.error(error);
    });
</script>

<script>
ClassicEditor
    .create(document.querySelector('#rencana_perbaikan'))
    .then(editor => {
        console.log(editor);
    })
    .catch(error => {
        console.error(error);
    });
</script>

<script>
ClassicEditor
    .create(document.querySelector('#faktor_penyebab'))
    .then(editor => {
        console.log(editor);
    })
    .catch(error => {
        console.error(error);
    });
</script>

<script>
ClassicEditor
    .create(document.querySelector('#kegiatan'))
    .then(editor => {
        console.log(editor);
    })
    .catch(error => {
        console.error(error);
    });
</script>
@endsection