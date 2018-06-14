<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Testing extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Common_model', '', TRUE);
    }

    public function crop() {
        $file_name = 'ewewwewewewewestore2.png';
        $target_file = $_SERVER['DOCUMENT_ROOT'] . offer_media_path . $file_name;
        $destination = $_SERVER['DOCUMENT_ROOT'] . offer_media_thumbnail_path . $file_name;
        $destination_ = $_SERVER['DOCUMENT_ROOT'] . offer_media_thumbnail_path;
        $command = '~/bin/ffmpeg  -i ' . $target_file . "  -ss 00:00:1.435  -vframes 1 " . $destination_ . "_videoimg.jpg";
        exec($command, $a, $b);
        pr($a);
        echo '<br>';
        pr($b);
        if (!$b) {
            echo 'here';
        }

//        echo phpinfo();
        die();
        echo 'hello';


        $this->Common_model->crop_product_image($target_file, MEDIA_THUMB_IMAGE_WIDTH, MEDIA_THUMB_IMAGE_HEIGHT, $destination);
    }

}
