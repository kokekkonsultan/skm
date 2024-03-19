<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PertanyaanTerbukaController extends CI_Controller
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
		$this->load->model('PertanyaanTerbuka_model');
	}

	public function index()
	{
		$this->data = [];
		$this->data['title'] = "Pertanyaan Tambahan";

		return view('pertanyaan_terbuka/index', $this->data);
	}

	public function ajax_list()
	{

		$list = $this->PertanyaanTerbuka_model->get_datatables();
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = '- ' . $value->nama_klasifikasi_survei . '<br>- ' . $value->nama_jenis_pelayanan_responden . '<br>- <b>' . $value->nomor_unsur . '. ' . $value->nama_unsur_pelayanan;
			$row[] = $value->nomor_pertanyaan_terbuka . '. ' . $value->nama_pertanyaan_terbuka . '</br>' . $value->isi_pertanyaan_terbuka;
			$row[] = anchor('pertanyaan-terbuka/edit/' . $value->id_pertanyaan_terbuka, 'Edit', ['class' => 'btn btn-light-primary shadow btn-sm font-weight-bold']);
			$row[] = '<a class="btn btn-light-primary shadow btn-sm font-weight-bold" href="javascript:void(0)" title="Hapus ' . $value->nama_pertanyaan_terbuka . '" onclick="delete_data(' . "'" . $value->id_pertanyaan_terbuka . "'" . ')">Delete</a>';

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->PertanyaanTerbuka_model->count_all(),
			"recordsFiltered" => $this->PertanyaanTerbuka_model->count_filtered(),
			"data" => $data,
		);

		echo json_encode($output);
	}

	public function add()
	{
		$this->load->model('PertanyaanTerbuka_model');

		$this->data = array();
		$this->data['title'] 		= 'Tambah Pertanyaan Tambahan';
		$this->data['form_action'] 	= 'pertanyaan-terbuka/add';

		$this->form_validation->set_rules('nama_pertanyaan_terbuka', 'Nama Pertanyaan Terbuka', 'trim|required');

		if ($this->form_validation->run() == FALSE) {

			$this->data['nama_pertanyaan_terbuka'] = [
				'name' 		=> 'nama_pertanyaan_terbuka',
				'id'		=> 'nama_pertanyaan_terbuka',
				'type'		=> 'text',
				'value'		=>	$this->form_validation->set_value('nama_pertanyaan_terbuka'),
				'class'		=> 'form-control',
			];

			$this->data['id_jenis_pelayanan'] = [
				'name' 		=> 'id_jenis_pelayanan',
				'id' 		=> 'id_jenis_pelayanan',
				'options' 	=> $this->PertanyaanTerbuka_model->dropdown_jenis_pelayanan(),
				'selected' 	=> $this->form_validation->set_value('id_jenis_pelayanan'),
				'class' 	=> "form-control",
			];

			$this->data['id_unsur_pelayanan'] = [
				'name' 		=> 'id_unsur_pelayanan',
				'id' 		=> 'id_unsur_pelayanan',
				'options' 	=> $this->PertanyaanTerbuka_model->dropdown_unsur_pelayanan(),
				'selected' 	=> $this->form_validation->set_value('id_unsur_pelayanan'),
				'class' 	=> "form-control",
			];

			return view('pertanyaan_terbuka/form_add', $this->data);
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
				redirect(base_url() . 'pertanyaan-terbuka', 'refresh');
			} else {

				$this->data['message_data_danger'] = "Gagal menambah data";
				return view('pertanyaan-terbuka/form_add', $this->data);
			}
		}
	}

	public function delete($id = NULL)
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

	public function edit($id = NULL)
	{
		$this->data = array();
		$this->data['title'] = 'Edit Pertanyaan Tambahan';
		$this->data['form_action'] = 'pertanyaan-terbuka/edit/' . $id;

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

		$this->data['pilihan_jawaban'] = $this->PertanyaanTerbuka_model->get_isi_pertanyaan_ganda($this->data['perincian_pertanyaan_terbuka']->id);


		$this->form_validation->set_rules('nama_pertanyaan_terbuka', 'Nama Pertanyaan Terbuka', 'trim|required');
		$this->form_validation->set_rules('isi_pertanyaan_terbuka', 'Isi Pertanyaan Terbuka', 'trim|required');

		if ($this->form_validation->run() == FALSE) {

			$this->data['nomor_pertanyaan_terbuka'] = [
				'name' 		=> 'nomor_pertanyaan_terbuka',
				'id'		=> 'nomor_pertanyaan_terbuka',
				'type'		=> 'text',
				'value'		=>	$this->form_validation->set_value('nomor_pertanyaan_terbuka', $this->data['current']->nomor_pertanyaan_terbuka),
				'class'		=> 'form-control',
				'disabled' => 'disabled'
			];

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

			return view('pertanyaan_terbuka/form_edit', $this->data);
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
				$this->session->set_flashdata('message_success', 'Berhasil mengubah Pertanyaan Terbuka');
				redirect(base_url() . 'pertanyaan-terbuka', 'refresh');
			}
			// var_dump($object);
			if ($this->db->affected_rows() > 0) {
				$this->session->set_flashdata('message_success', 'Berhasil mengubah Pertanyaan Terbuka');
				redirect(base_url() . 'pertanyaan-terbuka', 'refresh');
			} else {

				$this->data['message_data_danger'] = "Gagal mengubah Pertanyaan Terbuka";
				return view('pertanyaan-terbuka/form_edit', $this->data);
			}
		}
	}
}

/* End of file PertanyaanTerbukaController.php */
/* Location: ./application/controllers/PertanyaanTerbukaController.php */