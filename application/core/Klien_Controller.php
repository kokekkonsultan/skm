<?php

namespace application\core;

class Klien_Controller
{

    function _get_data_profile($id1, $id2)
    {
        $ci = &get_instance();

        $ci->db->select('*');
        $ci->db->from('users');
        $ci->db->join('users_groups', 'users.id = users_groups.user_id');
        $ci->db->where('users.username', $ci->session->userdata('username'));
        $data_user = $ci->db->get()->row();
        $user_identity = 'drs' . $data_user->is_parent;

        $ci->db->select('*');
        if ($data_user->group_id == 5) {
            $ci->db->from('manage_survey');
            $ci->db->join("supervisor_$user_identity", "manage_survey.id_berlangganan = supervisor_$user_identity.id_berlangganan");
            $ci->db->join("users", "supervisor_$user_identity.id_user = users.id");
        } else {
            $ci->db->from('users');
            $ci->db->join('manage_survey', 'manage_survey.id_user = users.id');
        }
        $ci->db->where('users.username', $id1);
        $ci->db->where('manage_survey.slug', $id2);
        $profiles = $ci->db->get();

        if ($profiles->num_rows() == 0) {
            // echo 'Survey tidak ditemukan atau sudah dihapus !';
            // exit();
            show_404();
        }
        return $profiles->row();


        // $ci->db->select('users.username, manage_survey.survey_name, manage_survey.slug, manage_survey.description, manage_survey.is_privacy, manage_survey.table_identity');
        // $ci->db->from('users');
        // $ci->db->join('manage_survey', 'manage_survey.id_user = users.id');
        // $ci->db->where('users.username', $id1);
        // $ci->db->where('manage_survey.slug', $id2);
        // $profiles = $ci->db->get();

        // if ($profiles->num_rows() == 0) {
        //     echo 'Survey tidak ditemukan atau sudah dihapus !';
        //     exit();
        // }

        // return $profiles->row();
    }
}
