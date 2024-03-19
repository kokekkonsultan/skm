<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Carbon\Carbon;

class UsersManagementController extends CI_Controller
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
		$this->load->model('Berlangganan_model', 'models');
		$this->load->model('KelolaDivision_model');
		$this->load->model('UserManagement_model');
	}

	public function index()
	{
		$this->data = [];
		$this->data['title'] = "Kelola Paket";

		return view('users_management/index', $this->data);
	}

	public function ajax_list()
	{
		$id_user = $this->ion_auth->user()->row()->id;

		$list = $this->models->get_datatables($id_user);
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			$no++;
			$row = array();
			$row[] = $no;

			$row[] = $value->nama_paket;
			$row[] = anchor(base_url() . $this->session->userdata('username') . '/' . 'users-management/list-users/' . $value->uuid, '<i class="fa fa-users"></i> Users Management <i', ['class' => 'btn btn-light-primary btn-sm font-weight-bold shadow']);

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->models->count_all($id_user),
			"recordsFiltered" => $this->models->count_filtered($id_user),
			"data" => $data,
		);

		echo json_encode($output);
	}


	// ====================================== LIST USER SUPERVISOR =======================================================

	public function list_users($username, $uuid_berlangganan)
	{
		$this->data = [];
		$this->data['title'] = "Kelola Pengguna";

		$user = $this->ion_auth->user()->row();
		$user_identity = $user->user_identity;

		$this->db->select('*, users.id AS id_pengguna, berlangganan.id AS id_berlangganan');
		$this->db->from('users');
		$this->db->join('berlangganan', 'berlangganan.id_user = users.id');
		$this->db->join('paket', 'paket.id = berlangganan.id_paket');
		$this->db->where('users.username', $username);
		$this->db->where('berlangganan.uuid', $uuid_berlangganan);
		$this->data['data_langganan'] = $this->db->get()->row();
		// var_dump($this->data['data_langganan']);

		$this->data['division'] = $this->db->get_where('division_' . $user->user_identity, array('id_berlangganan' => $this->data['data_langganan']->id_berlangganan));

		$this->data['supervisor'] = $this->db->query("SELECT *, users.id AS user_id
		FROM supervisor_$user->user_identity
		JOIN users ON supervisor_$user->user_identity.id_user = users.id
		JOIN berlangganan ON supervisor_$user->user_identity.id_berlangganan = berlangganan.id
		WHERE berlangganan.id = " . $this->data['data_langganan']->id_berlangganan);

		$tanggal_mulai = $this->data['data_langganan']->tanggal_mulai;
		$tanggal_selesai = $this->data['data_langganan']->tanggal_selesai;

		$this->data['tanggal_sekarang'] = $tanggal_mulai;
		$this->data['tanggal_expired'] = $tanggal_selesai;

		$tanggal_mulai = $this->data['data_langganan']->tanggal_mulai;
		$tanggal_selesai = $this->data['data_langganan']->tanggal_selesai;

		$now = Carbon::now();
		$start_date = Carbon::parse($tanggal_mulai);
		$end_date = Carbon::parse($tanggal_selesai);
		$due_date = $now->diffInDays($end_date); // Tanggal jatuh tempo


		if ($now->between($start_date, $end_date)) {
			$this->data['status_jatuh_tempo'] = 'Paket berakhir dalam ' . $due_date . ' hari lagi';
			$this->data['status_paket'] = '<span class="badge badge-success">Aktif</span>';
			$this->data['btn_add_divisi'] = '<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#tambah"><i class="fas fa-plus"></i> Tambah Divisi</button>';

			//CEK USER PAKET
			$cek_paket = $this->db->query("SELECT *, (SELECT COUNT(id) FROM supervisor_$user_identity WHERE supervisor_$user_identity.id_berlangganan = berlangganan.id) AS jumlah_supervisor, (jumlah_user - ((SELECT COUNT(id) FROM supervisor_$user_identity WHERE supervisor_$user_identity.id_berlangganan = berlangganan.id) + 1)) AS kurang_user
			FROM berlangganan
			JOIN paket ON berlangganan.id_paket = paket.id
			WHERE berlangganan.uuid = '" . $this->uri->segment(4) . "'")->row();

			if ($cek_paket->kurang_user == 0) {
				$this->data['btn_add'] = '<a class="btn btn-primary btn-sm" onclick="cek()"><i class="fa fa-user-plus"></i> Tambah Admin Survei</a>';
				// $this->data['btn_add_divisi'] = '';
			} else {
				$this->data['btn_add'] = '<a class="btn btn-primary btn-sm" href="' . base_url() . $this->session->userdata('username') . '/users-management/list-users/' . $this->uri->segment(4) . '/add"><i class="fa fa-user-plus"></i> Tambah Admin Survei</a>';
			}
		} else {
			$this->data['status_jatuh_tempo'] = 'Packet is Expired';
			$this->data['status_paket'] = '<span class="badge badge-danger">Expired</span>';
			$this->data['btn_add'] = '';
			$this->data['btn_add_divisi'] = '';
		}
		return view('users_management/list_users_management', $this->data);
	}

	public function ajax_list_users($username, $uuid_berlangganan)
	{
		$user = $this->ion_auth->user()->row();
		$user_identity = $user->user_identity;

		$berlangganan = $this->db->get_where('berlangganan', array('uuid' => $uuid_berlangganan))->row();
		$tanggal_mulai = $berlangganan->tanggal_mulai;
		$tanggal_selesai = $berlangganan->tanggal_selesai;
		$now = Carbon::now();
		$start_date = Carbon::parse($tanggal_mulai);
		$end_date = Carbon::parse($tanggal_selesai);

		$list = $this->UserManagement_model->get_datatables($user_identity);
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			if ($value->id_supervisor == NULL) {
				$jenis_akun = '<span class="badge badge-secondary">AGENT</span>';
			} else {
				$jenis_akun = '<span class="badge badge-secondary">Admin Survei</span>';
			};

			$no++;
			$row = array();
			$row[] = $no;

			$row[] = '<button type="button" class="btn btn-light-info btn-sm font-weight-bold" data-toggle="modal" data-target="#detail' . $value->id_users . '"><i class="fa fa-user-circle"></i></i> Detail Pengguna</button>';

			$row[] = $value->first_name . ' ' . $value->last_name;
			$row[] = $value->division_name;
			$row[] = $jenis_akun;

			if ($now->between($start_date, $end_date)) {
				$row[] = '<a class="btn btn-light-primary btn-sm font-weight-bold" href="' . base_url() . $this->session->userdata('username') . '/users-management/list-users/' . $this->uri->segment(4) . '/edit/' . $value->id_users . '"><i class="fa fa-user-edit"></i> Edit</a>';

				$row[] = '<a class="btn btn-light-primary btn-sm font-weight-bold" href="javascript:void(0)" title="Hapus ' . $value->first_name . ' ' . $value->last_name . '" onclick="delete_list(' . "'" . $value->id_users . "'" . ')"><i class="fa fa-user-times"></i> Delete</a>';
			} else {
				$row[] = '';
				$row[] = '';
			}



			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->UserManagement_model->count_all($user_identity),
			"recordsFiltered" => $this->UserManagement_model->count_filtered($user_identity),
			"data" => $data,
		);

		echo json_encode($output);
	}


	public function add_list_users()
	{
		$this->data = [];
		$this->data['title'] = "Tambah Pengguna Admin Survei";

		$user = $this->ion_auth->user()->row();
		$user_identity = $user->user_identity;

		//CEK USERS PAKET
		$cek_paket = $this->db->query("SELECT *, (SELECT COUNT(id) FROM supervisor_$user_identity WHERE supervisor_$user_identity.id_berlangganan = berlangganan.id) AS jumlah_supervisor, (jumlah_user - ((SELECT COUNT(id) FROM supervisor_$user_identity WHERE supervisor_$user_identity.id_berlangganan = berlangganan.id) + 1)) AS kurang_user
		FROM berlangganan
		JOIN paket ON berlangganan.id_paket = paket.id
		WHERE berlangganan.uuid = '" . $this->uri->segment(4) . "'")->row();

		if ($cek_paket->kurang_user == 0) {
			'die("Sorry, your links is not correctly !")';
		}

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
				'company' => $user->company,
				'phone' => $this->input->post('phone'),
				're_password' => $password,
				'id_klasifikasi_survei' =>  $user->id_klasifikasi_survei,
				'is_parent' => $user->id,
				'is_trial' => ''
			];
			$group = array('5');
		}
		if ($this->form_validation->run() === TRUE && $this->ion_auth->register($identity, $password, $email, $additional_data, $group)) {

			$this->load->library('uuid');
			$id_berlangganan = $this->db->get_where('berlangganan', array('uuid' => $this->uri->segment(4)))->row()->id;
			$id_users = $this->db->get_where('users', array('username' =>  $this->input->post('identity')))->row()->id;

			$object = [
				'uuid' => $this->uuid->v4(),
				'id_user' => $id_users,
				'id_berlangganan' => $id_berlangganan,
				'id_division' => $this->input->post('id_divisi')
			];
			$this->db->insert('supervisor_' . $user_identity, $object);

			$this->session->set_flashdata('message_success', 'Berhasil menambah data');
			redirect(base_url() . $this->session->userdata('username') . '/users-management/list-users/' . $this->uri->segment(4), 'refresh');
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
			$this->data['email'] = [
				'name' => 'email',
				'id' => 'email',
				'type' => 'text',
				'value' => $this->form_validation->set_value('email'),
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

			$this->data['id_divisi'] = [
				'name' 		=> 'id_divisi',
				'id' 		=> 'id_divisi',
				'options' 	=> $this->UserManagement_model->dropdown_divisi($user_identity),
				'selected' 	=> $this->form_validation->set_value('id_divisi'),
				'class' 	=> "form-control",
				'autofocus' => 'autofocus',
				'required' => 'required'
			];

			return view("users_management/form_add", $this->data);
		}
	}

	public function edit_list_users($id1, $id2)
	{
		$this->data = [];
		$this->data['title'] = "Edit Pengguna Supervisor";

		$user = $this->ion_auth->user()->row();
		$user_identity = $user->user_identity;

		$this->db->select('*');
		$this->db->from('supervisor_' . $user_identity);
		$this->db->join('users', "supervisor_$user_identity.id_user = users.id");
		$this->db->where("supervisor_$user_identity.id_user", $this->uri->segment(6));
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
				];
				if ($this->input->post('password')) {
					$data['password'] = $this->input->post('password');
				}
				$this->db->where('id', $this->uri->segment(6));
				$this->db->update('users', $data);

				$obj = [
					'id_division' => $this->input->post('id_divisi')
				];
				$this->db->where('id_user', $this->uri->segment(6));
				$this->db->update('supervisor_' . $user_identity, $obj);

				$this->session->set_flashdata('message_success', 'Berhasil mengubah data');
				redirect(base_url() . $this->session->userdata('username') . '/users-management/list-users/' . $this->uri->segment(4), 'refresh');
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

		$this->data['id_divisi'] = [
			'name' 		=> 'id_divisi',
			'id' 		=> 'id_divisi',
			'options' 	=> $this->UserManagement_model->dropdown_divisi($user_identity),
			'selected' 	=> $this->form_validation->set_value('id_divisi', $current->id_division),
			'class' 	=> "form-control",
			'autofocus' => 'autofocus'
		];

		return view("users_management/form_edit", $this->data);
	}

	public function delete_list_users()
	{
		$user = $this->ion_auth->user()->row();
		$user_identity = $user->user_identity;

		$this->db->delete('supervisor_' . $user_identity, array('id_user' => $this->uri->segment(6)));
		$this->db->delete('users_groups', array('user_id' => $this->uri->segment(6)));
		$this->db->delete('users', array('id' => $this->uri->segment(6)));

		echo json_encode(array("status" => TRUE));
	}

	//=================================== DIVISION =============================================================

	public function ajax_list_division($username, $uuid_berlangganan)
	{
		$user = $this->ion_auth->user()->row();
		$user_identity = $user->user_identity;

		$berlangganan = $this->db->get_where('berlangganan', array('uuid' => $uuid_berlangganan))->row();
		$tanggal_mulai = $berlangganan->tanggal_mulai;
		$tanggal_selesai = $berlangganan->tanggal_selesai;
		$now = Carbon::now();
		$start_date = Carbon::parse($tanggal_mulai);
		$end_date = Carbon::parse($tanggal_selesai);

		$list = $this->KelolaDivision_model->get_datatables($user_identity);
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			$no++;
			$row = array();
			$row[] = $no;

			if ($value->is_default == 1) {

				$row[] = '<i class="fa fa-bookmark text-primary" aria-hidden="true"></i> <b>' . $value->division_name . '</b>';
				$row[] = '';
				$row[] = '';
			} else {

				$row[] = $value->division_name;

				if ($now->between($start_date, $end_date)) {
					$row[] = '<button type="button" class="btn btn-light-primary btn-sm" data-toggle="modal" data-target="#edit' . $value->id_division . '"><i class="fa fa-edit"></i> Edit</button>';
					$row[] = '<a class="btn btn-light-primary btn-sm font-weight-bold shadow" href="javascript:void(0)" title="Hapus ' . $value->division_name . '" onclick="delete_divisi(' . "'" . $value->id_division . "'" . ')"><i class="fa fa-trash"></i> Delete</a>';
				} else {
					$row[] = '';
					$row[] = '';
				}
			};
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->KelolaDivision_model->count_all($user_identity),
			"recordsFiltered" => $this->KelolaDivision_model->count_filtered($user_identity),
			"data" => $data,
		);

		echo json_encode($output);
	}

	public function add_division()
	{
		$user = $this->ion_auth->user()->row();
		$user_identity = $user->user_identity;

		$object = [
			'id_berlangganan' => $this->input->post('id_berlangganan'),
			'division_name' => $this->input->post('division_name'),
			'is_default' => '0',
		];
		// var_dump($object);
		$this->db->insert('division_' . $user_identity, $object);

		$this->session->set_flashdata('message_success', 'Berhasil menambah data');
		redirect($this->session->userdata('username') . '/users-management/list-users/' . $this->input->post('uuid_berlangganan'), 'refresh');
	}

	public function edit_division()
	{
		$user = $this->ion_auth->user()->row();
		$user_identity = $user->user_identity;

		$object = [
			'division_name' => $this->input->post('division_name')
		];
		// var_dump($object);
		$this->db->where('id',  $this->input->post('id'));
		$this->db->update('division_' . $user_identity, $object);

		$this->session->set_flashdata('message_success', 'Berhasil mengubah data');
		redirect($this->session->userdata('username') . '/users-management/list-users/' . $this->input->post('uuid_berlangganan'), 'refresh');
	}

	public function delete_division()
	{
		$user = $this->ion_auth->user()->row();
		$user_identity = $user->user_identity;

		$cek_spv = $this->db->get_where('supervisor_' . $user_identity, array('id_division' => $this->uri->segment(3)));
		if ($cek_spv->num_rows() > 0) {
			// ubah divisi ke default terlebih dahulu
			$object = [
				'id_division' => 1,
			];
			$this->db->where('id_division', $this->uri->segment(3));
			$this->db->update('supervisor_' . $user_identity, $object);
		}
		$this->db->where('id', $this->uri->segment(3));
		$this->db->delete('division_' . $user_identity);

		echo json_encode(array("status" => TRUE));
	}
}

/* End of file UsersManagementController.php */
/* Location: ./application/controllers/UsersManagementController.php */