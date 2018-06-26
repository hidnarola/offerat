<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Stores extends MY_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('Common_model', '', TRUE);
        $this->load->model('Email_template_model', '', TRUE);

        $this->bread_crum[] = array(
            'url' => '',
            'title' => 'Stores',
        );

        if (!in_array($this->loggedin_user_type, array(COUNTRY_ADMIN_USER_TYPE, STORE_OR_MALL_ADMIN_USER_TYPE)))
            redirect('/');
    }

    public function index() {

        $this->data['title'] = $this->data['page_header'] = 'Stores List';
        $this->bread_crum[] = array(
            'url' => '',
            'title' => 'List',
        );
        $store_list_url = '';
        $filter_list_url = '';
        $store_details_url = '';
        $delete_store_url = '';
        $add_store_url = '';
        $edit_store_url = '';
        if ($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE) {
            $store_list_url = 'country-admin/stores';
            $filter_list_url = 'country-admin/stores/filter_stores';
            $store_details_url = 'country-admin/stores/get_store_details/';
            $delete_store_url = 'country-admin/stores/delete/';
            $this->data['delete_store_url'] = $delete_store_url;
            $add_store_url = 'country-admin/stores/save/';
            $edit_store_url = 'country-admin/stores/save/';
        } elseif ($this->loggedin_user_type == STORE_OR_MALL_ADMIN_USER_TYPE) {
            $store_list_url = 'mall-store-user/stores';
            $filter_list_url = 'mall-store-user/stores/filter_stores';
            $store_details_url = 'mall-store-user/stores/get_store_details/';
            $add_store_url = 'mall-store-user/stores/save/';
            $edit_store_url = 'mall-store-user/stores/save/';
        }

        $this->data['store_list_url'] = $store_list_url;
        $this->data['filter_list_url'] = $filter_list_url;
        $this->data['store_details_url'] = $store_details_url;
        $this->data['add_store_url'] = $add_store_url;
        $this->data['edit_store_url'] = $edit_store_url;

        $this->template->load('user', 'Common/Store/index', $this->data);
    }

    public function filter_stores() {

        $filter_array = $this->Common_model->create_datatable_request($this->input->post());

        $filter_array['order_by'] = array(tbl_store . '.id_store' => 'DESC');
        $filter_array['group_by'] = array(tbl_store . '.id_store');
        $filter_array['where'] = array(
            tbl_store . '.is_delete' => IS_NOT_DELETED_STATUS,
            tbl_user . '.is_delete' => IS_NOT_DELETED_STATUS,
            tbl_country . '.is_delete' => IS_NOT_DELETED_STATUS,
        );

        $filter_array['where_with_sign'][] = 'country.id_country = store.id_country';
        $filter_array['where_with_sign'][] = 'user.id_user = store.id_users';

        if ($this->loggedin_user_type == STORE_OR_MALL_ADMIN_USER_TYPE) {
            $filter_array['where'][tbl_store . '.id_users'] = $this->loggedin_user_data['user_id'];
        }
        if ($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE) {
            $filter_array['where_with_sign'][] = 'country.id_users =  ' . $this->loggedin_user_data['user_id'];
        }

        $filter_array['join'][] = array(
            'table' => tbl_user . ' as user',
            'condition' => tbl_user . '.id_user = ' . tbl_store . '.id_users',
            'join_type' => 'left',
        );
        $filter_array['join'][] = array(
            'table' => tbl_country . ' as country',
            'condition' => tbl_country . '.id_country = ' . tbl_store . '.id_country',
            'join_type' => 'left',
        );

        $filter_records = $this->Common_model->get_filtered_records(tbl_store, $filter_array);
//        query();
        $total_filter_records = $this->Common_model->get_filtered_records(tbl_store, $filter_array, 1);

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->Common_model->master_count(tbl_store),
            "recordsFiltered" => $total_filter_records,
            "data" => $filter_records,
        );
        echo json_encode($output);
    }

    public function get_store_details($store_id = NULL) {
        $response = array(
            'status' => '0',
            'sub_view' => '0',
        );
        if (isset($store_id) && !empty($store_id)) {

            $select_store = array(
                'table' => tbl_store . ' store',
                'fields' => array('store.store_name', 'store.store_logo store_store_logo', 'store.status store_status', 'store.created_date store_created_date', 'store.telephone store_telephone', 'store.website store_website', 'store.facebook_page store_facebook_page', 'user.first_name user_first_name', 'user.last_name user_last_name', 'user.email_id user_email_id', 'user.mobile user_mobile'),
                'where' => array('store.id_store' => $store_id, 'store.is_delete' => IS_NOT_DELETED_STATUS),
                'join' => array(
                    array(
                        'table' => tbl_user . ' as user',
                        'condition' => tbl_user . '.id_user = ' . tbl_store . '.id_users',
                        'join' => 'left'
                    )
                )
            );

            $store_details = $this->Common_model->master_single_select($select_store);

            if (isset($store_details) && !empty($store_details)) {
                $select_store_category = array(
                    'table' => tbl_store_category . ' store_category',
                    'fields' => array('category.category_name, sub_category.sub_category_name'),
                    'where' => array('store_category.id_store' => $store_id, 'store_category.is_delete' => IS_NOT_DELETED_STATUS),
                    'join' => array(
                        array(
                            'table' => tbl_category . ' as category',
                            'condition' => 'category.id_category = store_category.id_category',
                            'join' => 'left'
                        ),
                        array(
                            'table' => tbl_sub_category . ' as sub_category',
                            'condition' => 'sub_category.id_sub_category = store_category.id_sub_category',
                            'join' => 'left'
                        )
                    )
                );
                $store_categories = $this->Common_model->master_select($select_store_category);

                $this->data['store_details'] = $store_details;
                $this->data['store_categories'] = $store_categories;

                $html = $this->load->view('Common/Store/details', $this->data, TRUE);
                $response = array(
                    'status' => '1',
                    'sub_view' => $html,
                );
            }
        }
        echo json_encode($response);
    }

    public function save($id = NULL) {

        $date = date('Y-m-d h:i:s');
        $back_url = '';
        $img_name = $image_name = '';
        $country_id = 0;
        $download_locations_url = '';

        if ($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE) {
            $back_url = 'country-admin/stores';
            $download_locations_url = 'country-admin/stores/loacation_excel_download/' . $id;
        } elseif ($this->loggedin_user_type == STORE_OR_MALL_ADMIN_USER_TYPE) {
            $back_url = 'mall-store-user/stores';
        }

        $status_arr = array(
            ACTIVE_STATUS => 'Active',
            IN_ACTIVE_STATUS => 'Inactive',
        );
        $this->bread_crum[] = array(
            'url' => $back_url,
            'title' => ' List',
        );

        if (!is_null($id)) {

            $this->data['title'] = $this->data['page_header'] = 'Edit Store';
            $this->bread_crum[] = array(
                'url' => '',
                'title' => 'Edit Store'
            );

            $select_store = array(
                'table' => tbl_store . ' store',
                'fields' => '*, store.status store_status, country.id_users con',
                'where' => array(
                    'store.is_delete' => IS_NOT_DELETED_STATUS,
                    'user.is_delete' => IS_NOT_DELETED_STATUS,
                    'country.is_delete' => IS_NOT_DELETED_STATUS,
                    'store.id_store' => $id
                ),
                'where_with_sign' => array(
                    'store.status IN (' . ACTIVE_STATUS . ', ' . IN_ACTIVE_STATUS . ', ' . NOT_VERIFIED_STATUS . ')',
                    'country.id_country = store.id_country',
                    'user.id_user = store.id_users'
                ),
                'join' => array(
                    array(
                        'table' => tbl_user . ' as user',
                        'condition' => tbl_store . '.id_users = ' . tbl_user . '.id_user',
                        'join' => 'left',
                    ),
                    array(
                        'table' => tbl_country . ' as country',
                        'condition' => tbl_country . '.id_country = ' . tbl_store . '.id_country',
                        'join' => 'left',
                    )
                )
            );

            if ($this->loggedin_user_type == STORE_OR_MALL_ADMIN_USER_TYPE)
                $select_store['where_with_sign'][] = 'FIND_IN_SET("' . $this->loggedin_user_data['user_id'] . '", store.id_users) <> 0';

            if ($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE)
                $select_store['where_with_sign'][] = 'FIND_IN_SET("' . $this->loggedin_user_data['user_id'] . '", country.id_users) <> 0';

            $store_details = $this->Common_model->master_single_select($select_store);

            if (isset($store_details) && sizeof($store_details) > 0) {

                $image_name = $store_details['store_logo'];
                $country_id = $store_details['id_country'];
                $select_store_category = array(
                    'table' => tbl_store_category . ' store_category',
                    'fields' => array('id_store_category', 'category.id_category', 'sub_category.id_sub_category', 'category.category_name', 'sub_category.sub_category_name'),
                    'where' => array('store_category.id_store' => $id, 'store_category.is_delete' => IS_NOT_DELETED_STATUS),
                    'join' => array(
                        array(
                            'table' => tbl_category . ' as category',
                            'condition' => 'category.id_category = store_category.id_category',
                            'join' => 'left'
                        ),
                        array(
                            'table' => tbl_sub_category . ' as sub_category',
                            'condition' => 'sub_category.id_sub_category = store_category.id_sub_category',
                            'join' => 'left'
                        )
                    )
                );
                $store_categories = $this->Common_model->master_select($select_store_category);

                $select_sales_trend = array(
                    'table' => tbl_sales_trend,
                    'where' => array('id_store' => $id, 'is_delete' => IS_NOT_DELETED_STATUS)
                );
                $sales_trends = $this->Common_model->master_select($select_sales_trend);

                $select_store_mall_locations = array(
                    'table' => tbl_store_location,
                    'where' => array('id_store' => $id, 'is_delete' => IS_NOT_DELETED_STATUS, 'location_type' => MALL_LOCATION_TYPE),
                    'where_with_sign' => array('id_location > 0')
                );
                $store_malls = $this->Common_model->master_select($select_store_mall_locations);
                if (isset($store_malls) && sizeof($store_malls) > 0) {
                    $store_malls_list = array_column($store_malls, 'id_location');
                    $this->data['store_malls_list'] = $store_malls_list;
                }

                $this->data['store_details'] = $store_details;
                $this->data['store_categories'] = $store_categories;
                $this->data['sales_trends'] = $sales_trends;
            } else {
                redirect($back_url);
            }

            if ($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE && $store_details['store_status'] == NOT_VERIFIED_STATUS) {
                $status_arr[NOT_VERIFIED_STATUS] = 'Not Verified';
            }
        } else {
            $this->data['title'] = $this->data['page_header'] = 'Add Store';
            $this->bread_crum[] = array(
                'url' => '',
                'title' => 'Add Store',
            );
            $select_country = array(
                'table' => tbl_store . ' store',
                'where' => array(
                    'store.status' => ACTIVE_STATUS,
                    'store.is_delete' => IS_NOT_DELETED_STATUS,
                    'user.is_delete' => IS_NOT_DELETED_STATUS,
                    'country.is_delete' => IS_NOT_DELETED_STATUS
                ),
                'where_with_sign' => array(
                    'country.id_country = store.id_country',
                    'user.id_user = store.id_users'
                ),
                'join' => array(
                    array(
                        'table' => tbl_user . ' as user',
                        'condition' => tbl_store . '.id_users = ' . tbl_user . '.id_user',
                        'join' => 'left',
                    ),
                    array(
                        'table' => tbl_country . ' as country',
                        'condition' => tbl_country . '.id_country = ' . tbl_store . '.id_country',
                        'join' => 'left',
                    )
                )
            );

            if ($this->loggedin_user_type == STORE_OR_MALL_ADMIN_USER_TYPE)
                $select_country['where_with_sign'] = array('FIND_IN_SET("' . $this->loggedin_user_data['user_id'] . '", store.id_users) <> 0');
            if ($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE)
                $select_country['where_with_sign'] = array('FIND_IN_SET("' . $this->loggedin_user_data['user_id'] . '", country.id_users) <> 0');

            $country_details = $this->Common_model->master_single_select($select_country);

            if (isset($country_details) && sizeof($country_details) > 0) {
                $country_id = $country_details['id_country'];
            }
        }

        if ($this->input->post()) {

            $file_name = '';

            $validate_fields = array(
                'store_name',
                'website',
                'facebook_page',
                'store_logo',
            );

            if ($this->input->post('email_id') != '') {
                $validate_fields[] = 'first_name';
                $validate_fields[] = 'last_name';
                $validate_fields[] = 'email_id';
                $validate_fields[] = 'telephone';
            }

            $validate_fields[] = 'category_count';

            if ($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE)
                $validate_fields[] = 'location_count';

            if ($this->loggedin_user_type == STORE_OR_MALL_ADMIN_USER_TYPE && is_null($id))
                $validate_fields[] = 'terms_condition';

            if ($this->_validate_form($validate_fields)) {
                $user_id = 0;
                $delete_store_category = array();
                $delete_store_place = array();
                $delete_store_sales_trend = array();

                $exist_store_category_ids = array();
                $exist_store_place_ids = array();
                $exist_store_sales_trend_ids = array();

                $do_store_image_has_error = false;
                $do_location_file_has_error = false;

                //Upload Store Logo Image
                if (isset($_FILES['store_logo'])) {
                    if (($_FILES['store_logo']['size']) > 0) {
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

                //Upload Excel Sheet to add new Locations
                if (isset($_FILES['location_excel'])) {
                    if (($_FILES['location_excel']['size']) > 0) {

                        $file_path = $_SERVER['DOCUMENT_ROOT'] . location_excel_img_path;
                        if (!file_exists($file_path)) {
                            $this->Common_model->created_directory($file_path);
                        }
                        $supported_files = 'xlsx';
                        $new_file_name = trim(str_replace(' ', '_', $this->input->post('store_name', TRUE))) . '_' . date('Y_m_d_h_i_s');

                        $uplaoded_file_name = $this->Common_model->upload_file('location_excel', $file_path, $supported_files, $new_file_name);
                        if (empty($uplaoded_file_name)) {
                            $do_location_file_has_error = true;
                            $this->data['file_errors'] = $this->upload->display_errors();
                        } else {
                            $file_name = $uplaoded_file_name;
                            if (!is_null($id) && $id > 0) {
                                $this->load->library('excel');
                                $file_location = $_SERVER['DOCUMENT_ROOT'] . location_excel_img_path . $file_name;
                                $file_type = PHPExcel_IOFactory::identify($file_location);
                                $objReader = PHPExcel_IOFactory::createReader($file_type);
                                $objPHPExcel = $objReader->load($file_location);
                                $sheet_data = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);

                                foreach ($sheet_data as $key => $data) {
                                    if (isset($data['A']) && isset($data['B']) && !empty($data['A']) && !empty($data['B']) && $key > 1) {

                                        $latitude = $data['A'];
                                        $longitude = $data['B'];
                                        $select_location = array(
                                            'table' => tbl_store_location,
                                            'where' => array(
                                                'id_store' => $id,
                                                'is_delete' => IS_NOT_DELETED_STATUS,
                                                'latitude' => $latitude,
                                                'longitude' => $longitude
                                            )
                                        );
                                        $locations = $this->Common_model->master_single_select($select_location);

                                        if (isset($locations) && sizeof($locations) > 0) {
                                            
                                        } else {
                                            $in_store_location = array(
                                                'id_store' => $id,
                                                'latitude' => $latitude,
                                                'longitude' => $longitude,
                                                'id_location' => 0,
                                                'location_type' => STORE_LOCATION_TYPE,
                                                'contact_number' => $this->input->post('mobile', TRUE),
                                                'created_date' => $date,
                                                'is_testdata' => (ENVIRONMENT !== 'production') ? 1 : 0,
                                                'is_delete' => IS_NOT_DELETED_STATUS
                                            );

                                            $this->Common_model->master_save(tbl_store_location, $in_store_location);
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        if (!empty($_FILES['location_excel']['tmp_name'])) {
                            $do_location_file_has_error = true;
                            $this->data['file_errors'] = 'Invalid File';
                        }
                    }
                }

                if (!$do_store_image_has_error && !$do_location_file_has_error) {
                    $store_data = array(
                        'store_name' => $this->input->post('store_name', TRUE),
                        'store_logo' => $image_name,
                        'website' => $this->input->post('website', TRUE),
                        'facebook_page' => $this->input->post('facebook_page', TRUE),
                        'telephone' => ($this->input->post('mobile', TRUE) != '') ? $this->input->post('mobile', TRUE) : ' '
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

                    if ($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE)
                        $store_data['status'] = $this->input->post('status', TRUE);
                    else
                        $store_data['status'] = NOT_VERIFIED_STATUS;

                    $store_data['id_users'] = $user_id;

                    if ($id) {

                        $store_data['modified_date'] = $date;
                        $where_store = array('id_store' => $id);
                        $this->Common_model->master_update(tbl_store, $store_data, $where_store);

//                        pr($_POST);
                        $mall_presence = $this->input->post('id_malls', TRUE);

                        //check with exist Data
                        if (isset($store_malls_list) && sizeof($store_malls_list) > 0) {
                            $remove_exist_store_malls_list = array();
                            foreach ($store_malls_list as $list) {
                                if (isset($mall_presence) && sizeof($mall_presence) > 0 && in_array($list, $mall_presence)) {
                                    
                                } else
                                    $remove_exist_store_malls_list[] = $list;
                            }
                            if (isset($remove_exist_store_malls_list) && sizeof($remove_exist_store_malls_list) > 0) {
                                $this->db->query('UPDATE ' . tbl_store_location . ' SET is_delete=' . IS_DELETED_STATUS .
                                        ' WHERE is_delete=' . IS_NOT_DELETED_STATUS . ' AND id_store=' . $id
                                        . ' AND location_type=' . MALL_LOCATION_TYPE . ' AND id_location IN (' . implode(',', $remove_exist_store_malls_list) . ')');
                            }
                        }

                        if (isset($mall_presence) && sizeof($mall_presence) > 0) {
                            foreach ($mall_presence as $mall) {
                                if (isset($store_malls_list) && sizeof($store_malls_list) > 0 && in_array($mall, $store_malls_list)) {
                                    
                                } else {
                                    $in_store_location_data = array(
                                        'id_store' => $id,
                                        'id_location' => $mall,
                                        'location_type' => MALL_LOCATION_TYPE,
                                        'contact_number' => ($this->input->post('mobile', TRUE) != '') ? $this->input->post('mobile', TRUE) : ' ',
                                        'created_date' => $date,
                                        'is_testdata' => (ENVIRONMENT !== 'production') ? 1 : 0,
                                        'is_delete' => IS_NOT_DELETED_STATUS
                                    );
                                    $this->Common_model->master_save(tbl_store_location, $in_store_location_data);
                                }
                            }
                        }

                        $this->add_category_sub_category($date, $id);
                        $this->add_locations($date, $id, $country_id);
                        $this->add_sales_trend($date, $id);

                        if (isset($store_categories) && sizeof($store_categories) > 0) {
                            foreach ($store_categories as $cat) {

                                if ($this->input->post('exist_category_' . $cat['id_store_category'], TRUE) != '') {
                                    $update_store_category = array(
                                        'id_category' => $this->input->post('exist_category_' . $cat['id_store_category'], TRUE),
                                        'id_sub_category' => $this->input->post('exist_sub_category_' . $cat['id_store_category'], TRUE),
                                        'modified_date' => $date
                                    );
                                    $where_store_category = array('id_store_category' => $cat['id_store_category']);
                                    $this->Common_model->master_update(tbl_store_category, $update_store_category, $where_store_category);
                                } else
                                    $delete_store_category[] = $cat['id_store_category'];
                            }

                            if (isset($delete_store_category) && sizeof($delete_store_category) > 0) {
                                $update_store_category_data = 'is_delete = ' . IS_DELETED_STATUS . ' , modified_date = "' . $date . '"';
                                $where_store_category_data = 'id_store_category IN (' . implode(',', $delete_store_category) . ')';
                                $this->Common_model->master_update(tbl_store_category, $update_store_category_data, $where_store_category_data, TRUE);
                            }
                        }

                        if (isset($sales_trends) && sizeof($sales_trends) > 0) {
                            foreach ($sales_trends as $trend) {
                                if ($this->input->post('exist_from_date_' . $trend['id_sales_trend'], TRUE) != '') {

                                    $from_date = $this->input->post('exist_from_date_' . $trend['id_sales_trend'], TRUE);
                                    $from_date_text = date('y-m-d', strtotime($from_date));
                                    $to_date = $this->input->post('exist_to_date_' . $trend['id_sales_trend'], TRUE);
                                    $to_date_text = date('y-m-d', strtotime($to_date));
                                    $update_store_sales_trend = array(
                                        'from_date' => $from_date_text,
                                        'to_date' => $to_date_text,
                                        'modified_date' => $date
                                    );
                                    $where_store_sales_trend = array('id_sales_trend' => $trend['id_sales_trend']);
                                    $this->Common_model->master_update(tbl_sales_trend, $update_store_sales_trend, $where_store_sales_trend);
                                } else {
                                    $delete_store_sales_trend[] = $trend['id_sales_trend'];
                                }
                            }

                            if (isset($delete_store_sales_trend) && sizeof($delete_store_sales_trend) > 0) {
                                $update_store_sales_trend = 'is_delete = ' . IS_DELETED_STATUS . ' , modified_date = "' . $date . '"';
                                $where_store_sales_trend = 'id_sales_trend IN (' . implode(',', $delete_store_sales_trend) . ')';
                                $this->Common_model->master_update(tbl_sales_trend, $update_store_sales_trend, $where_store_sales_trend, TRUE);
                            }
                        }
                        $this->session->set_flashdata('success_msg', 'Store updated successfully');
                    } else {
                        $store_data['created_date'] = date('Y-m-d h:i:s');
                        $store_data['is_testdata'] = (ENVIRONMENT !== 'production') ? 1 : 0;
                        $store_data['is_delete'] = IS_NOT_DELETED_STATUS;

                        $store_id = $this->Common_model->master_save(tbl_store, $store_data);

                        $this->add_category_sub_category($date, $store_id);
                        $this->add_locations($date, $store_id, $country_id);
                        $this->add_sales_trend($date, $store_id);
                        $this->session->set_flashdata('success_msg', 'Store added successfully');
                    }

                    if (isset($store_data['status']) && $store_data['status'] == NOT_VERIFIED_STATUS) {
                        //Send Email to Country Admin for added new store.
                        $country_admin_data = array(
                            'table' => tbl_country . ' country',
                            'fields' => array('user.email_id'),
                            'where' => array(
                                'country.id_country' => $country_id,
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
                        }
                    }

                    redirect($back_url);
                }
            }
        }

        $select_category = array(
            'table' => tbl_category,
            'where' => array('status' => ACTIVE_STATUS, 'is_delete' => IS_NOT_DELETED_STATUS),
            'order_by' => array('sort_order' => 'ASC')
        );
        $category_list = $this->Common_model->master_select($select_category);

        $select_country = array(
            'table' => tbl_country,
            'where' => array('status' => ACTIVE_STATUS, 'is_delete' => IS_NOT_DELETED_STATUS)
        );
        $country_list = $this->Common_model->master_select($select_country);

        $select_mall = array(
            'table' => tbl_mall,
            'where' => array('is_delete' => IS_NOT_DELETED_STATUS, 'status' => ACTIVE_STATUS, 'id_country' => $country_id)
        );
        $malls_list = $this->Common_model->master_select($select_mall);

        $this->data['status_list'] = $status_arr;
        $this->data['category_list'] = $category_list;
        $this->data['country_list'] = $country_list;
        $this->data['malls_list'] = $malls_list;
        $this->data['country_id'] = $country_id;
        $this->data['download_locations_url'] = $download_locations_url;
        $this->data['back_url'] = $back_url;

        $this->template->load('user', 'Common/Store/form', $this->data);
    }

    public function _validate_form($validate_fields) {

        if (in_array('store_name', $validate_fields)) {
            $validation_rules[] = array(
                'field' => 'store_name',
                'label' => 'Store Name',
                'rules' => 'trim|required|min_length[2]|callback_check_store_name|max_length[250]|htmlentities'
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
        if (in_array('store_logo', $validate_fields)) {

            $check_store_validation = '';
            if ($this->input->post('store_id') == '' || (isset($_FILES['store_logo']) && $_FILES['store_logo']['size'] > 0))
                $check_store_validation = '|callback_custom_store_logo[store_logo]';
            $validation_rules[] = array(
                'field' => 'store_logo',
                'label' => 'Store Logo',
                'rules' => 'trim' . $check_store_validation . '|htmlentities'
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
//            if ($_FILES[$image_control]['type'] != 'image/jpeg' && $_FILES[$image_control]['type'] != 'image/jpg' && $_FILES[$image_control]['type'] != 'image/gif' && $_FILES[$image_control]['type'] != 'image/png') {
            if ($_FILES[$image_control]['type'] != 'image/jpeg' && $_FILES[$image_control]['type'] != 'image/jpg' && $_FILES[$image_control]['type'] != 'image/png') {
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
            if ($this->input->post('store_id', TRUE) == '') {
                $this->form_validation->set_message('custom_store_logo', 'The {field} field is required.');
                return FALSE;
            } else {
                return TRUE;
            }
        }
        return TRUE;
    }

    function check_store_name($store_name) {

        if ($this->input->post('store_id', TRUE) != '') {
            $select_data = array(
                'table' => tbl_store,
                'where' => array(
                    'is_delete' => IS_NOT_DELETED_STATUS,
                    'store_name' => $store_name,
                    'id_store' => $this->input->post('store_id', TRUE)
                ),
                'where_with_sign' => array('id_store <>' . $this->input->post('store_id', TRUE))
            );
        } else {
            $select_data = array(
                'table' => tbl_store,
                'where' => array(
                    'is_delete' => IS_NOT_DELETED_STATUS,
                    'store_name' => $store_name
                )
            );
        }
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

    public function delete($id) {

        if (!is_null($id) && $id > 0 && $this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE) {

            $update_data = array('is_delete' => IS_DELETED_STATUS);
            $where_data = array('is_delete' => IS_NOT_DELETED_STATUS, 'id_store' => $id);

            $is_updated = $this->Common_model->master_update(tbl_store, $update_data, $where_data);
            if ($is_updated)
                $this->session->set_flashdata('success_msg', 'Store deleted successfully.');
            else
                $this->session->set_flashdata('error_msg', 'Invalid request sent to delete Store. Please try again later.');
            redirect('country-admin/stores');
        } else {
            dashboard_redirect($this->loggedin_user_type);
        }
    }

    function add_category_sub_category($date = NULL, $store_id = NULL) {

        if (!is_null($store_id) && $store_id > 0) {
            $category_count = $this->input->post('category_count', TRUE);
            for ($i = 0; $i <= $category_count; $i++) {
                if ($this->input->post('category_' . $i, TRUE) > 0) {
                    $in_category_data = array(
                        'id_store' => $store_id,
                        'id_category' => $this->input->post('category_' . $i, TRUE),
                        'id_sub_category' => ($this->input->post('sub_category_' . $i, TRUE) > 0 ) ? $this->input->post('sub_category_' . $i, TRUE) : 0,
                        'created_date' => $date,
                        'contact_number' => ($this->input->post('mobile', TRUE) != '') ? $this->input->post('mobile', TRUE) : ' ',
                        'is_testdata' => (ENVIRONMENT !== 'production') ? 1 : 0,
                        'is_delete' => IS_NOT_DELETED_STATUS
                    );
                    $this->Common_model->master_save(tbl_store_category, $in_category_data);
                }
            }
        }
    }

    function add_locations($date = NULL, $store_id = NULL, $country_id = NULL) {

        $location_count = $this->input->post('location_count', TRUE);
        for ($i = 0; $i <= $location_count; $i++) {

            if ($this->input->post('latitude_' . $i, TRUE) != '' && $this->input->post('longitude_' . $i, TRUE) != '') {
                $in_store_location_data = array(
                    'id_store' => $store_id,
                    'latitude' => $this->input->post('latitude_' . $i, TRUE),
                    'longitude' => $this->input->post('longitude_' . $i, TRUE),
                    'id_location' => 0,
                    'location_type' => STORE_LOCATION_TYPE,
                    'contact_number' => ($this->input->post('mobile', TRUE) != '') ? $this->input->post('mobile', TRUE) : ' ',
                    'created_date' => $date,
                    'is_testdata' => (ENVIRONMENT !== 'production') ? 1 : 0,
                    'is_delete' => IS_NOT_DELETED_STATUS
                );
                $this->Common_model->master_save(tbl_store_location, $in_store_location_data);
            }
        }
    }

    function add_sales_trend($date = NULL, $store_id = NULL) {

        if (!is_null($store_id) && $store_id > 0) {
            $sales_trend_count = $this->input->post('sales_trend_count', TRUE);
            for ($i = 0; $i <= $sales_trend_count; $i++) {
                if ($this->input->post('from_date_' . $i, TRUE) > 0) {

                    $from_date = $this->input->post('from_date_' . $i, TRUE);
                    $from_date_text = date('y-m-d', strtotime($from_date));
                    $to_date = $this->input->post('to_date_' . $i, TRUE);
                    $to_date_text = date('y-m-d', strtotime($to_date));

                    $in_sales_trend_data = array(
                        'id_store' => $store_id,
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

    function loacation_excel_download($id = NULL) {

        if (!is_null($id) && $id > 0) {
            $select_store_locatons = array(
                'table' => tbl_store_location . ' store_location',
                'fields' => array('store.id_store', 'store_location.latitude', 'store_location.longitude', 'store_location.is_delete', 'store.store_name'),
                'where' => array(
                    'store_location.is_delete' => IS_NOT_DELETED_STATUS,
                    'store.id_store' => $id
                ),
                'join' => array(
                    array(
                        'table' => tbl_store . ' as store',
                        'condition' => tbl_store . '.id_store = ' . tbl_store_location . '.id_store',
                        'join' => 'left'
                    )
                )
            );

            $store_locations = $this->Common_model->master_select($select_store_locatons);
            $columnHeader = '';
            $columnHeader = "Latitude" . "\t" . "Longitude" . "\t" . "Status" . "\t";
            $setData = '';
            $rowData = '';
            $store_name = '';
            if (isset($store_locations) && sizeof($store_locations) > 0) {
                foreach ($store_locations as $value) {
                    $store_name = $value['store_name'];
                    $value = '"' . $value['latitude'] . '"' . "\t" . '"' . $value['longitude'] . '"' . "\t" . '"' . (($value['is_delete'] == IS_NOT_DELETED_STATUS) ? 'Active' : 'Deleted') . '"' . "\t" . "\n";
                    $rowData .= $value;
                }
                $setData .= trim($rowData) . "\n";
                header("Content-type: application/octet-stream");
                header("Content-Disposition: attachment; filename=" . $store_name . "_" . date('Y_m_d_h_i_s') . ".xls");
                header("Pragma: no-cache");
                header("Expires: 0");

                echo ucwords($columnHeader) . "\n" . $setData . "\n";
            }
        }
    }

    function locations($id) {

        $back_url = '';

        if ($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE)
            $back_url = 'country-admin/stores/save/' . $id;
        elseif ($this->loggedin_user_type == STORE_OR_MALL_ADMIN_USER_TYPE)
            $back_url = 'mall-store-user/stores';

        if ($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE) {

            $select_store = array(
                'table' => tbl_store . ' store',
                'where' => array(
                    'store.is_delete' => IS_NOT_DELETED_STATUS,
                    'user.is_delete' => IS_NOT_DELETED_STATUS,
                    'country.is_delete' => IS_NOT_DELETED_STATUS,
                    'store.id_store' => $id
                ),
                'where_with_sign' => array(
                    'store.status IN (' . ACTIVE_STATUS . ', ' . IN_ACTIVE_STATUS . ', ' . NOT_VERIFIED_STATUS . ')',
                    'country.id_country = store.id_country',
                    'user.id_user = store.id_users'
                ),
                'join' => array(
                    array(
                        'table' => tbl_user . ' as user',
                        'condition' => tbl_store . '.id_users = ' . tbl_user . '.id_user',
                        'join' => 'left',
                    ),
                    array(
                        'table' => tbl_country . ' as country',
                        'condition' => tbl_country . '.id_country = ' . tbl_store . '.id_country',
                        'join' => 'left',
                    )
                )
            );

            if ($this->loggedin_user_type == STORE_OR_MALL_ADMIN_USER_TYPE)
                $select_store['where_with_sign'][] = 'FIND_IN_SET("' . $this->loggedin_user_data['user_id'] . '", store.id_users) <> 0';

            if ($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE)
                $select_store['where_with_sign'][] = 'FIND_IN_SET("' . $this->loggedin_user_data['user_id'] . '", country.id_users) <> 0';

            $store_details = $this->Common_model->master_single_select($select_store);

            if (isset($store_details) && sizeof($store_details) > 0) {

                if ($this->input->post()) {

                    $delete_location_ids = $this->input->post('delete_location_ids', TRUE);
                    if (isset($delete_location_ids) && sizeof($delete_location_ids) > 0) {
                        $result = $this->db->query('UPDATE ' . tbl_store_location . ' SET is_delete=' . IS_DELETED_STATUS .
                                ' WHERE is_delete = ' . IS_NOT_DELETED_STATUS . ' AND id_store_location IN (' . implode(',', $delete_location_ids) . ') ');
                    }
                    if ($result)
                        $this->session->set_flashdata('success_msg', 'Location(s) deleted successfully.');
                    else
                        $this->session->set_flashdata('error_msg', 'Location(s) not deleted.');
                    redirect('country-admin/stores/locations/' . $id);
                }

                $this->data['title'] = $this->data['page_header'] = 'Edit Locations - ' . $store_details['store_name'];
                $this->bread_crum[] = array(
                    'url' => SITEURL . 'country-admin/stores',
                    'title' => 'List',
                );
                $this->bread_crum[] = array(
                    'url' => SITEURL . 'country-admin/stores/save/' . $id,
                    'title' => 'Edit ' . $store_details['store_name'],
                );
                $this->bread_crum[] = array(
                    'url' => '',
                    'title' => 'Locations',
                );

                $select_store_location = array(
                    'table' => tbl_store_location,
                    'where' => array(
                        'id_store' => $id,
                        'is_delete' => IS_NOT_DELETED_STATUS,
                        'location_type' => STORE_LOCATION_TYPE,
                        'id_location' => 0
                    )
                );

                $store_locations = $this->Common_model->master_select($select_store_location);

                $this->data['store_name'] = $store_details['store_name'];
                $this->data['store_locations'] = $store_locations;
                $this->data['back_url'] = $back_url;
                $this->data['action_url'] = 'country-admin/stores/locations/' . $id;

                $this->template->load('user', 'Common/Store/locations', $this->data);
            } else {
                redirect($back_url);
            }
        } else {
            redirect($back_url);
        }
    }

}