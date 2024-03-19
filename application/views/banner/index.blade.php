@extends('include_backend/template_backend')

@php 
	$ci = get_instance();
@endphp

@section('style')
<link href="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
@endsection

@section('content')

<div class="container">
	<div class="text-right mb-5">
        <button class="btn btn-secondary font-weight-bold" onclick="reload_table()">Refresh</button>
        <a href="{{ base_url() }}banner/add" class="btn btn-primary font-weight-bold">Tambah Banner</a>
    </div>
	<div class="card">
		<div class="card-header font-weight-bold">
			Slide Banner
		</div>
		<div class="card-body">
			<table id="table" class="display table" style="width:100%">
		        <thead>
		            <tr>
		                <th>No</th>
		                <th>Gambar</th>
		                <th>Nama Banner</th>
		                <th>Tombol Read More</th>
		                <th>Tombol Contact</th>
		                <th>Tampilkan Banner</th>
		                <th></th>
		                <th></th>
		                <th></th>
		            </tr>
		        </thead>
		        <tbody>
		        </tbody>
		    </table>
		</div>
	</div>
</div>

<div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
	  <div class="modal-content">
	    <div class="modal-header">
	      <h5 class="modal-title" id="exampleModalLabel"></h5>
	      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	        <i aria-hidden="true" class="ki ki-close"></i>
	      </button>
	    </div>
	    <div class="modal-body" id="bodyModalDetail">
	      
	    </div>
	  </div>
	</div>
</div>

@endsection

@section('javascript')
<script src="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.js"></script>

<script>

var table;

$(document).ready(function() {

    table = $('#table').DataTable({ 

        "processing": true, 
        "serverSide": true, 
        "order": [], 
         
        "ajax": {
            "url": "{{ base_url() }}banner/ajax-list",
            "type": "POST",
        },

         
        "columnDefs": [
        { 
            "targets": [ 0 ], 
            "orderable": false, 
        },
        ],



    });

    $('#table').on( 'change', '.toggle_dash_1', function () {
    	// alert("TT");
		var mode = $(this).prop('checked');
        var nilai_id = $(this).val();

        $.ajax({
            type: 'POST',
            dataType: 'JSON',
            url: "{{ base_url() }}banner/update-read-more-active-value",
            data: {
                'mode': mode,
                'nilai_id': nilai_id
            },
            success: function(data) {
                var data = eval(data);
                message = data.message;
                success = data.success;

                toastr["success"](message);

            }
        });
	});

	$('#table').on( 'change', '.toggle_1', function () {
		// alert("TT");
		var mode = $(this).prop('checked');
        var nilai_id = $(this).val();

        $.ajax({
            type: 'POST',
            dataType: 'JSON',
            url: "{{ base_url() }}banner/update-read-more-active",
            data: {
                'mode': mode,
                'nilai_id': nilai_id
            },
            success: function(data) {
                var data = eval(data);
                message = data.message;
                success = data.success;

                toastr["success"](message);

            }
        });
	});

	$('#table').on( 'change', '.toggle_dash_2', function () {
    	// alert("TT");
		var mode = $(this).prop('checked');
        var nilai_id = $(this).val();

        $.ajax({
            type: 'POST',
            dataType: 'JSON',
            url: "{{ base_url() }}banner/update-contact-active-value",
            data: {
                'mode': mode,
                'nilai_id': nilai_id
            },
            success: function(data) {
                var data = eval(data);
                message = data.message;
                success = data.success;

                toastr["success"](message);

            }
        });
	});

	$('#table').on( 'change', '.toggle_2', function () {
		// alert("TT");
		var mode = $(this).prop('checked');
        var nilai_id = $(this).val();

        $.ajax({
            type: 'POST',
            dataType: 'JSON',
            url: "{{ base_url() }}banner/update-contact-active",
            data: {
                'mode': mode,
                'nilai_id': nilai_id
            },
            success: function(data) {
                var data = eval(data);
                message = data.message;
                success = data.success;

                toastr["success"](message);

            }
        });
	});

	$('#table').on( 'change', '.toggle_dash_3', function () {
    	// alert("TT");
		var mode = $(this).prop('checked');
        var nilai_id = $(this).val();

        $.ajax({
            type: 'POST',
            dataType: 'JSON',
            url: "{{ base_url() }}banner/update-is-show-value",
            data: {
                'mode': mode,
                'nilai_id': nilai_id
            },
            success: function(data) {
                var data = eval(data);
                message = data.message;
                success = data.success;

                toastr["success"](message);

            }
        });
	});

	$('#table').on( 'change', '.toggle_3', function () {
		// alert("TT");
		var mode = $(this).prop('checked');
        var nilai_id = $(this).val();

        $.ajax({
            type: 'POST',
            dataType: 'JSON',
            url: "{{ base_url() }}banner/update-is-show",
            data: {
                'mode': mode,
                'nilai_id': nilai_id
            },
            success: function(data) {
                var data = eval(data);
                message = data.message;
                success = data.success;

                toastr["success"](message);

            }
        });
	});

});

function reload_table() {
    table.ajax.reload(null, false);
}

function showDetail(id)
{
    $.ajax({
        type: "post",
        url: "{{ base_url() }}banner/get-detail",
        data: "id="+id,
        dataType: "html",
        success: function (response) {
            $('#modalDetail').modal('show');
            $('.modal-title').text('Detail Slide Banner');

            $('#bodyModalDetail').empty();
            $('#bodyModalDetail').append(response);
        }
    });
}

</script>
@endsection