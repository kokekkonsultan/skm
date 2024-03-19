<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PHLAK\StrGen;

class DataSurveyorController extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		if (!$this->ion_auth->logged_in()) {
			$this->session->set_flashdata('message_warning', 'You must be an admin to view this page');
			redirect('auth', 'refresh');
		}

		$this->load->library('form_validation');
		$this->load->library(['ion_auth', 'form_validation']);
		$this->load->helper(['url', 'language']);
		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

		$this->lang->load('auth');
		$this->load->model('DataSurveyor_model', 'models');
	}

	public function index($id1, $id2)
	{
		$url = $this->uri->uri_string();
		$this->session->set_userdata('urlback', $url);

		$this->data = [];
		$this->data['title'] = "Data Surveyor";
		$this->data['profiles'] = $this->_get_data_profile($id1, $id2);

		$this->data['slug'] = $this->uri->segment(2);

		$get_identity = $this->db->get_where('manage_survey', ['slug' => $this->data['slug']])->row();
		$id_manage_survey = $get_identity->id;

		$this->db->select('*, surveyor.uuid AS uuid_surveyor');
		$this->db->from('surveyor');
		$this->db->join('users', 'surveyor.id_user = users.id');
		$this->db->where('surveyor.id_manage_survey', $id_manage_survey);
		//$this->data['surveyor'] = $this->db->get();
		$total_surveyor = $this->db->get()->num_rows;

		if($total_surveyor > 0){
			$this->db->select('*, surveyor.uuid AS uuid_surveyor');
			$this->db->from('surveyor');
			$this->db->join('users', 'surveyor.id_user = users.id');
			$this->db->where('surveyor.id_manage_survey', $id_manage_survey);
		}else{
			$this->db->select('*, surveyor_induk.uuid AS uuid_surveyor');
			$this->db->from('surveyor_induk');
			$this->db->join('users', 'surveyor_induk.id_user = users.id');
			$this->db->where('surveyor_induk.id_manage_survey', $id_manage_survey);
		}
		$this->data['surveyor'] = $this->db->get();

		return view('data_surveyor/index', $this->data);
	}

	public function ajax_list()
	{
		$slug = $this->uri->segment(2);

		$get_identity = $this->db->get_where('manage_survey', ['slug' => "$slug"])->row();
		$id_manage_survey = $get_identity->id;

		$list = $this->models->get_datatables($id_manage_survey);
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = '<a class="btn btn-light-info btn-sm font-weight-bold shadow" data-toggle="modal" data-target="#detail' . $value->id_user . ' "><i class="fa fa-info-circle"></i>Detail</a>';
			$row[] = $value->first_name . ' ' . $value->last_name;
			$row[] = $value->kode_surveyor;
			$row[] = anchor($this->session->userdata('username') . '/' . $this->uri->segment(2) . '/data-surveyor/edit/' . $value->id_user, '<i class="fa fa-edit"></i> Edit', ['class' => 'btn btn-light-primary btn-sm font-weight-bold shadow']);
			$row[] = '<a class="btn btn-light-primary btn-sm font-weight-bold shadow" href="javascript:void(0)" title="Hapus ' . $value->first_name . ' ' . $value->last_name . '" onclick="delete_data(' . "'" . $value->id_user . "'" . ')"><i class="fa fa-trash"></i> Delete</a>';

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->models->count_all($id_manage_survey),
			"recordsFiltered" => $this->models->count_filtered($id_manage_survey),
			"data" => $data,
		);

		echo json_encode($output);
	}

	public function add_surveyor($id1, $id2)
	{
		$this->data = [];
		$this->data['title'] = "Tambah Data Surveyor";
		$this->data['profiles'] = $this->_get_data_profile($id1, $id2);
		$kode_surveyor = $this->_kode_surveyor();

		// if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
		// 	redirect('auth', 'refresh');
		// }

		$tables = $this->config->item('tables', 'ion_auth');
		$identity_column = $this->config->item('identity', 'ion_auth');
		$this->data['identity_column'] = $identity_column;

		// validate form input
		$this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'trim|required');
		$this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'trim|required');
		if ($identity_column !== 'email') {
			$this->form_validation->set_rules('identity', $this->lang->line('create_user_validation_identity_label'), 'trim|required|is_unique[' . $tables['users'] . '.' . $identity_column . ']');
			$this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|required|valid_email');
		} else {
			$this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|required|valid_email|is_unique[' . $tables['users'] . '.email]');
		}
		$this->form_validation->set_rules('phone', $this->lang->line('create_user_validation_phone_label'), 'trim');
		$this->form_validation->set_rules('company', $this->lang->line('create_user_validation_company_label'), 'trim');
		$this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|matches[password_confirm]');
		$this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');

		if ($this->form_validation->run() === TRUE) {
			$email = strtolower($this->input->post('email'));
			$identity = ($identity_column === 'email') ? $email : $this->input->post('identity');
			$password = $this->input->post('password');

			// get id manage_survey
			$this->db->select('*');
			$this->db->from('manage_survey');
			$this->db->where('manage_survey.slug', $this->uri->segment(2));
			$manage_survey = $this->db->get()->row();

			$additional_data = [
				'first_name' => $this->input->post('first_name'),
				'last_name' => $this->input->post('last_name'),
				'company' => $this->input->post('company'),
				'phone' => $this->input->post('phone'),
				'is_surveyor' => 1,
				'is_parent' => $manage_survey->id_user,
				'is_trial' => ''
			];
			$group = array('3');
		}
		if ($this->form_validation->run() === TRUE && $this->ion_auth->register($identity, $password, $email, $additional_data, $group)) {

			// get id user
			$this->db->select('users.id');
			$this->db->from('users');
			$this->db->where('username', $this->input->post('identity'));
			$id_user = $this->db->get()->row()->id;

			$this->load->library('uuid');

			$object = [
				'uuid' => $this->uuid->v4(),
				'id_user' => $id_user,
				'id_manage_survey' => $manage_survey->id,
				'kode_surveyor' => $this->input->post('kode_surveyor')
			];

			$this->db->insert('surveyor', $object);

			$this->session->set_flashdata('message_success', 'Berhasil menambah data');
			redirect($this->session->userdata('urlback'));
		} else {

			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			$this->data['first_name'] = [
				'name' => 'first_name',
				'id' => 'first_name',
				'type' => 'text',
				'value' => $this->form_validation->set_value('first_name'),
				'class' => 'form-control',
				'autofocus' => 'autofocus'
			];
			$this->data['last_name'] = [
				'name' => 'last_name',
				'id' => 'last_name',
				'type' => 'text',
				'value' => $this->form_validation->set_value('last_name'),
				'class' => 'form-control',
			];
			$this->data['identity'] = [
				'name' => 'identity',
				'id' => 'identity',
				'type' => 'text',
				'value' => $this->form_validation->set_value('identity'),
				'class' => 'form-control',
			];
			$this->data['email'] = [
				'name' => 'email',
				'id' => 'email',
				'type' => 'text',
				'value' => $this->form_validation->set_value('email'),
				'class' => 'form-control',
			];
			$this->data['company'] = [
				'name' => 'company',
				'id' => 'company',
				'type' => 'text',
				'value' => $this->form_validation->set_value('company'),
				'class' => 'form-control',
			];
			$this->data['phone'] = [
				'name' => 'phone',
				'id' => 'phone',
				'type' => 'number',
				'value' => $this->form_validation->set_value('phone'),
				'class' => 'form-control',
			];
			$this->data['password'] = [
				'name' => 'password',
				'id' => 'password',
				'type' => 'password',
				'value' => $this->form_validation->set_value('password'),
				'class' => 'form-control',
			];
			$this->data['password_confirm'] = [
				'name' => 'password_confirm',
				'id' => 'password_confirm',
				'type' => 'password',
				'value' => $this->form_validation->set_value('password_confirm'),
				'class' => 'form-control',
			];

			$this->data['kode_surveyor'] = [
				'name' => 'kode_surveyor',
				'id' => 'kode_surveyor',
				'type' => 'text',
				'value' => $kode_surveyor,
				'class' => 'form-control font-weight-bold',
				'readonly' => 'readonly',
				'style' => 'background-color:#f2f2f2;'
			];

			return view("data_surveyor/form_add", $this->data);
		}
	}

	public function edit_surveyor($id1, $id2)
	{
		$this->data = [];
		$this->data['title'] = "Edit Data Surveyor";
		$this->data['profiles'] = $this->_get_data_profile($id1, $id2);
		$this->data['form_action'] = base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2) . '/data-surveyor/edit/' . $this->uri->segment(5);

		$this->db->select('*, users.id AS id_users');
		$this->db->from('surveyor');
		$this->db->join('users', 'users.id = surveyor.id_user');
		$this->db->where('surveyor.id_user', $this->uri->segment(5));
		$total_surveyor = $this->db->get()->num_rows;

		if($total_surveyor > 0){
			$this->db->select('*, users.id AS id_users');
			$this->db->from('surveyor');
			$this->db->join('users', 'users.id = surveyor.id_user');
			$this->db->where('surveyor.id_user', $this->uri->segment(5));
		}else{
			$this->db->select('*, users.id AS id_users');
			$this->db->from('surveyor_induk');
			$this->db->join('users', 'users.id = surveyor_induk.id_user');
			$this->db->where('surveyor_induk.id_user', $this->uri->segment(5));
		}
		$get_data = $this->db->get();
		$current = $get_data->row();

		$this->form_validation->set_rules('first_name', $this->lang->line('edit_user_validation_fname_label'), 'trim|required');
		$this->form_validation->set_rules('last_name', $this->lang->line('edit_user_validation_lname_label'), 'trim|required');
		$this->form_validation->set_rules('phone', $this->lang->line('edit_user_validation_phone_label'), 'trim');
		$this->form_validation->set_rules('company', $this->lang->line('edit_user_validation_company_label'), 'trim');

		if (isset($_POST) && !empty($_POST)) {

			// update the password if it was posted
			if ($this->input->post('password')) {
				$this->form_validation->set_rules('password', $this->lang->line('edit_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|matches[password_confirm]');
				$this->form_validation->set_rules('password_confirm', $this->lang->line('edit_user_validation_password_confirm_label'), 'required');
			}

			if ($this->form_validation->run() === TRUE) {
				$data = [
					'first_name' => $this->input->post('first_name'),
					'last_name' => $this->input->post('last_name'),
					'company' => $this->input->post('company'),
					'phone' => $this->input->post('phone'),
				];

				$surveyor = [
					'kode_surveyor' => $this->input->post('kode_surveyor')
				];

				if ($this->input->post('password')) {
					$data['password'] = $this->input->post('password');
				}

				$this->db->where('id', $current->id_users);
				$this->db->update('users', $data);

				if($total_surveyor > 0){
					$this->db->where('id_user', $current->id_users);
					$this->db->update('surveyor', $surveyor);
				}

				$this->session->set_flashdata('message_success', 'Berhasil menambah data');
				redirect($this->session->userdata('urlback'));
			}
		}

		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		$this->data['first_name'] = [
			'name'  => 'first_name',
			'id'    => 'first_name',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('first_name', $current->first_name),
			'class' => 'form-control',
		];
		$this->data['last_name'] = [
			'name'  => 'last_name',
			'id'    => 'last_name',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('last_name', $current->last_name),
			'class' => 'form-control',
		];
		$this->data['company'] = [
			'name'  => 'company',
			'id'    => 'company',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('company', $current->company),
			'class' => 'form-control',
		];
		$this->data['email'] = [
			'name'  => 'email',
			'id'    => 'email',
			'type'  => 'email',
			'value' => $this->form_validation->set_value('email', $current->email),
			'class' => 'form-control',
		];
		$this->data['phone'] = [
			'name'  => 'phone',
			'id'    => 'phone',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('phone', $current->phone),
			'class' => 'form-control',
		];
		$this->data['password'] = [
			'name' => 'password',
			'id'   => 'password',
			'type' => 'password',
			'class' => 'form-control',
		];
		$this->data['password_confirm'] = [
			'name' => 'password_confirm',
			'id'   => 'password_confirm',
			'type' => 'password',
			'class' => 'form-control',
		];

		$this->data['kode_surveyor'] = [
			'name' => 'kode_surveyor',
			'id'   => 'kode_surveyor',
			'type' => 'text',
			'value' => $this->form_validation->set_value('kode_surveyor', $current->kode_surveyor),
			'class' => 'form-control',
			'readonly' => 'readonly',
			'style' => 'background-color:#f2f2f2;'
		];

		return view('data_surveyor/form_edit', $this->data);
	}

	public function delete_surveyor()
	{
		// $this->data['profiles'] = $this->_get_data_profile($id1, $id2);
		$this->db->select('*, users.id AS id_users');
		$this->db->from('surveyor');
		$this->db->join('users', 'users.id = surveyor.id_user');
		$this->db->join('users_groups', 'users.id = users_groups.user_id');
		$this->db->where('surveyor.id_user', $this->uri->segment(5));
		$get_data = $this->db->get();
		$current = $get_data->row();

		$this->db->delete('users', array('id' => $current->id_users));
		$this->db->delete('surveyor', array('id_user' => $current->id_users));
		$this->db->delete('users_groups', array('user_id' => $current->id_users));

		echo json_encode(array("status" => TRUE));
	}

	public function _kode_surveyor()
	{
		$this->db->select('RIGHT(surveyor.kode_surveyor,2) as kode_surveyor', FALSE);
		$this->db->order_by('kode_surveyor', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get('surveyor');  //cek dulu apakah ada sudah ada kode di tabel.    
		if ($query->num_rows() <> 0) {
			//cek kode jika telah tersedia    
			$data = $query->row();
			$kode = intval($data->kode_surveyor) + 1;
		} else {
			$kode = 1;  //cek jika kode belum terdapat pada table
		}
		$batas = str_pad($kode, 3, "0", STR_PAD_LEFT);
		$kodetampil = "SURV" . $batas;  //format kode
		return $kodetampil;
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


	public function linktosurveyor()
	{
		$this->db->select('*');
		$this->db->from('users');
		//$this->db->join('users_groups', 'users.id = users_groups.user_id');
		//$this->db->where('users.username', $this->session->userdata('username'));
		$this->db->limit(352, 48);
		$data_user = $this->db->get();
		$i=0;
		foreach ($data_user->result() as $row) {
			$i++;
			// $this->db->select('id_user');
			// $this->db->from('manage_survey');
			// $this->db->where('manage_survey.id_user', $row->id);
			// $manage_survey = $this->db->get()->row();

			// $data = [
			// 	'is_parent' => $manage_survey->id_user,
			// ];
			//$this->db->where('id', $current->id_users);
			//$this->db->update('users', $data);
			echo $i.'. '.$row->id.' = '.$row->company.'<br>';
		}
	}

	public function excel_client()
	{
		$object = PHPExcel_IOFactory::load('Akun Anak Djp.xlsx');
	
		foreach($object->getWorksheetIterator() as $worksheet){

			$highestRow = $worksheet->getHighestRow();
			$highestColumn = $worksheet->getHighestColumn();

			for($row=4; $row<=$highestRow; $row++){
				$identity_column = $this->config->item('identity', 'ion_auth');
				$nama = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
				$user = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
				$email = $user.'@gmail.com';
				$identity = ($identity_column === 'email') ? $email : $user;
				$password = '12345678';
				$pieces = explode(" ", $nama);
				$first_name = $pieces[0];
				$last_name = substr(strstr($nama," "), 1);

				$generator = new StrGen\Generator();
				$first = $generator->length(5)->charset([StrGen\CharSet::MIXED_ALPHA, StrGen\CharSet::NUMERIC])->generate();
				$last = $generator->length(15)->charset([StrGen\CharSet::MIXED_ALPHA, StrGen\CharSet::NUMERIC])->generate();
				$app_id = $first . '-' . $last;
				$this->load->library('uuid');

				$additional_data = [
					'first_name' => $first_name,
					'last_name' => $last_name,
					'company' => $nama,
					'phone' => '12345678',
					're_password' => $password,
					'id_klasifikasi_survei' => 10,
					'app_id' => $app_id,
					'uuid' => $this->uuid->v4(),
				];
				//$group = array(2);
				//$this->ion_auth->register($identity, $password, $email, $additional_data, $group);

				//echo $first_name.' '.$last_name.' = '.$user.' '.$email.' - '.$app_id.' - '.$this->uuid->v4().'<br>';

			} 

		}
	}

	public function excel_surveyor()
	{
		$object = PHPExcel_IOFactory::load('Akun Anak Djp.xlsx');
	
		foreach($object->getWorksheetIterator() as $worksheet){

			$highestRow = $worksheet->getHighestRow();
			$highestColumn = $worksheet->getHighestColumn();

			for($row=4; $row<=$highestRow; $row++){
				$identity_column = $this->config->item('identity', 'ion_auth');
				$nama = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
				$user = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
				$email = $user.'@gmail.com';
				$identity = ($identity_column === 'email') ? $email : $user;
				$password = '12345678';
				$pieces = explode(" ", $nama);
				$first_name = $pieces[0];
				$last_name = substr(strstr($nama," "), 1);

				$generator = new StrGen\Generator();
				$first = $generator->length(5)->charset([StrGen\CharSet::MIXED_ALPHA, StrGen\CharSet::NUMERIC])->generate();
				$last = $generator->length(15)->charset([StrGen\CharSet::MIXED_ALPHA, StrGen\CharSet::NUMERIC])->generate();
				$app_id = $first . '-' . $last;
				$this->load->library('uuid');

				$this->db->select('*');
				$this->db->from('manage_survey');
				$this->db->where('manage_survey.slug', $this->uri->segment(2));
				//$manage_survey = $this->db->get()->row();

				$this->db->select('users.id');
				$this->db->from('users');
				//$this->db->where('username', $this->input->post('identity'));
				//$id_user = $this->db->get()->row()->id;

				$kode_surveyor = $this->_kode_surveyor();

				$additional_data = [
					'first_name' => $first_name,
					'last_name' => $last_name,
					'company' => $nama,
					'phone' => '12345678',
					're_password' => $password,
					'id_klasifikasi_survei' => 10,
					//'is_parent' => $manage_survey->id_user,
					'app_id' => $app_id,
					'uuid' => $this->uuid->v4(),
				];
				//$group = array(3);
				//$this->ion_auth->register($identity, $password, $email, $additional_data, $group);

				//if ($this->ion_auth->register($identity, $password, $email, $additional_data, $group)) {
					$user_id = $this->db->query("SELECT id FROM users WHERE username = '$identity'")->row()->id;
					$object = [
						'uuid' => $this->uuid->v4(),
						//'id_user' => $user_id,
						//'id_manage_survey' => $manage_survey->id,
						'kode_surveyor' => $kode_surveyor
					];
					//$this->db->insert('surveyor', $object);
				//}
				
				//echo $first_name.' '.$last_name.' = '.$user.' '.$email.' - '.$app_id.' - '.$this->uuid->v4().'<br>';

			} 

		}
	}



	public function excel_surveyor_survey()
	{
		$object = PHPExcel_IOFactory::load('Akun Anak Djp Surveyor 2.xlsx');
	
		foreach($object->getWorksheetIterator() as $worksheet){

			$highestRow = $worksheet->getHighestRow();
			$highestColumn = $worksheet->getHighestColumn();

			for($row=2; $row<=$highestRow; $row++){
				$djp = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
				$nama = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
				$user = $worksheet->getCellByColumnAndRow(4, $row)->getValue();

				$this->db->select('id');
				$this->db->from('users');
				$this->db->where('users.company', $nama);
				$users = $this->db->get()->row();

				$this->db->select('id');
				$this->db->from('users');
				$this->db->where('users.company', 'Surveyor '.$djp);
				$djps = $this->db->get()->row();

				$this->db->select('*');
				$this->db->from('manage_survey');
				$this->db->where('manage_survey.id_user', $users->id);
				$manage_survey = $this->db->get();

				$this->load->library('uuid');

				foreach($manage_survey->result() as $data){
					//echo $nama.' = '.$data->id.'<br>';
					if($data->survey_name=="Survei Kepuasan Pengguna Layanan DJP Tatap Muka"){
						$urut = 1;
					}
					echo "INSERT INTO surveyor_induk (id_user, id_manage_survey, uuid) VALUES(".$djps->id.", ".$data->id.", '".$this->uuid->v4()."');<br>";
				}

				echo '<br>';

				//echo $djp.' = '.$djps->id.'<br>';

			} 

		}
	}

	

}

/* End of file DataSurveyorController.php */
/* Location: ./application/controllers/DataSurveyorController.php */