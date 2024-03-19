@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')
<link href="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.css" rel="stylesheet"
    type="text/css" />
@endsection

@section('content')

<div class="container-fluid">

    <div class="text-right mb-5">
        @php
        echo anchor(base_url().'klasifikasi-survei/add', '<i class="fa fa-plus"></i> Tambah Klasifikasi Survei',
        ['class'
        => 'btn
        btn-primary font-weight-bold shadow'])
        @endphp
    </div>
    <div class="card" data-aos="fade-down">
        <div class="card-body">

            <div class="mb-5">
                <h3>KLASIFIKASI SURVEI</h3>
                <p>Anda dapat mengelola klasifikasi survei pada halaman ini.</p>
            </div>

            <hr>

            <div class="table-responsive">
                <table id="table" class="table table-hover" cellspacing="0" width="100%">
                    <thead class="">
                        <tr>
                            <!-- <th>No.</th> -->
                            <th>Klasifikasi Survei</th>
                            <!-- <th></th>
                            <th></th> -->
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
            "url": "{{ base_url() }}klasifikasi-survei/ajax-list",
            "type": "POST",
            "data": function(data) {}
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
            url: "{{ base_url() }}klasifikasi-survei/delete/" + id,
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