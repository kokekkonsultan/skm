<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AuthKlien_model extends CI_Model
{

	var $table 			= 'users_groups';
	var $column_order 	= array(null, null, null, null, null, null, null, null);
	var $column_search 	= array('users.first_name');
	var $order 			= array('users_groups.id' => 'desc');

	public function __construct()
	{
		parent::__construct();
	}

	private function _get_datatables_query()
	{
		$this->db->from('users_groups');
		$this->db->join('groups', 'groups.id = users_groups.group_id');
		$this->db->join('users', 'users.id = users_groups.user_id');
		$this->db->join('klasifikasi_survei', 'users.id_klasifikasi_survei = klasifikasi_survei.id');
		$this->db->where('groups.name', 'client');

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

	function get_datatables()
	{
		$this->_get_datatables_query();
		if ($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all()
	{
		$this->db->from('users_groups');
		$this->db->join('groups', 'groups.id = users_groups.group_id');
		$this->db->join('users', 'users.id = users_groups.user_id');
		$this->db->where('groups.name', 'members');

		return $this->db->count_all_results();
	}

	public function dropdown_klasifikasi_survei()
	{
		$query = $this->db->get('klasifikasi_survei');

		if ($query->num_rows() > 0) {

			$dd[''] = 'Please Select';
			foreach ($query->result_array() as $row) {
				$dd[$row['id']] = $row['nama_klasifikasi_survei'];
			}

			return $dd;
		}
	}

	public function dropdown_paket()
	{
		$this->db->select('*');
		$this->db->from('paket');
		$this->db->where('is_active', '1');
		$this->db->where('is_trial', '0');
		$query = $this->db->get();

		if ($query->num_rows() > 0) {

			$dd[''] = 'Please Select';
			foreach ($query->result_array() as $row) {
				$dd[$row['id']] = $row['nama_paket'] . ' - ' . $row['panjang_hari'] . ' Hari - Rp. ' . $row['harga_paket'] . ' ( ' . $row['jumlah_user'] . ' User - ' . $row['jumlah_kuesioner'] . ' Kuesioner )';
			}

			return $dd;
		}
	}

	public function dropdown_paket_berlangganan_trial()
	{
		$this->db->select('*');
		$this->db->from('paket');
		$this->db->where('is_active', '1');
		$query = $this->db->get();

		if ($query->num_rows() > 0) {

			$dd[''] = 'Please Select';
			foreach ($query->result_array() as $row) {
				$dd[$row['id']] = $row['nama_paket'] . ' - ' . $row['panjang_hari'] . ' Hari - Rp. ' . $row['harga_paket'] . ' ( ' . $row['jumlah_user'] . ' User - ' . $row['jumlah_kuesioner'] . ' Kuesioner )';
			}

			return $dd;
		}
	}

	public function dropdown_paket_trial()
	{
		$this->db->select('*');
		$this->db->from('paket');
		$this->db->where('is_active', '1');
		$this->db->where('is_trial', '1');
		$query = $this->db->get();

		if ($query->num_rows() > 0) {

			$dd[''] = 'Please Select';
			foreach ($query->result_array() as $row) {
				$dd[$row['id']] = $row['nama_paket'] . ' - ' . $row['panjang_hari'] . ' Hari - Rp. ' . $row['harga_paket'] . ' ( ' . $row['jumlah_user'] . ' User - ' . $row['jumlah_kuesioner'] . ' Kuesioner )';
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

	public function dropdown_reseller()
	{
		$query = $this->db->query("SELECT * FROM users JOIN users_groups ON users.id = users_groups.user_id WHERE users_groups.group_id = 4");

		if ($query->num_rows() > 0) {

			$dd[''] = 'Please Select';
			foreach ($query->result_array() as $row) {
				$dd[$row['user_id']] = 'R' . $row['user_id'] . ' -- ' . $row['first_name'] . ' ' . $row['last_name'];
			}

			return $dd;
		}
	}
}

/* End of file AuthKlien_model.php */
/* Location: ./application/models/AuthKlien_model.php */