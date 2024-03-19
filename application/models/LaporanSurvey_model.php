<?php
class LaporanSurvey_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function getAllFiles()
    {
        $query = $this->db->get('files');
        return $query->result();
    }

    public function insertfile($file)
    {
        return $this->db->insert('files', $file);
    }
}