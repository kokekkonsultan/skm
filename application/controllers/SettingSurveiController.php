<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SettingSurveiController extends CI_Controller
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

    public function index($id1, $id2)
    {
        $this->data = [];
        $this->data['title'] = "Pengaturan Survei";
        $this->data['profiles'] = $this->_get_data_profile($id1, $id2);

        $this->db->select("*");
        $this->db->from('manage_survey');
        $this->db->where('manage_survey.slug', $this->uri->segment(2));
        $this->data['manage_survey'] = $this->db->get()->row();

        $this->data['survey_start'] = [
            'name'         => 'survey_start',
            'id'        => 'survey_start',
            'type'        => 'date',
            'value'        =>    $this->form_validation->set_value('survey_start', $this->data['manage_survey']->survey_start),
            'class'        => 'form-control',
        ];

        $this->data['survey_end'] = [
            'name'         => 'survey_end',
            'id'        => 'survey_end',
            'type'        => 'date',
            'value'        =>    $this->form_validation->set_value('survey_start', $this->data['manage_survey']->survey_end),
            'class'        => 'form-control',
        ];

        $this->data['deskripsi_tunda'] = [
            'name'         => 'deskripsi_tunda',
            'id'        => 'deskripsi_tunda',
            'type'        => 'text',
            'value'        =>    $this->form_validation->set_value('deskripsi_tunda', $this->data['manage_survey']->deskripsi_tunda),
            'class'        => 'form-control',
            'rows' => '3'
        ];

        return view('setting_survei/index', $this->data);
    }

    public function periode()
    {
        $slug = $this->uri->segment(2);

        if ($this->input->post('hapus_periode') == 1) {
            $this->db->set('survey_start', NULL);
            $this->db->set('survey_end', NULL);
            $this->db->where('slug', "$slug");
            $this->db->update('manage_survey');
        } else {
            $object = [
                'survey_start' => $this->input->post('survey_start'),
                'survey_end' => $this->input->post('survey_end'),
            ];
            $this->db->where('slug', "$slug");
            $this->db->update('manage_survey', $object);
        }

        $pesan = 'Data berhasil disimpan';
        $msg = ['sukses' => $pesan];
        echo json_encode($msg);
    }

    public function tunda()
    {
        $slug = $this->uri->segment(2);
        $object = [
            'is_privacy' => $this->input->post('is_privacy'),
            'deskripsi_tunda' => $this->input->post('deskripsi_tunda')
        ];
        $this->db->where('slug', "$slug");
        $this->db->update('manage_survey', $object);

        $pesan = 'Data berhasil disimpan';
        $msg = ['sukses' => $pesan];
        echo json_encode($msg);
    }

    public function display($id1, $id2)
    {
        $this->data = [];
        $this->data['title'] = "Pengaturan Tampilan Survei";
        $this->data['profiles'] = $this->_get_data_profile($id1, $id2);

        $this->data['data_user'] = $this->ion_auth->user()->row();

        $slug = $this->uri->segment('2');

        $this->db->select('*');
        $this->db->from('manage_survey');
        $this->db->where("slug = '$slug'");
        $this->data['manage_survey'] = $this->db->get()->row();

        return view('setting_survei/form_display', $this->data);
    }

    public function update_header()
    {
        $slug = $this->uri->segment(2);
        $object = [
            'title_header_survey' => $this->input->post('title'),
        ];
        $this->db->where('slug', "$slug");
        $this->db->update('manage_survey', $object);

        $pesan = 'Data berhasil disimpan';
        $msg = ['sukses' => $pesan];
        echo json_encode($msg);
    }

    public function update_display()
    {
        $slug = $this->uri->segment(2);
        $object = [
            'deskripsi_opening_survey' => $this->input->post('deskripsi')
        ];
        $this->db->where('slug', "$slug");
        $this->db->update('manage_survey', $object);

        $pesan = 'Data berhasil disimpan';
        $msg = ['sukses' => $pesan];
        echo json_encode($msg);
    }


    public function update_saran()
    {
        $slug = $this->uri->segment(2);
        $object = [
            'is_saran' => $this->input->post('is_saran'),
            'judul_form_saran' => $this->input->post('judul_form_saran')
        ];
        $this->db->where('slug', "$slug");
        $this->db->update('manage_survey', $object);

        $pesan = 'Data berhasil disimpan';
        $msg = ['sukses' => $pesan];
        echo json_encode($msg);
    }


    public function default()
    {
        $this->data = [];
        $this->data['title'] = "Pengaturan Umum";

        $this->data['manage_survey'] = $this->db->get_where('manage_survey', array('slug' => $this->uri->segment(2)))->row();

        return view('setting_survei/form_default', $this->data);
    }

    public function setting_general($id1, $id2)
    {
        $this->data = [];
        $this->data['title'] = "Settings";

        $this->data['profiles'] = $this->_get_data_profile($id1, $id2);

        $slug = $id2;

        $this->db->select('*');
        $this->db->from('manage_survey');
        $this->db->where("slug = '$slug'");
        $current = $this->db->get()->row();

        $this->data['id_manage_survey'] = $current->id;
        $this->data['atribut_pertanyaan_survey'] = unserialize($current->atribut_pertanyaan_survey);

        return view('setting_survei/form_settings', $this->data);
    }

    public function setting_pertanyaan()
    {
        $slug = $this->uri->segment(2);

        $this->db->select('*');
        $this->db->from('manage_survey');
        $this->db->where("slug = '$slug'");
        $current = $this->db->get()->row();

        $this->db->empty_table('trash_jawaban_pertanyaan_harapan_' . $current->table_identity);
        $this->db->empty_table('trash_jawaban_pertanyaan_terbuka_' . $current->table_identity);
        $this->db->empty_table('trash_jawaban_pertanyaan_unsur_' . $current->table_identity);
        $this->db->empty_table('trash_jawaban_pertanyaan_kualitatif_' . $current->table_identity);
        $this->db->empty_table('trash_survey_' . $current->table_identity);
        $this->db->empty_table('trash_responden_' . $current->table_identity);

        $this->db->empty_table('jawaban_pertanyaan_harapan_' . $current->table_identity);
        $this->db->empty_table('jawaban_pertanyaan_terbuka_' . $current->table_identity);
        $this->db->empty_table('jawaban_pertanyaan_unsur_' . $current->table_identity);
        $this->db->empty_table('jawaban_pertanyaan_kualitatif_' . $current->table_identity);
        $this->db->empty_table('survey_' . $current->table_identity);
        $this->db->empty_table('responden_' . $current->table_identity);


        if ($current->is_survey_close == 1) {
            $this->db->query("DROP TABLE origin_responden_$current->table_identity");
            $this->db->query("DROP TABLE origin_survey_$current->table_identity");
            $this->db->query("DROP TABLE origin_jawaban_pertanyaan_unsur_$current->table_identity");
            $this->db->query("DROP TABLE origin_jawaban_pertanyaan_kualitatif_$current->table_identity");
            $this->db->query("DROP TABLE origin_jawaban_pertanyaan_terbuka_$current->table_identity");
            $this->db->query("DROP TABLE origin_jawaban_pertanyaan_harapan_$current->table_identity");


            $this->db->query("DROP TABLE koreksi_responden_$current->table_identity");
            $this->db->query("DROP TABLE koreksi_survey_$current->table_identity");
            $this->db->query("DROP TABLE koreksi_jawaban_pertanyaan_unsur_$current->table_identity");
            $this->db->query("DROP TABLE koreksi_jawaban_pertanyaan_kualitatif_$current->table_identity");
            $this->db->query("DROP TABLE koreksi_jawaban_pertanyaan_terbuka_$current->table_identity");
            $this->db->query("DROP TABLE koreksi_jawaban_pertanyaan_harapan_$current->table_identity");
        }

        $object = [
            'atribut_pertanyaan_survey' => serialize($this->input->post('atribut_pertanyaan')),
            'is_question' => 1,
            'is_origin_backup' => NULL,
            'is_koreksi' => NULL,
            'is_survey_close' => NULL
        ];
        $this->db->where('slug', "$slug");
        $this->db->update('manage_survey', $object);

        $pesan = 'Data berhasil disimpan';
        $msg = ['sukses' => $pesan];
        echo json_encode($msg);
    }

    public function settings_question($id1, $id2)
    {
        $this->data = [];
        $this->data['title'] = "Tunda Survey";
        $this->data['profiles'] = $this->_get_data_profile($id1, $id2);

        $slug = $this->uri->segment('2');

        $this->db->select('*');
        $this->db->from('manage_survey');
        $this->db->where("slug = '$slug'");
        $current = $this->db->get()->row();

        $this->data['current'] = $current;

        $this->form_validation->set_rules('is_question', 'Is Question', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            return view('setting_survei/settings_question', $this->data);
        } else {
            $target = [
                'is_question' => $this->input->post('is_question')
            ];

            $this->db->empty_table('jawaban_pertanyaan_harapan_' . $current->table_identity);
            $this->db->empty_table('jawaban_pertanyaan_terbuka_' . $current->table_identity);
            $this->db->empty_table('jawaban_pertanyaan_unsur_' . $current->table_identity);
            $this->db->empty_table('jawaban_pertanyaan_kualitatif_' . $current->table_identity);
            $this->db->empty_table('survey_' . $current->table_identity);
            $this->db->empty_table('responden_' . $current->table_identity);
            $this->db->where('id', $current->id);
            $this->db->update('manage_survey', $target);
        }
        $this->session->set_flashdata('message_success', 'Berhasil mengubah data');
        redirect(base_url() . $this->session->userdata('username') . '/' . $this->uri->segment('2') . '/' . 'settings-question', 'refresh');
    }

    public function _get_data_profile($id1, $id2)
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->join('users_groups', 'users.id = users_groups.user_id');
        $this->db->where('users.username', $this->session->userdata('username'));
        $data_user = $this->db->get()->row();
        $user_identity = 'drs' . $data_user->is_parent;

        $this->db->select('users.username, manage_survey.survey_name, manage_survey.slug, manage_survey.description, manage_survey.is_privacy, manage_survey.survey_start, manage_survey.survey_end, manage_survey.kuesioner_name, manage_survey.id AS id_manage_survey, manage_survey.logo_survey, manage_survey.is_question, manage_survey.table_identity, manage_survey.jumlah_populasi, klasifikasi_survei.nama_klasifikasi_survei, id_jenis_pelayanan, nama_jenis_pelayanan_responden, sampling.nama_sampling, jumlah_sampling, manage_survey.atribut_pertanyaan_survey');

        if ($data_user->group_id == 2) {
            $this->db->from('users');
            $this->db->join('manage_survey', 'manage_survey.id_user = users.id');
            $this->db->join('jenis_pelayanan', 'manage_survey.id_jenis_pelayanan = jenis_pelayanan.id', 'left');
            $this->db->join('klasifikasi_survei', 'klasifikasi_survei.id = users.id_klasifikasi_survei');
            $this->db->join('sampling', 'sampling.id = manage_survey.id_sampling');
            $this->db->where('users.username', $id1);
            $this->db->where('manage_survey.slug', $id2);
        } else {
            $this->db->from('manage_survey');
            $this->db->join("supervisor_$user_identity", "manage_survey.id_berlangganan = supervisor_$user_identity.id_berlangganan");
            $this->db->join("users", "supervisor_$user_identity.id_user = users.id");
            $this->db->join('jenis_pelayanan', 'manage_survey.id_jenis_pelayanan = jenis_pelayanan.id', 'left');
            $this->db->join('klasifikasi_survei', 'klasifikasi_survei.id = users.id_klasifikasi_survei');
            $this->db->join('sampling', 'sampling.id = manage_survey.id_sampling');
            $this->db->where('users.username', $id1);
            $this->db->where('manage_survey.slug', $id2);
        }
        $profiles = $this->db->get();
        // var_dump($profiles->row());

        if ($profiles->num_rows() == 0) {
            // echo 'Survey tidak ditemukan atau sudah dihapus !';
            // exit();
            show_404();
        }
        return $profiles->row();
    }
}

/* End of file SettingSurveiController.php */
/* Location: ./application/controllers/SettingSurveiController.php */