@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
<style type="text/css">
.flex {
    display: flex;
    width: 100%;
    flex-direction: row;
}

.chart {
    min-width: 200px;
    height: 100%;
    width: 100%;
}
</style>

@endsection

@section('content')

<div class="container-fluid">

    <div class="row mt-5">
        <div class="col-md-3">
            @include('data_survey_klien/menu_data_survey_klien')
        </div>
        <div class="col-md-9">

            <div class="card" data-aos="fade-down">
                <div class="card-header font-weight-bold bg-secondary">
                    Grafik Kuadran
                </div>
                <div class="card-body">


                    {{-- <div class="text-right">
                        <button id="btn_convert" class="btn btn-light-danger btn-sm"><i class="fa fa-file-image"></i> Convert to Image</button>
                    </div> --}}

                    <div id="root"></div>

                    <table class="table table-bordered table-hover mt-5">
                        <tr style="background-color: #E4E6EF;">
                            <th>Kuadran</th>
                            <th>Kode Unsur</th>
                            <th>Nama Unsur</th>
                        </tr>

                        @foreach ($grafik->result() as $value)
                        <tr>
                            <td>
                                <?php if (
                                    $value->skor_unsur <= $total_rata_unsur && $value->skor_harapan >= $total_rata_harapan
                                ) {
                                    echo 'Kuadran I';
                                } else if ($value->skor_unsur >= $total_rata_unsur && $value->skor_harapan >= $total_rata_harapan) {
                                    echo 'Kuadran II';
                                } else if ($value->skor_unsur <= $total_rata_unsur && $value->skor_harapan <= $total_rata_harapan) {
                                    echo 'Kuadran III';
                                } else if ($value->skor_unsur >= $total_rata_unsur && $value->skor_harapan <= $total_rata_harapan) {
                                    echo 'Kuadran IV';
                                } else {
                                    NULL;
                                }
                                ?>

                            </td>
                            <td><?php echo $value->nomor ?></td>
                            <td><?php echo $value->nama_unsur_pelayanan ?></td>

                        </tr>
                        @endforeach
                    </table>

                </div>
            </div>

            <div class="card mt-5" data-aos="fade-down">
                <div class="card-header font-weight-bold bg-secondary">
                    Nilai Persepsi Dan Harapan
                </div>
                <div class="card-body">


                    <div class="table-responsive">
                        <table width="100%" class="table table-bordered" style="font-size: 12px;">
                            <tr style="background-color: black; color:white;">
                                <td colspan="<?php echo $colspan_unsur ?>">
                                    <div align="center"><strong>PERSEPSI</strong></div>
                                </td>
                            </tr>
                            <tr class="bg-light">
                                <td>
                                    <div align=" center"><strong></strong>
                                    </div>
                                </td>

                                @foreach ($persepsi->result() as $object)
                                <td>
                                    <div align="center"><strong><?php echo $object->nomor ?></strong></div>
                                </td>
                                @endforeach
                            </tr>

                            <tr>
                                <td class="bg-light"><strong>Rata-Rata per Unsur</strong></td>

                                @foreach ($nilai_per_unsur->result() as $nilai_per_unsur)
                                <td>
                                    <div align="center">
                                        <strong><?php echo $nilai_per_unsur->nilai_per_unsur ?></strong>
                                    </div>
                                </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td class="bg-light"><strong>Rata-Rata Akhir</strong></td>
                                <td colspan="9"><strong><?php echo round($total_rata_unsur, 2) ?></strong></td>
                            </tr>
                        </table>
                    </div>

                    <br>
                    <br>

                    <div class="table-responsive">
                        <table width="100%" class="table table-bordered" style="font-size: 12px;">
                            <tr style="background-color: black; color:white;">
                                <td colspan="<?php echo $colspan_unsur ?>">
                                    <div align="center"><strong>HARAPAN</strong></div>
                                </td>
                            </tr>
                            <tr class="bg-light">
                                <td>
                                    <div align="center"><strong></strong></div>
                                </td>

                                @foreach ($persepsi->result() as $object_harapan)
                                <td>
                                    <div align="center">
                                        <strong>H<?php echo $object_harapan->nomor_harapan ?></strong>
                                    </div>
                                </td>
                                @endforeach

                            </tr>

                            <tr>
                                <td class="bg-light"><strong>Rata-Rata per Harapan</strong></td>

                                @foreach ($nilai_per_unsur_harapan->result() as $nilai_per_unsur_harapan)
                                <td>
                                    <div align="center">
                                        <strong><?php echo $nilai_per_unsur_harapan->nilai_per_unsur ?></strong>
                                    </div>
                                </td>
                                @endforeach

                            </tr>
                            <tr>
                                <td class="bg-light"><strong>Rata-Rata Akhir</strong></td>
                                <td colspan="9"><strong><?php echo round($total_rata_harapan, 2) ?></strong>
                                </td>
                            </tr>
                        </table>
                    </div>


                </div>
            </div>

        </div>
    </div>

</div>

@endsection

@section('javascript')

<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
    integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
</script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/highcharts/7.1.1/highcharts.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/react/16.8.6/umd/react.production.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/react-dom/16.8.6/umd/react-dom.production.min.js'></script>


<script>
Highcharts.chart('root', {
    chart: {
        type: 'scatter',
        plotBorderWidth: 1,
        zoomType: 'xy',
        height: 450,
    },

    title: 'quadrant',
    legend: {
        enabled: false
    },
    tooltip: {
        pointFormat: '<span>{point.counter}</span>',
    },

    xAxis: {
        gridLineWidth: 0,
        startOnTick: true,
        endOnTick: true,
        crosshair: true,
        title: {
            text: 'PERFORMANCE'
        },
        plotLines: [{
            color: 'black',
            dashStyle: 'dot',
            width: 2,
            value: <?php echo round($total_rata_unsur, 2) ?>, //GARIS BATAS NILAI X
            zIndex: 3
        }],
    },

    yAxis: {
        gridLineWidth: 0,
        startOnTick: true,
        endOnTick: true,
        crosshair: true,
        title: {
            text: 'IMPORTANCE'
        },
        maxPadding: 0.2,
        plotLines: [{
            color: 'black',
            dashStyle: 'dot',
            width: 2,
            value: <?php echo round($total_rata_harapan, 2) ?>, //GARIS BATAS NILAI Y
            label: {
                align: 'right',
                style: {
                    fontStyle: 'italic'
                },
                x: -10
            },
            zIndex: 3
        }],
    },
    plotOptions: {
        series: {
            dataLabels: {
                defer: true,
                enabled: true,
                format: '{point.name}',
                style: {
                    fontSize: '14px',
                    fontFamily: 'monospace',
                    color: 'black'
                    //  fontStyle: 'italic'
                },
            }
        }
    },
    series: [{
        data: [
            <?php
                foreach ($grafik->result() as $rows_grafik) {
                ?> {
                x: <?php echo $rows_grafik->skor_unsur ?>,
                y: <?php echo $rows_grafik->skor_harapan ?>,
                name: '<?php echo $rows_grafik->nomor ?>',
                counter: '<?php echo $rows_grafik->nomor . '. ' . $rows_grafik->nama_unsur_pelayanan ?>',

            },
            <?php } ?>
        ]
    }, {
        enableMouseTracking: false,
        linkedTo: 0,
        marker: {
            enabled: false
        },
        dataLabels: {
            defer: false,
            enabled: true,
            //  y: 20,
            style: {
                fontSize: '14px',
                fontFamily: 'monospace',
                color: 'black',
                fontStyle: 'italic'
            },
            format: 'Kuadran {point.name}'
        },
        keys: ['x', 'y', 'name'],
        data: [
            [<?php echo round($total_rata_unsur - 2, 2) ?>,
                <?php echo round($total_rata_harapan + 2, 2) ?>, 'I'
            ],
            [<?php echo round($total_rata_unsur + 2, 2) ?>,
                <?php echo round($total_rata_harapan + 2, 2) ?>, 'II'
            ],
            [<?php echo round($total_rata_unsur - 2, 2) ?>,
                <?php echo round($total_rata_harapan - 2, 2) ?>, 'III'
            ],
            [<?php echo round($total_rata_unsur + 2, 2) ?>,
                <?php echo round($total_rata_harapan - 2, 2) ?>, 'IV'
            ]
        ]
    }],

});
</script>

@endsection