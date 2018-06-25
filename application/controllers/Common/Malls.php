<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Malls extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Common_model', '', TRUE);

        $this->bread_crum[] = array(
            'url' => '',
            'title' => 'Malls',
        );

        if (!in_array($this->loggedin_user_type, array(COUNTRY_ADMIN_USER_TYPE, STORE_OR_MALL_ADMIN_USER_TYPE)))
            redirect('/');
    }

    public function index() {

        $this->data['title'] = $this->data['page_header'] = 'Mall List';
        $this->bread_crum[] = array(
            'url' => '',
            'title' => 'List',
        );

        $mall_list_url = '';
        $filter_list_url = '';
        $mall_details_url = '';
        $delete_mall_url = '';
        $add_mall_url = '';
        $edit_mall_url = '';

        if ($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE) {
            $mall_list_url = 'country-admin/malls';
            $filter_list_url = 'country-admin/malls/filter_malls';
            $mall_details_url = 'country-admin/malls/get_mall_details/';
            $delete_mall_url = 'country-admin/malls/delete/';
            $this->data['delete_mall_url'] = $delete_mall_url;
            $add_mall_url = 'country-admin/malls/save/';
            $edit_mall_url = 'country-admin/malls/save/';
        } elseif ($this->loggedin_user_type == STORE_OR_MALL_ADMIN_USER_TYPE) {
            $mall_list_url = 'mall-store-user/malls';
            $filter_list_url = 'mall-store-user/malls/filter_malls';
            $mall_details_url = 'mall-store-user/malls/get_mall_details/';
            $add_mall_url = 'mall-store-user/malls/save/';
            $edit_mall_url = 'mall-store-user/malls/save/';
        }

        $this->data['mall_list_url'] = $mall_list_url;
        $this->data['filter_list_url'] = $filter_list_url;
        $this->data['mall_details_url'] = $mall_details_url;
        $this->data['add_mall_url'] = $add_mall_url;
        $this->data['edit_mall_url'] = $edit_mall_url;

        $this->data['filter_list_url'] = $filter_list_url;
        $this->template->load('user', 'Common/Mall/index', $this->data);
    }

    public function filter_malls() {

        $filter_array = $this->Common_model->create_datatable_request($this->input->post());

        $filter_array['order_by'] = array(tbl_mall . '.id_mall' => 'DESC');
        $filter_array['group_by'] = array(tbl_mall . '.id_mall');
        $filter_array['where'] = array(
            tbl_mall . '.is_delete' => IS_NOT_DELETED_STATUS,
            tbl_user . '.is_delete' => IS_NOT_DELETED_STATUS,
            tbl_country . '.is_delete' => IS_NOT_DELETED_STATUS,
        );

        $filter_array['where_with_sign'][] = 'country.id_country = mall.id_country';
        $filter_array['where_with_sign'][] = 'user.id_user = mall.id_users';

        if ($this->loggedin_user_type == STORE_OR_MALL_ADMIN_USER_TYPE) {
            $filter_array['where'][tbl_mall . '.id_users'] = $this->loggedin_user_data['user_id'];
        }
        if ($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE) {
            $filter_array['where_with_sign'][] = 'country.id_users =  ' . $this->loggedin_user_data['user_id'];
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
//        query();
        $total_filter_records = $this->Common_model->get_filtered_records(tbl_mall, $filter_array, 1);

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->Common_model->master_count(tbl_mall),
            "recordsFiltered" => $total_filter_records,
            "data" => $filter_records,
        );
        echo json_encode($output);
    }

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

    public function save($id = NULL) {

        $back_url = '';
        $img_name = $image_name = '';
        $country_id = 0;
        if ($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE) {
            $back_url = 'country-admin/malls';
        } elseif ($this->loggedin_user_type == STORE_OR_MALL_ADMIN_USER_TYPE) {
            $back_url = 'mall-store-user/malls';
        }

        if ($this->loggedin_user_type == STORE_OR_MALL_ADMIN_USER_TYPE && is_null($id))
            redirect($back_url);

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
                $country_id = $mall_details['id_country'];

                $select_sales_trend = array(
                    'table' => tbl_sales_trend,
                    'where' => array('id_mall' => $id, 'is_delete' => IS_NOT_DELETED_STATUS)
                );
                $sales_trends = $this->Common_model->master_select($select_sales_trend);

                $this->data['mall_details'] = $mall_details;
                $this->data['sales_trends'] = $sales_trends;
            } else {
                redirect($back_url);
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

            $select_country = array(
                'table' => tbl_mall . ' mall',
                'where' => array(
                    'mall.status' => ACTIVE_STATUS,
                    'mall.is_delete' => IS_NOT_DELETED_STATUS,
                    'user.is_delete' => IS_NOT_DELETED_STATUS,
                    'country.is_delete' => IS_NOT_DELETED_STATUS
                ),
                'where_with_sign' => array(
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
                $select_country['where_with_sign'] = array('FIND_IN_SET("' . $this->loggedin_user_data['user_id'] . '", mall.id_users) <> 0');
            if ($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE)
                $select_country['where_with_sign'] = array('FIND_IN_SET("' . $this->loggedin_user_data['user_id'] . '", country.id_users) <> 0');

            $country_details = $this->Common_model->master_single_select($select_country);
            if (isset($country_details) && sizeof($country_details) > 0) {
                $country_id = $country_details['id_country'];
            }
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

            if ($this->_validate_form($validate_fields)) {
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
                            'first_name' => $this->input->post('first_name', TRUE),
                            'last_name' => $this->input->post('last_name', TRUE),
                            'mobile' => $this->input->post('mobile', TRUE)
                        );
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
                        $mall_data['created_date'] = date('Y-m-d h:i:s');
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

    public function _validate_form($validate_fields) {

        if (in_array('mall_name', $validate_fields)) {

            $validation_rules[] = array(
                'field' => 'mall_name',
                'label' => 'Mall Name',
                'rules' => 'trim|required|min_length[2]|callback_check_mall_name|max_length[250]|htmlentities'
            );
        }
        if (in_array('website', $validate_fields)) {
            $validation_rules[] = array(
                'field' => 'website',
                'label' => 'Website',
                'rules' => 'trim|min_length[2]|max_length[250]|callback_custom_valid_url|htmlentities'
            );
        }
        if (in_array('facebook_page', $validate_fields)) {
            $validation_rules[] = array(
                'field' => 'facebook_page',
                'label' => 'Facebook Page URL',
                'rules' => 'trim|min_length[2]|max_length[250]|callback_custom_valid_url|htmlentities'
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
                'rules' => 'trim|min_length[2]|max_length[100]|htmlentities'
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
        } else {
            if ($this->input->post('mall_id', TRUE) == '') {
                $this->form_validation->set_message('custom_mall_logo', 'The {field} field is required.');
                return FALSE;
            } else {
                return TRUE;
            }
        }
        return TRUE;
    }

    function check_mall_name($mall_name) {

        if ($this->input->post('mall_id', TRUE) != '') {
            $select_data = array(
                'table' => tbl_mall,
                'where' => array(
                    'is_delete' => IS_NOT_DELETED_STATUS,
                    'mall_name' => $mall_name,
                    'id_mall' => $this->input->post('mall_id', TRUE)
                ),
                'where_with_sign' => array('id_mall <>' . $this->input->post('mall_id', TRUE))
            );
        } else {
            $select_data = array(
                'table' => tbl_mall,
                'where' => array(
                    'is_delete' => IS_NOT_DELETED_STATUS,
                    'mall_name' => $mall_name
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

    function delete($id) {

        if (!is_null($id) && $id > 0 && $this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE) {

            $select_mall_location = array(
                'table' => tbl_mall_location,
                'where' => array(
                    'is_delete' => IS_NOT_DELETED_STATUS,
                    'id_location' => $id,
                    'location_type' => MALL_LOCATION_TYPE
                )
            );

            $mall_locations = $this->Common_model->master_single_select($select_mall_location);

            if (isset($mall_locations) && sizeof($mall_locations) > 0) {

                $this->session->set_flashdata('error_msg', 'Store is using this Mall, You can not delete this Mall.');
            } else {
                $update_data = array('is_delete' => IS_DELETED_STATUS);
                $where_data = array('is_delete' => IS_NOT_DELETED_STATUS, 'id_mall' => $id);

                $is_updated = $this->Common_model->master_update(tbl_mall, $update_data, $where_data);
                if ($is_updated)
                    $this->session->set_flashdata('success_msg', 'Mall deleted successfully.');
                else
                    $this->session->set_flashdata('error_msg', 'Invalid request sent to delete Mall. Please try again later.');
            }
            redirect('country-admin/malls');
        } else {
            dashboard_redirect($this->loggedin_user_type);
        }
    }

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

}
