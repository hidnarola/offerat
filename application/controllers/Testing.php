<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Testing extends CI_Controller {

    public function __construct() {
        parent::__construct();
        ini_set('error_reporting', E_ALL);
        $this->load->model('Common_model', '', TRUE);
    }

    function index() {
        
        phpinfo();

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

    function hello() {


        $result = $this->db->query('SELECT * FROM offer_announcement WHERE offer_type = 0')->result_array();

        foreach ($result as $res) {

            $in_data = array(
                'image_name' => $res['media_name'],
                'image_thumbnail' => $res['media_thumbnail'],
                'image_height' => $res['media_height'],
                'image_width' => $res['media_width'],
                'id_offer' => $res['id_offer'],
                'sort_order' => 1,
                'created_date' => $res['created_date'],
                'is_testdata' => $res['is_testdata'],
                'is_delete' => $res['is_delete']
            );
            $this->Common_model->master_save(tbl_offer_announcement_image, $in_data);
        }
//        pr($result);
    }

}
