<!----------------- MODAL EMAIL ------------------------>
<?php
foreach ($prospek_surveyor as $ps) {
?>
<div class="example-modal">
    <div id="email<?php echo $ps->id ?>" class="modal fade" role="dialog" style="display:none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-secondary">
                    <h5 class="font-weight-bold">Bagikan Via Email</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <img src="<?php echo base_url(); ?>assets/img/site/campaign/bg-bagi-link-email.jpg"
                        class="img-fluid" alt="">

                    <br>
                    <br>

                    <div class="" id="kt_blockui_content">

                        <form action="{{ base_url() }}prospek-surveyor/get-email"
                            class="form_email<?php echo $ps->id ?>" method="POST">

                            <input type="email" name="email_akun" class="form-control" value="<?php echo $ps->email ?>"
                                required hidden>

                            <label for="" class="font-weight-bold">Isi Email *</label>
                            <textarea name="isi_email" class="form-control" rows="8"
                                required>Kami Tim Survey Kepuasan Masyarakat <?php echo $manage_survey->company; ?>, memohon kepada Bapak/ Ibu, untuk mengisi Kuesioner <?php echo $manage_survey->survey_name; ?> dengan link berikut ini <?php echo base_url() ?>survei/<?php echo $manage_survey->slug; ?>/<?php echo $surveyor->uuid; ?>. Mohon diisi sebelum tanggal <?php echo date('d-m-Y', strtotime($manage_survey->survey_end)) ?>. Atas kesedian dan partisipasinya kami ucapkan Terima Kasih.</textarea>

                            <div class="mt-5 text-right">
                                <p class="font-italic">Pastikan email sudah benar dan aktif !</p>
                                <button type="button" class="btn btn-light-primary font-weight-bold"
                                    data-dismiss="modal">Batal</button>
                                <button type="submit"
                                    class="btn btn-light-primary font-weight-bold tombolEmailTest shadow">Kirim Link
                                    Survey ke <?php echo $ps->email ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
}
?>