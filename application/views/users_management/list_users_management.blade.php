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
    @include('include_backend/partials_backend/_message')

    <div class="card-deck" width="100%">
        <div class="card col-5">
            <div class="card-header font-weight-bold">
                Paket Anda
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p>
                            <label>Nama Paket</label><br>
                            <span class="font-weight-bold">{{ $data_langganan->nama_paket }}</span>
                        </p>

                        <p>
                            <label>Deskripsi</label><br>
                            <span class="font-weight-bold">{!! $data_langganan->deskripsi_paket !!}</span>
                        </p>

                        <p>
                            <label>Lama Berlangganan</label><br>
                            <span class="font-weight-bold">{{ $data_langganan->panjang_hari }} Hari</span>
                        </p>

                        <p>
                            <label>Harga Paket</label><br>
                            <span class="font-weight-bold">{{ $data_langganan->harga_paket }}</span>
                        </p>

                        <p>
                            <label>Tanggal Pembelian</label><br>
                            <span
                                class="font-weight-bold">{{ date('d-m-Y', strtotime($data_langganan->tanggal_mulai)) }}</span>
                        </p>

                    </div>

                    <div class="col-md-6">
                        <p>
                            <label>Jumlah User</label><br>
                            <span class="font-weight-bold">{{ $data_langganan->jumlah_user }}</span>
                        </p>

                        <p>
                            <label>Jumlah Kuesioner</label><br>
                            <span class="font-weight-bold">{{ $data_langganan->jumlah_kuesioner }}</span>
                        </p>

                        <p>
                            <label>Status Paket</label><br>
                            <span>{!! $status_paket !!}</span>
                        </p>

                        <p>
                            <label>Tanggal Jatuh Tempo</label><br>
                            <span class="font-weight-bold text-danger">{!! $status_jatuh_tempo !!}</span>
                        </p>

                    </div>
                </div>
            </div>
        </div>
        <div class="card col-7">
            <div class="card-header font-weight-bold">Kelola Divisi</div>
            <div class="card-body">

                <div class="text-right mb-3">
                    {!! $btn_add_divisi !!}
                </div>

                <div class="table-responsive">
                    <table id="table_divisi" class="table table-bordered table-hover" cellspacing="0" width="100%"
                        style="font-size: 12px;">
                        <thead class="">
                            <tr>
                                <th width="5%">No.</th>
                                <th>Nama Divisi</th>
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
    <br>

    <div class="card" data-aos="fade-down">
        <div class="card-header font-weight-bold">Kelola Admin Survei</div>
        <div class="card-body">
            <div class="text-right mb-3">
                {!! $btn_add !!}
            </div>

            <div class="table-responsive">
                <table id="table" class="table table-hover" cellspacing="0" width="100%" style="font-size: 12px;">
                    <thead class="">
                        <tr>
                            <th width="5%">No.</th>
                            <th></th>
                            <th>Nama Lengkap</th>
                            <th>Devisi</th>
                            <th>Jenis Akun</th>
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

@include("users_management/form_modal_divisi")

@endsection

@section('javascript')
<script src="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.js"></script>

<script>
$(document).ready(function() {
    table = $('#table_divisi').DataTable({

        "processing": true,
        "serverSide": true,
        "order": [],
        "language": {
            "processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',
        },
        "ajax": {
            "url": "<?php echo base_url() . $ci->session->userdata('username') . '/ajax-list-division/' . $ci->uri->segment(4) ?>",
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

function delete_divisi(id) {
    if (confirm('Are you sure delete this data?')) {
        $.ajax({
            url: "<?php echo base_url() . $ci->session->userdata('username') . '/delete-division/' ?>" +
                id,
            type: "POST",
            dataType: "JSON",
            success: function(data) {
                if (data.status) {

                    $('#table_divisi').DataTable().ajax.reload();
                    $('#table').DataTable().ajax.reload();

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


<script>
$(document).ready(function() {
    table = $('#table').DataTable({

        "processing": true,
        "serverSide": true,

        "lengthMenu": [
            [5, 10, 25],
            [5, 10, 25]
        ],
        "pageLength": 5,
        "order": [],
        "language": {
            "processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',
        },
        "ajax": {
            "url": "<?php echo base_url() . $ci->session->userdata('username') . '/users-management/ajax-list-users/' . $ci->uri->segment(4) ?>",
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

function delete_list(id) {
    if (confirm('Are you sure delete this data?')) {
        $.ajax({
            url: "<?php echo base_url() . $ci->session->userdata('username') . '/users-management/list-users/' . $ci->uri->segment(4) . '/delete/' . $ci->uri->segment(6) ?>" +
                id,
            type: "POST",
            dataType: "JSON",
            success: function(data) {
                if (data.status) {

                    // $('#table').DataTable().ajax.reload()

                    Swal.fire(
                        'Informasi',
                        'Berhasil menghapus data',
                        'success'
                    );

                    window.setTimeout(function() {
                    location.reload()
                    }, 1500);

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

<script>
function cek() {
    Swal.fire({
        icon: 'warning',
        title: 'Informasi',
        text: 'Kuota Pengguna Sudah Terpenuhi!',
        allowOutsideClick: false,
        confirmButtonColor: '#DD6B55',
        confirmButtonText: 'Ya, Saya mengerti !',
    });
}
</script>
@endsection