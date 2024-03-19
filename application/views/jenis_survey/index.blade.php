@extends('include_backend/template_backend')

@php 
  $ci = get_instance();
@endphp

@section('style')

@endsection

@section('content')

<div class="container mt-5">
<div class="text-end">
    <a href="<?= base_url('jenissurvey/create'); ?>" class="btn btn-primary"></i> Create Jenis Survey</a>
</div>
<h1>Jenis Survey</h1>
<p>Below is a list of survey types.</p>

<table class="table table-striped">
<tr>
		<th>Jenis Survey</th>
		<th>Action</th>
		<th>Delete</th>
	</tr>
	<?php foreach ($jenis_survey as $row):?>
		<tr>
            <td><?php echo $row->nama_jenis_survey;?></td>
			
            <td>
				<a href="<?php echo base_url(); ?>jenissurvey/edit/<?php echo $row->id; ?>"
                                    class="btn btn-secondary">Edit</a>
            </td>

			<td>
				<?= anchor("jenissurvey/delete/".$row->id, 'Delete', ['class' => 'btn btn-secondary', 'onclick'=> "return confirm('Anda yakin ingin menghapus jenis survey ?')"]) ?>
            </td>
			
		</tr>
	<?php endforeach;?>
</table>


</div>
@endsection