<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Malls extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Common_model', '', TRUE);

        if (!in_array($this->loggedin_user_type, array(COUNTRY_ADMIN_USER_TYPE, STORE_OR_MALL_ADMIN_USER_TYPE)))
            redirect('/');
    }

    public function index() {

        $this->data['title'] = $this->data['page_header'] = 'Mall List';

        $filter_list_url = '';
        $store_details_url = '';
        $delete_store_url = '';

        if ($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE) {
            $filter_list_url = 'country-admin/malls/filter_malls';
            $mall_details_url = 'country-admin/malls/get_mall_details/';
            $delete_mall_url = 'country-admin/malls/delete/';
            $this->data['delete_mall_url'] = $delete_mall_url;
        } elseif ($this->loggedin_user_type == STORE_OR_MALL_ADMIN_USER_TYPE) {
            $filter_list_url = 'mall-store-user/malls/filter_malls';
            $mall_details_url = 'mall-store-user/malls/get_mall_details/';
        }

        $this->data['filter_list_url'] = $filter_list_url;
        $this->data['mall_details_url'] = $mall_details_url;

        $this->data['filter_list_url'] = $filter_list_url;
        $this->template->load('user', 'Common/Mall/index', $this->data);
    }

    public function filter_malls() {

        $filter_array = $this->Common_model->create_datatable_request($this->input->post());

        $filter_array['order_by'] = array(tbl_mall . '.id_mall' => 'DESC');
        $filter_array['group_by'] = array(tbl_mall . '.id_mall');
        $filter_array['where'][tbl_mall . '.is_delete'] = IS_NOT_DELETED_STATUS;

        if ($this->loggedin_user_type == STORE_OR_MALL_ADMIN_USER_TYPE) {
            $filter_array['where'][tbl_mall . '.id_users'] = $this->loggedin_user_data['user_id'];
        }
        $filter_array['join'][] = array(
            'table' => tbl_user . ' as user',
            'condition' => tbl_user . '.id_user = ' . tbl_mall . '.id_users',
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
                'fields' => array('mall.mall_name', 'mall.mall_logo mall_mall_logo', 'mall.status mall_status', 'mall.created_date mall_created_date', 'mall.telephone mall_telephone', 'mall.website mall_website', 'mall.facebook_page mall_facebook_page', 'user.first_name user_first_name', 'user.last_name user_last_name', 'user.email_id user_email_id', 'user.mobile user_mobile', 'mall.street', 'mall.street1', 'mall.city', 'mall.state', 'country.country_name'),
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

    function delete($id) {

        if (!is_null($id) && $id > 0 && $this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE) {

            $select_store_location = array(
                'table' => tbl_store_location,
                'where' => array(
                    'is_delete' => IS_NOT_DELETED_STATUS,
                    'id_location' => $id,
                    'location_type' => MALL_LOCATION_TYPE
                )
            );

            $store_locations = $this->Common_model->master_single_select($select_store_location);

            if (isset($store_locations) && sizeof($store_locations) > 0) {

                $this->session->set_flashdata('error_msg', 'Store is using this Mall, You can not delet this Mall.');
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

}
