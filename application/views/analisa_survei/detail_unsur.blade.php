@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')

<link href="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
@endsection

@section('content')

<div class="container-fluid">


    <div class="card card-body bg-light-primary border border-primary mb-3">

        <div class="row">
            <div class="col-md-5 text-left">
                <a href="{{ base_url() }}{{ $ci->session->userdata('username'); }}/{{ $ci->uri->segment(2) }}/analisa-survei/{{ $ci->uri->segment(4) }}" title="Kembali" class="btn btn-dark btn-sm font-weight-bold"><i class="fa fa-arrow-left"></i>
                    Kembali</a>
            </div>
            <div class="col-md-7">
                <h4><b>NILAI INDEKS DAN PERTANYAAN</b></h4>
            </div>
        </div>
        <hr>

        <table class="table">
            <tr>
                <td><span class="font-weight-bold"><b>Jenis Pelayanan</b></span></td>
                <td>:</td>
                <td>
                    @php
                    $layanan = $ci->db->get_where("layanan_survei_$profiles->table_identity", ['id' =>
                    $ci->uri->segment(4)])->row();
                    echo strtoupper($layanan->nama_layanan);
                    @endphp
                </td>
            </tr>

            <tr>
                <td><span class="font-weight-bold"><b>Unsur</b></span></td>
                <td>:</td>
                <td>{{ $isi_pertanyaan->nomor_unsur }} {!! $isi_pertanyaan->nama_unsur_pelayanan !!}</td>
            </tr>

            <tr>
                <td><span class="font-weight-bold"><b>Indeks</b></span></td>
                <td>:</td>
                <td>{!! ROUND($isi_pertanyaan->nilai_per_unsur, 2) !!}</td>
            </tr>

            <tr>
                <td><span class="font-weight-bold"><b>Ketegori</b></span></td>
                <td>:</td>
                <td>

                    @php
                    $nilai_konversi = $isi_pertanyaan->nilai_per_unsur * $skala_likert;
                    foreach ($definisi_skala->result() as $obj) {
                    if ($nilai_konversi <= $obj->range_bawah && $nilai_konversi >= $obj->range_atas) {
                        echo $obj->kategori;
                        }
                        }
                        if ($nilai_konversi <= 0) { echo 'NULL' ; } @endphp </td> </tr> @if($cek_turunan_unsur->num_rows() == 0)
            <tr>
                <td><span class="font-weight-bold"><b>Pertanyaan</b></span></td>
                <td>:</td>
                <td>{!! $isi_pertanyaan->isi_pertanyaan_unsur !!}</td>
            </tr>
            @endif
        </table>
    </div>



    <!-- cek  unsur memiliki turunan atau tidak -->
    @if($cek_turunan_unsur->num_rows() > 0)

    @foreach($cek_turunan_unsur->result() as $row)
    <div class="card card-custom card-sticky mt-5">
        <div class="card-header">
            <div class="card-title">
                <?= $row->nomor_unsur . ' ' . $row->nama_unsur_pelayanan ?>
            </div>
            <div class="card-toolbar"></div>
        </div>
        <div class="card-body">
            <form id="form-filter-unsur_<?= $row->id ?>" class="">
                <div class="row mb-5">
                    <div class="col-md-6">
                        <label for="skor_jawaban_unsur_<?= $row->id ?>" class="form-label font-weight-bold text-primary">Filter
                            Berdasarkan
                            Pilihan Jawaban</label>
                        <select id="skor_jawaban_unsur_<?= $row->id ?>" class="form-control" onchange="updateUnitUnsur_<?= $row->id ?>();">
                            <option value="">Please Select</option>
                            <option value="1">Skor 1</option>
                            <option value="2">Skor 2</option>
                        </select>

                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table id="table_unsur_<?= $row->id ?>" class="table table-bordered mt-5" cellspacing="0" width="100%">
                    <thead class="bg-secondary">
                        <tr>
                            <th width="5%">No.</th>
                            <!-- <th>Nama Responden</th> -->
                            @foreach ($profil as $row)
                            <th>{{$row->nama_profil_responden}}</th>
                            @endforeach

                            <th>Pilihan Jawaban</th>
                            <th>Alasan Jawaban</th>
                            <th>Saran</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endforeach


    @else

    <div class="card card-custom card-sticky mt-5">
        <div class="card-body">
            <div class="text-left">
                <h5 class="text-primary">Jawaban Pertanyaan Unsur</h5>
            </div>
            <hr>

            <form id="form-filter-unsur" class="">
                <div class="row mb-5">
                    <div class="col-md-6">
                        <label for="skor_jawaban_unsur" class="form-label font-weight-bold text-dark">Filter
                            Berdasarkan
                            Pilihan Jawaban</label>
                        <select id="skor_jawaban_unsur" class="form-control" onchange="updateUnitUnsur();">
                            <option value="">Please Select</option>
                            <option value="1">Skor 1</option>
                            <option value="2">Skor 2</option>
                        </select>

                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table id="table_unsur" class="table table-bordered mt-5" cellspacing="0" width="100%">
                    <thead class="bg-secondary">
                        <tr>
                            <th width="5%">No.</th>
                            <!-- <th>Nama Responden</th> -->
                            @foreach ($profil as $row)
                            <th>{{$row->nama_profil_responden}}</th>
                            @endforeach

                            <th>Pilihan Jawaban</th>
                            <th>Alasan Jawaban</th>
                            <th>Saran</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif







    <div class="card card-custom card-sticky mt-5">
        <div class="card-body">
            <div class="text-left">
                <h5 class="text-primary">Analisa</h5>
                <span>Anda bisa mengisi analisa pada bidang ini.</span>
            </div>
            <hr>
            <br>


            <form action="{{$form_action}}" method="POST">

                <div class="form-group row">
                    <label class="col-sm-3 col-form-label
                        font-weight-bold">Faktor-faktor Yang Mempengaruhi <span style="color: red;">*</span></label>
                    <div class="col-sm-9">
                        @php
                        echo form_textarea($faktor_penyebab);
                        @endphp
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-3 col-form-label
                        font-weight-bold">Rencana Tindak Lanjut <span style="color: red;">*</span></label>
                    <div class="col-sm-9">
                        @php
                        echo form_textarea($rencana_perbaikan);
                        @endphp
                    </div>
                </div>


                <div class="form-group row">
                    <label class="col-sm-3 col-form-label
                        font-weight-bold">Waktu <span style="color: red;">*</span></label>

                    <div class="input-group input-append date col-sm-9" id="datepicker" data-date="12-{{date('Y')}}" data-date-format="mm-yyyy">
                        @php
                        echo form_input($waktu);
                        @endphp
                        <span class="add-on"><i class="icon-th"></i></span>
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="la la-calendar-check-o"></i></span>
                        </div>
                    </div>
                </div>



                <div class="form-group row">
                    <label class="col-sm-3 col-form-label
                        font-weight-bold">Penanggung Jawab <span style="color: red;">*</span></label>
                    <div class="col-sm-9">
                        @php
                        echo form_input($penanggung_jawab);
                        @endphp
                    </div>
                </div>



                <div class="text-right">
                    <button class="btn btn-primary font-weight-bold" type="submit">Simpan Analisa</button>
                </div>


            </form>

        </div>
    </div>

</div>



<!-- ======================================= EDIT ALASAN ========================================== -->
<div class="modal fade" id="modal_edit_alasan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light-primary">
                <h5 class="modal-title" id="exampleModalLabel">Edit Alasan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body" id="bodyModalEditAlasan">
                <div align="center" id="loading_registration">
                    <img src="{{ base_url() }}assets/site/img/ajax-loader.gif" alt="">
                </div>
            </div>
        </div>
    </div>
</div>


<!-- ======================================= EDIT SARAN ========================================== -->
<div class="modal fade" id="modal_edit_saran" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light-info">
                <h5 class="modal-title" id="exampleModalLabel">Edit Saran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body" id="bodyModalEditSaran">
                <div align="center" id="loading_registration">
                    <img src="{{ base_url() }}assets/site/img/ajax-loader.gif" alt="">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script src="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.js"></script>

@if($cek_turunan_unsur->num_rows() > 0)
@foreach($cek_turunan_unsur->result() as $row)
<script>
    $(document).ready(function() {
        table = $('#table_unsur_<?= $row->id ?>').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "language": {
                "processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',
            },
            "ajax": {
                "url": "<?= base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/analisa-survei/ajax-list-unsur/' . $ci->uri->segment(4) . '/' . $row->id ?>",
                "type": "POST",
                "data": function(data) {
                    data.skor_jawaban_unsur = $('#skor_jawaban_unsur_<?= $row->id ?>').val();
                }
            },

            dom: 'Bfrtip',
            buttons: [
                'colvis'
            ],

            "columnDefs": [{
                "targets": [-1],
                "orderable": false,
            }, ],

        });
    });

    function updateUnitUnsur_<?= $row->id ?>() {
        $('#table_unsur_<?= $row->id ?>').DataTable().ajax.reload(null, false);
        // table.ajax.reload(null, false);
    }
</script>

@endforeach
@else

<script>
    $(document).ready(function() {
        table = $('#table_unsur').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "language": {
                "processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',
            },
            "ajax": {
                "url": "<?= base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/analisa-survei/ajax-list-unsur/' . $ci->uri->segment(4) . '/' . $ci->uri->segment(5) ?>",
                "type": "POST",
                "data": function(data) {
                    data.skor_jawaban_unsur = $('#skor_jawaban_unsur').val();
                }
            },

            // "lengthMenu": [
            //     [5, 10, 25, 50, 100, -1],
            //     [5, 10, 25, 50, 100, "Semua data"]
            //     ],
            //     "pageLength": 5,

            dom: 'Bfrtip',
            buttons: [
                'colvis'
            ],

            "columnDefs": [{
                "targets": [-1],
                "orderable": false,
            }, ],

        });
    });

    function updateUnitUnsur() {
        $('#table_unsur').DataTable().ajax.reload(null, false);
        // table.ajax.reload(null, false);
    }
</script>
@endif


<script src="https://cdn.ckeditor.com/ckeditor5/34.2.0/classic/ckeditor.js"></script>
<script>
    $("#datepicker").datepicker({
        format: "MM yyyy",
        viewMode: "months",
        minViewMode: "months"
    });
</script>


<script>
    ClassicEditor
        .create(document.querySelector('#rencana_perbaikan'))
        .then(editor => {
            console.log(editor);
        })
        .catch(error => {
            console.error(error);
        });
</script>

<script>
    ClassicEditor
        .create(document.querySelector('#faktor_penyebab'))
        .then(editor => {
            console.log(editor);
        })
        .catch(error => {
            console.error(error);
        });
</script>




<script>
function showeditalasan(id) {
    $('#bodyModalEditAlasan').html(
        "<div class='text-center'><img src='{{ base_url() }}assets/img/ajax/ajax-loader-big.gif'></div>");

    $.ajax({
        type: "post",
        url: "<?= base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/analisa-survei/modal-alasan/' ?>" +
            id,
        // data: "id=" + id,
        dataType: "text",
        success: function(response) {

            // $('.modal-title').text('Edit Pertanyaan Unsur');
            $('#bodyModalEditAlasan').empty();
            $('#bodyModalEditAlasan').append(response);
        }
    });
}
</script>


<script>
function showeditsaran(id) {
    $('#bodyModalEditSaran').html(
        "<div class='text-center'><img src='{{ base_url() }}assets/img/ajax/ajax-loader-big.gif'></div>");

    $.ajax({
        type: "post",
        url: "<?= base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/analisa-survei/modal-saran/' ?>" +
            id,
        // data: "id=" + id,
        dataType: "text",
        success: function(response) {

            // $('.modal-title').text('Edit Pertanyaan Unsur');
            $('#bodyModalEditSaran').empty();
            $('#bodyModalEditSaran').append(response);
        }
    });
}
</script>

@endsection