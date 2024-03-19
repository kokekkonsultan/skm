<?php
defined('BASEPATH') or exit('No direct script access allowed');

class NilaiUnsurPerBagianController extends Client_Controller
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
        $this->data['title'] = 'Nilai Unsur Per Akun Anak';


        return view('nilai_unsur_per_bagian/index', $this->data);
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
                $kualitas_pelayanan = '-'; //NULL
            }

            $no++;
            $row = array();
            $row[] = '
			<a href="' . base_url() . 'nilai-unsur-per-bagian/' . $klien_user->username . '/' . $value->slug . '" title="">
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
        $this->data['title'] = 'Nilai Unsur';

        $slug = $this->uri->segment(3);

        $manage_survey = $this->db->get_where('manage_survey', ['slug' => "$slug"])->row();
        $users = $this->db->get_where("users", array('id' => $manage_survey->id_user))->row();
        $table_identity = $manage_survey->table_identity;
        $this->data['manage_survey'] = $manage_survey;
        $this->data['table_identity'] = $manage_survey->table_identity;
        $this->data['nama_survey'] = $manage_survey->survey_name;
        $this->data['nama_users'] = $users->first_name . ' ' . $users->last_name;


        //PENDEFINISIAN SKALA LIKERT
        $this->data['skala_likert'] = 100 / ($manage_survey->skala_likert == 5 ? 5 : 4);
        $this->data['definisi_skala'] = $this->db->query("SELECT * FROM definisi_skala_$table_identity ORDER BY id DESC");



        $this->data['unsur_pelayanan'] = $this->db->query("SELECT *, unsur_pelayanan_$table_identity.id AS id_unsur_pelayanan, (SELECT isi_pertanyaan_unsur FROM pertanyaan_unsur_pelayanan_$table_identity WHERE id_unsur_pelayanan = unsur_pelayanan_$table_identity.id) as isi_pertanyaan_unsur
        FROM unsur_pelayanan_$table_identity
        WHERE id_parent = 0");

        $this->data['get_pilihan_jawaban'] = $this->db->query("SELECT *, (SELECT COUNT(skor_jawaban) FROM jawaban_pertanyaan_unsur_$table_identity
        JOIN survey_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden WHERE jawaban_pertanyaan_unsur_$table_identity.id_pertanyaan_unsur = kategori_unsur_pelayanan_$table_identity.id_pertanyaan_unsur && kategori_unsur_pelayanan_$table_identity.nomor_kategori_unsur_pelayanan = jawaban_pertanyaan_unsur_$table_identity.skor_jawaban && is_submit = 1) AS perolehan, (SELECT COUNT(id) FROM survey_$table_identity WHERE is_submit = 1) AS jumlah_pengisi
        FROM kategori_unsur_pelayanan_$table_identity");


        $this->data['rekap_turunan_unsur'] = $this->db->query("SELECT *, pertanyaan_unsur_pelayanan_$table_identity.id AS id_pertanyaan_unsur_pelayanan,
        (SELECT COUNT(skor_jawaban)
        FROM jawaban_pertanyaan_unsur_$table_identity
        JOIN survey_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden
        WHERE is_submit = 1 && id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id AND skor_jawaban = 1) AS perolehan_1,
        (SELECT COUNT(skor_jawaban)
        FROM jawaban_pertanyaan_unsur_$table_identity
        JOIN survey_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden
        WHERE is_submit = 1 && id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id AND skor_jawaban = 2) AS perolehan_2,
        (SELECT COUNT(skor_jawaban)
        FROM jawaban_pertanyaan_unsur_$table_identity
        JOIN survey_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden
        WHERE is_submit = 1 && id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id AND skor_jawaban = 3) AS perolehan_3,
        (SELECT COUNT(skor_jawaban)
        FROM jawaban_pertanyaan_unsur_$table_identity
        JOIN survey_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden
        WHERE is_submit = 1 && id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id AND skor_jawaban = 4) AS perolehan_4,
        (SELECT COUNT(skor_jawaban)
        FROM jawaban_pertanyaan_unsur_$table_identity
        JOIN survey_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden
        WHERE is_submit = 1 && id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id AND skor_jawaban = 5) AS perolehan_5,
        (SELECT COUNT(id) FROM survey_$table_identity WHERE is_submit = 1) AS jumlah_pengisi,
        (SELECT AVG(skor_jawaban)
        FROM jawaban_pertanyaan_unsur_$table_identity
        JOIN survey_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden
        WHERE is_submit = 1 && id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id) AS rata_rata
        FROM unsur_pelayanan_$table_identity
        JOIN pertanyaan_unsur_pelayanan_$table_identity ON pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan = unsur_pelayanan_$table_identity.id");


        //NILAI PER UNSUR
        $this->data['nilai_per_unsur'] = $this->db->query("SELECT IF(id_parent = 0, unsur_pelayanan_$table_identity.id, unsur_pelayanan_$table_identity.id_parent) AS id_sub, 
        (SUM(skor_jawaban)/COUNT(DISTINCT survey_$table_identity.id_responden)) AS rata_rata, 
        (COUNT(id_parent)/COUNT(DISTINCT survey_$table_identity.id_responden)) AS colspan, 
        ((SUM(skor_jawaban)/COUNT(DISTINCT survey_$table_identity.id_responden))/(COUNT(id_parent)/COUNT(DISTINCT survey_$table_identity.id_responden))) AS nilai_per_unsur, 
        (SELECT nomor_unsur FROM unsur_pelayanan_$table_identity WHERE id_sub = unsur_pelayanan_$table_identity.id) as nomor_unsur, 
        (SELECT nama_unsur_pelayanan FROM unsur_pelayanan_$table_identity WHERE id_sub = unsur_pelayanan_$table_identity.id) as nama_unsur_pelayanan, unsur_pelayanan_$table_identity.id AS id_unsur

        FROM jawaban_pertanyaan_unsur_$table_identity 
        JOIN pertanyaan_unsur_pelayanan_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id 
        JOIN unsur_pelayanan_$table_identity ON pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan = unsur_pelayanan_$table_identity.id
        JOIN survey_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden
        WHERE survey_$table_identity.is_submit = 1 AND jawaban_pertanyaan_unsur_$table_identity.skor_jawaban != '0.0' 
        GROUP BY id_sub
        ORDER BY unsur_pelayanan_$table_identity.id");


        $this->data['skala_likert'] = 100 / ($manage_survey->skala_likert == 5 ? 5 : 4);
        $this->data['definisi_skala'] = $this->db->query("SELECT * FROM definisi_skala_$table_identity ORDER BY id DESC");

        // //JUMLAH KUISIONER
        $this->data['jumlah_kuesioner_terisi'] = $this->db->query("SELECT COUNT(id) AS total_kuesioner
        FROM survey_$table_identity WHERE is_submit = 1")->row()->total_kuesioner;


        return view('nilai_unsur_per_bagian/detail', $this->data);
    }



    public function modal()
    {
        $this->data = [];

        $slug = $this->uri->segment(3);
        $manage_survey = $this->db->get_where('manage_survey', ['slug' => "$slug"])->row();
        $table_identity = $manage_survey->table_identity;

        $this->data['unsur'] = $this->db->query("SELECT *,
        (SELECT id FROM pertanyaan_unsur_pelayanan_$table_identity WHERE id_unsur_pelayanan = unsur_pelayanan_$table_identity.id) AS id_pertanyaan_unsur,
        (SELECT isi_pertanyaan_unsur FROM pertanyaan_unsur_pelayanan_$table_identity WHERE id_unsur_pelayanan = unsur_pelayanan_$table_identity.id) AS isi_pertanyaan_unsur
        FROM unsur_pelayanan_$table_identity
        WHERE id = " . $this->uri->segment(5))->row();
        $id_pertanyaan_unsur = $this->data['unsur']->id_pertanyaan_unsur;

        $this->data['responden'] = $this->db->query("SELECT *, (SELECT nama_lengkap FROM responden_$table_identity WHERE id = survey_$table_identity.id) AS nama_lengkap,
        (SELECT nama_kategori_unsur_pelayanan FROM kategori_unsur_pelayanan_$table_identity WHERE nomor_kategori_unsur_pelayanan = jawaban_pertanyaan_unsur_$table_identity.skor_jawaban && kategori_unsur_pelayanan_$table_identity.id_pertanyaan_unsur = $id_pertanyaan_unsur) AS nama_kategori_unsur_pelayanan
        
        FROM jawaban_pertanyaan_unsur_$table_identity
        JOIN survey_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden
        WHERE is_submit = 1 && id_pertanyaan_unsur = $id_pertanyaan_unsur");



        // echo $this->uri->segment(2) . '<br>';
        // echo $this->uri->segment(3) . '<br>';
        // echo $this->uri->segment(4) . '<br>';
        // echo $this->uri->segment(5) . '<br>';


        return view('nilai_unsur_per_bagian/modal_detail', $this->data);
    }
}

/* End of file OlahDataPerBagianController.php */
/* Location: ./application/controllers/OlahDataPerBagianController.php */
