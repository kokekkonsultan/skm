<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DataPerolehanPerBagianController extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		if (!$this->ion_auth->logged_in()) {
			$this->session->set_flashdata('message_warning', 'You must be an admin to view this page');
			redirect('auth', 'refresh');
		}
		$this->load->model('DataPerolehanPerBagian_model', 'models');
	}

	public function index()
	{
		$this->data = [];
		$this->data['title'] = 'Data Perolehan Survei Per Bagian';


		return view('data_perolehan_per_bagian/index', $this->data);
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

			if ($value->is_privacy == 1) {
				$status = '<span class="badge badge-info" width="40%">Public</span>';
			} else {
				$status = '<span class="badge badge-danger" width="40%">Private</span>';
			};
			
			$responden_lengkap = $this->db->query("SELECT id FROM survey_$value->table_identity WHERE is_submit = 1")->num_rows();
			$responden_tidak_lengkap = $this->db->query("SELECT id FROM survey_$value->table_identity WHERE is_submit <> 1")->num_rows();

			$no++;
			$row = array();
			$row[] = '
			<a href="' . base_url() . 'data-perolehan-per-bagian/' . $value->slug . '" title="">
			<div class="card mb-5 shadow" style="background-color: SeaShell;">
				<div class="card-body">
					<div class="row">
						<div class="col sm-10">
							<strong style="font-size: 17px;">' . $value->survey_name . '</strong><br>
							<span class="text-dark">Nama Akun : <b>' . $value->first_name . ' ' . $value->last_name . '</b></span><br>
							<span class="text-dark">Responden Isi Lengkap : <b>' . $responden_lengkap . '</b></span><br>
                            <span class="text-dark">Responden Isi Tidak Lengkap : <b>' . $responden_tidak_lengkap . '</b></span><br>
						</div>
						<div class="col sm-2 text-right"><!--<div class="text-dark">Responden Isi Lengkap: <b>' .$responden_lengkap. '</b></div>
						    <div class="text-dark">Responden Isi Tidak Lengkap: <b>' .$responden_tidak_lengkap. '</b></div>-->
							<div class="mt-3 text-dark font-weight-bold" style="font-size: 11px;">
								Periode Survei : ' . date('d-m-Y', strtotime($value->survey_start)) . ' s/d ' . date('d-m-Y', strtotime($value->survey_end)) . '
							</div>

						</div>
					</div>
					<!--small class="text-secondary">' . $value->description . '</small><br-->
					
				</div>
			</div>
		</a>';

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



	public function detail()
	{
		$this->data = [];
		$this->data['title'] = 'Detail Perolehan Survei';

		$this->db->select('manage_survey.id AS id_manage_survey, manage_survey.table_identity AS table_identity');
		$this->db->from('manage_survey');
		$this->db->where('manage_survey.slug', $this->uri->segment(2));
		$manage_survey = $this->db->get()->row();

		//PANGGIL PROFIL RESPONDEN
		$this->data['profil'] = $this->db->query("SELECT * FROM profil_responden_$manage_survey->table_identity ORDER BY IF(urutan != '',urutan,id) ASC")->result();

		//PANGGIL PROFIL RESPONDEN UNTUK FILTER
		$this->data['profil_responden_filter'] = $this->db->query("SELECT *,  REPLACE(LOWER(nama_profil_responden), ' ', '_') AS nama_alias FROM profil_responden_$manage_survey->table_identity WHERE jenis_isian = 1");

		//LOAD KATEGORI PROFIL RESPONDEN JIKA PILIHAN GANDA
		$this->data['kategori_profil_responden'] = $this->db->get('kategori_profil_responden_' . $manage_survey->table_identity);


		return view('data_perolehan_per_bagian/form_detail', $this->data);
	}
}

/* End of file DataPerolehanSurveiController.php */
/* Location: ./application/controllers/DataPerolehanSurveiController.php */