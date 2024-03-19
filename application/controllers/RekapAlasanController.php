<?php
defined('BASEPATH') or exit('No direct script access allowed');

require 'application/core/Klien_Controller.php';
use application\core\Klien_Controller;

class RekapAlasanController extends CI_Controller
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
        $this->load->model('PertanyaanUnsurSurvei_model', 'models');
        $this->load->model('RekapAlasan_model');
    }

    public function index($id1, $id2)
    {
        $this->data = [];
        $this->data['title'] = "Rekap Alasan Jawaban";
        $profiles = new Klien_Controller();
        $this->data['profiles'] = $profiles->_get_data_profile($id1, $id2);

        $this->data['slug'] = $this->uri->segment(2);
        return view('rekap_alasan/index', $this->data);
    }

    public function ajax_list()
    {
        $slug = $this->uri->segment(2);

        // Get Identity
        $get_identity = $this->db->get_where('manage_survey', ['slug' => "$slug"])->row();
        $table_identity = $get_identity->table_identity;

        $list = $this->models->get_datatables($table_identity);
        $data = array();
        $no = $_POST['start'];

        foreach ($list as $value) {

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $value->isi_pertanyaan;
            $row[] = anchor($this->session->userdata('username') . '/' . $this->uri->segment(2) . '/rekap-alasan/detail/' . $value->id, 'Detail Alasan <i class="fa fa-arrow-right"></i>', ['class' => 'btn btn-light-primary btn-sm font-weight-bold shadow']);
            // $row[] = '<a class="btn btn-light-primary btn-sm font-weight-bold shadow" href="javascript:void(0)" title="Hapus ' . $value->id . '" onclick="delete_data(' . "'" . $value->id . "'" . ')">Delete</a>';

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->models->count_all($table_identity),
            "recordsFiltered" => $this->models->count_filtered($table_identity),
            "data" => $data,
        );

        echo json_encode($output);
    }

    public function detail()
    {
        $this->data = [];
        $this->data['title'] = "Detail Alasan";

        $slug = $this->uri->segment(2);
        $get_identity = $this->db->get_where('manage_survey', ['slug' => "$slug"])->row();

        $this->data['pertanyaan'] = $this->db->get_where("pertanyaan_unsur_pelayanan_$get_identity->table_identity", array('id' => $this->uri->segment(5)))->row();

        return view('rekap_alasan/detail', $this->data);
    }

    public function ajax_list_detail()
    {
        $slug = $this->uri->segment(2);

        $get_identity = $this->db->get_where('manage_survey', ['slug' => "$slug"])->row();
        $table_identity = $get_identity->table_identity;

        $id_pertanyaan_unsur = $this->uri->segment(5);

        $list = $this->RekapAlasan_model->get_datatables($table_identity, $id_pertanyaan_unsur);
        $data = array();
        $no = $_POST['start'];

        foreach ($list as $value) {

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $value->nama_lengkap;
            $row[] = $value->bobot;
            $row[] = $value->alasan_pilih_jawaban;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->RekapAlasan_model->count_all($table_identity, $id_pertanyaan_unsur),
            "recordsFiltered" => $this->RekapAlasan_model->count_filtered($table_identity, $id_pertanyaan_unsur),
            "data" => $data,
        );

        echo json_encode($output);
    }

    public function cetak_pdf($id1, $id2)
	{
		$this->data = [];
		$this->data['title'] = "Rekap Alasan";

		$this->data['profiles'] = $this->_get_data_profile($id1, $id2);
		$this->data['user'] = $this->ion_auth->user()->row();

		// get tabel identity
		$this->db->select('*');
		$this->db->from('manage_survey');
		$this->db->where('slug', $id2);
		$this->data['manage_survey'] = $this->db->get()->row();
		$this->data['table_identity'] = $this->data['manage_survey']->table_identity;
		$table_identity = $this->data['table_identity'];

		$this->data['pertanyaan'] = $this->db->get('pertanyaan_unsur_pelayanan_' . $table_identity);

		$this->data['alasan'] = $this->db->query("SELECT *, (SELECT nama_kategori_unsur_pelayanan FROM kategori_unsur_pelayanan_$table_identity WHERE nomor_kategori_unsur_pelayanan = jawaban_pertanyaan_unsur_$table_identity.skor_jawaban && id_pertanyaan_unsur = jawaban_pertanyaan_unsur_$table_identity.id_pertanyaan_unsur) AS bobot
		FROM responden_$table_identity
		JOIN survey_$table_identity ON  responden_$table_identity.id = survey_$table_identity.id_responden
		JOIN jawaban_pertanyaan_unsur_$table_identity ON responden_$table_identity.id = jawaban_pertanyaan_unsur_$table_identity.id_responden
		WHERE is_submit = 1 && alasan_pilih_jawaban != '' && jawaban_pertanyaan_unsur_$table_identity.is_active = 1 && skor_jawaban IN (1,2)")->result();


		$this->load->library('pdfgenerator');
		$this->data['title_pdf'] = 'Rekap Alasan';
		$file_pdf = 'Rekap Alasan';
		$paper = 'A4';
		$orientation = "potrait";
		$html = $this->load->view('rekap_alasan/cetak_pdf', $this->data, true);
		$this->pdfgenerator->generate($html, $file_pdf, $paper, $orientation);
	}

    public function cetak_pdf2()
    {

        $this->data = [];
        $this->data['title'] = "Rekap Alasan";

        $this->data['user'] = $this->ion_auth->user()->row();

        // get tabel identity
        $this->db->select('*');
        $this->db->from('manage_survey');
        $this->db->where('slug', $this->uri->segment(2));
        $this->data['manage_survey'] = $this->db->get()->row();
        $table_identity =  $this->data['manage_survey']->table_identity;

        $this->data['pertanyaan'] = $this->db->get('pertanyaan_unsur_pelayanan_' . $table_identity);

        $this->data['alasan'] = $this->db->query("SELECT *, (SELECT nama_jawaban FROM nilai_unsur_pelayanan_$table_identity WHERE nilai_jawaban = jawaban_pertanyaan_unsur_$table_identity.skor_jawaban && id_pertanyaan_unsur = jawaban_pertanyaan_unsur_$table_identity.id_pertanyaan_unsur) AS bobot
        FROM survey_$table_identity
        JOIN responden_$table_identity ON survey_$table_identity.id_responden = responden_$table_identity.id
        JOIN jawaban_pertanyaan_unsur_$table_identity ON survey_$table_identity.id = jawaban_pertanyaan_unsur_$table_identity.id_survey
        WHERE is_submit = 1 && alasan_pilih_jawaban != ''
        ORDER BY jawaban_pertanyaan_unsur_$table_identity.skor_jawaban ASC
        LIMIT 600
        ")->result();

        $this->load->library('pdfgenerator');
        $this->data['title_pdf'] = 'Rekap Alasan -' . $this->data['manage_survey']->survey_name;
        $file_pdf = 'Rekap Alasan -' . $this->data['manage_survey']->survey_name;
        $paper = 'A4';
        $orientation = "potrait";

        $html = $this->load->view('rekap_alasan/cetak_pdf', $this->data, true);

        $this->pdfgenerator->generate($html, $file_pdf, $paper, $orientation);
    }

    public function _get_data_profile($id1, $id2)
	{
		$this->db->select('*');
		$this->db->from('users');
		$this->db->join('users_groups', 'users.id = users_groups.user_id');
		$this->db->where('users.username', $id1);
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
