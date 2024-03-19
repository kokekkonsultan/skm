<?php
defined('BASEPATH') or exit('No direct script access allowed');

class KuadranController extends CI_Controller
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
		$this->data['title'] = "Kuadran";
		$this->data['profiles'] = $this->_get_data_profile($id1, $id2);

		$this->db->select('manage_survey.id AS id_manage_survey, manage_survey.table_identity AS table_identity, manage_survey.id_jenis_pelayanan AS id_jenis_pelayanan');
		$this->db->from('manage_survey');
		$this->db->where('manage_survey.slug', $this->uri->segment(2));
		$manage_survey = $this->db->get()->row();
		$table_identity = $manage_survey->table_identity;

		//SKOR JAWABAN UNSUR
		$this->db->select('*');
		$this->db->from('jawaban_pertanyaan_unsur_' . $table_identity);
		$this->db->join("survey_$table_identity", "jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden");
		$this->db->where("survey_$table_identity.is_submit = 1");
		$this->data['skor'] = $this->db->get();

		//JUDUL PERSEPSI
		$this->db->select("unsur_pelayanan_$table_identity.nomor_unsur AS nomor,
		SUBSTRING(nomor_unsur, 2, 4) AS nomor_harapan, nama_unsur_pelayanan");
		$this->db->from("unsur_pelayanan_$table_identity");
		$this->db->where('id_parent = 0');
		$this->data['persepsi'] = $this->db->get();

		$this->data['jumlah_unsur'] = $this->data['persepsi']->num_rows();
		$this->data['colspan_unsur'] = ($this->data['jumlah_unsur'] + 1);
		// var_dump($this->data['persepsi']->result());

		//NILAI PER UNSUR
		$this->db->select("IF(id_parent = 0,unsur_pelayanan_$table_identity.id, unsur_pelayanan_$table_identity.id_parent) AS id_sub, SUM(skor_jawaban)/COUNT(IF(skor_jawaban != 0, 1, NULL)) AS nilai_per_unsur");
		$this->db->from('jawaban_pertanyaan_unsur_' . $table_identity);
		$this->db->join("pertanyaan_unsur_pelayanan_$table_identity", "jawaban_pertanyaan_unsur_$table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id");
		$this->db->join("unsur_pelayanan_$table_identity", "pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan = unsur_pelayanan_$table_identity.id");
		$this->db->join("survey_$table_identity", "jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden");
		$this->db->where("survey_$table_identity.is_submit = 1");
		$this->db->group_by('id_sub');
		$object_unsur = $this->db->get();
		$this->data['nilai_per_unsur'] = $object_unsur;

		$nilai_unsur = 0;
		foreach ($object_unsur->result() as $values) {
			$nilai_unsur += $values->nilai_per_unsur;
		}

		//NILAI PER HARAPAN
		$this->db->select("SUM(skor_jawaban)/COUNT(IF(skor_jawaban != 0, 1, NULL)) AS nilai_per_unsur");
		$this->db->from("jawaban_pertanyaan_harapan_$table_identity");
		$this->db->join("pertanyaan_unsur_pelayanan_$table_identity", "jawaban_pertanyaan_harapan_$table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id");
		$this->db->join("unsur_pelayanan_$table_identity", "pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan = unsur_pelayanan_$table_identity.id");
		$this->db->join("survey_$table_identity", "jawaban_pertanyaan_harapan_$table_identity.id_responden = survey_$table_identity.id_responden");
		$this->db->where("survey_$table_identity.is_submit = 1");
		$this->db->group_by("IF(id_parent = 0,unsur_pelayanan_$table_identity.id, unsur_pelayanan_$table_identity.id_parent)");
		$object_harapan = $this->db->get();
		$this->data['nilai_per_unsur_harapan'] = $object_harapan;

		$nilai_harapan = 0;
		foreach ($object_harapan->result() as $rows) {
			$nilai_harapan += $rows->nilai_per_unsur;
		}

		// $query =  $this->db->query("SELECT nama_unsur_pelayanan, IF(id_parent = 0,unsur_pelayanan_$table_identity.id, unsur_pelayanan_$table_identity.id_parent) AS id_sub,
		// ROUND((SUM((SELECT SUM(skor_jawaban) FROM jawaban_pertanyaan_unsur_$table_identity JOIN survey_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden WHERE is_submit = 1 && pertanyaan_unsur_pelayanan_$table_identity.id = jawaban_pertanyaan_unsur_$table_identity.id_pertanyaan_unsur))/(SELECT COUNT(survey_$table_identity.id_responden) FROM jawaban_pertanyaan_unsur_$table_identity 
		// 		JOIN survey_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden
		// 		WHERE pertanyaan_unsur_pelayanan_$table_identity.id = jawaban_pertanyaan_unsur_$table_identity.id_pertanyaan_unsur && survey_$table_identity.is_submit = 1)/COUNT(id_parent)),2) AS skor_unsur,

		// 		ROUND((SUM(
		// 		(SELECT SUM(skor_jawaban) FROM jawaban_pertanyaan_harapan_$table_identity JOIN survey_$table_identity ON jawaban_pertanyaan_harapan_$table_identity.id_responden = survey_$table_identity.id_responden WHERE is_submit = 1 && pertanyaan_unsur_pelayanan_$table_identity.id = jawaban_pertanyaan_harapan_$table_identity.id_pertanyaan_unsur))/(SELECT COUNT(survey_$table_identity.id_responden) FROM jawaban_pertanyaan_unsur_$table_identity 
		// 		JOIN survey_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden
		// 		WHERE pertanyaan_unsur_pelayanan_$table_identity.id = jawaban_pertanyaan_unsur_$table_identity.id_pertanyaan_unsur && survey_$table_identity.is_submit = 1)/COUNT(id_parent)), 2) AS skor_harapan,

		// 		IF(is_sub_unsur_pelayanan = 1,SUBSTR(nomor_unsur,1, 3), nomor_unsur) AS nomor

		// 		FROM pertanyaan_unsur_pelayanan_$table_identity
		// 		JOIN unsur_pelayanan_$table_identity ON pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan = unsur_pelayanan_$table_identity.id
		// 		GROUP BY id_sub");

		if ($this->data['skor']->num_rows() > 0) {
			$this->data['skor'] = $this->data['skor'];
			$this->data['total_rata_unsur'] = $nilai_unsur / $this->data['jumlah_unsur'];
			$this->data['total_rata_harapan'] = $nilai_harapan / $this->data['jumlah_unsur'];
			$total_rata_unsur = $this->data['total_rata_unsur'];
			$total_rata_harapan = $this->data['total_rata_harapan'];
		} else {
			$this->data['pesan'] = 'survei belum dimulai atau belum ada responden !';
			return view('not_questions/index', $this->data);
			exit();
		}


		$this->data['grafik'] =  $this->db->query("SELECT *,
		(CASE
			WHEN kup.skor_unsur <= $total_rata_unsur && kup.skor_harapan >= $total_rata_harapan
					THEN 1
			WHEN kup.skor_unsur >= $total_rata_unsur && kup.skor_harapan >= $total_rata_harapan
					THEN 2
				WHEN kup.skor_unsur <= $total_rata_unsur && kup.skor_harapan <= $total_rata_harapan
					THEN 3
				WHEN kup.skor_unsur >= $total_rata_unsur && kup.skor_harapan <= $total_rata_harapan
					THEN 4
			ELSE 0
		END) AS kuadran
		
		FROM (SELECT IF(id_parent = 0,unsur_pelayanan_$table_identity.id, unsur_pelayanan_$table_identity.id_parent) AS id_sub, (SELECT nomor_unsur FROM unsur_pelayanan_$table_identity WHERE id_sub = unsur_pelayanan_$table_identity.id) AS nomor_unsur, (SELECT nama_unsur_pelayanan FROM unsur_pelayanan_$table_identity WHERE id_sub = unsur_pelayanan_$table_identity.id) AS nama_unsur_pelayanan, 
		
		(SUM((SELECT SUM(skor_jawaban) FROM jawaban_pertanyaan_unsur_$table_identity JOIN survey_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden WHERE is_submit = 1 && pertanyaan_unsur_pelayanan_$table_identity.id = jawaban_pertanyaan_unsur_$table_identity.id_pertanyaan_unsur))/(SELECT COUNT(IF(skor_jawaban != 0, 1, NULL)) FROM jawaban_pertanyaan_unsur_$table_identity 
		JOIN survey_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden
		WHERE pertanyaan_unsur_pelayanan_$table_identity.id = jawaban_pertanyaan_unsur_$table_identity.id_pertanyaan_unsur && survey_$table_identity.is_submit = 1)/COUNT(id_parent)) AS skor_unsur,
		
		(SUM((SELECT SUM(skor_jawaban) FROM jawaban_pertanyaan_harapan_$table_identity JOIN survey_$table_identity ON jawaban_pertanyaan_harapan_$table_identity.id_responden = survey_$table_identity.id_responden WHERE is_submit = 1 && pertanyaan_unsur_pelayanan_$table_identity.id = jawaban_pertanyaan_harapan_$table_identity.id_pertanyaan_unsur))/(SELECT COUNT(IF(skor_jawaban != 0, 1, NULL)) FROM jawaban_pertanyaan_unsur_$table_identity 
		JOIN survey_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden
		WHERE pertanyaan_unsur_pelayanan_$table_identity.id = jawaban_pertanyaan_unsur_$table_identity.id_pertanyaan_unsur && survey_$table_identity.is_submit = 1)/COUNT(id_parent)) AS skor_harapan
		
		FROM pertanyaan_unsur_pelayanan_$table_identity
		JOIN unsur_pelayanan_$table_identity ON pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan = unsur_pelayanan_$table_identity.id
		GROUP BY id_sub) AS kup");
		// var_dump($this->data['grafik']->result());

		return view('kuadran/index', $this->data);
	}

	public function convert_kuadran()
	{
		$slug = $this->uri->segment(2);
		$manage_survey = $this->db->get_where('manage_survey', array('slug' => $this->uri->segment(2)))->row();

		$img = $_POST['imgBase64'];
		$img = str_replace('data:image/png;base64,', '', $img);
		$img = str_replace(' ', '+', $img);
		$fileData = base64_decode($img);
		$fileName = 'assets/klien/img_kuadran/kuadran-' . $manage_survey->table_identity . '.png';
		file_put_contents($fileName, $fileData);

		$data = [
			'atribut_kuadran' => serialize(array('kuadran-' . $manage_survey->table_identity . '.png', date("d/m/Y")))
		];
		$this->db->where('slug', $slug);
		$this->db->update('manage_survey', $data);

		// $coba = unserialize($data);
		// var_dump($coba[1]);

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

/* End of file KuadranController.php */
/* Location: ./application/controllers/KuadranController.php */