<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Carbon\Carbon;

class PenayangSurveiController extends Client_Controller
{

	public function __construct()
	{
		parent::__construct();

		if (!$this->ion_auth->logged_in()) {
			$this->session->set_flashdata('message_warning', 'You must be an admin to view this page');
			redirect('auth', 'refresh');
		}

		$this->load->model('PenayangSurvei_model', 'models');
		$this->load->library('form_validation');

		$this->load->library('image_lib');
		$this->load->helper('file');
	}

	public function index()
	{

		$this->data = [];
		$this->data['title'] = "Label Penayangan Survei";

		$data_user = $this->_cek_user()->row();
		$this->data['penayang_survei'] = $this->db->get_where('penayang_survei', array('id_user' => $data_user->user_id));


		return view('penayang_survei/index', $this->data);
	}



	public function ajax_list()
	{
		$user_id = $this->_cek_user()->row()->user_id;

		$list = $this->models->get_datatables($user_id);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $value) {

			$no++;
			$row = array();

			$row[] = $no;
			$row[] = '
			<div class="card card-body shadow wave wave-animate-slow wave-info">
				<div class="row">
				<div class="col-xl-9">
				<a href="' . base_url() . $this->session->userdata('username') .  '/penayang-survei/edit/' . $value->id . '" title="" class="text-info">
					<div class="timeline timeline-1">
						<div class="timeline-item">
							<div class="timeline-badge">
								<span class="svg-icon svg-icon-info svg-icon-4x">
									<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
										width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
										<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
											<rect x="0" y="0" width="24" height="24"></rect>
											<path
												d="M2.56066017,10.6819805 L4.68198052,8.56066017 C5.26776695,7.97487373 6.21751442,7.97487373 6.80330086,8.56066017 L8.9246212,10.6819805 C9.51040764,11.267767 9.51040764,12.2175144 8.9246212,12.8033009 L6.80330086,14.9246212 C6.21751442,15.5104076 5.26776695,15.5104076 4.68198052,14.9246212 L2.56066017,12.8033009 C1.97487373,12.2175144 1.97487373,11.267767 2.56066017,10.6819805 Z M14.5606602,10.6819805 L16.6819805,8.56066017 C17.267767,7.97487373 18.2175144,7.97487373 18.8033009,8.56066017 L20.9246212,10.6819805 C21.5104076,11.267767 21.5104076,12.2175144 20.9246212,12.8033009 L18.8033009,14.9246212 C18.2175144,15.5104076 17.267767,15.5104076 16.6819805,14.9246212 L14.5606602,12.8033009 C13.9748737,12.2175144 13.9748737,11.267767 14.5606602,10.6819805 Z"
												fill="#000000" opacity="0.3"></path>
											<path
												d="M8.56066017,16.6819805 L10.6819805,14.5606602 C11.267767,13.9748737 12.2175144,13.9748737 12.8033009,14.5606602 L14.9246212,16.6819805 C15.5104076,17.267767 15.5104076,18.2175144 14.9246212,18.8033009 L12.8033009,20.9246212 C12.2175144,21.5104076 11.267767,21.5104076 10.6819805,20.9246212 L8.56066017,18.8033009 C7.97487373,18.2175144 7.97487373,17.267767 8.56066017,16.6819805 Z M8.56066017,4.68198052 L10.6819805,2.56066017 C11.267767,1.97487373 12.2175144,1.97487373 12.8033009,2.56066017 L14.9246212,4.68198052 C15.5104076,5.26776695 15.5104076,6.21751442 14.9246212,6.80330086 L12.8033009,8.9246212 C12.2175144,9.51040764 11.267767,9.51040764 10.6819805,8.9246212 L8.56066017,6.80330086 C7.97487373,6.21751442 7.97487373,5.26776695 8.56066017,4.68198052 Z"
												fill="#000000"></path>
										</g>
									</svg>
									<!--end::Svg Icon-->
								</span>
							</div>
							<strong style="font-size: 17px; padding-left:25px;">' . $value->nama_label . '</strong>
						</div>
					</div>
					</a>
				</div>
				<div class="col-xl-3 text-right">
					<a class="btn btn-info btn-sm font-weight-bold" href="' . base_url() . 'survei-list/' . $value->link_penayang . '" target="_blank"><i class="fa fa-desktop"></i> Lihat Penayangan</a>
				</div>
			
			</div>
        </div>';
			$data[] = $row;
		}

		$output = array(
			"draw" 				=> $_POST['draw'],
			"recordsTotal" 		=> $this->models->count_all($user_id),
			"recordsFiltered" 	=> $this->models->count_filtered($user_id),
			"data" 				=> $data,
		);

		echo json_encode($output);
	}


	public function add()
	{
		$this->load->library('uuid');
		$this->load->helper('slug');

		$this->data = [];
		$this->data['title'] 		= 'Tambah Label Penayangan';
		$data_user = $this->_cek_user()->row();


		$this->form_validation->set_rules('nama_label', 'Nama Label', 'trim|required');
		$this->form_validation->set_rules('kata_pembuka', 'Kata Pembuka', 'trim|required');
		$this->form_validation->set_rules('link_penayang', 'Link Penayang', 'trim|required');
		$this->form_validation->set_rules('jenis_penayang', 'Jenis Penayang', 'trim|required');
		$this->form_validation->set_rules('list_survei[]', 'List Survei', 'trim|required');

		$this->data['nama_label'] = [
			'name' 		=> 'nama_label',
			'id'		=> 'nama_label',
			'type'		=> 'text',
			'value'		=>	$this->form_validation->set_value('nama_label'),
			'class'		=> 'form-control',
			'required' => 'required',
			'autofocus' => 	'autofocus'
		];


		$this->data['kata_pembuka'] = [
			'name' 		=> 'kata_pembuka',
			'id'		=> 'kata_pembuka',
			'type'		=> 'text',
			'value'		=>	$this->form_validation->set_value('kata_pembuka'),
			'class'		=> 'form-control',
		];

		$this->data['kata_penutup'] = [
			'name' 		=> 'kata_penutup',
			'id'		=> 'kata_penutup',
			'type'		=> 'text',
			'value'		=>	$this->form_validation->set_value('kata_penutup'),
			'class'		=> 'form-control',
		];

		$this->data['link_penayang'] = [
			'name' 		=> 'link_penayang',
			'id'		=> 'link_penayang',
			'type'		=> 'text',
			'value'		=>	$this->form_validation->set_value('link_penayang'),
			'class'		=> 'form-control',
			'required' => 'required'
		];

		$this->data['manage_survey'] = $this->db->get_where('manage_survey', array('id_user' => $data_user->user_id));

		if ($this->form_validation->run() == FALSE) {

			return view('penayang_survei/add', $this->data);
		} else {

			$images_file_banner = $_FILES['benner']['name'];

			if ($images_file_banner != "") {
				$config['upload_path']     	= './assets/klien/benner_penayang/';
				$config['allowed_types']   	= 'jpg|png';
				$config['detect_mime']		= TRUE;
				$config['max_size']        	= 10000;
				$config['file_name'] 		= "benner";

				$this->load->library('upload', $config);
				$this->upload->initialize($config);

				if (!$this->upload->do_upload('benner')) {

					// print_r($this->upload->display_errors());

					$this->session->set_flashdata('message_danger', 'Terjadi kesalahan input gambar');
					redirect(base_url() . $this->session->userdata('username') .  '/penayang-survei', 'refresh');
				} else {

					$file_banner = $this->upload->data();
				}
			}
			$nama_file_banner = $file_banner['file_name'];

			$input 	= $this->input->post(NULL, TRUE);

			$object = [
				'id_user' => $data_user->user_id,
				'uuid' => $this->uuid->v4(),
				'nama_label' 	=> $input['nama_label'],
				'img_benner' => $nama_file_banner,
				'kata_pembuka' => $input['kata_pembuka'],
				'list_survei' 	=> serialize($input['list_survei']),
				'kata_penutup' => $input['kata_penutup'],
				'link_penayang' => 1,
				'jenis_penayang' => $input['jenis_penayang'],
			];
			$this->db->insert('penayang_survei', $object);

			$insert_id = $this->db->insert_id();
			$link_penayang = slug($input['link_penayang']);

			$cek = $this->db->get_where('penayang_survei', array('link_penayang' => "$link_penayang"));
			if ($cek->num_rows() == 0) {
				$link = $link_penayang;
			} else {
				$link = $link_penayang . '-' . $insert_id;
			};

			// LAKUKAN UPDATE LINK
			$last_object = [
				'link_penayang' => $link,
			];
			$this->db->where('id', $insert_id);
			$this->db->update('penayang_survei', $last_object);

			if ($this->db->affected_rows() > 0) {
				$this->session->set_flashdata('message_success', 'Berhasil menambah data');
				redirect(base_url() . $this->session->userdata('username') .  '/penayang-survei', 'refresh');
			} else {

				$this->data['message_data_danger'] = "Gagal menambah data";
				return view('penayang_survei/add', $this->data);
			}
		}
	}


	public function edit()
	{
		$this->load->library('uuid');
		$this->load->helper('slug');

		$this->data = [];
		$this->data['title'] 		= 'Edit Label Penayangan';
		$data_user = $this->_cek_user()->row();

		$current = $this->db->get_where('penayang_survei', array('id' => $this->uri->segment(4)))->row();
		$this->data['penayang_survei'] = $current;


		$this->form_validation->set_rules('nama_label', 'Nama Label', 'trim|required');
		$this->form_validation->set_rules('kata_pembuka', 'Kata Pembuka', 'trim|required');
		$this->form_validation->set_rules('link_penayang', 'Link Penayang', 'trim|required');
		$this->form_validation->set_rules('jenis_penayang', 'Jenis Penayang', 'trim|required');

		$this->data['nama_label'] = [
			'name' 		=> 'nama_label',
			'id'		=> 'nama_label',
			'type'		=> 'text',
			'value'		=>	$this->form_validation->set_value('nama_label', $current->nama_label),
			'class'		=> 'form-control',
			'required' => 'required',
			'autofocus' => 	'autofocus'
		];


		$this->data['kata_pembuka'] = [
			'name' 		=> 'kata_pembuka',
			'id'		=> 'kata_pembuka',
			'type'		=> 'text',
			'value'		=>	$this->form_validation->set_value('kata_pembuka', $current->kata_pembuka),
			'class'		=> 'form-control',
		];

		$this->data['kata_penutup'] = [
			'name' 		=> 'kata_penutup',
			'id'		=> 'kata_penutup',
			'type'		=> 'text',
			'value'		=>	$this->form_validation->set_value('kata_penutup', $current->kata_penutup),
			'class'		=> 'form-control',
		];

		$this->data['link_penayang'] = [
			'name' 		=> 'link_penayang',
			'id'		=> 'link_penayang',
			'type'		=> 'text',
			'value'		=>	$this->form_validation->set_value('link_penayang', $current->link_penayang),
			'class'		=> 'form-control',
			'required' => 'required'
		];

		$get_id = implode(",", unserialize($current->list_survei));
		$this->data['manage_survey'] = $this->db->query('SELECT * FROM manage_survey WHERE id IN (' . $get_id . ')');

		if ($this->form_validation->run() == FALSE) {

			return view('penayang_survei/edit', $this->data);
		} else {

			if ($_FILES['benner']['name'] != NULL) {

				$images_file_banner = $_FILES['benner']['name'];

				if ($images_file_banner != "") {
					$config['upload_path']     	= './assets/klien/benner_penayang/';
					$config['allowed_types']   	= 'jpg|png';
					$config['detect_mime']		= TRUE;
					$config['max_size']        	= 10000;
					$config['file_name'] 		= "benner";

					$this->load->library('upload', $config);
					$this->upload->initialize($config);

					if (!$this->upload->do_upload('benner')) {

						// print_r($this->upload->display_errors());

						$this->session->set_flashdata('message_danger', 'Terjadi kesalahan input gambar');
						redirect(base_url() . $this->session->userdata('username') .  '/penayang-survei', 'refresh');
					} else {

						$file_banner = $this->upload->data();
					}
				}
				$nama_file_banner = $file_banner['file_name'];

				$object_img = [
					'img_benner' => $nama_file_banner,
				];
				$this->db->where('id', $this->uri->segment(4));
				$this->db->update('penayang_survei', $object_img);
			}

			$input 	= $this->input->post(NULL, TRUE);

			$link_penayang = slug($input['link_penayang']);
			$cek = $this->db->get_where('penayang_survei', array('link_penayang' => "$link_penayang"));
			if ($cek->num_rows() == 0) {
				$link = $link_penayang;
			} else {
				$link = $link_penayang . '-' . $this->uri->segment(4);
			};

			$object = [
				'id_user' => $data_user->user_id,
				'uuid' => $this->uuid->v4(),
				'nama_label' 	=> $input['nama_label'],
				// 'img_benner' => $nama_file_banner,
				'kata_pembuka' => $input['kata_pembuka'],
				// 'list_survei' 	=> serialize($input['list_survei']),
				'kata_penutup' => $input['kata_penutup'],
				'link_penayang' => $link,
				'jenis_penayang' => $input['jenis_penayang'],
			];
			$this->db->where('id', $this->uri->segment(4));
			$this->db->update('penayang_survei', $object);

			if ($this->db->affected_rows() > 0) {
				$this->session->set_flashdata('message_success', 'Berhasil menambah data');
				redirect(base_url() . $this->session->userdata('username') .  '/penayang-survei', 'refresh');
			} else {

				$this->data['message_data_danger'] = "Gagal menambah data";
				return view('penayang_survei/edit', $this->data);
			}
		}
	}


	public function delete()
	{

		$current = $this->db->get_where('penayang_survei', array('id' => $this->uri->segment(4)))->row();

		unlink('./assets/klien/benner_penayang/' . $current->img_benner);

		$this->db->where('id', $this->uri->segment(4));
		$this->db->delete('penayang_survei');


		echo json_encode(array("status" => TRUE));
	}


	public function _cek_user()
	{
		$this->db->select('*');
		$this->db->from('users');
		$this->db->join('users_groups', 'users.id = users_groups.user_id');
		$this->db->where('users.username', $this->session->userdata('username'));
		return $this->db->get();
	}


	// public function _get_data_profile($id1, $id2)
	// {
	// 	$data_user = $this->_cek_user()->row();
	// 	$user_identity = 'drs' . $data_user->is_parent;

	// 	$this->db->select('users.username, manage_survey.survey_name, manage_survey.slug, is_publikasi_link_survei, manage_survey.description, manage_survey.is_privacy, manage_survey.survey_start, manage_survey.survey_end, manage_survey.kuesioner_name, manage_survey.id AS id_manage_survey, manage_survey.logo_survey, manage_survey.is_question, manage_survey.table_identity, manage_survey.jumlah_populasi, klasifikasi_survei.nama_klasifikasi_survei, id_jenis_pelayanan, nama_jenis_pelayanan_responden, sampling.nama_sampling, jumlah_sampling, manage_survey.atribut_pertanyaan_survey');

	// 	if ($data_user->group_id == 2) {
	// 		$this->db->from('users');
	// 		$this->db->join('manage_survey', 'manage_survey.id_user = users.id');
	// 		$this->db->join('jenis_pelayanan', 'manage_survey.id_jenis_pelayanan = jenis_pelayanan.id', 'left');
	// 		$this->db->join('klasifikasi_survei', 'klasifikasi_survei.id = users.id_klasifikasi_survei');
	// 		$this->db->join('sampling', 'sampling.id = manage_survey.id_sampling');
	// 		$this->db->where('users.username', $id1);
	// 		$this->db->where('manage_survey.slug', $id2);
	// 	} else {
	// 		$this->db->from('manage_survey');
	// 		$this->db->join("supervisor_$user_identity", "manage_survey.id_berlangganan = supervisor_$user_identity.id_berlangganan");
	// 		$this->db->join("users", "supervisor_$user_identity.id_user = users.id");
	// 		$this->db->join('jenis_pelayanan', 'manage_survey.id_jenis_pelayanan = jenis_pelayanan.id', 'left');
	// 		$this->db->join('klasifikasi_survei', 'klasifikasi_survei.id = users.id_klasifikasi_survei');
	// 		$this->db->join('sampling', 'sampling.id = manage_survey.id_sampling');
	// 		$this->db->where('users.username', $id1);
	// 		$this->db->where('manage_survey.slug', $id2);
	// 	}
	// 	$profiles = $this->db->get();
	// 	// var_dump($profiles->row());

	// 	if ($profiles->num_rows() == 0) {
	// 		// echo 'Survey tidak ditemukan atau sudah dihapus !';
	// 		// exit();
	// 		show_404();
	// 	}
	// 	return $profiles->row();
	// }
}

/* End of file ManageSurveyController.php */
/* Location: ./application/controllers/ManageSurveyController.php */