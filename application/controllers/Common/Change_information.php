<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Change_information
        extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Common_model', '', TRUE);
    }

    public function index() {

        $user_dashboard = '';
        if ($this->loggedin_user_type == SUPER_ADMIN_USER_TYPE) {
            $user_dashboard = 'super-admin/dashboard';
            $change_password_url = 'super-admin/change-information';
        } elseif ($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE) {
            $user_dashboard = 'country-admin/dashboard';
            $change_password_url = 'country-admin/change-information';
        } elseif ($this->loggedin_user_type == STORE_OR_MALL_ADMIN_USER_TYPE) {
            $user_dashboard = 'mall-store-user/dashboard';
            $change_password_url = 'mall-store-user/change-information';
        }

        if ($this->input->post()) {

            $validate_fields = array(
                'current_password',
                'new_password',
                'confirm_password',
            );
            if ($this->_validate_form($validate_fields)) {

                $logged_in_user_id = $this->loggedin_user_data['user_id'];

                $date = date('Y-m-d h:i:s');
                $data = array(
                    'password' => md5($this->input->post('new_password', TRUE)),
                    'modified_date' => $date
                );
                $where = array('id_user' => $logged_in_user_id);
                $is_updated = $this->Common_model->master_update(tbl_user, $data, $where);
                if ($is_updated) {
                    $this->session->set_flashdata('success_msg', "Password Updated Successfully.");
                } else {
                    $this->session->set_flashdata('error_msg', "Password not updated.");
                }
                redirect($change_password_url);
            }
        }

        $this->data['title'] = $this->data['page_header'] = 'Change Information';

        $this->bread_crum[] = array(
            'url' => '',
            'title' => 'Change Information',
        );

        $this->data['back_url'] = $user_dashboard;
        $this->template->load('user', 'Common/change_information', $this->data);
    }

    function _validate_form($validate_fields) {
        $validation_rules = array();

        if (in_array('first_name', $validate_fields)) {
            $validation_rules[] = array(
                'field' => 'first_name',
                'label' => 'Contact Person\'s First Name',
                'rules' => 'trim|required|min_length[2]|max_length[150]|htmlentities'
            );
        }
        if (in_array('last_name', $validate_fields)) {
            $validation_rules[] = array(
                'field' => 'last_name',
                'label' => 'Last Name',
                'rules' => 'trim|required|min_length[2]|max_length[150]|htmlentities'
            );
        }
        if (in_array('email_id', $validate_fields)) {
            $validation_rules[] = array(
                'field' => 'email_id',
                'label' => 'Email Address',
                'rules' => 'trim|required|min_length[2]|max_length[100]|htmlentities'
            );
        }
        if (in_array('mobile', $validate_fields)) {
            $validation_rules[] = array(
                'field' => 'mobile',
                'label' => 'Mobile Number',
                'rules' => 'trim|required|min_length[6]|max_length[15]|htmlentities'
            );
        }

        $this->form_validation->set_rules($validation_rules);
        return $this->form_validation->run();
    }

}
