<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Carbon\Carbon;

class BannerController extends CI_Controller
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

		$this->load->library('image_lib');
		$this->load->helper('file');
		$this->load->helper('security');
	}

	public function index()
	{
		$this->data = [];
		$this->data['title'] = 'Slide Banner';

		return view('banner/index', $this->data);
	}

	public function ajax_list()
	{
		$this->load->model('Banner_model');

		$list = $this->Banner_model->get_datatables();
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			$checked_is_read_more = ($value->button_read_more_active == 1) ? "checked" : "";
			$checked_is_contact = ($value->button_contact_active == 1) ? "checked" : "";
			$checked_is_show = ($value->is_show == 1) ? "checked" : "";

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = '<img src="' . base_url() . 'assets/img/banner/small/' . $value->banner_file . '" alt="">';
			$row[] = $value->banner_name;

			$row[] =
				'
				<span class="switch switch-sm">
					<label>
						<input value="' . $value->id . '" type="checkbox" name="setting_value" class="toggle_dash_1" ' . $checked_is_read_more . ' />
						<span></span>
					</label>
				</span>
				';

			$row[] =
				'
				<span class="switch switch-sm">
					<label>
						<input value="' . $value->id . '" type="checkbox" name="setting_value" class="toggle_dash_2" ' . $checked_is_contact . ' />
						<span></span>
					</label>
				</span>
				';

			$row[] =
				'
				<span class="switch switch-sm">
					<label>
						<input value="' . $value->id . '" type="checkbox" name="setting_value" class="toggle_dash_3" ' . $checked_is_show . ' />
						<span></span>
					</label>
				</span>
				';

			$row[] = anchor('banner/edit/' . $value->id, 'Edit', array('class' => 'text-primary'));
			$row[] = anchor('banner/delete/' . $value->id, 'Delete', array('onclick' => "return confirm('Anda yakin ingin menghapus banner ?')", 'class' => 'text-danger'));
			$row[] = '<a class="text-primary" href="javascript:void(0)" title="View" onclick="showDetail(' . "'" . $value->id . "'" . ')">View</a>';

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->Banner_model->count_all(),
			"recordsFiltered" => $this->Banner_model->count_filtered(),
			"data" => $data,
		);

		echo json_encode($output);
	}

	public function get_detail()
	{
		$id = $this->input->post('id');
		$this->data = [];
		$this->data['id'] = $id;
		$this->data['banner'] = $this->db->get_where('banner', ['id' => $id])->row();

		return view('banner/form_detail', $this->data);
	}

	public function get_detail_link()
	{
		$id = $this->input->post('id');

		return view('banner/form_list_link');
	}

	public function check_file_banner($str)
	{
		if (!$this->ion_auth->is_admin()) {
			echo 'You cannot access this page.';
		} else {
			$allowed_mime_type_arr = array('image/jpeg', 'image/pjpeg', 'image/png', 'image/x-png');
			$mime = get_mime_by_extension($_FILES['banner_file']['name']);
			if (isset($_FILES['banner_file']['name']) && $_FILES['banner_file']['name'] != "") {
				if (in_array($mime, $allowed_mime_type_arr)) {
					return true;
				} else {
					$this->form_validation->set_message('check_file_banner', 'Silahkan pilih hanya file jpg / png.');
					return false;
				}
			} else {
				$this->form_validation->set_message('check_file_banner', 'Silakan pilih file yang akan diunggah.');
				return false;
			}
		}
	}

	public function add()
	{
		$this->data = [];
		$this->data['title'] = 'Buat Slide Banner';
		$this->data['form_action'] = base_url() . 'banner/add';

		$this->form_validation->set_rules('banner_name', 'Nama Banner', 'trim|required');
		$this->form_validation->set_rules('banner_file', '', 'callback_check_file_banner');
		$this->form_validation->set_rules('banner_title', 'Judul Banner', 'trim');
		$this->form_validation->set_rules('banner_description', 'Deskripsi Banner', 'trim');
		$this->form_validation->set_rules('button_read_more_active', 'Tombol Baca Selengkapnya Aktif', 'trim');
		$this->form_validation->set_rules('read_more_link', 'Link Baca Selengkapnya', 'trim');
		$this->form_validation->set_rules('button_contact_active', 'Tombol Kontak Aktif', 'trim');
		$this->form_validation->set_rules('contact_link', 'Link Kontak', 'trim');

		if ($this->form_validation->run() == FALSE) {

			$this->data['pesan_error'] = "Terjadi Kesalahan Pengisian";
			return view('banner/form_add', $this->data);
		} else {

			// echo '<pre>';
			// print_r($_FILES);
			// echo '</pre>';

			$images_file_banner = $_FILES['banner_file']['name'];

			if ($images_file_banner != "") {
				$config['upload_path']     	= './assets/img/banner/';
				$config['allowed_types']   	= 'jpg|png';
				$config['detect_mime']		= TRUE;
				$config['max_size']        	= 20000;
				$nama_file 					= strtolower($this->input->post('banner_name'));
				$config['file_name'] 		= $nama_file . "_" . time();

				$this->load->library('upload', $config);
				$this->upload->initialize($config);

				if (!$this->upload->do_upload('banner_file')) {

					print_r($this->upload->display_errors());

					$this->data['message_data_warning'] = 'Terjadi kesalahan input gambar';
					return view('banner/form_add', $this->data);
				} else {

					$file_banner = $this->upload->data();
					// Membuat ukuran kecil bisa disini
					$this->_create_thumbs($file_banner['file_name']);
				}
			}

			$nama_file_banner = $file_banner['file_name'];

			$object = array(
				'banner_name' 				=> $this->input->post('banner_name'),
				'banner_file' 				=> $nama_file_banner,
				'banner_title' 				=> $this->input->post('banner_title'),
				'banner_description' 		=> $this->input->post('banner_description'),
				'button_read_more_active' 	=> $this->input->post('button_read_more_active'),
				'read_more_link' 			=> $this->input->post('read_more_link'),
				'button_contact_active' 	=> $this->input->post('button_contact_active'),
				'contact_link' 				=> $this->input->post('contact_link'),
				'is_default' 				=> '0',
				'is_show' 					=> '1',
			);

			$this->db->insert('banner', $object);

			$this->session->set_flashdata('message_success', 'Berhasil menambahkan banner.');

			redirect(base_url() . 'banner', 'refresh');
		}
	}

	function _create_thumbs($file_name)
	{
		// Image resizing config
		$config = array(
			// Image Large
			array(
				'image_library' => 'GD2',
				'source_image'  => './assets/img/banner/' . $file_name,
				'maintain_ratio' => FALSE,
				'width'         => 700,
				'height'        => 467,
				'new_image'     => './assets/img/banner/large/' . $file_name
			),
			// image Medium
			array(
				'image_library' => 'GD2',
				'source_image'  => './assets/img/banner/' . $file_name,
				'maintain_ratio' => FALSE,
				'width'         => 600,
				'height'        => 400,
				'new_image'     => './assets/img/banner/medium/' . $file_name
			),
			// Image Small
			array(
				'image_library' => 'GD2',
				'source_image'  => './assets/img/banner/' . $file_name,
				'maintain_ratio' => FALSE,
				'width'         => 100,
				'height'        => 67,
				'new_image'     => './assets/img/banner/small/' . $file_name
			)
		);

		$this->load->library('image_lib', $config[0]);
		foreach ($config as $item) {
			$this->image_lib->initialize($item);
			if (!$this->image_lib->resize()) {
				return false;
			}
			$this->image_lib->clear();
		}
	}

	public function edit($id)
	{
		$this->data = [];
		$this->data['title'] = 'Edit Slide Banner';
		$this->data['form_action'] = base_url() . 'banner/edit/' . $id;


		$this->form_validation->set_rules('banner_name', 'Nama Banner', 'trim|required|xss_clean');
		$this->form_validation->set_rules('banner_title', 'Judul Banner', 'trim');
		$this->form_validation->set_rules('banner_description', 'Deskripsi Banner', 'trim');
		$this->form_validation->set_rules('button_read_more_active', 'Tombol Baca Selengkapnya Aktif', 'trim');
		$this->form_validation->set_rules('read_more_link', 'Link Baca Selengkapnya', 'trim');
		$this->form_validation->set_rules('button_contact_active', 'Tombol Kontak Aktif', 'trim');
		$this->form_validation->set_rules('contact_link', 'Link Kontak', 'trim');


		if ($this->input->post('banner_file')) {
			$this->form_validation->set_rules('banner_file', '', 'callback_check_file_banner');
		}

		if ($this->form_validation->run() == FALSE) {

			$search = $this->db->get_where('banner', ['id' => $id])->row();

			if ($search) {

				foreach ($search as $key => $value) {
					$this->data['form_value'][$key] = $value;
				}

				return view('banner/form_edit', $this->data);
			} else {

				$this->session->set_flashdata('message_warning', 'Data tidak ditemukan.');
				redirect(base_url() . 'banner', 'refresh');
			}
		} else {

			if ($_FILES['banner_file']['name'] != NULL) {

				// REMOVE BANNER
				$search = $this->db->get_where('banner', ['id' => $id])->row();

				if (($search->banner_file != "") or (!empty($search->banner_file))) {
					unlink('./assets/img/banner/' . $search->banner_file);
					unlink('./assets/img/banner/large/' . $search->banner_file);
					unlink('./assets/img/banner/medium/' . $search->banner_file);
					unlink('./assets/img/banner/small/' . $search->banner_file);
				}

				// UPLOAD Banner
				$images_file_banner = $_FILES['banner_file']['name'];

				if ($images_file_banner != "") {

					$config['upload_path']     	= './assets/img/banner/';
					$config['allowed_types']   	= 'jpg|png';
					$config['detect_mime']		= TRUE;
					$config['max_size']        	= 20000;
					$nama_file 					= strtolower($this->input->post('banner_name'));
					$config['file_name'] 		= $nama_file . "_" . time();

					$this->load->library('upload', $config);
					$this->upload->initialize($config);

					if (!$this->upload->do_upload('banner_file')) {
						print_r($this->upload->display_errors());

						$this->data['pesan_error'] = 'Terjadi kesalahan input gambar';
						return view('banner/form_edit', $this->data);
					} else {
						$file_banner = $this->upload->data();
						$this->_create_thumbs($file_banner['file_name']);
					}

					$data = array(
						'banner_file' => $file_banner['file_name']
					);

					$this->db->where('id', $id);
					$query = $this->db->update('banner', $data);
				}
			}

			$object = array(
				'banner_name' 				=> $this->input->post('banner_name'),
				'banner_title' 				=> $this->input->post('banner_title'),
				'banner_description' 		=> $this->input->post('banner_description'),
				'button_read_more_active' 	=> $this->input->post('button_read_more_active'),
				'read_more_link' 			=> $this->input->post('read_more_link'),
				'button_contact_active' 	=> $this->input->post('button_contact_active'),
				'contact_link' 				=> $this->input->post('contact_link'),
			);
			$this->db->where('id', $id);
			$this->db->update('banner', $object);

			$this->session->set_flashdata('message_success', 'Berhasil mengupdate banner.');

			redirect(base_url() . 'banner', 'refresh');
		}
	}

	public function delete($id)
	{
		$current_data = $this->db->get_where('banner', ['id' => $id])->row();
		unlink('./assets/img/banner/' . $current_data->banner_file);
		unlink('./assets/img/banner/large/' . $current_data->banner_file);
		unlink('./assets/img/banner/medium/' . $current_data->banner_file);
		unlink('./assets/img/banner/small/' . $current_data->banner_file);

		$this->db->where('id', $id);
		$this->db->delete('banner');

		$this->session->set_flashdata('message_success', 'Berhasil menghapus banner.');
		redirect(base_url() . 'banner', 'refresh');
	}

	public function update_read_more_active()
	{
		$mode = $_POST['mode'];
		$id = $_POST['nilai_id'];

		if ($mode == 'true') //Jika mode bernilai true berarti tombol status enable
		{
			$object = [
				'button_read_more_active' => '1'
			];
			$this->db->where('id', $id);
			$this->db->update('banner', $object);

			$message = 'Tombol read more berhasil diaktifkan';
			$success = 'Enabled';
			echo json_encode(array('message' => $message, '$success' => $success));
		} else if ($mode == 'false') //Jika mode bernilai false berarti tombol status disable
		{
			$object = [
				'button_read_more_active' => '0'
			];
			$this->db->where('id', $id);
			$this->db->update('banner', $object);

			$message = 'Tombol read more berhasil dinonaktifkan';
			$success = 'Disabled';
			echo json_encode(array('message' => $message, 'success' => $success));
		}
	}

	public function update_read_more_active_value()
	{
		$mode = $_POST['mode'];
		$id = $_POST['nilai_id'];

		if ($mode == 'true') //Jika mode bernilai true berarti tombol status enable
		{
			$object = [
				'button_read_more_active' => '1'
			];
			$this->db->where('id', $id);
			$this->db->update('banner', $object);

			$message = 'Tombol read more berhasil diaktifkan';
			$success = 'Enabled';
			echo json_encode(array('message' => $message, '$success' => $success));
		} else if ($mode == 'false') //Jika mode bernilai false berarti tombol status disable
		{
			$object = [
				'button_read_more_active' => '0'
			];
			$this->db->where('id', $id);
			$this->db->update('banner', $object);

			$message = 'Tombol read more berhasil dinonaktifkan';
			$success = 'Disabled';
			echo json_encode(array('message' => $message, 'success' => $success));
		}
	}

	public function update_contact_active()
	{
		$mode = $_POST['mode'];
		$id = $_POST['nilai_id'];

		if ($mode == 'true') //Jika mode bernilai true berarti tombol status enable
		{
			$object = [
				'button_contact_active' => '1'
			];
			$this->db->where('id', $id);
			$this->db->update('banner', $object);

			$message = 'Tombol kontak berhasil diaktifkan';
			$success = 'Enabled';
			echo json_encode(array('message' => $message, '$success' => $success));
		} else if ($mode == 'false') //Jika mode bernilai false berarti tombol status disable
		{
			$object = [
				'button_contact_active' => '0'
			];
			$this->db->where('id', $id);
			$this->db->update('banner', $object);

			$message = 'Tombol kontak berhasil dinonaktifkan';
			$success = 'Disabled';
			echo json_encode(array('message' => $message, 'success' => $success));
		}
	}

	public function update_contact_active_value()
	{
		$mode = $_POST['mode'];
		$id = $_POST['nilai_id'];

		if ($mode == 'true') //Jika mode bernilai true berarti tombol status enable
		{
			$object = [
				'button_contact_active' => '1'
			];
			$this->db->where('id', $id);
			$this->db->update('banner', $object);

			$message = 'Tombol kontak berhasil diaktifkan';
			$success = 'Enabled';
			echo json_encode(array('message' => $message, '$success' => $success));
		} else if ($mode == 'false') //Jika mode bernilai false berarti tombol status disable
		{
			$object = [
				'button_contact_active' => '0'
			];
			$this->db->where('id', $id);
			$this->db->update('banner', $object);

			$message = 'Tombol kontak berhasil dinonaktifkan';
			$success = 'Disabled';
			echo json_encode(array('message' => $message, 'success' => $success));
		}
	}

	public function update_is_show()
	{
		$mode = $_POST['mode'];
		$id = $_POST['nilai_id'];

		if ($mode == 'true') //Jika mode bernilai true berarti tombol status enable
		{
			$object = [
				'is_show' => '1'
			];
			$this->db->where('id', $id);
			$this->db->update('banner', $object);

			$message = 'Banner berhasil ditampilkan';
			$success = 'Enabled';
			echo json_encode(array('message' => $message, '$success' => $success));
		} else if ($mode == 'false') //Jika mode bernilai false berarti tombol status disable
		{
			$object = [
				'is_show' => '0'
			];
			$this->db->where('id', $id);
			$this->db->update('banner', $object);

			$message = 'Banner tidak ditampilkan';
			$success = 'Disabled';
			echo json_encode(array('message' => $message, 'success' => $success));
		}
	}

	public function update_is_show_value()
	{
		$mode = $_POST['mode'];
		$id = $_POST['nilai_id'];

		if ($mode == 'true') //Jika mode bernilai true berarti tombol status enable
		{
			$object = [
				'is_show' => '1'
			];
			$this->db->where('id', $id);
			$this->db->update('banner', $object);

			$message = 'Banner berhasil ditampilkan';
			$success = 'Enabled';
			echo json_encode(array('message' => $message, '$success' => $success));
		} else if ($mode == 'false') //Jika mode bernilai false berarti tombol status disable
		{
			$object = [
				'is_show' => '0'
			];
			$this->db->where('id', $id);
			$this->db->update('banner', $object);

			$message = 'Banner tidak ditampilkan';
			$success = 'Disabled';
			echo json_encode(array('message' => $message, 'success' => $success));
		}
	}
}

/* End of file BannerController.php */
/* Location: ./application/controllers/BannerController.php */