<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ImageUploadController extends CI_Controller {

	private $datauser;

	public function __construct()
	{
		parent::__construct();
		
		$this->load->library('ion_auth');

		if (!$this->ion_auth->logged_in()) {
			$this->session->set_flashdata('message_warning', 'You must be logged in to access this page');
			redirect('auth', 'refresh');
		}

		$this->load->library('form_validation');
		$this->load->helper('file');
	}

	public function index()
	{
		$this->data = [];
		$this->data['title'] = 'Upload Gambar/ Foto';

		return view('image_upload/index', $this->data);
	}

	public function ajax_list()
	{
		$this->load->model('ImageUpload_model');

		$list = $this->ImageUpload_model->get_datatables();
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $value->image_file_name;
			$row[] = "
						<div class='input-group'>
                            <input type='text' class='form-control' id='kt_clipboard_".$no."' value='".base_url()."assets/img/img-upload/".$value->image_file_name."' placeholder='Type some value to copy' />
                            <div class='input-group-append'>
                                <a href='javascript:void(0)' class='btn btn-light-primary font-weight-bold shadow' data-clipboard='true' data-clipboard-target='#kt_clipboard_".$no."'><i class='la la-copy'></i> Copy Url</a>
                            </div>
                        </div>
			";
			$row[] = '<a class="text-primary" href="javascript:void(0)" title="View" onclick="showDetail(' . "'" . $value->id . "'" . ')">View</a>';
			$row[] = '<a class="text-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data(' . "'" . $value->id . "'" . ')">Delete</a>';

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->ImageUpload_model->count_all(),
			"recordsFiltered" => $this->ImageUpload_model->count_filtered(),
			"data" => $data,
		);

		echo json_encode($output);
	}

	public function get_detail()
	{
		$id = $this->input->post('id');
		$this->data = [];

		$this->db->select('*');
		$this->db->from('image_upload');
		$this->db->where('id', $id);
		$this->data['image'] = $this->db->get()->row();

		return view('image_upload/form_detail', $this->data);

	}

	public function ajax_delete($id)
    {
    	$current_data = $this->db->get_where('image_upload', ['id' => $id])->row();

		unlink('./assets/img/img-upload/'.$current_data->image_file_name);

		$this->db->where('id', $id);
		$this->db->delete('image_upload');

		echo json_encode(array("status" => TRUE));
    }

	function process_upload() {

		/*if (!empty($_FILES)) {
			$tempFile = $_FILES['file']['tmp_name'];
			$fileName = $_FILES['file']['name'];
			$fileType = $_FILES['file']['type'];
			$fileSize = $_FILES['file']['size'];
			$targetPath = './assets/img/img-upload/';
			$targetFile = $targetPath . $fileName ;

			move_uploaded_file($tempFile, $targetFile);
			
			$this->db->insert('image_upload',array('image_file_name' => $fileName, 'image_type' => $fileType, 'image_size' => $fileSize));
		}*/

		if(!empty($_FILES['file']['name'])){
				
			$config['upload_path'] = './assets/img/img-upload/';	
			$config['allowed_types'] = 'jpg|jpeg|png|gif';
			$config['max_size']    = '1024'; // max_size in kb
			$config['file_name'] = $_FILES['file']['name'];
					
			$this->load->library('upload',$config);			
				
			if($this->upload->do_upload('file')){
				
				$uploadData = $this->upload->data();

				$fileName = $_FILES['file']['name'];
				$fileType = $_FILES['file']['type'];
				$fileSize = $_FILES['file']['size'];

				$object = [
					'image_file_name' => str_replace(" ","_", $fileName),
					'image_type' => $fileType,
					'image_size' => $fileSize,
				];

				$this->db->insert('image_upload', $object);
			}

		}
	}

}

/* End of file ImageUploadController.php */
/* Location: ./application/controllers/ImageUploadController.php */