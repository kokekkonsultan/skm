@extends('include_backend/template_backend')

@php 
	$ci = get_instance();
@endphp

@section('style')

@endsection

@section('content')

<div class="container">
  
  {!! anchor(base_url().'berlangganan', 'Kembali', ['class' => 'btn btn-light-primary font-weight-bold shadow-lg']); !!}

  <div class="alert alert-primary mt-5" role="alert">
  Di sini Anda bisa mendapatkan beberapa info tentang langganan dan pembayaran Klien Anda
  </div>

  <div class="card mt-5">
    <div class="card-header font-weight-bold">
      Informasi Klien
    </div>
    <div class="card-body">
      
      <div class="row">
        <div class="col-md-6">
        @php
        $user = $ci->ion_auth->user($ci->uri->segment(3))->row();
        @endphp
            <p>
                        <label>Nama Lengkap</label><br>
                        <span class="font-weight-bold">{{ $user->first_name }} {{ $user->last_name }}</span>
                    </p>

                    <p>
                        <label>Username</label><br>
                        <span class="font-weight-bold">{{ $user->username }}</span>
                    </p>

                    <p>
                        <label>Email</label><br>
                        <span class="font-weight-bold">{{ $user->email }}</span>
                    </p>

                    <p>
                        <label>Phone</label><br>
                        <span class="font-weight-bold">{{ $user->phone }}</span>
                    </p>
          </div>
          <div class="col-md-6">
            <p>
                        <label>Organisasi</label><br>
                        <span class="font-weight-bold">{{ $user->company }}</span>
                    </p>
          </div>
      </div>

    </div>
  </div>

  <div class="card mt-5">
    <div class="card-header font-weight-bold">
      Current Subscription
    </div>
    <div class="card-body">
      
      <div class="row">
            <div class="col-md-6">
                
                <p>
                    <label>Nama Paket</label><br>
                    <span class="font-weight-bold">{{ $last_payment->nama_paket }}</span>
                </p>

                <p>
                    <label>Deskripsi</label><br>
                    <span class="font-weight-bold">{!! $last_payment->deskripsi_paket !!}</span>
                </p>

                <p>
                    <label>Lama Berlangganan</label><br>
                    <span class="font-weight-bold">{{ $last_payment->panjang_hari }} Hari</span>
                </p>

                <p>
                    <label>Harga Paket</label><br>
                    <span class="font-weight-bold">{{ $last_payment->harga_paket }}</span>
                </p>

                <p>
                    <label>Tanggal Pembelian</label><br>
                    <span class="font-weight-bold">{{ date('d-m-Y', strtotime($last_payment->tanggal_mulai)) }}</span>
                </p>

            </div>

            <div class="col-md-6">
                
                <p>
                    <label>Jumlah User</label><br>
                    <span class="font-weight-bold">{{ $last_payment->jumlah_user }}</span>
                </p>

                <p>
                    <label>Jumlah Kuesioner</label><br>
                    <span class="font-weight-bold">{{ $last_payment->jumlah_kuesioner }}</span>
                </p>

                <p>
                    <label>Status Paket</label><br>
                    <span>{!! $status_paket !!}</span>
                </p>

                <p>
                    <label>Tanggal Jatuh Tempo</label><br>
                    <span class="font-weight-bold">{!! $status_jatuh_tempo !!}</span>

                </p>

            </div>
        </div>

    </div>
    <div class="card-footer text-center">
      {!! anchor('BerlanggananController/perpanjangan/'.$last_payment->id_user, 'Perpanjang Paket', ['class' => 'btn btn-primary font-weight-bold']); !!} 
      {!! anchor('BerlanggananController/edit_perpanjangan/'.$last_payment->id_berlangganan, 'Ubah Paket', ['class' => 'btn btn-light-primary font-weight-bold']); !!}
    </div>
  </div>

	<div class="card mt-5 mb-5">
		<div class="card-header font-weight-bold">
			Payment History
		</div>
		<div class="card-body">
			<div class="mb-5">
				
			</div>
			@php
				echo $table
			@endphp
		</div>
	</div>
</div>

<div class="modal fade" id="modal_userDetail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-secondary text-white">
        <h5 class="modal-title" id="exampleModalLabel">Caption</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <i aria-hidden="true" class="ki ki-close"></i>
        </button>
      </div>
      <div class="modal-body" id="bodyModalDetail">
        <div align="center" id="loading_registration">
          <img src="{{ base_url() }}assets/site/img/ajax-loader.gif" alt="">
        </div>
        
      </div>
    </div>
  </div>
</div>

@endsection

@section('javascript')
<script>
function showuserdetail(id)
    {
        $('#bodyModalDetail').html("<div class='text-center'><img src='{{ base_url() }}assets/img/ajax/ajax-loader-big.gif'></div>");

        $.ajax({
            type: "post",
            url: "{{ base_url() }}berlangganan/detail",
            data: "id="+id,
            dataType: "text",
            success: function (response) {

                $('.modal-title').text('Detail Klien Berlangganan');
                $('#bodyModalDetail').empty();
                $('#bodyModalDetail').append(response);
            }
        });
    }
</script>
@endsection