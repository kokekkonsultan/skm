<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ProfileController extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		if (!$this->ion_auth->logged_in()) {
			$this->session->set_flashdata('message_warning', 'You must be an admin to view this page');
			redirect('auth', 'refresh');
		}

		$this->load->library('form_validation');
		$this->load->helper('security');

		$this->load->library('image_lib');
		$this->load->helper('file');
	}

	public function index()
	{
		$this->data = [];
		$this->data['title'] = "Profile Anda";
		$this->data['form_action'] = base_url() . 'profile/update-profile';
		$this->data['data_user'] = $this->ion_auth->user()->row();

		$tables = $this->config->item('tables', 'ion_auth');
		$identity_column = $this->config->item('identity', 'ion_auth');
		$this->data['identity_column'] = $identity_column;

		$this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
		$this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
		$this->form_validation->set_rules('phone', 'Phone', 'trim|required');
		$this->form_validation->set_rules('company', 'Company', 'trim|required');

		if ($identity_column !== 'email') {
			$this->form_validation->set_rules('identity', $this->lang->line('create_user_validation_identity_label'), 'trim|required|is_unique[' . $tables['users'] . '.' . $identity_column . ']');
			$this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|required|valid_email');
		} else {
			$this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|required|valid_email|is_unique[' . $tables['users'] . '.email]');
		}

		if ($this->form_validation->run() == FALSE) {
			return view('profile/index', $this->data);
		} else {
			$this->update_profile();
		}
	}

	public function update_foto()
	{
		$this->data['data_user'] = $this->ion_auth->user()->row();

		$output = array('error' => false);
		// $file_ext = pathinfo($_FILES["gambar"]['name'], PATHINFO_EXTENSION);
		// 	$new_name = $current->id . "_" . "$current->username" . "." . $file_ext;

		$images_logo = $_FILES['file']['name'];

		if ($images_logo != "") {

			// $config['file_name'] = $_FILES['file']['name'];
			$nama_file             = strtolower("profil_");
			$config['upload_path'] = 'assets/klien/foto_profile/';
			$config['allowed_types'] = 'png|jpg|jpeg';
			$config['max_size']  = 10000;
			$config['remove_space'] = TRUE;
			$config['overwrite'] = true;
			$config['detect_mime']        = TRUE;
			$config['file_name']         = $nama_file . $this->data['data_user']->id;

			$this->load->library('upload', $config);
			$this->upload->initialize($config);

			if ($this->upload->do_upload('file')) {
				$uploadData = $this->upload->data();
				$filename = $uploadData['file_name'];

				$file['foto_profile'] = $filename;

				// $this->db->insert('files', $file);

				$this->db->where('id', $this->data['data_user']->id);
				$query = $this->db->update('users', $file);

				if ($query) {
					$output['message'] = 'Berhasil mengunggah logo.';
				} else {
					$output['error'] = true;
					$output['message'] = 'Logo diunggah tetapi tidak dimasukkan ke database.';
				}
			} else {
				$output['error'] = true;
				$output['message'] = 'Silahkan pilih hanya logo png/jpg/jpeg!';
			}
		} else {
			$output['error'] = true;
			$output['message'] = 'Silahkan pilih logo yang akan diunggah!';
		}

		echo json_encode($output);
	}

	public function update_profile()
	{
		$this->data = [];
		$this->data['title'] = 'Profile';

		$user = $this->ion_auth->user()->row();


		$data = array(
			'first_name' => $this->input->post('first_name'),
			'last_name' => $this->input->post('last_name'),
			'company' => $this->input->post('company'),
			'phone' => $this->input->post('phone'),
			'email' => $this->input->post('email')
		);
		// var_dump($data);
		$this->db->where('id', $user->id);
		$this->db->update('users', $data);

		$this->session->set_flashdata('msg', 'Berhasil mengubah profil');
		redirect(base_url() . 'profile', 'refresh');
	}

	// public function delete_foto_profile()
	// {
	// 	if ($this->session->userdata('nama_grup') == 'admin') {
	// 		$search = $this->db->get_where('view_pengguna_admin', ['uuid' => $_SESSION['uuid']])->row();
	// 	} else {
	// 		$search = $this->db->get_where('view_pengguna', ['uuid' => $_SESSION['uuid']])->row();
	// 	}


	// 	if (($search->foto_profile != "") or (!empty($search->foto_profile))) {
	// 		unlink('./assets/images/foto-profile/' . $search->foto_profile);
	// 	}

	// 	$object = [
	// 		'foto_profile' => null
	// 	];

	// 	$this->db->where('uuid', $_SESSION['uuid']);
	// 	$this->db->update('pengguna', $object);

	// 	$this->session->set_flashdata('message_success', 'Berhasil menghapus foto profile');
	// 	redirect('update-profile', 'refresh');
	// }

	// public function check_foto_profile($str)
	// {
	// 	$allowed_mime_type_arr = array('image/png',  'image/x-png');
	// 	$mime = get_mime_by_extension($_FILES['foto_profile']['name']);
	// 	if (isset($_FILES['foto_profile']['name']) && $_FILES['foto_profile']['name'] != "") {
	// 		if (in_array($mime, $allowed_mime_type_arr)) {
	// 			return true;
	// 		} else {
	// 			$this->form_validation->set_message('check_foto_profile', 'Silahkan pilih hanya file png atau jpg.');
	// 			return false;
	// 		}
	// 	} else {
	// 		$this->form_validation->set_message('check_foto_profile', 'Silakan pilih file yang akan diunggah.');
	// 		return false;
	// 	}
	// }

	// public function password_check($str)
	// {
	// 	if ($this->input->post('password') == $this->input->post('re_password')) {
	// 		return TRUE;
	// 	} else {
	// 		$this->form_validation->set_message('password_check', 'Field password tidak sama');
	// 		return FALSE;
	// 	}
	// }
}

/* End of file ProfileController.php */
/* Location: ./application/controllers/ProfileController.php */