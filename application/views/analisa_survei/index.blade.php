@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')
<link href="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
@endsection

@section('content')

<div class="container-fluid">
    @include("include_backend/partials_no_aside/_inc_menu_repository")

    <div class="row mt-5">
        <div class="col-md-3">
            @include('manage_survey/menu_data_survey')
        </div>
        <div class="col-md-9">

            <div class="card card-custom bgi-no-repeat gutter-b aos-init aos-animate" style="height: 120px; background-color: #1c2840; background-position: calc(100% + 0.5rem) 100%; background-size: 100% auto; background-image: url(/assets/img/banner/taieri.svg)" data-aos="fade-down">
                <div class="card-body d-flex align-items-center">
                    <div>
                        <h4 class="text-white font-weight-bolder line-height-lg">{{strtoupper($title)}}</h4>

                        <h4 class="text-white text-uppercase"><b>Nilai IKM : {{ROUND($nilai_tertimbang, 3)}}
                                <?php foreach ($definisi_skala->result() as $obj) {
                                    if ($ikm <= $obj->range_bawah && $ikm >= $obj->range_atas) {
                                        echo '(' . $obj->kategori . ')';
                                    }
                                }
                                if ($ikm <= 0) {
                                    echo  'NULL';
                                }
                                ?>
                            </b>
                        </h4>
                    </div>
                </div>
            </div>



            <div class="card card-body" data-aos="fade-down">
                <!-- <h4 class="text-primary"><b>Unsur</b></h4> -->
                <div class="table-responsive">
                    <table class="table table-hover table-bordered example" style="width:100%">
                        <thead class="bg-secondary">
                            <tr>
                                <th width="4%" valign="top">No</th>
                                <th>Layanan</th>
                                <th>Indeks</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>

                            @php
                            $a = 1;
                            $layanan = $ci->db->get_where("layanan_survei_$profiles->table_identity", array('is_active' => 1));
                            @endphp

                            @foreach ($layanan->result() as $value)
                            @php
                            $indeks = $ci->db->query("SELECT
                            IF(id_parent = 0,unsur_pelayanan_$table_identity.id, unsur_pelayanan_$table_identity.id_parent) AS id_sub,
                            (SELECT nomor_unsur FROM unsur_pelayanan_$table_identity unsur_sub WHERE unsur_sub.id = id_sub) AS nomor_unsur,

                            ((SUM(skor_jawaban)/COUNT(DISTINCT survey_$table_identity.id_responden)) / (COUNT(id_parent)/COUNT(DISTINCT survey_$table_identity.id_responden))) AS nilai_per_unsur

                            FROM jawaban_pertanyaan_unsur_$table_identity
                            JOIN pertanyaan_unsur_pelayanan_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id
                            JOIN unsur_pelayanan_$table_identity ON pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan = unsur_pelayanan_$table_identity.id
                            JOIN survey_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden
                            JOIN responden_$table_identity ON survey_$table_identity.id_responden = responden_$table_identity.id
                            WHERE survey_$table_identity.is_submit = 1 && jawaban_pertanyaan_unsur_$table_identity.skor_jawaban != '0.0' && responden_$table_identity.id_layanan_survei = $value->id
                            GROUP BY id_sub");

                            $total = [];
                            $ikm_layanan = 0;
                            foreach ($indeks->result() as $row) {
                            $total[] = $row->nilai_per_unsur;
                            $ikm_layanan = array_sum($total) / count($total);
                            }
                            @endphp
                            <tr>
                                <td class="text-center">{{ $a++ }}</td>
                                <td><a data-toggle="collapse" href="#Collapse{{$value->id}}"><b class="text-primary">{{ $value->nama_layanan }}</b></a>
                                  
                                @php
                                $analisa = $ci->db->query("SELECT *, analisa_$table_identity.id AS id_analisa
                                FROM analisa_$table_identity
                                JOIN unsur_pelayanan_$table_identity ON analisa_$table_identity.id_unsur_pelayanan = unsur_pelayanan_$table_identity.id
                                WHERE id_layanan_survei = $value->id
                                ORDER BY id_unsur_pelayanan ASC
                                ");
                                $no = 1;
                                @endphp
                                <div class="collapse multi-collapse {{$analisa->num_rows() > 0 ? 'show' : ''}}" id="Collapse{{$value->id}}">

                                @if($analisa->num_rows() > 0)
                                <hr>
                                @foreach($analisa->result() as $row)
                                    <ul>
                                        <li><b>{{$row->nomor_unsur . '. ' . $row->nama_unsur_pelayanan}}</b>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="badge badge-info border dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>

                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="{{base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/analisa-survei/edit/' . $row->id_analisa}}">Edit</a>
                                                    <a class="dropdown-item" href="javascript:void(0)" onclick="delete_analisa_survei({{$row->id_analisa}})">Delete</a>
                                                </div>
                                            </div>
                                        </li>
                                        <ul>
                                            <li><b>Faktor Yang Mempengaruhi :</b> {!! $row->faktor_penyebab !!}</li>
                                            <li><b>Rencana Tindak Lanjut :</b> {!! $row->rencana_perbaikan !!}</li>
                                            <li><b>Waktu :</b> {!! $row->waktu !!}</li>
                                            <li><b>Penanggung Jawab :</b> {!! $row->penanggung_jawab !!}</li>
                                        </ul>
                                    </ul>
                                    <hr>
                                @endforeach
                                @else 


                                <div class="text-center mt-3"><i>Belum ada analisa yang dibuat.</i></div>

                                @endif
                                </div>


                                </td>
                                <td>{{ROUND($ikm_layanan,3)}}
                                <?php
                                    $konversi = $ikm_layanan * $skala_likert;
                                    foreach ($definisi_skala->result() as $obj) {
                                        if ($konversi <= $obj->range_bawah && $konversi >= $obj->range_atas) {
                                            echo '(' . $obj->kategori . ')';
                                        }
                                    }
                                    if ($konversi <= 0) {
                                        echo  '(NULL)';
                                    }
                                    ?>
                                </td>
                                <td>
                                    @if($konversi == 0)
                                    <a class="btn btn-light-primary btn-sm font-weight-bold" onclick="cek()"><i class="fa fa-book"></i> Analisa Layanan</a>
                                    @else
                                    <a href="{{ base_url() }}{{ $ci->session->userdata('username'); }}/{{ $ci->uri->segment(2) }}/analisa-survei/{{ $value->id }}" class="btn btn-light-primary btn-sm font-weight-bold"><i class="fa fa-book"></i> Analisa Layanan</a>
                                    @endif
                                </td>
                            </tr>

                            @php
                            $no++
                            @endphp
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


<script>
function cek() {
    Swal.fire({
        icon: 'info',
        title: 'Informasi',
        text: 'Belum ada responden yang mengisi pelayanan tersebut!',
        allowOutsideClick: false,
        confirmButtonColor: 'rgb(221, 51, 51)',
        confirmButtonText: 'Ya, Saya mengerti !',
    });
}
</script>
@endsection