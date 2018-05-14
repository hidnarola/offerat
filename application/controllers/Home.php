<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Home
        extends CI_Controller {

    public function index() {
        $this->data['page'] = 'home_page';
        $this->data['page_header'] = 'Offerat';
        
        
        $this->template->load('home', 'Home/index', $this->data);
//        $this->template->load('user', 'Home/index', $this->data);
    }

}
