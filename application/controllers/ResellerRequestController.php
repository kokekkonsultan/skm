<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ResellerRequestController extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		

	}

	public function index()
	{
		$this->data = [];
		$this->data['title'] = 'Permintaan Reseller';

		return view('reseller_request/index', $this->data);
	}

	public function ajax_list()
	{
		$this->load->model('ResellerRequest_model');

		$list = $this->ResellerRequest_model->get_datatables();
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $value) {

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $value->full_name;
			$row[] = $value->profession;
			$row[] = $value->organization;
			$row[] = date('d F Y, h:i A', strtotime($value->registration_time));
			$row[] = '<a class="text-primary" href="javascript:void(0)" title="Detail Artikel" onclick="showDetail(' . "'" . $value->id . "'" . ')">Detail</a>';
			$row[] = '<a class="text-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data(' . "'" . $value->id . "'" . ')">Delete</a>';

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->ResellerRequest_model->count_all(),
			"recordsFiltered" => $this->ResellerRequest_model->count_filtered(),
			"data" => $data,
		);

		echo json_encode($output);
	}

	public function get_detail()
	{
		$id = $this->input->post('id');
		$this->data = [];
		$this->data['id'] = $id;

		$this->db->select('*');
		$this->db->from('reseller_request');
		$this->db->where('id', $id);
		$this->data['data_resller_request'] = $this->db->get()->row();

		return view('reseller_request/form_detail', $this->data);

	}

	public function ajax_delete($id)
	{
		$cek_data = $this->db->get_where('reseller_request', ['id' => $id]);

		if ($cek_data->num_rows() > 0) {

			$this->db->where('id', $id);
			$this->db->delete('reseller_request');

			if ($this->db->affected_rows() > 0) {
				echo json_encode(array("status" => TRUE));
			}
		}

	}

}

/* End of file ResellerRequestController.php */
/* Location: ./application/controllers/ResellerRequestController.php */