@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')
<link href="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
@endsection

@section('content')

<div class="container-fluid">

    <div class="row mt-5">
        <div class="col-md-12">

            <div class="card card-custom bgi-no-repeat gutter-b" style="height: 150px; background-color: #1c2840; background-position: calc(100% + 0.5rem) 100%; background-size: 100% auto; background-image: url(/assets/img/banner/taieri.svg)" data-aos="fade-down">
                <div class="card-body d-flex align-items-center">
                    <div>
                        <h3 class="text-white font-weight-bolder line-height-lg mb-5">
                            {{strtoupper($title)}}
                        </h3>

                    </div>
                </div>
            </div>

            <div class="card card-custom card-sticky" data-aos="fade-down">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="table" class="table table-bordered table-hover" cellspacing="0" width="100%" style="font-size: 12px;">
                            <thead class="bg-secondary">
                                <tr>
                                    <th width="5%">No.</th>
                                    <th>Nama Kelompok</th>
                                    <th>Objek Survei</th>
                                    <th>Indeks</th>
                                </tr>
                            </thead>
                            <tbody>

                                @php
                                $a = 1;
                                @endphp
                                @foreach($ci->db->query("SELECT * FROM kelompok_anak_induk_$table_identity")->result() as $row)

                                @php
                                $id_manage_survey = implode(", ", unserialize($row->id_objek));
                                $manage_survey = $ci->db->query("SELECT *, (SELECT first_name FROM users WHERE id = manage_survey.id_user) AS first_name,
                                (SELECT last_name FROM users WHERE id = manage_survey.id_user) AS last_name
                                FROM manage_survey WHERE id IN ($id_manage_survey)");

                                if ($manage_survey->num_rows() > 0) {

                                $no = 1;
                                $array_anak = [];
                                $nilai = [];
                                foreach ($manage_survey->result() as $value) {


                                    if ($ci->db->get_where("survey_$value->table_identity", array('is_submit' => 1))->num_rows() > 0) {

                                        $nilai_per_unsur[$no] = $ci->db->query("SELECT IF(id_parent = 0,unsur_pelayanan_$value->table_identity.id, unsur_pelayanan_$value->table_identity.id_parent) AS id_sub,
                                        ((SUM(skor_jawaban)/COUNT(DISTINCT survey_$value->table_identity.id_responden))/(COUNT(id_parent)/COUNT(DISTINCT survey_$value->table_identity.id_responden))) AS nilai_per_unsur, (((SUM(skor_jawaban)/COUNT(DISTINCT survey_$value->table_identity.id_responden))/(COUNT(id_parent)/COUNT(DISTINCT survey_$value->table_identity.id_responden)))/(SELECT COUNT(id) FROM unsur_pelayanan_$value->table_identity WHERE id_parent = 0)) AS rata_rata_bobot

                                        FROM jawaban_pertanyaan_unsur_$value->table_identity
                                        JOIN pertanyaan_unsur_pelayanan_$value->table_identity ON jawaban_pertanyaan_unsur_$value->table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$value->table_identity.id
                                        JOIN unsur_pelayanan_$value->table_identity ON pertanyaan_unsur_pelayanan_$value->table_identity.id_unsur_pelayanan = unsur_pelayanan_$value->table_identity.id
                                        JOIN survey_$value->table_identity ON jawaban_pertanyaan_unsur_$value->table_identity.id_responden = survey_$value->table_identity.id_responden
                                        WHERE survey_$value->table_identity.is_submit = 1 AND jawaban_pertanyaan_unsur_$value->table_identity.skor_jawaban != '0.0'
                                        GROUP BY id_sub");

                                        $nilai_bobot[$no] = [];
                                        foreach ($nilai_per_unsur[$no]->result() as $get) {
                                            $nilai_bobot[$no][] = $get->rata_rata_bobot;
                                            $nilai_tertimbang[$no] = array_sum($nilai_bobot[$no]);
                                        }

                                        $nilai[] = $nilai_tertimbang[$no];
                                        $nilai_survei = ROUND($nilai_tertimbang[$no],3);
                                    } else {
                                        $nilai_survei = 0;
                                        $nilai[] = 0;
                                    }

                                    $array_anak[] = '<li>' . $value->survey_name . ' <b>(' . $value->first_name . ' ' . $value->last_name . ') - <span class="text-primary">' . $nilai_survei . '</span></b></li>';
                                    $no++;
                                }

                                    $data_anak = $array_anak;
                                    $hasil_akhir = array_sum($nilai)/count($nilai);
                                } else {
                                    $data_anak = '';
                                    $hasil_akhir = 0;
                                }
                                @endphp


                                <tr>
                                    <td>{{$a++}}</td>
                                    <td>{{$row->nama_kelompok}}</td>
                                    <td>{!! implode("", $data_anak) !!}</td>

                                    <td><span class="badge badge-dark">{{ROUND($hasil_akhir,3)}}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>


                </div>
            </div>

        </div>
    </div>

</div>
@endsection

@section('javascript')
<script src="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.js"></script>

<script>
    $(document).ready(function() {
        table = $('#table').DataTable();
    });
</script>
@endsection