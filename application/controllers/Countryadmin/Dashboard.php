<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard
        extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->data['title'] = $this->data['page_header'] = 'Dashboard';
    }

    /*
     * Dashboard
     */

    public function index() {
        $this->data['page'] = 'dashboard_page';
        $this->data['page_header'] = 'Dashboard';

        $this->template->load('user', 'Countryadmin/Dashboard/index', $this->data);
    }

}
