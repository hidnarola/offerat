<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Storeregistration extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Common_model', '', TRUE);
        $this->load->model('Email_template_model', '', TRUE);
    }

    function index() {

        if ($this->input->post()) {

            $validate_fields = array(
                'store_name',
                'website',
                'facebook_page',
                'store_logo',
                'first_name',
                'last_name',
                'email_id',
                'telephone',
                'id_country',
                'terms_condition'
            );

            if ($this->_validate_form($validate_fields)) {

                $date = date('Y-m-d h:i:s');

                $do_store_image_has_error = false;
                $img_name = $image_name = '';
                if (isset($_FILES['store_logo'])) {

                    if (($_FILES['store_logo']['size']) > 0) {
                        $image_path = dirname($_SERVER["SCRIPT_FILENAME"]) . '/' . store_img_path;
                        if (!file_exists($image_path)) {
                            $this->Common_model->created_directory($image_path);
                        }
                        $supported_files = 'gif|jpg|png|jpeg';
                        $img_name = $this->Common_model->upload_image('store_logo', $image_path, $supported_files);

                        if (empty($img_name)) {
                            $do_store_image_has_error = true;
                            $this->data['image_errors'] = $this->upload->display_errors();
                        } else {
                            $image_name = $img_name;
                        }
                    } else {
                        if (!empty($_FILES['store_logo']['tmp_name'])) {
                            $do_store_image_has_error = true;
                            $this->data['image_errors'] = 'Invalid File';
                        }
                    }
                }

                $this->Common_model->master_update(
                        tbl_user, array('is_delete' => IS_DELETED_STATUS), array(
                    'email_id' => $this->input->post('email_id', TRUE),
                    'user_type' => STORE_OR_MALL_ADMIN_USER_TYPE,
                    'status' => NOT_VERIFIED_STATUS
                        )
                );

                $new_user_status = false;
                $select_user = array(
                    'table' => tbl_user,
                    'where' => array('email_id' => $this->input->post('email_id', TRUE), 'is_delete' => IS_NOT_DELETED_STATUS)
                );

                $user_data = $this->Common_model->master_single_select($select_user);
                if (isset($user_data) && sizeof($user_data) > 0) {
                    $user_id = $user_data['id_user'];
                    $email_id = $user_data['email_id'];
                } else {
                    $in_user_data = array(
                        'user_type' => STORE_OR_MALL_ADMIN_USER_TYPE,
                        'email_id' => $this->input->post('email_id', TRUE),
                        'first_name' => $this->input->post('first_name', TRUE),
                        'last_name' => $this->input->post('last_name', TRUE),
                        'mobile' => $this->input->post('telephone', TRUE),
                        'status' => NOT_VERIFIED_STATUS,
                        'created_date' => $date,
                        'is_testdata' => (ENVIRONMENT !== 'production') ? 1 : 0,
                        'is_delete' => IS_NOT_DELETED_STATUS
                    );
//                    echo 'in_user_data';
//                    pr($in_user_data);
//                    $user_id = 1;
                    $email_id = $this->input->post('email_id', TRUE);
                    $user_id = $this->Common_model->master_save(tbl_user, $in_user_data);

                    $new_user_status = true;
                }

                $in_store_data = array(
                    'store_name' => $this->input->post('store_name', TRUE),
                    'store_logo' => $image_name,
                    'id_users' => $user_id,
                    'website' => $this->input->post('website', TRUE),
                    'facebook_page' => $this->input->post('facebook_page', TRUE),
                    'telephone' => $this->input->post('telephone', TRUE),
                    'status' => NOT_VERIFIED_STATUS,
                    'created_date' => $date,
                    'is_testdata' => (ENVIRONMENT !== 'production') ? 1 : 0,
                    'is_delete' => IS_NOT_DELETED_STATUS
                );
                $store_id = $this->Common_model->master_save(tbl_store, $in_store_data);
//                $store_id = 1;                
//                echo 'in_store_data';
//                pr($in_store_data);
//                if ($this->input->post('location_count', TRUE) > 0) {
                $location_count = $this->input->post('location_count', TRUE);

                for ($i = 0; $i <= $location_count; $i++) {

                    if ($this->input->post('place_id_' . $i, TRUE) != '') {

                        $in_place_data = array(
                            'id_google' => $this->input->post('place_id_' . $i, TRUE),
                            'street' => $this->input->post('address_' . $i, TRUE),
                            'street1' => $this->input->post('street1_' . $i, TRUE),
                            'city' => $this->input->post('city_' . $i, TRUE),
                            'state' => $this->input->post('state_' . $i, TRUE),
                            'id_country' => $this->input->post('id_country', TRUE),
                            'latitude' => $this->input->post('latitude_' . $i, TRUE),
                            'longitude' => $this->input->post('longitude_' . $i, TRUE),
                            'created_date' => $date,
                            'is_testdata' => (ENVIRONMENT !== 'production') ? 1 : 0,
                            'is_delete' => IS_NOT_DELETED_STATUS
                        );
//                            echo 'in_place_data';
//                            pr($in_place_data);
//                            $place_id = 1;
                        $place_id = $this->Common_model->master_save(tbl_place, $in_place_data);

                        $in_store_location_data = array(
                            'id_store' => $store_id,
                            'id_place' => $place_id,
                            'id_location' => $this->input->post('mall_' . $i, TRUE),
                            'location_type' => ($this->input->post('mall_' . $i, TRUE) == 0) ? STORE_LOCATION_TYPE : MALL_LOCATION_TYPE,
                            'contact_number' => $this->input->post('telephone', TRUE),
                            'created_date' => $date,
                            'is_testdata' => (ENVIRONMENT !== 'production') ? 1 : 0,
                            'is_delete' => IS_NOT_DELETED_STATUS
                        );
//                            echo 'in_store_location_data';                            
                        $this->Common_model->master_save(tbl_store_location, $in_store_location_data);
                    }
                }
//                }

                $category_count = $this->input->post('category_count', TRUE);
                for ($i = 0; $i <= $category_count; $i++) {
                    if ($this->input->post('category_' . $i, TRUE) > 0) {
                        $in_category_data = array(
                            'id_store' => $store_id,
                            'id_category' => $this->input->post('category_' . $i, TRUE),
                            'id_sub_category' => $this->input->post('sub_category_' . $i, TRUE),
                            'created_date' => $date,
                            'contact_number' => $this->input->post('telephone', TRUE),
                            'is_testdata' => (ENVIRONMENT !== 'production') ? 1 : 0,
                            'is_delete' => IS_NOT_DELETED_STATUS
                        );
//                        echo 'in_category_data';
//                        pr($in_category_data);
                        $this->Common_model->master_save(tbl_store_category, $in_category_data);
                    }
                }

                //Send verification Email for new user
                if ($new_user_status == true) {
                    $verification_code = md5(time());
                    $verification_link = SITEURL . 'account-verification?verification=' . $verification_code;
                    $subject = 'Complete your registration';
                    $content = $this->Email_template_model->account_verification_format($verification_link);
                    $response = $this->Email_template_model->send_email(NULL, $email_id, $subject, $content);

                    if (isset($response) && $response == 'yes') {

                        $in_veri_data = array(
                            'id_user' => $user_id,
                            'verification_code' => $verification_code,
                            'purpose' => VERIFICATION_ACCOUNT,
                            'email_id' => $email_id,
                            'link_url' => $verification_link,
                            'status' => 0
                        );
                        $this->Common_model->master_save(tbl_verification, $in_veri_data);

                        $this->session->set_flashdata('success_msg', 'Verification Email Sent. Please click on link in Email for Verification.');
                        redirect('/');
                    } else {
                        $this->Common_model->master_delete(tbl_store, array('id_store' => $store_id));
                        $this->session->set_flashdata('error_msg', 'Unable to send Email for Account Verification. Please try again later.');
                        redirect('store-registration');
                    }
                } else {

                    //to send email to Country Admin
                    $country_admin_data = array(
                        'table' => tbl_country . ' country',
                        'fields' => array('user.email_id'),
                        'where' => array(
                            'country.id_country' => $this->input->post('id_country', TRUE),
                            'user.is_delete' => IS_NOT_DELETED_STATUS,
                            'country.is_delete' => IS_NOT_DELETED_STATUS,
                            'user.status' => ACTIVE_STATUS,
                            'country.status' => ACTIVE_STATUS,
                        ),
                        'join' => array(
                            array(
                                'table' => tbl_user . ' as user',
                                'condition' => 'user.id_user = country.id_users',
                                'join' => 'left'
                            )
                        )
                    );

                    $country_admin_details = $this->Common_model->master_single_select($country_admin_data);

                    if (isset($country_admin_details) && sizeof($country_admin_details) > 0) {

                        $country_admin_email_id = $country_admin_details['email_id'];
                        $subject = 'Add new Store - ' . $this->input->post('store_name', TRUE);
                        $content = ' ';
                        $response = $this->Email_template_model->send_email(NULL, $country_admin_email_id, $subject, $content);

                        if (isset($response) && $response == 'yes') {
                            $this->session->set_flashdata('success_msg', 'Thank you for Your Store Registration. Please allow 1-2 business days for store activation.');
                            redirect('/');
                        } else {
                            $this->Common_model->master_delete(tbl_store, array('id_store' => $store_id));
                            $this->session->set_flashdata('error_msg', 'Unable to send Email for Account Verification. Please try again later.');
                            redirect('store-registration');
                        }
                    }
                }
            }
        }
        $this->data['title'] = $this->data['page_header'] = 'Store Registration';
        $this->data['sub_header'] = 'Add New Store';

        $select_category = array(
            'table' => tbl_category,
            'where' => array('status' => ACTIVE_STATUS),
            'order_by' => array('sort_order' => 'ASC')
        );
        $this->data['category_list'] = $this->Common_model->master_select($select_category);

        $select_country = array(
            'table' => tbl_country,
            'where' => array('status' => ACTIVE_STATUS)
        );
        $this->data['country_list'] = $this->Common_model->master_select($select_country);

        $this->template->load('front', 'Registration/store', $this->data);
    }

    /**
     * function to load sub categories using ajax
     */
    public function show_sub_category() {

        $category_id = $this->input->post('category_id', TRUE);

        $select_sub_category = array(
            'table' => tbl_sub_category,
            'fields' => array('id_sub_category', 'sub_category_name'),
            'where' => array(
                'id_category' => $category_id,
                'status' => ACTIVE_STATUS
            ),
            'order_by' => array('sort_order' => 'ASC')
        );
        $sub_category_list = $this->Common_model->master_select($select_sub_category);
        $load_data['sub_category_list'] = $sub_category_list;

        echo $this->load->view('Registration/sub_category', $load_data, TRUE);
    }

    public function show_mall() {

        $country_id = $this->input->post('country_id', TRUE);

        $select_mall = array(
            'table' => tbl_mall,
            'fields' => array('id_mall', 'mall_name'),
            'where' => array(
                'id_country' => $country_id,
                'status' => ACTIVE_STATUS
            )
        );
        $mall_list = $this->Common_model->master_select($select_mall);

        $load_data['mall_list'] = $mall_list;
        echo $this->load->view('Registration/mall', $load_data, TRUE);
    }

    /*
     * Account Verification
     */

    function verification() {
        $verification_code = $this->input->get('verification', TRUE);
        if (isset($verification_code) && !empty($verification_code)) {
            $verification_code_arr = array(
                'table' => tbl_verification,
                'where' => array(
                    'verification_code' => $verification_code,
                    'purpose' => VERIFICATION_ACCOUNT
                )
            );
            $verified_data = $this->Common_model->master_single_select($verification_code_arr);
            if (isset($verified_data) && sizeof($verified_data) > 0) {
                if ($verified_data['status'] == 0) {

                    $user_id = $verified_data['id_user'];
                    $user_arr = array(
                        'table' => tbl_user . ' user',
                        'fields' => array('user.status', 'place.id_country', 'store.store_name'),
                        'where' => array('user.id_user' => $user_id),
                        'join' => array(
                            array(
                                'table' => tbl_store . ' as store',
                                'condition' => 'store.id_users = user.id_user',
                                'join' => 'left'
                            ),
                            array(
                                'table' => tbl_store_location . ' as store_location',
                                'condition' => 'store_location.id_store = store.id_store',
                                'join' => 'left'
                            ),
                            array(
                                'table' => tbl_place . ' as place',
                                'condition' => 'store_location.id_place = place.id_place',
                                'join' => 'left'
                            )
                        )
                    );
                    $user = $this->Common_model->master_single_select($user_arr);

                    if (isset($user) && sizeof($user) > 0) {

                        if ($user['status'] == ACTIVE_STATUS) {
                            $this->session->set_flashdata('error_msg', 'Your Account is already verified.');
                            redirect('/');
                        } else {
                            if ($user['status'] == NOT_VERIFIED_STATUS) {
                                $date = date('Y-m-d h:i:s');
                                $up_data = array('status' => ACTIVE_STATUS);
                                $wh_data = array('id_user' => $user_id, 'status' => NOT_VERIFIED_STATUS);
                                $result = $this->Common_model->master_update(tbl_user, $up_data, $wh_data);

                                $update_data = array('status' => 1, 'modified_date' => $date);
                                $where = array(
                                    'verification_code' => $verification_code,
                                    'id_user' => $user_id,
                                    'status' => 0
                                );
                                $this->Common_model->master_update(tbl_verification, $update_data, $where);

                                if ($result) {

                                    //to send email to Country Admin
                                    $country_admin_data = array(
                                        'table' => tbl_country . ' country',
                                        'fields' => array('user.email_id'),
                                        'where' => array(
                                            'country.id_country' => $user['id_country'],
                                            'country.is_delete' => IS_NOT_DELETED_STATUS,
                                            'country.status' => ACTIVE_STATUS
                                        ),
                                        'join' => array(
                                            array(
                                                'table' => tbl_user . ' as user',
                                                'condition' => 'user.id_user = country.id_users',
                                                'join' => 'left'
                                            )
                                        )
                                    );

                                    $country_admin_details = $this->Common_model->master_single_select($country_admin_data);

                                    if (isset($country_admin_details) && sizeof($country_admin_details) > 0) {

                                        $country_admin_email_id = $country_admin_details['email_id'];
                                        $subject = 'Add new Store - ' . $user['store_name'];
                                        $content = ' ';
                                        $response = $this->Email_template_model->send_email(NULL, $country_admin_email_id, $subject, $content);

                                        if (isset($response) && $response == 'yes') {
                                            $this->session->set_flashdata('success_msg', 'Thank you for Your Store Registration. Please allow 1-2 business days for store activation.');
                                        }
                                        redirect('/');
                                    }
                                } else {
                                    $message = 'Account Verification failed.';
                                    $this->session->set_flashdata('error_msg', $message);
                                }
                            } else {
                                $this->session->set_flashdata('error_msg', 'Invalid Request');
                            }
                            redirect('/');
                        }
                    } else {
                        $this->session->set_flashdata('error_msg', 'Invalid User');
                        redirect('/');
                    }
                } else {
                    if ($verified_data['status'] == 1)
                        $this->session->set_flashdata('error_msg', 'Your Account is already verified.');
                    else
                        $this->session->set_flashdata('error_msg', 'Invalid Request');

                    redirect('/');
                }
            } else {
                $this->session->set_flashdata('error_msg', 'Invalid Request');
                redirect('/');
            }
        }
    }

    public function _validate_form($validate_fields) {

        if (in_array('store_name', $validate_fields)) {
            $validation_rules[] = array(
                'field' => 'store_name',
                'label' => 'Store Name',
                'rules' => 'trim|required|min_length[2]|max_length[250]|htmlentities'
            );
        }
        if (in_array('website', $validate_fields)) {
            $validation_rules[] = array(
                'field' => 'website',
                'label' => 'Website',
                'rules' => 'trim|required|min_length[2]|max_length[250]|callback_custom_valid_url|htmlentities'
            );
        }
        if (in_array('facebook_page', $validate_fields)) {
            $validation_rules[] = array(
                'field' => 'facebook_page',
                'label' => 'Facebook Page URL',
                'rules' => 'trim|required|min_length[2]|max_length[250]|callback_custom_valid_url|htmlentities'
            );
        }
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
                'rules' => 'trim|required|min_length[2]|max_length[100]|htmlentities'
            );
        }
        if (in_array('telephone', $validate_fields)) {
            $validation_rules[] = array(
                'field' => 'telephone',
                'label' => 'Contact Number',
                'rules' => 'trim|required|min_length[8]|max_length[20]|htmlentities'
            );
        }
        if (in_array('id_country', $validate_fields)) {
            $validation_rules[] = array(
                'field' => 'id_country',
                'label' => 'Country',
                'rules' => 'trim|required|htmlentities'
            );
        }
        if (in_array('terms_condition', $validate_fields)) {
            $validation_rules[] = array(
                'field' => 'terms_condition',
                'label' => 'Terms and Conditions',
                'rules' => 'trim|required|min_length[2]|max_length[255]|htmlentities'
            );
        }
        $this->form_validation->set_rules($validation_rules);
        return $this->form_validation->run();
    }

    function custom_valid_url($url) {

        if (isset($url) && !empty($url)) {
            if (preg_match('%^((https?://)|(www\.))([a-z0-9-].?)+(:[0-9]+)?(/.*)?$%i', $url)) {
                return TRUE;
            } else {
                $this->form_validation->set_message('custom_valid_url', 'The {field} not a valid url.');
                return FALSE;
            }
        }
    }

    function location() {
        $location_name = str_replace(' ', '+', $this->input->post('country_name', TRUE));
        $url = "https://maps.google.com/maps/api/geocode/json?key=" . GOOGLE_API_KEY . "&address=" . $location_name . "&sensor=false";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);
        $response_a = json_decode($response);

//        pr($response_a, 1);

        $google_map_lat = @$response_a->results[0]->geometry->location->lat;
        $google_map_long = @$response_a->results[0]->geometry->location->lng;
//        pr(@$response_a->results[0]->geometry->bounds->northeast->lat);
//        pr(@$response_a->results[0]->geometry->bounds->southwest);

        $response = array();
        $response['latitude'] = $google_map_lat;
        $response['longitude'] = $google_map_long;
        $response['northEastLatitude'] = @$response_a->results[0]->geometry->bounds->northeast->lat;
        $response['northEastLongitude'] = @$response_a->results[0]->geometry->bounds->northeast->lng;
        $response['southWestLatitude'] = @$response_a->results[0]->geometry->bounds->southwest->lat;
        $response['southWestLongitude'] = @$response_a->results[0]->geometry->bounds->southwest->lng;

        echo json_encode($response);
    }

}
