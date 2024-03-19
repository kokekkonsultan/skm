<?php
defined('BASEPATH') or exit('No direct script access allowed');

class JenisSurvey_model extends CI_Model
{

    private $table = "jenis_survey";
    public function getAllJenisSurvey()
    {
        return $this->db->get($this->table)->result();
    }

    public function insert()
    {
        $nama_jenis_survey = trim(strtolower($this->input->post('nama_jenis_survey')));
        $out = explode(" ", $nama_jenis_survey);
        $slug = implode("-", $out);

        $this->db->insert('jenis_survey', ['nama_jenis_survey' => $nama_jenis_survey, 'slug' => $slug]);
        redirect('jenissurvey');
    }

    public function getById($id)
    {
        return $this->db->get_where('jenis_survey', ['id' => $id]);
    }

    public function getJenisSurveyById($id)
    {
        return $this->db->get_where('jenis_survey', ['id' => $id])->row();
    }

    // public function update()
    // {
    //     $id =  $this->input->post('id', true);
    //     $data = [
    //         'nama_jenis_survey' => $this->input->post('nama_jenis_survey', true) 
    //     ];

    //     $this->db->where('id', $id);
    //     $this->db->update('jenis_survey', $data);

    //     redirect('jenissurvey/index');
    // }



    public function update($id, $input)
    {

        $nama_jenis_survey = trim(strtolower($input['nama_jenis_survey']));
        $out = explode(" ", $nama_jenis_survey);
        $slug = implode("-", $out);

        $data = array(
            'nama_jenis_survey'  => $nama_jenis_survey,
            'slug' => $slug
        );
        $this->db->where('id', $id);
        $this->db->update('jenis_survey', $data);

        redirect(base_url() . 'jenissurvey', 'refresh');

        // if ($this->db->affected_rows() > 0) {
        // 	return true;
        // } else {
        // 	return false;
        // }

    }


    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('jenis_survey');
    }
}

/* End of file Auth_model.php */
/* Location: ./application/models/Auth_model.php */