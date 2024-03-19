@php
$ci = get_instance();
@endphp

<br>
<br>


<table class="table table-hover example">
	<thead style="display: none;">
		<tr>
			<td></td>
		</tr>
	</thead>
	<tbody>
	@foreach ($manage_survey->result() as $key => $value)

	@php
	$lengkap[$key] = 0;
	$tidaklengkap[$key] = 0;
	foreach($ci->db->query("SELECT * FROM survey_$value->table_identity WHERE id_surveyor_induk != 0")->result() as $row){
		if ($row->is_submit == 1) {
			$lengkap[$key] = $lengkap[$key] + 1;
		} else {
			$tidaklengkap[$key] = $tidaklengkap[$key] + 1;
		}
	}
	@endphp
		<tr>
			<td>
				<a data-toggle="modal" onclick="showdetail({{$value->id_manage_survey}})" href="#modal_valid" title="">
					<div class="card mb-5 shadow" style="background-color: SeaShell;">
						<div class="card-body">
							<div class="row">
								<div class="col sm-10">
									<strong style="font-size: 15px;">{{ $value->survey_name }}</strong>
									<br>
									<span class="text-dark">Lengkap : <b></b>{{$lengkap[$key]}} Responden</b></span><br>
									<span class="text-dark">Tidak Lengkap : <b>{{$tidaklengkap[$key]}} Responden</b></span><br>
								</div>
								<div class="col sm-2 text-right">
									<!-- <div class="mt-3 text-dark font-weight-bold" style="font-size: 11px;">
										<a class="btn btn-info btn-sm"><i class="fa fa-circle-info"></i> Detail</a>
									</div> -->
								</div>
							</div>
						</div>
					</div>
				</a>
			</td>
		</tr>
		@endforeach
	</tbody>
</table>


<!-- ======================================= MODAL VALID ========================================== -->
<div class="modal fade" id="modal_valid" data-backdrop="static" aria-labelledby="staticBackdrop" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content" id="bodyDetail">
            <div align="center" id="loading_registration">
                <img src="{{ base_url() }}assets/site/img/ajax-loader.gif" alt="">
            </div>
        </div>
    </div>
</div>


<script>
$(document).ready(function() {
    $('.example').DataTable();
});
</script>

<script>
    function showdetail(id) {
        $('#bodyDetail').html(
            "<div class='text-center'><img src='{{ base_url() }}assets/img/ajax/ajax-loader-big.gif'><br><i>Proses ini membutuhkan waktu beberapa menit..</i></div>"
        );

        $.ajax({
            type: "post",
            url: "{{base_url() . 'rekap-surveyor-induk/modal-detail/'}}" + id,
            // data: "id=" + id,
            dataType: "text",
            success: function(response) {
                // $('.modal-title').text('Edit Pertanyaan Unsur');
                $('#bodyDetail').empty();
                $('#bodyDetail').append(response);
            }
        });
    }
</script>