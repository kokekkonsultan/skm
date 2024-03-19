<?php
defined('BASEPATH') or exit('No direct script access allowed');

class KoreksiDataController extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		if (!$this->ion_auth->logged_in()) {
			$this->session->set_flashdata('message_warning', 'You must be an admin to view this page');
			redirect('auth', 'refresh');
		}
		$this->load->model('OlahData_model', 'models');
		$this->load->model('KoreksiData_model');
	}

	public function index($id1, $id2)
	{
		$this->data = [];
		$this->data['title'] = 'Koreksi Data';
		$this->data['profiles'] = $this->_get_data_profile($id1, $id2);

		$slug = $this->uri->segment(2);
		$manage_survey = $this->db->get_where('manage_survey', ['slug' => "$slug"])->row();
		$this->data['manage_survey'] = $manage_survey;
		$table_identity = $manage_survey->table_identity;
		$atribut_pertanyaan = unserialize($manage_survey->atribut_pertanyaan_survey);

		//CEK JUMLAH SURVEI
		if ($this->db->get_where("survey_$table_identity", ["is_submit" => 1])->num_rows() == 0) {
			$this->data['pesan'] = 'survei belum dimulai atau belum ada responden !';
			return view('not_questions/index', $this->data);
		}

		//PENDEFINISIAN SKALA LIKERT
		$this->data['skala_likert'] = 100 / ($manage_survey->skala_likert == 5 ? 5 : 4);
		$this->data['definisi_skala'] = $this->db->query("SELECT * FROM definisi_skala_$table_identity ORDER BY id DESC");


		//CEK STATUS KONFIRMASI
		if ($manage_survey->is_survey_close == '') {
			redirect(base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2) . '/koreksi-data/konfirmasi', 'refresh');
		}

		$this->session->set_userdata('aside_minimize', 1);
		if ($manage_survey->is_origin_backup != 1) {
			$object = [
				'is_origin_backup' => 1
			];
			$this->db->where('id', $manage_survey->id);
			$this->db->update('manage_survey', $object);

			//TABEL JAWABAN PERTANYAAN KUALITATIF ORIGINAL UNTUK BACKUP JAWABAN
			$this->db->query("CREATE TABLE origin_jawaban_pertanyaan_kualitatif_$table_identity LIKE jawaban_pertanyaan_kualitatif_$table_identity");

			//TABEL JAWABAN PERTANYAAN KUALITATIF KOREKSI UNTUK MENGOLAH JAWABAN
			$this->db->query("CREATE TABLE koreksi_jawaban_pertanyaan_kualitatif_$table_identity LIKE jawaban_pertanyaan_kualitatif_$table_identity");

			//TABEL JAWABAN PERTANYAAN TERBUKA ORIGINAL UNTUK BACKUP JAWABAN
			$this->db->query("CREATE TABLE origin_jawaban_pertanyaan_terbuka_$table_identity LIKE jawaban_pertanyaan_terbuka_$table_identity");

			//TABEL JAWABAN PERTANYAAN TERBUKA KOREKSI UNTUK MENGOLAH JAWABAN
			$this->db->query("CREATE TABLE koreksi_jawaban_pertanyaan_terbuka_$table_identity LIKE jawaban_pertanyaan_terbuka_$table_identity");

			//TABEL JAWABAN PERTANYAAN HARAPAN ORIGINAL UNTUK BACKUP JAWABAN
			$this->db->query("CREATE TABLE origin_jawaban_pertanyaan_harapan_$table_identity LIKE jawaban_pertanyaan_harapan_$table_identity");

			//TABEL JAWABAN PERTANYAAN HARAPAN KOREKSI UNTUK MENGOLAH JAWABAN
			$this->db->query("CREATE TABLE koreksi_jawaban_pertanyaan_harapan_$table_identity LIKE jawaban_pertanyaan_harapan_$table_identity");


			if (in_array(3, $atribut_pertanyaan)) {
				$this->db->query("INSERT INTO origin_jawaban_pertanyaan_kualitatif_$table_identity SELECT * FROM jawaban_pertanyaan_kualitatif_$table_identity");
				$this->db->query("INSERT INTO koreksi_jawaban_pertanyaan_kualitatif_$table_identity SELECT * FROM jawaban_pertanyaan_kualitatif_$table_identity");
			}

			if (in_array(2, $atribut_pertanyaan)) {
				$this->db->query("INSERT INTO origin_jawaban_pertanyaan_terbuka_$table_identity SELECT * FROM jawaban_pertanyaan_terbuka_$table_identity");
				$this->db->query("INSERT INTO koreksi_jawaban_pertanyaan_terbuka_$table_identity SELECT * FROM jawaban_pertanyaan_terbuka_$table_identity");
			}

			if (in_array(1, $atribut_pertanyaan)) {
				$this->db->query("INSERT INTO origin_jawaban_pertanyaan_harapan_$table_identity SELECT * FROM jawaban_pertanyaan_harapan_$table_identity");
				$this->db->query("INSERT INTO koreksi_jawaban_pertanyaan_harapan_$table_identity SELECT * FROM jawaban_pertanyaan_harapan_$table_identity");
			}

			//TABEL JAWABAN PERTANYAAN UNSUR ORIGINAL UNTUK BACKUP JAWABAN
			$this->db->query("CREATE TABLE origin_jawaban_pertanyaan_unsur_$table_identity LIKE jawaban_pertanyaan_unsur_$table_identity");
			$this->db->query("INSERT INTO origin_jawaban_pertanyaan_unsur_$table_identity SELECT * FROM jawaban_pertanyaan_unsur_$table_identity");

			//TABEL JAWABAN PERTANYAAN UNSUR KOREKSI UNTUK MENGOLAH JAWABAN
			$this->db->query("CREATE TABLE koreksi_jawaban_pertanyaan_unsur_$table_identity LIKE jawaban_pertanyaan_unsur_$table_identity");
			$this->db->query("INSERT INTO koreksi_jawaban_pertanyaan_unsur_$table_identity SELECT * FROM jawaban_pertanyaan_unsur_$table_identity");


			//TABEL SURVEI ORIGINAL UNTUK BACKUP JAWABAN
			$this->db->query("CREATE TABLE origin_survey_$table_identity LIKE survey_$table_identity");
			$this->db->query("INSERT INTO origin_survey_$table_identity SELECT * FROM survey_$table_identity");

			//TABEL SURVEI KOREKSI UNTUK MENGOLAH JAWABAN
			$this->db->query("CREATE TABLE koreksi_survey_$table_identity LIKE survey_$table_identity");
			$this->db->query("INSERT INTO koreksi_survey_$table_identity SELECT * FROM survey_$table_identity");


			//TABEL RESPONDEN ORIGINAL UNTUK BACKUP JAWABAN
			$this->db->query("CREATE TABLE origin_responden_$table_identity LIKE responden_$table_identity");
			$this->db->query("INSERT INTO origin_responden_$table_identity SELECT * FROM responden_$table_identity");

			//TABEL RESPONDEN KOREKSI UNTUK MENGOLAH JAWABAN
			$this->db->query("CREATE TABLE koreksi_responden_$table_identity LIKE responden_$table_identity");
			$this->db->query("INSERT INTO koreksi_responden_$table_identity SELECT * FROM responden_$table_identity");
		}


		$this->data['unsur'] = $this->db->query("SELECT *, SUBSTR(nomor_unsur,2) AS nomor_harapan
		FROM unsur_pelayanan_$table_identity
		JOIN pertanyaan_unsur_pelayanan_$table_identity ON unsur_pelayanan_$table_identity.id = pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan
		");

		//JUMLAH KUISIONER
		$this->data['jumlah_kuisioner'] = $this->db->get_where("survey_$table_identity", array('is_submit' => 1))->num_rows();

		if ($this->data['jumlah_kuisioner'] == 0) {
			$this->data['pesan'] = 'survei belum dimulai atau belum ada responden !';
			return view('not_questions/index', $this->data);
		}


		$this->data['jumlah_pertanyaan'] = $this->db->query("SELECT COUNT(id) AS colspan FROM pertanyaan_unsur_pelayanan_$table_identity")->row()->colspan;


		$this->_get_data_asli($table_identity);
		$this->_get_data_koreksi($table_identity);

		return view('koreksi_data/index', $this->data);
	}


	public function ajax_list_data_asli()
	{
		$slug = $this->uri->segment(2);

		$get_identity = $this->db->get_where('manage_survey', ['slug' => "$slug"])->row();
		$table_identity = $get_identity->table_identity;

		$jawaban_unsur = $this->db->get("jawaban_pertanyaan_unsur_$table_identity");

		$list = $this->models->get_datatables($table_identity);
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = '<span class="badge badge-primary">' . $value->total_skor . '</span>';
			// $row[] = $value->nama_lengkap;

			foreach ($jawaban_unsur->result() as $get_unsur) {
				if ($get_unsur->id_responden == $value->id_responden) {
					$row[] = $get_unsur->skor_jawaban;
				}
			}
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->models->count_all($table_identity),
			"recordsFiltered" => $this->models->count_filtered($table_identity),
			"data" => $data,
		);

		echo json_encode($output);
	}

	public function ajax_list_data_koreksi()
	{
		$slug = $this->uri->segment(2);

		$get_identity = $this->db->get_where('manage_survey', ['slug' => "$slug"])->row();
		$table_identity = $get_identity->table_identity;

		$jawaban_unsur = $this->db->get("jawaban_pertanyaan_unsur_$table_identity");

		$list = $this->KoreksiData_model->get_datatables($table_identity);
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			$no++;
			$row = array();
			$row[] = '<div class="checkbox-list"><label class="checkbox"><input type="checkbox" name="delete_list[]" value="' . $value->id_responden . '" class="child"><span></span>' . $no . '</label></div>';
			$row[] = '<span class="badge badge-warning text-white">' . $value->total_skor . '</span>';
			// $row[] = $value->nama_lengkap;

			foreach ($jawaban_unsur->result() as $get_unsur) {
				if ($get_unsur->id_responden == $value->id_responden) {
					$row[] = $get_unsur->skor_jawaban;
				}
			}
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->KoreksiData_model->count_all($table_identity),
			"recordsFiltered" => $this->KoreksiData_model->count_filtered($table_identity),
			"data" => $data,
		);

		echo json_encode($output);
	}

	public function _get_data_asli($table_identity)
	{
		//TOTAL
		$this->data['total'] = $this->db->query("SELECT SUM(skor_jawaban) AS sum_skor_jawaban
		FROM jawaban_pertanyaan_unsur_$table_identity
		JOIN responden_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = responden_$table_identity.id
		JOIN survey_$table_identity ON responden_$table_identity.id = survey_$table_identity.id
		WHERE is_submit = 1
		GROUP BY id_pertanyaan_unsur");

		//RATA-RATA
		$this->db->select("(SUM(skor_jawaban)/COUNT(DISTINCT jawaban_pertanyaan_unsur_$table_identity.id_responden)) AS rata_rata");
		$this->db->from('jawaban_pertanyaan_unsur_' . $table_identity);
		$this->db->join("survey_$table_identity", "jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden");
		$this->db->where("survey_$table_identity.is_submit = 1");
		$this->db->group_by('id_pertanyaan_unsur');
		$this->data['rata_rata'] = $this->db->get();

		//NILAI PER UNSUR
		$this->db->select("nama_unsur_pelayanan, IF(id_parent = 0,unsur_pelayanan_$table_identity.id, unsur_pelayanan_$table_identity.id_parent) AS id_sub, (SUM(skor_jawaban)/COUNT(DISTINCT survey_$table_identity.id_responden)) AS rata_rata,  (COUNT(id_parent)/COUNT(DISTINCT survey_$table_identity.id_responden)) AS colspan, ((SUM(skor_jawaban)/COUNT(DISTINCT survey_$table_identity.id_responden))/(COUNT(id_parent)/COUNT(DISTINCT survey_$table_identity.id_responden))) AS nilai_per_unsur");
		$this->db->from('jawaban_pertanyaan_unsur_' . $table_identity);
		$this->db->join("pertanyaan_unsur_pelayanan_$table_identity", "jawaban_pertanyaan_unsur_$table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id");
		$this->db->join("unsur_pelayanan_$table_identity", "pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan = unsur_pelayanan_$table_identity.id");
		$this->db->join("survey_$table_identity", "jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden");
		$this->db->where("survey_$table_identity.is_submit = 1");
		$this->db->group_by('id_sub');
		$this->data['nilai_per_unsur'] = $this->db->get();

		//RATA-RATA BOBOT
		$this->db->select("nama_unsur_pelayanan, IF(id_parent = 0,unsur_pelayanan_$table_identity.id, unsur_pelayanan_$table_identity.id_parent) AS id_sub, (SUM(skor_jawaban)/COUNT(DISTINCT survey_$table_identity.id_responden)) AS rata_rata,  (COUNT(id_parent)/COUNT(DISTINCT survey_$table_identity.id_responden)) AS colspan, ((SUM(skor_jawaban)/COUNT(DISTINCT survey_$table_identity.id_responden))/(COUNT(id_parent)/COUNT(DISTINCT survey_$table_identity.id_responden))) AS nilai, (((SUM(skor_jawaban)/COUNT(DISTINCT survey_$table_identity.id_responden))/(COUNT(id_parent)/COUNT(DISTINCT survey_$table_identity.id_responden)))/(SELECT COUNT(id) FROM unsur_pelayanan_$table_identity WHERE id_parent = 0)) AS rata_rata_bobot");
		$this->db->from('jawaban_pertanyaan_unsur_' . $table_identity);
		$this->db->join("pertanyaan_unsur_pelayanan_$table_identity", "jawaban_pertanyaan_unsur_$table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id");
		$this->db->join("unsur_pelayanan_$table_identity", "pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan = unsur_pelayanan_$table_identity.id");
		$this->db->join("survey_$table_identity", "jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden");
		$this->db->where("survey_$table_identity.is_submit = 1");
		$this->db->group_by('id_sub');
		$this->data['rata_rata_bobot'] = $this->db->get();
	}


	public function _get_data_koreksi($table_identity)
	{
		//JUMLAH KUISIONER
		$this->data['koreksi_jumlah_kuisioner'] = $this->db->get_where("koreksi_survey_$table_identity", array('is_submit' => 1))->num_rows();

		//TOTAL
		$this->data['koreksi_total'] = $this->db->query("SELECT SUM(skor_jawaban) AS sum_skor_jawaban
		FROM koreksi_jawaban_pertanyaan_unsur_$table_identity
		JOIN koreksi_responden_$table_identity ON koreksi_jawaban_pertanyaan_unsur_$table_identity.id_responden = koreksi_responden_$table_identity.id
		JOIN koreksi_survey_$table_identity ON koreksi_responden_$table_identity.id = koreksi_survey_$table_identity.id
		WHERE is_submit = 1
		GROUP BY id_pertanyaan_unsur");

		//RATA-RATA
		$this->data['koreksi_rata_rata'] = $this->db->query("SELECT (SUM(skor_jawaban)/COUNT(DISTINCT koreksi_jawaban_pertanyaan_unsur_$table_identity.id_responden)) AS rata_rata FROM koreksi_jawaban_pertanyaan_unsur_$table_identity
		JOIN koreksi_survey_$table_identity ON koreksi_jawaban_pertanyaan_unsur_$table_identity.id_responden = koreksi_survey_$table_identity.id_responden
		WHERE koreksi_survey_$table_identity.is_submit = 1
		GROUP BY id_pertanyaan_unsur");

		//NILAI PER UNSUR
		$this->data['koreksi_nilai_per_unsur'] = $this->db->query("SELECT nama_unsur_pelayanan, IF(id_parent = 0, unsur_pelayanan_$table_identity.id, unsur_pelayanan_$table_identity.id_parent) AS id_sub, (SUM(skor_jawaban)/COUNT(DISTINCT koreksi_survey_$table_identity.id_responden)) AS rata_rata, (COUNT(id_parent)/COUNT(DISTINCT koreksi_survey_$table_identity.id_responden)) AS colspan, ((SUM(skor_jawaban)/COUNT(DISTINCT koreksi_survey_$table_identity.id_responden))/(COUNT(id_parent)/COUNT(DISTINCT koreksi_survey_$table_identity.id_responden))) AS nilai_per_unsur
		FROM koreksi_jawaban_pertanyaan_unsur_$table_identity
		JOIN pertanyaan_unsur_pelayanan_$table_identity ON koreksi_jawaban_pertanyaan_unsur_$table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id
		JOIN unsur_pelayanan_$table_identity ON pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan = unsur_pelayanan_$table_identity.id
		JOIN koreksi_survey_$table_identity ON koreksi_jawaban_pertanyaan_unsur_$table_identity.id_responden = koreksi_survey_$table_identity.id_responden
		WHERE koreksi_survey_$table_identity.is_submit = 1
		GROUP BY id_sub");

		//RATA-RATA BOBOT
		$this->data['koreksi_rata_rata_bobot'] = $this->db->query("SELECT nama_unsur_pelayanan, IF(id_parent = 0, unsur_pelayanan_$table_identity.id, unsur_pelayanan_$table_identity.id_parent) AS id_sub, (SUM(skor_jawaban)/COUNT(DISTINCT koreksi_survey_$table_identity.id_responden)) AS rata_rata, (COUNT(id_parent)/COUNT(DISTINCT koreksi_survey_$table_identity.id_responden)) AS colspan, ((SUM(skor_jawaban)/COUNT(DISTINCT koreksi_survey_$table_identity.id_responden))/(COUNT(id_parent)/COUNT(DISTINCT koreksi_survey_$table_identity.id_responden))) AS nilai, (((SUM(skor_jawaban)/COUNT(DISTINCT koreksi_survey_$table_identity.id_responden))/(COUNT(id_parent)/COUNT(DISTINCT koreksi_survey_$table_identity.id_responden)))/(SELECT COUNT(id) FROM unsur_pelayanan_$table_identity WHERE id_parent = 0)) AS rata_rata_bobot
		FROM koreksi_jawaban_pertanyaan_unsur_$table_identity
		JOIN pertanyaan_unsur_pelayanan_$table_identity ON koreksi_jawaban_pertanyaan_unsur_$table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id
		JOIN unsur_pelayanan_$table_identity ON pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan = unsur_pelayanan_$table_identity.id
		JOIN koreksi_survey_$table_identity ON koreksi_jawaban_pertanyaan_unsur_$table_identity.id_responden = koreksi_survey_$table_identity.id_responden
		WHERE koreksi_survey_$table_identity.is_submit = 1
		GROUP BY id_sub");
	}

	public function delete_by_checkbox()
	{
		$slug = $this->uri->segment(2);
		$manage_survey = $this->db->get_where('manage_survey', ['slug' => "$slug"])->row();
		$atribut_pertanyaan = unserialize($manage_survey->atribut_pertanyaan_survey);

		if ($this->input->post('delete_list[]') != NULL) {

			$id = implode(", ", $this->input->post('delete_list[]'));
			$object = [
				'is_koreksi' => 1
			];
			$this->db->where('id', $manage_survey->id);
			$this->db->update('manage_survey', $object);

			if (in_array(3, $atribut_pertanyaan)) {
				$this->db->query("DELETE FROM koreksi_jawaban_pertanyaan_kualitatif_$manage_survey->table_identity WHERE id_responden IN ($id)");
			}

			if (in_array(2, $atribut_pertanyaan)) {
				$this->db->query("DELETE FROM koreksi_jawaban_pertanyaan_terbuka_$manage_survey->table_identity WHERE id_responden IN ($id)");
			}

			if (in_array(1, $atribut_pertanyaan)) {
				$this->db->query("DELETE FROM koreksi_jawaban_pertanyaan_harapan_$manage_survey->table_identity WHERE id_responden IN ($id)");
			}

			$this->db->query("DELETE FROM koreksi_jawaban_pertanyaan_unsur_$manage_survey->table_identity WHERE id_responden IN ($id)");
			$this->db->query("DELETE FROM koreksi_survey_$manage_survey->table_identity WHERE id_responden IN ($id)");
			$this->db->query("DELETE FROM koreksi_responden_$manage_survey->table_identity WHERE id IN ($id)");
		}

		$pesan = 'Data berhasil disimpan';
		$msg = ['sukses' => $pesan];
		echo json_encode($msg);
	}


	public function simpan_data_koreksi()
	{
		$slug = $this->uri->segment(2);
		$manage_survey = $this->db->get_where('manage_survey', ['slug' => "$slug"])->row();
		$table_identity = $manage_survey->table_identity;
		$atribut_pertanyaan = unserialize($manage_survey->atribut_pertanyaan_survey);

		$this->db->query("DELETE FROM responden_$table_identity");
		$this->db->query("INSERT INTO responden_$table_identity SELECT * FROM koreksi_responden_$table_identity");

		$this->db->query("DELETE FROM survey_$table_identity");
		$this->db->query("INSERT INTO survey_$table_identity SELECT * FROM koreksi_survey_$table_identity");

		$this->db->query("DELETE FROM jawaban_pertanyaan_unsur_$table_identity");
		$this->db->query("INSERT INTO jawaban_pertanyaan_unsur_$table_identity SELECT * FROM koreksi_jawaban_pertanyaan_unsur_$table_identity");

		if (in_array(3, $atribut_pertanyaan)) {
			$this->db->query("DELETE FROM jawaban_pertanyaan_kualitatif_$table_identity");
			$this->db->query("INSERT INTO jawaban_pertanyaan_kualitatif_$table_identity SELECT * FROM koreksi_jawaban_pertanyaan_kualitatif_$table_identity");
		}

		if (in_array(2, $atribut_pertanyaan)) {
			$this->db->query("DELETE FROM jawaban_pertanyaan_terbuka_$table_identity");
			$this->db->query("INSERT INTO jawaban_pertanyaan_terbuka_$table_identity SELECT * FROM koreksi_jawaban_pertanyaan_terbuka_$table_identity");
		}

		if (in_array(1, $atribut_pertanyaan)) {
			$this->db->query("DELETE FROM jawaban_pertanyaan_harapan_$table_identity");
			$this->db->query("INSERT INTO jawaban_pertanyaan_harapan_$table_identity SELECT * FROM koreksi_jawaban_pertanyaan_harapan_$table_identity");
		}

		$object = [
			'is_koreksi' => NULL
		];
		$this->db->where('id', $manage_survey->id);
		$this->db->update('manage_survey', $object);

		$pesan = 'Data berhasil disimpan';
		$msg = ['sukses' => $pesan];
		echo json_encode($msg);
	}

	public function restore_data_koreksi()
	{
		$slug = $this->uri->segment(2);
		$manage_survey = $this->db->get_where('manage_survey', ['slug' => "$slug"])->row();
		$table_identity = $manage_survey->table_identity;
		$atribut_pertanyaan = unserialize($manage_survey->atribut_pertanyaan_survey);


		if (in_array(3, $atribut_pertanyaan)) {
			$this->db->query("DELETE FROM koreksi_jawaban_pertanyaan_kualitatif_$table_identity");
			$this->db->query("INSERT INTO koreksi_jawaban_pertanyaan_kualitatif_$table_identity SELECT * FROM jawaban_pertanyaan_kualitatif_$table_identity");
		}

		if (in_array(2, $atribut_pertanyaan)) {
			$this->db->query("DELETE FROM koreksi_jawaban_pertanyaan_terbuka_$table_identity");
			$this->db->query("INSERT INTO koreksi_jawaban_pertanyaan_terbuka_$table_identity SELECT * FROM jawaban_pertanyaan_terbuka_$table_identity");
		}

		if (in_array(1, $atribut_pertanyaan)) {
			$this->db->query("DELETE FROM koreksi_jawaban_pertanyaan_harapan_$table_identity");
			$this->db->query("INSERT INTO koreksi_jawaban_pertanyaan_harapan_$table_identity SELECT * FROM jawaban_pertanyaan_harapan_$table_identity");
		}

		$this->db->query("DELETE FROM koreksi_jawaban_pertanyaan_unsur_$table_identity");
		$this->db->query("INSERT INTO koreksi_jawaban_pertanyaan_unsur_$table_identity SELECT * FROM jawaban_pertanyaan_unsur_$table_identity");

		$this->db->query("DELETE FROM koreksi_survey_$table_identity");
		$this->db->query("INSERT INTO koreksi_survey_$table_identity SELECT * FROM survey_$table_identity");

		$this->db->query("DELETE FROM koreksi_responden_$table_identity");
		$this->db->query("INSERT INTO koreksi_responden_$table_identity SELECT * FROM responden_$table_identity");

		$object = [
			'is_koreksi' => NULL
		];
		$this->db->where('id', $manage_survey->id);
		$this->db->update('manage_survey', $object);


		$pesan = 'Data berhasil disimpan';
		$msg = ['sukses' => $pesan];
		echo json_encode($msg);
	}


	public function konfirmasi($id1, $id2)
	{
		$this->data = [];
		$this->data['title'] = 'Konfirmasi Koreksi Data';
		$this->data['profiles'] = $this->_get_data_profile($id1, $id2);

		$slug = $this->uri->segment(2);
		$manage_survey = $this->db->get_where('manage_survey', ['slug' => "$slug"])->row();

		return view('koreksi_data/form_konfirmasi', $this->data);
	}

	public function update_konfirmasi()
	{
		$slug = $this->uri->segment(2);
		$manage_survey = $this->db->get_where('manage_survey', ['slug' => "$slug"])->row();

		if (date("Y-m-d") > $manage_survey->survey_end) {
			$survey_close = $manage_survey->survey_end;
		} else {
			$survey_close = date("Y-m-d");
		};

		$object = [
			'is_survey_close' => 1,
			'survey_end' => $survey_close
		];
		$this->db->where('id', $manage_survey->id);
		$this->db->update('manage_survey', $object);


		$pesan = 'Data berhasil disimpan';
		$msg = ['sukses' => $pesan];
		echo json_encode($msg);
	}


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

/* End of file DataPerolehanSurveiController.php */
/* Location: ./application/controllers/DataPerolehanSurveiController.php */