<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Stores extends MY_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('Common_model', '', TRUE);
        $this->load->model('Email_template_model', '', TRUE);

        if (!in_array($this->loggedin_user_type, array(COUNTRY_ADMIN_USER_TYPE, STORE_OR_MALL_ADMIN_USER_TYPE)))
            redirect('/');
    }

    public function index() {

        $this->data['title'] = $this->data['page_header'] = 'Stores List';
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
        $filter_array['where'] = array(tbl_store . '.is_delete' => IS_NOT_DELETED_STATUS);

        if ($this->loggedin_user_type == STORE_OR_MALL_ADMIN_USER_TYPE) {
            $filter_array['where'][tbl_store . '.id_users'] = $this->loggedin_user_data['user_id'];
        }

        $filter_array['join'][] = array(
            'table' => tbl_user . ' as user',
            'condition' => tbl_user . '.id_user = ' . tbl_store . '.id_users',
            'join_type' => 'left',
        );

        $filter_records = $this->Common_model->get_filtered_records(tbl_store, $filter_array);
//            query();
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

                $select_store_locations = array(
                    'table' => tbl_place . ' place',
                    'fields' => array('place.street', 'place.street1', 'place.city', 'place.state', 'country.country_name'),
                    'where' => array(
                        'store.id_store' => $store_id,
                        'store_location.is_delete' => IS_NOT_DELETED_STATUS,
                        'place.is_delete' => IS_NOT_DELETED_STATUS
                    ),
                    'where_with_sign' => array(
                        'store_location.id_place = place.id_place',
                        'store.id_store = store_location.id_store'
                    ),
                    'join' => array(
                        array(
                            'table' => tbl_store_location . ' as store_location',
                            'condition' => 'store_location.id_place = place.id_place',
                            'join' => 'left'
                        ),
                        array(
                            'table' => tbl_store . ' as store',
                            'condition' => 'store.id_store = store_location.id_store',
                            'join' => 'left'
                        ),
                        array(
                            'table' => tbl_country . ' as country',
                            'condition' => 'country.id_country = place.id_country',
                            'join' => 'left'
                        )
                    )
                );
                $store_locations = $this->Common_model->master_select($select_store_locations);

                $this->data['store_details'] = $store_details;
                $this->data['store_categories'] = $store_categories;
                $this->data['store_locations'] = $store_locations;

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

        $back_url = '';
        $img_name = $image_name = '';
        $country_id = 0;
        if ($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE) {
            $back_url = 'country-admin/stores';
        } elseif ($this->loggedin_user_type == STORE_OR_MALL_ADMIN_USER_TYPE) {
            $back_url = 'mall-store-user/stores';
        }

        $status_arr = array(
            ACTIVE_STATUS => 'Active',
            IN_ACTIVE_STATUS => 'Inactive',
        );
        if (!is_null($id)) {

            $this->data['title'] = $this->data['page_header'] = 'Edit Store';

            $select_store = array(
                'table' => tbl_store . ' store',
                'fields' => '*, store.status store_status',
//                'where' => array('store.status' => ACTIVE_STATUS, 'store.is_delete' => IS_NOT_DELETED_STATUS, 'store.id_store' => $id),
                'where' => array('store.is_delete' => IS_NOT_DELETED_STATUS, 'store.id_store' => $id),
                'where_with_sign' => array('store.status IN (' . ACTIVE_STATUS . ', ' . IN_ACTIVE_STATUS . ', ' . NOT_VERIFIED_STATUS . ')')
            );

            $select_store['join'][] = array(
                'table' => tbl_user . ' as user',
                'condition' => tbl_store . '.id_users = ' . tbl_user . '.id_user',
                'join' => 'left',
            );

            if ($this->loggedin_user_type == STORE_OR_MALL_ADMIN_USER_TYPE)
                $select_store['where_with_sign'] = array('FIND_IN_SET("' . $this->loggedin_user_data['user_id'] . '", store.id_users) <> 0');

            $select_store['join'][] = array(
                'table' => tbl_store_location . ' as store_location',
                'condition' => tbl_store_location . '.id_store = ' . tbl_store . '.id_store',
                'join' => 'left',
            );
            $select_store['join'][] = array(
                'table' => tbl_place . ' as place',
                'condition' => tbl_place . '.id_place = ' . tbl_store_location . '.id_place',
                'join' => 'left',
            );
            $select_store['join'][] = array(
                'table' => tbl_country . ' as country',
                'condition' => tbl_country . '.id_country = ' . tbl_place . '.id_country',
                'join' => 'left',
            );
            if ($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE)
                $select_store['where_with_sign'] = array('FIND_IN_SET("' . $this->loggedin_user_data['user_id'] . '", country.id_users) <> 0');

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

                $select_store_locations = array(
                    'table' => tbl_place . ' place',
                    'fields' => array('place.id_place', 'store_location.id_location', 'place.latitude', 'place.longitude', 'place.id_google place_id', 'place.street', 'place.street1', 'place.city', 'place.state', 'country.country_name'),
                    'where' => array(
                        'store.id_store' => $id,
                        'store_location.is_delete' => IS_NOT_DELETED_STATUS,
                        'place.is_delete' => IS_NOT_DELETED_STATUS
                    ),
                    'where_with_sign' => array(
                        'store_location.id_place = place.id_place',
                        'store.id_store = store_location.id_store'
                    ),
                    'join' => array(
                        array(
                            'table' => tbl_store_location . ' as store_location',
                            'condition' => 'store_location.id_place = place.id_place',
                            'join' => 'left'
                        ),
                        array(
                            'table' => tbl_store . ' as store',
                            'condition' => 'store.id_store = store_location.id_store',
                            'join' => 'left'
                        ),
                        array(
                            'table' => tbl_country . ' as country',
                            'condition' => 'country.id_country = place.id_country',
                            'join' => 'left'
                        )
                    )
                );
                $store_locations = $this->Common_model->master_select($select_store_locations);

                $select_malls = array(
                    'table' => tbl_mall,
                    'fields' => array('id_mall', 'mall_name'),
                    'where' => array('id_country' => $store_details['id_country'], 'is_delete' => IS_NOT_DELETED_STATUS, 'status' => ACTIVE_STATUS)
                );
                $malls = $this->Common_model->master_select($select_malls);

                $select_sales_trend = array(
                    'table' => tbl_sales_trend,
                    'where' => array('id_store' => $id, 'is_delete' => IS_NOT_DELETED_STATUS)
                );
                $sales_trends = $this->Common_model->master_select($select_sales_trend);

                $this->data['store_details'] = $store_details;
                $this->data['store_categories'] = $store_categories;
                $this->data['store_locations'] = $store_locations;
                $this->data['malls'] = $malls;
                $this->data['sales_trends'] = $sales_trends;
            } else {
                redirect($back_url);
            }

            if ($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE) {

                $select_user_status = array(
                    'table' => tbl_store,
                    'where' => array('is_delete' => IS_NOT_DELETED_STATUS, 'status' => NOT_VERIFIED_STATUS, 'id_store' => $id)
                );
                $user_status = $this->Common_model->master_single_select($select_user_status);
                if (isset($user_status) && sizeof($user_status) > 0)
                    $status_arr[NOT_VERIFIED_STATUS] = 'Not Verified';
            }
        } else {
            $this->data['title'] = $this->data['page_header'] = 'Add Store';

            $select_country = array(
                'table' => tbl_store . ' store',
                'where' => array('store.status' => ACTIVE_STATUS, 'store.is_delete' => IS_NOT_DELETED_STATUS)
            );

            $select_country['join'][] = array(
                'table' => tbl_user . ' as user',
                'condition' => tbl_store . '.id_users = ' . tbl_user . '.id_user',
                'join' => 'left',
            );

            if ($this->loggedin_user_type == STORE_OR_MALL_ADMIN_USER_TYPE)
                $select_country['where_with_sign'] = array('FIND_IN_SET("' . $this->loggedin_user_data['user_id'] . '", store.id_users) <> 0');

            $select_country['join'][] = array(
                'table' => tbl_store_location . ' as store_location',
                'condition' => tbl_store_location . '.id_store = ' . tbl_store . '.id_store',
                'join' => 'left',
            );
            $select_country['join'][] = array(
                'table' => tbl_place . ' as place',
                'condition' => tbl_place . '.id_place = ' . tbl_store_location . '.id_place',
                'join' => 'left',
            );
            $select_country['join'][] = array(
                'table' => tbl_country . ' as country',
                'condition' => tbl_country . '.id_country = ' . tbl_place . '.id_country',
                'join' => 'left',
            );
            if ($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE)
                $select_country['where_with_sign'] = array('FIND_IN_SET("' . $this->loggedin_user_data['user_id'] . '", country.id_users) <> 0');

            $country_details = $this->Common_model->master_single_select($select_country);
            if (isset($country_details) && sizeof($country_details) > 0) {
                $country_id = $country_details['id_country'];
            }
        }

        if ($this->input->post()) {

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
            $validate_fields[] = 'location_count';

            if ($this->loggedin_user_type == STORE_OR_MALL_ADMIN_USER_TYPE && is_null($id))
                $validate_fields[] = 'terms_condition';

            if ($this->_validate_form($validate_fields)) {
                $user_id = 0;
                $date = date('Y-m-d h:i:s');
                $delete_store_category = array();
                $delete_store_place = array();
                $delete_store_sales_trend = array();

                $exist_store_category_ids = array();
                $exist_store_place_ids = array();
                $exist_store_sales_trend_ids = array();

                $do_store_image_has_error = false;

                if (isset($_FILES['store_logo'])) {
                    if (($_FILES['store_logo']['size']) > 0) {
                        $image_path = $_SERVER['DOCUMENT_ROOT'] . store_img_path;
                        if (!file_exists($image_path)) {
                            $this->Common_model->created_directory($image_path);
                        }
                        $supported_files = 'gif|jpg|png|jpeg';
                        $img_name = $this->Common_model->upload_image('store_logo', $image_path, $supported_files);
                        pr($img_name);
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
                            );
                            if ($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE)
                                $user_data['status'] = $this->input->post('status', TRUE);
                            else
                                $user_data['status'] = NOT_VERIFIED_STATUS;

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

                    if (isset($store_data['status']) && $store_data['status'] == NOT_VERIFIED_STATUS) {

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

                    $store_data['id_users'] = $user_id;

                    if ($id) {

                        $store_data['modified_date'] = $date;
                        $where_store = array('id_store' => $id);
                        $this->Common_model->master_update(tbl_store, $store_data, $where_store);

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

                        if (isset($store_locations) && sizeof($store_locations) > 0) {
                            foreach ($store_locations as $loc) {
                                if ($this->input->post('exist_mall_' . $loc['id_place'], TRUE) != '') {
                                    $update_store_location = array(
                                        'street' => $this->input->post('exist_address_' . $loc['id_place'], TRUE),
                                        'street' => $this->input->post('exist_street_' . $loc['id_place'], TRUE),
                                        'street1' => $this->input->post('exist_street1_' . $loc['id_place'], TRUE),
                                        'city' => $this->input->post('exist_city_' . $loc['id_place'], TRUE),
                                        'state' => $this->input->post('exist_state_' . $loc['id_place'], TRUE),
                                        'latitude' => $this->input->post('exist_latitude_' . $loc['id_place'], TRUE),
                                        'longitude' => $this->input->post('exist_longitude_' . $loc['id_place'], TRUE),
                                        'id_google' => $this->input->post('exist_place_id_' . $loc['id_place'], TRUE),
                                        'modified_date' => $date
                                    );
                                    $where_store_location = array('id_place' => $loc['id_place']);
                                    $this->Common_model->master_update(tbl_place, $update_store_location, $where_store_location);
                                } else {
                                    $delete_store_place[] = $loc['id_place'];
                                }
                            }

                            if (isset($delete_store_place) && sizeof($delete_store_place) > 0) {
                                $update_store_place_data = 'is_delete = ' . IS_DELETED_STATUS . ' , modified_date = "' . $date . '"';
                                $where_store_place_data = 'id_place IN (' . implode(',', $delete_store_place) . ')';
                                $this->Common_model->master_update(tbl_place, $update_store_place_data, $where_store_place_data, TRUE);
                                $this->Common_model->master_update(tbl_store_location, $update_store_place_data, $where_store_place_data, TRUE);
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

        $this->data['status_list'] = $status_arr;
        $this->data['category_list'] = $category_list;
        $this->data['country_list'] = $country_list;

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
            if ($this->input->post('store_id') == '')
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

            if ($this->input->post('place_id_' . $i, TRUE) != '') {

                $in_place_data = array(
                    'id_google' => $this->input->post('place_id_' . $i, TRUE),
                    'street' => $this->input->post('street_' . $i, TRUE),
                    'street1' => $this->input->post('street1_' . $i, TRUE),
                    'city' => $this->input->post('city_' . $i, TRUE),
                    'state' => $this->input->post('state_' . $i, TRUE),
                    'id_country' => $country_id,
                    'latitude' => $this->input->post('latitude_' . $i, TRUE),
                    'longitude' => $this->input->post('longitude_' . $i, TRUE),
                    'created_date' => $date,
                    'is_testdata' => (ENVIRONMENT !== 'production') ? 1 : 0,
                    'is_delete' => IS_NOT_DELETED_STATUS
                );

                $place_id = $this->Common_model->master_save(tbl_place, $in_place_data);

                $in_store_location_data = array(
                    'id_store' => $store_id,
                    'id_place' => $place_id,
                    'id_location' => $this->input->post('mall_' . $i, TRUE),
                    'location_type' => ($this->input->post('mall_' . $i, TRUE) == 0) ? STORE_LOCATION_TYPE : MALL_LOCATION_TYPE,
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

}
