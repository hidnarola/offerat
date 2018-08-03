<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Uploads extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Common_model', '', TRUE);
    }

    /*
     * Initiate UploadHandler class when the image is uploaded
     */

    public function index() {

//        $extension = explode(".", $_FILES['files']['name'][0]);
//        $file_name = time() . '_' . rand(11111, 99999) . '_image.' . $extension[1];
//        $target_file = $_SERVER['DOCUMENT_ROOT'] . offer_media_path . $file_name;
//        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
//
//        if (move_uploaded_file($_FILES['files']['tmp_name'][0], $target_file)) {
//            $destination = $_SERVER['DOCUMENT_ROOT'] . offer_media_thumbnail_path . $file_name;
//            $this->Common_model->crop_product_image($target_file, $destination, MEDIA_THUMB_IMAGE_WIDTH, MEDIA_THUMB_IMAGE_HEIGHT);
//        }
//        return json_encode('result');

        if ($this->input->post()) {
            $upload_handler = new UploadHandler();
            exit;
        }
    }

}
