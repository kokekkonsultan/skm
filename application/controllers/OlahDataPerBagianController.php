<?php
defined('BASEPATH') or exit('No direct script access allowed');

class OlahDataPerBagianController extends Client_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library('ion_auth');

        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message_warning', 'You must be an admin to view this page');
            redirect('auth', 'refresh');
        }
        $this->load->library('form_validation');
        $this->load->model('DataPerolehanPerBagian_model', 'models');
        $this->load->model('OlahData_model', 'models');
        $this->load->model('OlahData_model');
    }

    public function index()
    {
        $this->data = [];
        $this->data['title'] = 'Olah Data';

        $users_parent = $this->db->query("SELECT GROUP_CONCAT(id) AS id_parent_induk FROM users WHERE id_parent_induk =" . $this->session->userdata('user_id'))->row();
		if($users_parent->id_parent_induk == null){
			$parent = 0;
		} else {
			$parent = $users_parent->id_parent_induk;
		}

        $this->db->select('*, manage_survey.slug AS slug_manage_survey, (SELECT first_name FROM users WHERE id = manage_survey.id_user) AS first_name, (SELECT last_name FROM users WHERE id = manage_survey.id_user) AS last_name');
        $this->db->from('manage_survey');
        $this->db->where("id_user IN ($parent)");

        $manage_survey = $this->db->get();

        if ($manage_survey->num_rows() > 0) {
            $no = 1;
            foreach ($manage_survey->result() as $value) {

                $skala_likert = (100 / ($value->skala_likert == 5 ? 5 : 4));
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

                    $data_chart[] = '{"label": "' . $value->survey_name .' - '. $value->organisasi . '",
						"value": "' . ROUND($nilai_tertimbang[$no], 3) . '"}';
                } else {
                    $data_chart[] = '{"label": "' . $value->survey_name .' - '. $value->organisasi . '", "value": "0"}';
                };
                $no++;
            }
            $this->data['get_data_chart'] = implode(", ", $data_chart);
        } else {
            $this->data['get_data_chart'] = '{"label": "", "value": "0"}';
        }

        return view('olah_data_per_bagian/index', $this->data);
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

            $klien_user = $this->db->get_where("users", array('id' => $value->id_user))->row();
            $skala_likert = (100 / ($value->skala_likert == 5 ? 5 : 4));

            if ($value->is_privacy == 1) {
                $status = '<span class="badge badge-info" width="40%">Public</span>';
            } else {
                $status = '<span class="badge badge-danger" width="40%">Private</span>';
            };

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
                $nilai_skala_4 = ROUND($nilai_tertimbang[$no], 3);
                $nilai_ikk = ROUND($nilai_tertimbang[$no] * $skala_likert, 2);
            } else {
                $nilai_skala_4 = 0;
                $nilai_ikk = 0;
            };

            foreach ($this->db->query("SELECT * FROM definisi_skala_$value->table_identity ORDER BY id DESC")->result() as $obj) {
                if ($nilai_ikk <= $obj->range_bawah && $nilai_ikk >= $obj->range_atas) {
                    $kualitas_pelayanan = $obj->kategori;
                }
            }
            if ($nilai_ikk <= 0) {
                $kualitas_pelayanan = '-';//NULL
            }

            $no++;
            $row = array();
            $row[] = '
			<a href="' . base_url() . 'olah-data-per-bagian/' . $klien_user->username . '/' . $value->slug . '" title="">
			<div class="card mb-5 shadow" style="background-color: SeaShell;">
				<div class="card-body">
					<div class="row">
						<div class="col sm-10">
							<strong style="font-size: 17px;">' . $value->survey_name . '</strong><br>
							<span class="text-dark">Nama Akun : <b>' . $value->first_name . ' ' . $value->last_name . '</b></span><br>
                            <span class="text-dark">Nilai Indeks : <b>' . $nilai_skala_4 . '</b></span><br>
                            <span class="text-dark">Kategori : <b>' . $kualitas_pelayanan . '</b></span><br>
						</div>
						<div class="col sm-2 text-right"><span class="badge badge-info" width="40%">Detail</span>
							<div class="mt-3 text-dark font-weight-bold" style="font-size: 11px;">
                            Periode Survei : ' . date('d-m-Y', strtotime($value->survey_start)) . ' s/d ' . date('d-m-Y', strtotime($value->survey_end)) . '
							</div>

						</div>
					</div>
					<!--small class="text-secondary">' . $value->description . '</small><br-->
					
				</div>
			</div>
		</a>';

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


    

    public function detail($id1, $id2)
    {
        $this->data = [];
        $this->data['title'] = 'Olah Data';
        $this->data['profiles'] = Client_Controller::_get_data_profile($id1, $id2);

        $slug = $this->uri->segment(3);

        $manage_survey = $this->db->get_where('manage_survey', ['slug' => "$slug"])->row();
        $table_identity = $manage_survey->table_identity;
        $this->data['nama_survey'] = $manage_survey->survey_name;


        $this->data['skala_likert'] = 100 / ($manage_survey->skala_likert == 5 ? 5 : 4);
		$this->data['definisi_skala'] = $this->db->query("SELECT * FROM definisi_skala_$table_identity ORDER BY id DESC");

        //JUMLAH KUISIONER
        $this->data['jumlah_kuesioner_terisi'] = $this->db->query("SELECT COUNT(id) AS total_kuesioner
        FROM survey_$table_identity WHERE is_submit = 1")->row()->total_kuesioner;


		$this->data['unsur'] = $this->db->query("SELECT *, SUBSTR(nomor_unsur,2) AS nomor_harapan
		FROM unsur_pelayanan_$table_identity
		JOIN pertanyaan_unsur_pelayanan_$table_identity ON unsur_pelayanan_$table_identity.id = pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan
		");

		//TOTAL
		$this->data['total'] = $this->db->query("SELECT SUM(skor_jawaban) AS sum_skor_jawaban
		FROM jawaban_pertanyaan_unsur_$table_identity
		JOIN responden_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = responden_$table_identity.id
		JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
		WHERE is_submit = 1 AND jawaban_pertanyaan_unsur_$table_identity.skor_jawaban != '0.0'
		GROUP BY id_pertanyaan_unsur");

		
		//RATA-RATA
		$this->db->select("(SUM(skor_jawaban)/COUNT(DISTINCT jawaban_pertanyaan_unsur_$table_identity.id_responden)) AS rata_rata");
		$this->db->from('jawaban_pertanyaan_unsur_' . $table_identity);
		$this->db->join("survey_$table_identity", "jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden");
		$this->db->where("survey_$table_identity.is_submit = 1");
        $this->db->where("jawaban_pertanyaan_unsur_$table_identity.skor_jawaban != '0.0'");
		$this->db->group_by('id_pertanyaan_unsur');
		$this->data['rata_rata'] = $this->db->get();

		//RATA-RATA HARAPAN
		$this->db->select("(SUM(skor_jawaban)/COUNT(DISTINCT survey_$table_identity.id_responden)) AS rata_rata");
		$this->db->from('jawaban_pertanyaan_harapan_' . $table_identity);
		$this->db->join("survey_$table_identity", "jawaban_pertanyaan_harapan_$table_identity.id_responden = survey_$table_identity.id_responden");
		$this->db->where("survey_$table_identity.is_submit = 1");
		$this->db->group_by('id_pertanyaan_unsur');
		$this->data['rata_rata_harapan'] = $this->db->get();

		//NILAI PER UNSUR
		$this->db->select("nama_unsur_pelayanan, IF(id_parent = 0,unsur_pelayanan_$table_identity.id, unsur_pelayanan_$table_identity.id_parent) AS id_sub, (SUM(skor_jawaban)/COUNT(DISTINCT survey_$table_identity.id_responden)) AS rata_rata,  (COUNT(id_parent)/COUNT(DISTINCT survey_$table_identity.id_responden)) AS colspan, ((SUM(skor_jawaban)/COUNT(DISTINCT survey_$table_identity.id_responden))/(COUNT(id_parent)/COUNT(DISTINCT survey_$table_identity.id_responden))) AS nilai_per_unsur");
		$this->db->from('jawaban_pertanyaan_unsur_' . $table_identity);
		$this->db->join("pertanyaan_unsur_pelayanan_$table_identity", "jawaban_pertanyaan_unsur_$table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id");
		$this->db->join("unsur_pelayanan_$table_identity", "pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan = unsur_pelayanan_$table_identity.id");
		$this->db->join("survey_$table_identity", "jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden");
		$this->db->where("survey_$table_identity.is_submit = 1");
        $this->db->where("jawaban_pertanyaan_unsur_$table_identity.skor_jawaban != '0.0'");
		$this->db->group_by('id_sub');
		$this->data['nilai_per_unsur'] = $this->db->get();


		//RATA-RATA BOBOT
		$this->db->select("nama_unsur_pelayanan, IF(id_parent = 0,unsur_pelayanan_$table_identity.id, unsur_pelayanan_$table_identity.id_parent) AS id_sub, (SUM(skor_jawaban)/COUNT(DISTINCT survey_$table_identity.id_responden)) AS rata_rata,  (COUNT(id_parent)/COUNT(DISTINCT survey_$table_identity.id_responden)) AS colspan, ((SUM(skor_jawaban)/COUNT(DISTINCT survey_$table_identity.id_responden))/(COUNT(id_parent)/COUNT(DISTINCT survey_$table_identity.id_responden))) AS nilai, (((SUM(skor_jawaban)/COUNT(DISTINCT survey_$table_identity.id_responden))/(COUNT(id_parent)/COUNT(DISTINCT survey_$table_identity.id_responden)))/(SELECT COUNT(id) FROM unsur_pelayanan_$table_identity WHERE id_parent = 0)) AS rata_rata_bobot");
		$this->db->from('jawaban_pertanyaan_unsur_' . $table_identity);
		$this->db->join("pertanyaan_unsur_pelayanan_$table_identity", "jawaban_pertanyaan_unsur_$table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id");
		$this->db->join("unsur_pelayanan_$table_identity", "pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan = unsur_pelayanan_$table_identity.id");
		$this->db->join("survey_$table_identity", "jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden");
		$this->db->where("survey_$table_identity.is_submit = 1");
        $this->db->where("jawaban_pertanyaan_unsur_$table_identity.skor_jawaban != '0.0'");
		$this->db->group_by('id_sub');
		$this->data['rata_rata_bobot'] = $this->db->get();

		//TERTIMBANG
		$this->db->select("IF(id_parent = 0,unsur_pelayanan_$table_identity.id, unsur_pelayanan_$table_identity.id_parent) AS id_sub,  (COUNT(id_parent)/COUNT(DISTINCT survey_$table_identity.id_responden)) AS colspan, (((SUM(skor_jawaban)/COUNT(DISTINCT survey_$table_identity.id_responden))/(COUNT(id_parent)/COUNT(DISTINCT survey_$table_identity.id_responden)))) AS tertimbang, ((((SUM(skor_jawaban)/COUNT(DISTINCT survey_$table_identity.id_responden))/(COUNT(id_parent)/COUNT(DISTINCT survey_$table_identity.id_responden))))*25) AS skm");
		$this->db->from('jawaban_pertanyaan_unsur_' . $table_identity);
		$this->db->join("pertanyaan_unsur_pelayanan_$table_identity", "jawaban_pertanyaan_unsur_$table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id");
		$this->db->join("unsur_pelayanan_$table_identity", "pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan = unsur_pelayanan_$table_identity.id");
		$this->db->join("survey_$table_identity", "jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden");
		$this->db->where("survey_$table_identity.is_submit = 1");
        $this->db->where("jawaban_pertanyaan_unsur_$table_identity.skor_jawaban != '0.0'");
		$this->data['tertimbang'] = $this->db->get()->row();

        //JUMLAH KUESIONER
		$this->db->select("(COUNT(DISTINCT jawaban_pertanyaan_unsur_$table_identity.id_responden)) AS jumlah_kuisioner");
		$this->db->from('jawaban_pertanyaan_unsur_' . $table_identity);
		$this->db->join("survey_$table_identity", "jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden");
		$this->db->where("survey_$table_identity.is_submit = 1");
		$this->db->where("jawaban_pertanyaan_unsur_$table_identity.skor_jawaban != '0.0'");
		$this->db->group_by('id_pertanyaan_unsur');
		$this->data['jumlah_kuisioner_per_unsur'] = $this->db->get();

        // var_dump($total_biaya);

        return view('olah_data_per_bagian/detail', $this->data);
    }

}

/* End of file OlahDataPerBagianController.php */
/* Location: ./application/controllers/OlahDataPerBagianController.php */
