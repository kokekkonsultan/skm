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

                    <form class="form_restore"
                        action="<?php echo base_url() . 'data-survey-klien/restore-data-sampah/' . $ci->uri->segment(3) ?>"
                        method="POST">

                        <div class="checkbox-inline">
                            <label class="checkbox checkbox-lg checkbox-primary">
                                <input type="checkbox" class="checkAll font-weight-bold" name="checkAll"
                                    id="checkAll" />
                                <span></span><b class="text-primary">Pilih Semua</b>
                            </label>
                        </div>
                        <div class="table-responsive">
                            <table id="table" class="table table-bordered table-hover" cellspacing="0" width="100%"
                                style="font-size: 12px;">
                                <thead class="bg-secondary">
                                    <tr>
                                        <th width="5%">No.</th>
                                        <th>Deleted Time</th>
                                        <th>Status</th>
                                        <!-- <th>Nama Responden</th> -->

                                        @foreach ($profil as $row)
                                        <th>{{$row->nama_profil_responden}}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div class="text-right mt-5">
                            <button type="submit"
                                onclick="return confirm('Apakah anda yakin ingin mengembalikan data survei ?')"
                                class="btn btn-danger btn-sm font-weight-bold tombolRestore"><i
                                    class="fa fa-retweet"></i>
                                Restore Data di Pilih
                            </button>
                        </div>
                    </form>

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
    $("#checkAll").click(function() {
        $(".child").prop("checked", this.checked);
    });
});
</script>


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
            "url": "<?php echo base_url() . 'data-survey-klien/ajax-list-data-sampah/' . $ci->uri->segment(3) ?>",
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

function updateUnit() {
    $('#checkAll').prop('checked', false);
    $('#form-filter')[0].reset();
    table.ajax.reload(null, false);
}
</script>


<script>
$('.form_restore').submit(function(e) {
    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        dataType: 'json',
        data: $(this).serialize(),
        cache: false,
        beforeSend: function() {
            $('.tombolRestore').attr('disabled', 'disabled');
            $('.tombolRestore').html(
                '<i class="fa fa-spin fa-spinner"></i> Sedang diproses');

            Swal.fire({
                title: 'Memproses data',
                html: 'Mohon tunggu sebentar. Sistem sedang melakukan request anda.',
                allowOutsideClick: false,
                onOpen: () => {
                    swal.showLoading()
                }
            });

        },
        complete: function() {
            $('.tombolRestore').removeAttr('disabled');
            $('.tombolRestore').html('<i class="fa fa-retweet"></i> Restore Data di Pilih');
        },
        error: function(e) {
            alert('Error deleting data');
        },

        success: function(data) {
            if (data.validasi) {
                $('.pesan').fadeIn();
                $('.pesan').html(data.validasi);
            }
            if (data.sukses) {
                $('#checkAll').prop('checked', false);
                table.ajax.reload();

                Swal.fire(
                    'Informasi',
                    'Data berhasil dipilihkan',
                    'success'
                );
            }
        }
    });
    return false;
});
</script>

@endsection