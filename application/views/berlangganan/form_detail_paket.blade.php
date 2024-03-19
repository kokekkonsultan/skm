@php 
	$ci = get_instance();
@endphp

<div class="card">
	<div class="card-header">
		Informasi Paket
	</div>
	<div class="card-body">
		<p>
            <label>Nama Paket</label><br>
            <span class="font-weight-bold">{{ $paket->nama_paket }}</span>
        </p>

        <p>
            <label>Deskripsi</label><br>
            <span class="font-weight-bold">{!! $paket->deskripsi_paket !!}</span>
        </p>

        <p>
            <label>Lama Berlangganan</label><br>
            <span class="font-weight-bold">{{ $paket->panjang_hari }} Hari</span>
        </p>

        <p>
            <label>Harga Paket</label><br>
            <span class="font-weight-bold">{{ $paket->harga_paket }}</span>
        </p>

         <p>
            <label>Jumlah User</label><br>
            <span class="font-weight-bold">{{ $paket->jumlah_user }}</span>
        </p>

        <p>
            <label>Jumlah Kuesioner</label><br>
            <span class="font-weight-bold">{{ $paket->jumlah_kuesioner }}</span>
        </p>
	</div>
</div>