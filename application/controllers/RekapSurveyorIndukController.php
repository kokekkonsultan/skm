<?php
defined('BASEPATH') or exit('No direct script access allowed');

class RekapSurveyorIndukController extends CI_Controller{

	public $ion_auth;
	public $session;
	public $data;
	public $db;

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
        $this->data['title'] = "Rekap Surveyor";

		// echo "<pre>";
		// print_r($_SESSION);
		// echo "</pre>";
		
		$this->db->select('*');
		$this->db->from('users');
		$this->db->join('users_groups', 'users_groups.user_id = users.id');
		$this->db->join('groups', 'groups.id = users_groups.group_id');
		// $this->db->join('surveyor_induk', 'surveyor_induk.id_user = users.id');
		$this->db->where('users.id_parent_induk', $_SESSION['user_id']);
		$data_surveyor_induk = $this->db->get();
		
		

		// echo "<pre>";
		// print_r($data_surveyor_induk->result());
		// echo "</pre>";
		
		$this->data['data_surveyor_induk'] = $data_surveyor_induk;
		$this->data['data_user'] = $this->ion_auth->user()->row();
		
        return view('rekap_surveyor_induk/index', $this->data);
	}

	public function unit_satuan_kerja()
	{
		$akun_surveyor = $_GET['akun_surveyor'];

		if ($akun_surveyor) {

			$this->db->select('users.*');
			$this->db->from('users');
			$this->db->join('manage_survey', 'users.id = manage_survey.id_user');
			$this->db->join('surveyor_induk', 'manage_survey.id = surveyor_induk.id_manage_survey');
			//$this->db->where('users.id_parent_induk =' . $surveyor->is_parent);
			$this->db->where('surveyor_induk.id_user =' . $akun_surveyor);
			$this->db->group_by('users.username'); 
			$users =  $this->db->get();
			$this->data['user_anak'] = $users;
			echo '<option value="">Please Select</option>';
			foreach($users->result() as $row){
				echo '<option value="'.$row->id.'">'.$row->first_name.' '.$row->last_name.'</option>';
			}

			//return view('rekap_surveyor_induk/unit_satuan_kerja', $this->data);
		} else {
			/*echo '<label for="" class="font-weight-bold">Pilih Unit atau Satuan Kerja</label>
			<select name="akun_anak" id="akun_anak" class="form-control kt_select2_2">
			<option value="">Please Select</option>
			</select>';*/
			echo '<option value="">Please Select</option>';
		}
	}

	public function proses()
	{
		$akun_surveyor = $_GET['akun_surveyor'];
		$akun_anak = $_GET['akun_anak'];

		if ($akun_anak) {

			$this->db->select('*');
			$this->db->from('manage_survey');
			$this->db->join('surveyor_induk', 'manage_survey.id = surveyor_induk.id_manage_survey');
			$this->db->where('manage_survey.id_user', $akun_anak);
			$this->db->where('surveyor_induk.id_user', $akun_surveyor);
			$this->db->group_by('manage_survey.uuid'); 
			$this->data['manage_survey'] = $this->db->get();

			return view('rekap_surveyor_induk/detail_akun', $this->data);
		} else {
			echo '<div class="text-center mt-5">Silahkan pilih akun anak terlebih dahulu</div>';
		}
	}

	public function modal_detail($id)
	{
		$this->data = [];

		$this->data['manage_survey'] = $this->db->query("SELECT *
		FROM manage_survey WHERE id = $id")->row();

		$this->data['table_identity'] = $this->data['manage_survey']->table_identity;
		$table_identity = $this->data['table_identity'];

		//PANGGIL PROFIL RESPONDEN
		$this->data['profil_responden'] = $this->db->query("SELECT * FROM profil_responden_$table_identity ORDER BY IF(urutan != '',urutan,id) ASC")->result();


		return view('rekap_surveyor_induk/modal_detail', $this->data);
	}

	public function proses2()
	{
		$akun_anak = $_GET['akun_anak'];

		if ($akun_anak) {

			$this->db->select('*');
			$this->db->from('manage_survey');
			$this->db->join('surveyor_induk', 'manage_survey.id = surveyor_induk.id_manage_survey');
			$this->db->where('manage_survey.id_user', $akun_anak);
			$this->db->group_by('manage_survey.uuid'); 
			$this->data['manage_survey'] = $this->db->get();

			return view('rekap_surveyor_induk/detail_perolehan', $this->data);
		} else {
			echo '<div class="text-center mt-5">Silahkan pilih unit atau satuan kerja terlebih dahulu</div>';
		}
	}

}
