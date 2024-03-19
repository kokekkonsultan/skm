<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Survei_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function dropdown_layanan_survei($table_identity)
    {
        $query = $this->db->get_where("layanan_survei_$table_identity", array('is_active' => 1));

        if ($query->num_rows() > 0) {

            $dd[''] = 'Please Select';
            foreach ($query->result_array() as $row) {
                $dd[$row['id']] = $row['nama_layanan'];
            }

            return $dd;
        }
    }

    public function js_pertanyaan_terbuka_atas($table_identity)
    {
        #==========================================================================================================#
        #====================================== START PERTANYAAN TERBUKA ATAS =====================================#
        #==========================================================================================================#


        $javascript_ta = [];
        foreach ($this->db->query("SELECT *, perincian_pertanyaan_terbuka_$table_identity.id AS id_perincian_pertanyaan_terbuka
		FROM pertanyaan_terbuka_$table_identity
		JOIN perincian_pertanyaan_terbuka_$table_identity ON pertanyaan_terbuka_$table_identity.id = perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka
		WHERE is_letak_pertanyaan = 1 && id_jenis_pilihan_jawaban = 1")->result() as $a => $row) {


            $arr_ipg[$a] = [];
            foreach ($this->db->query("SELECT * FROM isi_pertanyaan_ganda_$table_identity WHERE id_perincian_pertanyaan_terbuka = $row->id_perincian_pertanyaan_terbuka")->result() as $b => $ipg) {

                #KHUSUS PILIHAN LAINNYA
                if ($ipg->pertanyaan_ganda == 'Lainnya') {
                    $pt_lainnya[$a][$b] = "
						$('#terbuka_lainnya_$row->nomor_pertanyaan_terbuka').prop('required', true).show();
						$('#text_terbuka_$row->nomor_pertanyaan_terbuka').show();
						";
                } else {
                    $pt_lainnya[$a][$b] = "
						$('#terbuka_lainnya_$row->nomor_pertanyaan_terbuka').removeAttr('required').hide();
						$('#text_terbuka_$row->nomor_pertanyaan_terbuka').hide();
						";
                }


                #LOOP ACTION
                $arr_subpt[$a][$b] = [];
                foreach ($this->db->query("SELECT *, perincian_pertanyaan_terbuka_$table_identity.id AS id_perincian
                FROM pertanyaan_terbuka_$table_identity
                JOIN perincian_pertanyaan_terbuka_$table_identity ON pertanyaan_terbuka_$table_identity.id = perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka
                WHERE is_letak_pertanyaan = 1")->result() as $c => $subpt) {

                    // #TAMPILKAN T < T ACTION
                    if (substr($row->nomor_pertanyaan_terbuka, 1) < substr($subpt->nomor_pertanyaan_terbuka, 1)) {

                        #ACTION HIDE SHOW
                        if (substr($subpt->nomor_pertanyaan_terbuka, 1) < substr($ipg->is_next_step, 1)) {

                            $model_pt[$a][$b][$c] = $subpt->id_jenis_pilihan_jawaban == 1 ? ".removeAttr('checked')" : ".val('')";
                            $stts_pt[$a][$b][$c] = "hide()";
                            $prop_pt[$a][$b][$c] = "removeAttr('required')" . $model_pt[$a][$b][$c];
                        } else {
                            $stts_pt[$a][$b][$c] = "show()";
                            $prop_pt[$a][$b][$c] = "prop('required', true)";
                        }

                        $arr_subpt[$a][$b][] =  "
								$('#display_$subpt->nomor_pertanyaan_terbuka')." . $stts_pt[$a][$b][$c] . ";
								$('.terbuka_$subpt->nomor_pertanyaan_terbuka')." . $prop_pt[$a][$b][$c] . ";";
                    } else {
                        $arr_subpt[$a][$b][] = '';
                    }
                }






                $arr_ipg_1[$a][] = "
                    if ($('.terbuka_$row->nomor_pertanyaan_terbuka:checked').val() == '$ipg->pertanyaan_ganda') {
                        " . $pt_lainnya[$a][$b] . implode("", $arr_subpt[$a][$b]) . "
                    };";

                $arr_ipg_2[$a][] = "
                    if ($(this).val() == '$ipg->pertanyaan_ganda') {
                        " . $pt_lainnya[$a][$b] . implode("", $arr_subpt[$a][$b]) . "
                    }";
            }



            $javascript_ta[] = "
            $(document).ready(function() {
				" . implode("", $arr_ipg_1[$a]) . "
			});


            $(function() {
                $(':radio.terbuka_$row->nomor_pertanyaan_terbuka').click(function() {
                    " . implode("", $arr_ipg_2[$a]) . "
                });
            });";
        }
        #==========================================================================================================#
        #======================================== END PERTANYAAN TERBUKA ATAS =====================================#
        #==========================================================================================================#

        return implode("", $javascript_ta);
    }

    

    public function js_pertanyaan_unsur($table_identity, $pertanyaan_unsur)
    {
        #==========================================================================================================#
        #====================================== START PERTANYAAN UNSUR ============================================#
        #==========================================================================================================#

        #LOOPING UNSUR
        $javascript_unsur = [];
        foreach ($pertanyaan_unsur->result() as $a => $pr) {

            #LOOPING JAWABAN UNSUR
            $arr_kup[$a] = [];
            foreach ($this->db->get_where("kategori_unsur_pelayanan_$table_identity", ['id_pertanyaan_unsur' => $pr->id_pertanyaan_unsur])->result() as $b => $kup) {

                #Tampilkan alasan jika jawaban pertanyaan 1 dan 2
                if ($kup->nomor_kategori_unsur_pelayanan == 1 || $kup->nomor_kategori_unsur_pelayanan == 2) {
                    $alasan[$a] = "
					$('#input_alasan_$pr->nomor').prop('required', true).show();
					$('#text_alasan_$pr->nomor').show();";
                } else {
                    $alasan[$a] = "
					$('#input_alasan_$pr->nomor').removeAttr('required').hide();
            		$('#text_alasan_$pr->nomor').hide();";
                }


                #LOOPING Terbuka hide show
                $arr_pt[$a][$b] = [];
                foreach ($this->db->query("SELECT *, perincian_pertanyaan_terbuka_$table_identity.id AS id_perincian
				FROM pertanyaan_terbuka_$table_identity
				JOIN perincian_pertanyaan_terbuka_$table_identity ON pertanyaan_terbuka_$table_identity.id = perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka
				WHERE id_unsur_pelayanan = $pr->id_unsur_pelayanan")->result() as $pt) {

                    if (substr($pt->nomor_pertanyaan_terbuka, 1) < substr($kup->is_next_step, 1)) {

                        $model[$a][$b] = $pt->id_jenis_pilihan_jawaban == 1 ? ".removeAttr('checked')" : ".val('')";

                        $stts[$a][$b] = "hide()";
                        $prop[$a][$b] = "removeAttr('required')" . $model[$a][$b];
                    } else {
                        $stts[$a][$b] = "show()";
                        $prop[$a][$b] = "prop('required', true)";
                    }

                    $arr_pt[$a][$b][] = "
					$('#display_$pt->nomor_pertanyaan_terbuka')." . $stts[$a][$b] . ";
					$('.terbuka_$pt->nomor_pertanyaan_terbuka')." . $prop[$a][$b] . ";";
                }

                #Untuk menutup lompatan ketika sudah ada data
                $arr_kup_1[$a][] = "
					if ($('.unsur_$pr->nomor:checked').val() == $kup->nomor_kategori_unsur_pelayanan) {"
                    . $alasan[$a] . implode("", $arr_pt[$a][$b]) .
                    "}";

                #Untuk menutup lompatan ketika belum ada data
                $arr_kup_2[$a][] = "
					if ($(this).val() == $kup->nomor_kategori_unsur_pelayanan) {"
                    . $alasan[$a] . implode("", $arr_pt[$a][$b]) .
                    "}";
            }



            #==========================================================================================================#
            #============================================== START PERTANYAAN TERBUKA ==================================#
            #==========================================================================================================#

            $javascript_pt[$a] = [];
            foreach ($this->db->query("SELECT *, perincian_pertanyaan_terbuka_$table_identity.id AS id_perincian
			FROM pertanyaan_terbuka_$table_identity
			JOIN perincian_pertanyaan_terbuka_$table_identity ON pertanyaan_terbuka_$table_identity.id = perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka
			WHERE id_jenis_pilihan_jawaban = 1 && id_unsur_pelayanan = $pr->id_unsur_pelayanan")->result() as $c => $pt_u) {

                #LOOP PILIHAN TERBUKA
                $arr_ipg[$a][$c] = [];
                foreach ($this->db->get_where("isi_pertanyaan_ganda_$table_identity", ['id_perincian_pertanyaan_terbuka' => $pt_u->id_perincian])->result() as $d => $ipg) {


                    #KHUSUS PILIHAN LAINNYA
                    if ($ipg->pertanyaan_ganda == 'Lainnya') {
                        $pt_lainnya[$a][$c][$d] = "
							$('#terbuka_lainnya_$pt_u->nomor_pertanyaan_terbuka').prop('required', true).show();
							$('#text_terbuka_$pt_u->nomor_pertanyaan_terbuka').show();
							";
                    } else {
                        $pt_lainnya[$a][$c][$d] = "
							$('#terbuka_lainnya_$pt_u->nomor_pertanyaan_terbuka').removeAttr('required').hide();
							$('#text_terbuka_$pt_u->nomor_pertanyaan_terbuka').hide();
							";
                    }


                    #LOOP ACTION
                    $arr_subpt[$a][$c][$d] = [];
                    foreach ($this->db->query("SELECT *, perincian_pertanyaan_terbuka_$table_identity.id AS id_perincian
						FROM pertanyaan_terbuka_$table_identity
						JOIN perincian_pertanyaan_terbuka_$table_identity ON pertanyaan_terbuka_$table_identity.id = perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka
						WHERE id_unsur_pelayanan = $pr->id_unsur_pelayanan")->result() as $e => $subpt_u) {

                        #TAMPILKAN T < T ACTION
                        if (substr($pt_u->nomor_pertanyaan_terbuka, 1) < substr($subpt_u->nomor_pertanyaan_terbuka, 1)) {


                            #ACTION HIDE SHOW
                            if (substr($subpt_u->nomor_pertanyaan_terbuka, 1) < substr($ipg->is_next_step, 1)) {

                                $model_pt[$a][$c][$d][$e] = $subpt_u->id_jenis_pilihan_jawaban == 1 ? ".removeAttr('checked')" : ".val('')";

                                $stts_pt[$a][$c][$d][$e] = "hide()";
                                $prop_pt[$a][$c][$d][$e] = "removeAttr('required')" . $model_pt[$a][$c][$d][$e];
                            } else {
                                $stts_pt[$a][$c][$d][$e] = "show()";
                                $prop_pt[$a][$c][$d][$e] = "prop('required', true)";
                            }

                            $arr_subpt[$a][$c][$d][] =  "
								$('#display_$subpt_u->nomor_pertanyaan_terbuka')." . $stts_pt[$a][$c][$d][$e] . ";
								$('.terbuka_$subpt_u->nomor_pertanyaan_terbuka')." . $prop_pt[$a][$c][$d][$e] . ";";
                        } else {
                            $arr_subpt[$a][$c][$d][] = '';
                        }
                    }




                    $arr_ipg_1[$a][$c][] = "
						if ($('.terbuka_$pt_u->nomor_pertanyaan_terbuka:checked').val() == '$ipg->pertanyaan_ganda') {
							" . $pt_lainnya[$a][$c][$d] . implode("", $arr_subpt[$a][$c][$d]) . "
						};";

                    $arr_ipg_2[$a][$c][] = "
						if ($(this).val() == '$ipg->pertanyaan_ganda') {
							" . $pt_lainnya[$a][$c][$d] . implode("", $arr_subpt[$a][$c][$d]) . "
						};";
                }


                $javascript_pt[$a][] = "
				$(document).ready(function() {
					" . implode("", $arr_ipg_1[$a][$c]) . "
				});

				$(':radio.terbuka_$pt_u->nomor_pertanyaan_terbuka').click(function() {
					" . implode("", $arr_ipg_2[$a][$c]) . "
				});";
            }

            #==========================================================================================================#
            #============================================== END PERTANYAAN TERBUKA ====================================#
            #==========================================================================================================#




            $javascript_unsur[] = "
            $(document).ready(function() {
				" . implode("", $arr_kup_1[$a]) . "
			});

			$(function() {
				$(':radio.unsur_$pr->nomor').click(function() {
					" . implode("", $arr_kup_2[$a]) . "
				});
			});" . implode("", $javascript_pt[$a]);
        }
        #==========================================================================================================#
        #====================================== END PERTANYAAN UNSUR ==============================================#
        #==========================================================================================================#


        $data = implode("", $javascript_unsur);
        return $data;
    }






    public function js_pertanyaan_terbuka_bawah($table_identity)
    {
        #==========================================================================================================#
        #====================================== START PERTANYAAN TERBUKA BAWAH =====================================#
        #==========================================================================================================#


        $javascript_tb = [];
        foreach ($this->db->query("SELECT *, perincian_pertanyaan_terbuka_$table_identity.id AS id_perincian_pertanyaan_terbuka
		FROM pertanyaan_terbuka_$table_identity
		JOIN perincian_pertanyaan_terbuka_$table_identity ON pertanyaan_terbuka_$table_identity.id = perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka
		WHERE is_letak_pertanyaan = 2 && id_jenis_pilihan_jawaban = 1")->result() as $a => $row) {


            $arr_ipg[$a] = [];
            foreach ($this->db->query("SELECT * FROM isi_pertanyaan_ganda_$table_identity WHERE id_perincian_pertanyaan_terbuka = $row->id_perincian_pertanyaan_terbuka")->result() as $b => $ipg) {

                #KHUSUS PILIHAN LAINNYA
                if ($ipg->pertanyaan_ganda == 'Lainnya') {
                    $pt_lainnya[$a][$b] = "
						$('#terbuka_lainnya_$row->nomor_pertanyaan_terbuka').prop('required', true).show();
						$('#text_terbuka_$row->nomor_pertanyaan_terbuka').show();
						";
                } else {
                    $pt_lainnya[$a][$b] = "
						$('#terbuka_lainnya_$row->nomor_pertanyaan_terbuka').removeAttr('required').hide();
						$('#text_terbuka_$row->nomor_pertanyaan_terbuka').hide();
						";
                }


                #LOOP ACTION
                $arr_subpt[$a][$b] = [];
                foreach ($this->db->query("SELECT *, perincian_pertanyaan_terbuka_$table_identity.id AS id_perincian
                FROM pertanyaan_terbuka_$table_identity
                JOIN perincian_pertanyaan_terbuka_$table_identity ON pertanyaan_terbuka_$table_identity.id = perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka
                WHERE is_letak_pertanyaan = 2")->result() as $c => $subpt) {

                    // #TAMPILKAN T < T ACTION
                    if (substr($row->nomor_pertanyaan_terbuka, 1) < substr($subpt->nomor_pertanyaan_terbuka, 1)) {

                        #ACTION HIDE SHOW
                        if (substr($subpt->nomor_pertanyaan_terbuka, 1) < substr($ipg->is_next_step, 1)) {

                            $model_pt[$a][$b][$c] = $subpt->id_jenis_pilihan_jawaban == 1 ? ".removeAttr('checked')" : ".val('')";
                            $stts_pt[$a][$b][$c] = "hide()";
                            $prop_pt[$a][$b][$c] = "removeAttr('required')" . $model_pt[$a][$b][$c];
                        } else {
                            $stts_pt[$a][$b][$c] = "show()";
                            $prop_pt[$a][$b][$c] = "prop('required', true)";
                        }

                        $arr_subpt[$a][$b][] =  "
								$('#display_$subpt->nomor_pertanyaan_terbuka')." . $stts_pt[$a][$b][$c] . ";
								$('.terbuka_$subpt->nomor_pertanyaan_terbuka')." . $prop_pt[$a][$b][$c] . ";";
                    } else {
                        $arr_subpt[$a][$b][] = '';
                    }
                }






                $arr_ipg_1[$a][] = "
                    if ($('.terbuka_$row->nomor_pertanyaan_terbuka:checked').val() == '$ipg->pertanyaan_ganda') {
                        " . $pt_lainnya[$a][$b] . implode("", $arr_subpt[$a][$b]) . "
                    };";

                $arr_ipg_2[$a][] = "
                    if ($(this).val() == '$ipg->pertanyaan_ganda') {
                        " . $pt_lainnya[$a][$b] . implode("", $arr_subpt[$a][$b]) . "
                    }";
            }



            $javascript_tb[] = "
            $(document).ready(function() {
				" . implode("", $arr_ipg_1[$a]) . "
			});


            $(function() {
                $(':radio.terbuka_$row->nomor_pertanyaan_terbuka').click(function() {
                    " . implode("", $arr_ipg_2[$a]) . "
                });
            });";
        }
        #==========================================================================================================#
        #======================================== END PERTANYAAN TERBUKA BAWAH =====================================#
        #==========================================================================================================#

        return implode("", $javascript_tb);
    }
}

/* End of file JenisPelayanan_model.php */
/* Location: ./application/models/JenisPelayanan_model.php */