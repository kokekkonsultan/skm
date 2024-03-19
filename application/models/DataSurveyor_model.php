<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DataSurveyor_model extends CI_Model
{

    var $table          = '';
    var $column_order   = array(null, null, null, null, null, null, null, null, null);
    var $column_search  = array('kode_surveyor', 'users.first_name');//surveyor.kode_surveyor
    var $order          = array('users.first_name' => 'asc');//surveyor.id

    public function __construct()
    {
        parent::__construct();
    }

    private function _get_datatables_query($id_manage_survey)
    {

        $this->db->select("*, (SELECT COUNT(survey_cst$id_manage_survey.id) FROM survey_cst$id_manage_survey WHERE survey_cst$id_manage_survey.id_surveyor = surveyor.id && is_submit = 1) AS total_survey, surveyor.uuid AS uuid_surveyor");
        $this->db->from('surveyor');
        $this->db->join('users', 'surveyor.id_user = users.id');
        $this->db->where('surveyor.id_manage_survey', $id_manage_survey);
        $total_surveyor = $this->db->get()->num_rows;

		if($total_surveyor > 0){
			$this->db->select("*, (SELECT COUNT(survey_cst$id_manage_survey.id) FROM survey_cst$id_manage_survey WHERE survey_cst$id_manage_survey.id_surveyor = surveyor.id && is_submit = 1) AS total_survey, surveyor.uuid AS uuid_surveyor");
            $this->db->from('surveyor');
            $this->db->join('users', 'surveyor.id_user = users.id');
            $this->db->where('surveyor.id_manage_survey', $id_manage_survey);
		}else{
			$this->db->select("*, (SELECT COUNT(survey_cst$id_manage_survey.id) FROM survey_cst$id_manage_survey WHERE survey_cst$id_manage_survey.id_surveyor_induk = surveyor_induk.id && is_submit = 1) AS total_survey, surveyor_induk.uuid AS uuid_surveyor");
            $this->db->from('surveyor_induk');
            $this->db->join('users', 'surveyor_induk.id_user = users.id');
            $this->db->where('surveyor_induk.id_manage_survey', $id_manage_survey);
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

    function get_datatables($table_identity)
    {
        $this->_get_datatables_query($table_identity);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered($id_manage_survey)
    {
        $this->_get_datatables_query($id_manage_survey);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all($id_manage_survey)
    {
        $this->db->from('surveyor');
        $this->db->join('users', 'surveyor.id_user = users.id');
        $this->db->where('surveyor.id_manage_survey', $id_manage_survey);
        $total_surveyor = $this->db->get()->num_rows;

		if($total_surveyor > 0){
			$this->db->from('surveyor');
            $this->db->join('users', 'surveyor.id_user = users.id');
            $this->db->where('surveyor.id_manage_survey', $id_manage_survey);
		}else{
			$this->db->from('surveyor_induk');
            $this->db->join('users', 'surveyor_induk.id_user = users.id');
            $this->db->where('surveyor_induk.id_manage_survey', $id_manage_survey);
		}

        return $this->db->count_all_results();
    }
}