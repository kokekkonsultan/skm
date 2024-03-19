@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')
<link href="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<div class="container-fluid">


    <div class="card card-custom bgi-no-repeat gutter-b aos-init aos-animate" style="height: 150px; background-color: #1c2840; background-position: calc(100% + 0.5rem) 100%; background-size: 100% auto; background-image: url(/assets/img/banner/rhone-2.svg)" data-aos="fade-down">
        <div class="card-body d-flex align-items-center">
            <div>
                <h3 class="text-white font-weight-bolder line-height-lg mb-5">
                    {{strtoupper($title)}}
                </h3>

                <a class="btn btn-secondary btn-sm font-weight-bold" href="{{base_url() . 'data-perolehan-per-bagian'}}"><i class="fa fa-arrow-left"></i> Kembali</a>

                <button class="btn btn-secondary font-weight-bold btn-sm" onclick="reload_table()"><i class="fas fa-sync"></i> Refresh</button>

                <button type="button" class="btn btn-secondary btn-sm font-weight-bold" data-toggle="modal" data-target="#exampleModal"><i class="fa fa-filter"></i> Filter Data
                </button>

                <div class="btn-group" role="group">
                    <button id="btnGroupDrop1" type="button" class="btn
                        btn-secondary btn-sm font-weight-bold dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-print"></i> Export
                    </button>

                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                        <!-- <a class="dropdown-item"
                                    href="<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/data-perolehan-survei/export-all-pdf' ?>"
                                    target="_blank">PDF</a> -->

                        <a class="dropdown-item" href="<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/data-perolehan-survei/export' ?>" target="_blank">Excel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="card card-custom card-sticky" data-aos="fade-down">

        <div class="card-body">

            <?php echo $ci->session->set_flashdata('message_success') ?>
            <div class="table-responsive">
                <table id="table" class="table table-bordered table-hover" cellspacing="0" width="100%" style="font-size: 12px;">
                    <thead class="bg-secondary">
                        <tr>
                            <th width="5%">No.</th>
                            <th>Status</th>
                            <th>Form</th>
                            <th>Surveyor</th>
                            <th>Jenis Pelayanan</th>
                            <?php foreach ($profil as $row) { ?>
                                <th><?php echo $row->nama_profil_responden ?></th>
                            <?php } ?>

                            <th>Waktu Isi Survei</th>
                            <!--<th></th>
                            <th></th>-->
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>


<div class="modal fade bd-example-modal-lg" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-secondary">
                <h5 class="modal-title" id="exampleModalLabel">Filter Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form id="form-filter" class="">

                    <div class="form-group row">
                        <div class="col-md-6 mb-5">
                            <label for="is_submit" class="form-label font-weight-bold text-primary">Mulai
                                Dari</label>
                            <input class="form-control" type="date" id="is_tanggal_start" value="">
                        </div>

                        <div class="col-md-6 mb-5">
                            <label for="is_surveyor" class="form-label font-weight-bold text-primary">Sampai
                                Dengan</label>
                            <input class="form-control" type="date" id="is_tanggal_end" value="">
                        </div>

                        <div class="col-md-6 mb-5">
                            <label for="is_submit" class="form-label font-weight-bold text-primary">Status
                                Lengkap / Tidak Lengkap</label>
                            <select id="is_submit" class="form-control">
                                <option value="">Please Select</option>
                                <option value="1">Lengkap</option>
                                <option value="2">Tidak Lengkap</option>
                            </select>

                        </div>

                        <div class="col-md-6 mb-5">
                            <label for="is_surveyor" class="form-label font-weight-bold text-primary">Dengan
                                Surveyor / Tanpa Surveyor</label>
                            <select id="is_surveyor" class="form-control">
                                <option value="">Please Select</option>
                                <option value="0">Tanpa Surveyor</option>
                                <option value="1">Dengan Surveyor</option>
                            </select>

                        </div>
                    </div>
                    <hr>
                    <br>

                    <div class="form-group row">

                        @foreach($profil_responden_filter->result() as $row)
                        <div class="col-md-6 mb-5">
                            <label class="font-weight-bold text-primary">{{$row->nama_profil_responden}}</label>

                            <select class="form-control" name="<?php echo $row->nama_alias ?>" id="<?php echo $row->nama_alias ?>">
                                <option value="">Please Select</option>
                                @foreach ($kategori_profil_responden->result() as $value)
                                @if ($value->id_profil_responden == $row->id)
                                <option value="<?php echo $value->id ?>">
                                    <?php echo $value->nama_kategori_profil_responden ?></option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                        @endforeach
                    </div>

                    <div class="text-right">
                        <button type="button" id="btn-filter" class="btn btn-primary font-weight-bold">Filter
                            Data</button>
                        <button type="reset" id="btn-reset" class="btn btn-light-primary font-weight-bold">Reset
                            Filter</button>
                        <button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">Close</button>
                    </div>
                </form>


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
                "url": "<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/data-perolehan-survei/ajax-list' ?>",
                "type": "POST",
                "data": function(data) {
                    data.is_submit = $('#is_submit').val();
                    data.is_surveyor = $('#is_surveyor').val();
                    data.is_tanggal_start = $('#is_tanggal_start').val();
                    data.is_tanggal_end = $('#is_tanggal_end').val();

                    <?php foreach ($profil_responden_filter->result() as $row) { ?>
                        data.<?php echo $row->nama_alias ?> = $('#<?php echo $row->nama_alias ?>')
                            .val();
                    <?php } ?>
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

    function updateUnit() {
        table.ajax.reload(null, false);
    }

    function delete_data(id) {
        if (confirm('Are you sure delete this data?')) {
            $.ajax({
                url: "<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/data-perolehan-survei/delete/' ?>" +
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

    function reload_table() {
        table.ajax.reload(null, false);
    }
</script>
@endsection