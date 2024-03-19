<?php
defined('BASEPATH') or exit('No direct script access allowed');

class JawabanKualitatifController extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		if (!$this->ion_auth->logged_in()) {
			$this->session->set_flashdata('message_warning', 'You must be an admin to view this page');
			redirect('auth', 'refresh');
		}
		$this->load->model('PertanyaanKualitatif_model');
		$this->load->model('JawabanKualitatif_model');
	}

	public function index($id1, $id2)
	{
		$this->data = [];
		$this->data['title'] = "Rekap Jawaban Pertanyaan Kualitatif";

		$this->data['profiles'] = $this->_get_data_profile($id1, $id2);
		// $id_jenis_pelayanan = $this->data['profiles']->id_jenis_pelayanan;

		return view('jawaban_kualitatif/index', $this->data);
	}

	public function ajax_list()
	{
		$slug = $this->uri->segment(2);

		$get_identity = $this->db->get_where('manage_survey', array('slug' => "$slug"))->row();
		$table_identity = $get_identity->table_identity;

		$list = $this->PertanyaanKualitatif_model->get_datatables($table_identity);
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $value->isi_pertanyaan;
			$row[] = anchor($this->session->userdata('username') . '/' . $this->uri->segment(2) . '/jawaban-pertanyaan-kualitatif/detail/' . $value->id, 'Detail Jawaban Kualitatif <i class="fa fa-arrow-right"></i>', ['class' => 'btn btn-light-primary btn-sm font-weight-bold shadow']);


			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->PertanyaanKualitatif_model->count_all($table_identity),
			"recordsFiltered" => $this->PertanyaanKualitatif_model->count_filtered($table_identity),
			"data" => $data,
		);

		echo json_encode($output);
	}

	public function detail($id1 = NULL, $id2 = NULL)
	{
		$this->data = [];
		$this->data['title'] = "Detail Alasan";

		$this->data['profiles'] = $this->_get_data_profile($id1, $id2);

		$id_pertanyaan_kualitatif = $this->uri->segment(5);
		$slug = $this->uri->segment(2);

		$get_identity = $this->db->get_where('manage_survey', array('slug' => "$slug"))->row();
		$table_identity = $get_identity->table_identity;

		$this->data['pertanyaan'] = $this->db->get_where("pertanyaan_kualitatif_$table_identity", array('id' => $id_pertanyaan_kualitatif))->row();

		return view('jawaban_kualitatif/detail', $this->data);
	}

	public function ajax_list_detail()
	{

		$id_pertanyaan_kualitatif = $this->uri->segment(5);
		$slug = $this->uri->segment(2);

		$get_identity = $this->db->get_where('manage_survey', array('slug' => "$slug"))->row();
		$table_identity = $get_identity->table_identity;

		$list = $this->JawabanKualitatif_model->get_datatables($table_identity, $id_pertanyaan_kualitatif);
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $value->nama_lengkap;
			$row[] = $value->isi_jawaban_kualitatif;


			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->JawabanKualitatif_model->count_all($table_identity, $id_pertanyaan_kualitatif),
			"recordsFiltered" => $this->JawabanKualitatif_model->count_filtered($table_identity, $id_pertanyaan_kualitatif),
			"data" => $data,
		);

		echo json_encode($output);
	}


	public function cetak($id1, $id2)
	{
		$this->data = [];
		$this->data['title'] = "Jawaban Pertanyaan Kualitatif";

		$this->data['profiles'] = $this->_get_data_profile($id1, $id2);
		$this->data['user'] = $this->ion_auth->user()->row();

		// get tabel identity
		$this->db->select('*');
		$this->db->from('manage_survey');
		$this->db->where('slug', $id2);
		$this->data['manage_survey'] = $this->db->get()->row();
		$this->data['table_identity'] = $this->data['manage_survey']->table_identity;
		$table_identity = $this->data['table_identity'];


		$this->data['pertanyaan'] = $this->db->get('pertanyaan_kualitatif_' . $table_identity);

		$this->db->select("*");
		$this->db->from('responden_' . $table_identity);
		$this->db->join("survey_$table_identity", "responden_$table_identity.id = survey_$table_identity.id_responden");
		$this->db->join("jawaban_pertanyaan_kualitatif_$table_identity", "responden_$table_identity.id = jawaban_pertanyaan_kualitatif_$table_identity.id_responden");
		$this->db->where('is_submit', 1);
		$this->data['jawaban'] = $this->db->get();


		$this->load->library('pdfgenerator');
		$this->data['title_pdf'] = 'Rekap Pertanyaan Kualitatif';
		$file_pdf = 'Rekap Pertanyaan Kualitatif';
		$paper = 'A4';
		$orientation = "potrait";
		$html = $this->load->view('jawaban_kualitatif/cetak', $this->data, true);
		$this->pdfgenerator->generate($html, $file_pdf, $paper, $orientation);
	}


	public function download_docx($username, $slug)
	{
		$manage_survey = $this->db->get_where('manage_survey', array('slug' => "$slug"))->row();
		$table_identity = $manage_survey->table_identity;

		// $atribut_pertanyaan = unserialize($manage_survey->atribut_pertanyaan_survey);

		$user = $this->ion_auth->user()->row();
		$data_user = [
			'foto_profile' => ($user->foto_profile != '') ? $user->foto_profile : '200px.jpg',
		];

		$data_survei = [
			'nama_survei' => $manage_survey->survey_name,
			'tahun_survei' => $manage_survey->survey_year,
			'survei_dimulai' => date("d-m-Y", strtotime($manage_survey->survey_start)),
			'survei_selesai' => date("d-m-Y", strtotime($manage_survey->survey_end)),
			'nama_organisasi' => $manage_survey->organisasi,
		];


		$phpWord = new \PhpOffice\PhpWord\PhpWord();
		PhpOffice\PhpWord\Settings::setDefaultFontSize(11);
		$phpWord->addParagraphStyle('Heading2', array('alignment' => 'center'));
		$fontStyleName = 'rStyle';
		$phpWord->addFontStyle($fontStyleName, array('name' => 'Arial', 'size' => 11, 'allCaps' => true));
		$paragraphStyleName = 'pStyle';
		$phpWord->addParagraphStyle($paragraphStyleName, array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 100));

		$section = $phpWord->addSection();

		// Add header for all other pages
		$subsequent = $section->addHeader();
		$subsequent->addImage(
			base_url() . 'assets/klien/foto_profile/' . $data_user['foto_profile'],
			array(
				'positioning'        => 'relative',
				'marginTop'          => -5,
				'marginLeft'         => 0,
				'width'              => 55,
				'height'             => 55,
				'wrappingStyle'      => 'behind',
				'wrapDistanceRight'  => \PhpOffice\PhpWord\Shared\Converter::cmToPoint(),
				'wrapDistanceBottom' => \PhpOffice\PhpWord\Shared\Converter::cmToPoint(),
			)
		);
		$subsequent->addText('R E K A P I T U L A S I', array('name' => 'Arial', 'size' => 11, 'bold' => true, 'color' => 'DE2226'), array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 100));
		$subsequent->addText('Survei Kepuasan Masyarakat', array('name' => 'Arial', 'size' => 11), array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 100));
		$subsequent->addLine(['weight' => 1, 'width' => 450, 'height' => 0]);

		// Add footer
		$footer = $section->addFooter();
		$footer->addLine(['weight' => 1, 'width' => 450, 'height' => 0]);
		$footer->addText($data_survei['nama_organisasi'] . ' - ' . $data_survei['tahun_survei'], array('name' => 'Arial', 'size' => 10), array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 100));
		$footer->addPreserveText('{PAGE}', null, array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));



		// HALAMAN REKAPITULASI ALASAN
		$section->addText('Rekapitulasi Pertanyaan Kualitatif', array('bold' => true, 'size' => 18), $paragraphStyleName);
		$section->addTextBreak(2);


		//QUERY
		$pertanyaan = $this->db->get('pertanyaan_kualitatif_' . $table_identity);

		$this->db->select("*");
		$this->db->from('responden_' . $table_identity);
		$this->db->join("survey_$table_identity", "responden_$table_identity.id = survey_$table_identity.id_responden");
		$this->db->join("jawaban_pertanyaan_kualitatif_$table_identity", "responden_$table_identity.id = jawaban_pertanyaan_kualitatif_$table_identity.id_responden");
		$this->db->where('is_submit', 1);
		$jawaban = $this->db->get();



		$no = 1;
		foreach ($pertanyaan->result() as $row) {
			$table = $section->addTable('Judul Pertanyaan Unsur');
			$table->addRow();
			$table->addCell(500)->addText($no++ . '.', array('name' => 'Arial', 'size' => 11, 'valign' => 'center'));
			$table->addCell(9000)->addText(strip_tags($row->isi_pertanyaan), array('name' => 'Arial', 'size' => 11, 'valign' => 'center'));


			$fancyTableStyleName = 'Pertanyaan Unsur';
			$fancyTableStyle = array('borderSize' => 5, 'borderColor' => 'A5A5A5', 'cellMargin' => 80, 'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER);
			$fancyTableFirstRowStyle = array('bgColor' => 'A5A5A5');
			$fancyTableCellStyle = array('valign' => 'center');
			$fancyTableFontStyle = array('name' => 'Arial', 'size' => 11, 'bold' => true, 'color' => 'FFFFFF');
			$phpWord->addTableStyle($fancyTableStyleName, $fancyTableStyle, $fancyTableFirstRowStyle);
			$table = $section->addTable($fancyTableStyleName);
			$cellTableFontStyle = array('name' => 'Arial', 'size' => 11, 'valign' => 'center');

			$table->addRow();
			$table->addCell(150, $fancyTableCellStyle)->addText('No', $fancyTableFontStyle);
			$table->addCell(3000, $fancyTableCellStyle)->addText('Nama Responden', $fancyTableFontStyle);
			$table->addCell(5200, $fancyTableCellStyle)->addText('Jawaban', $fancyTableFontStyle);


			$i = 1;
			foreach ($jawaban->result() as $get) {
				if ($get->id_pertanyaan_kualitatif == $row->id) {
					$table->addRow();
					$table->addCell(150)->addText($i++, $cellTableFontStyle);
					$table->addCell(3000)->addText($get->nama_lengkap, $cellTableFontStyle);
					$table->addCell(5200)->addText($get->isi_jawaban_kualitatif, $cellTableFontStyle);
				}
			}
			$section->addTextBreak(2);
		}

		$filename = 'Rekap Pertanyaan Kualitatif';
		header('Content-Type: application/msword');
		header('Content-Disposition: attachment;filename="' . $filename . '.docx"');
		header('Cache-Control: max-age=0');
		$phpWord->save('php://output');
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

/* End of file JawabanKualitatifController.php */
/* Location: ./application/controllers/JawabanKualitatifController.php */