<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Malls extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Common_model', '', TRUE);
        $this->load->model('Email_template_model', '', TRUE);

        $this->bread_crum[] = array(
            'url' => '',
            'title' => 'Malls',
        );

        if (!in_array($this->loggedin_user_type, array(COUNTRY_ADMIN_USER_TYPE, STORE_OR_MALL_ADMIN_USER_TYPE)))
            override_404();
    }

    //Mall List
    public function index() {

        $this->data['title'] = $this->data['page_header'] = 'Mall List';
        $this->bread_crum[] = array(
            'url' => '',
            'title' => 'List',
        );

        $mall_list_url = '';
        $filter_list_url = '';
        $mall_details_url = '';
        $sponsored_mall_url = '';
        $delete_mall_url = '';
        $add_mall_url = '';
        $edit_mall_url = '';
        $report_url = '';
        if ($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE) {
            $mall_list_url = 'country-admin/malls';
            $filter_list_url = 'country-admin/malls/filter_malls';
            $mall_details_url = 'country-admin/malls/get_mall_details/';
            $delete_mall_url = 'country-admin/malls/delete/';
            $sponsored_mall_url = 'country-admin/malls/sponsored/';
            $this->data['delete_mall_url'] = $delete_mall_url;
            $this->data['sponsored_mall_url'] = $sponsored_mall_url;
            $add_mall_url = 'country-admin/malls/save/';
            $edit_mall_url = 'country-admin/malls/save/';
            $report_url = 'country-admin/report/mall/';
        } elseif ($this->loggedin_user_type == STORE_OR_MALL_ADMIN_USER_TYPE) {
            $mall_list_url = 'mall-store-user/malls';
            $filter_list_url = 'mall-store-user/malls/filter_malls';
            $mall_details_url = 'mall-store-user/malls/get_mall_details/';
            $add_mall_url = 'mall-store-user/malls/save/';
            $edit_mall_url = 'mall-store-user/malls/save/';
            $report_url = 'mall-store-user/report/mall/';
        }

        $this->data['mall_list_url'] = $mall_list_url;
        $this->data['filter_list_url'] = $filter_list_url;
        $this->data['mall_details_url'] = $mall_details_url;
        $this->data['add_mall_url'] = $add_mall_url;
        $this->data['edit_mall_url'] = $edit_mall_url;
        $this->data['report_url'] = $report_url;

        $this->data['filter_list_url'] = $filter_list_url;
        $this->template->load('user', 'Common/Mall/index', $this->data);
    }

    //Filter Malls
    public function filter_malls() {

        $filter_array = $this->Common_model->create_datatable_request($this->input->post());

        $date = date('Y-m-d h:i:s');
        $current_time_zone_today_date = new DateTime($date, new DateTimeZone(date_default_timezone_get()));
        $current_time_zone_today_date->setTimezone(new DateTimeZone(date_default_timezone_get()));
        $current_time_zone_today_date_ = $current_time_zone_today_date->format('Y-m-d H:i:s');
        $current_time_zone_offeset = $current_time_zone_today_date->format('P');
        $logged_in_country_zone_today_date = new DateTime($date, new DateTimeZone($this->loggedin_user_country_data['timezone']));
        $logged_in_country_zone_today_date->setTimezone(new DateTimeZone($this->loggedin_user_country_data['timezone']));
        $logged_in_country_zone_today_date_ = $logged_in_country_zone_today_date->format('Y-m-d H:i:s');
        $logged_in_country_zone_offset = $logged_in_country_zone_today_date->format('P');

        $filter_array['fields'][] = 'DATE_FORMAT(CONVERT_TZ(' . tbl_mall . '.created_date,"' . $current_time_zone_offeset . '","' . $logged_in_country_zone_offset . '"),"%Y-%m-%d %H:%i") as mall_created_date';

        $filter_array['order_by'] = array(tbl_mall . '.id_mall' => 'DESC');
        $filter_array['group_by'] = array(tbl_mall . '.id_mall');
        $filter_array['where'] = array(
            tbl_mall . '.is_delete' => IS_NOT_DELETED_STATUS,
            tbl_user . '.is_delete' => IS_NOT_DELETED_STATUS,
            tbl_country . '.is_delete' => IS_NOT_DELETED_STATUS,
        );

        if ($this->loggedin_user_type == STORE_OR_MALL_ADMIN_USER_TYPE) {
            $filter_array['where'][tbl_mall . '.id_users'] = $this->loggedin_user_data['user_id'];
            $filter_array['where_with_sign'][] = 'user.id_user = mall.id_users';
        }
        if ($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE) {
            $filter_array['where_with_sign'][] = 'country.id_country =  ' . $this->loggedin_user_country_data['id_country'];
            $filter_array['where_with_sign'][] = 'country.id_country = mall.id_country';
        }

        $filter_array['join'][] = array(
            'table' => tbl_user . ' as user',
            'condition' => tbl_user . '.id_user = ' . tbl_mall . '.id_users',
            'join_type' => 'left',
        );
        $filter_array['join'][] = array(
            'table' => tbl_country . ' as country',
            'condition' => tbl_country . '.id_country = ' . tbl_mall . '.id_country',
            'join_type' => 'left',
        );

        $filter_records = $this->Common_model->get_filtered_records(tbl_mall, $filter_array);
        $total_filter_records = $this->Common_model->get_filtered_records(tbl_mall, $filter_array, 1);

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->Common_model->master_count(tbl_mall),
            "recordsFiltered" => $total_filter_records,
            "data" => $filter_records,
        );
        echo json_encode($output);
    }

    /* Get Mall Details for specific Mall
     * @param int $mall_id
     */

    public function get_mall_details($mall_id = NULL) {
        $response = array(
            'status' => '0',
            'sub_view' => '0',
        );
        if (isset($mall_id) && !empty($mall_id)) {

            $select_mall = array(
                'table' => tbl_mall . ' mall',
                'fields' => array('mall.mall_name', 'mall.mall_logo mall_mall_logo', 'mall.status mall_status', 'mall.created_date mall_created_date', 'mall.telephone mall_telephone', 'mall.website mall_website', 'mall.facebook_page mall_facebook_page', 'user.first_name user_first_name', 'user.last_name user_last_name', 'user.email_id user_email_id', 'user.mobile user_mobile'),
                'where' => array('mall.id_mall' => $mall_id, 'mall.is_delete' => IS_NOT_DELETED_STATUS),
                'join' => array(
                    array(
                        'table' => tbl_user . ' as user',
                        'condition' => tbl_user . '.id_user = ' . tbl_mall . '.id_users',
                        'join' => 'left'
                    ),
                    array(
                        'table' => tbl_country . ' as country',
                        'condition' => 'country.id_country = mall.id_country',
                        'join' => 'left'
                    )
                )
            );

            $mall_details = $this->Common_model->master_single_select($select_mall);

            if (isset($mall_details) && !empty($mall_details)) {

                $this->data['mall_details'] = $mall_details;

                $html = $this->load->view('Common/Mall/details', $this->data, TRUE);
                $response = array(
                    'status' => '1',
                    'sub_view' => $html,
                );
            }
        }
        echo json_encode($response);
    }

    /*
     * Add, Edit Mall Details
     */

    public function save($id = NULL) {

        $back_url = '';
        $img_name = $image_name = '';
        $country_id = $this->loggedin_user_country_data['id_country'];
        if ($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE) {
            $back_url = 'country-admin/malls';
        } elseif ($this->loggedin_user_type == STORE_OR_MALL_ADMIN_USER_TYPE) {
            $back_url = 'mall-store-user/malls';
        }

        if ($this->loggedin_user_type == STORE_OR_MALL_ADMIN_USER_TYPE && is_null($id))
            override_404();

        $status_arr = array(
            ACTIVE_STATUS => 'Active',
            IN_ACTIVE_STATUS => 'Inactive',
        );
        $this->bread_crum[] = array(
            'url' => $back_url,
            'title' => ' List',
        );
        if (!is_null($id)) {

            $this->data['title'] = $this->data['page_header'] = 'Edit Mall';
            $this->bread_crum[] = array(
                'url' => '',
                'title' => 'Edit Mall'
            );

            $select_mall = array(
                'table' => tbl_mall . ' mall',
                'fields' => '*, mall.status mall_status, country.id_users con',
                'where' => array(
                    'mall.is_delete' => IS_NOT_DELETED_STATUS,
                    'user.is_delete' => IS_NOT_DELETED_STATUS,
                    'country.is_delete' => IS_NOT_DELETED_STATUS,
                    'mall.id_mall' => $id
                ),
                'where_with_sign' => array(
                    'mall.status IN (' . ACTIVE_STATUS . ', ' . IN_ACTIVE_STATUS . ', ' . NOT_VERIFIED_STATUS . ')',
                    'country.id_country = mall.id_country',
                    'user.id_user = mall.id_users'
                ),
                'join' => array(
                    array(
                        'table' => tbl_user . ' as user',
                        'condition' => tbl_mall . '.id_users = ' . tbl_user . '.id_user',
                        'join' => 'left',
                    ),
                    array(
                        'table' => tbl_country . ' as country',
                        'condition' => tbl_country . '.id_country = ' . tbl_mall . '.id_country',
                        'join' => 'left',
                    )
                )
            );

            if ($this->loggedin_user_type == STORE_OR_MALL_ADMIN_USER_TYPE)
                $select_mall['where_with_sign'][] = 'FIND_IN_SET("' . $this->loggedin_user_data['user_id'] . '", mall.id_users) <> 0';

            if ($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE)
                $select_mall['where_with_sign'][] = 'FIND_IN_SET("' . $this->loggedin_user_data['user_id'] . '", country.id_users) <> 0';

            $mall_details = $this->Common_model->master_single_select($select_mall);

            if (isset($mall_details) && sizeof($mall_details) > 0) {

                $image_name = $mall_details['mall_logo'];
//                $country_id = $mall_details['id_country'];

                $select_sales_trend = array(
                    'table' => tbl_sales_trend,
                    'where' => array('id_mall' => $id, 'is_delete' => IS_NOT_DELETED_STATUS)
                );
                $sales_trends = $this->Common_model->master_select($select_sales_trend);

                $this->data['mall_details'] = $mall_details;
                $this->data['sales_trends'] = $sales_trends;
            } else {
                override_404();
            }

            if ($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE) {
                $select_user_status = array(
                    'table' => tbl_mall,
                    'where' => array('is_delete' => IS_NOT_DELETED_STATUS, 'status' => NOT_VERIFIED_STATUS, 'id_mall' => $id)
                );
                $user_status = $this->Common_model->master_single_select($select_user_status);
                if (isset($user_status) && sizeof($user_status) > 0)
                    $status_arr[NOT_VERIFIED_STATUS] = 'Not Verified';
            }
        } else {
            $this->data['title'] = $this->data['page_header'] = 'Add Mall';
            $this->bread_crum[] = array(
                'url' => '',
                'title' => 'Add Mall',
            );
        }

        if ($this->input->post()) {

            $validate_fields = array(
                'mall_name',
                'website',
                'facebook_page',
                'mall_logo',
            );

            if ($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE) {
                $validate_fields[] = 'latitude';
                $validate_fields[] = 'longitude';
            }

            if ($this->input->post('email_id') != '') {
                $validate_fields[] = 'first_name';
                $validate_fields[] = 'last_name';
                $validate_fields[] = 'email_id';
                $validate_fields[] = 'telephone';
            }

            if ($this->_validate_form($validate_fields, $country_id)) {
                $user_id = 0;
                $date = date('Y-m-d h:i:s');
                $delete_mall_sales_trend = array();
                $exist_mall_sales_trend_ids = array();
                $do_mall_image_has_error = false;

                if (isset($_FILES['mall_logo'])) {
                    if (($_FILES['mall_logo']['size']) > 0) {
                        $image_path = $_SERVER['DOCUMENT_ROOT'] . store_img_path;
                        if (!file_exists($image_path)) {
                            $this->Common_model->created_directory($image_path);
                        }
                        $supported_files = 'gif|jpg|png|jpeg';
                        $img_name = $this->Common_model->upload_image('mall_logo', $image_path, $supported_files);
                        if (empty($img_name)) {
                            $do_mall_image_has_error = true;
                            $this->data['image_errors'] = $this->upload->display_errors();
                        } else {
                            $image_name = $img_name;
                        }
                    } else {
                        if (!empty($_FILES['mall_logo']['tmp_name'])) {
                            $do_mall_image_has_error = true;
                            $this->data['image_errors'] = 'Invalid File';
                        }
                    }
                }

                if (!$do_mall_image_has_error) {
                    $mall_data = array(
                        'mall_name' => $this->input->post('mall_name', TRUE),
                        'mall_logo' => $image_name,
                        'website' => $this->input->post('website', TRUE),
                        'facebook_page' => $this->input->post('facebook_page', TRUE),
                        'telephone' => ($this->input->post('mobile', TRUE) != '') ? $this->input->post('mobile', TRUE) : ' ',
                        'id_country' => $country_id,
                        'latitude' => $this->input->post('latitude', TRUE),
                        'longitude' => $this->input->post('longitude', TRUE)
                    );

                    if ($this->input->post('email_id', TRUE) != '') {
                        $select_user = array(
                            'table' => tbl_user,
                            'where' => array('email_id' => $this->input->post('email_id', TRUE), 'is_delete' => IS_NOT_DELETED_STATUS)
                        );

                        $user_details = $this->Common_model->master_single_select($select_user);
                    }
                    if (isset($user_details) && sizeof($user_details) > 0) {
                        $user_id = $user_details['id_user'];
                        $where_user_data = array('id_user' => $user_id);
                        $update_user_data = array(
                            'status' => ACTIVE_STATUS,
                            'first_name' => $this->input->post('first_name', TRUE),
                            'last_name' => $this->input->post('last_name', TRUE),
                            'mobile' => ($this->input->post('mobile', TRUE) != '') ? $this->input->post('mobile', TRUE) : ' ',
                        );
                        if (empty($user_details['password'])) {
                            $new_password = $this->Common_model->random_generate_code(5);
                            $content = $this->Email_template_model->send_password_format($user_details['first_name'], $user_details['last_name'], $new_password);
                            $response = $this->Email_template_model->send_email(NULL, $this->input->post('email_id', TRUE), 'Account Password', $content);
                            $update_user_data['password'] = md5($new_password);
                        }

                        $this->Common_model->master_update(tbl_user, $update_user_data, $where_user_data);
                    } else {

                        if ($this->input->post('email_id', TRUE) != '') {
                            $new_password = $this->Common_model->random_generate_code(5);
                            $user_data = array(
                                'user_type' => STORE_OR_MALL_ADMIN_USER_TYPE,
                                'email_id' => $this->input->post('email_id', TRUE),
                                'first_name' => $this->input->post('first_name', TRUE),
                                'last_name' => $this->input->post('last_name', TRUE),
                                'mobile' => ($this->input->post('mobile', TRUE) != '') ? $this->input->post('mobile', TRUE) : ' ',
                                'password' => md5($new_password),
                                'created_date' => $date,
                                'is_testdata' => (ENVIRONMENT !== 'production') ? 1 : 0,
                                'is_delete' => IS_NOT_DELETED_STATUS,
                                'status' => ACTIVE_STATUS
                            );

                            $user_id = $this->Common_model->master_save(tbl_user, $user_data);

                            $content = $this->Email_template_model->send_password_format($this->input->post('first_name', TRUE), $this->input->post('last_name', TRUE), $new_password);
                            $response = $this->Email_template_model->send_email(NULL, $this->input->post('email_id', TRUE), 'Account Password', $content);
                        } else {
                            $user_id = $this->loggedin_user_data['user_id'];
                        }
                    }
                    $mall_data['id_users'] = $user_id;
                    if ($id) {

                        $mall_data['modified_date'] = $date;
                        $where_mall = array('id_mall' => $id);
                        $this->Common_model->master_update(tbl_mall, $mall_data, $where_mall);

                        $this->add_sales_trend($date, $id);

                        if (isset($sales_trends) && sizeof($sales_trends) > 0) {
                            foreach ($sales_trends as $trend) {
                                if ($this->input->post('exist_from_date_' . $trend['id_sales_trend'], TRUE) != '') {

                                    $from_date = $this->input->post('exist_from_date_' . $trend['id_sales_trend'], TRUE);
                                    $to_date = $this->input->post('exist_to_date_' . $trend['id_sales_trend'], TRUE);
                                    if (!empty($from_date) && !empty($to_date)) {
                                        $from_date_text = date('y-m-d', strtotime($from_date));
                                        $to_date_text = date('y-m-d', strtotime($to_date));
                                        $update_mall_sales_trend = array(
                                            'from_date' => $from_date_text,
                                            'to_date' => $to_date_text,
                                            'modified_date' => $date
                                        );
                                        $where_mall_sales_trend = array('id_sales_trend' => $trend['id_sales_trend']);
                                        $this->Common_model->master_update(tbl_sales_trend, $update_mall_sales_trend, $where_mall_sales_trend);
                                    }
                                } else {
                                    $delete_mall_sales_trend[] = $trend['id_sales_trend'];
                                }
                            }

                            if (isset($delete_mall_sales_trend) && sizeof($delete_mall_sales_trend) > 0) {
                                $update_mall_sales_trend = 'is_delete = ' . IS_DELETED_STATUS . ' , modified_date = "' . $date . '"';
                                $where_mall_sales_trend = 'id_sales_trend IN (' . implode(',', $delete_mall_sales_trend) . ')';
                                $this->Common_model->master_update(tbl_sales_trend, $update_mall_sales_trend, $where_mall_sales_trend, TRUE);
                            }
                        }
                        $this->session->set_flashdata('success_msg', 'Mall updated successfully');
                    } else {
                        $mall_data['created_date'] = $date;
                        $mall_data['is_testdata'] = (ENVIRONMENT !== 'production') ? 1 : 0;
                        $mall_data['is_delete'] = IS_NOT_DELETED_STATUS;
                        $mall_data['status'] = ACTIVE_STATUS;

                        $mall_id = $this->Common_model->master_save(tbl_mall, $mall_data);

                        $this->add_sales_trend($date, $mall_id);
                        $this->session->set_flashdata('success_msg', 'Mall added successfully');
                    }

                    redirect($back_url);
                }
            }
        }

        $select_country = array(
            'table' => tbl_country,
            'where' => array('status' => ACTIVE_STATUS, 'is_delete' => IS_NOT_DELETED_STATUS)
        );
        $country_list = $this->Common_model->master_select($select_country);

        $this->data['status_list'] = $status_arr;
        $this->data['country_list'] = $country_list;
        $this->data['back_url'] = $back_url;

        $this->template->load('user', 'Common/Mall/form', $this->data);
    }

    public function _validate_form($validate_fields, $country_id) {

        if (in_array('mall_name', $validate_fields)) {
            $validation_rules[] = array(
                'field' => 'mall_name',
                'label' => 'Mall Name',
                'rules' => 'trim|required|min_length[2]|callback_check_mall_name[' . $country_id . ']|max_length[250]'
            );
        }
        if (in_array('website', $validate_fields)) {
            $validation_rules[] = array(
                'field' => 'website',
                'label' => 'Website',
                'rules' => 'trim|min_length[5]|max_length[250]|callback_custom_valid_url'
            );
        }
        if (in_array('facebook_page', $validate_fields)) {
            $validation_rules[] = array(
                'field' => 'facebook_page',
                'label' => 'Facebook Page URL',
                'rules' => 'trim|required|min_length[5]|max_length[250]|callback_custom_valid_url'
            );
        }
        if (in_array('mall_logo', $validate_fields)) {

            $check_mall_validation = '';
            if ($this->input->post('mall_id') == '' || (isset($_FILES['mall_logo']) && $_FILES['mall_logo']['size'] > 0))
                $check_mall_validation = '|callback_custom_mall_logo[mall_logo]';
            $validation_rules[] = array(
                'field' => 'mall_logo',
                'label' => 'Mall Logo',
                'rules' => 'trim' . $check_mall_validation . '|htmlentities'
            );
        }
        if (in_array('first_name', $validate_fields)) {
            $validation_rules[] = array(
                'field' => 'first_name',
                'label' => 'Contact Person\'s First Name',
                'rules' => 'trim|min_length[2]|max_length[150]|htmlentities'
            );
        }
        if (in_array('last_name', $validate_fields)) {
            $validation_rules[] = array(
                'field' => 'last_name',
                'label' => 'Contact Person\'s Last Name',
                'rules' => 'trim|min_length[2]|max_length[150]|htmlentities'
            );
        }
        if (in_array('email_id', $validate_fields)) {
            $validation_rules[] = array(
                'field' => 'email_id',
                'label' => 'Email Address',
                'rules' => 'trim|min_length[5]|max_length[100]|htmlentities'
            );
        }
        if (in_array('mobile', $validate_fields)) {
            $validation_rules[] = array(
                'field' => 'mobile',
                'label' => 'Mobile Number',
                'rules' => 'trim|min_length[8]|max_length[20]|htmlentities'
            );
        }
        if (in_array('latitude', $validate_fields)) {
            $validation_rules[] = array(
                'field' => 'latitude',
                'label' => 'Latitude',
                'rules' => 'trim|min_length[2]|max_length[10]|decimal|htmlentities'
            );
        }
        if (in_array('longitude', $validate_fields)) {
            $validation_rules[] = array(
                'field' => 'longitude',
                'label' => 'Longitude',
                'rules' => 'trim|min_length[2]|max_length[10]|decimal|htmlentities'
            );
        }

        $this->form_validation->set_rules($validation_rules);
        return $this->form_validation->run();
    }

    function custom_mall_logo($image, $image_control) {

        if ($_FILES[$image_control]['name'] != '') {
//            if ($_FILES[$image_control]['type'] != 'image/jpeg' && $_FILES[$image_control]['type'] != 'image/jpg' && $_FILES[$image_control]['type'] != 'image/gif' && $_FILES[$image_control]['type'] != 'image/png') {
            if ($_FILES[$image_control]['type'] != 'image/jpeg' && $_FILES[$image_control]['type'] != 'image/jpg' && $_FILES[$image_control]['type'] != 'image/png') {
                $this->form_validation->set_message('custom_mall_logo', 'The {field} contain invalid image type.');
                return FALSE;
            }
            if ($_FILES[$image_control]['error'] > 0) {
                $this->form_validation->set_message('custom_mall_logo', 'The {field} contain invalid image.');
                return FALSE;
            }
            if ($_FILES[$image_control]['size'] <= 0) {
                $this->form_validation->set_message('custom_mall_logo', 'The {field} contain invalid image size.');
                return FALSE;
            }
        }
//        else {
//            if ($this->input->post('mall_id', TRUE) == '') {
//                $this->form_validation->set_message('custom_mall_logo', 'The {field} field is required.');
//                return FALSE;
//            } else {
//                return TRUE;
//            }
//        }
        return TRUE;
    }

    function check_mall_name($mall_name, $country_id = NULL) {

        if ($this->input->post('mall_id', TRUE) != '') {
            $select_data = array(
                'table' => tbl_mall,
                'where' => array(
                    'is_delete' => IS_NOT_DELETED_STATUS,
                    'mall_name' => $mall_name,
                    'id_country' => $country_id
                ),
                'where_with_sign' => array('id_mall <>' . $this->input->post('mall_id', TRUE))
            );
        } else {
            $select_data = array(
                'table' => tbl_mall,
                'where' => array(
                    'is_delete' => IS_NOT_DELETED_STATUS,
                    'mall_name' => $mall_name,
                    'id_country' => $country_id
                )
            );
        }
        $check_mall_name = $this->Common_model->master_single_select($select_data);

        if (isset($check_mall_name) && sizeof($check_mall_name) > 0) {
            $this->form_validation->set_message('check_mall_name', 'The {field} already exists.');
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

    /*
     * Delete Mall
     * @param int id: mall id
     */

    function delete($id) {

        if (!is_null($id) && $id > 0 && $this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE) {
            $update_data = array('is_delete' => IS_DELETED_STATUS);
            $where_data = array('is_delete' => IS_NOT_DELETED_STATUS, 'id_mall' => $id);

            $is_updated = $this->Common_model->master_update(tbl_mall, $update_data, $where_data);
            if ($is_updated)
                $this->session->set_flashdata('success_msg', 'Mall deleted successfully.');
            else
                $this->session->set_flashdata('error_msg', 'Invalid request sent to delete Mall. Please try again later.');

            redirect('country-admin/malls');
        } else {
            override_404();
        }
    }

    /*
     * Add Sales Trend At time of Add and Edit Mall
     * @param date : date : Today's Date
     * @param int mall_id : mall id
     */

    function add_sales_trend($date = NULL, $mall_id = NULL) {

        if (!is_null($mall_id) && $mall_id > 0) {
            $sales_trend_count = $this->input->post('sales_trend_count', TRUE);
            for ($i = 0; $i <= $sales_trend_count; $i++) {
                if ($this->input->post('from_date_' . $i, TRUE) > 0) {

                    $from_date = $this->input->post('from_date_' . $i, TRUE);
                    $from_date_text = date('y-m-d', strtotime($from_date));
                    $to_date = $this->input->post('to_date_' . $i, TRUE);
                    $to_date_text = date('y-m-d', strtotime($to_date));

                    $in_sales_trend_data = array(
                        'id_mall' => $mall_id,
                        'from_date' => $from_date_text,
                        'to_date' => $to_date_text,
                        'created_date' => $date,
                        'is_testdata' => (ENVIRONMENT !== 'production') ? 1 : 0,
                        'is_delete' => IS_NOT_DELETED_STATUS
                    );
                    $this->Common_model->master_save(tbl_sales_trend, $in_sales_trend_data);
                }
            }
        }
    }

    /*
     * Sponsopred Featured Page
     * @param int id : mall id
     */

    function sponsored($id = NULL) {

        $back_url = '';
        $this->data['mall_id'] = $id;
        if ($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE)
            $back_url = 'country-admin/malls/';
        elseif ($this->loggedin_user_type == STORE_OR_MALL_ADMIN_USER_TYPE)
            $back_url = 'mall-store-user/malls';

        if ($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE) {

            $select_mall = array(
                'table' => tbl_mall . ' mall',
                'fields' => array('mall.id_mall', 'mall.mall_name', 'mall.is_sponsored', 'sponsored_log.position', 'sponsored_log.from_date', 'sponsored_log.to_date', 'sponsored_log.id_sponsored_log', 'country.timezone'),
                'where' => array('mall.id_mall' => $id, 'mall.is_delete' => IS_NOT_DELETED_STATUS, 'mall.id_country' => $this->loggedin_user_country_data['id_country']),
                'join' => array(
                    array(
                        'table' => tbl_sponsored_log . ' as sponsored_log',
                        'condition' => 'sponsored_log.id_mall = ' . $id . ' AND sponsored_log.id_mall = mall.id_mall AND sponsored_log.is_delete = ' . IS_NOT_DELETED_STATUS,
                        'join' => 'left'
                    ),
                    array(
                        'table' => tbl_country . ' as country',
                        'condition' => 'country.id_country = mall.id_country',
                        'join' => 'left'
                    )
                ),
                'group_by' => array('mall.id_mall')
            );
            $mall_details = $this->Common_model->master_single_select($select_mall);
            if (isset($mall_details) && sizeof($mall_details) > 0) {
                $this->bread_crum[] = array(
                    'url' => $back_url,
                    'title' => ' List',
                );

                $this->bread_crum[] = array(
                    'url' => 'country-admin/malls/save/' . $mall_details['id_mall'],
                    'title' => 'Edit ' . $mall_details['mall_name'],
                );

                $this->data['title'] = $this->data['page_header'] = 'Sponsored Mall';
                $this->bread_crum[] = array(
                    'url' => '',
                    'title' => 'Sponsored Mall',
                );

                if ($this->input->post()) {

                    $date = date('Y-m-d h:i:s');
                    $add_success_data = 0;

                    $position = $this->input->post('position', TRUE);
                    $from_to_date_text = $this->input->post('from_to_date', TRUE);
                    $from_to_date = explode(' - ', $from_to_date_text);

                    if (!empty($position) && !empty($from_to_date_text) && isset($from_to_date[0]) && isset($from_to_date[1])) {

                        $up_sponsored_data = array('is_delete' => IS_DELETED_STATUS);
                        $wh_sponsored_data = array(
                            'is_delete' => IS_NOT_DELETED_STATUS,
                            'id_mall' => $id,
                            'id_category' => 0,
                            'id_sub_category' => 0
                        );
                        $this->Common_model->master_update(tbl_sponsored_log, $up_sponsored_data, $wh_sponsored_data);

                        $from_date = date_create($from_to_date[0]);
                        $from_date_text = date_format($from_date, "Y-m-d");
                        $to_date = date_create($from_to_date[1]);
                        $to_date_text = date_format($to_date, "Y-m-d");
                        $in_sponsored_log = array(
                            'id_store' => 0,
                            'id_mall' => $id,
                            'id_category' => 0,
                            'id_sub_category' => 0,
                            'from_date' => $from_date_text,
                            'to_date' => $to_date_text,
                            'position' => $position,
                            'created_date' => $date,
                            'is_testdata' => (ENVIRONMENT !== 'production') ? 1 : 0,
                            'is_delete' => IS_NOT_DELETED_STATUS
                        );
                        $this->Common_model->master_save(tbl_sponsored_log, $in_sponsored_log);

//                        $from_date = date_create($from_to_date[0] . ' 00:00:00');
////                        $from_date_text = strtotime(date_format($from_date, "Y-m-d"));
//                        $to_date = date_create($from_to_date[1] . ' 00:00:00');
////                        $to_date_text = strtotime(date_format($to_date, "Y-m-d"));
//                        $now = time();

                        $date = date('Y-m-d H:i:s');
                        $converted_today_date = new DateTime($date, new DateTimeZone($mall_details['timezone']));
                        $converted_today_date->setTimezone(new DateTimeZone($mall_details['timezone']));
                        $now = strtotime($converted_today_date->format('Y-m-d H:i:s'));

                        $converted_from_date = new DateTime($from_to_date[0] . ' 00:00:00', new DateTimeZone($mall_details['timezone']));
                        $converted_from_date->setTimezone(new DateTimeZone($mall_details['timezone']));
                        $from_date_text = strtotime($converted_from_date->format('Y-m-d H:i:s'));

                        $converted_to_date = new DateTime($from_to_date[1] . ' 00:00:00', new DateTimeZone($mall_details['timezone']));
                        $converted_to_date->setTimezone(new DateTimeZone($mall_details['timezone']));
                        $to_date_text = strtotime($converted_to_date->format('Y-m-d H:i:s'));

                        if ($from_date_text < $now && $to_date_text >= $now) {
                            $up_mall = array(
                                'is_sponsored' => SPONSORED_TYPE,
                                'sponsored_position' => $position
                            );
                            $wh_mall = array(
                                'id_mall' => $id,
                                'is_delete' => IS_NOT_DELETED_STATUS
                            );
                            $this->Common_model->master_update(tbl_mall, $up_mall, $wh_mall);
                        } else {
                            $up_mall = array(
                                'is_sponsored' => UNSPONSORED_TYPE,
                                'sponsored_position' => 0
                            );
                            $wh_mall = array(
                                'id_mall' => $id,
                                'is_delete' => IS_NOT_DELETED_STATUS
                            );
                            $this->Common_model->master_update(tbl_mall, $up_mall, $wh_mall);
                        }

                        $add_success_data++;
                    }

                    if ($add_success_data > 0)
                        $this->session->set_flashdata('success_msg', 'Data updated successfully.');
                    else
                        $this->session->set_flashdata('error_msg', 'Please select proper Position and From-To Date.');

                    redirect('country-admin/malls/sponsored/' . $id);
                }

                $this->data['back_url'] = $back_url;
                $this->data['mall_details'] = $mall_details;

                $this->template->load('user', 'Common/Mall/sponsored', $this->data);
            } else {
                override_404();
            }
        } else {
            override_404();
        }
    }

    /*
     * Delete sponsored feature from Mall
     * @param int id : mall id
     */

    function delete_sponsored($id) {
        if ($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE && $this->input->post()) {
            if (isset($id) && sizeof($id) > 0) {
                $result = $this->db->query('UPDATE ' . tbl_sponsored_log . ' SET is_delete=' . IS_DELETED_STATUS .
                        ' WHERE is_delete = ' . IS_NOT_DELETED_STATUS . ' AND id_mall = ' . $id);
            }

            if ($result)
                $this->session->set_flashdata('success_msg', 'Data deleted successfully.');
            else
                $this->session->set_flashdata('error_msg', 'Data not deleted.');

            redirect('country-admin/malls/sponsored/' . $id);
        } else {
            override_404();
        }
    }

    public function edit_store($mall_id) {
        $data['title'] = $data['page_header'] = 'Edit Store Location';
        $this->bread_crum[] = array(
            'url' => '',
            'title' => 'Edit Store Location',
        );

        $mall_id = base64_decode($mall_id);

        $select_mall = array(
            'table' => tbl_store_location . ' location',
            'fields' => array('location.*', 'store.store_name'),
            'where' => array('location.id_location' => $mall_id, 'location.location_type' => 0, 'location.is_delete' => IS_NOT_DELETED_STATUS),
            'join' => array(
                array(
                    'table' => tbl_store . ' as store',
                    'condition' => 'location.id_store = store.id_store',
                    'join' => 'left'
                )
            )
        );
        $data['store_locations'] = $this->Common_model->master_select($select_mall);

        $data['store_floors'] = array(-2, -1, 0, 1, 2, 3, 4);

        $this->template->load('user', 'Common/Mall/edit_store_location', $data);
    }

    public function update_store_floor_number() {
        $is_updated = false;

        $store_location_id = $this->input->post('store_location_id');
        $store_floor_no = $this->input->post('store_floor_no');

        $result = $this->db->query('UPDATE ' . tbl_store_location . ' SET store_floor_no=' . $store_floor_no .
                ' WHERE id_store_location = ' . $store_location_id);

        if ($result) {
            $is_updated = true;
        }

        echo $is_updated;
        exit;
    }

    public function edit_store_location($mall_id) {
        $data['title'] = $data['page_header'] = 'Store Location Map';
        $this->bread_crum[] = array(
            'url' => '',
            'title' => 'Store Location Map',
        );

        $map_locations = [];

        $mall_id = base64_decode($mall_id);

        $select_mall = array(
            'table' => tbl_store_location . ' location',
            'fields' => array('location.*', 'store.*'),
            'where' => array('location.id_location' => $mall_id, 'location.location_type' => 0, 'location.is_delete' => IS_NOT_DELETED_STATUS),
            'join' => array(
                array(
                    'table' => tbl_store . ' as store',
                    'condition' => 'location.id_store = store.id_store',
                    'join' => 'left'
                )
            )
        );
        $data['store_locations'] = $this->Common_model->master_select($select_mall);

        $this->template->load('user', 'Common/Mall/store_locations_map', $data);
    }

    public function check_file_exists() {
        $image_name = $this->input->post('image_name');
        $image_path = './media/StoreLogo/' . $image_name;

        if (file_exists($image_path)) {
            $image = site_url('media/StoreLogo/' . $image_name);
        } else {
            $image = site_url("assets/user/images/store2.png");
        }

        echo $image;
        exit;
    }

    public function upload_store_image() {
        $is_updated = false;

        $store_id = $this->input->post('store_id');
        $encrypted_image = base64_decode($this->input->post('encrypted_image'));

        $image_parts = explode(";base64,", $encrypted_image);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];

        $image_base64 = base64_decode($image_parts[1]);
        $file_upload_path = './media/StoreLogo/';
        $file_name = time() . '_imqge.' . $image_type;
        $file_path = $file_upload_path . $file_name;

        file_put_contents($file_path, $image_base64);

        if (file_exists($file_path)) {
            $result = $this->db->query('UPDATE ' . tbl_store . ' SET store_logo="' . $file_name .
                    '" WHERE id_store = ' . $store_id);

            if ($result) {
                $is_updated = true;
            }
        }

        echo $is_updated;
        exit;
    }

}
