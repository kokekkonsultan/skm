<!----------------- MODAL EMAIL ------------------------>
<?php
foreach ($klasifikasi_survey as $ks) {
?>
    <div class="example-modal">
        <div id="klasifikasi<?php echo $ks->id ?>" class="modal fade" role="dialog" style="display:none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-secondary">
                        <h5 class="font-weight-bold">Klasifikasi Survei - <?php echo $nama_klasifikasi_survey ?></h5>
                    </div>
                    <div class="modal-body">
                        <div class="" id="kt_blockui_content">

                            @php
                            echo form_open($form_action);
                            @endphp
                            @php
                            echo validation_errors();
                            @endphp

                            <input type="hidden" name="id_klasifikasi_survei" value="<?php echo $ci->uri->segment(3) ?>">

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label font-weight-bold">Profil Responden</label>
                                <div class="col-sm-8">

                                    <?php
                                    foreach ($mst_profil->result() as $row) {
                                    ?>

                                        <div class="col mt-3">
                                            <input class="form-check-input" type="checkbox" value="<?php echo $row->id ?>" name="check_list[]">
                                            <label class="form-check-label" for="defaultCheck1">
                                                <?php echo $row->nama_mst_profil_responden ?>
                                            </label>
                                        </div>

                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>

                            <br>

                            <div class="text-right mt-3 mb-3">
                                <button type="button" class="btn
                            btn-light-primary
                            font-weight-bold" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary font-weight-bold">Tambah</button>
                            </div>

                            @php
                            echo form_close();
                            @endphp
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
}
?>