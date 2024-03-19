@extends('include_backend/template_auth')

@php 
  $ci = get_instance();
@endphp

@section('style')

@endsection

@section('content')

<div class="container">

<h1><?php echo lang('reset_password_heading');?></h1>

<div id="infoMessage"><?php echo $message;?></div>

<?php echo form_open('auth/reset_password/' . $code);?>

	<p>
		<label for="new_password"><?php echo sprintf(lang('reset_password_new_password_label'), $min_password_length);?></label> <br />
		<?php echo form_input($new_password);?>
	</p>

	<p>
		<?php echo lang('reset_password_new_password_confirm_label', 'new_password_confirm');?> <br />
		<?php echo form_input($new_password_confirm);?>
	</p>

	<?php echo form_input($user_id);?>
	<?php echo form_hidden($csrf); ?>

	<p><?php echo form_submit('submit', lang('reset_password_submit_btn'), ['class' => 'btn btn-primary font-weight-bold shadow-lg']);?></p>

<?php echo form_close();?>

</div>

@endsection

@section('javascript')

@endsection