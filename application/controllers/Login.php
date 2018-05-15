<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Login
        extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Common_model', '', TRUE);
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

                    if ($user['status'] == ACTIVE_STATUS && $user['is_delete'] == IS_NOT_DELETED_STATUS && $user['user_type'] == SUPER_ADMIN_USER_TYPE) {
                        //manage session
                        $session_user_type = SUPER_ADMIN_USER_TYPE;
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
                        redirect('super-admin/dashboard');
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
        redirect('login');
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

    public function forgot_password() {

        $this->data['title'] = $this->data['page_header'] = 'Forgot Password';
        $this->login_template->load('index', 'Login/forgot_password', $this->data);
    }

    public function reset_password() {

        $this->data['title'] = $this->data['page_header'] = 'Reset Password';
        $this->login_template->load('index', 'Login/reset_password', $this->data);
    }

}
