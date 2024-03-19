@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')
<link href="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />


<style type="text/css">
    [pointer-events="bounding-box"] {
        display: none
    }

    .dataTables_filter {
        display: none
    }

    .dataTables_length {
        display: none
    }
</style>
@endsection

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">

            <div class="card card-custom bgi-no-repeat gutter-b" style="height: 150px; background-color: #1c2840; background-position: calc(100% + 0.5rem) 100%; background-size: 100% auto; background-image: url(/assets/img/banner/rhone-2.svg)" data-aos="fade-down">
                <div class="card-body d-flex align-items-center">
                    <div>
                        <h3 class="text-white font-weight-bolder line-height-lg mb-5">
                            {{strtoupper($title)}}
                            <br>
                            {{strtoupper($nama_users . ' - ' . $nama_survey)}}
                        </h3>

                        <a class="btn btn-light-primary btn-sm" href="{{base_url() . 'nilai-unsur-per-bagian'}}"><i class="fa fa-arrow-left"></i> Kembali</a>
                    </div>
                </div>
            </div>


            @if($jumlah_kuesioner_terisi > 0)


            <div class="card card-custom card-sticky mb-5" data-aos="fade-down">
                <div class="card-body">

                    <div id="chart"></div>

                    <table class="table-bordered example mt-5" style="width:100%">
                        <thead class="bg-light">
                            <tr>
                                <th style="display:none;">ID</th>
                                <th style="vertical-align: middle; text-align:center;">Unsur</th>
                                <th style="vertical-align: middle; text-align:center;">Nilai Interval<br>(NI)</th>
                                <th style="vertical-align: middle; text-align:center;">Nilai Interval Konversi<br>(NIK)
                                </th>
                                <th style="vertical-align: middle; text-align:center;">Predikat{{--<br>(x)--}}</th>
                                {{--<th style="vertical-align: middle; text-align:center;">Kinerja Unit Pelayanan<br>(y)--}}
                                </th>

                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($nilai_per_unsur->result() as $value)
                            <?php
                            $data_chart[] = str_word_count($value->nama_unsur_pelayanan) > 2 ? '{label:"' . substr($value->nomor_unsur, 0, 2) . '. ' . substr($value->nama_unsur_pelayanan, 0, 7) . ' [...] ",  value:"' . $value->nilai_per_unsur . '"}' : '{label:"' . substr($value->nomor_unsur, 0, 2) . '. ' . $value->nama_unsur_pelayanan . '", value:"' . $value->nilai_per_unsur . '"}';

                            $nilai_konversi = $value->nilai_per_unsur * $skala_likert;
                            foreach ($definisi_skala->result() as $obj) {
                                if ($nilai_konversi <= $obj->range_bawah && $nilai_konversi >= $obj->range_atas) {
                                    $ktg = $obj->kategori;
                                    $mutu = $obj->mutu;
                                }
                            }
                            if ($nilai_konversi <= 0) {
                                $kategori = 'NULL';
                                $mutu = 'NULL';
                            }
                            // if ($nilai_konversi >= 25 && $nilai_konversi <= 64.99) {
                            //     $ktg = 'Tidak baik';
                            //     $mutu = 'D';
                            // } elseif ($nilai_konversi >= 65 && $nilai_konversi <= 76.60) {
                            //     $ktg = 'Kurang baik';
                            //     $mutu = 'C';
                            // } elseif ($nilai_konversi >= 76.61 && $nilai_konversi <= 88.30) {
                            //     $ktg = 'Baik';
                            //     $mutu = 'B';
                            // } elseif ($nilai_konversi >= 88.31 && $nilai_konversi <= 100) {
                            //     $ktg = 'Sangat baik';
                            //     $mutu = 'A';
                            // } else {
                            //     $ktg = 'NULL';
                            //     $mutu = 'NULL';
                            // };
                            ?>

                            <tr>
                                <td style="display:none;">{{$value->id_unsur}}</td>
                                <td>{{$value->nomor_unsur . '. ' . $value->nama_unsur_pelayanan}}</td>
                                <td class="text-center">{{ ROUND($value->nilai_per_unsur,3) }}</td>
                                <td class="text-center">{{ ROUND($nilai_konversi,2)}}</td>
                                <td class="text-center">{{$mutu}}</td>
                                <td class="text-center"><a class="btn btn-info btn-sm font-weight-bold" data-toggle="modal" onclick="showdetail({{$value->id_unsur}})" href="#modal_detail"><i class="fa fa-info-circle"></i> Detail</a></td>
                                {{--<td class="text-center">{{$ktg}}</td>--}}
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @php
                    $get_data_chart = implode(", ", $data_chart);
                    @endphp

                    <!-- Modal -->
                    <div class="modal fade bd-example-modal-lg" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <div class="card card-body">
                                        <img src="<?php echo base_url() . 'assets/survey/img/nilai_index_permenpan.png' ?>" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>



            <!-- START PIE CHART -->
            @foreach($unsur_pelayanan->result() as $row)
            <div class="card card-custom card-sticky mb-5" data-aos="fade-down">
                <div class="card-body">
                    <div id="pie_{{$row->id_unsur_pelayanan}}" class="d-flex justify-content-center"></div>

                    @php
                    $cek_sub = $ci->db->get_where("unsur_pelayanan_$table_identity", ['id_parent' =>
                    $row->id_unsur_pelayanan]);
                    @endphp

                    @if($cek_sub->num_rows() == 0)

                    <!-- UNSUR YANG TIDAK MEMILIKI TURUNAN -->
                    <table class="table table-bordered example mt-5" style="width:100%">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Kategori</th>
                                <th class="text-center">Jumlah</th>
                                <th class="text-center">Persentase</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $no = 1;
                            $t_perolehan = 0;
                            $t_persentase = 0;
                            @endphp
                            @foreach ($get_pilihan_jawaban->result() as $value)
                            @if($value->id_unsur_pelayanan == $row->id_unsur_pelayanan)
                            <tr>
                                <td class="text-center">{{$no++}}</td>
                                <td>{{$value->nama_kategori_unsur_pelayanan}}</td>
                                <td class="text-center">{{$value->perolehan}}</td>
                                <td class="text-center">
                                    {{ROUND(($value->perolehan/$value->jumlah_pengisi) * 100, 2)}} %
                                </td>
                            </tr>
                            @php
                            $t_perolehan += $value->perolehan;
                            $t_persentase += ($value->perolehan/$value->jumlah_pengisi) * 100;
                            @endphp
                            @endif
                            @endforeach
                        </tbody>
                        <tfoot class="bg-light">
                            <tr>
                                <th class="text-center" colspan="2">TOTAL</th>
                                <th class="text-center">{{$t_perolehan}}</th>
                                <th class="text-center">{{ROUND($t_persentase)}} %</th>
                            </tr>
                        </tfoot>

                    </table>

                    @else

                    <!-- UNSUR YANG MEMILIKI TURUNAN -->
                    <div class="row mb-5">
                        <div class="col-xl-9 font-weight-bold font-size-h6">
                            Kesimpulan {{ $row->nama_unsur_pelayanan }}
                        </div>
                        <div class="col-xl-3 text-right">
                            <a class="btn btn-primary btn-sm font-weight-bold shadow" data-toggle="collapse" href="#collapseExample{{ $row->id }}"><i class="fa fa-info-circle"></i> Lihat Detail
                                Sub
                                Unsur
                            </a>
                        </div>
                    </div>

                    <div class="collapse" id="collapseExample{{ $row->id_unsur_pelayanan }}">
                        @foreach($cek_sub->result() as $get)
                        <div class="card card-body mb-5">
                            <h5>{{$get->nomor_unsur . '. ' . $get->nama_unsur_pelayanan }}</h5>

                            <table class="table table-bordered example" style="width:100%">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Kategori</th>
                                        <th class="text-center">Jumlah</th>
                                        <th class="text-center">Persentase</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $no = 1;
                                    $t_perolehan_turunan = 0;
                                    $t_persentase_turunan = 0;
                                    @endphp
                                    @foreach ($get_pilihan_jawaban->result() as $value)
                                    @if($value->id_unsur_pelayanan == $get->id)
                                    <tr>
                                        <td class="text-center">{{$no++}}</td>
                                        <td>{{$value->nama_kategori_unsur_pelayanan}}</td>
                                        <td class="text-center">{{$value->perolehan}}</td>
                                        <td class="text-center">
                                            {{ROUND(($value->perolehan/$value->jumlah_pengisi) * 100, 2)}} %
                                        </td>
                                    </tr>
                                    @php
                                    $t_perolehan_turunan += $value->perolehan;
                                    $t_persentase_turunan += ($value->perolehan/$value->jumlah_pengisi) * 100;
                                    @endphp
                                    @endif
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-light">
                                    <tr>
                                        <th class="text-center" colspan="2">TOTAL</th>
                                        <th class="text-center">{{$t_perolehan_turunan}}</th>
                                        <th class="text-center">{{ROUND($t_persentase_turunan)}} %</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        @endforeach
                        <br>
                        <hr>
                        <hr>
                        <br>
                    </div>



                    <!-- RATA RATA UNSUR TURUNAN -->
                    <table class="table table-bordered example" style="width:100%">
                        <thead class="text-center bg-light">
                            <tr>
                                <th style="display: none;">ID</th>
                                <th width="30%" rowspan="2">Unsur</th>
                                <th colspan="{{ $manage_survey->skala_likert == 5 ? 5 : 4 }}">Persentase
                                    Persepsi Responden</th>
                                <th width="10%" rowspan="2">Indeks</th>
                                <th width="15%" rowspan="2">Predikat</th>
                            </tr>
                            <tr>
                                @php
                                $pilihan_jawaban_turunan = $ci->db->query("SELECT *
                                FROM kategori_unsur_pelayanan_$table_identity
                                JOIN unsur_pelayanan_$table_identity ON
                                kategori_unsur_pelayanan_$table_identity.id_unsur_pelayanan =
                                unsur_pelayanan_$table_identity.id
                                WHERE id_parent = $row->id_unsur_pelayanan
                                GROUP BY nomor_kategori_unsur_pelayanan")->result_array();
                                @endphp


                                <td>{{ $pilihan_jawaban_turunan[0]['nama_kategori_unsur_pelayanan']}}</td>
                                <td>{{ $pilihan_jawaban_turunan[1]['nama_kategori_unsur_pelayanan'] }}</td>
                                <td>{{ $pilihan_jawaban_turunan[2]['nama_kategori_unsur_pelayanan'] }}</td>
                                <td>{{ $pilihan_jawaban_turunan[3]['nama_kategori_unsur_pelayanan'] }}</td>

                                @if($manage_survey->skala_likert == 5)
                                <td>{{ $pilihan_jawaban_turunan[4]['nama_kategori_unsur_pelayanan'] }}</td>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $i = 0;
                            $jum_persentase_1 = 0;
                            $jum_persentase_2 = 0;
                            $jum_persentase_3 = 0;
                            $jum_persentase_4 = 0;
                            $jum_persentase_5 = 0;
                            $jum_indeks = 0;
                            @endphp
                            @foreach($rekap_turunan_unsur->result() as $obj)
                            @if($obj->id_parent == $row->id_unsur_pelayanan)
                            <tr>
                                <td style="display: none;">{{$obj->id_unsur_pelayanan}}</td>
                                <td>{{$obj->nomor_unsur . '. ' . $obj->nama_unsur_pelayanan}}</td>
                                <td class=" text-center">{{ROUND(($obj->perolehan_1/$obj->jumlah_pengisi) * 100, 2)}}
                                    %
                                </td>
                                <td class="text-center">{{ROUND(($obj->perolehan_2/$obj->jumlah_pengisi) * 100, 2)}}
                                    %
                                </td>
                                <td class="text-center">{{ROUND(($obj->perolehan_3/$obj->jumlah_pengisi) * 100, 2)}}
                                    %
                                </td>
                                <td class="text-center">{{ROUND(($obj->perolehan_4/$obj->jumlah_pengisi) * 100, 2)}}
                                    %
                                </td>

                                @if($manage_survey->skala_likert == 5)
                                <td class="text-center">{{ROUND(($obj->perolehan_5/$obj->jumlah_pengisi) * 100, 2)}}
                                    %
                                </td>
                                @endif

                                <td class="text-center">{{ROUND($obj->rata_rata, 2)}}</td>
                                <td class="text-center">
                                    <?php
                                    $predikat = $obj->rata_rata * $skala_likert;
                                    foreach ($definisi_skala->result() as $val) {
                                        if ($predikat <= $val->range_bawah && $predikat >= $val->range_atas) {
                                            echo $val->kategori;
                                        }
                                    }
                                    if ($predikat <= 0) {
                                        echo 'NULL';
                                    }

                                    // $predikat = $obj->rata_rata * $skala_likert;
                                    // if ($predikat <= 100 && $predikat >= 88.31) {
                                    //     echo 'Sangat Baik';
                                    // } elseif ($predikat <= 88.40 && $predikat >= 76.61) {
                                    //     echo 'Baik';
                                    // } elseif ($predikat <= 76.60 && $predikat >= 65) {
                                    //     echo 'Kurang Baik';
                                    // } elseif ($predikat <= 64.99 && $predikat >= 25) {
                                    //     echo 'Tidak Baik';
                                    // } else {
                                    //     echo 'NULL';
                                    // }
                                    ?>
                                </td>
                            </tr>

                            @php
                            $jum_persentase_1 += ($obj->perolehan_1/$obj->jumlah_pengisi) * 100;
                            $jum_persentase_2 += ($obj->perolehan_2/$obj->jumlah_pengisi) * 100;
                            $jum_persentase_3 += ($obj->perolehan_3/$obj->jumlah_pengisi) * 100;
                            $jum_persentase_4 += ($obj->perolehan_4/$obj->jumlah_pengisi) * 100;
                            $jum_persentase_5 += ($obj->perolehan_5/$obj->jumlah_pengisi) * 100;
                            $jum_indeks += $obj->rata_rata;
                            $i++
                            @endphp
                            @endif
                            @endforeach
                        </tbody>

                        <tfoot class="bg-light text-center">
                            <tr>
                                <th>RATA - RATA</th>
                                <th>{{ROUND($jum_persentase_1/$i,2)}} %</th>
                                <th>{{ROUND($jum_persentase_2/$i,2)}} %</th>
                                <th>{{ROUND($jum_persentase_3/$i,2)}} %</th>
                                <th>{{ROUND($jum_persentase_4/$i,2)}} %</th>

                                @if($manage_survey->skala_likert == 5)
                                <th>{{ROUND($jum_persentase_5/$i,2)}} %</th>
                                @endif

                                <th>{{ROUND($jum_indeks/$i,2)}}</th>
                                <th>
                                    <?php
                                    $rata_predikat = ($jum_indeks / $i) * $skala_likert;
                                    foreach ($definisi_skala->result() as $val) {
                                        if ($predikat <= $val->range_bawah && $predikat >= $val->range_atas) {
                                            echo $val->kategori;
                                        }
                                    }
                                    if ($predikat <= 0) {
                                        echo 'NULL';
                                    }


                                    // $rata_predikat = ($jum_indeks / $i) * 25;
                                    // if ($rata_predikat <= 100 && $rata_predikat >= 88.31) {
                                    //     echo 'Sangat Baik';
                                    // } elseif ($rata_predikat <= 88.40 && $rata_predikat >= 76.61) {
                                    //     echo 'Baik';
                                    // } elseif ($rata_predikat <= 76.60 && $rata_predikat >= 65) {
                                    //     echo 'Kurang Baik';
                                    // } elseif ($rata_predikat <= 64.99 && $rata_predikat >= 25) {
                                    //     echo 'Tidak Baik';
                                    // } else {
                                    //     echo 'NULL';
                                    // }
                                    ?>
                                </th>
                            </tr>
                        </tfoot>
                    </table>


                    @endif

                </div>
            </div>
            @endforeach


            @else

            <div class="card card-body">
                <div class="text-danger text-center"><i>Belum ada data responden yang sesuai.</i></div>
            </div>
            @endif


        </div>
    </div>
</div>

<!-- ======================================= MODAL DETAIL ========================================== -->
<div class="modal fade" id="modal_detail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border border-light">
            <!-- <div class="modal-header bg-light">
                <h5 class="modal-title" id="exampleModalLabel">Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div> -->
            <div class="modal-body" id="bodyModalDetail">
                <div align="center" id="loading_registration">
                    <img src="{{ base_url() }}assets/site/img/ajax-loader.gif" alt="">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
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
<script src="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.js"></script>



<script>
    $(document).ready(function() {
        $('.example').DataTable();
    });
</script>


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



@foreach($unsur_pelayanan->result() as $row)
@php
$cek_sub = $ci->db->get_where("unsur_pelayanan_$table_identity", ['id_parent' =>
$row->id_unsur_pelayanan]);
@endphp

@if($cek_sub->num_rows() == 0)
<script>
    FusionCharts.ready(function() {
        var myChart = new FusionCharts({
            "type": "pie3d",
            "renderAt": "pie_{{$row->id_unsur_pelayanan}}",
            "width": "100%",
            "height": "350",
            "dataFormat": "json",
            dataSource: {
                "chart": {
                    "caption": "<?php echo $row->nomor_unsur . '. ' . $row->nama_unsur_pelayanan ?>",
                    subcaption: "<?php echo strip_tags($row->isi_pertanyaan_unsur) ?>",
                    "enableSmartLabels": "1",
                    "startingAngle": "0",
                    "showPercentValues": "1",
                    "decimals": "2",
                    "useDataPlotColorForLabels": "1",
                    "theme": "umber",
                    "bgColor": "#ffffff"
                },
                "data": [
                    <?php foreach ($get_pilihan_jawaban->result() as $value) {
                        if ($value->id_unsur_pelayanan == $row->id_unsur_pelayanan) { ?> {
                                label: "<?php echo $value->nama_kategori_unsur_pelayanan . ' = ' . $value->perolehan ?>",
                                value: "<?php echo $value->perolehan ?>"
                            },
                        <?php }
                } ?>
                ]
            }
        });
        myChart.render();
    });
</script>
@else

@php
$unsur_turunan = $ci->db->query("SELECT nama_kategori_unsur_pelayanan, nomor_kategori_unsur_pelayanan,
(SELECT COUNT(skor_jawaban) FROM jawaban_pertanyaan_unsur_$table_identity
JOIN survey_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden =
survey_$table_identity.id_responden
JOIN pertanyaan_unsur_pelayanan_$table_identity ON pertanyaan_unsur_pelayanan_$table_identity.id =
jawaban_pertanyaan_unsur_$table_identity.id_pertanyaan_unsur
JOIN unsur_pelayanan_$table_identity ON pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan =
unsur_pelayanan_$table_identity.id
WHERE is_submit = 1 && unsur_pelayanan_$table_identity.id_parent = $row->id_unsur_pelayanan && skor_jawaban =
kategori_unsur_pelayanan_$table_identity.nomor_kategori_unsur_pelayanan) AS perolehan

FROM kategori_unsur_pelayanan_$table_identity
JOIN unsur_pelayanan_$table_identity ON kategori_unsur_pelayanan_$table_identity.id_unsur_pelayanan =
unsur_pelayanan_$table_identity.id
WHERE id_parent = $row->id_unsur_pelayanan
GROUP BY nomor_kategori_unsur_pelayanan")
@endphp

<script>
    FusionCharts.ready(function() {
        var myChart = new FusionCharts({
            "type": "pie3d",
            "renderAt": "pie_{{$row->id_unsur_pelayanan}}",
            "width": "100%",
            "height": "350",
            "dataFormat": "json",
            dataSource: {
                "chart": {
                    "caption": "<?php echo $row->nomor_unsur . '. ' . $row->nama_unsur_pelayanan ?>",
                    "enableSmartLabels": "1",
                    "startingAngle": "0",
                    "showPercentValues": "1",
                    "decimals": "2",
                    "useDataPlotColorForLabels": "1",
                    "theme": "umber",
                    "bgColor": "#ffffff"
                },
                "data": [
                    <?php foreach ($unsur_turunan->result() as $obj) { ?> {
                            label: "<?php echo $obj->nama_kategori_unsur_pelayanan . ' = ' . $obj->perolehan ?>",
                            value: "<?php echo $obj->perolehan ?>"
                        },
                    <?php } ?>
                ]
            }
        });
        myChart.render();
    });
</script>
@endif


@endforeach





<script>
    function showdetail(id) {
        $('#bodyModalDetail').html(
            "<div class='text-center'><img src='{{ base_url() }}assets/img/ajax/ajax-loader-big.gif'></div>");

        $.ajax({
            type: "post",
            url: "{{base_url() . 'nilai-unsur-per-bagian/' . $ci->uri->segment(2) . '/' . $ci->uri->segment(3) . '/detail/'}}" +
                id,
            // data: "id=" + id,
            dataType: "text",
            success: function(response) {

                // $('.modal-title').text('Edit Pertanyaan Unsur');
                $('#bodyModalDetail').empty();
                $('#bodyModalDetail').append(response);
            }
        });
    }
</script>
@endsection