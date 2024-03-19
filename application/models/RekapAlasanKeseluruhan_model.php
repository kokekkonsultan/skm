<?php
defined('BASEPATH') or exit('No direct script access allowed');

class RekapAlasanKeseluruhan_model extends CI_Model
{

    var $table          = '';
    var $column_order   = array(null, null, null, null, null, null, null, null, null);
    var $column_search  = array('alasan_pilih_jawaban');
    var $order          = array('id' => 'desc');

    public function __construct()
    {
        parent::__construct();
    }

    private function _get_datatables_query($tabel_union, $id_pertanyaan_unsur)
    {

        $this->db->select("*");
        $this->db->from("(SELECT *, 'null' AS is_submit FROM jawaban_pertanyaan_unsur $tabel_union) rspdn");
        $this->db->where('id_pertanyaan_unsur', $id_pertanyaan_unsur);
        $this->db->where('alasan_pilih_jawaban != ""');

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

    function get_datatables($tabel_union, $id_pertanyaan_unsur)
    {
        $this->_get_datatables_query($tabel_union, $id_pertanyaan_unsur);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered($tabel_union, $id_pertanyaan_unsur)
    {
        $this->_get_datatables_query($tabel_union, $id_pertanyaan_unsur);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all($tabel_union, $id_pertanyaan_unsur)
    {
        $this->db->from("(SELECT *, 'null' AS is_submit FROM jawaban_pertanyaan_unsur $tabel_union) rspdn");
        $this->db->where('id_pertanyaan_unsur', $id_pertanyaan_unsur);
        $this->db->where('alasan_pilih_jawaban != ""');
        return $this->db->count_all_results();
    }
}
