<?php
$n = $pertanyaan_terbuka_atas->num_rows() + 1;
?>

<?php $__currentLoopData = $pertanyaan_terbuka->result(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row_terbuka): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<?php if($row_terbuka->id_unsur_pelayanan == $row->id_unsur_pelayanan): ?>
<?php
$is_required_t = $row_terbuka->is_required == 1 ? '<b class="text-danger">*</b>' : '';
?>

<div class=" mt-10 mb-10" id="display_<?php echo e($row_terbuka->nomor_pertanyaan_terbuka); ?>">
    <input type="hidden" name="id_pertanyaan_terbuka[<?php echo e($row_terbuka->id_pertanyaan_terbuka); ?>]"
        value="<?php echo e($row_terbuka->id_pertanyaan_terbuka); ?>">
    <table class="table table-borderless" width="100%" border="0">
        <tr>
            <td width="5%" valign="top"><?php echo $row_terbuka->nomor_pertanyaan_terbuka . '' .
                $is_required_t; ?>.</td>
            <td width="95%"><?php echo $row_terbuka->isi_pertanyaan_terbuka; ?></td>
        </tr>

        <tr>
            <td width="5%"></td>
            <td style="font-weight:bold;" width="95%">
                <?php $__currentLoopData = $jawaban_pertanyaan_terbuka->result(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value_terbuka): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($value_terbuka->id_perincian_pertanyaan_terbuka ==
                $row_terbuka->id_perincian_pertanyaan_terbuka): ?>

                <div class="radio-inline mb-2">
                    <label class="radio radio-outline radio-success radio-lg" style="font-size: 16px;">
                        <input type="radio" name="jawaban_pertanyaan_terbuka[<?php echo e($row_terbuka->id_pertanyaan_terbuka); ?>]"
                            value="<?php echo e($value_terbuka->pertanyaan_ganda); ?>"
                            class="terbuka_<?php echo e($row_terbuka->nomor_pertanyaan_terbuka); ?>"
                            <?= $value_terbuka->pertanyaan_ganda == $row_terbuka->jawaban ? 'checked' : '' ?>
                            <?= $row_terbuka->stts_required ?>>
                        <span></span> <?php echo e($value_terbuka->pertanyaan_ganda); ?>

                    </label>
                </div>
                <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                <?php if($row_terbuka->dengan_isian_lainnya == 1): ?>

                <input class="form-control" name="jawaban_lainnya[<?php echo e($row_terbuka->id_pertanyaan_terbuka); ?>]"
                    value="<?php echo e($row_terbuka->jawaban_lainnya); ?>" pattern="^[a-zA-Z0-9.,\s]*$|^\w$"
                    placeholder="Masukkan jawaban lainnya ..."
                    id="terbuka_lainnya_<?php echo e($row_terbuka->nomor_pertanyaan_terbuka); ?>"
                    <?= $row_terbuka->jawaban == 'Lainnya' ? 'required' : 'style="display:none"' ?>>

                <small id="text_terbuka_<?php echo e($row_terbuka->nomor_pertanyaan_terbuka); ?>" class="text-danger"
                    <?= $row_terbuka->jawaban == 'Lainnya' ? '' : 'style="display:none"' ?>>**Pengisian
                    form hanya dapat menggunakan tanda baca
                    (.) titik dan (,) koma</small>
                <br>
                <?php endif; ?>



                <?php if($row_terbuka->id_jenis_pilihan_jawaban == 2): ?>
                <textarea type="text"
                    name="jawaban_pertanyaan_terbuka[<?php echo e($row_terbuka->id_pertanyaan_terbuka); ?>]"
                    class="form-control terbuka_<?php echo e($row_terbuka->nomor_pertanyaan_terbuka); ?>"
                    placeholder="Masukkan Jawaban Anda ..."
                    <?= $row_terbuka->stts_required ?>><?php echo e($row_terbuka->jawaban); ?></textarea>

                <?php endif; ?>
            </td>
        </tr>
    </table>
</div>
<?php endif; ?>

<?php
$n++;
?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php /**PATH C:\Users\IT\Documents\Htdocs MAMP\mk_skm\application\views/survei/pertanyaan_terbuka/_terbuka.blade.php ENDPATH**/ ?>