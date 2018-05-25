<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Change_information
        extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Common_model', '', TRUE);
        $this->load->model('Email_template_model', '', TRUE);
    }

    public function index() {

        $user_id = $this->loggedin_user_data['user_id'];
        $user_type = $this->loggedin_user_type;

        $user_data = array(
            'table' => tbl_user,
            'where' => array('id_user' => $user_id)
        );
        $user_details = $this->Common_model->master_single_select($user_data);
        $this->data['user_details'] = $user_details;
        $exist_email_id = $user_details['email_id'];

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
                'first_name',
                'last_name',
                'email_id',
                'mobile'
            );
            if ($this->_validate_form($validate_fields)) {

                $date = date('Y-m-d h:i:s');
                $data = array(
                    'first_name' => $this->input->post('first_name', TRUE),
                    'last_name' => $this->input->post('last_name', TRUE),
                    'mobile' => $this->input->post('mobile', TRUE),
                    'modified_date' => $date
                );
                if ($user_type != STORE_OR_MALL_ADMIN_USER_TYPE)
                    $data['email_id'] = $this->input->post('email_id', TRUE);

                $where = array('id_user' => $user_id);

                $is_updated = $this->Common_model->master_update(tbl_user, $data, $where);
                if ($is_updated) {

                    if ($user_type == STORE_OR_MALL_ADMIN_USER_TYPE && $exist_email_id != $this->input->post('email_id', TRUE)) {
                        //send verification email

                        $update_data = array('status' => 1);
                        $where = array(
                            'id_user' => $user_id,
                            'purpose' => VERIFICATION_CHANGE_EMAIL,
                            'status' => 0
                        );
                        $is_updated = $this->Common_model->master_update(tbl_verification, $update_data, $where);

                        $verification_code = md5(time());
                        $verification_link = SITEURL . 'email-change-verify?verification=' . $verification_code;
                        $subject = 'Email Change Verification';
                        $content = $this->Email_template_model->email_change_verification($verification_link);                        
                        $response = $this->Email_template_model->send_email(NULL, $this->input->post('email_id', TRUE), $subject, $content);

                        if (isset($response) && $response == 'yes') {

                            $in_veri_data = array(
                                'id_user' => $user_id,
                                'purpose' => VERIFICATION_CHANGE_EMAIL,
                                'email_id' => $this->input->post('email_id', TRUE),
                                'status' => 0,
                                'verification_code' => $verification_code
                            );

                            $this->Common_model->master_save(tbl_verification, $in_veri_data);

                            $this->session->set_userdata('change_credetials', SUCCESS_CHANGE_EMAIL);
                        }
                    }

                    $this->session->set_flashdata('success_msg', "Information Updated Successfully.");
                } else {
                    $this->session->set_flashdata('error_msg', "Information not updated.");
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
                'label' => 'Contact Person\'s Last Name',
                'rules' => 'trim|required|min_length[2]|max_length[150]|htmlentities'
            );
        }
        if (in_array('email_id', $validate_fields)) {
            $validation_rules[] = array(
                'field' => 'email_id',
                'label' => 'Email Address',
                'rules' => 'trim|required|min_length[2]|max_length[100]|htmlentities|callback_validate_email_id'
            );
        }
        if (in_array('mobile', $validate_fields)) {
            $validation_rules[] = array(
                'field' => 'mobile',
                'label' => 'Mobile Number',
                'rules' => 'trim|required|alpha_numeric|min_length[8]|max_length[20]|htmlentities'
            );
        }

        $this->form_validation->set_rules($validation_rules);
        return $this->form_validation->run();
    }

    function validate_email_id($email) {

        $user_id = $this->loggedin_user_data['user_id'];

        $user_data = array(
            'table' => tbl_user,
            'where' => array(
                'email_id' => $email,
                'is_delete' => IS_NOT_DELETED_STATUS
            ),
            'where_with_sign' => array('id_user !=' . $user_id)
        );

        $user_exist = $this->Common_model->master_single_select($user_data);

        if (isset($user_exist) && sizeof($user_exist) > 0) {
            $this->form_validation->set_message('validate_email_id', 'Email Address already exist.');
            return FALSE;
        } else {
            return TRUe;
        }
    }

}
