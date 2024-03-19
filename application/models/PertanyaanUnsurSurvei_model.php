<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PertanyaanUnsurSurvei_model extends CI_Model
{

    var $table             = '';
    var $column_order     = array(null, null, null, null, null);
    var $column_search     = array('nama_unsur_pelayanan');
    // var $order             = array('nomor_unsur' => 'asc');

    public function __construct()
    {
        parent::__construct();
    }

    private function _get_datatables_query($table_identity)
    {
        $this->db->select("*, pertanyaan_unsur_pelayanan_$table_identity.id AS id_pertanyaan_unsur, (SELECT nama_kategori_unsur_pelayanan FROM kategori_unsur_pelayanan_$table_identity WHERE id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id && nomor_kategori_unsur_pelayanan = 1) AS pilihan_1,
        (SELECT nama_kategori_unsur_pelayanan FROM kategori_unsur_pelayanan_$table_identity WHERE id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id && nomor_kategori_unsur_pelayanan = 2) AS pilihan_2,
        (SELECT nama_kategori_unsur_pelayanan FROM kategori_unsur_pelayanan_$table_identity WHERE id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id && nomor_kategori_unsur_pelayanan = 3) AS pilihan_3,
        (SELECT nama_kategori_unsur_pelayanan FROM kategori_unsur_pelayanan_$table_identity WHERE id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id && nomor_kategori_unsur_pelayanan = 4) AS pilihan_4, 
        (SELECT nama_kategori_unsur_pelayanan FROM kategori_unsur_pelayanan_$table_identity WHERE id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id && nomor_kategori_unsur_pelayanan = 5) AS pilihan_5, 
        pertanyaan_unsur_pelayanan_$table_identity.jenis_pilihan_jawaban AS pilihan, 
        (SELECT COUNT(jawaban_pertanyaan_unsur_$table_identity.id) FROM jawaban_pertanyaan_unsur_$table_identity JOIN survey_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = survey_$table_identity.id_responden WHERE id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id && is_submit = 1 && alasan_pilih_jawaban != '' && jawaban_pertanyaan_unsur_$table_identity.is_active = 1 && skor_jawaban IN (1,2)) AS jumlah_alasan, unsur_pelayanan_$table_identity.id AS id_unsur, if(id_unsur_pelayanan != '', 1, 2) AS unsur_turunan, (SELECT nomor_pertanyaan_terbuka FROM pertanyaan_terbuka_$table_identity WHERE pertanyaan_terbuka_$table_identity.id_unsur_pelayanan = unsur_pelayanan_$table_identity.id GROUP BY id_unsur_pelayanan) AS is_pertanyaan_terbuka, SUBSTR(nomor_unsur,2) AS nomor_harapan");

        if ($this->uri->segment(3) == 'pertanyaan-unsur' || $this->uri->segment(1) == 'data-survey-klien') {
            $this->db->from("unsur_pelayanan_$table_identity");
            $this->db->join("pertanyaan_unsur_pelayanan_$table_identity", "pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan = unsur_pelayanan_$table_identity.id", 'left');
        } else {
            $this->db->from("pertanyaan_unsur_pelayanan_$table_identity");
            $this->db->join("unsur_pelayanan_$table_identity", "pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan = unsur_pelayanan_$table_identity.id");
        }
        $this->db->order_by("SUBSTR(nomor_unsur,2) + 0");


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
        if ($this->uri->segment(3) == 'pertanyaan-unsur' || $this->uri->segment(1) == 'data-survey-klien') {
            $this->db->from("unsur_pelayanan_$table_identity");
            $this->db->join("pertanyaan_unsur_pelayanan_$table_identity", "pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan = unsur_pelayanan_$table_identity.id", 'left');
        } else {
            $this->db->from("pertanyaan_unsur_pelayanan_$table_identity");
            $this->db->join("unsur_pelayanan_$table_identity", "pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan = unsur_pelayanan_$table_identity.id");
        }
        return $this->db->count_all_results();
    }

    public function dropdown_unsur_pelayanan()
    {
        $this->db->select('');
        $this->db->from('manage_survey');
        $this->db->where('manage_survey.slug', $this->uri->segment(2));
        $table_identity_manage_survey = $this->db->get()->row()->table_identity;

        $this->db->select("*, unsur_pelayanan_$table_identity_manage_survey.id AS id_unsur_pelayanan");
        $this->db->from("unsur_pelayanan_$table_identity_manage_survey");
        $this->db->where('id_parent', 0);

        if ($this->uri->segment(5) != NULL) {
            $this->db->where('id !=' . $this->uri->segment(5));
        }


        $query = $this->db->get();
        if ($query->num_rows() > 0) {

            $dd[''] = 'Please Select';
            foreach ($query->result_array() as $row) {
                $dd[$row['id_unsur_pelayanan']] = $row['nomor_unsur'] . '. ' . $row['nama_unsur_pelayanan'];
            }


            return $dd;
        }
    }

    function tampil_data($skala_likert)
    {
        $this->db->select('*');
        $this->db->from('pilihan_jawaban_pertanyaan_harapan');
        $this->db->where('skala_likert', $skala_likert);
        return $this->db->get();
        // return $this->db->get('unsur_pelayanan');
    }

    function cari($id)
    {
        $this->db->select('*');
        $this->db->from('pilihan_jawaban_pertanyaan_harapan');
        $this->db->where('id', $id);
        return $this->db->get();

        // $query = $this->db->get_where('unsur_pelayanan', array('id' => $id));
        // return $query;
    }

    public function dropdown_sub_unsur_pelayanan($table_identity)
    {
        $query = $this->db->query("SELECT *
        FROM (SELECT unsur_pelayanan_$table_identity.id AS id_unsur, nomor_unsur, nama_unsur_pelayanan, if(id_unsur_pelayanan != '', 1, 2) AS unsur_turunan FROM unsur_pelayanan_$table_identity
        LEFT JOIN pertanyaan_unsur_pelayanan_$table_identity ON unsur_pelayanan_$table_identity.id = pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan) as unsur_modif
        WHERE unsur_modif.unsur_turunan = 2");

        if ($query->num_rows() > 0) {

            $dd[''] = 'Please Select';
            foreach ($query->result_array() as $row) {
                $dd[$row['id_unsur']] = $row['nomor_unsur'] . '. ' . $row['nama_unsur_pelayanan'];
            }

            return $dd;
        }
    }
}
    

/* End of file UnsurPelayanan_model.php */
/* Location: ./application/models/UnsurPelayanan_model.php */
