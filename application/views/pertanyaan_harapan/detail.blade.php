<!----------------- MODAL EMAIL ------------------------>
<?php
foreach ($pertanyaan_harapan as $row) {
?>
<div class="example-modal">
    <div id="pertanyaan_tambahan<?php echo $row->id ?>" class="modal fade" role="dialog" style="display:none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-secondary">
                    <h5 class="font-weight-bold">Detail Jawaban</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">

                    <div class="" id="kt_blockui_content">

                        <p><b><?php echo $row->isi_pertanyaan_unsur ?></b></p>

                        <div class="ml-3">
                            <li><?php echo $row->pilihan_1 ?></li>
                            <li><?php echo $row->pilihan_2 ?></li>
                            <li><?php echo $row->pilihan_3 ?></li>
                            <li><?php echo $row->pilihan_4 ?></li>
                        </div>

                        <br>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
}
?>