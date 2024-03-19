<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ResellerAreaController extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		
		$this->load->library('form_validation');
	}

	public function index()
	{
		$this->data = [];
		$this->data['title'] = 'Reseller Area';

		$this->db->select('*');
		$content = $this->db->get_where('website_configuration', ['id' => 2])->row();
		$this->data['content'] = $content;

		// SEO
		$this->data['meta_title'] = $content->meta_title;
		$this->data['meta_description'] = $content->meta_description;
		$this->data['meta_keywords'] = $content->meta_keywords;

		return view('reseller_area/index', $this->data);
	}

	public function registration_form()
	{
		$this->data = [];
		$this->data['title'] = 'Form Registrasi Reseller';

		$this->data['form_action'] = 'form-pendaftaran-reseller/validate-message';

		$this->data['full_name'] = [
			'name' 		=> 'full_name',
			'id'		=> 'full_name',
			'type'		=> 'text',
			'value'		=>	$this->form_validation->set_value('full_name'),
			'class'		=> 'form-control',
			'required'	=> 'required',
			'placeholder' => 'Nama Lengkap'
		];

		$this->data['profession'] = [
			'name' 		=> 'profession',
			'id'		=> 'profession',
			'type'		=> 'text',
			'value'		=>	$this->form_validation->set_value('profession'),
			'class'		=> 'form-control',
			'required'	=> 'required',
			'placeholder' => 'Profesi atau jabatan anda bekerja saat ini'
		];

		$this->data['organization'] = [
			'name' 		=> 'organization',
			'id'		=> 'organization',
			'type'		=> 'text',
			'value'		=>	$this->form_validation->set_value('organization'),
			'class'		=> 'form-control',
			'required'	=> 'required',
			'placeholder' => 'Nama organisasi atau tempat anda bekerja saat ini'
		];

		$this->data['email'] = [
			'name' 		=> 'email',
			'id'		=> 'email',
			'type'		=> 'email',
			'value'		=>	$this->form_validation->set_value('email'),
			'class'		=> 'form-control',
			'required'	=> 'required',
			'placeholder' => 'Email'
		];

		$this->data['whatsapp'] = [
			'name' 		=> 'whatsapp',
			'id'		=> 'whatsapp',
			'type'		=> 'text',
			'value'		=>	$this->form_validation->set_value('whatsapp'),
			'class'		=> 'form-control',
			'required'	=> 'required',
			'placeholder' => 'Whatsapp'
		];

		$this->data['reason'] = [
			'data' 		=> 'reason',
			'value' 	=> $this->form_validation->set_value('reason'),
			'id' 		=> 'reason',
			'name'		=> 'reason',
			'class' 	=> 'form-control',
			'required'	=> 'required',
			'placeholder' => 'Jelaskan secara singkat mengapa anda ingin bergabung, gambarkan juga mengapa anda mampu menjalankan program reseller ini. Jawaban anda disini sangat mempengaruhi pertimbangan kami.',
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

		$this->captcha();

		return view('reseller_area/form_registration', $this->data);
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

		$this->form_validation->set_rules('full_name', 'Nama Lengkap', 'trim|required');
		$this->form_validation->set_rules('profession', 'Profesi atau jabatan', 'trim|required');
		$this->form_validation->set_rules('organization', 'Organisasi', 'trim|required');
		$this->form_validation->set_rules('email', 'Email', 'trim|required');
		$this->form_validation->set_rules('whatsapp', 'Whatsapp', 'trim|required');
		$this->form_validation->set_rules('reason', 'Alasan', 'trim|required');
		$this->form_validation->set_rules('kodeCaptcha', 'Kode Captcha', 'trim|required');

		if ($this->form_validation->run()) {

			$input = $this->input->post(NULL, TRUE);

			if($input['kodeCaptcha'] === $this->session->userdata('mycaptcha')){
	            

				$object = [
					'full_name' => $input['full_name'],
					'profession' => $input['profession'],
					'organization' => $input['organization'],
					'email' => $input['email'],
					'whatsapp' => $input['whatsapp'],
					'reason' => $input['reason'],
					'registration_time' => date("Y-m-d h:i:s"),
				];

				$this->db->insert('reseller_request', $object);

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
		        $htmlContent .= "<p>Dear Marketing,</p>
								<p>Berikut diinformasikan, ada pengunjung web https://survei-kepuasan.com yang mendaftar sebagai reseller dengan detail sebagai berikut:</p>
								<p>&nbsp;</p>";

									        $htmlContent .= "
								<table width='100%' border='1'>
								  <tr>
								    <th width='37%' scope='row'><div align='right'>Nama Lengkap</div></th>
								    <td width='8%'><div align='center'><strong>:</strong></div></td>
								    <td width='55%'>".$input['full_name']."</td>
								  </tr>
								  <tr>
								    <th scope='row'><div align='right'>Email</div></th>
								    <td><div align='center'><strong>:</strong></div></td>
								    <td>".$input['email']."</td>
								  </tr>
								  <tr>
								    <th scope='row'><div align='right'>Whatsapp</div></th>
								    <td><div align='center'><strong>:</strong></div></td>
								    <td>".$input['whatsapp']."</td>
								  </tr>
								  <tr>
								    <th scope='row'><div align='right'>Profesi atau jabatan pekerjaan</div></th>
								    <td><div align='center'><strong>:</strong></div></td>
								    <td>".$input['profession']."</td>
								  </tr>
								  <tr>
								    <th scope='row'><label for=''>
								      <div align='right'>Organisasi atau tempat bekerja</div>
								    </label></th>
								    <td><div align='center'><strong>:</strong></div></td>
								    <td>".$input['organization']."</td>
								  </tr>
								  <tr>
								    <th scope='row'><div align='right'>Alasan anda mengikuti program reseller</div></th>
								    <td><div align='center'><strong>:</strong></div></td>
								    <td>".$input['reason']."</td>
								  </tr>
								</table>
		        ";
		       

		        $this->email->initialize($config);
		        $this->email->from(CAPTION_EMAIL_SENDER, 'ADMIN E-SKM');
		        $this->email->to(EMAIL_SENDER);
		        $this->email->subject('PERMINTAAN MENGIKUTI PROGRAM RESELLER');
		        $this->email->message($htmlContent);
		        
		        if ($this->email->send()) {

		        	$htmlContent = '';
					$htmlContent .= "
									<p>Dear ".$input['full_name'].",</p>
									<p>Permintaan anda untuk mengikuti program reseller telah masuk di kotak pesan kami. Kami akan meninjau data anda dan akan menghubungi anda kembali jika permohonan anda disetujui.</p>
									<p>&nbsp;</p>
									<p>Terima Kasih<br />
									<strong>Admin</strong> </p>
					";

					$this->email->from(CAPTION_EMAIL_SENDER, 'ADMIN E-SKM');
			        $this->email->to($input['email']);
			        $this->email->subject('PROGRAM RESELLER');
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


			echo json_encode(array(
				"status" => FALSE, 
				"error" => '<div class="alert alert-warning alert-dismissible fade show" role="alert">
							<strong>Terjadi Kesalahan!</strong> Silahkan mengisi data formulir pesan terlebih dahulu.
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					            <span aria-hidden="true"><i class="ki ki-close"></i></span>
					        </button>
							</div>', 
				"message" => 'Data yang diinput tidak lengkap'
			));

		}

	}

}

/* End of file ResellerAreaController.php */
/* Location: ./application/controllers/ResellerAreaController.php */