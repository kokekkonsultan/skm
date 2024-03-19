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
                        @if ($is_question == 1)
                        <a href="{{ base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/unsur-pelayanan-survey/add' }}"
                            class="btn btn-primary font-weight-bolder mr-2 shadow btn-sm"><i class="fas fa-plus"></i>
                            Tambah Unsur Pelayanan
                        </a>
                        @endif
                    </div>
                </div>

                <div class="card-body">

                    <p>
                        Unsur pelayanan merupakan elemen dari SKM yang akan dilakukan untuk survei. Anda wajib menginputkan Unsur Pelayanan sebelum anda membuat Pertanyaan Unsur.
                    </p>

                    <div class="table-responsive">
                        <table id="table" class="table table-bordered table-hover" cellspacing="0" width="100%"
                            style="font-size: 12px;">
                            <thead class="bg-secondary">
                                <tr>
                                    <th width="5%">No.</th>
                                    <th>Nomor Unsur</th>
                                    <th>Nama Unsur Pelayanan</th>
                                    <th></th>
                                    @if ($is_question == 1)
                                    <th></th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-5">
                        <div class="row">
                            <div class="col-md-6">
                                @php
                                echo anchor(base_url() .
                                $ci->session->userdata('username').'/'.$ci->uri->segment(2).'/profil-responden-survei',
                                '<i class="fas fa-arrow-left text-dark"></i> Profil Responden Survei', ['class' => 'btn
                                btn-light-secondary text-dark font-weight-bold shadow']);
                                @endphp
                            </div>
                            <div class="col-md-6 text-right">
                                @php
                                echo anchor(base_url() .
                                $ci->session->userdata('username').'/'.$ci->uri->segment(2).'/pertanyaan-unsur',
                                'Pertanyaan Unsur Pelayanan <i class="fas fa-arrow-right text-dark"></i>', ['class' => 'btn
                                btn-light-secondary text-dark font-weight-bold shadow']);
                                @endphp
                            </div>
                        </div>
                    </div>
                </div>
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
            "url": "<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/unsur-pelayanan-survey/ajax-list' ?>",
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
            url: "<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/unsur-pelayanan-survey/delete/' ?>" +
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
@endsection