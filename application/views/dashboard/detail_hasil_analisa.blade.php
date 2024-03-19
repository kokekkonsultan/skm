@php
$ci = get_instance();
$ci->load->helper('form');
@endphp


<div class="table-responsive">
    <table id="table1" class="table table-bordered table-hover" cellspacing="0" width="100%" style="font-size: 12px;">
        <thead class="bg-secondary">
            <tr>
                <th width="5%">No.</th>
                <th>Unsur</th>
                {{--<th>Sarana & Masukkan</th>--}}
                <th>Faktor-faktor Yang Mempengaruhi</th>
                <th>Rencana Tindak Lanjut</th>
                <th>Waktu</th>
                {{--<th>Kegiatan</th>--}}
                <th>Penanggung Jawab</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<script>
$(document).ready(function() {
    table = $('#table1').DataTable({

        "processing": true,
        "serverSide": true,
        "order": [],
        "language": {
            "processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',
        },
        "ajax": {
            "url": "<?php echo base_url() . $ci->session->userdata('username') . '/' . $manage_survey->slug . '/analisa-survei/ajax-list' ?>",
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