<?php
defined('BASEPATH') or exit('No direct script access allowed');

class RekapitulasiPertanyaanTambahanController extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		if (!$this->ion_auth->logged_in()) {
			$this->session->set_flashdata('message_warning', 'You must be an admin to view this page');
			redirect('auth', 'refresh');
		}
	}

	public function index($id1, $id2)
	{
		$this->data = [];
		$this->data['title'] = "Rekapitulasi Pertanyaan Tambahan";
		$this->data['profiles'] = $this->_get_data_profile($id1, $id2);

		$manage_survey = $this->db->get_where('manage_survey', array('slug' => $this->uri->segment(2)))->row();
		$table_identity = $manage_survey->table_identity;

		$cek_survey = $this->db->get_where("survey_$table_identity", array('is_submit', 1));
		if ($cek_survey->num_rows() == 0) {
			$this->data['pesan'] = 'survei belum dimulai atau belum ada responden !';
			return view('not_questions/index', $this->data);
		}

		$this->data['pertanyaan_tambahan'] = $this->db->query("SELECT *, (SELECT DISTINCT dengan_isian_lainnya FROM isi_pertanyaan_ganda_$table_identity WHERE isi_pertanyaan_ganda_$table_identity.id_perincian_pertanyaan_terbuka = perincian_pertanyaan_terbuka_$table_identity.id) AS is_lainnya,

		(SELECT COUNT(*) FROM responden_$table_identity
		JOIN jawaban_pertanyaan_terbuka_$table_identity ON responden_$table_identity.id =
		jawaban_pertanyaan_terbuka_$table_identity.id_responden
		JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id_responden
		WHERE survey_$table_identity.is_submit = 1 && jawaban_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka =
		perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka && jawaban_pertanyaan_terbuka_$table_identity.jawaban =
		'Lainnya') AS perolehan,
		
		(((SELECT COUNT(*) FROM responden_$table_identity
		JOIN jawaban_pertanyaan_terbuka_$table_identity ON responden_$table_identity.id =
		jawaban_pertanyaan_terbuka_$table_identity.id_responden
		JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id_responden
		WHERE survey_$table_identity.is_submit = 1 && jawaban_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka =
		perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka && jawaban_pertanyaan_terbuka_$table_identity.jawaban =
		'Lainnya') / (SELECT COUNT(*) FROM survey_$table_identity JOIN responden_$table_identity ON survey_$table_identity.id_responden = responden_$table_identity.id JOIN jawaban_pertanyaan_terbuka_$table_identity ON responden_$table_identity.id = jawaban_pertanyaan_terbuka_$table_identity.id_responden WHERE is_submit = 1 && jawaban_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka = perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka && jawaban_pertanyaan_terbuka_$table_identity.jawaban != '' )) * 100) AS persentase

		FROM pertanyaan_terbuka_$table_identity
		JOIN perincian_pertanyaan_terbuka_$table_identity ON pertanyaan_terbuka_$table_identity.id = perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka");

		//--------------------------------------------------------------------------------------------------------

		$this->data['jawaban_ganda'] = $this->db->query("SELECT *, (SELECT COUNT(*) FROM responden_$table_identity
		JOIN jawaban_pertanyaan_terbuka_$table_identity ON responden_$table_identity.id =
		jawaban_pertanyaan_terbuka_$table_identity.id_responden
		JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id_responden
		WHERE survey_$table_identity.is_submit = 1 && jawaban_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka =
		perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka && jawaban_pertanyaan_terbuka_$table_identity.jawaban =
		isi_pertanyaan_ganda_$table_identity.pertanyaan_ganda) AS perolehan,

		(((SELECT COUNT(*) FROM responden_$table_identity
		JOIN jawaban_pertanyaan_terbuka_$table_identity ON responden_$table_identity.id =
		jawaban_pertanyaan_terbuka_$table_identity.id_responden
		JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id_responden
		WHERE survey_$table_identity.is_submit = 1 && jawaban_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka =
		perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka && jawaban_pertanyaan_terbuka_$table_identity.jawaban =
		isi_pertanyaan_ganda_$table_identity.pertanyaan_ganda) / (SELECT COUNT(*) FROM survey_$table_identity JOIN responden_$table_identity ON survey_$table_identity.id_responden = responden_$table_identity.id JOIN jawaban_pertanyaan_terbuka_$table_identity ON responden_$table_identity.id = jawaban_pertanyaan_terbuka_$table_identity.id_responden WHERE is_submit = 1 && jawaban_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka = perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka && jawaban_pertanyaan_terbuka_$table_identity.jawaban != '' )) * 100) AS persentase

		FROM isi_pertanyaan_ganda_$table_identity
		JOIN perincian_pertanyaan_terbuka_$table_identity ON isi_pertanyaan_ganda_$table_identity.id_perincian_pertanyaan_terbuka
		= perincian_pertanyaan_terbuka_$table_identity.id
		WHERE perincian_pertanyaan_terbuka_$table_identity.id_jenis_pilihan_jawaban = 1");

		//--------------------------------------------------------------------------------------------------------

		$this->data['jawaban_isian'] = $this->db->query("SELECT *
		FROM jawaban_pertanyaan_terbuka_$table_identity
		JOIN pertanyaan_terbuka_$table_identity ON pertanyaan_terbuka_$table_identity.id = jawaban_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka
		JOIN perincian_pertanyaan_terbuka_$table_identity ON pertanyaan_terbuka_$table_identity.id = perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka
		JOIN responden_$table_identity ON jawaban_pertanyaan_terbuka_$table_identity.id_responden = responden_$table_identity.id
		JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id_responden
		WHERE id_jenis_pilihan_jawaban = 2 && survey_$table_identity.is_submit = 1 && jawaban_pertanyaan_terbuka_$table_identity.jawaban != ''");



		$this->data['detail_lainnya'] = $this->db->query("SELECT *
		FROM jawaban_pertanyaan_terbuka_$table_identity
		JOIN responden_$table_identity ON jawaban_pertanyaan_terbuka_$table_identity.id_responden = responden_$table_identity.id
		JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id_responden
		WHERE survey_$table_identity.is_submit = 1 && jawaban_pertanyaan_terbuka_$table_identity.jawaban = 'Lainnya'");



		return view('rekapitulasi_pertanyaan_tambahan/index', $this->data);
	}


	public function download_docx($username, $slug)
	{


		$manage_survey = $this->db->get_where('manage_survey', array('slug' => "$slug"))->row();
		$table_identity = $manage_survey->table_identity;

		$atribut_pertanyaan = unserialize($manage_survey->atribut_pertanyaan_survey);

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



		// HALAMAN REKAPITULASI PERTANYAAN TAMBAHAN
		if (in_array(2, $atribut_pertanyaan)) {
			$section->addText('Rekapitulasi Pertanyaan Tambahan', array('bold' => true, 'size' => 18), $paragraphStyleName);
			$section->addTextBreak(2);

			$pertanyaan_tambahan = $this->db->query("SELECT *, (SELECT DISTINCT dengan_isian_lainnya FROM isi_pertanyaan_ganda_$table_identity WHERE isi_pertanyaan_ganda_$table_identity.id_perincian_pertanyaan_terbuka = perincian_pertanyaan_terbuka_$table_identity.id) AS is_lainnya,
		(SELECT COUNT(*) FROM responden_$table_identity
		JOIN jawaban_pertanyaan_terbuka_$table_identity ON responden_$table_identity.id =
		jawaban_pertanyaan_terbuka_$table_identity.id_responden
		JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id_responden
		WHERE survey_$table_identity.is_submit = 1 && jawaban_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka =
		perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka && jawaban_pertanyaan_terbuka_$table_identity.jawaban =
		'Lainnya') AS perolehan,
		(((SELECT COUNT(*) FROM responden_$table_identity
		JOIN jawaban_pertanyaan_terbuka_$table_identity ON responden_$table_identity.id =
		jawaban_pertanyaan_terbuka_$table_identity.id_responden
		JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id_responden
		WHERE survey_$table_identity.is_submit = 1 && jawaban_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka =
		perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka && jawaban_pertanyaan_terbuka_$table_identity.jawaban =
		'Lainnya') / (SELECT COUNT(*) FROM survey_$table_identity JOIN responden_$table_identity ON survey_$table_identity.id_responden = responden_$table_identity.id JOIN jawaban_pertanyaan_terbuka_$table_identity ON responden_$table_identity.id = jawaban_pertanyaan_terbuka_$table_identity.id_responden WHERE is_submit = 1 && jawaban_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka = perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka && jawaban_pertanyaan_terbuka_$table_identity.jawaban != '' )) * 100) AS persentase

		FROM pertanyaan_terbuka_$table_identity
		JOIN perincian_pertanyaan_terbuka_$table_identity ON pertanyaan_terbuka_$table_identity.id = perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka");

			$jawaban_ganda = $this->db->query("SELECT *, (SELECT COUNT(*) FROM responden_$table_identity
        JOIN jawaban_pertanyaan_terbuka_$table_identity ON responden_$table_identity.id =
        jawaban_pertanyaan_terbuka_$table_identity.id_responden
        JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id_responden
        WHERE survey_$table_identity.is_submit = 1 && jawaban_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka =
        perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka && jawaban_pertanyaan_terbuka_$table_identity.jawaban =
        isi_pertanyaan_ganda_$table_identity.pertanyaan_ganda) AS perolehan,
        (((SELECT COUNT(*) FROM responden_$table_identity
        JOIN jawaban_pertanyaan_terbuka_$table_identity ON responden_$table_identity.id =
        jawaban_pertanyaan_terbuka_$table_identity.id_responden
        JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id_responden
        WHERE survey_$table_identity.is_submit = 1 && jawaban_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka =
        perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka && jawaban_pertanyaan_terbuka_$table_identity.jawaban =
        isi_pertanyaan_ganda_$table_identity.pertanyaan_ganda) / (SELECT COUNT(*) FROM survey_$table_identity JOIN responden_$table_identity ON survey_$table_identity.id_responden = responden_$table_identity.id JOIN jawaban_pertanyaan_terbuka_$table_identity ON responden_$table_identity.id = jawaban_pertanyaan_terbuka_$table_identity.id_responden WHERE is_submit = 1 && jawaban_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka = perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka && jawaban_pertanyaan_terbuka_$table_identity.jawaban != '' )) * 100) AS persentase
        FROM isi_pertanyaan_ganda_$table_identity
        JOIN perincian_pertanyaan_terbuka_$table_identity ON isi_pertanyaan_ganda_$table_identity.id_perincian_pertanyaan_terbuka
        = perincian_pertanyaan_terbuka_$table_identity.id
        WHERE perincian_pertanyaan_terbuka_$table_identity.id_jenis_pilihan_jawaban = 1");

			$jawaban_isian = $this->db->query("SELECT *
        FROM jawaban_pertanyaan_terbuka_$table_identity
        JOIN pertanyaan_terbuka_$table_identity ON pertanyaan_terbuka_$table_identity.id = jawaban_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka
        JOIN perincian_pertanyaan_terbuka_$table_identity ON pertanyaan_terbuka_$table_identity.id = perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka
        JOIN responden_$table_identity ON jawaban_pertanyaan_terbuka_$table_identity.id_responden = responden_$table_identity.id
        JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id_responden
        WHERE id_jenis_pilihan_jawaban = 2 && survey_$table_identity.is_submit = 1");


			foreach ($pertanyaan_tambahan->result() as $row) {
				$table = $section->addTable('Judul Pertanyaan Tambahan');
				$table->addRow();
				$table->addCell(500)->addText($row->nomor_pertanyaan_terbuka . '.', array('name' => 'Arial', 'size' => 11, 'valign' => 'center'));
				$table->addCell(9000)->addText(strip_tags($row->isi_pertanyaan_terbuka), array('name' => 'Arial', 'size' => 11, 'valign' => 'center'));



				if ($row->id_jenis_pilihan_jawaban == 1) {

					$fancyTableStyleName = 'Pertanyaan Tambahan 1';
					$fancyTableStyle = array('borderSize' => 5, 'borderColor' => 'A5A5A5', 'cellMargin' => 80, 'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER);
					$fancyTableFirstRowStyle = array('bgColor' => 'A5A5A5');
					$fancyTableCellStyle = array('valign' => 'center');
					$fancyTableCellBtlrStyle = array('valign' => 'center', 'textDirection' => \PhpOffice\PhpWord\Style\Cell::TEXT_DIR_BTLR);
					$fancyTableFontStyle = array('name' => 'Arial', 'size' => 11, 'bold' => true, 'color' => 'FFFFFF');
					$phpWord->addTableStyle($fancyTableStyleName, $fancyTableStyle, $fancyTableFirstRowStyle);
					$table = $section->addTable($fancyTableStyleName);
					$cellTableFontStyle = array('name' => 'Arial', 'size' => 11, 'valign' => 'center');

					$table->addRow();
					$table->addCell(150, $fancyTableCellStyle)->addText('No', $fancyTableFontStyle);
					$table->addCell(3000, $fancyTableCellStyle)->addText('Kelompok', $fancyTableFontStyle);
					$table->addCell(2500, $fancyTableCellStyle)->addText('Jumlah', $fancyTableFontStyle);
					$table->addCell(2500, $fancyTableCellStyle)->addText('Persentase', $fancyTableFontStyle);

					$nt = 1;
					foreach ($jawaban_ganda->result() as $value) {
						if ($value->id_pertanyaan_terbuka == $row->id_pertanyaan_terbuka) {
							$table->addRow();
							$table->addCell(150)->addText($nt++, $cellTableFontStyle);
							$table->addCell(3000)->addText($value->pertanyaan_ganda, $cellTableFontStyle);
							$table->addCell(2500)->addText($value->perolehan, $cellTableFontStyle);
							$table->addCell(2500)->addText(ROUND($value->persentase, 2) . '%', $cellTableFontStyle);
						}
					}
					if ($row->is_lainnya == 1) {
						$table->addRow();
						$table->addCell(150)->addText($nt++, $cellTableFontStyle);
						$table->addCell(3000)->addText('Lainnya', $cellTableFontStyle);
						$table->addCell(2500)->addText($row->perolehan, $cellTableFontStyle);
						$table->addCell(2500)->addText(ROUND($row->persentase, 2) . '%', $cellTableFontStyle);
					}
				} else {
					$fancyTableStyleName = 'Pertanyaan Tambahan 2';
					$fancyTableStyle = array('borderSize' => 5, 'borderColor' => 'A5A5A5', 'cellMargin' => 80, 'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER);
					$fancyTableFirstRowStyle = array('bgColor' => 'A5A5A5');
					$fancyTableCellStyle = array('valign' => 'center');
					$fancyTableFontStyle = array('name' => 'Arial', 'size' => 11, 'bold' => true, 'color' => 'FFFFFF');
					$phpWord->addTableStyle($fancyTableStyleName, $fancyTableStyle, $fancyTableFirstRowStyle);
					$table = $section->addTable($fancyTableStyleName);
					$cellTableFontStyle = array('name' => 'Arial', 'size' => 11, 'valign' => 'center');

					$table->addRow();
					$table->addCell(150, $fancyTableCellStyle)->addText('No', $fancyTableFontStyle);
					$table->addCell(8200, $fancyTableCellStyle)->addText('Jawaban', $fancyTableFontStyle);


					$i = 1;
					foreach ($jawaban_isian->result() as $get) {
						if ($get->id_pertanyaan_terbuka == $row->id_pertanyaan_terbuka) {
							$table->addRow();
							$table->addCell(150)->addText($i++, $cellTableFontStyle);
							$table->addCell(8200)->addText($get->jawaban, $cellTableFontStyle);
						}
					}
				}
				$section->addTextBreak();
			}
		}

		$filename = 'Rekap Pertanyaan Tambahan';
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
			show_404();
		}
		return $profiles->row();
	}
}

/* End of file RekapitulasiPertanyaanTambahanController.php */
/* Location: ./application/controllers/RekapitulasiPertanyaanTambahanController.php */