@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')
<link href="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.css"
    rel="stylesheet" type="text/css" />
@endsection

@section('content')

<div class="container-fluid">
    <div class="card">
        <div class="card-header bg-secondary font-weight-bold">
            {{ $title }}
        </div>
        <div class="card-body">
            @php
            //$user_anak = $ci->db->get_where('users', ['id_parent_induk' => $data_user->is_parent]);
            @endphp
            <label for="" class="font-weight-bold">Pilih Instansi</label>
            <select name="akun_anak" id="akun_anak" class="form-control kt_select2_1">
                <option value="">Please Select</option>
                @foreach ($user_anak->result() as $value)
                    <option value="{{ $value->id }}">{{ $value->first_name }} {{ $value->last_name }}</option>
                @endforeach
            </select>

            <div id="progress-table"></div>
                
        </div>
    </div>
</div>

@endsection

@section('javascript')
<script src="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.js"></script>
<script>
"use strict";
// Class definition

var KTClipboardDemo = function() {

    // Private functions
    var demos = function() {
        // basic example
        new ClipboardJS('[data-clipboard=true]').on('success', function(e) {
            e.clearSelection();
            // alert('Copied!');
            toastr["success"]('Link berhasil dicopy, Silahkan paste di browser anda sekarang.');
        });
    }

    return {
        // public functions
        init: function() {
            demos();
        }
    };
}();

jQuery(document).ready(function() {
    KTClipboardDemo.init();
});
</script>

<script>
	$('.kt_select2_1').select2({
	placeholder: "Pilih Instansi"
	});

	$(document).ready(function(){
		
		loadData();
		
		$("#akun_anak").change(function(){
			loadData()
		});
	});

	function loadData()
	{
		var akun_anak = $("#akun_anak").val();
		$.ajax({
		url:"{{ base_url() . 'link-survey-surveyor-induk/proses' }}",
		data:"akun_anak=" + akun_anak,
		success: function(html)
		{
			$("#progress-table").html(html);
		}
		});
	}

</script>
@endsection