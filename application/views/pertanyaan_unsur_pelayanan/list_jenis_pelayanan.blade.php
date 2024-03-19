@extends('include_backend/template_backend')

@php 
	$ci = get_instance();
@endphp

@section('style')
<link href="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<div class="container-fluid">
    <div class="card" data-aos="fade-down">
        <div class="card-header bg-secondary font-weight-bold">
            Pilih Jenis Pelayanan
        </div>
        <div class="card-body">

        	<div class="row">
                <div class="col-md-6 mb-5">
                    {!! anchor(base_url().'pertanyaan-unsur-pelayanan', '<i class="fas fa-arrow-left"></i> Kembali', ['class' => "btn btn-light-primary font-weight-bold shadow"]) !!}                    
                </div>
                <div class="col-md-6">
                    
                </div>
            </div>

            <br>
            <h4>Klasifikasi Survei: <span class="text-primary">{{ $nama_klasifikasi }}</span></h4>
            <br>

        	<input type="hidden" name="klasifikasi_survei" id="klasifikasi_survei" value="{{ $ci->uri->segment(3) }}">

            <div class="table-responsive">
                <table id="table" class="table" cellspacing="0" width="100%">
                    <thead class="bg-secondary">
                        <tr>
                            <th>Jenis Pelayanan</th>
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
<script src="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.js"></script>
<script>
    $(document).ready(function() {
        table = $('#table').DataTable({

            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "{{ base_url() }}pertanyaan-unsur-pelayanan/ajax-list-jenis-pelayanan",
                "type": "POST",
                "data": function(data) {
                    data.id_klasifikasi_survei = $('#klasifikasi_survei').val();
                }
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