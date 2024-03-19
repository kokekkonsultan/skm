@extends('include_backend/template_backend')

@php 
	$ci = get_instance();
@endphp

@section('style')
<link href="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />

<style>
	.content{
		width: 100%;
	 	padding: 5px;
	 	margin: 0 auto;
	}
	.content span{
		width: 250px;
	}
	.dz-message{
		text-align: center;
		font-size: 28px;
	}
	</style>
	<script>
		// Add restrictions
		Dropzone.options.fileupload = {
		    acceptedFiles: 'image/*',
		    maxFilesize: 1 // MB
		};
	</script>
@endsection

@section('content')

<div class="container-fluid">
	<h3>Buat Artikel</h3>

{!! form_open_multipart($form_action); !!}

	<div class="card mt-5">
		<div class="card-header font-weight-bold">
			Isi Artikel
		</div>
		<div class="card-body">
			
			<div class="form-group row">
				<label for="" class="col-sm-2 col-form-label">Judul artikel <span class="text-danger">*</span></label>
				<div class="col-sm-10">
					{!! form_input($title); !!}
				</div>
			</div>

			<div class="form-group row">
				<label for="" class="col-sm-2 col-form-label">Kategori artikel <span class="text-danger">*</span></label>
				<div class="col-sm-10">
					{!! form_dropdown($id_article_category); !!}
				</div>
			</div>

			<div class="form-group row">
				<label for="" class="col-sm-2 col-form-label">Gambar Utama <span class="text-danger">*</span></label>
				<div class="col-sm-10">
					{!! form_error('main_picture'); !!}

					{!! form_upload($main_picture); !!}
				</div>
			</div>

			<div class="form-group row">
				<label for="" class="col-sm-2 col-form-label">Isi konten artikel</label>
				<div class="col-sm-10">
					{!! form_textarea($content_value); !!}

					<a href="javascript:void(0)" class="text-primary mt-3" onclick="show_modal()">Upload gambar atau foto</a>

				</div>
			</div>

			<div class="form-group row">
				<label for="" class="col-sm-2 col-form-label">Nama lengkap penulis artikel <span class="text-danger">**</span></label>
				<div class="col-sm-10">
					{!! form_input($alias_name_writter); !!}
				</div>
			</div>


		</div>
	</div>

	<div class="card mt-5">
		<div class="card-header font-weight-bold">
			Pengaturan SEO
		</div>
		<div class="card-body">
			
			<div class="form-group row">
				<label for="" class="col-sm-2 col-form-label">SEO Title <span class="text-danger">**</span></label>
				<div class="col-sm-10">
					{!! form_input($seo_title); !!}
				</div>
			</div>

			<div class="form-group row">
				<label for="" class="col-sm-2 col-form-label">SEO Description <span class="text-danger">**</span></label>
				<div class="col-sm-10">
					{!! form_input($seo_description); !!}
				</div>
			</div>

			<div class="form-group row">
				<label for="" class="col-sm-2 col-form-label">SEO Keywords <span class="text-danger">**</span></label>
				<div class="col-sm-10">
					{!! form_input($seo_keywords); !!}
				</div>
			</div>

		</div>
	</div>

	<div class="mt-5 mb-5 text-right">
		<a href="{{ base_url() }}article-post" class="btn btn-light-primary font-weight-bold shadow">Batal</a>
		<button type="submit" name="submit" class="btn btn-primary font-weight-bold shadow">Simpan</button>
	</div>

{!! form_close(); !!}

</div>


<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Upload Gambar/ Foto</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <i aria-hidden="true" class="ki ki-close"></i>
        </button>
      </div>
      <div class="modal-body">

<div class='content'>
	   	<form action="{{ base_url() }}image-upload/process-upload" class="dropzone" id='fileupload'>
	   	</form> 
	</div>

	<div class="text-right mb-5">
        <button class="btn btn-secondary font-weight-bold" onclick="reload_table()">Refresh</button>
    </div>
<table id="table" class="display table" style="width:100%">
		        <thead>
		            <tr>
		                <th>No</th>
		                <th>Nama File</th>
		                <th>Link Gambar/ Foto</th>
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
</div>

@endsection

@section('javascript')
<script src="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/tinymce/tinymce.bundle.js"></script>
<script src="{{ TEMPLATE_BACKEND_PATH }}js/pages/crud/forms/editors/tinymce.js"></script>

<script>
// Class definition

var KTTinymce = function () {
    // Private functions
    var demos = function () {
        tinymce.init({
            selector: '#content_value_add',
            menubar: false,
            toolbar: ['styleselect fontselect fontsizeselect',
                'undo redo | cut copy paste | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | outdent indent | blockquote subscript superscript | advlist | autolink | link image |  code'],
            plugins : 'advlist autolink link image lists charmap print preview code'
        });
    }

    return {
        // public functions
        init: function() {
            demos();
        }
    };
}();

// Initialization
jQuery(document).ready(function() {
    KTTinymce.init();
});
</script>

<script src="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.js"></script>
<script src="{{ base_url() }}assets/vendor/dropzone/dropzone.js"></script>
<script src="{{ base_url() }}assets/vendor/dropzone/build.js"></script>

<script>
var table;
$(document).ready(function() {

    table = $('#table').DataTable({ 

        "processing": true, 
        "serverSide": true, 
        "order": [], 
         
        "ajax": {
            "url": "{{ base_url() }}image-upload/ajax-list",
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

function show_modal()
{
	$('#exampleModal').modal('show');
}

function showDetail(id)
{
    $.ajax({
        type: "post",
        url: "{{ base_url() }}image-upload/get-detail",
        data: "id="+id,
        dataType: "html",
        success: function (response) {
            $('#modalDetail').modal('show');
            $('.modal-title').text('View');

            $('#bodyModalDetail').empty();
            $('#bodyModalDetail').append(response);
        }
    });
}

function delete_data(id) {

    Swal.fire({
        title: 'Apakah anda yakin?',
        text: "Anda akan menghapus gambar ini ?",
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
                url: "{{ base_url() }}image-upload/ajax-delete/" + id,
                type: "POST",
                dataType: "JSON",
                success: function(data) {

                    reload_table();

                    toastr["success"]('Gambar berhasil dihapus.');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error deleting data');
                }
            });


        }
    })
}
</script>
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
@endsection