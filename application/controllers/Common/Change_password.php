<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Change_password
        extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Common_model', '', TRUE);
    }

    public function index() {

        $user_dashboard = '';
        if ($this->loggedin_user_type == SUPER_ADMIN_USER_TYPE) {
            $user_dashboard = 'super-admin/dashboard';
            $change_password_url = 'super-admin/change-password';
        } elseif ($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE) {
            $user_dashboard = 'country-admin/dashboard';
            $change_password_url = 'country-admin/change-password';
        } elseif ($this->loggedin_user_type == STORE_OR_MALL_ADMIN_USER_TYPE) {
            $user_dashboard = 'mall-store-user/dashboard';
            $change_password_url = 'mall-store-user/change-password';
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
        $this->data['title'] = $this->data['page_header'] = 'Change Password';

        $this->bread_crum[] = array(
            'url' => '',
            'title' => 'Change Password',
        );

        $this->data['back_url'] = $user_dashboard;
        $this->template->load('user', 'Common/change_password', $this->data);
    }

    function _validate_form($validate_fields) {
        $validation_rules = array();

        if (in_array('current_password', $validate_fields)) {
            $validation_rules[] = array(
                'field' => 'current_password',
                'label' => 'Current Password',
                'rules' => 'required|callback_validate_current_password|htmlentities',
            );
        }
        if (in_array('new_password', $validate_fields)) {
            $validation_rules[] = array(
                'field' => 'new_password',
                'label' => 'New Password',
                'rules' => 'required|min_length[5]|matches[confirm_password]|htmlentities',
            );
        }
        if (in_array('confirm_password', $validate_fields)) {
            $validation_rules[] = array(
                'field' => 'confirm_password',
                'label' => 'Confirm Password',
                'rules' => 'required|min_length[5]|htmlentities',
            );
        }

        $this->form_validation->set_rules($validation_rules);
        return $this->form_validation->run();
    }

    function validate_current_password($control_value) {        
        $logged_in_user_id = $this->loggedin_user_data['user_id'];
        $where = array(
            'id_user' => $logged_in_user_id,
            'password' => md5($control_value),
        );
        $count = $this->Common_model->master_count(tbl_user, $where);        
        if ($count == 1) {
            return TRUE;
        } else {
            $this->form_validation->set_message('validate_current_password', 'The {field} is invalid.');
            return FALSE;
        }
    }

}
