@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp


@section('style')

@endsection


@section('content')
<div class="container-fluid">
    @include('include_backend/partials_no_aside/_message')
    @include("include_backend/partials_no_aside/_inc_menu_repository")

    <div class="row mt-5">
        <div class="col-md-12">


            @if (in_array(1, $atribut_pertanyaan) && $query->atribut_kuadran == '')
            <div class="card mb-5" data-aos="fade-down">
                <div class="card-header font-weight-bold">
                    {{ $title }}
                </div>
                <div class="card-body">

                    <p>
                        Sebelum anda mendownload laporan SKM silahkan convert terlebih dahulu grafik kuadran agar
                        memudahkan proses download laporan SKM
                    </p>

                    <div id="chart_data">
                        <div id="root"></div>
                    </div>

                    <div class="text-right mb-3">
                        <button id="btn_convert" class="btn btn-primary font-weight-bold shadow tombolSubmit"><i class="fa fa-download"></i>
                            Convert Grafik Kuadran</button>
                    </div>

                    <div class="text-right text-danger font-weight-bold" style="font-style: italic;">Terakhir di Convert
                        pada <?php echo $tgl_convert ?></div>

                </div>
            </div>
            @else


            <img class="card-img-top" src="{{ base_url() }}assets/img/banner/laporan-img.jpg" alt="new image">

            <div class=" card mb-5 mt-5" data-aos="fade-down">
                <div class="card-body">
                    <p>
                        Setelah aktivitas survei selesai dan data sudah terkumpul maka Anda dapat mendownload
                        laporan SKM. Gunakan tombol dibawah ini untuk mendownload laporan SKM.
                        Anda.
                    </p>

                    <br>

                    <div class="card-deck">
                        <a href="{{ base_url() }}laporan-induk/{{ $ci->uri->segment(2) }}/{{ $ci->uri->segment(3) }}/download-docx" target="_blank" class="card card-body border border-primary text-primary shadow wave wave-animate-slow wave-primary">
                            <div class="text-center font-weight-bold">
                                <i class="fa fa-file-word text-primary" style="font-size: 30px;"></i><br>
                                <h6 class="mt-3">Download Laporan SKM format .docx</h6>
                            </div>
                        </a>

                        <a href="{{base_url() . 'laporan-induk/' . $ci->uri->segment(2) . '/' . $ci->uri->segment(3) . '/download-pdf'}}" class="card card-body text-danger border border-danger shadow wave wave-animate-slow wave-danger" target="_blank">
                            <div class="text-center font-weight-bold">
                                <i class="fa fa-file-pdf text-danger" style="font-size: 30px;"></i><br>
                                <h6 class="mt-3">Download Laporan SKM format .pdf</h5>
                            </div>
                        </a>
                    </div>

                </div>
            </div>
            @endif


            <!-- <div class="card mt-5">
                <div class="card-header">
                    Laporan SKM
                </div>
                <div class="card-body">
                    <a href="{{ base_url() }}{{ $ci->session->userdata('username') }}/{{ $ci->uri->segment(2) }}/laporan-survey/download"
                        title="Download laporan" target="_blank">Lihat data</a>
                    <br>
                    <a href="{{ base_url() }}{{ $ci->session->userdata('username') }}/{{ $ci->uri->segment(2) }}/laporan-survey/download-docx"
                        title="Download laporan" target="_blank">Download Word</a>
                </div>
            </div> -->

        </div>
    </div>
</div>



<!-- <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <div class="text-center">

                    <button class="btn btn-secondary tombolCancel" data-toggle="modal"
                        data-target=".bd-example-modal-lg">Cancel</button>
                    <button id="btn_convert" class="btn btn-info tombolSubmit"><i class="fa fa-file-image"></i>
                        Convert to Image</button>
                </div>
            </div>
        </div>
    </div>
</div> -->

@endsection

@section('javascript')

<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
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


<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script>
<script type="text/javascript">
    $(document).ready(function() {

        document.getElementById("btn_convert").addEventListener("click", function() {
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
                    beforeSend: function() {
                        $('.tombolCancel').attr('disabled', 'disabled');
                        $('.tombolSubmit').attr('disabled', 'disabled');
                        $('.tombolSubmit').html(
                            '<i class="fa fa-spin fa-spinner"></i> Sedang diproses');

                        Swal.fire({
                            title: 'Memproses data',
                            html: 'Mohon tunggu sebentar. Sistem sedang menyiapkan request anda.',
                            onOpen: () => {
                                swal.showLoading()
                            }
                        });

                    },

                    complete: function() {
                        $('.tombolCancel').removeAttr('disabled');
                        $('.tombolSubmit').removeAttr('disabled');
                        $('.tombolSubmit').html(
                            '<i class="fa fa-file-image"></i> Convert to Image');
                    }
                }).done(function(o) {
                    Swal.fire(
                        'Sukses',
                        'Image Berhasil di Convert.',
                        'success'
                    );
                    setTimeout(function() {
                        location.reload()
                    }, 1500);
                });
            });
        });
    });
</script>
@endsection