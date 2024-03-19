<?php
defined('BASEPATH') or exit('No direct script access allowed');

class RekapHasilPerBagianController extends Client_Controller
{

	public function __construct()
	{
		parent::__construct();

		if (!$this->ion_auth->logged_in()) {
			$this->session->set_flashdata('message_warning', 'You must be an admin to view this page');
			redirect('auth', 'refresh');
		}
		$this->load->model('DataPerolehanPerBagian_model', 'models');
		$this->load->model('PertanyaanUnsurSurvei_model');
		$this->load->model('Alasan_model');
		$this->load->library('form_validation');
	}

	public function index()
	{
		$this->data = [];
		$this->data['title'] = 'Rekap Hasil Per Bagian';

		return view('rekap_hasil_per_bagian/index', $this->data);
	}

	public function ajax_list()
	{
		$users_parent = $this->db->query("SELECT GROUP_CONCAT(id) AS id_parent_induk FROM users WHERE id_parent_induk =" . $this->session->userdata('user_id'))->row();
		if($users_parent->id_parent_induk == null){
			$parent = 0;
		} else {
			$parent = $users_parent->id_parent_induk;
		}

		
		$list = $this->models->get_datatables($parent);
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			$klien_user = $this->db->get_where("users", array('id' => $value->id_user))->row();

			if ($value->is_privacy == 1) {
				$status = '<span class="badge badge-info" width="40%">Public</span>';
			} else {
				$status = '<span class="badge badge-danger" width="40%">Private</span>';
			};


			$no++;
			$row = array();
			$row[] = '
			<a data-toggle="modal" data-target="#' . $value->slug . '" title="">
				<div class="card mb-5 shadow" style="background-color: SeaShell;">
					<div class="card-body">
						<div class="row">
							<div class="col sm-10">
								<strong style="font-size: 17px;" class="text-primary">' . $value->survey_name . '</strong><br>
								<span class="text-dark">Nama Akun : <b>' . $value->first_name . ' ' . $value->last_name . '</b></span><br>
							</div>
							<div class="col sm-2 text-right">
								<div class="mt-3 text-dark font-weight-bold" style="font-size: 11px;">
									Periode Survei : ' . date('d-m-Y', strtotime($value->survey_start)) . ' s/d ' . date('d-m-Y', strtotime($value->survey_end)) . '
								</div>

							</div>
						</div>
					</div>
				</div>
			</a>
		
			<div class="modal fade" id="' . $value->slug . '" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header bg-secondary">
							<h5 class="modal-title">Pilih Rekap</h5>
						</div>
						<div class="modal-body">
							<div class="card-deck">
								<a href="' . base_url() . 'rekap-hasil-per-bagian/rekap-alasan/' . $value->slug . '" class="card card-body btn btn-outline-primary shadow">
									<div class="text-center font-weight-bold">
										<i class="fa fa-info-circle"></i><br>Lihat Rekap Alasan Jawaban
									</div>
								</a>

								<a href="' . base_url() . 'rekap-hasil-per-bagian/rekap-saran/' . $value->slug . '" class="card card-body btn btn-outline-primary shadow">
									<div class="text-center font-weight-bold">
										<i class="fa fa-info-circle"></i><br>Lihat Rekap Saran
									</div>
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>';

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->models->count_all($parent),
			"recordsFiltered" => $this->models->count_filtered($parent),
			"data" => $data,
		);
		echo json_encode($output);
	}


	public function rekap_alasan()
	{
		$this->data = [];
		$this->data['title'] = "Rekap Alasan Jawaban";
		// $this->data['profiles'] = Client_Controller::_get_data_profile($id1, $id2);

		$this->data['slug'] = $this->uri->segment(3);
		$slug = $this->data['slug'];
		$get_identity = $this->db->get_where('manage_survey', ['slug' => "$slug"])->row();
		$this->data['manage_survey'] = $get_identity;
		// $this->data['name_survey'] = $get_identity->name_survey;

		$this->data['users'] = $this->db->get_where("users", array('id' => $get_identity->id_user))->row();
		// var_dump($this->data['slug']);

		
		return view('rekap_hasil_per_bagian/form_rekap_alasan', $this->data);
	}

	public function ajax_list_rekap_alasan()
	{
		$slug = $this->uri->segment(3);

		// Get Identity
		$get_identity = $this->db->get_where('manage_survey', ['slug' => "$slug"])->row();
		$table_identity = $get_identity->table_identity;

		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('id', $get_identity->id_user);
		$data_user = $this->db->get()->row();

		$list = $this->PertanyaanUnsurSurvei_model->get_datatables($table_identity);
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $value->isi_pertanyaan_unsur;
			$row[] = anchor('rekap-hasil-per-bagian/rekap-alasan/' . $data_user->username . '/' . $this->uri->segment(3) . '/' . $value->id_pertanyaan_unsur, 'Detail Alasan <i class="fa fa-arrow-right"></i>', ['class' => 'btn btn-light-primary btn-sm font-weight-bold shadow']);
			// $row[] = '<a class="btn btn-light-primary btn-sm font-weight-bold shadow" href="javascript:void(0)" title="Hapus ' . $value->id . '" onclick="delete_data(' . "'" . $value->id . "'" . ')">Delete</a>';

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

	public function detail_rekap_alasan($id1, $id2)
	{
		$this->data = [];
		$this->data['title'] = "Detail Alasan";

		$this->data['profiles'] = Client_Controller::_get_data_profile($id1, $id2);

		$get_identity = $this->db->get_where('manage_survey', array('slug' => "$id2"))->row();
		$table_identity = $get_identity->table_identity;

		$this->db->select("*");
		$this->db->from('pertanyaan_unsur_pelayanan_' . $table_identity);
		$this->db->join("unsur_pelayanan_$table_identity", "pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan = unsur_pelayanan_$table_identity.id");
		$this->db->where("pertanyaan_unsur_pelayanan_$table_identity.id", $this->uri->segment(5));
		$this->data['pertanyaan'] = $this->db->get()->row();

		$this->data['detail'] = $this->db->get("jawaban_pertanyaan_unsur_$table_identity")->result();

		$this->form_validation->set_rules('is_active', 'Status', 'trim|required');

		if ($this->form_validation->run() === FALSE) {
			return view('rekap_hasil_per_bagian/form_detail_rekap_alasan', $this->data);
		} else {

			$value = [
				'is_active' => $this->input->post('is_active')
			];

			$this->db->where('id', $this->input->post('id_responden'));
			$this->db->update('jawaban_pertanyaan_unsur_' . $table_identity, $value);

			$this->session->set_flashdata('message_success', 'Berhasil mengedit data');
			redirect(base_url() . $this->uri->segment(3) . '/' . $this->uri->segment(4) . '/alasan/detail/' . $this->uri->segment(5), 'refresh');
		}
	}

	public function ajax_list_detail()
	{

		$id_pertanyaan_unsur = $this->uri->segment(6);
		$slug = $this->uri->segment(5);

		$get_identity = $this->db->get_where('manage_survey', array('slug' => "$slug"))->row();
		$table_identity = $get_identity->table_identity;

		$list = $this->Alasan_model->get_datatables($table_identity, $id_pertanyaan_unsur);
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			if ($value->is_active == 1) {
				$status = '<span class="badge badge-primary">Di Tampilkan</span>';
			} else {
				$status = '<span class="badge badge-danger">Tidak di Tampilkan</span>';
			}

			$no++;
			$row = array();
			$row[] = $no;
			// $row[] = $value->nama_lengkap;
			$row[] = $value->bobot;
			$row[] = $value->alasan_pilih_jawaban;
			$row[] = $status;
			// $row[] = '<a class="btn btn-light-info btn-sm font-weight-bold shadow" data-toggle="modal" data-target="#detail' . $value->id_jawaban . ' "><i class="fa fa-edit"></i>Edit Status</a>';


			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->Alasan_model->count_all($table_identity, $id_pertanyaan_unsur),
			"recordsFiltered" => $this->Alasan_model->count_filtered($table_identity, $id_pertanyaan_unsur),
			"data" => $data,
		);

		echo json_encode($output);
	}

	public function detail_rekap_alasan2()
	{
		$this->data = [];
		$this->data['title'] = "Detail Rekap Alasan Jawaban";
		// $this->data['profiles'] = Client_Controller::_get_data_profile($id1, $id2);

		$slug = $this->uri->segment(3);
		$get_identity = $this->db->get_where('manage_survey', ['slug' => "$slug"])->row();
		$this->data['manage_survey'] = $get_identity;

		$this->data['users'] = $this->db->get_where("users", array('id' => $get_identity->id_user))->row();

		$this->data['pertanyaan'] = $this->db->get_where("pertanyaan_unsur_pelayanan_$get_identity->table_identity", array('id' => $this->uri->segment(4)))->row();



		return view('rekap_hasil_per_bagian/form_detail_rekap_alasan', $this->data);
	}


	public function rekap_saran()
	{
		$this->data = [];
		$this->data['title'] = "Rekap Saran";
		// $this->data['profiles'] = Client_Controller::_get_data_profile($id1, $id2);

		$this->data['slug'] = $this->uri->segment(3);
		$slug = $this->data['slug'];
		$get_identity = $this->db->get_where('manage_survey', ['slug' => "$slug"])->row();
		$this->data['manage_survey'] = $get_identity;

		$this->data['users'] = $this->db->get_where("users", array('id' => $get_identity->id_user))->row();

		return view('rekap_hasil_per_bagian/form_rekap_saran', $this->data);
	}

}

/* End of file RekapHasilPerBagianController.php */
/* Location: ./application/controllers/RekapHasilPerBagianController.php */
