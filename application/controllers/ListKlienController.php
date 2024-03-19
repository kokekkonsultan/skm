<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Carbon\Carbon;

class ListKlienController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message_warning', 'You must be an admin to view this page');
            redirect('auth', 'refresh');
        }

        $this->load->library('form_validation');
        $this->load->model('ListKlien_model', 'models');
    }

    public function index()
    {
        $this->data = [];
        $this->data['title'] = 'List Klien';

        return view('list_klien/index', $this->data);
    }

    public function ajax_list()
    {

        $user = $this->ion_auth->user()->row();

        $list = $this->models->get_datatables($user->id);
        $data = array();
        $no = $_POST['start'];

        $now = Carbon::now();
        foreach ($list as $value) {

            $start_date = Carbon::parse($value->tanggal_mulai);
            $end_date = Carbon::parse($value->tanggal_selesai);
            $due_date = $now->diffInDays($end_date);

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $value->first_name . ' ' . $value->last_name;
            $row[] = '<span class="badge badge-secondary">' . $value->nama_paket . '</span>';

            if ($now->between($start_date, $end_date)) {
                $row[] = '<span class="text-success">Paket berakhir dalam <b>' . $due_date . ' hari</b> lagi</span>';
            } else {
                $row[] = '<span class="text-danger">Packet is Expired</span>';
            }

            $row[] = '<a class="btn btn-light-primary font-weight-bold" href="' . base_url() . $value->username . '/info-berlangganan" target="_blank"><i class="fa fa-info-circle"></i> Detail Berlangganan</a>';

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->models->count_all($user->id),
            "recordsFiltered" => $this->models->count_filtered($user->id),
            "data" => $data,
        );

        echo json_encode($output);
    }
}