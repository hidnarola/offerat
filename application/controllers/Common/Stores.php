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

        $filter_list_url = 'country-admin/stores/filter_stores';

        $this->data['filter_list_url'] = $filter_list_url;
        $this->template->load('user', 'Common/Store/index', $this->data);
    }

    public function filter_stores() {

        $filter_array = $this->Common_model->create_datatable_request($this->input->post());

        $filter_array['order_by'] = array(tbl_store . '.id_store' => 'DESC');
        $filter_array['group_by'] = array(tbl_store . '.id_store');
        $filter_array['where'] = array(tbl_store . '.is_delete' => IS_NOT_DELETED_STATUS);

        if ($this->loggedin_user_type == STORE_OR_MALL_ADMIN_USER_TYPE) {
            $filter_array['where'][] = array();
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

}
