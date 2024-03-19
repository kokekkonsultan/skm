@extends('include_backend/template_no_aside')

@php 
	$ci = get_instance();
@endphp

@section('style')

@endsection

@section('content')

<div class="container-fluid">
	@include("include_backend/partials_no_aside/_inc_menu_repository")

	<div class="row justify-content-md-center">
	    <div class="col col-lg-8">

	    	<div class="mt-10 mb-10">
	    		<h4>Berikut adalah jenis pelayanan dari klasifikasi {{ $profiles->nama_klasifikasi_survei }}</h4>
	    	</div>
			@php
				$data_klasifikasi = $ci->db->get_where('jenis_pelayanan', ['id_klasifikasi_survei' => $profiles->id_klasifikasi_survei]);
			@endphp
			@foreach ($data_klasifikasi->result() as $value)
				<div class="card mt-5">
					<div class="card-body">

						<div class="row">
							<div class="col-md-6">
								<b>{{ $value->nama_jenis_pelayanan_responden }}</b>
							</div>
							<div class="col-md-6 text-right">
								@php
									echo '<a class="btn btn-light-primary font-weight-bold shadow" data-toggle="modal" title="Detail" onclick="showuserdetail('.$value->id.')" href="#modal_userDetail"><i class="far fa-eye"></i> Lihat Template Pertanyaan</a>';//anchor(base_url().'master_organisasi/hirarki_by_id/'.$value->id_branch_agency, 'Hirarki', ['class' => 'btn btn-secondary btn-sm', 'target' => '_blank']);
								@endphp
								{{-- <button type="button" class="btn btn-light-primary font-weight-bold" data-toggle="modal" data-target="#exampleModal">
								  <i class="far fa-eye"></i> Lihat Template Pertanyaan
								</button> --}}
								@php
									// echo anchor('url', '<i class="far fa-eye"></i> Lihat Template Pertanyaan', ['class' => 'btn btn-light-primary font-weight-bold']);
								@endphp
								@php
									echo anchor('url', '<i class="fas fa-hand-point-up"></i> Pilih Pertanyaan', ['class' => 'btn btn-light-primary font-weight-bold']);
								@endphp
							</div>
						</div>

				
						
					</div>
				</div>
			@endforeach

			

			@php
				// print_r($profiles)
			@endphp
	    </div>
	</div>
</div>

<div class="modal fade" id="modal_userDetail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="z-index: 10000;">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header bg-secondary text-white">
        <h5 class="modal-title" id="exampleModalLabel"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i aria-hidden="true" class="ki ki-close"></i>
        </button>
      </div>
      <div class="modal-body" id="bodyModalDetail">
        <div align="center" id="loading_registration">
          <img src="{{ base_url() }}assets/img/ajax/ajax-loader.gif" alt="">
        </div>
        
      </div>
    </div>
  </div>
</div>

@endsection

@section('javascript')


<script>
	function showuserdetail(id)
    {
        $('#bodyModalDetail').html("<div class='text-center'><img src='{{ base_url() }}assets/img/ajax/ajax-loader-big.gif'></div>");

        $.ajax({
            type: "post",
            url: "{{ base_url() }}template-pertanyaan/get-detail",
            data: "id="+id,
            dataType: "text",
            success: function (response) {

                $('.modal-title').text('Detail');
                $('#bodyModalDetail').empty();
                $('#bodyModalDetail').append(response);
            }
        });
    }
</script>
@endsection