<?php
defined('BASEPATH') or exit('No direct script access allowed');

require 'application/core/Klien_Controller.php';
use application\core\Klien_Controller;

class RekapSaranController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library('ion_auth');

        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message_warning', 'You must be logged in to access this page');
            redirect('auth', 'refresh');
        }
        $this->load->model('RekapSaran_model', 'models');
    }
    public function index($id1, $id2)
    {
        $this->data = [];
        $this->data['title'] = "Rekap Saran";

        $profiles = new Klien_Controller();
        $this->data['profiles'] = $profiles->_get_data_profile($id1, $id2);
        $table_identity = $this->data['profiles']->table_identity;

        return view('rekap_saran/index', $this->data);
    }

    public function ajax_list()
    {
        $slug = $this->uri->segment(2);

        $table_identity = $this->db->get_where('manage_survey', array('slug' => $slug))->row()->table_identity;


        $list = $this->models->get_datatables($table_identity);
        $data = array();
        $no = $_POST['start'];

        foreach ($list as $value) {

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $value->nama_lengkap;
            $row[] = $value->saran;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->models->count_all($table_identity),
            "recordsFiltered" => $this->models->count_filtered($table_identity),
            "data" => $data,
        );

        echo json_encode($output);
    }

    /*public function cetak_pdf()
    {

        $this->data = [];
        $this->data['title'] = "Rekap Saran";

        $this->data['user'] = $this->ion_auth->user()->row();

        // get tabel identity
        $this->db->select('*');
        $this->db->from('manage_survey');
        $this->db->where('slug', $this->uri->segment(2));
        $this->data['manage_survey'] = $this->db->get()->row();
        $table_identity =  $this->data['manage_survey']->table_identity;

        $this->db->select('*');
        $this->db->from('survey_' . $table_identity);
        $this->db->join("responden_$table_identity", "survey_$table_identity.id_responden = responden_$table_identity.id");
        $this->db->join("barang_jasa", "responden_$table_identity.id_barang_jasa = barang_jasa.id");
        $this->db->where('is_submit', 1);
        $this->db->where('saran != ""');
        $this->data['saran'] = $this->db->get();

        $this->load->library('pdfgenerator');
        $this->data['title_pdf'] = 'Report Inovasi dan Saran';
        $file_pdf = 'Report Inovasi dan Saran';
        $paper = 'A4';
        $orientation = "potrait";

        $html = $this->load->view('rekap_saran/cetak_pdf', $this->data, true);

        $this->pdfgenerator->generate($html, $file_pdf, $paper, $orientation);
    }*/

    public function cetak_pdf()
    {

        $this->data = [];
        $this->data['title'] = "Rekap Saran";

        $this->data['user'] = $this->ion_auth->user()->row();

        // get tabel identity
        $this->db->select('*');
        $this->db->from('manage_survey');
        $this->db->where('slug', $this->uri->segment(2));
        $this->data['manage_survey'] = $this->db->get()->row();
        $table_identity =  $this->data['manage_survey']->table_identity;

        $this->db->select('*');
        $this->db->from('survey_' . $table_identity);
        $this->db->join("responden_$table_identity", "survey_$table_identity.id_responden = responden_$table_identity.id");
        // $this->db->join("barang_jasa", "responden_$table_identity.id_barang_jasa = barang_jasa.id");
        $this->db->where('is_submit', 1);
        $this->db->where('saran != ""');
        $this->data['saran'] = $this->db->get();

        $this->load->library('pdfgenerator');
        $this->data['title_pdf'] = 'Report Inovasi dan Saran';
        $file_pdf = 'Report Inovasi dan Saran';
        $paper = 'A4';
        $orientation = "potrait";

        $html = $this->load->view('rekap_saran/cetak_pdf', $this->data, true);

        $this->pdfgenerator->generate($html, $file_pdf, $paper, $orientation);
    }

    public function batch_pdf()
    {
        $this->load->helper('form');

        $this->data = [];

        $this->data['title'] = 'Batch Pdf';

        $this->data['user'] = $this->ion_auth->user()->row();

        // get tabel identity
        $this->db->select('*');
        $this->db->from('manage_survey');
        $this->db->where('slug', $this->uri->segment(2));
        $this->data['manage_survey'] = $this->db->get()->row();
        $table_identity =  $this->data['manage_survey']->table_identity;

        $this->db->select('*');
        $this->db->from('survey_' . $table_identity);
        $this->db->join("responden_$table_identity", "survey_$table_identity.id_responden = responden_$table_identity.id");
        // $this->db->join("barang_jasa", "responden_$table_identity.id_barang_jasa = barang_jasa.id");
        $this->db->where('is_submit', 1);
        $this->db->where('saran != ""');
        $this->data['data'] = $this->db->get();


        $this->data['collection'] = $this->data['data']->result_array();

        $this->data['jumlah_data'] = $this->data['data']->num_rows();

        $this->data['per_page'] = 300;

        return view('rekap_saran/batch_pdf', $this->data);
    }

    public function create_pdf()
    {
        $data_arr = $this->input->post('arr');


        // (DARI REQUEST)
        $arr = explode(",", $data_arr);
        $jumlah = count($arr);

        $this->data['first_number'] = reset($arr) + 1;

        $this->data['user'] = $this->ion_auth->user()->row();

        // get tabel identity
        $this->db->select('*');
        $this->db->from('manage_survey');
        $this->db->where('slug', $this->uri->segment(2));
        $this->data['manage_survey'] = $this->db->get()->row();

        $table_identity =  $this->data['manage_survey']->table_identity;

        $this->db->select('*');
        $this->db->from('survey_' . $table_identity);
        $this->db->join("responden_$table_identity", "survey_$table_identity.id_responden = responden_$table_identity.id");
        $this->db->join("barang_jasa", "responden_$table_identity.id_barang_jasa = barang_jasa.id");
        $this->db->where('is_submit', 1);
        $this->db->where('saran != ""');
        $data = $this->db->get();
        $collection = $data->result_array();

        // jadikan id ke array terlebih dahulu (DARI DB)
        $arr_coll = [];
        foreach ($collection as $key => $value) {
            array_push($arr_coll, $key);
        }


        // cek jika data ada yg dihapus, maka akhiri
        $arr_record = [];
        foreach ($arr as $key => $value) {

            if (!in_array($value, $arr_coll)) {
                echo "Something wrong, array id " . $value . " not found";
                exit();
            }
        }

        $this->data['arr'] = $arr;
        $this->data['jumlah'] = $jumlah;
        $this->data['collection'] = $collection;



        $this->load->library('pdfgenerator');
        $this->data['title_pdf'] = 'Report Inovasi dan Saran';
        $file_pdf = 'Report Inovasi dan Saran';
        $paper = 'A4';
        $orientation = "potrait";

        $html = $this->load->view('rekap_saran/create_pdf', $this->data, true);

        $this->pdfgenerator->generate($html, $file_pdf, $paper, $orientation);
    }
}
