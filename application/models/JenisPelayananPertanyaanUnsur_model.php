<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class JenisPelayananPertanyaanUnsur_model extends CI_Model {

	var $table 			= 'jenis_pelayanan';
	var $column_order 	= array(null);
	var $column_search 	= array('nama_jenis_pelayanan_responden');
	var $order 			= array('id' => 'asc');

	public function __construct()
	{
		parent::__construct();
		
	}

	private function _get_datatables_query()
	{
		$this->db->select('*, jenis_pelayanan.id AS id_jenis_pelayanan');
		$this->db->from($this->table);

		if ($this->input->post('id_klasifikasi_survei')) {
            $this->db->where('jenis_pelayanan.id_klasifikasi_survei', $this->input->post('id_klasifikasi_survei'));
        }

		$i = 0;
	
		foreach ($this->column_search as $item)
		{
			if($_POST['search']['value'])
			{
				
				if($i===0)
				{
					$this->db->group_start();
					$this->db->like($item, $_POST['search']['value']);
				}
				else
				{
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if(count($this->column_search) - 1 == $i)
					$this->db->group_end();
			}
			$i++;
		}
		
		if(isset($_POST['order']))
		{
			$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($this->order))
		{
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function get_datatables()
	{
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
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
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}

}

/* End of file JenisPelayananPertanyaanUnsur_model.php */
/* Location: ./application/models/JenisPelayananPertanyaanUnsur_model.php */