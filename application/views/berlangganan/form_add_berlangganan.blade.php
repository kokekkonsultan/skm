@extends('include_backend/template_backend')

@php 
	$ci = get_instance();
@endphp

@section('style')

@endsection

@section('content')
<div class="container">

    <div class="row">
        <div class="col-md-8">

    <div class="card">
        <div class="card-header font-weight-bold">
            {{ $title }}
        </div>
        <div class="card-body">
            {!! form_open($form_action); !!}

            {!! validation_errors(); !!}

            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Paket Langganan <span class="text-danger">*</span></label>
                <div class="col-sm-9">
                    {!! form_dropdown($id_paket); !!}
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                <div class="col-sm-9">
                    {!! form_dropdown($id_metode_pembayaran); !!}
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Tanggal Mulai Berlangganan <span class="text-danger">*</span></label>
                <div class="col-sm-9">
                    {!! form_input($tanggal_mulai); !!}
                </div>
            </div>

            <div class="text-right mt-3 mb-3">
                {!! anchor($ci->session->userdata('urlback'), 'Batal', ['class'=>'btn btn-light-primary font-weight-bold shadow-lg']); !!}
                <button type="submit" class="btn btn-primary font-weight-bold shadow-lg">Simpan</button>
            </div>

            {!! form_close(); !!}

        </div>
    </div>

    <div class="card mt-5 mb-5">
        <div class="card-header font-weight-bold">
            Paket Sebelumnya
        </div>
        <div class="card-body">
            
            @php
            $ci->db->select('*');
            $ci->db->from('paket');
            $ci->db->join('berlangganan', 'berlangganan.id_paket = paket.id');
            $ci->db->join('users', 'users.id = berlangganan.id_user');
            $ci->db->where('users.id', $ci->uri->segment(3));
            $ci->db->order_by('berlangganan.id', 'desc');
            $ci->db->limit(1);
            $paket_terakhir = $ci->db->get()->row();
            @endphp

            <div class="row">
                <div class="col-md-6">
                    
                    <p>
                        <label>Nama Paket</label><br>
                        <span class="font-weight-bold">{{ $paket_terakhir->nama_paket }}</span>
                    </p>

                    <p>
                        <label>Deskripsi</label><br>
                        <span class="font-weight-bold">{!! $paket_terakhir->deskripsi_paket !!}</span>
                    </p>

                    <p>
                        <label>Lama Berlangganan</label><br>
                        <span class="font-weight-bold">{{ $paket_terakhir->panjang_hari }} Hari</span>
                    </p>

                    <p>
                        <label>Harga Paket</label><br>
                        <span class="font-weight-bold">{{ $paket_terakhir->harga_paket }}</span>
                    </p>

                    <p>
                        <label>Tanggal Pembelian</label><br>
                        <span class="font-weight-bold">{{ date('d-m-Y', strtotime($paket_terakhir->tanggal_mulai)) }}</span>
                    </p>

                </div>

                <div class="col-md-6">
                    
                    <p>
                        <label>Jumlah User</label><br>
                        <span class="font-weight-bold">{{ $paket_terakhir->jumlah_user }}</span>
                    </p>

                    <p>
                        <label>Jumlah Kuesioner</label><br>
                        <span class="font-weight-bold">{{ $paket_terakhir->jumlah_kuesioner }}</span>
                    </p>

                </div>
            </div>

            

        </div>

        <div class="card-footer text-center">
            Tanggal kedaluarsa paket : <span class="font-weight-bold">{{ date('d-m-Y', strtotime($paket_terakhir->tanggal_selesai)) }}</span>
        </div>
    </div>

        </div>
        <div class="col-md-4">
            <div id="informasi_paket">
                
            </div>
        </div>
    </div>

</div>
@endsection

@section('javascript')
<script>
$( function() {

    $.ajaxSetup({
        type: "POST",
        url: "{{ base_url() }}berlangganan/get-detail-ajax",
        cache: false,
    });

    $("#id_paket").change(function() {
        var value_res = $(this).val();
        if (value_res) {
            $.ajax({
                data: {
                    modul: 'get_paket',
                    id: value_res
                },
                success: function(respond) {
                    $("#informasi_paket").html(respond);
                    $("#informasi_paket").fadeIn("fast");
                }
            })
        }
    });

});
</script>
@endsection