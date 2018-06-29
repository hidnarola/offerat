<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Verify_offers extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Common_model', '', TRUE);
        $this->data['title'] = $this->data['page_header'] = 'Verify Offers';
    }

    public function index() {

        $select_stores = array(
            'table' => tbl_store . ' store',
            'fields' => array('store.id_store', 'store.store_name', 'sales_trend.from_date', 'sales_trend.to_date'),
//            'where' => array(
//                'store.status' => ACTIVE_STATUS,
//                'store.is_delete' => IS_NOT_DELETED_STATUS,
//            ),
            'join' => array(
                array(
                    'table' => tbl_sales_trend . ' as sales_trend',
                    'condition' => 'sales_trend.id_store = store.id_store AND store.is_delete = ' . IS_NOT_DELETED_STATUS,
                    'join' => 'right',
                )
            ),
            'group_by' => array('sales_trend.id_sales_trend')
        );

        $store_store_sales_trends = $this->Common_model->master_select($select_stores);
        pr($store_store_sales_trends);
    }

}
