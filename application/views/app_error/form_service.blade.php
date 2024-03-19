@extends('include_frontend/template')

@section('style')

@endsection

@section('content')

<div class="container position-relative">
    <div class="row align-items-center py-8">
        <div class="col-md-5 col-lg-6 order-md-1 text-center text-md-end"><img class="img-fluid"
                src="<?= base_url('assets/') ?>img/site/campaign/pengaturan.png" width="500" alt="" /></div>

        <div class="col-md-7 col-lg-6 text-center text-md-start">
            <h1 class="display-3 fw-bold lh-sm">Mohon Maaf, </h1>
            <h3 class="fw-bold lh-sm">Sistem Sedang Dalam Proses Pemeliharaan ...!</h3>
            <h6>Anda Dapat Mencobanya Lagi Dalam Beberapa Saat ...</h6>

        </div>
    </div>
</div>

@endsection

@section('javascript')

@endsection