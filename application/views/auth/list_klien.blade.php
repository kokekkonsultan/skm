@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')
<link href="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.css" rel="stylesheet"
    type="text/css" />
@endsection

@section('content')

<div class="container-fluid mt-5">

    <div class="text-right mb-5">
        @php
        echo anchor(base_url().'pengguna-klien/create-klien', '<i class="fas fa-plus"></i> Tambah Pengguna Klien',
        ['class' => 'btn btn-primary font-weight-bold shadow'])
        @endphp
        <!-- @php
                echo anchor(base_url().'berlangganan', '<i class="fas fa-users"></i> Lihat Status Berlangganan Klien',
                ['class' => 'btn btn-light-primary font-weight-bold shadow']);
                @endphp -->
    </div>

    <div class="card">
        <div class="card-body">

            <div class="mb-5">
                <h3 class="text-primary">PENGGUNA KLIEN</h3>
                <p>Anda dapat mengelola akun klien pada halaman ini.</p>
            </div>
            <hr>
            <div class="table-responsive mt-5">
                <table id="table" class="table table-hover" cellspacing="0" width="100%">
                    <thead class="">
                        <tr>
                            <th>No.</th>
                            <th>Nama User</th>
                            <th>Klasifikasi Survei</th>
                            <th>Terakhir login</th>
                            <th>Status</th>
                            <th></th>
                            <th></th>
                            <th></th>

                            <!-- <th>Last Name</th>
                            <th>Email</th> -->
                            <!-- <th>Groups</th> -->
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>



</div>

<div class="modal fade" id="modal_userDetail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title" id="exampleModalLabel">Caption</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body" id="bodyModalDetail">
                <div align="center" id="loading_registration">
                    <img src="{{ base_url() }}assets/site/img/ajax-loader.gif" alt="">
                </div>

            </div>
        </div>
    </div>
</div>

@endsection

@section('javascript')
@if ($message)
<script type="text/Javascript">
    Swal.fire('@php
        echo $message @endphp ');
</script>
@endif

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
            "url": "{{ base_url() }}pengguna-klien/ajax-list-klien",
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

function showuserdetail(id) {
    $('#bodyModalDetail').html(
        "<div class='text-center'><img src='{{ base_url() }}assets/img/ajax/ajax-loader-big.gif'></div>");

    $.ajax({
        type: "post",
        url: "{{ base_url() }}pengguna-klien/detail",
        data: "id=" + id,
        dataType: "text",
        success: function(response) {

            $('.modal-title').text('Detail Klien Berlangganan');
            $('#bodyModalDetail').empty();
            $('#bodyModalDetail').append(response);
        }
    });
}
</script>
@endsection