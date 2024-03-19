<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DetailPerolehanSurveyor_model extends CI_Model
{

    var $table          = '';
    var $column_order   = array(null, null, null, null, null, null, null, null, null);
    var $column_search  = array('nama_lengkap');
    var $order          = array('id_responden' => 'desc');

    public function __construct()
    {
        parent::__construct();
    }

    private function _get_datatables_query($table_identity, $id_surveyor)
    {

        $this->db->select("*, responden_$table_identity.uuid AS uuid_responden");
        $this->db->from("responden_$table_identity");
        $this->db->join("survey_$table_identity", "responden_$table_identity.id = survey_$table_identity.id_responden");
        $this->db->join("surveyor", "survey_$table_identity.id_surveyor = surveyor.id", "left");
        $this->db->join("users", "surveyor.id_user  = users.id", "left");
        $this->db->where('id_surveyor', $id_surveyor);
        $this->db->where('is_submit', 1);
        $total_surveyor = $this->db->get()->num_rows;

		if($total_surveyor > 0){
			$this->db->select("*, responden_$table_identity.uuid AS uuid_responden");
            $this->db->from("responden_$table_identity");
            $this->db->join("survey_$table_identity", "responden_$table_identity.id = survey_$table_identity.id_responden");
            $this->db->join("surveyor", "survey_$table_identity.id_surveyor = surveyor.id", "left");
            $this->db->join("users", "surveyor.id_user  = users.id", "left");
            $this->db->where('id_surveyor', $id_surveyor);
            $this->db->where('is_submit', 1);
		}else{
			$this->db->select("*, responden_$table_identity.uuid AS uuid_responden");
            $this->db->from("responden_$table_identity");
            $this->db->join("survey_$table_identity", "responden_$table_identity.id = survey_$table_identity.id_responden");
            $this->db->join("surveyor_induk", "survey_$table_identity.id_surveyor_induk = surveyor_induk.id", "left");
            $this->db->join("users", "surveyor_induk.id_user  = users.id", "left");
            $this->db->where('id_surveyor_induk', $id_surveyor);
            $this->db->where('is_submit', 1);
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

    function get_datatables($table_identity, $id_surveyor)
    {
        $this->_get_datatables_query($table_identity, $id_surveyor);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered($table_identity, $id_surveyor)
    {
        $this->_get_datatables_query($table_identity, $id_surveyor);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all($table_identity, $id_surveyor)
    {
        $this->db->from("responden_$table_identity");
        $this->db->join("survey_$table_identity", "responden_$table_identity.id = survey_$table_identity.id_responden");
        $this->db->join("surveyor", "survey_$table_identity.id_surveyor = surveyor.id", "left");
        $this->db->join("users", "surveyor.id_user  = users.id", "left");
        $this->db->where('id_surveyor', $id_surveyor);
        $this->db->where('is_submit', 1);
        $total_surveyor = $this->db->get()->num_rows;

		if($total_surveyor > 0){
			$this->db->from("responden_$table_identity");
            $this->db->join("survey_$table_identity", "responden_$table_identity.id = survey_$table_identity.id_responden");
            $this->db->join("surveyor", "survey_$table_identity.id_surveyor = surveyor.id", "left");
            $this->db->join("users", "surveyor.id_user  = users.id", "left");
            $this->db->where('id_surveyor', $id_surveyor);
            $this->db->where('is_submit', 1);
		}else{
			$this->db->from("responden_$table_identity");
            $this->db->join("survey_$table_identity", "responden_$table_identity.id = survey_$table_identity.id_responden");
            $this->db->join("surveyor_induk", "survey_$table_identity.id_surveyor_induk = surveyor_induk.id", "left");
            $this->db->join("users", "surveyor_induk.id_user  = users.id", "left");
            $this->db->where('id_surveyor_induk', $id_surveyor);
            $this->db->where('is_submit', 1);
		}
        return $this->db->count_all_results();
    }
}
