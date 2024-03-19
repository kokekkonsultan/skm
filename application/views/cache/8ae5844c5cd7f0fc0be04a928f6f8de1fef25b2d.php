<?php
$ci = get_instance();
?>

<div class="card card-custom">
    <div class="card-body">
        <!-- <div class="text-center"> -->
        <div class="text-right mb-3">
        <button type="button" class="btn btn-info btn-sm font-weight-bold" data-toggle="modal"
                            data-target="#exampleModal2"><i class="fa fa-filter"></i> Filter Data
                        </button>
                    </div>
                        
        <table id="table" class="table table-bordered table-hover" cellspacing="0" width="100%">
            <thead class="bg-secondary">
                <tr>
                    <th>No</th>
                    <th>Nama Survei</th>
                    <th>Organisasi</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Berakhir</th>
                    <th>Nilai Indeks</th>
                    <th>Mutu Pelayanan</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        <!-- </div> -->
    </div>
</div>


<!-- MODAL -->
<div class="modal fade bd-example-modal-lg" id="exampleModal2" tabindex="-1" role="dialog"
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
                            <label for="is_surveyor" class="form-label font-weight-bold text-primary">Akun Anak</label>
                            <select id="id_akun_anak" class="form-control">
                                <option value="">Please Select</option>
                                <?php
                                $id_user = $ci->session->userdata('user_id');
		                        $akun_anak = $ci->db->query("SELECT * FROM users WHERE id_parent_induk IN ($id_user)");
                                ?>
                                <?php $__currentLoopData = $akun_anak->result(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($row->id); ?>"><?php echo e($row->first_name); ?> <?php echo e($row->last_name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>

                        </div>

                        <div class="col-md-6 mb-5">
                            <label for="is_submit" class="form-label font-weight-bold text-primary">Status
                                <!--Buka / Tutup--></label>
                            <select id="is_submit" class="form-control">
                                <option value="">Please Select</option>
                                <option value="1">Survei Sedang Berlangsung</option>
                                <option value="2">Survei Sudah Ditutup</option>
                            </select>

                        </div>
                        
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

<script>
$(document).ready(function() {
    table = $('#table').DataTable({

        "processing": true,
        "serverSide": true,
        "lengthMenu": [
            [10, 15, -1],
            [10, 15, "Semua data"]
        ],
        "pageLength": 10,
        "order": [],
        "language": {
            "processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',
        },
        "ajax": {
            "url": "<?php echo base_url() . 'dashboard/ajax-list-tabel-survei-induk' ?>",
            "type": "POST",
            "data": function(data) {
                data.is_submit = $('#is_submit').val();
                data.id_akun_anak = $('#id_akun_anak').val();
                data.is_tanggal_start = $('#is_tanggal_start').val();
                data.is_tanggal_end = $('#is_tanggal_end').val();
            }
            //"data": function(data) {}
        },

        "columnDefs": [{
            //"targets": [-1],
             "targets": [0,5,6,7],
            "orderable": false,
        }, ],

    });
});

$('#btn-filter').click(function() {
    table.ajax.reload();
});
$('#btn-reset').click(function() {
    $('#form-filter')[0].reset();
    $('#checkAll').prop('checked', false);
    table.ajax.reload();
});
</script>
<?php /**PATH C:\Users\IT\Documents\Htdocs MAMP\mk_skm\application\views/dashboard/tabel_survei_induk.blade.php ENDPATH**/ ?>