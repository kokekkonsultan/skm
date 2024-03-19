<?php
defined('BASEPATH') or exit('No direct script access allowed');
class PertanyaanTerbukaSurveiController extends CI_Controller

{
	public function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in()) {
			$this->session->set_flashdata('message_warning', 'You must be an admin to view this page');
			redirect('auth', 'refresh');
		}
		$this->load->library('form_validation');
		$this->load->model('PertanyaanTerbukaSurvei_model');
	}


	public function index($id1 = NULL, $id2 = NULL)
	{
		$this->data = [];
		$this->data['title'] = "Pertanyaan Tambahan";
		$this->data['profiles'] = $this->_get_data_profile($id1, $id2);

		$this->db->select('');
		$this->db->from('manage_survey');
		$this->db->where('manage_survey.slug', $this->uri->segment(2));
		$manage_survey = $this->db->get()->row();

		$this->data['is_question'] = $manage_survey->is_question;

		$table_identity = $manage_survey->table_identity;
		$this->db->select("*, pertanyaan_terbuka_$table_identity.id AS id_terbuka, (SELECT nomor_unsur FROM unsur_pelayanan_$table_identity WHERE unsur_pelayanan_$table_identity.id = pertanyaan_terbuka_$table_identity.id_unsur_pelayanan) AS nomor_unsur, (SELECT nama_unsur_pelayanan FROM unsur_pelayanan_$table_identity WHERE unsur_pelayanan_$table_identity.id = pertanyaan_terbuka_$table_identity.id_unsur_pelayanan) AS nama_unsur_pelayanan, (SELECT nama_unsur_pelayanan FROM unsur_pelayanan_$table_identity WHERE unsur_pelayanan_$table_identity.id = pertanyaan_terbuka_$table_identity.id_unsur_pelayanan) AS nama_unsur_pelayanan, IF(is_letak_pertanyaan = 1, 'Paling Atas', 'Paling Bawah') AS letak_pertanyaan, perincian_pertanyaan_terbuka_$table_identity.id AS id_perincian_pertanyaan_terbuka, (SELECT DISTINCT(dengan_isian_lainnya) FROM isi_pertanyaan_ganda_$table_identity WHERE isi_pertanyaan_ganda_$table_identity.id_perincian_pertanyaan_terbuka = perincian_pertanyaan_terbuka_$table_identity.id) AS dengan_isian_lainnya");
        $this->db->from('pertanyaan_terbuka_' . $table_identity);
        $this->db->join("perincian_pertanyaan_terbuka_$table_identity", "pertanyaan_terbuka_$table_identity.id = perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka");
		$this->data['pertanyaan_tambahan'] = $this->db->get();
		$this->data['pertanyaan_tambahan_total'] = $this->data['pertanyaan_tambahan']->num_rows();



		// foreach($this->db->get('manage_survey')->result() as $row){

		// 	foreach($this->db->query("SELECT * FROM perincian_pertanyaan_terbuka_$row->table_identity
		// 	WHERE (SELECT DISTINCT dengan_isian_lainnya FROM isi_pertanyaan_ganda_$row->table_identity WHERE id_perincian_pertanyaan_terbuka = perincian_pertanyaan_terbuka_$row->table_identity.id GROUP BY id_perincian_pertanyaan_terbuka) = 1")->result() as $value){

		// 		$this->db->query("INSERT INTO isi_pertanyaan_ganda_$row->table_identity (id_perincian_pertanyaan_terbuka, pertanyaan_ganda, dengan_isian_lainnya) VALUES ($value->id, 'Lainnya', 1);");

		// 	}
		// }

		return view('pertanyaan_terbuka_survei/index', $this->data);
	}


	public function ajax_list()
	{
		$slug = $this->uri->segment(2);
		$get_identity = $this->db->get_where('manage_survey', array('slug' => "$slug"))->row();
		$table_identity = $get_identity->table_identity;


		$list = $this->PertanyaanTerbukaSurvei_model->get_datatables($table_identity);
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {
			if ($value->nomor_unsur == '') {
				$letak_pertanyaan = '<b>' . $value->letak_pertanyaan . '</b>';
			} else {
				$letak_pertanyaan = 'Dibawah Unsur <br><b>' . $value->nomor_unsur . '. ' . $value->nama_unsur_pelayanan . '</b>';
			}	

			$no++;
			$row = array();

			$edit_pertanyaan = '<button type="button" class="btn btn-link" data-toggle="modal" data-target="#exampleModal'.$no.'" title="Ubah nomor tambahan dan nama tambahan">
			<i class="flaticon-edit-1"></i>
		</button>';

			$row[] = $no;
			$row[] = '<b>' . $value->nomor_pertanyaan_terbuka . '. ' . $value->nama_pertanyaan_terbuka . '</b>' .$edit_pertanyaan . '<br>' . $value->isi_pertanyaan_terbuka;

			if ($value->id_jenis_pilihan_jawaban == 1) {
				$pilihan = [];
				foreach ($this->db->get_where("isi_pertanyaan_ganda_$table_identity", array('id_perincian_pertanyaan_terbuka' => $value->id_perincian_pertanyaan_terbuka))->result() as $get) {
					$pilihan[] = '<label><input type="radio">&ensp;' . $get->pertanyaan_ganda . '&emsp;</label>';
				};

				$row[] = implode("<br>", $pilihan);

			} else {
				$row[] = '';
			}

			$row[] = $letak_pertanyaan;

			$row[] = anchor($this->session->userdata('username') . '/' . $this->uri->segment(2) . '/pertanyaan-terbuka/edit/' . $value->id_pertanyaan_terbuka, '<i class="fa fa-edit"></i> Edit', ['class' => 'btn btn-light-primary btn-sm font-weight-bold shadow']);

			if ($get_identity->is_question == 1) {

				$row[] = '<a class="btn btn-light-primary btn-sm font-weight-bold shadow" href="javascript:void(0)" title="Hapus ' . $value->nama_pertanyaan_terbuka . '" onclick="delete_pertanyaan_terbuka(' . "'" . $value->id_pertanyaan_terbuka . "'" . ')"><i class="fa fa-trash"></i> Delete</a>';
			}
			$data[] = $row;
		}
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->PertanyaanTerbukaSurvei_model->count_all($table_identity),
			"recordsFiltered" => $this->PertanyaanTerbukaSurvei_model->count_filtered($table_identity),
			"data" => $data,
		);
		echo json_encode($output);
	}


	public function add($id1 = NULL, $id2 = NULL)
	{
		$this->data = [];
		$this->data['title'] = "Tambah Pertanyaan Tambahan";
		$this->data['profiles'] = $this->_get_data_profile($id1, $id2);

		$this->db->select('*');
		$this->db->from('manage_survey');
		$this->db->where('manage_survey.slug', $this->uri->segment(2));
		$table_identity = $this->db->get()->row()->table_identity;

		$this->db->select('(COUNT(nomor_pertanyaan_terbuka)+1) AS nomor_terbuka');
		$this->db->from('pertanyaan_terbuka_' . $table_identity);
		$nomor_pertanyaan_terbuka = $this->db->get()->row()->nomor_terbuka;

		$this->form_validation->set_rules('nama_pertanyaan_terbuka', 'Nama Pertanyaan Terbuka', 'trim|required');
		$this->form_validation->set_rules('isi_pertanyaan_terbuka', 'Isi Pertanyaan Terbuka', 'trim|required');

		$this->data['id_unsur_pelayanan'] = [
			'name' 		=> 'id_unsur_pelayanan',
			'id' 		=> 'id_unsur_pelayanan',
			'options' 	=> $this->PertanyaanTerbukaSurvei_model->dropdown_unsur_pelayanan(),
			'selected' 	=> $this->form_validation->set_value('id_unsur_pelayanan'),
			'class' 	=> "form-control",
			// 'autofocus' => 'autofocus',
			'required' => 'required'
		];


		$this->data['nama_pertanyaan_terbuka'] = [
			'name' 		=> 'nama_pertanyaan_terbuka',
			'id'		=> 'nama_pertanyaan_terbuka',
			'type'		=> 'text',
			'value'		=>	$this->form_validation->set_value('nama_pertanyaan_terbuka'),
			'class'		=> 'form-control',
			'required' => 'required'
		];


		$this->data['isi_pertanyaan_terbuka'] = [
			'name' 		=> 'isi_pertanyaan_terbuka',
			'id'		=> 'isi_pertanyaan_terbuka',
			'type'		=> 'text',
			'value'		=>	$this->form_validation->set_value('isi_pertanyaann_terbuka'),
			'class'		=> 'form-control',
			'rows' 		=> '3'
		];

		$this->data['jumlah_tambahan'] = ($this->db->get("pertanyaan_terbuka_$table_identity")->num_rows()) + 1;

		if ($this->form_validation->run() == FALSE) {
			return view('pertanyaan_terbuka_survei/add', $this->data);
		} else {

			//Pertanyaan sudah di modifikasi
		$this->db->update('manage_survey', array('id_jenis_pelayanan' => null), array('table_identity' => "$table_identity"));

		
			$input 	= $this->input->post(NULL, TRUE);

			// if ($input['is_required'] == 1) {
			// 	$is_required = 1;
			// } else {
			// 	$is_required = NULL;
			// }

			if ($this->uri->segment(5) == 1) {

				$id_unsur_pelayanan = $input['id_unsur_pelayanan'];
				$cek_parent = $this->db->query("SELECT * FROM unsur_pelayanan_$table_identity WHERE id_parent = $id_unsur_pelayanan ORDER BY id ASC");

				if ($cek_parent->num_rows() > 0) {
					$id_unsur_parent = $cek_parent->last_row()->id;
				} else {
					$id_unsur_parent = $input['id_unsur_pelayanan'];
				}

				$data = [
					'id_unsur_pelayanan' 	=> $id_unsur_parent,
					'nomor_pertanyaan_terbuka' 	=> 'T' . $nomor_pertanyaan_terbuka,
					'nama_pertanyaan_terbuka' 	=> $input['nama_pertanyaan_terbuka'],
					'is_required' =>  $input['is_required']
				];
			} else {
				$data = [
					'is_letak_pertanyaan' 	=> $input['is_letak_pertanyaan_tambahan'],
					'nomor_pertanyaan_terbuka' 	=> 'T' . $nomor_pertanyaan_terbuka,
					'nama_pertanyaan_terbuka' 	=> $input['nama_pertanyaan_terbuka'],
					'is_required' => $input['is_required']
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


				//INSERT JAWABAN LAINNYA
				if ($opsi_pilihan_jawaban == 1) {
					$this->db->query("INSERT INTO isi_pertanyaan_ganda_$table_identity (id_perincian_pertanyaan_terbuka, pertanyaan_ganda, dengan_isian_lainnya)
					VALUES ($id_perincian_pertanyaan_terbuka, 'Lainnya', 1)");
				}

				//DELETE PILIHAN JAWABAN YANG KOSONG
				$this->db->query("DELETE FROM isi_pertanyaan_ganda_$table_identity WHERE id_perincian_pertanyaan_terbuka = $id_perincian_pertanyaan_terbuka && pertanyaan_ganda = ''");
			}

			if ($this->db->affected_rows() > 0) {
				$this->session->set_flashdata('message_success', 'Berhasil menambah data');
				redirect(base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2) . '/pertanyaan-terbuka', 'refresh');
			} else {
				$this->data['message_data_danger'] = "Gagal menambah data";
				return view('pertanyaan_terbuka_survei/add', $this->data);
			}
		}
	}


	public function edit($id1 = NULL, $id2 = NULL)
	{
		$this->data = [];
		$this->data['title'] = "Edit Pertanyaan Tambahan";
		$this->data['profiles'] = $this->_get_data_profile($id1, $id2);

		$this->db->select('*');
		$this->db->from('manage_survey');
		$this->db->where('manage_survey.slug', $this->uri->segment(2));
		$table_identity = $this->db->get()->row()->table_identity;

		$this->db->select("*, pertanyaan_terbuka_$table_identity.id AS id_pertanyaan_terbuka");
		$this->db->from('pertanyaan_terbuka_' . $table_identity);
		$this->db->join("perincian_pertanyaan_terbuka_$table_identity", "pertanyaan_terbuka_$table_identity.id = perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka");
		$this->db->where("pertanyaan_terbuka_$table_identity.id =", $this->uri->segment(5));
		$this->data['search_data'] = $this->db->get();


		if ($this->data['search_data']->num_rows() == 0) {
			$this->session->set_flashdata('message_danger', 'Data tidak ditemukan');
			redirect($this->session->userdata('urlback'), 'refresh');
		}
		$this->data['current'] = $this->data['search_data']->row();

		$id_pertanyaan_terbuka = $this->uri->segment(5);
		$query = $this->db->query("SELECT * , isi_pertanyaan_ganda_$table_identity.id AS id_isi_pertanyaan_ganda
		FROM isi_pertanyaan_ganda_$table_identity
		JOIN perincian_pertanyaan_terbuka_$table_identity ON isi_pertanyaan_ganda_$table_identity.id_perincian_pertanyaan_terbuka = perincian_pertanyaan_terbuka_$table_identity.id
		WHERE id_pertanyaan_terbuka = '$id_pertanyaan_terbuka'");
		$this->data['pilihan_jawaban'] = $query->result();


		$this->form_validation->set_rules('nama_pertanyaan_terbuka', 'Nama Pertanyaan Terbuka', 'trim|required');
		$this->form_validation->set_rules('isi_pertanyaan_terbuka', 'Isi Pertanyaan Terbuka', 'trim|required');

		$this->data['nama_pertanyaan_terbuka'] = [
			'name' 		=> 'nama_pertanyaan_terbuka',
			'id'		=> 'nama_pertanyaan_terbuka',
			'type'		=> 'text',
			'value'		=>	$this->form_validation->set_value('nama_pertanyaan_terbuka', $this->data['current']->nama_pertanyaan_terbuka),
			'class'		=> 'form-control',
			'autofocus' => 'autofocus',
			'required' => 'required'
		];

		$this->data['isi_pertanyaan_terbuka'] = [
			'name' 		=> 'isi_pertanyaan_terbuka',
			'id'		=> 'isi_pertanyaan_terbuka',
			'type'		=> 'text',
			'value'		=>	$this->form_validation->set_value('isi_pertanyaann_terbuka', $this->data['current']->isi_pertanyaan_terbuka),
			'class'		=> 'form-control',
			'rows' 		=> '3',
			'required' => 'required'
		];


		if ($this->form_validation->run() == FALSE) {

			return view('pertanyaan_terbuka_survei/edit', $this->data);
		} else {

			$input 	= $this->input->post(NULL, TRUE);
			// if ($input['is_required'] == 1) {
			// 	$is_required = 1;
			// } else {
			// 	$is_required = NULL;
			// }

			$data = [
				'nama_pertanyaan_terbuka' 	=> $input['nama_pertanyaan_terbuka'],
				'is_required' =>  $input['is_required']
			];
			$this->db->where('id', $id_pertanyaan_terbuka);
			$this->db->update('pertanyaan_terbuka_' . $table_identity, $data);

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
			}
			$this->session->set_flashdata('message_success', 'Berhasil mengubah data');
			redirect(base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2) . '/pertanyaan-terbuka', 'refresh');
		}
	}



	public function delete($id = NULL)
	{
		$this->db->select('');
		$this->db->from('manage_survey');
		$this->db->where('manage_survey.slug', $this->uri->segment(2));
		$table_identity = $this->db->get()->row()->table_identity;

		//Pertanyaan sudah di modifikasi
		$this->db->update('manage_survey', array('id_jenis_pelayanan' => null), array('table_identity' => "$table_identity"));

		$id_pertanyaan_terbuka = $this->uri->segment('5');

		$query = $this->db->query("SELECT *, perincian_pertanyaan_terbuka_$table_identity.id AS id_perincian_pertanyaan_terbuka
		FROM pertanyaan_terbuka_$table_identity
		JOIN perincian_pertanyaan_terbuka_$table_identity ON pertanyaan_terbuka_$table_identity.id = perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka
		WHERE id_pertanyaan_terbuka = $id_pertanyaan_terbuka
		");
		$current = $query->row();


		$this->db->where('id_perincian_pertanyaan_terbuka', $current->id_perincian_pertanyaan_terbuka);
		$this->db->delete('isi_pertanyaan_ganda_' . $table_identity);
		$this->db->where('id', $current->id_pertanyaan_terbuka);
		$this->db->delete('perincian_pertanyaan_terbuka_' . $table_identity);
		$this->db->where('id', $current->id_pertanyaan_terbuka);
		$this->db->delete('pertanyaan_terbuka_' . $table_identity);

		echo json_encode(array("status" => TRUE));
	}


	public function edit_terbuka()
	{
		$this->db->select('*');
		$this->db->from('manage_survey');
		$this->db->where('manage_survey.slug', $this->uri->segment(2));
		$manage_survey = $this->db->get()->row();

		$input 	= $this->input->post(NULL, TRUE);
		$object = [
			'nomor_pertanyaan_terbuka' 	=> $input['nomor_pertanyaan_terbuka'],
			'nama_pertanyaan_terbuka' 	=> $input['nama_pertanyaan_terbuka']
		];

		$this->db->where('id', $input['id']);
		$this->db->update('pertanyaan_terbuka_' . $manage_survey->table_identity, $object);

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



/* End of file PertanyaanTerbukaSurveiController.php */
/* Location: ./application/controllers/PertanyaanTerbukaSurveiController.php */