<?php
defined('BASEPATH') or exit('No direct script access allowed');

class FormSurveiController extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		if (!$this->ion_auth->logged_in()) {
			$this->session->set_flashdata('message_warning', 'You must be an admin to view this page');
			redirect('auth', 'refresh');
		}

		$this->load->library('form_validation');
		$this->load->model('PertanyaanUnsurSurvei_model');
		$this->load->library('uuid');
	}

	public function index($id1, $id2)
	{

		$url = $this->uri->uri_string();
		$this->session->set_userdata('urlback', $url);

		$this->data = [];
		$this->data['title'] = 'Form Survei';
		$this->data['profiles'] = $this->_get_data_profile($id1, $id2);

		$this->data['data_user'] = $this->ion_auth->user()->row();

		$slug = $this->uri->segment('2');

		$this->db->select('*');
		$this->db->from('manage_survey');
		$this->db->where("slug = '$slug'");
		$this->data['manage_survey'] = $this->db->get()->row();

		$this->data['id_manage_survey'] = $this->data['manage_survey']->id;
		$this->data['atribut_pertanyaan_survey'] = unserialize($this->data['manage_survey']->atribut_pertanyaan_survey);


		if ($this->data['manage_survey']->kode_warna != '') {
			$this->data['kode_warna'] = $this->data['manage_survey']->kode_warna;
		} else {
			$this->data['kode_warna'] = '#ffa800';
		}
		// var_dump($this->data['kode_warna']);


		return view('form_survei/index', $this->data);
	}

	public function update_header()
	{
		$slug = $this->uri->segment(2);
		$object = [

			'title_header_survey' => serialize($this->input->post('title')),
		];

		// var_dump(serialize(array("SURVEI KEPUASAN MASYARAKAT", "Dinas terkait")));
		// var_dump($object);
		$this->db->where('slug', "$slug");
		$this->db->update('manage_survey', $object);

		$pesan = 'Data berhasil disimpan';
		$msg = ['sukses' => $pesan];
		echo json_encode($msg);
	}

	public function update_is_kata_pembuka()
	{
		$mode = $_POST['mode'];
		$id = $_POST['nilai_id'];

		if ($mode == 'true') //Jika mode bernilai true berarti tombol status enable
		{
			$object = [
				'is_opening_survey' => 'true'
			];
			$this->db->where('id', $id);
			$this->db->update('manage_survey', $object);

			$message = 'Anda berhasil mengaktifkan form pembuka survei';
			$success = 'Enabled';
			echo json_encode(array('message' => $message, '$success' => $success));
		} else if ($mode == 'false') //Jika mode bernilai false berarti tombol status disable
		{
			$object = [
				'is_opening_survey' => 'false'
			];
			$this->db->where('id', $id);
			$this->db->update('manage_survey', $object);

			$message = 'Anda berhasil menonaktifkan form pembuka survei';
			$success = 'Disabled';
			echo json_encode(array('message' => $message, 'success' => $success));
		}

		
	}

	public function update_display()
	{
		$slug = $this->uri->segment(2);
		$object = [
			'deskripsi_opening_survey' => $this->input->post('deskripsi')
		];
		$this->db->where('slug', "$slug");
		$this->db->update('manage_survey', $object);

		$pesan = 'Data berhasil disimpan';
		$msg = ['sukses' => $pesan];
		echo json_encode($msg);
	}

	public function update_kata_penutup()
	{
		$slug = $this->uri->segment(2);
		$object = [
			'deskripsi_selesai_survei' => $this->input->post('deskripsi_selesai_survei')
		];
		$this->db->where('slug', "$slug");
		$this->db->update('manage_survey', $object);

		$pesan = 'Kata-kata penutup berhasil disimpan';
		$msg = ['sukses' => $pesan];
		echo json_encode($msg);
	}

	public function update_kode_warna()
	{
		$slug = $this->uri->segment(2);
		$object = [
			'kode_warna' => $this->input->post('kode_warna')
		];
		$this->db->where('slug', "$slug");
		$this->db->update('manage_survey', $object);

		$pesan = 'Data berhasil disimpan';
		$msg = ['sukses' => $pesan];
		echo json_encode($msg);
	}


	public function update_saran()
	{
		$slug = $this->uri->segment(2);

		if ($this->input->post('is_saran') == 1) {
			$judul_form_saran = $this->input->post('judul_form_saran');
		} else {
			$judul_form_saran = NULL;
		};

		$object = [
			'is_saran' => $this->input->post('is_saran'),
			'judul_form_saran' => $judul_form_saran
		];
		$this->db->where('slug', "$slug");
		$this->db->update('manage_survey', $object);

		$pesan = 'Data berhasil disimpan';
		$msg = ['sukses' => $pesan];
		echo json_encode($msg);
	}

	public function do_uploud()
	{
		$slug = $this->uri->segment(2);

		$this->db->select('*');
		$this->db->from('manage_survey');
		$this->db->where("slug = '$slug'");
		$this->data['manage_survey'] = $this->db->get()->row();

		$output = array('error' => false);

		$images_logo = $_FILES['file']['name'];

		if ($images_logo != "") {

			$nama_file             = strtolower("benner_");
			$config['upload_path'] = 'assets/klien/benner_survei/';
			$config['allowed_types'] = 'png|jpg|jpeg';
			// $config['max_size']  = 10000;
			$config['remove_space'] = TRUE;
			$config['overwrite'] = true;
			$config['detect_mime']        = TRUE;
			$config['file_name']         = $nama_file . $this->data['manage_survey']->table_identity;

			$this->load->library('upload', $config);
			$this->upload->initialize($config);

			if ($this->upload->do_upload('file')) {
				$uploadData = $this->upload->data();
				$filename = $uploadData['file_name'];

				$file['img_benner'] = $filename;

				$this->db->where("slug = '$slug'");
				$this->db->update('manage_survey', $file);
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


	public function form_opening()
	{
		$this->data = [];
		$this->data['title'] = 'Edit Form Opening';

		$data_uri = $this->uri->segment('2');

		$this->db->select("*, DATE_FORMAT(survey_end, '%d %M %Y') AS survey_selesai, IF(CURDATE() > survey_end,1,NULL) AS survey_berakhir, IF(CURDATE() < survey_start ,1,NULL) AS survey_belum_mulai");
		$this->db->from('manage_survey');
		$this->db->join('users', 'manage_survey.id_user = users.id');
		$this->db->where("slug = '$data_uri'");
		$this->data['manage_survey'] = $this->db->get()->row();
		$this->data['judul'] = $this->data['manage_survey'];
		$this->data['status_saran'] = $this->data['manage_survey']->is_saran;

		if ($this->data['manage_survey']->is_question == 1) {
			return view('form_survei/form_opening', $this->data);
		} else {
			$this->session->set_flashdata('message_warning', 'Anda tidak dapat merubah pertanyaan survei!');
			redirect(base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2) . '/form-survei', 'refresh');
		}
	}



	//=================================================== DATA RESPONDEN ============================================
	//===============================================================================================================
	//===============================================================================================================
	public function data_responden($id1, $id2)
	{
		$this->data = [];
		$this->data['title'] = 'Edit Form Data Responden';
		$this->data['profiles'] = $this->_get_data_profile($id1, $id2);

		$this->db->select("*, DATE_FORMAT(survey_end, '%d %M %Y') AS survey_selesai, IF(CURDATE() > survey_end,1,NULL) AS survey_berakhir, IF(CURDATE() < survey_start ,1,NULL) AS survey_belum_mulai");
		$this->db->from('manage_survey');
		$this->db->where('manage_survey.slug', $this->uri->segment(2));
		$this->data['manage_survey'] = $this->db->get()->row();
		$table_identity_manage_survey = $this->data['manage_survey']->table_identity;
		$this->data['status_saran'] = $this->data['manage_survey']->is_saran;

		//=================================== MODAL DEFAULT ===================================
		$this->data['profil_default'] = $this->db->query("SELECT *
		FROM profil_responden
		WHERE NOT EXISTS (SELECT * FROM profil_responden_$table_identity_manage_survey WHERE profil_responden.nama_profil_responden = profil_responden_$table_identity_manage_survey.nama_profil_responden)");

		//=================================== MODAL CUSTOM ============================================
		$this->data['nama_profil_responden'] = [
			'name' 		=> 'nama_profil_responden',
			'id'		=> 'nama_profil_responden',
			'type'		=> 'text',
			'value'		=>	$this->form_validation->set_value('nama_profil_responden'),
			'class'		=> 'form-control',
			'required' => 'required'
		];

		//LOAD PROFIL RESPONDEN
		$this->data['profil_responden'] = $this->db->query("SELECT * FROM profil_responden_$table_identity_manage_survey");

		//LOAD KATEGORI PROFIL RESPONDEN JIKA PILIHAN GANDA
		$this->data['kategori_profil_responden'] = $this->db->get('kategori_profil_responden_' . $table_identity_manage_survey);

		$this->data['nama_lengkap'] = [
			'name' 		=> 'nama_lengkap',
			'id'		=> 'nama_lengkap',
			'type'		=> 'text',
			'value'		=>	$this->form_validation->set_value('nama_lengkap'),
			'class'		=> 'form-control',
			'required' => 'required',
			'placeholder' => 'Masukkan data anda ...',
		];

		if ($this->data['manage_survey']->is_question == 1) {
			return view('form_survei/data_responden/form_data_responden', $this->data);
		} else {
			$this->session->set_flashdata('message_warning', 'Anda tidak dapat merubah pertanyaan survei!');
			redirect(base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2) . '/form-survei', 'refresh');
		}
	}

	public function add_custom_data_responden()
	{
		$this->db->select('');
		$this->db->from('manage_survey');
		$this->db->where('manage_survey.slug', $this->uri->segment(2));
		$table_identity_manage_survey = $this->db->get()->row()->table_identity;

		$input 	= $this->input->post(NULL, TRUE);

		$profil = $this->db->get('profil_responden')->num_rows();
		$profil_survei = $this->db->get_where('profil_responden_' . $table_identity_manage_survey, array('is_default' => 2));

		$nama_profil_responden = $input['nama_profil_responden'];

		//Cek terdapat tanda baca atau tidak
		if (!preg_match('/^[a-zA-Z0-9 ]+$/', $nama_profil_responden)) {
			$this->session->set_flashdata('message_danger', 'Penulisan Profil Responden tidak boleh menggunakan tanda baca!');
			redirect(base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2) . '/profil-responden-survei/add-custom', 'refresh');
		}

		$nama_alias = preg_replace('/\s+/', '_', strtolower($nama_profil_responden));

		$cek_nama = $this->db->query("SELECT * FROM (SELECT * FROM profil_responden_$table_identity_manage_survey) AS profil_responden_$table_identity_manage_survey WHERE nama_alias = '$nama_alias'");

		if ($cek_nama->num_rows() != 0) {
			$this->session->set_flashdata('message_danger', 'Mohon maaf Nama Profil Responden yang anda isikan sudah ada!');
			redirect(base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2) . '/profil-responden-survei', 'refresh');
		}

		//CEK PERTANYAAN CUSTOM CUDAH ADA APA BELUM
		if ($profil_survei->num_rows() == 0) {
			$cek_id = $profil + 1;
		} else {
			$cek_id = '';
		};

		//CEK TYPE JENIS JAWABAN
		if ($input['jenis_jawaban'] == 2) {

			if (isset($_POST['type_data'])) {
				$cek_type_data = $input['type_data'];
			} else {
				$cek_type_data =  'text';
			}
		} else {
			$cek_type_data = '';
		};

		$data = [
			'id' => $cek_id,
			'nama_profil_responden' => $input['nama_profil_responden'],
			'jenis_isian' => $input['jenis_jawaban'],
			'is_default' => 2,
			'type_data' => $cek_type_data
		];
		$this->db->insert('profil_responden_' . $table_identity_manage_survey, $data);

		$id_profil_responden = $this->db->insert_id();

		if ($input['jenis_jawaban'] == '1') {

			$id_profil_responden = $this->db->insert_id();
			$pilihan_jawaban = $input['pilihan_jawaban'];

			$result = array();
			foreach ($_POST['pilihan_jawaban'] as $key => $val) {
				$result[] = array(
					'id_profil_responden' => $id_profil_responden,
					'nama_kategori_profil_responden' => $pilihan_jawaban[$key]
				);
			}
			$this->db->insert_batch('kategori_profil_responden_' . $table_identity_manage_survey, $result);
		}

		$data_profil = $this->db->query("SELECT *, IF(type_data != '' ,'VARCHAR (255)','INT') AS type_data_db FROM profil_responden_$table_identity_manage_survey WHERE id = $id_profil_responden")->row();

		$this->db->query("ALTER TABLE responden_$table_identity_manage_survey ADD $data_profil->nama_alias $data_profil->type_data_db");

		$pesan = 'Data berhasil disimpan';
		$msg = ['sukses' => $pesan];
		echo json_encode($msg);
	}

	public function edit_data_responden()
	{
		$this->db->select('');
		$this->db->from('manage_survey');
		$this->db->where('manage_survey.slug', $this->uri->segment(2));
		$table_identity_manage_survey = $this->db->get()->row()->table_identity;

		$input 	= $this->input->post(NULL, TRUE);

		$profil_responden = $this->db->query("SELECT *, IF(type_data != '' ,'VARCHAR (255)','INT') AS type_data_db FROM profil_responden_$table_identity_manage_survey WHERE id =" . $input['id'])->row();
		// var_dump($profil_responden);

		//CEK TYPE DATA
		if ($input['type_data'] == '') {
			$cek_type_data = 'INT';
		} else {
			$cek_type_data = 'VARCHAR (255)';
		};

		$new_nama_profil_responden =  preg_replace('/\s+/', '_', strtolower($input['edit_nama_profil_responden']));
		$this->db->query("ALTER TABLE responden_$table_identity_manage_survey CHANGE $profil_responden->nama_alias $new_nama_profil_responden $cek_type_data");

		$data = [
			'nama_profil_responden' 	=> $input['edit_nama_profil_responden'],
			'type_data' => $input['type_data']
		];
		// var_dump($data);
		$this->db->where('id', $input['id']);
		$this->db->update('profil_responden_' . $table_identity_manage_survey, $data);

		if ($this->input->post('jenis_isian') == '1') {

			$id = $input['id_kategori'];
			$pertanyaan_ganda = $input['jawaban'];

			for ($i = 0; $i < sizeof($id); $i++) {
				$kategori = array(
					'nama_kategori_profil_responden' => ($pertanyaan_ganda[$i])
				);
				$this->db->where('id', $id[$i]);
				$this->db->update('kategori_profil_responden_' . $table_identity_manage_survey, $kategori);
			}
		}

		$pesan = 'Data berhasil disimpan';
		$msg = ['sukses' => $pesan];
		echo json_encode($msg);
	}



	//========================================== PERTANYAAN ====================================================
	//==========================================================================================================
	//==========================================================================================================
	public function data_pertanyaan($id1, $id2)
	{
		$this->data = [];
		$this->data['title'] = 'Edit Form Pertanyaan Survei';
		$this->data['profiles'] = $this->_get_data_profile($id1, $id2);

		$this->db->select('');
		$this->db->from('manage_survey');
		$this->db->where('manage_survey.slug', $this->uri->segment(2));
		$this->data['manage_survey'] = $this->db->get()->row();
		$table_identity = $this->data['manage_survey']->table_identity;
		$this->data['status_saran'] = $this->data['manage_survey']->is_saran;
		$this->data['table_identity'] = $table_identity;

		//PERTANYAAN UNSUR
		$this->data['pertanyaan_unsur'] = $this->db->query("SELECT *, pertanyaan_unsur_pelayanan_$table_identity.id AS id_pertanyaan_unsur, pertanyaan_unsur_pelayanan_$table_identity.jenis_pilihan_jawaban AS pilihan, 
        (SELECT COUNT(jawaban_pertanyaan_unsur_$table_identity.id) FROM jawaban_pertanyaan_unsur_$table_identity JOIN survey_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden WHERE id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id && is_submit = 1 && alasan_pilih_jawaban != '' && jawaban_pertanyaan_unsur_$table_identity.is_active = 1 && skor_jawaban IN (1,2)) AS jumlah_alasan, unsur_pelayanan_$table_identity.id AS id_unsur, if(id_unsur_pelayanan != '', 1, 2) AS unsur_turunan
		FROM unsur_pelayanan_$table_identity
		LEFT JOIN pertanyaan_unsur_pelayanan_$table_identity ON unsur_pelayanan_$table_identity.id = pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan
		ORDER BY nomor_unsur ASC");

		//JAWABAN PERTANYAAN UNSUR
		$this->data['jawaban_pertanyaan_unsur'] = $this->db->query("SELECT kategori_unsur_pelayanan_$table_identity.id_pertanyaan_unsur, kategori_unsur_pelayanan_$table_identity.nomor_kategori_unsur_pelayanan, kategori_unsur_pelayanan_$table_identity.nama_kategori_unsur_pelayanan
		FROM kategori_unsur_pelayanan_$table_identity");

		//PERTANYAAM TERBUKA PALING ATAS
		$this->data['pertanyaan_terbuka_atas'] = $this->db->query("SELECT *, perincian_pertanyaan_terbuka_$table_identity.id AS id_perincian_pertanyaan_terbuka, (SELECT DISTINCT dengan_isian_lainnya FROM isi_pertanyaan_ganda_$table_identity WHERE isi_pertanyaan_ganda_$table_identity.id_perincian_pertanyaan_terbuka = perincian_pertanyaan_terbuka_$table_identity.id) AS dengan_isian_lainnya
		FROM pertanyaan_terbuka_$table_identity
		JOIN perincian_pertanyaan_terbuka_$table_identity ON pertanyaan_terbuka_$table_identity.id = perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka
		WHERE pertanyaan_terbuka_$table_identity.is_letak_pertanyaan = 1");

		//PERTANYAAM TERBUKA
		$this->data['pertanyaan_terbuka'] = $this->db->query("SELECT *, perincian_pertanyaan_terbuka_$table_identity.id AS id_perincian_pertanyaan_terbuka, (SELECT DISTINCT dengan_isian_lainnya FROM isi_pertanyaan_ganda_$table_identity WHERE isi_pertanyaan_ganda_$table_identity.id_perincian_pertanyaan_terbuka = perincian_pertanyaan_terbuka_$table_identity.id) AS dengan_isian_lainnya
		FROM pertanyaan_terbuka_$table_identity
		JOIN perincian_pertanyaan_terbuka_$table_identity ON pertanyaan_terbuka_$table_identity.id = perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka
		WHERE pertanyaan_terbuka_$table_identity.id_unsur_pelayanan != ''");

		//PERTANYAAM TERBUKA PALING BAWAH
		$this->data['pertanyaan_terbuka_bawah'] = $this->db->query("SELECT *, perincian_pertanyaan_terbuka_$table_identity.id AS id_perincian_pertanyaan_terbuka, (SELECT DISTINCT dengan_isian_lainnya FROM isi_pertanyaan_ganda_$table_identity WHERE isi_pertanyaan_ganda_$table_identity.id_perincian_pertanyaan_terbuka = perincian_pertanyaan_terbuka_$table_identity.id) AS dengan_isian_lainnya
		FROM pertanyaan_terbuka_$table_identity
		JOIN perincian_pertanyaan_terbuka_$table_identity ON pertanyaan_terbuka_$table_identity.id = perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka
		WHERE pertanyaan_terbuka_$table_identity.is_letak_pertanyaan = 2");


		//JAWABAN PERTANYAAN TERBUKA
		$this->data['jawaban_pertanyaan_terbuka'] = $this->db->query("SELECT *
		FROM isi_pertanyaan_ganda_$table_identity
		LEFT JOIN perincian_pertanyaan_terbuka_$table_identity ON isi_pertanyaan_ganda_$table_identity.id_perincian_pertanyaan_terbuka = perincian_pertanyaan_terbuka_$table_identity.id");

		$atribut_pertanyaan = unserialize($this->data['manage_survey']->atribut_pertanyaan_survey);

		if (in_array(1, $atribut_pertanyaan)) {
			$this->data['url_next'] = base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2)
				. '/form-survei/pertanyaan-harapan';
		} else if (in_array(3, $atribut_pertanyaan)) {
			$this->data['url_next'] = base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2)
				. '/form-survei/pertanyaan-kualitatif';
		} else if ($this->data['manage_survey']->is_saran == 1) {
			$this->data['url_next'] = base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2)
				. '/form-survei/saran';
		} else {
			$this->data['url_next'] = base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2) . '/form-survei/konfirmasi';
		}

		//=================================== ADD PERTANYAAN UNSUR =========================================
		$this->db->select('');
		$this->db->from('unsur_pelayanan_' . $table_identity);
		$this->db->where("NOT EXISTS (SELECT * FROM pertanyaan_unsur_pelayanan_$table_identity WHERE unsur_pelayanan_$table_identity.id = pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan)", null, false);
		$this->data['unsur_pelayanan'] = $this->db->get();

		$this->data['pilihan'] = $this->PertanyaanUnsurSurvei_model->tampil_data();

		$this->data['nama_unsur_pelayanan'] = [
			'name' 		=> 'nama_unsur_pelayanan',
			'id'		=> 'nama_unsur_pelayanan',
			'type'		=> 'text',
			'value'		=>	$this->form_validation->set_value('nama_unsur_pelayanan'),
			'class'		=> 'form-control',
			'required' => 'required'

		];

		$this->data['isi_pertanyaan_unsur'] = [
			'name' 		=> 'isi_pertanyaan_unsur',
			'id'		=> 'isi_pertanyaan_unsur',
			'type'		=> 'text',
			'value'		=>	$this->form_validation->set_value('isi_pertanyaan_unsur'),
			'class'		=> 'form-control',
			'rows'		 => '5',
			// 'required' => 'required'
		];

		$this->data['pilihan_jawaban'] = [
			'name' 		=> 'pilihan_jawaban[]',
			'id'		=> '',
			'type'		=> 'text',
			'value'		=>	$this->form_validation->set_value('pilihan_jawaban'),
			'class'		=> 'form-control',
			'placeholder' => 'Misalnya : Tidak Baik | Kurang Baik | Baik | Sangat Baik'
		];

		$this->data['pilihan_jawaban_1'] = [
			'name' 		=> 'pilihan_jawaban_1',
			'id'		=> '',
			'type'		=> 'text',
			'value'		=>	$this->form_validation->set_value('pilihan_jawaban_1'),
			'class'		=> 'form-control pilihan_jawaban',
			'placeholder' => 'Misalnya : Ya | Tidak / Sudah | Belum'
		];

		$this->data['pilihan_jawaban_2'] = [
			'name' 		=> 'pilihan_jawaban_2',
			'id'		=> '',
			'type'		=> 'text',
			'value'		=>	$this->form_validation->set_value('pilihan_jawaban_2'),
			'class'		=> 'form-control pilihan_jawaban',
			'placeholder' => 'Misalnya : Ya | Tidak / Sudah | Belum'
		];

		$this->data['id_parent'] = [
			'name'         => 'id_parent',
			'id'         => 'id_parent',
			'options'     => $this->PertanyaanUnsurSurvei_model->dropdown_sub_unsur_pelayanan($table_identity),
			'selected'     => $this->form_validation->set_value('id_parents'),
			'class'     => "form-control",
			'required' => 'required',
		];

		//EDIT PERTANYAAN UNSUR
		$this->data['nama_kategori_unsur'] = $this->db->get('kategori_unsur_pelayanan_' . $table_identity);


		//====================================== PERTANYAAN TAMBAHAN ==============================================
		$this->load->model('PertanyaanTerbukaSurvei_model');

		$this->data['jumlah_tambahan'] = ($this->db->get("pertanyaan_terbuka_$table_identity")->num_rows()) + 1;


		$this->data['nama_pertanyaan_terbuka'] = [
			'name' 		=> 'nama_pertanyaan_terbuka',
			'id'		=> 'nama_pertanyaan_terbuka',
			'type'		=> 'text',
			'value'		=>	$this->form_validation->set_value('nama_pertanyaan_terbuka'),
			'class'		=> 'form-control',
			'required' => 'required'
		];

		$this->data['id_unsur_pelayanan'] = [
			'name' 		=> 'id_unsur_pelayanan',
			'id' 		=> 'id_unsur_pelayanan',
			'options' 	=> $this->PertanyaanTerbukaSurvei_model->dropdown_unsur_pelayanan(),
			'selected' 	=> $this->form_validation->set_value('id_unsur_pelayanan'),
			'class' 	=> "form-control",
			'required' => 'required'
		];

		$this->data['isi_pertanyaan_terbuka'] = [
			'name' 		=> 'isi_pertanyaan_terbuka',
			'id'		=> 'isi_pertanyaan_terbuka',
			'type'		=> 'text',
			'value'		=>	$this->form_validation->set_value('isi_pertanyaann_terbuka'),
			'class'		=> 'form-control',
			'rows' 		=> '5'
		];

		if ($this->data['manage_survey']->is_question == 1) {
			return view('form_survei/pertanyaan/form_pertanyaan', $this->data);
		} else {
			$this->session->set_flashdata('message_warning', 'Anda tidak dapat merubah pertanyaan survei!');
			redirect(base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2) . '/form-survei', 'refresh');
		}
	}

	public function add_pertanyaan_unsur()
	{
		$slug = $this->uri->segment(2);
		$manage_survey = $this->db->get_where('manage_survey', array('slug' => "$slug"))->row();
		$table_identity = $manage_survey->table_identity;

		$input 	= $this->input->post(NULL, TRUE);

		$this->db->select('(COUNT(nomor_unsur)+1)AS kode_unsur');
		$this->db->from('unsur_pelayanan_' . $table_identity);
		$this->db->where('id_parent = 0');
		$nomor_unsur = $this->db->get()->row()->kode_unsur;

		$object = [
			'uuid' => $this->uuid->v4(),
			'id_jenis_pelayanan' => $manage_survey->id_jenis_pelayanan,
			'nama_unsur_pelayanan'     => $input['nama_unsur_pelayanan'],
			'nomor_unsur' => 'U' . $nomor_unsur,
			'is_sub_unsur_pelayanan' => 2,
			'id_parent' => '0',
		];
		// var_dump($object);
		$this->db->insert('unsur_pelayanan_' . $table_identity, $object);


		if ($input['is_sub_unsur_pelayanan'] == 2) {
			$id_unsur = $this->db->insert_id();
			$this->_tambah_pertanyaan($input, $table_identity, $id_unsur);
		} else {
			$pesan = 'Data berhasil disimpan';
			$msg = ['sukses' => $pesan];
			echo json_encode($msg);
		};
	}

	public function add_pertanyaan_sub_unsur()
	{
		$slug = $this->uri->segment(2);
		$manage_survey = $this->db->get_where('manage_survey', array('slug' => "$slug"))->row();
		$table_identity = $manage_survey->table_identity;

		$input 	= $this->input->post(NULL, TRUE);

		$id_parent = $input['id_parent'];

		$this->db->select('nomor_unsur');
		$this->db->from('unsur_pelayanan_' . $table_identity);
		$this->db->where('id =' . $id_parent);
		$nomor = $this->db->get()->row()->nomor_unsur;

		$this->db->select('(COUNT(id_parent)+1)AS nomor_sub');
		$this->db->from('unsur_pelayanan_' . $table_identity);
		$this->db->where('id_parent =' . $id_parent);
		$sub = $this->db->get()->row()->nomor_sub;

		$object = [
			'uuid' => $this->uuid->v4(),
			'nomor_unsur' => $nomor . '.' . $sub,
			'nama_unsur_pelayanan'     => $input['nama_unsur_pelayanan'],
			'is_sub_unsur_pelayanan' => 1,
			'id_parent' => $id_parent,
			'id_jenis_pelayanan' => $manage_survey->id_jenis_pelayanan
		];
		// var_dump($object);
		$this->db->insert('unsur_pelayanan_' . $table_identity, $object);
		$id_unsur = $this->db->insert_id();
		$this->_tambah_pertanyaan($input, $table_identity, $id_unsur);
	}


	public function _tambah_pertanyaan($input, $table_identity, $id_unsur)
	{
		if ($input['isi_pertanyaan_unsur'] == NULL) {
			$this->session->set_flashdata('message_danger', 'Gagal Menambah Pertanyaan Unsur!');
			redirect(base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2) . '/form-survei/pertanyaan', 'refresh');
		}

		$object_1 = [
			'id_unsur_pelayanan' 	=> $id_unsur,
			'isi_pertanyaan_unsur' 	=> $input['isi_pertanyaan_unsur'],
			'jenis_pilihan_jawaban' 	=> $input['jenis_pilihan_jawaban']
		];
		// var_dump($object_1);
		$this->db->insert('pertanyaan_unsur_pelayanan_' . $table_identity, $object_1);

		$id_pertanyaan_unsur = $this->db->insert_id();
		if ($this->input->post('jenis_pilihan_jawaban') == "2") {
			$result = array();
			foreach ($_POST['pilihan_jawaban'] as $key => $val) {
				$no_next = $key + 1;
				$result[] = array(
					'id_unsur_pelayanan' => $id_unsur,
					'id_pertanyaan_unsur' => $id_pertanyaan_unsur,
					'nomor_kategori_unsur_pelayanan' => $no_next,
					'nama_kategori_unsur_pelayanan' => $_POST['pilihan_jawaban'][$key]
				);
			}
			$this->db->insert_batch('kategori_unsur_pelayanan_' . $table_identity, $result);
		} else {
			$data = [
				'id_unsur_pelayanan' => $id_unsur,
				'id_pertanyaan_unsur' => $id_pertanyaan_unsur,
				'nomor_kategori_unsur_pelayanan' => '1',
				'nama_kategori_unsur_pelayanan' => $input['pilihan_jawaban_1']
			];

			$data_1 = [
				'id_unsur_pelayanan' => $id_unsur,
				'id_pertanyaan_unsur' => $id_pertanyaan_unsur,
				'nomor_kategori_unsur_pelayanan' => '4',
				'nama_kategori_unsur_pelayanan' => $input['pilihan_jawaban_2']
			];
			$this->db->insert('kategori_unsur_pelayanan_' . $table_identity, $data);
			$this->db->insert('kategori_unsur_pelayanan_' . $table_identity, $data_1);
		}

		$this->db->query("INSERT INTO nilai_tingkat_kepentingan_$table_identity (id_pertanyaan_unsur_pelayanan, nama_tingkat_kepentingan, nomor_tingkat_kepentingan)
			VALUES ($id_pertanyaan_unsur, 'Tidak Penting', '1'), ($id_pertanyaan_unsur, 'Kurang Penting', '2'), ($id_pertanyaan_unsur, 'Penting', '3'), ($id_pertanyaan_unsur, 'Sangat Penting', '4')");

		$pesan = 'Data berhasil disimpan';
		$msg = ['sukses' => $pesan];
		echo json_encode($msg);
	}


	public function get_detail_edit_pertanyaan_unsur()
	{
		$id_unsur_pelayanan = $this->uri->segment(5);

		$this->data = [];
		$this->data['id_unsur_pelayanan'] = $id_unsur_pelayanan;

		$newdata = array(
			'input'  => $this->data
		);

		$this->session->set_userdata($newdata);

		$this->db->select('');
		$this->db->from('manage_survey');
		$this->db->where('manage_survey.slug', $this->uri->segment(2));
		$this->data['manage_survey'] = $this->db->get()->row();
		$table_identity = $this->data['manage_survey']->table_identity;
		$this->data['status_saran'] = $this->data['manage_survey']->is_saran;

		//PERTANYAAN UNSUR
		$this->data['pertanyaan_unsur'] = $this->db->query("SELECT *, pertanyaan_unsur_pelayanan_$table_identity.id AS id_pertanyaan_unsur, unsur_pelayanan_$table_identity.id AS id_unsur, if(id_unsur_pelayanan != '', 1, 2) AS unsur_turunan
		FROM unsur_pelayanan_$table_identity
		LEFT JOIN pertanyaan_unsur_pelayanan_$table_identity ON unsur_pelayanan_$table_identity.id = pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan
		WHERE unsur_pelayanan_$table_identity.id = $id_unsur_pelayanan")->row();

		//EDIT PERTANYAAN UNSUR
		$this->data['kategori_unsur'] = $this->db->get_where('kategori_unsur_pelayanan_' . $table_identity, array('id_pertanyaan_unsur' => $this->data['pertanyaan_unsur']->id_pertanyaan_unsur));

		return view('form_survei/pertanyaan/detail_edit_pertanyaan_unsur', $this->data);
	}


	public function edit_pertanyaan_unsur()
	{
		$slug = $this->uri->segment('2');
		$manage_survey = $this->db->get_where("manage_survey", array('slug' => "$slug"))->row();

		$input = $this->input->post(NULL, TRUE);
		$object = [
			'nama_unsur_pelayanan' => $input['nama_unsur_pelayanan']
		];
		$this->db->where('id', $this->uri->segment(5));
		$this->db->update('unsur_pelayanan_' . $manage_survey->table_identity, $object);


		if ($input['unsur_turunan'] == 1) {

			$object_1 = [
				'isi_pertanyaan_unsur' 	=> $input['isi_pertanyaan_unsur']
			];
			$this->db->where('id_unsur_pelayanan', $this->uri->segment(5));
			$this->db->update('pertanyaan_unsur_pelayanan_' . $manage_survey->table_identity, $object_1);


			$id = $input['id_kategori'];
			$nama_kategori_input = $input['nama_kategori_unsur_pelayanan'];
			for ($i = 0; $i < sizeof($id); $i++) {
				$kategori = array(
					'id' => $id[$i],
					'nama_kategori_unsur_pelayanan' => $nama_kategori_input[$i]
				);
				$this->db->where('id', $id[$i]);
				$this->db->update('kategori_unsur_pelayanan_' . $manage_survey->table_identity, $kategori);
			}
		}

		$pesan = 'Data berhasil disimpan';
		$msg = ['sukses' => $pesan];
		echo json_encode($msg);
	}



	public function add_pertanyaan_tambahan()
	{
		$slug = $this->uri->segment(2);
		$manage_survey = $this->db->get_where('manage_survey', array('slug' => "$slug"))->row();
		$table_identity = $manage_survey->table_identity;

		$this->db->select('(COUNT(nomor_pertanyaan_terbuka)+1) AS nomor_terbuka');
		$this->db->from('pertanyaan_terbuka_' . $table_identity);
		$nomor_pertanyaan_terbuka = $this->db->get()->row()->nomor_terbuka;

		$input 	= $this->input->post(NULL, TRUE);

		if ($input['jenis_pertanyaan_tambahan'] == 1) {
			$data = [
				'id_unsur_pelayanan' 	=> $input['id_unsur_pelayanan'],
				'nomor_pertanyaan_terbuka' 	=> 'T' . $nomor_pertanyaan_terbuka,
				'nama_pertanyaan_terbuka' 	=> $input['nama_pertanyaan_terbuka']
			];
		} else {
			$data = [
				'is_letak_pertanyaan' 	=> $input['is_letak_pertanyaan_tambahan'],
				'nomor_pertanyaan_terbuka' 	=> 'T' . $nomor_pertanyaan_terbuka,
				'nama_pertanyaan_terbuka' 	=> $input['nama_pertanyaan_terbuka']
			];
		}
		// var_dump($data);
		$this->db->insert('pertanyaan_terbuka_' . $table_identity, $data);

		$id_pertanyaan_terbuka = $this->db->insert_id();
		if ($this->input->post('jenis_jawaban') == '2') {
			$object = [
				'isi_pertanyaan_terbuka' 	=> $input['isi_pertanyaan_terbuka'],
				'id_pertanyaan_terbuka' 	=> $id_pertanyaan_terbuka,
				'id_jenis_pilihan_jawaban' 	=> $input['jenis_jawaban']
			];
			$this->db->insert('perincian_pertanyaan_terbuka_' . $table_identity, $object);
		} else {
			$object = [
				'isi_pertanyaan_terbuka' 	=> $input['isi_pertanyaan_terbuka'],
				'id_pertanyaan_terbuka' 	=> $id_pertanyaan_terbuka,
				'id_jenis_pilihan_jawaban' 	=> $input['jenis_jawaban']
			];
			// var_dump($object);
			$this->db->insert('perincian_pertanyaan_terbuka_' . $table_identity, $object);

			$id_perincian_pertanyaan_terbuka = $this->db->insert_id();
			$pilihan_jawaban = $input['pilihan_jawaban'];

			if (isset($_POST['opsi_pilihan_jawaban'])) {
				$opsi_pilihan_jawaban = $input['opsi_pilihan_jawaban'];
			} else {
				$opsi_pilihan_jawaban =  2;
			}

			$result = array();
			foreach ($_POST['pilihan_jawaban'] as $key => $val) {
				$result[] = array(
					'id_perincian_pertanyaan_terbuka' => $id_perincian_pertanyaan_terbuka,
					'pertanyaan_ganda' => $pilihan_jawaban[$key],
					'dengan_isian_lainnya' => $opsi_pilihan_jawaban
				);
			}
			$this->db->insert_batch('isi_pertanyaan_ganda_' . $table_identity, $result);
		}
		$pesan = 'Data berhasil disimpan';
		$msg = ['sukses' => $pesan];
		echo json_encode($msg);
	}

	public function get_detail_edit_pertanyaan_tambahan()
	{
		$id_pertanyaan_terbuka = $this->uri->segment(5);

		$this->data = [];
		$this->data['id_pertanyaan_terbuka'] = $id_pertanyaan_terbuka;

		$newdata = array(
			'input'  => $this->data
		);

		$this->session->set_userdata($newdata);

		$this->db->select('');
		$this->db->from('manage_survey');
		$this->db->where('manage_survey.slug', $this->uri->segment(2));
		$this->data['manage_survey'] = $this->db->get()->row();
		$table_identity = $this->data['manage_survey']->table_identity;
		$this->data['status_saran'] = $this->data['manage_survey']->is_saran;

		//PERTANYAAN TERBUKA
		$this->data['current'] = $this->db->query("SELECT *, perincian_pertanyaan_terbuka_$table_identity.id AS id_perincian_pertanyaan_terbuka
		FROM pertanyaan_terbuka_$table_identity
		JOIN perincian_pertanyaan_terbuka_$table_identity ON pertanyaan_terbuka_$table_identity.id = perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka
		WHERE id_pertanyaan_terbuka = $id_pertanyaan_terbuka")->row();

		$this->data['pilihan_jawaban'] = $this->db->query("SELECT * , isi_pertanyaan_ganda_$table_identity.id AS id_isi_pertanyaan_ganda
		FROM isi_pertanyaan_ganda_$table_identity
		JOIN perincian_pertanyaan_terbuka_$table_identity ON isi_pertanyaan_ganda_$table_identity.id_perincian_pertanyaan_terbuka = perincian_pertanyaan_terbuka_$table_identity.id
		WHERE id_pertanyaan_terbuka = '$id_pertanyaan_terbuka'");


		$this->data['nama_pertanyaan_terbuka'] = [
			'name' 		=> 'nama_pertanyaan_terbuka',
			'id'		=> 'nama_pertanyaan_terbuka',
			'type'		=> 'text',
			'value'		=>	$this->form_validation->set_value('nama_pertanyaan_terbuka', $this->data['current']->nama_pertanyaan_terbuka),
			'class'		=> 'form-control',
			'required' => 'required'
		];

		return view('form_survei/pertanyaan/detail_edit_pertanyaan_terbuka', $this->data);
	}


	public function edit_pertanyaan_tambahan()
	{
		$slug = $this->uri->segment('2');
		$manage_survey = $this->db->get_where("manage_survey", array('slug' => "$slug"))->row();
		$table_identity = $manage_survey->table_identity;

		$id_pertanyaan_terbuka = $this->uri->segment(5);
		$input 	= $this->input->post(NULL, TRUE);

		$data = [
			'nama_pertanyaan_terbuka' 	=> $input['nama_pertanyaan_terbuka']
		];

		$this->db->where('id', $id_pertanyaan_terbuka);
		$this->db->update('pertanyaan_terbuka_' . $table_identity, $data);
		// var_dump($data);

		$object = [
			'isi_pertanyaan_terbuka' 	=> $input['isi_pertanyaan_terbuka']
		];
		$this->db->where('id_pertanyaan_terbuka', $id_pertanyaan_terbuka);
		$this->db->update('perincian_pertanyaan_terbuka_' . $table_identity, $object);

		if ($this->input->post('id_jenis_jawaban') == 1) {

			$id = $input['id_kategori'];
			$pertanyaan_ganda = $input['pertanyaan_ganda'];

			for ($i = 0; $i < sizeof($id); $i++) {
				$kategori = array(
					'id' => $id[$i],
					'pertanyaan_ganda' => ($pertanyaan_ganda[$i])
				);
				$this->db->where('id', $id[$i]);
				$this->db->update('isi_pertanyaan_ganda_' . $table_identity, $kategori);
			}
			// var_dump($kategori);
		}

		$pesan = 'Data berhasil disimpan';
		$msg = ['sukses' => $pesan];
		echo json_encode($msg);
	}





	//====================================== PERTANYAAN HARAPAN ================================================
	//==========================================================================================================
	//==========================================================================================================
	public function data_pertanyaan_harapan($id1, $id2)
	{
		$this->data = [];
		$this->data['title'] = 'Edit Form Pertanyaan Harapan';
		$this->data['profiles'] = $this->_get_data_profile($id1, $id2);

		$this->db->select('');
		$this->db->from('manage_survey');
		$this->db->where('manage_survey.slug', $this->uri->segment(2));
		$this->data['manage_survey'] = $this->db->get()->row();
		$table_identity = $this->data['manage_survey']->table_identity;
		$this->data['status_saran'] = $this->data['manage_survey']->is_saran;

		$this->data['pertanyaan_harapan'] = $this->db->query("SELECT pertanyaan_unsur_pelayanan_$table_identity.id AS id,
		pertanyaan_unsur_pelayanan_$table_identity.isi_pertanyaan_unsur,
		( SELECT nama_tingkat_kepentingan FROM nilai_tingkat_kepentingan_$table_identity WHERE nomor_tingkat_kepentingan = 1 AND nilai_tingkat_kepentingan_$table_identity.id_pertanyaan_unsur_pelayanan = pertanyaan_unsur_pelayanan_$table_identity.id ) AS pilihan_1,
		( SELECT nama_tingkat_kepentingan FROM nilai_tingkat_kepentingan_$table_identity WHERE nomor_tingkat_kepentingan = 2 AND nilai_tingkat_kepentingan_$table_identity.id_pertanyaan_unsur_pelayanan = pertanyaan_unsur_pelayanan_$table_identity.id ) AS pilihan_2,
		( SELECT nama_tingkat_kepentingan FROM nilai_tingkat_kepentingan_$table_identity WHERE nomor_tingkat_kepentingan = 3 AND nilai_tingkat_kepentingan_$table_identity.id_pertanyaan_unsur_pelayanan = pertanyaan_unsur_pelayanan_$table_identity.id ) AS pilihan_3,
		( SELECT nama_tingkat_kepentingan FROM nilai_tingkat_kepentingan_$table_identity WHERE nomor_tingkat_kepentingan = 4 AND nilai_tingkat_kepentingan_$table_identity.id_pertanyaan_unsur_pelayanan = pertanyaan_unsur_pelayanan_$table_identity.id ) AS pilihan_4,
		IF(unsur_pelayanan_$table_identity.is_sub_unsur_pelayanan = 2, SUBSTRING(nama_unsur_pelayanan, 1, 2), SUBSTRING(nama_unsur_pelayanan, 1, 4)) AS nomor, SUBSTRING(nomor_unsur, 2, 4) AS nomor_harapan
		FROM pertanyaan_unsur_pelayanan_$table_identity
		JOIN unsur_pelayanan_$table_identity ON unsur_pelayanan_$table_identity.id = pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan
		ORDER BY pertanyaan_unsur_pelayanan_$table_identity.id ASC");

		$this->load->model('PertanyaanHarapanSurvei_model');
		$this->data['pilihan_jawaban'] = $this->PertanyaanHarapanSurvei_model->tampil_data();


		$atribut_pertanyaan = unserialize($this->data['manage_survey']->atribut_pertanyaan_survey);

		if (in_array(3, $atribut_pertanyaan)) {
			$this->data['url_next'] = base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2)
				. '/form-survei/pertanyaan-kualitatif';
		} else if ($this->data['manage_survey']->is_saran == 1) {
			$this->data['url_next'] = base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2)
				. '/form-survei/saran';
		} else {
			$this->data['url_next'] = base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2) . '/form-survei/konfirmasi';
		}

		if ($this->data['manage_survey']->is_question == 1) {
			return view('form_survei/pertanyaan_harapan/form_pertanyaan_harapan', $this->data);
		} else {
			$this->session->set_flashdata('message_warning', 'Anda tidak dapat merubah pertanyaan survei!');
			redirect(base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2) . '/form-survei', 'refresh');
		}
	}


	public function edit_pertanyaan_harapan($id1, $id2)
	{
		$this->db->select('');
		$this->db->from('manage_survey');
		$this->db->where('manage_survey.slug', $this->uri->segment(2));
		$table_identity_manage_survey = $this->db->get()->row()->table_identity;

		$this->form_validation->set_rules('pilihan_1', 'Pilihan 1', 'trim|required');
		$this->form_validation->set_rules('pilihan_2', 'Pilihan 2', 'trim|required');
		$this->form_validation->set_rules('pilihan_3', 'Pilihan 3', 'trim|required');
		$this->form_validation->set_rules('pilihan_4', 'Pilihan 4', 'trim|required');

		if ($this->form_validation->run() == FALSE) {
			return view('pertanyaan_harapan_survei/index', $this->data);
		} else {
			$input     = $this->input->post(NULL, TRUE);
			$object_1 = [
				'nama_tingkat_kepentingan'     => $input['pilihan_1']
			];

			$object_2 = [
				'nama_tingkat_kepentingan'     => $input['pilihan_2']
			];

			$object_3 = [
				'nama_tingkat_kepentingan'     => $input['pilihan_3']
			];

			$object_4 = [
				'nama_tingkat_kepentingan'     => $input['pilihan_4']
			];
			$this->db->where('nomor_tingkat_kepentingan', 1);
			$this->db->update('nilai_tingkat_kepentingan_' . $table_identity_manage_survey, $object_1);

			$this->db->where('nomor_tingkat_kepentingan', 2);
			$this->db->update('nilai_tingkat_kepentingan_' . $table_identity_manage_survey, $object_2);

			$this->db->where('nomor_tingkat_kepentingan', 3);
			$this->db->update('nilai_tingkat_kepentingan_' . $table_identity_manage_survey, $object_3);

			$this->db->where('nomor_tingkat_kepentingan', 4);
			$this->db->update('nilai_tingkat_kepentingan_' . $table_identity_manage_survey, $object_4);

			// var_dump($object_1);
		}
		$pesan = 'Data berhasil disimpan';
		$msg = ['sukses' => $pesan];
		echo json_encode($msg);
	}



	//==========================================================================================================
	//==========================================================================================================
	//==========================================================================================================
	public function pertanyaan_kualitatif($id1, $id2)
	{
		$this->data = array();
		$this->data['title'] = 'Edit Form Pertanyaan Kualitatif';
		$this->data['profiles'] = $this->_get_data_profile($id1, $id2);

		$this->db->select('');
		$this->db->from('manage_survey');
		$this->db->where('manage_survey.slug', $this->uri->segment(2));
		$this->data['manage_survey'] = $this->db->get()->row();
		$table_identity_manage_survey = $this->data['manage_survey']->table_identity;
		$this->data['status_saran'] = $this->data['manage_survey']->is_saran;


		$this->data['kualitatif'] = $this->db->query("select *
		FROM pertanyaan_kualitatif_$table_identity_manage_survey")->result();

		$atribut_pertanyaan = unserialize($this->data['manage_survey']->atribut_pertanyaan_survey);


		if (in_array(1, $atribut_pertanyaan)) {
			$this->data['url_back'] = base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2)
				. '/form-survei/pertanyaan-harapan';
		} else {
			$this->data['url_back'] = base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2) . '/form-survei/pertanyaan';
		}

		if ($this->data['manage_survey']->is_saran == 1) {
			$this->data['url_next'] = base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2)
				. '/form-survei/saran';
		} else {
			$this->data['url_next'] = base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2) . '/form-survei/konfirmasi';
		}

		if ($this->data['manage_survey']->is_question == 1) {
			return view('form_survei/pertanyaan_kualitatif/form_pertanyaan_kualitatif', $this->data);
		} else {
			$this->session->set_flashdata('message_warning', 'Anda tidak dapat merubah pertanyaan survei!');
			redirect(base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2) . '/form-survei', 'refresh');
		}
	}

	public function add_pertanyaan_kualitatif()
	{
		$slug = $this->uri->segment(2);
		$manage_survey = $this->db->get_where("manage_survey", array('slug' => "$slug"))->row();
		$table_identity = $manage_survey->table_identity;

		$input 	= $this->input->post(NULL, TRUE);

		if ($input['isi_pertanyaan'] == NULL) {
			$this->session->set_flashdata('message_danger', 'Gagal Menambah Data!');
			redirect(base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2) . '/form-survei/pertanyaan-kualitatif', 'refresh');
		}

		$object = [
			'isi_pertanyaan' 	=> $input['isi_pertanyaan'],
			'is_active' 	=> $input['is_active']
		];
		$this->db->insert('pertanyaan_kualitatif_' . $table_identity, $object);

		$pesan = 'Data berhasil disimpan';
		$msg = ['sukses' => $pesan];
		echo json_encode($msg);
	}

	public function get_detail_edit_pertanyaan_kualitatif()
	{
		$id_pertanyaan_kualitatif = $this->uri->segment(5);

		$this->data = [];
		$this->data['id_pertanyaan_kualitatif'] = $id_pertanyaan_kualitatif;

		$newdata = array(
			'input'  => $this->data
		);

		$this->session->set_userdata($newdata);

		$this->db->select('');
		$this->db->from('manage_survey');
		$this->db->where('manage_survey.slug', $this->uri->segment(2));
		$this->data['manage_survey'] = $this->db->get()->row();
		$table_identity = $this->data['manage_survey']->table_identity;
		$this->data['status_saran'] = $this->data['manage_survey']->is_saran;

		$this->data['kualitatif'] = $this->db->get_where('pertanyaan_kualitatif_' . $table_identity, ['id' => $id_pertanyaan_kualitatif])->row();

		return view('form_survei/pertanyaan_kualitatif/detail_edit_kualitatif', $this->data);
	}

	public function edit_pertanyaan_kualitatif()
	{
		$slug = $this->uri->segment(2);
		$manage_survey = $this->db->get_where("manage_survey", array('slug' => "$slug"))->row();
		$table_identity = $manage_survey->table_identity;

		$input 	= $this->input->post(NULL, TRUE);

		if ($input['isi_pertanyaan'] == NULL) {
			$this->session->set_flashdata('message_danger', 'Gagal Menambah Data!');
			redirect(base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2) . '/form-survei/pertanyaan-kualitatif', 'refresh');
		}

		$object = [
			'isi_pertanyaan' 	=> $input['isi_pertanyaan'],
			'is_active' 	=> $input['is_active']
		];
		$this->db->where('id', $this->uri->segment(5));
		$this->db->update('pertanyaan_kualitatif_' . $table_identity, $object);

		$pesan = 'Data berhasil disimpan';
		$msg = ['sukses' => $pesan];
		echo json_encode($msg);
	}



	//==========================================================================================================
	//==========================================================================================================
	//==========================================================================================================
	public function saran($id1, $id2)
	{
		$this->data = [];
		$this->data['title'] = 'Edit Form Saran';
		$this->data['profiles'] = $this->_get_data_profile($id1, $id2);

		$this->db->select('');
		$this->db->from('manage_survey');
		$this->db->where('manage_survey.slug', $this->uri->segment(2));
		$this->data['manage_survey'] = $this->db->get()->row();

		$this->data['saran'] = [
			'name' 		=> 'saran',
			'id'		=> 'saran',
			'type'		=> 'text',
			'value'		=>	$this->form_validation->set_value('saran'),
			'class'		=> 'form-control',
			'placeholder' => 'Masukkan saran atau opini anda terhadap survei ini ..',
		];

		$atribut_pertanyaan = unserialize($this->data['manage_survey']->atribut_pertanyaan_survey);

		if (in_array(3, $atribut_pertanyaan)) {
			$this->data['url_back'] = base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2)
				. '/form-survei/pertanyaan-kualitatif';
		} else if (in_array(1, $atribut_pertanyaan)) {
			$this->data['url_back'] = base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2) . '/form-survei/pertanyaan-harapan';
		} else {
			$this->data['url_back'] = base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2) . '/form-survei/pertanyaan';
		}

		if ($this->data['manage_survey']->is_question == 1) {
			return view('form_survei/form_saran', $this->data);
		} else {
			$this->session->set_flashdata('message_warning', 'Anda tidak dapat merubah pertanyaan survei!');
			redirect(base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2) . '/form-survei', 'refresh');
		}
	}



	//==========================================================================================================
	//==========================================================================================================
	//==========================================================================================================
	public function form_konfirmasi($id1, $id2)
	{
		$this->data = [];
		$this->data['title'] = 'Edit Form Form Konfirmasi';
		$this->data['profiles'] = $this->_get_data_profile($id1, $id2);

		$this->db->select('');
		$this->db->from('manage_survey');
		$this->db->where('manage_survey.slug', $this->uri->segment(2));
		$this->data['manage_survey'] = $this->db->get()->row();
		$this->data['status_saran'] = $this->data['manage_survey']->is_saran;

		if ($this->data['manage_survey']->is_saran == 1) {

			$this->data['url_back'] = base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2) . '/form-survei/saran';
		} else if (in_array(3, unserialize($this->data['manage_survey']->atribut_pertanyaan_survey))) {

			$this->data['url_back'] = base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2)
				. '/form-survei/pertanyaan-kualitatif';
		} else if (in_array(1, unserialize($this->data['manage_survey']->atribut_pertanyaan_survey))) {

			$this->data['url_back'] = base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2)
				. '/form-survei/pertanyaan-harapan';
		} else {

			$this->data['url_back'] = base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2)
				. '/form-survei/pertanyaan';
		}

		if ($this->data['manage_survey']->is_question == 1) {
			return view('form_survei/form_konfirmasi', $this->data);
		} else {
			$this->session->set_flashdata('message_warning', 'Anda tidak dapat merubah pertanyaan survei!');
			redirect(base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2) . '/form-survei', 'refresh');
		}
	}



	//==========================================================================================================
	//==========================================================================================================
	//==========================================================================================================
	public function form_closing()
	{
		$this->data = [];
		$this->data['title'] = 'Edit Form Sukses';

		$this->db->select('*');
		$this->db->from('manage_survey');
		$this->db->where('manage_survey.slug', $this->uri->segment(2));
		$this->data['judul'] = $this->db->get()->row();
		$this->data['status_saran'] = $this->data['judul']->is_saran;


		if ($this->data['judul']->is_question == 1) {
			return view('form_survei/form_closing', $this->data);
		} else {
			$this->session->set_flashdata('message_warning', 'Anda tidak dapat merubah pertanyaan survei!');
			redirect(base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2) . '/form-survei', 'refresh');
		}
	}



	//==========================================================================================================
	//==========================================================================================================
	//==========================================================================================================
	public function _get_data_profile($id1, $id2)
	{
		$this->db->select('*');
		$this->db->from('users');
		$this->db->join('users_groups', 'users.id = users_groups.user_id');
		$this->db->where('users.username', $this->session->userdata('username'));
		$data_user = $this->db->get()->row();
		$user_identity = 'drs' . $data_user->is_parent;

		$this->db->select('users.username, manage_survey.survey_name, is_question, manage_survey.slug, manage_survey.description, manage_survey.is_privacy, manage_survey.table_identity, manage_survey.id_jenis_pelayanan, manage_survey.atribut_pertanyaan_survey');
		if ($data_user->group_id == 2) {
			$this->db->from('users');
			$this->db->join('manage_survey', 'manage_survey.id_user = users.id');
		} else {
			$this->db->from('manage_survey');
			$this->db->join("supervisor_$user_identity", "manage_survey.id_berlangganan = supervisor_$user_identity.id_berlangganan");
			$this->db->join("users", "supervisor_$user_identity.id_user = users.id");
		}
		$this->db->where('users.username', $id1);
		$this->db->where('manage_survey.slug', $id2);
		$profiles = $this->db->get();

		if ($profiles->num_rows() == 0) {
			// echo 'Survey tidak ditemukan atau sudah dihapus !';
			// exit();
			show_404();
		}
		return $profiles->row();
	}
}

/* End of file PertanyaanKualitatifController.php */
/* Location: ./application/controllers/PertanyaanKualitatifController.php */
