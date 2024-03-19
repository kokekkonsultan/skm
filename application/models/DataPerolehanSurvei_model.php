<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DataPerolehanSurvei_model extends CI_Model
{

    var $table          = '';
    var $column_order   = array(null, null, null, null, null, null, null, null, null);
    var $column_search  = array('waktu_isi');
    var $order          = array('id_responden' => 'desc');

    public function __construct()
    {
        parent::__construct();
    }

    private function _get_datatables_query($table_identity, $profil_responden)
    {

        $data_profil = [];
        foreach ($profil_responden as $get) {
            if ($get->jenis_isian == 1) {

                $data_profil[] = "(SELECT nama_kategori_profil_responden FROM kategori_profil_responden_$table_identity WHERE responden_$table_identity.$get->nama_alias = kategori_profil_responden_$table_identity.id) AS $get->nama_alias";
            } else {
                $data_profil[] = $get->nama_alias;
            }
        }
        $query_profil = implode(",", $data_profil);

        $this->db->select("*, id_responden,
        is_submit,
        responden_$table_identity.uuid AS uuid_responden,
        waktu_isi,
        (SELECT first_name FROM users WHERE users.id = surveyor.id_user) AS first_name,
        (SELECT last_name FROM users WHERE users.id = surveyor.id_user) AS last_name, 
        (SELECT first_name FROM users WHERE users.id = surveyor_induk.id_user) AS first_name_surveyor_induk,
        (SELECT last_name FROM users WHERE users.id = surveyor_induk.id_user) AS last_name_surveyor_induk, 
        (SELECT nama_layanan FROM layanan_survei_$table_identity WHERE id_layanan_survei = layanan_survei_$table_identity.id) AS nama_layanan,
        $query_profil");
        $this->db->from("responden_$table_identity");
        $this->db->join("survey_$table_identity", "responden_$table_identity.id = survey_$table_identity.id_responden");
        $this->db->join("surveyor", "survey_$table_identity.id_surveyor = surveyor.id", "left");
        $this->db->join("surveyor_induk", "survey_$table_identity.id_surveyor_induk = surveyor_induk.id", "left");
        // $this->db->join("users", "surveyor.id_user  = users.id", "left");

        if ($this->input->post('is_submit')) {
            $this->db->where('is_submit', $this->input->post('is_submit'));
        }

        if ($this->input->post('is_surveyor')) {
            $this->db->where('IF(id_surveyor = 0,0,1) =' . $this->input->post('is_surveyor'));
        }

        if ($this->input->post('is_tanggal_start')) {
            $tanggal_start = date("Y/m/d", strtotime($this->input->post('is_tanggal_start')));
            $this->db->where("DATE(waktu_isi) >= DATE('$tanggal_start')");
        }

        if ($this->input->post('is_tanggal_end')) {
            $tanggal_end = date("Y/m/d", strtotime($this->input->post('is_tanggal_end')));
            $this->db->where("DATE(waktu_isi) <= DATE('$tanggal_end')");
        }

        //PANGGIL PROFIL RESPONDEN UNTUK FILTER
        $profil_responden_filter = $this->db->query("SELECT *,  REPLACE(LOWER(nama_profil_responden), ' ', '_') AS nama_alias FROM profil_responden_$table_identity WHERE jenis_isian = 1");

        foreach ($profil_responden_filter->result() as $value) {
            if ($this->input->post("$value->nama_alias")) {
                $this->db->where("responden_$table_identity.$value->nama_alias", $this->input->post("$value->nama_alias"));
            }
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
        $this->db->from("responden_$table_identity");
        $this->db->join("survey_$table_identity", "responden_$table_identity.id = survey_$table_identity.id_responden");
        $this->db->join("surveyor", "survey_$table_identity.id_surveyor = surveyor.id", "left");
        // $this->db->join("users", "surveyor.id_user  = users.id", "left");

        return $this->db->count_all_results();
    }
}
