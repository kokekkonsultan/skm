@php 
	$ci = get_instance();
@endphp


<div class="text-right mb-5">
    <button class="btn btn-secondary font-weight-bold" onclick="reload_table()">Refresh</button>
</div>
<div class="card">
	<div class="card-header font-weight-bold">
		List Link Artikel
	</div>
	<div class="card-body">
		<table id="table" class="display table" style="width:100%">
	        <thead>
	            <tr>
	                <th>No</th>
	                <th>Judul</th>
	                <th>Link</th>
	                <th></th>
	            </tr>
	        </thead>
	        <tbody>
	        </tbody>
	    </table>
	</div>
</div>
<div class="card mt-5">
	<div class="card-header font-weight-bold">
		List link statis
	</div>
	<div class="card-body">
		
		<table class="table">
			<thead>
				<tr>
					<th>No</th>
					<th>Judul</th>
					<th>Link</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
@php
$link_statis = [
	[
		'id' => 1,
		'link_name' => "Home",
		'link_value' => 'home'
	],
	[
		'id' => 2,
		'link_name' => "Contact",
		'link_value' => 'contact'
	],
	[
		'id' => 3,
		'link_name' => "Reseller Area",
		'link_value' => 'reseller-area'
	],
	[
		'id' => 4,
		'link_name' => "Login",
		'link_value' => 'login'
	],
];
@endphp
				@php
					$no = 1;
				@endphp

				@foreach ($link_statis as $key => $value)
				<tr>
					<td>{{ $no++ }}</td>
					<td>{{ $value['link_name'] }}</td>
					<td>
						<div class='input-group'>
                            <input type='text' class='form-control' id='kt_clipboard_x{{ $no }}' value='{{ base_url() }}{{ $value['link_value'] }}' placeholder='Type some value to copy' />
                            <div class='input-group-append'>
                                <a href='javascript:void(0)' class='btn btn-light-primary font-weight-bold shadow' data-clipboard='true' data-clipboard-target='#kt_clipboard_x{{ $no }}'><i class='la la-copy'></i> Copy Link</a>
                            </div>
                        </div>
					</td>
					<td><a href="{{ base_url() }}{{ $value['link_value'] }}" class="text-primary" target="_blank">Lihat Link</a></td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>

<script>
var table;
$(document).ready(function() {

    table = $('#table').DataTable({ 

        "processing": true, 
        "serverSide": true, 
        "order": [], 
         
        "ajax": {
            "url": "{{ base_url() }}list-link-article/ajax-list",
            "type": "POST",
        },

         
        "columnDefs": [
        { 
            "targets": [ 0 ], 
            "orderable": false, 
        },
        ],



    });

});

function reload_table() {
    table.ajax.reload(null, false);
}

</script>