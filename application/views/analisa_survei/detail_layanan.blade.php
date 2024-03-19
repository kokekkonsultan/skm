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


            <div class="card card-custom bgi-no-repeat gutter-b aos-init aos-animate"
                style="height: 150px; background-color: #1c2840; background-position: calc(100% + 0.5rem) 100%; background-size: 100% auto; background-image: url(/assets/img/banner/taieri.svg)"
                data-aos="fade-down">
                <div class="card-body d-flex align-items-center">
                    <div>
                        <h5 class="text-white">Jenis Pelayanan :</h5>
                        <h5 class="text-white font-weight-bolder line-height-lg">
                            @php
                            $table_identity = $profiles->table_identity;
                            $layanan = $ci->db->get_where("layanan_survei_$table_identity", ['id' =>
                            $ci->uri->segment(4)])->row();
                            foreach ($definisi_skala->result() as $obj) {
                            if ($nilai_konversi <= $obj->range_bawah && $nilai_konversi >= $obj->range_atas) {
                                $kategori = $obj->kategori;
                                }
                                }
                                if ($nilai_konversi <= 0) { $kategori='NULL' ; } echo strtoupper($layanan->nama_layanan)
                                    . ' - '. ROUND($nilai_tertimbang, 3) . ' (' . $kategori . ')';
                                    @endphp
                        </h5>
                        <a class="btn btn-secondary btn-sm font-weight-bold"
                            href="{{base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/analisa-survei' }}"><i
                                class="fa fa-arrow-left"></i> Kembali</a>
                    </div>
                </div>
            </div>

            <div class="card card-body" data-aos="fade-down">
                <div class="table-responsive">
                    <table class="table table-hover example" style="width:100%">
                        <thead class="">
                            <tr>
                                <th>No</th>
                                <th>Unsur</th>
                                <th>Indeks</th>
                                <th>Kategori</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            @php
                            $no = 1;
                            @endphp
                            @foreach ($nilai_per_unsur->result() as $value)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $value->nomor_unsur }}. {{ $value->nama_unsur_pelayanan }}</td>
                                <td>{{ ROUND($value->nilai_per_unsur,2) }}</td>
                                <td>
                                    <?php
                                    $nilai_konversi = $value->nilai_per_unsur * $skala_likert;
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
                                <td>
                                    <a href="{{ base_url() }}{{ $ci->session->userdata('username'); }}/{{ $ci->uri->segment(2) }}/analisa-survei/{{ $ci->uri->segment(4) }}/{{ $value->id_sub }}"
                                        class="btn btn-light-primary btn-sm font-weight-bold"><i class="fa fa-book"></i>
                                        Lakukan Analisa</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>


            <div class="card card-body mt-5" data-aos="fade-down">
                <h5 class="text-primary">Hasil Analisa {{$layanan->nama_layanan}}</h5>
                <hr>


                <div class="table-responsive">
                    <table class="table table-hover example" style="width:100%">
                        <thead class="">
                            <tr>
                                <th></th>
                                <th width="10%"></th>
                            </tr>
                        </thead>

                        <tbody>

                            @php
                            $analisa = $ci->db->query("SELECT *, analisa_$table_identity.id AS id_analisa
                            FROM analisa_$table_identity
                            JOIN layanan_survei_$table_identity ON analisa_$table_identity.id_layanan_survei =
                            layanan_survei_$table_identity.id
                            JOIN unsur_pelayanan_$table_identity ON analisa_$table_identity.id_unsur_pelayanan =
                            unsur_pelayanan_$table_identity.id");
                            $no = 1;
                            @endphp

                            @foreach($analisa->result() as $row)
                            <tr>
                                <td>
                                    <b
                                        class="text-primary">{{$row->nomor_unsur . '. ' . $row->nama_unsur_pelayanan}}</b>
                                    <hr>
                                    <ul>
                                        <li><b>Faktor Yang Mempengaruhi :</b> {!! $row->faktor_penyebab !!}</li>
                                        <li><b>Rencana Tindak Lanjut :</b> {!! $row->rencana_perbaikan !!}</li>
                                        <li><b>Waktu :</b> {!! $row->waktu !!}</li>
                                        <li><b>Penanggung Jawab :</b> {!! $row->penanggung_jawab !!}</li>
                                    </ul>
                                </td>
                                <td>
                                    <a href="{{base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/analisa-survei/edit/' . $row->id_analisa}}"
                                        class="btn btn-light-primary btn-sm font-weight-bold shadow"><i
                                            class="fa fa-edit"></i> Edit</a>

                                    <hr>
                                    <a class="btn btn-light-primary btn-sm font-weight-bold shadow"
                                        href="javascript:void(0)" title="Hapus ' . $value->nama_unsur_pelayanan . '"
                                        onclick="delete_analisa_survei({{$row->id_analisa}})"><i
                                            class="fa fa-trash"></i> Delete</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('javascript')
<script src="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.9.11/tinymce.min.js"></script>
<script src="{{ base_url() }}assets/themes/metronic/assets/plugins/custom/datatables/datatables.bundle.js"></script>
<script>
$(document).ready(function() {
    $('.example').DataTable({
        "lengthMenu": [
            [5, 10, 25, 50, 100, -1],
            [5, 10, 25, 50, 100, "Semua data"]
        ],
        "pageLength": 5,
    });
});
</script>


<script>
// $(document).ready(function() {
//     table = $('#table').DataTable({

//         "processing": true,
//         "serverSide": true,
//         "order": [],
//         "language": {
//             "processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',
//         },
//         "ajax": {
//             "url": "<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/analisa-survei/ajax-list' ?>",
//             "type": "POST",
//             "data": function(data) {}
//         },

//         "columnDefs": [{
//             "targets": [-1],
//             "orderable": false,
//         }, ],

//     });
// });

// $('#btn-filter').click(function() {
//     table.ajax.reload();
// });
// $('#btn-reset').click(function() {
//     $('#form-filter')[0].reset();
//     table.ajax.reload();
// });

function delete_analisa_survei(id) {
    if (confirm('Are you sure delete this data?')) {
        $.ajax({
            url: "<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/analisa-survei/delete/' ?>" +
                id,
            type: "POST",
            dataType: "JSON",
            success: function(data) {
                if (data.status) {

                    Swal.fire(
                        'Informasi',
                        'Berhasil menghapus data',
                        'success'
                    );
                    window.setTimeout(function() {
                        location.reload()
                    }, 1500);

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