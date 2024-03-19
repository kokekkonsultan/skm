<?php
defined('BASEPATH') or exit('No direct script access allowed');

class UnsurPelayanan_model extends CI_Model
{

    var $table             = 'view_unsur_pelayanan';
    var $column_order     = array(null, null, null, null, null);
    var $column_search     = array('nama_unsur_pelayanan', 'nama_klasifikasi_survei');
    var $order             = array('');

    public function __construct()
    {
        parent::__construct();
    }

    private function _get_datatables_query()
    {
        // $this->db->select('*, unsur_pelayanan.id AS id_unsur_pelayanan');
        // $this->db->from('unsur_pelayanan');
        // $this->db->join('jenis_pelayanan', 'unsur_pelayanan.id_jenis_pelayanan = jenis_pelayanan.id');
        // $this->db->join('unsur_skm', 'unsur_pelayanan.id_unsur_skm = unsur_skm.id');
        // $this->db->join('klasifikasi_survei', 'klasifikasi_survei.id = jenis_pelayanan.id_klasifikasi_survei');

        if ($this->input->post('id_jenis_pelayanan')) {
            $this->db->where('id_jenis_pelayanan', $this->input->post('id_jenis_pelayanan'));
        }

        $this->db->from($this->table);

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
        // $this->db->select('*');
        // $this->db->from('unsur_pelayanan');
        // $this->db->join('jenis_pelayanan', 'jenis_pelayanan.id = unsur_pelayanan.id_jenis_pelayanan');
        // $this->db->join('klasifikasi_survei', 'klasifikasi_survei.id = jenis_pelayanan.id_klasifikasi_survei');

        $this->db->from($this->table);
        return $this->db->count_all_results();
    }


    public function dropdown_jenis_pelayanan()
    {
        $this->db->select('*, jenis_pelayanan.id AS id_jenis_pelayanan');
        $this->db->from('jenis_pelayanan');
        $this->db->join('klasifikasi_survei', 'klasifikasi_survei.id = jenis_pelayanan.id_klasifikasi_survei');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $dd[''] = 'Please Select';
            foreach ($query->result_array() as $row) {
                $dd[$row['id_jenis_pelayanan']] = $row['nama_klasifikasi_survei'] . ' -- ' . $row['nama_jenis_pelayanan_responden'];
            }

            return $dd;
        }
    }

    public function dropdown_unsur_skm()
    {
        $query = $this->db->get('unsur_skm');

        if ($query->num_rows() > 0) {

            $dd[''] = 'Please Select';
            foreach ($query->result_array() as $row) {
                $dd[$row['id']] = $row['nama_unsur_skm'];
            }

            return $dd;
        }
    }

    public function dropdown_unsur_pelayanan()
    {
        $this->db->select('*, unsur_pelayanan.id AS id_unsur_pelayanan');
        $this->db->from('unsur_pelayanan');
        $this->db->join('jenis_pelayanan', 'jenis_pelayanan.id = unsur_pelayanan.id_jenis_pelayanan');
        $this->db->join('klasifikasi_survei', 'klasifikasi_survei.id = jenis_pelayanan.id_klasifikasi_survei');
        $this->db->where('id_parent = 0');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $dd[''] = 'Please Select';
            foreach ($query->result_array() as $row) {
                $dd[$row['id_unsur_pelayanan']] = $row['nama_klasifikasi_survei'] . ' -- ' . $row['nama_jenis_pelayanan_responden'] . ' -- ' . $row['nomor_unsur'] . '. ' . $row['nama_unsur_pelayanan'];
            }

            return $dd;
        }
    }

    public function dropdown_unsur_pelayanan_by_id()
    {
        $this->db->select('*, unsur_pelayanan.id AS id_unsur_pelayanan');
        $this->db->from('unsur_pelayanan');
        $this->db->join('jenis_pelayanan', 'jenis_pelayanan.id = unsur_pelayanan.id_jenis_pelayanan');
        $this->db->join('klasifikasi_survei', 'klasifikasi_survei.id = jenis_pelayanan.id_klasifikasi_survei');
        $this->db->where('jenis_pelayanan.id', $this->uri->segment(4));
        $this->db->where('id_parent = 0');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $dd[''] = 'Please Select';
            foreach ($query->result_array() as $row) {
                $dd[$row['id_unsur_pelayanan']] = $row['nama_klasifikasi_survei'] . ' -- ' . $row['nama_jenis_pelayanan_responden'] . ' -- ' . $row['nomor_unsur'] . '. ' . $row['nama_unsur_pelayanan'];
            }

            return $dd;
        }
    }

    public function getAllUnsurPelayanan()
    {
        $this->db->select('');
        $this->db->from('unsur_pelayanan');
        $query = $this->db->get()->result();
        return $query;
    }
}
    

/* End of file UnsurPelayanan_model.php */
/* Location: ./application/models/UnsurPelayanan_model.php */