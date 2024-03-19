@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')
<link href="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
@endsection

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">

            @include('data_survey_klien/menu_data_survey_klien')
        </div>
        <div class="col-md-9">
            <div class="card" data-aos="fade-down" data-aos-delay="300">
                <div class="card-header font-weight-bold">
                    {{ $title }}
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="table" class="table table-bordered table-hover" cellspacing="0" width="100%" style="font-size: 12px;">
                            <thead class="bg-secondary">
                                <tr>
                                    <th width="5%">No.</th>
                                    <th></th>
                                    <th>Kode Surveyor</th>
                                    <th>Nama Surveyor</th>
                                    <th>Total Perolehan</th>
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

<!----------------- MODAL EMAIL ------------------------>
<?php
foreach ($surveyor->result() as $ps) {
?>
    <div class="example-modal">
        <div id="detail<?php echo $ps->id_user ?>" class="modal fade" role="dialog" style="display:none;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-secondary">
                        <h5 class="font-weight-bold">Detail Surveyor -- <b><?php echo $ps->kode_surveyor ?></b></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="" id="kt_blockui_content">
                            <table id="table" class="table table-hover" cellspacing="0" width="100%">
                                <tr>
                                    <th width="40%">Nama Lengkap</th>
                                    <td><?php echo $ps->first_name ?> <?php echo $ps->last_name ?></td>
                                </tr>
                                <tr>
                                    <th width="40%">Kode Surveyor</th>
                                    <td><?php echo $ps->kode_surveyor ?></td>
                                </tr>
                                <tr>
                                    <th width="40%">Username</th>
                                    <td class="text-primary"><?php echo $ps->username ?></td>
                                </tr>
                                <tr>
                                    <th width="40%">Nama Perusahaan</th>
                                    <td><?php echo $ps->company ?></td>
                                </tr>
                                <tr>
                                    <th width="40%">Email</th>
                                    <td><?php echo $ps->email ?></td>
                                </tr>
                                <tr>
                                    <th width="40%">Telephone</th>
                                    <td><?php echo $ps->phone ?></td>
                                </tr>
                                <tr>
                                    <th width="40%">Link Survei</th>
                                    <td>
                                        <div class='input-group'>
                                            <input class='form-control form-control-sm' id='kt_clipboard{{$ps->id_user}}' value="<?php echo base_url() . 'survei/' . $manage_survey->slug . '/' . $ps->uuid_surveyor ?>" readonly>
                                            <div class='input-group-append'>
                                                <a href='javascript:void(0)' class='btn btn-light-primary btn-sm' data-clipboard='true' data-clipboard-target='#kt_clipboard{{$ps->id_user}}'><i class='la la-copy'></i></a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
}
?>

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
            "ajax": {
                "url": "<?php echo base_url() . 'data-survey-klien/ajax-list-data-surveyor/' . $ci->uri->segment(3) ?>",
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
<script>
    "use strict";
    var KTClipboardDemo = function() {
        var demos = function() {
            new ClipboardJS('[data-clipboard=true]').on('success', function(e) {
                e.clearSelection();
                toastr["success"]('Link berhasil dicopy, Silahkan paste di browser anda sekarang.');
            });
        }

        return {
            init: function() {
                demos();
            }
        };
    }();

    jQuery(document).ready(function() {
        KTClipboardDemo.init();
    });
</script>
@endsection