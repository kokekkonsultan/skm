<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AlurPertanyaanLompatController extends Client_Controller
{

	public function __construct()
	{
		parent::__construct();

		if (!$this->ion_auth->logged_in()) {
			$this->session->set_flashdata('message_warning', 'You must be an admin to view this page');
			redirect('auth', 'refresh');
		}
	}

	public function index($id1, $id2)
	{
		$this->data = [];
		$this->data['title'] = "Alur Pertanyaan Lompat";
		$this->data['profiles'] = Client_Controller::_get_data_profile($id1, $id2);
        $this->data['manage_survey'] = $this->db->get_where('manage_survey', ['slug' => $this->uri->segment(2)])->row();
		$this->data['table_identity'] = $this->data['manage_survey']->table_identity;
        $table_identity = $this->data['table_identity'];


		$this->data['pertanyaan_unsur'] = $this->db->query("SELECT *, pertanyaan_unsur_pelayanan_$table_identity.id AS id_pertanyaan_unsur
    
		FROM pertanyaan_unsur_pelayanan_$table_identity
		JOIN unsur_pelayanan_$table_identity ON unsur_pelayanan_$table_identity.id = pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan
		ORDER BY pertanyaan_unsur_pelayanan_$table_identity.id ASC");


		return view('alur_pertanyaan_lompat/index', $this->data);
	}


	public function update_alur_unsur()
	{
		$username 		= $this->uri->segment(1);
		$slug 			= $this->uri->segment(2);
		$id_kategori 	= $this->uri->segment(4);
		$is_next_step 	= $this->uri->segment(5);

		$manage_survey = $this->db->get_where('manage_survey', array('slug' => $slug))->row();
		$table_identity = $manage_survey->table_identity;


		$kategori = [
			'is_next_step' => $is_next_step
		];
		$this->db->where('id', $id_kategori);
		$this->db->update('kategori_unsur_pelayanan_' . $table_identity, $kategori);


		$pesan = 'Data berhasil disimpan';
		$msg = ['sukses' => $pesan];
		echo json_encode($msg);
	}

	
	public function update_alur_terbuka()
	{
		$username 		= $this->uri->segment(1);
		$slug 			= $this->uri->segment(2);
		$id_kategori 	= $this->uri->segment(4);
		$is_next_step 	= $this->uri->segment(5);

		$manage_survey = $this->db->get_where('manage_survey', array('slug' => $slug))->row();
		$table_identity = $manage_survey->table_identity;


		$kategori = [
			'is_next_step' => $is_next_step
		];
		$this->db->where('id', $id_kategori);
		$this->db->update('isi_pertanyaan_ganda_' . $table_identity, $kategori);


		$pesan = 'Data berhasil disimpan';
		$msg = ['sukses' => $pesan];
		echo json_encode($msg);
	}
}
