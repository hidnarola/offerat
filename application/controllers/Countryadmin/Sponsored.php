<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Sponsored extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Common_model', '', TRUE);
    }

    public function stores() {

        $this->data['title'] = $this->data['page_header'] = 'Sponsored Stores';
        $select_store = array(
            'table' => tbl_store . ' store',
            'fields' => array('store.id_store', 'store.store_name'),
            'where' => array(
                'store.is_delete' => IS_NOT_DELETED_STATUS,
                'store_category.is_delete' => IS_NOT_DELETED_STATUS,
                'sponsored_log.is_delete' => IS_NOT_DELETED_STATUS,
                'store.id_country' => $this->loggedin_user_country_data['id_country']
            ),
            'where_with_sign' => array(
                'sponsored_log.id_category = store_category.id_category',
                'sponsored_log.id_sub_category = store_category.id_sub_category',
                'sponsored_log.id_store = store.id_store'
            ),
            'join' => array(
                array(
                    'table' => tbl_store_category . ' as store_category',
                    'condition' => 'store_category.id_store = store_category.id_store',
                    'join' => 'left'
                ),
                array(
                    'table' => tbl_sponsored_log . ' as sponsored_log',
                    'condition' => '(sponsored_log.id_category = store_category.id_category AND sponsored_log.id_sub_category = store_category.id_sub_category)',
                    'join' => 'left'
                )
            ),
            'group_by' => array('id_store')
        );

        $stores_list = $this->Common_model->master_select($select_store);

//        pr($stores_list);
        $this->data['stores_list'] = $stores_list;

        $this->template->load('user', 'Countryadmin/Sponsored/index', $this->data);
    }

    function get_store_sponseored_details($store_id) {

        $select_store = array(
            'table' => tbl_store . ' store',
            'where' => array('store.id_store' => $store_id, 'store.id_country' => $this->loggedin_user_country_data['id_country'], 'store.is_delete' => IS_NOT_DELETED_STATUS),
            'join' => array(
                array(
                    'table' => tbl_country . ' as country',
                    'condition' => 'country.id_country = store.id_country',
                    'join' => 'left'
                )
            ),
            'group_by' => array('store.id_store')
        );
        $store_details = $this->Common_model->master_single_select($select_store);

        if (isset($store_details) && sizeof($store_details) > 0) {

            $select_store_category = array(
                'table' => tbl_store_category . ' store_category',
                'fields' => array('id_store_category', 'category.id_category', 'sub_category.id_sub_category', 'category.category_name', 'sub_category.sub_category_name', 'sponsored_log.position', 'sponsored_log.from_date', 'sponsored_log.to_date', 'sponsored_log.id_sponsored_log'),
                'where' => array('store_category.id_store' => $store_id, 'store_category.is_delete' => IS_NOT_DELETED_STATUS),
                'join' => array(
                    array(
                        'table' => tbl_category . ' as category',
                        'condition' => 'category.id_category = store_category.id_category',
                        'join' => 'left'
                    ),
                    array(
                        'table' => tbl_sub_category . ' as sub_category',
                        'condition' => 'sub_category.id_sub_category = store_category.id_sub_category',
                        'join' => 'left'
                    ),
                    array(
                        'table' => tbl_sponsored_log . ' as sponsored_log',
                        'condition' => 'sponsored_log.id_category = category.id_category AND (sponsored_log.id_sub_category = sub_category.id_sub_category OR sponsored_log.id_sub_category = 0) AND sponsored_log.id_store = ' . $store_id . ' AND sponsored_log.is_delete = ' . IS_NOT_DELETED_STATUS,
                        'join' => 'left'
                    )
                ),
                'group_by' => array('id_store_category')
            );
            $store_categories = $this->Common_model->master_select($select_store_category);

            $select_category = array(
                'table' => tbl_category,
                'where' => array('status' => ACTIVE_STATUS, 'is_delete' => IS_NOT_DELETED_STATUS),
                'order_by' => array('sort_order' => 'ASC')
            );
            $category_list = $this->Common_model->master_select($select_category);

            $this->data['store_categories'] = $store_categories;
            $this->data['category_list'] = $category_list;

            $html = $this->load->view('Countryadmin/Sponsored/display', $this->data, TRUE);
            $response = array(
                'status' => '1',
                'sub_view' => $html,
            );

            echo json_encode($response);
        }
    }

}
