@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')

@endsection

@section('content')

<div class="container-fluid">
    @include("include_backend/partials_no_aside/_inc_menu_repository")

    <div class="row mt-5">
        <div class="col-md-3">
            @include('manage_survey/menu_data_survey')
        </div>
        <div class="col-md-9">

            <div class="card card-custom bgi-no-repeat gutter-b"
                style="height: 150px; background-color: #1c2840; background-position: calc(100% + 0.5rem) 100%; background-size: 100% auto; background-image: url(/assets/img/banner/taieri.svg)"
                data-aos="fade-down">
                <div class="card-body d-flex align-items-center">
                    <div>
                        <h3 class="text-white font-weight-bolder line-height-lg mb-5">
                            {{strtoupper($title)}}
                        </h3>

                    </div>
                </div>
            </div>

            <div class="card mt-5">
				<div class="card-body">

					<div>
						<div id="chart-container">FusionCharts XT will load here!</div>
					</div>
					<table width="466" border="1" class="table table-bordered">
						<tr>
						  <td width="233"><strong>Nama Survei Yang Dilakukan</strong></td>
						  <td width="233">{{ $manage_survey->survey_name }} </td>
						</tr>
						<tr>
						  <td><strong>Responden</strong></td>
						  <td>{{ $jumlah_kuisioner }}</td>
						</tr>
                        @php
                        $new_cart = '';
                        $indeks = 0;
                        $i = 0;
                        $b = 0;
                        @endphp
                        @foreach($layanan->result() as $value)
                        @php
                        $nilai_tertimbang = 0;
                        $nilai_bobot_chart[] = '';
                        $i++;
                        $ci->db->select("nama_unsur_pelayanan, IF(id_parent = 0,unsur_pelayanan_$table_identity.id, unsur_pelayanan_$table_identity.id_parent) AS id_sub, (SUM(skor_jawaban)/COUNT(DISTINCT survey_$table_identity.id_responden)) AS rata_rata,  (COUNT(id_parent)/COUNT(DISTINCT survey_$table_identity.id_responden)) AS colspan, ((SUM(skor_jawaban)/COUNT(DISTINCT survey_$table_identity.id_responden))/(COUNT(id_parent)/COUNT(DISTINCT survey_$table_identity.id_responden))) AS nilai, (((SUM(skor_jawaban)/COUNT(DISTINCT survey_$table_identity.id_responden))/(COUNT(id_parent)/COUNT(DISTINCT survey_$table_identity.id_responden)))/(SELECT COUNT(id) FROM unsur_pelayanan_$table_identity WHERE id_parent = 0)) AS rata_rata_bobot");
                        $ci->db->from('jawaban_pertanyaan_unsur_' . $table_identity);
                        $ci->db->join("pertanyaan_unsur_pelayanan_$table_identity", "jawaban_pertanyaan_unsur_$table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id");
                        $ci->db->join("unsur_pelayanan_$table_identity", "pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan = unsur_pelayanan_$table_identity.id");
                        $ci->db->join("survey_$table_identity", "jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden");
                        $ci->db->join("responden_$table_identity", "responden_$table_identity.id = survey_$table_identity.id_responden");
                        $ci->db->where("survey_$table_identity.is_submit = 1");
                        $ci->db->where("jawaban_pertanyaan_unsur_$table_identity.skor_jawaban != '0.0'");
                        $ci->db->where("responden_$table_identity.id_layanan_survei = $value->id");
                        $ci->db->group_by('id_sub');
                        //$ci->db->limit(1);
                        $rata_rata_bobot_query = $ci->db->get();

                        foreach ($rata_rata_bobot_query->result() as $rata_rata_bobot) {
                            $nilai_tertimbang += $rata_rata_bobot->rata_rata_bobot;
                        }

                        if($i!=1){ $new_cart .= ','; }
                        $new_cart .= '{
                            "label": "'.$value->nama_layanan.'",
                            "value": "'.ROUND($nilai_tertimbang, 3).'"
                        }';

                        $indeks += $nilai_tertimbang;

                        if($nilai_tertimbang > 0){
                            $b = ($b+1);
                        }else{
                            $b = ($b+0);
                        }
                        @endphp
                        <tr>
						  <td><strong>{{ $value->nama_layanan }}</strong></td>
						  <td>{{ ROUND($nilai_tertimbang, 3) }}</td>
						</tr>
                        @endforeach
						
                        @php
                        $nilai_indeks = $indeks/$b;
                        @endphp
						<tr>
						  <td><strong>Indeks</strong></td>
						  <td>{{ ROUND($nilai_indeks, 3) }}</td>
						</tr>
					  </table>

					  <div>
						disclaimer : indeks pelayanan hanya sebagai gambaran, tidak dilakukan penelitian khusus untuk layanan tersebut
					  </div>
				</div>
			</div>


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


<style type="text/css">
[pointer-events="bounding-box"] {
    display: none
}
</style>
    

<script type="text/javascript">
    //STEP 2 - Chart Data
    /*const chartData = [{
        "label": "Indeks Layanan A",
        "value": "3.1"
    }, {
        "label": "Indeks Layanan B",
        "value": "3.2"
    }];*/
    const chartData = [{!! $new_cart !!}];
    
    //STEP 3 - Chart Configurations
    const chartConfig = {
    type: 'bar3d',//column3d
    renderAt: 'chart-container',
    width: '100%',
    height: '500',
    dataFormat: 'json',
    dataSource: {
        // Chart Configuration
        "chart": {
            "caption": "{{ $manage_survey->survey_name }}",
            "subCaption": "Grafik per layanan yang disurvei",
            "xAxisName": "Layanan yang disurvei",
            "yAxisName": "Nilai Indeks",
            "numberSuffix": "",
			"showValues": "1",
          	"rotateValues": "0",
			"valueFont": "Arial",
			"valueFontColor": "#5d62b5",
			"valueFontSize": "12",
			"valueFontBold": "1",
            "theme": "fusion"//gammel
            },
        // Chart Data
        "data": chartData
        }
    };
    FusionCharts.ready(function(){
    var fusioncharts = new FusionCharts(chartConfig);
    fusioncharts.render();
    });

</script>
@endsection
