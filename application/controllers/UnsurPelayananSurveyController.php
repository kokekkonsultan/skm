<?php
defined('BASEPATH') or exit('No direct script access allowed');

class UnsurPelayananSurveyController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message_warning', 'You must be an admin to view this page');
            redirect('auth', 'refresh');
        }
        $this->load->model('PertanyaanUnsurSurvei_model');
        $this->load->model('UnsurPelayananSurvey_model', 'models');
        $this->load->library('form_validation');
    }

    public function index($id1, $id2)
    {
        $this->data = [];
        $this->data['title'] = "Unsur Pelayanan";
        $this->data['profiles'] = $this->_get_data_profile($id1, $id2);

        $this->db->select('');
        $this->db->from('manage_survey');
        $this->db->where('manage_survey.slug', $this->uri->segment(2));
        $manage_survey = $this->db->get()->row();
        $table_identity_manage_survey = $manage_survey->table_identity;

        $this->data['is_question'] = $manage_survey->is_question;

        $query = $this->db->query("
        SELECT * FROM unsur_pelayanan_$table_identity_manage_survey ORDER BY unsur_pelayanan_$table_identity_manage_survey.id ASC
        ");
        $this->data['query'] = $query;


        return view('unsur_pelayanan_survey/index', $this->data);
    }

    public function ajax_list()
    {
        $slug = $this->uri->segment(2);
        $get_identity = $this->db->get_where('manage_survey', array('slug' => "$slug"))->row();
        $table_identity = $get_identity->table_identity;

        $list = $this->models->get_datatables($table_identity);
        $data = array();
        $no = $_POST['start'];

        foreach ($list as $value) {

            $no++;
            $row = array();
            $row[] = $no;
            $row[] =  $value->nomor_unsur;
            $row[] = $value->nama_unsur_pelayanan;
            $row[] = anchor($this->session->userdata('username') . '/' . $this->uri->segment(2) . '/unsur-pelayanan-survey/edit/' . $value->id, '<i class="fa fa-edit"></i> Edit', ['class' => 'btn btn-light-primary btn-sm font-weight-bold shadow']);

            if ($get_identity->is_question == 1) {
                $row[] = '<a class="btn btn-light-primary btn-sm font-weight-bold shadow" href="javascript:void(0)" title="Hapus ' . $value->nama_unsur_pelayanan . '" onclick="delete_data(' . "'" . $value->id . "'" . ')"><i class="fa fa-trash"></i> Delete</a>';
            }

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

    public function add($id1, $id2)
    {
        $this->data = [];
        $this->data['title'] = "Tambah Unsur Pelayanan";
        $this->load->library('uuid');

        $this->data['profiles'] = $this->_get_data_profile($id1, $id2);

        $this->db->select('');
        $this->db->from('manage_survey');
        $this->db->where('manage_survey.slug', $this->uri->segment(2));
        $manage_survey = $this->db->get()->row();
        $table_identity_manage_survey = $manage_survey->table_identity;

        // var_dump($is_privacy);

        $this->form_validation->set_rules('nama_unsur_pelayanan', 'Nama Unsur Pelayanan', 'trim|required');

        if ($this->form_validation->run() == FALSE) {

            $this->data['nama_unsur_pelayanan'] = [
                'name'         => 'nama_unsur_pelayanan',
                'id'        => 'nama_unsur_pelayanan',
                'type'        => 'text',
                'value'        =>    $this->form_validation->set_value('nama_unsur_pelayanan'),
                'class'        => 'form-control',
                'autofocus' => 'autofocus',
                'required' => 'required',
            ];

            $this->data['id_parent'] = [
                'name'         => 'id_parent',
                'id'         => 'id_parent',
                'options'     => $this->PertanyaanUnsurSurvei_model->dropdown_unsur_pelayanan(),
                'selected'     => $this->form_validation->set_value('id_parent'),
                'class'     => "form-control",
                'style' => "display:none",
                // 'required' => 'required',
            ];
            return view('unsur_pelayanan_survey/add', $this->data);
        } else {
            $input     = $this->input->post(NULL, TRUE);

            if ($input['custom'] == 1) {

                $id_parent = $input['id_parent'];

                $this->db->select('nomor_unsur');
                $this->db->from('unsur_pelayanan_' . $table_identity_manage_survey);
                $this->db->where('id =' . $id_parent);
                $nomor = $this->db->get()->row()->nomor_unsur;

                $this->db->select('(COUNT(id_parent)+1)AS nomor_sub');
                $this->db->from('unsur_pelayanan_' . $table_identity_manage_survey);
                $this->db->where('id_parent =' . $id_parent);
                $sub = $this->db->get()->row()->nomor_sub;

                $object = [
                    'uuid' => $this->uuid->v4(),
                    'nomor_unsur' => $nomor . '.' . $sub,
                    'nama_unsur_pelayanan'     => $input['nama_unsur_pelayanan'],
                    'is_sub_unsur_pelayanan' => $input['custom'],
                    'id_parent' => $id_parent,
                    'id_jenis_pelayanan' => $manage_survey->id_jenis_pelayanan
                ];
            } else {
                $this->db->select('(COUNT(nomor_unsur)+1)AS kode_unsur');
                $this->db->from('unsur_pelayanan_' . $table_identity_manage_survey);
                $this->db->where('id_parent = 0');
                $nomor_unsur = $this->db->get()->row()->kode_unsur;

                $object = [
                    'uuid' => $this->uuid->v4(),
                    'nomor_unsur' => 'U' . $nomor_unsur,
                    'nama_unsur_pelayanan'     => $input['nama_unsur_pelayanan'],
                    'is_sub_unsur_pelayanan' => $input['custom'],
                    'id_parent' => '0',
                    'id_jenis_pelayanan' => $manage_survey->id_jenis_pelayanan
                ];
            }
            // var_dump($object);

            $this->db->insert('unsur_pelayanan_' . $table_identity_manage_survey, $object);


            if ($this->db->affected_rows() > 0) {

                $this->session->set_flashdata('message_success', 'Berhasil menambah data');
                redirect(base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2) . '/unsur-pelayanan-survey', 'refresh');
            } else {

                $this->data['message_data_danger'] = "Gagal menambah data";
                return view('unsur-pelayanan-survey/add', $this->data);
            }
        }
    }

    public function edit($id1, $id2)
    {
        $this->data = [];
        $this->data['title'] = "Edit Unsur Pelayanan";
        $this->load->library('uuid');

        $this->data['profiles'] = $this->_get_data_profile($id1, $id2);

        $this->db->select('');
        $this->db->from('manage_survey');
        $this->db->where('manage_survey.slug', $this->uri->segment(2));
        $table_identity_manage_survey = $this->db->get()->row()->table_identity;

        $this->db->select("*, (SELECT nama_unsur_pelayanan FROM unsur_pelayanan_$table_identity_manage_survey uns WHERE uns.id = unsur_pelayanan_$table_identity_manage_survey.id_parent) AS nama_parent, (SELECT nomor_unsur FROM unsur_pelayanan_$table_identity_manage_survey uns WHERE uns.id = unsur_pelayanan_$table_identity_manage_survey.id_parent) AS nomor_parent");
        $this->db->from('unsur_pelayanan_' . $table_identity_manage_survey);
        $this->db->where('id', $this->uri->segment(5));
        $unsur_pelayanan = $this->db->get()->row();
        $this->data['data'] = $unsur_pelayanan;

        $this->form_validation->set_rules('nama_unsur_pelayanan', 'Nama Unsur Pelayanan', 'trim|required');

        if ($this->form_validation->run() == FALSE) {

            $this->data['nama_unsur_pelayanan'] = [
                'name'         => 'nama_unsur_pelayanan',
                'id'        => 'nama_unsur_pelayanan',
                'type'        => 'text',
                'value'        =>    $this->form_validation->set_value('nama_unsur_pelayanan', $unsur_pelayanan->nama_unsur_pelayanan),
                'class'        => 'form-control',
                'required' => 'required',
            ];
        
            $this->data['id_parent'] = [
                'name'         => 'id_parent',
                'id'         => 'id_parent',
                'value'     => $this->form_validation->set_value('id_parent', $unsur_pelayanan->nomor_parent . '. ' .$unsur_pelayanan->nama_parent),
                'class'     => "form-control",
                'disabled' => 'disabled'
            ];

            return view('unsur_pelayanan_survey/edit', $this->data);
        } else {
            $input = $this->input->post(NULL, TRUE);
            $object = [
                'nama_unsur_pelayanan'     => $input['nama_unsur_pelayanan'],
                // 'is_sub_unsur_pelayanan' => $input['custom'],
                // 'id_parent' => $id_parent
            ];
            // var_dump($object);

            $this->db->where('id', $unsur_pelayanan->id);
            $this->db->update('unsur_pelayanan_' . $table_identity_manage_survey, $object);


            $this->session->set_flashdata('message_success', 'Berhasil mengubah data');
            redirect(base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2) . '/unsur-pelayanan-survey', 'refresh');
        }
    }

    public function delete($id1, $id2)
    {
        $this->data = [];
        $this->data['title'] = "Delete Pertanyaan Unsur";
        $this->load->library('uuid');

        $this->data['profiles'] = $this->_get_data_profile($id1, $id2);

        $this->db->select('');
        $this->db->from('manage_survey');
        $this->db->where('manage_survey.slug', $this->uri->segment(2));
        $table_identity_manage_survey = $this->db->get()->row()->table_identity;

        $this->db->select('*');
        $this->db->from('unsur_pelayanan_' . $table_identity_manage_survey);
        $this->db->where('id', $this->uri->segment(5));
        $unsur_pelayanan = $this->db->get()->row();

        $this->db->select('*');
        $this->db->from('pertanyaan_terbuka_' . $table_identity_manage_survey);
        $this->db->where('id_unsur_pelayanan', $this->uri->segment(5));
        $pertanyaan_terbuka = $this->db->get()->row();

        $this->db->select('*');
        $this->db->from('pertanyaan_unsur_pelayanan_' . $table_identity_manage_survey);
        $this->db->where('id_unsur_pelayanan', $unsur_pelayanan->id);
        $pertanyaan_unsur = $this->db->get()->row();

        $this->db->select('*');
        $this->db->from('perincian_pertanyaan_terbuka_' . $table_identity_manage_survey);
        $this->db->where('id_pertanyaan_terbuka', $pertanyaan_terbuka->id);
        $perincian_pertanyaan_terbuka = $this->db->get()->row();

        // $this->db->select('*');
        // $this->db->from('kategori_unsur_pelayanan_' . $table_identity_manage_survey);
        // $this->db->where('id_pertanyaan_unsur', $pertanyaan_unsur->id);
        // $this->data['nama_kategori_unsur'] = $this->db->get()->result();
        // var_dump($kategori_unsur_pelayanan);

        $this->db->where('id_pertanyaan_terbuka', $pertanyaan_terbuka->id);
        $this->db->delete('jawaban_pertanyaan_terbuka_' . $table_identity_manage_survey);

        $this->db->where('id_pertanyaan_unsur', $pertanyaan_unsur->id);
        $this->db->delete('jawaban_pertanyaan_harapan_' . $table_identity_manage_survey);

        $this->db->where('id_pertanyaan_unsur', $pertanyaan_unsur->id);
        $this->db->delete('jawaban_pertanyaan_unsur_' . $table_identity_manage_survey);

        $this->db->where('id_perincian_pertanyaan_terbuka', $perincian_pertanyaan_terbuka->id);
        $this->db->delete('isi_pertanyaan_ganda_' . $table_identity_manage_survey);

        $this->db->where('id_pertanyaan_terbuka', $pertanyaan_terbuka->id);
        $this->db->delete('perincian_pertanyaan_terbuka_' . $table_identity_manage_survey);

        $this->db->where('id_unsur_pelayanan', $unsur_pelayanan->id);
        $this->db->delete('pertanyaan_terbuka_' . $table_identity_manage_survey);

        $this->db->where('id_pertanyaan_unsur', $pertanyaan_unsur->id);
        $this->db->delete('kategori_unsur_pelayanan_' . $table_identity_manage_survey);

        $this->db->where('id_pertanyaan_unsur_pelayanan', $pertanyaan_unsur->id);
        $this->db->delete('nilai_tingkat_kepentingan_' . $table_identity_manage_survey);

        $this->db->where('id_pertanyaan_unsur', $pertanyaan_unsur->id);
        $this->db->delete('kategori_unsur_pelayanan_' . $table_identity_manage_survey);

        $this->db->where('id_unsur_pelayanan', $unsur_pelayanan->id);
        $this->db->delete('pertanyaan_unsur_pelayanan_' . $table_identity_manage_survey);

        $this->db->where('id', $this->uri->segment(5));
        $this->db->delete('unsur_pelayanan_' . $table_identity_manage_survey);

        echo json_encode(array("status" => TRUE));
    }


    public function _get_data_profile($id1, $id2)
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->join('users_groups', 'users.id = users_groups.user_id');
        $this->db->where('users.username', $this->session->userdata('username'));
        $data_user = $this->db->get()->row();
        $user_identity = 'drs' . $data_user->is_parent;

        $this->db->select('users.username, manage_survey.survey_name, is_question, manage_survey.slug, manage_survey.description, manage_survey.is_privacy, manage_survey.table_identity, manage_survey.id_jenis_pelayanan, manage_survey.atribut_pertanyaan_survey');
        if ($data_user->group_id == 2) {
            $this->db->from('users');
            $this->db->join('manage_survey', 'manage_survey.id_user = users.id');
        } else {
            $this->db->from('manage_survey');
            $this->db->join("supervisor_$user_identity", "manage_survey.id_berlangganan = supervisor_$user_identity.id_berlangganan");
            $this->db->join("users", "supervisor_$user_identity.id_user = users.id");
        }
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

/* End of file PertanyaanUnsurSurveiController.php */
/* Location: ./application/controllers/PertanyaanUnsurSurveiController.php */