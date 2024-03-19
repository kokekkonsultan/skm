@extends('include_backend/template_backend')

@php 
	$ci = get_instance();
@endphp

@section('style')
<link href="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
@endsection

@section('content')

<div class="container">
	<div class="card">
		<div class="card-header font-weight-bold">
			Buat Slide Banner
		</div>
		<div class="card-body">
			
			{!! form_open_multipart($form_action); !!}

      

      <div class="form-group row">
        <label for="" class="col-sm-2 col-form-label">File Banner <span class="text-danger">*</span></label>
        <div class="col-sm-10">
          {!! form_error('banner_file'); !!}

          <div class="mb-5">
          	<span>Gambar sebelumnya</span><br><br>
          	<img src="{{ base_url() }}assets/img/banner/medium/{{ $form_value['banner_file'] }}" alt="">
          </div>
          <br>

          @php
            $form = array(
            'banner_file' => array(
              'name'      => 'banner_file', 
              'value'     => set_value('banner_file', isset($form_value['banner_file']) ? $form_value['banner_file'] : ''),
            ),
          );
          @endphp

          {!! form_upload($form['banner_file']); !!}
          <small>Wajib menggunakan gambar berdimensi 1920 x 1080 pixel</small>
        </div>
      </div>

      <div class="form-group row">
        <label for="" class="col-sm-2 col-form-label">Nama Banner <span class="text-danger">*</span></label>
        <div class="col-sm-10">

          {!! form_error('banner_name'); !!}

          @php
            $form = array(
              'banner_name' => array(
                'name' => 'banner_name', 
                'value'=>set_value('label', isset($form_value['banner_name']) ? $form_value['banner_name'] : ''),
                'class' => 'form-control',
              ),
            );
          @endphp

          {!! form_input($form['banner_name']); !!}
        </div>
      </div>

      <div class="form-group row">
        <label for="" class="col-sm-2 col-form-label">Judul pada tampilan banner <span class="text-danger">*</span></label>
        <div class="col-sm-10">

          {!! form_error('banner_title'); !!}

          @php
            $form = array(
              'banner_title' => array(
                'name' => 'banner_title', 
                'value'=>set_value('label', isset($form_value['banner_title']) ? $form_value['banner_title'] : ''),
                'class' => 'form-control',
              ),
            );
          @endphp

          {!! form_input($form['banner_title']); !!}
        </div>
      </div>

      <div class="form-group row">
        <label for="" class="col-sm-2 col-form-label">Deskripsi pada tampilan banner <span class="text-danger">**</span></label>
        <div class="col-sm-10">

          {!! form_error('banner_description'); !!}
          
          @php
            $form = array(
              'banner_description' => array(
                'name' => 'banner_description', 
                'value'=>set_value('label', isset($form_value['banner_description']) ? $form_value['banner_description'] : ''),
                'class' => 'form-control',
              ),
            );
          @endphp

          {!! form_input($form['banner_description']); !!}

        </div>
      </div>

      <div class="form-group row">
        <label for="" class="col-sm-2 col-form-label">Aktifkan tombol baca selengkapnya ? <span class="text-danger">*</span></label>
        <div class="col-sm-10">

          {!! form_error('button_read_more_active'); !!}
          
          <div>
              <div>
                  <label>{!! form_radio('button_read_more_active', '1', set_radio('button_read_more_active', '1', isset($form_value['button_read_more_active']) && $form_value['button_read_more_active'] == '1' ? TRUE : FALSE), array('required'=>'required')); !!}
                   Ya</label>
              </div>
              <div>
                  <label>{!! form_radio('button_read_more_active', '0', set_radio('button_read_more_active', '0', isset($form_value['button_read_more_active']) && $form_value['button_read_more_active'] == '0' ? TRUE : FALSE), array('required'=>'required')); !!}
                   Tidak</label>
              </div>
          </div>

        </div>
      </div>

      <div class="form-group row">
        <label for="" class="col-sm-2 col-form-label">Link pada tombol baca selengkapnya <span class="text-danger">**</span></label>
        <div class="col-sm-10">
          
          {!! form_error('read_more_link'); !!}

          @php
            $form = array(
              'read_more_link' => array(
                'name' => 'read_more_link', 
                'value'=>set_value('label', isset($form_value['read_more_link']) ? $form_value['read_more_link'] : ''),
                'class' => 'form-control',
              ),
            );
          @endphp

          {!! form_input($form['read_more_link']); !!}
          <br>
          <a class="text-primary" href="javascript:void(0)" title="View" onclick="showDetail('id')">Lihat Link</a>

        </div>
      </div>

      <div class="form-group row">
        <label for="" class="col-sm-2 col-form-label">Aktifkan tombol kontak kami ? <span class="text-danger">*</span></label>
        <div class="col-sm-10">

          {!! form_error('button_contact_active'); !!}
          
          <div>
              <div>
                  <label>{!! form_radio('button_contact_active', '1', set_radio('button_contact_active', '1', isset($form_value['button_contact_active']) && $form_value['button_contact_active'] == '1' ? TRUE : FALSE), array('required'=>'required')); !!}
                   Ya</label>
              </div>
              <div>
                  <label>{!! form_radio('button_contact_active', '0', set_radio('button_contact_active', '0', isset($form_value['button_contact_active']) && $form_value['button_contact_active'] == '0' ? TRUE : FALSE), array('required'=>'required')); !!}
                   Tidak</label>
              </div>
          </div>

        </div>
      </div>

      <div class="form-group row">
        <label for="" class="col-sm-2 col-form-label">Link pada tombol kontak <span class="text-danger">**</span></label>
        <div class="col-sm-10">

          {!! form_error('contact_link'); !!}
          
          @php
            $form = array(
              'contact_link' => array(
                'name' => 'contact_link', 
                'value'=>set_value('label', isset($form_value['contact_link']) ? $form_value['contact_link'] : ''),
                'class' => 'form-control',
              ),
            );
          @endphp

          {!! form_input($form['contact_link']); !!}
          <br>
          <a class="text-primary" href="javascript:void(0)" title="View" onclick="showDetail('id')">Lihat Link</a>

        </div>
      </div>

      <div class="text-right">
        <a href="{{ base_url() }}banner" class="btn btn-light-primary font-weight-bold">Kembali</a>
        <button type="submit" class="btn btn-primary font-weight-bold">Simpan</button>
      </div>

      
      
			{!! form_close(); !!}
		</div>
	</div>
</div>

<div class="modal fade" id="modalDetailLink" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <i aria-hidden="true" class="ki ki-close"></i>
        </button>
      </div>
      <div class="modal-body" id="bodyModalDetailLink">
        
      </div>
    </div>
  </div>
</div>

@endsection

@section('javascript')
<script src="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.js"></script>

<script>
function showDetail(id)
{
    $.ajax({
        type: "post",
        url: "{{ base_url() }}banner/get-detail-link",
        data: "id="+id,
        dataType: "html",
        success: function (response) {
            $('#modalDetailLink').modal('show');
            $('.modal-title').text('Link');

            $('#bodyModalDetailLink').empty();
            $('#bodyModalDetailLink').append(response);
        }
    });
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