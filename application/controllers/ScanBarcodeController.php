<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ScanBarcodeController extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->library('Pdf');

		$this->load->helper('form');
	}

	public function index($id1, $id2)
	{
		$this->data = [];
		$this->data['title'] = "Scan Barcode";
		$this->data['profiles'] = $this->_get_data_profile($id1, $id2);

		if ($this->data['profiles']->is_question == 1) {
			$this->data['pesan'] = 'Link survei belum dikonfirmasi. Silahkan Konfirmasi Link Survei terlebih dahulu!';
			return view('not_questions/index', $this->data);
		}

		return view("scan_barcode/index", $this->data);
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

	public function process()
	{
		$array_bg = ['light', 'dark'];
		$background = $this->input->get("bg");

		// Lakukan pengecekan
		if (!in_array($background, $array_bg)) {
			echo 'Link yang anda request tidak ada !';
			exit();
		}


		$slug = $this->uri->segment(2);
		$manage_survey = $this->db->get_where('manage_survey', array('slug' => "$slug"))->row();

		$user = $this->db->get_where('users', array('id' => $manage_survey->id_user))->row();

		if ($background == "light") {
			$pdf = new ScanBarcodePdfLight(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		}

		if ($background == "dark") {
			$pdf = new ScanBarcodePdfDark(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		}

		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('E-SKM');
		$pdf->SetTitle('Scan Barcode');
		$pdf->SetSubject('E-SKM');
		$pdf->SetKeywords('E-SKM');

		$pdf->setPrintHeader(true);
		$pdf->setPrintFooter(true);
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$pdf->SetDisplayMode('fullpage', 'SinglePage', 'UseNone');

		$pdf->AddPage('P', 'mm', 'A4');
		$pdf->setPage(1, true);

		$pdf->SetDrawColor(0, 0, 0);

		$pdf->SetFillColor(255, 255, 255);
		$pdf->SetTextColor(0, 0, 0);


		// if ($user->foto_profile == NULL) {
		// 	$pdf->Image(base_url() . 'assets/klien/foto_profile/200px.jpg', 10, 6, 19, 20, 'JPG', base_url() . 'survei/' . $this->uri->segment(2), '', true, 150, '', false, false, 0, false, false, false);
		// } else {
		// 	$pdf->Image(base_url() . 'assets/klien/foto_profile/' . $user->foto_profile, 10, 6, 19, 20, '', base_url() . 'survei/' . $this->uri->segment(2), '', true, 150, '', false, false, 0, false, false, false);
		// };

		if ($user->foto_profile == NULL) {
			$img = base_url() . 'assets/klien/foto_profile/200px.jpg';
		} else {
			$img = base_url() . 'assets/klien/foto_profile/' . $user->foto_profile;
		};

		$pdf->SetTextColor(52, 50, 123);
		$pdf->SetFont('helvetica', 'B', 14);
		$pdf->Ln(-3);
		// $pdf->MultiCell(0, 10, $manage_survey->organisasi, 0, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
		// $pdf->Ln(7);
		// $pdf->MultiCell(0, 10, $user->company, 0, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');

		$html = '
<table>
<tr>
	<td width="110px"><img src="' . $img . '" border="0" height="86" width="86" align="top" /></td>
	<td width="600px" valign="middle" scope="col">' . $manage_survey->organisasi . '</td>
</tr>
</table>
		';
		$pdf->writeHTML($html, true, false, true, false, '');

		$pdf->Ln(11);

		$pdf->SetFont('helvetica', '', 10);
		$pdf->SetY(30);

		$pdf->setLeftMargin(25);
		$pdf->setRightMargin(25);

		$pdf->SetFillColor(255, 255, 255);
		$pdf->SetTextColor(0, 0, 0);


		if ($background == "light") {
			$pdf->SetTextColor(000);
			$pdf->Text(76, 225, 'Atau kunjungi link survei dibawah ini');

			$pdf->SetTextColor(000);
			$pdf->SetFont('helvetica', 'B', 11);
			$pdf->Ln(10);
			$pdf->MultiCell(0, 10, base_url() . 'survei/' . $this->uri->segment(2), 0, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
		}

		if ($background == "dark") {
			$pdf->SetTextColor(255);
			$pdf->Text(76, 225, 'Atau kunjungi link survei dibawah ini');

			$pdf->SetTextColor(255);
			$pdf->SetFont('helvetica', 'B', 11);
			$pdf->Ln(10);
			$pdf->MultiCell(0, 10, base_url() . 'survei/' . $this->uri->segment(2), 0, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
		}



		// new style
		$style = array(
			'border' => false,
			'padding' => 0,
			'fgcolor' => array(0, 0, 0),
			'bgcolor' => false
		);

		$pdf->write2DBarcode(base_url() . 'survei/' . $this->uri->segment(2), 'QRCODE,H', 70, 122, 70, 70, $style, 'N');


		$pdf->lastPage();

		$pdf->Output('Test' . '.pdf', 'I');
	}

	public function create_qrcode($id1, $id2)
	{
		// print_r($_POST);
		$this->data = [];
		$this->data['title'] = 'QR Code Generator - PT. KOKEK';


		$this->load->library('form_validation');

		$this->form_validation->set_rules('link', 'Link', 'required');
		$this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');

		if ($this->form_validation->run() == FALSE) {
			redirect(base_url() . $this->session->userdata('username') . '/' . $id2 . '/scan-barcode', 'refresh');
		} else {

			include APPPATH . "third_party/phpqrcode/qrlib.php";

			$tempdir = "temp/"; //Nama folder tempat menyimpan file qrcode
			if (!file_exists($tempdir)) //Buat folder bername temp
				mkdir($tempdir);



			//isi qrcode jika di scan
			$codeContents = $this->input->post('link');

			$file_name = time() . '_qr_result.png';

			//simpan file qrcode
			QRcode::png($codeContents, $tempdir . $file_name, QR_ECLEVEL_H, 15, 2);

			// ambil file qrcode
			$QR = imagecreatefrompng($tempdir . $file_name);


			$QR_width = imagesx($QR);
			$QR_height = imagesy($QR);


			// Scale logo to fit in the QR Code
			$logo_qr_width = $QR_width / 5;

			if (isset($_POST['with_logo'])) {

				// Cek gambar user
				$this->db->select('users.foto_profile');
				$this->db->from('users');
				$this->db->where('users.username', $this->session->userdata('username'));
				$data_user = $this->db->get()->row();

				if ($data_user->foto_profile != '') {

					//ambil logo
					// $logopath = FCPATH."assets\klien\logo.png";
					$logopath = FCPATH . "assets/klien/foto_profile/" . $data_user->foto_profile;

					// memulai menggambar logo dalam file qrcode
					$logo = imagecreatefromstring(file_get_contents($logopath));

					imagecolortransparent($logo, imagecolorallocatealpha($logo, 0, 0, 0, 127));
					imagealphablending($logo, false);
					imagesavealpha($logo, true);

					$logo_width = imagesx($logo);
					$logo_height = imagesy($logo);
					$scale = $logo_width / $logo_qr_width;
					$logo_qr_height = $logo_height / $scale;
					imagecopyresampled($QR, $logo, $QR_width / 2.5, $QR_height / 2.5, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
				}
			}

			// Simpan kode QR lagi, dengan logo di atasnya
			imagepng($QR, $tempdir . $file_name);

			$img = base_url() . $tempdir . $file_name;
			$newdata = array(
				'qr_link' => $this->input->post('link'),
				'qr_result'  => $img,
				'qr_file'    => $file_name,
			);

			$this->session->set_userdata($newdata);
			$this->session->set_userdata('qr_result', $img);

			redirect(base_url() . $this->session->userdata('username') . '/' . $id2 . '/scan-barcode', 'refresh');
		}
	}

	public function download($id1, $id2)
	{
		$this->load->helper('download');
		$path = 'temp/' . $this->session->userdata('qr_file');
		force_download($path, NULL);
	}

	public function clear_data($id1, $id2)
	{
		if (!file_exists($this->session->userdata('qr_result'))) {
			unlink('temp/' . $this->session->userdata('qr_file'));
		}

		$array_items = array('qr_result', 'qr_file');

		$this->session->unset_userdata($array_items);

		redirect(base_url() . $this->session->userdata('username') . '/' . $id2 . '/scan-barcode', 'refresh');
	}
}

/* End of file ScanBarcodeController.php */
/* Location: ./application/controllers/ScanBarcodeController.php */