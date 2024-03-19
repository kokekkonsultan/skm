<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ProspekSurveyor_model extends CI_Model {

	var $table          = '';
    var $column_order   = array(null, null, null, null, null, null, null, null, null);
    var $column_search  = array('nama_lengkap', 'telepon', 'email');
    var $order          = array('id' => 'asc');

    public function __construct()
    {
        parent::__construct();
    }

    private function _get_datatables_query($table_identity, $user)
    {

        $this->db->from('data_prospek_survey_'.$table_identity);
        $this->db->where('id_user', $user);

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

    function get_datatables($table_identity, $user)
    {
        $this->_get_datatables_query($table_identity, $user);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered($table_identity, $user)
    {
        $this->_get_datatables_query($table_identity, $user);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all($table_identity, $user)
    {
        $this->db->from('data_prospek_survey_'.$table_identity);
        $this->db->where('id_user', $user);
        return $this->db->count_all_results();
    }

}

/* End of file DataProspekSurvey_model.php */
/* Location: ./application/models/DataProspekSurvey_model.php */