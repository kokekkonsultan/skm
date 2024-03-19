<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class InboxController extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		
		if (!$this->ion_auth->logged_in()) {
			$this->session->set_flashdata('message_warning', 'You must be an admin to view this page');
			redirect('auth', 'refresh');
		}
	}

	public function index()
	{
		$this->data = [];
		$this->data['title'] = "Kontak Masuk";

		return view('inbox/index', $this->data);
	}

	public function ajax_list()
	{
		$this->load->model('Inbox_model');

		$list = $this->Inbox_model->get_datatables();
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $value->conNama;
			$row[] = $value->conOrganisasi;
			$row[] = $value->conEmail;
			$row[] = $value->conTelp;
			$row[] = date('d F Y, h:i A', strtotime($value->conTime));
			$row[] = '<a class="text-primary" data-toggle="modal" title="Klik untuk melihat detail pendaftar" onclick="showdata('.$value->conId.')" href="#modal_userDetail">Details</a>';

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->Inbox_model->count_all(),
			"recordsFiltered" => $this->Inbox_model->count_filtered(),
			"data" => $data,
		);

		echo json_encode($output);
	}

	public function delete_inbox($id)
	{
		$this->db->where('conId', $id);
		$this->db->delete('contact_me');

		echo json_encode(array("status" => TRUE));
	}

	public function delete_reply($id)
	{
		$this->db->where('id', $id);
		$this->db->delete('contact_reply');

		echo json_encode(array("status" => TRUE));
	}

	public function get_data_inbox()
	{
		

		$id = $this->input->post('id');

		$data_inbox = $this->db->get_where('contact_me', ['conId' => $id])->row();
		$data_pesan_balasan = $this->db->get_where('contact_reply', ['id_contact' => $id]);

		$this->data = [];
		$this->data['data_inbox'] = $data_inbox;
		$this->data['data_pesan_balasan'] = $data_pesan_balasan;
		

		return view('inbox/form_get_data_inbox', $this->data);
	}

	public function reply($id)
	{
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->data = [];
		$this->data['title'] = "Balas Kontak Masuk";
		$this->data['form_action'] = 'inbox/validate-message';

		$data_inbox = $this->db->get_where('contact_me', ['conId' => $id])->row();
		$this->data['data_inbox'] = $data_inbox;

		$this->data['conSubject'] = [
			'name' 		=> 'conSubject',
			'id'		=> 'conSubject',
			'type'		=> 'text',
			'value'		=>	$this->form_validation->set_value('conSubject'),
			'class'		=> 'form-control',
			'required'	=> 'required',
			'placeholder' => 'Subjek'
		];

		$this->data['conMessage'] = [
			'data' 		=> 'conMessage',
			'value' 	=> $this->form_validation->set_value('conMessage'),
			'id' 		=> 'content',
			'name'		=> 'conMessage',
			'class' 	=> 'form-control',
			'required'	=> 'required',
			'placeholder' => 'Pesan'
		];

		return view('inbox/form_reply', $this->data);
	}

	public function validate_message()
	{
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('conSubject', 'Subject', 'trim|required');
		$this->form_validation->set_rules('conMessage', 'Message', 'required');

		if ($this->form_validation->run()) {

			$input = $this->input->post(NULL, TRUE);

			$object = [
					'id_contact'	=> $input['id_contact'],
					'subjek'		=> $input['conSubject'],
					'isi_pesan'		=> $input['conMessage'],
					'waktu_kirim'	=> date("Y-m-d h:i:s"),
				];

				$this->db->insert('contact_reply', $object);



			$this->load->helper('email');

	        $config['protocol']     = "smtp";
	        $config['smtp_host']    = HOST_EMAIL_NAME;
	        $config['smtp_port']    = HOST_EMAIL_PORT;
	        $config['smtp_user']    = USERNAME_EMAIL;
	        $config['smtp_pass']    = PASS_EMAIL;
	        $config['charset']      = "utf-8";
	        $config['mailtype']     = "html";
	        $config['newline']      = "\r\n";
	        $htmlContent = '';
	        $htmlContent .= $input['conMessage'];
	       

	        $this->email->initialize($config);
	        $this->email->from(CAPTION_EMAIL_SENDER, 'PT. KOKEK');
	        $this->email->to($input['email_contact']);
	        $this->email->subject($input['conSubject']);
	        $this->email->message($htmlContent);
	        
	        if ($this->email->send()) {

	        	$htmlContent = '';
				$htmlContent .= "
<p>Pesan balasan anda sudah berhasil dikirim.</p>
<p>Dikirim ke : ".$input['email_contact']."</p>
<p>Subjek : ".$input['conSubject']."</p>
<p>Isi Balasan : ".$input['conMessage']."</p>
				";

				$this->email->from(CAPTION_EMAIL_SENDER, 'PT. KOKEK');
		        $this->email->to(EMAIL_SENDER);
		        $this->email->subject('KONTAK KAMI');
		        $this->email->message($htmlContent);


	        	$this->email->send();
	        }


			echo json_encode(array("status" => TRUE, "success" => '<div class="alert alert-primary" role="alert">
							  <h4 class="alert-heading"><i class="far fa-paper-plane"></i> Well done!</h4>
							  <p>Sukses, pesan yang anda kirimkan telah di inbox kami.</p>
							  <hr>
							  <p class="mb-0">Kami akan segera menindaklanjuti pesan dari anda melalui kontak yang Anda cantumkan.</p>
							</div>'));
			
			

		} else {


			echo json_encode(array("status" => FALSE, "error" => '<div class="alert alert-warning alert-dismissible fade show" role="alert">
							<strong>Terjadi Kesalahan!</strong> Silahkan mengisi data formulir pesan terlebih dahulu.
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					            <span aria-hidden="true"><i class="ki ki-close"></i></span>
					        </button>
							</div>', "message" => 'Data yang diinput tidak lengkap'));

		}

	}

}

/* End of file InboxController.php */
/* Location: ./application/controllers/InboxController.php */