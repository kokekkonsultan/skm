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
                @php
                echo form_label('<b>Jenis Pelayanan</b> <span style="color: red;">*</span>', '', ['class' => 'col-sm-2
                col-form-label']);
                @endphp
                <div class="col-sm-10">
                    @php
                    echo form_dropdown($id_jenis_pelayanan);
                    @endphp
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label fw-bold"><b>Sub Unsur Pelayanan</b> <span
                        style="color: red;">*</span></label>
                <div class="col-sm-10">
                    <div>
                        <input type="radio" name="custom" id="default" value="2" class="custom"
                            {{ $data->is_sub_unsur_pelayanan == '2' ? 'checked' : ''}}><label>&nbsp Tanpa Sub
                            Unsur</label><br>
                    </div>
                    <div>
                        <input type="radio" name="custom" id="custom" value="1" class="custom"
                            {{ $data->is_sub_unsur_pelayanan == '1' ? 'checked' : ''}}><label>&nbsp Dengan Sub
                            Unsur</label><br>
                    </div>
                    <div class="mb-3">
                        @php
                        echo form_dropdown($id_parent);
                        @endphp
                    </div>
                </div>
                </label>
            </div>


            <div class="form-group row">
                @php
                echo form_label('<b>Nomor Unsur</b> <span style="color: red;">**</span>', '', ['class' => 'col-sm-2
                col-form-label']);
                @endphp
                <div class="col-sm-10">
                    @php
                    echo form_input($nomor_unsur);
                    @endphp
                </div>
            </div>

            <div class="form-group row">
                @php
                echo form_label('<b>Unsur Pelayanan</b> <span style="color: red;">*</span>', '', ['class' => 'col-sm-2
                col-form-label']);
                @endphp
                <div class="col-sm-10">
                    @php
                    echo form_input($nama_unsur_pelayanan);
                    @endphp
                </div>
            </div>

            <div class="text-right mt-3 mb-3">
                @php
                echo anchor(base_url().'unsur-pelayanan', 'Batal', ['class'=>'btn btn-light-primary
                font-weight-bold']);
                @endphp
                <button type="submit" name="submit" value="simpan"
                    class="btn btn-primary font-weight-bold">Simpan</button>
            </div>

            @php
            echo form_close();
            @endphp

        </div>
    </div>
</div>
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