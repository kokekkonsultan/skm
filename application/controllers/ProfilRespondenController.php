<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ProfilRespondenController extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->library('ion_auth');

		if (!$this->ion_auth->logged_in()) {
			$this->session->set_flashdata('message_warning', 'You must be logged in to access this page');
			redirect('auth', 'refresh');
		}
	}

	public function index()
	{
		$this->data = [];
		$this->data['title'] = 'Nilai SKM';

		$this->data['table_jenis_kelamin'] = $this->_jenis_kelamin();
		$this->data['table_umur'] = $this->_umur();
		$this->data['table_pendidikan_akhir'] = $this->_pendidikan_akhir();
		$this->data['table_pekerjaan_utama'] = $this->_pekerjaan_utama();
		$this->data['table_pembiayaan'] = $this->_pembiayaan();
		$this->data['table_status_responden'] = $this->_status_responden();
		$this->data['table_jenis_pelayanan'] = $this->_jenis_pelayanan();


		return view('profil_responden/index', $this->data);
	}

	public function _jenis_kelamin()
	{
		$this->load->library('table');

		$template = array(
			'table_open'            => '<table class="table table-bordered table-hover">',
			'table_close'           => '</table>'
		);

		$this->table->set_template($template);

		$this->table->set_heading('NO', 'JENIS KELAMIN');

		$get_data = $this->db->get('jenis_kelamin');

		$no = 1;
		foreach ($get_data->result() as $value) {
			$this->table->add_row(
				$no++,
				$value->jenis_kelamin_responden
			);
		}

		return $this->table->generate();
	}

	public function _umur()
	{
		$this->load->library('table');

		$template = array(
			'table_open'            => '<table class="table table-bordered table-hover">',
			'table_close'           => '</table>'
		);

		$this->table->set_template($template);

		$this->table->set_heading('NO', 'UMUR RESPONDEN');

		$get_data = $this->db->get('umur');

		$no = 1;
		foreach ($get_data->result() as $value) {
			$this->table->add_row(
				$no++,
				$value->umur_responden
			);
		}

		return $this->table->generate();
	}

	public function _pendidikan_akhir()
	{
		$this->load->library('table');

		$template = array(
			'table_open'            => '<table class="table table-bordered table-hover">',
			'table_close'           => '</table>'
		);

		$this->table->set_template($template);

		$this->table->set_heading('NO', 'PENDIDIKAN TERAKHIR');

		$get_data = $this->db->get('pendidikan_terakhir');

		$no = 1;
		foreach ($get_data->result() as $value) {
			$this->table->add_row(
				$no++,
				$value->nama_pendidikan_terakhir_responden
			);
		}

		return $this->table->generate();
	}

	public function _pekerjaan_utama()
	{
		$this->load->library('table');

		$template = array(
			'table_open'            => '<table class="table table-bordered table-hover">',
			'table_close'           => '</table>'
		);

		$this->table->set_template($template);

		$this->table->set_heading('NO', 'PEKERJAAN UTAMA');

		$get_data = $this->db->get('pekerjaan_utama');

		$no = 1;
		foreach ($get_data->result() as $value) {
			$this->table->add_row(
				$no++,
				$value->nama_pekerjaan_utama_responden
			);
		}

		return $this->table->generate();
	}

	public function _pembiayaan()
	{
		$this->load->library('table');

		$template = array(
			'table_open'            => '<table class="table table-bordered table-hover">',
			'table_close'           => '</table>'
		);

		$this->table->set_template($template);

		$this->table->set_heading('NO', 'PEMBIAYAAN');

		$get_data = $this->db->get('pembiayaan');

		$no = 1;
		foreach ($get_data->result() as $value) {
			$this->table->add_row(
				$no++,
				$value->nama_pembiayaan_responden
			);
		}

		return $this->table->generate();
	}

	public function _status_responden()
	{
		$this->load->library('table');

		$template = array(
			'table_open'            => '<table class="table table-bordered table-hover">',
			'table_close'           => '</table>'
		);

		$this->table->set_template($template);

		$this->table->set_heading('NO', 'STATUS');

		$get_data = $this->db->get('status_responden');

		$no = 1;
		foreach ($get_data->result() as $value) {
			$this->table->add_row(
				$no++,
				$value->nama_status_responden
			);
		}

		return $this->table->generate();
	}

	public function _jenis_pelayanan()
	{
		$this->load->library('table');

		$template = array(
			'table_open'            => '<table class="table table-bordered table-hover">',
			'table_close'           => '</table>'
		);

		$this->table->set_template($template);

		$this->table->set_heading('NO', 'STATUS');

		$get_data = $this->db->get('jenis_pelayanan_bkpsdm');

		$no = 1;
		foreach ($get_data->result() as $value) {
			$this->table->add_row(
				$no++,
				$value->nama_pelayanan
			);
		}

		return $this->table->generate();
	}
}

/* End of file ProfilRespondenController.php */
/* Location: ./application/controllers/ProfilRespondenController.php */