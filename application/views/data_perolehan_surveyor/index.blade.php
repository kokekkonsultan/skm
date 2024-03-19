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
            <?php echo $ci->session->set_flashdata('message_success') ?>
            <div class="table-responsive">
                <table id="table" class="table table-bordered table-hover" cellspacing="0" width="100%"
                    style="font-size: 12px;">
                    <thead class="bg-secondary">
                        <tr>
                            <th width="5%">No.</th>
                            <th>Status</th>
                            <th>Form</th>
                            <th>Surveyor</th>
                            <th>Nama Responden</th>

                            @foreach ($profil as $row)
                            <th><?php echo $row->nama_profil_responden ?></th>
                            @endforeach

                            @foreach ($unsur->result() as $row)
                            <th><?php echo $row->nomor_unsur ?></th>
                            @endforeach

                            @foreach ($unsur->result() as $row)
                            <th>A<?php echo $row->kode_alasan ?></th>
                            @endforeach

                            <th>Saran</th>
                            <th>Waktu Isi Survei</th>
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
        "language": {
            "processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',
        },
        "ajax": {
            "url": "{{ base_url() }}data-perolehan-surveyor/ajax-list",
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
</script>
@endsection