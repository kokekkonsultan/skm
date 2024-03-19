<?php
$ci = get_instance();
$slug = $ci->uri->segment(2);
?>

<?php
$identitas_survey = $ci->db->query("
SELECT *, DATE_FORMAT(survey_end, '%d %M %Y') AS survey_selesai, IF(CURDATE() > survey_end,1,NULL) AS survey_berakhir,
IF(CURDATE() < survey_start ,1,NULL) AS survey_belum_mulai FROM manage_survey JOIN users ON
    manage_survey.id_user=users.id WHERE slug='$slug' ")->row();
?>

MOHON DIISI SEBELUM TANGGAL <strong><?php echo e($identitas_survey->survey_selesai); ?></strong>

<?php /**PATH C:\Users\IT\Documents\Htdocs MAMP\mk_skm\application\views/include_backend/partials_backend/_tanggal_survei.blade.php ENDPATH**/ ?>