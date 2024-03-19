@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')

@endsection

@section('content')
<div class=" container mt-5">

    <div id="response-list-data-terakhir-berlangganan">
        <div align="center">
            <div class="ajax-loader"></div>
        </div>
    </div>

    <div id="response-list-data-berlangganan">
        <div align="center"><br><br>
            <div class="ajax-loader"></div>
        </div>
    </div>

</div>

@endsection

@section('javascript')

<script>
$(function() {

    $.ajax({
        type: "GET",
        url: "{{ base_url() }}{{ $ci->uri->segment(1) }}/info-berlangganan/data-berlangganan",
        dataType: "html",
        success: function(response) {
            $("#response-list-data-berlangganan").html(response);
        }

    });

});

$(function() {

    $.ajax({
        type: "GET",
        url: "{{ base_url() }}{{ $ci->uri->segment(1) }}/info-berlangganan/data-terakhir-berlangganan",
        dataType: "html",
        success: function(response) {
            $("#response-list-data-terakhir-berlangganan").html(response);
        }

    });

});
</script>
@endsection