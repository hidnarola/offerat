<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller
        extends CI_Controller {

    public function __construct() {
        parent::__construct();
        is_logged_in();
        
        $this->loggedin_user_type = $this->session->userdata('loggedin_user_type');
        $this->loggedin_user_data = $this->session->userdata('loggedin_user_data');
    }

    
    
    
}
