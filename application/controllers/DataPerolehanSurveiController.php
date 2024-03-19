<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DataPerolehanSurveiController extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		if (!$this->ion_auth->logged_in()) {
			$this->session->set_flashdata('message_warning', 'You must be an admin to view this page');
			redirect('auth', 'refresh');
		}
		$this->load->model('DataPerolehanSurvei_model', 'models');
	}

	public function index($id1, $id2)
	{
		$url = $this->uri->uri_string();
		$this->session->set_userdata('urlback', $url);

		$this->data = [];
		$this->data['title'] = 'Perolehan Survei';
		$this->data['profiles'] = $this->_get_data_profile($id1, $id2);

		$this->db->select('manage_survey.id AS id_manage_survey, manage_survey.table_identity AS table_identity');
		$this->db->from('manage_survey');
		$this->db->where('manage_survey.slug', $this->uri->segment(2));
		$manage_survey = $this->db->get()->row();

		//PANGGIL PROFIL RESPONDEN
		$this->data['profil'] = $this->db->query("SELECT * FROM profil_responden_$manage_survey->table_identity ORDER BY IF(urutan != '',urutan,id) ASC")->result();

		//PANGGIL PROFIL RESPONDEN UNTUK FILTER
		$this->data['profil_responden_filter'] = $this->db->query("SELECT * FROM profil_responden_$manage_survey->table_identity WHERE jenis_isian = 1");

		//LOAD KATEGORI PROFIL RESPONDEN JIKA PILIHAN GANDA
		$this->data['kategori_profil_responden'] = $this->db->get('kategori_profil_responden_' . $manage_survey->table_identity);

		return view('data_perolehan_survei/index', $this->data);
	}

	public function ajax_list()
	{
		$slug = $this->uri->segment(2);

		$get_identity = $this->db->get_where('manage_survey', ['slug' => "$slug"])->row();
		$table_identity = $get_identity->table_identity;

		//PANGGIL PROFIL RESPONDEN
		$profil_responden = $this->db->query("SELECT * FROM profil_responden_$table_identity ORDER BY IF(urutan != '',urutan,id) ASC")->result();

		$list = $this->models->get_datatables($table_identity, $profil_responden);
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			if ($value->is_submit == 1) {
				$status = '<span class="badge badge-primary">Lengkap</span>';
			} else {
				$status = '<span class="badge badge-danger">Tidak Lengkap</span><br>
				<small class="text-dark-50 font-italic">' . $value->is_end . '</small>';
			}

			$no++;
			$row = array();
			$row[] = '<div class="checkbox-list"><label class="checkbox"><input type="checkbox" name="delete_list[]" value="' . $value->id_responden . '" class="child"><span></span>' . $no . '</label></div>';

			$row[] = $status;
			$row[] = anchor($this->uri->segment(2) . '/hasil-survei/' . $value->uuid_responden, '<i class="fas fa-file-pdf text-danger"></i>', ['target' => '_blank']);
			$row[] = $value->first_name . ' ' . $value->last_name;
			// $row[] = $value->first_name_surveyor_induk . ' ' . $value->last_name_surveyor_induk;
			// $row[] = $value->nama_lengkap;

			if($get_identity->is_layanan_survei != 0){
				$row[] = $value->nama_layanan;
			}

			
			foreach ($profil_responden as $get) {
				$profil = $get->nama_alias;
				$row[] = $value->$profil;
			}

			if($this->uri->segment(2) != 'bbpmp-balai-besar-penjaminan-mutu-pendidikan'){
				$row[] = date("H:i", strtotime($value->waktu_isi)) . '<br>' . date("d-m-Y", strtotime($value->waktu_isi));
			}

			// $row[] = anchor('/survei/' . $slug . '/data-responden/'  . $value->uuid_responden . '/edit', '<i class="fa fa-edit"></i> Edit', ['class' => 'btn btn-light-primary btn-sm font-weight-bold shadow', 'target' => '_blank']);
			if ((time() < strtotime($get_identity->survey_end))) { 
				$row[] = '<a class="btn btn-light-primary btn-sm font-weight-bold shadow" href="javascript:void(0)" title="Hapus" onclick="delete_data(' . "'" . $value->id_responden . "'" . ')"><i class="fa fa-trash"></i> Delete</a>';
			}else{
				$row[] = ''; 
			}
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->models->count_all($table_identity, $profil_responden),
			"recordsFiltered" => $this->models->count_filtered($table_identity, $profil_responden),
			"data" => $data,
		);

		echo json_encode($output);
	}

	public function delete()
	{
		// $this->data['profiles'] = $this->_get_data_profile($id1, $id2);
		$this->db->select('*');
		$this->db->from('manage_survey');
		$this->db->where('manage_survey.slug', $this->uri->segment(2));
		$manage_survey = $this->db->get()->row();
		$table_identity = $manage_survey->table_identity;
		$atribut_pertanyaan = unserialize($manage_survey->atribut_pertanyaan_survey);
	
		$this->db->query("INSERT INTO trash_responden_$table_identity SELECT * FROM responden_$table_identity WHERE id = " . $this->uri->segment(5));
		$this->db->query("INSERT INTO trash_survey_$table_identity SELECT * FROM survey_$table_identity WHERE id_responden = " . $this->uri->segment(5));
		$this->db->query("INSERT INTO trash_jawaban_pertanyaan_unsur_$table_identity SELECT * FROM jawaban_pertanyaan_unsur_$table_identity WHERE id_responden = " . $this->uri->segment(5));
		
		//PINDAH KE TABEL SAMPAH
		if (in_array(3, $atribut_pertanyaan)) {
			$this->db->query("INSERT INTO trash_jawaban_pertanyaan_kualitatif_$table_identity SELECT * FROM jawaban_pertanyaan_kualitatif_$table_identity WHERE id_responden = " . $this->uri->segment(5));
		}
		if (in_array(2, $atribut_pertanyaan)) {
			$this->db->query("INSERT INTO trash_jawaban_pertanyaan_terbuka_$table_identity SELECT * FROM jawaban_pertanyaan_terbuka_$table_identity WHERE id_responden = " . $this->uri->segment(5));
		}
		if (in_array(1, $atribut_pertanyaan)) {
			$this->db->query("INSERT INTO trash_jawaban_pertanyaan_harapan_$table_identity SELECT * FROM jawaban_pertanyaan_harapan_$table_identity WHERE id_responden = " . $this->uri->segment(5));
		}


		//UBAH WAKTU DELETE
		$obj = [
			'deleted_at' => date("Y/m/d H:i:s"),
		];
		$this->db->where('id', $this->uri->segment(5));
		$this->db->update("trash_responden_$table_identity", $obj);


		$this->db->delete('jawaban_pertanyaan_kualitatif_' . $table_identity, array('id_responden' => $this->uri->segment(5)));
		$this->db->delete('jawaban_pertanyaan_terbuka_' . $table_identity, array('id_responden' => $this->uri->segment(5)));
		$this->db->delete('jawaban_pertanyaan_unsur_' . $table_identity, array('id_responden' => $this->uri->segment(5)));
		$this->db->delete('jawaban_pertanyaan_harapan_' . $table_identity, array('id_responden' => $this->uri->segment(5)));
		$this->db->delete('survey_' . $table_identity, array('id_responden' => $this->uri->segment(5)));
		$this->db->delete('responden_' . $table_identity, array('id' => $this->uri->segment(5)));

		echo json_encode(array("status" => TRUE));
	}

	public function delete_by_checkbox()
	{
		$slug = $this->uri->segment(2);
		$manage_survey = $this->db->get_where('manage_survey', ['slug' => "$slug"])->row();
		$table_identity = $manage_survey->table_identity;
		$atribut_pertanyaan = unserialize($manage_survey->atribut_pertanyaan_survey);

		if ($this->input->post('delete_list[]') != NULL) {
			$id = implode(", ", $this->input->post('delete_list[]'));

			
			$this->db->query("INSERT INTO trash_responden_$table_identity SELECT * FROM responden_$table_identity WHERE id IN ($id)");
			$this->db->query("INSERT INTO trash_survey_$table_identity SELECT * FROM survey_$table_identity WHERE id_responden IN ($id)");
			$this->db->query("INSERT INTO trash_jawaban_pertanyaan_unsur_$table_identity SELECT * FROM jawaban_pertanyaan_unsur_$table_identity WHERE id_responden IN ($id)");

			//PINDAH KE TABEL SAMPAH
			if (in_array(3, $atribut_pertanyaan)) {
				$this->db->query("INSERT INTO trash_jawaban_pertanyaan_kualitatif_$table_identity SELECT * FROM jawaban_pertanyaan_kualitatif_$table_identity WHERE id_responden IN ($id)");
			}
			if (in_array(2, $atribut_pertanyaan)) {
				$this->db->query("INSERT INTO trash_jawaban_pertanyaan_terbuka_$table_identity SELECT * FROM jawaban_pertanyaan_terbuka_$table_identity WHERE id_responden IN ($id)");
			}
			if (in_array(1, $atribut_pertanyaan)) {
				$this->db->query("INSERT INTO trash_jawaban_pertanyaan_harapan_$table_identity SELECT * FROM jawaban_pertanyaan_harapan_$table_identity WHERE id_responden IN ($id)");
			}

			//UBAH WAKTU DELETE
			$date = date("Y/m/d H:i:s");
			$this->db->query("UPDATE trash_responden_$table_identity SET deleted_at = '$date' WHERE id IN ($id)");


			$this->db->query("DELETE FROM jawaban_pertanyaan_kualitatif_$table_identity WHERE id_responden IN ($id)");
			$this->db->query("DELETE FROM jawaban_pertanyaan_terbuka_$table_identity WHERE id_responden IN ($id)");
			$this->db->query("DELETE FROM jawaban_pertanyaan_unsur_$table_identity WHERE id_responden IN ($id)");
			$this->db->query("DELETE FROM jawaban_pertanyaan_harapan_$table_identity WHERE id_responden IN ($id)");
			$this->db->query("DELETE FROM survey_$table_identity WHERE id_responden IN ($id)");
			$this->db->query("DELETE FROM responden_$table_identity WHERE id IN ($id)");
		}

		$pesan = 'Data berhasil disimpan';
		$msg = ['sukses' => $pesan];
		echo json_encode($msg);
	}

	public function export()
	{
		$this->data = [];
		$this->data['title'] = "Perolehan Survei";

		$slug = $this->uri->segment(2);

		$this->data['manage_survey'] = $this->db->get_where('manage_survey', ['slug' => "$slug"])->row();
		$table_identity = $this->data['manage_survey']->table_identity;
		$this->data['is_saran'] = $this->data['manage_survey']->is_saran;

		$this->data['unsur'] = $this->db->query("SELECT *, SUBSTR(nomor_unsur,2) AS nomor_harapan
		FROM unsur_pelayanan_$table_identity
		JOIN pertanyaan_unsur_pelayanan_$table_identity ON unsur_pelayanan_$table_identity.id = pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan
		");

		//PANGGIL PROFIL RESPONDEN
		$this->data['profil'] = $this->db->query("SELECT * FROM profil_responden_$table_identity ORDER BY IF(urutan != '',urutan,id) ASC");

		$data_profil = [];
		foreach ($this->data['profil']->result() as $get) {
			if ($get->jenis_isian == 1) {

				$data_profil[] = "(SELECT nama_kategori_profil_responden FROM kategori_profil_responden_$table_identity WHERE responden_$table_identity.$get->nama_alias = kategori_profil_responden_$table_identity.id) AS $get->nama_alias";
			} else {
				$data_profil[] = $get->nama_alias;
			}
		}
		$query_profil = implode(",", $data_profil);

		$this->db->select("*, responden_$table_identity.uuid AS uuid_responden, (SELECT first_name FROM users WHERE users.id = surveyor.id_user) AS first_name, (SELECT last_name FROM users WHERE users.id = surveyor.id_user) AS last_name,
		(SELECT nama_layanan FROM layanan_survei_$table_identity WHERE id_layanan_survei = layanan_survei_$table_identity.id) AS nama_layanan,
		$query_profil");
		$this->db->from("responden_$table_identity");
		$this->db->join("survey_$table_identity", "responden_$table_identity.id = survey_$table_identity.id_responden");
		$this->db->join("surveyor", "survey_$table_identity.id_surveyor = surveyor.id", "left");
		$this->db->where('is_submit', 1);
		$this->data['survey'] = $this->db->get();
		// var_dump($this->data['survey']->result());

		$atribut_pertanyaan = unserialize($this->data['manage_survey']->atribut_pertanyaan_survey);
		$this->data['atribut_pertanyaan'] = $atribut_pertanyaan;

		if ((in_array(1, $atribut_pertanyaan)) && ($this->data['manage_survey']->is_saran == 1)) {
			$this->data['colspan'] = (($this->data['unsur']->num_rows() * 3) + $this->data['profil']->num_rows() + 5);
		} else if ((in_array(1, $atribut_pertanyaan)) && ($this->data['manage_survey']->is_saran != 1)) {
			$this->data['colspan'] = (($this->data['unsur']->num_rows() * 3) + $this->data['profil']->num_rows() + 4);
		} else if ((!in_array(1, $atribut_pertanyaan)) && ($this->data['manage_survey']->is_saran == 1)) {
			$this->data['colspan'] = (($this->data['unsur']->num_rows() * 2) + $this->data['profil']->num_rows() + 5);
		} else {
			$this->data['colspan'] = (($this->data['unsur']->num_rows() * 2) + $this->data['profil']->num_rows() + 4);
		}

		$this->data['jawaban_unsur'] = $this->db->get("jawaban_pertanyaan_unsur_$table_identity");
		$this->data['jawaban_harapan'] = $this->db->get("jawaban_pertanyaan_harapan_$table_identity");

		header("Content-type: application/vnd-ms-excel");
		header("Content-Disposition: attachment; filename=$slug.xls");

		$this->load->view('data_perolehan_survei/cetak', $this->data);
	}

	public function export_all_pdf()
	{
		$this->data = [];
		$this->data['title'] = "Perolehan Survei";

		$this->data['user'] = $this->ion_auth->user()->row();

		$slug = $this->uri->segment(2);
		$manage_survey = $this->db->get_where('manage_survey', ['slug' => "$slug"])->row();
		$this->data['manage_survey'] =  $manage_survey;

		$this->data['responden'] = $this->db->query("SELECT nama_lengkap, handphone, alamat_responden, jenis_kelamin_responden, umur_responden, nama_pendidikan_terakhir_responden, nama_pekerjaan_utama_responden, pekerjaan_lainnya, nama_pembiayaan_responden, nama_status_responden, banyak_kunjungan, nama_pelayanan, lama_bekerja, created_at, id_responden, saran
		FROM responden_$manage_survey->table_identity
		LEFT JOIN jenis_kelamin ON responden_$manage_survey->table_identity.id_jenis_kelamin = jenis_kelamin.id
		LEFT JOIN umur ON responden_$manage_survey->table_identity.id_umur = umur.id
		LEFT JOIN pendidikan_terakhir ON responden_$manage_survey->table_identity.id_pendidikan_akhir = pendidikan_terakhir.id
		LEFT JOIN pekerjaan_utama ON responden_$manage_survey->table_identity.id_pekerjaan_utama = pekerjaan_utama.id
		LEFT JOIN pembiayaan ON responden_$manage_survey->table_identity.id_pembiayaan = pembiayaan.id
		LEFT JOIN status_responden ON responden_$manage_survey->table_identity.id_status_responden = status_responden.id
		LEFT JOIN jumlah_kunjungan ON responden_$manage_survey->table_identity.id_jumlah_kunjungan = jumlah_kunjungan.id
		LEFT JOIN jenis_pelayanan_bkpsdm ON responden_$manage_survey->table_identity.id_jenis_pelayanan_bkpsdm = jenis_pelayanan_bkpsdm.id
		JOIN survey_$manage_survey->table_identity ON responden_$manage_survey->table_identity.id = survey_$manage_survey->table_identity.id_responden
		WHERE is_submit = 1");


		$this->data['pertanyaan_unsur'] = $this->db->query("SELECT *, (SELECT nama_kategori_unsur_pelayanan FROM kategori_unsur_pelayanan_$manage_survey->table_identity WHERE id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$manage_survey->table_identity.id && nomor_kategori_unsur_pelayanan = 1 ) AS pilihan_1,
        (SELECT nama_kategori_unsur_pelayanan FROM kategori_unsur_pelayanan_$manage_survey->table_identity WHERE id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$manage_survey->table_identity.id && nomor_kategori_unsur_pelayanan = 2 ) AS pilihan_2,
        (SELECT nama_kategori_unsur_pelayanan FROM kategori_unsur_pelayanan_$manage_survey->table_identity WHERE id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$manage_survey->table_identity.id && nomor_kategori_unsur_pelayanan = 3 ) AS pilihan_3,
        (SELECT nama_kategori_unsur_pelayanan FROM kategori_unsur_pelayanan_$manage_survey->table_identity WHERE id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$manage_survey->table_identity.id && nomor_kategori_unsur_pelayanan = 4 ) AS pilihan_4 
        FROM pertanyaan_unsur_pelayanan_$manage_survey->table_identity
        JOIN unsur_pelayanan_$manage_survey->table_identity ON pertanyaan_unsur_pelayanan_$manage_survey->table_identity.id_unsur_pelayanan = unsur_pelayanan_$manage_survey->table_identity.id
				JOIN jawaban_pertanyaan_unsur_$manage_survey->table_identity ON pertanyaan_unsur_pelayanan_$manage_survey->table_identity.id = jawaban_pertanyaan_unsur_$manage_survey->table_identity.id_pertanyaan_unsur");

		$this->db->select("*");
		$this->db->from("pertanyaan_kualitatif_$manage_survey->table_identity");
		$this->db->join("jawaban_pertanyaan_kualitatif_$manage_survey->table_identity", "pertanyaan_kualitatif_$manage_survey->table_identity.id = jawaban_pertanyaan_kualitatif_$manage_survey->table_identity.id_pertanyaan_kualitatif");
		$this->data['jawaban_kualitatif'] = $this->db->get();

		$this->load->library('pdfgenerator');
		$this->data['title_pdf'] = $manage_survey->survey_name;
		$file_pdf = $manage_survey->survey_name;
		$paper = 'A4';
		$orientation = "potrait";

		$html = $this->load->view('data_perolehan_survei/export_pdf', $this->data, true);

		$this->pdfgenerator->generate($html, $file_pdf, $paper, $orientation);
	}

	public function _get_data_profile($id1, $id2)
	{
		$this->db->select('*');
		$this->db->from('users');
		$this->db->join('users_groups', 'users.id = users_groups.user_id');
		$this->db->where('users.username', $this->session->userdata('username'));
		$data_user = $this->db->get()->row();
		$user_identity = 'drs' . $data_user->is_parent;

		$this->db->select('*');
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

/* End of file DataPerolehanSurveiController.php */
/* Location: ./application/controllers/DataPerolehanSurveiController.php */