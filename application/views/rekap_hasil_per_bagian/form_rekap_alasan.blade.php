@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')
<link href="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
@endsection

@section('content')

<div class="container-fluid">
    <div class="card card-custom card-sticky" data-aos="fade-down" data-aos-delay="300">
        <div class="card-body">

            <div class="card card-custom bgi-no-repeat gutter-b" style="height: 150px; background-color: #1c2840; background-position: calc(100% + 0.5rem) 100%; background-size: 100% auto; background-image: url(/assets/img/banner/rhone-2.svg)" data-aos="fade-down">
                <div class="card-body d-flex align-items-center">
                    <div>
                        <h3 class="text-white font-weight-bolder line-height-lg mb-5">
                            {{strtoupper($title)}}
                            <br>
                            {{-- strtoupper($users->first_name . ' ' . $users->last_name) --}}
                            {{ strtoupper($manage_survey->survey_name) }}
                        </h3>

                        @php
                        echo anchor(base_url(). $users->username . '/' . $ci->uri->segment(3) .
                        '/alasan/cetak',
                        '<i class="fa fa-file-pdf"></i></i> Export PDF', ['class' => 'btn
                        btn-secondary btn-sm font-weight-bold shadow', 'target' => '_blank'])
                        @endphp
                    </div>
                </div>
            </div>


            <div class="table-responsive">
                <table id="table" class="table table-bordered table-hover" cellspacing="0" width="100%">
                    <thead class="bg-secondary">
                        <tr>
                            <th>No.</th>
                            <th>Isi Pertanyaan</th>
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
<script src="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.js"></script>
<script>
    $(document).ready(function() {
        table = $('#table').DataTable({

            "processing": true,
            "serverSide": true,
            "order": [],
            "language": {
                "processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',
            },
            "lengthMenu": [
                [5, 10, 25, 50, 100],
                [5, 10, 25, 50, 100]
            ],
            "pageLength": 5,
            "ajax": {
                "url": "<?php echo base_url() . 'rekap-hasil-per-bagian/ajax-list-rekap-alasan/' . $ci->uri->segment(3) ?>",
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