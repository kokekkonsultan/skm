<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PertanyaanUnsurPelayananController extends CI_Controller
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
		$this->load->model('KlasifikasiJenisPelayananSurvei_model');
		$this->load->model('JenisPelayananPertanyaanUnsur_model');
		$this->load->model('UnsurPelayanan_model');
		$this->load->model('PertanyaanUnsurPelayanan_model');
		$this->load->model('PertanyaanTambahan_model');
		$this->load->model('PertanyaanHarapan_model');
	}

	//---------------------KLASIFIKASI SURVEI--------------------
	public function index()
	{
		$this->data = [];
		$this->data['title'] = "Pertanyaan Unsur Pelayanan";

		return view('pertanyaan_unsur_pelayanan/index', $this->data);
	}

	public function ajax_list()
	{

		$list = $this->KlasifikasiJenisPelayananSurvei_model->get_datatables();
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			$jumlah = $this->db->get_where('jenis_pelayanan', ['id_klasifikasi_survei' => $value->id])->num_rows();

			$no++;
			$row = array();
			$row[] = '<a href="' . base_url() . 'pertanyaan-unsur-pelayanan/jenis-pelayanan/' . $value->id . '"><div class="card" style="background-color: Linen;"><div class="card-body font-weight-bold shadow text-dark">' . $value->nama_klasifikasi_survei . ' (' . $jumlah . ')</div></div></a>';

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->KlasifikasiJenisPelayananSurvei_model->count_all(),
			"recordsFiltered" => $this->KlasifikasiJenisPelayananSurvei_model->count_filtered(),
			"data" => $data,
		);

		echo json_encode($output);
	}


	//---------------------------JENIS PELAYANAN------------------------
	public function list_jenis_pelayanan($id = NULL)
	{
		$url = $this->uri->uri_string();
		$this->session->set_userdata('urlback', $url);

		$this->data = [];
		$this->data['title'] = "Pertanyaan Unsur Pelayanan";

		$this->db->select('nama_klasifikasi_survei');
		$this->db->from('klasifikasi_survei');
		$this->db->where('id', $id);
		$current_data = $this->db->get()->row();
		$this->data['nama_klasifikasi'] = $current_data->nama_klasifikasi_survei;

		return view('pertanyaan_unsur_pelayanan/list_jenis_pelayanan', $this->data);
	}

	public function ajax_list_jenis_pelayanan()
	{

		$id_klasifikasi_survei = $this->input->post('id_klasifikasi_survei');

		$list = $this->JenisPelayananPertanyaanUnsur_model->get_datatables();
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			$jumlah = $this->db->get_where('unsur_pelayanan', ['id_jenis_pelayanan' => $value->id_jenis_pelayanan])->num_rows();

			$no++;
			$row = array();
			$row[] = '<a href="' . base_url() . 'pertanyaan-unsur-pelayanan/list-unsur-pelayanan/' . $id_klasifikasi_survei . '/' . $value->id . '"><div class="card bg-secondary"><div class="card-body font-weight-bold shadow text-dark">' . $no . '. ' . $value->nama_jenis_pelayanan_responden . '(' . $jumlah . ' Pertanyaan Unsur)</div></div></a>';

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->JenisPelayananPertanyaanUnsur_model->count_all(),
			"recordsFiltered" => $this->JenisPelayananPertanyaanUnsur_model->count_filtered(),
			"data" => $data,
		);

		echo json_encode($output);
	}

	//------------------------------UNSUR PELAYANAN------------------------------------

	public function list_unsur_pelayanan($id1 = NULL, $id2 = NULL)
	{
		$url = $this->uri->uri_string();
		$this->session->set_userdata('urlback_second', $url);

		$this->data = [];
		$this->data['title'] = "Pertanyaan Unsur Pelayanan";

		$this->db->select('jenis_pelayanan.nama_jenis_pelayanan_responden, klasifikasi_survei.nama_klasifikasi_survei');
		$this->db->from('jenis_pelayanan');
		$this->db->join('klasifikasi_survei', 'klasifikasi_survei.id = jenis_pelayanan.id_klasifikasi_survei');
		$this->db->where('jenis_pelayanan.id', $id2);
		$current_data = $this->db->get()->row();

		$this->data['nama_jenis_pelayanan'] = $current_data->nama_jenis_pelayanan_responden;
		$this->data['nama_klasifikasi'] = $current_data->nama_klasifikasi_survei;

		$this->data['pertanyaan_harapan'] = $this->db->query('SELECT pertanyaan_unsur_pelayanan.id AS id,
		pertanyaan_unsur_pelayanan.isi_pertanyaan_unsur,
		( SELECT  nilai_tingkat_kepentingan.nama_tingkat_kepentingan FROM nilai_tingkat_kepentingan  WHERE nilai_tingkat_kepentingan.nomor_tingkat_kepentingan = 1 AND nilai_tingkat_kepentingan.id_pertanyaan_unsur_pelayanan = pertanyaan_unsur_pelayanan.id ) AS pilihan_1,
		( SELECT  nilai_tingkat_kepentingan.nama_tingkat_kepentingan FROM nilai_tingkat_kepentingan  WHERE nilai_tingkat_kepentingan.nomor_tingkat_kepentingan = 2 AND nilai_tingkat_kepentingan.id_pertanyaan_unsur_pelayanan = pertanyaan_unsur_pelayanan.id ) AS pilihan_2,
		( SELECT  nilai_tingkat_kepentingan.nama_tingkat_kepentingan FROM nilai_tingkat_kepentingan  WHERE nilai_tingkat_kepentingan.nomor_tingkat_kepentingan = 3 AND nilai_tingkat_kepentingan.id_pertanyaan_unsur_pelayanan = pertanyaan_unsur_pelayanan.id ) AS pilihan_3,
		( SELECT  nilai_tingkat_kepentingan.nama_tingkat_kepentingan FROM nilai_tingkat_kepentingan  WHERE nilai_tingkat_kepentingan.nomor_tingkat_kepentingan = 4 AND nilai_tingkat_kepentingan.id_pertanyaan_unsur_pelayanan = pertanyaan_unsur_pelayanan.id ) AS pilihan_4
		FROM pertanyaan_unsur_pelayanan 
		JOIN unsur_pelayanan ON unsur_pelayanan .id = pertanyaan_unsur_pelayanan .id_unsur_pelayanan
		ORDER BY pertanyaan_unsur_pelayanan .id ASC')->result();

		return view('pertanyaan_unsur_pelayanan/list_unsur_pelayanan', $this->data);
	}

	public function ajax_list_unsur_pelayanan()
	{

		$list = $this->UnsurPelayanan_model->get_datatables();
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $value->nomor_unsur;
			$row[] = $value->nama_unsur_pelayanan;
			$row[] = anchor('pertanyaan-unsur-pelayanan/edit-unsur/' . $value->id_unsur_pelayanan, 'Edit', ['class' => 'btn btn-light-primary btn-sm font-weight-bold shadow']);

			$row[] = '<a class="btn btn-light-primary btn-sm font-weight-bold shadow" href="javascript:void(0)" title="Hapus ' . $value->nama_unsur_pelayanan . '" onclick="delete_data_unsur(' . "'" . $value->id_unsur_pelayanan . "'" . ')">Delete</a>';

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->UnsurPelayanan_model->count_all(),
			"recordsFiltered" => $this->UnsurPelayanan_model->count_filtered(),
			"data" => $data,
		);

		echo json_encode($output);
	}

	public function add_unsur($id1 = NULL, $id2 = NULL)
	{

		$this->data = array();
		$this->data['title'] 		= 'Tambah Unsur Pelayanan';
		$this->data['form_action'] 	= "pertanyaan-unsur-pelayanan/add-unsur/$id1/$id2";

		$this->form_validation->set_rules('nama_unsur_pelayanan', 'Nama Unsur Pelayanan', 'trim|required');

		if ($this->form_validation->run() == FALSE) {

			$this->data['nama_unsur_pelayanan'] = [
				'name' 		=> 'nama_unsur_pelayanan',
				'id'		=> 'nama_unsur_pelayanan',
				'type'		=> 'text',
				'value'		=>	$this->form_validation->set_value('nama_unsur_pelayanan'),
				'class'		=> 'form-control'
			];

			$this->data['id_jenis_pelayanan'] = [
				'name' 		=> 'id_jenis_pelayanan',
				'id' 		=> 'id_jenis_pelayanan',
				'options' 	=> $this->UnsurPelayanan_model->dropdown_jenis_pelayanan(),
				'selected' 	=> $this->form_validation->set_value('id_jenis_pelayanan'),
				'class' 	=> "form-control",
				'autofocus' => 'autofocus'
			];

			$this->data['id_parent'] = [
				'name' 		=> 'id_parent',
				'id' 		=> 'id_parent',
				'options' 	=> $this->UnsurPelayanan_model->dropdown_unsur_pelayanan_by_id(),
				'selected' 	=> $this->form_validation->set_value('id_parent'),
				'class' 	=> "form-control",
				'style' => "display:none",
				// 'required' => 'required',
			];

			return view('pertanyaan_unsur_pelayanan/form_add_unsur', $this->data);
		} else {

			$input 	= $this->input->post(NULL, TRUE);
			$this->load->library('uuid');

			if ($input['customisasi'] == 1) {

				$id_parent = $input['id_parent'];

				$this->db->select('nomor_unsur');
				$this->db->from('unsur_pelayanan');
				$this->db->where('id =' . $id_parent);
				$nomor = $this->db->get()->row()->nomor_unsur;

				$this->db->select('(COUNT(id_parent)+1)AS nomor_sub');
				$this->db->from('unsur_pelayanan');
				$this->db->where('id_parent =' . $id_parent);
				// $this->db->where('id_jenis_pelayanan =' . $input['id_jenis_pelayanan']);
				$this->db->where('id_jenis_pelayanan =' . $id2);
				$sub = $this->db->get()->row()->nomor_sub;

				$object = [
					'uuid' => $this->uuid->v4(),
					// 'id_jenis_pelayanan' 	=> $input['id_jenis_pelayanan'],
					'id_jenis_pelayanan' 	=> $id2,
					'nomor_unsur' => $nomor . '.' . $sub,
					'nama_unsur_pelayanan' 	=> $input['nama_unsur_pelayanan'],
					'is_sub_unsur_pelayanan' => $input['customisasi'],
					'id_parent' => $id_parent
				];
			} else {
				$this->db->select('(COUNT(nomor_unsur)+1)AS kode_unsur');
				$this->db->from('unsur_pelayanan');
				$this->db->where('id_parent = 0');
				// $this->db->where('id_jenis_pelayanan =' . $input['id_jenis_pelayanan']);
				$this->db->where('id_jenis_pelayanan =' . $id2);
				$nomor_unsur = $this->db->get()->row()->kode_unsur;

				$object = [
					'uuid' => $this->uuid->v4(),
					// 'id_jenis_pelayanan' 	=> $input['id_jenis_pelayanan'],
					'id_jenis_pelayanan' 	=> $id2,
					'nomor_unsur' => 'U' . $nomor_unsur,
					'nama_unsur_pelayanan' 	=> $input['nama_unsur_pelayanan'],
					'is_sub_unsur_pelayanan' => $input['customisasi'],
					'id_parent' => '0'
				];
			}

			$query = $this->db->insert('unsur_pelayanan', $object);

			if ($query) {
				$this->session->set_flashdata('message_success', 'Berhasil menambah data');
				redirect($this->session->userdata('urlback_second'), 'refresh');
			} else {
				$this->data['message_data_danger'] = "Gagal menambah data";
				return view('pertanyaan_unsur_pelayanan/form_add_unsur', $this->data);
			}
		}
	}

	public function edit_unsur($id1 = NULL)
	{
		$this->data = array();
		$this->data['title'] = 'Edit Unsur Pelayanan';
		$this->data['form_action'] = "pertanyaan-unsur-pelayanan/edit-unsur/$id1";

		$this->db->select('*');
		$this->db->from('unsur_pelayanan');
		$this->db->where('unsur_pelayanan.id', $id1);


		$search_data = $this->db->get();

		if ($search_data->num_rows() == 0) {

			$this->session->set_flashdata('message_danger', 'Data tidak ditemukan');
			redirect($this->session->userdata('urlback_second'), 'refresh');
		}

		$current = $search_data->row();

		$this->data['nama_unsur_pelayanan'] = [
			'name' 		=> 'nama_unsur_pelayanan',
			'id'		=> 'nama_unsur_pelayanan',
			'type'		=> 'text',
			'value'		=>	$this->form_validation->set_value('nama_unsur_pelayanan', $current->nama_unsur_pelayanan),
			'class'		=> 'form-control',
			'required' => 'required',
			'autofocus' => 'autofocus' 
		];

		$this->data['nomor_unsur'] = $current->nomor_unsur;

		$this->form_validation->set_rules('nama_unsur_pelayanan', 'Nama Unsur Pelayanan', 'trim|required');

		if ($this->form_validation->run() == FALSE) {

			return view('pertanyaan_unsur_pelayanan/form_edit_unsur', $this->data);
		} else {

			$input 	= $this->input->post(NULL, TRUE);

			// Update unsur_pelayanan
			$object = [
				'nama_unsur_pelayanan' 	=> $input['nama_unsur_pelayanan']
			];

			$this->db->where('id', $id1);
			$this->db->update('unsur_pelayanan', $object);

			$this->session->set_flashdata('message_success', 'Berhasil mengubah data');
			redirect($this->session->userdata('urlback_second'), 'refresh');
		}
	}

	public function delete_unsur($id = NULL)
	{
		$search_data = $this->db->query("SELECT pertanyaan_unsur_pelayanan.id AS id_pertanyaan_unsur_pelayanan, unsur_pelayanan.id AS id_unsur_pelayanan FROM unsur_pelayanan LEFT JOIN pertanyaan_unsur_pelayanan ON unsur_pelayanan.id = pertanyaan_unsur_pelayanan.id_unsur_pelayanan WHERE unsur_pelayanan.id =" .  $id);

		if ($search_data->num_rows() == 0) {

			echo json_encode(array("status" => FALSE));
		}

		$current = $search_data->row();
		// var_dump($current);

		// delete nilai_tingkat_kepentingan
		$this->db->where('id_pertanyaan_unsur_pelayanan', $current->id_pertanyaan_unsur_pelayanan);
		$this->db->delete('nilai_tingkat_kepentingan');

		// delete kategori_unsur_pelayanan
		$this->db->where('id_pertanyaan_unsur', $current->id_pertanyaan_unsur_pelayanan);
		$this->db->delete('kategori_unsur_pelayanan');

		// delete pertanyaan_unsur_pelayanan
		$this->db->where('id', $current->id_pertanyaan_unsur_pelayanan);
		$this->db->delete('pertanyaan_unsur_pelayanan');

		// delete unsur_pelayanan
		$this->db->where('id', $current->id_unsur_pelayanan);
		$this->db->delete('unsur_pelayanan');

		echo json_encode(array("status" => TRUE));
	}


	//------------------------------PERTANYAAN UNSUR--------------------------------

	public function ajax_list_pertanyaan_unsur_pelayanan()
	{

		$list = $this->PertanyaanUnsurPelayanan_model->get_datatables();
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			$no++;
			$row = array();
			$row[] = $no;
			// $row[] = '<b>' . $value->nama_klasifikasi_survei . '</b><br>' . $value->nama_jenis_pelayanan_responden;
			$row[] = $value->nomor_unsur . '. ' . $value->nama_unsur_pelayanan;
			$row[] = $value->isi_pertanyaan_unsur;
			$row[] = anchor('pertanyaan-unsur-pelayanan/edit/' . $value->id_unsur_pelayanan . '/' . $value->id_pertanyaan_unsur_pelayanan, 'Edit', ['class' => 'btn btn-light-primary btn-sm font-weight-bold shadow']);
			$row[] = '<a class="btn btn-light-primary btn-sm font-weight-bold shadow" href="javascript:void(0)" title="Hapus ' . $value->isi_pertanyaan_unsur . '" onclick="delete_data(' . "'" . $value->id_pertanyaan_unsur_pelayanan . "'" . ')">Delete</a>';

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->PertanyaanUnsurPelayanan_model->count_all(),
			"recordsFiltered" => $this->PertanyaanUnsurPelayanan_model->count_filtered(),
			"data" => $data,
		);

		echo json_encode($output);
	}

	public function cari()
	{
		$id = $_GET['id'];
		$cari = $this->PertanyaanUnsurPelayanan_model->cari($id)->result();
		echo json_encode($cari);
	}

	public function add($id1 = NULL, $id2 = NULL)
	{

		$this->data = array();
		$this->data['title'] 		= 'Tambah Pertanyaan Unsur Pelayanan';
		$this->data['form_action'] 	= "pertanyaan-unsur-pelayanan/add/$id1/$id2";

		$this->data['pilihan'] = $this->PertanyaanUnsurPelayanan_model->tampil_data();

		$this->form_validation->set_rules('id_unsur_pelayanan', 'Id Unsur Pelayanan', 'trim|required');
		$this->form_validation->set_rules('isi_pertanyaan_unsur', 'Isi Pertanyaan Unsur', 'trim|required');

		if ($this->form_validation->run() == FALSE) {

			$this->data['id_jenis_pelayanan'] = [
				'name' 		=> 'id_jenis_pelayanan',
				'id' 		=> 'id_jenis_pelayanan',
				'options' 	=> $this->UnsurPelayanan_model->dropdown_jenis_pelayanan(),
				'selected' 	=> $this->form_validation->set_value('id_jenis_pelayanan'),
				'class' 	=> "form-control",
				'autofocus' => 'autofocus'
			];

			$this->data['id_unsur_pelayanan'] = [
				'name' 		=> 'id_unsur_pelayanan',
				'id' 		=> 'id_unsur_pelayanan',
				'options' 	=> $this->PertanyaanUnsurPelayanan_model->dropdown_unsur_pelayanan(),
				'selected' 	=> $this->form_validation->set_value('id_unsur_pelayanan'),
				'class' 	=> "form-control",
				'required'	=> 'required',
			];

			$this->data['isi_pertanyaan_unsur'] = [
				'name' 		=> 'isi_pertanyaan_unsur',
				'id'		=> 'isi_pertanyaan_unsur',
				'type'		=> 'text',
				'value'		=>	$this->form_validation->set_value('isi_pertanyaan_unsur'),
				'class'		=> 'form-control',
				'required'	=> 'required',
				'placeholder' => 'Masukkan pertanyan unsur anda ...'
			];

			$this->data['pilihan_jawaban_1'] = [
				'name' 		=> 'pilihan_jawaban_1',
				'id'		=> '',
				'type'		=> 'text',
				'value'		=>	$this->form_validation->set_value('pilihan_jawaban_1'),
				'class'		=> 'form-control',
				'placeholder' => 'Masukkan pilihan jawaban anda, misalnya : Ya | Tidak / Sudah | Belum'
			];

			$this->data['pilihan_jawaban_2'] = [
				'name' 		=> 'pilihan_jawaban_2',
				'id'		=> '',
				'type'		=> 'text',
				'value'		=>	$this->form_validation->set_value('pilihan_jawaban_2'),
				'class'		=> 'form-control',
				'placeholder' => 'Masukkan pilihan jawaban anda, misalnya : Ya | Tidak / Sudah | Belum'
			];

			return view('pertanyaan_unsur_pelayanan/form_add', $this->data);
		} else {

			$input 	= $this->input->post(NULL, TRUE);

			$this->load->library('uuid');


			$object = [
				'isi_pertanyaan_unsur' 	=> $input['isi_pertanyaan_unsur'],
				'id_unsur_pelayanan' 	=> $input['id_unsur_pelayanan'],
				'jenis_pilihan_jawaban' 	=> $input['jenis_pilihan_jawaban']
			];

			$this->db->insert('pertanyaan_unsur_pelayanan', $object);

			$id_pertanyaan_unsur = $this->db->insert_id();

			if ($this->input->post('jenis_pilihan_jawaban') == "2") {
				$result = array();
				foreach ($_POST['pilihan_jawaban'] as $key => $val) {
					$no_next = $key + 1;
					$result[] = array(
						'id_pertanyaan_unsur' => $id_pertanyaan_unsur,
						'id_unsur_pelayanan' => $input['id_unsur_pelayanan'],
						'nomor_kategori_unsur_pelayanan' => $no_next,
						'nama_kategori_unsur_pelayanan' => $_POST['pilihan_jawaban'][$key]
					);
				}
				$query = $this->db->insert_batch('kategori_unsur_pelayanan', $result);


				// var_dump($result);
			} else {

				$object = [
					'id_pertanyaan_unsur' => $id_pertanyaan_unsur,
					'id_unsur_pelayanan' => $input['id_unsur_pelayanan'],
					'nomor_kategori_unsur_pelayanan' => '1',
					'nama_kategori_unsur_pelayanan' => $input['pilihan_jawaban_1']
				];

				$object_1 = [
					'id_pertanyaan_unsur' => $id_pertanyaan_unsur,
					'id_unsur_pelayanan' => $input['id_unsur_pelayanan'],
					'nomor_kategori_unsur_pelayanan' => '4',
					'nama_kategori_unsur_pelayanan' => $input['pilihan_jawaban_2']
				];

				$query = [
					$this->db->insert('kategori_unsur_pelayanan', $object),
					$this->db->insert('kategori_unsur_pelayanan', $object_1),
				];
			}

			$this->db->query("INSERT INTO nilai_tingkat_kepentingan (id_pertanyaan_unsur_pelayanan, nama_tingkat_kepentingan, nomor_tingkat_kepentingan)
			VALUES ($id_pertanyaan_unsur, 'Tidak Penting', '1'), ($id_pertanyaan_unsur, 'Kurang Penting', '2'), ($id_pertanyaan_unsur, 'Penting', '3'), ($id_pertanyaan_unsur, 'Sangat Penting', '4')");

			if ($query) {
				$this->session->set_flashdata('message_success', 'Berhasil menambah data');
				redirect($this->session->userdata('urlback_second'), 'refresh');
			} else {

				$this->data['message_data_danger'] = "Gagal menambah data";
				return view('pertanyaan_unsur_pelayanan/form_add', $this->data);
			}
		}
	}

	public function delete($id = NULL)
	{
		$search_data = $this->db->get_where('pertanyaan_unsur_pelayanan', ['id' => $id]);

		if ($search_data->num_rows() == 0) {

			echo json_encode(array("status" => FALSE));
		}

		$current = $search_data->row();

		// delete nilai_tingkat_kepentingan
		$this->db->where('id_pertanyaan_unsur_pelayanan', $current->id);
		$this->db->delete('nilai_tingkat_kepentingan');

		// delete kategori_unsur_pelayanan
		$this->db->where('id_pertanyaan_unsur', $current->id);
		$this->db->delete('kategori_unsur_pelayanan');

		// delete pertanyaan_unsur_pelayanan
		$this->db->where('id', $current->id);
		$this->db->delete('pertanyaan_unsur_pelayanan');

		echo json_encode(array("status" => TRUE));
	}

	public function edit($id1 = NULL, $id2 = NULL)
	{
		$this->data = array();
		$this->data['title'] = 'Edit Pertanyaan Unsur Pelayanan';
		$this->data['form_action'] = "pertanyaan-unsur-pelayanan/edit/$id1/$id2";

		$this->db->select('*, pertanyaan_unsur_pelayanan.id AS id_pertanyaan_unsur');
		$this->db->from('pertanyaan_unsur_pelayanan');
		$this->db->join('unsur_pelayanan', 'pertanyaan_unsur_pelayanan.id_unsur_pelayanan = unsur_pelayanan.id');
		$this->db->where('pertanyaan_unsur_pelayanan.id', $id2);


		$search_data = $this->db->get();

		if ($search_data->num_rows() == 0) {

			$this->session->set_flashdata('message_danger', 'Data tidak ditemukan');
			redirect($this->session->userdata('urlback_second'), 'refresh');
		}

		$current = $search_data->row();
		// var_dump($current);


		$this->data['nama_unsur_pelayanan'] = [
			'name' 		=> 'nama_unsur_pelayanan',
			'id'		=> 'nama_unsur_pelayanan',
			'type'		=> 'text',
			'value'		=>	$this->form_validation->set_value('nama_unsur_pelayanan', $current->nama_unsur_pelayanan),
			'class'		=> 'form-control',
			'disabled' => 'disabled',
		];
		$this->data['nomor_unsur'] = $current->nomor_unsur;



		$this->data['isi_pertanyaan_unsur'] = [
			'name' 		=> 'isi_pertanyaan_unsur',
			'id'		=> 'isi_pertanyaan_unsur',
			'type'		=> 'text',
			'value'		=>	$this->form_validation->set_value('isi_pertanyaan_unsur', $current->isi_pertanyaan_unsur),
			'class'		=> 'form-control',
		];

		$this->data['id_unsur_pelayanan'] = [
			'name' 		=> 'id_unsur_pelayanan',
			'id' 		=> 'id_unsur_pelayanan',
			'value'		=>	$this->form_validation->set_value('id_unsur_pelayanan', $current->nomor_unsur . '. ' . $current->nama_unsur_pelayanan),
			'class' 	=> "form-control",
			'disabled' => 'disabled'
		];

		$this->data['pilihan_jawaban_1'] = [
			'name' 		=> 'pilihan_jawaban_1',
			'id'		=> '',
			'type'		=> 'text',
			'value'		=>	$this->form_validation->set_value('pilihan_jawaban_1'),
			'class'		=> 'form-control',
			'placeholder' => 'Masukkan pilihan jawaban anda, misalnya : Tidak Baik | Kurang Baik | Baik | Sangat Baik'
		];

		$this->data['pilihan_jawaban_2'] = [
			'name' 		=> 'pilihan_jawaban_2',
			'id'		=> '',
			'type'		=> 'text',
			'value'		=>	$this->form_validation->set_value('pilihan_jawaban_2'),
			'class'		=> 'form-control',
			'placeholder' => 'Masukkan pilihan jawaban anda, misalnya : Tidak Baik | Kurang Baik | Baik | Sangat Baik'
		];


		$this->data['nama_kategori_unsur'] = $this->PertanyaanUnsurPelayanan_model->get_kategori_unsur_pelayanan($current->id_pertanyaan_unsur);

		$this->form_validation->set_rules('isi_pertanyaan_unsur', 'Isi Pertanyaan Unsur', 'trim|required');

		if ($this->form_validation->run() == FALSE) {

			return view('pertanyaan_unsur_pelayanan/form_edit', $this->data);
		} else {

			$input 	= $this->input->post(NULL, TRUE);
			$id_kategori = $this->input->post("id");
			$kategori_unsur = $this->db->get_where('kategori_unsur_pelayanan', ['id' => $id_kategori]);
			$unsur = $kategori_unsur->row();

			$object = [
				'isi_pertanyaan_unsur' 	=> $input['isi_pertanyaan_unsur']
			];

			$this->db->where('id', $id2);
			$this->db->update('pertanyaan_unsur_pelayanan', $object);

			$id = $input['id_kategori'];
			$nama_kategori_input = $input['nama_kategori_unsur_pelayanan'];


			for ($i = 0; $i < sizeof($id); $i++) {
				$kategori = array(
					'id' => $id[$i],
					'nama_kategori_unsur_pelayanan' => $nama_kategori_input[$i]
				);
				$this->db->where('id', $id[$i]);
				$this->db->update('kategori_unsur_pelayanan', $kategori);
			}

			$this->session->set_flashdata('message_success', 'Berhasil mengubah data');
			redirect($this->session->userdata('urlback_second'), 'refresh');
		}
	}


	//----------------------------------PERTANYAAN HARAPAN--------------------------------
	public function ajax_list_pertanyaan_harapan()
	{

		$list = $this->PertanyaanHarapan_model->get_datatables();
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $value->nomor_unsur . '. ' . $value->nama_unsur_pelayanan;
			$row[] = $value->isi_pertanyaan_unsur;
			$row[] = '<a class="btn btn-light-primary btn-sm font-weight-bold shadow" data-toggle="modal" data-target="#pertanyaan_tambahan' . $value->id_pertanyaan_unsur_pelayanan . ' ">Detail Pilihan Jawaban</a>';

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->PertanyaanHarapan_model->count_all(),
			"recordsFiltered" => $this->PertanyaanHarapan_model->count_filtered(),
			"data" => $data,
		);

		echo json_encode($output);
	}



	//--------------------------------------------PERTANYAAN TAMBAHAN-------------------
	public function ajax_list_pertanyaan_tambahan()
	{

		$list = $this->PertanyaanTambahan_model->get_datatables();
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $value->nomor_unsur . '. ' . $value->nama_unsur_pelayanan;
			$row[] = '<span class="text-primary">' . $value->nomor_pertanyaan_terbuka . '. ' . $value->nama_pertanyaan_terbuka . '</span></br>' . $value->isi_pertanyaan_terbuka;
			$row[] = anchor('pertanyaan-unsur-pelayanan/edit-pertanyaan-tambahan/' . $value->id_pertanyaan_terbuka, 'Edit', ['class' => 'btn btn-light-primary shadow btn-sm font-weight-bold']);
			$row[] = '<a class="btn btn-light-primary shadow btn-sm font-weight-bold" href="javascript:void(0)" title="Hapus ' . $value->nama_pertanyaan_terbuka . '" onclick="delete_table(' . "'" . $value->id_pertanyaan_terbuka . "'" . ')">Delete</a>';

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->PertanyaanTambahan_model->count_all(),
			"recordsFiltered" => $this->PertanyaanTambahan_model->count_filtered(),
			"data" => $data,
		);

		echo json_encode($output);
	}


	public function add_pertanyaan_tambahan($id1 = NULL, $id2 = NULL)
	{

		$this->data = array();
		$this->data['title'] 		= 'Tambah Pertanyaan Tambahan';
		$this->data['form_action'] 	= "pertanyaan-unsur-pelayanan/add-pertanyaan-tambahan/$id1/$id2";

		$this->form_validation->set_rules('nama_pertanyaan_terbuka', 'Nama Pertanyaan Terbuka', 'trim|required');

		if ($this->form_validation->run() == FALSE) {

			$this->data['nama_pertanyaan_terbuka'] = [
				'name' 		=> 'nama_pertanyaan_terbuka',
				'id'		=> 'nama_pertanyaan_terbuka',
				'type'		=> 'text',
				'value'		=>	$this->form_validation->set_value('nama_pertanyaan_terbuka'),
				'class'		=> 'form-control',
			];

			$this->data['id_unsur_pelayanan'] = [
				'name' 		=> 'id_unsur_pelayanan',
				'id' 		=> 'id_unsur_pelayanan',
				'options' 	=> $this->PertanyaanTambahan_model->dropdown_unsur_pelayanan($id2),
				'selected' 	=> $this->form_validation->set_value('id_unsur_pelayanan'),
				'class' 	=> "form-control",
			];

			return view('pertanyaan_unsur_pelayanan/form_add_pertanyaan_tambahan', $this->data);
		} else {

			$input 	= $this->input->post(NULL, TRUE);

			$id_jenis_pelayanan = $this->db->query('SELECT id_jenis_pelayanan FROM unsur_pelayanan WHERE id =' . $input['id_unsur_pelayanan'])->row()->id_jenis_pelayanan;

			$this->db->select('(COUNT(nomor_pertanyaan_terbuka)+1) AS nomor_terbuka');
			$this->db->from('pertanyaan_terbuka');
			$this->db->join('unsur_pelayanan', 'pertanyaan_terbuka.id_unsur_pelayanan = unsur_pelayanan.id');
			$this->db->where('id_jenis_pelayanan  =' . $id_jenis_pelayanan);
			$nomor_pertanyaan_terbuka = $this->db->get()->row()->nomor_terbuka;

			$object = [
				'id_unsur_pelayanan' 	=> $input['id_unsur_pelayanan'],
				'nomor_pertanyaan_terbuka' 	=> 'T' . $nomor_pertanyaan_terbuka,
				'nama_pertanyaan_terbuka' 	=> $input['nama_pertanyaan_terbuka']
			];
			// var_dump($object);
			$this->db->insert('pertanyaan_terbuka', $object);

			$id_pertanyaan_terbuka = $this->db->insert_id();

			if ($this->input->post('jenis_jawaban') == '2') {
				$object = [
					'id_pertanyaan_terbuka' 	=> $id_pertanyaan_terbuka,
					'isi_pertanyaan_terbuka' 	=> $input['isi_pertanyaan_terbuka'],
					'id_jenis_pilihan_jawaban' 	=> $input['jenis_jawaban']
				];
				$this->db->insert('perincian_pertanyaan_terbuka', $object);
			} else {
				$object = [
					'id_pertanyaan_terbuka' 	=> $id_pertanyaan_terbuka,
					'isi_pertanyaan_terbuka' 	=> $input['isi_pertanyaan_terbuka'],
					'id_jenis_pilihan_jawaban' 	=> $input['jenis_jawaban']
				];
				$this->db->insert('perincian_pertanyaan_terbuka', $object);

				$id_perincian_pertanyaan_terbuka = $this->db->insert_id();

				$pilihan_jawaban = $input['pilihan_jawaban'];
				$opsi_pilihan_jawaban = $input['opsi_pilihan_jawaban'];

				$result = array();
				foreach ($_POST['pilihan_jawaban'] as $key => $val) {
					$result[] = array(
						'id_perincian_pertanyaan_terbuka' => $id_perincian_pertanyaan_terbuka,
						'pertanyaan_ganda' => $pilihan_jawaban[$key],
						'dengan_isian_lainnya' => $opsi_pilihan_jawaban,
					);
				}
				$this->db->insert_batch('isi_pertanyaan_ganda', $result);
				// var_dump($result);
			}

			if ($this->db->affected_rows() > 0) {

				$this->session->set_flashdata('message_success', 'Berhasil menambah data');
				redirect($this->session->userdata('urlback_second'), 'refresh');
			} else {

				$this->data['message_data_danger'] = "Gagal menambah data";
				return view('pertanyaan_unsur_pelayanan/form_add_pertanyaan_tambahan', $this->data);
			}
		}
	}

	public function delete_pertanyaan_tambahan($id = NULL)
	{
		$search_data = $this->db->get_where('pertanyaan_terbuka', ['id' => $id]);

		$query = $this->db->query("SELECT perincian_pertanyaan_terbuka.id AS id_perincian_pertanyaan_terbuka
		FROM pertanyaan_terbuka
		JOIN perincian_pertanyaan_terbuka ON pertanyaan_terbuka.id = perincian_pertanyaan_terbuka.id_pertanyaan_terbuka
		WHERE id_pertanyaan_terbuka = $id")->row();


		if ($search_data->num_rows() == 0) {

			echo json_encode(array("status" => FALSE));
		}

		$current = $search_data->row();

		$this->db->where('id_perincian_pertanyaan_terbuka', $query->id_perincian_pertanyaan_terbuka);
		$this->db->delete('isi_pertanyaan_ganda');

		$this->db->where('id', $query->id_perincian_pertanyaan_terbuka);
		$this->db->delete('perincian_pertanyaan_terbuka');

		$this->db->where('id', $current->id);
		$this->db->delete('pertanyaan_terbuka');


		echo json_encode(array("status" => TRUE));
	}

	public function edit_pertanyaan_tambahan($id = NULL)
	{
		$this->data = array();
		$this->data['title'] = 'Edit Pertanyaan Tambahan';
		$this->data['form_action'] = 'pertanyaan-unsur-pelayanan/edit-pertanyaan-tambahan/' . $id;

		$this->db->select('*, pertanyaan_terbuka.id AS id_pertanyaan_terbuka');
		$this->db->from('pertanyaan_terbuka');
		$this->db->join('unsur_pelayanan', 'pertanyaan_terbuka.id_unsur_pelayanan = unsur_pelayanan.id');
		$this->db->where('pertanyaan_terbuka.id', $id);
		$this->data['search_data'] = $this->db->get();

		if ($this->data['search_data']->num_rows() == 0) {

			$this->session->set_flashdata('message_danger', 'Data tidak ditemukan');
			redirect($this->session->userdata('urlback'), 'refresh');
		}

		$this->data['current'] = $this->data['search_data']->row();

		$this->data['perincian_pertanyaan_terbuka'] = $this->db->get_where('perincian_pertanyaan_terbuka', array('id_pertanyaan_terbuka' => $this->data['current']->id_pertanyaan_terbuka))->row();

		$this->data['pilihan_jawaban'] = $this->PertanyaanTambahan_model->get_isi_pertanyaan_ganda($this->data['perincian_pertanyaan_terbuka']->id);
		// var_dump($this->data['pilihan_jawaban']);

		$this->data['nama_pertanyaan_terbuka'] = [
			'name' 		=> 'nama_pertanyaan_terbuka',
			'id'		=> 'nama_pertanyaan_terbuka',
			'type'		=> 'text',
			'value'		=>	$this->form_validation->set_value('nama_pertanyaan_terbuka', $this->data['current']->nama_pertanyaan_terbuka),
			'class'		=> 'form-control',
			'autofocus' => 'autofocus'
		];

		$this->data['id_unsur_pelayanan'] = [
			'name' 		=> 'id_unsur_pelayanan',
			'id' 		=> 'id_unsur_pelayanan',
			'value'		=>	$this->form_validation->set_value('nama_pertanyaan_terbuka', $this->data['current']->nomor_unsur . '. ' . $this->data['current']->nama_unsur_pelayanan),
			'class' 	=> "form-control",
			'disabled' => 'disabled'

		];

		$this->form_validation->set_rules('nama_pertanyaan_terbuka', 'Nama Pertanyaan Terbuka', 'trim|required');
		$this->form_validation->set_rules('isi_pertanyaan_terbuka', 'Isi Pertanyaan Terbuka', 'trim|required');

		if ($this->form_validation->run() == FALSE) {

			return view('pertanyaan_unsur_pelayanan/form_edit_pertanyaan_tambahan', $this->data);
		} else {

			$input 	= $this->input->post(NULL, TRUE);

			$this->load->helper('slug');

			$object = [
				'nama_pertanyaan_terbuka' 	=> $input['nama_pertanyaan_terbuka']
			];
			// var_dump($object);
			$this->db->where('id', $id);
			$this->db->update('pertanyaan_terbuka', $object);


			if ($this->input->post('id_jenis_jawaban') == '2') {
				$object = [
					'isi_pertanyaan_terbuka' 	=> $input['isi_pertanyaan_terbuka']
				];

				$this->db->where('id', $this->data['perincian_pertanyaan_terbuka']->id);
				$this->db->update('perincian_pertanyaan_terbuka', $object);
			} else {
				$object = [
					'isi_pertanyaan_terbuka' 	=> $input['isi_pertanyaan_terbuka']
				];

				$this->db->where('id', $this->data['perincian_pertanyaan_terbuka']->id);
				$this->db->update('perincian_pertanyaan_terbuka', $object);

				$id = $input['id_kategori'];
				$pertanyaan_ganda = $input['pertanyaan_ganda'];


				for ($i = 0; $i < sizeof($id); $i++) {
					$kategori = array(
						'id' => $id[$i],
						'pertanyaan_ganda' => ($pertanyaan_ganda[$i])
					);
					$this->db->where('id', $id[$i]);
					$this->db->update('isi_pertanyaan_ganda', $kategori);
					// var_dump($kategori);
				}
			}
			$this->session->set_flashdata('message_success', 'Berhasil menambah data');
			redirect($this->session->userdata('urlback_second'), 'refresh');
		}
	}


	//-----------------------------PREVIEW HASIL----------------------------
	public function preview_hasil($id1 = NULL, $id2 = NULL)
	{
		$this->data = [];
		$this->data['title'] = 'Detail Pertanyaan Unsur';

		$this->db->select('jenis_pelayanan.nama_jenis_pelayanan_responden, klasifikasi_survei.nama_klasifikasi_survei');
		$this->db->from('jenis_pelayanan');
		$this->db->join('klasifikasi_survei', 'klasifikasi_survei.id = jenis_pelayanan.id_klasifikasi_survei');
		$this->db->where('jenis_pelayanan.id', $id2);
		$current_data = $this->db->get()->row();

		$this->data['nama_klasifikasi'] = $current_data->nama_klasifikasi_survei;
		$this->data['nama_jenis_pelayanan'] = $current_data->nama_jenis_pelayanan_responden;

		//PERTANYAAN UNSUR
		$query = $this->db->query("SELECT pertanyaan_unsur_pelayanan .id_unsur_pelayanan AS id_unsur_pelayanan, pertanyaan_unsur_pelayanan .id AS id_pertanyaan_unsur, isi_pertanyaan_unsur, unsur_pelayanan .nomor_unsur AS nomor, SUBSTRING(nomor_unsur, 2, 4) AS nomor_harapan, nama_unsur_pelayanan
		FROM pertanyaan_unsur_pelayanan 
		JOIN unsur_pelayanan  ON unsur_pelayanan .id = pertanyaan_unsur_pelayanan .id_unsur_pelayanan
		WHERE id_jenis_pelayanan = $id2
		ORDER BY pertanyaan_unsur_pelayanan .id ASC");
		$this->data['pertanyaan_unsur'] = $query;

		//JAWABAN PERTANYAAN UNSUR
		$this->data['jawaban_pertanyaan_unsur'] = $this->db->query("SELECT *
		FROM kategori_unsur_pelayanan 
		JOIN unsur_pelayanan  ON kategori_unsur_pelayanan .id_unsur_pelayanan = unsur_pelayanan .id
		WHERE id_jenis_pelayanan = $id2");

		//PERTANYAAM TERBUKA
		$this->data['pertanyaan_terbuka'] = $this->db->query("SELECT DISTINCT IF(dengan_isian_lainnya = 1,'Lainnya',null) AS lainnya,
		perincian_pertanyaan_terbuka.id AS id_perincian_pertanyaan_terbuka, pertanyaan_terbuka.id_unsur_pelayanan AS id_unsur_pelayanan,  isi_pertanyaan_terbuka, nomor_pertanyaan_terbuka, id_jenis_pilihan_jawaban, nama_pertanyaan_terbuka
		FROM pertanyaan_terbuka
		JOIN unsur_pelayanan ON pertanyaan_terbuka.id_unsur_pelayanan = unsur_pelayanan.id
		JOIN perincian_pertanyaan_terbuka  ON pertanyaan_terbuka .id = perincian_pertanyaan_terbuka .id_pertanyaan_terbuka
		LEFT JOIN isi_pertanyaan_ganda  ON perincian_pertanyaan_terbuka .id = isi_pertanyaan_ganda .id_perincian_pertanyaan_terbuka
		WHERE id_jenis_pelayanan = $id2
		ORDER BY pertanyaan_terbuka.id ASC
		");

		//JAWABAN PERTANYAAN TERBUKA
		$this->data['jawaban_pertanyaan_terbuka'] = $this->db->query("SELECT perincian_pertanyaan_terbuka.id AS id_perincian_pertanyaan_terbuka, isi_pertanyaan_ganda.pertanyaan_ganda AS pertanyaan_ganda
		FROM isi_pertanyaan_ganda 
		JOIN perincian_pertanyaan_terbuka  ON isi_pertanyaan_ganda .id_perincian_pertanyaan_terbuka = perincian_pertanyaan_terbuka .id
		JOIN pertanyaan_terbuka ON perincian_pertanyaan_terbuka.id_pertanyaan_terbuka = pertanyaan_terbuka.id
		JOIN unsur_pelayanan ON pertanyaan_terbuka.id_unsur_pelayanan = unsur_pelayanan.id
		WHERE id_jenis_pelayanan = $id2");


		return view('pertanyaan_unsur_pelayanan/form_preview_hasil', $this->data);
	}
}

/* End of file PertanyaanUnsurPelayananController.php */
/* Location: ./application/controllers/PertanyaanUnsurPelayananController.php */