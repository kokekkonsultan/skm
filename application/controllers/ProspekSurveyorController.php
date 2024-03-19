<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ProspekSurveyorController extends CI_Controller
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
        $this->load->model('ProspekSurveyor_model', 'models');
    }

    public function index()
    {
        $this->data = [];
        $this->data['title'] = "Prospek Surveyor";

        $user = $this->ion_auth->user()->row()->id;

        $this->db->select('id_manage_survey, surveyor.uuid AS uuid');
        $this->db->from('users');
        $this->db->join('surveyor', 'users.id = surveyor.id_user');
        $this->db->where('users.id =' . $user);
        $surveyor = $this->db->get()->row();
        $this->data['surveyor'] =  $surveyor;


        $this->db->select('*');
        $this->db->from('manage_survey');
        $this->db->join('users', 'manage_survey.id_user = users.id');
        $this->db->where('manage_survey.id =' . $surveyor->id_manage_survey);
        $this->data['manage_survey'] = $this->db->get()->row();

        $this->data['prospek_surveyor'] = $this->db->get_where('data_prospek_survey_cst' . $surveyor->id_manage_survey, ['id_user' => $user])->result();
        // var_dump($this->data['prospek_surveyor']->result());

        // $get_identity = $this->db->get_where('manage_survey', ['id' => $surveyor->id_manage_survey])->row();

        return view('prospek_surveyor/index', $this->data);
    }

    public function ajax_list()
    {

        $user = $this->ion_auth->user()->row()->id;
        // var_dump($user);

        $this->db->select('id_manage_survey, surveyor.uuid AS uuid');
        $this->db->from('users');
        $this->db->join('surveyor', 'users.id = surveyor.id_user');
        $this->db->where('users.id =' . $user);
        $surveyor = $this->db->get()->row();

        // Get Identity
        $get_identity = $this->db->get_where('manage_survey', ['id' => $surveyor->id_manage_survey])->row();
        $table_identity = $get_identity->table_identity;

        $list = $this->models->get_datatables($table_identity, $user);
        $data = array();
        $no = $_POST['start'];

        foreach ($list as $value) {

            if ($value->email != '') {
                $btn_email = '<a href="prospek-surveyor/edit/' . $value->id . '" class="btn btn-light-primary btn-sm font-weight-bold shadow" data-toggle="modal" data-target="#email' . $value->id . ' "><i class="fas fa-at"></i> Bagikan Via Email</a>';
            } else {
                $btn_email = '';
            }


            if ($value->telepon != '') {
                $btn_wa = '<a href="prospek-surveyor/edit/' . $value->id . '" class="btn btn-light-primary btn-sm font-weight-bold shadow" data-toggle="modal" data-target="#wa' . $value->id . ' "><i class="fab fa-whatsapp"></i> Bagikan Via WhatsApp</a>';
            } else {
                $btn_wa = '';
            }

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $value->nama_lengkap;
            $row[] = $value->alamat;
            $row[] = $value->telepon;
            $row[] = $value->email;
            $row[] = $value->keterangan;
            $row[] = $btn_email . '<br><hr>' . $btn_wa;
            $row[] = anchor('prospek-surveyor/edit/' . $value->id, 'Edit', ['class' => 'btn btn-light-primary btn-sm font-weight-bold shadow']);
            $row[] = '<a class="btn btn-light-primary btn-sm font-weight-bold shadow" href="javascript:void(0)" title="Hapus ' . $value->nama_lengkap . '" onclick="delete_data(' . "'" . $value->id . "'" . ')">Delete</a>';

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->models->count_all($table_identity, $user),
            "recordsFiltered" => $this->models->count_filtered($table_identity, $user),
            "data" => $data,
        );

        echo json_encode($output);
    }

    public function add()
    {
        $this->data = array();
        $this->data['title']         = 'Tambah Prospek Surveyor';
        $this->data['form_action']     = 'prospek-surveyor/add';

        $user = $this->ion_auth->user()->row()->id;

        $this->db->select('surveyor.id AS id_surveyor, id_manage_survey, surveyor.uuid AS uuid');
        $this->db->from('users');
        $this->db->join('surveyor', 'users.id = surveyor.id_user');
        $this->db->where('users.id =' . $user);
        $surveyor = $this->db->get()->row();

        $get_identity = $this->db->get_where('manage_survey', ['id' => $surveyor->id_manage_survey])->row();

        $this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'trim|required');
        $this->form_validation->set_rules('alamat', 'Alamat', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required');
        $this->form_validation->set_rules('whatsapp', 'Whatsapp', 'trim|required');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'trim|required');

        if ($this->form_validation->run() == FALSE) {

            $this->data['nama_lengkap'] = [
                'name'         => 'nama_lengkap',
                'id'        => 'nama_lengkap',
                'type'        => 'text',
                'value'        =>    $this->form_validation->set_value('nama_lengkap'),
                'class'        => 'form-control',
                'autofocus' => 'autofocus'
            ];

            $this->data['alamat'] = [
                'name'         => 'alamat',
                'id'        => 'alamat',
                'type'        => 'text',
                'value'        =>    $this->form_validation->set_value('alamat'),
                'class'        => 'form-control'
            ];

            $this->data['email'] = [
                'name'         => 'email',
                'id'        => 'email',
                'type'        => 'email',
                'value'        =>    $this->form_validation->set_value('email'),
                'class'        => 'form-control'
            ];

            $this->data['whatsapp'] = [
                'name'         => 'whatsapp',
                'id'        => 'whatsapp',
                'type'        => 'num',
                'value'        =>    $this->form_validation->set_value('whatsapp'),
                'class'        => 'form-control'
            ];

            $this->data['keterangan'] = [
                'name'         => 'keterangan',
                'id'        => 'keterangan',
                'type'        => 'text',
                'value'        =>    $this->form_validation->set_value('keterangan'),
                'class'        => 'form-control'
            ];

            return view('prospek_surveyor/add', $this->data);
        } else {

            $input     = $this->input->post(NULL, TRUE);

            $this->load->helper('slug');

            $object = [
                'nama_lengkap'     => $input['nama_lengkap'],
                'alamat'     => $input['alamat'],
                'email'     => $input['email'],
                'telepon'     => $input['whatsapp'],
                'keterangan'     => $input['keterangan'],
                'id_user' => $user,
                'id_surveyor' => $surveyor->id_surveyor
            ];
            // var_dump($object);

            $this->db->insert('data_prospek_survey_' . $get_identity->table_identity, $object);

            if ($this->db->affected_rows() > 0) {

                $this->session->set_flashdata('message_success', 'Berhasil mengubah prospek surveyor');
                redirect(base_url() . 'prospek-surveyor', 'refresh');
            } else {

                $this->data['message_data_danger'] = "Gagal mengubah prospek surveyor";
                return view('prospek_surveyor/edit', $this->data);
            }
        }
    }

    public function edit($id = NULL)
    {
        $this->data = array();
        $this->data['title'] = 'Edit Prospek Surveyor';
        $this->data['form_action'] = 'prospek-surveyor/edit/' . $id;

        $user = $this->ion_auth->user()->row()->id;

        $this->db->select('id_manage_survey, surveyor.uuid AS uuid');
        $this->db->from('users');
        $this->db->join('surveyor', 'users.id = surveyor.id_user');
        $this->db->where('users.id =' . $user);
        $surveyor = $this->db->get()->row();

        $get_identity = $this->db->get_where('manage_survey', ['id' => $surveyor->id_manage_survey])->row();

        $search_data = $this->db->get_where('data_prospek_survey_' . $get_identity->table_identity, ['id' => $id]);

        if ($search_data->num_rows() == 0) {

            $this->session->set_flashdata('message_danger', 'Data tidak ditemukan');
            redirect($this->session->userdata('urlback'), 'refresh');
        }

        $current = $search_data->row();

        $this->data['nama_lengkap'] = [
            'name'         => 'nama_lengkap',
            'id'        => 'nama_lengkap',
            'type'        => 'text',
            'value'        =>    $this->form_validation->set_value('nama_lengkap', $current->nama_lengkap),
            'class'        => 'form-control',
            'autofocus' => 'autofocus'
        ];

        $this->data['alamat'] = [
            'name'         => 'alamat',
            'id'        => 'alamat',
            'type'        => 'text',
            'value'        =>    $this->form_validation->set_value('alamat', $current->alamat),
            'class'        => 'form-control'
        ];

        $this->data['email'] = [
            'name'         => 'email',
            'id'        => 'email',
            'type'        => 'email',
            'value'        =>    $this->form_validation->set_value('email', $current->email),
            'class'        => 'form-control'
        ];

        $this->data['whatsapp'] = [
            'name'         => 'whatsapp',
            'id'        => 'whatsapp',
            'type'        => 'num',
            'value'        =>    $this->form_validation->set_value('whatsapp', $current->telepon),
            'class'        => 'form-control'
        ];

        $this->data['keterangan'] = [
            'name'         => 'keterangan',
            'id'        => 'keterangan',
            'type'        => 'text',
            'value'        =>    $this->form_validation->set_value('keterangan', $current->keterangan),
            'class'        => 'form-control'
        ];

        $this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'trim|required');
        $this->form_validation->set_rules('alamat', 'Alamat', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required');
        $this->form_validation->set_rules('whatsapp', 'Whatsapp', 'trim|required');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'trim|required');
        if ($this->form_validation->run() == FALSE) {

            return view('prospek_surveyor/edit', $this->data);
        } else {

            $input     = $this->input->post(NULL, TRUE);

            $this->load->helper('slug');

            $object = [
                'nama_lengkap'     => $input['nama_lengkap'],
                'alamat'     => $input['alamat'],
                'email'     => $input['email'],
                'telepon'     => $input['whatsapp'],
                'keterangan'     => $input['keterangan'],
                'id_user' => $user
            ];
            // var_dump($object);

            $this->db->where('id', $id);
            $this->db->update('data_prospek_survey_' . $get_identity->table_identity, $object);

            if ($this->db->affected_rows() > 0) {

                $this->session->set_flashdata('message_success', 'Berhasil mengubah prospek surveyor');
                redirect(base_url() . 'prospek-surveyor', 'refresh');
            } else {

                $this->data['message_data_danger'] = "Gagal mengubah prospek surveyor";
                return view('prospek_surveyor/edit', $this->data);
            }
        }
    }

    public function delete($id = NULL)
    {
        $user = $this->ion_auth->user()->row()->id;

        $this->db->select('id_manage_survey, surveyor.uuid AS uuid');
        $this->db->from('users');
        $this->db->join('surveyor', 'users.id = surveyor.id_user');
        $this->db->where('users.id =' . $user);
        $surveyor = $this->db->get()->row();

        $get_identity = $this->db->get_where('manage_survey', ['id' => $surveyor->id_manage_survey])->row();

        $search_data = $this->db->get_where('data_prospek_survey_' . $get_identity->table_identity, ['id' => $id]);

        if ($search_data->num_rows() == 0) {

            echo json_encode(array("status" => FALSE));
        }

        $current = $search_data->row();

        $this->db->where('id', $current->id);
        $this->db->delete('data_prospek_survey_' . $get_identity->table_identity);

        echo json_encode(array("status" => TRUE));
    }

    public function get_email()
    {

        $email_akun     = $this->input->post('email_akun');
        $isi_email     = $this->input->post('isi_email');

        $settings = $this->db->query("
							SELECT
							( SELECT setting_value FROM web_settings WHERE alias = 'akun_email') AS email_akun,
							( SELECT setting_value FROM web_settings WHERE alias = 'email_pengirim') AS email_pengirim,
							( SELECT setting_value FROM web_settings WHERE alias = 'email_username') AS email_username,
							( SELECT setting_value FROM web_settings WHERE alias = 'email_password') AS email_password,
							( SELECT setting_value FROM web_settings WHERE alias = 'email_host') AS email_host,
							( SELECT setting_value FROM web_settings WHERE alias = 'email_port') AS email_port,
							( SELECT setting_value FROM web_settings WHERE alias = 'email_cc') AS email_cc,
							( SELECT setting_value FROM web_settings WHERE alias = 'email_bcc') AS email_bcc
							FROM
							web_settings LIMIT 1
						")->row();

        $this->load->library('email');

        $ci = get_instance();
        $config['protocol']     = "smtp";
        $config['smtp_host']    = $settings->email_host;
        $config['smtp_port']    = $settings->email_port;
        $config['smtp_user']    = $settings->email_username;
        $config['smtp_pass']    = $settings->email_password;
        $config['charset']      = "utf-8";
        $config['mailtype']     = "html";
        $config['newline']      = "\r\n";

        $html = '';
        $html .= '

        <table width="100%" border="0" cellpadding="4" cellspacing="0">
          <tr>
            <td bgcolor="#AE0000" style="font-size: 20px; color: #FFFFFF;"><div align="center"><strong>SISTEM INFORMASI E-SKM</strong></div></td>
          </tr>
          <tr>
            <td style="font-size: 16px;">
        <br><br>
        <p>Kepada Bapak/ Ibu<br />
        Di Tempat</p>
        <p>' . $isi_email . '</p>
        <p><strong><u>Admin E-SKM</u></strong></p>
        <br><br>
        
            </td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC" style="font-size: 12px;"><div align="center">View as a Web Page<br />
            Sistem Informasi E-SKM<br />
              survei-kepuasan.com
            </div></td>
          </tr>
        </table>
                            ';

        $ci->email->initialize($config);
        $ci->email->from($settings->email_pengirim, 'Auto Reply Sistim Informasi E-SKM');
        $ci->email->to($email_akun);

        $ci->email->subject('Informasi Survey Kepuasan Masyarakat');
        $ci->email->message($html);
        $this->email->send();

        $pesan = 'Email berhasil dikirim';
        $msg = ['sukses' => $pesan];
        echo json_encode($msg);
    }
}

/* End of file KlasifikasiSurveyController.php */
/* Location: ./application/controllers/KlasifikasiSurveyController.php */