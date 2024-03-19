@extends('include_backend/template_backend')

@php 
	$ci = get_instance();
@endphp

@section('style')
<link href="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<div class="container">
	{{-- <div class="text-right mb-5">	
	@php
		echo anchor(base_url().'paket/add', 'Buat Paket Baru', ['class' => 'btn btn-primary font-weight-bold shadow-lg']);
	@endphp
	</div> --}}
	<div class="text-right mb-5">
        <button class="btn btn-secondary font-weight-bold" onclick="reload_table()">Refresh</button>
        <button class="btn btn-primary font-weight-bold" onclick="add_data()"></i>Tambah Paket Berlangganan</button>
    </div>

<div class="card">
	<div class="card-header font-weight-bold">
		Paket Berlangganan
	</div>
	<div class="card-body">

		<form id="myDashboard" name="myDashboard" action="{{ base_url() }}paket/paket/update-dashboard-value" method="post">
		<table id="table" class="display table" style="width:100%">
	        <thead>
	            <tr>
	                <th>No</th>
	                <th>Nama Paket</th>
	                <th>Deskripsi Paket</th>
	                <th>Harga (Rp.)</th>
	                <th>Status Aktif</th>
                    <th></th>
	                <th></th>
	                <th></th>
	            </tr>
	        </thead>
	        <tbody>
	        </tbody>
	    </table>
	    </form>

	</div>
</div>

<div class="text-right mt-5">
    <button class="btn btn-secondary font-weight-bold" onclick="reload_table_trial()">Refresh</button>
    <button class="btn btn-primary font-weight-bold" onclick="add_data_trial()"></i>Tambah TRIAL</button>
</div>

<div class="card mt-5">
	<div class="card-header font-weight-bold">
		TRIAL Pengguna Baru
	</div>
	<div class="card-body">
		
		<table id="table_trial" class="display table" style="width:100%">
	        <thead>
	            <tr>
	                <th>No</th>
	                <th>Nama Paket</th>
	                <th>Deskripsi Paket</th>
	                <th>Status Aktif</th>
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

	{{-- <div class="row">

		
	@foreach ($paket->result() as $value)
	<div class="col-md-6">

		<div class="card mb-5">
		  <img src="{{ base_url() }}assets/themes/metronic/assets/media/bg/bg-9.jpg" class="card-img-top" alt="...">
		  <div class="card-body">
		    <h5 class="card-title text-primary">{{ $value->nama_paket }}</h5>
		    <p class="card-text">{{ $value->deskripsi_paket }}</p>
		    <p class="card-text">Harga Paket : Rp. {{ number_format($value->harga_paket,2,',','.') }}</p>
		    <p class="card-text">Panjang Hari : {{ $value->panjang_hari }}</p>
		    <div class="text-right">
		    	@php
		    		$cek_paket = $ci->db->get_where('berlangganan', ['id_paket' => $value->id]);
		    	@endphp
		    	@if ($cek_paket->num_rows() == 0)
		    		<a href="{{ base_url() }}paket/edit/{{ $value->id }}" class="btn btn-light-primary font-weight-bold shadow-lg">Edit paket ini</a>
		    		<a href="javascript:void(0)" class="btn btn-light-primary font-weight-bold shadow-lg" onclick="delete_data({{ $value->id }})">Delete</a>
		    	@endif
		    </div>
		  </div>
		</div>
		
	</div>

	@endforeach
	</div> --}}

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
                        <label class="col-sm-3 col-form-label">Nama Paket <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            {!! form_input($nama_paket) !!}
                            <span class="help-block"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Deskripsi Paket</label>
                        <div class="col-sm-9">
                            {!! form_textarea($deskripsi_paket) !!}
                            <span class="help-block"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Jumlah User <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <div class="input-group">
                            {!! form_input($jumlah_user) !!}
                            <div class="input-group-append"><span class="input-group-text">User</span></div>
                            </div>
                            <span class="help-block"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Jumlah Kuesioner <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <div class="input-group">
                             {!! form_input($jumlah_kuesioner) !!}
                             <div class="input-group-append"><span class="input-group-text">Kuesioner</span></div>
                            </div>
                            
                            <span class="help-block"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Panjang Hari <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <div class="input-group">
                            {!! form_input($panjang_hari) !!}
                            <div class="input-group-append"><span class="input-group-text">Hari</span></div>
                            </div>
                            <span class="help-block"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Harga Paket <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text">Rp.</span></div>
                            {!! form_input($harga_paket) !!}
                            </div>
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
                    <input type="hidden" value="" name="instansiasi_deskripsi" id="instansiasi_deskripsi">

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Nama Paket <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            {!! form_input($nama_paket) !!}
                            <span class="help-block"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Deskripsi Paket</label>
                        <div class="col-sm-9">
                            {!! form_textarea($deskripsi_paket_edit) !!}
                            <span class="help-block"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Jumlah User <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <div class="input-group">
                            {!! form_input($jumlah_user) !!}
                            <div class="input-group-append"><span class="input-group-text">User</span></div>
                            </div>
                            <span class="help-block"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Jumlah Kuesioner <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <div class="input-group">
                            {!! form_input($jumlah_kuesioner) !!}
                            <div class="input-group-append"><span class="input-group-text">Kuesioner</span></div>
                            </div>
                            <span class="help-block"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Panjang Hari <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <div class="input-group">
                            {!! form_input($panjang_hari) !!}
                            <div class="input-group-append"><span class="input-group-text">hari</span></div>
                            </div>
                            <span class="help-block"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Harga Paket <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">Rp.</span></div>
                            {!! form_input($harga_paket) !!}
                            </div>
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





<div class="modal fade" id="modal_form_add_trial" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body form">


                <form id="form_add_trial" action="#">

                	<input type="hidden" value="" name="instansiasi_add_deskripsi" id="instansiasi_add_deskripsi">

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Nama Paket <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            {!! form_input($nama_paket) !!}
                            <span class="help-block"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Deskripsi Paket</label>
                        <div class="col-sm-9">
                            {!! form_textarea($deskripsi_paket_trial) !!}
                            <span class="help-block"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Jumlah User <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <div class="input-group">
                            {!! form_input($jumlah_user) !!}
                            <div class="input-group-append"><span class="input-group-text">User</span></div>
                            </div>
                            <span class="help-block"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Jumlah Kuesioner <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <div class="input-group">
                            {!! form_input($jumlah_kuesioner) !!}
                            <div class="input-group-append"><span class="input-group-text">Kuesioner</span></div>
                            </div>
                            <span class="help-block"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Panjang Hari <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <div class="input-group">
                            {!! form_input($panjang_hari) !!}
                            <div class="input-group-append"><span class="input-group-text">Hari</span></div>
                            </div>
                            <span class="help-block"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Harga Paket <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text">Rp.</span></div>
                            {!! form_input($harga_paket) !!}
                            </div>
                            <span class="help-block"></span>
                        </div>
                    </div>

                </form>

            </div>
            <div class="modal-footer">

                <button type="button" id="btnSave" onclick="save_add_trial()" class="btn btn-primary font-weight-bold">Save</button>
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cancel</button>

            </div>
        </div>
    </div>
</div>


</div>
@endsection

@section('javascript')

<script src="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.js"></script>
<script src="{{ base_url() }}assets/vendor/ckeditor/ckeditor.js"></script>

<script>

CKEDITOR.replace('deskripsi_paket', {
    toolbar: [
        ['Bold', 'Italic', 'Underline', 'Strike', 'TextColor', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink', '-','Format','Font','FontSize', '-', 'Source']
    ],
    height: 400,
    enterMode: 1,
    shiftEnterMode: 2
});

CKEDITOR.replace('deskripsi_paket_edit', {
    toolbar: [
        ['Bold', 'Italic', 'Underline', 'Strike', 'TextColor', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink', '-','Format','Font','FontSize', '-', 'Source']
    ],
    height: 400,
    enterMode: 1,
    shiftEnterMode: 2
});

CKEDITOR.replace('deskripsi_paket_trial', {
    toolbar: [
        ['Bold', 'Italic', 'Underline', 'Strike', 'TextColor', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink', '-','Format','Font','FontSize', '-', 'Source']
    ],
    height: 400,
    enterMode: 1,
    shiftEnterMode: 2
});

var table;
$(document).ready(function() {

    table = $('#table').DataTable({ 

        "processing": true, 
        "serverSide": true, 
        "order": [], 
         
        "ajax": {
            "url": "{{ base_url() }}paket/ajax-list",
            "type": "POST",
        },

         
        "columnDefs": [
        { 
            "targets": [ 0 ], 
            "orderable": false, 
        },
        ],



    });

    $('#table').on( 'change', '.toggle_dash', function () {
		var mode = $(this).prop('checked');
        var nilai_id = $(this).val();

        $.ajax({
            type: 'POST',
            dataType: 'JSON',
            url: "{{ base_url() }}paket/update-status-aktif-value",
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

	$('#table').on( 'change', '.toggle', function () {
		var mode = $(this).prop('checked');
        var nilai_id = $(this).val();

        $.ajax({
            type: 'POST',
            dataType: 'JSON',
            url: "{{ base_url() }}paket/update-status-aktif",
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

var table_trial;
$(document).ready(function() {

    table_trial = $('#table_trial').DataTable({ 

        "processing": true, 
        "serverSide": true, 
        "order": [], 
         
        "ajax": {
            "url": "{{ base_url() }}paket/trial-ajax-list",
            "type": "POST",
        },

         
        "columnDefs": [
        { 
            "targets": [ 0 ], 
            "orderable": false, 
        },
        ],



    });

    $('#table_trial').on( 'change', '.toggle_dash', function () {
		var mode = $(this).prop('checked');
        var nilai_id = $(this).val();

        $.ajax({
            type: 'POST',
            dataType: 'JSON',
            url: "{{ base_url() }}paket/update-status-aktif-value",
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

	$('#table_trial').on( 'change', '.toggle', function () {
		var mode = $(this).prop('checked');
        var nilai_id = $(this).val();

        $.ajax({
            type: 'POST',
            dataType: 'JSON',
            url: "{{ base_url() }}paket/update-status-aktif",
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

function reload_table_trial() {
    table_trial.ajax.reload(null, false);
}

function add_data() {
    $('#form')[0].reset();
    $('.form-control').removeClass('is-invalid');
    $('.help-block').empty();
    $('#modal_form_add').modal('show');
    $('.modal-title').text('Tambah Paket Berlangganan');

    $('.ckeditor').removeClass('is-invalid');
    CKEDITOR.instances['deskripsi_paket'].setData( '', function() { this.updateElement(); } );
}

function save_add() {
    $('#btnSave').text('Saving...');
    $('#btnSave').attr('disabled', true);

    var nama_paket = document.getElementById("nama_paket").value;
    var deskripsi_paket = CKEDITOR.instances['deskripsi_paket'].getData();
    var jumlah_user = document.getElementById("jumlah_user").value;
    var jumlah_kuesioner = document.getElementById("jumlah_kuesioner").value;
    var panjang_hari = document.getElementById("panjang_hari").value;
    var harga_paket = document.getElementById("harga_paket").value;

    $.ajax({
        url: "{{ base_url() }}paket/ajax-add",
        type: "POST",
        data: {
        	'nama_paket': nama_paket, 
        	'deskripsi_paket': deskripsi_paket, 
        	'jumlah_user': jumlah_user, 
        	'jumlah_kuesioner': jumlah_kuesioner, 
        	'panjang_hari': panjang_hari, 
        	'harga_paket': harga_paket 
        },
        dataType: "JSON",
        beforeSend:function(){

        },
        success: function(data) {

            if (data.status) {
                $('#modal_form_add').modal('hide');
                reload_table();

                // Swal.fire(
                //   'Sukses',
                //   'Data berhasil disimpan',
                //   'success'
                // );
                toastr["success"]("Berhasil menambah paket");

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
        url: "{{ base_url() }}paket/ajax-edit/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data) {

            $('[name="id"]').val(data.id);
            $('[name="nama_paket"]').val(data.nama_paket);
            CKEDITOR.instances['deskripsi_paket_edit'].setData(data.deskripsi_paket);
            $('[name="jumlah_user"]').val(data.jumlah_user);
            $('[name="jumlah_kuesioner"]').val(data.jumlah_kuesioner);
            $('[name="panjang_hari"]').val(data.panjang_hari);
            $('[name="harga_paket"]').val(data.harga_paket);

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

    var deskripsi_paket = CKEDITOR.instances['deskripsi_paket_edit'].getData();
    var instansiasi_deskripsi = document.getElementById("instansiasi_deskripsi");
    instansiasi_deskripsi.value = deskripsi_paket;

    $.ajax({
        url: "{{ base_url() }}paket/ajax-update",
        type: "POST",
        data: $('#form_edit').serialize(),
        dataType: "JSON",
        success: function(data) {

            if (data.status) {
                $('#modal_form_edit').modal('hide');
                reload_table();
                reload_table_trial();

                // Swal.fire(
                //   'Sukses',
                //   'Data berhasil diupdate',
                //   'success'
                // );
                toastr["success"]("Berhasil mengubah paket");

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
        text: "Anda akan menghapus paket ini ?",
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
                url: "{{ base_url() }}paket/ajax-delete/" + id,
                type: "POST",
                dataType: "JSON",
                success: function(data) {

                    reload_table();
                    reload_table_trial();

                    // Swal.fire(
                    //   'Deleted!',
                    //   'Data berhasil dihapus',
                    //   'success'
                    // );

                    toastr["success"]("Paket berhasil dihapus");
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error deleting data');
                }
            });


        }
    })
}

function showDetail(id)
{
    $.ajax({
        type: "post",
        url: "{{ base_url() }}paket/get-detail",
        data: "id="+id,
        dataType: "html",
        success: function (response) {
            $('#modalDetail').modal('show');
            $('.modal-title').text('Details');

            $('#bodyModalDetail').empty();
            $('#bodyModalDetail').append(response);
        }
    });
}



function add_data_trial() {
    $('#form_add_trial')[0].reset();
    $('.form-control').removeClass('is-invalid');
    $('.help-block').empty();
    $('#modal_form_add_trial').modal('show');
    $('.modal-title').text('Tambah TRIAL');

    $('.ckeditor').removeClass('is-invalid');
    CKEDITOR.instances['deskripsi_paket_trial'].setData( '', function() { this.updateElement(); } );
}

function save_add_trial() {
    $('#btnSave').text('Saving...');
    $('#btnSave').attr('disabled', true);

    
    var deskripsi_paket = CKEDITOR.instances['deskripsi_paket_trial'].getData();
    var instansiasi_add_deskripsi = document.getElementById("instansiasi_add_deskripsi");
    instansiasi_add_deskripsi.value = deskripsi_paket;

    // var nama_paket = document.getElementById("nama_paket").value;
    // var deskripsi_paket = CKEDITOR.instances['deskripsi_paket_trial'].getData();
    // var jumlah_user = document.getElementById("jumlah_user").value;
    // var jumlah_kuesioner = document.getElementById("jumlah_kuesioner").value;
    // var panjang_hari = document.getElementById("panjang_hari").value;
    // var harga_paket = document.getElementById("harga_paket").value;

    $.ajax({
        url: "{{ base_url() }}paket/ajax-add-trial",
        type: "POST",
        data: $('#form_add_trial').serialize(),
        
        // data: {
        // 	'nama_paket': nama_paket, 
        // 	'deskripsi_paket': deskripsi_paket, 
        // 	'jumlah_user': jumlah_user, 
        // 	'jumlah_kuesioner': jumlah_kuesioner, 
        // 	'panjang_hari': panjang_hari, 
        // 	'harga_paket': harga_paket 
        // },
        dataType: "JSON",
        beforeSend:function(){

        },
        success: function(data) {

            if (data.status) {
                $('#modal_form_add_trial').modal('hide');
                reload_table_trial();

                Swal.fire(
                  'Sukses',
                  'Data berhasil disimpan',
                  'success'
                );

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


</script>

{{-- <script>
function delete_data(id) {
    if (confirm('Are you sure delete this data?')) {
        $.ajax({
            url: "{{ base_url() }}paket/delete/" + id,
            type: "POST",
            dataType: "JSON",
            success: function(data) {
                if (data.status) {

                    // Swal.fire(
                    //     'Informasi',
                    //     'Berhasil menghapus data',
                    //     'success'
                    // );

                   	window.location.href = "{{ base_url() }}paket";

                   	alert("Paket berhasil dihapus");

                } else {
                    Swal.fire(
                        'Informasi',
                        'Hak akses terbatasi. Bukan akun administrator.',
                        'warning'
                    );
                }


            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error deleting data');
            }
        });

    }
}
</script> --}}
@endsection