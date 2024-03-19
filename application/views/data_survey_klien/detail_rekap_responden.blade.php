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

            @foreach ($profil_responden as $key => $row)
            <div class="card card-custom card-sticky mb-5">
                <div class="card-body">
                    <h4><span class="badge badge-secondary"><?php echo $row->nama_profil_responden ?></span></h4>

                    <div class="d-flex justify-content-center" id="<?php echo $row->nama_alias ?>"></div>
                    <br>

                    <table class="table table-bordered table-striped table-hover">
                        <tr>
                            <th>No.</th>
                            <th>Kelompok</th>
                            <th>Jumlah</th>
                            <th>Persentase</th>
                        </tr>

                        @php
                        $kategori_profil_responden = $ci->db->query("SELECT *, (SELECT COUNT(*) FROM
                        responden_$table_identity JOIN survey_$table_identity ON responden_$table_identity.id =
                        survey_$table_identity.id_responden WHERE kategori_profil_responden_$table_identity.id =
                        responden_$table_identity.$row->nama_alias && is_submit = 1) AS perolehan,
                        ROUND((((SELECT COUNT(*) FROM responden_$table_identity JOIN survey_$table_identity ON
                        responden_$table_identity.id = survey_$table_identity.id_responden WHERE
                        kategori_profil_responden_$table_identity.id = responden_$table_identity.$row->nama_alias &&
                        is_submit = 1) / (SELECT COUNT(*) FROM survey_$table_identity WHERE is_submit = 1)) * 100), 2)
                        AS persentase

                        FROM kategori_profil_responden_$table_identity")
                        @endphp

                        <?php
                        $no = 1;
                        $jumlah = [];
                        $nama_kelompok = [];
                        foreach ($kategori_profil_responden->result() as $value) { ?>

                        <?php if ($value->id_profil_responden == $row->id) { ?>



                        <tr>
                            <td><?php echo $no++ ?></td>
                            <td><?php echo $value->nama_kategori_profil_responden ?></td>
                            <td><?php echo $value->perolehan ?></th>
                            <td><?php echo $value->persentase ?> %</td>
                        </tr>

                        <?php } ?>
                        <?php } ?>

                    </table>
                </div>
            </div>
            @endforeach

        </div>
    </div>
</div>
@endsection

@section('javascript')

<script src="{{ TEMPLATE_BACKEND_PATH }}js/pages/features/charts/apexcharts.js"></script>

@foreach ($profil_responden as $get)

@php
$kategori_profil_responden = $ci->db->query("SELECT *, (SELECT COUNT(*) FROM responden_$table_identity JOIN
survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id_responden WHERE
kategori_profil_responden_$table_identity.id = responden_$table_identity.$get->nama_alias && is_submit = 1) AS
perolehan,
ROUND((((SELECT COUNT(*) FROM responden_$table_identity JOIN survey_$table_identity ON responden_$table_identity.id =
survey_$table_identity.id_responden WHERE kategori_profil_responden_$table_identity.id =
responden_$table_identity.$get->nama_alias && is_submit = 1) / (SELECT COUNT(*) FROM survey_$table_identity WHERE
is_submit = 1)) * 100), 2) AS persentase

FROM kategori_profil_responden_$table_identity")
@endphp

<?php
$jumlah = [];
$nama_kelompok = [];
foreach ($kategori_profil_responden->result() as $kpr) {
    if ($kpr->id_profil_responden == $get->id) {

        $jumlah[] = $kpr->perolehan;
        // $nama_kelompok[] = "'" . $kpr->nama_kategori_profil_responden . "'";
        $nama_kelompok[] = str_word_count($kpr->nama_kategori_profil_responden) > 3 ? "'" .
            substr($kpr->nama_kategori_profil_responden, 0, 7) . " [...]'" : "'" . $kpr->nama_kategori_profil_responden . "'";
    }
}
$total = implode(", ", $jumlah);
$kelompok = implode(", ", $nama_kelompok);
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

var chart = new ApexCharts(document.querySelector("#<?php echo $get->nama_alias ?>"), options);
chart.render();
</script>
@endforeach

@endsection