<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PertanyaanUnsurSurveiController extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		if (!$this->ion_auth->logged_in()) {
			$this->session->set_flashdata('message_warning', 'You must be an admin to view this page');
			redirect('auth', 'refresh');
		}
		$this->load->model('PertanyaanUnsurSurvei_model');
		$this->load->library('form_validation');
	}

	public function index($id1 = NULL, $id2 = NULL)
	{
		$this->data = [];
		$this->data['title'] = "Pertanyaan Unsur Pelayanan";

		$this->data['profiles'] = $this->_get_data_profile($id1, $id2);
		$this->data['is_question'] = $this->data['profiles']->is_question;
		$table_identity = $this->data['profiles']->table_identity;


		return view('pertanyaan_unsur_survei/index', $this->data);
	}

	public function ajax_list()
	{
		$slug = $this->uri->segment(2);

		$get_identity = $this->db->get_where('manage_survey', array('slug' => "$slug"))->row();
		$table_identity = $get_identity->table_identity;

		$list = $this->PertanyaanUnsurSurvei_model->get_datatables($table_identity);
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {


			if ($get_identity->is_question == 1 && $value->is_pertanyaan_terbuka != '') {
				$is_pertanyaan_terbuka = '<a class="btn btn-dark btn-sm" data-toggle="modal" 
				onclick="showdetailalur(' . $value->id_pertanyaan_unsur . ')"
				href="#modal_detail_alur"><i class="fa fa-random"></i> Alur Pengisian</a>';
			} else {
				$is_pertanyaan_terbuka = '';
			};


			if ($value->pilihan == 2) {
				$jawaban = '<label><input type="radio">&ensp;' . $value->pilihan_1 . '&emsp;</label><label><input type="radio">&ensp;' . $value->pilihan_2 . '&emsp;</label><label><input type="radio">&ensp;' . $value->pilihan_3 . '&emsp;</label><label><input type="radio">&ensp;' . $value->pilihan_4 . '&emsp;</label>';
			} else if ($value->pilihan == 1) {
				$jawaban = '<label><input type="radio">&ensp;' . $value->pilihan_1 . '&emsp;</label><label><input type="radio">&ensp;' . $value->pilihan_4 . '&emsp;</label>';
			} else {
				$jawaban = '<span class="text-danger">Unsur ini memiliki Sub.</span>';
			}

			$cek_unsur = $this->db->get_where("unsur_pelayanan_$table_identity", array('id_parent' => $value->id_unsur));

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = '<b>' . $value->nomor_unsur . '. ' . $value->nama_unsur_pelayanan . '</b>';
			$row[] = $value->isi_pertanyaan_unsur;
			$row[] = $jawaban . '<br>' . $is_pertanyaan_terbuka;

			$row[] = anchor($this->session->userdata('username') . '/' . $this->uri->segment(2) . '/pertanyaan-unsur/edit/' . $value->id_unsur, '<i class="fa fa-edit"></i> Edit', ['class' => 'btn btn-light-primary btn-sm font-weight-bold shadow']);


			if ($get_identity->is_question == 1) {
				if ($cek_unsur->num_rows() == 0) {
					$row[] = '<a class="btn btn-light-primary btn-sm font-weight-bold shadow" href="javascript:void(0)" title="Hapus ' . $value->nama_unsur_pelayanan . '" onclick="delete_data(' . "'" . $value->id_unsur . "'" . ')"><i class="fa fa-trash"></i> Delete</a>';
				} else {
					$row[] = '<a class="btn btn-light-primary btn-sm font-weight-bold shadow" onclick="cek()"><i class="fa fa-trash"></i> Delete</a>';
				}
			}


			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->PertanyaanUnsurSurvei_model->count_all($table_identity),
			"recordsFiltered" => $this->PertanyaanUnsurSurvei_model->count_filtered($table_identity),
			"data" => $data,
		);

		echo json_encode($output);
	}

	public function add($id1, $id2)
	{
		$this->data = [];
		$this->data['title'] = "Tambah Pertanyaan Unsur";
		$this->load->library('uuid');

		$this->data['profiles'] = $this->_get_data_profile($id1, $id2);

		$this->db->select('');
		$this->db->from('manage_survey');
		$this->db->where('manage_survey.slug', $this->uri->segment(2));
		$manage_survey = $this->db->get()->row();
		$table_identity = $manage_survey->table_identity;

		$this->data['pilihan'] = $this->PertanyaanUnsurSurvei_model->tampil_data();

		$this->form_validation->set_rules('nama_unsur_pelayanan', 'Nama Unsur Pelayanan', 'trim|required');

		$this->data['nama_unsur_pelayanan'] = [
			'name' 		=> 'nama_unsur_pelayanan',
			'id'		=> '',
			'type'		=> 'text',
			'value'		=>	$this->form_validation->set_value('nama_unsur_pelayanan'),
			'class'		=> 'form-control',
			'autofocus' => 'autofocus',
			'required' => 'required'
		];

		$this->data['isi_pertanyaan_unsur'] = [
			'name' 		=> 'isi_pertanyaan_unsur',
			'id'		=> 'isi_pertanyaan_unsur',
			'type'		=> 'text',
			'value'		=>	$this->form_validation->set_value('isi_pertanyaan_unsur'),
			'class'		=> 'form-control',
			// 'rows'		 => '3',
			// 'required' => 'required'
		];

		$this->data['pilihan_jawaban'] = [
			'name' 		=> 'pilihan_jawaban[]',
			'id'		=> '',
			'type'		=> 'text',
			'value'		=>	$this->form_validation->set_value('pilihan_jawaban'),
			'class'		=> 'form-control pilihan',
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

		$this->data['jumlah_unsur'] = ($this->db->get_where("unsur_pelayanan_$table_identity", array('id_parent' => 0))->num_rows()) + 1;

		if ($this->form_validation->run() == FALSE) {

			return view('pertanyaan_unsur_survei/add', $this->data);
		} else {
			$input 	= $this->input->post(NULL, TRUE);

			if ($input['is_sub_unsur_pelayanan'] == 2) {

				if ($input['isi_pertanyaan_unsur'] == NULL) {
					$this->session->set_flashdata('message_danger', 'Gagal Menambah Pertanyaan Unsur!');
					redirect(base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2) . '/pertanyaan-unsur', 'refresh');
				}

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
				$this->db->insert('unsur_pelayanan_' . $table_identity, $object);

				$id_unsur = $this->db->insert_id();
				$object_1 = [
					'id_unsur_pelayanan' 	=> $id_unsur,
					'isi_pertanyaan_unsur' 	=> $input['isi_pertanyaan_unsur'],
					'jenis_pilihan_jawaban' 	=> $input['jenis_pilihan_jawaban']
				];
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

				$this->session->set_flashdata('message_success', 'Berhasil Menambah Data!');
				redirect(base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2) . '/pertanyaan-unsur', 'refresh');
			} else {

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
				$this->db->insert('unsur_pelayanan_' . $table_identity, $object);
				$id_unsur = $this->db->insert_id();

				$this->session->set_flashdata('message_success', 'Berhasil Menambah Data!');
				redirect(base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2) . '/pertanyaan-unsur/add-sub/' . $id_unsur, 'refresh');
			};
		}
	}

	public function add_sub($id1, $id2)
	{
		$this->data = [];
		$this->data['title'] = "Tambah Pertanyaan Sub Unsur";
		$this->load->library('uuid');

		$this->data['profiles'] = $this->_get_data_profile($id1, $id2);

		$this->db->select('');
		$this->db->from('manage_survey');
		$this->db->where('manage_survey.slug', $this->uri->segment(2));
		$manage_survey = $this->db->get()->row();
		$table_identity = $manage_survey->table_identity;
		$this->data['table_identity'] = $manage_survey->table_identity;

		$this->data['pilihan'] = $this->PertanyaanUnsurSurvei_model->tampil_data();

		$this->form_validation->set_rules('nama_unsur_pelayanan', 'Nama Unsur Pelayanan', 'trim|required');
		$this->form_validation->set_rules('id_parent', 'Id Parent', 'trim|required');

		$this->data['id_parent'] = [
			'name'         => 'id_parent',
			'id'         => 'id_parent',
			'options'     => $this->PertanyaanUnsurSurvei_model->dropdown_sub_unsur_pelayanan($table_identity),
			'selected'     => $this->form_validation->set_value('id_parents'),
			'class'     => "form-control",
			'required' => 'required',
		];

		$this->data['nama_unsur_pelayanan'] = [
			'name' 		=> 'nama_unsur_pelayanan',
			'id'		=> '',
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
			'class'		=> 'form-control'
		];

		$this->data['pilihan_jawaban'] = [
			'name' 		=> 'pilihan_jawaban[]',
			'id'		=> '',
			'type'		=> 'text',
			'value'		=>	$this->form_validation->set_value('pilihan_jawaban'),
			'class'		=> 'form-control pilihan',
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

		if ($this->form_validation->run() == FALSE) {

			return view('pertanyaan_unsur_survei/add_sub', $this->data);
		} else {
			$input 	= $this->input->post(NULL, TRUE);

			if ($input['isi_pertanyaan_unsur'] == NULL) {
				$this->session->set_flashdata('message_danger', 'Gagal Menambah Pertanyaan Unsur!');
				redirect(base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2) . '/pertanyaan-unsur', 'refresh');
			}

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
			$this->db->insert('unsur_pelayanan_' . $table_identity, $object);

			$id_unsur = $this->db->insert_id();
			$object_1 = [
				'id_unsur_pelayanan' 	=> $id_unsur,
				'isi_pertanyaan_unsur' 	=> $input['isi_pertanyaan_unsur'],
				'jenis_pilihan_jawaban' 	=> $input['jenis_pilihan_jawaban']
			];
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


			if ($input['is_submit'] == 1) {
				$this->session->set_flashdata('message_success', 'Berhasil Menambah Data!');
				redirect(base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2) . '/pertanyaan-unsur', 'refresh');
			} else {
				$this->session->set_flashdata('message_success', 'Berhasil Menambah Data!');
				redirect(base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2) . '/pertanyaan-unsur/add-sub/' . $id_parent, 'refresh');
			}
		}
	}

	public function edit($id1, $id2)
	{
		$this->data = [];
		$this->data['title'] = "Edit Pertanyaan Unsur";
		$this->load->library('uuid');

		$this->data['profiles'] = $this->_get_data_profile($id1, $id2);

		$this->db->select('');
		$this->db->from('manage_survey');
		$this->db->where('manage_survey.slug', $this->uri->segment(2));
		$table_identity_manage_survey = $this->db->get()->row()->table_identity;

		$this->db->select("*, pertanyaan_unsur_pelayanan_$table_identity_manage_survey.id AS id_pertanyaan_unsur, if(id_unsur_pelayanan != '', 1, 2) AS unsur_turunan");
		$this->db->from("unsur_pelayanan_$table_identity_manage_survey");
		$this->db->join("pertanyaan_unsur_pelayanan_$table_identity_manage_survey", "pertanyaan_unsur_pelayanan_$table_identity_manage_survey.id_unsur_pelayanan = unsur_pelayanan_$table_identity_manage_survey.id", 'left');
		$this->db->where("unsur_pelayanan_$table_identity_manage_survey.id =", $this->uri->segment(5));
		$pertanyaan_unsur = $this->db->get()->row();
		$this->data['nomor_unsur'] = $pertanyaan_unsur->nomor_unsur;
		$this->data['unsur_turunan'] = $pertanyaan_unsur->unsur_turunan;

		$this->db->select('*');
		$this->db->from('kategori_unsur_pelayanan_' . $table_identity_manage_survey);
		$this->db->where('id_pertanyaan_unsur', $pertanyaan_unsur->id_pertanyaan_unsur);
		$this->data['nama_kategori_unsur'] = $this->db->get()->result();

		$this->form_validation->set_rules('nama_unsur_pelayanan', 'Nama Unsur Pelayanan', 'trim|required');

		if ($this->form_validation->run() == FALSE) {

			$this->data['nama_unsur_pelayanan'] = [
				'name' 		=> 'nama_unsur_pelayanan',
				'id'		=> 'nama_unsur_pelayanan',
				'type'		=> 'text',
				'class'		=> 'form-control',
				'value'		=>	$this->form_validation->set_value('nama_unsur_pelayanan', $pertanyaan_unsur->nama_unsur_pelayanan),
				'autofocus' => 'autofocus'
			];

			$this->data['isi_pertanyaan_unsur'] = [
				'name' 		=> 'isi_pertanyaan_unsur',
				'id'		=> 'isi_pertanyaan_unsur',
				'type'		=> 'text',
				'value'		=>	$this->form_validation->set_value('isi_pertanyaan_unsur', $pertanyaan_unsur->isi_pertanyaan_unsur),
				'class'		=> 'form-control',
				// 'rows'		 => '3',
				// 'autofocus' => 'autofocus',
				// 'required' => 'required'
			];

			return view('pertanyaan_unsur_survei/edit', $this->data);
		} else {
			$input = $this->input->post(NULL, TRUE);

			$object = [
				'nama_unsur_pelayanan' 	=> $input['nama_unsur_pelayanan']
			];
			$this->db->where('id', $this->uri->segment(5));
			$this->db->update('unsur_pelayanan_' . $table_identity_manage_survey, $object);

			if ($pertanyaan_unsur->unsur_turunan == 1) {

				if ($input['isi_pertanyaan_unsur'] == NULL) {
					$this->session->set_flashdata('message_danger', 'Gagal Menambah Pertanyaan Unsur!');
					redirect(base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2) . '/pertanyaan-unsur/edit/' . $this->uri->segment(5), 'refresh');
				}

				$object_1 = [
					'isi_pertanyaan_unsur' 	=> $input['isi_pertanyaan_unsur']
				];
				$this->db->where('id', $pertanyaan_unsur->id_pertanyaan_unsur);
				$this->db->update('pertanyaan_unsur_pelayanan_' . $table_identity_manage_survey, $object_1);


				$id = $input['id_kategori'];
				$nama_kategori_input = $input['nama_kategori_unsur_pelayanan'];
				for ($i = 0; $i < sizeof($id); $i++) {
					$kategori = array(
						'id' => $id[$i],
						'nama_kategori_unsur_pelayanan' => $nama_kategori_input[$i]
					);
					$this->db->where('id', $id[$i]);
					$this->db->update('kategori_unsur_pelayanan_' . $table_identity_manage_survey, $kategori);
				}
			}

			$this->session->set_flashdata('message_success', 'Berhasil mengubah data');
			redirect(base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2) . '/pertanyaan-unsur', 'refresh');
		}
	}

	public function delete()
	{
		$table_identity_manage_survey = $this->db->get_where('manage_survey', array('slug' => $this->uri->segment(2)))->row()->table_identity;

		$this->db->select("*, pertanyaan_unsur_pelayanan_$table_identity_manage_survey.id AS id_pertanyaan_unsur, if(id_unsur_pelayanan != '', 1, 2) AS unsur_turunan");
		$this->db->from("unsur_pelayanan_$table_identity_manage_survey");
		$this->db->join("pertanyaan_unsur_pelayanan_$table_identity_manage_survey", "pertanyaan_unsur_pelayanan_$table_identity_manage_survey.id_unsur_pelayanan = unsur_pelayanan_$table_identity_manage_survey.id", 'left');
		$this->db->where("unsur_pelayanan_$table_identity_manage_survey.id =", $this->uri->segment(5));
		$pertanyaan_unsur = $this->db->get()->row();
		// var_dump($pertanyaan_unsur);

		if ($pertanyaan_unsur->unsur_turunan == 1) {

			$cek_id_terbuka = $this->db->get_where('pertanyaan_terbuka_' . $table_identity_manage_survey, array('id_unsur_pelayanan' => $this->uri->segment(5)));
			if ($cek_id_terbuka->num_rows() > 0) {

				foreach ($cek_id_terbuka->result() as $row) {
					$this->db->where('id_pertanyaan_terbuka', $row->id);
					$this->db->delete('perincian_pertanyaan_terbuka_' . $table_identity_manage_survey);
				}
				$this->db->where('id_unsur_pelayanan', $this->uri->segment(5));
				$this->db->delete('pertanyaan_terbuka_' . $table_identity_manage_survey);
			}

			$this->db->where('id_pertanyaan_unsur', $pertanyaan_unsur->id_pertanyaan_unsur);
			$this->db->delete('kategori_unsur_pelayanan_' . $table_identity_manage_survey);

			$this->db->where('id', $pertanyaan_unsur->id_pertanyaan_unsur);
			$this->db->delete('pertanyaan_unsur_pelayanan_' . $table_identity_manage_survey);
		}
		$this->db->where('id', $this->uri->segment(5));
		$this->db->delete('unsur_pelayanan_' . $table_identity_manage_survey);

		echo json_encode(array("status" => TRUE));
	}


	public function detail_alur()
	{
		$this->data = [];
		$this->data['title'] = "Detail Alur";

		$id_pertanyaan_unsur = $this->uri->segment(5);

		$this->data['manage_survey'] = $this->db->get_where('manage_survey', array('slug' => $this->uri->segment(2)))->row();
		$table_identity = $this->data['manage_survey']->table_identity;

		$this->data['kategori_unsur'] = $this->db->get_where("kategori_unsur_pelayanan_$table_identity", array('id_pertanyaan_unsur' => $id_pertanyaan_unsur));


		return view('pertanyaan_unsur_survei/detail_alur', $this->data);
	}


	public function update_detail_alur()
	{
		$manage_survey = $this->db->get_where('manage_survey', array('slug' => $this->uri->segment(2)))->row();
		$table_identity = $manage_survey->table_identity;


		$input = $this->input->post(NULL, TRUE);
		$id = $input['id_kategori'];
		$is_next_step = $input['is_next_step'];
		for ($i = 0; $i < sizeof($id); $i++) {
			$kategori = array(
				'id' => $id[$i],
				'is_next_step' => $is_next_step[$i]
			);
			$this->db->where('id', $id[$i]);
			$this->db->update('kategori_unsur_pelayanan_' . $table_identity, $kategori);
		}

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

	public function cari()
	{
		$id = $_GET['id'];
		$cari = $this->PertanyaanUnsurPelayanan_model->cari($id)->result();
		echo json_encode($cari);
	}
}

/* End of file PertanyaanUnsurSurveiController.php */
/* Location: ./application/controllers/PertanyaanUnsurSurveiController.php */