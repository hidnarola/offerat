<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    public $fb;

    function __construct() {
        parent::__construct();

        require_once 'vendor/facebook/php-sdk-v4/src/Facebook/autoload.php';

        // now we only need to build the object...
        $this->fb = new Facebook\Facebook([
            'app_id' => $this->config->item('facebook_app_id'),
            'app_secret' => $this->config->item('facebook_app_secret'),
            'default_graph_version' => 'v2.5'
        ]);

        $this->load->model('Common_model', '', TRUE);
        $this->load->model('Email_template_model', '', TRUE);
        $this->load->helper('html');
    }

    public function index() {
        $helper = $this->fb->getRedirectLoginHelper();
        $permissions = ['public_profile', 'email'];

        $img = array(
            'src' => 'assets/images/icons/fb-sign-in-button.png',
            'alt' => 'Login With Facebook',
            'width' => '170px'
        );

        $this->data['facebook_url'] = anchor($helper->getLoginUrl(base_url() . '/login/facebook', $permissions), img($img));

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
                        'email_id' => $email,
                        'password' => md5($password)
                    )
                );

                $user = $this->Common_model->master_single_select($select_data_user);

                if (isset($user) && sizeof($user) > 0) {

                    if ($user['status'] == ACTIVE_STATUS && $user['is_delete'] == IS_NOT_DELETED_STATUS && in_array($user['user_type'], array(SUPER_ADMIN_USER_TYPE, COUNTRY_ADMIN_USER_TYPE, STORE_OR_MALL_ADMIN_USER_TYPE))) {
                        //manage session
                        $session_user_type = '';
                        if ($user['user_type'] == SUPER_ADMIN_USER_TYPE)
                            $session_user_type = SUPER_ADMIN_USER_TYPE;
                        elseif ($user['user_type'] == COUNTRY_ADMIN_USER_TYPE) {
                            $session_user_type = COUNTRY_ADMIN_USER_TYPE;
                            $select_country = array(
                                'table' => tbl_country,
                                'fields' => array('id_country', 'country_name', 'timezone'),
                                'where' => array('is_delete' => IS_NOT_DELETED_STATUS, 'id_users' => $user['id_user'], 'status' => ACTIVE_STATUS)
                            );

                            $country_details = $this->Common_model->master_single_select($select_country);
                            if (isset($country_details) && sizeof($country_details) > 0) {
                                $this->session->set_userdata('loggedin_user_country_data', $country_details);
                            } else {
                                $this->session->set_flashdata('error_msg', 'Invalid Request for Login');
                                redirect('login');
                            }
                        } elseif ($user['user_type'] == STORE_OR_MALL_ADMIN_USER_TYPE) {
                            $session_user_type = STORE_OR_MALL_ADMIN_USER_TYPE;

                            $select_country = array(
                                'table' => tbl_country . ' country',
                                'fields' => array('country.id_country', 'country.country_name', 'country.timezone'),
                                'where' => array(
                                    'country.is_delete' => IS_NOT_DELETED_STATUS,
                                    'country.status' => ACTIVE_STATUS
                                ),
                                'where_with_sign' => array(
                                    '(country.id_country = mall.id_country OR country.id_country = store.id_country)',
                                    '(mall.id_users = ' . $user['id_user'] . ' OR store.id_users = ' . $user['id_user'] . ')'
                                ),
                                'join' => array(
                                    array(
                                        'table' => tbl_mall . ' as mall',
                                        'condition' => 'mall.id_country = country.id_country',
                                        'join_type' => 'left',
                                    ),
                                    array(
                                        'table' => tbl_store . ' as store',
                                        'condition' => 'store.id_country = country.id_country',
                                        'join_type' => 'left',
                                    )
                                )
                            );

                            $country_details = $this->Common_model->master_single_select($select_country);
                            if (isset($country_details) && sizeof($country_details) > 0) {
                                $this->session->set_userdata('loggedin_user_country_data', $country_details);
                            } else {
                                $this->session->set_flashdata('error_msg', 'Invalid Request for Login');
                                redirect('login');
                            }
                        }

//                        $session_user_data = array(
//                            'user_id' => $user['id_user'],
//                            'email_id' => $user['email_id']
//                        );
                        
                        $session_user_data = $user;

                        $this->session->set_userdata('loggedin_user_type', $session_user_type);
                        $this->session->set_userdata('loggedin_user_data', $session_user_data);

                        //update login time
                        $update_user_data = array('last_login_at' => $date);
                        $where_user_data = array('id_user' => $user['id_user']);

                        $this->Common_model->master_update(tbl_user, $update_user_data, $where_user_data);

//                        $this->session->set_flashdata('success_msg', 'Welcome Super Admin');

                        if ($user['user_type'] == SUPER_ADMIN_USER_TYPE)
                            redirect('super-admin/dashboard');
                        elseif ($user['user_type'] == COUNTRY_ADMIN_USER_TYPE)
                            redirect('country-admin/dashboard');
                        elseif ($user['user_type'] == STORE_OR_MALL_ADMIN_USER_TYPE)
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

                    if ($user['status'] == ACTIVE_STATUS && $user['is_delete'] == IS_NOT_DELETED_STATUS && in_array($user['user_type'], array(SUPER_ADMIN_USER_TYPE, COUNTRY_ADMIN_USER_TYPE, STORE_OR_MALL_ADMIN_USER_TYPE))) {

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

                            $this->session->set_flashdata('success_msg', 'Reset Password Email Sent. Please click on link in Email for Reset Password.');
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

    function change_verify() {

        $verification_code = $this->input->get('verification', TRUE);

        if (isset($verification_code) && !empty($verification_code)) {

            $verification_code_arr = array(
                'table' => tbl_verification,
                'where' => array(
                    'verification_code' => $verification_code,
                    'status' => 0,
                    'purpose' => VERIFICATION_CHANGE_EMAIL
                )
            );
            $verified_data = $this->Common_model->master_single_select($verification_code_arr);
            if (isset($verified_data) && sizeof($verified_data) > 0) {

                $date = date('Y-m-d h:i:s');
                $user_id = $verified_data['id_user'];
                $user_arr = array(
                    'table' => tbl_user,
                    'where' => array(
                        'id_user' => $user_id,
                        'user_type' => STORE_OR_MALL_ADMIN_USER_TYPE,
                        'status' => ACTIVE_STATUS
                    )
                );
                $user = $this->Common_model->master_single_select($user_arr);

                if (isset($user) && sizeof($user) > 0) {

                    $up_data = array('email_id' => $verified_data['email_id']);
                    $wh_data = array('id_user' => $user_id);
                    $result = $this->Common_model->master_update(tbl_user, $up_data, $wh_data);

                    //update Verification
                    $update_data = array('status' => 1, 'modified_date' => $date);
                    $where = array(
                        'verification_code' => $verification_code,
                        'id_user' => $user_id,
                        'status' => 0,
                        'purpose' => VERIFICATION_CHANGE_EMAIL
                    );
                    $is_updated = $this->Common_model->master_update(tbl_verification, $update_data, $where);

                    if ($is_updated) {
                        $message = 'Email updated successfully!';
                        $this->session->set_flashdata('success_msg', $message);
                    } else {
                        $message = 'Email not updated';
                        $this->session->set_flashdata('error_msg', $message);
                    }
                    redirect('login');
                } else {
                    $this->session->set_flashdata('error_msg', 'Invalid Request');
                    redirect('/');
                }
            } else {
                $this->session->set_flashdata('error_msg', 'Invalid Request');
                redirect('/');
            }
        }
    }

    public function facebook() {
        $helper = $this->fb->getRedirectLoginHelper();
        $_SESSION['FBRLH_state'] = $this->input->get('state');

        try {
            $accessToken = $helper->getAccessToken();
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error  
            // echo 'Graph returned an error: ' . $e->getMessage();
            $this->session->set_flashdata('error_msg', 'Something went wrong.');
            redirect('login');
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues  
            // echo 'Facebook SDK returned an error: ' . $e->getMessage();    
            $this->session->set_flashdata('error_msg', 'Something went wrong.');
            redirect('login');
        }

        try {
            $response = $this->fb->get('/me?fields=id,name,email,first_name,last_name,birthday,location,gender', $accessToken);
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            // echo 'ERROR: Graph ' . $e->getMessage();
            $this->session->set_flashdata('error_msg', 'Something went wrong.');
            redirect('login');
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            $this->session->set_flashdata('error_msg', 'Your account validation has been failed.');
            redirect('login');
        }

        $fbUserData = $response->getGraphUser();

        if (!empty($fbUserData)) {
            $fb_user_email = $fbUserData->getProperty('email');
            $fb_user_id = $fbUserData->getProperty('id');

            $email = $fbUserData->getProperty('email');

            $select_data_user = array(
                'table' => tbl_user,
                'where' => array(
                    'email_id' => $email,
                    'status' => ACTIVE_STATUS,
                    'is_delete' => IS_NOT_DELETED_STATUS,
                )
            );

            $user = $this->Common_model->master_single_select($select_data_user);

            if (isset($user) && sizeof($user) > 0) {
                if (in_array($user['user_type'], array(SUPER_ADMIN_USER_TYPE, COUNTRY_ADMIN_USER_TYPE, STORE_OR_MALL_ADMIN_USER_TYPE))) {
                    //manage session
                    $session_user_type = '';
                    if ($user['user_type'] == SUPER_ADMIN_USER_TYPE)
                        $session_user_type = SUPER_ADMIN_USER_TYPE;
                    elseif ($user['user_type'] == COUNTRY_ADMIN_USER_TYPE) {
                        $session_user_type = COUNTRY_ADMIN_USER_TYPE;
                        $select_country = array(
                            'table' => tbl_country,
                            'fields' => array('id_country', 'country_name', 'timezone'),
                            'where' => array('is_delete' => IS_NOT_DELETED_STATUS, 'id_users' => $user['id_user'], 'status' => ACTIVE_STATUS)
                        );

                        $country_details = $this->Common_model->master_single_select($select_country);
                        if (isset($country_details) && sizeof($country_details) > 0) {
                            $this->session->set_userdata('loggedin_user_country_data', $country_details);
                        } else {
                            $this->session->set_flashdata('error_msg', 'Invalid Request for Login');
                            redirect('login');
                        }
                    } elseif ($user['user_type'] == STORE_OR_MALL_ADMIN_USER_TYPE) {
                        $session_user_type = STORE_OR_MALL_ADMIN_USER_TYPE;

                        $select_country = array(
                            'table' => tbl_country . ' country',
                            'fields' => array('country.id_country', 'country.country_name', 'country.timezone'),
                            'where' => array(
                                'country.is_delete' => IS_NOT_DELETED_STATUS,
                                'country.status' => ACTIVE_STATUS
                            ),
                            'where_with_sign' => array(
                                '(country.id_country = mall.id_country OR country.id_country = store.id_country)',
                                '(mall.id_users = ' . $user['id_user'] . ' OR store.id_users = ' . $user['id_user'] . ')'
                            ),
                            'join' => array(
                                array(
                                    'table' => tbl_mall . ' as mall',
                                    'condition' => 'mall.id_country = country.id_country',
                                    'join_type' => 'left',
                                ),
                                array(
                                    'table' => tbl_store . ' as store',
                                    'condition' => 'store.id_country = country.id_country',
                                    'join_type' => 'left',
                                )
                            )
                        );

                        $country_details = $this->Common_model->master_single_select($select_country);
                        if (isset($country_details) && sizeof($country_details) > 0) {
                            $this->session->set_userdata('loggedin_user_country_data', $country_details);
                        } else {
                            $this->session->set_flashdata('error_msg', 'Invalid Request for Login');
                            redirect('login');
                        }
                    }

                    $session_user_data = array(
                        'user_id' => $user['id_user'],
                        'email_id' => $user['email_id']
                    );

                    $this->session->set_userdata('loggedin_user_type', $session_user_type);
                    $this->session->set_userdata('loggedin_user_data', $session_user_data);

                    $this->saveFacebookId($fb_user_id, $user['id_user']);

                    //update login time
                    $update_user_data = array('last_login_at' => $date);
                    $where_user_data = array('id_user' => $user['id_user']);

                    $this->Common_model->master_update(tbl_user, $update_user_data, $where_user_data);

                    if ($user['user_type'] == SUPER_ADMIN_USER_TYPE)
                        redirect('super-admin/dashboard');
                    elseif ($user['user_type'] == COUNTRY_ADMIN_USER_TYPE)
                        redirect('country-admin/dashboard');
                    elseif ($user['user_type'] == STORE_OR_MALL_ADMIN_USER_TYPE)
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

    function saveFacebookId($facebook_id, $user_id) {
        $update_data = array('facebook_id' => $facebook_id);
        $where_data = array('id_user' => $user_id);
        $is_updated = $this->Common_model->master_update(tbl_user, $update_data, $where_data);

        if ($is_updated) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}
