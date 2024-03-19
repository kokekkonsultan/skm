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
    <div class="row mt-5" data-aos="fade-down">
        <div class="col-md-3">
            @include('data_survey_klien/menu_data_survey_klien')
        </div>
        <div class="col-md-9">

            <div class="card card-custom card-sticky mb-2" data-aos="fade-down">
                <div class="card-header">
                    <div class="card-title">
                        Rekapitulasi Pertanyaan Tambahan
                    </div>
                    <div class="card-toolbar">

                    </div>
                </div>
            </div>


            @foreach($pertanyaan_tambahan->result() as $row)
            <div class="card card-body mb-5">
                <h4><span
                        class="badge badge-secondary">{{$row->nomor_pertanyaan_terbuka . '. ' . $row->nama_pertanyaan_terbuka}}</span>
                </h4>

                <div class="d-flex justify-content-center" id="<?php echo $row->nomor_pertanyaan_terbuka ?>"></div>
                <br>

                @if($row->id_jenis_pilihan_jawaban == 1)
                <table class="table table-bordered table-striped table-hover mt-5">
                    <tr>
                        <th>No</th>
                        <th>Kelompok</th>
                        <th>Jumlah</th>
                        <th>Persentase</th>
                    </tr>

                    @php
                    $no = 1;
                    @endphp
                    @foreach ($jawaban_ganda->result() as $value)
                    @if ($value->id_pertanyaan_terbuka == $row->id_pertanyaan_terbuka)
                    <tr>
                        <td><?php echo $no++ ?></td>
                        <td><?php echo $value->pertanyaan_ganda ?></td>
                        <td><?php echo $value->perolehan ?></th>
                        <td><?php echo ROUND($value->persentase, 2) ?> %</td>
                    </tr>
                    @endif
                    @endforeach


                    @if($row->is_lainnya == 1)
                    <tr>
                        <td><?php echo $no++ ?></td>
                        <td>Lainnya</td>
                        <td><?php echo $row->perolehan ?></th>
                        <td><?php echo ROUND($row->persentase, 2) ?> %</td>
                    </tr>
                    @endif

                </table>
                @else

                <table class="table table-bordered table-striped table-hover mt-5">
                    <tr>
                        <th>No</th>
                        <th>Jawaban</th>
                    </tr>

                    @php
                    $i = 1;
                    @endphp
                    @foreach ($jawaban_isian->result() as $get)
                    @if ($get->id_pertanyaan_terbuka == $row->id_pertanyaan_terbuka)
                    <tr>
                        <td><?php echo $i++ ?></td>
                        <td><?php echo $get->jawaban ?></td>
                    </tr>
                    @endif
                    @endforeach
                </table>

                @endif

            </div>
            @endforeach

        </div>
    </div>
</div>


@endsection

@section('javascript')

@foreach($pertanyaan_tambahan->result() as $row)

@if($row->id_jenis_pilihan_jawaban == 1)

<?php
$jumlah = [];
$nama_kelompok = [];
foreach ($jawaban_ganda->result() as $value) {
    if ($value->id_pertanyaan_terbuka == $row->id_pertanyaan_terbuka) {
        $jumlah[] = $value->perolehan;
        $nama_kelompok[] = "'" . $value->pertanyaan_ganda . "'";
    }
}

if ($row->is_lainnya == 1) {
    $total_data = implode(", ", $jumlah);
    $kelompok_data = implode(", ", $nama_kelompok);

    $total = $total_data . ', ' . $row->perolehan;
    $kelompok = $kelompok_data . ", 'Lainnya'";
} else {
    $total = implode(", ", $jumlah);
    $kelompok = implode(", ", $nama_kelompok);
}
?>

<script>
var options = {
    series: [
        <?php echo  $total ?>

    ],
    chart: {
        width: 355,
        type: 'pie',
    },
    labels: [<?php echo $kelompok ?>],
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

var chart = new ApexCharts(document.querySelector("#<?php echo $row->nomor_pertanyaan_terbuka ?>"), options);
chart.render();
</script>

@endif
@endforeach

@endsection