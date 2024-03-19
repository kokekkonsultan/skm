<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DataPerolehanKeseluruhan_model extends CI_Model
{

    var $table          = '';
    var $column_order   = array(null, null, null, null, null, null, null, null, null);
    var $column_search  = array('nama_lengkap');
    var $order          = array('');

    public function __construct()
    {
        parent::__construct();
    }

    private function _get_datatables_query($tabel_union)
    {

        // $this->data['log_survey'] = $this->db->query('SELECT * from log_survey ' . $tabel_union . ' ORDER BY log_time DESC
		// LIMIT 8')->result();


        $this->db->select("*");
        $this->db->from("(SELECT
                        id_responden,
                        '' AS nama_lengkap,
                        'saran',
                        'waktu_isi',
                        'is_submit',
                        'responden.uuid' AS uuid_responden, 
                        'null' AS nama_layanan,
                        'null' AS slug,
                        'null' AS table_identity,
                        'survey.id_surveyor' AS id_surveyor,
                         'null' AS is_end,
                        'surveyor.kode_surveyor' AS kode_surveyor,
                        'surveyor.first_name' AS first_name,
                        'surveyor.last_name' AS last_name,
                        'null' AS nama_depan_user,
                        'null' AS nama_belakang_user
                       

                            FROM responden
                            JOIN survey ON responden.id = survey.id_responden
                            LEFT JOIN surveyor ON survey.id_surveyor = surveyor.id
                            $tabel_union) rspdn");

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

    function get_datatables($tabel_union)
    {
        $this->_get_datatables_query($tabel_union);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered($tabel_union)
    {
        $this->_get_datatables_query($tabel_union);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all($tabel_union)
    {
        $this->db->from("(SELECT
                        id_responden,
                        '' AS nama_lengkap,
                        'saran',
                        'waktu_isi',
                        'is_submit',
                        'responden.uuid' AS uuid_responden, 
                        'null' AS nama_layanan,
                        'null' AS slug,
                        'null' AS table_identity,
                        'survey.id_surveyor' AS id_surveyor,
                        'null' AS is_end,
                        'surveyor.kode_surveyor' AS kode_surveyor,
                        'surveyor.first_name' AS first_name,
                        'surveyor.last_name' AS last_name,
                        'null' AS nama_depan_user,
                        'null' AS nama_belakang_user
                        
                            FROM responden
                            JOIN survey ON responden.id = survey.id_responden
                            LEFT JOIN surveyor ON survey.id_surveyor = surveyor.id
                            $tabel_union) rspdn");

        return $this->db->count_all_results();
    }
}