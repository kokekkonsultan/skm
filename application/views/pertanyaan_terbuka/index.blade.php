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
    <div class="card">
        <div class="card-header bg-secondary font-weight-bold">
            {{ $title }}
        </div>
        <div class="card-body">
            <div class="text-right mb-3">
                @php
                echo anchor(base_url().'pertanyaan-terbuka/add', 'Tambah Pertanyaan Tambahan', ['class' => 'btn
                btn-primary btn-sm font-weight-bold shadow-lg'])
                @endphp
            </div>

            <form id="form-filter" class="">
                <div class="row mb-5">
                    <div class="col-md-6">

                        <label for="jenis_pelayanan" class="form-label font-weight-bold text-primary">Filter Klasifikasi
                            dan Pelayanan</label>
                        <select name="jenis_pelayanan" id="jenis_pelayanan" class="form-control"
                            onchange="updateUnit();">
                            <option value="">Please Select</option>
                            @php
                            $ci->db->select('*, jenis_pelayanan.id AS id_jenis_pelayanan');
                            $ci->db->from('klasifikasi_survei');
                            $ci->db->join('jenis_pelayanan', 'jenis_pelayanan.id_klasifikasi_survei =
                            klasifikasi_survei.id');
                            $jenis_pelayanan = $ci->db->get();
                            @endphp
                            @foreach ($jenis_pelayanan->result() as $value)
                            <option value="{{ $value->id_jenis_pelayanan }}">{{ $value->nama_klasifikasi_survei }} --
                                {{ $value->nama_jenis_pelayanan_responden }}
                            </option>
                            @endforeach
                        </select>

                    </div>

                </div>
            </form>
            <script>
            function updateUnit() {
                table.ajax.reload(null, false);
            }
            </script>

            <div class="table-responsive">
                <table id="table" class="table table-bordered table-hover" cellspacing="0" width="100%">
                    <thead class="bg-secondary">
                        <tr>
                            <th>No.</th>
                            <th>Klasifikasi, Jenis, Unsur Pelayanan & Pertanyaan Tambahan</th>
                            <th>Isi Pertanyaan Tambahan</th>
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
            "url": "{{ base_url() }}pertanyaan-terbuka/ajax-list",
            "type": "POST",
            "data": function(data) {
                data.id_jenis_pelayanan = $('#jenis_pelayanan').val();
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
            url: "{{ base_url() }}pertanyaan-terbuka/delete/" + id,
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