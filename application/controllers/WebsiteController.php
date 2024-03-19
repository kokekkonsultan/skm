<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class WebsiteController extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		
		$this->load->helper('security');
	}

	public function index()
	{
		$this->data = [];
		$this->data['title'] = ' Konfigurasi Website';
		$this->data['web_config'] = $this->db->get_where('website_configuration', ['id' => 1])->row();

		$this->data['home_config'] = $this->db->query("
			SELECT
			( SELECT constant_value FROM website_constant WHERE id = 1) AS website_title,
			( SELECT constant_value FROM website_constant WHERE id = 2) AS website_description,
			( SELECT constant_value FROM website_constant WHERE id = 3) AS website_object_title,
			( SELECT constant_value FROM website_constant WHERE id = 4) AS website_object_1,
			( SELECT constant_value FROM website_constant WHERE id = 5) AS website_object_2,
			( SELECT constant_value FROM website_constant WHERE id = 6) AS website_object_3,
			( SELECT constant_value FROM website_constant WHERE id = 7) AS website_object_4
			FROM
			website_constant LIMIT 1
			")->row();

		return view('website/form_configuration', $this->data);
	}

	public function update_website_configuration()
	{
		$object = [
			'meta_title' => $this->input->post('meta_title'),
			'meta_description' => $this->input->post('meta_description'),
			'meta_keywords' => $this->input->post('meta_keywords'),
			'meta_copyright' => $this->input->post('meta_copyright'),
			'meta_author' => $this->input->post('meta_author'),
			'meta_language' => $this->input->post('meta_language'),
		];

		$this->db->where('id', 1);
		$this->db->update('website_configuration', $object);


		$pesan = 'Pengaturan berhasil disimpan';
		$msg = ['sukses' => $pesan];
		echo json_encode($msg);
	}

	public function update_home_configuration()
	{

		$website_title = $this->input->post('website_title');
		$website_description = $this->input->post('website_description');
		$website_object_title = $this->input->post('website_object_title');
		$website_object_1 = $this->input->post('website_object_1', false);
		$website_object_2 = $this->input->post('website_object_2', false);
		$website_object_3 = $this->input->post('website_object_3', false);
		$website_object_4 = $this->input->post('website_object_4', false);

		$query = "
UPDATE `website_constant`
SET `constant_value`= (CASE
when `id` = '1' then '$website_title'
when `id` = '2' then '$website_description'
when `id` = '3' then '$website_object_title'
when `id` = '4' then '$website_object_1'
when `id` = '5' then '$website_object_2'
when `id` = '6' then '$website_object_3'
when `id` = '7' then '$website_object_4'
		";

		$query .= "
ELSE `constant_value` END)
		";

		$this->db->query($query);

		$pesan = 'Pengaturan berhasil disimpan';
		$msg = ['sukses' => $pesan];
		echo json_encode($msg);
	}

	public function ajax_edit_reseller_area($id)
	{
		$data = $this->db->get_where('website_configuration', ['id' => 2])->row();

		echo json_encode($data);
	}

	public function update_reseller_area_configuration()
	{
		$object = [
			'meta_title' => $this->input->post('meta_title_reseller'),
			'meta_description' => $this->input->post('meta_description_reseller'),
			'meta_keywords' => $this->input->post('meta_keywords_reseller'),
			'content_page' => $this->input->post('instansiasi_content_page', FALSE),
		];

		$this->db->where('id', 2);
		$this->db->update('website_configuration', $object);

		echo json_encode(array("status" => TRUE));
	}

}

/* End of file WebsiteController.php */
/* Location: ./application/controllers/WebsiteController.php */