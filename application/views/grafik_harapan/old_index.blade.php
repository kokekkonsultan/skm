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

            @foreach ($unsur_pelayanan->result() as $value)

            <div class="card mb-5" data-aos="fade-down">
                <div class="card-body">

                    <!-- <div class="alert alert-secondary text-center" role="alert">
                        <h5>H{{ $value->nomor_harapan . '. ' . $value->nama_unsur_pelayanan }}</h5>
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
                                <i class="fa fa-info-circle"></i> Lihat Detail Sub
                            </a>
                        </div>
                    </div>


                    <div class="collapse" id="collapseExample{{ $value->id }}">

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
                        pertanyaan_unsur_pelayanan_$table_identity.id AS id_pertanyaan_unsur_pelayanan,
                        SUBSTR(nomor_unsur,2) AS nomor_harapan");
                        $ci->db->from("unsur_pelayanan_$table_identity");
                        $ci->db->join("pertanyaan_unsur_pelayanan_$table_identity",
                        "pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan =
                        unsur_pelayanan_$table_identity.id");
                        $ci->db->where(["unsur_pelayanan_$table_identity.id" => $element_a->id_unsur_pelayanan]);
                        $unsur_pelayanan_aa = $ci->db->get()->row();
                        @endphp


                        <div class="card card-body mb-5">
                            <div class="alert alert-secondary text-center" role="alert">
                                <h5>H{{ $unsur_pelayanan_aa->nomor_harapan . '. ' . $unsur_pelayanan_aa->nama_unsur_pelayanan }}
                                </h5>
                                <?php echo $unsur_pelayanan_aa->isi_pertanyaan_unsur ?>
                            </div>

                            @php
                            $id_pertanyaan_unsur_pelayanan = $unsur_pelayanan_aa->id_pertanyaan_unsur_pelayanan;
                            $persentase_detail = $ci->db->query("
                            SELECT
                            kup.id AS id_kup,
                            kup.nama_tingkat_kepentingan,
                            ( SELECT COUNT(skor_jawaban) FROM jawaban_pertanyaan_harapan_$table_identity JOIN
                            responden_$table_identity ON jawaban_pertanyaan_harapan_$table_identity.id_responden =
                            responden_$table_identity.id
                            JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
                            WHERE is_submit = 1 &&
                            id_pertanyaan_unsur = $id_pertanyaan_unsur_pelayanan AND skor_jawaban =
                            kup.nomor_tingkat_kepentingan) AS jumlah,

                            ( SELECT ROUND(( SELECT COUNT(skor_jawaban) FROM jawaban_pertanyaan_harapan_$table_identity
                            JOIN responden_$table_identity ON jawaban_pertanyaan_harapan_$table_identity.id_responden =
                            responden_$table_identity.id
                            JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
                            WHERE is_submit = 1 && id_pertanyaan_unsur = $id_pertanyaan_unsur_pelayanan AND
                            skor_jawaban = kup.nomor_tingkat_kepentingan) / ( SELECT COUNT(*) FROM
                            jawaban_pertanyaan_harapan_$table_identity JOIN responden_$table_identity ON
                            jawaban_pertanyaan_harapan_$table_identity.id_responden = responden_$table_identity.id
                            JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
                            WHERE is_submit = 1 && id_pertanyaan_unsur =
                            $id_pertanyaan_unsur_pelayanan ) * 100,2) ) AS persentase
                            FROM nilai_tingkat_kepentingan_$table_identity kup
                            WHERE id_pertanyaan_unsur_pelayanan = $id_pertanyaan_unsur_pelayanan
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
                                    <td>{{ $val_p->nama_tingkat_kepentingan }}</td>
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
                            <a href="<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/rekap-pertanyaan-harapan/' . $unsur_pelayanan_aa->id_pertanyaan_unsur_pelayanan ?>"
                                class="font-weight-bold text-primary" target="_blank">Detail Rekapitulasi</a>
                        </div>

                        <?php } ?>



                        @php
                        $ci->db->select('id_pertanyaan_unsur_pelayanan');
                        $ci->db->from("nilai_tingkat_kepentingan_$table_identity");
                        $ci->db->where('id', $val_p->id_kup);
                        $get_opsi = $ci->db->get()->row();

                        $ci->db->select('DISTINCT(nama_tingkat_kepentingan) AS nama_tingkat_kepentingan');
                        $ci->db->from("nilai_tingkat_kepentingan_$table_identity");
                        $get_data_opsi = $ci->db->get()->result();

                        @endphp

                        @php
                        $rel_data = $ci->db->query("
                        SELECT *,
                        pertanyaan_unsur_pelayanan_$table_identity.id AS id_pertanyaan_unsur_pelayanan,

                        ( SELECT COUNT(skor_jawaban) FROM jawaban_pertanyaan_harapan_$table_identity JOIN
                        responden_$table_identity ON jawaban_pertanyaan_harapan_$table_identity.id_responden =
                        responden_$table_identity.id
                        JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
                        WHERE is_submit = 1 &&
                        id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id AND
                        skor_jawaban = 1) AS jumlah_1,

                        ( SELECT COUNT(skor_jawaban) FROM jawaban_pertanyaan_harapan_$table_identity JOIN
                        responden_$table_identity ON jawaban_pertanyaan_harapan_$table_identity.id_responden =
                        responden_$table_identity.id
                        JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
                        WHERE is_submit = 1 &&
                        id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id AND
                        skor_jawaban = 2) AS
                        jumlah_2,

                        ( SELECT COUNT(skor_jawaban) FROM jawaban_pertanyaan_harapan_$table_identity JOIN
                        responden_$table_identity ON jawaban_pertanyaan_harapan_$table_identity.id_responden =
                        responden_$table_identity.id
                        JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
                        WHERE is_submit = 1 &&
                        id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id AND
                        skor_jawaban = 3) AS
                        jumlah_3,

                        ( SELECT COUNT(skor_jawaban) FROM jawaban_pertanyaan_harapan_$table_identity JOIN
                        responden_$table_identity ON jawaban_pertanyaan_harapan_$table_identity.id_responden =
                        responden_$table_identity.id
                        JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
                        WHERE is_submit = 1 &&
                        id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id AND
                        skor_jawaban = 4) AS
                        jumlah_4,

                        ( SELECT ROUND(COUNT(skor_jawaban) / ( SELECT COUNT(skor_jawaban) FROM
                        jawaban_pertanyaan_harapan_$table_identity JOIN responden_$table_identity ON
                        jawaban_pertanyaan_harapan_$table_identity.id_responden = responden_$table_identity.id
                        JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
                        WHERE is_submit = 1 && id_pertanyaan_unsur =
                        pertanyaan_unsur_pelayanan_$table_identity.id) * 100, 2) FROM
                        jawaban_pertanyaan_harapan_$table_identity JOIN responden_$table_identity ON
                        jawaban_pertanyaan_harapan_$table_identity.id_responden = responden_$table_identity.id
                        JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
                        WHERE is_submit = 1 && id_pertanyaan_unsur =
                        pertanyaan_unsur_pelayanan_$table_identity.id AND skor_jawaban = 1 ) AS persentase_1,

                        ( SELECT ROUND(COUNT(skor_jawaban) / ( SELECT COUNT(skor_jawaban) FROM
                        jawaban_pertanyaan_harapan_$table_identity JOIN responden_$table_identity ON
                        jawaban_pertanyaan_harapan_$table_identity.id_responden = responden_$table_identity.id
                        JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
                        WHERE is_submit = 1 && id_pertanyaan_unsur =
                        pertanyaan_unsur_pelayanan_$table_identity.id) * 100, 2) FROM
                        jawaban_pertanyaan_harapan_$table_identity JOIN responden_$table_identity ON
                        jawaban_pertanyaan_harapan_$table_identity.id_responden = responden_$table_identity.id
                        JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
                        WHERE is_submit = 1 && id_pertanyaan_unsur =
                        pertanyaan_unsur_pelayanan_$table_identity.id AND skor_jawaban = 2 ) AS persentase_2,

                        ( SELECT ROUND(COUNT(skor_jawaban) / ( SELECT COUNT(skor_jawaban) FROM
                        jawaban_pertanyaan_harapan_$table_identity JOIN responden_$table_identity ON
                        jawaban_pertanyaan_harapan_$table_identity.id_responden = responden_$table_identity.id
                        JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
                        WHERE is_submit = 1 && id_pertanyaan_unsur =
                        pertanyaan_unsur_pelayanan_$table_identity.id) * 100, 2) FROM
                        jawaban_pertanyaan_harapan_$table_identity JOIN responden_$table_identity ON
                        jawaban_pertanyaan_harapan_$table_identity.id_responden = responden_$table_identity.id
                        JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
                        WHERE is_submit = 1 && id_pertanyaan_unsur =
                        pertanyaan_unsur_pelayanan_$table_identity.id AND skor_jawaban = 3 ) AS persentase_3,

                        ( SELECT ROUND(COUNT(skor_jawaban) / ( SELECT COUNT(skor_jawaban) FROM
                        jawaban_pertanyaan_harapan_$table_identity JOIN responden_$table_identity ON
                        jawaban_pertanyaan_harapan_$table_identity.id_responden = responden_$table_identity.id
                        JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
                        WHERE is_submit = 1 && id_pertanyaan_unsur =
                        pertanyaan_unsur_pelayanan_$table_identity.id) * 100, 2) FROM
                        jawaban_pertanyaan_harapan_$table_identity JOIN responden_$table_identity ON
                        jawaban_pertanyaan_harapan_$table_identity.id_responden = responden_$table_identity.id
                        JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
                        WHERE is_submit = 1 && id_pertanyaan_unsur =
                        pertanyaan_unsur_pelayanan_$table_identity.id AND skor_jawaban = 4 ) AS persentase_4,

                        ( SELECT ROUND(COUNT(skor_jawaban) / ( SELECT COUNT(skor_jawaban) FROM
                        jawaban_pertanyaan_harapan_$table_identity JOIN responden_$table_identity ON
                        jawaban_pertanyaan_harapan_$table_identity.id_responden = responden_$table_identity.id
                        JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
                        WHERE is_submit = 1 && id_pertanyaan_unsur =
                        pertanyaan_unsur_pelayanan_$table_identity.id) * 100, 2) FROM
                        jawaban_pertanyaan_harapan_$table_identity JOIN responden_$table_identity ON
                        jawaban_pertanyaan_harapan_$table_identity.id_responden = responden_$table_identity.id
                        JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
                        WHERE is_submit = 1 && id_pertanyaan_unsur =
                        pertanyaan_unsur_pelayanan_$table_identity.id AND skor_jawaban = 5 ) AS persentase_5,

                        ( SELECT COUNT(skor_jawaban) FROM jawaban_pertanyaan_harapan_$table_identity JOIN
                        responden_$table_identity ON jawaban_pertanyaan_harapan_$table_identity.id_responden =
                        responden_$table_identity.id
                        JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
                        WHERE is_submit = 1 &&
                        id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id) AS
                        jumlah_pengisi,
                        ( SELECT AVG(skor_jawaban) FROM jawaban_pertanyaan_harapan_$table_identity JOIN
                        responden_$table_identity ON jawaban_pertanyaan_harapan_$table_identity.id_responden =
                        responden_$table_identity.id
                        JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
                        WHERE is_submit = 1 &&
                        id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id) AS rata_rata

                        FROM unsur_pelayanan_$table_identity
                        JOIN pertanyaan_unsur_pelayanan_$table_identity ON
                        pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan =
                        unsur_pelayanan_$table_identity.id
                        WHERE unsur_pelayanan_$table_identity.id_parent = $value->id_unsur_pelayanan

                        ");
                        @endphp

                    </div>

                    <table width="100%" class="table table-bordered mt-7">
                        <tr>
                            <td width="39%" rowspan="2">
                                <div align="center"><strong>Unsur</strong></div>
                            </td>
                            <td colspan="{{$manage_survey->skala_likert == 5 ? 5 : 4 }}">
                                <div align="center"><strong>Persentase Persepsi Responden </strong></div>
                            </td>
                            <td width="10%" rowspan="2">
                                <div align="center"><strong>Indeks</strong></div>
                            </td>
                        </tr>
                        <tr>
                            @foreach($get_data_opsi as $get)
                            <td width="8%">
                                <div align="center">
                                    <strong>{{ $get->nama_tingkat_kepentingan }}</strong>
                                </div>
                            </td>
                            @endforeach
                        </tr>

                        @php
                        $no = 0;
                        $jum_persentase_1 = 0;
                        $jum_persentase_2 = 0;
                        $jum_persentase_3 = 0;
                        $jum_persentase_4 = 0;
                        $jum_persentase_5 = 0;
                        $jum_indeks = 0;
                        @endphp

                        <?php foreach ($rel_data->result() as $elements) { ?>

                        <tr>
                            <td><b>{{ $elements->nama_unsur_pelayanan }}</b></td>
                            <td class="text-center">{{ ROUND($elements->persentase_1,2) }} %</td>
                            <td class="text-center">{{ ROUND($elements->persentase_2,2) }} %</td>
                            <td class="text-center">{{ ROUND($elements->persentase_3,2) }} %</td>
                            <td class="text-center">{{ ROUND($elements->persentase_4,2) }} %</td>

                            @if($manage_survey->skala_likert == 5)
                            <td class="text-center">{{ ROUND($elements->persentase_5,2) }} %</td>
                            @endif

                            <td class="text-center">{{ ROUND($elements->rata_rata, 2) }}</td>
                        </tr>

                        @php
                        $jum_persentase_1 += $elements->persentase_1;
                        $jum_persentase_2 += $elements->persentase_2;
                        $jum_persentase_3 += $elements->persentase_3;
                        $jum_persentase_4 += $elements->persentase_4;
                        $jum_persentase_5 += $elements->persentase_5;
                        $jum_indeks += $elements->rata_rata;
                        $no++;

                        $f_indeks = round(($jum_indeks/ $no), 2);
                        @endphp

                        <?php } ?>

                        <tr>
                            <td>
                                <div align="center"><strong>Rata-rata</strong></div>
                            </td>
                            <td class="text-center">{{ round(($jum_persentase_1 / $no), 2) }} %</td>
                            <td class="text-center">{{ round(($jum_persentase_2 / $no), 2) }} %</td>
                            <td class="text-center">{{ round(($jum_persentase_3 / $no), 2) }} %</td>
                            <td class="text-center">{{ round(($jum_persentase_4 / $no), 2) }} %</td>

                            @if($manage_survey->skala_likert == 5)
                            <td class="text-center">{{ round(($jum_persentase_5 / $no), 2) }} %</td>
                            @endif

                            <td class="text-center">{{ $f_indeks }}</td>
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
                    SELECT kup.nama_tingkat_kepentingan,

                    (SELECT COUNT(skor_jawaban) FROM jawaban_pertanyaan_harapan_$table_identity JOIN
                    responden_$table_identity ON
                    jawaban_pertanyaan_harapan_$table_identity.id_responden = responden_$table_identity.id JOIN
                    survey_$table_identity ON
                    responden_$table_identity.id = survey_$table_identity.id WHERE is_submit = 1 && id_pertanyaan_unsur
                    = $id_pertanyaan_unsur_pelayanan AND
                    skor_jawaban = kup.nomor_tingkat_kepentingan) AS jumlah,

                    (SELECT ROUND(( SELECT COUNT(skor_jawaban) FROM jawaban_pertanyaan_harapan_$table_identity JOIN
                    responden_$table_identity ON jawaban_pertanyaan_harapan_$table_identity.id_responden =
                    responden_$table_identity.id JOIN
                    survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id WHERE is_submit =
                    1 && id_pertanyaan_unsur =
                    $id_pertanyaan_unsur_pelayanan AND skor_jawaban = kup.nomor_tingkat_kepentingan) / ( SELECT COUNT(*)
                    FROM
                    jawaban_pertanyaan_harapan_$table_identity JOIN responden_$table_identity ON
                    jawaban_pertanyaan_harapan_$table_identity.id_responden = responden_$table_identity.id JOIN
                    survey_$table_identity ON
                    responden_$table_identity.id = survey_$table_identity.id WHERE is_submit = 1 && id_pertanyaan_unsur
                    = $id_pertanyaan_unsur_pelayanan) * 100,2) ) AS
                    persentase

                    FROM nilai_tingkat_kepentingan_$table_identity kup
                    WHERE id_pertanyaan_unsur_pelayanan = $id_pertanyaan_unsur_pelayanan");

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
                            <td>{{ $val_p->nama_tingkat_kepentingan }}</td>
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
                    <a href="<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/rekap-pertanyaan-harapan/' . $value->id_pertanyaan_unsur ?>"
                        class="font-weight-bold text-primary" target="_blank">Detail Rekapitulasi</a>

                    <?php } ?>
                </div>
            </div>
            @endforeach


        </div>
    </div>

</div>


@endsection

@section('javascript')
<script src="{{ base_url() }}assets/themes/metronic/assets/js/pages/features/charts/apexcharts.js"></script>

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

    $persentase_detail = $ci->db->query("
                    SELECT kup.nama_tingkat_kepentingan,

                    (SELECT COUNT(skor_jawaban) FROM jawaban_pertanyaan_harapan_$table_identity JOIN
                    responden_$table_identity ON
                    jawaban_pertanyaan_harapan_$table_identity.id_responden = responden_$table_identity.id JOIN
                    survey_$table_identity ON
                    responden_$table_identity.id = survey_$table_identity.id WHERE is_submit = 1 && id_pertanyaan_unsur
                    = $id_pertanyaan_unsur_pelayanan AND
                    skor_jawaban = kup.nomor_tingkat_kepentingan) AS jumlah,

                    (SELECT ROUND(( SELECT COUNT(skor_jawaban) FROM jawaban_pertanyaan_harapan_$table_identity JOIN
                    responden_$table_identity ON jawaban_pertanyaan_harapan_$table_identity.id_responden =
                    responden_$table_identity.id JOIN
                    survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id WHERE is_submit =
                    1 && id_pertanyaan_unsur =
                    $id_pertanyaan_unsur_pelayanan AND skor_jawaban = kup.nomor_tingkat_kepentingan) / ( SELECT COUNT(*)
                    FROM
                    jawaban_pertanyaan_harapan_$table_identity JOIN responden_$table_identity ON
                    jawaban_pertanyaan_harapan_$table_identity.id_responden = responden_$table_identity.id JOIN
                    survey_$table_identity ON
                    responden_$table_identity.id = survey_$table_identity.id WHERE is_submit = 1 && id_pertanyaan_unsur
                    = $id_pertanyaan_unsur_pelayanan) * 100,2) ) AS
                    persentase

                    FROM nilai_tingkat_kepentingan_$table_identity kup
                    WHERE id_pertanyaan_unsur_pelayanan = $id_pertanyaan_unsur_pelayanan")->result_array();
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
                "caption": "H<?php echo $value->nomor_harapan . '. ' . $value->nama_unsur_pelayanan ?>",
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
                    label: "<?php echo $element['nama_tingkat_kepentingan'] . ' = ' . $element['jumlah'] ?>",
                    value: "<?php echo ROUND($element['persentase'], 2) ?>"
                },
                <?php } ?>
            ]
        }

    });
    myChart.render();
});
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


        $ci->db->select('DISTINCT(nama_tingkat_kepentingan) AS nama_tingkat_kepentingan');
        $ci->db->from("nilai_tingkat_kepentingan_$table_identity");
        $get_data_opsi = $ci->db->get()->result_array();


        $rel_data = $ci->db->query("SELECT *,
            pertanyaan_unsur_pelayanan_$table_identity.id AS id_pertanyaan_unsur_pelayanan,
            ( SELECT COUNT(skor_jawaban) FROM jawaban_pertanyaan_harapan_$table_identity JOIN responden_$table_identity ON
            jawaban_pertanyaan_harapan_$table_identity.id_responden = responden_$table_identity.id
            JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
            WHERE is_submit = 1 && id_pertanyaan_unsur =
            pertanyaan_unsur_pelayanan_$table_identity.id AND skor_jawaban = 1) AS jumlah_1,
            ( SELECT COUNT(skor_jawaban) FROM jawaban_pertanyaan_harapan_$table_identity JOIN responden_$table_identity ON
            jawaban_pertanyaan_harapan_$table_identity.id_responden = responden_$table_identity.id
            JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
            WHERE is_submit = 1 && id_pertanyaan_unsur =
            pertanyaan_unsur_pelayanan_$table_identity.id AND skor_jawaban = 2) AS jumlah_2,
            ( SELECT COUNT(skor_jawaban) FROM jawaban_pertanyaan_harapan_$table_identity JOIN responden_$table_identity ON
            jawaban_pertanyaan_harapan_$table_identity.id_responden = responden_$table_identity.id
            JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
            WHERE is_submit = 1 && id_pertanyaan_unsur =
            pertanyaan_unsur_pelayanan_$table_identity.id AND skor_jawaban = 3) AS jumlah_3,
            ( SELECT COUNT(skor_jawaban) FROM jawaban_pertanyaan_harapan_$table_identity JOIN responden_$table_identity ON
            jawaban_pertanyaan_harapan_$table_identity.id_responden = responden_$table_identity.id
            JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
            WHERE is_submit = 1 && id_pertanyaan_unsur =
            pertanyaan_unsur_pelayanan_$table_identity.id AND skor_jawaban = 4) AS jumlah_4,

            ( SELECT ROUND(COUNT(skor_jawaban) / ( SELECT COUNT(skor_jawaban) FROM jawaban_pertanyaan_harapan_$table_identity JOIN
            responden_$table_identity ON jawaban_pertanyaan_harapan_$table_identity.id_responden = responden_$table_identity.id
            JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
            WHERE is_submit = 1 &&
            id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id) * 100, 2) FROM
            jawaban_pertanyaan_harapan_$table_identity JOIN responden_$table_identity ON
            jawaban_pertanyaan_harapan_$table_identity.id_responden =
            responden_$table_identity.id
            JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
            WHERE is_submit = 1 && id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id AND
            skor_jawaban = 1 ) AS persentase_1,
            ( SELECT ROUND(COUNT(skor_jawaban) / ( SELECT COUNT(skor_jawaban) FROM jawaban_pertanyaan_harapan_$table_identity JOIN
            responden_$table_identity ON jawaban_pertanyaan_harapan_$table_identity.id_responden = responden_$table_identity.id
            JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
            WHERE is_submit = 1 &&
            id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id) * 100, 2) FROM
            jawaban_pertanyaan_harapan_$table_identity JOIN responden_$table_identity ON
            jawaban_pertanyaan_harapan_$table_identity.id_responden =
            responden_$table_identity.id
            JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
            WHERE is_submit = 1 && id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id AND
            skor_jawaban = 2 ) AS persentase_2,
            ( SELECT ROUND(COUNT(skor_jawaban) / ( SELECT COUNT(skor_jawaban) FROM jawaban_pertanyaan_harapan_$table_identity JOIN
            responden_$table_identity ON jawaban_pertanyaan_harapan_$table_identity.id_responden = responden_$table_identity.id
            JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
            WHERE is_submit = 1 &&
            id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id) * 100, 2) FROM
            jawaban_pertanyaan_harapan_$table_identity JOIN responden_$table_identity ON
            jawaban_pertanyaan_harapan_$table_identity.id_responden =
            responden_$table_identity.id
            JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
            WHERE is_submit = 1 && id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id AND
            skor_jawaban = 3 ) AS persentase_3,
            ( SELECT ROUND(COUNT(skor_jawaban) / ( SELECT COUNT(skor_jawaban) FROM jawaban_pertanyaan_harapan_$table_identity JOIN
            responden_$table_identity ON jawaban_pertanyaan_harapan_$table_identity.id_responden = responden_$table_identity.id
            JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
            WHERE is_submit = 1 &&
            id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id) * 100, 2) FROM
            jawaban_pertanyaan_harapan_$table_identity JOIN responden_$table_identity ON
            jawaban_pertanyaan_harapan_$table_identity.id_responden =
            responden_$table_identity.id
            JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
            WHERE is_submit = 1 && id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id AND
            skor_jawaban = 4 ) AS persentase_4,

            ( SELECT COUNT(skor_jawaban) FROM jawaban_pertanyaan_harapan_$table_identity JOIN responden_$table_identity ON
            jawaban_pertanyaan_harapan_$table_identity.id_responden = responden_$table_identity.id
            JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
            WHERE is_submit = 1 && id_pertanyaan_unsur =
            pertanyaan_unsur_pelayanan_$table_identity.id) AS jumlah_pengisi,
            ( SELECT AVG(skor_jawaban) FROM jawaban_pertanyaan_harapan_$table_identity JOIN responden_$table_identity ON
            jawaban_pertanyaan_harapan_$table_identity.id_responden = responden_$table_identity.id
            JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
            WHERE is_submit = 1 && id_pertanyaan_unsur =
            pertanyaan_unsur_pelayanan_$table_identity.id) AS rata_rata,
            ( SELECT IF(( SELECT AVG(skor_jawaban) FROM jawaban_pertanyaan_harapan_$table_identity JOIN responden_$table_identity ON
            jawaban_pertanyaan_harapan_$table_identity.id_responden = responden_$table_identity.id
            JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
            WHERE is_submit = 1 && id_pertanyaan_unsur =
            pertanyaan_unsur_pelayanan_$table_identity.id) > 3.5324,
            'Sangat Baik',
            IF(( SELECT AVG(skor_jawaban) FROM jawaban_pertanyaan_harapan_$table_identity JOIN responden_$table_identity ON
            jawaban_pertanyaan_harapan_$table_identity.id_responden = responden_$table_identity.id
            JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
            WHERE is_submit = 1 && id_pertanyaan_unsur =
            pertanyaan_unsur_pelayanan_$table_identity.id) > 2.9,
            'Baik',
            IF(( SELECT AVG(skor_jawaban) FROM jawaban_pertanyaan_harapan_$table_identity JOIN responden_$table_identity ON
            jawaban_pertanyaan_harapan_$table_identity.id_responden = responden_$table_identity.id
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
                "caption": "H<?php echo $value->nomor_harapan . '. ' . $value->nama_unsur_pelayanan ?>",
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
                    label: "<?php echo $get_data_opsi[0]['nama_tingkat_kepentingan'] . ' = ' . $jumlah_1 ?>",
                    value: "<?php echo ROUND($jum_persentase_1, 2) ?>"
                },
                {
                    label: "<?php echo $get_data_opsi[1]['nama_tingkat_kepentingan'] . ' = ' . $jumlah_2 ?>",
                    value: "<?php echo ROUND($jum_persentase_2, 2) ?>"
                },
                {
                    label: "<?php echo $get_data_opsi[2]['nama_tingkat_kepentingan'] . ' = ' . $jumlah_3 ?>",
                    value: "<?php echo ROUND($jum_persentase_3, 2) ?>"
                },
                {
                    label: "<?php echo $get_data_opsi[3]['nama_tingkat_kepentingan'] . ' = ' . $jumlah_4 ?>",
                    value: "<?php echo ROUND($jum_persentase_4, 2) ?>"
                }
            ]
        }

    });
    myChart.render();
});
</script>


@endif
<?php } ?>


@endsection