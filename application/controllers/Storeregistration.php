<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Storeregistration
        extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Common_model', '', TRUE);
    }

    function index() {

        if ($this->input->post()) {
            pr($_POST);
            $date = date('Y-m-d h:i:s');

            $do_store_image_has_error = false;
            $img_name = '';
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

            if (!$do_store_image_has_error) {

                $select_user = array(
                    'table' => tbl_user,
                    'where' => array('email_id' => $this->input->post('email_id', TRUE))
                );
                $user_data = $this->Common_model->master_single_select($select_user);

                if (isset($user_data) && sizeof($user_data) > 0) {
                    $user_id = $user_data['id_user'];
                } else {
                    $in_user_data = array(
                        'user_type' => STORE_OR_MALL_ADMIN_USER_TYPE,
                        'email_id' => $this->input->post('email_id', TRUE),
                        'first_name' => $this->input->post('first_name', TRUE),
                        'last_name' => $this->input->post('last_name', TRUE),
                        'mobile' => $this->input->post('mobile', TRUE),
                        'status' => NOT_VERIFIED_STATUS,
                        'created_date' => $date,
                        'is_testdata' => (ENVIRONMENT !== 'production') ? 1 : 0,
                        'is_delete' => IS_NOT_DELETED_STATUS
                    );
                    echo 'in_user_data';
                    pr($in_user_data);
                    $user_id = 1;
//                $user_id = $this->Common_model->master_insert($in_user_data);
                }

                $in_store_data = array(
                    'store_name' => $this->input->post('store_name', TRUE),
                    'store_logo' => $this->input->post('store_logo', TRUE),
                    'id_users' => $user_id,
                    'website' => $this->input->post('website', TRUE),
                    'facebook_page' => $this->input->post('facebook_page', TRUE),
                    'telephone' => $this->input->post('telephone', TRUE),
                    'status' => NOT_VERIFIED_STATUS,
                    'created_date' => $date,
                    'is_testdata' => (ENVIRONMENT !== 'production') ? 1 : 0,
                    'is_delete' => IS_NOT_DELETED_STATUS
                );
//            $store_id = $this->Common_model->master_insert($in_store_data);
                $store_id = 1;
                echo 'in_store_data';
                pr($in_store_data);

                if ($this->input->post('location_count', TRUE) > 0) {
                    $location_count = $this->input->post('location_count', TRUE);
                    for ($i = 0; $i < $location_count; $i++) {
                        $in_place_data = array(
                            'id_google' => $this->input->post('place_id_' . $i, TRUE),
                            'street' => $this->input->post('address_' . $i, TRUE),
                            'street1' => $this->input->post('street1_' . $i, TRUE),
                            'city' => $this->input->post('city_' . $i, TRUE),
                            'state' => $this->input->post('state_' . $i, TRUE),
                            'id_country' => $this->input->post('country_id', TRUE),
                            'latitude' => $this->input->post('latitude_' . $i, TRUE),
                            'longitude' => $this->input->post('longitude_' . $i, TRUE),
                            'created_date' => $date,
                            'is_testdata' => (ENVIRONMENT !== 'production') ? 1 : 0,
                            'is_delete' => IS_NOT_DELETED_STATUS
                        );
                        echo 'in_place_data';
                        pr($in_place_data);
//                    $place_id = $this->Common_model->master_insert($in_place_data);
                        $place_id = 1;

                        $in_store_location_data = array(
                            'id_store' => $store_id,
                            'id_place' => $place_id,
                            'id_location' => $this->input->post('mall_' . $i, TRUE),
                            'location_type' => STORE_LOCATION_TYPE,
                            'created_date' => $date,
                            'is_testdata' => (ENVIRONMENT !== 'production') ? 1 : 0,
                            'is_delete' => IS_NOT_DELETED_STATUS
                        );
                        echo 'in_store_location_data';
                        pr($in_store_location_data);
//                    $this->Common_model->master_insert($in_store_location_data);
                    }
                }

                if ($this->input->post('category_count', TRUE) > 0) {
                    $category_count = $this->input->post('category_count', TRUE);
                    for ($i = 0; $i < $category_count; $i++) {
                        $in_category_data = array(
                            'id_store' => $store_id,
                            'id_category' => $this->input->post('category_' . $i, TRUE),
                            'id_sub_category' => $this->input->post('sub_category_' . $i, TRUE),
                            'created_date' => $date,
                            'is_testdata' => (ENVIRONMENT !== 'production') ? 1 : 0,
                            'is_delete' => IS_NOT_DELETED_STATUS
                        );
                        echo 'in_category_data';
                        pr($in_category_data);
//                    $this->Common_model->master_insert($in_category_data);
                    }
                }

                $verification_code = md5(time());
                $reset_link = SITEURL . 'store-registration?account_verification=' . $verification_code;
                $subject = 'Complete your registration';
//                $content = $this->Email_template_model->forgot_password_format($reset_link);
//                $response = $this->Email_template_model->send_email(NULL, $user['email_id'], $subject, $content);
//                account_verification_format();
                die("end");
            }
        }

        $this->data['title'] = $this->data['page_header'] = 'Store Registration';

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

        $this->load->view('Registration/store', $this->data);
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

}
