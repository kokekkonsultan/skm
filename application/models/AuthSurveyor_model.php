<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AuthSurveyor_model extends CI_Model
{

	var $table 			= 'users_groups';
	var $column_order 	= array(null, null, null, null, null, null, null, null, null);
	var $column_search 	= array('users.first_name');
	var $order 			= array('users_groups.id' => 'desc');

	public function __construct()
	{
		parent::__construct();
	}

	private function _get_datatables_query()
	{
		$user = $this->ion_auth->user()->row();

		$this->db->from('users_groups');
		$this->db->join('groups', 'groups.id = users_groups.group_id');
		$this->db->join('users', 'users.id = users_groups.user_id');
		// $this->db->where('groups.name', 'surveyor');
		// $this->db->or_where('groups.name', 'surveyor_induk');
		$this->db->where('groups.name', 'surveyor_induk');
		$this->db->where('users.is_parent', $user->id);

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
		$user = $this->ion_auth->user()->row();
		
		$this->db->from('users_groups');
		$this->db->join('groups', 'groups.id = users_groups.group_id');
		$this->db->join('users', 'users.id = users_groups.user_id');
		// $this->db->where('groups.name', 'surveyor');
		// $this->db->or_where('groups.name', 'surveyor_induk');
		$this->db->where('groups.name', 'surveyor_induk');
		$this->db->where('users.is_parent', $user->id);

		return $this->db->count_all_results();
	}

	// public function getAllDataSurveyor()
	// {
	// 	$this->db->select('users.id, users.first_name');
	// 	$this->db->from('users');
	// 	$this->db->join('users_groups', 'users.id = users_groups.user_id');
	// 	$this->db->where('users_groups.group_id = 3 ');
	// 	$query = $this->db->get();
	// 	return $query;
	// }

	// public function getById($id)
	// {
	// 	return $this->db->get_where('users', ['id' => $id])->row_array();
	// }
}

/* End of file AuthSurveyor_model.php */
/* Location: ./application/models/AuthSurveyor_model.php */