@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')
<link href="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
@endsection

@section('content')

<div class="container-fluid mt-5">

    <a href="{{ base_url() }}{{ $ci->session->userdata('username'); }}/{{ $ci->uri->segment(2) }}/analisa-survei"
        title="Kembali" class="btn btn-secondary font-weight-bold">Kembali</a>

    <br><br>

    <div class="card card-custom card-sticky">
        <div class="card-header">
            <div class="card-title">Nilai Indeks Dan Pertanyaan</div>
            <div class="card-toolbar"></div>
        </div>
        <div class="card-body">
            <div>
                <table class="table">
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

                            <?php
                            $nilai_konversi = $isi_pertanyaan->nilai_per_unsur * $skala_likert;
                            foreach ($definisi_skala->result() as $obj) {
                                if ($nilai_konversi <= $obj->range_bawah && $nilai_konversi >= $obj->range_atas) {
                                    echo $obj->kategori;
                                }
                            }
                            if ($nilai_konversi <= 0) {
                                echo  'NULL';
                            }
                            ?>
                        </td>
                    </tr>

                    @if($cek_turunan_unsur->num_rows() == 0)
                    <tr>
                        <td><span class="font-weight-bold"><b>Pertanyaan</b></span></td>
                        <td>:</td>
                        <td>{!! $isi_pertanyaan->isi_pertanyaan_unsur !!}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>



    <!-- cek  unsur memiliki turunan atau tidak -->
    @if($cek_turunan_unsur->num_rows() > 0)
    @foreach($cek_turunan_unsur->result() as $row)
    <div class="card card-custom card-sticky mt-5">
        <div class="card-header">
            <div class="card-title">
                <?php echo $row->nomor_unsur . ' ' . $row->nama_unsur_pelayanan ?>
            </div>
            <div class="card-toolbar"></div>
        </div>
        <div class="card-body">
            <form id="form-filter-unsur_<?php echo $row->id ?>" class="">
                <div class="row mb-5">
                    <div class="col-md-6">
                        <label for="skor_jawaban_unsur_<?php echo $row->id ?>"
                            class="form-label font-weight-bold text-primary">Filter
                            Berdasarkan
                            Pilihan Jawaban</label>
                        <select id="skor_jawaban_unsur_<?php echo $row->id ?>" class="form-control"
                            onchange="updateUnitUnsur_<?php echo $row->id ?>();">
                            <option value="">Please Select</option>
                            <option value="1">Skor 1</option>
                            <option value="2">Skor 2</option>
                            <option value="3">Skor 3</option>
                            <option value="4">Skor 4</option>
                        </select>

                    </div>
                </div>
            </form>
            <table id="table_unsur_<?php echo $row->id ?>" class="table table-bordered mt-5" cellspacing="0"
                width="100%">
                <thead class="">
                    <tr>
                        <th width="5%">No.</th>
                        <!-- <th>Nama Responden</th> -->
                        @foreach ($profil as $row)
                        <th>{{$row->nama_profil_responden}}</th>
                        @endforeach

                        <th>Pilihan Jawaban</th>
                        <th>Alasan Jawaban</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    @endforeach

    <!-- looping harapan -->
    @foreach($cek_turunan_unsur->result() as $value)
    <div class="card card-custom card-sticky mt-5">
        <div class="card-header">
            <div class="card-title">
                H<?php echo substr($value->nomor_unsur, 1) . ' ' . $value->nama_unsur_pelayanan ?>
            </div>
            <div class="card-toolbar"></div>
        </div>
        <div class="card-body">
            <form id="form-filter-harapan_<?php echo $value->id ?>" class="">
                <div class="row mb-5">
                    <div class="col-md-6">
                        <label for="skor_jawaban_harapan_<?php echo $value->id ?>"
                            class="form-label font-weight-bold text-primary">Filter
                            Berdasarkan
                            Pilihan Jawaban</label>
                        <select id="skor_jawaban_harapan_<?php echo $value->id ?>" class="form-control"
                            onchange="updateUnitHarapan_<?php echo $value->id ?>();">
                            <option value="">Please Select</option>
                            <option value="1">Skor 1</option>
                            <option value="2">Skor 2</option>
                            <option value="3">Skor 3</option>
                            <option value="4">Skor 4</option>
                        </select>

                    </div>
                </div>
            </form>

            <table id="table_harapan_<?php echo $value->id ?>" class="table table-bordered mt-5" cellspacing="0"
                width="100%">
                <thead class="">
                    <tr>
                        <th width="5%">No.</th>
                        <!-- <th>Nama Responden</th> -->
                        <th>Pilihan Jawaban</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>

        </div>
    </div>
    @endforeach
    @else

    <div class="card card-custom card-sticky mt-5">
        <div class="card-header">
            <div class="card-title">
                Jawaban Pertanyaan Unsur
            </div>
            <div class="card-toolbar"></div>
        </div>
        <div class="card-body">
            <form id="form-filter-unsur" class="">
                <div class="row mb-5">
                    <div class="col-md-6">
                        <label for="skor_jawaban_unsur" class="form-label font-weight-bold text-primary">Filter
                            Berdasarkan
                            Pilihan Jawaban</label>
                        <select id="skor_jawaban_unsur" class="form-control" onchange="updateUnitUnsur();">
                            <option value="">Please Select</option>
                            <option value="1">Skor 1</option>
                            <option value="2">Skor 2</option>
                            <option value="3">Skor 3</option>
                            <option value="4">Skor 4</option>
                        </select>

                    </div>
                </div>
            </form>

            <table id="table_unsur" class="table table-bordered mt-5" cellspacing="0" width="100%">
                <thead class="">
                    <tr>
                        <th width="5%">No.</th>
                        <!-- <th>Nama Responden</th> -->
                        @foreach ($profil as $row)
                        <th>{{$row->nama_profil_responden}}</th>
                        @endforeach
                        <th>Pilihan Jawaban</th>
                        <th>Alasan Jawaban</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card card-custom card-sticky mt-5">
        <div class="card-header">
            <div class="card-title">
                Jawaban Pertanyaan Harapan
            </div>
            <div class="card-toolbar"></div>
        </div>
        <div class="card-body">
            <form id="form-filter-harapan" class="">
                <div class="row mb-5">
                    <div class="col-md-6">
                        <label for="skor_jawaban_harapan" class="form-label font-weight-bold text-primary">Filter
                            Berdasarkan
                            Pilihan Jawaban</label>
                        <select id="skor_jawaban_harapan" class="form-control" onchange="updateUnitHarapan();">
                            <option value="">Please Select</option>
                            <option value="1">Skor 1</option>
                            <option value="2">Skor 2</option>
                            <option value="3">Skor 3</option>
                            <option value="4">Skor 4</option>
                        </select>

                    </div>
                </div>
            </form>
            <table id="table_harapan" class="table table-bordered mt-5" cellspacing="0" width="100%">
                <thead class="">
                    <tr>
                        <th width="5%">No.</th>
                        <!-- <th>Nama Responden</th> -->
                        <th>Pilihan Jawaban</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>

        </div>
    </div>
    @endif










    <div class="card card-custom card-sticky mt-5">
        <div class="card-header">
            <div class="card-title">Analisa</div>
            <div class="card-toolbar"></div>
        </div>
        <div class="card-body">
            <p>
                Anda bisa mengisi analisa pada bidang ini.
            </p>
            <?php echo form_open($form_action); ?>


            <input type="hidden" name="id_unsur_pelayanan" value="{{ $ci->uri->segment(5) }}">

            {{--<div class="form-group row">
                <label class="col-sm-3 col-form-label
                        font-weight-bold">Saran & Masukan <span style="color: red;">*</span></label>
                <div class="col-sm-9">
                    @php
                    echo form_textarea($saran_masukan);
                    @endphp
                </div>
            </div>--}}

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

            {{--<div class="form-group row">
                <label class="col-sm-3 col-form-label
                        font-weight-bold">Kegiatan / Program <span style="color: red;">*</span></label>
                <div class="col-sm-9">
                    @php
                    echo form_textarea($kegiatan);
                    @endphp
                </div>
            </div>--}}

            <div class="form-group row">
                <label class="col-sm-3 col-form-label
                        font-weight-bold">Waktu <span style="color: red;">*</span></label>

                <div class="input-group input-append date col-sm-9" id="datepicker" data-date="12-{{date('Y')}}"
                    data-date-format="mm-yyyy">
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
                <?php echo form_submit('submit', 'Simpan', ['class' => 'btn btn-primary font-weight-bold']); ?>
            </div>

            <?php echo form_close(); ?>

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
    table = $('#table_unsur_<?php echo $row->id ?>').DataTable({
        "processing": true,
        "serverSide": true,
        "order": [],
        "language": {
            "processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',
        },
        "ajax": {
            "url": "<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/analisa-survei/ajax-list-analisa/' . $row->id ?>",
            "type": "POST",
            "data": function(data) {
                data.skor_jawaban_unsur = $('#skor_jawaban_unsur_<?php echo $row->id ?>').val();
            }
        },

        "columnDefs": [{
            "targets": [-1],
            "orderable": false,
        }, ],

    });
});
$('#btn-filter-unsur_<?php echo $row->id ?>').click(function() {
    // table.ajax.reload();
    $('#table_unsur_<?php echo $row->id ?>').DataTable().ajax.reload()
});
$('#btn-reset-unsur_<?php echo $row->id ?>').click(function() {
    $('#form-filter-unsur_<?php echo $row->id ?>')[0].reset();
    $('#table_unsur_<?php echo $row->id ?>').DataTable().ajax.reload()
    // table.ajax.reload();
});

function updateUnitUnsur_<?php echo $row->id ?>() {
    $('#table_unsur_<?php echo $row->id ?>').DataTable().ajax.reload(null, false);
    // table.ajax.reload(null, false);
}
</script>

<script>
$(document).ready(function() {
    table = $('#table_harapan_<?php echo $row->id ?>').DataTable({

        "processing": true,
        "serverSide": true,
        "order": [],
        "language": {
            "processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',
        },
        "ajax": {
            "url": "<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/analisa-survei/ajax-list-analisa-harapan/' . $row->id ?>",
            "type": "POST",
            "data": function(data) {
                data.skor_jawaban_harapan = $('#skor_jawaban_harapan_<?php echo $row->id ?>').val();
            }
        },

        "columnDefs": [{
            "targets": [-1],
            "orderable": false,
        }, ],

    });
});
$('#btn-filter-harapan_<?php echo $row->id ?>').click(function() {
    // table.ajax.reload();
    $('#table_harapan_<?php echo $row->id ?>').DataTable().ajax.reload()
});
$('#btn-reset-harapan_<?php echo $row->id ?>').click(function() {
    $('#form-filter-harapan_<?php echo $row->id ?>')[0].reset();
    $('#table_harapan_<?php echo $row->id ?>').DataTable().ajax.reload()
    // table.ajax.reload();
});

function updateUnitHarapan_<?php echo $row->id ?>() {
    $('#table_harapan_<?php echo $row->id ?>').DataTable().ajax.reload(null, false);
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
            "url": "<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/analisa-survei/ajax-list-analisa/' . $ci->uri->segment(5) ?>",
            "type": "POST",
            "data": function(data) {
                data.skor_jawaban_unsur = $('#skor_jawaban_unsur').val();
            }
        },

        "columnDefs": [{
            "targets": [-1],
            "orderable": false,
        }, ],

    });
});
$('#btn-filter-unsur').click(function() {
    // table.ajax.reload();
    $('#table_unsur').DataTable().ajax.reload()
});
$('#btn-reset-unsur').click(function() {
    $('#form-filter-unsur')[0].reset();
    $('#table_unsur').DataTable().ajax.reload()
    // table.ajax.reload();
});

function updateUnitUnsur() {
    $('#table_unsur').DataTable().ajax.reload(null, false);
    // table.ajax.reload(null, false);
}
</script>

<script>
$(document).ready(function() {
    table = $('#table_harapan').DataTable({

        "processing": true,
        "serverSide": true,
        "order": [],
        "language": {
            "processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',
        },
        "ajax": {
            "url": "<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/analisa-survei/ajax-list-analisa-harapan/' . $ci->uri->segment(5) ?>",
            "type": "POST",
            "data": function(data) {
                data.skor_jawaban_harapan = $('#skor_jawaban_harapan').val();
            }
        },

        "columnDefs": [{
            "targets": [-1],
            "orderable": false,
        }, ],

    });
});
$('#btn-filter-harapan').click(function() {
    // table.ajax.reload();
    $('#table_harapan').DataTable().ajax.reload()
});
$('#btn-reset-harapan').click(function() {
    $('#form-filter-harapan')[0].reset();
    $('#table_harapan').DataTable().ajax.reload()
    // table.ajax.reload();
});

function updateUnitHarapan() {
    $('#table_harapan').DataTable().ajax.reload(null, false);
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
    .create(document.querySelector('#saran_masukan'))
    .then(editor => {
        console.log(editor);
    })
    .catch(error => {
        console.error(error);
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
ClassicEditor
    .create(document.querySelector('#kegiatan'))
    .then(editor => {
        console.log(editor);
    })
    .catch(error => {
        console.error(error);
    });
</script>
@endsection