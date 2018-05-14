<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Login
        extends CI_Controller {

    public function __construct() {
        parent::__construct();        
    }

    public function index() {        
        
        $this->data['title'] = $this->data['page_header'] = 'Login';
        $this->login_template->load('index', 'Login/login', $this->data);
    }

    public function logout() {
        
    }
    
        
    public function forgot_password() {
        
        $this->data['title'] = $this->data['page_header'] = 'Forgot Password';
        $this->login_template->load('index', 'Login/forgot_password', $this->data);
    }

    public function reset_password() {
        
        $this->data['title'] = $this->data['page_header'] = 'Reset Password';
        $this->login_template->load('index', 'Login/reset_password', $this->data);
    }
}
