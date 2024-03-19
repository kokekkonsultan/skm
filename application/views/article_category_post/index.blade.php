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
        <button class="btn btn-primary font-weight-bold" onclick="add_data()"></i>Tambah Kategori Artikel</button>
    </div>
	<div class="card">
		<div class="card-header font-weight-bold">
			Kategori Artikel
		</div>
		<div class="card-body">
			<table id="table" class="display table" style="width:100%">
		        <thead>
		            <tr>
		                <th>No</th>
		                <th>Nama Kategori</th>
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

<div class="modal fade" id="modal_form_add" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body form">


                <form id="form" action="#">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Nama Kategori Artikel <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            {!! form_input($category_name) !!}
                            <span class="help-block"></span>
                        </div>
                    </div>

                </form>

            </div>
            <div class="modal-footer">

                <button type="button" id="btnSave" onclick="save_add()" class="btn btn-primary font-weight-bold">Save</button>
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cancel</button>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_form_edit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body form">

                

                <form action="#" id="form_edit">


                    <input type="hidden" value="" name="id" id="id" />

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Nama Kategori Artikel <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            {!! form_input($category_name) !!}
                            <span class="help-block"></span>
                        </div>
                    </div>

                </form>

            </div>
            <div class="modal-footer">

                <button type="button" id="btnSaveEdit" onclick="save_edit()" class="btn btn-primary font-weight-bold">Save</button>
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cancel</button>

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
            "url": "{{ base_url() }}article-category-post/ajax-list",
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

function add_data() {
    $('#form')[0].reset();
    $('.form-control').removeClass('is-invalid');
    $('.help-block').empty();
    $('#modal_form_add').modal('show');
    $('.modal-title').text('Tambah Kategori Artikel');

}

function save_add() {
    $('#btnSave').text('Saving...');
    $('#btnSave').attr('disabled', true);

    var category_name = document.getElementById("category_name").value;

    $.ajax({
        url: "{{ base_url() }}article-category-post/ajax-add",
        type: "POST",
        data: {
        	'category_name': category_name
        },
        dataType: "JSON",
        beforeSend:function(){

        },
        success: function(data) {

            if (data.status) {
                $('#modal_form_add').modal('hide');
                reload_table();

                toastr["success"]('Berhasil menambahkan kategori artikel');

            } else {
                for (var i = 0; i < data.inputerror.length; i++) {
                    $('[name="' + data.inputerror[i] + '"]').next().html(data.error_string[i]);
                }
            }
            $('#btnSave').text('Save');
            $('#btnSave').attr('disabled', false);


        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Error adding / update data');
            $('#btnSave').text('Save');
            $('#btnSave').attr('disabled', false);

        }
    });
}

function edit_data(id) {
    $('#form_edit')[0].reset();
    $('.help-block').empty();

    $.ajax({
        url: "{{ base_url() }}article-category-post/ajax-edit/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data) {

            $('[name="id"]').val(data.id);
            $('[name="category_name"]').val(data.category_name);

            $('#modal_form_edit').modal('show');
            $('.modal-title').text('Edit Paket');

        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Error get data from ajax');
        }
    });
}

function save_edit() {
    $('#btnSaveEdit').text('saving...');
    $('#btnSaveEdit').attr('disabled', true);

    $.ajax({
        url: "{{ base_url() }}article-category-post/ajax-update",
        type: "POST",
        data: $('#form_edit').serialize(),
        dataType: "JSON",
        success: function(data) {

            if (data.status) {
                $('#modal_form_edit').modal('hide');
                reload_table();

                toastr["success"]('Berhasil mengubah kategori artikel');

            } else {
                for (var i = 0; i < data.inputerror.length; i++) {
                    $('[name="' + data.inputerror[i] + '"]').next().html(data.error_string[i]);
                }
            }

            $('#btnSaveEdit').text('Save');
            $('#btnSaveEdit').attr('disabled', false);


        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Error adding / update data');
            $('#btnSaveEdit').text('Save');
            $('#btnSaveEdit').attr('disabled', false);

        }
    });
}

function delete_data(id) {

    Swal.fire({
        title: 'Apakah anda yakin?',
        text: "Anda akan menghapus kategori artikel ini ?",
        type: 'warning',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Oke',
        cancelButtonText: 'Batal',
    }).then((result) => {
        if (result.value) {

        	if (id == 1) {
        		
        		Swal.fire(
                      'Gagal!',
                      'Tidak dapat menghapus kategori default',
                      'error'
                    );

        	} else {

            $.ajax({
                url: "{{ base_url() }}article-category-post/ajax-delete/" + id,
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


        }
    })
}
</script>
@endsection