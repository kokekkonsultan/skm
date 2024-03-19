<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PerincianPertanyaanTerbukaController extends CI_Controller
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
		$this->load->model('PerincianPertanyaanTerbuka_model');
	}

	public function index()
	{
		$this->data = [];
		$this->data['title'] = "Perincian Pertanyaan Terbuka";

		return view('perincian_pertanyaan_terbuka/index', $this->data);
	}

	public function ajax_list()
	{
		$list = $this->PerincianPertanyaanTerbuka_model->get_datatables();
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = '- ' . $value->nama_klasifikasi_survei . '<br>- ' . $value->nama_jenis_pelayanan_responden . '<br>- ' . $value->nama_unsur_pelayanan . '<br>- <b>' . $value->nama_pertanyaan_terbuka . '</b>';
			$row[] = $value->isi_pertanyaan_terbuka;
			$row[] = anchor('perincian-pertanyaan-terbuka/edit/' . $value->id_perincian_pertanyaan_terbuka, 'Edit', ['class' => 'btn btn-secondary btn-sm font-weight-bold']);
			$row[] = '<a class="btn btn-secondary btn-sm font-weight-bold" href="javascript:void(0)" title="Hapus ' . $value->isi_pertanyaan_terbuka . '" onclick="delete_data(' . "'" . $value->id_perincian_pertanyaan_terbuka . "'" . ')">Delete</a>';

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->PerincianPertanyaanTerbuka_model->count_all(),
			"recordsFiltered" => $this->PerincianPertanyaanTerbuka_model->count_filtered(),
			"data" => $data,
		);

		echo json_encode($output);
	}

	public function add()
	{
		$this->data = array();
		$this->data['title'] 		= 'Tambah Isi Pertanyaan Terbuka';
		$this->data['form_action'] 	= 'perincian-pertanyaan-terbuka/add';

		$this->form_validation->set_rules('id_pertanyaan_terbuka', 'Id Pertanyaan Terbuka', 'trim|required');
		$this->form_validation->set_rules('isi_pertanyaan_terbuka', 'Isi Pertanyaan Terbuka', 'trim|required');

		if ($this->form_validation->run() == FALSE) {

			$this->data['id_pertanyaan_terbuka'] = [
				'name' 		=> 'id_pertanyaan_terbuka',
				'id' 		=> 'id_pertanyaan_terbuka',
				'options' 	=> $this->PerincianPertanyaanTerbuka_model->dropdown_pertanyaan_terbuka(),
				'selected' 	=> $this->form_validation->set_value('id_pertanyaan_terbuka'),
				'class' 	=> "form-control",
			];

			return view('perincian_pertanyaan_terbuka/form_add', $this->data);
		} else {

			$input 	= $this->input->post(NULL, TRUE);

			if ($this->input->post('jenis_jawaban') == '2') {
				$object = [
					'isi_pertanyaan_terbuka' 	=> $input['isi_pertanyaan_terbuka'],
					'id_pertanyaan_terbuka' 	=> $input['id_pertanyaan_terbuka'],
					'id_jenis_pilihan_jawaban' 	=> $input['jenis_jawaban']
				];
				$this->db->insert('perincian_pertanyaan_terbuka', $object);
			} else {
				$object = [
					'isi_pertanyaan_terbuka' 	=> $input['isi_pertanyaan_terbuka'],
					'id_pertanyaan_terbuka' 	=> $input['id_pertanyaan_terbuka'],
					'id_jenis_pilihan_jawaban' 	=> $input['jenis_jawaban']
				];
				$this->db->insert('perincian_pertanyaan_terbuka', $object);

				$id_perincian_pertanyaan_terbuka = $this->db->insert_id();
				$pilihan_jawaban = $input['pilihan_jawaban'];
				$opsi_pilihan_jawaban = $input['opsi_pilihan_jawaban'];
				$skala_nilai = $input['skala_nilai'];
				$data = array();

				$result = array();
				foreach ($_POST['pilihan_jawaban'] as $key => $val) {
					$result[] = array(
						'id_perincian_pertanyaan_terbuka' => $id_perincian_pertanyaan_terbuka,
						'pertanyaan_ganda' => $pilihan_jawaban[$key],
						'dengan_isian_lainnya' => $opsi_pilihan_jawaban,
						'nilai_pertanyaan_ganda' => $skala_nilai[$key]
					);
				}
				$this->db->insert_batch('isi_pertanyaan_ganda', $result);
				// var_dump($result);
			}

			if ($this->db->affected_rows() > 0) {

				$this->session->set_flashdata('message_success', 'Berhasil menambah data');
				redirect(base_url() . 'perincian-pertanyaan-terbuka', 'refresh');
			} else {

				$this->data['message_data_danger'] = "Gagal menambah data";
				return view('perincian-pertanyaan-terbuka/form_add', $this->data);
			}
		}
	}

	public function delete($id = NULL)
	{
		$search_data = $this->db->get_where('perincian_pertanyaan_terbuka', ['id' => $id]);

		if ($search_data->num_rows() == 0) {

			echo json_encode(array("status" => FALSE));
		}

		$current = $search_data->row();

		$this->db->where('id_perincian_pertanyaan_terbuka', $current->id);
		$this->db->delete('isi_pertanyaan_ganda');

		$this->db->where('id', $current->id);
		$this->db->delete('perincian_pertanyaan_terbuka');


		echo json_encode(array("status" => TRUE));
	}

	public function edit($id = NULL)
	{
		$this->data = array();
		$this->data['title'] = 'Edit Isi Pertanyaan Terbuka';
		$this->data['form_action'] = 'perincian-pertanyaan-terbuka/edit/' . $id;

		$search_data = $this->db->get_where('perincian_pertanyaan_terbuka', ['id' => $id]);

		if ($search_data->num_rows() == 0) {

			$this->session->set_flashdata('message_danger', 'Data tidak ditemukan');
			redirect($this->session->userdata('urlback'), 'refresh');
		}

		$this->data['current'] = $search_data->row();

		$this->data['id_pertanyaan_terbuka'] = [
			'name' 		=> 'id_pertanyaan_terbuka',
			'id' 		=> 'id_pertanyaan_terbuka',
			'options' 	=> $this->PerincianPertanyaanTerbuka_model->dropdown_pertanyaan_terbuka(),
			'selected' 	=> $this->form_validation->set_value('id_pertanyaan_terbuka', $this->data['current']->id_pertanyaan_terbuka),
			'class' 	=> "form-control",
		];

		$this->form_validation->set_rules('id_pertanyaan_terbuka', 'Id Pertanyaan Terbuka', 'trim|required');
		$this->form_validation->set_rules('isi_pertanyaan_terbuka', 'Isi Pertanyaan Terbuka', 'trim|required');

		$this->data['pilihan_jawaban'] = $this->PerincianPertanyaanTerbuka_model->get_isi_pertanyaan_ganda($this->data['current']->id);

		if ($this->form_validation->run() == FALSE) {

			return view('perincian_pertanyaan_terbuka/form_edit', $this->data);
		} else {

			$input 	= $this->input->post(NULL, TRUE);

			if ($this->input->post('id_jenis_jawaban') == '2') {
				$object = [
					'id_pertanyaan_terbuka' 	=> $input['id_pertanyaan_terbuka'],
					'isi_pertanyaan_terbuka' 	=> $input['isi_pertanyaan_terbuka']
				];

				$this->db->where('id', $id);
				$this->db->update('perincian_pertanyaan_terbuka', $object);
			} else {
				$object = [
					'id_pertanyaan_terbuka' 	=> $input['id_pertanyaan_terbuka'],
					'isi_pertanyaan_terbuka' 	=> $input['isi_pertanyaan_terbuka']
				];

				$this->db->where('id', $id);
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
				}
				// var_dump($kategori);
				redirect(base_url() . 'perincian-pertanyaan-terbuka', 'refresh');
			}



			if ($this->db->affected_rows() > 0) {

				$this->session->set_flashdata('message_success', 'Berhasil mengubah klasifikasi survei');
				redirect(base_url() . 'perincian-pertanyaan-terbuka', 'refresh');
			} else {

				$this->data['message_data_danger'] = "Gagal mengubah klasifikasi survei";
				return view('perincian-pertanyaan-terbuka/form_edit', $this->data);
			}
		}
	}
}

/* End of file PerincianPertanyaanTerbukaController.php */
/* Location: ./application/controllers/PerincianPertanyaanTerbukaController.php */