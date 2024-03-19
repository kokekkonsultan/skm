<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PreviewFormSurveiController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message_warning', 'You must be an admin to view this page');
            redirect('auth', 'refresh');
        }

        $this->load->library('form_validation');
        $this->load->model('Survei_model');
    }

    public function index($id1, $id2)
    { }


    public function form_opening()
    {
        $this->data = [];
        $this->data['title'] = 'Preview Form Opening';

        $data_uri = $this->uri->segment('2');

        $this->db->select("*, DATE_FORMAT(survey_end, '%d %M %Y') AS survey_selesai, IF(CURDATE() > survey_end,1,NULL) AS survey_berakhir, IF(CURDATE() < survey_start ,1,NULL) AS survey_belum_mulai");
        $this->db->from('manage_survey');
        $this->db->join('users', 'manage_survey.id_user = users.id');
        $this->db->where("slug = '$data_uri'");
        $this->data['manage_survey'] = $this->db->get()->row();
        $this->data['judul'] = $this->data['manage_survey'];
        $this->data['status_saran'] = $this->data['manage_survey']->is_saran;

        return view('preview_form_survei/form_opening', $this->data);
    }

    public function data_responden($id1, $id2)
    {
        $this->data = [];
        $this->data['title'] = 'Preview Form Data Responden';
        $this->data['profiles'] = $this->_get_data_profile($id1, $id2);

        $this->db->select("*, DATE_FORMAT(survey_end, '%d %M %Y') AS survey_selesai, IF(CURDATE() > survey_end,1,NULL) AS survey_berakhir, IF(CURDATE() < survey_start ,1,NULL) AS survey_belum_mulai");
        $this->db->from('manage_survey');
        $this->db->where('manage_survey.slug', $this->uri->segment(2));
        $this->data['manage_survey'] = $this->db->get()->row();
        $table_identity = $this->data['manage_survey']->table_identity;
        $this->data['status_saran'] = $this->data['manage_survey']->is_saran;

        //LOAD PROFIL RESPONDEN
        $this->data['profil_responden'] = $this->db->query("SELECT * FROM profil_responden_$table_identity ORDER BY IF(urutan != '',urutan,id) ASC");

        //LOAD KATEGORI PROFIL RESPONDEN JIKA PILIHAN GANDA
        $this->data['kategori_profil_responden'] = $this->db->get('kategori_profil_responden_' . $table_identity);

        $this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'trim|required');

        foreach ($this->data['profil_responden']->result() as $get) {
            $this->form_validation->set_rules("$get->nama_alias", "$get->nama_profil_responden", 'trim|required');
        }


        $this->data['id_layanan_survei'] = [
            'name'         => 'id_layanan_survei',
            'id'         => 'id_layanan_survei',
            'options'     => $this->Survei_model->dropdown_layanan_survei($table_identity),
            'selected'     => $this->form_validation->set_value('id_layanan_survei'),
            'class'     => "form-control",
            'required' => 'required',
        ];

        return view('preview_form_survei/form_data_responden', $this->data);
    }

    public function getdatalayanan($id1, $id2, $id3)
    {
        $this->db->select('*');
        $this->db->from('manage_survey');
        $this->db->where('manage_survey.slug', $id2);
        $manage_survey = $this->db->get()->row();

        $searchTerm = $this->input->post('searchTerm');
        $this->db->select('*');
        $this->db->from("layanan_survei_$manage_survey->table_identity");
        $this->db->where('id_kategori_layanan', $id3);
        $this->db->where("nama_layanan like '%" . $searchTerm . "%' ");
        $this->db->order_by('urutan', 'asc');
        $fetched_records = $this->db->get()->result_array();

        $data = array();
        foreach ($fetched_records as $kab) {
            $data[] = array("id" => $kab['id'], "text" => $kab['nama_layanan']);
        }


        echo json_encode($data);
    }


    public function data_pertanyaan($id1, $id2)
    {
        $this->data = [];
        $this->data['title'] = 'Preview Form Pertanyaan Survei';
        $this->data['profiles'] = $this->_get_data_profile($id1, $id2);

        $this->db->select('');
        $this->db->from('manage_survey');
        $this->db->where('manage_survey.slug', $this->uri->segment(2));
        $this->data['manage_survey'] = $this->db->get()->row();
        $table_identity = $this->data['manage_survey']->table_identity;
        $this->data['status_saran'] = $this->data['manage_survey']->is_saran;

        //PERTANYAAN UNSUR
        $this->data['pertanyaan_unsur'] = $this->db->query("SELECT pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan AS id_unsur_pelayanan, pertanyaan_unsur_pelayanan_$table_identity.id AS id_pertanyaan_unsur, isi_pertanyaan_unsur, unsur_pelayanan_$table_identity.nomor_unsur AS nomor, SUBSTRING(nomor_unsur, 2, 4) AS nomor_harapan, nama_unsur_pelayanan, (SELECT nomor_pertanyaan_terbuka FROM pertanyaan_terbuka_$table_identity WHERE unsur_pelayanan_$table_identity.id = pertanyaan_terbuka_$table_identity.id_unsur_pelayanan GROUP BY id_unsur_pelayanan) AS nomor_pertanyaan_terbuka, is_required, is_alasan
		FROM pertanyaan_unsur_pelayanan_$table_identity
		JOIN unsur_pelayanan_$table_identity ON unsur_pelayanan_$table_identity.id = pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan
		ORDER BY pertanyaan_unsur_pelayanan_$table_identity.id ASC");

        //JAWABAN PERTANYAAN UNSUR
        $this->data['jawaban_pertanyaan_unsur'] = $this->db->query("SELECT kategori_unsur_pelayanan_$table_identity.id_pertanyaan_unsur, kategori_unsur_pelayanan_$table_identity.nomor_kategori_unsur_pelayanan, kategori_unsur_pelayanan_$table_identity.nama_kategori_unsur_pelayanan
		FROM kategori_unsur_pelayanan_$table_identity");


        //PERTANYAAM TERBUKA PALING ATAS
        $this->data['pertanyaan_terbuka_atas'] = $this->db->query("SELECT *, perincian_pertanyaan_terbuka_$table_identity.id AS id_perincian_pertanyaan_terbuka, (SELECT DISTINCT dengan_isian_lainnya FROM isi_pertanyaan_ganda_$table_identity WHERE isi_pertanyaan_ganda_$table_identity.id_perincian_pertanyaan_terbuka = perincian_pertanyaan_terbuka_$table_identity.id) AS dengan_isian_lainnya
        FROM pertanyaan_terbuka_$table_identity
        JOIN perincian_pertanyaan_terbuka_$table_identity ON pertanyaan_terbuka_$table_identity.id = perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka
        WHERE pertanyaan_terbuka_$table_identity.is_letak_pertanyaan = 1");

        //PERTANYAAM TERBUKA
        $this->data['pertanyaan_terbuka'] = $this->db->query("SELECT *, perincian_pertanyaan_terbuka_$table_identity.id AS id_perincian_pertanyaan_terbuka, (SELECT DISTINCT dengan_isian_lainnya FROM isi_pertanyaan_ganda_$table_identity WHERE isi_pertanyaan_ganda_$table_identity.id_perincian_pertanyaan_terbuka = perincian_pertanyaan_terbuka_$table_identity.id) AS dengan_isian_lainnya
        FROM pertanyaan_terbuka_$table_identity
        JOIN perincian_pertanyaan_terbuka_$table_identity ON pertanyaan_terbuka_$table_identity.id = perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka
        WHERE pertanyaan_terbuka_$table_identity.id_unsur_pelayanan != ''");

        //PERTANYAAM TERBUKA PALING BAWAH
        $this->data['pertanyaan_terbuka_bawah'] = $this->db->query("SELECT *, perincian_pertanyaan_terbuka_$table_identity.id AS id_perincian_pertanyaan_terbuka, (SELECT DISTINCT dengan_isian_lainnya FROM isi_pertanyaan_ganda_$table_identity WHERE isi_pertanyaan_ganda_$table_identity.id_perincian_pertanyaan_terbuka = perincian_pertanyaan_terbuka_$table_identity.id) AS dengan_isian_lainnya
        FROM pertanyaan_terbuka_$table_identity
        JOIN perincian_pertanyaan_terbuka_$table_identity ON pertanyaan_terbuka_$table_identity.id = perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka
        WHERE pertanyaan_terbuka_$table_identity.is_letak_pertanyaan = 2");



        //JAWABAN PERTANYAAN TERBUKA
        $this->data['jawaban_pertanyaan_terbuka'] = $this->db->query("SELECT *
		FROM isi_pertanyaan_ganda_$table_identity
		LEFT JOIN perincian_pertanyaan_terbuka_$table_identity ON isi_pertanyaan_ganda_$table_identity.id_perincian_pertanyaan_terbuka = perincian_pertanyaan_terbuka_$table_identity.id");

        $atribut_pertanyaan = unserialize($this->data['manage_survey']->atribut_pertanyaan_survey);

        if (in_array(1, $atribut_pertanyaan)) {
            $this->data['url_next'] = base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2)
                . '/preview-form-survei/pertanyaan-harapan';
        } else if (in_array(3, $atribut_pertanyaan)) {
            $this->data['url_next'] = base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2)
                . '/preview-form-survei/pertanyaan-kualitatif';
        } else if ($this->data['manage_survey']->is_saran == 1) {
            $this->data['url_next'] = base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2)
                . '/preview-form-survei/saran';
        } else {
            $this->data['url_next'] = base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2) . '/preview-preview-form-survei/konfirmasi';
        }

        return view('preview_form_survei/form_pertanyaan', $this->data);
    }

    public function data_pertanyaan_harapan($id1, $id2)
    {
        $this->data = [];
        $this->data['title'] = 'Preview Form Pertanyaan Harapan';
        $this->data['profiles'] = $this->_get_data_profile($id1, $id2);

        $this->db->select('');
        $this->db->from('manage_survey');
        $this->db->where('manage_survey.slug', $this->uri->segment(2));
        $this->data['manage_survey'] = $this->db->get()->row();
        $table_identity = $this->data['manage_survey']->table_identity;
        $this->data['status_saran'] = $this->data['manage_survey']->is_saran;

        $query = $this->db->query("SELECT pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan AS id_unsur_pelayanan, pertanyaan_unsur_pelayanan_$table_identity.id AS id_pertanyaan_unsur, isi_pertanyaan_unsur, IF(unsur_pelayanan_$table_identity.is_sub_unsur_pelayanan = 2, SUBSTRING(nama_unsur_pelayanan, 1, 2), SUBSTRING(nama_unsur_pelayanan, 1, 4)) AS nomor, SUBSTRING(nomor_unsur, 2, 4) AS nomor_harapan
		FROM pertanyaan_unsur_pelayanan_$table_identity
		JOIN unsur_pelayanan_$table_identity ON unsur_pelayanan_$table_identity.id = pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan
		ORDER BY pertanyaan_unsur_pelayanan_$table_identity.id ASC");
        $this->data['pertanyaan_unsur'] = $query;
        // var_dump($this->data['pertanyaan_unsur']->result());

        //JAWABAN PERTANYAAN HARAPAN
        $this->data['jawaban_pertanyaan_harapan'] = $this->db->query("SELECT id_pertanyaan_unsur_pelayanan, nomor_tingkat_kepentingan, nama_tingkat_kepentingan
		FROM nilai_tingkat_kepentingan_$table_identity");

        $atribut_pertanyaan = unserialize($this->data['manage_survey']->atribut_pertanyaan_survey);

        if (in_array(3, $atribut_pertanyaan)) {
            $this->data['url_next'] = base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2)
                . '/preview-form-survei/pertanyaan-kualitatif';
        } else if ($this->data['manage_survey']->is_saran == 1) {
            $this->data['url_next'] = base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2)
                . '/preview-form-survei/saran';
        } else {
            $this->data['url_next'] = base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2) . '/preview-form-survei/konfirmasi';
        }

        return view('preview_form_survei/form_pertanyaan_harapan', $this->data);
    }

    public function pertanyaan_kualitatif($id1, $id2)
    {
        $this->data = array();
        $this->data['title'] = 'Preview Form Pertanyaan Kualitatif';
        $this->data['profiles'] = $this->_get_data_profile($id1, $id2);

        $this->db->select('');
        $this->db->from('manage_survey');
        $this->db->where('manage_survey.slug', $this->uri->segment(2));
        $this->data['manage_survey'] = $this->db->get()->row();
        $table_identity = $this->data['manage_survey']->table_identity;
        $this->data['status_saran'] = $this->data['manage_survey']->is_saran;


        $this->data['kualitatif'] = $this->db->query("select *
		FROM pertanyaan_kualitatif_$table_identity
		WHERE pertanyaan_kualitatif_$table_identity.is_active = 1")->result();

        $atribut_pertanyaan = unserialize($this->data['manage_survey']->atribut_pertanyaan_survey);


        if (in_array(1, $atribut_pertanyaan)) {
            $this->data['url_back'] = base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2)
                . '/preview-form-survei/pertanyaan-harapan';
        } else {
            $this->data['url_back'] = base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2) . '/preview-form-survei/pertanyaan';
        }

        if ($this->data['manage_survey']->is_saran == 1) {
            $this->data['url_next'] = base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2)
                . '/preview-form-survei/saran';
        } else {
            $this->data['url_next'] = base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2) . '/preview-form-survei/konfirmasi';
        }

        return view('preview_form_survei/pertanyaan_kualitatif', $this->data);
    }

    public function saran($id1, $id2)
    {
        $this->data = [];
        $this->data['title'] = 'Preview Form Saran';
        $this->data['profiles'] = $this->_get_data_profile($id1, $id2);

        $this->db->select('');
        $this->db->from('manage_survey');
        $this->db->where('manage_survey.slug', $this->uri->segment(2));
        $this->data['manage_survey'] = $this->db->get()->row();

        $this->data['saran'] = [
            'name'         => 'saran',
            'id'        => 'saran',
            'type'        => 'text',
            'value'        =>    $this->form_validation->set_value('saran'),
            'class'        => 'form-control',
            'placeholder' => 'Masukkan saran atau opini anda terhadap survei ini ..',
        ];

        $atribut_pertanyaan = unserialize($this->data['manage_survey']->atribut_pertanyaan_survey);

        if (in_array(3, $atribut_pertanyaan)) {
            $this->data['url_back'] = base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2)
                . '/preview-form-survei/pertanyaan-kualitatif';
        } else if (in_array(1, $atribut_pertanyaan)) {
            $this->data['url_back'] = base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2) . '/preview-form-survei/pertanyaan-harapan';
        } else {
            $this->data['url_back'] = base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2) . '/preview-form-survei/pertanyaan';
        }

        return view('preview_form_survei/form_saran', $this->data);
    }


    public function form_konfirmasi($id1, $id2)
    {
        $this->data = [];
        $this->data['title'] = 'Preview Form Konfirmasi';
        $this->data['profiles'] = $this->_get_data_profile($id1, $id2);

        $this->db->select('');
        $this->db->from('manage_survey');
        $this->db->where('manage_survey.slug', $this->uri->segment(2));
        $this->data['manage_survey'] = $this->db->get()->row();
        $this->data['status_saran'] = $this->data['manage_survey']->is_saran;

        if ($this->data['manage_survey']->is_saran == 1) {

            $this->data['url_back'] = base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2) . '/preview-form-survei/saran';
        } else if (in_array(3, unserialize($this->data['manage_survey']->atribut_pertanyaan_survey))) {

            $this->data['url_back'] = base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2)
                . '/preview-form-survei/pertanyaan-kualitatif';
        } else if (in_array(1, unserialize($this->data['manage_survey']->atribut_pertanyaan_survey))) {

            $this->data['url_back'] = base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2)
                . '/preview-form-survei/pertanyaan-harapan';
        } else {

            $this->data['url_back'] = base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2)
                . '/preview-form-survei/pertanyaan';
        }

        return view('preview_form_survei/form_konfirmasi', $this->data);
    }

    public function form_closing()
    {
        $this->data = [];
        $this->data['title'] = 'Preview Form Sukses';

        $this->db->select('*');
        $this->db->from('manage_survey');
        $this->db->where('manage_survey.slug', $this->uri->segment(2));
        $this->data['judul'] = $this->db->get()->row();
        $this->data['status_saran'] = $this->data['judul']->is_saran;


        return view('preview_form_survei/form_closing', $this->data);
    }



    public function _get_data_profile($id1, $id2)
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->join('users_groups', 'users.id = users_groups.user_id');
        $this->db->where('users.username', $this->session->userdata('username'));
        $data_user = $this->db->get()->row();
        $user_identity = 'drs' . $data_user->is_parent;

        $this->db->select('users.username, manage_survey.survey_name, is_question, manage_survey.slug, manage_survey.description, manage_survey.is_privacy, manage_survey.table_identity, manage_survey.id_jenis_pelayanan, manage_survey.atribut_pertanyaan_survey');
        if ($data_user->group_id == 2) {
            $this->db->from('users');
            $this->db->join('manage_survey', 'manage_survey.id_user = users.id');
        } else {
            $this->db->from('manage_survey');
            $this->db->join("supervisor_$user_identity", "manage_survey.id_berlangganan = supervisor_$user_identity.id_berlangganan");
            $this->db->join("users", "supervisor_$user_identity.id_user = users.id");
        }
        $this->db->where('users.username', $id1);
        $this->db->where('manage_survey.slug', $id2);
        $profiles = $this->db->get();

        if ($profiles->num_rows() == 0) {
            // echo 'Survey tidak ditemukan atau sudah dihapus !';
            // exit();
            show_404();
        }
        return $profiles->row();
    }
}

/* End of file PertanyaanKualitatifController.php */
/* Location: ./application/controllers/PertanyaanKualitatifController.php */
