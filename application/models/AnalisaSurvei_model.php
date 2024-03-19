<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AnalisaSurvei_model extends CI_Model
{
    var $table             = '';
    var $column_order     = array(null, null, null, null, null);
    var $column_search     = array('nama_unsur_pelayanan');
    var $order             = array('asc');

    public function __construct()
    {
        parent::__construct();
    }

    private function _get_datatables_query($table_identity)
    {
        $this->db->select("*, analisa_$table_identity.id AS id_analisa");
        $this->db->from('analisa_' . $table_identity);
        $this->db->join("unsur_pelayanan_$table_identity", "analisa_$table_identity.id_unsur_pelayanan = unsur_pelayanan_$table_identity.id");

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
        $this->db->from('analisa_' . $table_identity);
        $this->db->join("unsur_pelayanan_$table_identity", "analisa_$table_identity.id_unsur_pelayanan = unsur_pelayanan_$table_identity.id");
        return $this->db->count_all_results();
    }

    public function dropdown_unsur_pelayanan($table_identity)
    {
        $this->db->select("*, unsur_pelayanan_$table_identity.id AS id_unsur_pelayanan");
        $this->db->from("unsur_pelayanan_$table_identity");
        $this->db->where("NOT EXISTS (SELECT * FROM analisa_$table_identity WHERE unsur_pelayanan_$table_identity.id = analisa_$table_identity.id_unsur_pelayanan)", null, false);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $dd[''] = 'Please Select';
            foreach ($query->result_array() as $row) {
                $dd[$row['id_unsur_pelayanan']] = $row['nomor_unsur'] . '. ' . $row['nama_unsur_pelayanan'];
            }


            return $dd;
        }
    }
}
    

/* End of file UnsurPelayanan_model.php */
/* Location: ./application/models/UnsurPelayanan_model.php */