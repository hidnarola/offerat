<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Country
        extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Common_model', '', TRUE);
    }

    public function index() {
        
        $this->data['page'] = 'country_list_page';
        $this->data['page_header'] = 'Countries';


        $this->template->load('user', 'Superadmin/Country/index', $this->data);
    }

    /**
     * Display and Filter Countries
     */
    public function filter_countries() {        
        $filter_array = $this->Common_model->create_datatable_request($this->input->post());
        $filter_records = $this->Common_model->get_filtered_records(tbl_country, $filter_array);
        $total_filter_records = $this->Common_model->get_filtered_records(tbl_country, $filter_array, 1);

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->Common_model->master_count(tbl_country),
            "recordsFiltered" => $total_filter_records,
            "data" => $filter_records,
        );
        echo json_encode($output);
    }

}
