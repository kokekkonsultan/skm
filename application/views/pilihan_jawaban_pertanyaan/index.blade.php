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
    <div class="card" data-aos="fade-down">
        <div class="card-header bg-secondary font-weight-bold">
            {{ $title }}
        </div>
        <div class="card-body">
            <div class="text-right mb-5">
                <button type="button" class="btn btn-primary btn-sm font-weight-bold shadow" data-toggle="modal"
                    data-target="#add"><i class="fa fa-plus"></i> Tambah Pilihan Jawaban Pertanyaan
                </button>
            </div>
            <div class="table-responsive">
                <table id="table" class="table table-bordered table-hover" cellspacing="0" width="100%">
                    <thead class="bg-secondary">
                        <tr>
                            <th>No.</th>
                            <th>Pilihan 1</th>
                            <th>Pilihan 2</th>
                            <th>Pilihan 3</th>
                            <th>Pilihan 4</th>
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




<!-- Modal Add -->
<div class="modal fade" id="add" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-secondary">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Pilihan Jawaban Pertanyaan</h5>
            </div>
            <div class="modal-body">

                <form action="<?php echo base_url() . 'pilihan-jawaban-pertanyaan/add' ?>" class="form_simpan"
                    method="POST">

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label font-weight-bold">Pilihan Jawaban
                            1 <span style="color: red;">*</span></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="pilihan_1" id="pilihan_1" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label font-weight-bold">Pilihan Jawaban
                            2 <span style="color: red;">*</span></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="pilihan_2" id="pilihan_2" required=>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label font-weight-bold">Pilihan Jawaban
                            3 <span style="color: red;">*</span></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="pilihan_3" id="pilihan_3" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label font-weight-bold">Pilihan Jawaban
                            4 <span style="color: red;">*</span></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="pilihan_4" id="pilihan_4" required>
                        </div>
                    </div>

                    <br>

                    <div class="text-right">
                        <button type="button" class="btn btn-secondary font-weight-bold btn-sm"
                            data-dismiss="modal">Batal</button>
                        <button type="submit"
                            class="btn btn-primary font-weight-bold btn-sm tombolSimpan">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>





<!-- Modal Edit -->
@foreach($ci->db->get('pilihan_jawaban_pertanyaan_harapan')->result() as $row)

<div class="modal fade" id="edit_{{$row->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-secondary">
                <h5 class="modal-title" id="exampleModalLabel">Edit Pilihan Jawaban Pertanyaan</h5>
            </div>
            <div class="modal-body">

                <form action="<?php echo base_url() . 'pilihan-jawaban-pertanyaan/edit' ?>" class="form_simpan"
                    method="POST">

                    <input name="id" value="{{$row->id}}" hidden>

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label font-weight-bold">Pilihan Jawaban
                            1 <span style="color: red;">*</span></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" value="{{$row->pilihan_1}}" name="pilihan_1"
                                id="pilihan_1" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label font-weight-bold">Pilihan Jawaban
                            2 <span style="color: red;">*</span></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" value="{{$row->pilihan_2}}" name="pilihan_2"
                                id="pilihan_2" required=>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label font-weight-bold">Pilihan Jawaban
                            3 <span style="color: red;">*</span></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" value="{{$row->pilihan_3}}" name="pilihan_3"
                                id="pilihan_3" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label font-weight-bold">Pilihan Jawaban
                            4 <span style="color: red;">*</span></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="pilihan_4" value="{{$row->pilihan_4}}"
                                id="pilihan_4" required>
                        </div>
                    </div>

                    <br>

                    <div class="text-right">
                        <button type="button" class="btn btn-secondary font-weight-bold btn-sm"
                            data-dismiss="modal">Batal</button>
                        <button type="submit"
                            class="btn btn-primary font-weight-bold btn-sm tombolSimpan">Simpan</button>
                    </div>


                </form>
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
        "ajax": {
            "url": "{{ base_url() }}pilihan-jawaban-pertanyaan/ajax-list",
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
            url: "{{ base_url() }}pilihan-jawaban-pertanyaan/delete/" + id,
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
$('.form_simpan').submit(function(e) {

    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        dataType: 'json',
        data: $(this).serialize(),
        cache: false,
        beforeSend: function() {
            $('.tombolSimpan').attr('disabled', 'disabled');
            $('.tombolSimpan').html('<i class="fa fa-spin fa-spinner"></i> Sedang diproses');

            KTApp.block('#content_1', {
                overlayColor: '#000000',
                state: 'primary',
                message: 'Processing...'
            });

            setTimeout(function() {
                KTApp.unblock('#content_1');
            }, 1000);

        },
        complete: function() {
            $('.tombolSimpan').removeAttr('disabled');
            $('.tombolSimpan').html('Simpan');
        },
        error: function(e) {
            Swal.fire(
                'Error !',
                e,
                'error'
            )
        },
        success: function(data) {
            if (data.validasi) {
                $('.pesan').fadeIn();
                $('.pesan').html(data.validasi);
            }
            if (data.sukses) {
                toastr["success"]('Data berhasil disimpan');
                window.setTimeout(function() {
                    location.reload()
                }, 1500);
            }
        }
    })
    return false;
});
</script>
@endsection