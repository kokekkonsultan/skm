<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Berlangganan_model extends CI_Model
{
	var $table             = '';
	var $column_order     = array(null, null, null, null, null);
	var $column_search     = array('nama_paket');
	var $order             = array('berlangganan.id' => 'desc');

	public function __construct()
	{
		parent::__construct();
	}

	private function _get_datatables_query($id_user)
	{
		$this->db->select("*");
		$this->db->from('berlangganan');
		$this->db->join('paket', 'berlangganan.id_paket = paket.id');

		if ($this->uri->segment(1) != '') {
			$this->db->where('berlangganan.id_user', $id_user);
		}

		$i = 0;

		foreach ($this->column_search as $item) {
			if ($_POST['search']['value']) {

				if ($i === 0) {
					$this->db->group_start();
					$this->db->like($item, $_POST['search']['value']);
				} else {
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if (count($this->column_search) - 1 == $i)
					$this->db->group_end();
			}
			$i++;
		}

		if (isset($_POST['order'])) {
			$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} else if (isset($this->order)) {
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function get_datatables($id_user)
	{
		$this->_get_datatables_query($id_user);
		if ($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered($id_user)
	{
		$this->_get_datatables_query($id_user);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all($id_user)
	{
		$this->db->from('berlangganan');
		$this->db->join('paket', 'berlangganan.id_paket = paket.id');

		if ($this->uri->segment(1) != '') {
			$this->db->where('berlangganan.id_user', $id_user);
		}

		return $this->db->count_all_results();
	}



	public function dropdown_users()
	{
		$query = $this->db->get('users');

		if ($query->num_rows() > 0) {

			$dd[''] = 'Please Select';
			foreach ($query->result_array() as $row) {
				$dd[$row['id']] = $row['first_name'] . ' ' . $row['last_name'];
			}

			return $dd;
		}
	}

	public function dropdown_paket()
	{
		$query = $this->db->get('paket');

		if ($query->num_rows() > 0) {

			$dd[''] = 'Please Select';
			foreach ($query->result_array() as $row) {
				$dd[$row['id']] = $row['nama_paket'] . ' - ' . $row['panjang_hari'] . ' - ' . $row['harga_paket'];
			}

			return $dd;
		}
	}

	public function dropdown_metode_pembayaran()
	{
		$query = $this->db->get('metode_pembayaran');

		if ($query->num_rows() > 0) {

			$dd[''] = 'Please Select';
			foreach ($query->result_array() as $row) {
				$dd[$row['id']] = $row['nama_metode_pembayaran'];
			}

			return $dd;
		}
	}
}
    

/* End of file UnsurPelayanan_model.php */
/* Location: ./application/models/UnsurPelayanan_model.php */