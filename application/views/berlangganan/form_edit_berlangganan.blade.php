@extends('include_backend/template_backend')

@php 
	$ci = get_instance();
@endphp

@section('style')

@endsection

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header font-weight-bold">
            {{ $title }}
        </div>
        <div class="card-body">
            {!! form_open($form_action); !!}
            {!! validation_errors(); !!}

            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Paket Langganan <span class="text-danger">*</span></label>
                <div class="col-sm-9">
                    {!! form_dropdown($id_paket); !!}
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                <div class="col-sm-9">
                    {!! form_dropdown($id_metode_pembayaran); !!}
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Tanggal Mulai Berlangganan <span class="text-danger">*</span></label>
                <div class="col-sm-9">
                    {!! form_input($tanggal_mulai); !!}
                </div>
            </div>

            <div class="text-right mt-3 mb-3">
                {!! anchor($ci->session->userdata('urlback'), 'Batal', ['class'=>'btn btn-light-primary font-weight-bold shadow-lg']); !!}
                <button type="submit" class="btn btn-primary font-weight-bold shadow-lg">Simpan</button>
            </div>

            {!! form_close(); !!}

        </div>
    </div>

    <div class="mt-5 mb-5">
        <div id="informasi_paket"></div>
    </div>
    
</div>
@endsection

@section('javascript')
<script>
$( function() {

    $.ajaxSetup({
        type: "POST",
        url: "{{ base_url() }}berlangganan/get-detail-ajax",
        cache: false,
    });

    $("#id_paket").change(function() {
        var value_res = $(this).val();
        if (value_res) {
            $.ajax({
                data: {
                    modul: 'get_paket',
                    id: value_res
                },
                success: function(respond) {
                    $("#informasi_paket").html(respond);
                    $("#informasi_paket").fadeIn("fast");
                }
            })
        }
    });

});
</script>
@endsection