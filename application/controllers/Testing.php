<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Testing extends CI_Controller {

    public function __construct() {
        parent::__construct();
        ini_set('error_reporting', E_ALL);
        $this->load->model('Common_model', '', TRUE);
    }

    function index() {

//        $videoID = the_field('video_link');
//        $jsonurl = 'http://vimeo.com/api/v2/video/239882943.json';
//        $json = file_get_contents($jsonurl, 0, null, null);
//        $json_output = json_decode($json, true);
//        pr($json_output);
//        echo '<img src="' . $json_output[0]['thumbnail_large'] . '" />';
        //http://www.metacafe.com/watch/11705253/son-riding-car-at-the-mall/
//        $jsonurl = 'http://vimeo.com/api/v2/video/239882943.json';
//        $json = file_get_contents($jsonurl, 0, null, null);
//        $json_output = json_decode($json, true);
//        pr($json_output);
//        echo '<img src="' . $json_output[0]['thumbnail_large'] . '" />';
    }

    function stores_locations($id = 7) {

        $select_store_locatons = array(
            'table' => tbl_store_location . ' store_location',
            'fields' => array('store.id_store', 'store_location.latitude', 'store_location.longitude', 'store_location.is_delete', 'store.store_name'),
            'where' => array(
                'store_location.is_delete' => IS_NOT_DELETED_STATUS,
                'store.id_store' => $id
            ),
            'join' => array(
                array(
                    'table' => tbl_store . ' as store',
                    'condition' => tbl_store . '.id_store = ' . tbl_store_location . '.id_store',
                    'join' => 'left'
                )
            )
        );

        $store_locations = $this->Common_model->master_select($select_store_locatons);

        $columnHeader = '';
        $columnHeader = "Latitude" . "\t" . "Longitude" . "\t" . "Status" . "\t";
        $setData = '';
        $rowData = '';
        $store_name = '';
        if (isset($store_locations) && sizeof($store_locations) > 0) {
            foreach ($store_locations as $value) {
                $store_name = $value['store_name'];
                $value = '"' . $value['latitude'] . '"' . "\t" . '"' . $value['longitude'] . '"' . "\t" . '"' . (($value['is_delete'] == IS_NOT_DELETED_STATUS) ? 'Active' : 'Deleted') . '"' . "\t" . "\n";
                $rowData .= $value;
            }
            $setData .= trim($rowData) . "\n";
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=" . $store_name . "_" . date('Y_m_d_h_i_s') . ".xls");
            header("Pragma: no-cache");
            header("Expires: 0");

            echo ucwords($columnHeader) . "\n" . $setData . "\n";
        }
    }

    public function crop() {
//        echo phpinfo();
//        die();

        $file_name = '1528969593_video.mp4';
        $target_file = $_SERVER['DOCUMENT_ROOT'] . offer_media_path . $file_name;
        $destination = $_SERVER['DOCUMENT_ROOT'] . offer_media_thumbnail_path . $file_name;
        $destination_ = $_SERVER['DOCUMENT_ROOT'] . offer_media_thumbnail_path;
//        $command = '~/bin/ffmpeg  -i ' . $target_file . "  -ss 00:00:1.435  -vframes 1 " . $destination_ . "_videoimg.jpg";
        $command = 'C:/FFMPEG/bin/ffmpeg.exe  -i ' . $target_file . "  -ss 00:00:1.435  -vframes 1 " . $destination_ . "videoimg.jpg";
//        $thumbFile = "hello_videoimg.png";
//        $command = "ffmpeg -i $target_file -vf scale=320:240 $thumbFile";
//        $command = "C:/FFMPEG/bin/ffmpeg.exe  -i $target_file -vf scale=320:240 $thumbFile";
//        $command = '~/bin/ffmpeg -h';
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
