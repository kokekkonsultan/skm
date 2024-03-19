<?php
defined('BASEPATH') or exit('No direct script access allowed');

class UnsurPelayananController extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->library('ion_auth');

		if (!$this->ion_auth->logged_in()) {
			$this->session->set_flashdata('message_warning', 'You must be logged in to access this page');
			redirect('auth', 'refresh');
		}
		$this->load->model('UnsurPelayanan_model');
		$this->load->library('form_validation');
	}

	public function index()
	{
		$this->data = [];
		$this->data['title'] = "Unsur Pelayanan";

		return view('unsur_pelayanan/index', $this->data);
	}

	public function ajax_list()
	{
		$list = $this->UnsurPelayanan_model->get_datatables();
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = '<b>' . $value->nama_klasifikasi_survei . '</b><br>' . $value->nama_jenis_pelayanan_responden;
			$row[] = '<b>' . $value->nomor_unsur . '. ' . $value->nama_unsur_pelayanan . '</b>';
			$row[] = $value->nomor_unsur_relasi . '. ' . $value->nama_unsur_relasi;
			$row[] = anchor('unsur-pelayanan/edit/' . $value->id_unsur_pelayanan, 'Edit', ['class' => 'btn btn-light-primary btn-sm font-weight-bold shadow']);
			$row[] = '<a class="btn btn-light-primary btn-sm font-weight-bold shadow" href="javascript:void(0)" title="Hapus ' . $value->nama_unsur_pelayanan . '" onclick="delete_data(' . "'" . $value->id_unsur_pelayanan . "'" . ')">Delete</a>';

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

	public function add()
	{
		$this->load->library('uuid');
		$this->data = array();
		$this->data['title'] 		= 'Tambah Unsur Pelayanan';


		$this->data['form_action'] 	= 'unsur-pelayanan/add';


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
				'options' 	=> $this->UnsurPelayanan_model->dropdown_unsur_pelayanan(),
				'selected' 	=> $this->form_validation->set_value('id_parent'),
				'class' 	=> "form-control",
				'style' => "display:none",
			];

			return view('unsur_pelayanan/form_add', $this->data);
		} else {

			$input 	= $this->input->post(NULL, TRUE);

			if ($input['custom'] == 1) {

				$id_parent = $input['id_parent'];

				$this->db->select('nomor_unsur');
				$this->db->from('unsur_pelayanan');
				$this->db->where('id =' . $id_parent);
				$nomor = $this->db->get()->row()->nomor_unsur;

				$this->db->select('(COUNT(id_parent)+1)AS nomor_sub');
				$this->db->from('unsur_pelayanan');
				$this->db->where('id_parent =' . $id_parent);
				$this->db->where('id_jenis_pelayanan =' . $input['id_jenis_pelayanan']);
				$sub = $this->db->get()->row()->nomor_sub;

				$object = [
					'uuid' => $this->uuid->v4(),
					'id_jenis_pelayanan' 	=> $input['id_jenis_pelayanan'],
					'nomor_unsur' => $nomor . '.' . $sub,
					'nama_unsur_pelayanan' 	=> $input['nama_unsur_pelayanan'],
					'is_sub_unsur_pelayanan' => $input['custom'],
					'id_parent' => $id_parent
				];
			} else {
				$this->db->select('(COUNT(nomor_unsur)+1)AS kode_unsur');
				$this->db->from('unsur_pelayanan');
				$this->db->where('id_parent = 0');
				$this->db->where('id_jenis_pelayanan =' . $input['id_jenis_pelayanan']);
				$nomor_unsur = $this->db->get()->row()->kode_unsur;

				$object = [
					'uuid' => $this->uuid->v4(),
					'id_jenis_pelayanan' 	=> $input['id_jenis_pelayanan'],
					'nomor_unsur' => 'U' . $nomor_unsur,
					'nama_unsur_pelayanan' 	=> $input['nama_unsur_pelayanan'],
					'is_sub_unsur_pelayanan' => $input['custom'],
					'id_parent' => '0'
				];
			}
			// var_dump($object);

			$this->db->insert('unsur_pelayanan', $object);

			if ($this->db->affected_rows() > 0) {

				$this->session->set_flashdata('message_success', 'Berhasil menambah data');
				redirect(base_url() . 'unsur-pelayanan', 'refresh');
			} else {

				$this->data['message_data_danger'] = "Gagal menambah data";
				return view('unsur_pelayanan/form_add', $this->data);
			}
		}
	}


	public function delete($id = NULL)
	{
		$search_data = $this->db->get_where('unsur_pelayanan', ['id' => $id]);

		$query = $this->data->query("SELECT pertanyaan_unsur_pelayanan.id AS id_pertanyaan_unsur, id_pertanyaan_terbuka, perincian_pertanyaan_terbuka.id AS id_perincian_pertanyaan_terbuka
		FROM unsur_pelayanan
		JOIN pertanyaan_unsur_pelayanan ON unsur_pelayanan.id = pertanyaan_unsur_pelayanan.id_unsur_pelayanan
		JOIN pertanyaan_terbuka ON unsur_pelayanan.id = pertanyaan_terbuka.id_unsur_pelayanan
		JOIN perincian_pertanyaan_terbuka ON pertanyaan_terbuka.id = perincian_pertanyaan_terbuka.id_pertanyaan_terbuka
		WHERE unsur_pelayanan.id = $id");

		if ($search_data->num_rows() == 0) {

			echo json_encode(array("status" => FALSE));
		}

		$value = $query->row();

		$current = $search_data->row();

		$this->db->where('id_perincian_pertanyaan_terbuka', $value->id_perincian_pertanyaan_terbuka);
		$this->db->delete('isi_pertanyaan_ganda');

		$this->db->where('id_pertanyaan_terbuka', $query->id_pertanyaan_terbuka);
		$this->db->delete('perincian_pertanyaan_terbuka');

		$this->db->where('id_unsur_pelayanan', $id);
		$this->db->delete('pertanyaan_terbuka');

		$this->db->where('id_pertanyaan_unsur', $query->id_pertanyaan_unsur);
		$this->db->delete('nilai_tingkat_kepentingan');

		$this->db->where('id_pertanyaan_unsur', $query->id_pertanyaan_unsur);
		$this->db->delete('kategori_unsur_pelayanan');

		$this->db->where('id_unsur_pelayanan', $id);
		$this->db->delete('pertanyaan_unsur_pelayanan');

		$this->db->where('id', $id);
		$this->db->delete('unsur_pelayanan');

		echo json_encode(array("status" => TRUE));
	}

	public function edit($id = NULL)
	{
		$this->data = array();
		$this->data['title'] = 'Edit Unsur Pelayanan';
		$this->data['form_action'] = 'unsur-pelayanan/edit/' . $id;

		$this->data['search_data'] = $this->db->get_where('unsur_pelayanan', ['id' => $id]);

		if ($this->data['search_data']->num_rows() == 0) {

			$this->session->set_flashdata('message_danger', 'Data tidak ditemukan');
			redirect($this->session->userdata('urlback'), 'refresh');
		}

		$this->data['data'] = $this->data['search_data']->row();

		$this->data['nama_unsur_pelayanan'] = [
			'name' 		=> 'nama_unsur_pelayanan',
			'id'		=> 'nama_unsur_pelayanan',
			'type'		=> 'text',
			'value'		=>	$this->form_validation->set_value('nama_unsur_pelayanan', $this->data['data']->nama_unsur_pelayanan),
			'class'		=> 'form-control',
		];

		$this->data['nomor_unsur'] = [
			'name'         => 'nomor_unsur',
			'id'        => 'nomor_unsur',
			'type'        => 'text',
			'value'        =>    $this->form_validation->set_value('nama_unsur_pelayanan', $this->data['data']->nomor_unsur),
			'class'        => 'form-control',
			'disabled' => 'disabled'
		];

		$this->data['id_jenis_pelayanan'] = [
			'name' 		=> 'id_jenis_pelayanan',
			'id' 		=> 'id_jenis_pelayanan',
			'options' 	=> $this->UnsurPelayanan_model->dropdown_jenis_pelayanan(),
			'selected' 	=> $this->form_validation->set_value('id_jenis_pelayanan', $this->data['data']->id_jenis_pelayanan),
			'class' 	=> "form-control",
		];

		$this->data['id_parent'] = [
			'name' 		=> 'id_parent',
			'id' 		=> 'id_parent',
			'options' 	=> $this->UnsurPelayanan_model->dropdown_unsur_pelayanan(),
			'selected' 	=> $this->form_validation->set_value('id_parent', $this->data['data']->id_parent),
			'class' 	=> "form-control",
			'style' 	=> "display:none",
		];

		$this->form_validation->set_rules('nama_unsur_pelayanan', 'Nama Unsur Pelayanan', 'trim|required');

		if ($this->form_validation->run() == FALSE) {

			return view('unsur_pelayanan/form_edit', $this->data);
		} else {

			$input = $this->input->post(NULL, TRUE);

			if ($input['custom'] == 1) {

				$id_parent = $input['id_parent'];

				$object = [
					'id_jenis_pelayanan' 	=> $input['id_jenis_pelayanan'],
					'nama_unsur_pelayanan' 	=> $input['nama_unsur_pelayanan'],
					'is_sub_unsur_pelayanan' => $input['custom'],
					'id_parent' => $id_parent
				];
			} else {

				$object = [
					'id_jenis_pelayanan' 	=> $input['id_jenis_pelayanan'],
					'nama_unsur_pelayanan' 	=> $input['nama_unsur_pelayanan'],
					'is_sub_unsur_pelayanan' => $input['custom'],
					'id_parent' => '0'
				];
			}

			$this->db->where('id', $id);
			$this->db->update('unsur_pelayanan', $object);

			if ($this->db->affected_rows() > 0) {

				$this->session->set_flashdata('message_success', 'Berhasil mengubah unsur pelayanan');
				redirect(base_url() . 'unsur-pelayanan', 'refresh');
			} else {

				$this->data['message_data_danger'] = "Gagal mengubah unsur pelayanan";
				return view('unsur_pelayanan/form_edit', $this->data);
			}
		}
	}
}

/* End of file UnsurPelayananController.php */
/* Location: ./application/controllers/UnsurPelayananController.php */