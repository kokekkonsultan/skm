<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PertanyaanSurveyController extends CI_Controller
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
        $this->load->model('PertanyaanUnsurSurvei_model');
        $this->load->model('PertanyaanTerbukaSurvei_model');
        $this->load->model('PertanyaanKualitatif_model');
        $this->load->model('PertanyaanHarapanSurvei_model');
    }

    public function index($id1, $id2)
    {
        $this->data = [];
        $this->data['title'] = "Pertanyaan Survey";
        $this->data['profiles'] = $this->_get_data_profile($id1, $id2);

        $this->data['is_question'] = $this->data['profiles']->is_question;

        $this->db->select('');
        $this->db->from('manage_survey');
        $this->db->where('manage_survey.slug', $this->uri->segment(2));
        $this->data['manage_survey'] = $this->db->get()->row();

        $this->data['pilihan_jawaban'] = $this->PertanyaanHarapanSurvei_model->tampil_data();

        return view('pertanyaan_survei/index', $this->data);
    }

    //------------------------------------PERTANYAAN UNSUR-----------------------

    public function ajax_list()
    {
        $slug = $this->uri->segment(2);

        $get_identity = $this->db->get_where('manage_survey', array('slug' => "$slug"))->row();
        $table_identity = $get_identity->table_identity;

        $list = $this->PertanyaanUnsurSurvei_model->get_datatables($table_identity);
        $data = array();
        $no = $_POST['start'];

        foreach ($list as $value) {

            if ($value->pilihan == 2) {
                $jawaban = '<label><input type="radio">&ensp;' . $value->pilihan_1 . '&emsp;</label><label><input type="radio">&ensp;' . $value->pilihan_2 . '&emsp;</label><label><input type="radio">&ensp;' . $value->pilihan_3 . '&emsp;</label><label><input type="radio">&ensp;' . $value->pilihan_4 . '&emsp;</label>';
            } else {
                $jawaban = '<label><input type="radio">&ensp;' . $value->pilihan_1 . '&emsp;</label><label><input type="radio">&ensp;' . $value->pilihan_4 . '&emsp;</label>';
            }

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = '<b>' . $value->nomor_unsur . '. ' . $value->nama_unsur_pelayanan . '</b>';
            $row[] = $value->isi_pertanyaan_unsur;
            $row[] = $jawaban;
            $row[] = anchor($this->session->userdata('username') . '/' . $this->uri->segment(2) . '/pertanyaan-unsur-survey/edit/' . $value->id_pertanyaan_unsur, '<i class="fa fa-edit"></i> Edit', ['class' => 'btn btn-light-primary btn-sm font-weight-bold shadow']);

            if ($get_identity->is_question == 1) {
                $row[] = '<a class="btn btn-light-primary btn-sm font-weight-bold shadow" href="javascript:void(0)" title="Hapus ' . $value->isi_pertanyaan_unsur . '" onclick="delete_data(' . "'" . $value->id_pertanyaan_unsur . "'" . ')"><i class="fa fa-trash"></i> Delete</a>';
            }

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->PertanyaanUnsurSurvei_model->count_all($table_identity),
            "recordsFiltered" => $this->PertanyaanUnsurSurvei_model->count_filtered($table_identity),
            "data" => $data,
        );

        echo json_encode($output);
    }

    public function add_unsur($id1, $id2)
    {
        $this->data = [];
        $this->data['title'] = "Tambah Pertanyaan Unsur";
        $this->load->library('uuid');

        $this->data['profiles'] = $this->_get_data_profile($id1, $id2);
        $id_jenis_pelayanan = $this->data['profiles']->id_jenis_pelayanan;

        $this->db->select('');
        $this->db->from('manage_survey');
        $this->db->where('manage_survey.slug', $this->uri->segment(2));
        $manage_survey = $this->db->get()->row();
        $table_identity_manage_survey = $manage_survey->table_identity;

        $this->db->select('');
        $this->db->from('unsur_pelayanan_' . $table_identity_manage_survey);
        $this->db->where("NOT EXISTS (SELECT * FROM pertanyaan_unsur_pelayanan_$table_identity_manage_survey WHERE unsur_pelayanan_$table_identity_manage_survey.id = pertanyaan_unsur_pelayanan_$table_identity_manage_survey.id_unsur_pelayanan)", null, false);
        $this->data['unsur_pelayanan'] = $this->db->get();

        $this->data['pilihan'] = $this->PertanyaanUnsurSurvei_model->tampil_data();


        // var_dump($is_privacy);

        $this->form_validation->set_rules('isi_pertanyaan_unsur', 'Isi Pertanyaan Unsur', 'trim|required');

        if ($this->form_validation->run() == FALSE) {


            $this->data['isi_pertanyaan_unsur'] = [
                'name'         => 'isi_pertanyaan_unsur',
                'id'        => 'isi_pertanyaan_unsur',
                'type'        => 'text',
                'value'        =>    $this->form_validation->set_value('isi_pertanyaan_unsur'),
                'class'        => 'form-control',
                'rows'         => '3'
            ];

            $this->data['pilihan_jawaban'] = [
                'name'         => 'pilihan_jawaban[]',
                'id'        => '',
                'type'        => 'text',
                'value'        =>    $this->form_validation->set_value('pilihan_jawaban'),
                'class'        => 'form-control',
                'placeholder' => 'Misalnya : Tidak Baik | Kurang Baik | Baik | Sangat Baik'
            ];

            $this->data['pilihan_jawaban_1'] = [
                'name'         => 'pilihan_jawaban_1',
                'id'        => '',
                'type'        => 'text',
                'value'        =>    $this->form_validation->set_value('pilihan_jawaban_1'),
                'class'        => 'form-control',
                'placeholder' => 'Misalnya : Ya | Tidak / Sudah | Belum'
            ];

            $this->data['pilihan_jawaban_2'] = [
                'name'         => 'pilihan_jawaban_2',
                'id'        => '',
                'type'        => 'text',
                'value'        =>    $this->form_validation->set_value('pilihan_jawaban_2'),
                'class'        => 'form-control',
                'placeholder' => 'Misalnya : Ya | Tidak / Sudah | Belum'
            ];

            return view('pertanyaan_survei/pertanyaan_unsur_survei/add', $this->data);
        } else {
            $input     = $this->input->post(NULL, TRUE);

            $id_unsur_pelayanan = $this->db->insert_id();
            $object_1 = [
                'isi_pertanyaan_unsur'     => $input['isi_pertanyaan_unsur'],
                'id_unsur_pelayanan'     => $input['id_unsur_pelayanan'],
                'jenis_pilihan_jawaban'     => $input['jenis_pilihan_jawaban']
            ];
            // var_dump($object_1);

            $this->db->insert('pertanyaan_unsur_pelayanan_' . $table_identity_manage_survey, $object_1);

            $id_pertanyaan_unsur = $this->db->insert_id();
            if ($this->input->post('jenis_pilihan_jawaban') == "2") {
                $result = array();
                foreach ($_POST['pilihan_jawaban'] as $key => $val) {
                    $no_next = $key + 1;
                    $result[] = array(
                        'id_pertanyaan_unsur' => $id_pertanyaan_unsur,
                        'id_unsur_pelayanan' => $input['id_unsur_pelayanan'],
                        'nomor_kategori_unsur_pelayanan' => $no_next,
                        'nama_kategori_unsur_pelayanan' => $_POST['pilihan_jawaban'][$key]
                    );
                }
                $this->db->insert_batch('kategori_unsur_pelayanan_' . $table_identity_manage_survey, $result);
            } else {
                $data = [
                    'id_pertanyaan_unsur' => $id_pertanyaan_unsur,
                    'id_unsur_pelayanan' => $input['id_unsur_pelayanan'],
                    'nomor_kategori_unsur_pelayanan' => '1',
                    'nama_kategori_unsur_pelayanan' => $input['pilihan_jawaban_1']
                ];

                $data_1 = [
                    'id_pertanyaan_unsur' => $id_pertanyaan_unsur,
                    'id_unsur_pelayanan' => $input['id_unsur_pelayanan'],
                    'nomor_kategori_unsur_pelayanan' => '4',
                    'nama_kategori_unsur_pelayanan' => $input['pilihan_jawaban_2']
                ];
                $this->db->insert('kategori_unsur_pelayanan_' . $table_identity_manage_survey, $data);
                $this->db->insert('kategori_unsur_pelayanan_' . $table_identity_manage_survey, $data_1);
            }

            $this->db->query("INSERT INTO nilai_tingkat_kepentingan_$table_identity_manage_survey (id_pertanyaan_unsur_pelayanan, nama_tingkat_kepentingan, nomor_tingkat_kepentingan)
            VALUES ($id_pertanyaan_unsur, 'Tidak Penting', '1'), ($id_pertanyaan_unsur, 'Kurang Penting', '2'), ($id_pertanyaan_unsur, 'Penting', '3'), ($id_pertanyaan_unsur, 'Sangat Penting', '4')");

            if ($this->db->affected_rows() > 0) {

                $this->session->set_flashdata('message_success', 'Berhasil menambah data');
                redirect(base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2) . '/pertanyaan-survey', 'refresh');
            } else {

                $this->data['message_data_danger'] = "Gagal menambah data";
                return view('pertanyaan_survei/pertanyaan_unsur_survei/add', $this->data);
            }
        }
    }

    public function edit_unsur($id1, $id2)
    {
        $this->data = [];
        $this->data['title'] = "Edit Pertanyaan Unsur";
        $this->load->library('uuid');

        $this->data['profiles'] = $this->_get_data_profile($id1, $id2);

        $this->db->select('');
        $this->db->from('manage_survey');
        $this->db->where('manage_survey.slug', $this->uri->segment(2));
        $table_identity_manage_survey = $this->db->get()->row()->table_identity;

        $this->db->select("*, pertanyaan_unsur_pelayanan_$table_identity_manage_survey.id AS id_pertanyaan_unsur");
        $this->db->from('pertanyaan_unsur_pelayanan_' . $table_identity_manage_survey);
        $this->db->join("unsur_pelayanan_$table_identity_manage_survey", "pertanyaan_unsur_pelayanan_$table_identity_manage_survey.id_unsur_pelayanan = unsur_pelayanan_$table_identity_manage_survey.id");
        $this->db->where("pertanyaan_unsur_pelayanan_$table_identity_manage_survey.id =", $this->uri->segment(5));
        $pertanyaan_unsur = $this->db->get()->row();

        $this->db->select('*');
        $this->db->from('kategori_unsur_pelayanan_' . $table_identity_manage_survey);
        $this->db->where('id_pertanyaan_unsur', $pertanyaan_unsur->id_pertanyaan_unsur);
        $this->data['nama_kategori_unsur'] = $this->db->get()->result();
        // var_dump($this->data['nama_kategori_unsur']);

        $this->form_validation->set_rules('isi_pertanyaan_unsur', 'Isi Pertanyaan Unsur', 'trim|required');

        $this->data['id_unsur_pelayanan'] = [
            'name'         => 'id_unsur_pelayanan',
            'id'         => 'id_unsur_pelayanan',
            'value'        =>    $this->form_validation->set_value('id_unsur_pelayanan', $pertanyaan_unsur->nomor_unsur . '. ' . $pertanyaan_unsur->nama_unsur_pelayanan),
            'class'     => "form-control",
            'autofocus' => 'autofocus',
            'disabled' => 'disabled'
        ];

        $this->data['isi_pertanyaan_unsur'] = [
            'name'         => 'isi_pertanyaan_unsur',
            'id'        => 'isi_pertanyaan_unsur',
            'type'        => 'text',
            'value'        =>    $this->form_validation->set_value('isi_pertanyaan_unsur', $pertanyaan_unsur->isi_pertanyaan_unsur),
            'class'        => 'form-control',
            'rows'         => '3',
            'autofocus' => 'autofocus'
        ];

        if ($this->form_validation->run() == FALSE) {

            return view('pertanyaan_survei/pertanyaan_unsur_survei/edit', $this->data);
        } else {
            $input = $this->input->post(NULL, TRUE);

            $object_1 = [
                'isi_pertanyaan_unsur'     => $input['isi_pertanyaan_unsur']
            ];
            $this->db->where('id', $pertanyaan_unsur->id_pertanyaan_unsur);
            $this->db->update('pertanyaan_unsur_pelayanan_' . $table_identity_manage_survey, $object_1);


            $id = $input['id_kategori'];
            $nama_kategori_input = $input['nama_kategori_unsur_pelayanan'];
            for ($i = 0; $i < sizeof($id); $i++) {
                $kategori = array(
                    'id' => $id[$i],
                    'nama_kategori_unsur_pelayanan' => $nama_kategori_input[$i]
                );
                $this->db->where('id', $id[$i]);
                $this->db->update('kategori_unsur_pelayanan_' . $table_identity_manage_survey, $kategori);
            }

            $this->session->set_flashdata('message_success', 'Berhasil mengubah data');
            redirect(base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2) . '/pertanyaan-survey', 'refresh');
        }
    }

    public function delete_unsur()
    {
        $this->data = [];
        $this->data['title'] = "Delete Pertanyaan Unsur";
        $this->load->library('uuid');


        $table_identity_manage_survey = $this->db->get_where('manage_survey', array('slug' => $this->uri->segment(2)))->row()->table_identity;
        // var_dump($table_identity_manage_survey);

        $this->db->where('id_pertanyaan_unsur', $this->uri->segment(5));
        $this->db->delete('kategori_unsur_pelayanan_' . $table_identity_manage_survey);

        $this->db->where('id', $this->uri->segment(5));
        $this->db->delete('pertanyaan_unsur_pelayanan_' . $table_identity_manage_survey);

        echo json_encode(array("status" => TRUE));
    }



    //------------------------------------PERTANYAAN TERBUKA-----------------------

    public function ajax_list_pertanyaan_terbuka_survei()
    {
        $slug = $this->uri->segment(2);

        $get_identity = $this->db->get_where('manage_survey', array('slug' => "$slug"))->row();
        $table_identity = $get_identity->table_identity;

        $list = $this->PertanyaanTerbukaSurvei_model->get_datatables($table_identity);
        $data = array();
        $no = $_POST['start'];

        foreach ($list as $value) {

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = '<b>' . $value->nomor_unsur . '. ' . $value->nama_unsur_pelayanan . '</b>';
            $row[] = '<b>' . $value->nomor_pertanyaan_terbuka . '. ' . $value->nama_pertanyaan_terbuka . '</b><br>' . $value->isi_pertanyaan_terbuka;
            $row[] = anchor($this->session->userdata('username') . '/' . $this->uri->segment(2) . '/pertanyaan-terbuka-survey/edit/' . $value->id_pertanyaan_terbuka, '<i class="fa fa-edit"></i> Edit', ['class' => 'btn btn-light-primary btn-sm font-weight-bold shadow']);

            if ($get_identity->is_question == 1) {
                $row[] = '<a class="btn btn-light-primary btn-sm font-weight-bold shadow" href="javascript:void(0)" title="Hapus ' . $value->nama_pertanyaan_terbuka . '" onclick="delete_pertanyaan_terbuka(' . "'" . $value->id_pertanyaan_terbuka . "'" . ')"><i class="fa fa-trash"></i> Delete</a>';
            }


            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->PertanyaanTerbukaSurvei_model->count_all($table_identity),
            "recordsFiltered" => $this->PertanyaanTerbukaSurvei_model->count_filtered($table_identity),
            "data" => $data,
        );

        echo json_encode($output);
    }

    public function add_terbuka($id1 = NULL, $id2 = NULL)
    {
        $this->data = [];
        $this->data['title'] = "Tambah Pertanyaan Tambahan";

        $this->data['profiles'] = $this->_get_data_profile($id1, $id2);

        $this->db->select('');
        $this->db->from('manage_survey');
        $this->db->where('manage_survey.slug', $this->uri->segment(2));
        $table_identity_manage_survey = $this->db->get()->row()->table_identity;

        $this->db->select('(COUNT(nomor_pertanyaan_terbuka)+1) AS nomor_terbuka');
        $this->db->from('pertanyaan_terbuka_' . $table_identity_manage_survey);
        $nomor_pertanyaan_terbuka = $this->db->get()->row()->nomor_terbuka;

        $this->form_validation->set_rules('nama_pertanyaan_terbuka', 'Nama Pertanyaan Terbuka', 'trim|required');
        $this->form_validation->set_rules('isi_pertanyaan_terbuka', 'Isi Pertanyaan Terbuka', 'trim|required');


        if ($this->form_validation->run() == FALSE) {

            $this->data['nama_pertanyaan_terbuka'] = [
                'name'         => 'nama_pertanyaan_terbuka',
                'id'        => 'nama_pertanyaan_terbuka',
                'type'        => 'text',
                'value'        =>    $this->form_validation->set_value('nama_pertanyaan_terbuka'),
                'class'        => 'form-control',
            ];

            $this->data['id_unsur_pelayanan'] = [
                'name'         => 'id_unsur_pelayanan',
                'id'         => 'id_unsur_pelayanan',
                'options'     => $this->PertanyaanTerbukaSurvei_model->dropdown_unsur_pelayanan(),
                'selected'     => $this->form_validation->set_value('id_unsur_pelayanan'),
                'class'     => "form-control",
                'autofocus' => 'autofocus'
            ];

            $this->data['isi_pertanyaan_terbuka'] = [
                'name'         => 'isi_pertanyaan_terbuka',
                'id'        => 'isi_pertanyaan_terbuka',
                'type'        => 'text',
                'value'        =>    $this->form_validation->set_value('isi_pertanyaann_terbuka'),
                'class'        => 'form-control',
                'rows'         => '3'
            ];

            return view('pertanyaan_survei/pertanyaan_terbuka_survei/add', $this->data);
        } else {

            $input     = $this->input->post(NULL, TRUE);

            $data = [
                'nomor_pertanyaan_terbuka'     => 'T' . $nomor_pertanyaan_terbuka,
                'nama_pertanyaan_terbuka'     => $input['nama_pertanyaan_terbuka'],
                'id_unsur_pelayanan'     => $input['id_unsur_pelayanan']
            ];
            // var_dump($data);
            $this->db->insert('pertanyaan_terbuka_' . $table_identity_manage_survey, $data);

            $id_pertanyaan_terbuka = $this->db->insert_id();
            if ($this->input->post('jenis_jawaban') == '2') {
                $object = [
                    'isi_pertanyaan_terbuka'     => $input['isi_pertanyaan_terbuka'],
                    'id_pertanyaan_terbuka'     => $id_pertanyaan_terbuka,
                    'id_jenis_pilihan_jawaban'     => $input['jenis_jawaban']
                ];
                $this->db->insert('perincian_pertanyaan_terbuka_' . $table_identity_manage_survey, $object);
            } else {
                $object = [
                    'isi_pertanyaan_terbuka'     => $input['isi_pertanyaan_terbuka'],
                    'id_pertanyaan_terbuka'     => $id_pertanyaan_terbuka,
                    'id_jenis_pilihan_jawaban'     => $input['jenis_jawaban']
                ];
                // var_dump($object);
                $this->db->insert('perincian_pertanyaan_terbuka_' . $table_identity_manage_survey, $object);

                $id_perincian_pertanyaan_terbuka = $this->db->insert_id();
                $pilihan_jawaban = $input['pilihan_jawaban'];
                $opsi_pilihan_jawaban = $input['opsi_pilihan_jawaban'];

                $result = array();
                foreach ($_POST['pilihan_jawaban'] as $key => $val) {
                    $result[] = array(
                        'id_perincian_pertanyaan_terbuka' => $id_perincian_pertanyaan_terbuka,
                        'pertanyaan_ganda' => $pilihan_jawaban[$key],
                        'dengan_isian_lainnya' => $opsi_pilihan_jawaban
                    );
                }
                $this->db->insert_batch('isi_pertanyaan_ganda_' . $table_identity_manage_survey, $result);
                // var_dump($result);
            }

            if ($this->db->affected_rows() > 0) {

                $this->session->set_flashdata('message_success', 'Berhasil menambah data');
                redirect(base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2) . '/pertanyaan-survey', 'refresh');
            } else {

                $this->data['message_data_danger'] = "Gagal menambah data";
                return view('pertanyaan_survei/pertanyaan_terbuka_survei/add', $this->data);
            }
        }
    }

    public function edit_terbuka($id1 = NULL, $id2 = NULL)
    {
        $this->data = [];
        $this->data['title'] = "Edit Pertanyaan Tambahan";

        $this->data['profiles'] = $this->_get_data_profile($id1, $id2);

        $this->db->select('');
        $this->db->from('manage_survey');
        $this->db->where('manage_survey.slug', $this->uri->segment(2));
        $table_identity_manage_survey = $this->db->get()->row()->table_identity;

        $this->db->select("*, pertanyaan_terbuka_$table_identity_manage_survey.id AS id_pertanyaan_terbuka");
        $this->db->from('pertanyaan_terbuka_' . $table_identity_manage_survey);
        $this->db->join("unsur_pelayanan_$table_identity_manage_survey", "pertanyaan_terbuka_$table_identity_manage_survey.id_unsur_pelayanan = unsur_pelayanan_$table_identity_manage_survey.id");
        $this->db->where("pertanyaan_terbuka_$table_identity_manage_survey.id =", $this->uri->segment(5));
        $this->data['search_data'] = $this->db->get();

        if ($this->data['search_data']->num_rows() == 0) {
            $this->session->set_flashdata('message_danger', 'Data tidak ditemukan');
            redirect($this->session->userdata('urlback'), 'refresh');
        }
        $this->data['current'] = $this->data['search_data']->row();

        $this->data['perincian'] = $this->db->get_where('perincian_pertanyaan_terbuka_' . $table_identity_manage_survey, ['id_pertanyaan_terbuka' => $this->uri->segment(5)])->row();
        // var_dump($this->data['perincian']);

        $id_pertanyaan_terbuka = $this->uri->segment(5);

        $query = $this->db->query("SELECT * , isi_pertanyaan_ganda_$table_identity_manage_survey.id AS id_isi_pertanyaan_ganda
		FROM isi_pertanyaan_ganda_$table_identity_manage_survey
		JOIN perincian_pertanyaan_terbuka_$table_identity_manage_survey ON isi_pertanyaan_ganda_$table_identity_manage_survey.id_perincian_pertanyaan_terbuka = perincian_pertanyaan_terbuka_$table_identity_manage_survey.id
		WHERE id_pertanyaan_terbuka = '$id_pertanyaan_terbuka'");
        $this->data['pilihan_jawaban'] = $query->result();

        // var_dump($this->data['pilihan_jawaban']);

        $this->form_validation->set_rules('nama_pertanyaan_terbuka', 'Nama Pertanyaan Terbuka', 'trim|required');
        $this->form_validation->set_rules('isi_pertanyaan_terbuka', 'Isi Pertanyaan Terbuka', 'trim|required');

        $this->data['nama_pertanyaan_terbuka'] = [
            'name'         => 'nama_pertanyaan_terbuka',
            'id'        => 'nama_pertanyaan_terbuka',
            'type'        => 'text',
            'value'        =>    $this->form_validation->set_value('nama_pertanyaan_terbuka', $this->data['current']->nama_pertanyaan_terbuka),
            'class'        => 'form-control',
            'autofocus' => 'autofocus'
        ];


        $this->data['id_unsur_pelayanan'] = [
            'name'         => 'id_unsur_pelayanan',
            'id'         => 'id_unsur_pelayanan',
            'value'        =>    $this->form_validation->set_value('id_unsur_pelayanan', $this->data['current']->nomor_unsur . '. ' . $this->data['current']->nama_unsur_pelayanan),
            'class'     => "form-control",
            'autofocus' => 'autofocus',
            'disabled' => 'disabled'
        ];

        $this->data['isi_pertanyaan_terbuka'] = [
            'name'         => 'isi_pertanyaan_terbuka',
            'id'        => 'isi_pertanyaan_terbuka',
            'type'        => 'text',
            'value'        =>    $this->form_validation->set_value('isi_pertanyaann_terbuka', $this->data['perincian']->isi_pertanyaan_terbuka),
            'class'        => 'form-control',
            'rows'         => '3'
        ];

        if ($this->form_validation->run() == FALSE) {

            return view('pertanyaan_survei/pertanyaan_terbuka_survei/edit', $this->data);
        } else {

            $input     = $this->input->post(NULL, TRUE);

            $data = [
                'nama_pertanyaan_terbuka'     => $input['nama_pertanyaan_terbuka']
            ];

            $this->db->where('id', $id_pertanyaan_terbuka);
            $this->db->update('pertanyaan_terbuka_' . $table_identity_manage_survey, $data);
            // var_dump($data);

            if ($this->input->post('id_jenis_jawaban') == '2') {
                $object = [
                    'isi_pertanyaan_terbuka'     => $input['isi_pertanyaan_terbuka']
                ];

                $this->db->where('id', $this->data['perincian']->id);
                $this->db->update('perincian_pertanyaan_terbuka_' . $table_identity_manage_survey, $object);
            } else {
                $object = [
                    'isi_pertanyaan_terbuka'     => $input['isi_pertanyaan_terbuka']
                ];

                $this->db->where('id', $this->data['perincian']->id);
                $this->db->update('perincian_pertanyaan_terbuka_' . $table_identity_manage_survey, $object);

                $id = $input['id_kategori'];
                $pertanyaan_ganda = $input['pertanyaan_ganda'];

                for ($i = 0; $i < sizeof($id); $i++) {
                    $kategori = array(
                        'id' => $id[$i],
                        'pertanyaan_ganda' => ($pertanyaan_ganda[$i])
                    );
                    $this->db->where('id', $id[$i]);
                    $this->db->update('isi_pertanyaan_ganda_' . $table_identity_manage_survey, $kategori);
                }
                // var_dump($kategori);
            }
            redirect(base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2) . '/pertanyaan-survey', 'refresh');
        }
    }

    public function delete_terbuka($id = NULL)
    {
        $this->db->select('');
        $this->db->from('manage_survey');
        $this->db->where('manage_survey.slug', $this->uri->segment(2));
        $table_identity_manage_survey = $this->db->get()->row()->table_identity;

        $id_pertanyaan_terbuka = $this->uri->segment('5');

        $query = $this->db->query("SELECT *, perincian_pertanyaan_terbuka_$table_identity_manage_survey.id AS id_perincian_pertanyaan_terbuka
		FROM pertanyaan_terbuka_$table_identity_manage_survey
		JOIN perincian_pertanyaan_terbuka_$table_identity_manage_survey ON pertanyaan_terbuka_$table_identity_manage_survey.id = perincian_pertanyaan_terbuka_$table_identity_manage_survey.id_pertanyaan_terbuka
		WHERE id_pertanyaan_terbuka = $id_pertanyaan_terbuka
		");
        $current = $query->row();
        // var_dump($current);

        $this->db->where('id_perincian_pertanyaan_terbuka', $current->id_perincian_pertanyaan_terbuka);
        $this->db->delete('isi_pertanyaan_ganda_' . $table_identity_manage_survey);

        $this->db->where('id', $current->id_pertanyaan_terbuka);
        $this->db->delete('perincian_pertanyaan_terbuka_' . $table_identity_manage_survey);

        $this->db->where('id', $current->id_pertanyaan_terbuka);
        $this->db->delete('pertanyaan_terbuka_' . $table_identity_manage_survey);

        echo json_encode(array("status" => TRUE));
    }


    //------------------------------------PERTANYAAN KUALITATIF-----------------------

    public function ajax_list_pertanyaan_kualitatif()
    {
        $slug = $this->uri->segment(2);

        $get_identity = $this->db->get_where('manage_survey', array('slug' => "$slug"))->row();
        $table_identity = $get_identity->table_identity;

        $list = $this->PertanyaanKualitatif_model->get_datatables($table_identity);
        $data = array();
        $no = $_POST['start'];

        foreach ($list as $value) {

            if ($value->is_active == 1) {
                $status = '<span class="badge badge-primary">Aktif</span>';
            } else {
                $status = '<span class="badge badge-danger">Tidak Aktif</span>';
            }

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $value->isi_pertanyaan;
            $row[] = $status;
            $row[] = anchor($this->session->userdata('username') . '/' . $this->uri->segment(2) . '/pertanyaan-kualitatif-survey/edit/' . $value->id, '<i class="fa fa-edit"></i> Edit', ['class' => 'btn btn-light-primary btn-sm font-weight-bold shadow']);

            if ($get_identity->is_question == 1) {
                $row[] = '<a class="btn btn-light-primary btn-sm font-weight-bold shadow" href="javascript:void(0)" title="Hapus ' . $value->isi_pertanyaan . '" onclick="delete_pertanyaan_kualitatif(' . "'" . $value->id . "'" . ')"><i class="fa fa-trash"></i> Delete</a>';
            }


            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->PertanyaanKualitatif_model->count_all($table_identity),
            "recordsFiltered" => $this->PertanyaanKualitatif_model->count_filtered($table_identity),
            "data" => $data,
        );

        echo json_encode($output);
    }

    public function add_kualitatif($id1, $id2)
    {
        $this->data = [];
        $this->data['title'] = "Tambah Pertanyaan Kualitatif";

        $this->data['profiles'] = $this->_get_data_profile($id1, $id2);

        $this->db->select('manage_survey.id AS id_manage_survey, manage_survey.table_identity AS table_identity');
        $this->db->from('manage_survey');
        $this->db->where('manage_survey.slug', $this->uri->segment(2));
        $manage_survey = $this->db->get()->row();


        $this->form_validation->set_rules('isi_pertanyaan', 'Isi Pertanyaan', 'trim|required');

        if ($this->form_validation->run() == FALSE) {

            return view('pertanyaan_survei/pertanyaan_kualitatif/add', $this->data);
        } else {

            $input     = $this->input->post(NULL, TRUE);

            $object = [
                'isi_pertanyaan'     => $input['isi_pertanyaan'],
                'is_active'     => $input['is_active']
            ];
            // var_dump($object);

            $this->db->insert('pertanyaan_kualitatif_' . $manage_survey->table_identity, $object);

            if ($this->db->affected_rows() > 0) {

                $this->session->set_flashdata('message_success', 'Berhasil menambah data');
                redirect(base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2) . '/pertanyaan-survey', 'refresh');
            } else {

                $this->data['message_data_danger'] = "Gagal menambah data";
                return view('pertanyaan_survei/pertanyaan_kualitatif/add', $this->data);
            }
        }
    }

    public function edit_kualitatif($id1, $id2)
    {
        $this->data = [];
        $this->data['title'] = "Edit Pertanyaan Kualitatif";

        $this->data['profiles'] = $this->_get_data_profile($id1, $id2);


        $this->db->select('manage_survey.id AS id_manage_survey, manage_survey.table_identity AS table_identity');
        $this->db->from('manage_survey');
        $this->db->where('manage_survey.slug', $this->uri->segment(2));
        $manage_survey = $this->db->get()->row();

        $this->data['kualitatif'] = $this->db->get_where('pertanyaan_kualitatif_' . $manage_survey->table_identity, ['id' => $this->uri->segment(5)])->row();

        $this->form_validation->set_rules('isi_pertanyaan', 'Isi Pertanyaan', 'trim|required');

        if ($this->form_validation->run() == FALSE) {

            return view('pertanyaan_survei/pertanyaan_kualitatif/edit', $this->data);
        } else {

            $input     = $this->input->post(NULL, TRUE);

            $object = [
                'isi_pertanyaan'     => $input['isi_pertanyaan'],
                'is_active'     => $input['is_active']
            ];
            // var_dump($object);

            $this->db->where('id', $this->uri->segment(5));
            $this->db->update('pertanyaan_kualitatif_' . $manage_survey->table_identity, $object);

            if ($this->db->affected_rows() > 0) {

                $this->session->set_flashdata('message_success', 'Berhasil mengubah data');
                redirect(base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2) . '/pertanyaan-survey', 'refresh');
            } else {

                $this->data['message_data_danger'] = "Gagal menambah data";
                return view('pertanyaan_survei/pertanyaan_kualitatif/edit', $this->data);
            }
        }
    }

    public function delete_kualitatif()
    {
        // $this->data['profiles'] = $this->_get_data_profile($id1, $id2);
        $this->db->select('manage_survey.id AS id_manage_survey, manage_survey.table_identity AS table_identity');
        $this->db->from('manage_survey');
        $this->db->where('manage_survey.slug', $this->uri->segment(2));
        $manage_survey = $this->db->get()->row();

        $this->db->delete('pertanyaan_kualitatif_' . $manage_survey->table_identity, array('id' => $this->uri->segment(5)));

        echo json_encode(array("status" => TRUE));
    }


    //------------------------------------PERTANYAAN HARAPAN-----------------------

    public function ajax_list_pertanyaan_harapan_survei()
    {
        $slug = $this->uri->segment(2);

        $get_identity = $this->db->get_where('manage_survey', array('slug' => "$slug"))->row();
        $table_identity = $get_identity->table_identity;

        $list = $this->PertanyaanHarapanSurvei_model->get_datatables($table_identity);
        $data = array();
        $no = $_POST['start'];

        foreach ($list as $value) {

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = '<b>' . $value->nomor_unsur . '. ' . $value->nama_unsur_pelayanan . '</b>';
            $row[] = $value->isi_pertanyaan_unsur;
            $row[] = '<label><input type="radio">&ensp;' . $value->pilihan_1 . '&emsp;</label><label><input type="radio">&ensp;' . $value->pilihan_2 . '&emsp;</label><label><input type="radio">&ensp;' . $value->pilihan_3 . '&emsp;</label><label><input type="radio">&ensp;' . $value->pilihan_4 . '&emsp;</label>';


            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->PertanyaanHarapanSurvei_model->count_all($table_identity),
            "recordsFiltered" => $this->PertanyaanHarapanSurvei_model->count_filtered($table_identity),
            "data" => $data,
        );

        echo json_encode($output);
    }

    public function edit_harapan($id1, $id2)
    {
        $this->data = [];
        $this->data['title'] = "Pertanyaan Harapan";

        $this->data['profiles'] = $this->_get_data_profile($id1, $id2);

        $this->db->select('');
        $this->db->from('manage_survey');
        $this->db->where('manage_survey.slug', $this->uri->segment(2));
        $table_identity_manage_survey = $this->db->get()->row()->table_identity;

        $this->form_validation->set_rules('pilihan_1', 'Pilihan 1', 'trim|required');
        $this->form_validation->set_rules('pilihan_2', 'Pilihan 2', 'trim|required');
        $this->form_validation->set_rules('pilihan_3', 'Pilihan 3', 'trim|required');
        $this->form_validation->set_rules('pilihan_4', 'Pilihan 4', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            return view('pertanyaan_harapan_survei/index', $this->data);
        } else {
            $input     = $this->input->post(NULL, TRUE);
            $object_1 = [
                'nama_tingkat_kepentingan'     => $input['pilihan_1']
            ];

            $object_2 = [
                'nama_tingkat_kepentingan'     => $input['pilihan_2']
            ];

            $object_3 = [
                'nama_tingkat_kepentingan'     => $input['pilihan_3']
            ];

            $object_4 = [
                'nama_tingkat_kepentingan'     => $input['pilihan_4']
            ];
            $this->db->where('nomor_tingkat_kepentingan', 1);
            $this->db->update('nilai_tingkat_kepentingan_' . $table_identity_manage_survey, $object_1);

            $this->db->where('nomor_tingkat_kepentingan', 2);
            $this->db->update('nilai_tingkat_kepentingan_' . $table_identity_manage_survey, $object_2);

            $this->db->where('nomor_tingkat_kepentingan', 3);
            $this->db->update('nilai_tingkat_kepentingan_' . $table_identity_manage_survey, $object_3);

            $this->db->where('nomor_tingkat_kepentingan', 4);
            $this->db->update('nilai_tingkat_kepentingan_' . $table_identity_manage_survey, $object_4);

            // var_dump($object_1);
        }

        $this->session->set_flashdata('message_success', 'Berhasil mengubah data');
        redirect(base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2) . '/pertanyaan-survey', 'refresh');
    }

    public function cari()
    {
        $id = $_GET['id'];
        $cari = $this->PertanyaanHarapanSurvei_model->cari($id)->result();
        echo json_encode($cari);
    }

    public function _get_data_profile($id1, $id2)
    {
        $this->db->select('users.username, manage_survey.survey_name, manage_survey.slug, manage_survey.description, manage_survey.is_privacy, manage_survey.table_identity, manage_survey.id_jenis_pelayanan, is_question');
        $this->db->from('users');
        $this->db->join('manage_survey', 'manage_survey.id_user = users.id');
        $this->db->where('users.username', $id1);
        $this->db->where('manage_survey.slug', $id2);
        $profiles = $this->db->get();

        if ($profiles->num_rows() == 0) {
            // echo 'Survey tidak ditemukan atau sudah dihapus !';
            // exit();
            show_404();
        }

        return $profiles->row();
    }
}
