<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SelectDropdownController extends CI_Controller {

	public function getMenu()
	{
		$search = $_POST['searchTerm'];
		$page = $_POST['page'];
	    $resultCount = 10;
	    $end = ($page - 1) * $resultCount;       
	    $start = $end + $resultCount;

	    $this->db->select('*');
	    $this->db->from('menu_program');
		$this->db->order_by('id_menu_program', 'asc');
		$this->db->like('nama_menu_program', $search);
		$this->db->or_like('hastag_menu', $search, 'BOTH');
		$this->db->limit($start, $end);
		$stmt = $this->db->get();

		$data = array();
		$empty = array();

		$count = $stmt->num_rows();
		foreach ($stmt->result_array() as $key => $row) {
			$data[] = ['id'=>$row['link_menu_program'], 'text'=>$row['nama_menu_program'], 'total_count'=>$count];
		}

		if (empty($data)){
            $empty[] = ['id'=>'', 'text'=>'', 'total_count'=>'']; 
            echo json_encode($empty);
        }else{ 
            echo json_encode($data);
        }
	}

}

/* End of file SelectDropdownController.php */
/* Location: ./application/controllers/SelectDropdownController.php */