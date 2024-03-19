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

                        @if ($profiles->is_question == 1)
                        <a class="btn btn-primary btn-sm font-weight-bold"
                            href="<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/definisi-skala/add' ?>"><i
                                class="fa fa-plus-square"></i>
                            Buat Range Nilai Interval
                        </a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card card-custom card-sticky" data-aos="fade-down">
                <div class="card-body">
                    <p>
                        Anda bisa mendefinisikan sendiri nilai interval untuk survei anda. Nilai Interval pada sistem
                        ini
                        menggunakan skala 100.
                        <br><br>
                    </p>
                    <div class="table-responsive">
                        <table id="table" class="table table-bordered table-hover" cellspacing="0" width="100%"
                            style="font-size: 12px;">
                            <thead class="bg-secondary">
                                <tr>
                                    <th width="5%">Skala</th>
                                    <th>Batas Atas</th>
                                    <th>Batas Bawah</th>
                                    <th>Mutu</th>
                                    <th>Kategori</th>
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



<!-- Modal -->
@foreach($definisi_skala->result() as $row)
<div class="modal fade" id="edit{{$row->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-secondary">
                <h6 class="modal-title" id="exampleModalLabel">Edit</h6>
            </div>
            <div class="modal-body">
                <form
                    action="<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/definisi-skala/edit' ?>"
                    method="POST" class="form_default">

                    <input name="id" value="{{$row->id}}" hidden>

                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="font-weight-bold">Batas Atas <span style="color: red;">*</span></label>
                            <input class="form-control" name="batas_atas" value="{{$row->range_atas}}" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="font-weight-bold">Batas Bawah <span style="color: red;">*</span></label>
                            <input class="form-control" name="batas_bawah" value="{{$row->range_bawah}}" disabled>
                        </div>
                    </div>
                    <hr>
                    <hr>
                    <br>

                    <div class=" form-group row">
                        <label class="col-sm-3 col-form-label font-weight-bold">Mutu<span
                                style="color: red;">*</span></label>
                        <div class="col-sm-9">
                            <input class="form-control" name="mutu" value="{{$row->mutu}}">
                        </div>
                    </div>
                    <div class=" form-group row">
                        <label class="col-sm-3 col-form-label font-weight-bold">Kategori<span
                                style="color: red;">*</span></label>
                        <div class="col-sm-9">
                            <input class="form-control" name="kategori" value="{{$row->kategori}}">
                        </div>
                    </div>

                    <div class=" text-right">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-sm tombolSimpanDefault">Simpan</button>
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
        "language": {
            "processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',
        },
        "ajax": {
            "url": "<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/definisi-skala/ajax-list' ?>",
            "type": "POST",
            "data": function(data) {}
        },
        "columnDefs": [{
            "targets": [-1],
            "orderable": false,
        }, ],
    });
});
</script>

<script>
$('.form_default').submit(function(e) {
    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        dataType: 'json',
        data: $(this).serialize(),
        cache: false,
        beforeSend: function() {
            $('.tombolSimpanDefault').attr('disabled', 'disabled');
            $('.tombolSimpanDefault').html('<i class="fa fa-spin fa-spinner"></i> Sedang diproses');
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
            $('.tombolSimpanDefault').removeAttr('disabled');
            $('.tombolSimpanDefault').html('Simpan');
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
                table.ajax.reload();
            }
        }
    })
    return false;
});
</script>
@endsection