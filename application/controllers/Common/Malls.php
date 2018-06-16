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

        $this->data['title'] = $this->data['page_header'] = 'Malls List';
        
        $this->template->load('user', 'Common/Mall/index', $this->data);
    }

}
