<?php

defined('BASEPATH') or exit('No direct script access allowed');



class DashboardIndukController extends Client_Controller

{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');

        if (!$this->ion_auth->logged_in()) {
			$this->session->set_flashdata('message_warning', 'You must be logged in to access this page');
			redirect('auth', 'refresh');
		}
        $this->load->model('DashboardInduk_model', 'models');
    }


    public function get_chart_survei()
    {
        $this->data = [];
        $this->data['title'] = 'Dashboard Chart';
        
        $users_parent = $this->db->get_where("users", array('id_parent_induk' => $this->session->userdata('user_id')));
        if($users_parent->num_rows() > 0){
            $parent = [];
            foreach($users_parent->result() as $get){
                $parent[] = $get->id;
            }
        } else {
            $parent = [0];
        }


        $this->db->select('*, manage_survey.slug AS slug_manage_survey, (SELECT first_name FROM users WHERE id = manage_survey.id_user) AS first_name, (SELECT last_name FROM users WHERE id = manage_survey.id_user) AS last_name');
        $this->db->from('manage_survey');
        $this->db->where_in('id_user', $parent);
        $this->db->order_by('manage_survey.id', 'DESC');
        $this->data['manage_survey'] = $this->db->get();
        $this->data['total_survey'] = $this->data['manage_survey']->num_rows();


        if ($this->data['manage_survey']->num_rows() > 0) {
            
            $pilih_survei = [];
            $no = 1;
            foreach ($this->data['manage_survey']->result() as $value) {
                // $skala_likert = (100 / ($value->skala_likert == 5 ? 5 : 4));
                $this->data['tahun_awal'] = $value->survey_year;
                if ($this->db->get_where("survey_$value->table_identity", array('is_submit' => 1))->num_rows() > 0) {

                    $nilai_per_unsur[$no] = $this->db->query("SELECT IF(id_parent = 0,unsur_pelayanan_$value->table_identity.id, unsur_pelayanan_$value->table_identity.id_parent) AS id_sub,

					((SUM(skor_jawaban)/COUNT(DISTINCT survey_$value->table_identity.id_responden))/(COUNT(id_parent)/COUNT(DISTINCT survey_$value->table_identity.id_responden))) AS nilai_per_unsur, (((SUM(skor_jawaban)/COUNT(DISTINCT survey_$value->table_identity.id_responden))/(COUNT(id_parent)/COUNT(DISTINCT survey_$value->table_identity.id_responden)))/(SELECT COUNT(id) FROM unsur_pelayanan_$value->table_identity WHERE id_parent = 0)) AS rata_rata_bobot
					FROM jawaban_pertanyaan_unsur_$value->table_identity

					JOIN pertanyaan_unsur_pelayanan_$value->table_identity ON jawaban_pertanyaan_unsur_$value->table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$value->table_identity.id

					JOIN unsur_pelayanan_$value->table_identity ON pertanyaan_unsur_pelayanan_$value->table_identity.id_unsur_pelayanan = unsur_pelayanan_$value->table_identity.id

					JOIN survey_$value->table_identity ON jawaban_pertanyaan_unsur_$value->table_identity.id_responden = survey_$value->table_identity.id_responden
					WHERE survey_$value->table_identity.is_submit = 1 AND jawaban_pertanyaan_unsur_$value->table_identity.skor_jawaban != '0.0'
					GROUP BY id_sub");


                    $nilai_bobot[$no] = [];
                    foreach ($nilai_per_unsur[$no]->result() as $get) {
                        $nilai_bobot[$no][] = $get->rata_rata_bobot;
                        $nilai_tertimbang[$no] = array_sum($nilai_bobot[$no]);
                    }

                    // $new_chart[] = '{ label: "' . $value->survey_name . ' - ' . $value->organisasi . '", value: "' . ROUND($nilai_tertimbang[$no], 3) . '" }';

                    $category[] = '{"label": "' . $value->survey_name . ' - ' . $value->organisasi . '"}';
                    $dataset[] = '{"value": "' . ROUND($nilai_tertimbang[$no], 3) . '"}';
                    
                } else {

                    // $new_chart[] = '{ label: "' . $value->survey_name . ' - ' . $value->organisasi . '", value: "0" }';
                    $category[] = '{"label": "' . $value->survey_name . ' - ' . $value->organisasi . '"}';
                    $dataset[] = '{"value": "0"}';
                };

                $no++;

                $pilih_survei[] = '<label class="checkbox">
                                            <table class="table table-bordered">
                                            <tr>
                                                <td width="5%">
                                                    <input type="checkbox" name="id[]" value="' . $value->id . '" class="child">
                                                    <span></span>
                                                </td>
                                                <td width="70%">' . $value->survey_name . '</td>
                                                <td width="25%"><b>' . $value->first_name . ' ' .  $value->last_name . '</b></td>
                                            </tr>
                                            </table>
                                        </label>';
            }

            $this->data['checkbox'] = implode("", $pilih_survei);
            // $this->data['new_chart'] = implode(", ", $new_chart);
            $this->data['category'] = implode(", ", $category);
            $this->data['dataset'] = implode(", ", $dataset);
        } else {

            $this->data['category'] = '{"label": ""}';
            $this->data['dataset'] = '{"value": "0"}';
            // $this->data['new_chart'] = '{ label: "", value: "0" }';
            $this->data['checkbox'] = '<div class="text-center"><i>Belum ada survei yang di kaitkan dengan akun ini!</i></div>';
        }

        return view("dashboard/chart_survei_induk", $this->data);
    }



    public function get_tabel_survei()
    {
        $this->data = [];
        $this->data['title'] = 'Dashboard Tabel';

        $users_parent = $this->db->query("SELECT GROUP_CONCAT(id) AS id_parent_induk FROM users WHERE id_parent_induk =" . $this->session->userdata('user_id'))->row();
		if($users_parent->id_parent_induk == null){
			$this->data['parent'] = 0;
		} else {
			$this->data['parent'] = $users_parent->id_parent_induk;
		}

        return view("dashboard/tabel_survei_induk", $this->data);
    }



    public function ajax_list_tabel_survei_induk()
    {
    
        $users_parent = $this->db->get_where("users", array('id_parent_induk' => $this->session->userdata('user_id')));
        if($users_parent->num_rows() > 0){
            $parent = [];
            foreach($users_parent->result() as $get){
                $parent[] = $get->id;
            }
        } else {
            $parent = [0];
        }

        // $parent = array('179', '190');

        $list = $this->models->get_datatables($parent);
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $value) {

            $no++;
            $row = array();
            $skala_likert = (100 / ($value->skala_likert == 5 ? 5 : 4));

            if ($this->db->get_where("survey_$value->table_identity", array('is_submit' => 1))->num_rows() > 0) {

                $nilai_per_unsur[$no] = $this->db->query("SELECT IF(id_parent = 0,unsur_pelayanan_$value->table_identity.id, unsur_pelayanan_$value->table_identity.id_parent) AS id_sub,

				((SUM(skor_jawaban)/COUNT(DISTINCT survey_$value->table_identity.id_responden))/(COUNT(id_parent)/COUNT(DISTINCT survey_$value->table_identity.id_responden))) AS nilai_per_unsur, (((SUM(skor_jawaban)/COUNT(DISTINCT survey_$value->table_identity.id_responden))/(COUNT(id_parent)/COUNT(DISTINCT survey_$value->table_identity.id_responden)))/(SELECT COUNT(id) FROM unsur_pelayanan_$value->table_identity WHERE id_parent = 0)) AS rata_rata_bobot

				FROM jawaban_pertanyaan_unsur_$value->table_identity
				JOIN pertanyaan_unsur_pelayanan_$value->table_identity ON jawaban_pertanyaan_unsur_$value->table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$value->table_identity.id
				JOIN unsur_pelayanan_$value->table_identity ON pertanyaan_unsur_pelayanan_$value->table_identity.id_unsur_pelayanan = unsur_pelayanan_$value->table_identity.id
				JOIN survey_$value->table_identity ON jawaban_pertanyaan_unsur_$value->table_identity.id_responden = survey_$value->table_identity.id_responden
				WHERE survey_$value->table_identity.is_submit = 1 AND jawaban_pertanyaan_unsur_$value->table_identity.skor_jawaban != '0.0'
				GROUP BY id_sub");

                $nilai_bobot[$no] = [];
                foreach ($nilai_per_unsur[$no]->result() as $get) {
                    $nilai_bobot[$no][] = $get->rata_rata_bobot;
                    $nilai_tertimbang[$no] = array_sum($nilai_bobot[$no]);
                }
                $skor_indeks[$no] = $nilai_tertimbang[$no];
                $skor_akhir[$no] = ROUND($nilai_tertimbang[$no] * $skala_likert, 2);
            } else {
                $skor_akhir[$no] = 0;
                $skor_indeks[$no] = 0;
            };


            foreach ($this->db->query("SELECT * FROM definisi_skala_$value->table_identity ORDER BY id DESC")->result() as $obj) {
                if ($skor_akhir[$no] <= $obj->range_bawah && $skor_akhir[$no] >= $obj->range_atas) {
                    $kualitas_pelayanan[$no] = $obj->kategori;
                    $mutu_pelayanan[$no] = $obj->mutu;
                }
            }

            if ($skor_akhir[$no] <= 0) {
                $kualitas_pelayanan[$no] = '-';//NULL
                $mutu_pelayanan[$no] = '-';//NULL
            }

            $this->db->select('*');
            $this->db->from('log_report_'.$value->table_identity);
            $log_report = $this->db->get()->num_rows();

            if ($log_report > 0) {
                $status_survei = 'Tutup';
            }else{
                $status_survei = 'Buka';
            }


            $row[] = $no;
            $row[] = $value->survey_name;
            $row[] = $value->first_name . ' ' . $value->last_name;
            $row[] = date("d-m-Y", strtotime($value->survey_start));
            $row[] = date("d-m-Y", strtotime($value->survey_end));
            $row[] = ROUND($skor_indeks[$no], 3);
            $row[] = $mutu_pelayanan[$no];
            // $row[] = $kualitas_pelayanan[$no];
            $row[] = $status_survei;

            $data[] = $row;
        }

        $output = array(
            "draw"                 => $_POST['draw'],
            "recordsTotal"         => $this->models->count_all($parent),
            "recordsFiltered"     => $this->models->count_filtered($parent),
            "data"                 => $data,
        );

        echo json_encode($output);
    }
}



/* End of file DashboardIndukController.php */
/* Location: ./application/controllers/DashboardIndukController.php */
