<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Carbon\Carbon;

class PenggunaResellerController extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		if (!$this->ion_auth->logged_in()) {
			$this->session->set_flashdata('message_warning', 'You must be an admin to view this page');
			redirect('auth', 'refresh');
		}

		$this->load->library('form_validation');
		$this->load->helper('security');
		$this->load->model('PenggunaReseller_model', 'models');
	}

	public function index()
	{
		$this->data = [];
		$this->data['title'] = "Kelola Pengguna Reseller";

		$this->db->select("*");
        $this->db->from("users");
        $this->db->join("users_groups", "users.id = users_groups.user_id");
        $this->db->where("group_id", 4);
		$this->data['reseller'] = $this->db->get();

		$this->data['cek_klien'] = $this->db->get('users');

		return view('pengguna_reseller/index', $this->data);
	}


	public function ajax_list()
	{
		$user = $this->ion_auth->user()->row();
		$user_identity = $user->user_identity;

		$list = $this->models->get_datatables($user_identity);
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {
			$no++;
			$row = array();
			$row[] = $no;


			$row[] = '<a class="btn btn-light-primary shadow" data-toggle="modal" title="Detail Klien" onclick="showuserdetail(' . $value->user_id . ')" href="#modal_userDetail"><i class="fas fa-key"></i> Detail</a>';

			$row[] = $value->first_name . ' ' . $value->last_name;
			$row[] = $value->email;

			$row[] = '<button type="button" class="btn btn-light-info font-weight-bold" data-toggle="modal" data-target="#detail' . $value->user_id . '"><i class="fa fa-users"></i> Lihat Klien</button>';

			$row[] = '<a class="btn btn-light-primary font-weight-bold" href="' . base_url() . 'pengguna-reseller/edit/' . $value->user_id . '"><i class="fa fa-user-edit"></i> Edit</a>';

			$row[] = '<a class="btn btn-light-primary font-weight-bold" href="javascript:void(0)" title="Hapus ' . $value->first_name . ' ' . $value->last_name . '" onclick="delete_user(' . "'" . $value->user_id . "'" . ')"><i class="fa fa-user-times"></i> Delete</a>';

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->models->count_all($user_identity),
			"recordsFiltered" => $this->models->count_filtered($user_identity),
			"data" => $data,
		);

		echo json_encode($output);
	}


	public function add()
	{
		$this->data = [];
		$this->data['title'] = "Tambah Pengguna Reseller";

		$tables = $this->config->item('tables', 'ion_auth');
		$identity_column = $this->config->item('identity', 'ion_auth');
		$this->data['identity_column'] = $identity_column;

		$this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
		$this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');

		if ($identity_column !== 'email') {
			$this->form_validation->set_rules('identity', 'Username', 'trim|required|is_unique[' . $tables['users'] . '.' . $identity_column . ']');
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		} else {
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[' . $tables['users'] . '.email]');
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
				'company' => $this->input->post('company'),
				'is_trial' => ''
			];
			$group = array('4');
		}
		if ($this->form_validation->run() === TRUE && $this->ion_auth->register($identity, $password, $email, $additional_data, $group)) {

			$this->session->set_flashdata('message_success', 'Berhasil menambah data');
			redirect(base_url() . 'pengguna-reseller', 'refresh');
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

			$this->data['company'] = [
				'name' => 'company',
				'id' => 'company',
				'type' => 'company',
				'value' => $this->form_validation->set_value('company'),
				'class' => 'form-control',
			];

			return view("pengguna_reseller/form_add", $this->data);
		}
	}

	public function edit()
	{
		$this->data = [];
		$this->data['title'] = "Edit Pengguna Reseller";

		$this->db->select("*");
		$this->db->from("users");
		$this->db->join("users_groups", "users.id = users_groups.user_id");
		$this->db->where("users.id", $this->uri->segment(3));
		$current = $this->db->get()->row();

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
					'email' => $this->input->post('email'),
					'company' => $this->input->post('company')
				];
				if ($this->input->post('password')) {
					$data['password'] = $this->input->post('password');
				}
				// var_dump($data);
				$this->db->where('id', $this->uri->segment(3));
				$this->db->update('users', $data);

				$this->session->set_flashdata('message_success', 'Berhasil mengubah data');
				redirect(base_url() . 'pengguna-reseller', 'refresh');
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

		$this->data['company'] = [
			'name' => 'company',
			'id' => 'company',
			'type' => 'company',
			'value' => $this->form_validation->set_value('company', $current->company),
			'class' => 'form-control',
		];

		return view("pengguna_reseller/form_edit", $this->data);
	}

	public function delete()
	{
		$this->db->delete('users_groups', array('user_id' => $this->uri->segment(3)));
		$this->db->delete('users', array('id' => $this->uri->segment(3)));

		echo json_encode(array("status" => TRUE));
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
		$this->db->where('id', $id);
		$this->data['pelanggan'] = $this->db->get()->last_row();


		return view('pengguna_reseller/detail', $this->data);
	}
}

/* End of file UsersManagementController.php */
/* Location: ./application/controllers/UsersManagementController.php */