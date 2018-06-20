<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Stores extends MY_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('Common_model', '', TRUE);

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
        if ($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE) {
            $store_list_url = 'country-admin/stores';
            $filter_list_url = 'country-admin/stores/filter_stores';
            $store_details_url = 'country-admin/stores/get_store_details/';
            $delete_store_url = 'country-admin/stores/delete/';
            $this->data['delete_store_url'] = $delete_store_url;
            $add_store_url = 'country-admin/stores/save/';
        } elseif ($this->loggedin_user_type == STORE_OR_MALL_ADMIN_USER_TYPE) {
            $store_list_url = 'mall-store-user/stores';
            $filter_list_url = 'mall-store-user/stores/filter_stores';
            $store_details_url = 'mall-store-user/stores/get_store_details/';
            $add_store_url = 'mall-store-user/stores/save/';
        }

        $this->data['store_list_url'] = $store_list_url;
        $this->data['filter_list_url'] = $filter_list_url;
        $this->data['store_details_url'] = $store_details_url;
        $this->data['add_store_url'] = $add_store_url;

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
                'where' => array('store.status' => ACTIVE_STATUS, 'store.is_delete' => IS_NOT_DELETED_STATUS, 'store.id_store' => $id)
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
        }

        if ($this->input->post()) {

            pr($_POST);
            $date = date('Y-m-d h:i:s');
            $delete_store_category = array();
            $delete_store_place = array();
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

            $store_data = array(
                'store_name' => $this->input->post('store_name', TRUE),
                'store_logo' => $image_name,
                'website' => $this->input->post('website', TRUE),
                'facebook_page' => $this->input->post('facebook_page', TRUE),
                'telephone' => $this->input->post('telephone', TRUE)
            );

            $user_data = array(
                'email_id' => $this->input->post('email_id', TRUE),
                'first_name' => $this->input->post('first_name', TRUE),
                'last_name' => $this->input->post('last_name', TRUE),
                'mobile' => $this->input->post('mobile', TRUE),
            );

            if ($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE) {
                $store_data['status'] = $this->input->post('status', TRUE);
                $user_data['status'] = $this->input->post('status', TRUE);
            }

            if ($id) {
                $store_data['modified_date'] = $date;
                $user_data['modified_date'] = $date;

                if (isset($store_categories) && sizeof($store_categories) > 0) {
                    foreach ($store_categories as $cat) {

                        if ($this->input->post('exist_category_' . $cat['id_store_category'], TRUE) != '') {
                            $update_store_category = array(
                                'id_category' => $this->input->post('exist_category_' . $cat['id_store_category'], TRUE),
                                'id_sub_category' => $this->input->post('exist_sub_category_' . $cat['id_store_category'], TRUE)
                            );
                            $where_store_category = array('id_store_category' => $cat['id_store_category']);
//                            $this->Common_model->master_update(tbl_store_category, $update_store_category, $where_store_category);
                        } else
                            $delete_store_category[] = $cat['id_store_category'];
                    }

                    if (isset($delete_store_category) && sizeof($delete_store_category) > 0) {
                        $update_store_category_data = 'is_delete = ' . IS_NOT_DELETED_STATUS;
                        $where_store_category_data = 'id_store_category IN ("' . implode(',', $delete_store_category) . '")';
//                    $this->Common_model->master_update(tbl_store_category, $update_store_category_data, $where_store_category_data, TRUE);
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
                            );                            
                            $where_store_location = array('id_place' => $loc['id_place']);
//                            $this->Common_model->master_update(tbl_place, $update_store_location, $where_store_location);
                        } else {
                            $delete_store_place[] = $loc['id_place'];
                        }
                    }

                    if (isset($delete_store_place) && sizeof($delete_store_place) > 0) {
                        $update_store_place_data = 'is_delete = ' . IS_NOT_DELETED_STATUS;
                        $where_store_place_data = 'id_place IN ("' . implode(',', $delete_store_place) . '")';
//                        $this->Common_model->master_update(tbl_place, $update_store_place_data, $where_store_place_data, TRUE);
//                        $this->Common_model->master_update(tbl_store_location, $update_store_place_data, $where_store_place_data, TRUE);
                    }
                }

                
                $store_locations;
                $sales_trends;
            } else {
                $store_data['created_date'] = date('Y-m-d h:i:s');
                $store_data['is_testdata'] = (ENVIRONMENT !== 'production') ? 1 : 0;
                $store_data['is_delete'] = IS_NOT_DELETED_STATUS;

                $user_data['created_date'] = date('Y-m-d h:i:s');
                $user_data['is_testdata'] = (ENVIRONMENT !== 'production') ? 1 : 0;
                $user_data['is_delete'] = IS_NOT_DELETED_STATUS;
            }

            die();
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
                'rules' => 'trim|required|min_length[2]|max_length[250]|callback_check_store_name|htmlentities'
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
            $validation_rules[] = array(
                'field' => 'store_logo',
                'label' => 'Store Logo',
                'rules' => 'trim|callback_custom_store_logo[store_logo]|htmlentities'
            );
        }
        if (in_array('telephone', $validate_fields)) {
            $validation_rules[] = array(
                'field' => 'telephone',
                'label' => 'Telephone Number',
                'rules' => 'trim|required|min_length[8]|max_length[20]|htmlentities'
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
        if (in_array('mobile', $validate_fields)) {
            $validation_rules[] = array(
                'field' => 'mobile',
                'label' => 'Mobile Number',
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

}
