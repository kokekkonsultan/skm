<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SertifikatIndukKeseluruhanController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message_warning', 'You must be an admin to view this page');
            redirect('auth', 'refresh');
        }
        $this->load->library('form_validation');
    }

    public function index()
    {
        $this->data = [];
        $this->data['title'] = 'E-Sertifikat Keseluruhan';

        $this->data['induk'] = $this->db->get_where("pengguna_klien_induk", array('id_user' => $this->session->userdata('user_id')))->row();

        return view('sertifikat_keseluruhan/index', $this->data);
    }

    public function cetak()
    {
        $this->data = [];
        $this->data['title'] = "E-Sertifikat";

        $this->data['user'] = $this->ion_auth->user()->row();
        $this->data['induk'] = $this->db->get_where("pengguna_klien_induk", array('id_user' => $this->session->userdata('user_id')))->row();


        $get = $this->input->get(NULL, TRUE);
        $this->data['nama'] = $get['nama'];
        $this->data['jabatan'] = $get['jabatan'];
        $this->data['model_sertifikat'] = $get['model_sertifikat'];
        $this->data['periode'] = $get['periode'];
        $this->data['tahun'] = $get['tahun'];


        //------------------------------CETAK-------------------------//
        $this->load->library('pdfgenerator');
        $this->data['title_pdf'] = 'SERTIFIKAT E-SKM';
        $file_pdf = 'SERTIFIKAT E-SKM';
        $paper = 'A4';
        $orientation = "potrait";

        $html = $this->load->view('sertifikat_keseluruhan/cetak', $this->data, true);

        $this->pdfgenerator->generate($html, $file_pdf, $paper, $orientation);
    }
}

/* End of file SertifikatIndukKeseluruhanController.php */
/* Location: ./application/controllers/SertifikatIndukKeseluruhanController.php */
