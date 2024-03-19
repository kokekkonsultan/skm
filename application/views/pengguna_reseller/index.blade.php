@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')
<link href="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.css" rel="stylesheet"
    type="text/css" />
@endsection

@section('content')
<div class="container">
    @include('include_backend/partials_backend/_message')
    <div class="card" data-aos="fade-down">
        <div class="card-header font-weight-bold bg-secondary">{{$title}}</div>
        <div class="card-body">
            <div class="text-right mb-3">
                <a class="btn btn-primary" href="<?php echo base_url() . 'pengguna-reseller/add' ?>"><i
                        class="fa fa-user-plus"></i> Tambah Pengguna
                    Reseller</a>
            </div>
            <div class="table-responsive">
                <table id="table" class="table table-hover table-bordered" cellspacing="0" width="100%"
                    style="font-size: 12px;">
                    <thead class="bg-secondary">
                        <tr>
                            <th width="5%">No.</th>
                            <th></th>
                            <th>Nama Lengkap</th>
                            <th>Email</th>
                            <th></th>
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

<div class="modal fade" id="modal_userDetail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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


<!-------------------------------------------- Modal -------------------------------------------->
@foreach($reseller->result() as $row)

<div class="modal fade" id="detail{{$row->user_id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-secondary">
        <h5 class="modal-title" id="exampleModalLabel">Detail Klien</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered table-striped">
            <tr>
                <td>No</td>
                <td>Nama Klien</td>
            </tr>
            <?php
            $no = 1;
            foreach($cek_klien->result() as $value) {
                if($value->is_reseller == $row->user_id) {
                ?>
            <tr>
                <td>{{$no++}}</td>
                <td>{{$value->first_name}} {{$value->last_name}}</td>
            </tr>

            <?php } } ?>
        </table>
      </div>
    </div>
  </div>
</div>
@endforeach


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
            "url": "<?php echo base_url() . 'pengguna-reseller/ajax-list' ?>",
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

function delete_user(id) {
    if (confirm('Are you sure delete this data?')) {
        $.ajax({
            url: "<?php echo base_url() . 'pengguna-reseller/delete/' ?>" +
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

function showuserdetail(id)
    {
        $('#bodyModalDetail').html("<div class='text-center'><img src='{{ base_url() }}assets/img/ajax/ajax-loader-big.gif'></div>");

        $.ajax({
            type: "post",
            url: "{{ base_url() }}pengguna-reseller/detail",
            data: "id="+id,
            dataType: "text",
            success: function (response) {

                $('.modal-title').text('Detail Klien Berlangganan');
                $('#bodyModalDetail').empty();
                $('#bodyModalDetail').append(response);
            }
        });
    }
</script>
@endsection