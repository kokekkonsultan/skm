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

  <?= form_open($form_action);?>

      <p>
        <label>Nama Jenis Survey:</label>
        <input type="text" class="form-control" id="nama_jenis_survey" name="nama_jenis_survey" value="<?php echo $jenis_survey->nama_jenis_survey; ?>" >
      </p>

      <p>
        <input type="hidden" id="slug" name="slug" value="<?php echo $jenis_survey->slug; ?>" >
      </p>

      
<!-- @php
	  echo form_open($form_action);
  @endphp -->

      <!-- <p>
            <label>Nama Jenis Survey</label>
            @php
            	echo form_input($nama_jenis_survey);
            @endphp
      </p> -->

      <!-- <p>
            <label>Slug</label>
            @php
            	echo form_input($slug);
            @endphp
      </p> -->

      <!-- @php
	  echo form_close();
  @endphp -->

<a type="button" href="<?= base_url('jenissurvey') ?>" class="btn btn-secondary pull-left">Cancel</a>
<button tye="submit" class="btn btn-primary">Update Survey Type</button>

<?= form_close(); ?> 

</div>
</div>


</div>

@endsection

@section('javascript')

@endsection