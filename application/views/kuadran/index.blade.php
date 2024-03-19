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

.highcharts-credits {
    display: none;
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

            <div class="card" data-aos="fade-down">
                <div class="card-header font-weight-bold bg-secondary">
                    Grafik Kuadran
                </div>
                <div class="card-body">


                    {{-- <div class="text-right">
                        <button id="btn_convert" class="btn btn-light-danger btn-sm"><i class="fa fa-file-image"></i> Convert to Image</button>
                    </div> --}}

                    <div id="root"></div>

                    <table class="table table-bordered table-hover mt-8">
                        <tr>
                            <th class="text-center bg-light" width="30%" style="vertical-align: middle;">
                                KUADRAN I</th>
                            <td>
                                <ul>
                                    @foreach($grafik->result() as $row)
                                    @if($row->kuadran == 1)
                                    <li>{{$row->nomor_unsur . '. ' . $row->nama_unsur_pelayanan}}</li>
                                    <hr>
                                    @endif
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-center bg-light" width="30%" style="vertical-align: middle;">KUADRAN II</th>
                            <td>
                                <ul>
                                    @foreach($grafik->result() as $row)
                                    @if($row->kuadran == 2)
                                    <li>{{$row->nomor_unsur . '. ' . $row->nama_unsur_pelayanan}}</li>
                                    <hr>
                                    @endif
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-center bg-light" width="30%" style="vertical-align: middle;">KUADRAN III
                            </th>
                            <td>
                                <ul>
                                    @foreach($grafik->result() as $row)
                                    @if($row->kuadran == 3)
                                    <li>{{$row->nomor_unsur . '. ' . $row->nama_unsur_pelayanan}}</li>
                                    <hr>
                                    @endif
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-center bg-light" width="30%" style="vertical-align: middle;">KUADRAN IV</th>
                            <td>
                                <ul>
                                    @foreach($grafik->result() as $row)
                                    @if($row->kuadran == 4)
                                    <li>{{$row->nomor_unsur . '. ' . $row->nama_unsur_pelayanan}}</li>
                                    <hr>
                                    @endif
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                    </table>

                    <a data-toggle="modal" data-target="#exampleModalCenter" class="font-weight-bold text-primary">
                        Tabel Analisa Atribut Prioritas Untuk Perbaikan </a>
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
                                        <strong><?php echo ROUND($nilai_per_unsur->nilai_per_unsur, 2) ?></strong>
                                    </div>
                                </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td class="bg-light"><strong>Rata-Rata Akhir</strong></td>
                                <td colspan="9"><strong><?php echo ROUND($total_rata_unsur, 2) ?></strong></td>
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
                                        <strong><?php echo ROUND($nilai_per_unsur_harapan->nilai_per_unsur, 2) ?></strong>
                                    </div>
                                </td>
                                @endforeach

                            </tr>
                            <tr>
                                <td class="bg-light"><strong>Rata-Rata Akhir</strong></td>
                                <td colspan="9"><strong><?php echo ROUND($total_rata_harapan, 2) ?></strong>
                                </td>
                            </tr>
                        </table>
                    </div>


                </div>
            </div>

        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="exampleModalCenter" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-secondary">
                <h6 class="modal-title">Analisa Atribut Prioritas Untuk Perbaikan</h6>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-hover table-striped">
                    <tr>
                        <th class="text-center">KUADRAN</th>
                        <th class="text-center">KETERANGAN</th>
                    </tr>
                    <tr>
                        <td class="text-center" style="vertical-align: middle;">I</td>
                        <td>Menunjukkan faktor atau atribut yang dianggap mempengaruhi, kepuasan
                            masyarakat,
                            termasuk unsur-unsur jasa yang dianggap sangat penting, namun
                            manajemen belum
                            melaksanakannya sesuai keinginan masyarakat. Sehingga
                            mengecewakan/tidak puas.
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center" style="vertical-align: middle;">II</td>
                        <td>Menunjukkan unsur jasa pokok yang telah berhasil dilaksanakan, untuk
                            itu wajib
                            dipertahankan. Dianggap sangat penting dan sangat memuaskan.</td>
                    </tr>
                    <tr>
                        <td class="text-center" style="vertical-align: middle;">III</td>
                        <td>Menunjukkan beberapa faktor yang kurang penting pengaruhnya bagi
                            masyarakat,
                            pelaksanaannya oleh instansi biasa-biasa saja. Dianggap kurang
                            penting dan kurang
                            memuaskan.
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center" style="vertical-align: middle;">IV</td>
                        <td>Menunjukkan faktor yang mempengaruhi masyarakat kurang penting, akan
                            tetapi
                            pelaksanaannya berlebihan. Dianggap kurang penting tetapi sangat
                            memuaskan.
                        </td>
                    </tr>
                </table>
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
            value: <?php echo $total_rata_unsur ?>, //GARIS BATAS NILAI X
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
            value: <?php echo $total_rata_harapan ?>, //GARIS BATAS NILAI Y
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
                name: '<?php echo $rows_grafik->nomor_unsur ?>',
                counter: '<?php echo $rows_grafik->nomor_unsur . '. ' . $rows_grafik->nama_unsur_pelayanan . '<br>Persepsi = ' . ROUND($rows_grafik->skor_unsur, 3) . ', Harapan = ' . ROUND($rows_grafik->skor_harapan, 3) ?>',

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
            [<?php echo $total_rata_unsur - 0.5 ?>,
                <?php echo $total_rata_harapan + 0.5 ?>, 'I'
            ],
            [<?php echo $total_rata_unsur + 0.5 ?>,
                <?php echo $total_rata_harapan + 0.5 ?>, 'II'
            ],
            [<?php echo $total_rata_unsur - 0.5 ?>,
                <?php echo $total_rata_harapan - 0.5 ?>, 'III'
            ],
            [<?php echo $total_rata_unsur + 0.5 ?>,
                <?php echo $total_rata_harapan - 0.5 ?>, 'IV'
            ]
        ]
    }],

});
</script>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script>

<script>
function get_canvas() {
    // $(document).ready(function() {

    // document.getElementById("btn_convert").addEventListener("click", function() {

    html2canvas(document.getElementById("root"), {
        allowTaint: true,
        useCORS: true
    }).then(function(canvas) {
        var anchorTag = document.createElement("a");
        document.body.appendChild(anchorTag);

        var dataURL = canvas.toDataURL();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/kuadran/convert' ?>",
            data: {
                imgBase64: dataURL
            },
            beforeSend: function() {},
            complete: function() {}
        }).done(function(o) {

        });
    });
    // });
    // });
};
setTimeout(get_canvas, 2500);
</script>

@endsection