@extends('include_template/template')

@php 
	$ci = get_instance();
@endphp

@section('style')

@endsection

@section('content')
<div class="container">
	<div class="card">
	    <div class="card-header fw-bold">
	        {{ $title }}
	    </div>
	    <div class="card-body">

	        <div class="text-right mb-3">
	            
	        </div>
	        <table id="table" class="table table-bordered table-hover" cellspacing="0" width="100%">
	            <thead class="bg-gray-300">
	                <tr>
	                    <th>No</th>
	                    <th>Organisasi</th>
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
@endsection

@section('javascript')
<script>
    var table;
    $(document).ready(function() {
        table = $("#table").DataTable({
            "processing"        : true,
            "serverSide"        : true,
            //"order"           : [],
            "iDisplayLength"    : 10,
            "ordering"          : true,
            "processing"        : true,
            "serverSide"        : true,
            "ajax": {
                "url"       : "{{ base_url() }}organisasi/ajax_list",
                "type"      : "POST",
                "dataType"  : "json",
                "dataSrc": function (jsonData) {              
                    return jsonData.data;
                },
                "data": function ( data ) {
                },

            },
            "columnDefs": [
                { 
                    "targets": [ 0 ],
                    "orderable": false,
                },
            ],

            responsive: {
                details: {
                    display: $.fn.dataTable.Responsive.display.modal({
                        header: function (row) {
                            var data = row.data();
                            return 'Details for ' + data[0] + ' ' + data[1];
                        }
                    }),
                    renderer: $.fn.dataTable.Responsive.renderer.tableAll({
                        tableClass: 'table'
                    })
                }
            }
            
        });

        $('#btn-reset-table-data-entry-data').click(function(){
            table_data_entry_data.ajax.reload();
        });
    });

    function reload_table_entry_data()
    {
        table_data_entry_data.ajax.reload(null,false);
    }

    function delete_data_entry_data(id)
    {
        if(confirm('Apakah anda akan menghapus data ?'))
        {
            $.ajax({
                url : "{{ base_url() }}KabupatenKotaController/delete/"+id,
                type: "POST",
                dataType: "JSON",
                success: function(data)
                {
                    reload_table_entry_data();
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert('Error deleting data');
                }
            });
        }
    }
</script>
@endsection