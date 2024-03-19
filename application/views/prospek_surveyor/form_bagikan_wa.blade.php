<!----------------- MODAL WA ------------------------>
<?php
foreach ($prospek_surveyor as $pros) {
?>
    <div class="example-modal">
        <div id="wa<?php echo $pros->id; ?>" class="modal fade" role="dialog" style="display:none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header font-weight-bold">
                        <h5 class="font-weight-bold" style="color: #5777ba"><?php echo $pros->keterangan; ?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <img src="<?php echo base_url(); ?>assets/img/site/campaign/bg-bagi-link-wa.jpg" class="img-fluid" alt="">

                        <br>
                        <br>
                        <div style="color: red; border: 1px red solid; padding: 10px;">
                            Features in progress...
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