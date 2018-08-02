<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
var_dump(extension_loaded ('zip')); 
//require_once '/phpexcel/PHPExcel.php';

set_include_path(implode(PATH_SEPARATOR, [
    realpath(__DIR__ . '/phpexcel'), // assuming Classes is in the same directory as this script
    get_include_path()
]));

require_once 'PHPExcel.php';

class Excel extends PHPExcel {

    public function __construct() {
        parent::__construct();
    }

}
