<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PerincianPertanyaanTerbuka_model extends CI_Model
{

    var $table             = 'perincian_pertanyaan_terbuka';
    var $column_order     = array(null, null, null, null, null);
    var $column_search     = array('perincian_pertanyaan_terbuka.isi_pertanyaan_terbuka');
    var $order             = array('perincian_pertanyaan_terbuka.id' => 'desc');

    public function __construct()
    {
        parent::__construct();
    }

    private function _get_datatables_query()
    {
        $this->db->select('*, perincian_pertanyaan_terbuka.id AS id_perincian_pertanyaan_terbuka');
        $this->db->from('perincian_pertanyaan_terbuka');
        $this->db->join('pertanyaan_terbuka', 'pertanyaan_terbuka.id = perincian_pertanyaan_terbuka.id_pertanyaan_terbuka');
        $this->db->join('unsur_pelayanan', 'unsur_pelayanan.id = pertanyaan_terbuka.id_unsur_pelayanan');
        $this->db->join('jenis_pelayanan', 'jenis_pelayanan.id = unsur_pelayanan.id_jenis_pelayanan');
        $this->db->join('klasifikasi_survei', 'klasifikasi_survei.id = jenis_pelayanan.id_klasifikasi_survei');

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

    function get_datatables()
    {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->select('*');
        $this->db->from('perincian_pertanyaan_terbuka');
        $this->db->join('pertanyaan_terbuka', 'pertanyaan_terbuka.id = perincian_pertanyaan_terbuka.id_pertanyaan_terbuka');
        $this->db->join('unsur_pelayanan', 'unsur_pelayanan.id = pertanyaan_terbuka.id_unsur_pelayanan');
        $this->db->join('jenis_pelayanan', 'jenis_pelayanan.id = unsur_pelayanan.id_jenis_pelayanan');
        $this->db->join('klasifikasi_survei', 'klasifikasi_survei.id = jenis_pelayanan.id_klasifikasi_survei');
        return $this->db->count_all_results();
    }


    public function dropdown_pertanyaan_terbuka()
    {
        $this->db->select('*, pertanyaan_terbuka.id AS id_pertanyaan_terbuka');
        $this->db->from('pertanyaan_terbuka');
        $this->db->join('unsur_pelayanan', 'unsur_pelayanan.id = pertanyaan_terbuka.id_unsur_pelayanan');
        $this->db->join('jenis_pelayanan', 'jenis_pelayanan.id = unsur_pelayanan.id_jenis_pelayanan');
        $this->db->join('klasifikasi_survei', 'klasifikasi_survei.id = jenis_pelayanan.id_klasifikasi_survei');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $dd[''] = 'Please Select';
            foreach ($query->result_array() as $row) {
                $dd[$row['id_pertanyaan_terbuka']] = $row['nama_klasifikasi_survei'] . ' - ' . $row['nama_jenis_pelayanan_responden'] . ' - ' . $row['nama_unsur_pelayanan'] . ' - ' . $row['nama_pertanyaan_terbuka'];
            }

            return $dd;
        }
    }

    public function get_isi_pertanyaan_ganda($id)
    {
        $query = $this->db->query("SELECT * , isi_pertanyaan_ganda.id AS id_isi_pertanyaan_ganda
                                    FROM `isi_pertanyaan_ganda`
                                    WHERE isi_pertanyaan_ganda.id_perincian_pertanyaan_terbuka = '$id'");
        return $query->result();
    }
}

/* End of file JenisPelayanan_model.php */
/* Location: ./application/models/JenisPelayanan_model.php */