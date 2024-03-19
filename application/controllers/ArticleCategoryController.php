<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ArticleCategoryController extends CI_Controller {

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
		$this->data['title'] = 'Article Category Post';

		$this->data['category_name'] = [
			'name' 		=> 'category_name',
			'id'		=> 'category_name',
			'type'		=> 'text',
			'value'		=>	$this->form_validation->set_value('category_name'),
			'class'		=> 'form-control',
			'required'	=> 'required',
			'placeholder' => 'Nama Kategori',
		];

		return view('article_category_post/index', $this->data);
	}

	public function ajax_list()
	{
		$this->load->model('ArticleCategory_model');

		$list = $this->ArticleCategory_model->get_datatables();
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			if ($value->id == 1) {
				$mark = '<i class="fas fa-bookmark text-dark"></i>';
			} else {
				$mark = '';
			}

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $value->category_name.' '.$mark;
			$row[] = '<a class="text-primary" href="javascript:void(0)" title="Edit" onclick="edit_data(' . "'" . $value->id . "'" . ')">Edit</a>';
			$row[] = '<a class="text-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data(' . "'" . $value->id . "'" . ')">Delete</a>';

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->ArticleCategory_model->count_all(),
			"recordsFiltered" => $this->ArticleCategory_model->count_filtered(),
			"data" => $data,
		);

		echo json_encode($output);
	}

	public function ajax_add()
	{
		$this->_validate_add();

		$object = [
			'category_name' 	=> $this->input->post('category_name'),
		];

		$this->db->insert('article_category', $object);

		echo json_encode(array("status" => TRUE));
	}

	private function _validate_add()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		$this->form_validation->set_rules('category_name', 'Nama kategori Artikel', 'trim|required');

		$this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');


		if ($this->form_validation->run() == FALSE) {
			$data['inputerror'][] = 'category_name';
			$data['error_string'][] = form_error('category_name');
			$data['status'] = FALSE;
		}


		if ($data['status'] === FALSE) {
			echo json_encode($data);
			exit();
		}
	}

	public function ajax_delete($id)
	{
		$cek_data = $this->db->get_where('article_category', ['id' => $id]);

		if ($cek_data->num_rows() > 0) {

			$this->db->where('id', $id);
			$this->db->where('is_default', '0');
			$this->db->delete('article_category');

			if ($this->db->affected_rows() > 0) {
				echo json_encode(array("status" => TRUE));
			}
		}

	}

	public function ajax_edit($id)
	{

		$data = $this->db->get_where('article_category', ['id' => $id])->row();

		echo json_encode($data);
	}

	public function ajax_update()
	{
		$this->_validate_add();

		$object = [
			'category_name' 	=> $this->input->post('category_name'),
		];

		$this->db->where('id', $this->input->post('id'));
		$this->db->update('article_category', $object);

		echo json_encode(array("status" => TRUE));
	}

}

/* End of file ArticleCategoryController.php */
/* Location: ./application/controllers/ArticleCategoryController.php */