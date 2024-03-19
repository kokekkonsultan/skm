<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ProfilRespondenSurvei_model extends CI_Model
{

    var $table             = '';
    var $column_order     = array(null, null, null, null, null);
    var $column_search     = array('nama_profil_responden');
    var $order             = array('is_urutan' => 'asc');


    public function __construct()
    {
        parent::__construct();
    }

    private function _get_datatables_query($table_identity)
    {
        $this->db->select("*, IF(urutan != '',urutan,id) AS is_urutan");
        $this->db->from("profil_responden_$table_identity");
        // $this->db->from("(SELECT '0' AS id, 'Nama Lengkap' AS nama_profil_responden, '' AS jenis_isian, '' AS is_default, '' AS type_data UNION SELECT id, nama_profil_responden, jenis_isian, is_default, type_data FROM profil_responden_$table_identity) AS pro_res_$table_identity");

        // $this->db->from('profil_responden_' . $table_identity);

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

    function count_filtered($table_identity)
    {
        $this->_get_datatables_query($table_identity);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all($table_identity)
    {
        $this->db->from("profil_responden_$table_identity");
        return $this->db->count_all_results();
    }
}
    

/* End of file UnsurPelayanan_model.php */
/* Location: ./application/models/UnsurPelayanan_model.php */