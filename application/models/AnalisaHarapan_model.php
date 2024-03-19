<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AnalisaHarapan_model extends CI_Model
{

    var $table             = '';
    var $column_order     = array(null, null, null, null, null);
    var $column_search     = array('nama_lengkap');
    var $order             = array('asc');

    public function __construct()
    {
        parent::__construct();
    }

    private function _get_datatables_query($table_identity, $id_unsur_pelayanan)
    {

        $this->db->select("*, (SELECT nama_kategori_unsur_pelayanan FROM kategori_unsur_pelayanan_$table_identity WHERE nomor_kategori_unsur_pelayanan = jawaban_pertanyaan_harapan_$table_identity.skor_jawaban && id_pertanyaan_unsur = jawaban_pertanyaan_harapan_$table_identity.id_pertanyaan_unsur) AS bobot");
        $this->db->from("jawaban_pertanyaan_harapan_$table_identity");
        $this->db->join("responden_$table_identity", "responden_$table_identity.id = jawaban_pertanyaan_harapan_$table_identity.id_responden");
        $this->db->join("survey_$table_identity", "responden_$table_identity.id = survey_$table_identity.id_responden");
        $this->db->join("pertanyaan_unsur_pelayanan_$table_identity", "jawaban_pertanyaan_harapan_$table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id");
        $this->db->where("pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan", $id_unsur_pelayanan);
        $this->db->where("survey_$table_identity.is_submit", 1);

        if ($this->input->post('skor_jawaban_harapan')) {
            $this->db->where("jawaban_pertanyaan_harapan_$table_identity.skor_jawaban", $this->input->post('skor_jawaban_harapan'));
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

    function get_datatables($table_identity, $id_unsur_pelayanan)
    {
        $this->_get_datatables_query($table_identity, $id_unsur_pelayanan);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered($table_identity, $id_unsur_pelayanan)
    {
        $this->_get_datatables_query($table_identity, $id_unsur_pelayanan);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all($table_identity, $id_unsur_pelayanan)
    {
        $this->db->from("jawaban_pertanyaan_harapan_$table_identity");
        $this->db->join("responden_$table_identity", "responden_$table_identity.id = jawaban_pertanyaan_harapan_$table_identity.id_responden");
        $this->db->join("survey_$table_identity", "responden_$table_identity.id = survey_$table_identity.id_responden");
        $this->db->join("pertanyaan_unsur_pelayanan_$table_identity", "jawaban_pertanyaan_harapan_$table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id");
        $this->db->where("pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan", $id_unsur_pelayanan);
        $this->db->where("survey_$table_identity.is_submit", 1);
        return $this->db->count_all_results();
    }
}

/* End of file Analisa_model.php */
/* Location: ./application/models/Analisa_model.php */