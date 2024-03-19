<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PertanyaanHarapanController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library('ion_auth');

        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message_warning', 'You must be logged in to access this page');
            redirect('auth', 'refresh');
        }
        $this->load->library('form_validation');
    }

    public function index()
    {
        $this->data = [];
        $this->data['title'] = "Pertanyaan Harapan";


        $this->data['pertanyaan_harapan'] = $this->db->query('SELECT pertanyaan_unsur_pelayanan.id AS id,
		pertanyaan_unsur_pelayanan.isi_pertanyaan_unsur,
		( SELECT  nilai_tingkat_kepentingan.nama_tingkat_kepentingan FROM nilai_tingkat_kepentingan  WHERE nilai_tingkat_kepentingan.nomor_tingkat_kepentingan = 1 AND nilai_tingkat_kepentingan.id_pertanyaan_unsur_pelayanan = pertanyaan_unsur_pelayanan.id ) AS pilihan_1,
		( SELECT  nilai_tingkat_kepentingan.nama_tingkat_kepentingan FROM nilai_tingkat_kepentingan  WHERE nilai_tingkat_kepentingan.nomor_tingkat_kepentingan = 2 AND nilai_tingkat_kepentingan.id_pertanyaan_unsur_pelayanan = pertanyaan_unsur_pelayanan.id ) AS pilihan_2,
		( SELECT  nilai_tingkat_kepentingan.nama_tingkat_kepentingan FROM nilai_tingkat_kepentingan  WHERE nilai_tingkat_kepentingan.nomor_tingkat_kepentingan = 3 AND nilai_tingkat_kepentingan.id_pertanyaan_unsur_pelayanan = pertanyaan_unsur_pelayanan.id ) AS pilihan_3,
		( SELECT  nilai_tingkat_kepentingan.nama_tingkat_kepentingan FROM nilai_tingkat_kepentingan  WHERE nilai_tingkat_kepentingan.nomor_tingkat_kepentingan = 4 AND nilai_tingkat_kepentingan.id_pertanyaan_unsur_pelayanan = pertanyaan_unsur_pelayanan.id ) AS pilihan_4
		FROM pertanyaan_unsur_pelayanan 
		JOIN unsur_pelayanan ON unsur_pelayanan .id = pertanyaan_unsur_pelayanan .id_unsur_pelayanan
		ORDER BY pertanyaan_unsur_pelayanan .id ASC')->result();

        $this->db->select('*');
        $this->db->from('nilai_tingkat_kepentingan');
        $this->data['prospek_surveyor'] = $this->db->get()->result();
        // $this->data['prospek_surveyor'] = $this->db->get_where('nilai_tingkat_kepentingan', ['id_user' => $user])->result();

        return view('pertanyaan_harapan/index', $this->data);
    }

    public function ajax_list()
    {
        $this->load->model('PertanyaanUnsurPelayanan_model');

        $list = $this->PertanyaanUnsurPelayanan_model->get_datatables();
        $data = array();
        $no = $_POST['start'];

        foreach ($list as $value) {

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = '<b>' . $value->nama_klasifikasi_survei . '</b><br>' . $value->nama_jenis_pelayanan_responden;
            $row[] = $value->nomor_unsur . '. ' . $value->nama_unsur_pelayanan;
            $row[] = $value->isi_pertanyaan_unsur;
            $row[] = '<a class="btn btn-light-primary btn-sm font-weight-bold shadow" data-toggle="modal" data-target="#pertanyaan_tambahan' . $value->id_pertanyaan_unsur_pelayanan . ' ">Detail</a>';

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->PertanyaanUnsurPelayanan_model->count_all(),
            "recordsFiltered" => $this->PertanyaanUnsurPelayanan_model->count_filtered(),
            "data" => $data,
        );

        echo json_encode($output);
    }

    public function cari()
    {
        $id = $_GET['id'];
        $cari = $this->PertanyaanUnsurPelayanan_model->cari($id)->result();
        echo json_encode($cari);
    }
}

/* End of file PertanyaanHarapanController.php */
/* Location: ./application/controllers/PertanyaanHarapanController.php */