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
    @include("include_backend/partials_no_aside/_inc_menu_repository")

    <div class="row mt-5">
        <div class="col-md-3">
            @include('manage_survey/menu_data_survey')
        </div>
        <div class="col-md-9">

            <div class="card card-custom card-sticky" data-aos="fade-down">
                @include('include_backend/partials_backend/_message')
                <div class="card-header">
                    <div class="card-title">
                        {{$title}}
                    </div>
                    <div class="card-toolbar">
                        <a href="<?= base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) .
                                        '/' . 'alasan/' ?>" class="btn btn-danger shadow"><i
                                class="fas fa-arrow-left"></i> Kembali</a>
                    </div>
                </div>

                <div class="card-body">

                    <div class="card card-body">
                        <table width="100%" border="0">
                            <tr class="font-weight-bold">
                                <td width="3%" valign="top">
                                    <?php echo $pertanyaan->nomor_unsur ?>.</td>
                                <td><?php echo $pertanyaan->isi_pertanyaan_unsur ?></td>
                            </tr>
                        </table>
                    </div>
                    <br>
                    <hr>
                    <br>

                    <div class="table-responsive">
                        <table id="table" class="table table-bordered table-hover" cellspacing="0" width="100%"
                            style="font-size: 12px;">
                            <thead class="bg-secondary">
                                <tr>
                                    <th width="5%">No.</th>
                                    <th>Nama Responden</th>
                                    <th>Pilihan Jawaban</th>
                                    <th>Alasan Jawaban</th>
                                    <th>Status</th>
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
    </div>

</div>
@include("alasan/edit")


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
            "url": "<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/alasan/ajax-list-detail/' . $ci->uri->segment(5) ?>",
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