<!----------------- MODAL SARAN ------------------------>
<?php
foreach ($survey->result() as $ps) {
?>
<div class="example-modal">
    <div id="detail<?php echo $ps->id_responden ?>" class="modal fade" role="dialog" style="display:none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-secondary">
                    <h5 class="font-weight-bold"><b>Ubah Status</b></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form method="post"
                        action="<?php echo base_url() . $ci->session->userdata('username') . '/' . $ci->uri->segment(2) . '/inovasi-dan-saran' ?>">

                        <input type="hidden" name="id_responden" value="<?php echo $ps->id_responden ?>">
                        <div class="form-group row">
                            <label for="inputPassword" class="col-sm-2 col-form-label font-weight-bold">Status</label>
                            <div class="col-sm-10">
                                <select class="custom-select" name="is_active">

                                    <option value="1" <?php if ($ps->is_active == 1) {
                                                                echo 'selected';
                                                            } ?>>Di Tampilkan</option>
                                    <option value="2" <?php if ($ps->is_active == 2) {
                                                                echo 'selected';
                                                            } ?>>Tidak di Tampilan</option>
                                </select>
                            </div>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary btn-sm">Update</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
<?php
}
?>