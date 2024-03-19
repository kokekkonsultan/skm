<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SurveiListController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $cek = $this->db->get_where('penayang_survei', ['link_penayang' => $this->uri->segment(2)]);
        if ($cek->num_rows() == 0) {
            show_404();
        }

        $this->load->library('form_validation');
    }

    public function index()
    {
        $this->data = [];
        $this->data['title'] = 'DAFTAR SURVEI';


        $slug = $this->uri->segment(2);
        $this->data['penayang_survei'] = $this->db->query("SELECT * FROM penayang_survei JOIN users ON penayang_survei.id_user = users.id WHERE link_penayang = '$slug'")->row();

        $get_id = implode(",", unserialize($this->data['penayang_survei']->list_survei));
        $this->data['manage_survey'] = $this->db->query('SELECT * FROM manage_survey WHERE id IN (' . $get_id . ')');

        return view('survei_list/index', $this->data);
    }
}