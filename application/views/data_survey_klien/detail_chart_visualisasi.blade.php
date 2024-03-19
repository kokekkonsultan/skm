@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')

@endsection

@section('content')

<div class="container-fluid">

    <div class="row mt-5">
        <div class="col-md-3">
            @include('data_survey_klien/menu_data_survey_klien')
        </div>
        <div class="col-md-9">

            <div class="card card-custom card-sticky mb-5" data-aos="fade-down">
                <div class="card-body">

                    <h5>Indeks Keseluruhan</h5>

                    <div id="chart"></div>


                    <table class="table table-bordered">
                        <tr>
                            <th>No</th>
                            <th>Unsur</th>
                            <th>Indeks</th>
                            <th>Kategori</th>
                        </tr>

                        <?php
                        $no = 1;
                        $nama_unsur_pelayanan = [];
                        foreach ($nilai_per_unsur->result() as $value) { ?>

                        @php
                        $nama_unsur_pelayanan[] = $value->nama_unsur_pelayanan;
                        @endphp
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $value->nama_unsur_pelayanan }}</td>
                            <td>{{ ROUND($value->nilai_per_unsur,2) }}</td>
                            <td>
                                @php
                                $npu = $value->nilai_per_unsur;
                                if ($npu <= 4 && $npu>= 3.5) {
                                    $ktg = "Sangat Baik";
                                    } elseif($npu <= 3.4 && $npu>= 3.1){
                                        $ktg = "Baik";
                                        } elseif($npu <= 3.0 && $npu>= 2.6){
                                            $ktg = "Kurang Baik";
                                            } else {
                                            $ktg = "Tidak Baik";
                                            }
                                            @endphp
                                            {{ $ktg }}
                            </td>
                        </tr>
                        <?php } ?>
                    </table>

                    @php
                    $nama_unsur = [];
                    $nilai_index = [];
                    foreach ($nilai_per_unsur->result() as $value) {
                    $nama_unsur[] = "'".$value->nama_unsur_pelayanan."'";
                    $nilai_index[] = $value->nilai_per_unsur;
                    }

                    $nama_unsur = implode(", ", $nama_unsur);
                    $nilai_index = implode(", ",$nilai_index);
                    @endphp

                </div>
            </div>

            <?php
            $noc = 1;
            foreach ($unsur_pelayanan->result() as $value) { ?>

            <div class="card mb-5" data-aos="fade-down">
                <div class="card-body">
                    <h4><span class="badge badge-secondary">{{ $value->nama_unsur_pelayanan }}</span></h4>
                    <br>

                    <?php $sub_unsur = $ci->db->get_where("unsur_pelayanan_$table_identity", ['id_parent' => $value->id_unsur_pelayanan]); ?>

                    <?php if ($sub_unsur->num_rows() > 0) { ?>

                    <p>
                        Unsur pelayanan ini memiliki sub unsur.
                        <a class="btn btn-light-primary font-weight-bold shadow" data-toggle="collapse"
                            href="#collapseExample{{ $noc }}" role="button" aria-expanded="false"
                            aria-controls="collapseExample{{ $noc }}">
                            Tampilkan Detail
                        </a>
                    </p>
                    <div class="collapse" id="collapseExample{{ $noc }}">
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


                            <h4><span
                                    class="badge badge-secondary">{{ $unsur_pelayanan_aa->nama_unsur_pelayanan }}</span>
                            </h4>
                            <br>

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
                                    <td>{{ $val_p->persentase }} %</td>
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
                                    <td>{{ $t_persen }} %</td>
                                </tr>
                            </table>

                            <?php } ?>

                            <br>
                            <br>

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


                    <div id="pie_<?php echo $noc++ ?>" class="d-flex justify-content-center"></div>


                    <div class="mt-10 mb-10 font-weight-bold">
                        Kesimpulan {{ $value->nama_unsur_pelayanan }}
                    </div>

                    <table width="100%" class="table table-bordered">
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
                            <td>{{ $elements->persentase_1 }} %</td>
                            <td>{{ $elements->persentase_2 }} %</td>
                            <td>{{ $elements->persentase_3 }} %</td>
                            <td>{{ $elements->persentase_4 }} %</td>
                            <td>{{ round($elements->rata_rata, 2) }}</td>
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
                            <td>{{ round(($jum_persentase_3 / $no), 2) }} %</td>
                            <td>{{ round(($jum_persentase_4 / $no), 2) }} %</td>
                            <td>{{ $f_indeks }}</td>
                            <td>
                                @php
                                if ($f_indeks > 3.5324) {
                                $h_indeks = "Sangat Baik";
                                } elseif($f_indeks > 2.9){
                                $h_indeks = "Baik";
                                } elseif($f_indeks > 2.6){
                                $h_indeks = "Kurang Baik";
                                } else {
                                $h_indeks = "Tidak Baik";
                                }
                                @endphp
                                <b>{{ $h_indeks }}</b>
                            </td>
                        </tr>
                    </table>

                    <?php } else { ?>

                    <div id="pie_<?php echo $noc++ ?>" class="d-flex justify-content-center"></div>


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
                            <td>{{ $val_p->persentase }} %</td>
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
                            <td>{{ $t_persen }} %</td>
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
var options = {
    series: [{
        data: [<?php echo $nilai_index ?>]
    }],
    chart: {
        type: 'bar',
        height: 400
    },
    plotOptions: {
        bar: {
            borderRadius: 4,
            horizontal: true,
        }
    },
    dataLabels: {
        enabled: false
    },
    xaxis: {

        categories: [
            <?php echo $nama_unsur ?>
        ],
    }
};

var chart = new ApexCharts(document.querySelector("#chart"), options);
chart.render();
</script>


<?php
$noc = 1;
foreach ($unsur_pelayanan->result() as $value) { ?>


@php
$sub_unsur = $ci->db->get_where("unsur_pelayanan_$table_identity", ['id_parent' => $value->id_unsur_pelayanan]);
@endphp



<?php if ($sub_unsur->num_rows() > 0) { ?>


@php
$ci->db->select("*, unsur_pelayanan_$table_identity.id AS id_unsur_pelayanan,
pertanyaan_unsur_pelayanan_$table_identity.id AS id_pertanyaan_unsur_pelayanan");
$ci->db->from("unsur_pelayanan_$table_identity");
$ci->db->join("pertanyaan_unsur_pelayanan_$table_identity",
"pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan = unsur_pelayanan_$table_identity.id");
$ci->db->where(['id_parent' => $value->id_unsur_pelayanan]);
$unsur_pelayanan_a = $ci->db->get();
@endphp

<?php foreach ($unsur_pelayanan_a->result() as $element_a) { ?>

@php
$ci->db->select("*, unsur_pelayanan_$table_identity.id AS id_unsur_pelayanan,
pertanyaan_unsur_pelayanan_$table_identity.id AS id_pertanyaan_unsur_pelayanan");
$ci->db->from("unsur_pelayanan_$table_identity");
$ci->db->join("pertanyaan_unsur_pelayanan_$table_identity",
"pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan = unsur_pelayanan_$table_identity.id");
$ci->db->where(["unsur_pelayanan_$table_identity.id" => $element_a->id_unsur_pelayanan]);
$unsur_pelayanan_aa = $ci->db->get()->row();
@endphp

@php
$id_pertanyaan_unsur_pelayanan = $unsur_pelayanan_aa->id_pertanyaan_unsur_pelayanan;
$persentase_detail = $ci->db->query("
SELECT
kup.id AS id_kup,
kup.nama_kategori_unsur_pelayanan,
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
@endphp

@php
$no = 1;
$t_jum = 0;
$t_persen = 0;
@endphp

<?php foreach ($persentase_detail->result() as $val_p) { ?>

@php
$t_jum += $val_p->jumlah;
$t_persen += $val_p->persentase;
@endphp
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
@endphp

@php
$no = 0;
$jum_persentase_1 = 0;
$jum_persentase_2 = 0;
$jum_persentase_3 = 0;
$jum_persentase_4 = 0;
$jum_indeks = 0;
$nama_sub_unsur = [];
@endphp

<?php foreach ($rel_data->result() as $elements) { ?>

@php

$nama_sub_unsur[] = $elements->nama_unsur_pelayanan;
$jum_persentase_1 += $elements->persentase_1;
$jum_persentase_2 += $elements->persentase_2;
$jum_persentase_3 += $elements->persentase_3;
$jum_persentase_4 += $elements->persentase_4;
$jum_indeks += $elements->rata_rata;
$no++;

$f_indeks = round(($jum_indeks/ $no), 2);
@endphp

<?php } ?>

<?php } ?>


<script>
var options = {
    series: [<?php echo $jum_persentase_1 ?>, <?php echo $jum_persentase_2 ?>, <?php echo $jum_persentase_3 ?>,
        <?php echo $jum_persentase_4 ?>
    ],
    chart: {
        width: 380,
        type: 'pie',
    },
    colors: ['#FF2360', '#FEBD01', '#4C00FF', '#00F4CE'],
    labels: ['<?php echo $get_data_opsi[0]['nama_kategori_unsur_pelayanan'] ?>',
        '<?php echo $get_data_opsi[1]['nama_kategori_unsur_pelayanan'] ?>',
        '<?php echo $get_data_opsi[2]['nama_kategori_unsur_pelayanan'] ?>',
        '<?php echo $get_data_opsi[3]['nama_kategori_unsur_pelayanan'] ?>'
    ],
    responsive: [{
        breakpoint: 480,
        options: {
            chart: {
                width: 200
            },
            legend: {
                position: 'bottom'
            }
        }
    }]
};

var chart = new ApexCharts(document.querySelector("#pie_<?php echo $noc++ ?>"), options);
chart.render();
</script>

<?php } else { ?>

<!-- Tidak Ada Turunan <br> -->

@php
$ci->db->select("*, unsur_pelayanan_$table_identity.id AS id_unsur_pelayanan,
pertanyaan_unsur_pelayanan_$table_identity.id AS id_pertanyaan_unsur_pelayanan");
$ci->db->from("unsur_pelayanan_$table_identity");
$ci->db->join("pertanyaan_unsur_pelayanan_$table_identity",
"pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan = unsur_pelayanan_$table_identity.id");
$ci->db->where(["unsur_pelayanan_$table_identity.id" => $value->id_unsur_pelayanan]);
$unsur_pelayanan_b = $ci->db->get()->row();
@endphp

@php
$id_pertanyaan_unsur_pelayanan = $unsur_pelayanan_b->id_pertanyaan_unsur_pelayanan;

$persentase_detail = $ci->db->query("
SELECT
kup.nama_kategori_unsur_pelayanan,
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
@endphp


@php
$nama_kategori_unsur_pelayanan = [];
$persentase = [];
@endphp
@foreach ($persentase_detail as $element)
@php
$nama_kategori_unsur_pelayanan[] = "'" . $element['nama_kategori_unsur_pelayanan'] . "'";
$persentase[] = $element['persentase'];
@endphp
@endforeach

@php
$total = implode(", ", $persentase);
$kelompok = implode(", ", $nama_kategori_unsur_pelayanan);
@endphp

<script>
var options = {
    series: [<?php echo $total ?>],
    chart: {
        width: 380,
        type: 'pie',
    },
    colors: ['#FF2360', '#FEBD01', '#4C00FF', '#00F4CE'],
    labels: [<?php echo $kelompok ?>],
    responsive: [{
        breakpoint: 450,
        options: {
            chart: {
                width: 200
            },
            legend: {
                position: 'bottom'
            }
        }
    }]
};

var chart = new ApexCharts(document.querySelector("#pie_<?php echo $noc++ ?>"), options);
chart.render();
</script>



<?php } ?>


<?php } ?>


@endsection