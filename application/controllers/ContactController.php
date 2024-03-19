<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ContactController extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		
		$this->load->library('form_validation');
	}

	public function index()
	{
		$this->data = [];
		$this->data['title'] = 'Contact';

		$email_address = (isset($_GET['email_address'])) ? $_GET['email_address'] : '';

		$this->data['form_action'] = 'contact/validate-message';

		$this->data['conNama'] = [
			'name' 		=> 'conNama',
			'id'		=> 'conNama',
			'type'		=> 'text',
			'value'		=>	$this->form_validation->set_value('conNama'),
			'class'		=> 'form-control',
			'required'	=> 'required',
			'placeholder' => 'Nama Lengkap'
		];

		$this->data['conEmail'] = [
			'name' 		=> 'conEmail',
			'id'		=> 'conEmail',
			'type'		=> 'email',
			'value'		=>	$this->form_validation->set_value('conEmail', $email_address),
			'class'		=> 'form-control',
			'required'	=> 'required',
			'placeholder' => 'Email'
		];

		$this->data['conTelp'] = [
			'name' 		=> 'conTelp',
			'id'		=> 'conTelp',
			'type'		=> 'text',
			'value'		=>	$this->form_validation->set_value('conTelp'),
			'class'		=> 'form-control',
			'required'	=> 'required',
			'placeholder' => 'Whatsapp'
		];

		$this->data['conOrganisasi'] = [
			'name' 		=> 'conOrganisasi',
			'id'		=> 'conOrganisasi',
			'type'		=> 'text',
			'value'		=>	$this->form_validation->set_value('conOrganisasi'),
			'class'		=> 'form-control',
			'required'	=> 'required',
			'placeholder' => 'Nama organisasi atau nama perusahaan'
		];

		$this->data['conSubject'] = [
			'name' 		=> 'conSubject',
			'id'		=> 'conSubject',
			'type'		=> 'text',
			'value'		=>	$this->form_validation->set_value('conSubject'),
			'class'		=> 'form-control',
			'required'	=> 'required',
			'placeholder' => 'Perihal'
		];

		$this->data['conMessage'] = [
			'data' 		=> 'conMessage',
			'value' 	=> $this->form_validation->set_value('conMessage'),
			'id' 		=> 'conMessage',
			'name'		=> 'conMessage',
			'class' 	=> 'form-control',
			'required'	=> 'required',
			'placeholder' => 'Tinggalkan pesan anda disini'
		];

		$this->data['kodeCaptcha'] = [
			'name' 		=> 'kodeCaptcha',
			'id'		=> 'kodeCaptcha',
			'type'		=> 'text',
			'value'		=>	$this->form_validation->set_value('kodeCaptcha'),
			'class'		=> 'form-control',
			'required'	=> 'required',
			'placeholder' => 'Masukkan kode captcha pada gambar diatas'
		];

		// SEO
		$this->data['meta_title'] = 'Hubungi Kami';
		$this->data['meta_description'] = 'Hubungi kami survei kepuasan masyarakat';
		$this->data['meta_keywords'] = 'Hubungi kami survei kepuasan masyarakat';

		$this->captcha();

		return view('contact/form_contact', $this->data);
	}

	public function captcha()
	{
		$this->load->helper(array('captcha'));
		$config_captcha = array(
			'img_path' => 'captcha/',
            'img_url' => base_url().'captcha/',
            'font_path' => FCPATH . 'captcha/font/Poppins-Regular.ttf',
			'img_width' => 190,
    		'img_height' => 60,
			'border' => 0, 
			'expiration' => 7200,
			'word_length'   => 4,
			'font_size'     => 16,
		);
		// create captcha image
		$cap = create_captcha($config_captcha);
		// store image html code in a variable
		$this->data['gambar_captcha'] = $cap['image'];
		// store the captcha word in a session
		$this->session->set_userdata('mycaptcha', $cap['word']);
	}

	public function refresh_captcha()
	{
		$this->load->helper(array('captcha'));
		$config_captcha = array(
			'img_path' => 'captcha/',
            'img_url' => base_url().'captcha/',
            'font_path' => FCPATH . 'captcha/font/Poppins-Regular.ttf',
			'img_width' => 190,
    		'img_height' => 60,
			'border' => 0, 
			'expiration' => 7200,
			'word_length'   => 4,
			'font_size'     => 16,
		);
		// create captcha image
		$cap = create_captcha($config_captcha);
		// store the captcha word in a session
		$this->session->unset_userdata('mycaptcha');
		$this->session->set_userdata('mycaptcha', $cap['word']);
		// store image html code in a variable
		echo $cap['image'];
	}

	public function validate_message()
	{
		$this->form_validation->set_rules('conNama', 'Nama', 'trim|required');
		$this->form_validation->set_rules('conEmail', 'Email', 'trim|required');
		$this->form_validation->set_rules('conTelp', 'Whatsapp', 'trim');
		$this->form_validation->set_rules('conOrganisasi', 'Organisasi', 'trim');
		$this->form_validation->set_rules('conSubject', 'Subject', 'trim|required');
		$this->form_validation->set_rules('conMessage', 'Message', 'trim|required');
		$this->form_validation->set_rules('kodeCaptcha', 'Kode Captcha', 'trim|required');

		if ($this->form_validation->run()) {

			$input = $this->input->post(NULL, TRUE);

			if($input['kodeCaptcha'] === $this->session->userdata('mycaptcha')){
	            
	   //          $newdata = array(
				// 	'input'  => $_POST
				// );

				// $this->session->set_userdata($newdata);

				

				$object = [
					'conNama'			=> $input['conNama'],
					'conEmail'			=> $input['conEmail'],
					'conTelp'			=> $input['conTelp'],
					'conOrganisasi'		=> $input['conOrganisasi'],
					'conSubject'		=> $input['conSubject'],
					'conMessage'		=> $input['conMessage'],
					'conTime'			=> date("Y-m-d h:i:s"),
					'conStatus'			=> 'n',
				];

				$this->db->insert('contact_me', $object);



			$this->load->helper('email');

			// $ci = get_instance();        
	        $config['protocol']     = "smtp";
	        $config['smtp_host']    = HOST_EMAIL_NAME;
	        $config['smtp_port']    = HOST_EMAIL_PORT;
	        $config['smtp_user']    = USERNAME_EMAIL;
	        $config['smtp_pass']    = PASS_EMAIL;
	        $config['charset']      = "utf-8";
	        $config['mailtype']     = "html";
	        $config['newline']      = "\r\n";
	        $htmlContent = '';
	        $htmlContent .= "<p>Dear Marketing,</p>
<p>Berikut diinformasikan, ada pengunjung web https://survei-kepuasan.com yang megirim pesan melalui halaman kontak dengan detail sebagai berikut:</p>
<p>&nbsp;</p>";

	        $htmlContent .= "
<table width='100%' border='1'>
  <tr>
    <th width='37%' scope='row'><div align='right'>Nama Lengkap</div></th>
    <td width='8%'><div align='center'><strong>:</strong></div></td>
    <td width='55%'>".$input['conNama']."</td>
  </tr>
  <tr>
    <th scope='row'><div align='right'>Email</div></th>
    <td><div align='center'><strong>:</strong></div></td>
    <td>".$input['conEmail']."</td>
  </tr>
  <tr>
    <th scope='row'><div align='right'>Whatsapp</div></th>
    <td><div align='center'><strong>:</strong></div></td>
    <td>".$input['conTelp']."</td>
  </tr>
  <tr>
    <th scope='row'><label for='conOrganisasi'>
      <div align='right'>Nama Organisasi</div>
    </label></th>
    <td><div align='center'><strong>:</strong></div></td>
    <td>".$input['conOrganisasi']."</td>
  </tr>
  <tr>
    <th scope='row'><div align='right'>Perihal</div></th>
    <td><div align='center'><strong>:</strong></div></td>
    <td>".$input['conSubject']."</td>
  </tr>
  <tr>
    <th scope='row'><div align='right'>Pesan</div></th>
    <td><div align='center'><strong>:</strong></div></td>
    <td>".$input['conMessage']."</td>
  </tr>
</table>
	        ";
	       

	        $this->email->initialize($config);
	        $this->email->from(CAPTION_EMAIL_SENDER, 'PT. KOKEK');
	        $this->email->to(EMAIL_SENDER);
	        $this->email->subject($input['conSubject']);
	        $this->email->message($htmlContent);
	        
	        if ($this->email->send()) {

	        	$htmlContent = '';
				$htmlContent .= "
<p>Dear ".$input['conNama'].",</p>
<p>Pesan anda sudah masuk di kotak pesan kami. Kami akan segera menghubungi anda kembali sesegera mungkin.</p>
<p>&nbsp;</p>
<p>Terima Kasih<br />
<strong>Admin</strong> </p>
				";

				$this->email->from(CAPTION_EMAIL_SENDER, 'PT. KOKEK');
		        $this->email->to($input['conEmail']);
		        $this->email->subject('KONTAK KAMI');
		        $this->email->message($htmlContent);


	        	$this->email->send();
	        }




















						echo json_encode(array(
							"status" => TRUE, 
							"success" => '<div class="alert alert-primary" role="alert">
									  <h4 class="alert-heading"><i class="far fa-paper-plane"></i> Well done!</h4>
									  <p>Sukses, pesan yang anda kirimkan telah di inbox kami.</p>
									  <hr>
									  <p class="mb-0">Kami akan segera menindaklanjuti pesan dari anda melalui kontak yang Anda cantumkan.</p>
									</div>'
								));

			        } else {

						echo json_encode(array(
							"status" => FALSE, 
							"error" => '
										<div class="alert alert-warning alert-dismissible fade show" role="alert">
										  <strong>Terjadi Kesalahan!</strong> Captcha yang anda masukkan salah. Silahkan anda masukkan captcha dengan benar.
										  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
										</div>', 
							"message" => 'Captcha yang anda masukkan salah'
						));
						
	        }
			
			

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

/* End of file ContactController.php */
/* Location: ./application/controllers/ContactController.php */