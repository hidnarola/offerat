<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Login
        extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Common_model', '', TRUE);
        $this->load->model('Email_template_model', '', TRUE);
    }

    public function index() {

        is_logged_in();
        $this->data['title'] = $this->data['page_header'] = 'Login';
        $date = date('Y-m-d h:i:s');

        if ($this->input->post()) {
            $validate_fields = array('email', 'password');

            if ($this->_login_validate_form($validate_fields)) {
                $email = $this->input->post('email', TRUE);
                $password = $this->input->post('password', TRUE);

                $select_data_user = array(
                    'table' => tbl_user,
                    'where' => array(
//                        'user_type' => SUPER_ADMIN_USER_TYPE,
                        'email_id' => $email,
                        'password' => md5($password)
                    )
                );

                $user = $this->Common_model->master_single_select($select_data_user);
                
                if (isset($user) && sizeof($user) > 0) {

                    if ($user['status'] == ACTIVE_STATUS && $user['is_delete'] == IS_NOT_DELETED_STATUS && in_array($user['user_type'], array(SUPER_ADMIN_USER_TYPE, COUNTRY_ADMIN_USER_TYPE, STORE_OR_MALL_ADMIN_USER_TYPE))) {
                        //manage session
                        $session_user_type = '';
                        if($user['user_type'] == SUPER_ADMIN_USER_TYPE)
                            $session_user_type = SUPER_ADMIN_USER_TYPE;
                        elseif($user['user_type'] == COUNTRY_ADMIN_USER_TYPE)
                            $session_user_type = COUNTRY_ADMIN_USER_TYPE;
                        elseif($user['user_type'] == STORE_OR_MALL_ADMIN_USER_TYPE)
                            $session_user_type = STORE_OR_MALL_ADMIN_USER_TYPE;

                        $session_user_data = array(
                            'user_id' => $user['id_user'],
                            'email_id' => $user['email_id']
                        );

                        $this->session->set_userdata('loggedin_user_type', $session_user_type);
                        $this->session->set_userdata('loggedin_user_data', $session_user_data);

                        //update login time
                        $update_user_data = array('last_login_at' => $date);
                        $where_user_data = array('id_user' => $user['id_user']);

                        $this->Common_model->master_update(tbl_user, $update_user_data, $where_user_data);

                        $this->session->set_flashdata('success_msg', 'Welcome Super Admin');
                        
                        if($user['user_type'] == SUPER_ADMIN_USER_TYPE)
                            redirect('super-admin/dashboard');
                        elseif($user['user_type'] == COUNTRY_ADMIN_USER_TYPE)
                            redirect('country-admin/dashboard');
                        elseif($user['user_type'] == STORE_OR_MALL_ADMIN_USER_TYPE)
                            redirect('mall-store-user/dashboard');
                        
                    } elseif ($user['status'] != ACTIVE_STATUS) {
                        $this->session->set_flashdata('error_msg', 'Your Account is inactivated.');
                        redirect('login');
                    } elseif ($user['is_delete'] != IS_NOT_DELETED_STATUS) {
                        $this->session->set_flashdata('error_msg', 'Your Account has been deleted earlier.');
                        redirect('login');
                    } else {
                        $this->session->set_flashdata('error_msg', 'Invalid Request for Login');
                        redirect('login');
                    }
                } else {
                    $this->session->set_flashdata('error_msg', 'Invalid Login Credentials');
                    redirect('login');
                }
            }
        }
        $this->login_template->load('index', 'Login/login', $this->data);
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect('/');
    }

    function _login_validate_form($validate_fields) {

        $validation_rules = array();

        if (in_array('email', $validate_fields)) {
            $validation_rules[] = array(
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'trim|required|min_length[3]|max_length[100]|htmlentities',
            );
        }

        if (in_array('password', $validate_fields)) {
            $validation_rules[] = array(
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'required|htmlentities',
            );
        }

        $this->form_validation->set_rules($validation_rules);

        return $this->form_validation->run();
    }

    public function reset_password() {

        is_logged_in();
        if ($this->input->post()) {

            $validate_fields = array('email');

            if ($this->_login_validate_form($validate_fields)) {
                $email = $this->input->post('email', TRUE);

                $select_data_user = array(
                    'table' => tbl_user,
                    'where' => array('email_id' => $email)
                );

                $user = $this->Common_model->master_single_select($select_data_user);

                if (isset($user) && sizeof($user) > 0) {

                    if ($user['status'] == ACTIVE_STATUS && $user['is_delete'] == IS_NOT_DELETED_STATUS && $user['user_type'] == SUPER_ADMIN_USER_TYPE) {

                        $reset_code = md5(time());
                        $reset_link = SITEURL . 'change-password?reset_code=' . $reset_code;
                        $subject = 'Forgot Password on Offerat';
                        $content = $this->Email_template_model->forgot_password_format($reset_link);
                        $response = $this->Email_template_model->send_email(NULL, $user['email_id'], $subject, $content);

                        if (isset($response) && $response == 'yes') {

                            $in_veri_data = array(
                                'id_user' => $user['id_user'],
                                'verification_code' => $reset_code,
                                'purpose' => VERIFICATION_RESET_PASSWORD,
                                'email_id' => $user['email_id'],
                                'link_url' => $reset_link,
                                'status' => 0
                            );
                            $this->Common_model->master_save(tbl_verification, $in_veri_data);

                            $this->session->set_flashdata('success_msg', 'Verification Email Sent. Please click on link in Email for Reset Password.');
                            redirect('login');
                        } else {
                            $this->session->set_flashdata('error_msg', 'Unable to send Email for Reset Password. Please try again later.');
                            redirect('reset-password');
                        }
                    } elseif ($user['status'] != ACTIVE_STATUS) {
                        $this->session->set_flashdata('error_msg', 'Your Account is inactivated.');
                        redirect('reset-password');
                    } elseif ($user['is_delete'] != IS_NOT_DELETED_STATUS) {
                        $this->session->set_flashdata('error_msg', 'Your Account has been deleted earlier.');
                        redirect('reset-password');
                    } else {
                        $this->session->set_flashdata('error_msg', 'Invalid Email request for Forgot Password');
                        redirect('reset-password');
                    }
                } else {
                    $this->session->set_flashdata('error_msg', 'Invalid Email request for Forgot Password');
                    redirect('reset-password');
                }
            }
        }

        $this->data['title'] = $this->data['page_header'] = 'Reset Password';
        $this->login_template->load('index', 'Login/reset_password', $this->data);
    }

    public function change_password() {
        is_logged_in();
        $reset_code = $this->input->get('reset_code', TRUE);

        if (isset($reset_code) && !empty($reset_code)) {

            $reset_code_arr = array(
                'table' => tbl_verification,
                'where' => array(
                    'verification_code' => $reset_code,
                    'purpose' => VERIFICATION_RESET_PASSWORD,
                    'status' => 0
                )
            );
            $verified_data = $this->Common_model->master_single_select($reset_code_arr);

            if (isset($verified_data) && sizeof($verified_data) > 0) {

                $user_arr = array(
                    'table' => tbl_user,
                    'where' => array(
                        'status' => ACTIVE_STATUS,
                        'id_user' => $verified_data['id_user']
                    )
                );
                $user_data = $this->Common_model->master_single_select($user_arr);

                if (isset($user_data) && sizeof($user_data) > 0) {
                    $user_id = $user_data['id_user'];

                    if ($this->input->post()) {

                        $validate_fields = array(
                            'password',
                            'confirm_password'
                        );

                        if ($this->_validate_reset_password_form($validate_fields)) {

                            $date = date('Y-m-d h:i:s');

                            $update_data = array(
                                'password' => md5($this->input->post('password', TRUE)),
                                'modified_date' => $date
                            );

                            $where = array('id_user' => $user_id);
                            $is_updated = $this->Common_model->master_update(tbl_user, $update_data, $where);

                            $update_data = array(
                                'status' => 1,
                                'modified_date' => $date
                            );
                            $where = array(
                                'verification_code' => $reset_code,
                                'id_user' => $user_id,
                                'purpose' => VERIFICATION_RESET_PASSWORD,
                                'status' => 0
                            );
                            $is_updated = $this->Common_model->master_update(tbl_verification, $update_data, $where);

                            if ($is_updated)
                                $this->session->set_flashdata('success_msg', "Password reset successfully!");
                            else
                                $this->session->set_flashdata('error_msg', "Password not reset");

                            redirect('login');
                        }
                    }
                }
                else {
                    $this->session->set_flashdata('error_msg', 'Invalid Request to reset password');
                    redirect('login');
                }
            } else {
                $this->session->set_flashdata('error_msg', 'Invalid Request to reset password');
                redirect('login');
            }

            $this->data['title'] = $this->data['page_header'] = 'Change Password';
            $this->login_template->load('index', 'Login/change_password', $this->data);
        }
    }

    function _validate_reset_password_form($validate_fields) {
        $validation_rules = array();

        if (in_array('password', $validate_fields)) {
            $validation_rules[] = array(
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'min_length[5]|required|htmlentities'
            );
        }
        if (in_array('confirm_password', $validate_fields)) {
            $validation_rules[] = array(
                'field' => 'confirm_password',
                'label' => 'Re-entered Password',
                'rules' => 'min_length[5]|matches[password]|htmlentities',
                'errors' => array(
                    'matches[confirm_password]' => 'The Password field does not match the re-entered password',
                )
            );
        }

        $this->form_validation->set_rules($validation_rules);
        return $this->form_validation->run();
    }

}
