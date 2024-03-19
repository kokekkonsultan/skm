<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ListKlien_model extends CI_Model
{
    var $table             = '';
    var $column_order     = array(null, null, null, null, null);
    var $column_search     = array('first_name', 'last_name');
    var $order             = array('id_user' => 'desc');

    public function __construct()
    {
        parent::__construct();
    }

    private function _get_datatables_query($user_id)
    {
        $this->db->select("*, (SELECT nama_paket FROM paket WHERE paket.id = berlangganan.id_paket) AS nama_paket");
        $this->db->from('users');
        $this->db->join('berlangganan', 'users.id = berlangganan.id_user');
        $this->db->where('users.is_reseller', $user_id);
        $this->db->where('id_status_berlangganan', 1);

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

    function get_datatables($user_id)
    {
        $this->_get_datatables_query($user_id);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered($user_id)
    {
        $this->_get_datatables_query($user_id);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all($user_id)
    {
        $this->db->from('users');
        $this->db->join('berlangganan', 'users.id = berlangganan.id_user');
        $this->db->where('users.is_reseller', $user_id);
        $this->db->where('id_status_berlangganan', 1);
        return $this->db->count_all_results();
    }
}
    

/* End of file UnsurPelayanan_model.php */
/* Location: ./application/models/UnsurPelayanan_model.php */