<?php
defined('BASEPATH') or exit('No direct script access allowed');

class InovasiSaranController extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		if (!$this->ion_auth->logged_in()) {
			$this->session->set_flashdata('message_warning', 'You must be an admin to view this page');
			redirect('auth', 'refresh');
		}

		$this->load->model('InovasiSaran_model');
		$this->load->library('form_validation');
	}

	public function index($id1 = NULL, $id2 = NULL)
	{
		$this->data = [];
		$this->data['title'] = "Rekapitulasi Opini Responden";

		$this->data['profiles'] = $this->_get_data_profile($id1, $id2);

		$get_identity = $this->db->get_where('manage_survey', array('slug' => "$id2"))->row();
		$table_identity = $get_identity->table_identity;

		$this->data['survey'] = $this->db->get('survey_' . $table_identity);

		$this->form_validation->set_rules('is_active', 'Status', 'required');

		if ($this->form_validation->run() == FALSE) {
			return view('inovasi_saran/index', $this->data);
		} else {

			$object = [
				'is_active' => $this->input->post('is_active')
			];

			$this->db->where('id_responden', $this->input->post('id_responden'));
			$this->db->update('survey_' . $table_identity, $object);
		}
		$this->session->set_flashdata('message_success', 'Berhasil menambah data');
		redirect(base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2) . '/inovasi-dan-saran', 'refresh');
	}

	public function ajax_list()
	{

		$slug = $this->uri->segment(2);

		$get_identity = $this->db->get_where('manage_survey', array('slug' => "$slug"))->row();
		$table_identity = $get_identity->table_identity;

		$list = $this->InovasiSaran_model->get_datatables($table_identity);
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			if ($value->is_active == 1) {
				$status = '<span class="badge badge-primary">Di Tampilkan</span>';
			} else {
				$status = '<span class="badge badge-danger">Tidak di Tampilkan</span>';
			}

			$no++;
			$row = array();

			if ($value->nama_lengkap != '') {
				$responden = $value->nama_lengkap;
			} else {
				$responden = 'Responden '.$no;
			}

			$row[] = $no;
			//$row[] = $value->nama_lengkap;
			$row[] = $responden;
			$row[] = $value->saran;
			$row[] = $status;
			$row[] = '<a class="btn btn-light-primary btn-sm font-weight-bold shadow" data-toggle="modal" data-target="#detail' . $value->id_responden . ' "><i class="fa fa-edit"></i> Edit Status</a>';


			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->InovasiSaran_model->count_all($table_identity),
			"recordsFiltered" => $this->InovasiSaran_model->count_filtered($table_identity),
			"data" => $data,
		);

		echo json_encode($output);
	}

	public function cetak($id1, $id2)
	{

		$this->data = [];
		$this->data['title'] = "Report Opini Responden";

		// $this->data['profiles'] = $this->_get_data_profile($id1, $id2);
		$this->data['user'] = $this->ion_auth->user()->row();

		// get tabel identity
		$this->db->select('*');
		$this->db->from('manage_survey');
		$this->db->where('slug', $id2);
		$this->data['manage_survey'] = $this->db->get()->row();
		$this->data['table_identity'] = $this->data['manage_survey']->table_identity;
		$table_identity = $this->data['table_identity'];

		$this->db->select('*');
		$this->db->from('responden_' . $table_identity);
		$this->db->join("survey_$table_identity", "responden_$table_identity.id = survey_$table_identity.id_responden");
		$this->db->where("is_submit", 1);
		$this->db->where("saran != ''");
		$this->db->where("is_active = 1");
		$this->data['saran'] = $this->db->get();

		// panggil library yang kita buat sebelumnya yang bernama pdfgenerator
		$this->load->library('pdfgenerator');
		$this->data['title_pdf'] = 'Report Opini Responden';
		$file_pdf = 'Report Opini Responden';
		$paper = 'A4';
		$orientation = "potrait";
		$html = $this->load->view('inovasi_saran/cetak', $this->data, true);
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
		$section->addText('Rekapitulasi Saran Responden', array('bold' => true, 'size' => 18), $paragraphStyleName);
		$section->addTextBreak(2);


		//QUERY
		$this->db->select('*');
		$this->db->from('responden_' . $table_identity);
		$this->db->join("survey_$table_identity", "responden_$table_identity.id = survey_$table_identity.id_responden");
		$this->db->where("is_submit", 1);
		$this->db->where("saran != '' ");
		$this->db->where("is_active = 1");
		$saran = $this->db->get();


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
		$table->addCell(2000, $fancyTableCellStyle)->addText('Responden', $fancyTableFontStyle);
		$table->addCell(6200, $fancyTableCellStyle)->addText('Saran', $fancyTableFontStyle);


		$i = 1;
		$r = 1;
		foreach ($saran->result() as $get) {

			$table->addRow();
			$table->addCell(150)->addText($i++, $cellTableFontStyle);
			//$table->addCell(3000)->addText($get->nama_lengkap, $cellTableFontStyle);
			$table->addCell(2000)->addText('Responden '.$r++, $cellTableFontStyle);
			$table->addCell(6200)->addText($get->saran, $cellTableFontStyle);
		}
		$section->addTextBreak(2);

		$filename = 'Rekap Saran Responden';
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
		// if ($data_user->group_id == 2) {
			$this->db->from('users');
			$this->db->join('manage_survey', 'manage_survey.id_user = users.id');
		// } else {
		// 	$this->db->from('manage_survey');
		// 	$this->db->join("supervisor_$user_identity", "manage_survey.id_berlangganan = supervisor_$user_identity.id_berlangganan");
		// 	$this->db->join("users", "supervisor_$user_identity.id_user = users.id");
		// }
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

/* End of file InovasiSaranController.php */
/* Location: ./application/controllers/InovasiSaranController.php */