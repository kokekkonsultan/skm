@extends('include_backend/template_backend')

@php 
  $ci = get_instance();
@endphp

@section('style')

@endsection

@section('content')

<div class="container">

  <div class="row justify-content-md-center">
  <div class="col col-lg-6 mt-5">
      
<h1><?= $title ?></h1>
<p><?= $subtitle ?></p>

<form method="post" action="<?= base_url('jenissurvey/insert') ?>">
      <p>
        <label>Nama Jenis Survey:</label>
        <input type="text" class="form-control" id="nama_jenis_survey" name="nama_jenis_survey"  >
      </p>

      <a type="button" href="<?= base_url('jenissurvey') ?>" class="btn btn-secondary pull-left">Cancel</a>
      <button type="submit" class="btn btn-primary">Create Survey Type</button>

</from>

      
    </div>
  </div>


</div>

@endsection

@section('javascript')

@endsection