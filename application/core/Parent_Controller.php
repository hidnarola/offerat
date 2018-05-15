<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Parent_Controller
        extends CI_Controller {

    public function __construct() {
        
        echo $loggedin_user_type = $CI->session->userdata('loggedin_user_type');
        
    }

    
    public function is_logged_in() {
        
    }
}
