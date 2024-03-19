@extends('include_frontend/template')

@section('style')

@endsection

@section('content')


<div class="container position-relative">
    <div class="row align-items-center py-8">
        <div class="col-md-5 col-lg-6 order-md-1 text-center text-md-end"><img class="img-fluid"
                src="<?= base_url('assets/') ?>themes/img/not-found.png" width="350" alt="" /></div>
        <div class="col-md-7 col-lg-6 text-center text-md-start">
            <h1 class="mb-4 display-3 fw-bold lh-sm">404 NOT FOUND</h1>

        </div>
    </div>
</div>

@endsection

@section('javascript')

@endsection