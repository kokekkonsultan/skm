<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DataPerolehanKeseluruhanController extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		if (!$this->ion_auth->logged_in()) {
			$this->session->set_flashdata('message_warning', 'You must be an admin to view this page');
			redirect('auth', 'refresh');
		}
		$this->load->model('DataPerolehanKeseluruhan_model', 'models');
	}

	public function index()
	{
		$this->data = [];
		$this->data['title'] = 'Data Perolehan Survei Keseluruhan';


		return view('data_perolehan_keseluruhan/index', $this->data);
	}

	public function ajax_list()
	{
		$users_parent = $this->db->query("SELECT GROUP_CONCAT(id) AS id_parent_induk FROM users WHERE id_parent_induk =" . $this->session->userdata('user_id'))->row();
		if($users_parent->id_parent_induk == null){
			$parent = 0;
		} else {
			$parent = $users_parent->id_parent_induk;
		}

		$data = array();
		foreach ($this->db->query("SELECT *, (SELECT first_name FROM users WHERE id = manage_survey.id_user) AS nama_depan, (SELECT last_name FROM users WHERE id = manage_survey.id_user) AS nama_belakang FROM manage_survey WHERE id_user IN ($parent)")->result() as $key => $value) {

			$data[$key] = "UNION SELECT
						id_responden,
						'' AS nama_lengkap,
						saran,
						waktu_isi,
						is_submit,
						responden_$value->table_identity.uuid,
						(SELECT nama_layanan FROM layanan_survei_$value->table_identity WHERE responden_$value->table_identity.id_layanan_survei = layanan_survei_$value->table_identity.id),
						'$value->slug',
						'$value->table_identity',
						survey_$value->table_identity.id_surveyor,
						survey_$value->table_identity.is_end,
						surveyor.kode_surveyor,
						(SELECT first_name FROM users WHERE surveyor.id_user = id),
						(SELECT last_name FROM users WHERE surveyor.id_user = id),
						'$value->nama_depan',
						'$value->nama_belakang'

						FROM responden_$value->table_identity
						JOIN survey_$value->table_identity ON responden_$value->table_identity.id = survey_$value->table_identity.id_responden
						LEFT JOIN surveyor ON survey_$value->table_identity.id_surveyor = surveyor.id";
		}
		$tabel_union = implode(" ", $data);

		$list = $this->models->get_datatables($tabel_union);
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			if ($value->is_submit == 1) {
				$status = '<span class="badge badge-primary">Lengkap</span>';
			} else {
				$status = '<span class="badge badge-danger">Tidak Lengkap</span><br>
				<small class="text-dark-50 font-italic">' . $value->is_end . '</small>';
			}

			// $id_layanan_survei = implode(", ", unserialize($value->id_layanan_survei));
			// $data_nama = [];
			// foreach($this->db->query("SELECT * FROM layanan_survei_$value->table_identity WHERE id IN ($id_layanan_survei)")->result() as $val){
			// 	$data_nama[] = '<li>' . $val->nama_layanan . '</li>';
			// }

			$no++;
			$row = array();
			
			if($value->nama_lengkap != ''){
				$responden = $value->nama_lengkap;
			}else{
				$responden = 'Responden '.$no;
			}

			$row[] = $no;
			$row[] = $value->nama_depan_user . ' '. $value->nama_belakang_user;

			$row[] = $status;
			$row[] = anchor($value->slug . '/hasil-survei/' . $value->uuid_responden, '<i class="fas fa-file-pdf text-danger"></i>', ['target' => '_blank']);

			$row[] = '<b>' . $value->kode_surveyor . '</b>--' . $value->first_name . ' ' . $value->last_name;

			$row[] = $value->nama_layanan; //'<b>' . $value->kode_surveyor . '</b>--' . $value->first_name . ' ' . $value->last_name;

			//$row[] = $value->nama_lengkap;
			$row[] = $responden;

			$row[] = date("d-m-Y", strtotime($value->waktu_isi));

			//$row[] = anchor('/survei/' . $value->slug . '/data-responden/'  . $value->uuid_responden . '/edit', '<i class="fa fa-edit"></i> Edit', ['class' => 'btn btn-light-primary btn-sm font-weight-bold shadow', 'target' => '_blank']);

			//$row[] = '<a class="btn btn-light-primary btn-sm font-weight-bold shadow" href="javascript:void(0)" title="Hapus ' . $value->nama_lengkap . '" onclick="delete_data(' . "'" . $value->id_responden . "'" . ')"><i class="fa fa-trash"></i> Delete</a>';

			$data[] = $row;
		}
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->models->count_all($tabel_union),
			"recordsFiltered" => $this->models->count_filtered($tabel_union),
			"data" => $data,
		);
		echo json_encode($output);
	}


	public function detail()
	{
		$this->data = [];
		$this->data['title'] = 'Data Perolehan Survei Keseluruhan';


		return view('data_perolehan_keseluruhan/index', $this->data);
	}
}

/* End of file DataPerolehanSurveiController.php */
/* Location: ./application/controllers/DataPerolehanSurveiController.php */