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
    if ($loggedin_user_type == SUPER_ADMIN_USER_TYPE && in_array($segment1, array('super-admin', 'superadmin'))) {
        echo 1111;
//        return TRUE;
    } elseif ($loggedin_user_type == COUNTRY_ADMIN_USER_TYPE && in_array($segment1, array('country-admin', 'countryadmin'))) {
        echo 22222;
//        return TRUE;
    } elseif ($loggedin_user_type == STORE_OR_MALL_ADMIN_USER_TYPE && in_array($segment1, array('mall-store-user', 'mall_store_user'))) {
        echo 3333;
//        return TRUE;
    } 
//    elseif (in_array($loggedin_user_type, array(SUPER_ADMIN_USER_TYPE, COUNTRY_ADMIN_USER_TYPE, STORE_OR_MALL_ADMIN_USER_TYPE)) && in_array($segment1, array('login', 'forgot-password', 'reset-password'))) {
//        echo 4444;
////        redirect('super-admin/dashboard');
//    } else {
////        redirect('')
//    }
}
