@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')

@endsection

@section('content')
<div class="container-fluid">
    @include("include_backend/partials_no_aside/_inc_menu_repository")

    <div class="row mt-5">
        <div class="col-md-3">
            @include('manage_survey/menu_data_survey')
        </div>
        <div class="col-md-9">
			
			<div class="card">
				<div class="card-header font-weight-bold">
					{{ $title }}
				</div>

				<form
                                action="<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/setting-pertanyaan' ?>"
                                class="form_atribut_pertanyaan">
				<div class="card-body">

					<div class="alert alert-secondary mb-5" role="alert">
						<i class="flaticon-exclamation-1"></i> Halaman ini digunakan untuk mengatur jenis pertanyaan yang dipakai di dalam survei. Perlu diperhatikan, Mengubah Jenis Pertanyaan juga akan menghapus semua data perolehan survei yang sudah masuk.
					</div>

                            

                                <div class="form-group row mt-5">
                                    <label for="recipient-name" class="col-sm-4 col-form-label font-weight-bold">Jenis
                                        Pertanyaan Survei <span style="color:red;">*</span></label>

                                    <div class="col-sm-8">
                                        <input type="hidden" name="atribut_pertanyaan[]" value="0">
                                        <label class="font-weight-bold"><input type="checkbox" checked disabled>
                                            Pertanyaan Unsur</label><br>

                                        <label><input type="checkbox" name="atribut_pertanyaan[]" value="1"
                                                <?php echo (in_array(1, $atribut_pertanyaan_survey)) ? 'checked' : '' ?>>
                                            Pertanyaan Harapan</label><br>

                                        <label><input type="checkbox" name="atribut_pertanyaan[]" value="2"
                                                <?php echo (in_array(2, $atribut_pertanyaan_survey)) ? 'checked' : '' ?>>
                                            Pertanyaan Tambahan</label><br>

                                        <label><input type="checkbox" name="atribut_pertanyaan[]" value="3"
                                                <?php echo (in_array(3, $atribut_pertanyaan_survey)) ? 'checked' : '' ?>>
                                            Pertanyaan Kualitatif</label>
                                    </div>
                                </div>
                                


								
								
								
							</div>
							<div class="card-footer">
								@if($manage_survey->is_question == 1)
								<div class="text-right mt-5">
									<button type="submit"
										onclick="return confirm('Apakah anda yakin ingin mengubah atribut pertanyaan survei ?')"
										class="btn btn-primary font-weight-bold btn-sm tombolSimpanJenisPertanyaan"
										<?php echo $manage_survey->is_survey_close == 1 ? 'disabled' : '' ?>>Update
										Jenis Pertanyaan</button>
								</div>
								@endif
					
							</div>
				</form>
			</div>
		</div>
	</div>
</div>
@endsection

@section('javascript')
<script>
$('.form_atribut_pertanyaan').submit(function(e) {
    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        dataType: 'json',
        data: $(this).serialize(),
        cache: false,
        beforeSend: function() {
            $('.tombolSimpanJenisPertanyaan').attr('disabled', 'disabled');
            $('.tombolSimpanJenisPertanyaan').html(
                '<i class="fa fa-spin fa-spinner"></i> Sedang diproses');

            KTApp.block('#content_1', {
                overlayColor: '#000000',
                state: 'primary',
                message: 'Processing...'
            });

            setTimeout(function() {
                KTApp.unblock('#content_1');
            }, 1000);

        },
        complete: function() {
            $('.tombolSimpanJenisPertanyaan').removeAttr('disabled');
            $('.tombolSimpanJenisPertanyaan').html('Update Jenis Pertanyaan');
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
                window.setTimeout(function() {
                    location.reload()
                }, 1000);
            }
        }
    })
    return false;
});
</script>	
@endsection
