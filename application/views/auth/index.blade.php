@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')
<link href="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
@endsection

@section('content')

<div class="container mt-5">
    {{-- <div class="text-end">
	<?php echo anchor('auth/create_user', lang('index_create_user_link'), ['class' => 'btn btn-primary']) ?> 
	<?php echo anchor('auth/create_group', lang('index_create_group_link'), ['class' => 'btn btn-secondary']) ?></div>

<h1>{{ lang('index_heading') }}</h1>
    <p>{{ lang('index_subheading') }}</p>

    <div id="infoMessage"><?php echo $message; ?></div>

    <table class="table table-striped">
        <tr>
            <th><?php echo lang('index_fname_th'); ?></th>
            <th><?php echo lang('index_lname_th'); ?></th>
            <th><?php echo lang('index_email_th'); ?></th>
            <th><?php echo lang('index_groups_th'); ?></th>
            <th><?php echo lang('index_status_th'); ?></th>
            <th><?php echo lang('index_action_th'); ?></th>
            <th>Delete</th>
        </tr>
        <?php foreach ($users as $user) : ?>
        <tr>
            <td><?php echo htmlspecialchars($user->first_name, ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($user->last_name, ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($user->email, ENT_QUOTES, 'UTF-8'); ?></td>
            <td>
                <?php foreach ($user->groups as $group) : ?>
                <?php echo anchor("auth/edit_group/" . $group->id, htmlspecialchars($group->name, ENT_QUOTES, 'UTF-8')); ?><br />
                <?php endforeach ?>
            </td>
            <td><?php echo ($user->active) ? anchor("auth/deactivate/" . $user->id, lang('index_active_link')) : anchor("auth/activate/" . $user->id, lang('index_inactive_link')); ?>
            </td>
            <td><?php echo anchor("auth/edit_user/" . $user->id, 'Edit', ['class' => 'btn btn-secondary']); ?></td>
            <td>
                @php
                echo anchor("auth/delete_user/".$user->id, 'Delete', ['class' => 'btn btn-secondary', 'onclick' =>
                "return confirm('Anda yakin ingin menghapus pengguna ?')"])
                @endphp
            </td>
        </tr>
        <?php endforeach; ?>
    </table> --}}


    <div class="card">
        <div class="card-header bg-secondary font-weight-bold">
            Pengguna Administrator
        </div>
        <div class="card-body">

            <div class="text-right mb-3">
                @php
                echo anchor(base_url().'pengguna-administrator/create-administrator', 'Tambah Administrator', ['class'
                => 'btn btn-primary btn-sm font-weight-bold'])
                @endphp
            </div>
            <div class="table-responsive">
                <table id="table" class="table table-bordered" cellspacing="0" width="100%">
                    <thead class="bg-secondary">
                        <tr>
                            <th>No.</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Groups</th>
                            <th>Status</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>

        </div>
    </div>



</div>

@endsection

@section('javascript')

@if ($message)
<script type="text/Javascript">
    Swal.fire('@php
        echo $message @endphp ');
</script>
@endif

<script src="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.js"></script>
<script>
$(document).ready(function() {
    table = $('#table').DataTable({

        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
            "url": "{{ base_url() }}pengguna-administrator/ajax-list-administrator",
            "type": "POST",
            "data": function(data) {}
        },

        "columnDefs": [{
            "targets": [-1],
            "orderable": false,
        }, ],

    });
});

$('#btn-filter').click(function() {
    table.ajax.reload();
});
$('#btn-reset').click(function() {
    $('#form-filter')[0].reset();
    table.ajax.reload();
});
</script>
@endsection