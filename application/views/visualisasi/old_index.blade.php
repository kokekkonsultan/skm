@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')
<script src="https://cdn.fusioncharts.com/fusioncharts/latest/fusioncharts.js"></script>
<script src="{{ base_url() }}assets/vendor/fusioncharts-suite-xt/js/themes/fusioncharts.theme.accessibility.js">
</script>
<script src="{{ base_url() }}assets/vendor/fusioncharts-suite-xt/js/themes/fusioncharts.theme.candy.js"></script>
<script src="{{ base_url() }}assets/vendor/fusioncharts-suite-xt/js/themes/fusioncharts.theme.carbon.js"></script>
<script src="{{ base_url() }}assets/vendor/fusioncharts-suite-xt/js/themes/fusioncharts.theme.fint.js"></script>
<script src="{{ base_url() }}assets/vendor/fusioncharts-suite-xt/js/themes/fusioncharts.theme.fusion.js"></script>
<script src="{{ base_url() }}assets/vendor/fusioncharts-suite-xt/js/themes/fusioncharts.theme.gammel.js"></script>
<script src="{{ base_url() }}assets/vendor/fusioncharts-suite-xt/js/themes/fusioncharts.theme.ocean.js"></script>
<script src="{{ base_url() }}assets/vendor/fusioncharts-suite-xt/js/themes/fusioncharts.theme.umber.js"></script>
<script src="{{ base_url() }}assets/vendor/fusioncharts-suite-xt/js/themes/fusioncharts.theme.zune.js"></script>


<style type="text/css">
[pointer-events="bounding-box"] {
    display: none
}
</style>
@endsection

@section('content')

<div class="container-fluid">
    @include("include_backend/partials_no_aside/_inc_menu_repository")

    <div class="row mt-5">
        <div class="col-md-3">
            @include('manage_survey/menu_data_survey')
        </div>
        <div class="col-md-9">

            <div class="card card-custom card-sticky mb-5" data-aos="fade-down">
                <div class="card-body">

                    <!-- <h5>Indeks Keseluruhan</h5> -->

                    <div id="chart"></div>


                    <table class="table table-bordered mt-5">
                        <tr class="bg-light">
                            <th style="vertical-align: middle; text-align:center;">Unsur</th>
                            <th style="vertical-align: middle; text-align:center;">Nilai Interval<br>(NI)</th>
                            <th style="vertical-align: middle; text-align:center;">Nilai Interval Konversi<br>(NIK)</th>
                            <th style="vertical-align: middle; text-align:center;">Mutu Pelayanan<br>(x)</th>
                            <th style="vertical-align: middle; text-align:center;">Kinerja Unit Pelayanan<br>(y)</th>
                        </tr>


                        <?php
                        $no = 1;
                        $nama_unsur_pelayanan = [];
                        foreach ($nilai_per_unsur->result() as $value) { ?>

                        @php
                        $nama_unsur_pelayanan[] = $value->nama_unsur_pelayanan;
                        $npu = $value->nilai_per_unsur;
                        $nilai_konversi = $value->nilai_per_unsur * 25;


                        if($nilai_konversi >= 25 && $nilai_konversi <= 64.99) { $ktg='Tidak baik' ; $mutu='D' ; } elseif
                            ($nilai_konversi>= 65 && $nilai_konversi <= 76.60) { $ktg='Kurang baik' ; $mutu='C' ; }
                                elseif ($nilai_konversi>= 76.61 && $nilai_konversi <= 88.30) { $ktg='Baik' ; $mutu='B' ;
                                    } elseif ($nilai_konversi>= 88.31 && $nilai_konversi <= 100) { $ktg='Sangat baik' ;
                                        $mutu='A' ; } else { $ktg='NULL' ; $mutu='NULL' ; }; @endphp <tr>
                                        <!-- <td class="text-center">{{ substr($value->nomor_unsur, 0, 2) }}</td> -->
                                        <td>
                                            <?php echo $value->nomor_unsur . '. ' . $value->nama_unsur_pelayanan ?>
                                        </td>
                                        <td class="text-center">{{ ROUND($value->nilai_per_unsur,3) }}</td>
                                        <td class="text-center">{{ ROUND($nilai_konversi,2)}}</td>
                                        <td class="text-center">{{$mutu}}</td>
                                        <td class="text-center">{{$ktg}}</td>
                                        </tr>
                                        <?php } ?>
                    </table>

                    @php
                    $nama_unsur = [];
                    $nilai_index = [];
                    foreach ($nilai_per_unsur->result() as $value) {
                    $nama_unsur[] = "'" . $value->nama_unsur_pelayanan . "'";
                    $nilai_index[] = $value->nilai_per_unsur;

                    $data_chart[] = str_word_count($value->nama_unsur_pelayanan) > 2 ? '{label:"' .
                    substr($value->nomor_unsur, 0, 2) . '. ' . substr($value->nama_unsur_pelayanan, 0, 7) . ' [...] ",
                    value:"' . $value->nilai_per_unsur
                    . '"}' : '{label:"' . substr($value->nomor_unsur, 0, 2) . '. ' . $value->nama_unsur_pelayanan . '",
                    value:"' .
                    $value->nilai_per_unsur . '"}';
                    }

                    $nama_unsur = implode(", ", $nama_unsur);
                    $nilai_index = implode(", ", $nilai_index);
                    $get_data_chart = implode(", ", $data_chart);
                    @endphp


                    <a data-toggle="modal" data-target="#exampleModalCenter" class="font-weight-bold text-primary">Tabel
                        Nilai IKM Berdasarkan Permenpan Nomor 14 Tahun 2017</a>

                    <!-- Modal -->
                    <div class="modal fade bd-example-modal-lg" id="exampleModalCenter" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <div class="card card-body">
                                        <img src="<?php echo base_url() . 'assets/survey/img/nilai_index_permenpan.png' ?>"
                                            alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>



            <?php
            //$noc = 1;
            foreach ($unsur_pelayanan->result() as $value) { ?>

            <div class="card mb-5" data-aos="fade-down">
                <div class="card-body">

                    <!-- <div class="alert alert-secondary text-center" role="alert">
                        <h5>{{ $value->nomor_unsur . '. ' . $value->nama_unsur_pelayanan }}</h5>
                        <?php echo $value->isi_pertanyaan_unsur ?>
                    </div> -->

                    @php
                    $sub_unsur = $ci->db->get_where("unsur_pelayanan_$table_identity", ['id_parent' =>
                    $value->id_unsur_pelayanan]);
                    @endphp

                    <?php if ($sub_unsur->num_rows() > 0) { ?>

                    <div id="pie_<?php echo $value->id ?>" class="d-flex justify-content-center"></div>

                    <div class="row mb-5">
                        <div class="col-xl-9 font-weight-bold font-size-h6">
                            Kesimpulan {{ $value->nama_unsur_pelayanan }}
                        </div>
                        <div class="col-xl-3 text-right">
                            <a class="btn btn-primary btn-sm font-weight-bold shadow" data-toggle="collapse"
                                href="#collapseExample{{ $value->id }}" role="button" aria-expanded="false"
                                aria-controls="collapseExample{{ $value->id }}">
                                <i class="fa fa-info-circle"></i> Lihat Detail Sub Unsur
                            </a>
                        </div>
                    </div>


                    <div class="collapse" id="collapseExample{{ $value->id }}">
                        <div class="card card-body">

                            @php
                            $ci->db->select("*, unsur_pelayanan_$table_identity.id AS id_unsur_pelayanan,
                            pertanyaan_unsur_pelayanan_$table_identity.id AS id_pertanyaan_unsur_pelayanan");
                            $ci->db->from("unsur_pelayanan_$table_identity");
                            $ci->db->join("pertanyaan_unsur_pelayanan_$table_identity",
                            "pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan =
                            unsur_pelayanan_$table_identity.id");
                            $ci->db->where(['id_parent' => $value->id_unsur_pelayanan]);
                            $unsur_pelayanan_a = $ci->db->get();
                            @endphp

                            <?php foreach ($unsur_pelayanan_a->result() as $element_a) { ?>

                            @php
                            $ci->db->select("*, unsur_pelayanan_$table_identity.id AS id_unsur_pelayanan,
                            pertanyaan_unsur_pelayanan_$table_identity.id AS id_pertanyaan_unsur_pelayanan");
                            $ci->db->from("unsur_pelayanan_$table_identity");
                            $ci->db->join("pertanyaan_unsur_pelayanan_$table_identity",
                            "pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan =
                            unsur_pelayanan_$table_identity.id");
                            $ci->db->where(["unsur_pelayanan_$table_identity.id" => $element_a->id_unsur_pelayanan]);
                            $unsur_pelayanan_aa = $ci->db->get()->row();
                            @endphp



                            <div class="alert alert-secondary text-center mt-5" role="alert">
                                <h5>{{ $unsur_pelayanan_aa->nomor_unsur . '. ' . $unsur_pelayanan_aa->nama_unsur_pelayanan }}
                                </h5>
                                <?php echo $unsur_pelayanan_aa->isi_pertanyaan_unsur ?>
                            </div>

                            @php
                            $id_pertanyaan_unsur_pelayanan = $unsur_pelayanan_aa->id_pertanyaan_unsur_pelayanan;
                            $persentase_detail = $ci->db->query("
                            SELECT
                            kup.id AS id_kup,
                            kup.nama_kategori_unsur_pelayanan,
                            ( SELECT COUNT(skor_jawaban) FROM jawaban_pertanyaan_unsur_$table_identity JOIN
                            responden_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden =
                            responden_$table_identity.id
                            JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
                            WHERE is_submit = 1 &&
                            id_pertanyaan_unsur = $id_pertanyaan_unsur_pelayanan AND skor_jawaban =
                            kup.nomor_kategori_unsur_pelayanan) AS jumlah,
                            ( SELECT ROUND(( SELECT COUNT(skor_jawaban) FROM jawaban_pertanyaan_unsur_$table_identity
                            JOIN responden_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden =
                            responden_$table_identity.id
                            JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
                            WHERE is_submit = 1 && id_pertanyaan_unsur = $id_pertanyaan_unsur_pelayanan AND skor_jawaban
                            =
                            kup.nomor_kategori_unsur_pelayanan) / ( SELECT COUNT(*) FROM
                            jawaban_pertanyaan_unsur_$table_identity JOIN responden_$table_identity ON
                            jawaban_pertanyaan_unsur_$table_identity.id_responden = responden_$table_identity.id
                            JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
                            WHERE is_submit = 1 && id_pertanyaan_unsur =
                            $id_pertanyaan_unsur_pelayanan ) * 100,2) ) AS persentase
                            FROM kategori_unsur_pelayanan_$table_identity kup
                            WHERE id_pertanyaan_unsur = $id_pertanyaan_unsur_pelayanan
                            ");
                            @endphp

                            <table class="table table-bordered">
                                <tr>
                                    <th width="7%">No</th>
                                    <th width="49%">Kategori</th>
                                    <th width="23%">Jumlah</th>
                                    <th width="21%">Persentase</th>
                                </tr>
                                @php
                                $no = 1;
                                $t_jum = 0;
                                $t_persen = 0;
                                @endphp

                                <?php foreach ($persentase_detail->result() as $val_p) { ?>
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $val_p->nama_kategori_unsur_pelayanan }}</td>
                                    <td>{{ $val_p->jumlah }}</td>
                                    <td>{{ ROUND($val_p->persentase,2) }} %</td>
                                </tr>
                                @php
                                $t_jum += $val_p->jumlah;
                                $t_persen += $val_p->persentase;
                                @endphp
                                <?php } ?>

                                <tr>
                                    <td colspan="2">
                                        <div align="center"><strong>TOTAL</strong></div>
                                    </td>
                                    <td>{{ $t_jum }}</td>
                                    <td>{{ ROUND($t_persen,2) }} %</td>
                                </tr>
                            </table>
                            <hr>

                            <?php } ?>



                            @php
                            $ci->db->select('id_pertanyaan_unsur');
                            $ci->db->from("kategori_unsur_pelayanan_$table_identity");
                            $ci->db->where('id', $val_p->id_kup);
                            $get_opsi = $ci->db->get()->row();

                            $ci->db->select('nama_kategori_unsur_pelayanan');
                            $ci->db->from("kategori_unsur_pelayanan_$table_identity");
                            $ci->db->where('id_pertanyaan_unsur', $get_opsi->id_pertanyaan_unsur);
                            $get_data_opsi = $ci->db->get()->result_array();
                            @endphp

                            @php
                            $rel_data = $ci->db->query("
                            SELECT *,
                            pertanyaan_unsur_pelayanan_$table_identity.id AS id_pertanyaan_unsur_pelayanan,
                            ( SELECT COUNT(skor_jawaban) FROM jawaban_pertanyaan_unsur_$table_identity JOIN
                            responden_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden =
                            responden_$table_identity.id
                            JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
                            WHERE is_submit = 1 &&
                            id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id AND skor_jawaban = 1) AS
                            jumlah_1,
                            ( SELECT COUNT(skor_jawaban) FROM jawaban_pertanyaan_unsur_$table_identity JOIN
                            responden_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden =
                            responden_$table_identity.id
                            JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
                            WHERE is_submit = 1 &&
                            id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id AND skor_jawaban = 2) AS
                            jumlah_2,
                            ( SELECT COUNT(skor_jawaban) FROM jawaban_pertanyaan_unsur_$table_identity JOIN
                            responden_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden =
                            responden_$table_identity.id
                            JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
                            WHERE is_submit = 1 &&
                            id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id AND skor_jawaban = 3) AS
                            jumlah_3,
                            ( SELECT COUNT(skor_jawaban) FROM jawaban_pertanyaan_unsur_$table_identity JOIN
                            responden_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden =
                            responden_$table_identity.id
                            JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
                            WHERE is_submit = 1 &&
                            id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id AND skor_jawaban = 4) AS
                            jumlah_4,

                            ( SELECT ROUND(COUNT(skor_jawaban) / ( SELECT COUNT(skor_jawaban) FROM
                            jawaban_pertanyaan_unsur_$table_identity JOIN responden_$table_identity ON
                            jawaban_pertanyaan_unsur_$table_identity.id_responden = responden_$table_identity.id
                            JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
                            WHERE is_submit = 1 && id_pertanyaan_unsur =
                            pertanyaan_unsur_pelayanan_$table_identity.id) * 100, 2) FROM
                            jawaban_pertanyaan_unsur_$table_identity JOIN responden_$table_identity ON
                            jawaban_pertanyaan_unsur_$table_identity.id_responden = responden_$table_identity.id
                            JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
                            WHERE is_submit = 1 && id_pertanyaan_unsur =
                            pertanyaan_unsur_pelayanan_$table_identity.id AND skor_jawaban = 1 ) AS persentase_1,
                            ( SELECT ROUND(COUNT(skor_jawaban) / ( SELECT COUNT(skor_jawaban) FROM
                            jawaban_pertanyaan_unsur_$table_identity JOIN responden_$table_identity ON
                            jawaban_pertanyaan_unsur_$table_identity.id_responden = responden_$table_identity.id
                            JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
                            WHERE is_submit = 1 && id_pertanyaan_unsur =
                            pertanyaan_unsur_pelayanan_$table_identity.id) * 100, 2) FROM
                            jawaban_pertanyaan_unsur_$table_identity JOIN responden_$table_identity ON
                            jawaban_pertanyaan_unsur_$table_identity.id_responden = responden_$table_identity.id
                            JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
                            WHERE is_submit = 1 && id_pertanyaan_unsur =
                            pertanyaan_unsur_pelayanan_$table_identity.id AND skor_jawaban = 2 ) AS persentase_2,
                            ( SELECT ROUND(COUNT(skor_jawaban) / ( SELECT COUNT(skor_jawaban) FROM
                            jawaban_pertanyaan_unsur_$table_identity JOIN responden_$table_identity ON
                            jawaban_pertanyaan_unsur_$table_identity.id_responden = responden_$table_identity.id
                            JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
                            WHERE is_submit = 1 && id_pertanyaan_unsur =
                            pertanyaan_unsur_pelayanan_$table_identity.id) * 100, 2) FROM
                            jawaban_pertanyaan_unsur_$table_identity JOIN responden_$table_identity ON
                            jawaban_pertanyaan_unsur_$table_identity.id_responden = responden_$table_identity.id
                            JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
                            WHERE is_submit = 1 && id_pertanyaan_unsur =
                            pertanyaan_unsur_pelayanan_$table_identity.id AND skor_jawaban = 3 ) AS persentase_3,
                            ( SELECT ROUND(COUNT(skor_jawaban) / ( SELECT COUNT(skor_jawaban) FROM
                            jawaban_pertanyaan_unsur_$table_identity JOIN responden_$table_identity ON
                            jawaban_pertanyaan_unsur_$table_identity.id_responden = responden_$table_identity.id
                            JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
                            WHERE is_submit = 1 && id_pertanyaan_unsur =
                            pertanyaan_unsur_pelayanan_$table_identity.id) * 100, 2) FROM
                            jawaban_pertanyaan_unsur_$table_identity JOIN responden_$table_identity ON
                            jawaban_pertanyaan_unsur_$table_identity.id_responden = responden_$table_identity.id
                            JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
                            WHERE is_submit = 1 && id_pertanyaan_unsur =
                            pertanyaan_unsur_pelayanan_$table_identity.id AND skor_jawaban = 4 ) AS persentase_4,

                            ( SELECT COUNT(skor_jawaban) FROM jawaban_pertanyaan_unsur_$table_identity JOIN
                            responden_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden =
                            responden_$table_identity.id
                            JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
                            WHERE is_submit = 1 &&
                            id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id) AS jumlah_pengisi,
                            ( SELECT AVG(skor_jawaban) FROM jawaban_pertanyaan_unsur_$table_identity JOIN
                            responden_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden =
                            responden_$table_identity.id
                            JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
                            WHERE is_submit = 1 &&
                            id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id) AS rata_rata,
                            ( SELECT IF(( SELECT AVG(skor_jawaban) FROM jawaban_pertanyaan_unsur_$table_identity JOIN
                            responden_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden =
                            responden_$table_identity.id
                            JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
                            WHERE is_submit = 1 &&
                            id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id) > 3.5324,
                            'Sangat Baik',
                            IF(( SELECT AVG(skor_jawaban) FROM jawaban_pertanyaan_unsur_$table_identity JOIN
                            responden_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden =
                            responden_$table_identity.id
                            JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
                            WHERE is_submit = 1 &&
                            id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id) > 2.9,
                            'Baik',
                            IF(( SELECT AVG(skor_jawaban) FROM jawaban_pertanyaan_unsur_$table_identity JOIN
                            responden_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden =
                            responden_$table_identity.id
                            JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
                            WHERE is_submit = 1 &&
                            id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id) > 2.6,
                            'Kurang Baik','Tidak Baik'))
                            ) ) AS predikat

                            FROM unsur_pelayanan_$table_identity
                            JOIN pertanyaan_unsur_pelayanan_$table_identity ON
                            pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan =
                            unsur_pelayanan_$table_identity.id
                            WHERE unsur_pelayanan_$table_identity.id_parent = $value->id_unsur_pelayanan

                            ");
                            @endphp

                        </div>
                    </div>

                    <table width="100%" class="table table-bordered mt-7">
                        <tr>
                            <td width="39%" rowspan="2">
                                <div align="center"><strong>Unsur</strong></div>
                            </td>
                            <td colspan="4">
                                <div align="center"><strong>Persentase Persepsi Responden </strong></div>
                            </td>
                            <td width="14%" rowspan="2">
                                <div align="center"><strong>Indeks</strong></div>
                            </td>
                            <td width="17%" rowspan="2">
                                <div align="center"><strong>Predikat</strong></div>
                            </td>
                        </tr>
                        <tr>
                            <td width="7%">
                                <div align="center">
                                    <strong>{{ $get_data_opsi[0]['nama_kategori_unsur_pelayanan'] }}</strong>
                                </div>
                            </td>
                            <td width="8%">
                                <div align="center">
                                    <strong>{{ $get_data_opsi[1]['nama_kategori_unsur_pelayanan'] }}</strong>
                                </div>
                            </td>
                            <td width="7%">
                                <div align="center">
                                    <strong>{{ $get_data_opsi[2]['nama_kategori_unsur_pelayanan'] }}</strong>
                                </div>
                            </td>
                            <td width="8%">
                                <div align="center">
                                    <strong>{{ $get_data_opsi[3]['nama_kategori_unsur_pelayanan'] }}</strong>
                                </div>
                            </td>
                        </tr>

                        @php
                        $no = 0;
                        $jum_persentase_1 = 0;
                        $jum_persentase_2 = 0;
                        $jum_persentase_3 = 0;
                        $jum_persentase_4 = 0;
                        $jum_indeks = 0;
                        @endphp

                        <?php foreach ($rel_data->result() as $elements) { ?>

                        <tr>
                            <td><b>{{ $elements->nama_unsur_pelayanan }}</b></td>
                            <td>{{ ROUND($elements->persentase_1,2) }} %</td>
                            <td>{{ ROUND($elements->persentase_2,2) }} %</td>
                            <td>{{ ROUND($elements->persentase_3,2) }} %</td>
                            <td>{{ ROUND($elements->persentase_4,2) }} %</td>
                            <td>{{ ROUND($elements->rata_rata, 2) }}</td>
                            <td>{{ $elements->predikat }}</td>
                        </tr>

                        @php
                        $jum_persentase_1 += $elements->persentase_1;
                        $jum_persentase_2 += $elements->persentase_2;
                        $jum_persentase_3 += $elements->persentase_3;
                        $jum_persentase_4 += $elements->persentase_4;
                        $jum_indeks += $elements->rata_rata;
                        $no++;

                        $f_indeks = round(($jum_indeks/ $no), 2);
                        @endphp

                        <?php } ?>

                        <tr>
                            <td>
                                <div align="center"><strong>Rata-rata</strong></div>
                            </td>
                            <td>{{ round(($jum_persentase_1 / $no), 2) }} %</td>
                            <td>{{ round(($jum_persentase_2 / $no), 2) }} %</td>
                            <td>{{ ($jum_persentase_3 / $no) }} %</td>
                            <td>{{ round(($jum_persentase_4 / $no), 2) }} %</td>
                            <td>{{ $f_indeks }}</td>
                            <td>

                                @php
                                $indeks = $f_indeks * 25;
                                if($indeks >= 25 && $indeks <= 64.99) { $h_indeks="Tidak Baik" ; } elseif ( $indeks>= 65
                                    && $indeks <= 76.60) { $h_indeks="Kurang Baik" ; } elseif ( $indeks>= 76.61 &&
                                        $indeks <= 88.30) { $h_indeks="Baik" ; } elseif ( $indeks>= 88.31 && $indeks <=
                                                100) { $h_indeks="Sangat Baik" ; } else { $h_indeks="NULL" ; }; @endphp
                                                <b>{{ $h_indeks }}</b>
                            </td>
                        </tr>
                    </table>

                    <?php } else { ?>

                    <div id="pie_<?php echo $value->id ?>" class="d-flex justify-content-center"></div>


                    @php
                    $ci->db->select("*, unsur_pelayanan_$table_identity.id AS id_unsur_pelayanan,
                    pertanyaan_unsur_pelayanan_$table_identity.id AS id_pertanyaan_unsur_pelayanan");
                    $ci->db->from("unsur_pelayanan_$table_identity");
                    $ci->db->join("pertanyaan_unsur_pelayanan_$table_identity",
                    "pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan =
                    unsur_pelayanan_$table_identity.id");
                    $ci->db->where(["unsur_pelayanan_$table_identity.id" => $value->id_unsur_pelayanan]);
                    $unsur_pelayanan_b = $ci->db->get()->row();

                    @endphp


                    @php
                    $id_pertanyaan_unsur_pelayanan = $unsur_pelayanan_b->id_pertanyaan_unsur_pelayanan;
                    $persentase_detail = $ci->db->query("
                    SELECT
                    kup.nama_kategori_unsur_pelayanan,
                    ( SELECT COUNT(skor_jawaban) FROM jawaban_pertanyaan_unsur_$table_identity JOIN
                    responden_$table_identity ON
                    jawaban_pertanyaan_unsur_$table_identity.id_responden = responden_$table_identity.id
                    JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
                    WHERE is_submit = 1 && id_pertanyaan_unsur
                    = $id_pertanyaan_unsur_pelayanan AND skor_jawaban = kup.nomor_kategori_unsur_pelayanan) AS jumlah,
                    ( SELECT ROUND(( SELECT COUNT(skor_jawaban) FROM jawaban_pertanyaan_unsur_$table_identity JOIN
                    responden_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden =
                    responden_$table_identity.id
                    JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
                    WHERE is_submit = 1 &&
                    id_pertanyaan_unsur = $id_pertanyaan_unsur_pelayanan AND skor_jawaban =
                    kup.nomor_kategori_unsur_pelayanan) / ( SELECT COUNT(*) FROM
                    jawaban_pertanyaan_unsur_$table_identity JOIN responden_$table_identity ON
                    jawaban_pertanyaan_unsur_$table_identity.id_responden = responden_$table_identity.id
                    JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
                    WHERE is_submit = 1 && id_pertanyaan_unsur = $id_pertanyaan_unsur_pelayanan
                    ) * 100,2) ) AS persentase
                    FROM kategori_unsur_pelayanan_$table_identity kup
                    WHERE id_pertanyaan_unsur = $id_pertanyaan_unsur_pelayanan
                    ");

                    @endphp

                    <table class="table table-bordered">
                        <tr>
                            <th width="7%">No</th>
                            <th width="49%">Kategori</th>
                            <th width="23%">Jumlah</th>
                            <th width="21%">Persentase</th>
                        </tr>
                        @php
                        $no = 1;
                        $t_jum = 0;
                        $t_persen = 0;
                        @endphp

                        <?php foreach ($persentase_detail->result() as $val_p) { ?>
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $val_p->nama_kategori_unsur_pelayanan }}</td>
                            <td>{{ $val_p->jumlah }}</td>
                            <td>{{ ROUND($val_p->persentase,2) }} %</td>
                        </tr>
                        @php
                        $t_jum += $val_p->jumlah;
                        $t_persen += $val_p->persentase;
                        @endphp

                        <?php } ?>
                        <tr>
                            <td colspan="2">
                                <div align="center"><strong>TOTAL</strong></div>
                            </td>
                            <td>{{ $t_jum }}</td>
                            <td>{{ ROUND($t_persen,2) }} %</td>
                        </tr>
                    </table>

                    <?php } ?>

                </div>
            </div>
            <?php } ?>


        </div>
    </div>

</div>


@endsection

@section('javascript')
<script src="{{ base_url() }}assets/themes/metronic/assets/js/pages/features/charts/apexcharts.js"></script>


<script>
FusionCharts.ready(function() {
    var myChart = new FusionCharts({
        type: "bar3d",
        renderAt: "chart",
        "width": "100%",
        "height": "90%",
        dataFormat: "json",
        dataSource: {
            chart: {
                caption: "Indeks Keseluruhan",
                // yaxisname: "Annual Income",
                showvalues: "1",
                "decimals": "3",
                theme: "umber",
                "bgColor": "#ffffff",
            },
            data: [<?php echo $get_data_chart ?>]
        }
    });
    myChart.render();
});
</script>




<!-- PIE CHART -->
<?php
foreach ($unsur_pelayanan->result() as $value) {
    $sub_unsur = $ci->db->get_where("unsur_pelayanan_$table_identity", ['id_parent' => $value->id_unsur_pelayanan]);
?>

@if ($sub_unsur->num_rows() == 0)

<!-- TIDAK YANG MEMILIKI TURUNAN -->
<?php
    $ci->db->select("*, unsur_pelayanan_$table_identity.id AS id_unsur_pelayanan, pertanyaan_unsur_pelayanan_$table_identity.id AS id_pertanyaan_unsur_pelayanan");
    $ci->db->from("unsur_pelayanan_$table_identity");
    $ci->db->join(
        "pertanyaan_unsur_pelayanan_$table_identity",
        "pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan = unsur_pelayanan_$table_identity.id"
    );
    $ci->db->where(["unsur_pelayanan_$table_identity.id" => $value->id_unsur_pelayanan]);
    $unsur_pelayanan_b = $ci->db->get()->row();

    $id_pertanyaan_unsur_pelayanan = $unsur_pelayanan_b->id_pertanyaan_unsur_pelayanan;

    $persentase_detail = $ci->db->query("SELECT kup.nama_kategori_unsur_pelayanan,
        ( SELECT COUNT(skor_jawaban) FROM jawaban_pertanyaan_unsur_$table_identity JOIN responden_$table_identity ON
        jawaban_pertanyaan_unsur_$table_identity.id_responden = responden_$table_identity.id
        JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
        WHERE is_submit = 1 && id_pertanyaan_unsur =
        $id_pertanyaan_unsur_pelayanan AND skor_jawaban = kup.nomor_kategori_unsur_pelayanan) AS jumlah,
        ( SELECT ROUND(( SELECT COUNT(skor_jawaban) FROM jawaban_pertanyaan_unsur_$table_identity JOIN responden_$table_identity
        ON
        jawaban_pertanyaan_unsur_$table_identity.id_responden = responden_$table_identity.id
        JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
        WHERE is_submit = 1 && id_pertanyaan_unsur =
        $id_pertanyaan_unsur_pelayanan AND skor_jawaban = kup.nomor_kategori_unsur_pelayanan) / ( SELECT COUNT(*) FROM
        jawaban_pertanyaan_unsur_$table_identity JOIN responden_$table_identity ON
        jawaban_pertanyaan_unsur_$table_identity.id_responden =
        responden_$table_identity.id
        JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
        WHERE is_submit = 1 && id_pertanyaan_unsur = $id_pertanyaan_unsur_pelayanan ) * 100,2) ) AS
        persentase
        FROM kategori_unsur_pelayanan_$table_identity kup
        WHERE id_pertanyaan_unsur = $id_pertanyaan_unsur_pelayanan
        ")->result_array();
    ?>



<script>
FusionCharts.ready(function() {
    var myChart = new FusionCharts({
        "type": "pie3d",
        "renderAt": "pie_<?php echo $value->id ?>",
        "width": "100%",
        "height": "350",
        "dataFormat": "json",
        dataSource: {
            "chart": {
                "caption": "<?php echo $value->nomor_unsur . '. ' . $value->nama_unsur_pelayanan ?>",
                subcaption: "<?php echo strip_tags($value->isi_pertanyaan_unsur) ?>",
                "enableSmartLabels": "1",
                "startingAngle": "0",
                "showPercentValues": "1",
                "decimals": "2",
                "useDataPlotColorForLabels": "1",
                "theme": "umber",
                "bgColor": "#ffffff"
            },

            "data": [
                <?php foreach ($persentase_detail as $element) { ?> {
                    label: "<?php echo $element['nama_kategori_unsur_pelayanan'] . ' = ' . $element['jumlah'] ?>",
                    value: "<?php echo ROUND($element['persentase'], 2) ?>"
                },
                <?php } ?>
            ]
        }

    });
    myChart.render();
});

// function get_canvas() {
//     html2canvas(document.getElementById("pie_<?php echo $value->id ?>"), {
//         allowTaint: true,
//         useCORS: true
//     }).then(function(canvas) {
//         var anchorTag = document.createElement("a");
//         document.body.appendChild(anchorTag);

//         var dataURL = canvas.toDataURL();
//         $.ajax({
//             type: "POST",
//             url: "<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/visualisasi/convert/' . $value->id ?>",
//             data: {
//                 imgBase64: dataURL
//             },
//             beforeSend: function() {},
//             complete: function() {}
//         }).done(function(o) {
//             toastr["success"]('Data berhasil disimpan');
//         });
//     });
// };
// setTimeout(get_canvas, 2500);
</script>


@else

<!-- UNSUR YANG MEMILIKI TURUNAN -->
<?php
    $ci->db->select("*, unsur_pelayanan_$table_identity.id AS id_unsur_pelayanan, pertanyaan_unsur_pelayanan_$table_identity.id AS id_pertanyaan_unsur_pelayanan");
    $ci->db->from("unsur_pelayanan_$table_identity");
    $ci->db->join("pertanyaan_unsur_pelayanan_$table_identity", "pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan = unsur_pelayanan_$table_identity.id");
    $ci->db->where(['id_parent' => $value->id_unsur_pelayanan]);
    $unsur_pelayanan_a = $ci->db->get();


    //LOOPING unsur_pelayanan_a
    foreach ($unsur_pelayanan_a->result() as $element_a) {

        $ci->db->select("*, unsur_pelayanan_$table_identity.id AS id_unsur_pelayanan, pertanyaan_unsur_pelayanan_$table_identity.id AS id_pertanyaan_unsur_pelayanan");
        $ci->db->from("unsur_pelayanan_$table_identity");
        $ci->db->join(
            "pertanyaan_unsur_pelayanan_$table_identity",
            "pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan = unsur_pelayanan_$table_identity.id"
        );
        $ci->db->where(["unsur_pelayanan_$table_identity.id" => $element_a->id_unsur_pelayanan]);
        $unsur_pelayanan_aa = $ci->db->get()->row();



        $id_pertanyaan_unsur_pelayanan = $unsur_pelayanan_aa->id_pertanyaan_unsur_pelayanan;
        $persentase_detail = $ci->db->query("SELECT kup.id AS id_kup, kup.nama_kategori_unsur_pelayanan,
            ( SELECT COUNT(skor_jawaban) FROM jawaban_pertanyaan_unsur_$table_identity JOIN responden_$table_identity ON
            jawaban_pertanyaan_unsur_$table_identity.id_responden = responden_$table_identity.id
            JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
            WHERE is_submit = 1 && id_pertanyaan_unsur =
            $id_pertanyaan_unsur_pelayanan AND skor_jawaban = kup.nomor_kategori_unsur_pelayanan) AS jumlah,
            ( SELECT ROUND(( SELECT COUNT(skor_jawaban) FROM jawaban_pertanyaan_unsur_$table_identity JOIN responden_$table_identity
            ON
            jawaban_pertanyaan_unsur_$table_identity.id_responden = responden_$table_identity.id
            JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
            WHERE is_submit = 1 && id_pertanyaan_unsur =
            $id_pertanyaan_unsur_pelayanan AND skor_jawaban = kup.nomor_kategori_unsur_pelayanan) / ( SELECT COUNT(*) FROM
            jawaban_pertanyaan_unsur_$table_identity JOIN responden_$table_identity ON
            jawaban_pertanyaan_unsur_$table_identity.id_responden =
            responden_$table_identity.id
            JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
            WHERE is_submit = 1 && id_pertanyaan_unsur = $id_pertanyaan_unsur_pelayanan ) * 100,2) ) AS
            persentase
            FROM kategori_unsur_pelayanan_$table_identity kup
            WHERE id_pertanyaan_unsur = $id_pertanyaan_unsur_pelayanan
            ");


        $no = 1;
        $t_jum = 0;
        $t_persen = 0;
        foreach ($persentase_detail->result() as $val_p) {
            $t_jum += $val_p->jumlah;
            $t_persen += $val_p->persentase;
        }


        $ci->db->select('id_pertanyaan_unsur');
        $ci->db->from("kategori_unsur_pelayanan_$table_identity");
        $ci->db->where('id', $val_p->id_kup);
        $get_opsi = $ci->db->get()->row();

        $ci->db->select('nama_kategori_unsur_pelayanan');
        $ci->db->from("kategori_unsur_pelayanan_$table_identity");
        $ci->db->where('id_pertanyaan_unsur', $get_opsi->id_pertanyaan_unsur);
        $get_data_opsi = $ci->db->get()->result_array();


        $rel_data = $ci->db->query("SELECT *,
            pertanyaan_unsur_pelayanan_$table_identity.id AS id_pertanyaan_unsur_pelayanan,
            ( SELECT COUNT(skor_jawaban) FROM jawaban_pertanyaan_unsur_$table_identity JOIN responden_$table_identity ON
            jawaban_pertanyaan_unsur_$table_identity.id_responden = responden_$table_identity.id
            JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
            WHERE is_submit = 1 && id_pertanyaan_unsur =
            pertanyaan_unsur_pelayanan_$table_identity.id AND skor_jawaban = 1) AS jumlah_1,
            ( SELECT COUNT(skor_jawaban) FROM jawaban_pertanyaan_unsur_$table_identity JOIN responden_$table_identity ON
            jawaban_pertanyaan_unsur_$table_identity.id_responden = responden_$table_identity.id
            JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
            WHERE is_submit = 1 && id_pertanyaan_unsur =
            pertanyaan_unsur_pelayanan_$table_identity.id AND skor_jawaban = 2) AS jumlah_2,
            ( SELECT COUNT(skor_jawaban) FROM jawaban_pertanyaan_unsur_$table_identity JOIN responden_$table_identity ON
            jawaban_pertanyaan_unsur_$table_identity.id_responden = responden_$table_identity.id
            JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
            WHERE is_submit = 1 && id_pertanyaan_unsur =
            pertanyaan_unsur_pelayanan_$table_identity.id AND skor_jawaban = 3) AS jumlah_3,
            ( SELECT COUNT(skor_jawaban) FROM jawaban_pertanyaan_unsur_$table_identity JOIN responden_$table_identity ON
            jawaban_pertanyaan_unsur_$table_identity.id_responden = responden_$table_identity.id
            JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
            WHERE is_submit = 1 && id_pertanyaan_unsur =
            pertanyaan_unsur_pelayanan_$table_identity.id AND skor_jawaban = 4) AS jumlah_4,

            ( SELECT ROUND(COUNT(skor_jawaban) / ( SELECT COUNT(skor_jawaban) FROM jawaban_pertanyaan_unsur_$table_identity JOIN
            responden_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = responden_$table_identity.id
            JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
            WHERE is_submit = 1 &&
            id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id) * 100, 2) FROM
            jawaban_pertanyaan_unsur_$table_identity JOIN responden_$table_identity ON
            jawaban_pertanyaan_unsur_$table_identity.id_responden =
            responden_$table_identity.id
            JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
            WHERE is_submit = 1 && id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id AND
            skor_jawaban = 1 ) AS persentase_1,
            ( SELECT ROUND(COUNT(skor_jawaban) / ( SELECT COUNT(skor_jawaban) FROM jawaban_pertanyaan_unsur_$table_identity JOIN
            responden_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = responden_$table_identity.id
            JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
            WHERE is_submit = 1 &&
            id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id) * 100, 2) FROM
            jawaban_pertanyaan_unsur_$table_identity JOIN responden_$table_identity ON
            jawaban_pertanyaan_unsur_$table_identity.id_responden =
            responden_$table_identity.id
            JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
            WHERE is_submit = 1 && id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id AND
            skor_jawaban = 2 ) AS persentase_2,
            ( SELECT ROUND(COUNT(skor_jawaban) / ( SELECT COUNT(skor_jawaban) FROM jawaban_pertanyaan_unsur_$table_identity JOIN
            responden_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = responden_$table_identity.id
            JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
            WHERE is_submit = 1 &&
            id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id) * 100, 2) FROM
            jawaban_pertanyaan_unsur_$table_identity JOIN responden_$table_identity ON
            jawaban_pertanyaan_unsur_$table_identity.id_responden =
            responden_$table_identity.id
            JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
            WHERE is_submit = 1 && id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id AND
            skor_jawaban = 3 ) AS persentase_3,
            ( SELECT ROUND(COUNT(skor_jawaban) / ( SELECT COUNT(skor_jawaban) FROM jawaban_pertanyaan_unsur_$table_identity JOIN
            responden_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = responden_$table_identity.id
            JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
            WHERE is_submit = 1 &&
            id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id) * 100, 2) FROM
            jawaban_pertanyaan_unsur_$table_identity JOIN responden_$table_identity ON
            jawaban_pertanyaan_unsur_$table_identity.id_responden =
            responden_$table_identity.id
            JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
            WHERE is_submit = 1 && id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id AND
            skor_jawaban = 4 ) AS persentase_4,

            ( SELECT COUNT(skor_jawaban) FROM jawaban_pertanyaan_unsur_$table_identity JOIN responden_$table_identity ON
            jawaban_pertanyaan_unsur_$table_identity.id_responden = responden_$table_identity.id
            JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
            WHERE is_submit = 1 && id_pertanyaan_unsur =
            pertanyaan_unsur_pelayanan_$table_identity.id) AS jumlah_pengisi,
            ( SELECT AVG(skor_jawaban) FROM jawaban_pertanyaan_unsur_$table_identity JOIN responden_$table_identity ON
            jawaban_pertanyaan_unsur_$table_identity.id_responden = responden_$table_identity.id
            JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
            WHERE is_submit = 1 && id_pertanyaan_unsur =
            pertanyaan_unsur_pelayanan_$table_identity.id) AS rata_rata,
            ( SELECT IF(( SELECT AVG(skor_jawaban) FROM jawaban_pertanyaan_unsur_$table_identity JOIN responden_$table_identity ON
            jawaban_pertanyaan_unsur_$table_identity.id_responden = responden_$table_identity.id
            JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
            WHERE is_submit = 1 && id_pertanyaan_unsur =
            pertanyaan_unsur_pelayanan_$table_identity.id) > 3.5324,
            'Sangat Baik',
            IF(( SELECT AVG(skor_jawaban) FROM jawaban_pertanyaan_unsur_$table_identity JOIN responden_$table_identity ON
            jawaban_pertanyaan_unsur_$table_identity.id_responden = responden_$table_identity.id
            JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
            WHERE is_submit = 1 && id_pertanyaan_unsur =
            pertanyaan_unsur_pelayanan_$table_identity.id) > 2.9,
            'Baik',
            IF(( SELECT AVG(skor_jawaban) FROM jawaban_pertanyaan_unsur_$table_identity JOIN responden_$table_identity ON
            jawaban_pertanyaan_unsur_$table_identity.id_responden = responden_$table_identity.id
            JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
            WHERE is_submit = 1 && id_pertanyaan_unsur =
            pertanyaan_unsur_pelayanan_$table_identity.id) > 2.6,
            'Kurang Baik','Tidak Baik'))
            ) ) AS predikat

            FROM unsur_pelayanan_$table_identity
            JOIN pertanyaan_unsur_pelayanan_$table_identity ON pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan =
            unsur_pelayanan_$table_identity.id
            WHERE unsur_pelayanan_$table_identity.id_parent = $value->id_unsur_pelayanan

            ");


        $no = 0;
        $jum_persentase_1 = 0;
        $jum_persentase_2 = 0;
        $jum_persentase_3 = 0;
        $jum_persentase_4 = 0;
        $jumlah_1 = 0;
        $jumlah_2 = 0;
        $jumlah_3 = 0;
        $jumlah_4 = 0;
        $jum_indeks = 0;
        $nama_sub_unsur = [];


        foreach ($rel_data->result() as $elements) {

            $nama_sub_unsur[] = $elements->nama_unsur_pelayanan;
            $jum_persentase_1 += $elements->persentase_1;
            $jum_persentase_2 += $elements->persentase_2;
            $jum_persentase_3 += $elements->persentase_3;
            $jum_persentase_4 += $elements->persentase_4;
            $jumlah_1 += $elements->jumlah_1;
            $jumlah_2 += $elements->jumlah_2;
            $jumlah_3 += $elements->jumlah_3;
            $jumlah_4 += $elements->jumlah_4;


            $jum_indeks += $elements->rata_rata;
            $no++;

            $f_indeks = round(($jum_indeks / $no), 2);
        }
    }
    ?>


<script>
FusionCharts.ready(function() {
    var myChart = new FusionCharts({
        "type": "pie3d",
        "renderAt": "pie_<?php echo $value->id ?>",
        "width": "100%",
        "height": "350",
        "dataFormat": "json",
        dataSource: {
            "chart": {
                "caption": "<?php echo $value->nomor_unsur . '. ' . $value->nama_unsur_pelayanan ?>",
                subcaption: "<?php echo strip_tags($value->isi_pertanyaan_unsur) ?>",
                "enableSmartLabels": "1",
                "startingAngle": "0",
                "showPercentValues": "1",
                "decimals": "2",
                "useDataPlotColorForLabels": "1",
                "theme": "umber",
                "bgColor": "#ffffff"
            },

            "data": [{
                    label: "<?php echo $get_data_opsi[0]['nama_kategori_unsur_pelayanan'] . ' = ' . $jumlah_1 ?>",
                    value: "<?php echo ROUND($jum_persentase_1, 2) ?>"
                },
                {
                    label: "<?php echo $get_data_opsi[1]['nama_kategori_unsur_pelayanan'] . ' = ' . $jumlah_2 ?>",
                    value: "<?php echo ROUND($jum_persentase_2, 2) ?>"
                },
                {
                    label: "<?php echo $get_data_opsi[2]['nama_kategori_unsur_pelayanan'] . ' = ' . $jumlah_3 ?>",
                    value: "<?php echo ROUND($jum_persentase_3, 2) ?>"
                },
                {
                    label: "<?php echo $get_data_opsi[3]['nama_kategori_unsur_pelayanan'] . ' = ' . $jumlah_4 ?>",
                    value: "<?php echo ROUND($jum_persentase_4, 2) ?>"
                }
            ]
        }

    });
    myChart.render();
});


// function get_canvas() {
//     html2canvas(document.getElementById("pie_<?php echo $value->id ?>"), {
//         allowTaint: true,
//         useCORS: true
//     }).then(function(canvas) {
//         var anchorTag = document.createElement("a");
//         document.body.appendChild(anchorTag);

//         var dataURL = canvas.toDataURL();
//         $.ajax({
//             type: "POST",
//             url: "<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/visualisasi/convert/' . $value->id ?>",
//             data: {
//                 imgBase64: dataURL
//             },
//             beforeSend: function() {},
//             complete: function() {}
//         }).done(function(o) {
//             toastr["success"]('Data berhasil disimpan');
//         });
//     });
// };
// setTimeout(get_canvas, 2500);
</script>


@endif
<?php } ?>


@endsection