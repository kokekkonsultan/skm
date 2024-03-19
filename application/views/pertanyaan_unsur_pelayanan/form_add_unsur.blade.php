@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')

@endsection

@section('content')
<div class="container">
    <div class="card mb-5" data-aos="fade-down">
        <div class="card-header bg-secondary font-weight-bold">
            Unsur Pelayanan
        </div>
        <div class="card-body">

            {!! form_open($form_action) !!}
            {!! validation_errors() !!}

            {{-- <div class="form-group row">
                {!! form_label('<b>Jenis Pelayanan</b> <span style="color: red;">*</span>', '', ['class' => 'col-sm-2 col-form-label']) !!}
                <div class="col-sm-10">
                    {!! form_dropdown($id_jenis_pelayanan) !!}
                </div>
            </div> --}}

            <div class="form-group row">
                <label class="col-sm-2 col-form-label fw-bold"><b>Sub Unsur Pelayanan</b> <span
                        style="color: red;">*</span></label>
                <div class="col-sm-10">
                    <div>
                        <label><input type="radio" name="customisasi" id="default" value="2" class="customisasi">&nbsp
                            Tanpa Sub Unsur</label><br>
                    </div>
                    <div>
                        <label><input type="radio" name="customisasi" id="custom" value="1" class="customisasi">&nbsp
                            Dengan Sub Unsur</label><br>
                    </div>
                    <div class="mb-3">
                        {!! form_dropdown($id_parent) !!}
                    </div>
                </div>
                </label>
            </div>

            <div class="form-group row">
                {!! form_label('<b>Unsur Pelayanan</b> <span style="color: red;">*</span>', '', ['class' => 'col-sm-2
                col-form-label']) !!}
                <div class="col-sm-10">
                    {!! form_input($nama_unsur_pelayanan) !!}
                </div>
            </div>


            <div class="text-right mt-3 mb-3">
                {!! anchor($ci->session->userdata('urlback_second'), 'Batal', ['class'=>'btn btn-light-primary
                font-weight-bold shadow']) !!}
                <button type="submit" class="btn btn-primary font-weight-bold shadow">Simpan</button>
            </div>

            {!! form_close() !!}

        </div>
    </div>
</div>
@endsection

@section('javascript')

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
<script type="text/javascript">
$(function() {
    $(":radio.customisasi").click(function() {
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