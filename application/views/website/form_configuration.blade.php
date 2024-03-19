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
			SEO Website Configuration
		</div>
		<div class="card-body">
			
			<form action="{{ base_url() }}update-website-configuration" class="form_website_configuration">

			  <div class="form-group row">
			    <label for="" class="col-sm-2 col-form-label">SEO Default Title <span class="text-danger">*</span></label>
			    <div class="col-sm-10">
			      <input type="text" name="meta_title" class="form-control" value="{{ $web_config->meta_title }}">
			    </div>
			  </div>

			  <div class="form-group row">
			    <label for="" class="col-sm-2 col-form-label">SEO Description <span class="text-danger">*</span></label>
			    <div class="col-sm-10">
			      <textarea name="meta_description" class="form-control">{{ $web_config->meta_description }}</textarea>
			    </div>
			  </div>

			  <div class="form-group row">
			    <label for="" class="col-sm-2 col-form-label">SEO Keywords <span class="text-danger">*</span></label>
			    <div class="col-sm-10">
			      <input type="text" name="meta_keywords" class="form-control" value="{{ $web_config->meta_keywords }}">
			    </div>
			  </div>

			  <div class="form-group row">
			    <label for="" class="col-sm-2 col-form-label">SEO Copyright <span class="text-danger">*</span></label>
			    <div class="col-sm-10">
			      <input type="text" name="meta_copyright" class="form-control" value="{{ $web_config->meta_copyright }}">
			    </div>
			  </div>

			  <div class="form-group row">
			    <label for="" class="col-sm-2 col-form-label">SEO Author <span class="text-danger">*</span></label>
			    <div class="col-sm-10">
			      <input type="text" name="meta_author" class="form-control" value="{{ $web_config->meta_author }}">
			    </div>
			  </div>

			  <div class="form-group row">
			    <label for="" class="col-sm-2 col-form-label">SEO Language <span class="text-danger">*</span></label>
			    <div class="col-sm-10">
			      <input type="text" name="meta_language" class="form-control" value="{{ $web_config->meta_language }}">
			    </div>
			  </div>

			  		<div class="mt-5 text-right">
						<button type="submit" class="btn btn-primary font-weight-bold tombolSimpanPengaturanWeb shadow">Simpan</button>
					</div>

			</form>

		</div>
	</div>

	<div class="mt-5">
		<div class="card">
			<div class="card-header font-weight-bold">
				Halaman Depan
			</div>
			<div class="card-body">
				
				<form action="{{ base_url() }}update-home-configuration" class="form_home_configuration">
				
			  <div class="form-group row">
			    <label for="" class="col-sm-2 col-form-label">Website Title <span class="text-danger">*</span></label>
			    <div class="col-sm-10">
			      <input type="text" name="website_title" class="form-control" value="{{ $home_config->website_title }}">
			    </div>
			  </div>

			  <div class="form-group row">
			    <label for="" class="col-sm-2 col-form-label">Website Description <span class="text-danger">*</span></label>
			    <div class="col-sm-10">
			      <input type="text" name="website_description" class="form-control" value="{{ $home_config->website_description }}">
			    </div>
			  </div>

			  <div class="form-group row">
			    <label for="" class="col-sm-2 col-form-label">Object Title <span class="text-danger">*</span></label>
			    <div class="col-sm-10">
			      <input type="text" name="website_object_title" class="form-control" value="{{ $home_config->website_object_title }}">
			    </div>
			  </div>

			  <div class="form-group row">
			    <label for="" class="col-sm-2 col-form-label">Object 1 <span class="text-danger">*</span></label>
			    <div class="col-sm-10">
			      
			      <textarea id="object-1" name="website_object_1" class="tox-target">{{ $home_config->website_object_1 }}</textarea>

			    </div>
			  </div>

			  <div class="form-group row">
			    <label for="" class="col-sm-2 col-form-label">Object 2 <span class="text-danger">*</span></label>
			    <div class="col-sm-10">
			      
			      <textarea id="object-2" name="website_object_2" class="tox-target">{{ $home_config->website_object_2 }}</textarea>

			    </div>
			  </div>

			  <div class="form-group row">
			    <label for="" class="col-sm-2 col-form-label">Object 3 <span class="text-danger">*</span></label>
			    <div class="col-sm-10">
			      
			      <textarea id="object-3" name="website_object_3" class="tox-target">{{ $home_config->website_object_3 }}</textarea>

			    </div>
			  </div>

			  <div class="form-group row">
			    <label for="" class="col-sm-2 col-form-label">Object 4 <span class="text-danger">*</span></label>
			    <div class="col-sm-10">
			      
			      <textarea id="object-4" name="website_object_4" class="tox-target">{{ $home_config->website_object_4 }}</textarea>

			    </div>
			  </div>

			  <div class="mt-5 text-right">
						<button type="submit" class="btn btn-primary font-weight-bold tombolSimpanPengaturanHome shadow">Simpan</button>
					</div>


				</form>
			</div>
		</div>
	</div>

	<div class="mt-5">
		<div class="card">
			<div class="card-header font-weight-bold">
				Reseller Area Configuration
			</div>
			<div class="card-body">

				<form action="#" id="form_edit_reseller_config">

					<input type="hidden" name="id">
					<input type="hidden" value="" name="instansiasi_content_page" id="instansiasi_content_page">

				  <div class="form-group row">
				    <label for="" class="col-sm-2 col-form-label">SEO Default Title <span class="text-danger">*</span></label>
				    <div class="col-sm-10">
				    	<input type="text" name="meta_title_reseller" class="form-control" value="">
				    </div>
				  </div>

				  <div class="form-group row">
				    <label for="" class="col-sm-2 col-form-label">SEO Description <span class="text-danger">*</span></label>
				    <div class="col-sm-10">
				      <textarea name="meta_description_reseller" class="form-control"></textarea>
				    </div>
				  </div>

				  <div class="form-group row">
				    <label for="" class="col-sm-2 col-form-label">SEO Keywords <span class="text-danger">*</span></label>
				    <div class="col-sm-10">
				      <input type="text" name="meta_keywords_reseller" class="form-control" value="">
				    </div>
				  </div>

				

				  {{-- <div class="form-group row">
				    <label for="" class="col-sm-2 col-form-label">Gambar Utama Halaman <span class="text-danger">*</span></label>
				    <div class="col-sm-10">
				      <input type="file" class="form-control" id="inputPassword" placeholder="">
				    </div>
				  </div> --}}

				  <div class="form-group row">
				    <label for="" class="col-sm-2 col-form-label">Konten Halaman <span class="text-danger">*</span></label>
				    <div class="col-sm-10">
				    	<input type="text" name="content_page_reseller" id="content_page_reseller" class="form-control" value="">
				    </div>
				  </div>


					{{-- <div class="mt-5 text-right">
						<button type="submit" class="btn btn-primary font-weight-bold tombolSimpanPengaturanReseller shadow">Simpan</button>
					</div> --}}

				</form>

				<div class="mt-5 text-right">
					<button type="button" id="btnSaveEdit" onclick="save_edit_reseller()" class="btn btn-primary font-weight-bold">Simpan</button>
				</div>
			</div>
		</div>
	</div>

</div>


@endsection

@section('javascript')
<script src="{{ base_url() }}assets/vendor/ckeditor/ckeditor.js"></script>

<script src="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/tinymce/tinymce.bundle.js"></script>
<script src="{{ TEMPLATE_BACKEND_PATH }}js/pages/crud/forms/editors/tinymce.js"></script>

<script>
var KTTinymce = function () {
    // Private functions
    var demos = function () {
        tinymce.init({
            selector: '#object-1',
            menubar: false,
            toolbar: ['styleselect fontselect fontsizeselect',
                'undo redo | cut copy paste | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | outdent indent | blockquote subscript superscript | advlist | autolink |  code'],
            plugins : 'advlist autolink link image lists charmap print preview code'
        });

        tinymce.init({
            selector: '#object-2',
            menubar: false,
            toolbar: ['styleselect fontselect fontsizeselect',
                'undo redo | cut copy paste | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | outdent indent | blockquote subscript superscript | advlist | autolink |  code'],
            plugins : 'advlist autolink link image lists charmap print preview code'
        });

        tinymce.init({
            selector: '#object-3',
            menubar: false,
            toolbar: ['styleselect fontselect fontsizeselect',
                'undo redo | cut copy paste | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | outdent indent | blockquote subscript superscript | advlist | autolink |  code'],
            plugins : 'advlist autolink link image lists charmap print preview code'
        });

        tinymce.init({
            selector: '#object-4',
            menubar: false,
            toolbar: ['styleselect fontselect fontsizeselect',
                'undo redo | cut copy paste | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | outdent indent | blockquote subscript superscript | advlist | autolink |  code'],
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

<script>
CKEDITOR.replace('content_page_reseller', {
    toolbar: [
        ['Bold', 'Italic', 'Underline', 'Strike', 'TextColor', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink', '-','Format','Font','FontSize', '-', 'Source']
    ],
    height: 400,
    enterMode: 1,
    shiftEnterMode: 2
});
</script>



<script>
	$(document).ready(function(e) {

		$('.form_website_configuration').submit(function(e) {

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                dataType: 'json',
                data: $(this).serialize(),
                cache: false,
                beforeSend: function() {
                    $('.tombolSimpanPengaturanWeb').attr('disabled', 'disabled');
                    $('.tombolSimpanPengaturanWeb').html('<i class="fa fa-spin fa-spinner"></i> Sedang diproses');

                    

                },
                complete: function() {
                    $('.tombolSimpanPengaturanWeb').removeAttr('disabled');
                    $('.tombolSimpanPengaturanWeb').html('Simpan');
                },
                error: function(e) {
                    Swal.fire(
                        'Error !',
                        e,
                        'error'
                    )
                },
                success: function(data) {

                    if (data.validasi) {
                        $('.pesan').fadeIn();
                        $('.pesan').html(data.validasi);
                    }
                    if (data.sukses) {

                        toastr["success"]('Data berhasil disimpan');


                    }
                }
            })
            return false;
        });

        $('.form_home_configuration').submit(function(e) {

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                dataType: 'json',
                data: $(this).serialize(),
                cache: false,
                beforeSend: function() {
                    $('.tombolSimpanPengaturanHome').attr('disabled', 'disabled');
                    $('.tombolSimpanPengaturanHome').html('<i class="fa fa-spin fa-spinner"></i> Sedang diproses');

                    

                },
                complete: function() {
                    $('.tombolSimpanPengaturanHome').removeAttr('disabled');
                    $('.tombolSimpanPengaturanHome').html('Simpan');
                },
                error: function(e) {
                    Swal.fire(
                        'Error !',
                        e,
                        'error'
                    )
                },
                success: function(data) {

                    if (data.validasi) {
                        $('.pesan').fadeIn();
                        $('.pesan').html(data.validasi);
                    }
                    if (data.sukses) {

                        toastr["success"]('Data berhasil disimpan');


                    }
                }
            })
            return false;
        });

        $('.form_reseller_area_configuration').submit(function(e) {

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                dataType: 'json',
                data: $(this).serialize(),
                cache: false,
                beforeSend: function() {
                    $('.tombolSimpanPengaturanReseller').attr('disabled', 'disabled');
                    $('.tombolSimpanPengaturanReseller').html('<i class="fa fa-spin fa-spinner"></i> Sedang diproses');

                    

                },
                complete: function() {
                    $('.tombolSimpanPengaturanReseller').removeAttr('disabled');
                    $('.tombolSimpanPengaturanReseller').html('Simpan');
                },
                error: function(e) {
                    Swal.fire(
                        'Error !',
                        e,
                        'error'
                    )
                },
                success: function(data) {

                    if (data.validasi) {
                        $('.pesan').fadeIn();
                        $('.pesan').html(data.validasi);
                    }
                    if (data.sukses) {

                        toastr["success"]('Data berhasil disimpan');


                    }
                }
            })
            return false;
        });



        $.ajax({
	        url: "{{ base_url() }}website-reseller-area-configuration/ajax-edit/2",
	        type: "GET",
	        dataType: "JSON",
	        success: function(data) {

	            $('[name="id"]').val(data.id);
	            $('[name="meta_title_reseller"]').val(data.meta_title);
	            $('[name="meta_description_reseller"]').val(data.meta_description);
	            $('[name="meta_keywords_reseller"]').val(data.meta_keywords);
	            CKEDITOR.instances['content_page_reseller'].setData(data.content_page);
	            

	        },
	        error: function(jqXHR, textStatus, errorThrown) {
	            alert('Error get data from ajax');
	        }
	    });





	});


	function save_edit_reseller() {

		    $('#btnSaveEdit').text('Saving...');
		    $('#btnSaveEdit').attr('disabled', true);

		    var content_page_reseller = CKEDITOR.instances['content_page_reseller'].getData();
		    var instansiasi_content_page = document.getElementById("instansiasi_content_page");
		    instansiasi_content_page.value = content_page_reseller;

		    $.ajax({
		        url: "{{ base_url() }}website-reseller-area-configuration/ajax-save-reseller-config",
		        type: "POST",
		        data: $('#form_edit_reseller_config').serialize(),
		        dataType: "JSON",
		        success: function(data) {

		            if (data.status) {
		                

		                toastr["success"]('Data berhasil disimpan');

		            } else {
		                for (var i = 0; i < data.inputerror.length; i++) {
		                    $('[name="' + data.inputerror[i] + '"]').next().html(data.error_string[i]);
		                }
		            }

		            $('#btnSaveEdit').text('Simpan');
		            $('#btnSaveEdit').attr('disabled', false);


		        },
		        error: function(jqXHR, textStatus, errorThrown) {
		            alert('Error adding / update data');
		            $('#btnSaveEdit').text('Simpan');
		            $('#btnSaveEdit').attr('disabled', false);

		        }
		    });
		}
</script>
@endsection