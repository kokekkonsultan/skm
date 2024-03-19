@extends('include_backend/template_backend')

@php 
	$ci = get_instance();
@endphp

@section('style')
<link href="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
@endsection

@section('content')

<div class="container-fluid">
	<div class="text-right mb-5">
        <button class="btn btn-secondary font-weight-bold" onclick="reload_table()">Refresh</button>
    </div>
	<div class="card">
		<div class="card-header font-weight-bold">
			Permintaan Reseller
		</div>
		<div class="card-body">
			
			<table id="table" class="display table" style="width:100%">
		        <thead>
		            <tr>
		                <th>No</th>
		                <th>Nama Lengkap</th>
		                <th>Profesi/ Jabatan</th>
		                <th>Tempat bekerja/ Organisasi</th>
		                <th>Tanggal</th>
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
            "url": "{{ base_url() }}reseller-request/ajax-list",
            "type": "POST",
        },

         
        "columnDefs": [
        { 
            "targets": [ 0 ], 
            "orderable": false, 
        },
        ],



    });

});

function reload_table() {
    table.ajax.reload(null, false);
}

function showDetail(id)
{
    $.ajax({
        type: "post",
        url: "{{ base_url() }}reseller-request/get-detail",
        data: "id="+id,
        dataType: "html",
        success: function (response) {
            $('#modalDetail').modal('show');
            $('.modal-title').text('Detail Permintaan Reseller');

            $('#bodyModalDetail').empty();
            $('#bodyModalDetail').append(response);
        }
    });
}

function delete_data(id) {

    Swal.fire({
        title: 'Apakah anda yakin?',
        text: "Anda akan menghapus permintaan reseller ini ?",
        type: 'warning',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Oke',
        cancelButtonText: 'Batal',
    }).then((result) => {
        if (result.value) {

        	$.ajax({
                url: "{{ base_url() }}reseller-request/ajax-delete/" + id,
                type: "POST",
                dataType: "JSON",
                success: function(data) {

                    reload_table();

                    Swal.fire(
                      'Deleted!',
                      'Data berhasil dihapus',
                      'success'
                    );
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error deleting data');
                }
            });


        }
    })
}
</script>
@endsection