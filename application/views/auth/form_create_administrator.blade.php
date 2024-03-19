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
      
      <div class="card">
        <div class="card-body">

<h1>{{ $title }}</h1>
<p><?php echo lang('create_user_subheading');?></p>

<div id="infoMessage"><?php echo $message;?></div>

<?php echo form_open("pengguna-administrator/create-administrator");?>

      <p>
            <?php echo lang('create_user_fname_label', 'first_name');?> <br />
            <?php echo form_input($first_name);?>
      </p>

      <p>
            <?php echo lang('create_user_lname_label', 'last_name');?> <br />
            <?php echo form_input($last_name);?>
      </p>
      
      <?php
      if($identity_column!=='email') {
          echo '<p>';
          echo lang('create_user_identity_label', 'identity');
          echo '<br />';
          echo form_error('identity');
          echo form_input($identity);
          echo '</p>';
      }
      ?>

      <p>
            <?php echo lang('create_user_company_label', 'company');?> <br />
            <?php echo form_input($company);?>
      </p>

      <p>
            <?php echo lang('create_user_email_label', 'email');?> <br />
            <?php echo form_input($email);?>
      </p>

      <p>
            <?php echo lang('create_user_phone_label', 'phone');?> <br />
            <?php echo form_input($phone);?>
      </p>

      <p>
            <?php echo lang('create_user_password_label', 'password');?> <br />
            <?php echo form_input($password);?>
      </p>

      <p>
            <?php echo lang('create_user_password_confirm_label', 'password_confirm');?> <br />
            <?php echo form_input($password_confirm);?>
      </p>


      <p>
        @php
          echo anchor(base_url().'auth', 'Cancel', ['class' => 'btn btn-light-primary font-weight-bold'])
        @endphp
        <?php echo form_submit('submit', lang('create_user_submit_btn'), ['class' => 'btn btn-primary font-weight-bold']);?>
      </p>

<?php echo form_close();?>

          
        </div>
      </div>


      
    </div>
  </div>


</div>

@endsection

@section('javascript')

@endsection