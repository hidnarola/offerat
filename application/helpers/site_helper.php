<?php

if (!function_exists('pr')) {

    function pr($value, $exit = 0) {
        echo "<pre>";
        print_r($value);
        echo "</pre>";
        if ($exit == 1)
            exit();
    }

}

if (!function_exists('query')) {

    function query($exit = 0) {
        $CI = & get_instance();
        echo $CI->db->last_query();
        if ($exit == 1)
            exit();
    }

}

function is_logged_in() {
    
    $CI = & get_instance();
    
    $loggedin_user_type = $CI->session->userdata('loggedin_user_type');
    $segment1 = $CI->uri->segment(1);
    if ( $loggedin_user_type == 1 && (in_array($segment1, array('super-admin', 'superadmin')))) {
        return TRUE;
    } elseif(!in_array($segment1, array('login'))) {
        redirect('/login');
    }
}
