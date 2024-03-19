<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Reporting extends CI_Controller
{
    public function index($id1, $id2)
    {

        $this->data = [];
        $this->data['title'] = 'Detail Pertanyaan Unsur';
        $this->data['profiles'] = $this->_get_data_profile($id1, $id2);

        $this->db->select('manage_survey.id AS id_manage_survey, manage_survey.table_identity AS table_identity, id_user');
        $this->db->from('manage_survey');
        $this->db->where('manage_survey.slug', $this->uri->segment(2));
        $manage_survey = $this->db->get()->row();

        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('id=' . $manage_survey->id_user);
        $user = $this->db->get()->row();
        // var_dump($user);

        $this->db->select('nama_mst_profil_responden');
        $this->db->from('profil_responden_kuesioner');
        $this->db->join('mst_profil_responden_kuesioner', 'profil_responden_kuesioner.id_mst_profil_responden = mst_profil_responden_kuesioner.id');
        $this->db->where('id_klasifikasi_survey=', $user->id_klasifikasi_survei);
        $this->data['profil_responden'] = $this->db->get();
        // var_dump($this->data['profil_responden']->result());

        $this->db->select("pertanyaan_unsur_pelayanan_$manage_survey->table_identity.id AS id_pertanyaan_unsur, unsur_pelayanan_$manage_survey->table_identity.nomor_unsur AS nomor, isi_pertanyaan_unsur");
        $this->db->from("pertanyaan_unsur_pelayanan_$manage_survey->table_identity");
        $this->db->join("unsur_pelayanan_$manage_survey->table_identity", "pertanyaan_unsur_pelayanan_$manage_survey->table_identity.id_unsur_pelayanan = unsur_pelayanan_$manage_survey->table_identity.id");
        $this->data['pertanyaan'] = $this->db->get();

        if ($this->data['pertanyaan']->num_rows() > 0) {
            $this->data['pertanyaan'] = $this->data['pertanyaan'];
        } else {
            echo 'Survei belum dimulai atau belum ada responden !';
            exit();

            $this->db->select("id_pertanyaan_unsur, nama_kategori_unsur_pelayanan, nomor_kategori_unsur_pelayanan");
            $this->db->from("kategori_unsur_pelayanan_$manage_survey->table_identity");
            $this->db->join("pertanyaan_unsur_pelayanan_$manage_survey->table_identity", "kategori_unsur_pelayanan_$manage_survey->table_identity.id_pertanyaan_unsur = pertanyaan_unsur_pelayanan_$manage_survey->table_identity.id");
            $this->db->join("unsur_pelayanan_$manage_survey->table_identity", "pertanyaan_unsur_pelayanan_$manage_survey->table_identity.id_unsur_pelayanan = unsur_pelayanan_$manage_survey->table_identity.id");
            $this->data['jawaban'] = $this->db->get();

            $this->db->select('id_pertanyaan_unsur, alasan_pilih_jawaban');
            $this->db->from('jawaban_pertanyaan_unsur_' . $manage_survey->table_identity);
            $this->db->where('id_responden', $this->uri->segment(5));
            $this->data['alasan'] = $this->db->get();

            // panggil library yang kita buat sebelumnya yang bernama pdfgenerator
            $this->load->library('pdfgenerator');

            // title dari pdf
            $this->data['title_pdf'] = 'Draf Kuesioner';

            // filename dari pdf ketika didownload
            $file_pdf = 'draf_kuesioner';
            // setting paper
            $paper = 'A4';
            //orientasi paper potrait / landscape
            $orientation = "landscape";

            $html = $this->load->view('laporan_pdf', $this->data, true);

            // run dompdf
            $this->pdfgenerator->generate($html, $file_pdf, $paper, $orientation);
        }
    }

    public function _get_data_profile($id1, $id2)
    {
        $this->db->select('users.username, manage_survey.survey_name, manage_survey.slug, manage_survey.description, manage_survey.is_privacy');
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
