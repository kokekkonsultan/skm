<?php
$ci = get_instance();
?>

<?php $__env->startSection('style'); ?>
<link href="<?php echo e(TEMPLATE_BACKEND_PATH); ?>plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />

<style type="text/css">
    /* .dataTables_length {
        display: none
    } */
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="container-fluid">
    <?php echo $__env->make("include_backend/partials_no_aside/_inc_menu_repository", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="row mt-5">
        <div class="col-md-3">
            <?php echo $__env->make('manage_survey/menu_data_survey', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <div class="col-md-9">

            <div class="card card-custom bgi-no-repeat gutter-b" style="height: 150px; background-color: #1c2840; background-position: calc(100% + 0.5rem) 100%; background-size: 100% auto; background-image: url(/assets/img/banner/taieri.svg)" data-aos="fade-down">
                <div class="card-body d-flex align-items-center">
                    <div>
                        <h3 class="text-white font-weight-bolder line-height-lg mb-5">
                            <?php echo e(strtoupper($title)); ?>

                        </h3>

                        <?php if($is_question == 1): ?>
                        <button type="button" class="btn btn-primary font-weight-bold shadow-lg btn-sm mr-2" data-toggle="modal" data-target="#exampleModal">
                            <i class="fas fa-plus"></i> Tambah Pertanyaan
                        </button>

                        <a class="btn btn-secondary font-weight-bold btn-sm" target="_blank" href="<?php echo e(base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/alur-pertanyaan-lompat'); ?>"><i class="fa fa-random"></i> Alur Pertanyaan Lompat</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>






            <div class="card card-custom card-sticky" data-aos="fade-down">

                <div class="card-body">

                    <div class="table-responsive">
                        <table id="table" class="table table-bordered table-hover" cellspacing="0" width="100%" style="font-size: 12px;">
                            <thead class="bg-secondary">
                                <tr>
                                    <th width="5%">No.</th>
                                    <th>Unsur Pelayanan</th>
                                    <th>Pilihan Jawaban</th>
                                    <th></th>
                                    <?php if ($is_question == 1) {
                                        echo '<th></th>';
                                    } ?>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-5">
                        <hr>
                        <b>Keterangan :</b><br>
                        <span><b class="text-danger">*</b> = Pertanyaan wajib di Isi.</span><br>
                        <span><b class="text-info">*</b> = Pertanyaan menggunakan alasan pada pilihan jawaban 1 dan 2.</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="card-deck">
                    <a href="<?php echo e(base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) .
                '/pertanyaan-unsur/add'); ?>" class="card card-body btn btn-outline-primary shadow">
                        <div class="text-center font-weight-bold">
                            <i class="fas fa-plus"></i><br>Tambah Pertanyaan Unsur
                        </div>
                    </a>

                    <a href="<?php echo e(base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) .
                '/pertanyaan-unsur/add-sub'); ?>" class="card card-body btn btn-outline-primary shadow">
                        <div class="text-center font-weight-bold">
                            <i class="fas fa-plus"></i><br>Tambah Pertanyaan Sub Unsur
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>


<?php $__currentLoopData = $ci->db->get("unsur_pelayanan_$profiles->table_identity")->result(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="modal fade" id="edit<?php echo e($value->id); ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit nomor unsur dan nama unsur</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>

            <form action="<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/pertanyaan-unsur/edit-unsur' ?>" method="POST" class="form_default">
                <div class="modal-body">

                    <input name="id" value="<?php echo e($value->id); ?>" hidden>
                    <div class="form-group">
                        <label>Nomor unsur dan nama unsur saat ini</label>
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text"><?php echo e($value->nomor_unsur); ?></span></div>
                            <input type="text" class="form-control" placeholder="" value="<?php echo e($value->nama_unsur_pelayanan); ?>" disabled />
                        </div>
                    </div>

                    <div class="">
                        <label>Inputkan nomor unsur dan nama unsur yang akan anda ubah pada bidang dibawah ini</label>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <input type="text" name="nomor_unsur" class="form-control" value="<?php echo e($value->nomor_unsur); ?>" required />
                        </div>
                        <div class="col-md-10">
                            <input type="text" name="nama_unsur_pelayanan" class="form-control" value="<?php echo e($value->nama_unsur_pelayanan); ?>" required />
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary tombolSimpanDefault">Simpan</button>
                </div>
            </form>

        </div>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('javascript'); ?>
<script src="<?php echo e(TEMPLATE_BACKEND_PATH); ?>plugins/custom/datatables/datatables.bundle.js"></script>
<script src="<?php echo e(base_url()); ?>assets/themes/metronic/assets/plugins/custom/datatables/datatables.bundle.js"></script>
<script>
    $(document).ready(function() {
        $('.example').DataTable({
            "lengthMenu": [
                [-1],
                ["All"]
            ],
            "pageLength": -1,
            "destroy": true
        });
    });
</script>


<script>
    $('.form_default').submit(function(e) {
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            dataType: 'json',
            data: $(this).serialize(),
            cache: false,
            beforeSend: function() {
                $('.tombolSimpanDefault').attr('disabled', 'disabled');
                $('.tombolSimpanDefault').html('<i class="fa fa-spin fa-spinner"></i> Sedang diproses');
                KTApp.block('#content_1', {
                    overlayColor: '#000000',
                    state: 'primary',
                    message: 'Processing...'
                });
                setTimeout(function() {
                    KTApp.unblock('#content_1');
                }, 1000);
            },
            complete: function() {
                $('.tombolSimpanDefault').removeAttr('disabled');
                $('.tombolSimpanDefault').html('Simpan');
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
                    toastr["success"]('Data berhasil disimpan');
                    table.ajax.reload();
                }
            }
        })
        return false;
    });

    $(document).ready(function() {
        table = $('#table').DataTable({

            "processing": true,
            "serverSide": true,
            "lengthMenu": [
                [5, 10, 25, 50, 100, -1],
                [5, 10, 25, 50, 100, "Semua data"]
            ],
            "pageLength": 5,
            "order": [],
            "language": {
                "processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',
            },
            "ajax": {
                "url": "<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/pertanyaan-unsur/ajax-list' ?>",
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


    function delete_data(id_pertanyaan_unsur) {
        if (confirm('Are you sure delete this data?')) {
            $.ajax({
                url: "<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/pertanyaan-unsur/delete/' ?>" +
                    id_pertanyaan_unsur,
                type: "POST",
                dataType: "JSON",
                success: function(data) {
                    if (data.status) {

                        $('#table').DataTable().ajax.reload()

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
    function cek() {
        Swal.fire({
            icon: 'warning',
            title: 'Informasi',
            text: 'Unsur tidak dapat dihapus karna masih terdapat sub unsur turunan di bawahnya. Silahkan hapus sub unsur turunan terlebih dahulu!',
            allowOutsideClick: false,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Ya, Saya mengerti !',
        });
    }
</script>


<script src="http://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $('table tbody').sortable({
            update: function(event, ui) {
                $(this).children().each(function(index) {
                    if ($(this).attr('data-position') != (index + 1)) {
                        $(this).attr('data-position', (index + 1)).addClass('updated');
                    }
                });

                saveNewPositions();
            }
        });
    });

    function saveNewPositions() {
        var positions = [];
        $('.updated').each(function() {
            positions.push([$(this).attr('data-index'), $(this).attr('data-position')]);
            $(this).removeClass('updated');
        });
        console.log(positions);


        $.ajax({
            url: '<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/pertanyaan-unsur/change-list' ?>',
            method: 'POST',
            dataType: 'text',
            data: {
                update: 1,
                positions: positions
            },
            success: function(response) {
                console.log(response);
                toastr["success"]('Urutan data berhasil diubah!');

                // var table = $('.example').DataTable();
                // table.clear().draw();
                // table.rows.add().draw();

                // Clear table

                // $('.example').DataTable().clear();
                // Redraw table
                // $('.example').DataTable().reload();
                // table.ajax.reload();
                window.setTimeout(function() {
                    location.reload()
                }, 1800);
            }
        });
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('include_backend/template_backend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\IT\Documents\Htdocs MAMP\mk_skm\application\views/pertanyaan_unsur_survei/index.blade.php ENDPATH**/ ?>