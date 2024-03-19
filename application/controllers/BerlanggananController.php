<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Carbon\Carbon;

class BerlanggananController extends CI_Controller
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
		$this->data['title'] = 'Berlangganan Paket';

		$this->load->library('table');

		$template = array(
			'table_open'            => '<table class="table table-bordered table-hover">',
			'table_close'           => '</table>'
		);

		$this->table->set_template($template);

		$this->table->set_heading('NO', 'Nama Pelanggan', 'Tanggal Berakhir', 'Data Berlangganan');

		$this->db->select('users.first_name, users.last_name, berlangganan.id_user, tanggal_selesai');
		$this->db->from('berlangganan');
		$this->db->join('users', 'users.id = berlangganan.id_user');
		$this->db->join('paket', 'paket.id = berlangganan.id_paket');
		$this->db->join('status_berlangganan', 'status_berlangganan.id = berlangganan.id_status_berlangganan');
		$this->db->join('metode_pembayaran', 'metode_pembayaran.id = berlangganan.id_metode_pembayaran');
		$this->db->group_by('berlangganan.id_user');
		$this->db->order_by('berlangganan.id_user', 'desc');
		$get_data = $this->db->get();

		$no = 1;
		foreach ($get_data->result() as $value) {
			$this->table->add_row(
				$no++,
				$value->first_name . ' ' . $value->last_name,
				date("d-m-Y", strtotime($value->tanggal_selesai)),
				anchor(base_url() . 'berlangganan/data-langganan/' . $value->id_user, 'Detail', ['class' => 'btn btn-light-primary font-weight-bold shadow-lg']),
			);
		}

		$this->data['table'] = $this->table->generate();

		return view('berlangganan/index', $this->data);
	}

	public function data_berlangganan($id)
	{
		$url = $this->uri->uri_string();
		$this->session->set_userdata('urlback', $url);

		$this->data = [];
		$this->data['title'] = 'Data Berlangganan';

		$this->load->library('table');

		$template = array(
			'table_open'            => '<table class="table table-bordered table-hover">',
			'table_close'           => '</table>'
		);

		$this->table->set_template($template);

		$this->table->set_heading('NO', 'Nama Pelanggan', 'Nama Paket', 'Panjang Hari', 'Harga Paket (Rp.)', 'Tanggal Aktif', 'Tanggal Kedaluarsa', '', '', '', '');

		$this->db->select('*, berlangganan.id AS id_berlangganan');
		$this->db->from('berlangganan');
		$this->db->join('users', 'users.id = berlangganan.id_user');
		$this->db->join('paket', 'paket.id = berlangganan.id_paket');
		$this->db->join('status_berlangganan', 'status_berlangganan.id = berlangganan.id_status_berlangganan');
		$this->db->join('metode_pembayaran', 'metode_pembayaran.id = berlangganan.id_metode_pembayaran');
		$this->db->where('berlangganan.id_user', $id);
		$this->db->order_by('berlangganan.id', 'asc');
		$get_data = $this->db->get();

		$jumlah = $get_data->num_rows();

		$no = 1;
		foreach ($get_data->result() as $value) {

			if ($no == $jumlah) {
				if ($no > 1) {
					$btn_langganan = anchor(base_url() . 'berlangganan/data-langganan/perpanjangan/' . $value->id_user, 'Perpanjangan', ['class' => 'btn btn-light-primary font-weight-bold shadow-lg']);
					$btn_email = '<a class="btn btn-light-primary font-weight-bold shadow-lg" data-toggle="modal" title="Detail Klien" onclick="showuserdetail(' . $value->id_user . ')" href="#modal_userDetail"><i class="fas fa-envelope"></i> Informasikan Email</a>';
					$btn_edit = anchor("berlangganan/data-langganan/edit-perpanjangan/" . $value->id_berlangganan, 'Edit', ['class' => 'btn btn-light-primary font-weight-bold shadow-lg']);
					$btn_delete = anchor("berlangganan/data-langganan/delete-perpanjangan/" . $value->id_berlangganan, 'Delete', ['class' => 'btn btn-light-primary font-weight-bold shadow-lg', 'onclick' => "return confirm('Anda yakin ingin menghapus perpanjangan ini ?')"]);
				} else {
					$btn_langganan = anchor(base_url() . 'berlangganan/data-langganan/perpanjangan/' . $value->id_user, 'Perpanjangan', ['class' => 'btn btn-light-primary font-weight-bold shadow-lg']);
					$btn_email = '<a class="btn btn-light-primary font-weight-bold shadow-lg" data-toggle="modal" title="Detail Klien" onclick="showuserdetail(' . $value->id_user . ')" href="#modal_userDetail"><i class="fas fa-envelope"></i> Informasikan Email</a>';
					$btn_edit = anchor("berlangganan/data-langganan/edit-perpanjangan/" . $value->id_berlangganan, 'Edit', ['class' => 'btn btn-light-primary font-weight-bold shadow-lg']);
					$btn_delete = '';
				}
			} else {
				$btn_langganan = '';
				$btn_email = '';
				$btn_edit = '';
				$btn_delete = '';
			}

			$this->table->add_row(
				$no++,
				$value->first_name . ' ' . $value->last_name,
				$value->nama_paket,
				$value->panjang_hari,
				number_format($value->harga_paket, 2, ',', '.'),
				date('d-m-Y', strtotime($value->tanggal_mulai)),
				date('d-m-Y', strtotime($value->tanggal_selesai)),
				'', //$btn_langganan,
				$btn_email,
				anchor('url', 'Get Invoice', ['class' => 'btn btn-light-primary font-weight-bold shadow']), //$btn_edit,
				$btn_delete,
			);
		}

		$this->data['table'] = $this->table->generate();

		$last_payment = $get_data->last_row();
		$this->data['last_payment'] = $last_payment;

		$tanggal_mulai = $last_payment->tanggal_mulai;
		$tanggal_selesai = $last_payment->tanggal_selesai;

		$this->data['tanggal_sekarang'] = $tanggal_mulai;
		$this->data['tanggal_expired'] = $tanggal_selesai;

		$now = Carbon::now();
		$start_date = Carbon::parse($tanggal_mulai);
		$end_date = Carbon::parse($tanggal_selesai);
		$due_date = $now->diffInDays($end_date); // Tanggal jatuh tempo

		if ($now->between($start_date, $end_date)) {
			$this->data['status_jatuh_tempo'] = 'Paket berakhir dalam ' . $due_date . ' hari lagi';
			$this->data['status_paket'] = '<span class="badge badge-success">Aktif</span>';
		} else {
			$this->data['status_jatuh_tempo'] = 'Packet is Expired';
			$this->data['status_paket'] = '<span class="badge badge-danger">Expired</span>';
		}

		return view('berlangganan/form_data_berlangganan', $this->data);
	}



	public function get_send_email()
	{
		if ($this->input->post('is_email') == 1) {

			$id_klien = $_SESSION['input']['id_klien'];

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
<p>Selamat, paket anda berhasil diperpanjang. Berikut ini adalah detail pembelian anda:</p>

<table border="1" style="border-collapse: collapse; border-color: #d3d3d3;" cellpadding="4" cellspacing="0">
<tr>
<th>Klasifikasi Survey</th>
<td>' . $data_klien->nama_klasifikasi_survei . '</td>
</tr>
<tr>
<th>Nama Paket</th>
<td>' . $data_klien->nama_paket . '</td>
</tr>
<tr>
<th>Panjang Hari</th>
<td>' . $data_klien->panjang_hari . ' hari</td>
</tr>
<tr>
<th>harga Paket</th>
<td>Rp. ' . number_format($data_klien->harga_paket, 2, ',', '.') . '</td>
</tr>
<tr>
<th>Metode Pembayaran</th>
<td>' . $data_klien->nama_metode_pembayaran . '</td>
</tr>
<tr>
<th>Tanggal Aktif</th>
<td>' . date("d-m-Y", strtotime($data_klien->tanggal_mulai)) . '</td>
</tr>
<tr>
<th>Tanggal Kedaluarsa</th>
<td>' . date("d-m-Y", strtotime($data_klien->tanggal_selesai)) . '</td>
</tr>
<tr>
<th>Kode Lisensi</th>
<td>' . $data_klien->kode_lisensi . '</td>
</tr>
</table>

<p>Terima Kasih.</p>
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
			$ci->email->to($data_klien->email);

			// if ($settings->email_cc != '') {
			// 	$this->email->cc($settings->email_cc);
			// }

			$ci->email->subject('Akun Berlangganan Survei E-SKM');
			$ci->email->message($html);
			$this->email->send();
		}


		$array = array(
			'success' => '<div class="alert alert-success">Berhasil menambah melakukan konfirmasi terima pembayaran registrasi dari pendaftar</div>'
		);

		echo json_encode($array);
	}

	public function get_detail()
	{
		$id = $this->input->post('id');

		$this->data = [];
		$this->data['id_klien'] = $id;

		$newdata = array(
			'input'  => $this->data
		);

		$this->session->set_userdata($newdata);

		$this->db->select('*');
		$this->db->from('users');
		$this->db->join('berlangganan', 'berlangganan.id_user = users.id');
		$this->db->join('paket', 'paket.id = berlangganan.id_paket');
		$this->db->join('metode_pembayaran', 'metode_pembayaran.id = berlangganan.id_metode_pembayaran');
		$this->db->join('status_berlangganan', 'status_berlangganan.id = berlangganan.id_status_berlangganan');
		$this->db->join('klasifikasi_survei', 'klasifikasi_survei.id = users.id_klasifikasi_survei');
		$this->db->where('berlangganan.id_user', $id);
		$this->data['pelanggan'] = $this->db->get()->last_row();

		// echo '<pre>';
		// print_r($pelanggan);
		// echo '</pre>';

		return view('berlangganan/detail_berlangganan', $this->data);
	}

	public function serial_number()
	{
		$jumlah_block = 4;
		$jumlah_karakter = 4;
		$serial = '';

		$charset = "48C93F6H1JKLMN0PQR57UVWXY2";
		for ($c = 0; $c < $jumlah_karakter; $c++) {
			$rand = '';
			for ($i = 0; $i < mt_rand($jumlah_block, $jumlah_block); $i++) {
				$rand .= $charset[(mt_rand(0, (strlen($charset) - 1)))];
			}
			$serial .= $rand;
			if ($c < ($jumlah_karakter - 1)) $serial .= "-";
		}
		return $serial;
	}

	public function perpanjangan($id)
	{
		$this->load->model('Berlangganan_model');

		$this->data = array();
		$this->data['title'] 		= 'Perpanjangan Berlangganan';
		$this->data['form_action'] 	= "berlangganan/data-langganan/perpanjangan/$id";

		$this->form_validation->set_rules('id_paket', 'Paket', 'trim|required');
		$this->form_validation->set_rules('id_metode_pembayaran', 'Metode Pembayaran', 'trim|required');
		$this->form_validation->set_rules('tanggal_mulai', 'Nama Jenis Pelayanan', 'trim|required');

		if ($this->form_validation->run() == FALSE) {

			$this->data['id_paket'] = [
				'name' 		=> 'id_paket',
				'id' 		=> 'id_paket',
				'options' 	=> $this->Berlangganan_model->dropdown_paket(),
				'selected' 	=> $this->form_validation->set_value('id_paket'),
				'class' 	=> "form-control",
			];

			$this->data['id_metode_pembayaran'] = [
				'name' 		=> 'id_metode_pembayaran',
				'id' 		=> 'id_metode_pembayaran',
				'options' 	=> $this->Berlangganan_model->dropdown_metode_pembayaran(),
				'selected' 	=> $this->form_validation->set_value('id_metode_pembayaran'),
				'class' 	=> "form-control",
			];

			$query = $this->db->get_where('berlangganan', ['id_user' => $id]);
			$current = $query->last_row();

			$this->data['tanggal_mulai'] = [
				'name' 		=> 'tanggal_mulai',
				'id'		=> 'tanggal_mulai',
				'type'		=> 'date',
				'value'		=>	$this->form_validation->set_value('tanggal_mulai', $current->tanggal_selesai),
				'class'		=> 'form-control',
				'style'		=> 'width: 250px;'
			];

			$this->data['id_user'] = $id;

			return view('berlangganan/form_add_berlangganan', $this->data);
		} else {

			// $data_berlangganan = $this->db->get_where('berlangganan', array('id_user' => $id))->last_row();

			$this->db->query("UPDATE berlangganan SET id_status_berlangganan = 2 WHERE id_user = $id");

			$input 	= $this->input->post(NULL, TRUE);

			// Cek Panjang Berlangganan
			$this->db->select('panjang_hari');
			$this->db->from('paket');
			$this->db->where('id', $input['id_paket']);
			$panjang_hari = $this->db->get()->row()->panjang_hari;

			$this->load->library('uuid');

			$object = [
				'uuid' => $this->uuid->v4(),
				'id_user' 	=> $id,
				'id_paket' 	=> $input['id_paket'],
				'id_metode_pembayaran' 	=> $input['id_metode_pembayaran'],
				'id_status_berlangganan' => 1,
				'tanggal_mulai' 	=> $input['tanggal_mulai'],
				'tanggal_selesai' 	=> date('Y-m-d', strtotime("+$panjang_hari days", strtotime($input['tanggal_mulai']))),
				'kode_lisensi' 	=> $this->serial_number(),
			];

			$this->db->insert('berlangganan', $object);

			if ($this->db->affected_rows() > 0) {

				$this->session->set_flashdata('message_success', 'Berhasil menambah data');
				redirect($this->session->userdata('urlback'), 'refresh');
			} else {

				$this->data['message_data_danger'] = "Gagal menambah data";
				return view('berlangganan/form_add_berlangganan', $this->data);
			}
		}
	}

	public function delete_perpanjangan($id)
	{
		$this->db->where('id', $id);
		$this->db->delete('berlangganan');
		redirect($this->session->userdata('urlback'), 'refresh');
	}

	public function get_detail_ajax()
	{
		$modul  = $this->input->post('modul');
		$id     = $this->input->post('id');

		$this->data = [];
		$this->data['paket'] = $this->db->get_where('paket', ['id' => $id])->row();

		if ($modul == "get_paket") {
			return view("berlangganan/form_detail_paket", $this->data);
		}
	}

	public function edit_perpanjangan($id = NULL)
	{
		$this->data = array();
		$this->data['title'] = 'Edit Perpanjangan';
		$this->data['form_action'] = "berlangganan/data-langganan/edit-perpanjangan/$id";

		$search_data = $this->db->get_where('berlangganan', ['id' => $id]);

		if ($search_data->num_rows() == 0) {

			$this->session->set_flashdata('message_danger', 'Data tidak ditemukan');
			redirect($this->session->userdata('urlback'), 'refresh');
		}

		$current = $search_data->row();

		$this->load->model('Berlangganan_model');

		$this->data['id_paket'] = [
			'name' 		=> 'id_paket',
			'id' 		=> 'id_paket',
			'options' 	=> $this->Berlangganan_model->dropdown_paket(),
			'selected' 	=> $this->form_validation->set_value('id_paket', $current->id_paket),
			'class' 	=> "form-control",
		];

		$this->data['id_metode_pembayaran'] = [
			'name' 		=> 'id_metode_pembayaran',
			'id' 		=> 'id_metode_pembayaran',
			'options' 	=> $this->Berlangganan_model->dropdown_metode_pembayaran(),
			'selected' 	=> $this->form_validation->set_value('id_metode_pembayaran', $current->id_metode_pembayaran),
			'class' 	=> "form-control",
		];

		$this->data['tanggal_mulai'] = [
			'name' 		=> 'tanggal_mulai',
			'id'		=> 'tanggal_mulai',
			'type'		=> 'date',
			'value'		=>	$this->form_validation->set_value('tanggal_mulai', $current->tanggal_mulai),
			'class'		=> 'form-control',
			'style'		=> 'width: 250px;'
		];


		$this->form_validation->set_rules('id_paket', 'Paket', 'trim|required');
		$this->form_validation->set_rules('id_metode_pembayaran', 'Metode Pembayaran', 'trim|required');
		$this->form_validation->set_rules('tanggal_mulai', 'Nama Jenis Pelayanan', 'trim|required');

		if ($this->form_validation->run() == FALSE) {

			return view('berlangganan/form_edit_berlangganan', $this->data);
		} else {

			$input 	= $this->input->post(NULL, TRUE);

			// Cek Panjang Berlangganan
			$this->db->select('panjang_hari');
			$this->db->from('paket');
			$this->db->where('id', $input['id_paket']);
			$panjang_hari = $this->db->get()->row()->panjang_hari;

			$object = [
				'id_paket' 	=> $input['id_paket'],
				'id_metode_pembayaran' 	=> $input['id_metode_pembayaran'],
				'id_status_berlangganan' => 1,
				'tanggal_mulai' 	=> $input['tanggal_mulai'],
				'tanggal_selesai' 	=> date('Y-m-d', strtotime("+$panjang_hari days", strtotime($input['tanggal_mulai']))),
				'kode_lisensi' 	=> null,
			];

			$this->db->where('id', $id);
			$this->db->update('berlangganan', $object);

			if ($this->db->affected_rows() > 0) {

				$this->session->set_flashdata('message_success', 'Berhasil mengubah berlangganan');
				redirect($this->session->userdata('urlback'), 'refresh');
			} else {

				$this->data['message_data_danger'] = "Gagal mengubah berlangganan";
				return view('berlangganan/form_edit_berlangganan', $this->data);
			}
		}
	}
}

/* End of file BerlanggananController.php */
/* Location: ./application/controllers/BerlanggananController.php */