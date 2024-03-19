<?php
defined('BASEPATH') or exit('No direct script access allowed');

class LayananSurvei_model extends CI_Model
{

    var $table             = '';
    var $column_order     = array(null, null, null, null, null);
    var $column_search     = array('nama_layanan', 'nama_kategori_layanan');
    var $order             = array('urutan' => 'asc');


    public function __construct()
    {
        parent::__construct();
    }

    private function _get_datatables_query($table_identity)
    {
        $this->db->select("layanan_survei_$table_identity.*, kategori_layanan_survei_$table_identity.nama_kategori_layanan");
        $this->db->from("layanan_survei_$table_identity");
        $this->db->join("kategori_layanan_survei_$table_identity", "kategori_layanan_survei_$table_identity.id = layanan_survei_$table_identity.id_kategori_layanan", "left");
		
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
        $this->db->from("layanan_survei_$table_identity");
        return $this->db->count_all_results();
    }
}
    

/* End of file LayananSurvei_model.php */
/* Location: ./application/models/LayananSurvei_model.php */