@php 
	$ci = get_instance();
@endphp


<table class="table table-bordered">
	<tr>
		<th>Nama</th>
		<td>{{ $data_inbox->conNama }}</td>
	</tr>
	<tr>
		<th>Organisasi</th>
		<td>{{ $data_inbox->conOrganisasi }}</td>
	</tr>
	<tr>
		<th>Email</th>
		<td>{{ $data_inbox->conEmail }}</td>
	</tr>
	<tr>
		<th>Telepon</th>
		<td>{{ $data_inbox->conTelp }}</td>
	</tr>
	<tr>
		<th>Keperluan</th>
		<td>{{ $data_inbox->conSubject }}</td>
	</tr>
	<tr>
		<th>Isi Pesan</th>
		<td>{!! $data_inbox->conMessage !!}</td>
	</tr>
</table>

<hr>
<div class="row">
	<div class="col-md-6">
		
		<span class="btn btn-danger font-weight-bold" style="cursor: pointer;" onclick="delete_inbox({{ $data_inbox->conId }})">Hapus pesan ini</span>
	</div>
	<div class="col-md-6 text-right">
		
		{!! anchor('inbox/reply/'.$data_inbox->conId, 'Balas pesan', ['class' => 'btn btn-primary font-weight-bold']); !!}
	</div>
</div>

@if ($data_pesan_balasan->num_rows() > 0)
<br><br><br>

<div class="card">
	<div class="card-header">
		Balasan terhadap pesan ini
	</div>
	<div class="card-body">
<table class="table table-bordered">
			<thead>
				<tr>
					<th>No</th>
					<th>Subject</th>
					<th>Isi Pesan</th>
					<th>Waktu Kirim</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@php
					$no = 1;
				@endphp
				@foreach ($data_pesan_balasan->result() as $value)
				<tr>
					<td>{{ $no++ }}</td>
					<td>{{ $value->subjek }}</td>
					<td>{!! $value->isi_pesan !!}</td>
					<td>{{ date('d-m-Y h:i:s A', strtotime($value->waktu_kirim)) }}</td>
					<td>
						<a class="text-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data('{{ $value->id }}')">Delete</a>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>		
	</div>
</div>
@endif


<script>
	function delete_data(id)
	{
		if (confirm('Are you sure delete this data?')) {
	        $.ajax({
	            url: "{{ base_url() }}inbox/delete-reply/" + id,
	            type: "POST",
	            dataType: "JSON",
	            success: function(data) {
	                
	                if (data.status) {

	                    Swal.fire(
	                        'Informasi',
	                        'Berhasil menghapus data',
	                        'success'
	                    );

	                    $('#modal_userDetail').modal('hide');

	                }


	            },
	            error: function(jqXHR, textStatus, errorThrown) {
	                alert('Error deleting data');
	            }
	        });

	    }
	}
	
</script>