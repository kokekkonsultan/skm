<?php
defined('BASEPATH') or exit('No direct script access allowed');

class RekapPertanyaanHarapanController extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		if (!$this->ion_auth->logged_in()) {
			$this->session->set_flashdata('message_warning', 'You must be an admin to view this page');
			redirect('auth', 'refresh');
		}
		$this->load->model('PertanyaanUnsurSurvei_model');
		$this->load->model('RekapPertanyaanHarapan_model');
		$this->load->library('form_validation');
	}

	public function index($id1, $id2)
	{
		$this->data = [];
		$this->data['title'] = "Rekap Pertanyaan Harapan";

		$this->data['profiles'] = $this->_get_data_profile($id1, $id2);

		return view('rekap_pertanyaan_harapan/index', $this->data);
	}

	public function ajax_list()
	{
		$slug = $this->uri->segment(2);

		$get_identity = $this->db->get_where('manage_survey', array('slug' => "$slug"))->row();
		$table_identity = $get_identity->table_identity;

		$list = $this->PertanyaanUnsurSurvei_model->get_datatables($table_identity);
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {


			$no++;
			$row = array();
			$row[] = $no;
			$row[] = '<b>H' . $value->nomor_harapan . '. ' . $value->nama_unsur_pelayanan . '</b><br>' . $value->isi_pertanyaan_unsur;

			$row[] = anchor($this->session->userdata('username') . '/' . $this->uri->segment(2) . '/rekap-pertanyaan-harapan/' . $value->id_pertanyaan_unsur, 'Detail <i class="fa fa-arrow-right"></i>', ['class' => 'btn btn-light-primary btn-sm font-weight-bold shadow']);


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

	public function detail($id1 = NULL, $id2 = NULL)
	{
		$this->data = [];
		$this->data['title'] = "Detail Jawaban Pertanyaan Harapan";

		$this->data['profiles'] = $this->_get_data_profile($id1, $id2);

		$get_identity = $this->db->get_where('manage_survey', array('slug' => "$id2"))->row();
		$table_identity = $get_identity->table_identity;

		$this->db->select("*, SUBSTR(nomor_unsur,2) AS nomor_harapan");
		$this->db->from('pertanyaan_unsur_pelayanan_' . $table_identity);
		$this->db->join("unsur_pelayanan_$table_identity", "pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan = unsur_pelayanan_$table_identity.id");
		$this->db->where("pertanyaan_unsur_pelayanan_$table_identity.id", $this->uri->segment(4));
		$this->data['pertanyaan'] = $this->db->get()->row();
		// var_dump($this->data['pertanyaan']);

		return view('rekap_pertanyaan_harapan/detail', $this->data);
	}

	public function ajax_list_detail()
	{
		$slug = $this->uri->segment(2);
		$id_pertanyaan_unsur = $this->uri->segment(5);

		$get_identity = $this->db->get_where('manage_survey', array('slug' => "$slug"))->row();
		$table_identity = $get_identity->table_identity;

		$list = $this->RekapPertanyaanHarapan_model->get_datatables($table_identity, $id_pertanyaan_unsur);
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $value->nama_responden;
			$row[] = $value->nama_tingkat_kepentingan;


			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->RekapPertanyaanHarapan_model->count_all($table_identity, $id_pertanyaan_unsur),
			"recordsFiltered" => $this->RekapPertanyaanHarapan_model->count_filtered($table_identity, $id_pertanyaan_unsur),
			"data" => $data,
		);

		echo json_encode($output);
	}

	public function cetak($id1, $id2)
	{
		$this->data = [];
		$this->data['title'] = "Rekap Jawaban Pertanyaan Harapan";

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

		$this->db->select("*, (SELECT nama_tingkat_kepentingan FROM nilai_tingkat_kepentingan_$table_identity WHERE id_pertanyaan_unsur_pelayanan = jawaban_pertanyaan_harapan_$table_identity.id_pertanyaan_unsur && nomor_tingkat_kepentingan = jawaban_pertanyaan_harapan_$table_identity.skor_jawaban) AS nama_tingkat_kepentingan, (SELECT nama_lengkap FROM responden_$table_identity WHERE responden_$table_identity.id = survey_$table_identity.id_responden) AS nama_responden, survey_$table_identity.id AS id_survey");
		$this->db->from("jawaban_pertanyaan_harapan_$table_identity");
		$this->db->join("survey_$table_identity", "jawaban_pertanyaan_harapan_$table_identity.id_responden = survey_$table_identity.id_responden");
		$this->db->where('is_submit', 1);
		$this->data['jawaban_pertanyaan_harapan'] = $this->db->get()->result();


		$this->load->library('pdfgenerator');
		$this->data['title_pdf'] = 'Rekap Jawaban Pertanyaan Harapan';
		$file_pdf = 'Rekap Jawaban Pertanyaan Harapan';
		$paper = 'A4';
		$orientation = "potrait";

		$html = $this->load->view('rekap_pertanyaan_harapan/cetak', $this->data, true);
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
		$section->addText('Rekapitulasi Pertanyaan Harapan', array('bold' => true, 'size' => 18), $paragraphStyleName);
		$section->addTextBreak(2);


		$pertanyaan = $this->db->query("SELECT *, (SELECT nomor_unsur FROM unsur_pelayanan_$table_identity WHERE id_unsur_pelayanan = unsur_pelayanan_$table_identity.id) AS nomor_unsur FROM pertanyaan_unsur_pelayanan_$table_identity");

		$this->db->select("*, (SELECT nama_tingkat_kepentingan FROM nilai_tingkat_kepentingan_$table_identity WHERE id_pertanyaan_unsur_pelayanan = jawaban_pertanyaan_harapan_$table_identity.id_pertanyaan_unsur && nomor_tingkat_kepentingan = jawaban_pertanyaan_harapan_$table_identity.skor_jawaban) AS nama_tingkat_kepentingan, (SELECT nama_lengkap FROM responden_$table_identity WHERE responden_$table_identity.id = survey_$table_identity.id_responden) AS nama_responden, survey_$table_identity.id AS id_survey");
		$this->db->from("jawaban_pertanyaan_harapan_$table_identity");
		$this->db->join("survey_$table_identity", "jawaban_pertanyaan_harapan_$table_identity.id_responden = survey_$table_identity.id_responden");
		$this->db->where('is_submit', 1);
		$jawaban_pertanyaan_harapan = $this->db->get();

		$no = 1;
		foreach ($pertanyaan->result() as $row) {
			$table = $section->addTable('Judul Pertanyaan Unsur');
			$table->addRow();
			$table->addCell(500)->addText($no++ . '.', array('name' => 'Arial', 'size' => 11, 'valign' => 'center'));
			$table->addCell(9000)->addText(strip_tags($row->isi_pertanyaan_unsur), array('name' => 'Arial', 'size' => 11, 'valign' => 'center'));


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
			foreach ($jawaban_pertanyaan_harapan->result() as $get) {
				if ($get->id_pertanyaan_unsur == $row->id) {
					$table->addRow();
					$table->addCell(150)->addText($i++, $cellTableFontStyle);
					$table->addCell(3000)->addText($get->nama_responden, $cellTableFontStyle);
					$table->addCell(5200)->addText($get->nama_tingkat_kepentingan, $cellTableFontStyle);
				}
			}
			$section->addTextBreak(2);
		}

		$filename = 'Rekap Pertanyaan Harapan';
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

/* End of file AlasanController.php */
/* Location: ./application/controllers/AlasanController.php */