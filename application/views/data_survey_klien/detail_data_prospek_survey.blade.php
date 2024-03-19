@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')
<link href="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.css" rel="stylesheet"
    type="text/css" />
@endsection

@section('content')

<div class="container-fluid">

    <div class="row mt-5">
        <div class="col-md-3">
            @include('data_survey_klien/menu_data_survey_klien')
        </div>
        <div class="col-md-9">

            <div class="card card-custom card-sticky" data-aos="fade-down">
                <div class="card-header font-weight-bold">
                    <div class="card-title">
                        Prospek Survei
                    </div>
                    <div class="card-toolbar">

                    </div>
                </div>
                <div class="card-body">

                    <p>
                        Anda bisa menambahkan kontak email dan telepon. Dengan menginputkan data tesebut, anda bisa
                        mengirimkan link survey melalui Email dan Whatsapp.
                        <br><br>
                    </p>

                    <div class="table-responsive">
                        <table id="table" class="table table-bordered table-hover" cellspacing="0" width="100%"
                            style="font-size: 12px;">
                            <thead class="bg-gray-300">
                                <tr>
                                    <th>No.</th>
                                    <th>Nama Lengkap</th>
                                    <th>Alamat</th>
                                    <th>Telepon</th>
                                    <th>Email</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>


                </div>
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
            "url": "<?php echo base_url() . 'data-survey-klien/ajax-list-data-prospek-survey/' . $ci->uri->segment(3) ?>",
            "type": "POST",
            "data": function(data) {}
        },

        "columnDefs": [{
            "targets": [-1],
            "orderable": false,
        }, ],

    });

});
</script>
@endsection