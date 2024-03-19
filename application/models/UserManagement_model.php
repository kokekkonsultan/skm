<?php
defined('BASEPATH') or exit('No direct script access allowed');

class UserManagement_model extends CI_Model
{
    var $table             = '';
    var $column_order     = array(null, null, null, null, null);
    var $column_search     = array('first_name', 'last_name');
    var $order             = array('users.id' => 'asc');

    public function __construct()
    {
        parent::__construct();
    }

    private function _get_datatables_query($user_identity)
    {
        $this->db->select("*, supervisor_$user_identity.id AS id_supervisor, supervisor_$user_identity.id_user AS id_users");
        $this->db->from("supervisor_$user_identity");
        $this->db->join("users", "supervisor_$user_identity.id_user = users.id");
        $this->db->join("berlangganan", "berlangganan.id = supervisor_$user_identity.id_berlangganan");
        $this->db->join("division_$user_identity", "supervisor_$user_identity.id_division = division_$user_identity.id");
        $this->db->where("berlangganan.uuid", $this->uri->segment(4));


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

    function get_datatables($user_identity)
    {
        $this->_get_datatables_query($user_identity);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered($user_identity)
    {
        $this->_get_datatables_query($user_identity);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all($user_identity)
    {
        $this->db->from("supervisor_$user_identity");
        $this->db->join("users", "supervisor_$user_identity.id_user = users.id");
        $this->db->join("berlangganan", "berlangganan.id = supervisor_$user_identity.id_berlangganan");
        $this->db->join("division_$user_identity", "supervisor_$user_identity.id_division = division_$user_identity.id");
        $this->db->where("berlangganan.uuid", $this->uri->segment(4));
        return $this->db->count_all_results();
    }


    public function dropdown_divisi($user_identity)
    {
        $this->db->select("*, division_$user_identity.id AS id_division");
        $this->db->from('division_' . $user_identity);
        $this->db->join("berlangganan", "berlangganan.id = division_$user_identity.id_berlangganan", 'left');
        $this->db->where("berlangganan.uuid", $this->uri->segment(4));
        $this->db->or_where("is_default", "1");
        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $dd[''] = 'Please Select';
            foreach ($query->result_array() as $row) {
                $dd[$row['id_division']] = $row['division_name'];
            }


            return $dd;
        }
    }
}
    

/* End of file UnsurPelayanan_model.php */
/* Location: ./application/models/UnsurPelayanan_model.php */