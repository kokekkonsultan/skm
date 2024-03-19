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
            <div class="card card-custom card-sticky">
                @include('include_backend/partials_backend/_message')
                <div class="card-header">
                    <div class="card-title">
                        Pertanyaan Unsur Pelayanan
                    </div>
                    <div class="card-toolbar">
                        <?php if ($is_question == 1) { ?>
                        @php echo anchor(
                        base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) .
                        '/pertanyaan-unsur-survey/add',
                        '<i class="fas fa-plus"></i> Tambah Pertanyaan Unsur',
                        ['class' => 'btn btn-primary font-weight-bold shadow-lg btn-sm']
                        );
                        @endphp
                        <?php } ?>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="table" class="table table-bordered table-hover" cellspacing="0" width="100%"
                            style="font-size: 12px;">
                            <thead class="bg-secondary">
                                <tr>
                                    <th width="5%">No.</th>
                                    <th>Unsur Pelayanan</th>
                                    <th>Isi Pertanyaan</th>
                                    <th>Pilihan Jawaban</th>
                                    <th></th>
                                    <?php if ($manage_survey->is_question == 1) {
                                        echo '<th></th>';
                                    } ?>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <br>

            <div class="card card-custom card-sticky mt-5">
                <div class="card-header">
                    <div class="card-title">
                        Pertanyaan Terbuka
                    </div>
                    <div class="card-toolbar">
                        <?php if ($is_question == 1) { ?>
                        @php echo anchor(
                        base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) .
                        '/pertanyaan-terbuka-survey/add',
                        '<i class="fas fa-plus"></i> Tambah Pertanyaan Terbuka',
                        ['class' => 'btn btn-primary font-weight-bold shadow-lg btn-sm']
                        );
                        @endphp
                        <?php } ?>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="table_pertanyaan_terbuka" class="table table-bordered table-hover" cellspacing="0"
                            width="100%" style="font-size: 12px;">
                            <thead class="bg-secondary">
                                <tr>
                                    <th width="5%">No.</th>
                                    <th>Unsur Pelayanan</th>
                                    <th>Isi Pertanyaan</th>
                                    <th></th>
                                    <?php if ($manage_survey->is_question == 1) {
                                        echo '<th></th>';
                                    } ?>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <br>

            <div class="card card-custom card-sticky mt-5">
                <div class="card-header">
                    <div class="card-title">
                        Pertanyaan Kualitatif
                    </div>
                    <div class="card-toolbar">
                        <?php if ($is_question == 1) { ?>
                        @php echo anchor(
                        base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) .
                        '/pertanyaan-kualitatif-survey/add',
                        '<i class="fas fa-plus"></i> Tambah Pertanyaan Kualitatif',
                        ['class' => 'btn btn-primary font-weight-bold shadow-lg btn-sm']
                        );
                        @endphp
                        <?php } ?>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="table_pertanyaan_kualitatif" class="table table-bordered table-hover" cellspacing="0"
                            width="100%" style="font-size: 12px;">
                            <thead class="bg-secondary">
                                <tr>
                                    <th width="5%">No.</th>
                                    <th>Isi Pertanyaan</th>
                                    <th>Status</th>
                                    <th></th>
                                    <?php if ($manage_survey->is_question == 1) {
                                        echo '<th></th>';
                                    } ?>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <br>

            <div class="card card-custom card-sticky mt-5">
                <div class="card-header">
                    <div class="card-title">
                        Pertanyaan Harapan
                    </div>
                    <div class="card-toolbar">
                        <button type="button" class="btn btn-primary btn-sm font-weight-bold shadow-lg"
                            data-toggle="modal" data-target="#exampleModal">
                            <i class="fas fa-edit"></i> Edit Pilihan Jawaban
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="table_pertanyaan_harapan" class="table table-bordered table-hover" cellspacing="0"
                            width="100%" style="font-size: 12px;">
                            <thead class="bg-secondary">
                                <tr>
                                    <th width="5%">No.</th>
                                    <th>Unsur Pelayanan</th>
                                    <th>Isi Pertanyaan</th>
                                    <th>Pilihan Jawaban</th>
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

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Edit Pilihan Jawaban</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php echo form_open(base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/pertanyaan-harapan-survey/edit'); ?>
                @php
                echo validation_errors();
                @endphp

                <datalist id="data_jawaban">
                    <?php
                    foreach ($pilihan_jawaban->result() as $d) {
                        echo "<option value='$d->id'>$d->pilihan_1</option>";
                    }
                    ?>
                </datalist>

                <div class="form-group row">
                    <label class="col-sm-4 col-form-label font-weight-bold">Pilihan Jawaban
                        1</label>
                    <div class="col-sm-8">
                        <input class="form-control" list="data_jawaban" type="text" name="pilihan_1" id="id"
                            placeholder="Masukkan Pilihan Jawaban ..." onchange="return autofill();" autofocus
                            autocomplete='off' required="required">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4 col-form-label font-weight-bold">Pilihan Jawaban
                        2</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="pilihan_2" id="pilihan_2" required="required">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4 col-form-label font-weight-bold">Pilihan Jawaban
                        3</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="pilihan_3" id="pilihan_3" required="required">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4 col-form-label font-weight-bold">Pilihan Jawaban
                        4</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="pilihan_4" id="pilihan_4" required="required">
                    </div>
                </div>

                <br>

                <div class="text-right">
                    <button type="button" class="btn btn-light-primary font-weight-bold btn-sm shadow"
                        data-dismiss="modal">Batal</button>
                    <?php echo form_submit('submit', 'Ubah Pilihan Jawaban', ['class' => 'btn btn-primary font-weight-bold btn-sm shadow', 'id' => 'flash']); ?>
                </div>

                <?php echo form_close(); ?>
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
            "url": "<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/pertanyaan-survey/ajax-list' ?>",
            "type": "POST",
            "data": function(data) {}
        },

        "columnDefs": [{
            "targets": [-1],
            "orderable": false,
        }, ],

    });

    table = $('#table_pertanyaan_terbuka').DataTable({

        "processing": true,
        "serverSide": true,
        "order": [],
        "language": {
            "processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',
        },
        "ajax": {
            "url": "<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/pertanyaan-survey/ajax-list-pertanyaan-terbuka-survei' ?>",
            "type": "POST",
            "data": function(data) {}
        },

        "columnDefs": [{
            "targets": [-1],
            "orderable": false,
        }, ],

    });

    table = $('#table_pertanyaan_kualitatif').DataTable({

        "processing": true,
        "serverSide": true,
        "order": [],
        "language": {
            "processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',
        },
        "ajax": {
            "url": "<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/pertanyaan-survey/ajax-list-pertanyaan-kualitatif-survei' ?>",
            "type": "POST",
            "data": function(data) {}
        },

        "columnDefs": [{
            "targets": [-1],
            "orderable": false,
        }, ],

    });

    table = $('#table_pertanyaan_harapan').DataTable({

        "processing": true,
        "serverSide": true,
        "order": [],
        "language": {
            "processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',
        },
        "ajax": {
            "url": "<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/pertanyaan-survey/ajax-list-pertanyaan-harapan-survei' ?>",
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


function delete_data(id_pertanyaan_unsur) {
    if (confirm('Are you sure delete this data?')) {
        $.ajax({
            url: "<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/pertanyaan-unsur-survey/delete/' ?>" +
                id_pertanyaan_unsur,
            type: "POST",
            dataType: "JSON",
            success: function(data) {
                if (data.status) {

                    $('#table').DataTable().ajax.reload()

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

function delete_pertanyaan_terbuka(id_pertanyaan_terbuka) {
    if (confirm('Are you sure delete this data?')) {
        $.ajax({
            url: "<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/pertanyaan-terbuka-survey/delete/' ?>" +
                id_pertanyaan_terbuka,
            type: "POST",
            dataType: "JSON",
            success: function(data) {
                if (data.status) {

                    $('#table_pertanyaan_terbuka').DataTable().ajax.reload()

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

function delete_pertanyaan_kualitatif(id_pertanyaan_kualitatif) {
    if (confirm('Are you sure delete this data?')) {
        $.ajax({
            url: "<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/pertanyaan-kualitatif-survey/delete/' ?>" +
                id_pertanyaan_kualitatif,
            type: "POST",
            dataType: "JSON",
            success: function(data) {
                if (data.status) {

                    $('#table_pertanyaan_kualitatif').DataTable().ajax.reload()

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
function autofill() {
    var id = document.getElementById('id').value;
    $.ajax({
        url: "<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/pertanyaan-harapan/cari' ?>",
        data: '&id=' + id,
        success: function(data) {
            var hasil = JSON.parse(data);

            $.each(hasil, function(key, val) {

                document.getElementById('id').value = val.pilihan_1;
                document.getElementById('pilihan_2').value = val.pilihan_2;
                document.getElementById('pilihan_3').value = val.pilihan_3;
                document.getElementById('pilihan_4').value = val.pilihan_4;
            });
        }
    });
}
</script>
@endsection