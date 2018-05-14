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