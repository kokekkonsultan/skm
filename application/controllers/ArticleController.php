<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ArticleController extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->load->helper('text');

		$this->data = [];
		$this->data['title'] = 'All Article';

		$this->db->select('*, article.id AS id_article, article_content.id AS id_article_content');
		$this->db->from('article');
		$this->db->join('article_content', 'article_content.article_id = article.id');
		$posts = $this->db->get();

		$this->data['posts'] = $posts->result();

		return view('article/index', $this->data);
	}

	public function view($slug)
	{
		$this->db->select('*, article.id AS id_article, article_content.id AS id_article_content');
		$this->db->from('article');
		$this->db->join('article_content', 'article_content.article_id = article.id');
		$this->db->where('article.slug', $slug);
		$cek_post = $this->db->get();

		// cek jika link salah
		if ($cek_post->num_rows() == 0) {
			redirect(base_url() . 'home', 'refresh');
		}

		$article_data = $cek_post->row();

		// cek artikel is_active
		if ($article_data->is_show == '0') {
			die("Artikel tidak tersedia untuk saat ini.");
		}


		$this->load->helper('text');

		$this->data = [];




		$this->data['title'] = $article_data->title;
		$this->data['article_data'] = $article_data;
		$this->data['all_category'] = $this->db->get('article_category');

		$user_writter = $this->ion_auth->user($article_data->written_by_id)->row();
		$this->data['nama_penulis'] = ($article_data->alias_name_writter) ? $article_data->alias_name_writter : $user_writter->first_name . ' ' . $user_writter->last_name;
		$this->data['deskripsi_penulis'] = $user_writter->description;
		$this->data['foto_profile'] = $user_writter->foto_profile;
		$this->data['tanggal_post'] = date('F d, Y', strtotime($article_data->created_at));

		$this->db->select('*, article.id AS id_article, article_content.id AS id_article_content');
		$this->db->from('article');
		$this->db->join('article_category', 'article_category.id = article.id_article_category');
		$this->db->join('article_content', 'article_content.article_id = article.id');
		$this->db->where('article_category.id', $article_data->id_article_category);
		$this->db->where('article.id !=', $article_data->id_article);
		$this->db->limit(3);
		$artikel_terkait = $this->db->get();
		$this->data['artikel_terkait'] = $artikel_terkait;

		// SEO
		$this->data['meta_title'] = $article_data->seo_title;
		$this->data['meta_description'] = $article_data->seo_description;
		$this->data['meta_keywords'] = $article_data->seo_keywords;

		return view('article/view_article', $this->data);
	}
}

/* End of file ArticleController.php */
/* Location: ./application/controllers/ArticleController.php */