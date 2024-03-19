<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SurveiController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $cek = $this->db->get_where('manage_survey', ['slug' => $this->uri->segment(2)]);
        if ($cek->num_rows() == 0) {
            show_404();
        }

        $this->load->library('form_validation');
        $this->load->model('Survei_model');
    }

    public function index()
    { }


    public function _get_tamplate()
    {
        // $user = $this->ion_auth->user()->row()->id;
        $data_uri = $this->uri->segment('2');

        $this->db->select("*, DATE_FORMAT(survey_end, '%d %M %Y') AS survey_selesai, IF(CURDATE() > survey_end,1,NULL) AS survey_berakhir, IF(CURDATE() < survey_start ,1,NULL) AS survey_belum_mulai");
        $this->db->from('manage_survey');
        $this->db->join('users', 'manage_survey.id_user = users.id');
        $this->db->where("slug = '$data_uri'");
        $manage_survey = $this->db->get()->row();
        $this->data['judul'] = $manage_survey;
    }


    public function download_link()
    {
        $this->load->helper('download');

        $url = base_url() . 'survey/' . $this->uri->segment(2) . '/' . $this->uri->segment(3) . '/' . $this->uri->segment(4);
        $data = "$url";
        $name = 'Link Survey Kepuasan Masyarakat - RESUME-' . time() . '.txt';
        force_download($name, $data);
    }


    public function form_opening()
    {
        $this->data = [];
        $this->data['title'] = 'SURVEI KEPUASAN MASYARAKAT';

        $data_uri = $this->uri->segment('2');

        // $this->data['get_template'] = $this->_get_tamplate();

        $this->db->select("*, DATE_FORMAT(survey_end, '%d %M %Y') AS survey_selesai, IF(CURDATE() > survey_end,1,NULL) AS survey_berakhir, IF(CURDATE() < survey_start ,1,NULL) AS survey_belum_mulai");
        $this->db->from('manage_survey');
        $this->db->join('users', 'manage_survey.id_user = users.id');
        $this->db->where("slug = '$data_uri'");
        $manage_survey = $this->db->get()->row();
        $this->data['judul'] = $manage_survey;
        $this->data['manage_survey'] = $manage_survey;
        $this->data['status_saran'] = $manage_survey->is_saran;
        $tanggalselesai = date('Y-m-d', strtotime('+1 days', strtotime($manage_survey->survey_end)));


        // STATUS SURVEY DI TUNDA< BLUM DIMULAI< ATAU SURVEY SUDAH SELESAI
        if ($manage_survey->is_privacy == 2) {
            return view('survei/form_setting_survey/survey_hold', $this->data);
        } elseif (date("Y-m-d") < $manage_survey->survey_start) {
            return view('survei/form_setting_survey/unopened', $this->data);
        //} elseif (date("Y-m-d") >= $manage_survey->survey_end) {
        } elseif ((time() >= strtotime($tanggalselesai))) {
            return view('survei/form_setting_survey/survey_end', $this->data);
        } elseif ($manage_survey->is_question == 1) {
            return view('survei/form_setting_survey/survey_not_question', $this->data);
        } else {


            if($manage_survey->is_opening_survey == 'false'){
                $uuid = $this->uri->segment(3) != NULL ? $this->uri->segment(3) : '';
                redirect(base_url() . 'survei/' . $manage_survey->slug . '/data-responden/' . $uuid);
            } else {
                return view('survei/form_opening', $this->data);
            }
        }
    }

    public function getdatalayanan($id1, $id2)
    {
        $this->db->select('*');
        $this->db->from('manage_survey');
        $this->db->where('manage_survey.slug', $id1);
        $manage_survey = $this->db->get()->row();

        $searchTerm = $this->input->post('searchTerm');
        $this->db->select('*');
        $this->db->from("layanan_survei_$manage_survey->table_identity");
        $this->db->where('id_kategori_layanan', $id2);
        $this->db->where("nama_layanan like '%" . $searchTerm . "%' ");
        $this->db->order_by('urutan', 'asc');
        $fetched_records = $this->db->get()->result_array();

        $data = array();
        foreach ($fetched_records as $kab) {
            $data[] = array("id" => $kab['id'], "text" => $kab['nama_layanan']);
        }


        echo json_encode($data);
    }




    public function data_responden($id1)
    {
        $this->data = [];
        $this->data['title'] = 'Data Responden';
        $this->data['profiles'] = $this->_get_data_profile($id1);

        $this->db->select("*, DATE_FORMAT(survey_end, '%d %M %Y') AS survey_selesai, IF(CURDATE() > survey_end,1,NULL) AS survey_berakhir, IF(CURDATE() < survey_start ,1,NULL) AS survey_belum_mulai");
        $this->db->from('manage_survey');
        $this->db->where('manage_survey.slug', $this->uri->segment(2));
        $manage_survey = $this->db->get()->row();
        $table_identity = $manage_survey->table_identity;
        $this->data['status_saran'] = $manage_survey->is_saran;
        $this->data['manage_survey'] = $manage_survey;
        $tanggalselesai = date('Y-m-d', strtotime('+1 days', strtotime($manage_survey->survey_end)));



        if ($this->uri->segment(4) == null) {
            $this->data['form_action'] = base_url() . 'survei/' . $this->uri->segment(2) . '/add-data-responden/';
            $this->data['surveyor'] = 0;
            $this->data['surveyor_induk'] = 0;
        } else {
            $this->data['form_action'] = base_url() . 'survei/' . $this->uri->segment(2) . '/add-data-responden/' . $this->uri->segment(4);

            if($this->db->get_where('surveyor', array('uuid' => $this->uri->segment(4)))->num_rows > 0){
                $this->data['surveyor'] = $this->db->get_where('surveyor', array('uuid' => $this->uri->segment(4)))->row()->id;
                $this->data['surveyor_induk'] = 0;
            } else {
                $this->data['surveyor_induk'] = $this->db->get_where('surveyor_induk', array('uuid' => $this->uri->segment(4)))->row()->id;
                $this->data['surveyor'] = 0;
            }
        }

        //LOAD PROFIL RESPONDEN
        $this->data['profil_responden'] = $this->db->query("SELECT * FROM profil_responden_$table_identity ORDER BY IF(urutan != '',urutan,id) ASC");

        //LOAD KATEGORI PROFIL RESPONDEN JIKA PILIHAN GANDA
        $this->data['kategori_profil_responden'] = $this->db->get('kategori_profil_responden_' . $table_identity);


        $this->data['id_layanan_survei'] = [
            'name'         => 'id_layanan_survei',
            'id'         => 'id_layanan_survei',
            'options'     => $this->Survei_model->dropdown_layanan_survei($table_identity),
            'selected'     => $this->form_validation->set_value('id_layanan_survei'),
            'class'     => "form-control",
            'required' => 'required',
        ];


        //STATUS SURVEY DI TUNDA< BLUM DIMULAI< ATAU SURVEY SUDAH SELESAI
        if ($manage_survey->is_privacy == 2) {
            return view('survei/form_setting_survey/survey_hold', $this->data);
        } elseif (date("Y-m-d") < $manage_survey->survey_start) {
            return view('survei/form_setting_survey/unopened', $this->data);
        //} elseif (date("Y-m-d") >= $manage_survey->survey_end) {
        } elseif ((time() >= strtotime($tanggalselesai))) {
            return view('survei/form_setting_survey/survey_end', $this->data);
        } elseif ($manage_survey->is_question == 1) {
            return view('survei/form_setting_survey/survey_not_question', $this->data);
        } else {
            return view('survei/form_data_responden', $this->data);
        }
    }


    


    public function add_data_responden($id1)
    {
        $this->db->select("*, DATE_FORMAT(survey_end, '%d %M %Y') AS survey_selesai, IF(CURDATE() > survey_end,1,NULL) AS survey_berakhir, IF(CURDATE() < survey_start ,1,NULL) AS survey_belum_mulai");
        $this->db->from('manage_survey');
        $this->db->where('manage_survey.slug', $this->uri->segment(2));
        $manage_survey = $this->db->get()->row();
        $table_identity = $manage_survey->table_identity;

        //LOAD PROFIL RESPONDEN
        $this->data['profil_responden'] = $this->db->query("SELECT * FROM profil_responden_$table_identity");

        $atribut_pertanyaan = unserialize($manage_survey->atribut_pertanyaan_survey);

        $this->load->library('uuid');
        $input     = $this->input->post(null, true);



        if ($manage_survey->is_layanan_survei != 0) {

            if ($manage_survey->is_kategori_layanan_survei == 1 && $this->input->post('kategori_layanan') == 0) {
                $id_layanan_survei = 0;
                $layanan_survei_lainnya = $this->input->post('layanan_survei_lainnya');
            } else {
                $id_layanan_survei = $this->input->post('id_layanan_survei');
                $layanan_survei_lainnya = '';
            }
        } else {
            $id_layanan_survei = '';
            $layanan_survei_lainnya = '';
        }

        //INSERT DATA RSEPONDEN
        $object = [
            'uuid' => $this->uuid->v4(),
            'id_layanan_survei' => $id_layanan_survei,
            'layanan_survei_lainnya' => $layanan_survei_lainnya
            // 'created_at' => date("Y/m/d H:i:s")
        ];


        foreach ($this->data['profil_responden']->result() as $row) {
            $object[$row->nama_alias] = $this->input->post($row->nama_alias);

            if ($row->is_lainnya == 1) {
                $nama_lainnya = $row->nama_alias . '_lainnya';
                $object[$nama_lainnya] = $this->input->post($nama_lainnya);
            }
        }
        $this->db->insert("responden_$table_identity", $object);
        $id_responden = $this->db->insert_id();



        //INSERT SURVEY
        $value = [
            'uuid' => $this->uuid->v4(),
            'id_responden'     => $id_responden,
            'id_surveyor'     => $_POST['id_surveyor'],
            'id_surveyor_induk'     => $_POST['id_surveyor_induk'],
            'is_submit'     => '2',
            'waktu_isi' => date("Y/m/d H:i:s"),
            'is_end' => '* Berakhir di Data Responden',
            // 'color' => $color
        ];

        $this->db->insert("survey_$table_identity", $value);
        // $this->db->query("UPDATE survey_$table_identity JOIN responden_$table_identity ON survey_$table_identity.id_responden = responden_$table_identity.id SET survey_$table_identity.is_duplicated = $is_duplicated WHERE responden_$table_identity.nama_lengkap = '" . $this->input->post('nama_lengkap') . "'");





        //INSERT ID PERTANYAAN UNSUR
        $get_pertanyaan_unsur = $this->db->query("SELECT id FROM pertanyaan_unsur_pelayanan_$table_identity");

        $result = array();
        foreach ($get_pertanyaan_unsur->result() as $key => $value) {
            $result[] = array(
                'id_responden'                 => $id_responden,
                'id_pertanyaan_unsur'         => $value->id
            );
        }
        $this->db->insert_batch('jawaban_pertanyaan_unsur_' . $table_identity, $result);


        //PENGECEKAN PERTANYAAN HARAPAN
        if (in_array(1, $atribut_pertanyaan)) {
            $this->db->insert_batch('jawaban_pertanyaan_harapan_' . $table_identity, $result);
        }

        //PENGECEKAN PERTANYAAN TERBUKA
        if (in_array(2, $atribut_pertanyaan)) {

            //INSERT ID PERTANYAAN TERBUKA
            $get_pertanyaan_terbuka = $this->db->query("SELECT id FROM pertanyaan_terbuka_$table_identity ORDER BY id asc");

            $ambil = array();
            foreach ($get_pertanyaan_terbuka->result() as $key => $row) {
                $ambil[] = array(
                    'id_responden'                 => $id_responden,
                    'id_pertanyaan_terbuka'     => $row->id
                );
            }
            $this->db->insert_batch('jawaban_pertanyaan_terbuka_' . $table_identity, $ambil);
        }

        //PENGECEKAN PERTANYAAN KUALITATIF
        if (in_array(3, $atribut_pertanyaan)) {

            //INSERT ID PERTANYAAN KUALITATIF
            $get_pertanyaan_kualitatif = $this->db->query("SELECT id FROM pertanyaan_kualitatif_$table_identity");

            $get_value = array();
            foreach ($get_pertanyaan_kualitatif->result() as $key => $row) {
                $get_value[] = array(
                    'id_responden'                 => $id_responden,
                    'id_pertanyaan_kualitatif'     => $row->id,
                    'is_active' => '1'
                );
            }
            $this->db->insert_batch('jawaban_pertanyaan_kualitatif_' . $table_identity, $get_value);
        }
        $get_uuid_responden = $this->db->query("SELECT uuid FROM responden_$table_identity WHERE id = $id_responden")->row()->uuid;

        $pesan = 'Data berhasil disimpan';
        $msg = ['sukses' => $pesan, 'uuid' => $get_uuid_responden];
        echo json_encode($msg);
    }




    public function data_pertanyaan($id1)
    {
        $this->data = [];
        $this->data['title'] = 'Pertanyaan Survei';
        $this->data['profiles'] = $this->_get_data_profile($id1);

        $uuid_responden = $this->uri->segment(4);

        
        $manage_survey = $this->db->get_where('manage_survey', ['slug' => $this->uri->segment(2)])->row();
        $table_identity = $manage_survey->table_identity;
        $this->data['manage_survey'] = $manage_survey;
        $this->data['table_identity'] = $table_identity;
        

        //CEK APAKAH SURVEY SUDAH DI SUBMIT APA BELUM
        $this->db->select("survey_$table_identity.is_active, is_submit, id_responden");
        $this->db->from('survey_' . $table_identity);
        $this->db->join("responden_$table_identity", "survey_$table_identity.id_responden = responden_$table_identity.id");
        $this->db->where("responden_$table_identity.uuid = '$uuid_responden'");
        $cek_data_responden = $this->db->get();

        if ($cek_data_responden->num_rows() == 0) {
            show_404();
        }

        $responden = $cek_data_responden->row();
        $this->data['id_res'] = $responden->id_responden;

        //PERTANYAAN UNSUR
        $this->data['pertanyaan_unsur'] = $this->db->query("SELECT pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan AS id_unsur_pelayanan, pertanyaan_unsur_pelayanan_$table_identity.id AS id_pertanyaan_unsur, isi_pertanyaan_unsur, unsur_pelayanan_$table_identity.nomor_unsur AS nomor, SUBSTRING(nomor_unsur, 2, 4) AS nomor_harapan, nama_unsur_pelayanan, (SELECT alasan_pilih_jawaban FROM jawaban_pertanyaan_unsur_$table_identity WHERE id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id && id_responden = $responden->id_responden) AS alasan_jawaban, (SELECT skor_jawaban FROM jawaban_pertanyaan_unsur_$table_identity WHERE id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id && id_responden = $responden->id_responden) AS skor_jawaban, is_required, is_alasan
		FROM pertanyaan_unsur_pelayanan_$table_identity
		JOIN unsur_pelayanan_$table_identity ON unsur_pelayanan_$table_identity.id = pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan
		ORDER BY pertanyaan_unsur_pelayanan_$table_identity.id ASC");


        // //JAWABAN PERTANYAAN UNSUR
        // $this->data['jawaban_pertanyaan_unsur'] = $this->db->query("SELECT * FROM kategori_unsur_pelayanan_$table_identity");


        //PERTANYAAN TERBUKA POSISI PALING ATAS
        $this->data['pertanyaan_terbuka_atas'] = $this->db->query("SELECT DISTINCT jawaban_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka, dengan_isian_lainnya, perincian_pertanyaan_terbuka_$table_identity.id AS id_perincian_pertanyaan_terbuka, pertanyaan_terbuka_$table_identity.id_unsur_pelayanan AS id_unsur_pelayanan,  isi_pertanyaan_terbuka, nomor_pertanyaan_terbuka, id_jenis_pilihan_jawaban, id_jenis_pilihan_jawaban, jawaban, jawaban_lainnya, IF(is_required = 1, 'required', '') AS stts_required, is_required
		FROM pertanyaan_terbuka_$table_identity
		JOIN perincian_pertanyaan_terbuka_$table_identity ON pertanyaan_terbuka_$table_identity.id = perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka
		LEFT JOIN isi_pertanyaan_ganda_$table_identity ON perincian_pertanyaan_terbuka_$table_identity.id = isi_pertanyaan_ganda_$table_identity.id_perincian_pertanyaan_terbuka
		LEFT JOIN jawaban_pertanyaan_terbuka_$table_identity ON perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka = jawaban_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka
		LEFT JOIN responden_$table_identity ON jawaban_pertanyaan_terbuka_$table_identity.id_responden = responden_$table_identity.id
		WHERE responden_$table_identity.uuid = '$uuid_responden' && pertanyaan_terbuka_$table_identity.is_letak_pertanyaan = 1
		ORDER BY jawaban_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka ASC");


        //PERTANYAAN TERBUKA MELEKAT PADA UNSUR
        $this->data['pertanyaan_terbuka'] = $this->db->query("SELECT DISTINCT jawaban_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka, dengan_isian_lainnya, perincian_pertanyaan_terbuka_$table_identity.id AS id_perincian_pertanyaan_terbuka, pertanyaan_terbuka_$table_identity.id_unsur_pelayanan AS id_unsur_pelayanan,  isi_pertanyaan_terbuka, nomor_pertanyaan_terbuka, id_jenis_pilihan_jawaban, id_jenis_pilihan_jawaban, jawaban, jawaban_lainnya, IF(is_required = 1, 'required', '') AS stts_required, is_required
		FROM pertanyaan_terbuka_$table_identity
		JOIN perincian_pertanyaan_terbuka_$table_identity ON pertanyaan_terbuka_$table_identity.id = perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka
		LEFT JOIN isi_pertanyaan_ganda_$table_identity ON perincian_pertanyaan_terbuka_$table_identity.id = isi_pertanyaan_ganda_$table_identity.id_perincian_pertanyaan_terbuka
		LEFT JOIN jawaban_pertanyaan_terbuka_$table_identity ON perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka = jawaban_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka
		LEFT JOIN responden_$table_identity ON jawaban_pertanyaan_terbuka_$table_identity.id_responden = responden_$table_identity.id
		WHERE responden_$table_identity.uuid = '$uuid_responden' && pertanyaan_terbuka_$table_identity.id_unsur_pelayanan != ''
		ORDER BY jawaban_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka ASC");


        //PERTANYAAN TERBUKA POSISI PALING BAWAH
        $this->data['pertanyaan_terbuka_bawah'] = $this->db->query("SELECT DISTINCT jawaban_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka, dengan_isian_lainnya, perincian_pertanyaan_terbuka_$table_identity.id AS id_perincian_pertanyaan_terbuka, pertanyaan_terbuka_$table_identity.id_unsur_pelayanan AS id_unsur_pelayanan,  isi_pertanyaan_terbuka, nomor_pertanyaan_terbuka, id_jenis_pilihan_jawaban, id_jenis_pilihan_jawaban, jawaban, jawaban_lainnya, IF(is_required = 1, 'required', '') AS stts_required, is_required
		FROM pertanyaan_terbuka_$table_identity
		JOIN perincian_pertanyaan_terbuka_$table_identity ON pertanyaan_terbuka_$table_identity.id = perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka
		LEFT JOIN isi_pertanyaan_ganda_$table_identity ON perincian_pertanyaan_terbuka_$table_identity.id = isi_pertanyaan_ganda_$table_identity.id_perincian_pertanyaan_terbuka
		LEFT JOIN jawaban_pertanyaan_terbuka_$table_identity ON perincian_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka = jawaban_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka
		LEFT JOIN responden_$table_identity ON jawaban_pertanyaan_terbuka_$table_identity.id_responden = responden_$table_identity.id
		WHERE responden_$table_identity.uuid = '$uuid_responden' && pertanyaan_terbuka_$table_identity.is_letak_pertanyaan = 2
		ORDER BY jawaban_pertanyaan_terbuka_$table_identity.id_pertanyaan_terbuka ASC");


        //JAWABAN PERTANYAAN TERBUKA
        $this->data['jawaban_pertanyaan_terbuka'] = $this->db->query("SELECT * FROM isi_pertanyaan_ganda_$table_identity");



        if ($this->uri->segment(5) == 'edit') {
            $is_edit = '/edit';
        } else {
            $is_edit = '';
        }


        $atribut_pertanyaan = unserialize($manage_survey->atribut_pertanyaan_survey);

        //PENGECEKAN ATRIBUT
        if (in_array(1, $atribut_pertanyaan)) {
            $this->data['url_next'] = base_url() . 'survei/' . $this->uri->segment(2) . '/pertanyaan-harapan/' . $uuid_responden . $is_edit;
        } else if (in_array(3, $atribut_pertanyaan)) {
            $this->data['url_next'] = base_url() . 'survei/' . $this->uri->segment(2) . '/pertanyaan-kualitatif/' . $uuid_responden . $is_edit;
        } else if ($manage_survey->is_saran == 1) {
            $this->data['url_next'] = base_url() . 'survei/' . $this->uri->segment(2) . '/saran/' . $uuid_responden . $is_edit;
        } else {
            $this->data['url_next'] = base_url() . 'survei/' . $this->uri->segment(2) . '/add-konfirmasi/' . $uuid_responden . $is_edit;
        }




        #PANGGIL FUNGSI js_pertanyaan
		$this->data['js_pertanyaan_terbuka_atas'] = $this->Survei_model->js_pertanyaan_terbuka_atas($table_identity);
		$this->data['js_pertanyaan_unsur'] = $this->Survei_model->js_pertanyaan_unsur($table_identity, $this->data['pertanyaan_unsur']);
		$this->data['js_pertanyaan_terbuka_bawah'] = $this->Survei_model->js_pertanyaan_terbuka_bawah($table_identity);
        



        if ($this->uri->segment(5) == 'edit') {
            return view('survei/form_pertanyaan', $this->data);
        } elseif ($responden->is_submit == 2) {
            return view('survei/form_pertanyaan', $this->data);
        } else {
            redirect(base_url() . 'survei/' . $this->uri->segment(2) . '/selesai/' . $uuid_responden, 'refresh');
        }
    }



    public function add_pertanyaan($id1)
    {
        $this->data = array();
        $this->data['profiles'] = $this->_get_data_profile($id1);

        $uuid_responden = $this->uri->segment(4);

        $this->db->select('');
        $this->db->from('manage_survey');
        $this->db->where('manage_survey.slug', $this->uri->segment(2));
        $manage_survey = $this->db->get()->row();
        $table_identity = $manage_survey->table_identity;

        $this->db->select('*');
        $this->db->from('responden_' . $table_identity);
        $this->db->where('uuid', $uuid_responden);
        $id_res = $this->db->get()->row()->id;

        $this->db->select('*');
        $this->db->from('pertanyaan_unsur_pelayanan_' . $table_identity);
        $get_data = $this->db->get();

        $result = array();
        $no = 1;
        foreach ($get_data->result() as $key => $val) {

            (isset($_POST['jawaban_pertanyaan_unsur'][$no])) ? $jawaban_pertanyaan_unsur = $_POST['jawaban_pertanyaan_unsur'][$no] : $jawaban_pertanyaan_unsur = 0;
            (isset($_POST['alasan_pertanyaan_unsur'][$no])) ? $alasan_pertanyaan_unsur = $_POST['alasan_pertanyaan_unsur'][$no] : $alasan_pertanyaan_unsur = NULL;

            // if ($_POST['jawaban_pertanyaan_unsur'][$no] == 1 || $_POST['jawaban_pertanyaan_unsur'][$no] == 2) {
            if ($jawaban_pertanyaan_unsur == 1 || $jawaban_pertanyaan_unsur == 2) {
                //$alasan = $_POST['alasan_pertanyaan_unsur'][$no];
                $alasan = $alasan_pertanyaan_unsur;
            } else {
                $alasan = null;
            }

            $id_pertanyaan_unsur = $_POST['id_pertanyaan_unsur'][$no];
            $object = [
                // 'skor_jawaban'     => $_POST['jawaban_pertanyaan_unsur'][$no],
                'skor_jawaban'     => $jawaban_pertanyaan_unsur,
                'alasan_pilih_jawaban' => $alasan,
                'is_active' => '1'
            ];

            $this->db->where('id_responden', $id_res);
            $this->db->where('id_pertanyaan_unsur', $id_pertanyaan_unsur);
            $this->db->update('jawaban_pertanyaan_unsur_' . $table_identity, $object);

            $no++;
        }




        $atribut_pertanyaan = unserialize($manage_survey->atribut_pertanyaan_survey);

        //PENGECEKAN ATRIBUT PERTANYAAN TERBUKA
        if (in_array(2, $atribut_pertanyaan)) {

            $this->db->select('*');
            $this->db->from('pertanyaan_terbuka_' . $table_identity);
            // $this->db->where("is_letak_pertanyaan != 3");
            $get_data_terbuka = $this->db->get();

            $data = array();
            // $n = 1;
            foreach ($get_data_terbuka->result() as $key) {

                $id_pertanyaan_terbuka[$key->id] = $_POST['id_pertanyaan_terbuka'][$key->id];

                if (isset($_POST['jawaban_pertanyaan_terbuka'][$key->id])) {
                    $get_terbuka[$key->id] = $_POST['jawaban_pertanyaan_terbuka'][$key->id];
                } else {
                    $get_terbuka[$key->id] = '';
                };

                if ($get_terbuka[$key->id] == 'Lainnya') {
                	$jawaban_lainnya[$key->id] = $_POST['jawaban_lainnya'][$key->id];
                } else {
                	$jawaban_lainnya[$key->id] = '';
                }


                $value = [
                    'jawaban'     => $get_terbuka[$key->id],
                    'is_active' => '1',
                    'jawaban_lainnya' => $jawaban_lainnya[$key->id]
                ];
                // var_dump($id_pertanyaan_terbuka);

                $this->db->where('id_responden', $id_res);
                $this->db->where('id_pertanyaan_terbuka', $id_pertanyaan_terbuka[$key->id]);
                $this->db->update('jawaban_pertanyaan_terbuka_' . $table_identity, $value);

                // $n++;
            }
        }


        $obj_value = [
            'is_end' => '* Berakhir di Pertanyaan Unsur'
        ];
        $this->db->where('id_responden', $id_res);
        $this->db->update("survey_$table_identity", $obj_value);


        $pesan = 'Data berhasil disimpan';
        $msg = ['sukses' => $pesan];
        echo json_encode($msg);
    }



    public function data_pertanyaan_harapan($id1)
    {
        $this->data = [];
        $this->data['title'] = 'Pertanyaan Harapan';
        $this->data['profiles'] = $this->_get_data_profile($id1);

        $uuid_responden = $this->uri->segment(4);

        $this->db->select('');
        $this->db->from('manage_survey');
        $this->db->where('manage_survey.slug', $this->uri->segment(2));
        $manage_survey = $this->db->get()->row();
        $table_identity = $manage_survey->table_identity;
        $this->data['status_saran'] = $manage_survey->is_saran;
        $this->data['manage_survey'] = $manage_survey;

        $query = $this->db->query("SELECT pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan AS id_unsur_pelayanan, pertanyaan_unsur_pelayanan_$table_identity.id AS id_pertanyaan_unsur, isi_pertanyaan_unsur, IF(unsur_pelayanan_$table_identity.is_sub_unsur_pelayanan = 2, SUBSTRING(nama_unsur_pelayanan, 1, 2), SUBSTRING(nama_unsur_pelayanan, 1, 4)) AS nomor, SUBSTRING(nomor_unsur, 2, 4) AS nomor_harapan, is_required,
        (SELECT skor_jawaban FROM jawaban_pertanyaan_unsur_$table_identity JOIN responden_$table_identity ON jawaban_pertanyaan_unsur_$table_identity.id_responden = responden_$table_identity.id WHERE jawaban_pertanyaan_unsur_$table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$table_identity.id && responden_$table_identity.uuid = '$uuid_responden') AS skor_unsur
		FROM pertanyaan_unsur_pelayanan_$table_identity
		JOIN unsur_pelayanan_$table_identity ON unsur_pelayanan_$table_identity.id = pertanyaan_unsur_pelayanan_$table_identity.id_unsur_pelayanan
		ORDER BY pertanyaan_unsur_pelayanan_$table_identity.id ASC");
        $this->data['pertanyaan_unsur'] = $query;
        // var_dump($this->data['pertanyaan_unsur']->result());

        //JAWABAN PERTANYAAN HARAPAN
        $this->data['jawaban_pertanyaan_harapan'] = $this->db->query("SELECT id_pertanyaan_unsur_pelayanan, nomor_tingkat_kepentingan, nama_tingkat_kepentingan, skor_jawaban
		FROM nilai_tingkat_kepentingan_$table_identity
		LEFT JOIN jawaban_pertanyaan_harapan_$table_identity ON nilai_tingkat_kepentingan_$table_identity.id_pertanyaan_unsur_pelayanan = jawaban_pertanyaan_harapan_$table_identity.id_pertanyaan_unsur
		JOIN responden_$table_identity ON jawaban_pertanyaan_harapan_$table_identity.id_responden = responden_$table_identity.id
		WHERE responden_$table_identity.uuid = '$uuid_responden'");

        //CEK APAKAH SURVEY SUDAH DI SUBMIT APA BELUM
        $this->db->select("survey_$table_identity.is_active, is_submit");
        $this->db->from('survey_' . $table_identity);
        $this->db->join("responden_$table_identity", "survey_$table_identity.id_responden = responden_$table_identity.id");
        $this->db->where("responden_$table_identity.uuid = '$uuid_responden'");
        $cek_data_responden = $this->db->get();
        // $is_selesai_survey = $this->db->get()->row();a

        if ($cek_data_responden->num_rows() == 0) {
            show_404();
        }


        if ($this->uri->segment(5) == 'edit') {
            $is_edit = '/edit';
        } else {
            $is_edit = '';
        }

        $atribut_pertanyaan = unserialize($manage_survey->atribut_pertanyaan_survey);

        //PENGECEKAN ATRIBUT PERTANYAAN KUALITATIF
        if (in_array(3, $atribut_pertanyaan)) {
            $this->data['url_next'] = base_url() . 'survei/' . $this->uri->segment(2) . '/pertanyaan-kualitatif/' . $uuid_responden . $is_edit;
        } else if ($manage_survey->is_saran == 1) {
            $this->data['url_next'] = base_url() . 'survei/' . $this->uri->segment(2) . '/saran/' . $uuid_responden . $is_edit;
        } else {
            $this->data['url_next'] = base_url() . 'survei/' . $this->uri->segment(2) . '/add-konfirmasi/' . $uuid_responden . $is_edit;
        }



        if ($this->uri->segment(5) == 'edit') {
            return view('survei/form_pertanyaan_harapan', $this->data);
        } elseif ($cek_data_responden->row()->is_submit == 2) {
            return view('survei/form_pertanyaan_harapan', $this->data);
        } else {
            redirect(base_url() . 'survei/' . $this->uri->segment(2) . '/selesai/' . $uuid_responden, 'refresh');
        }
    }



    public function add_pertanyaan_harapan($id1)
    {
        $this->data = [];
        $this->data['profiles'] = $this->_get_data_profile($id1);

        $uuid_responden = $this->uri->segment(4);

        $this->db->select('');
        $this->db->from('manage_survey');
        $this->db->where('manage_survey.slug', $this->uri->segment(2));
        $manage_survey = $this->db->get()->row();
        $table_identity = $manage_survey->table_identity;

        $this->db->select('*');
        $this->db->from('responden_' . $table_identity);
        $this->db->where('uuid', $uuid_responden);
        $id_res = $this->db->get()->row()->id;

        $this->db->select('*');
        $this->db->from('pertanyaan_unsur_pelayanan_' . $table_identity);
        $get_data_harapan = $this->db->get();

        $result = array();
        $no = 1;
        foreach ($get_data_harapan->result() as $key => $val) {

            $id_pertanyaan_unsur = $_POST['id_pertanyaan_unsur'][$no];
            $object = [
                'skor_jawaban'     => $_POST['jawaban_pertanyaan_harapan'][$no]
            ];

            $this->db->where('id_responden', $id_res);
            $this->db->where('id_pertanyaan_unsur', $id_pertanyaan_unsur);
            $this->db->update('jawaban_pertanyaan_harapan_' . $table_identity, $object);

            $no++;
        }


        $obj_value = [
            'is_end' => '* Berakhir di Pertanyaan Harapan'
        ];
        $this->db->where('id_responden', $id_res);
        $this->db->update("survey_$table_identity", $obj_value);


        $pesan = 'Data berhasil disimpan';
        $msg = ['sukses' => $pesan];
        echo json_encode($msg);
    }




    public function pertanyaan_kualitatif($id1)
    {
        $this->data = array();
        $this->data['title'] = 'Pertanyaan Kualitatif';
        $this->data['profiles'] = $this->_get_data_profile($id1);

        $uuid_responden = $this->uri->segment(4);

        $this->db->select('');
        $this->db->from('manage_survey');
        $this->db->where('manage_survey.slug', $this->uri->segment(2));
        $manage_survey = $this->db->get()->row();
        $table_identity = $manage_survey->table_identity;
        $this->data['status_saran'] = $manage_survey->is_saran;
        $this->data['manage_survey'] = $manage_survey;

        $this->data['atribut_pertanyaan'] = unserialize($manage_survey->atribut_pertanyaan_survey);


        //CEK APAKAH SURVEY SUDAH DI SUBMIT APA BELUM
        $this->db->select("*, survey_$table_identity.is_active, is_submit");
        $this->db->from('survey_' . $table_identity);
        $this->db->join("responden_$table_identity", "survey_$table_identity.id_responden = responden_$table_identity.id");
        $this->db->where("responden_$table_identity.uuid = '$uuid_responden'");
        $cek_data_responden = $this->db->get();
        // $is_selesai_survey = $this->db->get()->row();

        if ($cek_data_responden->num_rows() == 0) {
            show_404();
        }

        $id_res = $cek_data_responden->row()->id_responden;
        $this->data['kualitatif'] = $this->db->query("select *
		FROM pertanyaan_kualitatif_$table_identity
		JOIN jawaban_pertanyaan_kualitatif_$table_identity ON pertanyaan_kualitatif_$table_identity.id = jawaban_pertanyaan_kualitatif_$table_identity.id_pertanyaan_kualitatif
		WHERE id_responden = $id_res && pertanyaan_kualitatif_$table_identity.is_active = 1")->result();


        if ($this->uri->segment(5) == 'edit') {
            $is_edit = '/edit';
        } else {
            $is_edit = '';
        };


        if ($manage_survey->is_saran == 1) {
            $this->data['url_next'] = base_url() . 'survei/' . $this->uri->segment(2) . '/saran/' . $uuid_responden . $is_edit;
        } else {
            $this->data['url_next'] = base_url() . 'survei/' . $this->uri->segment(2) . '/add-konfirmasi/' . $uuid_responden . $is_edit;
        };

        if ($this->uri->segment(5) == 'edit') {
            return view('survei/pertanyaan_kualitatif', $this->data);
        } elseif ($cek_data_responden->row()->is_submit == 2) {
            return view('survei/pertanyaan_kualitatif', $this->data);
        } else {
            redirect(base_url() . 'survei/' . $this->uri->segment(2) . '/selesai/' . $uuid_responden, 'refresh');
        }
    }

    public function add_kualitatif($id1)
    {
        $this->data = array();
        $this->data['title'] = 'Pertanyaan Kualitatif';
        $this->data['profiles'] = $this->_get_data_profile($id1);

        $uuid_responden = $this->uri->segment(4);

        $this->db->select('');
        $this->db->from('manage_survey');
        $this->db->where('manage_survey.slug', $this->uri->segment(2));
        $manage_survey = $this->db->get()->row();
        $table_identity = $manage_survey->table_identity;

        $this->db->select('*');
        $this->db->from('responden_' . $table_identity);
        $this->db->where('uuid', $this->uri->segment(4));
        $id_res = $this->db->get()->row()->id;

        $this->data['kualitatif'] = $this->db->query("select *
		FROM pertanyaan_kualitatif_$table_identity
		JOIN jawaban_pertanyaan_kualitatif_$table_identity ON pertanyaan_kualitatif_$table_identity.id = jawaban_pertanyaan_kualitatif_$table_identity.id_pertanyaan_kualitatif
		WHERE id_responden = $id_res && pertanyaan_kualitatif_$table_identity.is_active = 1")->result();


        $input     = $this->input->post(null, true);

        $id_kualitatif = $input['id_kualitatif'];
        $isi_jawaban_kualitatif = $input['isi_jawaban_kualitatif'];
        $data = array();
        $result = array();
        foreach ($_POST['isi_jawaban_kualitatif'] as $key => $val) {
            $result = [
                'isi_jawaban_kualitatif' => $isi_jawaban_kualitatif[$key]
            ];

            $this->db->where('id', $id_kualitatif[$key]);
            $this->db->update('jawaban_pertanyaan_kualitatif_' . $table_identity, $result);
            // var_dump($result);
        }


        $obj_value = [
            'is_end' => '* Berakhir di Pertanyaan Kualitatif'
        ];
        $this->db->where('id_responden', $id_res);
        $this->db->update("survey_$table_identity", $obj_value);




        $pesan = 'Data berhasil disimpan';
        $msg = ['sukses' => $pesan];
        echo json_encode($msg);
    }

    public function saran($id1)
    {
        $this->data = [];
        $this->data['title'] = 'Saran';
        $this->data['profiles'] = $this->_get_data_profile($id1);

        $uuid_responden = $this->uri->segment(4);

        $this->db->select('');
        $this->db->from('manage_survey');
        $this->db->where('manage_survey.slug', $this->uri->segment(2));
        $this->data['manage_survey'] = $this->db->get()->row();
        $table_identity = $this->data['manage_survey']->table_identity;

        $this->data['atribut_pertanyaan'] = unserialize($this->data['manage_survey']->atribut_pertanyaan_survey);

        $uuid_responden = $this->uri->segment(4);

        //DATA RESPONDEN
        $this->db->select("*, survey_$table_identity.is_active, is_submit");
        $this->db->from('survey_' . $table_identity);
        $this->db->join("responden_$table_identity", "survey_$table_identity.id_responden = responden_$table_identity.id");
        $this->db->where("responden_$table_identity.uuid = '$uuid_responden'");
        $cek_data_responden = $this->db->get();

        if ($cek_data_responden->num_rows() == 0) {
            show_404();
        }

        $this->data['saran'] = [
            'name'         => 'saran',
            'id'        => 'saran',
            'type'        => 'text',
            'value'        =>    $this->form_validation->set_value('saran', $cek_data_responden->row()->saran),
            'class'        => 'form-control',
            'autofocus' => 'autofocus',
            'placeholder' => 'Masukkan saran atau opini anda terhadap survei ini ..',
            'pattern' => "^[a-zA-Z0-9.,\s]*$|^\w$",
            // 'rows' => 5
        ];


        if ($this->uri->segment(5) == 'edit') {
            return view('survei/form_saran', $this->data);
        } elseif ($cek_data_responden->row()->is_submit == 2) {
            return view('survei/form_saran', $this->data);
        } else {
            redirect(base_url() . 'survei/' . $this->uri->segment(2) . '/selesai/' . $uuid_responden, 'refresh');
        }
    }

    public function add_saran($id1)
    {

        $uuid_responden = $this->uri->segment(4);

        $this->db->select('');
        $this->db->from('manage_survey');
        $this->db->where('manage_survey.slug', $this->uri->segment(2));
        $this->data['manage_survey'] = $this->db->get()->row();
        $table_identity = $this->data['manage_survey']->table_identity;

        //DATA RESPONDEN
        $this->db->select("*, survey_$table_identity.is_active, is_submit");
        $this->db->from('survey_' . $table_identity);
        $this->db->join("responden_$table_identity", "survey_$table_identity.id_responden = responden_$table_identity.id");
        $this->db->where("responden_$table_identity.uuid = '$uuid_responden'");
        $cek_data_responden = $this->db->get();

        $input     = $this->input->post(null, true);

        $object = [
            // 'waktu_isi' => date("Y/m/d H:i:s"),
            'saran'     => $input['saran'],
            'is_active'     => '1',
            'is_end' => '* Berakhir di Pengisian Saran'
        ];

        $id_res = $cek_data_responden->row()->id;
        $this->db->where('id_responden', $id_res);
        $this->db->update('survey_' . $table_identity, $object);


        $pesan = 'Data berhasil disimpan';
        $msg = ['sukses' => $pesan];
        echo json_encode($msg);
    }



    public function form_konfirmasi($id1)
    {
        $this->data = [];
        $this->data['title'] = 'Form Konfirmasi';
        $this->data['profiles'] = $this->_get_data_profile($id1);

        $uuid_responden = $this->uri->segment(4);

        $this->db->select('');
        $this->db->from('manage_survey');
        $this->db->where('manage_survey.slug', $this->uri->segment(2));
        $manage_survey = $this->db->get()->row();
        $table_identity = $manage_survey->table_identity;
        $this->data['status_saran'] = $manage_survey->is_saran;
        $this->data['manage_survey'] = $manage_survey;


        //DATA RESPONDEN
        $this->db->select("*, survey_$table_identity.is_active, is_submit");
        $this->db->from('survey_' . $table_identity);
        $this->db->join("responden_$table_identity", "survey_$table_identity.id_responden = responden_$table_identity.id");
        $this->db->where("responden_$table_identity.uuid = '$uuid_responden'");
        $cek_data_responden = $this->db->get();

        if ($cek_data_responden->num_rows() == 0) {
            show_404();
        }


        if ($this->uri->segment(5) == 'edit') {
            $is_edit = '/edit';
        } else {
            $is_edit = '';
        };


        if ($manage_survey->is_saran == 1) {
            $this->data['link_back'] =  anchor(base_url() . 'survei/' . $this->uri->segment(2) . '/saran/' . $this->uri->segment(4) . $is_edit, '<i class="fa fa-arrow-left"></i> Lengkapi Kembali', ['class' => 'btn btn-secondary btn-lg font-weight-bold shadow tombolCancel']);
        } else if (in_array(3, unserialize($manage_survey->atribut_pertanyaan_survey))) {

            $this->data['link_back'] =  anchor(base_url() . 'survei/' . $this->uri->segment(2) . '/pertanyaan-kualitatif/' . $this->uri->segment(4) . $is_edit, '<i class="fa fa-arrow-left"></i> Lengkapi Kembali', ['class' => 'btn btn-secondary btn-lg font-weight-bold shadow tombolCancel']);
        } else if (in_array(1, unserialize($manage_survey->atribut_pertanyaan_survey))) {

            $this->data['link_back'] =  anchor(base_url() . 'survei/' . $this->uri->segment(2) . '/pertanyaan-harapan/' . $this->uri->segment(4) . $is_edit, '<i class="fa fa-arrow-left"></i> Lengkapi Kembali', ['class' => 'btn btn-secondary btn-lg font-weight-bold shadow tombolCancel']);
        } else {

            $this->data['link_back'] =  anchor(base_url() . 'survei/' . $this->uri->segment(2) . '/pertanyaan/' . $this->uri->segment(4) . $is_edit, '<i class="fa fa-arrow-left"></i> Lengkapi Kembali', ['class' => 'btn btn-secondary btn-lg font-weight-bold shadow tombolCancel']);
        }


        if ($this->uri->segment(5) == 'edit') {
            return view('survei/form_konfirmasi', $this->data);
        } elseif ($cek_data_responden->row()->is_submit == 2) {
            return view('survei/form_konfirmasi', $this->data);
        } else {
            redirect(base_url() . 'survei/' . $this->uri->segment(2) . '/selesai/' . $uuid_responden, 'refresh');
        }
    }


    public function add_konfirmasi($id1)
    {
        $this->db->select('');
        $this->db->from('manage_survey');
        $this->db->where('manage_survey.slug', $this->uri->segment(2));
        $manage_survey = $this->db->get()->row();

        $this->db->select('*');
        $this->db->from('responden_' . $manage_survey->table_identity);
        $this->db->where('uuid', $this->uri->segment(4));
        $id_res = $this->db->get()->row()->id;

        $object = [
            'is_submit' => 1,
            'is_end' => '* Finish'
        ];

        $this->db->where('id_responden', $id_res);
        $this->db->update('survey_' . $manage_survey->table_identity, $object);

        redirect(base_url() . 'survei/' . $this->uri->segment(2) . '/selesai/' . $this->uri->segment(4), 'refresh');

        // $pesan = 'Data berhasil disimpan';
        // $msg = ['sukses' => $pesan];
        // echo json_encode($msg);
    }




    public function form_closing()
    {
        $this->data = [];
        $this->data['title'] = 'Sukses';
        $this->data['get_template'] = $this->_get_tamplate();

        $this->db->select('*');
        $this->db->from('manage_survey');
        $this->db->where('manage_survey.slug', $this->uri->segment(2));
        $this->data['manage_survey'] = $this->db->get()->row();
        $this->data['status_saran'] = $this->data['manage_survey']->is_saran;

        $this->db->select('*');
        $this->db->from('responden_' . $this->data['manage_survey']->table_identity);
        $this->db->where('uuid', $this->uri->segment(4));
        $this->data['responden'] = $this->db->get()->row();
        // var_dump($this->data['responden']);


        return view('survei/form_closing', $this->data);
    }

    public function unopened()
    {
        $this->data = [];
        $this->data['title'] = 'Link Survei';

        $this->db->select('*');
        $this->db->from('manage_survey');
        $this->db->where('manage_survey.slug', $this->uri->segment(2));
        $manage_survey = $this->db->get()->row();
        $this->data['judul'] = $manage_survey;


        return view('survei/form_setting_survey/unopened', $this->data);
    }

    public function survey_end()
    {
        $this->data = [];
        $this->data['title'] = 'Link Survei';

        $this->db->select('*');
        $this->db->from('manage_survey');
        $this->db->where('manage_survey.slug', $this->uri->segment(2));
        $manage_survey = $this->db->get()->row();
        $this->data['judul'] = $manage_survey;


        return view('survei/form_setting_survey/survey_end', $this->data);
    }

    public function survey_hold()
    {
        $this->data = [];
        $this->data['title'] = 'Link Survei';

        $this->db->select('*');
        $this->db->from('manage_survey');
        $this->db->where('manage_survey.slug', $this->uri->segment(2));
        $manage_survey = $this->db->get()->row();
        $this->data['judul'] = $manage_survey;

        return view('survei/form_setting_survey/survey_hold', $this->data);
    }

    public function survey_not_question()
    {
        $this->data = [];
        $this->data['title'] = 'Link Survei';

        $this->db->select('*');
        $this->db->from('manage_survey');
        $this->db->where('manage_survey.slug', $this->uri->segment(2));
        $manage_survey = $this->db->get()->row();
        $this->data['judul'] = $manage_survey;


        return view('survei/form_setting_survey/survey_not_question', $this->data);
    }

    public function petunjuk_pengisian_survey()
    {
        $this->data = [];
        $this->data['title'] = "Petunjuk Pengisian Survey";

        return view('survei/form_bantuan/form_petunjuk_pengisian_survey', $this->data);
        // $profiles =  $this->_get_data_profile($id1, $id2);
        // $this->data['profiles'] = $profiles;
    }

    public function kontak_kami()
    {
        $this->data = [];
        $this->data['title'] = "";

        // $profiles =  $this->_get_data_profile($id1, $id2);
        // $this->data['profiles'] = $profiles;
    }

    public function faq()
    {
        $this->data = [];
        $this->data['title'] = "";

        // $profiles =  $this->_get_data_profile($id1, $id2);
        // $this->data['profiles'] = $profiles;
    }


    public function edit_data_responden()
    {

        $this->data = [];
        $this->data['title'] = 'Edit Data Responden';

        $this->db->select("*, DATE_FORMAT(survey_end, '%d %M %Y') AS survey_selesai, IF(CURDATE() > survey_end,1,NULL) AS survey_berakhir, IF(CURDATE() < survey_start ,1,NULL) AS survey_belum_mulai");
        $this->db->from('manage_survey');
        $this->db->where('manage_survey.slug', $this->uri->segment(2));
        $manage_survey = $this->db->get()->row();
        $table_identity = $manage_survey->table_identity;
        $this->data['status_saran'] = $manage_survey->is_saran;
        $this->data['manage_survey'] = $manage_survey;


        //LOAD PROFIL RESPONDEN
        $this->data['profil_responden'] = $this->db->query("SELECT * FROM profil_responden_$table_identity ORDER BY IF(urutan != '',urutan,id) ASC");
        // var_dump($this->data['profil_responden']->result());

        //LOAD KATEGORI PROFIL RESPONDEN JIKA PILIHAN GANDA
        $this->data['kategori_profil_responden'] = $this->db->get('kategori_profil_responden_' . $table_identity);


        $uuid_responden = $this->uri->segment(4);
        $this->data['responden'] = $this->db->get_where("responden_$table_identity", array("uuid" => $uuid_responden))->row();
        // var_dump($this->data['responden']);

        // $this->data['nama_lengkap'] = [
        //     'name'         => 'nama_lengkap',
        //     'id'        => 'nama_lengkap',
        //     'type'        => 'text',
        //     'value'        =>  $this->form_validation->set_value('nama_lengkap', $this->data['responden']->nama_lengkap),
        //     'class'        => 'form-control',
        //     'autofocus' => 'autofocus',
        //     'required' => 'required',
        //     'placeholder' => 'Masukkan data anda ...',
        // ];


        $this->data['id_layanan_survei'] = [
            'name'         => 'id_layanan_survei',
            'id'         => 'id_layanan_survei',
            'options'     => $this->Survei_model->dropdown_layanan_survei($table_identity),
            'selected'     => $this->form_validation->set_value('id_layanan_survei', $this->data['responden']->id_layanan_survei),
            'class'     => "form-control",
            'required' => 'required',
        ];


        return view('survei/edit_data_responden', $this->data);
    }

    function update_data_responden()
    {
        $uuid_responden = $this->uri->segment(4);

        $this->db->select("*, DATE_FORMAT(survey_end, '%d %M %Y') AS survey_selesai, IF(CURDATE() > survey_end,1,NULL) AS survey_berakhir, IF(CURDATE() < survey_start ,1,NULL) AS survey_belum_mulai");
        $this->db->from('manage_survey');
        $this->db->where('manage_survey.slug', $this->uri->segment(2));
        $manage_survey = $this->db->get()->row();
        $table_identity = $manage_survey->table_identity;

        //LOAD PROFIL RESPONDEN
        $this->data['profil_responden'] = $this->db->query("SELECT * FROM profil_responden_$table_identity");

        $responden = $this->db->get_where("responden_$table_identity", array("uuid" => $uuid_responden))->row();
        // $cek_duplicat = $this->db->query("SELECT * FROM responden_$table_identity WHERE nama_lengkap = '$responden->nama_lengkap'");

        // if ($cek_duplicat->num_rows() > 1) {
        //     $is_duplicated = $cek_duplicat->num_rows() - 1;

        //     $color = $this->db->get_where("survey_$table_identity", array('id_responden' => $responden->id))->row()->color;

        //     $this->db->query("UPDATE survey_$table_identity JOIN responden_$table_identity ON survey_$table_identity.id_responden = responden_$table_identity.id SET survey_$table_identity.is_duplicated = $is_duplicated, survey_$table_identity.color = '$color' WHERE responden_$table_identity.nama_lengkap = '$responden->nama_lengkap'");
        // } else {
        //     $is_duplicated = 1;

        //     $this->db->query("UPDATE survey_$table_identity JOIN responden_$table_identity ON survey_$table_identity.id_responden = responden_$table_identity.id SET survey_$table_identity.is_duplicated = $is_duplicated WHERE responden_$table_identity.nama_lengkap = '$responden->nama_lengkap'");
        // }

        //INSERT DATA RSEPONDEN

        if ($manage_survey->is_layanan_survei != 0) {
            $id_layanan_survei = $this->input->post('id_layanan_survei');
        } else {
            $id_layanan_survei = '';
        }

        $object = [
            'id_layanan_survei'     => $id_layanan_survei,
            'updated_at' => date("Y/m/d H:i:s")
        ];

        foreach ($this->data['profil_responden']->result() as $row) {
            $object[$row->nama_alias] = $this->input->post($row->nama_alias);

            if ($row->is_lainnya == 1) {
                $nama_lainnya = $row->nama_alias . '_lainnya';
                $object[$nama_lainnya] = $this->input->post($nama_lainnya);
            }
        }
        $this->db->where('uuid', $uuid_responden);
        $this->db->update("responden_$table_identity", $object);


        // $cek_duplicat_2 = $this->db->query("SELECT * FROM responden_$table_identity WHERE nama_lengkap = '" . $this->input->post('nama_lengkap') . "'");
        // if ($cek_duplicat_2->num_rows() > 1) {
        //     $is_duplicated_2 = $cek_duplicat_2->num_rows();

        //     $color_2 = $this->db->get_where("survey_$table_identity", array('id_responden' => $cek_duplicat_2->row()->id))->row()->color;

        //     $this->db->query("UPDATE survey_$table_identity JOIN responden_$table_identity ON survey_$table_identity.id_responden = responden_$table_identity.id SET survey_$table_identity.is_duplicated = $is_duplicated_2, survey_$table_identity.color = '$color_2' WHERE responden_$table_identity.nama_lengkap = '" . $this->input->post('nama_lengkap') . "'");
        // } else {
        //     $is_duplicated_2 = 1;

        //     $this->db->query("UPDATE survey_$table_identity JOIN responden_$table_identity ON survey_$table_identity.id_responden = responden_$table_identity.id SET survey_$table_identity.is_duplicated = $is_duplicated_2 WHERE responden_$table_identity.nama_lengkap = '" . $this->input->post('nama_lengkap') . "'");
        // }


        $pesan = 'Data berhasil disimpan';
        $msg = ['sukses' => $pesan];
        echo json_encode($msg);
    }



    public function _get_data_profile($id1)
    {
        $this->data['get_template'] = $this->_get_tamplate();

        $this->db->select('users.username, manage_survey.survey_name, manage_survey.slug, manage_survey.description, manage_survey.is_privacy');
        $this->db->from('users');
        $this->db->join('manage_survey', 'manage_survey.id_user = users.id');
        $this->db->where('manage_survey.slug', $id1);
        $profiles = $this->db->get();

        if ($profiles->num_rows() == 0) {
            // echo 'Survey tidak ditemukan atau sudah dihapus !';
            // exit();
            show_404();
        }

        return $profiles->row();
    }
}