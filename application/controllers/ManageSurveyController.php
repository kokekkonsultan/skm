<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Carbon\Carbon;

class ManageSurveyController extends Client_Controller
{
	private $data;
	public $db;

    public function __construct()
    {
        parent::__construct();

        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message_warning', 'You must be an admin to view this page');
            redirect('auth', 'refresh');
        }

        $this->load->library('form_validation');
        $this->load->helper('security');

        $this->load->library('image_lib');
        $this->load->helper('file');
        $this->load->model('ManageSurvey_model', 'models');
    }

    public function index()
    {
        $this->data = [];
        $this->data['title'] = "Kelola Survey";

        $data_user = $this->_cek_user()->row();
        $this->data['data_user'] = $data_user;

        if ($data_user->group_id == 2) {
            $this->db->select('users.username, manage_survey.survey_name, manage_survey.slug, manage_survey.description, manage_survey.is_privacy');
            $this->db->from('users');
            $this->db->join('manage_survey', 'manage_survey.id_user = users.id');
            $this->db->where('users.username', $this->session->userdata('username'));
        } else {
            $this->db->select('users.username, manage_survey.survey_name, manage_survey.slug, manage_survey.description, manage_survey.is_privacy');
            $this->db->from('manage_survey');
            $this->db->join("supervisor_drs$data_user->is_parent", "manage_survey.id_berlangganan = supervisor_drs$data_user->is_parent.id_berlangganan");
            $this->db->join("users", "supervisor_drs$data_user->is_parent.id_user = users.id");
            $this->db->where('users.username', $this->session->userdata('username'));
        }
        $this->data['data_survey'] = $this->db->get();


        // $this->data['data_user'] = $this->ion_auth->user()->row();

        return view('manage_survey/index', $this->data);
    }

    public function info_berlangganan()
    {
        $this->data = [];
        $this->data['title'] = "Info Berlangganan";

        // $data_user = $this->ion_auth->user()->row();
        $data_user = $this->db->get_where('users', array('username' => $this->uri->segment(1)))->row();
        // var_dump($data_user);

        $this->data['data_user'] = $data_user;

        return view('manage_survey/form_info_berlangganan', $this->data);
    }

    public function ajax_list()
    {
        $data_user = $this->_cek_user()->row();

        $list = $this->models->get_datatables($data_user);
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $value) {

            if ($value->is_privacy == 1) {
                $color = 'success';
                $status = '<span class="badge badge-success" width="40%">Public</span>';
            } else {
                $color = 'danger';
                $status = '<span class="badge badge-danger" width="40%">Private</span>';
            };
            
            $klien_user = $this->db->get_where("users", array('id' => $value->id_user))->row();
            $skala_likert = (100 / ($value->skala_likert == 5 ? 5 : 4));

            if ($this->db->get_where("survey_$value->table_identity", array('is_submit' => 1))->num_rows() > 0) {

                $nilai_per_unsur[$no] = $this->db->query("SELECT IF(id_parent = 0,unsur_pelayanan_$value->table_identity.id, unsur_pelayanan_$value->table_identity.id_parent) AS id_sub,
					((SUM(skor_jawaban)/COUNT(DISTINCT survey_$value->table_identity.id_responden))/(COUNT(id_parent)/COUNT(DISTINCT survey_$value->table_identity.id_responden))) AS nilai_per_unsur, (((SUM(skor_jawaban)/COUNT(DISTINCT survey_$value->table_identity.id_responden))/(COUNT(id_parent)/COUNT(DISTINCT survey_$value->table_identity.id_responden)))/(SELECT COUNT(id) FROM unsur_pelayanan_$value->table_identity WHERE id_parent = 0)) AS rata_rata_bobot

					FROM jawaban_pertanyaan_unsur_$value->table_identity
					JOIN pertanyaan_unsur_pelayanan_$value->table_identity ON jawaban_pertanyaan_unsur_$value->table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$value->table_identity.id
					JOIN unsur_pelayanan_$value->table_identity ON pertanyaan_unsur_pelayanan_$value->table_identity.id_unsur_pelayanan = unsur_pelayanan_$value->table_identity.id
					JOIN survey_$value->table_identity ON jawaban_pertanyaan_unsur_$value->table_identity.id_responden = survey_$value->table_identity.id_responden
					WHERE survey_$value->table_identity.is_submit = 1 AND jawaban_pertanyaan_unsur_$value->table_identity.skor_jawaban != '0.0'
					GROUP BY id_sub");

                $nilai_bobot[$no] = [];
                foreach ($nilai_per_unsur[$no]->result() as $get) {
                    $nilai_bobot[$no][] = $get->rata_rata_bobot;
                    $nilai_tertimbang[$no] = array_sum($nilai_bobot[$no]);
                }
                $nilai_indeks = ROUND($nilai_tertimbang[$no] * $skala_likert, 2);
                // $nilai_indeks = ROUND($nilai_tertimbang[$no], 3);
            } else {
                $nilai_indeks = 0;
            };

            $no++;
            $row = array();

            $row[] = $no;
            $row[] = '<a href="' . base_url() . $this->session->userdata('username') . '/' . $value->slug_manage_survey . '/do" title="" class="text-' . $color . '">
			    <div class="card card-body mb-5 shadow  wave wave-animate-slow wave-' . $color . '">
			        <div class="d-flex align-items-center">
			            <span class="bullet bullet-bar bg-' . $color . ' align-self-stretch"></span>
			            <label
			                class="checkbox checkbox-lg checkbox-light-' . $color . ' checkbox-inline flex-shrink-0 m-0 mx-4">
			                <input type="checkbox" value="1" disabled>
			                <span></span>
			            </label>
			            <div class="d-flex flex-column flex-grow-1">
			                <div class="row">
			                    <div class="col sm-10">
			                        <strong style="font-size: 17px;">' . $value->survey_name . '</strong><br>
			                        <span class="text-dark">Organisasi yang disurvei : <b>' . $value->organisasi . '</b></span><br />
			                    </div>
			                    <div class="col sm-2 text-right"><div class="text-dark">Nilai IKM: <b>' .$nilai_indeks. '</b></div>
			                        <div class="mt-3 text-dark font-weight-bold" style="font-size: 11px;">
									Periode Survei : ' . date('d-m-Y', strtotime($value->survey_start)) . ' s/d ' . date('d-m-Y', strtotime($value->survey_end)) . '
			                        </div>

			                    </div>
			                </div>
			            </div>
			        </div>
			    </div>
			</a>';
            $data[] = $row;
        }

        $output = array(
            "draw"                 => $_POST['draw'],
            "recordsTotal"         => $this->models->count_all($data_user),
            "recordsFiltered"     => $this->models->count_filtered($data_user),
            "data"                 => $data,
        );

        echo json_encode($output);
    }

    public function add($username)//, $uuid_berlangganan
    {
        $this->load->library('form_validation');
        $this->load->helper('security');
        $this->load->library('uuid');
        $this->load->helper('slug');

        // ===================================== DITUTUP SEMENTARA =================================================================
        // $data_user = $this->_cek_user()->row();
        // if ($data_user->group_id != 2) {
        //     die("Sorry, You must be an client to view this page !");
        // }

        // if (Client_Controller::check_subscription() == false) {
        //     echo 'Expired';
        //     exit();
        // }

        // // cek uuid berlangganan
        // $this->db->select('berlangganan.id, berlangganan.id_paket');
        // $this->db->from('berlangganan');
        // $this->db->join('users', 'users.id = berlangganan.id_user');
        // $this->db->where('berlangganan.uuid', $uuid_berlangganan);
        // $this->db->where('users.username', $username);
        // $cek_berlangganan = $this->db->get();
        // if ($cek_berlangganan->num_rows() == 0) {
        //     die("Sorry, your links is not correctly !");
        // }

        // // cek uuid berlangganan
        // $this->db->select('berlangganan.uuid AS uuid_berlangganan, id_paket');
        // $this->db->from('berlangganan');
        // $this->db->join('users', 'users.id = berlangganan.id_user');
        // $this->db->where('users.username', $username);
        // $this->db->where('berlangganan.uuid', $uuid_berlangganan);
        // $berlangganan = $this->db->get()->row();

        // // cek paket jumlah kuota kuesioner
        // $id_paket = $berlangganan->id_paket;
        // $this->db->select('jumlah_kuesioner');
        // $this->db->from('paket');
        // $this->db->where('id', $id_paket);
        // $jumlah_kuesioner_paket = $this->db->get()->row()->jumlah_kuesioner;

        // // cek jumlah kuota kuesioner yang dipakai
        // $this->db->select('manage_survey.id');
        // $this->db->from('berlangganan');
        // $this->db->join('manage_survey', 'manage_survey.id_berlangganan = berlangganan.id');
        // $this->db->where('berlangganan.uuid', $uuid_berlangganan);
        // $this->db->where('berlangganan.id_paket', $id_paket);
        // $jumlah_kuesioner_dibuat = $this->db->get()->num_rows();

        // $pemakaian  = $jumlah_kuesioner_paket - $jumlah_kuesioner_dibuat;

        // if ($pemakaian == 0) {
        //     die("Sorry, Your quota has reached the limit !");
        // }
        // ===================================== DITUTUP SEMENTARA =================================================================

        $this->data = [];
        $this->data['title'] = "Create New Survey";


        // $this->data['form_action'] = base_url() . $this->session->userdata('username') . "/manage-survey/save-survey/" . $this->uri->segment(4);
        $this->data['form_action'] = base_url() . $this->session->userdata('username') . "/manage-survey/save-survey";
        $this->data['data_user'] = $this->ion_auth->user()->row();


        $this->db->select('users.id, users.id_klasifikasi_survei');
        $this->db->from('users');
        $this->db->where('users.username', $this->uri->segment(1));
        $klasifikasi = $this->db->get()->row()->id_klasifikasi_survei;

        $this->data['jenis_pelayanan'] = $this->models->getAll($klasifikasi);

        $this->form_validation->set_rules('survey_name', 'Nama Survey', 'trim|required|xss_clean');
        $this->form_validation->set_rules('organisasi', 'Organisasi', 'trim|required|xss_clean');
        $this->form_validation->set_rules('tanggal_survei', 'Tanggal Survei', 'trim|required');
        $this->form_validation->set_rules('description', 'Deskripsi', 'trim|xss_clean');
        $this->form_validation->set_rules('jumlah_populasi', 'Jumlah Populasi', 'trim|xss_clean');

        $this->data['survey_name'] = [
            'name'         => 'survey_name',
            'id'        => 'survey_name',
            'type'        => 'text',
            'value'        => $this->form_validation->set_value('survey_name'),
            'class'        => 'form-control',
            'required'    => 'required',
            'placeholder' => 'SKM Dinas XXX Kota XXX Tahun 2022, SKM Dinas XXX Periode Februari 2022 Sampai April 2022',
            'required' => 'required'
        ];

        $this->data['organisasi'] = [
            'name'         => 'organisasi',
            'id'        => 'organisasi',
            'type'        => 'text',
            'value'        => $this->form_validation->set_value('organisasi'),
            'class'        => 'form-control',
            'required'    => 'required',
            'placeholder' => 'Dinas XXX',
            'required' => 'required'
        ];

        $this->data['id_sampling'] = [
            'name'         => 'id_sampling',
            'id'         => 'id_sampling',
            'options'     => $this->models->dropdown_sampling(),
            'selected'     => $this->form_validation->set_value('id_sampling'),
            'class'     => "form-control",
            'required' => 'required'
        ];

        $this->data['survey_start'] = [
            'name'         => 'survey_start',
            'id'        => 'survey_start',
            'type'        => 'date',
            'value'        => $this->form_validation->set_value('survey_start'),
            'class'        => 'form-control',
            'required'    => 'required',
            'style'        => 'width: 200px;',
            'required' => 'required'
        ];

        $this->data['survey_end'] = [
            'name'         => 'survey_end',
            'id'        => 'survey_end',
            'type'        => 'date',
            'value'        => $this->form_validation->set_value('survey_end'),
            'class'        => 'form-control',
            'required'    => 'required',
            'style'        => 'width: 200px;',
            'required' => 'required'
        ];

        $this->data['description'] = [
            'name'         => 'description',
            'id'        => 'description',
            'type'        => 'text',
            'value'        => $this->form_validation->set_value('description'),
            'class'        => 'form-control',
            'rows'         => '3',
            'placeholder' => 'Survei Kepuasan Masyarakat Dinas XXX Kota XXX'
        ];


        $this->data['is_privacy'] = $this->form_validation->set_value('is_privacy');

        return view('manage_survey/form_add', $this->data);
    }

    public function create($username)//, $uuid_berlangganan
    {
        $this->load->helper('slug');
        $this->load->library('uuid');

        if ($this->input->post('custom') == "Custom") {
            $slug = slug($this->input->post('link'));
        } else {
            $slug = slug($this->input->post('survey_name'));
        };


        if ($this->input->post('id_sampling') == 1) {
            $jumlah_sampling = $this->input->post('populasi_krejcie');
            $sampling = $this->input->post('total_krejcie');
        } else if ($this->input->post('id_sampling') == 3) {
            $jumlah_sampling = $this->input->post('populasi_slovin');
            $sampling = $this->input->post('total_slovin');
        } else {
            $jumlah_sampling = null;
            $sampling = null;
        };

        // if ($this->input->post('id_jenis_pelayanan') == NULL) {
        // 	$id_jenis_pelayanan = NULL;
        // } else {
        // 	$id_jenis_pelayanan = $this->input->post('id_jenis_pelayanan');
        // };

        if ($this->input->post('template') == 1) {
            $id_jenis_pelayanan = $this->input->post('id_jenis_pelayanan');
            $skala_likert = 4;
            $is_kategori_layanan = 0;
        } elseif ($this->input->post('template') == 3) {
            $idntas_tabel = $this->db->get_where('manage_survey', array('id' => $this->input->post('id_manage_survey')))->row();

            $id_jenis_pelayanan = $idntas_tabel->id_jenis_pelayanan;
            $skala_likert = $idntas_tabel->skala_likert;
            $is_kategori_layanan = $idntas_tabel->is_kategori_layanan_survei;
        } else {
            $id_jenis_pelayanan = null;
            // $skala_likert = $this->input->post('skala_likert');
            $skala_likert = 4;
            $is_kategori_layanan = 0;
        }


        // Cari id berlangganan
        // $this->db->select('*');
        // $this->db->from('berlangganan');
        // $this->db->where('uuid', $uuid_berlangganan);
        // $berlangganan = $this->db->get()->row();

        // split tanggal
        $split = explode("/", str_replace(" ", "", $this->input->post('tanggal_survei')));
        $survey_start = $split[0];
        $survey_end = $split[1];

        $object = [
            'uuid' => $this->uuid->v4(),
            'survey_name' => $this->input->post('survey_name'),
            'organisasi' => $this->input->post('organisasi'),
            'id_template' => $this->input->post('template'),
            'id_user' => $this->session->userdata('user_id'), //$berlangganan->id_user,
            'survey_start' => $survey_start,
            'survey_end' => $survey_end,
            'survey_year' => date('Y'),
            'description' => $this->input->post('description'),
            'is_privacy' => 1, //$this->input->post('is_privacy'),
            'slug' => 1,
            'id_sampling' => $this->input->post('id_sampling'),
            'jumlah_populasi' => $jumlah_sampling,
            'deskripsi_tunda' => 'Mohon maaf, survei ditunda dan akan dilanjutkan kembali pada',
            'is_question' => '1',
            'jumlah_sampling' => $sampling,
            'id_jenis_pelayanan' => $id_jenis_pelayanan,
            'created_at' => date("Y/m/d H:i:s"),
            'title_header_survey' => serialize(array("SURVEI KEPUASAN MASYARAKAT", $this->input->post('organisasi'))),
            //'id_berlangganan' => 0, //$berlangganan->id
            'atribut_pertanyaan_survey' => serialize($this->input->post('atribut_pertanyaan')),
            'skala_likert' => $skala_likert,
            'is_saran' => 1,
            'judul_form_saran' => 'Saran / Opini Anda',
            'is_layanan_survei' => 1,
            'is_kategori_layanan_survei' => $is_kategori_layanan,
            'is_chart_layanan_survei' => 1,

            // 'deskripsi_opening_survey' => '<p>Bapak/Ibu yang terhormat,<br/><br>Dalam rangka mengukur Indeks Kepuasan Masyarakat, Bapak/Ibu dipercaya menjadi responden untuk menilai tingkat pelayanan fasilitas umum</p><p>Atas kesediaan Saudara kami sampaikan terima kasih dan penghargaan sedalam-dalamnya.<br/>Jika ada yang perlu dikonfirmasikan terkait survei ini dapat menghubungi WhatsApp +62 895-3362-29033</p><br><p>Hormat kami,</p><b>Tim Survei Indeks Kepuasan Masyarakat</b>',
            'deskripsi_opening_survey' => '<p>Dalam rangka meningkatkan kepuasan masyarakat, Saudara dipercaya menjadi responden pada kegiatan survei ini. Atas kesediaan Saudara memberikan pendapat kami sampaikan terima kasih dan penghargaan sedalam-dalamnya.</p>',

            'deskripsi_konfirmasi_survei' => 'Kuesioner Anda sudah diisi, silahkan klik tombol SUBMIT Kuesioner untuk mengakhiri survey.',

            // 'deskripsi_selesai_survei' => 'Terima kasih atas kesediaannya dan partisipasinya untuk mengisi kuesioner Survei Kepuasan Masyarakat.<br>Saran dan penilaian Saudara memberikan konstribusi yang sangat berarti bagi peningkatan instansi kami.',
            'deskripsi_selesai_survei' => 'Terima kasih atas kesediaan Saudara untuk mengisi kuesioner.',

            'template_email_prospek' => '<p>Kami Tim Survey Kepuasan Masyarakat ${1},</p>
			<p>memohon kepada Bapak/ Ibu, untuk mengisi Kuesioner ${2} dengan link berikut ini ${3}. Mohon diisi sebelum tanggal ${4}. Atas kesedian dan partisipasinya kami ucapkan Terima Kasih.</p>',

            'template_whatsapp_prospek' => '<p>Kami Tim Survei Kepuasan Masyarakat ${1}, memohon kepada Bapak/ Ibu, untuk mengisi Kuesioner ${2} dengan link berikut ini ${3}. Mohon diisi sebelum tanggal ${4}. Atas kesedian dan partisipasinya kami ucapkan Terima Kasih.</p>',

        ];
        $this->db->insert('manage_survey', $object);
        // var_dump($object);

        $insert_id = $this->db->insert_id();

        $cek = $this->db->query("SELECT * FROM manage_survey WHERE slug = '$slug'");
        if ($cek->num_rows() == 0) {
            $value_slug = $slug;
        } else {
            $value_slug = $slug . '-' . $insert_id;
        };

        // LAKUKAN UPDATE KOLOM table_identity
        $last_object = [
            'slug' => $value_slug,
            'table_identity' => "cst" . $insert_id
        ];
        $this->db->where('id', $insert_id);
        $this->db->update('manage_survey', $last_object);

        $fk = 'survey_cst' . $insert_id . '_ibfk_1';
        $fk1 = 'jawaban_pertanyaan_unsur_cst' . $insert_id . '_ibfk_2';
        $fk2 = 'jawaban_pertanyaan_terbuka_cst' . $insert_id . '_ibfk_2';
        $fk3 = 'jawaban_pertanyaan_kualitatif_cst' . $insert_id . '_ibfk_2';
        $fk4 = 'pertanyaan_kualitatif_cst' . $insert_id . '_ibfk_2';
        $fk5 = 'pertanyaan_unsur_pelayanan_cst' . $insert_id . '_ibfk_2';
        $fk6 = 'kategori_unsur_pelayanan_cst' . $insert_id . '_ibfk_1';
        $fk7 = 'pertanyaan_terbuka_cst' . $insert_id . '_ibfk_3';
        $fk8 = 'perincian_pertanyaan_terbuka_cst' . $insert_id . '_ibfk_2';
        $fk9 = 'isi_pertanyaan_ganda_cst' . $insert_id . '_ibfk_2';
        $fk10 = 'jawaban_pertanyaan_harapan_cst' . $insert_id . '_ibfk_3';
        $fk11 = 'nilai_tingkat_kepentingan_cst' . $insert_id . '_ibfk_3';
        $fk12 = 'kategori_profil_responden_cst' . $insert_id . '_ibfk_3';

        $fk13 = 'koreksi_survey_cst' . $insert_id . '_ibfk_1';
        $fk14 = 'koreksi_jawaban_pertanyaan_unsur_cst' . $insert_id . '_ibfk_2';


        $tb_survey = 'survey_cst' . $insert_id;
        $tb_responden = 'responden_cst' . $insert_id;
        $tb_jawaban_pertanyaan_unsur = 'jawaban_pertanyaan_unsur_cst' . $insert_id;
        $tb_jawaban_pertanyaan_terbuka = 'jawaban_pertanyaan_terbuka_cst' . $insert_id;
        $tb_pertanyaan_kualitatif = 'pertanyaan_kualitatif_cst' . $insert_id;
        $tb_jawaban_pertanyaan_kualitatif = 'jawaban_pertanyaan_kualitatif_cst' . $insert_id;
        $tb_unsur_pelayanan = 'unsur_pelayanan_cst' . $insert_id;
        $tb_pertanyaan_unsur_pelayanan = 'pertanyaan_unsur_pelayanan_cst' . $insert_id;
        $tb_kategori_unsur_pelayanan = 'kategori_unsur_pelayanan_cst' . $insert_id;
        $tb_pertanyaan_terbuka = 'pertanyaan_terbuka_cst' . $insert_id;
        $tb_perincian_pertanyaan_terbuka = 'perincian_pertanyaan_terbuka_cst' . $insert_id;
        $tb_isi_pertanyaan_ganda = 'isi_pertanyaan_ganda_cst' . $insert_id;
        $tb_jawaban_pertanyaan_harapan = 'jawaban_pertanyaan_harapan_cst' . $insert_id;
        $tb_nilai_tingkat_kepentingan = 'nilai_tingkat_kepentingan_cst' . $insert_id;
        $tb_data_prospek_survey = 'data_prospek_survey_cst' . $insert_id;
        $tb_log_survey = 'log_survey_cst' . $insert_id;
        $tb_profil_responden = 'profil_responden_cst' . $insert_id;
        $tb_kategori_profil_responden = 'kategori_profil_responden_cst' . $insert_id;
        $tb_analisa = 'analisa_cst' . $insert_id;
        $tb_definisi_skala = 'definisi_skala_cst' . $insert_id;
        $log_report = 'log_report_cst' . $insert_id;



        if ($this->input->post('template') == 3) {
            $idnt_tabel = $this->db->get_where('manage_survey', array('id' => $this->input->post('id_manage_survey')))->row()->table_identity;

            $this->db->query("CREATE TABLE kategori_layanan_survei_cst$insert_id (id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY) AS SELECT * FROM kategori_layanan_survei_$idnt_tabel");
            $this->db->query("CREATE TABLE layanan_survei_cst$insert_id (id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY) AS SELECT * FROM layanan_survei_$idnt_tabel");

            $this->db->query("CREATE TABLE $tb_responden LIKE responden_$idnt_tabel");
            $this->db->query("CREATE TABLE trash_responden_cst$insert_id LIKE responden_$idnt_tabel");
        } else {

            $this->db->query("CREATE TABLE kategori_layanan_survei_cst$insert_id LIKE kategori_layanan_survei");
            $this->db->query("CREATE TABLE layanan_survei_cst$insert_id LIKE layanan_survei");


            $this->db->query("CREATE TABLE $tb_responden LIKE responden");
            $this->db->query("CREATE TABLE trash_responden_cst$insert_id LIKE responden");
        }


        // $this->db->query("CREATE TABLE $tb_responden LIKE responden");
        $this->db->query("CREATE TABLE $tb_survey LIKE survey");
        $this->db->query("CREATE TABLE $tb_jawaban_pertanyaan_unsur LIKE jawaban_pertanyaan_unsur");
        $this->db->query("CREATE TABLE $tb_jawaban_pertanyaan_terbuka LIKE jawaban_pertanyaan_terbuka");
        $this->db->query("CREATE TABLE $tb_pertanyaan_kualitatif LIKE pertanyaan_kualitatif");
        $this->db->query("CREATE TABLE $tb_jawaban_pertanyaan_kualitatif LIKE jawaban_pertanyaan_kualitatif");
        $this->db->query("CREATE TABLE $tb_jawaban_pertanyaan_harapan LIKE jawaban_pertanyaan_harapan");
        $this->db->query("CREATE TABLE $tb_log_survey LIKE log_survey");
        $this->db->query("CREATE TABLE $log_report LIKE log_report");

        $this->db->query("CREATE TABLE $tb_profil_responden LIKE profil_responden");
        $this->db->query("CREATE TABLE $tb_kategori_profil_responden LIKE kategori_profil_responden");

        $this->db->query("CREATE TABLE $tb_data_prospek_survey LIKE data_prospek_survey;");


        $this->db->query("CREATE TABLE $tb_analisa LIKE analisa");

        $this->db->query("CREATE TABLE $tb_definisi_skala LIKE definisi_skala");
        $this->db->query("INSERT INTO $tb_definisi_skala SELECT * FROM definisi_skala WHERE skala_likert = IF($skala_likert = 5, 5, 4) && skala_likert = IF($skala_likert = 5, 5, 4)");

        $this->db->query("ALTER TABLE $tb_survey  ADD CONSTRAINT $fk FOREIGN KEY (`id_responden`) REFERENCES $tb_responden(`id`) ON DELETE CASCADE ON UPDATE CASCADE");

        $this->db->query("ALTER TABLE $tb_jawaban_pertanyaan_unsur  ADD CONSTRAINT $fk1 FOREIGN KEY (`id_responden`) REFERENCES $tb_responden(`id`) ON DELETE CASCADE ON UPDATE CASCADE");

        $this->db->query("ALTER TABLE $tb_jawaban_pertanyaan_harapan  ADD CONSTRAINT $fk10 FOREIGN KEY (`id_responden`) REFERENCES $tb_responden(`id`) ON DELETE CASCADE ON UPDATE CASCADE");

        $this->db->query("ALTER TABLE $tb_jawaban_pertanyaan_terbuka  ADD CONSTRAINT $fk2 FOREIGN KEY (`id_responden`) REFERENCES $tb_responden(`id`) ON DELETE CASCADE ON UPDATE CASCADE");

        $this->db->query("ALTER TABLE $tb_jawaban_pertanyaan_kualitatif  ADD CONSTRAINT $fk3 FOREIGN KEY (`id_responden`) REFERENCES $tb_responden(`id`) ON DELETE CASCADE ON UPDATE CASCADE");

        $this->db->query("ALTER TABLE $tb_jawaban_pertanyaan_kualitatif  ADD CONSTRAINT $fk4 FOREIGN KEY (`id_pertanyaan_kualitatif`) REFERENCES $tb_pertanyaan_kualitatif(`id`) ON DELETE CASCADE ON UPDATE CASCADE");

        //BUAT TABEL UNTUK MENAMPUNG DATA RESPONDEN YANG DI HAPUS
        // $this->db->query("CREATE TABLE trash_responden_cst$insert_id LIKE responden");
        $this->db->query("CREATE TABLE trash_survey_cst$insert_id LIKE survey");
        $this->db->query("CREATE TABLE trash_jawaban_pertanyaan_unsur_cst$insert_id LIKE jawaban_pertanyaan_unsur");
        $this->db->query("CREATE TABLE trash_jawaban_pertanyaan_terbuka_cst$insert_id LIKE jawaban_pertanyaan_terbuka");
        $this->db->query("CREATE TABLE trash_jawaban_pertanyaan_kualitatif_cst$insert_id LIKE jawaban_pertanyaan_kualitatif");
        $this->db->query("CREATE TABLE trash_jawaban_pertanyaan_harapan_cst$insert_id LIKE jawaban_pertanyaan_harapan");

        if ($this->input->post('template') == 1) {

            // $this->db->query("INSERT INTO $tb_profil_responden (id, nama_profil_responden, jenis_isian, is_default, type_data, urutan, nama_alias) VALUES (1, 'Nama Lengkap', 2, 1, 'text', 1, 'nama_lengkap')");

            $this->db->query(
                "CREATE TABLE $tb_unsur_pelayanan LIKE unsur_pelayanan;"
            );

            $this->db->query(
                "INSERT INTO $tb_unsur_pelayanan
			SELECT *
			FROM unsur_pelayanan
			WHERE id_jenis_pelayanan = $id_jenis_pelayanan;"
            );

            $this->db->query("CREATE TABLE $tb_pertanyaan_unsur_pelayanan LIKE pertanyaan_unsur_pelayanan;");

            $this->db->query(
                "INSERT INTO $tb_pertanyaan_unsur_pelayanan
			SELECT pertanyaan_unsur_pelayanan.id, id_unsur_pelayanan, isi_pertanyaan_unsur, gambar_pertanyaan_unsur, tampilkan_gambar, jenis_pilihan_jawaban
			FROM pertanyaan_unsur_pelayanan
			JOIN unsur_pelayanan ON pertanyaan_unsur_pelayanan.id_unsur_pelayanan = unsur_pelayanan.id
			JOIN jenis_pelayanan ON unsur_pelayanan.id_jenis_pelayanan = jenis_pelayanan.id
			WHERE id_jenis_pelayanan = $id_jenis_pelayanan;"
            );

            $this->db->query(
                "CREATE TABLE $tb_kategori_unsur_pelayanan LIKE kategori_unsur_pelayanan;"
            );
            $this->db->query(
                "INSERT INTO $tb_kategori_unsur_pelayanan
			SELECT kategori_unsur_pelayanan.id, kategori_unsur_pelayanan.id_unsur_pelayanan, kategori_unsur_pelayanan.id_pertanyaan_unsur, nomor_kategori_unsur_pelayanan, nama_kategori_unsur_pelayanan, is_next_step
			FROM kategori_unsur_pelayanan
			JOIN pertanyaan_unsur_pelayanan ON kategori_unsur_pelayanan.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan.id
			JOIN unsur_pelayanan ON pertanyaan_unsur_pelayanan.id_unsur_pelayanan = unsur_pelayanan.id
			JOIN jenis_pelayanan ON unsur_pelayanan.id_jenis_pelayanan = jenis_pelayanan.id
			WHERE id_jenis_pelayanan = $id_jenis_pelayanan;"
            );

            $this->db->query(
                "CREATE TABLE $tb_nilai_tingkat_kepentingan LIKE nilai_tingkat_kepentingan;"
            );
            $this->db->query(
                "INSERT INTO $tb_nilai_tingkat_kepentingan
			SELECT nilai_tingkat_kepentingan.id, nilai_tingkat_kepentingan.id_pertanyaan_unsur_pelayanan, nomor_tingkat_kepentingan, nama_tingkat_kepentingan
			FROM nilai_tingkat_kepentingan
			JOIN pertanyaan_unsur_pelayanan ON nilai_tingkat_kepentingan.id_pertanyaan_unsur_pelayanan = pertanyaan_unsur_pelayanan.id
			JOIN unsur_pelayanan ON pertanyaan_unsur_pelayanan.id_unsur_pelayanan = unsur_pelayanan.id
			JOIN jenis_pelayanan ON unsur_pelayanan.id_jenis_pelayanan = jenis_pelayanan.id
			WHERE id_jenis_pelayanan = $id_jenis_pelayanan;"
            );

            $this->db->query(
                "CREATE TABLE $tb_pertanyaan_terbuka LIKE pertanyaan_terbuka;"
            );
            $this->db->query(
                "INSERT INTO $tb_pertanyaan_terbuka
			SELECT pertanyaan_terbuka.id, id_unsur_pelayanan, nama_pertanyaan_terbuka, nomor_pertanyaan_terbuka, is_letak_pertanyaan, pertanyaan_terbuka.is_required
			FROM pertanyaan_terbuka
			JOIN unsur_pelayanan ON pertanyaan_terbuka.id_unsur_pelayanan = unsur_pelayanan.id
			JOIN jenis_pelayanan ON unsur_pelayanan.id_jenis_pelayanan = jenis_pelayanan.id
			WHERE id_jenis_pelayanan = $id_jenis_pelayanan;"
            );

            $this->db->query(
                "CREATE TABLE $tb_perincian_pertanyaan_terbuka LIKE perincian_pertanyaan_terbuka;"
            );
            $this->db->query(
                "INSERT INTO $tb_perincian_pertanyaan_terbuka
			SELECT perincian_pertanyaan_terbuka.id, id_pertanyaan_terbuka, id_jenis_pilihan_jawaban, isi_pertanyaan_terbuka
			FROM perincian_pertanyaan_terbuka
			JOIN pertanyaan_terbuka ON perincian_pertanyaan_terbuka.id_pertanyaan_terbuka = pertanyaan_terbuka.id
			JOIN unsur_pelayanan ON pertanyaan_terbuka.id_unsur_pelayanan = unsur_pelayanan.id
			JOIN jenis_pelayanan ON unsur_pelayanan.id_jenis_pelayanan = jenis_pelayanan.id
			WHERE id_jenis_pelayanan = $id_jenis_pelayanan;"
            );

            $this->db->query(
                "CREATE TABLE $tb_isi_pertanyaan_ganda LIKE isi_pertanyaan_ganda;"
            );
            $this->db->query(
                "INSERT INTO $tb_isi_pertanyaan_ganda
			SELECT isi_pertanyaan_ganda.id, id_perincian_pertanyaan_terbuka, pertanyaan_ganda, dengan_isian_lainnya, nilai_pertanyaan_ganda, is_next_step
			FROM isi_pertanyaan_ganda
			JOIN perincian_pertanyaan_terbuka ON isi_pertanyaan_ganda.id_perincian_pertanyaan_terbuka = perincian_pertanyaan_terbuka.id
			JOIN pertanyaan_terbuka ON perincian_pertanyaan_terbuka.id_pertanyaan_terbuka = pertanyaan_terbuka.id
			JOIN unsur_pelayanan ON pertanyaan_terbuka.id_unsur_pelayanan = unsur_pelayanan.id
			JOIN jenis_pelayanan ON unsur_pelayanan.id_jenis_pelayanan = jenis_pelayanan.id
			WHERE id_jenis_pelayanan = $id_jenis_pelayanan;"
            );


        } elseif ($this->input->post('template') == 3) {

            //CEK REFERENSI PERTANYAAN DARI SURVEI MANA
            $identitas_tabel = $this->db->get_where('manage_survey', array('id' => $this->input->post('id_manage_survey')))->row()->table_identity;

            //CEK KOLOM
            // if ($this->db->field_exists('is_required', 'unsur_pelayanan_' . $identitas_tabel))
            // {

            // }else{
            //     $this->db->query("ALTER TABLE unsur_pelayanan_$identitas_tabel ADD is_required tinyint(1) NULL DEFAULT '1'");
            // }

            // if ($this->db->field_exists('is_alasan', 'unsur_pelayanan_' . $identitas_tabel))
            // {

            // }else{
            //     $this->db->query("ALTER TABLE unsur_pelayanan_$identitas_tabel ADD is_alasan tinyint(1) NULL DEFAULT '1'");
            // }

            // if ($this->db->field_exists('is_required', 'profil_responden_' . $identitas_tabel))
            // {
                
            // }else{
            //     $this->db->query("ALTER TABLE profil_responden_$identitas_tabel ADD is_required tinyint(1) NULL DEFAULT '1'");
            // }

            $this->db->query("INSERT INTO $tb_profil_responden SELECT * FROM profil_responden_$identitas_tabel");
            $this->db->query("INSERT INTO $tb_kategori_profil_responden SELECT * FROM kategori_profil_responden_$identitas_tabel");

            $this->db->query("CREATE TABLE $tb_unsur_pelayanan LIKE unsur_pelayanan_$identitas_tabel");
            $this->db->query("INSERT INTO $tb_unsur_pelayanan SELECT * FROM unsur_pelayanan_$identitas_tabel");

            $this->db->query("CREATE TABLE $tb_pertanyaan_unsur_pelayanan LIKE pertanyaan_unsur_pelayanan_$identitas_tabel");
            $this->db->query("INSERT INTO $tb_pertanyaan_unsur_pelayanan SELECT * FROM pertanyaan_unsur_pelayanan_$identitas_tabel");

            $this->db->query("CREATE TABLE $tb_kategori_unsur_pelayanan LIKE kategori_unsur_pelayanan_$identitas_tabel");
            $this->db->query("INSERT INTO $tb_kategori_unsur_pelayanan SELECT * FROM kategori_unsur_pelayanan_$identitas_tabel");

            $this->db->query("CREATE TABLE $tb_nilai_tingkat_kepentingan LIKE nilai_tingkat_kepentingan_$identitas_tabel");
            $this->db->query("INSERT INTO $tb_nilai_tingkat_kepentingan SELECT * FROM nilai_tingkat_kepentingan_$identitas_tabel");

            $this->db->query("CREATE TABLE $tb_pertanyaan_terbuka LIKE pertanyaan_terbuka_$identitas_tabel");
            $this->db->query("INSERT INTO $tb_pertanyaan_terbuka SELECT * FROM pertanyaan_terbuka_$identitas_tabel");

            $this->db->query("CREATE TABLE $tb_perincian_pertanyaan_terbuka LIKE perincian_pertanyaan_terbuka_$identitas_tabel");
            $this->db->query("INSERT INTO $tb_perincian_pertanyaan_terbuka SELECT * FROM perincian_pertanyaan_terbuka_$identitas_tabel");

            $this->db->query("CREATE TABLE $tb_isi_pertanyaan_ganda LIKE isi_pertanyaan_ganda_$identitas_tabel");
            $this->db->query("INSERT INTO $tb_isi_pertanyaan_ganda SELECT * FROM isi_pertanyaan_ganda_$identitas_tabel");
        } else {

            // $this->db->query("INSERT INTO $tb_profil_responden (id, nama_profil_responden, jenis_isian, is_default, type_data, urutan, nama_alias) VALUES (1, 'Nama Lengkap', 2, 1, 'text', 1, 'nama_lengkap')");

            $this->db->query("CREATE TABLE $tb_unsur_pelayanan LIKE unsur_pelayanan;");
            $this->db->query("CREATE TABLE $tb_pertanyaan_unsur_pelayanan LIKE pertanyaan_unsur_pelayanan;");
            $this->db->query("CREATE TABLE $tb_kategori_unsur_pelayanan LIKE kategori_unsur_pelayanan;");
            $this->db->query("CREATE TABLE $tb_nilai_tingkat_kepentingan LIKE nilai_tingkat_kepentingan;");
            $this->db->query("CREATE TABLE $tb_pertanyaan_terbuka LIKE pertanyaan_terbuka;");
            $this->db->query("CREATE TABLE $tb_perincian_pertanyaan_terbuka LIKE perincian_pertanyaan_terbuka;");
            $this->db->query("CREATE TABLE $tb_isi_pertanyaan_ganda LIKE isi_pertanyaan_ganda;");
        }


        $this->db->query("
			ALTER TABLE $tb_pertanyaan_unsur_pelayanan  ADD CONSTRAINT $fk5 FOREIGN KEY (`id_unsur_pelayanan`) REFERENCES $tb_unsur_pelayanan(`id`) ON DELETE CASCADE ON UPDATE CASCADE
			");

        $this->db->query("
			ALTER TABLE $tb_kategori_unsur_pelayanan  ADD CONSTRAINT $fk6 FOREIGN KEY (`id_unsur_pelayanan`) REFERENCES $tb_unsur_pelayanan(`id`) ON DELETE CASCADE ON UPDATE CASCADE
			");

        $this->db->query("
			ALTER TABLE $tb_nilai_tingkat_kepentingan  ADD CONSTRAINT $fk11 FOREIGN KEY (`id_pertanyaan_unsur_pelayanan`) REFERENCES $tb_pertanyaan_unsur_pelayanan(`id`) ON DELETE CASCADE ON UPDATE CASCADE
			");

        $this->db->query("
			ALTER TABLE $tb_pertanyaan_terbuka  ADD CONSTRAINT $fk7 FOREIGN KEY (`id_unsur_pelayanan`) REFERENCES $tb_unsur_pelayanan(`id`) ON DELETE CASCADE ON UPDATE CASCADE
			");

        $this->db->query("
			ALTER TABLE $tb_perincian_pertanyaan_terbuka  ADD CONSTRAINT $fk8 FOREIGN KEY (`id_pertanyaan_terbuka`) REFERENCES $tb_pertanyaan_terbuka(`id`) ON DELETE CASCADE ON UPDATE CASCADE
			");

        $this->db->query("
			ALTER TABLE $tb_isi_pertanyaan_ganda ADD CONSTRAINT $fk9 FOREIGN KEY (`id_perincian_pertanyaan_terbuka`) REFERENCES $tb_perincian_pertanyaan_terbuka(`id`) ON DELETE CASCADE ON UPDATE CASCADE
			");

        $this->db->query("
		CREATE TRIGGER log_app_cst$insert_id AFTER INSERT ON responden_cst$insert_id
		FOR EACH ROW BEGIN 
		INSERT INTO log_survey_cst$insert_id(log_value, log_time) VALUES(CONCAT(NEW.uuid, ', sudah mengisi survei'), DATE_ADD(NOW(), INTERVAL 13 HOUR));		
		END");

        // mkdir('./assets/klien/survei/cst' . $insert_id);
        // mkdir('./assets/klien/survei/cst' . $insert_id . '/chart_profil_responden');
        // mkdir('./assets/klien/survei/cst' . $insert_id . '/chart_unsur');

        $pesan = 'Data berhasil disimpan';
        $msg = ['sukses' => $pesan];
        echo json_encode($msg);
    }

    public function edit($id = null)
    {
        $this->data = [];
        $this->data['title'] = "Kelola Survey";

        return view('manage_survey/index', $this->data);
    }

    // public function delete($id1 = NULL, $id2 = NULL)
    public function delete_survey($id)
    {
        // CARI DATA UNTUK MENGHAPUS TABEL
        $this->db->select('id, table_identity');
        $this->db->from('manage_survey');
        $this->db->where('id', $id);
        $current = $this->db->get()->row();

        // HAPUS TABEL
        $this->load->dbforge();
        // $this->db->query('use kjsnneu7_e_skm');
        $this->dbforge->drop_table('layanan_survei_' . $current->table_identity, true);
        $this->dbforge->drop_table('kategori_layanan_survei_' . $current->table_identity, true);
        $this->dbforge->drop_table('analisa_' . $current->table_identity, true);
        $this->dbforge->drop_table('jawaban_pertanyaan_unsur_' . $current->table_identity, true);
        $this->dbforge->drop_table('jawaban_pertanyaan_harapan_' . $current->table_identity, true);
        $this->dbforge->drop_table('jawaban_pertanyaan_terbuka_' . $current->table_identity, true);
        $this->dbforge->drop_table('jawaban_pertanyaan_kualitatif_' . $current->table_identity, true);
        $this->dbforge->drop_table('definisi_skala_' . $current->table_identity, true);
        $this->dbforge->drop_table('kategori_profil_responden_' . $current->table_identity, true);
        $this->dbforge->drop_table('profil_responden_' . $current->table_identity, true);
        $this->dbforge->drop_table('pertanyaan_kualitatif_' . $current->table_identity, true);
        $this->dbforge->drop_table('nilai_tingkat_kepentingan_' . $current->table_identity, true);
        $this->dbforge->drop_table('kategori_unsur_pelayanan_' . $current->table_identity, true);
        $this->dbforge->drop_table('pertanyaan_unsur_pelayanan_' . $current->table_identity, true);
        $this->dbforge->drop_table('isi_pertanyaan_ganda_' . $current->table_identity, true);
        $this->dbforge->drop_table('perincian_pertanyaan_terbuka_' . $current->table_identity, true);
        $this->dbforge->drop_table('pertanyaan_terbuka_' . $current->table_identity, true);
        $this->dbforge->drop_table('unsur_pelayanan_' . $current->table_identity, true);
        $this->dbforge->drop_table('data_prospek_survey_' . $current->table_identity, true);

        $this->dbforge->drop_table('origin_jawaban_pertanyaan_unsur_' . $current->table_identity, true);
        $this->dbforge->drop_table('origin_jawaban_pertanyaan_harapan_' . $current->table_identity, true);
        $this->dbforge->drop_table('origin_jawaban_pertanyaan_terbuka_' . $current->table_identity, true);
        $this->dbforge->drop_table('origin_jawaban_pertanyaan_kualitatif_' . $current->table_identity, true);
        $this->dbforge->drop_table('origin_survey_' . $current->table_identity, true);
        $this->dbforge->drop_table('origin_responden_' . $current->table_identity, true);

        $this->dbforge->drop_table('koreksi_jawaban_pertanyaan_unsur_' . $current->table_identity, true);
        $this->dbforge->drop_table('koreksi_jawaban_pertanyaan_harapan_' . $current->table_identity, true);
        $this->dbforge->drop_table('koreksi_jawaban_pertanyaan_terbuka_' . $current->table_identity, true);
        $this->dbforge->drop_table('koreksi_jawaban_pertanyaan_kualitatif_' . $current->table_identity, true);
        $this->dbforge->drop_table('koreksi_survey_' . $current->table_identity, true);
        $this->dbforge->drop_table('koreksi_responden_' . $current->table_identity, true);
        $this->dbforge->drop_table('trash_jawaban_pertanyaan_unsur_' . $current->table_identity, true);
        $this->dbforge->drop_table('trash_jawaban_pertanyaan_harapan_' . $current->table_identity, true);
        $this->dbforge->drop_table('trash_jawaban_pertanyaan_terbuka_' . $current->table_identity, true);
        $this->dbforge->drop_table('trash_jawaban_pertanyaan_kualitatif_' . $current->table_identity, true);
        $this->dbforge->drop_table('trash_survey_' . $current->table_identity, true);
        $this->dbforge->drop_table('trash_responden_' . $current->table_identity, true);
        $this->dbforge->drop_table('survey_' . $current->table_identity, true);
        $this->dbforge->drop_table('log_survey_' . $current->table_identity, true);
        $this->dbforge->drop_table('responden_' . $current->table_identity, true);

        $surveyor = $this->db->get_where('surveyor', array('id_manage_survey' => $current->id));

        if ($surveyor->num_rows() != 0) {

            foreach ($surveyor->result() as $row) {
                $id_users[] = $row->id_user;
            }
            $data = implode(", ", $id_users);

            $this->db->where('id_manage_survey', $current->id);
            $this->db->delete('surveyor');

            $this->db->where_in('user_id', $data);
            $this->db->delete('users_groups');

            $this->db->where_in('id', $data);
            $this->db->delete('users');
        }

        // HAPUS DATA TABEL SURVEY
        $this->db->where('id', $id);
        $this->db->delete('manage_survey');

        echo json_encode(array("status" => true));
    }

    public function repository($id1, $id2)
    {
        $url = $this->uri->uri_string();
        $this->session->set_userdata('urlback', $url);

        // $cek = $this->db->get_where('manage_survey', ['slug' => $this->uri->segment(2)]);
        // if ($cek->num_rows() == 0) {
        // 	show_404();
        // }

        $this->data = [];

        $this->data['profiles'] = $this->_get_data_profile($id1, $id2);
        $this->data['title'] = 'Deskripsi Survei';
        $jumlah_sampling = $this->data['profiles']->jumlah_sampling;

        $this->data['form_action'] = base_url() . $id1 . '/' . $id2 . '/' . $this->data['profiles']->id_manage_survey . "/update_info";
        $this->data['form_action_update_logo'] = base_url() . $id1 . '/' . $id2 . '/' . $this->data['profiles']->id_manage_survey . "/update_logo";

        $this->db->select('*, manage_survey.id AS id_manage_survey');
        $this->db->from('manage_survey');
        $this->db->where('manage_survey.slug', $this->uri->segment(2));
        $manage_survey = $this->db->get()->row();
        $this->data['manage_survey'] = $manage_survey;

        //JUMLAH KUISIONER
        $this->db->select('COUNT(id) AS id');
        $this->db->from('survey_' . $manage_survey->table_identity);
        $this->db->where("is_submit = 1");
        $this->data['jumlah_kuisioner'] = $this->db->get()->row()->id;
        $jumlah_kuisioner = $this->data['jumlah_kuisioner'];

        $this->data['sampling_belum'] = $this->db->query("SELECT ($jumlah_sampling - $jumlah_kuisioner) AS sample_kurang")->row()->sample_kurang;

        $this->data['id_sampling'] = [
            'name'         => 'id_sampling',
            'id'         => 'id_sampling',
            'options'     => $this->models->dropdown_sampling(),
            'selected'     => $this->form_validation->set_value('id_sampling', $manage_survey->id_sampling),
            'class'     => "form-control",
        ];

        return view('manage_survey/form_repository', $this->data);
    }

    public function update_repository()
    {
        $slug = $this->uri->segment(2);
        $jumlah_populasi = $this->input->post('jumlah_populasi');

        if ($this->input->post('id_sampling') == 1) {
            $sampling = $this->db->query("SELECT CEIL((3.841 * $jumlah_populasi * 0.5 * 0.5)/((0.05 * 0.05) * ($jumlah_populasi - 1) + (3.841 * 0.5 * 0.5)))AS krejcie")->row()->krejcie;
        } else if ($this->input->post('id_sampling') == 3) {
            $sampling = $this->db->query("SELECT CEIL(($jumlah_populasi / (1 + $jumlah_populasi  * (0.05 * 0.05)))) AS slovin")->row()->slovin;
        } else {
            $sampling = null;
        };

        $object = [
            'survey_name' => $this->input->post('nama_survei'),
            'organisasi' => $this->input->post('organisasi'),
            'description' => $this->input->post('deskripsi'),
            'alamat' => $this->input->post('alamat'),
            'email' => $this->input->post('email'),
            'no_tlpn' => $this->input->post('nomor'),
            // 'atribut_pertanyaan_survey' => serialize($this->input->post('atribut_pertanyaan')),
            'id_sampling' => $this->input->post('id_sampling'),
            'jumlah_populasi' => $jumlah_populasi,
            'jumlah_sampling' => $sampling,
            // 'visi' => $this->input->post('visi'),
            // 'misi' => $this->input->post('misi')
        ];
        $this->db->where('slug', "$slug");
        $this->db->update('manage_survey', $object);

        $pesan = 'Data berhasil disimpan';
        $msg = ['sukses' => $pesan];
        echo json_encode($msg);
    }

    public function confirm_question($id1, $id2)
    {
        $slug = $this->uri->segment('2');

        $this->db->select('*');
        $this->db->from('manage_survey');
        $this->db->where("slug = '$slug'");
        $current = $this->db->get()->row();
        $table_identity = $current->table_identity;

        $this->data['current'] = $current;

        $this->form_validation->set_rules('is_question', 'Is Question', 'trim|required');

        if ($this->form_validation->run() == false) {

            redirect(base_url() . $id1 . '/' . $id2 . '/' . 'link-survey', 'refresh');
        } else {


            $this->db->empty_table('trash_jawaban_pertanyaan_harapan_' . $table_identity);
            $this->db->empty_table('trash_jawaban_pertanyaan_terbuka_' . $table_identity);
            $this->db->empty_table('trash_jawaban_pertanyaan_unsur_' . $table_identity);
            $this->db->empty_table('trash_jawaban_pertanyaan_kualitatif_' . $table_identity);
            $this->db->empty_table('trash_survey_' . $table_identity);
            $this->db->empty_table('trash_responden_' . $table_identity);

            $this->db->empty_table('jawaban_pertanyaan_harapan_' . $table_identity);
            $this->db->empty_table('jawaban_pertanyaan_terbuka_' . $table_identity);
            $this->db->empty_table('jawaban_pertanyaan_unsur_' . $table_identity);
            $this->db->empty_table('jawaban_pertanyaan_kualitatif_' . $table_identity);
            $this->db->empty_table('survey_' . $table_identity);
            $this->db->empty_table('responden_' . $table_identity);


            if ($current->is_survey_close == 1) {
                $this->db->query("DROP TABLE origin_responden_$table_identity");
                $this->db->query("DROP TABLE origin_survey_$table_identity");
                $this->db->query("DROP TABLE origin_jawaban_pertanyaan_unsur_$table_identity");
                $this->db->query("DROP TABLE origin_jawaban_pertanyaan_kualitatif_$table_identity");
                $this->db->query("DROP TABLE origin_jawaban_pertanyaan_terbuka_$table_identity");
                $this->db->query("DROP TABLE origin_jawaban_pertanyaan_harapan_$table_identity");


                $this->db->query("DROP TABLE koreksi_responden_$table_identity");
                $this->db->query("DROP TABLE koreksi_survey_$table_identity");
                $this->db->query("DROP TABLE koreksi_jawaban_pertanyaan_unsur_$table_identity");
                $this->db->query("DROP TABLE koreksi_jawaban_pertanyaan_kualitatif_$table_identity");
                $this->db->query("DROP TABLE koreksi_jawaban_pertanyaan_terbuka_$table_identity");
                $this->db->query("DROP TABLE koreksi_jawaban_pertanyaan_harapan_$table_identity");
            }

            $target = [
                'is_question' => $this->input->post('is_question'),
                'is_origin_backup' => null,
                'is_koreksi' => null,
                'is_survey_close' => null
            ];
            $this->db->where('id', $current->id);
            $this->db->update('manage_survey', $target);
        }

        $pesan = 'Request berhasil dilakukan';
        $msg = ['sukses' => $pesan];
        echo json_encode($msg);
    }

    public function profile($id = null)
    {
        $this->data = [];
        $this->data['title'] = "Profile";
        $this->data['form_action'] = base_url() . $this->session->userdata('username');

        $this->data['data_user'] = $this->ion_auth->user()->row();
        $current = $this->data['data_user'];

        return view('manage_survey/form_profile', $this->data);
    }

    public function _cek_user()
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->join('users_groups', 'users.id = users_groups.user_id');
        $this->db->where('users.username', $this->session->userdata('username'));
        return $this->db->get();
    }

    function _data_survey()
    {
        $data_user = $this->_cek_user()->row();

        if ($data_user->group_id == 2) {
            $this->db->select('users.username, manage_survey.survey_name, manage_survey.slug, manage_survey.description, manage_survey.is_privacy, klasifikasi_survei.nama_klasifikasi_survei, table_identity');
            $this->db->from('users');
            $this->db->join('manage_survey', 'manage_survey.id_user = users.id');
            $this->db->join('klasifikasi_survei', 'klasifikasi_survei.id = users.id_klasifikasi_survei');
            $this->db->where('users.username', $this->session->userdata('username'));
        } else {
            $this->db->select('users.username, manage_survey.survey_name, manage_survey.slug, manage_survey.description, manage_survey.is_privacy, klasifikasi_survei.nama_klasifikasi_survei, table_identity');
            $this->db->from('manage_survey');
            $this->db->join("supervisor_drs$data_user->is_parent", "manage_survey.id_berlangganan = supervisor_drs$data_user->is_parent.id_berlangganan");
            $this->db->join("users", "supervisor_drs$data_user->is_parent.id_user = users.id");
            $this->db->join('klasifikasi_survei', 'users.id_klasifikasi_survei = klasifikasi_survei.id');
            $this->db->where('users.username', $this->session->userdata('username'));
        }
        return $this->db->get();
    }

    public function get_data_survey()
    {
        $this->data['data_survey'] = $this->_data_survey();

        return view("manage_survey/overview/list_survey", $this->data);
    }

    public function get_data_activity()
    {
        $this->data['data_survey'] = $this->_data_survey();

        $this->data['paket'] = $this->db->get('paket');

        $data = array();
        foreach ($this->data['data_survey']->result() as $key => $value) {
            $data[$key] = 'UNION ALL SELECT * from log_survey_' . $value->table_identity;
        }
        $tabel_union = implode(" ", $data);

        $this->data['log_survey'] = $this->db->query('SELECT * from log_survey ' . $tabel_union . ' ORDER BY log_time DESC
		LIMIT 8')->result();


        $this->data['total_log_survey'] = $this->db->query('SELECT * from log_survey ' . $tabel_union)->num_rows();

        return view("manage_survey/overview/list_activity", $this->data);
    }

    public function get_data_paket()
    {
        $this->data['data_survey'] = $this->_data_survey();

        $this->db->select('*');
        $this->db->from('paket');
        $this->db->where('is_active', '1');
        $this->db->where('is_trial', '0');
        $this->data['paket'] = $this->db->get();

        return view("manage_survey/overview/list_campaign", $this->data);
    }

    public function link_survey($id1, $id2)
    {
        $this->data = [];
        $this->data['title'] = "Link Survei";

        $profiles =  $this->_get_data_profile($id1, $id2);
        $this->data['profiles'] = $profiles;

        $this->data['atribut_pertanyaan'] =  unserialize($profiles->atribut_pertanyaan_survey);
        // var_dump($this->data['atribut_pertanyaan']);

        $this->data['profil_responden'] = $this->db->get('profil_responden_' . $profiles->table_identity,)->num_rows();
        $this->data['unsur_pelayanan'] = $this->db->get('unsur_pelayanan_' . $profiles->table_identity,)->num_rows();
        $this->data['pertanyaan_unsur'] = $this->db->get('pertanyaan_unsur_pelayanan_' . $profiles->table_identity,)->num_rows();
        $this->data['pertanyaan_terbuka'] = $this->db->get('pertanyaan_terbuka_' . $profiles->table_identity,)->num_rows();
        $this->data['pertanyaan_kualitatif'] = $this->db->get('pertanyaan_kualitatif_' . $profiles->table_identity,)->num_rows();

        if ($profiles->is_layanan_survei != 0) {
            $this->data['layanan_survei'] = $this->db->get_where("layanan_survei_$profiles->table_identity", array('is_active' => 1))->num_rows();
        } else {
            $this->data['layanan_survei'] = 1;
        }


        if ($this->data['atribut_pertanyaan'] == array(0)) {
            $this->data['hasil_atribute'] = $this->data['pertanyaan_unsur'] > 0;
        } else if ($this->data['atribut_pertanyaan'] == array(0, 1)) {
            $this->data['hasil_atribute'] = $this->data['pertanyaan_unsur'] > 0;
        } else if ($this->data['atribut_pertanyaan'] == array(0, 2)) {
            $this->data['hasil_atribute'] = $this->data['pertanyaan_terbuka'] > 0;
        } else if ($this->data['atribut_pertanyaan'] == array(0, 3)) {
            $this->data['hasil_atribute'] = $this->data['pertanyaan_kualitatif'] > 0;
        } else if ($this->data['atribut_pertanyaan'] == array(0, 1, 2)) {
            $this->data['hasil_atribute'] = ($this->data['pertanyaan_unsur'] > 0) && ($this->data['pertanyaan_terbuka'] > 0);
        } else if ($this->data['atribut_pertanyaan'] == array(0, 1, 3)) {
            $this->data['hasil_atribute'] = ($this->data['pertanyaan_unsur'] > 0) && ($this->data['pertanyaan_kualitatif'] > 0);
        } else if ($this->data['atribut_pertanyaan'] == array(0, 2, 3)) {
            $this->data['hasil_atribute'] = ($this->data['pertanyaan_terbuka'] > 0) && ($this->data['pertanyaan_kualitatif'] > 0);
        } else {
            $this->data['hasil_atribute'] = ($this->data['pertanyaan_unsur'] > 0) && ($this->data['pertanyaan_terbuka'] > 0)  && ($this->data['pertanyaan_kualitatif'] > 0);
        }

        $this->data['form_action'] = base_url() . $id1 . '/' . $id2 . '/confirm-question';

        return view('manage_survey/form_link_survey', $this->data);
    }

    public function _get_data_profile($id1, $id2)
    {
        $data_user = $this->_cek_user()->row();
        $user_identity = 'drs' . $data_user->is_parent;
        

        $this->db->select("*, manage_survey.slug AS slug_manage_survey, manage_survey.id AS id_manage_survey, IF(manage_survey.skala_likert = '', 4, manage_survey.skala_likert) AS skala_likert");

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

    public function update_info($id1, $id2, $id3)
    {
        $input     = $this->input->post(null, true);

        $object = [
            'kuesioner_name' => $input['kuesioner_name']
        ];

        $this->db->where('id', $id3);
        $this->db->update('manage_survey', $object);


        $pesan = 'Data berhasil disimpan';
        $msg = ['sukses' => $pesan];
        echo json_encode($msg);
    }

    public function check_logo($str)
    {
        $allowed_mime_type_arr = array('image/png',  'image/x-png');
        $mime = get_mime_by_extension($_FILES['logo']['name']);
        if (isset($_FILES['logo']['name']) && $_FILES['logo']['name'] != "") {
            if (in_array($mime, $allowed_mime_type_arr)) {
                return true;
            } else {
                $this->form_validation->set_message('check_logo', 'Silahkan pilih hanya file png.');
                return false;
            }
        } else {
            $this->form_validation->set_message('check_logo', 'Silakan pilih file yang akan diunggah.');
            return false;
        }
    }

    public function update_logo($id1, $id2, $id3)
    {
        $this->form_validation->set_rules('logo', '', 'callback_check_logo');

        if ($this->form_validation->run() == false) {

            $this->session->set_flashdata('message_warning', validation_errors());
            redirect($this->session->userdata('urlback'), 'refresh');
        } else {

            if ($_FILES['logo']['name'] != null) {

                // REMOVE FLAGS
                $search = $this->db->get_where('manage_survey', ['id' => $id3])->row();

                if (($search->logo_survey != "") or (!empty($search->logo_survey))) {
                    unlink('./assets/img/klien/logo_survey/' . $search->logo_survey);
                }

                $images_logo = $_FILES['logo']['name'];

                if ($images_logo != "") {

                    $config['upload_path']         = './assets/img/klien/logo_survey/';
                    $config['allowed_types']       = 'jpg|png|jpeg';
                    $config['detect_mime']        = true;
                    $config['max_size']            = 20000;
                    $nama_file                     = strtolower("Logo");
                    $config['file_name']         = $nama_file . "_" . time();

                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);

                    if (!$this->upload->do_upload('logo')) {
                        print_r($this->upload->display_errors());
                    } else {
                        $logo = $this->upload->data();
                    }

                    $data = array(
                        'logo_survey' => $logo['file_name']
                    );

                    $this->db->where('id', $id3);
                    $query = $this->db->update('manage_survey', $data);
                }
            }
        }

        $this->session->set_flashdata('message_success', 'Berhasil menyimpan pengaturan');
        redirect($this->session->userdata('urlback'), 'refresh');
    }

    public function ubah_privasi($id1, $id2)
    {
        $this->data = [];
        $this->data['title'] = "Ubah Privasi";
        $this->data['profiles'] = $this->_get_data_profile($id1, $id2);

        return view("manage_survey/form_change_privacy", $this->data);
    }

    public function update_privasi($id1, $id2)
    {
        $mode = $_POST['mode'];
        $id = $_POST['nilai_id'];

        if ($mode == 'true') {
            $object = [
                'is_privacy' => 1
            ];
            $this->db->where('slug', $id2);
            $this->db->update('manage_survey', $object);


            $message = 'Privacy Public diaktifkan';
            $success = 'Enabled';
            echo json_encode(array('message' => $message, '$success' => $success));
        } else if ($mode == 'false') {
            $object = [
                'is_privacy' => null
            ];
            $this->db->where('slug', $id2);
            $this->db->update('manage_survey', $object);


            $message = 'Privacy Private diaktifkan';
            $success = 'Disabled';
            echo json_encode(array('message' => $message, 'success' => $success));
        }
    }

    public function update_link($id1, $id2)
    {
        $this->data = [];
        $this->data['title'] = "Ubah Link Survei";
        $this->data['profiles'] = $this->_get_data_profile($id1, $id2);
        // var_dump($this->data['profiles']);

        return view("manage_survey/form_ubah_link_survei", $this->data);

		// $profil = $this->_get_data_profile($id1, $id2);

		// echo "<pre>";
		// print_r($profil);
		// echo "</pre>";
    }

    public function do_update_link($id1, $id2)
    {

        $this->load->helper('slug');
        $slug = slug($this->input->post('slug'));
       

        $this->db->select('id, slug');
        $this->db->from('manage_survey');
        $this->db->where('slug', $id2);
        $cek = $this->db->get()->row();

        if ($slug == $cek->slug) {

            $pesan = 'Link survei gagal diubah';
            $msg = ['fail' => $pesan];
            echo json_encode($msg);
        } else {

            $object = [
                'slug' => $slug,
            ];

            $this->db->where('id', $cek->id);
            $this->db->update('manage_survey', $object);


            $pesan = 'Link survei berhasil diubah';
            $msg = ['sukses' => $pesan];
            echo json_encode($msg);
        }
    }

    public function create_survey_client()
    {

        $username = $this->uri->segment(1);


        // ===================================== SEMENTARA ==========================================
        $cek_groups =  $this->db->query("SELECT *
        FROM users
        JOIN users_groups ON users.id = users_groups.user_id
        WHERE username = '$username'")->row();

        if ($cek_groups->group_id == 5) {
            $cek_users = $this->db->get_where("users", array('id' => $cek_groups->is_parent))->row();
            $new_username = $cek_users->username;
        } else {
            $new_username = $username;
        }
        $this->data['username'] = $new_username;
        // ===================================== SEMENTARA ==========================================


        // $username = $this->input->post('id');


        $this->db->select('*, berlangganan.uuid AS uuid_berlangganan');
        $this->db->from('users');
        $this->db->join('berlangganan', 'berlangganan.id_user = users.id');
        $this->db->join('paket', 'paket.id = berlangganan.id_paket');
        $this->db->join('status_berlangganan', 'status_berlangganan.id = berlangganan.id_status_berlangganan');
        $this->db->where('users.username', $new_username);
        $this->db->order_by('berlangganan.id', 'asc');
        $last_packet = $this->db->get();

        $this->data['client_packet'] = $last_packet;

        // var_dump($last_packet->last_row());

        return view('manage_survey/modal_create_survey', $this->data);
    }

    public function check_packet($username, $uuid_berlangganan)
    {
        if (Client_Controller::check_subscription() == false) {
            // return view("subscription/form_expired", $this->data);
            // exit();
            $data = [
                'id' => $username,
                'status' => false
            ];
            echo json_encode($data);
        } else {

            // cek uuid berlangganan
            $this->db->select('berlangganan.uuid AS uuid_berlangganan, id_paket');
            $this->db->from('berlangganan');
            $this->db->join('users', 'users.id = berlangganan.id_user');
            $this->db->where('users.username', $username);
            $this->db->where('berlangganan.uuid', $uuid_berlangganan);
            $berlangganan = $this->db->get()->row();

            // cek paket jumlah kuota kuesioner
            $id_paket = $berlangganan->id_paket;
            $this->db->select('jumlah_kuesioner');
            $this->db->from('paket');
            $this->db->where('id', $id_paket);
            $jumlah_kuesioner_paket = $this->db->get()->row()->jumlah_kuesioner;

            // cek jumlah kuota kuesioner yang dipakai
            $this->db->select('manage_survey.id');
            $this->db->from('berlangganan');
            $this->db->join('manage_survey', 'manage_survey.id_berlangganan = berlangganan.id');
            $this->db->where('berlangganan.uuid', $uuid_berlangganan);
            $this->db->where('berlangganan.id_paket', $id_paket);
            $jumlah_kuesioner_dibuat = $this->db->get()->num_rows();

            $pemakaian  = $jumlah_kuesioner_paket - $jumlah_kuesioner_dibuat;

            // $data = [
            // 	'id' => $pemakaian,
            // 	'status' => false
            // ];
            // echo json_encode($data);

            if ($pemakaian == 0) {

                $data = [
                    'id' => $username,
                    'status' => false
                ];
                echo json_encode($data);
            } else {
                $data = [
                    'id' => $username,
                    'uuid_berlangganan' => $uuid_berlangganan,
                    'status' => true
                ];
                echo json_encode($data);
            }
        }
    }

    public function get_detail_survey()
    {
        $slug = $this->input->post('id');

        $this->data = [];
        $this->data['slug'] = $slug;

        $this->db->select('*');
        $this->db->from('manage_survey');
        $this->db->join('berlangganan', 'berlangganan.id = manage_survey.id_berlangganan');
        $this->db->join('paket', 'paket.id = berlangganan.id_paket');
        $this->db->join('sampling', 'sampling.id = manage_survey.id_sampling');
        $this->db->where('manage_survey.slug', $slug);
        $this->data['survey'] = $this->db->get()->row();
        return view('manage_survey/overview/modal_detail_survey', $this->data);
    }

    public function get_detail_packet()
    {
        $id = $this->input->post('id');

        $this->data = [];

        $this->data['paket'] = $this->db->get_where('paket', ['id' => $id])->row();
        return view('manage_survey/overview/modal_detail_paket', $this->data);
    }

    public function data_berlangganan()
    {
        $this->data = [];

        // $data_user = $this->ion_auth->user()->row();
        $data_user = $this->db->get_where('users', array('username' => $this->uri->segment(1)))->row();
        $this->data['data_user'] = $data_user;

        $this->load->library('table');

        $template = array(
            'table_open'            => '<table class="table table-bordered table-hover">',
            'table_close'           => '</table>'
        );

        $this->table->set_template($template);

        $this->table->set_heading('NO', 'Nama Pelanggan', 'Nama Paket', 'Panjang Hari', 'Harga Paket (Rp.)', 'Tanggal Aktif', 'Tanggal Kedaluarsa', 'Status', '');

        $this->db->select('*, berlangganan.id AS id_berlangganan');
        $this->db->from('berlangganan');
        $this->db->join('users', 'users.id = berlangganan.id_user');
        $this->db->join('paket', 'paket.id = berlangganan.id_paket');
        $this->db->join('status_berlangganan', 'status_berlangganan.id = berlangganan.id_status_berlangganan');
        $this->db->join('metode_pembayaran', 'metode_pembayaran.id = berlangganan.id_metode_pembayaran');
        $this->db->where('berlangganan.id_user', $data_user->id);
        $this->db->order_by('berlangganan.id', 'asc');
        $get_data = $this->db->get();

        // $jumlah = $get_data->num_rows();

        $no = 1;
        $now = Carbon::now();
        foreach ($get_data->result() as $value) {

            if ($now->between(Carbon::parse($value->tanggal_mulai), Carbon::parse($value->tanggal_selesai))) {
                $status = '<span class="badge badge-success">Aktif</span';
            } else {
                $status = '<span class="badge badge-secondary">Tidak Aktif</span';
            }

            $this->table->add_row(
                $no++,
                $value->first_name . ' ' . $value->last_name,
                $value->nama_paket,
                $value->panjang_hari,
                number_format($value->harga_paket, 2, ',', '.'),
                date('d-m-Y', strtotime($value->tanggal_mulai)),
                date('d-m-Y', strtotime($value->tanggal_selesai)),
                $status,
                '<a href="javascript:void(0)" class="btn btn-light-primary btn-sm shadow" onclick="showDetail(' . $value->id_berlangganan . ')"><i class="fa fa-tags"></i> Invoice</a>'
            );
        }
        $this->data['table'] = $this->table->generate();

        return view('manage_survey/info_berlangganan/list_data_berlangganan', $this->data);
    }

    public function data_terakhir_berlangganan()
    {
        $this->data = [];

        $this->db->select('*, berlangganan.id AS id_berlangganan');
        $this->db->from('berlangganan');
        $this->db->join('users', 'users.id = berlangganan.id_user');
        $this->db->join('paket', 'paket.id = berlangganan.id_paket');
        $this->db->join('status_berlangganan', 'status_berlangganan.id = berlangganan.id_status_berlangganan');
        $this->db->join('metode_pembayaran', 'metode_pembayaran.id = berlangganan.id_metode_pembayaran');
        $this->db->where('users.username', $this->uri->segment(1));
        $this->db->order_by('berlangganan.id', 'asc');
        $get_data = $this->db->get();

        $last_payment = $get_data->last_row();
        $this->data['last_payment'] = $last_payment;

        $tanggal_mulai = $last_payment->tanggal_mulai;
        $tanggal_selesai = $last_payment->tanggal_selesai;

        $this->data['tanggal_sekarang'] = $tanggal_mulai;
        $this->data['tanggal_expired'] = $tanggal_selesai;

        $tanggal_mulai = $last_payment->tanggal_mulai;
        $tanggal_selesai = $last_payment->tanggal_selesai;

        $now = Carbon::now();
        $start_date = Carbon::parse($tanggal_mulai);
        $end_date = Carbon::parse($tanggal_selesai);
        $due_date = $now->diffInDays($end_date); // Tanggal jatuh tempo

        if ($now->between($start_date, $end_date)) {
            $this->data['status_jatuh_tempo'] = 'Berakhir dalam ' . $due_date . ' hari lagi';
            $this->data['status_paket'] = '<span class="btn btn-sm btn-text btn-light-success text-uppercase font-weight-bold">Aktif</span>';
        } else {
            $this->data['status_jatuh_tempo'] = 'Packet is Expired';
            $this->data['status_paket'] = '<span class="btn btn-sm btn-text btn-light-danger text-uppercase font-weight-bold">Expired</span>';
        }

        return view('manage_survey/info_berlangganan/list_data_terakhir_berlangganan', $this->data);
    }

    public function get_invoice()
    {
        $id_berlangganan = $this->input->post('id');

        $this->data = [];

        $this->db->select('*, berlangganan.id AS id_berlangganan');
        $this->db->from('berlangganan');
        $this->db->join('users', 'users.id = berlangganan.id_user');
        $this->db->join('paket', 'paket.id = berlangganan.id_paket');
        $this->db->join('status_berlangganan', 'status_berlangganan.id = berlangganan.id_status_berlangganan');
        $this->db->join('metode_pembayaran', 'metode_pembayaran.id = berlangganan.id_metode_pembayaran');
        $this->db->where('berlangganan.id', $id_berlangganan);
        $this->data['get_data'] = $this->db->get()->row();
        // var_dump($this->data['get_data']);


        return view('manage_survey/info_berlangganan/get_invoice', $this->data);
    }

    public function update_publikasi_link_survei($id1, $id2)
    {
        $mode = $_POST['mode'];
        $id = $_POST['nilai_id'];

        if ($mode == 'true') {
            $object = [
                'is_publikasi_link_survei' => 1
            ];
            $this->db->where('slug', $id2);
            $this->db->update('manage_survey', $object);


            $message = 'Survei Berhasil dipublikasi';
            $success = 'Enabled';
            echo json_encode(array('message' => $message, '$success' => $success));
        } else if ($mode == 'false') {
            $object = [
                'is_publikasi_link_survei' => null
            ];
            $this->db->where('slug', $id2);
            $this->db->update('manage_survey', $object);


            $message = 'Survei Berhasil diprivate';
            $success = 'Disabled';
            echo json_encode(array('message' => $message, 'success' => $success));
        }
    }

    public function draf_inject_survei($id1, $id2)
    {
        $this->data = [];
        $this->data['title'] = 'Draf Inject Survei';
        $this->data['profiles'] = $this->_get_data_profile($id1, $id2);

        $slug = $this->uri->segment(2);
        $manage_survey = $this->db->get_where('manage_survey', ['slug' => "$slug"])->row();
        $table_identity = $manage_survey->table_identity;

        $input  = $this->input->post(null, true);

        // $data_nama = array('ABDURRAHMAN HANIF', 'LEFI ANDRI LESTARI', 'YULIANA SUKMA RATNA SARI DEWI');
        $data_email = array('programmer@kokek.com', 'yusrina.amalia@kokek.com', 'fitri.susiyanti@kokek.com', 'zharfan.hamzah@kokek.com', 'yuliana.sari@kokek.com', 'lefi.andri@kokek.com');

        // $nama = $input['nama'];
        // $email = $input['email'];
        $email = $this->uri->segment(4);

        if (in_array($email, $data_email)) {

            $this->data['profil_responden'] = $this->db->get_where("profil_responden_$table_identity", array('jenis_isian' => 1));

            $this->data['kategori_profil_responden'] = $this->db->get("kategori_profil_responden_$table_identity");

            $this->data['surveyor']  = $this->db->query("SELECT *, (SELECT first_name FROM users WHERE users.id = id_user) AS nama_depan, (SELECT last_name FROM users WHERE users.id = id_user) AS nama_belakang, surveyor.id AS id_surveyor
				FROM surveyor
				WHERE id_manage_survey = $manage_survey->id");


            $this->load->view('manage_survey/form_draf_inject', $this->data);

            header("Content-type: application/vnd-ms-excel");
            header("Content-Disposition: attachment; filename=draf-inject-" . $manage_survey->slug . ".xls");
        } else {
            $this->session->set_flashdata('message_danger', 'Data anda tidak valid!');
            // $this->session->set_flashdata('message_success', 'Berhasil menambah data');
            redirect(base_url() . $this->session->userdata('username') . '/' . $this->uri->segment(2) . '/do', 'refresh');
            // $data = '';
            // var_dump($data);
        }
    }


    public function ajax_close_survey($id)
	{
		$object = [
			'survey_end' 	=> date("Y-m-d"), 
		];

		$this->db->where('id', $id);
		$this->db->update('manage_survey', $object);

		echo json_encode(array("status" => TRUE));
	}
}

/* End of file ManageSurveyController.php */
