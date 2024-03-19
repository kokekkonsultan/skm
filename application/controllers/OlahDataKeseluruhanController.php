<?php
defined('BASEPATH') or exit('No direct script access allowed');

class OlahDataKeseluruhanController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library('ion_auth');

        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message_warning', 'You must be logged in to access this page');
            redirect('auth', 'refresh');
        }

        $this->load->library('form_validation');
        $this->load->model('DataPerolehanPerBagian_model', 'models');
    }

    public function index()
    {
        $this->data = [];
        $this->data['title'] = 'Nilai Indeks Seluruh Survei';

        // $this->data['induk'] = $this->db->get_where("pengguna_admin_induk", array('id_user' => $this->session->userdata('user_id')))->row();
        $id_user = $this->session->userdata('user_id');
        // $this->data['induk'] = $this->db->get_where("pengguna_admin_induk", array('id_user' => $id_user))->row();

        $this->data['induk'] = $this->db->query("SELECT * FROM indeks_admin_induk WHERE id_user = $id_user");
        $this->data['nilai_induk'] = $this->data['induk']->row();

        return view('olah_data_keseluruhan/index', $this->data);
    }



    public function ajax_list()
    {
        $users_parent = $this->db->query("SELECT GROUP_CONCAT(id) AS id_parent_induk FROM users WHERE id_parent_induk =" . $this->session->userdata('user_id'))->row();
		if($users_parent->id_parent_induk == null){
			$parent = 0;
		} else {
			$parent = $users_parent->id_parent_induk;
		}

        $list = $this->models->get_datatables($parent);
        $data = array();
        $no = $_POST['start'];

        foreach ($list as $value) {

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
                $nilai_ikk = ROUND($nilai_tertimbang[$no], 3);
            } else {
                $nilai_ikk = 0;
            };


            $no++;
            $row = array();
            $row[] = '<div class="checkbox-list"><label class="checkbox"><input type="checkbox" name="id[]" value="' . $value->id . '" class="child"><span></span>' . $no . '</label></div>';
            $row[] = $value->survey_name;
            $row[] = $value->organisasi;
            $row[] = '<b class="text-primary">' . $nilai_ikk . '</b>';

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->models->count_all($parent),
            "recordsFiltered" => $this->models->count_filtered($parent),
            "data" => $data,
        );
        echo json_encode($output);
    }


    public function proses_index()
    {

            $id = $this->input->post('id[]');
    
            $this->db->select('*, manage_survey.slug AS slug_manage_survey, (SELECT first_name FROM users WHERE id = manage_survey.id_user) AS first_name, (SELECT last_name FROM users WHERE id = manage_survey.id_user) AS last_name');
            $this->db->from('manage_survey');
            $this->db->where_in("id", $id);
            $manage_survey = $this->db->get();
    
            if ($manage_survey->num_rows() > 0) {
                
                $no = 1;
                foreach ($manage_survey->result() as $value) {
    
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
    
                        $nilai[] = $nilai_tertimbang[$no];
                    } else {
                        $nilai[] = 0;
                    };
                    $no++;
                }
                $hasil_akhir = array_sum($nilai)/count($nilai);
            } else {
                $hasil_akhir = 0;
            }
    
            $object = [
                'id_user' => $this->session->userdata('user_id'),
                'label' => $this->input->post('label'),
                'nilai_indeks' => $hasil_akhir,
                'id_object_indeks' => serialize($id),
                'created_at' => date("Y/m/d H:i:s")
            ];
            $this->db->insert('indeks_admin_induk', $object);
    
            $pesan = 'Data berhasil disimpan';
            $msg = ['sukses' => $pesan];
        
        echo json_encode($msg);

    }


    public function history_nilai()
    {
        $this->data = [];
        $this->data['title'] = 'History Nilai Per Periode';

        $id_user = $this->session->userdata('user_id');
        $this->data['nilai_induk']  = $this->db->query("SELECT * FROM indeks_admin_induk WHERE id_user = $id_user ORDER BY id DESC");

        $users_parent = $this->db->query("SELECT GROUP_CONCAT(id) AS id_parent_induk FROM users WHERE id_parent_induk =" . $this->session->userdata('user_id'))->row();
		if($users_parent->id_parent_induk == null){
			$parent = 0;
		} else {
			$parent = $users_parent->id_parent_induk;
		}

        $this->data['manage_survey'] = $this->db->query("SELECT *, (SELECT first_name FROM users WHERE id = manage_survey.id_user) AS first_name, (SELECT last_name FROM users WHERE id = manage_survey.id_user) AS last_name FROM manage_survey WHERE id_user IN ($parent)");


        return view('olah_data_keseluruhan/form_history', $this->data);
    }


    public function edit()
	{
		    $id = $this->input->post('id[]');
    
            $this->db->select('*, manage_survey.slug AS slug_manage_survey, (SELECT first_name FROM users WHERE id = manage_survey.id_user) AS first_name, (SELECT last_name FROM users WHERE id = manage_survey.id_user) AS last_name');
            $this->db->from('manage_survey');
            $this->db->where_in("id", $id);
            $manage_survey = $this->db->get();
    
            if ($manage_survey->num_rows() > 0) {
                
                $no = 1;
                foreach ($manage_survey->result() as $value) {
    
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
    
                        $nilai[] = $nilai_tertimbang[$no];
                    } else {
                        $nilai[] = 0;
                    };
                    $no++;
                }
                $hasil_akhir = array_sum($nilai)/count($nilai);
            } else {
                $hasil_akhir = 0;
            }
    
            $object = [
                'label' => $this->input->post('label'),
                'nilai_indeks' => $hasil_akhir,
                'id_object_indeks' => serialize($id),
                'update_at' => date("Y/m/d H:i:s")
            ];
            $this->db->where('id', $this->input->post('id_nilai'));
            $this->db->update('indeks_admin_induk', $object);
    
            $pesan = 'Data berhasil disimpan';
            $msg = ['sukses' => $pesan];
        
        echo json_encode($msg);
    
	}

    public function delete()
	{
		$this->db->where('id', $this->uri->segment(3));
		$this->db->delete('indeks_admin_induk');

		echo json_encode(array("status" => TRUE));
	}
}
