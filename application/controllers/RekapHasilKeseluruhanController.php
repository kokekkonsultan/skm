<?php
defined('BASEPATH') or exit('No direct script access allowed');

class RekapHasilKeseluruhanController extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		if (!$this->ion_auth->logged_in()) {
			$this->session->set_flashdata('message_warning', 'You must be an admin to view this page');
			redirect('auth', 'refresh');
		}
		$this->load->model('RekapAlasanKeseluruhan_model', 'models');
		$this->load->model('RekapSaranKeseluruhan_model');
		$this->load->model('PertanyaanUnsurSurvei_model');
	}


	public function index()
	{
		$this->data = [];
		$this->data['title'] = "Rekap Saran";
		// $this->data['profiles'] = $this->_get_data_profile($id1, $id2);

		// $parent = $this->db->query("SELECT GROUP_CONCAT(id) AS id_parent_induk FROM users WHERE id_parent_induk =" . $this->session->userdata('user_id'))->row()->id_parent_induk;
		// $manage_survey = $this->db->query("SELECT * FROM manage_survey WHERE id_user IN ($parent)")->last_row();

		// $this->data['slug'] = $manage_survey->slug;

		return view('rekap_hasil_keseluruhan/index', $this->data);
	}

	public function ajax_list()
	{
		$slug = $this->uri->segment(3);
		// $slug = 'skm-tes-2023';

		// Get Identity
		$get_identity = $this->db->get_where('manage_survey', ['slug' => "$slug"])->row();
		$table_identity = $get_identity->table_identity;

		$list = $this->PertanyaanUnsurSurvei_model->get_datatables($table_identity);
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $value->isi_pertanyaan_unsur;
			$row[] = anchor('rekap-hasil-keseluruhan/rekap-alasan/' . $value->id_pertanyaan_unsur, 'Detail Alasan <i class="fa fa-arrow-right"></i>', ['class' => 'btn btn-light-primary btn-sm font-weight-bold shadow']);

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->PertanyaanUnsurSurvei_model->count_all($table_identity),
			"recordsFiltered" => $this->PertanyaanUnsurSurvei_model->count_filtered($table_identity),
			"data" => $data,
		);

		echo json_encode($output);
	}



	public function detail_rekap_alasan()
	{
		$this->data = [];
		$this->data['title '] =  "Detail Rekap Alasan Jawaban";

		$users_parent = $this->db->query("SELECT GROUP_CONCAT(id) AS id_parent_induk FROM users WHERE id_parent_induk =" . $this->session->userdata('user_id'))->row();
		if($users_parent->id_parent_induk == null){
			$parent = 0;
		} else {
			$parent = $users_parent->id_parent_induk;
		}

		$manage_survey = $this->db->query("SELECT * FROM manage_survey WHERE id_user IN ($parent)")->last_row();


		$this->data['pertanyaan'] = $this->db->get_where("pertanyaan_unsur_pelayanan_$manage_survey->table_identity", array('id' => $this->uri->segment(3)))->row();


		return view('rekap_hasil_keseluruhan/form_detail_rekap_alasan', $this->data);
	}


	public function ajax_list_rekap_alasan()
	{

		$users_parent = $this->db->query("SELECT GROUP_CONCAT(id) AS id_parent_induk FROM users WHERE id_parent_induk =" . $this->session->userdata('user_id'))->row();
		if($users_parent->id_parent_induk == null){
			$parent = 0;
		} else {
			$parent = $users_parent->id_parent_induk;
		}


		$data_survey = [];
		foreach ($this->db->query("SELECT * FROM manage_survey WHERE id IN ($parent)")->result() as $key => $value) {

			// $data_survey[$key] = "UNION
            //                 SELECT *
            //                 FROM (SELECT *, (SELECT is_submit FROM survey_$value->table_identity WHERE survey_$value->table_identity.id = jawaban_pertanyaan_unsur_$value->table_identity.id_survey) AS is_submit
			// 				FROM jawaban_pertanyaan_unsur_$value->table_identity) jpu_$value->table_identity
            //                 WHERE is_submit = 1";

			$data_survey[$key] = "UNION
			SELECT *
			FROM (SELECT *, (SELECT is_submit FROM survey_$value->table_identity JOIN responden_$value->table_identity ON responden_$value->table_identity.id = survey_$value->table_identity.id_responden WHERE responden_$value->table_identity.id = jawaban_pertanyaan_unsur_$value->table_identity.id_responden) AS is_submit
			FROM jawaban_pertanyaan_unsur_$value->table_identity) jpu_$value->table_identity
			WHERE is_submit = 1";
		}

		

		$union_survey = implode(" ", $data_survey);

		$id_pertanyaan_unsur = $this->uri->segment(3);

		$list = $this->models->get_datatables($union_survey, $id_pertanyaan_unsur);
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $value->alasan_pilih_jawaban;

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->models->count_all($union_survey, $id_pertanyaan_unsur),
			"recordsFiltered" => $this->models->count_filtered($union_survey, $id_pertanyaan_unsur),
			"data" => $data,
		);

		echo json_encode($output);
	}


	public function cetak_alasan()
	{
		$this->data =  [];
		$this->data['title'] = "Cetak Rekap Alasan";

		$this->data['user'] = $this->ion_auth->user()->row();


		$users_parent = $this->db->query("SELECT GROUP_CONCAT(id) AS id_parent_induk FROM users WHERE id_parent_induk =" . $this->session->userdata('user_id'))->row();
		if($users_parent->id_parent_induk == null){
			$parent = 0;
		} else {
			$parent = $users_parent->id_parent_induk;
		}

		$manage_survey = $this->db->query("SELECT * FROM manage_survey WHERE id_user IN ($parent)");
		$last_manage = $manage_survey->last_row();

		$this->data['pertanyaan'] = $this->db->query("SELECT *, (SELECT nomor_unsur FROM unsur_pelayanan_$last_manage->table_identity WHERE pertanyaan_unsur_pelayanan_$last_manage->table_identity.id_unsur_pelayanan = unsur_pelayanan_$last_manage->table_identity.id) AS kode_unsur
		FROM pertanyaan_unsur_pelayanan_$last_manage->table_identity");


		$data_survey = [];
		foreach ($manage_survey->result() as $key => $value) {

			// $data_survey[$key] = "UNION
            //                 SELECT *
            //                 FROM (SELECT *, (SELECT is_submit FROM survey_$value->table_identity WHERE survey_$value->table_identity.id = jawaban_pertanyaan_unsur_$value->table_identity.id_survey) AS is_submit
			// 				FROM jawaban_pertanyaan_unsur_$value->table_identity) jpu_$value->table_identity
            //                 WHERE is_submit = 1";
			
			$data_survey[$key] = "UNION
			SELECT *
			FROM (SELECT *, (SELECT is_submit FROM survey_$value->table_identity JOIN responden_$value->table_identity ON responden_$value->table_identity.id = survey_$value->table_identity.id_responden WHERE responden_$value->table_identity.id = jawaban_pertanyaan_unsur_$value->table_identity.id_responden) AS is_submit
			FROM jawaban_pertanyaan_unsur_$value->table_identity) jpu_$value->table_identity
			WHERE is_submit = 1";
		}
		$union_survey = implode(" ", $data_survey);


		$this->data['alasan'] = $this->db->query("SELECT *
		FROM (SELECT *, 'null' AS is_submit FROM jawaban_pertanyaan_unsur $union_survey) rspdn
		WHERE alasan_pilih_jawaban != ''");
		// var_dump($this->data['alasan']->result());


		$this->load->library('pdfgenerator');
        $this->data['title_pdf'] = 'Rekap Alasan';
        $file_pdf = 'Rekap Alasan';
        $paper = 'A4';
        $orientation = "potrait";

        $html = $this->load->view('rekap_hasil_keseluruhan/cetak_alasan', $this->data, true);

        $this->pdfgenerator->generate($html, $file_pdf, $paper, $orientation);




		// return view('rekap_hasil_keseluruhan/cetak_alasan', $this->data);
	}



	public function cetak_saran()
	{
		$this->data =  [];
		$this->data['title'] = "Cetak Rekap Saran";

		$this->data['slug'] = $this->uri->segment(2);


		$users_parent = $this->db->query("SELECT GROUP_CONCAT(id) AS id_parent_induk FROM users WHERE id_parent_induk =" . $this->session->userdata('user_id'))->row();
		if($users_parent->id_parent_induk == null){
			$parent = 0;
		} else {
			$parent = $users_parent->id_parent_induk;
		}

		$data_survey = [];
		foreach ($this->db->query("SELECT * FROM manage_survey WHERE id_user IN ($parent)")->result() as $key => $value) {

			$data_survey[$key] = "UNION
                            SELECT * FROM survey_$value->table_identity WHERE is_submit = 1 && saran != ''";
		}
		$union_survey = implode(" ", $data_survey);

		$this->data['saran'] = $this->db->query("SELECT * FROM (SELECT * FROM survey $union_survey) rspdn");


		$this->load->library('pdfgenerator');
        $this->data['title_pdf'] = 'Rekap Saran';
        $file_pdf = 'Rekap Saran';
        $paper = 'A4';
        $orientation = "potrait";

        $html = $this->load->view('rekap_hasil_keseluruhan/cetak_saran', $this->data, true);

        $this->pdfgenerator->generate($html, $file_pdf, $paper, $orientation);


		// return view('rekap_hasil_keseluruhan/cetak_saran', $this->data);
	}







	public function ajax_list_rekap_saran()
	{

		$users_parent = $this->db->query("SELECT GROUP_CONCAT(id) AS id_parent_induk FROM users WHERE id_parent_induk =" . $this->session->userdata('user_id'))->row();
		if($users_parent->id_parent_induk == null){
			$parent = 0;
		} else {
			$parent = $users_parent->id_parent_induk;
		}

		$data_survey = [];
		foreach ($this->db->query("SELECT * FROM manage_survey WHERE id_user IN ($parent)")->result() as $key => $value) {

			$data_survey[$key] = "UNION
                            SELECT * FROM survey_$value->table_identity WHERE is_submit = 1 && saran != ''";
		}
		$union_survey = implode(" ", $data_survey);

		$list = $this->RekapSaranKeseluruhan_model->get_datatables($union_survey);
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $value->saran;

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->RekapSaranKeseluruhan_model->count_all($union_survey),
			"recordsFiltered" => $this->RekapSaranKeseluruhan_model->count_filtered($union_survey),
			"data" => $data,
		);

		echo json_encode($output);
	}
}

/* End of file RekapHasilPerBagianController.php */
/* Location: ./application/controllers/RekapHasilPerBagianController.php */