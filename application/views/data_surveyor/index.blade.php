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

            <div class="card card-custom bgi-no-repeat gutter-b"
                style="height: 150px; background-color: #1c2840; background-position: calc(100% + 0.5rem) 100%; background-size: 100% auto; background-image: url(/assets/img/banner/taieri.svg)"
                data-aos="fade-down">
                <div class="card-body d-flex align-items-center">
                    <div>
                        <h3 class="text-white font-weight-bolder line-height-lg mb-5">
                            {{strtoupper($title)}}
                        </h3>

                        @php
                        echo
                        anchor(base_url().$ci->session->userdata('username').'/'.$ci->uri->segment(2).'/data-surveyor/add',
                        '<i class="fas fa-plus"></i> Tambah Data Surveyor', ['class' => 'btn btn-primary btn-sm
                        font-weight-bold shadow-lg']);
                        @endphp
                    </div>
                </div>
            </div>

            <div class="card card-custom card-sticky" data-aos="fade-down">
                <div class="card-body">
                    <?php echo $ci->session->set_flashdata('message_success') ?>
                    <div class="table-responsive">

                        <p>
                            Jika organisasi anda memiliki tenaga surveyor, anda dapat membuatkan akun surveyor untuk
                            memantau perolehan hasil survei mereka. Setelah akun surveyor dibuat, anda sebagai admin
                            wajib menyampaikan akun surveyor tersebut kepada surveyor untuk dikelola. Setiap surveyor
                            memiliki link sendiri-sendiri.
                        </p>

                        <table id="table" class="table table-bordered table-hover" cellspacing="0" width="100%"
                            style="font-size: 12px;">
                            <thead class="bg-secondary">
                                <tr>
                                    <th width="5%">No.</th>
                                    <th></th>
                                    <th>Nama Surveyor</th>
                                    <th>Kode Surveyor</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>

                        <!-- <br><br><br>
                        <div class="mt-5">
                            <div class="row">
                                <div class="col-md-6">
                                    @php
                                    echo anchor(base_url() .
                                    $ci->session->userdata('username').'/'.$ci->uri->segment(2).'/pertanyaan-kualitatif',
                                    '<i class="fas fa-arrow-left text-dark"></i> Pertanyaan Kualitatif', ['class' =>
                                    'btn
                                    btn-light-secondary text-dark font-weight-bold shadow']);
                                    @endphp
                                </div>
                                <div class="col-md-6 text-right">
                                    @php
                                    echo anchor(base_url() .
                                    $ci->session->userdata('username').'/'.$ci->uri->segment(2).'/link-survey', 'Link
                                    Survey
                                    <i class="fas fa-arrow-right text-dark"></i>', ['class' => 'btn btn-light-secondary
                                    text-dark font-weight-bold shadow']);
                                    @endphp
                                </div>
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
@include("data_surveyor/form_detail")
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
            "url": "<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/data-surveyor/ajax-list' ?>",
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
            url: "<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/data-surveyor/delete/' ?>" +
                id,
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
<script>
"use strict";
var KTClipboardDemo = function() {
    var demos = function() {
        new ClipboardJS('[data-clipboard=true]').on('success', function(e) {
            e.clearSelection();
            toastr["success"]('Link berhasil dicopy, Silahkan paste di browser anda sekarang.');
        });
    }

    return {
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