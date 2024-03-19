

<?php
$ci = get_instance();
?>

<?php $__env->startSection('style'); ?>
<link href="<?php echo e(TEMPLATE_BACKEND_PATH); ?>plugins/custom/datatables/datatables.bundle.css" rel="stylesheet"
    type="text/css" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class=" container-fluid">

    <div class="card card-custom bgi-no-repeat gutter-b"
        style="height: 150px; background-color: #1c2840; background-position: calc(100% + 0.5rem) 100%; background-size: 100% auto; background-image: url(/assets/img/banner/taieri.svg)"
        data-aos="fade-down">
        <div class="card-body d-flex align-items-center">
            <div>
                <h3 class="text-white font-weight-bolder line-height-lg mb-5">
                    <?php echo e(strtoupper('Survei Yang Telah Anda Buat')); ?>

                </h3>

                <table>
                    <tr>
                        <td>
                            <?php if($data_survey->num_rows() > 0): ?>
                            <!--<form id="form-filter" class="">
                                <select name="is_privacy" id="is_privacy" class="form-control form-control-sm"
                                    style="width: 200px;" onchange="updateUnit();">
                                    <option value="">Filter By...</option>
                                    <option value="1">Public</option>
                                    <option value="2">Private</option>
                                </select>
                            </form>
                            <script>
                            function updateUnit() {
                                table.ajax.reload(null, false);
                            }
                            </script>-->
                            <?php endif; ?>
                        </td>
                        <td></td>
                        <td>
                        
                            

                            
                                    
                            <a class="btn btn-primary font-weight-bold shadow-lg btn-sm" href="<?php echo e(base_url() . $ci->session->userdata('username') . '/manage-survey/create-survey'); ?>"
                                title="Buat Survei"><i
                                    class="fas fa-book"></i> Buat Survei</a>
                        </td>

                    </tr>
                </table>





            </div>
        </div>
    </div>


    <div class="card shadow aos-init aos-animate" data-aos="fade-up">
        <!-- <div class="card-header bg-secondary font-weight-bold">
            Survey Yang Telah Anda Buat
        </div> -->

        <div class="card-body">

            <div class="form-group row">
                <div class="col-sm-6">

                </div>
                <div class="col-sm-6 text-right">


                </div>
            </div>

            <?php if($data_survey->num_rows() > 0): ?>
            <style>
            thead {
                display: none;
            }
            </style>
            <table id="table" class="table mt-5" cellspacing="0" width="100%">
                <thead class="bg-gray-300">
                    <tr>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <?php else: ?>
            <div class="fs-5 lead text-center mt-5 text-primary">
                Organisasi anda belum melakukan survey
            </div>
            <?php endif; ?>

        </div>
    </div>
</div>


<div class="modal fade" id="modalCreateSurvey" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body" id="bodyModalCreateSurvey">

            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('javascript'); ?>
<script src="<?php echo e(TEMPLATE_BACKEND_PATH); ?>plugins/custom/datatables/datatables.bundle.js"></script>
<script>
var table;
$(document).ready(function() {
    table = $("#table").DataTable({
        "processing": true,
        "serverSide": true,
        "lengthMenu": [
            [5, 10, -1],
            [5, 10, "Semua data"]
        ],
        "pageLength": 5,
        "ordering": true,
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "<?php echo e(base_url()); ?>manage-survey/ajax-list",
            "type": "POST",
            "dataType": "json",
            "dataSrc": function(jsonData) {
                return jsonData.data;
            },
            "data": function(data) {
                data.is_privacy = $('#is_privacy').val();
            },

        },
        "columnDefs": [{
            "targets": [0],
            "orderable": false,
        }, ],

        responsive: {
            details: {
                display: $.fn.dataTable.Responsive.display.modal({
                    header: function(row) {
                        var data = row.data();
                        return 'Details for ' + data[0] + ' ' + data[1];
                    }
                }),
                renderer: $.fn.dataTable.Responsive.renderer.tableAll({
                    tableClass: 'table'
                })
            }
        }

    });

    $('#btn-reset-table-data-entry-data').click(function() {
        table_data_entry_data.ajax.reload();
    });
});

function reload_table_entry_data() {
    table_data_entry_data.ajax.reload(null, false);
}

function create_survey_old() {
    window.location.href = "<?php echo e(base_url().$ci->session->userdata('username')); ?>/manage-survey/create-survey";
}

function showDetailPaketForm(id) {
    $.ajax({
        type: "post",
        url: "<?php echo e(base_url()); ?><?php echo e($ci->uri->segment(1)); ?>/create-survey-client",
        data: "id=" + id,
        dataType: "html",
        success: function(response) {
            $('#modalCreateSurvey').modal('show');
            $('.modal-title').text('Pilih Paket');

            $('#bodyModalCreateSurvey').empty();
            $('#bodyModalCreateSurvey').append(response);
        }
    });
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('include_backend/template_backend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\IT\Documents\Htdocs MAMP\mk_skm\application\views/manage_survey/index.blade.php ENDPATH**/ ?>