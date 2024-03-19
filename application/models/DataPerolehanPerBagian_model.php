<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DataPerolehanPerBagian_model extends CI_Model
{

	var $table 				= '';
	var $column_order 		= array(null, null);
	var $column_search 		= array('manage_survey.survey_name');
	var $order 				= array('manage_survey.id' => 'desc');

	public function __construct()
	{
		parent::__construct();
	}

	private function _get_datatables_query($parent)
	{
		$this->db->select('*, manage_survey.slug AS slug_manage_survey, (SELECT first_name FROM users WHERE id = manage_survey.id_user) AS first_name, (SELECT last_name FROM users WHERE id = manage_survey.id_user) AS last_name');
		$this->db->from('manage_survey');
		$this->db->where("id_user IN ($parent)");


		if ($this->input->post('is_privacy')) {
			$this->db->where('is_privacy', $this->input->post('is_privacy'));
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

	function get_datatables($parent)
	{
		$this->_get_datatables_query($parent);
		if ($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered($parent)
	{
		$this->_get_datatables_query($parent);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all($parent)
	{
		$this->db->from('manage_survey');
		$this->db->where("id_user IN ($parent)");
		return $this->db->count_all_results();
	}
}


/* End of file ManageSurvey_model.php */
/* Location: ./application/models/ManageSurvey_model.php */
