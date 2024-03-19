<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Auth
 * @property Ion_auth|Ion_auth_model $ion_auth        The ION Auth spark
 * @property CI_Form_validation      $form_validation The form validation library
 */

use PHLAK\StrGen;
use Carbon\Carbon;

class Auth extends CI_Controller
{
	public $data = [];

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library(['ion_auth', 'form_validation']);
		$this->load->helper(['url', 'language']);

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

		$this->lang->load('auth');
		$this->load->model('Auth_model', 'Models');
	}

	//PENGGUNA ADMINISTRATOR
	public function index()
	{
		if (!$this->ion_auth->logged_in()) {
			// redirect them to the login page
			redirect('auth/login', 'refresh');
		} else if (!$this->ion_auth->is_admin()) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			// show_error('You must be an administrator to view this page.');
			$this->_redirection();
		} else {
			$this->data['title'] = $this->lang->line('index_heading');

			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			//list the users
			$this->data['users'] = $this->ion_auth->users()->result();

			//USAGE NOTE - you can do more complicated queries like this
			//$this->data['users'] = $this->ion_auth->where('field', 'value')->users()->result();

			foreach ($this->data['users'] as $k => $user) {
				$this->data['users'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
			}

			// $this->_render_page('auth' . DIRECTORY_SEPARATOR . 'index', $this->data);
			return view('auth/index', $this->data);
		}
	}

	public function ajax_list_administrator()
	{
		$this->load->model('AuthAdministrator_model');

		$list = $this->AuthAdministrator_model->get_datatables();
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $value->first_name;
			$row[] = $value->last_name;
			$row[] = $value->email;
			$row[] = anchor("auth/edit_group/" . $value->group_id, htmlspecialchars($value->name, ENT_QUOTES, 'UTF-8'));
			$row[] = ($value->active) ? anchor("auth/deactivate/" . $value->user_id, lang('index_active_link')) : anchor("auth/activate/" . $value->user_id, lang('index_inactive_link'));
			$row[] = anchor("pengguna-administrator/edit_administrator/" . $value->user_id, 'Edit', ['class' => 'btn btn-secondary font-weight-bold']);
			$row[] = anchor("auth/delete_user/" . $value->user_id, 'Delete', ['class' => 'btn btn-secondary font-weight-bold', 'onclick' => "return confirm('Anda yakin ingin menghapus pengguna ?')"]);
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->AuthAdministrator_model->count_all(),
			"recordsFiltered" => $this->AuthAdministrator_model->count_filtered(),
			"data" => $data,
		);

		echo json_encode($output);
	}

	public function create_administrator()
	{
		$this->data = [];
		$this->data['title'] = "Create Administrator";

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth', 'refresh');
		}

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

			$additional_data = [
				'first_name' => $this->input->post('first_name'),
				'last_name' => $this->input->post('last_name'),
				'company' => $this->input->post('company'),
				'phone' => $this->input->post('phone'),
			];

			$group = array('1');
		}
		if ($this->form_validation->run() === TRUE && $this->ion_auth->register($identity, $password, $email, $additional_data, $group)) {

			$this->session->set_flashdata('message', $this->ion_auth->messages());
			redirect(base_url() . "pengguna-administrator", 'refresh');
		} else {

			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			$this->data['first_name'] = [
				'name' => 'first_name',
				'id' => 'first_name',
				'type' => 'text',
				'value' => $this->form_validation->set_value('first_name'),
				'class' => 'form-control',
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
				'type' => 'text',
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


			return view("auth/form_create_administrator", $this->data);
		}
	}

	public function edit_administrator($id)
	{
		$this->data['title'] = 'Edit Administrator';

		if (!$this->ion_auth->logged_in() || (!$this->ion_auth->is_admin() && !($this->ion_auth->user()->row()->id == $id))) {
			redirect('auth', 'refresh');
		}

		$user = $this->ion_auth->user($id)->row();

		//USAGE NOTE - you can do more complicated queries like this
		//$groups = $this->ion_auth->where(['field' => 'value'])->groups()->result_array();


		// validate form input
		$this->form_validation->set_rules('first_name', $this->lang->line('edit_user_validation_fname_label'), 'trim|required');
		$this->form_validation->set_rules('last_name', $this->lang->line('edit_user_validation_lname_label'), 'trim|required');
		$this->form_validation->set_rules('phone', $this->lang->line('edit_user_validation_phone_label'), 'trim');
		$this->form_validation->set_rules('company', $this->lang->line('edit_user_validation_company_label'), 'trim');

		if (isset($_POST) && !empty($_POST)) {
			// do we have a valid request?
			if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id')) {
				show_error($this->lang->line('error_csrf'));
			}

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
					'phone' => $this->input->post('phone')
				];

				// update the password if it was posted
				if ($this->input->post('password')) {
					$data['password'] = $this->input->post('password');
				}

				// check to see if we are updating the user
				if ($this->ion_auth->update($user->id, $data)) {
					// redirect them back to the admin page if admin, or to the base url if non admin
					$this->session->set_flashdata('message', $this->ion_auth->messages());
					$this->redirectUser();
				} else {
					// redirect them back to the admin page if admin, or to the base url if non admin
					$this->session->set_flashdata('message', $this->ion_auth->errors());
					$this->redirectUser();
				}
			}
		}

		// display the edit user form
		$this->data['csrf'] = $this->_get_csrf_nonce();

		// set the flash data error message if there is one
		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		// pass the user to the view
		$this->data['user'] = $user;

		$this->data['first_name'] = [
			'name'  => 'first_name',
			'id'    => 'first_name',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('first_name', $user->first_name),
			'class' => 'form-control',
		];
		$this->data['last_name'] = [
			'name'  => 'last_name',
			'id'    => 'last_name',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('last_name', $user->last_name),
			'class' => 'form-control',
		];
		$this->data['company'] = [
			'name'  => 'company',
			'id'    => 'company',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('company', $user->company),
			'class' => 'form-control',
		];
		$this->data['phone'] = [
			'name'  => 'phone',
			'id'    => 'phone',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('phone', $user->phone),
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

		// $this->_render_page('auth/edit_user', $this->data);
		return view("auth/form_edit_administrator", $this->data);
	}

	public function delete_user($id)
	{
		$current = $this->db->query("SELECT * FROM users
		JOIN users_groups ON users_groups.user_id = users.id WHERE user_id = $id")->row();

		if ($current->group_id == 2) {
			$cek_spv = $this->db->get("supervisor_$current->user_identity");

			if ($cek_spv->num_rows > 0) {
				$spv = $this->db->query("SELECT GROUP_CONCAT(id_user) AS id_user FROM supervisor_$current->user_identity")->row();

				$this->db->where_in('id', $spv->id_user);
				$this->db->delete('users');
			}

			$this->load->dbforge();
			$this->dbforge->drop_table('division_' . $current->user_identity, TRUE);
			$this->dbforge->drop_table('supervisor_' . $current->user_identity, TRUE);
		}
		// $this->ion_auth->delete_user($id);
		// redirect('auth/index', 'refresh');

		$this->ion_auth->delete_user($id);

		echo json_encode(array("status" => TRUE));
	}


	//PENGGUNA KLIEN INDUK 
	public function pengguna_klien_induk()
	{
		if (!$this->ion_auth->logged_in()) {
			// redirect them to the login page
			redirect('auth/login', 'refresh');
		}

		$this->data = [];
		$this->data['title'] = "Pengguna Klien Induk";
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');


		$this->data['users'] = $this->db->query("SELECT *, (SELECT description FROM groups WHERE groups.id = group_id) AS name_groups
												FROM users
												JOIN users_groups ON users.id = users_groups.user_id
												WHERE group_id = 6");

		return view('auth/list_klien_induk', $this->data);
	}



	public function create_klien_induk()
	{
		$this->data = [];
		$this->data['title'] = "Create Klien Induk";

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth', 'refresh');
		}

		$tables = $this->config->item('tables', 'ion_auth');
		$identity_column = $this->config->item('identity', 'ion_auth');
		$this->data['identity_column'] = $identity_column;

		// $this->data['kelompok_skala'] = $this->db->get('kelompok_skala');

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


		$this->data['first_name'] = [
			'name' => 'first_name',
			'id' => 'first_name',
			'type' => 'text',
			'value' => $this->form_validation->set_value('first_name'),
			'class' => 'form-control',
			'required' => 'required',
		];
		$this->data['last_name'] = [
			'name' => 'last_name',
			'id' => 'last_name',
			'type' => 'text',
			'value' => $this->form_validation->set_value('last_name'),
			'class' => 'form-control',
			'required' => 'required',
		];
		$this->data['identity'] = [
			'name' => 'identity',
			'id' => 'identity',
			'type' => 'text',
			'value' => $this->form_validation->set_value('identity'),
			'class' => 'form-control',
			'required' => 'required',
		];
		$this->data['email'] = [
			'name' => 'email',
			'id' => 'email',
			'type' => 'text',
			'value' => $this->form_validation->set_value('email'),
			'class' => 'form-control',
			'required' => 'required',
		];
		$this->data['company'] = [
			'name' => 'company',
			'id' => 'company',
			'type' => 'text',
			'value' => $this->form_validation->set_value('company'),
			'class' => 'form-control',
			'required' => 'required',
		];
		$this->data['phone'] = [
			'name' => 'phone',
			'id' => 'phone',
			'type' => 'text',
			'value' => $this->form_validation->set_value('phone'),
			'class' => 'form-control',
			'required' => 'required',
		];
		$this->data['password'] = [
			'name' => 'password',
			'id' => 'password',
			'type' => 'password',
			'value' => $this->form_validation->set_value('password'),
			'class' => 'form-control',
			'required' => 'required',
			'data-toggle' => 'password'
		];
		$this->data['password_confirm'] = [
			'name' => 'password_confirm',
			'id' => 'password_confirm',
			'type' => 'password',
			'value' => $this->form_validation->set_value('password_confirm'),
			'class' => 'form-control',
			'required' => 'required',
		];


		if ($this->form_validation->run() === FALSE) {

			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			return view("auth/form_create_klien_induk", $this->data);
		} else {

			$email = strtolower($this->input->post('email'));
			$identity = ($identity_column === 'email') ? $email : $this->input->post('identity');
			$password = $this->input->post('password');

			$generator = new StrGen\Generator();
			$first = $generator->length(5)->charset([StrGen\CharSet::MIXED_ALPHA, StrGen\CharSet::NUMERIC])->generate();
			$last = $generator->length(15)->charset([StrGen\CharSet::MIXED_ALPHA, StrGen\CharSet::NUMERIC])->generate();
			$app_id = $first . '-' . $last;

			$this->load->library('uuid');
			$additional_data = [
				'first_name' => $this->input->post('first_name'),
				'last_name' => $this->input->post('last_name'),
				'company' => $this->input->post('company'),
				'phone' => $this->input->post('phone'),
				'app_id' => $app_id,
				'uuid' => $this->uuid->v4(),
			];

			if (isset($_POST['is_trial'])) {
				$additional_data['is_trial'] = $this->input->post('is_trial');
			} else {
				$additional_data['is_trial'] = 2;
			}

			$group = array(6);

			$this->ion_auth->register($identity, $password, $email, $additional_data, $group);
			// $insert_id = $this->db->insert_id();

			
			$user_id = $this->db->query("SELECT * FROM users WHERE username = '$identity'")->row()->id;
			$cakupan = implode(", ", $this->input->post('cakupan'));
			$this->db->query("UPDATE users SET id_parent_induk = $user_id WHERE id IN ($cakupan)");


			// 

			// $object = [
			// 	'id_user' => $user_id,
			// 	'cakupan_induk' => serialize($this->input->post('cakupan')),
			// ];
			// $this->db->insert('pengguna_klien_induk', $object);



			$this->session->set_flashdata('message', $this->ion_auth->messages());
			redirect(base_url() . "pengguna-klien-induk", 'refresh');
		}
	}


	public function edit_klien_induk($id)
	{
		$this->data['title'] = 'Edit Klien Induk';

		if (!$this->ion_auth->logged_in() || (!$this->ion_auth->is_admin() && !($this->ion_auth->user()->row()->id == $id))) {
			redirect('auth', 'refresh');
		}

		$user = $this->ion_auth->user($id)->row();
		$this->data['data_anak'] = [];
		foreach($this->db->get_where('users', array('id_parent_induk' => $id))->result() as $row){
			$this->data['data_anak'][] = $row->id;
		}

		// $induk = $this->db->get_where('pengguna_klien_induk', array('id_user' => $id))->row();
		// $this->data['survei_checked'] = unserialize($induk->cakupan_induk);


		$this->form_validation->set_rules('first_name', $this->lang->line('edit_user_validation_fname_label'), 'trim|required');
		$this->form_validation->set_rules('last_name', $this->lang->line('edit_user_validation_lname_label'), 'trim|required');
		$this->form_validation->set_rules('phone', $this->lang->line('edit_user_validation_phone_label'), 'trim');
		$this->form_validation->set_rules('company', $this->lang->line('edit_user_validation_company_label'), 'trim');



		if ($this->form_validation->run() === FALSE) {

			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			// pass the user to the view
			$this->data['user'] = $user;

			$this->data['first_name'] = [
				'name'  => 'first_name',
				'id'    => 'first_name',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('first_name', $user->first_name),
				'class' => 'form-control',
			];
			$this->data['last_name'] = [
				'name'  => 'last_name',
				'id'    => 'last_name',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('last_name', $user->last_name),
				'class' => 'form-control',
			];
			$this->data['company'] = [
				'name'  => 'company',
				'id'    => 'company',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('company', $user->company),
				'class' => 'form-control',
			];
			$this->data['phone'] = [
				'name'  => 'phone',
				'id'    => 'phone',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('phone', $user->phone),
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


			return view("auth/form_edit_klien_induk", $this->data);
		} else {
			$data = [
				'first_name' => $this->input->post('first_name'),
				'last_name' => $this->input->post('last_name'),
				'company' => $this->input->post('company'),
				'phone' => $this->input->post('phone')
			];

			// update the password if it was posted
			if ($this->input->post('password')) {
				$data['password'] = $this->input->post('password');
			}
			$this->ion_auth->update($user->id, $data);



				$data_anak = implode(", ", $this->data['data_anak']);
				$this->db->query("UPDATE users SET id_parent_induk = 0 WHERE id IN ($data_anak)");
	
	
				$cakupan = implode(", ", $this->input->post('cakupan'));
				$this->db->query("UPDATE users SET id_parent_induk = $id WHERE id IN ($cakupan)");

			

			// $obj = [
			// 	'cakupan_induk' => serialize($this->input->post('cakupan'))
			// ];
			// // var_dump($obj);
			// $this->db->where('id_user', $id);
			// $this->db->update('pengguna_klien_induk', $obj);


			$this->session->set_flashdata('message', $this->ion_auth->messages());
			redirect(base_url() . "pengguna-klien-induk", 'refresh');
		}
	}


	public function delete_klien_induk($id)
	{
		$this->ion_auth->delete_user($id);
		// $this->db->delete('pengguna_klien_induk', array('id_user' => $id));

		redirect(base_url() . 'pengguna-klien-induk', 'refresh');
	}

	

	public function delete_surveyor($id)
	{
		$this->ion_auth->delete_user($id);

		$this->db->where('id_user', $id);
		$this->db->delete('surveyor_induk');

		// delete data pada tabel surveyor
		$this->db->where('id_user', $id);
		$this->db->delete('surveyor');

		redirect(base_url() . 'pengguna-surveyor', 'refresh');
	}

	//PENGGUNA KLIEN
	public function pengguna_klien()
	{
		if (!$this->ion_auth->logged_in()) {
			// redirect them to the login page
			redirect('auth/login', 'refresh');
		}

		$this->data = [];
		$this->data['title'] = "Pengguna Klien";
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

		return view('auth/list_klien', $this->data);
	}

	public function ajax_list_klien()
	{
		$this->load->model('AuthKlien_model');

		$list = $this->AuthKlien_model->get_datatables();
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			if ($value->foto_profile == NULL) {
				$foto_profil = '<span class="symbol-label font-size-h5 font-weight-bold">' . substr($value->first_name, 0, 1) . ' ' . substr($value->last_name, 0, 1) . '</span>';
			} else {
				$foto_profil = '<div class="symbol-label">
									<img class="h-100 align-self-end" src="' . base_url() . 'assets/klien/foto_profile/' . $value->foto_profile . '" alt="photo" style="border-radius: 5px;">
								</div>';
			}

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = '<span style="width: 250px;">
						<div class="d-flex align-items-center">
							<div class="symbol symbol-50 symbol-sm flex-shrink-0 symbol-light">
								'  . $foto_profil . '
							</div>
							<div class="ml-4">
								<div class="text-dark-75 font-weight-bolder font-size-lg mb-0">' . $value->first_name . ' ' . $value->last_name . '
								</div>
								<span class="text-muted font-weight-bold text-hover-primary">' .  $value->email . '</span>
							</div>
						</div>
					</span>';
			// $row[] = $value->last_name;
			// $row[] = $value->email;
			$row[] = '<span class="text-muted font-weight-bold text-hover-primary">' .  $value->nama_klasifikasi_survei . '</span>';
			$row[] = '<span class="text-muted font-weight-bold text-hover-primary">' .  date("H:i", strtotime($value->last_login)) . '<br>' . date("d-m-Y", strtotime($value->last_login)) . '</span>';



			$row[] = ($value->active) ? '<a class="label label-lg font-weight-bold label-light-primary label-inline" href="' . base_url() . 'auth/deactivate/' . $value->user_id . '">' .  lang('index_active_link') . '</a>' : '<a class="label label-lg font-weight-bold label-light-danger label-inline" href="' . base_url() . 'auth/activate/' . $value->user_id . '">' .  lang('index_inactive_link') . '</a>';

			$row[] = '<a class="btn btn-sm btn-default btn-text-primary btn-hover-primary" data-toggle="modal" title="Detail Klien" onclick="showuserdetail(' . $value->user_id . ')" href="#modal_userDetail"><i class="fa fa-info-circle"></i></a>';

			$row[] = '<a class="btn btn-sm btn-default btn-text-primary btn-hover-primary" title="Detail Berlangganan" href="' . base_url() . 'berlangganan/data-langganan/' . $value->user_id . '"><i class="fas fa-key"></i></a>';

			$row[] = anchor("auth/edit_klien/" . $value->user_id, '<i class="fas fa-edit"></i>', ['class' => 'btn btn-sm btn-default btn-text-primary btn-hover-primary']);

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->AuthKlien_model->count_all(),
			"recordsFiltered" => $this->AuthKlien_model->count_filtered(),
			"data" => $data,
		);

		echo json_encode($output);
	}

	public function get_send_email()
	{
		if ($this->input->post('is_email') == 1) {

			$id_klien = $_SESSION['input']['id_berlangganan'];

			$this->db->select('*');
			$this->db->from('users');
			$this->db->join('berlangganan', 'berlangganan.id_user = users.id');
			$this->db->join('paket', 'paket.id = berlangganan.id_paket');
			$this->db->join('metode_pembayaran', 'metode_pembayaran.id = berlangganan.id_metode_pembayaran');
			$this->db->join('status_berlangganan', 'status_berlangganan.id = berlangganan.id_status_berlangganan');
			$this->db->join('klasifikasi_survei', 'klasifikasi_survei.id = users.id_klasifikasi_survei');
			$this->db->where('berlangganan.id_user', $id_klien);
			$data_klien = $this->db->get()->last_row();

			$settings = $this->db->query("
							SELECT
							( SELECT setting_value FROM web_settings WHERE alias = 'akun_email') AS email_akun,
							( SELECT setting_value FROM web_settings WHERE alias = 'email_pengirim') AS email_pengirim,
							( SELECT setting_value FROM web_settings WHERE alias = 'email_username') AS email_username,
							( SELECT setting_value FROM web_settings WHERE alias = 'email_password') AS email_password,
							( SELECT setting_value FROM web_settings WHERE alias = 'email_host') AS email_host,
							( SELECT setting_value FROM web_settings WHERE alias = 'email_port') AS email_port,
							( SELECT setting_value FROM web_settings WHERE alias = 'email_cc') AS email_cc,
							( SELECT setting_value FROM web_settings WHERE alias = 'email_bcc') AS email_bcc
							FROM
							web_settings LIMIT 1
						")->row();


			$this->load->library('email');

			$ci = get_instance();
			$config['protocol']     = "smtp";
			$config['smtp_host']    = $settings->email_host;
			$config['smtp_port']    = $settings->email_port;
			$config['smtp_user']    = $settings->email_username;
			$config['smtp_pass']    = $settings->email_password;
			$config['charset']      = "utf-8";
			$config['mailtype']     = "html";
			$config['newline']      = "\r\n";

			$html = '';
			$html .= '

<table width="100%" border="0" cellpadding="4" cellspacing="0">
  <tr>
    <td bgcolor="#AE0000" style="font-size: 20px; color: #FFFFFF;"><div align="center"><strong>SISTEM INFORMASI E-SKM</strong></div></td>
  </tr>
  <tr>
    <td style="font-size: 16px;">
<br><br>
<p>Kepada Bapak/ Ibu<br />
Di Tempat</p>
<p>Berikut kami sampaikan akun pengguna dan paket yang anda gunakan pada Aplikasi E-SKM, anda bisa login dengan akun berikut ini:</p>
<table border="1" style="border-collapse: collapse; border-color: #d3d3d3;" cellpadding="4" cellspacing="0">
<tr>
<th>Link Login</th>
<td>' . base_url() . 'auth/login</td>
</tr>
<tr>
<th>Username</th>
<td>' . $data_klien->username . '</td>
</tr>
<tr>
<th>Password</th>
<td>' . $data_klien->re_password . '</td>
</tr>
</table>
<br>
<p>Paket yang anda gunakan</p>
<table border="1" style="border-collapse: collapse; border-color: #d3d3d3;" cellpadding="4" cellspacing="0">
<tr>
<th>Klasifikasi Survey</th>
<td>' . $data_klien->nama_klasifikasi_survei . '</td>
</tr>
<tr>
<th>Nama Paket</th>
<td>' . $data_klien->nama_paket . '</td>
</tr>
<tr>
<th>Panjang Hari</th>
<td>' . $data_klien->panjang_hari . ' hari</td>
</tr>
<tr>
<th>harga Paket</th>
<td>Rp. ' . number_format($data_klien->harga_paket, 2, ',', '.') . '</td>
</tr>
<tr>
<th>Metode Pembayaran</th>
<td>' . $data_klien->nama_metode_pembayaran . '</td>
</tr>
<tr>
<th>Tanggal Aktif</th>
<td>' . date("d-m-Y", strtotime($data_klien->tanggal_mulai)) . '</td>
</tr>
<tr>
<th>Tanggal Kedaluarsa</th>
<td>' . date("d-m-Y", strtotime($data_klien->tanggal_selesai)) . '</td>
</tr>
<tr>
<th>Kode Lisensi</th>
<td>' . $data_klien->kode_lisensi . '</td>
</tr>
</table>
<p>Agar akun anda lebih aman, segera ubah password anda melalui link berikut ini <a href="' . base_url() . 'auth/forgot_password">' . base_url() . 'auth/forgot_password</a></p>
<p>Terima Kasih.</p>
<p><strong><u>Admin E-SKM</u></strong></p>
<br><br>

    </td>
  </tr>
  <tr>
    <td bgcolor="#CCCCCC" style="font-size: 12px;"><div align="center">View as a Web Page<br />
    Sistem Informasi E-SKM<br />
      survei-kepuasan.com
    </div></td>
  </tr>
</table>
					';


			$ci->email->initialize($config);
			$ci->email->from($settings->email_pengirim, 'Auto Reply Sistim Informasi E-SKM');
			$ci->email->to($data_klien->email);

			// if ($settings->email_cc != '') {
			// 	$this->email->cc($settings->email_cc);
			// }

			$ci->email->subject('Akun Berlangganan Survei E-SKM');
			$ci->email->message($html);
			$this->email->send();
		}


		$array = array(
			'success' => '<div class="alert alert-success">Berhasil menambah melakukan konfirmasi terima pembayaran registrasi dari pendaftar</div>'
		);

		echo json_encode($array);
	}

	public function get_detail()
	{
		$id = $this->input->post('id');

		$this->data = [];
		$this->data['id_berlangganan'] = $id;

		$newdata = array(
			'input'  => $this->data
		);

		$this->session->set_userdata($newdata);

		$this->db->select('*');
		$this->db->from('users');
		$this->db->join('berlangganan', 'berlangganan.id_user = users.id');
		$this->db->join('paket', 'paket.id = berlangganan.id_paket');
		$this->db->join('metode_pembayaran', 'metode_pembayaran.id = berlangganan.id_metode_pembayaran');
		$this->db->join('status_berlangganan', 'status_berlangganan.id = berlangganan.id_status_berlangganan');
		$this->db->join('klasifikasi_survei', 'klasifikasi_survei.id = users.id_klasifikasi_survei');
		$this->db->where('berlangganan.id_user', $id);
		$this->data['pelanggan'] = $this->db->get()->last_row();


		return view('auth/detail_berlangganan', $this->data);
	}


	public function create_klien()
	{
		$this->data = [];
		$this->data['title'] = "Create Klien";

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth', 'refresh');
		}

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

			$generator = new StrGen\Generator();
			$first = $generator->length(5)->charset([StrGen\CharSet::MIXED_ALPHA, StrGen\CharSet::NUMERIC])->generate();
			$last = $generator->length(15)->charset([StrGen\CharSet::MIXED_ALPHA, StrGen\CharSet::NUMERIC])->generate();
			$app_id = $first . '-' . $last;

			$this->load->library('uuid');

			$additional_data = [
				'first_name' => $this->input->post('first_name'),
				'last_name' => $this->input->post('last_name'),
				'company' => $this->input->post('company'),
				'phone' => $this->input->post('phone'),
				'id_klasifikasi_survei' => $this->input->post('id_klasifikasi_survei'),
				// 'is_reseller' => $this->input->post('is_reseller'),

				'id_paket' 	=> $this->input->post('id_paket'),
				'id_paket_trial' => $this->input->post('id_paket_trial'),
				'id_metode_pembayaran' 	=> $this->input->post('id_metode_pembayaran'),
				'id_status_berlangganan' => 1,
				'tanggal_mulai' 	=> $this->input->post('tanggal_mulai'),
				'tanggal_selesai' 	=> null,
				'kode_lisensi' 	=> null,
				'app_id' => $app_id,

				'uuid' => $this->uuid->v4(),
			];

			$group = array('2');
		}

		if (isset($_POST['is_reseller'])) {
			$additional_data['is_reseller'] = $this->input->post('is_reseller');
		} else {
			$additional_data['is_reseller'] = NULL;
		}

		if (isset($_POST['is_trial'])) {
			$additional_data['is_trial'] = $this->input->post('is_trial');
		} else {
			$additional_data['is_trial'] = 2;
		}

		if ($this->form_validation->run() === TRUE && $this->ion_auth->register($identity, $password, $email, $additional_data, $group)) {

			$cek_user = $this->db->get_where("users", array('username' => $identity))->row();
			$this->db->query("UPDATE users SET user_identity = 'drs$cek_user->id' WHERE id = $cek_user->id");

			$tb_division = 'division_drs' . $cek_user->id;
			$tb_supervisor = 'supervisor_drs' . $cek_user->id;

			$this->db->query("CREATE TABLE $tb_supervisor LIKE supervisor");
			$this->db->query("CREATE TABLE $tb_division LIKE division");
			$this->db->query("INSERT INTO $tb_division SELECT * FROM division;");

			$this->session->set_flashdata('message', $this->ion_auth->messages());
			redirect(base_url() . "pengguna-klien", 'refresh');
		} else {

			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			$this->data['first_name'] = [
				'name' => 'first_name',
				'id' => 'first_name',
				'type' => 'text',
				'value' => $this->form_validation->set_value('first_name'),
				'class' => 'form-control',
				'required' => 'required',
			];
			$this->data['last_name'] = [
				'name' => 'last_name',
				'id' => 'last_name',
				'type' => 'text',
				'value' => $this->form_validation->set_value('last_name'),
				'class' => 'form-control',
				'required' => 'required',
			];
			$this->data['identity'] = [
				'name' => 'identity',
				'id' => 'identity',
				'type' => 'text',
				'value' => $this->form_validation->set_value('identity'),
				'class' => 'form-control',
				'required' => 'required',
			];
			$this->data['email'] = [
				'name' => 'email',
				'id' => 'email',
				'type' => 'text',
				'value' => $this->form_validation->set_value('email'),
				'class' => 'form-control',
				'required' => 'required',
			];
			$this->data['company'] = [
				'name' => 'company',
				'id' => 'company',
				'type' => 'text',
				'value' => $this->form_validation->set_value('company'),
				'class' => 'form-control',
				'required' => 'required',
			];
			$this->data['phone'] = [
				'name' => 'phone',
				'id' => 'phone',
				'type' => 'text',
				'value' => $this->form_validation->set_value('phone'),
				'class' => 'form-control',
				'required' => 'required',
			];
			$this->data['password'] = [
				'name' => 'password',
				'id' => 'password',
				'type' => 'password',
				'value' => $this->form_validation->set_value('password'),
				'class' => 'form-control',
				'required' => 'required',
				'data-toggle' => 'password'
			];
			$this->data['password_confirm'] = [
				'name' => 'password_confirm',
				'id' => 'password_confirm',
				'type' => 'password',
				'value' => $this->form_validation->set_value('password_confirm'),
				'class' => 'form-control',
				'required' => 'required',
			];

			$this->load->model('AuthKlien_model');

			$this->data['id_klasifikasi_survei'] = [
				'name' 		=> 'id_klasifikasi_survei',
				'id' 		=> 'id_klasifikasi_survei',
				'options' 	=> $this->AuthKlien_model->dropdown_klasifikasi_survei(),
				'selected' 	=> $this->form_validation->set_value('id_klasifikasi_survei'),
				'class' 	=> "form-control",
				'required' => 'required',
			];

			$this->data['id_paket_trial'] = [
				'name' 		=> 'id_paket_trial',
				'id' 		=> 'id_paket_trial',
				'options' 	=> $this->AuthKlien_model->dropdown_paket_trial(),
				'selected' 	=> $this->form_validation->set_value('id_paket_trial'),
				'class' 	=> "form-control",
				// 'required' => 'required',
			];

			$this->data['id_paket'] = [
				'name' 		=> 'id_paket',
				'id' 		=> 'id_paket',
				'options' 	=> $this->AuthKlien_model->dropdown_paket(),
				'selected' 	=> $this->form_validation->set_value('id_paket'),
				'class' 	=> "form-control",
				// 'required' => 'required',
			];

			$this->data['id_metode_pembayaran'] = [
				'name' 		=> 'id_metode_pembayaran',
				'id' 		=> 'id_metode_pembayaran',
				'options' 	=> $this->AuthKlien_model->dropdown_metode_pembayaran(),
				'selected' 	=> $this->form_validation->set_value('id_metode_pembayaran'),
				'class' 	=> "form-control",
				// 'required' => 'required',
			];

			$this->data['tanggal_mulai'] = [
				'name' 		=> 'tanggal_mulai',
				'id'		=> 'datepicker',
				// 'type'		=> 'text',
				'type'		=> 'date',
				'value'		=>	$this->form_validation->set_value('tanggal_mulai', date('Y-m-d')),
				'class'		=> 'form-control',
				'style'		=> 'width: 250px;',
				// 'required' => 'required',
			];

			$this->data['is_reseller'] = [
				'name' 		=> 'is_reseller',
				'id' 		=> 'is_reseller',
				'options' 	=> $this->AuthKlien_model->dropdown_reseller(),
				'selected' 	=> $this->form_validation->set_value('is_reseller'),
				'class' 	=> "form-control",
				'style' => 'display:none'
			];


			return view("auth/form_create_klien", $this->data);
		}
	}

	public function generate_password()
	{
		$this->data = [];
		$this->data['password'] = $this->random_string(8);

		return view('auth/form_generate_password', $this->data);
	}

	public function generate_password_form()
	{
		$random = $this->random_string(8);
		echo $random;
	}

	public function random_string($long_string)
	{
		$karakter = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz123456789~@#$%^&*()_+-=<,>.?';
		$string = '';
		for ($i = 0; $i < $long_string; $i++) {
			$pos = rand(0, strlen($karakter) - 1);
			// $string .= $karakter{$pos};
			$string .= $karakter[$pos]; // Dipakai di PHP 7.4
		}
		return $string;
	}

	public function delete_klien($id)
	{
		$this->ion_auth->delete_user($id);
		redirect(base_url() . 'pengguna-klien', 'refresh');
	}

	public function edit_klien($id)
	{
		$this->data['title'] = 'Edit Klien';

		if (!$this->ion_auth->logged_in() || (!$this->ion_auth->is_admin() && !($this->ion_auth->user()->row()->id == $id))) {
			redirect('auth', 'refresh');
		}

		$user = $this->ion_auth->user($id)->row();

		//USAGE NOTE - you can do more complicated queries like this
		//$groups = $this->ion_auth->where(['field' => 'value'])->groups()->result_array();


		// validate form input
		$this->form_validation->set_rules('first_name', $this->lang->line('edit_user_validation_fname_label'), 'trim|required');
		$this->form_validation->set_rules('last_name', $this->lang->line('edit_user_validation_lname_label'), 'trim|required');
		$this->form_validation->set_rules('phone', $this->lang->line('edit_user_validation_phone_label'), 'trim');
		$this->form_validation->set_rules('company', $this->lang->line('edit_user_validation_company_label'), 'trim');

		if (isset($_POST) && !empty($_POST)) {
			// do we have a valid request?
			if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id')) {
				show_error($this->lang->line('error_csrf'));
			}

			// update the password if it was posted
			if ($this->input->post('password')) {
				$this->form_validation->set_rules('password', $this->lang->line('edit_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|matches[password_confirm]');
				$this->form_validation->set_rules('password_confirm', $this->lang->line('edit_user_validation_password_confirm_label'), 'required');
			}

			// if ($this->input->post('cek_res') == 1) {
			// 	$is_reseller = $this->input->post('is_reseller');
			// } else {
			// 	$is_reseller = NULL;
			// }


			if ($this->form_validation->run() === TRUE) {


				$data = [
					'first_name' => $this->input->post('first_name'),
					'last_name' => $this->input->post('last_name'),
					'company' => $this->input->post('company'),
					'phone' => $this->input->post('phone'),
					'id_klasifikasi_survei' => $this->input->post('id_klasifikasi_survei'),

					'id_paket' 	=> $this->input->post('id_paket'),
					'id_metode_pembayaran' 	=> $this->input->post('id_metode_pembayaran'),
					'id_status_berlangganan' => 1,
					'tanggal_mulai' 	=> $this->input->post('tanggal_mulai'),
					'tanggal_selesai' 	=> null,
					'kode_lisensi' 	=> null,
					// 'is_reseller' => $is_reseller
				];

				// update the password if it was posted
				if ($this->input->post('password')) {
					$data['password'] = $this->input->post('password');
				}

				// check to see if we are updating the user
				if ($this->ion_auth->update($user->id, $data)) {
					// redirect them back to the admin page if admin, or to the base url if non admin
					$this->session->set_flashdata('message', $this->ion_auth->messages());
					redirect(base_url() . 'pengguna-klien', 'refresh');
				} else {
					// redirect them back to the admin page if admin, or to the base url if non admin
					$this->session->set_flashdata('message', $this->ion_auth->errors());
					redirect(base_url() . 'pengguna-klien', 'refresh');
				}
			}
		}

		// display the edit user form
		$this->data['csrf'] = $this->_get_csrf_nonce();

		// set the flash data error message if there is one
		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		// pass the user to the view
		$this->data['user'] = $user;

		$this->data['first_name'] = [
			'name'  => 'first_name',
			'id'    => 'first_name',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('first_name', $user->first_name),
			'class' => 'form-control',
			'required' => 'required',
		];
		$this->data['last_name'] = [
			'name'  => 'last_name',
			'id'    => 'last_name',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('last_name', $user->last_name),
			'class' => 'form-control',
			'required' => 'required',
		];
		$this->data['company'] = [
			'name'  => 'company',
			'id'    => 'company',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('company', $user->company),
			'class' => 'form-control',
			'required' => 'required',
		];
		$this->data['phone'] = [
			'name'  => 'phone',
			'id'    => 'phone',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('phone', $user->phone),
			'class' => 'form-control',
			'required' => 'required',
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

		$this->load->model('AuthKlien_model');

		$this->data['id_klasifikasi_survei'] = [
			'name' 		=> 'id_klasifikasi_survei',
			'id' 		=> 'id_klasifikasi_survei',
			'options' 	=> $this->AuthKlien_model->dropdown_klasifikasi_survei(),
			'selected' 	=> $this->form_validation->set_value('id_klasifikasi_survei', $user->id_klasifikasi_survei),
			'class' 	=> "form-control",
			'required' => 'required',
		];

		// Cek berlangganan
		$data_langganan = $this->db->get_where('berlangganan', ['id_user' => $id])->row();

		$this->data['id_paket'] = [
			'name' 		=> 'id_paket',
			'id' 		=> 'id_paket',
			'options' 	=> $this->AuthKlien_model->dropdown_paket_berlangganan_trial(),
			'selected' 	=> $this->form_validation->set_value('id_paket', $data_langganan->id_paket),
			'class' 	=> "form-control",
			'required' => 'required',
		];

		$this->data['id_metode_pembayaran'] = [
			'name' 		=> 'id_metode_pembayaran',
			'id' 		=> 'id_metode_pembayaran',
			'options' 	=> $this->AuthKlien_model->dropdown_metode_pembayaran(),
			'selected' 	=> $this->form_validation->set_value('id_metode_pembayaran', $data_langganan->id_metode_pembayaran),
			'class' 	=> "form-control",
			'required' => 'required',
		];

		$this->data['tanggal_mulai'] = [
			'name' 		=> 'tanggal_mulai',
			'id'		=> 'tanggal_mulai',
			'type'		=> 'date',
			'value'		=>	$this->form_validation->set_value('tanggal_mulai', $data_langganan->tanggal_mulai),
			'class'		=> 'form-control',
			'style'		=> 'width: 250px;',
			'required' => 'required',
		];

		// $this->_render_page('auth/edit_user', $this->data);
		return view("auth/form_edit_klien", $this->data);
	}


	//PENGGUNA SURVEYOR
	public function pengguna_surveyor()
	{
		if (!$this->ion_auth->logged_in()) {
			// redirect them to the login page
			redirect('auth/login', 'refresh');
		}

		$this->data = [];
		$this->data['title'] = "Pengguna Surveyor";
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

		return view('auth/list_surveyor', $this->data);
	}

	public function ajax_list_surveyor()
	{
		$this->load->model('AuthSurveyor_model');

		$list = $this->AuthSurveyor_model->get_datatables();
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $value->first_name;
			$row[] = $value->last_name;
			$row[] = $value->email;
			// $row[] = anchor("auth/edit_group/" . $value->group_id, htmlspecialchars($value->name, ENT_QUOTES, 'UTF-8'));
			// $row[] = ($value->active) ? anchor("auth/deactivate/" . $value->user_id, lang('index_active_link')) : anchor("auth/activate/" . $value->user_id, lang('index_inactive_link'));
			// $row[] = anchor("auth/edit_user/" . $value->user_id, 'Edit', ['class' => 'btn btn-secondary btn-sm font-weight-bold']);
			$row[] = anchor("pengguna-surveyor/edit-surveyor/" . $value->user_id, 'Edit', ['class' => 'btn btn-secondary btn-sm font-weight-bold']);
			$row[] = anchor("pengguna-surveyor/delete-user/" . $value->user_id, 'Delete', ['class' => 'btn btn-secondary font-weight-bold', 'onclick' => "return confirm('Anda yakin ingin menghapus pengguna ?')"]);

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->AuthSurveyor_model->count_all(),
			"recordsFiltered" => $this->AuthSurveyor_model->count_filtered(),
			"data" => $data,
		);

		echo json_encode($output);
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

	public function add_list_surveyor()
	{
		$this->data = [];
		$this->data['title'] = "Tambah Pengguna Surveyor";

		$user = $this->ion_auth->user()->row();
		// $user_identity = $user->user_identity;
		//$kode_surveyor = $this->_kode_surveyor();
		$this->data['data_user'] = $this->ion_auth->user()->row();

		$tables = $this->config->item('tables', 'ion_auth');
		$identity_column = $this->config->item('identity', 'ion_auth');
		$this->data['identity_column'] = $identity_column;

		$this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
		$this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');

		if ($identity_column !== 'email') {
			//$this->form_validation->set_rules('identity', 'Username', 'trim|required|is_unique[' . $tables['users'] . '.' . $identity_column . ']');
			$this->form_validation->set_rules('identity', 'Username', 'trim|required|is_unique[users.' . $identity_column . ']');
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		} else {
			//$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[' . $tables['users'] . '.email]');
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[users.email]');
		}

		$this->form_validation->set_rules('phone', 'Phone', 'trim');
		$this->form_validation->set_rules('company', 'Company', 'trim');
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|matches[password_confirm]');
		$this->form_validation->set_rules('password_confirm', 'Password Confirm', 'required');

		if ($this->form_validation->run() === TRUE) {
			$email = strtolower($this->input->post('email'));
			$identity = ($identity_column === 'email') ? $email : $this->input->post('identity');
			$password = $this->input->post('password');

			$additional_data = [
				'first_name' => $this->input->post('first_name'),
				'last_name' => $this->input->post('last_name'),
				'company' => $this->input->post('company'),
				'phone' => $this->input->post('phone'),
				're_password' => $password,
				// 'id_klasifikasi_survei' =>  $user->id_klasifikasi_survei,
				'is_parent' => $user->id,
				'is_surveyor' => 1,
				'is_trial' => ''
			];
			$group = array('7');
		}
		if ($this->form_validation->run() === TRUE && $this->ion_auth->register($identity, $password, $email, $additional_data, $group)) {

			$this->load->library('uuid');
			$id_users = $this->db->get_where('users', array('username' =>  $this->input->post('identity')))->row()->id;

			$object = [
				'uuid' => $this->uuid->v4(),
				'id_user' => $id_users,
				'kode_surveyor' => $this->input->post('kode_surveyor')
			];
			// $this->db->insert('surveyor', $object);

			// survei
			// $id_surveyor = $this->db->get_where('surveyor', array('kode_surveyor' =>  $this->input->post('kode_surveyor')))->row()->id;
			$id_manage_survey = $this->input->post('id_manage_survey');
			if(isset($id_manage_survey)){
				for($i = 0; $i < sizeof($id_manage_survey); $i++){
					if($id_manage_survey[$i]){
						$this->db->insert('surveyor_induk', array(
							'id_user' => $id_users,
							'id_manage_survey' => $id_manage_survey[$i],
							'uuid' => $this->uuid->v4(),
						));
					}
				}
			}

			$this->session->set_flashdata('message_success', 'Berhasil menambah data');
			redirect(base_url() . '/pengguna-surveyor', 'refresh');
		} else {

			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			$this->data['first_name'] = [
				'name' => 'first_name',
				'id' => 'first_name',
				'type' => 'text',
				'value' => $this->form_validation->set_value('first_name'),
				'class' => 'form-control',
				'autofocus' => 'autofocus',
				'required' => 'required'
			];
			$this->data['last_name'] = [
				'name' => 'last_name',
				'id' => 'last_name',
				'type' => 'text',
				'value' => $this->form_validation->set_value('last_name'),
				'class' => 'form-control',
				'required' => 'required'
			];
			$this->data['identity'] = [
				'name' => 'identity',
				'id' => 'identity',
				'type' => 'text',
				'value' => $this->form_validation->set_value('identity'),
				'class' => 'form-control',
				'required' => 'required'
			];
			/*$this->data['kode_surveyor'] = [
				'name' => 'kode_surveyor',
				'id' => 'kode_surveyor',
				'type' => 'text',
				'value' => $kode_surveyor,
				'class' => 'form-control font-weight-bold',
				'readonly' => 'readonly',
				'style' => 'background-color:#f2f2f2;'
			];*/

			$this->data['email'] = [
				'name' => 'email',
				'id' => 'email',
				'type' => 'text',
				'value' => $this->form_validation->set_value('email'),
				'class' => 'form-control',
				'required' => 'required'
			];
			$this->data['company'] = [
				'name' => 'company',
				'id' => 'company',
				'type' => 'text',
				'value' => $this->form_validation->set_value('company'),
				'class' => 'form-control',
				'required' => 'required'
			];

			$this->data['phone'] = [
				'name' => 'phone',
				'id' => 'phone',
				'type' => 'number',
				'value' => $this->form_validation->set_value('phone'),
				'class' => 'form-control',
				'required' => 'required'
			];
			$this->data['password'] = [
				'name' => 'password',
				'id' => 'password',
				'type' => 'password',
				'value' => $this->form_validation->set_value('password'),
				'class' => 'form-control',
				'required' => 'required'
			];
			$this->data['password_confirm'] = [
				'name' => 'password_confirm',
				'id' => 'password_confirm',
				'type' => 'password',
				'value' => $this->form_validation->set_value('password_confirm'),
				'class' => 'form-control',
				'required' => 'required'
			];

			return view("auth/form_create_surveyor", $this->data);
		}
	}

	public function edit_list_surveyor()
	{
		$this->data = [];
		$this->data['title'] = "Edit Pengguna Surveyor";

		// $user = $this->ion_auth->user()->row();
		// $user_identity = $user->user_identity;

		$this->db->select('*');
		// $this->db->from('surveyor');
		// $this->db->join('users', "surveyor.id_user = users.id");
		// $this->db->where("surveyor.id_user", $this->uri->segment(3));
		$this->db->from('users');
		$this->db->where("id", $this->uri->segment(3));
		$current = $this->db->get()->row();

		$this->data['data_user'] = $this->ion_auth->user()->row();

		$this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
		$this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
		$this->form_validation->set_rules('phone', 'Phone', 'trim');

		if (isset($_POST) && !empty($_POST)) {

			if ($this->input->post('password')) {
				$this->form_validation->set_rules('password', 'Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|matches[password_confirm]');
				$this->form_validation->set_rules('password_confirm', 'Confirm Password', 'required');
			}

			if ($this->form_validation->run() === TRUE) {
				$data = [
					'first_name' => $this->input->post('first_name'),
					'last_name' => $this->input->post('last_name'),
					'phone' => $this->input->post('phone'),
					'company' => $this->input->post('company'),
					'email' => $this->input->post('email'),
				];
				if ($this->input->post('password')) {
					$data['password'] = $this->input->post('password');
				}
				$this->db->where('id', $this->uri->segment(3));
				$this->db->update('users', $data);

				$this->load->library('uuid');

				// survei
				//$id_surveyor = $this->db->get_where('surveyor', array('id_user' =>  $this->uri->segment(3)))->row()->id;
				$id_manage_survey = $this->input->post('id_manage_survey');
				if(isset($id_manage_survey)){
					$this->db->where('id_user', $this->uri->segment(3));
					$this->db->delete('surveyor_induk');
					for($i = 0; $i < sizeof($id_manage_survey); $i++){
						if($id_manage_survey[$i]){
							$this->db->insert('surveyor_induk', array(
								'id_user' => $this->uri->segment(3),
								'id_manage_survey' => $id_manage_survey[$i],
								'uuid' => $this->uuid->v4(),
							));
						}
					}
				}

				$this->session->set_flashdata('message_success', 'Berhasil mengubah data');
				redirect(base_url() . '/pengguna-surveyor', 'refresh');
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
		/*$this->data['kode_surveyor'] = [
			'name' => 'kode_surveyor',
			'id' => 'kode_surveyor',
			'type' => 'text',
			'value' => $this->form_validation->set_value('kode_surveyor', $current->kode_surveyor),
			'class' => 'form-control font-weight-bold',
			'readonly' => 'readonly',
			'style' => 'background-color:#f2f2f2;'
		];*/
		$this->data['email'] = [
			'name'  => 'email',
			'id'    => 'email',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('email', $current->email),
			'class' => 'form-control',
		];
		$this->data['company'] = [
			'name'  => 'company',
			'id'    => 'company',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('company', $current->company),
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

		return view("auth/form_edit_surveyor", $this->data);
	}

	public function _redirection()
	{
		if ($this->ion_auth->is_admin()) // remove this elseif if you want to enable this for non-admins
		{
			redirect('dashboard', 'refresh');
		} else if ($this->ion_auth->in_group('client')) {
			redirect('dashboard', 'refresh');
		} else if ($this->ion_auth->in_group('surveyor')) {
			redirect('dashboard', 'refresh');
		} else if ($this->ion_auth->in_group('reseller')) {
			redirect('dashboard', 'refresh');
		} else if ($this->ion_auth->in_group('supervisor')) {
			redirect('dashboard', 'refresh');
		} else if ($this->ion_auth->in_group('client_induk')) {
			redirect('dashboard', 'refresh');
		} else if ($this->ion_auth->in_group('surveyor_induk')) {
			redirect('dashboard', 'refresh');
		} else {
			redirect('auth/login', 'refresh');
		}
	}

	public function _check_subscription($group_id, $user_id_parent)
	{
		$this->db->select('*, berlangganan.id AS id_berlangganan');
		$this->db->from('berlangganan');
		$this->db->join('users', 'users.id = berlangganan.id_user');

		if ($group_id == 2) {
			$this->db->where('users.username', $this->input->post('identity'));
		} else {
			$this->db->where('users.id', $user_id_parent);
		}
		$this->db->order_by('berlangganan.id', 'asc');

		$last_payment = $this->db->get()->last_row();

		$now = Carbon::now();
		$start_date = Carbon::parse($last_payment->tanggal_mulai);
		$end_date = Carbon::parse($last_payment->tanggal_selesai);
		if ($now->between($start_date, $end_date)) {
			// return 'Paket berakhir dalam '.$due_date.' hari lagi';
			return TRUE;
		} else {
			// return 'Packet is Expired';
			return FALSE;
		}
	}

	/**
	 * Log the user in
	 */
	public function login()
	{
		$this->data['title'] = $this->lang->line('login_heading');

		// validate form input
		$this->form_validation->set_rules('identity', str_replace(':', '', $this->lang->line('login_identity_label')), 'required');
		$this->form_validation->set_rules('password', str_replace(':', '', $this->lang->line('login_password_label')), 'required');

		if ($this->form_validation->run() === TRUE) {



			// //CEK STATUS PAKET MASIH AKTIF ATAU TIDAK
			// $this->db->select('*');
			// $this->db->from('users');
			// $this->db->join('users_groups', 'users.id = users_groups.user_id');
			// $this->db->where('users.username', $this->input->post('identity'));
			// $cek_group = $this->db->get();

			// if ($cek_group->num_rows() > 0) {
			// 	$group_id = $cek_group->row()->group_id;
			// 	if ($group_id == 2 || $group_id == 5) {
			// 		$user_id_parent = $cek_group->row()->is_parent;
			// 		if ($this->_check_subscription($group_id, $user_id_parent) == FALSE) {

			// 			$this->session->set_flashdata('data_message', 'Paket anda sudah habis! Silahkan lakukan perpanjangan paket untuk Login ke menu SKM.');
			// 			redirect('auth/login', 'refresh');
			// 		}
			// 	}
			// }

			// check to see if the user is logging in
			// check for "remember me"
			$remember = (bool)$this->input->post('remember');

			if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember)) {

				//if the login is successful
				//redirect them back to the home page
				$this->session->set_userdata('aside_minimize', 1);
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				// redirect('/', 'refresh');
				$this->_redirection();
			} else {
				// if the login was un-successful
				// redirect them back to the login page
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect('auth/login', 'refresh'); // use redirects instead of loading views for compatibility with MY_Controller libraries
			}
		} else {
			// the user is not logging in so display the login page
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$this->data['identity'] = [
				'name' => 'identity',
				'id' => 'identity',
				'type' => 'text',
				'value' => $this->form_validation->set_value('identity'),
				'class' => 'form-control h-auto form-control-solid py-4 px-8',
				'placeholder' => 'Username',
				'autocomplete' => 'off',
			];

			$this->data['password'] = [
				'name' => 'password',
				'id' => 'password',
				'type' => 'password',
				'class' => 'form-control h-auto form-control-solid py-4 px-8',
				'placeholder' => 'Password',
			];

			// $this->_render_page('auth' . DIRECTORY_SEPARATOR . 'login', $this->data);
			return view('auth/login', $this->data);
		}
	}

	/**
	 * Log the user out
	 */
	public function logout()
	{
		$this->data['title'] = "Logout";

		// log the user out
		$this->ion_auth->logout();

		// redirect them to the login page
		redirect('auth/login', 'refresh');
	}

	public function user_logout()
	{
		$this->ion_auth->logout();

		echo json_encode(array("status" => TRUE));
	}

	/**
	 * Change password
	 */
	public function change_password()
	{
		$this->form_validation->set_rules('old', $this->lang->line('change_password_validation_old_password_label'), 'required');
		$this->form_validation->set_rules('new', $this->lang->line('change_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|matches[new_confirm]');
		$this->form_validation->set_rules('new_confirm', $this->lang->line('change_password_validation_new_password_confirm_label'), 'required');

		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		}

		$user = $this->ion_auth->user()->row();

		if ($this->form_validation->run() === FALSE) {
			// display the form
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
			$this->data['old_password'] = [
				'name' => 'old',
				'id' => 'old',
				'type' => 'password',
				'class' => 'form-control',
				'value' =>	$this->form_validation->set_value('old'),
			];
			$this->data['new_password'] = [
				'name' => 'new',
				'id' => 'new',
				'type' => 'password',
				'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
				'class' => 'form-control',
				'value' =>	$this->form_validation->set_value('new'),
			];
			$this->data['new_password_confirm'] = [
				'name' => 'new_confirm',
				'id' => 'new_confirm',
				'type' => 'password',
				'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
				'class' => 'form-control',
				'value' =>	$this->form_validation->set_value('new_confirm'),
			];
			$this->data['user_id'] = [
				'name' => 'user_id',
				'id' => 'user_id',
				'type' => 'hidden',
				'value' => $user->id,
			];

			// render
			// $this->_render_page('auth' . DIRECTORY_SEPARATOR . 'change_password', $this->data);
			return view('auth/change_password', $this->data);
		} else {
			$identity = $this->session->userdata('identity');

			$change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));

			if ($change) {
				//if the password was successfully changed
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				$this->logout();
			} else {
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect('auth/change_password', 'refresh');
			}
		}
	}

	/**
	 * Forgot password
	 */
	 
	public function forgot_password()
	{
		$this->data['title'] = $this->lang->line('forgot_password_heading');
		$this->form_validation->set_rules('identity', 'Email', 'required|valid_email');

		if ($this->form_validation->run() === FALSE) {
			// setup the input
			$this->data['identity'] = [
				'name' => 'identity',
				'id' => 'identity',
				'class' => 'form-control form-control-solid h-auto py-4 px-8',
				'placeholder' => 'Email',
				'autocomplete' => 'off',
			];

			// set any errors and display the form
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			// $this->_render_page('auth' . DIRECTORY_SEPARATOR . 'forgot_password', $this->data);
			return view('auth/forgot_password', $this->data);
		} else {
			$email = $this->input->post('identity');
            $clean = $this->security->xss_clean($email);
            $userInfo = $this->Models->getUserInfoByEmail($clean);

            if (!$userInfo) {

                $this->session->set_flashdata('message', 'No record of that email address.');
				//The Email Address field must contain a valid email address.
                redirect("auth/forgot_password", 'refresh');

            }else{

				$token = $this->Models->insertToken($userInfo->id);
				$qstring = $this->Models->base64url_encode($token);
				$url = base_url() . 'auth/reset_password/' . $qstring;
				$link = '<a href="' . $url . '">' . $url . '</a>';

				$this->load->library('email');

				$config['protocol']     = "smtp";
				$config['smtp_host']    = "smtp.gmail.com";
				$config['smtp_port']    = "587";
				$config['smtp_crypto']	= "tls";
				$config['smtp_user']    = "survei.kepuasan.online@gmail.com";
				$config['smtp_pass']    = "rgyteczpthpokkrs";
				$config['charset']      = "utf-8";
				$config['mailtype']     = "html";
				$config['newline']      = "\r\n";

				$message = 'Please click this link to change password: ' . $link;
				$this->email->initialize($config);
				$this->email->from('survei.kepuasan.online@gmail.com'); 
				$this->email->to($email);
				$this->email->subject('Reset Password');
				$this->email->message($message);
				if($this->email->send()){
					$this->session->set_flashdata("message","Email reset password link sent successfully.");
					redirect("auth/login", 'refresh'); 
				}else{
					//show_error($this->email->print_debugger());
					$this->session->set_flashdata("message","Error in sending Email."); 
				}
				
			}

		}
	}

	public function reset_password()
    {
		$this->data['title'] = $this->lang->line('reset_password_heading');
        $token = $this->Models->base64url_decode($this->uri->segment(3));
        $cleanToken = $this->security->xss_clean($token);

        $user_info = $this->Models->isTokenValid($cleanToken); //either false or array();          

        if (!$user_info) {
            $this->session->set_flashdata('message', 'Token not valid or expired');
            redirect("auth/login", 'refresh');
        }

		$this->form_validation->set_rules('new', 'New Password', 'required|min_length[8]');
        $this->form_validation->set_rules('new_confirm', 'New Password Confirmation', 'required|matches[new]');

		if ($this->form_validation->run() === FALSE) {

			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
			$this->data['new_password'] = [
				'name' => 'new',
				'id' => 'new',
				'type' => 'password',
				'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
				'class' => 'form-control',
			];
			$this->data['new_password_confirm'] = [
				'name' => 'new_confirm',
				'id' => 'new_confirm',
				'type' => 'password',
				'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
				'class' => 'form-control',
			];
			$this->data['user_id'] = [
				'name' => 'user_id',
				'id' => 'user_id',
				'type' => 'hidden',
				'value' => $user_info->id,
			];
			$this->data['csrf'] = $this->_get_csrf_nonce();
			$this->data['code'] = $this->uri->segment(3);

			// render
			// $this->_render_page('auth' . DIRECTORY_SEPARATOR . 'reset_password', $this->data);
			return view('auth/reset_password', $this->data);

		} else {
			
			// do we have a valid request?
			if ($this->_valid_csrf_nonce() === FALSE || $user_info->id != $this->input->post('user_id')) {
				show_error($this->lang->line('error_csrf'));
			} else {
				$post = $this->input->post(NULL, TRUE);
				$cleanPost = $this->security->xss_clean($post);
				//$hashed = md5($cleanPost['new']);
				$hashed = password_hash($cleanPost['new'], PASSWORD_BCRYPT);
				$cleanPost['password'] = $hashed;
				$cleanPost['re_password'] = $cleanPost['new'];
				$cleanPost['user_id'] = $user_info->id;
				if (!$this->Models->updatePassword($cleanPost)) {
					$this->session->set_flashdata('message', 'Your password failed to update.');
					redirect('auth/reset_password/' . $this->uri->segment(3), 'refresh');
				} else {
				    $this->Models->deleteToken($user_info->id);
					$this->session->set_flashdata('message', 'Your password has been updated successfully.');
					redirect("auth/login", 'refresh');
				}
			}

		}
        
    }
    
	public function forgot_password2()
	{
		$this->data['title'] = $this->lang->line('forgot_password_heading');

		// setting validation rules by checking whether identity is username or email
		if ($this->config->item('identity', 'ion_auth') != 'email') {
			$this->form_validation->set_rules('identity', $this->lang->line('forgot_password_identity_label'), 'required');
		} else {
			$this->form_validation->set_rules('identity', $this->lang->line('forgot_password_validation_email_label'), 'required|valid_email');
		}


		if ($this->form_validation->run() === FALSE) {
			$this->data['type'] = $this->config->item('identity', 'ion_auth');
			// setup the input
			$this->data['identity'] = [
				'name' => 'identity',
				'id' => 'identity',
				'class' => 'form-control form-control-solid h-auto py-4 px-8',
				'placeholder' => 'Email',
				'autocomplete' => 'off',
			];

			if ($this->config->item('identity', 'ion_auth') != 'email') {
				$this->data['identity_label'] = $this->lang->line('forgot_password_identity_label');
			} else {
				$this->data['identity_label'] = $this->lang->line('forgot_password_email_identity_label');
			}

			// set any errors and display the form
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			// $this->_render_page('auth' . DIRECTORY_SEPARATOR . 'forgot_password', $this->data);
			return view('auth/forgot_password', $this->data);
		} else {
			$identity_column = $this->config->item('identity', 'ion_auth');
			$identity = $this->ion_auth->where($identity_column, $this->input->post('identity'))->users()->row();

			if (empty($identity)) {

				if ($this->config->item('identity', 'ion_auth') != 'email') {
					$this->ion_auth->set_error('forgot_password_identity_not_found');
				} else {
					$this->ion_auth->set_error('forgot_password_email_not_found');
				}

				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect("auth/forgot_password", 'refresh');
			}

			// run the forgotten password method to email an activation code to the user
			$forgotten = $this->ion_auth->forgotten_password($identity->{$this->config->item('identity', 'ion_auth')});

			if ($forgotten) {
				// if there were no errors
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect("auth/login", 'refresh'); //we should display a confirmation page here instead of the login page
			} else {
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect("auth/forgot_password", 'refresh');
			}
		}
	}

	/**
	 * Reset password - final step for forgotten password
	 *
	 * @param string|null $code The reset code
	 */
	public function reset_password2($code = NULL)
	{
		if (!$code) {
			show_404();
		}

		$this->data['title'] = $this->lang->line('reset_password_heading');

		$user = $this->ion_auth->forgotten_password_check($code);

		if ($user) {
			// if the code is valid then display the password reset form

			$this->form_validation->set_rules('new', $this->lang->line('reset_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|matches[new_confirm]');
			$this->form_validation->set_rules('new_confirm', $this->lang->line('reset_password_validation_new_password_confirm_label'), 'required');

			if ($this->form_validation->run() === FALSE) {
				// display the form

				// set the flash data error message if there is one
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

				$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
				$this->data['new_password'] = [
					'name' => 'new',
					'id' => 'new',
					'type' => 'password',
					'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
					'class' => 'form-control',
				];
				$this->data['new_password_confirm'] = [
					'name' => 'new_confirm',
					'id' => 'new_confirm',
					'type' => 'password',
					'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
					'class' => 'form-control',
				];
				$this->data['user_id'] = [
					'name' => 'user_id',
					'id' => 'user_id',
					'type' => 'hidden',
					'value' => $user->id,
				];
				$this->data['csrf'] = $this->_get_csrf_nonce();
				$this->data['code'] = $code;

				// render
				// $this->_render_page('auth' . DIRECTORY_SEPARATOR . 'reset_password', $this->data);
				return view('auth/reset_password', $this->data);
			} else {
				$identity = $user->{$this->config->item('identity', 'ion_auth')};

				// do we have a valid request?
				if ($this->_valid_csrf_nonce() === FALSE || $user->id != $this->input->post('user_id')) {

					// something fishy might be up
					$this->ion_auth->clear_forgotten_password_code($identity);

					show_error($this->lang->line('error_csrf'));
				} else {
					// finally change the password
					$change = $this->ion_auth->reset_password($identity, $this->input->post('new'));

					if ($change) {
						// if the password was successfully changed
						$this->session->set_flashdata('message', $this->ion_auth->messages());
						redirect("auth/login", 'refresh');
					} else {
						$this->session->set_flashdata('message', $this->ion_auth->errors());
						redirect('auth/reset_password/' . $code, 'refresh');
					}
				}
			}
		} else {
			// if the code is invalid then send them back to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("auth/forgot_password", 'refresh');
		}
	}

	/**
	 * Activate the user
	 *
	 * @param int         $id   The user ID
	 * @param string|bool $code The activation code
	 */
	public function activate($id, $code = FALSE)
	{
		$activation = FALSE;

		if ($code !== FALSE) {
			$activation = $this->ion_auth->activate($id, $code);
		} else if ($this->ion_auth->is_admin()) {
			$activation = $this->ion_auth->activate($id);
		}

		if ($activation) {
			// redirect them to the auth page
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			redirect("auth", 'refresh');
		} else {
			// redirect them to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("auth/forgot_password", 'refresh');
		}
	}

	/**
	 * Deactivate the user
	 *
	 * @param int|string|null $id The user ID
	 */
	public function deactivate($id = NULL)
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			// redirect them to the home page because they must be an administrator to view this
			show_error('You must be an administrator to view this page.');
		}

		$id = (int)$id;

		$this->load->library('form_validation');
		$this->form_validation->set_rules('confirm', $this->lang->line('deactivate_validation_confirm_label'), 'required');
		$this->form_validation->set_rules('id', $this->lang->line('deactivate_validation_user_id_label'), 'required|alpha_numeric');

		if ($this->form_validation->run() === FALSE) {
			// insert csrf check
			$this->data['csrf'] = $this->_get_csrf_nonce();
			$this->data['user'] = $this->ion_auth->user($id)->row();
			$this->data['identity'] = $this->config->item('identity', 'ion_auth');

			// $this->_render_page('auth' . DIRECTORY_SEPARATOR . 'deactivate_user', $this->data);
			return view('auth/deactivate_user', $this->data);
		} else {
			// do we really want to deactivate?
			if ($this->input->post('confirm') == 'yes') {
				// do we have a valid request?
				if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id')) {
					show_error($this->lang->line('error_csrf'));
				}

				// do we have the right userlevel?
				if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
					$this->ion_auth->deactivate($id);
				}
			}

			// redirect them back to the auth page
			redirect('auth', 'refresh');
		}
	}

	/**
	 * Create a new user
	 */
	public function create_user()
	{
		$this->data['title'] = $this->lang->line('create_user_heading');

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth', 'refresh');
		}

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

			$additional_data = [
				'first_name' => $this->input->post('first_name'),
				'last_name' => $this->input->post('last_name'),
				'company' => $this->input->post('company'),
				'phone' => $this->input->post('phone'),
				'id_jenis_survey' => $this->input->post('id_jenis_survey'),
			];
		}
		if ($this->form_validation->run() === TRUE && $this->ion_auth->register($identity, $password, $email, $additional_data)) {
			// check to see if we are creating the user
			// redirect them back to the admin page
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			redirect("auth", 'refresh');
		} else {
			// display the create user form
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			$this->data['first_name'] = [
				'name' => 'first_name',
				'id' => 'first_name',
				'type' => 'text',
				'value' => $this->form_validation->set_value('first_name'),
				'class' => 'form-control',
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
				'type' => 'text',
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

			$this->load->model('Auth_model');

			$this->data['id_jenis_survey'] = [
				'name' 		=> 'id_jenis_survey',
				'options' 	=> $this->Auth_model->dropdown_jenis_survey(),
				'selected' 	=> $this->form_validation->set_value('id_jenis_survey'),
				'class' 	=> "form-select",
				'id' 		=> 'id_jenis_survey',
			];

			// $this->_render_page('auth' . DIRECTORY_SEPARATOR . 'create_user', $this->data);
			return view("auth/create_user", $this->data);
		}
	}
	/**
	 * Redirect a user checking if is admin
	 */
	public function redirectUser()
	{
		if ($this->ion_auth->is_admin()) {
			redirect('auth', 'refresh');
		}
		redirect('/', 'refresh');
	}


	/**
	 * Create a new group
	 */
	public function create_group()
	{
		$this->data['title'] = $this->lang->line('create_group_title');

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth', 'refresh');
		}

		// validate form input
		$this->form_validation->set_rules('group_name', $this->lang->line('create_group_validation_name_label'), 'trim|required|alpha_dash');

		if ($this->form_validation->run() === TRUE) {
			$new_group_id = $this->ion_auth->create_group($this->input->post('group_name'), $this->input->post('description'));
			if ($new_group_id) {
				// check to see if we are creating the group
				// redirect them back to the admin page
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect("auth", 'refresh');
			} else {
				$this->session->set_flashdata('message', $this->ion_auth->errors());
			}
		}

		// display the create group form
		// set the flash data error message if there is one
		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		$this->data['group_name'] = [
			'name'  => 'group_name',
			'id'    => 'group_name',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('group_name'),
			'class' => 'form-control',
		];
		$this->data['description'] = [
			'name'  => 'description',
			'id'    => 'description',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('description'),
			'class' => 'form-control',
		];

		// $this->_render_page('auth/create_group', $this->data);
		return view("auth/create_group", $this->data);
	}

	/**
	 * Edit a group
	 *
	 * @param int|string $id
	 */
	public function edit_group($id)
	{
		// bail if no group id given
		if (!$id || empty($id)) {
			redirect('auth', 'refresh');
		}

		$this->data['title'] = $this->lang->line('edit_group_title');

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth', 'refresh');
		}

		$group = $this->ion_auth->group($id)->row();

		// validate form input
		$this->form_validation->set_rules('group_name', $this->lang->line('edit_group_validation_name_label'), 'trim|required|alpha_dash');

		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$group_update = $this->ion_auth->update_group($id, $_POST['group_name'], array(
					'description' => $_POST['group_description']
				));

				if ($group_update) {
					$this->session->set_flashdata('message', $this->lang->line('edit_group_saved'));
					redirect("auth", 'refresh');
				} else {
					$this->session->set_flashdata('message', $this->ion_auth->errors());
				}
			}
		}

		// set the flash data error message if there is one
		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		// pass the user to the view
		$this->data['group'] = $group;

		$this->data['group_name'] = [
			'name'    => 'group_name',
			'id'      => 'group_name',
			'type'    => 'text',
			'value'   => $this->form_validation->set_value('group_name', $group->name),
			'class' => 'form-control',
		];
		if ($this->config->item('admin_group', 'ion_auth') === $group->name) {
			$this->data['group_name']['readonly'] = 'readonly';
		}

		$this->data['group_description'] = [
			'name'  => 'group_description',
			'id'    => 'group_description',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('group_description', $group->description),
			'class' => 'form-control',
		];

		// $this->_render_page('auth' . DIRECTORY_SEPARATOR . 'edit_group', $this->data);
		return view("auth/edit_group", $this->data);
	}

	/**
	 * @return array A CSRF key-value pair
	 */
	public function _get_csrf_nonce()
	{
		$this->load->helper('string');
		$key = random_string('alnum', 8);
		$value = random_string('alnum', 20);
		$this->session->set_flashdata('csrfkey', $key);
		$this->session->set_flashdata('csrfvalue', $value);

		return [$key => $value];
	}

	/**
	 * @return bool Whether the posted CSRF token matches
	 */
	public function _valid_csrf_nonce()
	{
		$csrfkey = $this->input->post($this->session->flashdata('csrfkey'));
		if ($csrfkey && $csrfkey === $this->session->flashdata('csrfvalue')) {
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * @param string     $view
	 * @param array|null $data
	 * @param bool       $returnhtml
	 *
	 * @return mixed
	 */
	public function _render_page($view, $data = NULL, $returnhtml = FALSE) //I think this makes more sense
	{

		$viewdata = (empty($data)) ? $this->data : $data;

		$view_html = $this->load->view($view, $viewdata, $returnhtml);

		// This will return html on 3rd argument being true
		if ($returnhtml) {
			return $view_html;
		}
	}

	public function update_aside()
	{
		$sess_val = $this->uri->segment(3);

		if ($sess_val == 2) {
			$this->session->set_userdata('aside_minimize', 1);

			$message = 'Menu samping diminimize';
			$success = 'Enabled';
			echo json_encode(array('message' => $message, '$success' => $success));
		} else if ($sess_val == 1) {
			$this->session->set_userdata('aside_minimize', 2);

			$message = 'Menu samping tidak diminimize';
			$success = 'Disabled';
			echo json_encode(array('message' => $message, 'success' => $success));
		}
	}
}