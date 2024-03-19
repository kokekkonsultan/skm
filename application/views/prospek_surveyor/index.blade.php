@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')
<link href="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
@endsection

@section('content')

<div class="container-fluid">
    <div class="card">
        <div class="card-header bg-secondary font-weight-bold">
            {{ $title }}
        </div>
        <div class="card-body">
            <div class="text-right mb-3">
                @php
                echo anchor(base_url().'prospek-surveyor/add', '<i class="fa fa-plus"></i> Tambah Prospek Survei', ['class' => 'btn
                btn-primary btn-sm font-weight-bold shadow-lg'])
                @endphp
            </div>
            <div class="table-responsive">
                <table id="table" class="table table-bordered table-hover" cellspacing="0" width="100%">
                    <thead class="bg-secondary">
                        <tr>
                            <th>No.</th>
                            <th>Nama Lengkap</th>
                            <th>Alamat</th>
                            <th>Telepon</th>
                            <th>Email</th>
                            <th>Keterangan</th>
                            <th></th>
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

@include("prospek_surveyor/form_bagikan_email")
{{-- @include("prospek_surveyor/form_bagikan_wa") --}}



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
                "url": "{{ base_url() }}prospek-surveyor/ajax-list",
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


    function delete_data(id) {
        if (confirm('Are you sure delete this data?')) {
            $.ajax({
                url: "{{ base_url() }}prospek-surveyor/delete/" + id,
                type: "POST",
                dataType: "JSON",
                success: function(data) {
                    if (data.status) {

                        table.ajax.reload();

                        Swal.fire(
                            'Informasi',
                            'Berhasil menghapus data',
                            'success'
                        );
                    } else {
                        Swal.fire(
                            'Informasi',
                            'Hak akses terbatasi. Bukan akun administrator.',
                            'warning'
                        );
                    }


                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error deleting data');
                }
            });

        }
    }
</script>

<script>
    $(document).ready(function(e) {
        <?php
        foreach ($prospek_surveyor as $ps_email) {
        ?>
            $('.form_email<?php echo $ps_email->id ?>').submit(function(e) {
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    dataType: 'json',
                    data: $(this).serialize(),
                    cache: false,
                    beforeSend: function() {
                        $('.tombolEmailTest').attr('disabled', 'disabled');
                        $('.tombolEmailTest').html('<i class="fa fa-spin fa-spinner"></i> Sedang diproses');


                        KTApp.block('#kt_blockui_content', {
                            overlayColor: '#000000',
                            state: 'primary',
                            message: 'Processing...'
                        });

                        setTimeout(function() {
                            KTApp.unblock('#kt_blockui_content');
                        }, 5000);
                    },
                    complete: function() {
                        $('.tombolEmailTest').removeAttr('disabled');
                        $('.tombolEmailTest').html('Kirim');

                        $('[name="email_akun"]').val('');
                        $('[name="isi_email"]').val('');
                    },
                    error: function(e) {
                        Swal.fire(
                            'Error !',
                            e,
                            'error'
                        )
                    },
                    success: function(data) {

                        if (data.validasi) {
                            $('.pesan').fadeIn();
                            $('.pesan').html(data.validasi);
                        }
                        if (data.sukses) {
                            toastr["success"]('Email berhasil dikirim');
                        }
                    }
                })
                return false;
            });
        <?php
        }
        ?>

    });
</script>
@endsection