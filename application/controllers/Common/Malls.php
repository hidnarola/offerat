<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Malls extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Common_model', '', TRUE);
        //STORE_OR_MALL_ADMIN_USER_TYPE
        if (!in_array($this->loggedin_user_type, array(COUNTRY_ADMIN_USER_TYPE)))
            redirect('/');
    }

    public function index() {

        $this->data['title'] = $this->data['page_header'] = 'Mall List';

        $filter_list_url = 'country-admin/malls/filter_malls';

        $this->data['filter_list_url'] = $filter_list_url;
        $this->template->load('user', 'Common/Mall/index', $this->data);
    }

    public function filter_malls() {

        $filter_array = $this->Common_model->create_datatable_request($this->input->post());

        $filter_array['order_by'] = array(tbl_mall . '.id_mall' => 'DESC');
        $filter_array['group_by'] = array(tbl_mall . '.id_mall');
        $filter_array['where'] = array(tbl_mall . '.is_delete' => IS_NOT_DELETED_STATUS);

        if ($this->loggedin_user_type == STORE_OR_MALL_ADMIN_USER_TYPE) {
            $filter_array['where'][] = array();
        }
        $filter_array['join'][] = array(
            'table' => tbl_user . ' as user',
            'condition' => tbl_user . '.id_user = ' . tbl_mall . '.id_users',
            'join_type' => 'left',
        );

        $filter_records = $this->Common_model->get_filtered_records(tbl_mall, $filter_array);
//            query();
        $total_filter_records = $this->Common_model->get_filtered_records(tbl_mall, $filter_array, 1);

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->Common_model->master_count(tbl_mall),
            "recordsFiltered" => $total_filter_records,
            "data" => $filter_records,
        );
        echo json_encode($output);
    }

}
