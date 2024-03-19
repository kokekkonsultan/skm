

<?php
$ci = get_instance();
?>

<?php $__env->startSection('style'); ?>
<link href="<?php echo e(TEMPLATE_BACKEND_PATH); ?>plugins/custom/datatables/datatables.bundle.css" rel="stylesheet"
    type="text/css" />
<!-- <style>
    .input[data-readonly] {
        pointer-events: none;
    }
</style> -->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="container-fluid">
    <?php echo $__env->make("include_backend/partials_no_aside/_inc_menu_repository", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="row mt-5">
        <div class="col-md-3">
            <?php echo $__env->make('manage_survey/menu_data_survey', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <div class="col-md-9">

            <div class="card card-custom bgi-no-repeat gutter-b"
                style="height: 150px; background-color: #1c2840; background-position: calc(100% + 0.5rem) 100%; background-size: 100% auto; background-image: url(/assets/img/banner/taieri.svg)"
                data-aos="fade-down">
                <div class="card-body d-flex align-items-center">
                    <div>
                        <h3 class="text-white font-weight-bolder line-height-lg mb-5">
                            <?php echo e(strtoupper($title)); ?>

                        </h3>

                        <button class="btn btn-secondary font-weight-bold btn-sm" onclick="reload_table()"><i
                                class="fas fa-sync"></i> Refresh</button>
                        &nbsp;
                        <button type="button" class="btn btn-light-info btn-sm font-weight-bold" data-toggle="modal"
                            data-target="#exampleModal"><i class="fa fa-filter"></i> Filter Data
                        </button>

                        &nbsp;
                        <div class="btn-group" role="group">
                            <button id="btnGroupDrop1" type="button" class="btn
                        btn-light-primary btn-sm font-weight-bold dropdown-toggle" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-print"></i> Export
                            </button>
                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                <!-- <a class="dropdown-item"
                                    href="<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/data-perolehan-survei/export-all-pdf' ?>"
                                    target="_blank">PDF</a> -->

                                <a class="dropdown-item" target="_blank"
                                    href="<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/data-perolehan-survei/export' ?>">Excel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="card card-custom card-sticky" data-aos="fade-down">


                <div class="card-body">
                    <!-- <?php echo $ci->session->set_flashdata('message_success') ?> -->

                    <form class="form_hapus"
                        action="<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/data-perolehan-survei/delete-by-checkbox' ?>"
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
                                        <th>Status</th>
                                        <th>Form</th>
                                        <th>Surveyor</th>
                                        <!-- <th>Surveyor Induk</th> -->

                                        <?php if($profiles->is_layanan_survei != 0): ?>
                                        <th>Jenis Layanan yang dipilih</th>
                                        <?php endif; ?>
                                        <!-- <th>Nama Responden</th> -->

                                        <?php foreach ($profil as $row) { ?>
                                        <th><?php echo $row->nama_profil_responden ?></th>
                                        <?php } ?>

                                        <?php if($ci->uri->segment(2) != 'bbpmp-balai-besar-penjaminan-mutu-pendidikan'): ?>
                                        <th>Waktu Isi</th>
                                        <?php endif; ?>
                                        <!-- <th></th> -->
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>


                        <div class="text-left mt-5">
                            <button type="submit"
                                onclick="return confirm('Apakah anda yakin ingin menghapus data survei ?')"
                                class="btn btn-danger btn-sm font-weight-bold tombolHapus"><i class="fa fa-trash"></i>
                                Hapus Data di Pilih</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>





<!-- MODAL -->
<div class="modal fade bd-example-modal-lg" id="exampleModal" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-secondary">
                <h5 class="modal-title" id="exampleModalLabel">Filter Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form id="form-filter" class="">

                    <div class="form-group row">
                        <div class="col-md-6 mb-5">
                            <label for="is_submit" class="form-label font-weight-bold text-primary">Mulai
                                Dari</label>
                            <input class="form-control" type="date" id="is_tanggal_start" value="">
                        </div>

                        <div class="col-md-6 mb-5">
                            <label for="is_surveyor" class="form-label font-weight-bold text-primary">Sampai
                                Dengan</label>
                            <input class="form-control" type="date" id="is_tanggal_end" value="">
                        </div>

                        <div class="col-md-6 mb-5">
                            <label for="is_submit" class="form-label font-weight-bold text-primary">Status
                                Lengkap / Tidak Lengkap</label>
                            <select id="is_submit" class="form-control">
                                <option value="">Please Select</option>
                                <option value="1">Lengkap</option>
                                <option value="2">Tidak Lengkap</option>
                            </select>

                        </div>

                        <div class="col-md-6 mb-5">
                            <label for="is_surveyor" class="form-label font-weight-bold text-primary">Dengan
                                Surveyor / Tanpa Surveyor</label>
                            <select id="is_surveyor" class="form-control">
                                <option value="">Please Select</option>
                                <option value="0">Tanpa Surveyor</option>
                                <option value="1">Dengan Surveyor</option>
                            </select>

                        </div>
                    </div>
                    <hr>
                    <br>

                    <div class="form-group row">

                        <?php $__currentLoopData = $profil_responden_filter->result(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-md-6 mb-5">
                            <label class="font-weight-bold text-primary"><?php echo e($row->nama_profil_responden); ?></label>

                            <select class="form-control" name="<?php echo $row->nama_alias ?>"
                                id="<?php echo $row->nama_alias ?>">
                                <option value="">Please Select</option>
                                <?php $__currentLoopData = $kategori_profil_responden->result(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($value->id_profil_responden == $row->id): ?>
                                <option value="<?php echo $value->id ?>">
                                    <?php echo $value->nama_kategori_profil_responden ?></option>
                                <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <div class="text-right">
                        <button type="button" id="btn-filter" class="btn btn-primary font-weight-bold">Filter
                            Data</button>
                        <button type="reset" id="btn-reset" class="btn btn-light-primary font-weight-bold">Reset
                            Filter</button>
                        <button type="button" class="btn btn-secondary font-weight-bold"
                            data-dismiss="modal">Close</button>
                    </div>
                </form>


            </div>
        </div>
    </div>
</div>


<?php $__env->stopSection(); ?>

<?php $__env->startSection('javascript'); ?>
<script src="<?php echo e(TEMPLATE_BACKEND_PATH); ?>plugins/custom/datatables/datatables.bundle.js"></script>


<script>
$(document).ready(function() {
    $("#checkAll").click(function() {
        $(".child").prop("checked", this.checked);
    });

    // $('.child').click(function() {
    //     if ($('.child:checked').length == $('.child').length) {
    //         $('.checkAll').prop('checked', false);
    //     } else {
    //         $('.checkAll').prop('checked', true);
    //     }
    // });

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
        "lengthMenu": [
            [5, 10, 25, 50, 100, -1],
            [5, 10, 25, 50, 100, "Semua data"]
        ],

        // initComplete: function() {
        //     $('.dataTables_filter input').unbind();
        //     $('.dataTables_filter input').bind('keyup', function(e) {
        //         var code = e.keyCode || e.which;
        //         if (code == 13) {
        //             table.search(this.value).draw();
        //         }
        //     });
        // },

        // paging: true,
        //     dom: 'Blfrtip',
        //     "buttons": [
        //         {
        //             extend: 'collection',
        //             text: 'Export',
        //             buttons: [
        //                 'excel'
        //             ]
        //         }
        //     ],

        "ajax": {
            "url": "<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/data-perolehan-survei/ajax-list' ?>",
            "type": "POST",
            "data": function(data) {
                data.is_submit = $('#is_submit').val();
                data.is_surveyor = $('#is_surveyor').val();
                data.is_tanggal_start = $('#is_tanggal_start').val();
                data.is_tanggal_end = $('#is_tanggal_end').val();

                <?php foreach ($profil_responden_filter->result() as $row) { ?>
                data.<?php echo $row->nama_alias ?> = $('#<?php echo $row->nama_alias ?>')
                    .val();
                <?php } ?>
            }
        },

        "columnDefs": [{
            "targets": [-1],
            "orderable": false,
        }, ],

    });
});

$('#btn-filter').click(function() {
    $('#checkAll').prop('checked', false);
    table.ajax.reload();
});
$('#btn-reset').click(function() {
    $('#form-filter')[0].reset();
    $('#checkAll').prop('checked', false);
    table.ajax.reload();
});


function delete_data(id) {
    if (confirm('Are you sure delete this data?')) {
        $.ajax({
            url: "<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/data-perolehan-survei/delete/' ?>" +
                id,
            type: "POST",
            dataType: "JSON",
            success: function(data) {
                if (data.status) {

                    $('#checkAll').prop('checked', false);
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

function reload_table() {
    $('#checkAll').prop('checked', false);
    $('#form-filter')[0].reset();
    table.ajax.reload(null, false);
}
</script>


<script>
$('.form_hapus').submit(function(e) {
    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        dataType: 'json',
        data: $(this).serialize(),
        cache: false,
        beforeSend: function() {
            $('.tombolHapus').attr('disabled', 'disabled');
            $('.tombolHapus').html(
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
            $('.tombolHapus').removeAttr('disabled');
            $('.tombolHapus').html('<i class="fa fa-trash"></i> Hapus Data di Pilih');
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
                    'Berhasil menghapus data',
                    'success'
                );
            }
        }
    });
    return false;
});
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('include_backend/template_backend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\IT\Documents\Htdocs MAMP\mk_skm\application\views/data_perolehan_survei/index.blade.php ENDPATH**/ ?>