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
                'category_count',
                'location_count',
                'terms_condition'
            );

            if ($this->_validate_form($validate_fields)) {

                $date = date('Y-m-d h:i:s');

                $do_store_image_has_error = false;
                $img_name = $image_name = '';
                if (isset($_FILES['store_logo'])) {

                    if (($_FILES['store_logo']['size']) > 0) {
//                        $image_path = dirname($_SERVER["SCRIPT_FILENAME"]) . '/' . store_img_path;
                        $image_path = $_SERVER['DOCUMENT_ROOT'] . store_img_path;
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
                            'street' => $this->input->post('street_' . $i, TRUE),
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
                            'id_sub_category' => ($this->input->post('sub_category_' . $i, TRUE) > 0 ) ? $this->input->post('sub_category_' . $i, TRUE) : 0,
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

        echo $this->load->view('Registration/select_sub_category', $load_data, TRUE);
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
        echo $this->load->view('Registration/select_mall', $load_data, TRUE);
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
                'rules' => 'trim|required|min_length[2]|max_length[250]|callback_check_store_name|htmlentities'
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
        if (in_array('store_logo', $validate_fields)) {
            $validation_rules[] = array(
                'field' => 'store_logo',
                'label' => 'Store Logo',
                'rules' => 'trim|callback_custom_store_logo[store_logo]|htmlentities'
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
        if (in_array('category_count', $validate_fields)) {
            $validation_rules[] = array(
                'field' => 'category_count',
                'label' => 'Category selection',
                'rules' => 'trim|required|htmlentities|greater_than[0]',
                'errors' => array(
                    'greater_than' => 'Category Selection is required.'
                )
            );
        }
        if (in_array('location_count', $validate_fields)) {
            $validation_rules[] = array(
                'field' => 'location_count',
                'label' => 'Branch Location',
                'rules' => 'trim|required|htmlentities|greater_than[0]',
                'errors' => array(
                    'greater_than' => 'Branch Location is required.'
                )
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

    function custom_store_logo($image, $image_control) {

        if ($_FILES[$image_control]['name'] != '') {
            if ($_FILES[$image_control]['type'] != 'image/jpeg' && $_FILES[$image_control]['type'] != 'image/jpg' && $_FILES[$image_control]['type'] != 'image/gif' && $_FILES[$image_control]['type'] != 'image/png') {
                $this->form_validation->set_message('custom_store_logo', 'The {field} contain invalid image type.');
                return FALSE;
            }
            if ($_FILES[$image_control]['error'] > 0) {
                $this->form_validation->set_message('custom_store_logo', 'The {field} contain invalid image.');
                return FALSE;
            }
            if ($_FILES[$image_control]['size'] <= 0) {
                $this->form_validation->set_message('custom_store_logo', 'The {field} contain invalid image size.');
                return FALSE;
            }
        } else {
            $this->form_validation->set_message('custom_store_logo', 'The {field} field is required.');
            return FALSE;
        }
        return TRUE;
    }

    function check_store_name($store_name) {

        $select_data = array(
            'table' => tbl_store,
            'where' => array(
                'is_delete' => IS_NOT_DELETED_STATUS,
                'store_name' => $store_name
            )
        );

        $check_store_name = $this->Common_model->master_single_select($select_data);

        if (isset($check_store_name) && sizeof($check_store_name) > 0) {
            $this->form_validation->set_message('check_store_name', 'The {field} already exists.');
            return FALSE;
        } else
            return TRUE;
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
        $location_name = 'Lebanon'; //str_replace(' ', '+', $this->input->post('country_name', TRUE));
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

        pr($response_a, 1);

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

    public function create_kml() {

        $name = 'IN';
        $file_name = str_replace(' ', '_', $name) . '.kml';
        $file_path = './assets/images/countries_kmls/' . $file_name;
        if (!file_exists($file_path)) {
            $kml = array('<?xml version="1.0" encoding="UTF-8"?>');
            $kml[] = '<kml xmlns="http://www.opengis.net/kml/2.2" xmlns:gx="http://www.google.com/kml/ext/2.2">';
            $kml[] = ' <Document>';
            $kml[] = ' <Style id="transBluePoly">';
            $kml[] = ' <LineStyle>';
            $kml[] = ' <color>ffff0000</color>';
            $kml[] = ' <width>1.5</width>';
            $kml[] = ' </LineStyle>';
            $kml[] = ' <PolyStyle>';
//                $kml[] = ' <color>7dff0000</color>';
            $kml[] = ' </PolyStyle>';
            $kml[] = ' </Style>';
            // Iterates through the rows, printing a node for each row.
//            $bountries = $this->Admin_model->getBountries("boundaries", array("country_code" => $name));
            if (!empty($bountries)) {
                $kml[] = ' <Placemark>';
                $kml[] = ' <styleUrl>#transBluePoly</styleUrl>';
                $kml[] = '<MultiGeometry><Polygon><outerBoundaryIs><LinearRing><coordinates>97.3488722283,28.2227731066,0 97.3611004192,27.940827173,0 96.8863822791,27.5998540331,0 97.1354092937,27.0872180069,0 96.7258912963,27.3656911503,0 96.1912272933,27.2698640362,0 95.1449822104,26.616173152,0 95.1649822696,26.0368000773,0 94.6285914268,25.4018730893,0 94.5784723396,25.2091640823,0 94.7351820782,25.0320092054,0 94.1477633822,23.8515271046,0 93.3375181599,24.0718362101,0 93.3878364012,23.2314540225,0 93.3054634152,23.0176361981,0 93.1395721398,23.0470821594,0 93.0927541813,22.714436105,0 93.197891243,22.2647181646,0 92.9244272925,22.0050000325,0 92.7063271538,22.1545090457,0 92.6008182709,21.9822181872,0 92.2780452603,23.7108271364,0 91.9586002006,23.7277731654,0 91.8181721413,23.0902731007,0 91.6115091095,22.9445822121,0 91.4260911848,23.2619450394,0 91.3442913533,23.0981911494,0 91.1619272913,23.6315271237,0 91.3819822574,24.1051361704,0 91.8825721476,24.1515541445,0 92.117200226,24.3900001798,0 92.2483823755,24.8945822028,0 92.4916273856,24.8775092718,0 92.4088723524,25.0255539665,0 92.0388822371,25.1874911628,0 90.412491268,25.148882105,0 89.850536291,25.288954101,0 89.7339363062,26.1563181379,0 89.6017181534,26.22747312,0 89.5482183093,26.0156272222,0 89.3199093478,26.0248270315,0 89.0707361528,26.385327156,0 88.9466911939,26.442682005,0 89.044009281,26.2746090879,0 88.8571453104,26.2401361641,0 88.4130722465,26.6261360498,0 88.3355912745,26.4829999653,0 88.5230631007,26.3673181341,0 88.1828911031,26.1505540707,0 88.1105363354,25.8355541019,0 89.0086632981,25.2902730773,0 88.9330452879,25.1644360675,0 88.4542272236,25.1884000964,0 88.3972183796,24.939718081,0 88.1411002749,24.9164180664,0 88.043872377,24.6852002492,0 88.1304002725,24.506527242,0 88.7420822313,24.2416731826,0 88.56596319,23.6466641703,0 88.7863361656,23.4928451825,0 88.7271362921,23.2470822487,0 88.9828000976,23.2061361486,0 88.8631093698,22.9682540478,0 89.0630001591,22.1154731814,0 89.0843632834,21.6252002406,0 88.7120091361,21.5622910396,0 88.6767821762,22.1971451051,0 88.5722182691,21.5600000978,0 88.5002633182,21.9480451679,0 88.4504002142,21.6113821702,0 88.3063364207,21.6105820338,0 88.2612092597,21.7969181121,0 88.257491215,21.5487452135,0 88.1992542545,22.151909147,0 87.9061003753,22.4204091667,0 88.1665182287,22.0897181074,0 87.7963722099,21.6988821988,0 86.9633181527,21.3819361197,0 86.8280451315,21.1525001275,0 87.0255542869,20.6748270312,0 86.4212272805,19.9849271634,0 86.1963632812,20.075000101,0 86.2720822095,19.9106911613,0 85.4513822394,19.6602730861,0 85.5750092766,19.835482188,0 85.4348822953,19.8870090997,0 85.1284912116,19.5486911791,0 85.382491377,19.6124999259,0 84.7265821384,19.1240000929,0 84.115882371,18.3020820646,0 82.362072224,17.0983271067,0 82.3017002252,16.5830539661,0 81.7272632532,16.3108270169,0 81.3210182214,16.3670821602,0 81.013336157,15.7834359913,0 80.8826181973,16.0119451126,0 80.8252722331,15.7519450133,0 80.6847092252,15.9000001016,0 80.2794361589,15.6991641689,0 80.0490821806,15.055554032,0 80.3138002857,13.4571180512,0 80.1524821767,13.7180540739,0 80.0494273474,13.6205540995,0 80.3487362258,13.3426361733,0 80.1602632651,12.4730541172,0 79.7643003139,11.6562450265,0 79.8581092334,10.2858271552,0 79.3243543316,10.2799269659,0 78.9433091565,9.59832702632,0 79.0091543706,9.33166415213,0 79.4462272041,9.15992700221,0 78.9679001517,9.27332711169,0 78.4099912871,9.09694521394,0 77.9969362942,8.33832715096,0 77.5361002622,8.07194523816,0 77.2991453675,8.13305400635,0 76.5758181345,8.87694523304,0 76.6637362524,9.00381807844,0 76.5341451558,8.96500014363,0 76.4424721129,9.14332714584,0 76.2455272273,9.90221812358,0 76.3529002454,9.5263910185,0 76.4988722631,9.53041802007,0 75.7177541545,11.3652731243,0 75.1938721288,12.010136188,0 74.8552543037,12.7550000508,0 74.4119272292,14.4833270327,0 74.0979723161,14.787464055,0 73.7886002923,15.3989911162,0 73.9495001443,15.3985450313,0 73.4474821455,16.0586091794,0 72.8538631881,18.6605541038,0 73.0541822926,19.0047180667,0 72.7732182152,18.9459359472,0 72.7790183245,19.3105541007,0 73.0426271685,19.2110449842,0 72.75374521,19.3727731358,0 72.6641542319,19.8708269974,0 72.9344181393,20.7747180273,0 72.5648452751,21.3750641326,0 73.1271273139,21.7578451998,0 72.5460272319,21.6638820533,0 72.7226272268,21.9901271835,0 72.5016542745,21.9749271049,0 72.5806822107,22.198327121,0 72.9147722993,22.2711091982,0 72.1551913233,22.2812450984,0 72.3254091694,22.1515270998,0 72.0389002954,21.9390180258,0 72.1631822945,21.8373542572,0 71.9983092528,21.8538820292,0 72.289154253,21.610826953,0 72.1081002824,21.2040910769,0 70.8251271191,20.6959640118,0 70.0608091943,21.144436066,0 68.9459542104,22.2893001074,0 69.0713821833,22.4807540082,0 69.2203911322,22.2739541838,0 70.16998231,22.5508272219,0 70.509718281,23.0981911494,0 69.7102632504,22.7427731404,0 69.2158183011,22.8402731148,0 68.4330452695,23.4300000193,0 68.4085271968,23.6081181444,0 68.7413633527,23.8441641056,0 68.3295721832,23.5849270947,0 68.1442271811,23.6090911158,0 68.197800283,23.7666821277,0 68.2847092198,23.9393002156,0 68.7472092273,23.9699910571,0 68.7887452457,24.333609082,0 70.0000003306,24.1697182316,0 70.5599822076,24.435827062,0 70.7619361185,24.2358271403,0 71.1049822707,24.4193001282,0 70.675672253,25.6801360146,0 70.2848452292,25.705554136,0 70.08852731,25.9831912685,0 70.167972162,26.5562452264,0 69.5113092226,26.7487451887,0 69.5835362501,27.1779821162,0 70.3665092739,28.0187450098,0 70.5873541507,28.0031910474,0 70.8294362555,27.7063821554,0 71.896945221,27.961936158,0 72.3897092634,28.7850001601,0 72.9502631215,29.0400001189,0 73.3974913013,29.9427731706,0 73.9334001846,30.1360001792,0 73.8704091763,30.387409163,0 74.6975912074,31.0590911319,0 74.5226630669,31.1750820876,0 74.5992822117,31.8694270463,0 75.381291149,32.2142362481,0 75.0576273098,32.4751271761,0 74.7106822315,32.480682031,0 74.6419271559,32.7770731689,0 74.3633822634,32.775054136,0 74.3317913731,33.0023540841,0 74.0197181972,33.184154044,0 74.1823450566,33.5075000415,0 73.9912182176,33.7454091321,0 74.2945633177,33.9736002441,0 73.9162273414,34.0638820587,0 74.0215821649,34.2019360277,0 73.7999091561,34.3975641163,0 73.9365722321,34.6329730528,0 74.3808182984,34.7826359577,0 75.6613722768,34.5008271521,0 76.4500002354,34.7672092325,0 76.8699822007,34.6588820185,0 77.0424821038,35.0991540568,0 77.8239272743,35.5013269924,0 78.0718001009,35.4990269982,0 78.0230452522,35.2806911603,0 78.3370722497,34.61180003,0 78.9853541241,34.3500181057,0 78.7358091078,34.0683271496,0 78.8138632345,33.5204091783,0 78.9362361713,33.4286271706,0 79.3751181549,33.0994359691,0 79.5287363123,32.7566640728,0 78.9711003624,32.350827072,0 78.746227143,32.6390180264,0 78.4059542272,32.5561002166,0 78.4759360782,32.243045017,0 78.7707542681,31.9684731466,0 78.7682452292,31.3089541575,0 79.0808183018,31.4372909861,0 79.5542912264,30.9570820288,0 79.8630362838,30.9658270361,0 80.2542272498,30.7337452123,0 80.2070002544,30.5755180139,0 81.0253633499,30.2043540969,0 80.3750093014,29.7402000046,0 80.06137223,28.8299271612,0 81.1888721531,28.3691640518,0 81.2980362163,28.1638820223,0 81.9010543047,27.8549270821,0 82.7011002595,27.7111090459,0 82.7665183318,27.5034730428,0 83.3099722691,27.3362450215,0 83.4183182586,27.4727729812,0 83.8583093355,27.3522271316,0 84.1472182837,27.5113910914,0 84.6380452684,27.3111090349,0 84.6552632063,27.0403450631,0 85.3280452161,26.7361091343,0 85.6307542973,26.8659731457,0 85.8604720892,26.5728450827,0 86.0329092956,26.6631911027,0 86.7333820961,26.4202002319,0 87.0044362494,26.5344450696,0 87.2697182889,26.3752732308,0 88.0201913212,26.3683641956,0 88.1917821231,26.725964014,0 87.9949822444,27.1122911292,0 88.14279124,27.8660542262,0 88.6243542099,28.1168002015,0 88.8357543581,28.0080542276,0 88.7646362564,27.5424270997,0 88.9169272233,27.3208271811,0 88.7519361643,27.1486090776,0 88.8938722956,26.9755539572,0 89.6430541796,26.7152730641,0 90.3887362837,26.9034640576,0 90.708600271,26.7725001729,0 92.031236265,26.8519361401,0 92.1142182801,27.2930542478,0 91.9936632104,27.4755820922,0 91.675818256,27.4870820634,0 91.6577633012,27.76471819,0 92.5449822228,27.8619361972,0 92.7104451829,28.1419001459,0 93.2220542582,28.3193001096,0 93.3519363745,28.618753995,0 93.9617272083,28.6692001441,0 94.2345543018,29.0734821342,0 94.6475092147,29.3334641286,0 95.3877722508,29.0352730608,0 96.0831453337,29.4644362276,0 96.3917272793,29.2575641511,0 96.1488822536,29.0597182109,0 96.1753273258,28.9013822155,0 96.4708272998,29.0566641807,0 96.6137273499,28.79569111,0 96.3402722843,28.5250000608,0 96.4019272173,28.3511092646,0 96.65387214,28.4674909847,0 97.3488722283,28.2227731066,0</coordinates></LinearRing></outerBoundaryIs></Polygon><Polygon><outerBoundaryIs><LinearRing><coordinates>92.8744181758,12.3066641186,0 92.7180452221,12.3411090469,0 92.7363722535,12.8097181207,0 92.9182543561,12.9132641295,0 92.9916541795,12.5250001241,0 92.8744181758,12.3066641186,0</coordinates></LinearRing></outerBoundaryIs></Polygon><Polygon><outerBoundaryIs><LinearRing><coordinates>92.8857362597,12.8984539771,0 92.8406821889,13.3275001325,0 93.0449823622,13.5701361136,0 93.0452541035,13.0655540905,0 92.8857362597,12.8984539771,0</coordinates></LinearRing></outerBoundaryIs></Polygon><Polygon><outerBoundaryIs><LinearRing><coordinates>92.7540543812,12.067881969,0 92.7165183746,11.4918001324,0 92.5253273303,11.8554181573,0 92.6740821398,12.2128451408,0 92.7540543812,12.067881969,0</coordinates></LinearRing></outerBoundaryIs></Polygon><Polygon><outerBoundaryIs><LinearRing><coordinates>92.5604721475,10.7769731551,0 92.5074913109,10.5313911027,0 92.3592913833,10.5426360964,0 92.3569362361,10.7898640193,0 92.4980361888,10.9008270066,0 92.5604721475,10.7769731551,0</coordinates></LinearRing></outerBoundaryIs></Polygon><Polygon><outerBoundaryIs><LinearRing><coordinates>93.8680453585,7.18230009092,0 93.8270632163,6.74582706635,0 93.6436002867,7.11860919363,0 93.8680453585,7.18230009092,0</coordinates></LinearRing></outerBoundaryIs></Polygon></MultiGeometry>';
                $kml[] = ' </Placemark>';
            }

            // End XML file
            $kml[] = ' </Document>';
            $kml[] = '</kml>';
            $kmlOutput = join("\n", $kml);
            header('Content-type: application/vnd.google-earth.kml+xml');
            $file_name = $this->get_content($file_name, $kmlOutput);
        }
        echo $file_name;
        exit;
    }

    public function get_content($file_name, $kmlOutput) {
        $file_path = './assets/images/countries_kmls/' . $file_name;
        $file_handler = fopen($file_path, 'w+');
        fputs($file_handler, $kmlOutput);
        fclose($file_handler);
        return $file_name;
    }

}
