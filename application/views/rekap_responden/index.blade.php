@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')
<link href="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.css" rel="stylesheet"type="text/css" />

<style type="text/css">
[pointer-events="bounding-box"] {
    display: none
}

.dataTables_length {
    display: none
}

.dataTables_filter {
    display: none
}
</style>

@endsection

@section('content')

<div class="container-fluid">
    @include("include_backend/partials_no_aside/_inc_menu_repository")

    <div class="row mt-5" data-aos="fade-down">
        <div class="col-md-3">
            @include('manage_survey/menu_data_survey')
        </div>
        <div class="col-md-9">


            <!-- <button onclick="get_canvas()">Convert</button> -->
            <!-- <div id="root"></div>
            <br> -->

            @php
            $table_identity = $profiles->table_identity;
            @endphp
            @foreach ($profil_responden as $key => $row)
            <div class="card card-custom card-sticky mb-5">
                <div class="card-body">
                    <!-- <h4><span class="badge badge-secondary"><?php echo $row->nama_profil_responden ?></span></h4> -->

                    <div class="d-flex justify-content-center" id="<?php echo $row->nama_alias ?>"></div>
                    <br>

                    <table class="table table-bordered table-striped example" style="width:100%">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Kelompok</th>
                                <th>Jumlah</th>
                                <th>Persentase</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $kategori_profil_responden = $ci->db->query("SELECT *, (SELECT COUNT(*) FROM
                            responden_$table_identity JOIN survey_$table_identity ON responden_$table_identity.id =
                            survey_$table_identity.id_responden WHERE kategori_profil_responden_$table_identity.id =
                            responden_$table_identity.$row->nama_alias && is_submit = 1) AS perolehan,
                            ROUND((((SELECT COUNT(*) FROM responden_$table_identity JOIN survey_$table_identity ON
                            responden_$table_identity.id = survey_$table_identity.id_responden WHERE
                            kategori_profil_responden_$table_identity.id = responden_$table_identity.$row->nama_alias &&
                            is_submit = 1) / (SELECT COUNT(*) FROM survey_$table_identity WHERE is_submit = 1)) * 100),
                            2)
                            AS persentase

                            FROM kategori_profil_responden_$table_identity")
                            @endphp

                            <?php
                            $no = 1;
                            $jumlah = [];
                            $nama_kelompok = [];
                            foreach ($kategori_profil_responden->result() as $value) {
                                if ($value->id_profil_responden == $row->id) { ?>

                            <tr>
                                <td><?php echo $no++ ?></td>
                                <td><?php echo $value->nama_kategori_profil_responden ?></td>
                                <td><?php echo $value->perolehan ?></th>
                                <td><?php echo $value->persentase ?> %</td>
                            </tr>

                            <?php }
                            } ?>
                        </tbody>
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
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/highcharts/7.1.1/highcharts.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/react/16.8.6/umd/react.production.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/react-dom/16.8.6/umd/react-dom.production.min.js'></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script>
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







@foreach ($profil_responden as $get)
@php
if($get->jumlah_pilihan >= 10){
$type_chart = 'bar3d';
} else {
$type_chart = 'pie3d';
}
@endphp

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

<script>
FusionCharts.ready(function() {
    var myChart = new FusionCharts({
        "type": "<?php echo $type_chart ?>",
        "renderAt": "<?php echo $get->nama_alias ?>",
        "width": "100%",
        "height": "350",
        "dataFormat": "json",
        dataSource: {
            "chart": {
                caption: "<?php echo $get->nama_profil_responden ?>",
                subcaption: "Chart Profil Responden Survei",
                "enableSmartLabels": "1",
                "startingAngle": "0",
                "showPercentValues": "1",
                "decimals": "2",
                "useDataPlotColorForLabels": "1",
                // "theme": "umber",
                // "bgColor": "#ffffff",

                theme: "fusion"
            },
            "data": [

                <?php foreach ($kategori_profil_responden->result() as $kpr) {
                        if ($kpr->id_profil_responden == $get->id) { ?> {
                    "label": <?php echo str_word_count($kpr->nama_kategori_profil_responden) > 3 ? '"' . substr($kpr->nama_kategori_profil_responden, 0, 7) . ' [...] (' . $kpr->perolehan . ')"' : '"' . $kpr->nama_kategori_profil_responden . ' (' . $kpr->perolehan . ')"'  ?>,
                    "value": "<?php echo $kpr->perolehan ?>"
                },
                <?php }
                    } ?>
            ]
        }

    });
    myChart.render();
});


// function get_canvas() {
//     html2canvas(document.getElementById("<?php echo $get->nama_alias ?>"), {
//         allowTaint: true,
//         useCORS: true
//     }).then(function(canvas) {
//         var anchorTag = document.createElement("a");
//         document.body.appendChild(anchorTag);

//         var dataURL = canvas.toDataURL();
//         $.ajax({
//             type: "POST",
//             url: "<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/rekap-responden/convert/' . $get->id ?>",
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
@endforeach


@endsection