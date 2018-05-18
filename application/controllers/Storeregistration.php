<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Storeregistration
        extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Common_model', '', TRUE);
    }

    function index() {

        $this->data['title'] = $this->data['page_header'] = 'Store Registration';

        $select_category = array(
            'table' => tbl_category,
            'where' => array('status' => ACTIVE_STATUS),
            'order_by' => array('sort_order' => 'ASC')
        );
        $this->data['category_list'] = $this->Common_model->master_select($select_category);

        $select_country = array(
            'table' => tbl_country,
            'where' => array('status' => ACTIVE_STATUS)
        );
        $this->data['country_list'] = $this->Common_model->master_select($select_country);

        $this->load->view('Registration/store', $this->data);
    }

}
