<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AnalisaUnsur_model extends CI_Model
{

    var $table             = '';
    var $column_order     = array();
    var $column_search     = array('nama_lengkap');
    var $order             = array('asc');

    public function __construct()
    {
        parent::__construct();
    }

    private function _get_datatables_query($table_identity, $id_unsur_pelayanan, $profil_responden, $id_layanan_survei)
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

        $this->db->select("*, survey_$table_identity.id AS id_survei, jawaban_pertanyaan_unsur_$table_identity.id AS id_jawaban_pertanyaan_unsur, (SELECT nama_kategori_unsur_pelayanan FROM kategori_unsur_pelayanan_$table_identity WHERE nomor_kategori_unsur_pelayanan = jawaban_pertanyaan_unsur_$table_identity.skor_jawaban && id_pertanyaan_unsur = jawaban_pertanyaan_unsur_$table_identity.id_pertanyaan_unsur) AS bobot, $query_profil");
        $this->db->from("jawaban_pertanyaan_unsur_$table_identity");
        $this->db->join("responden_$table_identity", "responden_$table_identity.id = jawaban_pertanyaan_unsur_$table_identity.id_responden");
        $this->db->join("survey_$table_identity", "responden_$table_identity.id = survey_$table_identity.id_responden");
        $this->db->join("pertanyaan_unsur_pelayanan_$table_identity", "jawaban_pertanyaan_unsur_$table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id");
        $this->db->where("pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan", $id_unsur_pelayanan);
        $this->db->where("survey_$table_identity.is_submit", 1);
        $this->db->where("responden_$table_identity.id_layanan_survei", $id_layanan_survei);
        $this->db->where_in("jawaban_pertanyaan_unsur_$table_identity.skor_jawaban", array(1,2));

        if ($this->input->post('skor_jawaban_unsur')) {
            $this->db->where("jawaban_pertanyaan_unsur_$table_identity.skor_jawaban", $this->input->post('skor_jawaban_unsur'));
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

    function get_datatables($table_identity, $id_unsur_pelayanan, $profil_responden, $id_layanan_survei)
    {
        $this->_get_datatables_query($table_identity, $id_unsur_pelayanan, $profil_responden, $id_layanan_survei);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered($table_identity, $id_unsur_pelayanan, $profil_responden, $id_layanan_survei)
    {
        $this->_get_datatables_query($table_identity, $id_unsur_pelayanan, $profil_responden, $id_layanan_survei);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all($table_identity, $id_unsur_pelayanan, $profil_responden, $id_layanan_survei)
    {
        $this->db->from("jawaban_pertanyaan_unsur_$table_identity");
        $this->db->join("responden_$table_identity", "responden_$table_identity.id = jawaban_pertanyaan_unsur_$table_identity.id_responden");
        $this->db->join("survey_$table_identity", "responden_$table_identity.id = survey_$table_identity.id_responden");
        $this->db->join("pertanyaan_unsur_pelayanan_$table_identity", "jawaban_pertanyaan_unsur_$table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id");
        $this->db->where("pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan", $id_unsur_pelayanan);
        $this->db->where("survey_$table_identity.is_submit", 1);
        $this->db->where("responden_$table_identity.id_layanan_survei", $id_layanan_survei);
        $this->db->where_in("jawaban_pertanyaan_unsur_$table_identity.skor_jawaban", array(1,2));
        return $this->db->count_all_results();
    }
}

/* End of file Analisa_model.php */
/* Location: ./application/models/Analisa_model.php */