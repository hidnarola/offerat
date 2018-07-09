<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    public function index() {
        $this->data['page'] = 'home_page';
        $this->data['page_header'] = $this->data['title'] = 'Offerat';


        $this->template->load('home', 'Home/index', $this->data);
//        $this->template->load('user', 'Home/index', $this->data);
    }

    public function js_disabled() {
//        $this->login_template->load('backend_login', 'Backend_login/js_error');
        $this->load->view('errors/html/js_error');
    }

}
