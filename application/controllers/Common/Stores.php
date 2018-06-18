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
        $filter_list_url = '';
        $store_details_url = '';
        $delete_store_url = '';
        if ($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE) {
            $filter_list_url = 'country-admin/stores/filter_stores';
            $store_details_url = 'country-admin/stores/get_store_details/';
            $delete_store_url = 'country-admin/stores/delete/';
            $this->data['delete_store_url'] = $delete_store_url;
        } elseif ($this->loggedin_user_type == STORE_OR_MALL_ADMIN_USER_TYPE) {
            $filter_list_url = 'mall-store-user/stores/filter_stores';
            $store_details_url = 'mall-store-user/stores/get_store_details/';
        }

        $this->data['filter_list_url'] = $filter_list_url;
        $this->data['store_details_url'] = $store_details_url;

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

    function delete($id) {

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
