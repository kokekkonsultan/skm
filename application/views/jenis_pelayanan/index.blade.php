@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')
<link href="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.css"
    rel="stylesheet" type="text/css" />
@endsection

@section('content')

<div class="container-fluid">
    <div class="card" data-aos="fade-down">
        <div class="card-header bg-secondary font-weight-bold">
            {{ $title }}
        </div>
        <div class="card-body">

            <p>
                Jenis Pelayanan adalah perluasan lingkup dari Klasifikasi.
            </p>
            {{-- <div class="text-right mb-3">
                @php
                echo anchor(base_url().'jenis-pelayanan/add', 'Tambah Jenis Pelayanan', ['class' => 'btn btn-primary
                btn-sm font-weight-bold shadow-lg'])
                @endphp
            </div> --}}


            {{-- <form id="form-filter" class="">
            <div class="row mb-5">
                <div class="col-md-6">
                    
                    <label for="klasifikasi" class="form-label font-weight-bold text-primary">Filter Klasifikasi Survei</label>
                    <select name="klasifikasi" id="klasifikasi" class="form-control" onchange="updateUnit();">
                        <option value="">Please Select</option>
                        @php
                            $klasifikasi = $ci->db->get('klasifikasi_survei');
                        @endphp
                        @foreach ($klasifikasi->result() as $value)
                            <option value="{{ $value->id }}">{{ $value->nama_klasifikasi_survei }}</option>
                        @endforeach
                    </select>

                </div>

            </div>
            </form>
            <script>
            function updateUnit() {
                table.ajax.reload(null, false);
            }
            </script> --}}


            <div class="table-responsive">
                <table id="table" class="table" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            {{-- <th>No.</th> --}}
                            <th>Pilih Klasifikasi Survei</th>
                            {{-- <th>Klasifikasi Survei</th>
                            <th>Jenis Pelayanan</th>
                            <th></th>
                            <th></th> --}}
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
            "url": "{{ base_url() }}jenis-pelayanan/ajax-list",
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