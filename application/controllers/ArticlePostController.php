<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ArticlePostController extends CI_Controller {

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
		$this->load->library('uuid');
		$this->load->helper('slug');
	}

	public function index()
	{
		$this->data = [];
		$this->data['title'] = 'Article Post';

		return view('article_post/index', $this->data);
	}

	public function ajax_list()
	{
		$this->load->model('ArticlePost_model');

		$list = $this->ArticlePost_model->get_datatables();
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			$user_writter = $this->ion_auth->user($value->written_by_id)->row();

			$checked_is_show = ($value->is_show == 1) ? "checked" : "";

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = '<img src="'.base_url().'assets/img/article/small/'.$value->main_picture.'" alt="">';
			$row[] = $value->title;
			$row[] = '<span class="badge badge-secondary">'.$value->category_name.'</span>';
			$row[] = ($value->alias_name_writter) ? $value->alias_name_writter : $user_writter->first_name.' '.$user_writter->last_name;
			$row[] = 
			'
				<span class="switch switch-sm">
					<label>
						<input value="' . $value->id . '" type="checkbox" name="setting_value" class="toggle_dash_1" ' . $checked_is_show . ' />
						<span></span>
					</label>
				</span>
				';
			$row[] = '<a href="'.base_url().'article-post/edit/'.$value->uuid.'" class="text-primary" title="Edit">Edit</a>';
			$row[] = anchor('article-post/delete/'.$value->uuid, 'Delete', array('onclick'=>"return confirm('Anda yakin ingin menghapus article ?')", 'class'=>'text-danger'));
			$row[] = '<a class="text-primary" href="javascript:void(0)" title="Detail Artikel" onclick="showDetail(' . "'" . $value->uuid . "'" . ')">Preview</a>';

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->ArticlePost_model->count_all(),
			"recordsFiltered" => $this->ArticlePost_model->count_filtered(),
			"data" => $data,
		);

		echo json_encode($output);
	}


	public function list_link_article()
	{
		$this->data = [];
		$this->data['title'] = 'List Link Artikel';

		return view('article_post/list_link_article', $this->data);
	}

	public function ajax_list_link_article()
	{
		$this->load->model('ListLinkArticle_model');

		$list = $this->ListLinkArticle_model->get_datatables();
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $value->title;
			$row[] = "
						<div class='input-group'>
                            <input type='text' class='form-control' id='kt_clipboard_".$no."' value='".base_url()."article/post/".$value->slug."' placeholder='Type some value to copy' />
                            <div class='input-group-append'>
                                <a href='javascript:void(0)' class='btn btn-light-primary font-weight-bold shadow' data-clipboard='true' data-clipboard-target='#kt_clipboard_".$no."'><i class='la la-copy'></i> Copy Link</a>
                            </div>
                        </div>
			";
			$row[] = '<a class="text-primary" href="'.base_url().'article/post/'.$value->slug.'" title="Lihat artikel ini" target="_blank">Lihat artikel</a>';
			// $row[] = '<a class="text-primary" href="javascript:void(0)" title="Detail" onclick="showDetail(' . "'" . $value->uuid . "'" . ')">View</a>';

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->ListLinkArticle_model->count_all(),
			"recordsFiltered" => $this->ListLinkArticle_model->count_filtered(),
			"data" => $data,
		);

		echo json_encode($output);
	}

	public function create()
	{
		$this->data = [];
		$this->data['title'] = 'Buat Artikel';
		$this->data['form_action'] = base_url().'article-post/create';

		$this->load->model('ArticlePost_model');

		$this->form_validation->set_rules('title', 'Judul', 'trim|required');
		$this->form_validation->set_rules('id_article_category', 'Kategori Artikel', 'trim|required');
		$this->form_validation->set_rules('main_picture', '', 'callback_check_file_main_picture');
		$this->form_validation->set_rules('content_value', 'Isi Konten', 'trim');
		$this->form_validation->set_rules('alias_name_writter', 'Alias Nama Penulis', 'trim');
		$this->form_validation->set_rules('seo_title', 'SEO Title', 'trim');
		$this->form_validation->set_rules('seo_description', 'SEO Description', 'trim');
		$this->form_validation->set_rules('seo_keywords', 'SEO Keywords', 'trim');

		if ($this->form_validation->run() == FALSE) {

			$this->data['title'] = [
				'name'		=> 'title',
				'id'		=> 'title',
				'type'		=> 'text',
				'value'		=> $this->form_validation->set_value('title'),
				'class'		=> 'form-control',
				'required'	=> 'required',
			];

			$this->data['id_article_category'] = [
				'name' 		=> 'id_article_category',
				'options' 	=> $this->ArticlePost_model->dropdown_article_category(),
				'selected' 	=> $this->form_validation->set_value('id_article_category'),
				'class' 	=> "form-control",
				'data-placeholder' => 'Pilih salah satu',
				'id' 		=> 'id_article_category',
				'required'	=> 'required',
			];

			$this->data['main_picture'] = [
				'name' 		=> 'main_picture',
				'value' 	=> $this->form_validation->set_value('main_picture'),
				'required'	=> 'required',
			];

			$this->data['content_value'] = [
				'name'		=> 'content_value',
				'id'		=> 'content_value_add',
				'type'		=> 'text',
				'value'		=> $this->form_validation->set_value('content_value'),
				'class'		=> 'tox-target',
			];

			$this->data['alias_name_writter'] = [
				'name'		=> 'alias_name_writter',
				'id'		=> 'alias_name_writter',
				'type'		=> 'text',
				'value'		=> $this->form_validation->set_value('alias_name_writter'),
				'class'		=> 'form-control',
			];

			$this->data['seo_title'] = [
				'name'		=> 'seo_title',
				'id'		=> 'seo_title',
				'type'		=> 'text',
				'value'		=> $this->form_validation->set_value('seo_title'),
				'class'		=> 'form-control',
			];

			$this->data['seo_description'] = [
				'name'		=> 'seo_description',
				'id'		=> 'seo_description',
				'type'		=> 'text',
				'value'		=> $this->form_validation->set_value('seo_description'),
				'class'		=> 'form-control',
			];

			$this->data['seo_keywords'] = [
				'name'		=> 'seo_keywords',
				'id'		=> 'seo_keywords',
				'type'		=> 'text',
				'value'		=> $this->form_validation->set_value('seo_keywords'),
				'class'		=> 'form-control',
			];

			return view('article_post/form_add', $this->data);

		}else{

			// echo '<pre>';
			// print_r($_FILES);
			// echo '</pre>';

			$images_main_picture = $_FILES['main_picture']['name'];

			if ($images_main_picture !="") {
				$config['upload_path']     	= './assets/img/article/';
			    $config['allowed_types']   	= 'jpg|png';	    
			    $config['detect_mime']		= TRUE;
			    $config['max_size']        	= 20000;
			    $nama_file 					= strtolower($this->input->post('title'));
				$config['file_name'] 		= $nama_file."_".time();

				$this->load->library('upload', $config);

				$this->upload->initialize($config);

				if (!$this->upload->do_upload('main_picture'))
				{
					print_r($this->upload->display_errors());

					$this->data['pesan_error'] = 'Terjadi kesalahan input gambar';
					return view('article_post/form_add', $this->data);

				} else {

					$main_picture = $this->upload->data();
					$this->_create_thumbs($main_picture['file_name']);
				}
			}

			$nama_main_picture = $main_picture['file_name'];

			$object_main = [
				'uuid' => $this->uuid->v4(),
				'id_article_category' => $this->input->post('id_article_category'),
				'title' => $this->input->post('title'),
				'slug' => slug($this->input->post('title')),
				'main_picture' => $nama_main_picture,
				'alias_name_writter' => $this->input->post('alias_name_writter'),
				'seo_title' => $this->input->post('seo_title'),
				'seo_description' => $this->input->post('seo_description'),
				'seo_keywords' => $this->input->post('seo_keywords'),
				'written_by_id' => $this->session->userdata('user_id'),
				'created_at' => date("Y/m/d H:i:s"),
			];

			$this->db->insert('article', $object_main);

			$insert_id = $this->db->insert_id();

			$object_content = [
				'article_id' => $insert_id,
				'content_value' => $this->input->post('content_value', false),
			];

			$this->db->insert('article_content', $object_content);
		
			$this->session->set_flashdata('message_success', 'Berhasil menyimpan data');
			redirect(base_url().'article-post','refresh');

		}

		


	}

	public function check_file_main_picture()
	{
		$allowed_mime_type_arr = array('image/jpeg','image/pjpeg','image/png','image/x-png');

        $mime = get_mime_by_extension($_FILES['main_picture']['name']);

        if(isset($_FILES['main_picture']['name']) && $_FILES['main_picture']['name']!=""){

            if(in_array($mime, $allowed_mime_type_arr)){
                
                return TRUE;

            }else{
                $this->form_validation->set_message('check_file_main_picture', 'Silahkan pilih hanya file jpg / png.');
                return FALSE;
            }
        }else{
            $this->form_validation->set_message('check_file_main_picture', 'Silakan pilih file yang akan diunggah.');
            return FALSE;
        }
	}

	function _create_thumbs($file_name){
        // Image resizing config
        $config = array(
            // Image Large
            array(
                'image_library' => 'GD2',
                'source_image'  => './assets/img/article/'.$file_name,
                'maintain_ratio'=> FALSE,
                'width'         => 700,
                'height'        => 467,
                'new_image'     => './assets/img/article/large/'.$file_name
                ),
            // image Medium
            array(
                'image_library' => 'GD2',
                'source_image'  => './assets/img/article/'.$file_name,
                'maintain_ratio'=> FALSE,
                'width'         => 600,
                'height'        => 400,
                'new_image'     => './assets/img/article/medium/'.$file_name
                ),
            // Image Small
            array(
                'image_library' => 'GD2',
                'source_image'  => './assets/img/article/'.$file_name,
                'maintain_ratio'=> FALSE,
                'width'         => 100,
                'height'        => 67,
                'new_image'     => './assets/img/article/small/'.$file_name
            ));
 
        $this->load->library('image_lib', $config[0]);
        foreach ($config as $item){
            $this->image_lib->initialize($item);
            if(!$this->image_lib->resize()){
                return false;
            }
            $this->image_lib->clear();
        }
    }

	public function edit($uuid)
	{
		$this->data = [];
		$this->data['title'] = 'Edit Artikel';
		$this->data['form_action'] = base_url().'article-post/edit/'.$uuid;

		$this->load->model('ArticlePost_model');

		$this->form_validation->set_rules('title', 'Judul', 'trim|required');
		$this->form_validation->set_rules('id_article_category', 'Kategori Artikel', 'trim|required');
		$this->form_validation->set_rules('content_value', 'Isi Konten', 'trim');
		$this->form_validation->set_rules('alias_name_writter', 'Alias Nama Penulis', 'trim');
		$this->form_validation->set_rules('seo_title', 'SEO Title', 'trim');
		$this->form_validation->set_rules('seo_description', 'SEO Description', 'trim');
		$this->form_validation->set_rules('seo_keywords', 'SEO Keywords', 'trim');

		if ($this->input->post('main_picture')) {
			$this->form_validation->set_rules('main_picture', '', 'callback_check_file_main_picture');
		}

		if ($this->form_validation->run() == FALSE){

			$this->db->select('*');
			$this->db->from('article');
			$this->db->join('article_content', 'article_content.article_id = article.id');
			$this->db->where('uuid', $uuid);
			$search_data = $this->db->get();

			if ($search_data->num_rows() == 0) {

				$this->session->set_flashdata('message_warning', 'Data Tidak ditemukan');
				$url = $this->session->userdata('urlback');
				redirect($url);
			}

			$current = $search_data->row();

			$this->data['gambar_sebelumnya'] = $current->main_picture;

			$this->data['title'] = [
				'name'		=> 'title',
				'id'		=> 'title',
				'type'		=> 'text',
				'value'		=> $this->form_validation->set_value('title', $current->title),
				'class'		=> 'form-control',
				'required'	=> 'required',
			];

			$this->data['id_article_category'] = [
				'name' 		=> 'id_article_category',
				'options' 	=> $this->ArticlePost_model->dropdown_article_category(),
				'selected' 	=> $this->form_validation->set_value('id_article_category', $current->id_article_category),
				'class' 	=> "form-control",
				'data-placeholder' => 'Pilih salah satu',
				'id' 		=> 'id_article_category',
				'required'	=> 'required',
			];

			$this->data['main_picture'] = [
				'name' 		=> 'main_picture',
				'value' 	=> $this->form_validation->set_value('main_picture'),
			];

			$this->data['content_value'] = [
				'name'		=> 'content_value',
				'id'		=> 'content_value_edit',
				'type'		=> 'text',
				'value'		=> $this->form_validation->set_value('content_value', $current->content_value),
				'class'		=> 'tox-target',
			];

			$this->data['alias_name_writter'] = [
				'name'		=> 'alias_name_writter',
				'id'		=> 'alias_name_writter',
				'type'		=> 'text',
				'value'		=> $this->form_validation->set_value('alias_name_writter', $current->alias_name_writter),
				'class'		=> 'form-control',
			];

			$this->data['seo_title'] = [
				'name'		=> 'seo_title',
				'id'		=> 'seo_title',
				'type'		=> 'text',
				'value'		=> $this->form_validation->set_value('seo_title', $current->seo_title),
				'class'		=> 'form-control',
			];

			$this->data['seo_description'] = [
				'name'		=> 'seo_description',
				'id'		=> 'seo_description',
				'type'		=> 'text',
				'value'		=> $this->form_validation->set_value('seo_description', $current->seo_description),
				'class'		=> 'form-control',
			];

			$this->data['seo_keywords'] = [
				'name'		=> 'seo_keywords',
				'id'		=> 'seo_keywords',
				'type'		=> 'text',
				'value'		=> $this->form_validation->set_value('seo_keywords', $current->seo_keywords),
				'class'		=> 'form-control',
			];

			return view('article_post/form_edit', $this->data);

		}else{

			$this->db->select('*, article.id AS id_article, article_content.id AS id_article_content');
			$this->db->from('article');
			$this->db->join('article_content', 'article_content.article_id = article.id');
			$this->db->where('article.uuid', $uuid);
			$search_data = $this->db->get()->row();

			if ($_FILES['main_picture']['name'] != NULL) {

				// Remove Image
				unlink('./assets/img/article/'.$search_data->main_picture);
				unlink('./assets/img/article/large/'.$search_data->main_picture);
				unlink('./assets/img/article/medium/'.$search_data->main_picture);
				unlink('./assets/img/article/small/'.$search_data->main_picture);

				// Upload files
				$images_main_picture = $_FILES['main_picture']['name'];

				if ($images_main_picture !="") {

					$config['upload_path']     	= './assets/img/article/';
				    $config['allowed_types']   	= 'jpg|png';	    
				    $config['detect_mime']		= TRUE;
				    $config['max_size']        	= 20000;
				    $nama_file 					= strtolower($this->input->post('title'));
					$config['file_name'] 		= $nama_file."_".time();

					$this->load->library('upload', $config);
					$this->upload->initialize($config);

					if (!$this->upload->do_upload('main_picture')){
						print_r($this->upload->display_errors());

						$this->data['pesan_error'] = 'Terjadi kesalahan input gambar';
						return view('article_post/form_edit', $this->data);

					}else{

						$main_picture = $this->upload->data();
						$this->_create_thumbs($main_picture['file_name']);

					}

					$nama_main_picture = $main_picture['file_name'];

					$object = [
						'main_picture' => $nama_main_picture,
					];

					$this->db->where('id', $search_data->id_article);
					$this->db->update('article', $object);

				}
			}

			$object_main = [
				'id_article_category' => $this->input->post('id_article_category'),
				'title' => $this->input->post('title'),
				'slug' => slug($this->input->post('title')),
				'alias_name_writter' => $this->input->post('alias_name_writter'),
				'seo_title' => $this->input->post('seo_title'),
				'seo_description' => $this->input->post('seo_description'),
				'seo_keywords' => $this->input->post('seo_keywords'),
				'written_by_id' => $this->session->userdata('user_id'),
				'updated_at' => date("Y/m/d H:i:s"),
			];

			$this->db->where('id', $search_data->id_article);
			$this->db->update('article', $object_main);

			$insert_id = $this->db->insert_id();

			$object_content = [
				'content_value' => $this->input->post('content_value', false),
			];

			$this->db->where('id', $search_data->id_article_content);
			$this->db->update('article_content', $object_content);
			
			$this->session->set_flashdata('message_success', 'Berhasil mengubah data');
			redirect(base_url().'article-post','refresh');
		}

		
	}

	public function delete($uuid)
    {
    	$current_data = $this->db->get_where('article', ['uuid' => $uuid])->row();
		unlink('./assets/img/article/'.$current_data->main_picture);
		unlink('./assets/img/article/large/'.$current_data->main_picture);
		unlink('./assets/img/article/medium/'.$current_data->main_picture);
		unlink('./assets/img/article/small/'.$current_data->main_picture);

		$this->db->where('uuid', $uuid);
		$this->db->delete('article');

		$this->session->set_flashdata('message_success', 'Berhasil menghapus post article.');
		redirect(base_url().'article-post','refresh');
    }

    public function get_detail()
	{
		$id = $this->input->post('id');
		$this->data = [];
		$this->data['id'] = $id;

		$this->db->select('*, article.id AS id_article, article_content.id AS id_article_content');
		$this->db->from('article');
		$this->db->join('article_content', 'article_content.article_id = article.id');
		$this->db->where('article.uuid', $id);
		$this->data['article'] = $this->db->get()->row();

		return view('article_post/form_detail', $this->data);

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
			$this->db->update('article', $object);

			$message = 'Article berhasil ditampilkan';
			$success = 'Enabled';
			echo json_encode(array('message' => $message, '$success' => $success));
		} else if ($mode == 'false') //Jika mode bernilai false berarti tombol status disable
		{
			$object = [
				'is_show' => '0'
			];
			$this->db->where('id', $id);
			$this->db->update('article', $object);

			$message = 'Article tidak ditampilkan';
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
			$this->db->update('article', $object);

			$message = 'Article berhasil ditampilkan';
			$success = 'Enabled';
			echo json_encode(array('message' => $message, '$success' => $success));
		} else if ($mode == 'false') //Jika mode bernilai false berarti tombol status disable
		{
			$object = [
				'is_show' => '0'
			];
			$this->db->where('id', $id);
			$this->db->update('article', $object);

			$message = 'Article tidak ditampilkan';
			$success = 'Disabled';
			echo json_encode(array('message' => $message, 'success' => $success));
		}
	}

}

/* End of file ArticlePostController.php */
/* Location: ./application/controllers/ArticlePostController.php */