<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PerolehanSurveyorController extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->library('ion_auth');

		if (!$this->ion_auth->logged_in()) {
			$this->session->set_flashdata('message_warning', 'You must be logged in to access this page');
			redirect('auth', 'refresh');
		}
		$this->load->model('DataSurveyor_model', 'models');
		$this->load->model('DetailPerolehanSurveyor_model');
	}

	public function index($id1, $id2)
	{
		$this->data = [];
		$this->data['title'] = 'Perolehan Surveyor';

		$this->data['profiles'] = $this->_get_data_profile($id1, $id2);

		$this->db->select('manage_survey.id');
		$this->db->from('manage_survey');
		$this->db->where('manage_survey.slug', $this->uri->segment(2));
		$id_manage_survey = $this->db->get()->row()->id;

		$this->db->select("users.id AS user_id, users.first_name, users.last_name, surveyor.kode_surveyor, surveyor.uuid, (SELECT COUNT(survey_cst$id_manage_survey.id) FROM survey_cst$id_manage_survey WHERE survey_cst$id_manage_survey.id_surveyor = surveyor.id) AS total_survey");
		$this->db->from('surveyor');
		$this->db->join('users', 'users.id = surveyor.id_user');
		$this->db->join('manage_survey', 'manage_survey.id = surveyor.id_manage_survey');
		$this->db->where('surveyor.id_manage_survey', $id_manage_survey);
		$this->data['get_data'] = $this->db->get();

		return view('perolehan_surveyor/index', $this->data);
	}

	public function ajax_list()
	{
		$slug = $this->uri->segment(2);

		$get_identity = $this->db->get_where('manage_survey', ['slug' => "$slug"])->row();
		$id_manage_survey = $get_identity->id;

		$list = $this->models->get_datatables($id_manage_survey);
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			$no++;
			$row = array();
			$row[] = $no;
			$row[] =  '<b>' . $value->kode_surveyor . '</b> -- ' . $value->first_name . ' ' . $value->last_name;
			$row[] = '<span class="badge badge-info">' . $value->total_survey . '</span>';
			$row[] = anchor($this->session->userdata('username') . '/' . $this->uri->segment(2) . '/detail-perolehan/' . $value->uuid_surveyor, 'Detail Perolehan <i class="fa fa-arrow-right"></i>', ['class' => 'btn btn-light-primary btn-sm font-weight-bold shadow']);


			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->models->count_all($id_manage_survey),
			"recordsFiltered" => $this->models->count_filtered($id_manage_survey),
			"data" => $data,
		);

		echo json_encode($output);
	}

	public function detail_perolehan_surveyor($id1, $id2, $id3)
	{
		$this->data = [];
		$this->data['title'] = 'Detail Perolehan Surveyor';

		$this->data['profiles'] = $this->_get_data_profile($id1, $id2);

		$this->db->select('manage_survey.table_identity');
		$this->db->from('manage_survey');
		$this->db->where('manage_survey.slug', $this->uri->segment(2));
		$table_identity = $this->db->get()->row()->table_identity;

		// var_dump($id_manage_survey);

		$this->db->select('*');
		$this->db->from('surveyor');
		$this->db->join('users', 'users.id = surveyor.id_user');
		$this->db->where('surveyor.uuid', $id3);
		$total_surveyor = $this->db->get()->num_rows;

		if($total_surveyor > 0){
			$this->db->select('*');
			$this->db->from('surveyor');
			$this->db->join('users', 'users.id = surveyor.id_user');
			$this->db->where('surveyor.uuid', $id3);
		}else{
			$this->db->select('*');
			$this->db->from('surveyor_induk');
			$this->db->join('users', 'users.id = surveyor_induk.id_user');
			$this->db->where('surveyor_induk.uuid', $id3);
		}
		$this->data['data_surveyor'] = $this->db->get()->row();

		if($total_surveyor > 0){
			$query = $this->db->query("
			SELECT responden_$table_identity.id AS id, waktu_isi, id_surveyor, responden_$table_identity.uuid AS uuid, responden_$table_identity.uuid AS uuid_responden
			FROM responden_$table_identity
			JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id_responden
			JOIN surveyor ON survey_$table_identity .id_surveyor = surveyor.id
			WHERE surveyor.uuid = '$id3'
			");
		}else{
			$query = $this->db->query("
			SELECT responden_$table_identity.id AS id, waktu_isi, id_surveyor_induk, responden_$table_identity.uuid AS uuid, responden_$table_identity.uuid AS uuid_responden
			FROM responden_$table_identity
			JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id_responden
			JOIN surveyor_induk ON survey_$table_identity .id_surveyor_induk = surveyor_induk.id
			WHERE surveyor_induk.uuid = '$id3'
			");
		}
		$this->data['perolehan'] = $query;
		// var_dump($query->result());


		return view('perolehan_surveyor/detail_perolehan', $this->data);
	}

	public function ajax_list_detail()
	{
		$slug = $this->uri->segment(2);
		$get_identity = $this->db->get_where('manage_survey', ['slug' => "$slug"])->row();
		$table_identity = $get_identity->table_identity;

		$surveyor = $this->db->get_where('surveyor', array('uuid' => $this->uri->segment(5)))->row();
		if(isset($surveyor->id)){
			$id_surveyor = $surveyor->id;
		}else{
			$surveyor = $this->db->get_where('surveyor_induk', array('uuid' => $this->uri->segment(5)))->row();
			$id_surveyor = $surveyor->id;
		}

		$list = $this->DetailPerolehanSurveyor_model->get_datatables($table_identity, $id_surveyor);
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			if ($value->is_submit == 1) {
				$status = '<span class="badge badge-primary">Valid</span>';
			} else {
				$status = '<span class="badge badge-danger">Tidak Valid</span>';
			}

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $status;
			$row[] = anchor($this->uri->segment(2) . '/hasil-survei/' . $value->uuid_responden, '<i class="fas fa-file-pdf text-danger"></i>', ['target' => '_blank']);
			$row[] = '<b>' . $value->kode_surveyor . '</b>--' . $value->first_name . ' ' . $value->last_name;
			$row[] = 'Responden ' . $no;
			$row[] = date("d-m-Y", strtotime($value->waktu_isi));
			// $row[] = anchor('/survei/' . $slug . '/data-responden/'  . $value->uuid_responden . '/edit', '<i class="fa fa-edit"></i> Edit', ['class' => 'btn btn-light-primary btn-sm font-weight-bold shadow', 'target' => '_blank']);

			$row[] = '<a class="btn btn-light-primary btn-sm font-weight-bold shadow" href="javascript:void(0)" title="Hapus" onclick="delete_data(' . "'" . $value->id_responden . "'" . ')"><i class="fa fa-trash"></i> Delete</a>';


			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->DetailPerolehanSurveyor_model->count_all($table_identity, $id_surveyor),
			"recordsFiltered" => $this->DetailPerolehanSurveyor_model->count_filtered($table_identity, $id_surveyor),
			"data" => $data,
		);

		echo json_encode($output);
	}

	public function get_email()
	{
		$id_surveyor 	= $this->input->post('id_surveyor');

		$surveyor = $this->db->query("SELECT *
		FROM users
		JOIN surveyor ON users.id = surveyor.id_user
		JOIN manage_survey ON surveyor.id_manage_survey = manage_survey.id
		WHERE users.id = 	$id_surveyor")->row();

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
    <td bgcolor="#AE0000" style="font-size: 20px; color: #FFFFFF;">
      <div align="center"><strong>SISTEM INFORMASI E-SKM</strong></div>
    </td>
  </tr>
  <tr>
    <td style="font-size: 16px;">
      <br><br>
      <p>Kepada Bapak / Ibu ' . $surveyor->first_name . ' ' . $surveyor->last_name . '<br />
        Di Tempat</p>
      <p>Anda telah didaftarkan sebagai surveyor untuk survey <b>' . $surveyor->survey_name . '</b> :</p>
      <table border="1" style="border-collapse: collapse; border-color: #d3d3d3;" cellpadding="4" cellspacing="0">
        <tr>
          <th>Link Login</th>
          <td>' . base_url() . 'auth/login</td>
        </tr>
        <tr>
          <th>Username</th>
          <td>' . $surveyor->username . '</td>
        </tr>
        <tr>
          <th>Password</th>
          <td>' . $surveyor->re_password . '</td>
        </tr>
      </table>
      <br>
      <p>Agar akun anda lebih aman, segera ubah password anda melalui link berikut ini <a href="' . base_url() . 'auth/forgot_password">' . base_url() . 'auth/forgot_password</a></p>
      <p>Terima Kasih.</p>
      <p><strong><u>Admin E-SKM</u></strong></p>
      <br><br>

    </td>
  </tr>
  <tr>
    <td bgcolor="#CCCCCC" style="font-size: 12px;">
      <div align="center">View as a Web Page<br />
        Sistem Informasi E-SKM<br />
        survei-kepuasan.com
      </div>
    </td>
  </tr>
</table>
					';

		$ci->email->initialize($config);
		$ci->email->from($settings->email_pengirim, 'Auto Reply Sistem Informasi E-SKM');
		$ci->email->to($surveyor->email);

		// if ($settings->email_cc != '') {
		// 	$this->email->cc($settings->email_cc);
		// }

		// if ($settings->email_bcc != '') {
		// 	$this->email->bcc($settings->email_bcc);
		// }

		$ci->email->subject('Akun Surveyor ' . $surveyor->survey_name);
		$ci->email->message($html);
		$this->email->send();

		$pesan = 'Email berhasil dikirim';
		$msg = ['sukses' => $pesan];
		echo json_encode($msg);
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

/* End of file PerolehanSurveyorController.php */
/* Location: ./application/controllers/PerolehanSurveyorController.php */
