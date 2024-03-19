@extends('include_backend/template_backend')

@php 
	$ci = get_instance();
@endphp

@section('style')
<link href="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<div class="container-fluid">
    <div class="card" data-aos="fade-down">
        <div class="card-header bg-secondary font-weight-bold">
            {{ $title }}
        </div>
        <div class="card-body">

        	<div class="row mb-5">
        		<div class="col-md-6">
        			{!! anchor(base_url().'jenis-pelayanan', '<i class="fas fa-arrow-left"></i> Kembali', ['class' => "btn btn-light-primary font-weight-bold shadow"]); !!}
        		</div>
        		<div class="col-md-6 text-right">
        			{!! anchor(base_url().'jenis-pelayanan/add/'.$ci->uri->segment(3), 'Tambah Jenis Pelayanan', ['class' => 'btn btn-primary btn-sm font-weight-bold shadow-lg']) !!}
        		</div>
        	</div>

            <p>
                Jenis Pelayanan adalah perluasan lingkup dari Klasifikasi.
            </p>
            <input type="hidden" name="klasifikasi" id="klasifikasi" value="{{ $ci->uri->segment(3) }}">

            <div class="table-responsive">
                <table id="table" class="table" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Klasifikasi Survei</th>
                            <th>Jenis Pelayanan</th>
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
<script src="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.js"></script>
<script>
$(document).ready(function() {
    table = $('#table').DataTable({

        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
            "url": "{{ base_url() }}jenis-pelayanan/ajax-list-jenis-pelayanan",
            "type": "POST",
            "data": function(data) {
                data.id_klasifikasi_survei = $('#klasifikasi').val();
            }
        },

        "columnDefs": [{
            "targets": [-1],
            "orderable": false,
        }, ],

    });
});

$('#btn-filter').click(function() {
    table.ajax.reload();
});
$('#btn-reset').click(function() {
    $('#form-filter')[0].reset();
    table.ajax.reload();
});


function delete_data(id) {
    if (confirm('Are you sure delete this data?')) {
        $.ajax({
            url: "{{ base_url() }}jenis-pelayanan/delete/" + id,
            type: "POST",
            dataType: "JSON",
            success: function(data) {
                if (data.status) {

                    table.ajax.reload();

                    Swal.fire(
                        'Informasi',
                        'Berhasil menghapus data',
                        'success'
                    );
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
</script>
@endsection