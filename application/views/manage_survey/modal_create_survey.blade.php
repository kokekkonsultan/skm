@php 
	$ci = get_instance();
@endphp

@if ($client_packet->num_rows() > 0)
@php
	$last_packet = $client_packet->last_row();

	// cek jumlah terpakai
	$ci->db->select('manage_survey.id');
	$ci->db->from('manage_survey');
	$ci->db->join('berlangganan', 'berlangganan.id = manage_survey.id_berlangganan');
	$ci->db->where('berlangganan.uuid', $last_packet->uuid_berlangganan);
	$jumlah_kuesioner_dibuat = $ci->db->get()->num_rows();
@endphp
<p>
	Paket anda yang tersedia :
</p>


{{-- MODAL SEMENTARA --}}
<a class="" title="Create Survei Dengan Paket Ini" href="<?php echo base_url() . $ci->uri->segment(1) . '/manage-survey/create-survey/' . $last_packet->uuid_berlangganan ?>">
	<div class="card shadow" style="background-color: Bisque;">
		<div class="card-body">
			<div class="row">
				<div class="col-md-6">
					<h4><div class="font-weight-bold">
					{{ $last_packet->nama_paket }}
					</div></h4>
					<div class="text-dark">
						{!! $last_packet->deskripsi_paket !!}
					</div>
				</div>
				<div class="col-md-6 text-right">
					<div class="text-dark">
						{{ $jumlah_kuesioner_dibuat }} kuesioner dibuat dari {{ $last_packet->jumlah_kuesioner }} kuesioner kuota
					</div>
				</div>
			</div>
			
			
		</div>
	</div>
</a>



{{-- <a class="" href="javascript:void(0)" title="Create Survei Dengan Paket Ini" onclick="edit_data('{{ $last_packet->uuid_berlangganan }}')">
	<div class="card shadow" style="background-color: Bisque;">
		<div class="card-body">
			<div class="row">
				<div class="col-md-6">
					<h4><div class="font-weight-bold">
					{{ $last_packet->nama_paket }}
					</div></h4>
					<div class="text-dark">
						{!! $last_packet->deskripsi_paket !!}
					</div>
				</div>
				<div class="col-md-6 text-right">
					<div class="text-dark">
						{{ $jumlah_kuesioner_dibuat }} kuesioner dibuat dari {{ $last_packet->jumlah_kuesioner }} kuesioner kuota
					</div>
				</div>
			</div>
			
			
		</div>
	</div>
</a> --}}
@else
	<div class="text-center">
		Anda belum memiliki paket pembelian.
	</div>
@endif




<script>
function edit_data(id) {

    $.ajax({
        url: "{{ base_url() }}{{ $ci->uri->segment(1) }}/check-packet/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data) {

        	if(data.status == true)
        	{
        		// alert(data.id);
        		// alert("Sukses");
        		window.location.href = "{{ base_url() }}{{ $ci->uri->segment(1) }}/manage-survey/create-survey/" + data.uuid_berlangganan;
        	}

        	if(data.status == false)
        	{
        		// alert(data.id);
        		// alert("Survei anda sudah memenuhi kuota");
				Swal.fire({
					icon: 'warning',
					title: 'Informasi',
					text: 'Jumlah survei yang anda buat sudah memenuhi kuota paket yang anda beli!',
					allowOutsideClick: false,
					confirmButtonColor: '#DD6B55',
					confirmButtonText: 'Ya, Saya mengerti !',
				});
        	}

        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Error get data from ajax');
        }
    });
}
</script>