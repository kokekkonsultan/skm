<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class DataProspekSurveyController extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		if (!$this->ion_auth->logged_in()) {
			$this->session->set_flashdata('message_warning', 'You must be an admin to view this page');
			redirect('auth', 'refresh');
		}

		$this->load->model('DataProspekSurvey_model', 'models');

		$this->load->library('form_validation');
		$this->load->helper('security');
		$this->load->library('image_lib');
		$this->load->helper('file');
	}

	public function index($id1 = NULL, $id2 = NULL)
	{
		$this->data = [];
		$this->data['title'] = "Data Prospek Survei";
		$this->data['form_action'] = base_url()."$id1/$id2/data-prospek-survey";

		$this->data['profiles'] = $this->_get_data_profile($id1, $id2);
		$this->data['ajax_link'] = base_url() . $id1 . '/' . $id2 . '/data-prospek-survey/ajax-list';
		$this->data['ajax_add'] = base_url() . $id1 . '/' . $id2 . '/data-prospek-survey/ajax-add';
		$this->data['ajax_edit'] = base_url() . $id1 . '/' . $id2 . '/data-prospek-survey/ajax-edit/';
		$this->data['ajax_update'] = base_url() . $id1 . '/' . $id2 . '/data-prospek-survey/ajax-update/';
		$this->data['ajax_delete'] = base_url() . $id1 . '/' . $id2 . '/data-prospek-survey/ajax-delete/';
		$this->data['ajax_bagikan_email'] = base_url() . $id1 . '/' . $id2 . '/data-prospek-survey/bagikan-email';
		$this->data['ajax_bagikan_whatsapp'] = base_url() . $id1 . '/' . $id2 . '/data-prospek-survey/bagikan-whatsapp';
		$this->data['link_hapus_lampiran'] = base_url() . $id1 . '/' . $id2 . '/data-prospek-survey/delete-attachment';
		$this->data['link_hapus_logo'] = base_url() . $id1 . '/' . $id2 . '/data-prospek-survey/delete-logo';

		$table_identity = $this->get_table_identity($id2);
		$this->db->select("id");
		$this->db->from("data_prospek_survey_$table_identity");
		$this->data['jumlah_prospek'] = $this->db->get()->num_rows();

		$manage_survey = $this->db->get_where('manage_survey', array('slug' => "$id2"))->row();

		// Get UUID
		$this->data['uuid'] = $this->db->get_where('manage_survey', ['slug' => $id2])->row()->id;
		$this->data['slug'] = $id2;

		$this->data['template_email_prospek'] = [
			'name'         => 'template_email_prospek',
			'id'        => 'template_email_prospek',
			'type'        => 'text',
			'value'        =>    $this->form_validation->set_value('template_email_prospek', $manage_survey->template_email_prospek)
		];

		$this->data['pt_email_prospek'] = $manage_survey->template_email_prospek;

		$this->data['template_email_footer_prospek'] = [
			'name'         => 'template_email_footer_prospek',
			'id'        => 'template_email_footer_prospek',
			'type'        => 'text',
			'value'        =>    $this->form_validation->set_value('template_email_footer_prospek', $manage_survey->template_email_footer_prospek)
		];

		$this->data['pt_template_email_footer_prospek'] = $manage_survey->template_email_footer_prospek;

		$this->data['lampiran'] = [
				'name' 		=> 'lampiran',
				'id'		=> 'lampiran',
				'value'		=>	$this->form_validation->set_value('lampiran'),
				'class'		=> '',
				'required'	=> 'required',
			];

			$this->data['logo'] = [
				'name' 		=> 'logo',
				'id'		=> 'logo',
				'value'		=>	$this->form_validation->set_value('logo'),
				'class'		=> '',
				'required'	=> 'required',
			];

			// Cek lampiran
			$this->db->select('lampiran_email_prospek, template_email_logo_prospek');
			$this->db->from('manage_survey');
			$this->db->where('slug', $id2);
			$lampiran_email = $this->db->get();

			$this->data['file_lampiran'] = $lampiran_email->row()->lampiran_email_prospek;
			$this->data['logo_prospek'] = $lampiran_email->row()->template_email_logo_prospek;

			if (isset($_POST['upload_lampiran'])) {
				$this->form_validation->set_rules('lampiran', '', 'callback_check_lampiran');
			}

			if (isset($_POST['upload_logo'])) {
				$this->form_validation->set_rules('logo', '', 'callback_check_logo');
			}
			
			

			if ($this->form_validation->run() == FALSE) {

				return view('data_prospek_survey/index', $this->data);

			} else {

				if (isset($_POST['upload_lampiran'])) {
					$images_lampiran = $_FILES['lampiran']['name'];

					if ($images_lampiran !="") {
						$config['upload_path']     	= './prospek/public/assets/files/prospek/';
				    $config['allowed_types']   	= 'pdf';	    
				    $config['detect_mime']		= TRUE;
				    $config['max_size']        	= 20000;
				    $nama_file 					= strtolower('Lampiran');
						$config['file_name'] 		= $nama_file."_".time();

						$this->load->library('upload', $config);
						$this->upload->initialize($config);

						if (!$this->upload->do_upload('lampiran')){
							
							print_r($this->upload->display_errors());
							return view('data_prospek_survey/index', $this->data);

						}else{

							$lampiran = $this->upload->data();

						}
					}
					$nama_lampiran = $lampiran['file_name'];

					$object = [
						'lampiran_email_prospek' => $nama_lampiran
					];
					$this->db->where('slug', $id2);
					$this->db->update('manage_survey', $object);
				}


				if (isset($_POST['upload_logo'])) {
					
					$images_logo = $_FILES['logo']['name'];

					if ($images_logo !="") {
						$config['upload_path']     	= './prospek/public/assets/img/prospek/logo/';
				    $config['allowed_types']   	= 'png|jpg|jpeg';	    
				    $config['detect_mime']		= TRUE;
				    $config['max_size']        	= 20000;
				    $nama_file 					= strtolower('Logo');
						$config['file_name'] 		= $nama_file."_".time();

						$this->load->library('upload', $config);
						$this->upload->initialize($config);

						if (!$this->upload->do_upload('logo')){
							
							print_r($this->upload->display_errors());
							return view('data_prospek_survey/index', $this->data);

						}else{

							$logo = $this->upload->data();

						}
					}
					$nama_logo = $logo['file_name'];

					$object = [
						'template_email_logo_prospek' => $nama_logo
					];
					$this->db->where('slug', $id2);
					$this->db->update('manage_survey', $object);

				}
				

				redirect(base_url()."$id1/$id2/data-prospek-survey",'refresh');

			}
	}

	public function delete_logo($id1, $id2)
	{
		$search = $this->db->get_where('manage_survey', ['slug' => $id2])->row();

		if (($search->template_email_logo_prospek != "") or (!empty($search->template_email_logo_prospek))) {
			unlink('./prospek/public/assets/img/prospek/logo/'.$search->template_email_logo_prospek);
		}

		$object = [
			'template_email_logo_prospek' => null
		];
		$this->db->where('slug', $id2);
		$this->db->update('manage_survey', $object);


		redirect(base_url()."$id1/$id2/data-prospek-survey",'refresh');
	}

	public function delete_attachment($id1, $id2)
	{
		$search = $this->db->get_where('manage_survey', ['slug' => $id2])->row();

		if (($search->lampiran_email_prospek != "") or (!empty($search->lampiran_email_prospek))) {
			unlink('./prospek/public/assets/files/prospek/'.$search->lampiran_email_prospek);
		}

		$object = [
			'lampiran_email_prospek' => null
		];
		$this->db->where('slug', $id2);
		$this->db->update('manage_survey', $object);


		redirect(base_url()."$id1/$id2/data-prospek-survey",'refresh');
	}

	function get_table_identity($slug_manage_survey)
	{
		$this->db->select('table_identity');
		$this->db->from('manage_survey');
		$this->db->where("slug", $slug_manage_survey);
		return $this->db->get()->row()->table_identity;
	}

	public function _get_data_profile($id1, $id2)
	{
		$this->db->select('*');
		$this->db->from('users');
		$this->db->join('users_groups', 'users.id = users_groups.user_id');
		$this->db->where('users.username', $this->session->userdata('username'));
		$data_user = $this->db->get()->row();
		$user_identity = 'drs' . $data_user->is_parent;

		$this->db->select('users.username, manage_survey.survey_name, manage_survey.slug, manage_survey.description, manage_survey.is_privacy, manage_survey.survey_start, manage_survey.survey_end, manage_survey.kuesioner_name, manage_survey.id AS id_manage_survey, manage_survey.logo_survey, manage_survey.is_question, manage_survey.table_identity, manage_survey.jumlah_populasi, klasifikasi_survei.nama_klasifikasi_survei, id_jenis_pelayanan, nama_jenis_pelayanan_responden, sampling.nama_sampling, jumlah_sampling, manage_survey.atribut_pertanyaan_survey');

		if ($data_user->group_id == 2) {
			$this->db->from('users');
			$this->db->join('manage_survey', 'manage_survey.id_user = users.id');
			$this->db->join('jenis_pelayanan', 'manage_survey.id_jenis_pelayanan = jenis_pelayanan.id', 'left');
			$this->db->join('klasifikasi_survei', 'klasifikasi_survei.id = users.id_klasifikasi_survei');
			$this->db->join('sampling', 'sampling.id = manage_survey.id_sampling');
		} else {
			$this->db->from('manage_survey');
			$this->db->join("supervisor_$user_identity", "manage_survey.id_berlangganan = supervisor_$user_identity.id_berlangganan");
			$this->db->join("users", "supervisor_$user_identity.id_user = users.id");
			$this->db->join('jenis_pelayanan', 'manage_survey.id_jenis_pelayanan = jenis_pelayanan.id', 'left');
			$this->db->join('klasifikasi_survei', 'klasifikasi_survei.id = users.id_klasifikasi_survei');
			$this->db->join('sampling', 'sampling.id = manage_survey.id_sampling');
		}
		$this->db->where('users.username', $id1);
		$this->db->where('manage_survey.slug', $id2);
		$profiles = $this->db->get();
		// var_dump($profiles->row());

		if ($profiles->num_rows() == 0) {
			// echo 'Survey tidak ditemukan atau sudah dihapus !';
			// exit();
			show_404();
		}
		return $profiles->row();
	}

	public function ajax_list($id1, $id2)
	{
		// Get Identity
		$get_identity = $this->db->get_where('manage_survey', ['slug' => $id2])->row();
		$table_identity = $get_identity->table_identity;

		$list = $this->models->get_datatables($table_identity);
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			/*if ($value->email != '') {
				$btn_email = '<a class="btn btn-light-primary btn-sm font-weight-bold shadow" data-toggle="modal" title="Bagikan Via Email" onclick="showemaildetail(' . $value->id . ')" href="#modal_userDetail"><i class="fas fa-at"></i> Bagikan Via Email</a>';
			} else {
				$btn_email = '';
			}

			if ($value->telepon != '') {
				$btn_wa = '<a class="btn btn-light-primary btn-sm font-weight-bold shadow" data-toggle="modal" title="Bagikan Via Email" onclick="showwadetail(' . $value->id . ')" href="#modal_userDetail"><i class="fab fa-whatsapp"></i> Bagikan Via WhatsApp</a>';
			} else {
				$btn_wa = '';
			}*/

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $value->nama_lengkap;
			$row[] = $value->alamat;
			$row[] = $value->telepon;
			$row[] = $value->email;
			$row[] = $value->keterangan;
			// $row[] = $btn_email . ' ' . $btn_wa;
			$row[] = '<a class="" href="javascript:void(0)" title="Edit" onclick="edit_prospek(' . "'" . $value->id . "'" . ')"><i class="flaticon-edit icon-md text-primary"></i> Edit</a>';
			$row[] = '<a class="text-danger" href="javascript:void(0)" title="Hapus" onclick="delete_prospek(' . "'" . $value->id . "'" . ')"><i class="flaticon-delete icon-md text-danger"></i> Delete</a>';

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

	public function bagikan_email($id1, $id2)
	{
		$id = $this->input->post('id');
		// Get Identity
		$this->db->select('*');
		$this->db->from('manage_survey');
		$this->db->join('users', 'users.id = manage_survey.id_user');
		$this->db->where('manage_survey.slug', $id2);
		$get_identity = $this->db->get()->row();
		$table_identity = $get_identity->table_identity;
		$nama_tabel = 'data_prospek_survey_' . $table_identity;

		$this->data = [];
		$this->data['id'] = $id;
		$this->data['nama_tabel'] = $nama_tabel;
		$this->data['detail'] = $get_identity;

		// Data Prospek
		$data_prospek = $this->db->get_where($nama_tabel, ['id' => $id])->row();

		$this->data['data_prospek'] = $data_prospek;
		$this->data['link_survey'] = base_url() . 'survei/' . $id2;

		return view('data_prospek_survey/form_bagikan_email', $this->data);
	}

	public function get_send_email()
	{
		if ($this->input->post('is_email') == 1) {

			$id_klien = $_SESSION['input']['id_berlangganan'];

			$isi_email = $this->input->post('isi_email');

			$this->db->select('*');
			$this->db->from('users');
			$this->db->join('berlangganan', 'berlangganan.id_user = users.id');
			$this->db->join('paket', 'paket.id = berlangganan.id_paket');
			$this->db->join('metode_pembayaran', 'metode_pembayaran.id = berlangganan.id_metode_pembayaran');
			$this->db->join('status_berlangganan', 'status_berlangganan.id = berlangganan.id_status_berlangganan');
			$this->db->join('klasifikasi_survei', 'klasifikasi_survei.id = users.id_klasifikasi_survei');
			$this->db->where('berlangganan.id_user', $id_klien);
			$data_klien = $this->db->get()->last_row();

			$settings = $this->db->query("
							SELECT
							( SELECT setting_value FROM web_settings WHERE alias = 'akun_email') AS email_akun,
							( SELECT setting_value FROM web_settings WHERE alias = 'email_pengirim') AS email_pengirim,
							( SELECT setting_value FROM web_settings WHERE alias = 'email_username') AS email_username,
							( SELECT setting_value FROM web_settings WHERE alias = 'email_password') AS email_password,
							( SELECT setting_value FROM web_settings WHERE alias = 'email_host') AS email_host,
							( SELECT setting_value FROM web_settings WHERE alias = 'email_port') AS email_port,
							( SELECT setting_value FROM web_settings WHERE alias = 'email_cc') AS email_cc,
							( SELECT setting_value FROM web_settings WHERE alias = 'email_bcc') AS email_bcc
							FROM
							web_settings LIMIT 1
						")->row();


			$this->load->library('email');

			$ci = get_instance();
			$config['protocol']     = "smtp";
			$config['smtp_host']    = $settings->email_host;
			$config['smtp_port']    = $settings->email_port;
			$config['smtp_user']    = $settings->email_username;
			$config['smtp_pass']    = $settings->email_password;
			$config['charset']      = "utf-8";
			$config['mailtype']     = "html";
			$config['newline']      = "\r\n";

			$html = '';
			$html .= '

<table width="100%" border="0" cellpadding="4" cellspacing="0">
  <tr>
    <td bgcolor="#AE0000" style="font-size: 20px; color: #FFFFFF;"><div align="center"><strong>SISTEM INFORMASI E-SKM</strong></div></td>
  </tr>
  <tr>
    <td style="font-size: 16px;">
<br><br>
<p>Kepada Bapak/ Ibu<br />
Di Tempat</p>
<p>' . $isi_email . '</p>
<p><strong><u>Admin E-SKM</u></strong></p>
<br><br>

    </td>
  </tr>
  <tr>
    <td bgcolor="#CCCCCC" style="font-size: 12px;"><div align="center">View as a Web Page<br />
    Sistem Informasi E-SKM<br />
      survei-kepuasan.com
    </div></td>
  </tr>
</table>
					';


			$ci->email->initialize($config);
			$ci->email->from($settings->email_pengirim, 'Auto Reply Sistim Informasi E-SKM');
			$ci->email->to($this->input->post('email_ke'));

			// if ($settings->email_cc != '') {
			// 	$this->email->cc($settings->email_cc);
			// }

			$ci->email->subject('Informasi Survey Kepuasan Masyarakat');
			$ci->email->message($html);
			$this->email->send();
		}

		$array = array(
			'success' => '<div class="alert alert-success">Berhasil menambah melakukan konfirmasi terima pembayaran registrasi dari pendaftar</div>'
		);

		echo json_encode($array);
	}

	public function bagikan_whatsapp($id1, $id2)
	{
		$this->data = [];
		$id = $this->input->post('id');
		$this->data['id'] = $id;

		return view('data_prospek_survey/form_bagikan_wa', $this->data);
	}

	public function ajax_add($id1, $id2)
	{
		$this->_validate_add();

		// Get Identity
		$get_identity = $this->db->get_where('manage_survey', ['slug' => "$id2"])->row();
		$table_identity = $get_identity->table_identity;
		$id_manage_survey = $get_identity->id;

		// Cek apakah sudah ada email
		$cek_data = $this->db->get_where('data_prospek_survey_' . $table_identity, ['email' => $this->input->post('email')]);

		if ($cek_data->num_rows() == 0) {
			
			$data = [
				'nama_lengkap' 	=> $this->input->post('nama_lengkap'),
				'id_manage_survey' => $id_manage_survey,
				'alamat' 	=> $this->input->post('alamat'),
				'email' => $this->input->post('email'),
				'telepon' => $this->input->post('telepon'),
				'keterangan' => $this->input->post('keterangan'),
			];


			$this->db->insert('data_prospek_survey_' . $table_identity, $data);

			echo json_encode(array("status" => TRUE));

		} else {
			echo json_encode(array("status" => FALSE));
		}

	}

	private function _validate_add()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		$this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'trim|required');
		$this->form_validation->set_rules('alamat', 'Alamat', 'trim');
		$this->form_validation->set_rules('email', 'Email', 'trim');
		$this->form_validation->set_rules('telepon', 'Whatsapp', 'trim');
		$this->form_validation->set_rules('keterangan', 'Keterangan', 'trim');

		$this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');


		if ($this->form_validation->run() == FALSE) {
			$data['inputerror'][] = 'nama_lengkap';
			$data['error_string'][] = form_error('nama_lengkap');
			$data['status'] = FALSE;

			$data['inputerror'][] = 'alamat';
			$data['error_string'][] = form_error('alamat');
			$data['status'] = FALSE;

			$data['inputerror'][] = 'email';
			$data['error_string'][] = form_error('email');
			$data['status'] = FALSE;

			$data['inputerror'][] = 'telepon';
			$data['error_string'][] = form_error('telepon');
			$data['status'] = FALSE;

			$data['inputerror'][] = 'keterangan';
			$data['error_string'][] = form_error('keterangan');
			$data['status'] = FALSE;
		}


		if ($data['status'] === FALSE) {
			echo json_encode($data);
			exit();
		}
	}

	public function ajax_delete($id1, $id2, $id3)
	{
		// Get Identity
		$get_identity = $this->db->get_where('manage_survey', ['slug' => "$id2"])->row();
		$table_identity = $get_identity->table_identity;

		$this->db->where('id', $id3);
		$this->db->delete('data_prospek_survey_' . $table_identity);

		echo json_encode(array("status" => TRUE));
	}



	public function ajax_edit($id1, $id2, $id3)
	{
		// Get Identity
		$get_identity = $this->db->get_where('manage_survey', ['slug' => "$id2"])->row();
		$table_identity = $get_identity->table_identity;

		$data = $this->db->get_where('data_prospek_survey_' . $table_identity, ['id' => $id3])->row();

		echo json_encode($data);
	}

	public function ajax_update($id1, $id2)
	{
		$this->_validate_edit();

		// // Get Identity
		$get_identity = $this->db->get_where('manage_survey', ['slug' => "$id2"])->row();
		$table_identity = $get_identity->table_identity;
		$id_manage_survey = $get_identity->id;

		// Cek apakah sudah ada email
		$cek_data = $this->db->get_where('data_prospek_survey_' . $table_identity, ['email' => $this->input->post('email')]);

		if ($cek_data->num_rows() == 0) {

			$data = [
				'nama_lengkap' 	=> $this->input->post('nama_lengkap'),
				// 'id_manage_survey' => $id_manage_survey,
				'alamat' 	=> $this->input->post('alamat'),
				'email' => $this->input->post('email'),
				'telepon' => $this->input->post('telepon'),
				'keterangan' => $this->input->post('keterangan'),
			];

			$this->db->where('id', $this->input->post('id'));
			$this->db->update('data_prospek_survey_' . $table_identity, $data);

			echo json_encode(array("status" => TRUE));

		} else {
			echo json_encode(array("status" => FALSE));
		}

	}

	private function _validate_edit()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		$this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'trim|required');
		$this->form_validation->set_rules('alamat', 'Alamat', 'trim');
		$this->form_validation->set_rules('email', 'Email', 'trim');
		$this->form_validation->set_rules('telepon', 'Whatsapp', 'trim');
		$this->form_validation->set_rules('keterangan', 'Keterangan', 'trim');

		$this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');


		if ($this->form_validation->run() == FALSE) {
			$data['inputerror'][] = 'nama_lengkap';
			$data['error_string'][] = form_error('nama_lengkap');
			$data['status'] = FALSE;

			$data['inputerror'][] = 'alamat';
			$data['error_string'][] = form_error('alamat');
			$data['status'] = FALSE;

			$data['inputerror'][] = 'email';
			$data['error_string'][] = form_error('email');
			$data['status'] = FALSE;

			$data['inputerror'][] = 'telepon';
			$data['error_string'][] = form_error('telepon');
			$data['status'] = FALSE;

			$data['inputerror'][] = 'keterangan';
			$data['error_string'][] = form_error('keterangan');
			$data['status'] = FALSE;
		}


		if ($data['status'] === FALSE) {
			echo json_encode($data);
			exit();
		}
	}

	// checkFileValidation
	public function checkFileValidation($string)
	{
		$file_mimes = array(
			'text/x-comma-separated-values',
			'text/comma-separated-values',
			'application/octet-stream',
			'application/vnd.ms-excel',
			'application/x-csv',
			'text/x-csv',
			'text/csv',
			'application/csv',
			'application/excel',
			'application/vnd.msexcel',
			'text/plain',
			'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
		);
		if (isset($_FILES['fileURL']['name'])) {
			$arr_file = explode('.', $_FILES['fileURL']['name']);
			$extension = end($arr_file);
			if (($extension == 'xlsx' || $extension == 'xls' || $extension == 'csv') && in_array($_FILES['fileURL']['type'], $file_mimes)) {
				return true;
			} else {
				$this->form_validation->set_message('checkFileValidation', 'Please choose correct file.');
				return false;
			}
		} else {
			$this->form_validation->set_message('checkFileValidation', 'Please choose a file.');
			return false;
		}
	}

	public function import($username, $slug_manage_survey)
	{
		$this->data = [];
		$this->data['title'] = "Import Data Prospek";

		return view('data_prospek_survey/form_import', $this->data);
	}

	public function preview($username, $slug_manage_survey)
	{
		$this->data = [];
		$this->data['title'] = "Import Preview";

		if (isset($_POST['preview'])) {

			$tgl_sekarang = date('YmdHis');
			$nama_file_baru = 'data' . $tgl_sekarang . '.xlsx';

			if (is_file('temp/' . $nama_file_baru))
				unlink('temp/' . $nama_file_baru);

			$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
			$tmp_file = $_FILES['file']['tmp_name'];

			if ($ext == "xlsx") {

				move_uploaded_file($tmp_file, 'temp/' . $nama_file_baru);

				$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
				$spreadsheet = $reader->load('temp/' . $nama_file_baru); // Load file yang tadi diupload ke folder temp
				$sheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

				$this->load->library('table');

				$template = array(
					'table_open' => '<table class="table table-bordered">',
					'table_close' => '</table>'
				);

				$this->table->set_template($template);
				$this->table->set_heading('Nama Lengkap', 'Alamat', 'Telepon', 'Email', 'Keterangan');

				$numrow = 1;
				$kosong = 0;
				foreach ($sheet as $row) {

					$nama_lengkap = $row['A'];
					$alamat = $row['B'];
					$telepon = $row['C'];
					$email = $row['D'];
					$keterangan = $row['E'];

					if ($nama_lengkap == "" && $alamat == "" && $telepon == "" && $email == "" && $keterangan == "")
						continue;

					if ($numrow > 1) {
						$this->table->add_row(
							$nama_lengkap,
							$alamat,
							$telepon,
							$email,
							$keterangan
						);
					}
					$numrow++;
				}

				$this->data['table'] = $this->table->generate();
				$this->data['nama_file_baru'] = $nama_file_baru;

				return view("data_prospek_survey/form_import_preview", $this->data);
			} else {
				echo "File yang anda pilih bukan merupakan file excel";
				exit();
			}
		}
	}

	public function proses($username, $slug_manage_survey)
	{
		if (isset($_POST['import'])) {

			$nama_file_baru = $_POST['namafile'];
			$path = 'temp/' . $nama_file_baru;

			$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
			$spreadsheet = $reader->load($path);
			$sheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

			$result = array();
			$numrow = 1;
			foreach ($sheet as $row) {

				$nama_lengkap = $row['A'];
				$alamat = $row['B'];
				$telepon = $row['C'];
				$email = $row['D'];
				$keterangan = $row['E'];

				if ($nama_lengkap == "" && $alamat == "" && $telepon == "" && $email == "" && $keterangan == "")
					continue;

				if ($numrow > 1) {

					$result[] = [
						'id_manage_survey' => '',
						'nama_lengkap' => $nama_lengkap,
						'keterangan' => $keterangan,
						'alamat' => $alamat,
						'telepon' => $telepon,
						'email' => $email,
					];
				}

				$numrow++;
			}

			$table_identity = $this->get_table_identity($slug_manage_survey);

			$this->db->insert_batch("data_prospek_survey_$table_identity", $result);

			unlink($path);

			redirect(base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2) . '/data-prospek-survey', 'refresh');
		}
	}

	public function truncate($username, $slug_manage_survey)
	{
		$table_identity = $this->get_table_identity($slug_manage_survey);

		$this->db->query("TRUNCATE table data_prospek_survey_$table_identity");

		echo json_encode(array("status" => TRUE));
	}

	public function download_template($username, $slug_manage_survey)
	{
		$this->load->helper('file');
		$this->load->helper('download');

		force_download('./assets/files/file_template/FormatImportDataProspek.xlsx', NULL);
	}

	public function update_email_prospek($username, $slug_manage_survey)
	{
		$object = [
			'template_email_prospek' => $this->input->post('template_email_prospek')
		];

		$this->db->where('slug', "$slug_manage_survey");
		$this->db->update('manage_survey', $object);

		$pesan = 'template email berhasil disimpan';
		$msg = ['sukses' => $pesan];
		echo json_encode($msg);
	}

	public function update_email_footer_prospek($username, $slug_manage_survey)
	{
		$object = [
			'template_email_footer_prospek' => $this->input->post('template_email_footer_prospek')
		];

		$this->db->where('slug', "$slug_manage_survey");
		$this->db->update('manage_survey', $object);

		$pesan = 'footer email berhasil disimpan';
		$msg = ['sukses' => $pesan];
		echo json_encode($msg);
	}

	public function check_lampiran($str)
    {
    	$allowed_mime_type_arr = array('application/pdf', 'application/force-download', 'application/x-download', 'binary/octet-stream');
        $mime = get_mime_by_extension($_FILES['lampiran']['name']);
        if(isset($_FILES['lampiran']['name']) && $_FILES['lampiran']['name']!=""){
            if(in_array($mime, $allowed_mime_type_arr)){
                return true;
            }else{
                $this->form_validation->set_message('check_lampiran', 'Silahkan pilih hanya file pdf.');
                return false;
            }
        }else{
            $this->form_validation->set_message('check_lampiran', 'Silakan pilih file yang akan diunggah.');
            return false;
        }
    }

    public function check_logo($str)
    {
    	$allowed_mime_type_arr = array('image/png',  'image/x-png', 'image/jpeg', 'image/pjpeg');
        $mime = get_mime_by_extension($_FILES['logo']['name']);
        if(isset($_FILES['logo']['name']) && $_FILES['logo']['name']!=""){
            if(in_array($mime, $allowed_mime_type_arr)){
                return true;
            }else{
                $this->form_validation->set_message('check_logo', 'Silahkan pilih file png/jpg.');
                return false;
            }
        }else{
            $this->form_validation->set_message('check_logo', 'Silakan pilih file yang akan diunggah.');
            return false;
        }
    }
}

/* End of file DataProspekSurveyController.php */
/* Location: ./application/controllers/DataProspekSurveyController.php */