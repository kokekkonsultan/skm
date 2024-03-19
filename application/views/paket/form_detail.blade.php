@php 
	$ci = get_instance();
@endphp

<form>
	<div class="form-group row">
    <label  class="col-2 col-form-label">Nama paket</label>
    <div class="col-10">
     {!! $post->nama_paket !!}
    </div>
   </div>

   <div class="form-group row">
    <label  class="col-2 col-form-label">Deskripsi paket</label>
    <div class="col-10">
     {!! $post->deskripsi_paket !!}
    </div>
   </div>

   <div class="form-group row">
    <label  class="col-2 col-form-label">Jumlah user</label>
    <div class="col-10">
     {!! $post->jumlah_user !!}
    </div>
   </div>

   <div class="form-group row">
    <label  class="col-2 col-form-label">Jumlah kuesioner</label>
    <div class="col-10">
     {!! $post->jumlah_kuesioner !!}
    </div>
   </div>

   <div class="form-group row">
    <label  class="col-2 col-form-label">Panjang hari</label>
    <div class="col-10">
     {!! $post->panjang_hari !!}
    </div>
   </div>

   <div class="form-group row">
    <label  class="col-2 col-form-label">Harga paket</label>
    <div class="col-10">
     {!! $post->harga_paket !!}
    </div>
   </div>


</form>


@php
$ci->db->select('users.first_name, users.last_name, users.company, berlangganan.tanggal_selesai');
$ci->db->from('paket');
$ci->db->join('berlangganan', 'berlangganan.id_paket = paket.id');
$ci->db->join('users', 'users.id = berlangganan.id_user');
$ci->db->where('paket.id', $id);
$berlangganan = $ci->db->get();

// print_r($berlangganan->result());
@endphp

@if ($berlangganan->num_rows() > 0)
<div class="text-center font-weight-bold mt-5 mb-5">
    Klien yang memakai paket ini antara lain:
</div>
<table class="table">
    <thead>
        <tr>
            <th>No.</th>
            <th>Nama</th>
            <th>Perusahaan</th>
            <th>Tanggal Akhir Belangganan</th>
        </tr>
    </thead>
    <tbody>
@php
$no = 1;
@endphp
@foreach ($berlangganan->result() as $value)
        <tr>
            <td>{{ $no++ }}</td>
            <td>{{ $value->first_name }} {{ $value->last_name }}</td>
            <td>{{ $value->company }}</td>
            <td>{{ $value->tanggal_selesai }}</td>
        </tr>
@endforeach
    </tbody>
</table>

@else
    <div class="text-center font-weight-bold">
        Paket ini belum ada klien yang memakai.
    </div>
@endif