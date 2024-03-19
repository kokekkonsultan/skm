<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SettingsController extends CI_Controller
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
	}

	public function index()
	{
		$this->data = [];
		$this->data['title'] = 'Pengaturan';

		$this->data['web_settings'] = $this->db->query("
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

		return view('settings/index', $this->data);
	}

	public function update_email()
	{
		$input 	= $this->input->post(NULL, TRUE);

		$email_akun = $input['email_akun'];
		$email_pengirim = $input['email_pengirim'];
		$email_username = $input['email_username'];
		$email_password = $input['email_password'];
		$email_host = $input['email_host'];
		$email_port = $input['email_port'];
		$email_cc = $input['email_cc'];
		$email_bcc = $input['email_bcc'];

		$query = "
UPDATE `web_settings`
SET `setting_value`= (CASE
when `alias` = 'akun_email' then '$email_akun'
when `alias` = 'email_pengirim' then '$email_pengirim'
when `alias` = 'email_username' then '$email_username'
when `alias` = 'email_host' then '$email_host'
when `alias` = 'email_port' then '$email_port'
when `alias` = 'email_cc' then '$email_cc'
when `alias` = 'email_bcc' then '$email_bcc'
		";

		if ($email_password != '') {
			$query .= "when `alias` = 'email_password' then '$email_password'";
		}

		$query .= "
ELSE `setting_value` END)
		";

		$this->db->query($query);

		$pesan = 'Data berhasil disimpan';
		$msg = ['sukses' => $pesan];
		echo json_encode($msg);
	}

	public function test_email()
	{
		$email_akun 	= $this->input->post('email_akun_test');
		$isi_email 	= $this->input->post('isi_email');

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
		$html .= "
				[TEST EMAIL]<br><br>

				" . $isi_email . "
		";

		$ci->email->initialize($config);
		$ci->email->from($settings->email_pengirim, 'Auto Reply Sistim Informasi E-SKM');
		$ci->email->to($email_akun);

		// if ($settings->email_cc != '') {
		// 	$this->email->cc($settings->email_cc);
		// }

		// if ($settings->email_bcc != '') {
		// 	$this->email->bcc($settings->email_bcc);
		// }

		$ci->email->subject('Testing Akun Email Aplikasi');
		$ci->email->message($html);
		$this->email->send();

		$pesan = 'Email berhasil dikirim';
		$msg = ['sukses' => $pesan];
		echo json_encode($msg);
	}
}

/* End of file SettingsController.php */
/* Location: ./application/controllers/SettingsController.php */