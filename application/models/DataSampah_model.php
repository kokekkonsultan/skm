<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DataSampah_model extends CI_Model
{

    var $table          = '';
    var $column_order   = array(null, null, null, null, null, null, null, null, null);
    var $column_search  = array('nama_lengkap');
    var $order          = array('deleted_at' => 'desc');

    public function __construct()
    {
        parent::__construct();
    }

    private function _get_datatables_query($table_identity, $profil_responden)
    {

        $data_profil = [];
        foreach ($profil_responden as $get) {
            if ($get->jenis_isian == 1) {

                $data_profil[] = "(SELECT nama_kategori_profil_responden FROM kategori_profil_responden_$table_identity WHERE trash_responden_$table_identity.$get->nama_alias = kategori_profil_responden_$table_identity.id) AS $get->nama_alias";
            } else {
                $data_profil[] = $get->nama_alias;
            }
        }
        $query_profil = implode(",", $data_profil);

        $this->db->select("*, id_responden, nama_lengkap, is_submit,  trash_responden_$table_identity.uuid AS uuid_responden, waktu_isi, $query_profil");
        $this->db->from("trash_responden_$table_identity");
        $this->db->join("trash_survey_$table_identity", "trash_responden_$table_identity.id = trash_survey_$table_identity.id_responden");

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

    function get_datatables($table_identity, $profil_responden)
    {
        $this->_get_datatables_query($table_identity, $profil_responden);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered($table_identity, $profil_responden)
    {
        $this->_get_datatables_query($table_identity, $profil_responden);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all($table_identity, $profil_responden)
    {
        $this->db->from("trash_responden_$table_identity");
        $this->db->join("trash_survey_$table_identity", "trash_responden_$table_identity.id = trash_survey_$table_identity.id_responden");
        $this->db->join("surveyor", "trash_survey_$table_identity.id_surveyor = surveyor.id", "left");
        // $this->db->join("users", "surveyor.id_user  = users.id", "left");

        return $this->db->count_all_results();
    }
}