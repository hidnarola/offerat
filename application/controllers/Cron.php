<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('Common_model', '', TRUE);
    }

    /*
     * Update is_sponsored, sponsored_position fields in store_category table when today is between from and to date. - For store
     */

    public function update_sponsored_store_categories() {

        $select_sponsored = array(
            'table' => tbl_sponsored_log . ' sponsored_log',
            'fields' => array(
                'sponsored_log.from_date',
                'sponsored_log.to_date',
                'sponsored_log.position',
                'store.id_store',
                'store.id_country',
                'country.timezone',
                'store_category.id_category',
                'store_category.id_sub_category',
            ),
            'where' => array(
                'sponsored_log.is_delete' => IS_NOT_DELETED_STATUS,
                'store.is_delete' => IS_NOT_DELETED_STATUS,
                'store.status' => ACTIVE_STATUS
            ),
            'where_with_sign' => array(
                'store_category.id_category = sponsored_log.id_category',
                'store_category.id_sub_category = sponsored_log.id_sub_category',
                'store_category.is_sponsored <> ' . SPONSORED_TYPE
            ),
            'join' => array(
                array(
                    'table' => tbl_store . ' as store',
                    'condition' => 'store.id_store = sponsored_log.id_store',
                    'join' => 'left'
                ),
                array(
                    'table' => tbl_country . ' as country',
                    'condition' => 'country.id_country = store.id_country',
                    'join' => 'left'
                ),
                array(
                    'table' => tbl_store_category . ' as store_category',
                    'condition' => 'store_category.id_category = sponsored_log.id_category AND store_category.id_sub_category = sponsored_log.id_sub_category',
                    'join' => 'left'
                )
            ),
            'group_by' => array('sponsored_log.id_sponsored_log')
        );

        $sponsorsors = $this->Common_model->master_select($select_sponsored);

        if (isset($sponsorsors) && sizeof($sponsorsors) > 0) {
            foreach ($sponsorsors as $spo) {
                $date = date('Y-m-d H:i:s');
                $converted_today_date = new DateTime($date, new DateTimeZone($spo['timezone']));
                $converted_today_date->setTimezone(new DateTimeZone($spo['timezone']));
                $converted_today_date_ = strtotime($converted_today_date->format('Y-m-d'));

                $converted_from_date = new DateTime($spo['from_date'] . ' 00:00:00', new DateTimeZone($spo['timezone']));
                $converted_from_date->setTimezone(new DateTimeZone($spo['timezone']));
                $converted_from_date_ = strtotime($converted_from_date->format('Y-m-d'));

                $converted_to_date = new DateTime($spo['to_date'] . ' 00:00:00', new DateTimeZone($spo['timezone']));
                $converted_to_date->setTimezone(new DateTimeZone($spo['timezone']));
                $converted_to_date_ = strtotime($converted_to_date->format('Y-m-d'));

                if ($converted_from_date_ < $converted_today_date_ && $converted_to_date_ >= $converted_today_date_) {
                    $update_store_category = array(
                        'is_sponsored' => SPONSORED_TYPE,
                        'sponsored_position' => $spo['position']
                    );
                    $where_store_category = array(
                        'id_store' => $spo['id_store'],
                        'is_delete' => IS_NOT_DELETED_STATUS,
                        'id_category' => $spo['id_category'],
                        'id_sub_category' => $spo['id_sub_category']
                    );
                    $this->Common_model->master_update(tbl_store_category, $update_store_category, $where_store_category);
                }
            }
        }
    }

    /*
     * Update is_sponsored, sponsored_position fields in store_category table when today is between from and to date. - For Mall
     */

    public function update_sponsored_mall() {

        $select_sponsored = array(
            'table' => tbl_sponsored_log . ' sponsored_log',
            'fields' => array(
                'sponsored_log.from_date',
                'sponsored_log.to_date',
                'sponsored_log.position',
                'mall.id_mall',
                'mall.id_country',
                'country.timezone'
            ),
            'where' => array(
                'sponsored_log.is_delete' => IS_NOT_DELETED_STATUS,
                'mall.is_delete' => IS_NOT_DELETED_STATUS,
                'mall.status' => ACTIVE_STATUS
            ),
            'where_with_sign' => array(
                'mall.is_sponsored <> ' . SPONSORED_TYPE
            ),
            'join' => array(
                array(
                    'table' => tbl_mall . ' as mall',
                    'condition' => 'mall.id_mall = sponsored_log.id_mall',
                    'join' => 'left'
                ),
                array(
                    'table' => tbl_country . ' as country',
                    'condition' => 'country.id_country = mall.id_country',
                    'join' => 'left'
                )
            ),
            'group_by' => array('sponsored_log.id_sponsored_log')
        );

        $sponsorsors = $this->Common_model->master_select($select_sponsored);

        if (isset($sponsorsors) && sizeof($sponsorsors) > 0) {
            foreach ($sponsorsors as $spo) {
                $date = date('Y-m-d H:i:s');
                $converted_today_date = new DateTime($date, new DateTimeZone($spo['timezone']));
                $converted_today_date->setTimezone(new DateTimeZone($spo['timezone']));
                $converted_today_date_ = strtotime($converted_today_date->format('Y-m-d'));

                $converted_from_date = new DateTime($spo['from_date'] . ' 00:00:00', new DateTimeZone($spo['timezone']));
                $converted_from_date->setTimezone(new DateTimeZone($spo['timezone']));
                $converted_from_date_ = strtotime($converted_from_date->format('Y-m-d'));

                $converted_to_date = new DateTime($spo['to_date'] . ' 00:00:00', new DateTimeZone($spo['timezone']));
                $converted_to_date->setTimezone(new DateTimeZone($spo['timezone']));
                $converted_to_date_ = strtotime($converted_to_date->format('Y-m-d'));

                if ($converted_from_date_ < $converted_today_date_ && $converted_to_date_ >= $converted_today_date_) {
                    $update_mall = array(
                        'is_sponsored' => SPONSORED_TYPE,
                        'sponsored_position' => $spo['position']
                    );
                    $where_mall = array(
                        'id_mall' => $spo['id_mall'],
                        'is_delete' => IS_NOT_DELETED_STATUS
                    );
                    $this->Common_model->master_update(tbl_mall, $update_mall, $where_mall);
                }
            }
        }
    }

    /*
     * Update is_sponsored, sponsored_position fields with 0 value if duration is over. - For store
     */

    public function remove_sponsored_store_categories() {

        $select_sponsored = array(
            'table' => tbl_sponsored_log . ' sponsored_log',
            'fields' => array(
                'sponsored_log.from_date',
                'sponsored_log.to_date',
                'sponsored_log.position',
                'store.id_store',
                'store.id_country',
                'country.timezone',
                'store_category.id_category',
                'store_category.id_sub_category',
            ),
            'where' => array(
                'sponsored_log.is_delete' => IS_NOT_DELETED_STATUS,
                'store.is_delete' => IS_NOT_DELETED_STATUS,
                'store.status' => ACTIVE_STATUS,
                'store_category.is_sponsored' => SPONSORED_TYPE
            ),
            'where_with_sign' => array(
                'store_category.id_category = sponsored_log.id_category',
                'store_category.id_sub_category = sponsored_log.id_sub_category'
            ),
            'join' => array(
                array(
                    'table' => tbl_store . ' as store',
                    'condition' => 'store.id_store = sponsored_log.id_store',
                    'join' => 'left'
                ),
                array(
                    'table' => tbl_country . ' as country',
                    'condition' => 'country.id_country = store.id_country',
                    'join' => 'left'
                ),
                array(
                    'table' => tbl_store_category . ' as store_category',
                    'condition' => 'store_category.id_category = sponsored_log.id_category AND store_category.id_sub_category = sponsored_log.id_sub_category',
                    'join' => 'left'
                )
            ),
            'group_by' => array('sponsored_log.id_sponsored_log')
        );

        $sponsorsors = $this->Common_model->master_select($select_sponsored);

        if (isset($sponsorsors) && sizeof($sponsorsors) > 0) {
            foreach ($sponsorsors as $spo) {
                $date = date('Y-m-d H:i:s');
                $converted_today_date = new DateTime($date, new DateTimeZone($spo['timezone']));
                $converted_today_date->setTimezone(new DateTimeZone($spo['timezone']));
                $converted_today_date_ = strtotime($converted_today_date->format('Y-m-d'));

                $converted_to_date = new DateTime($spo['to_date'] . ' 00:00:00', new DateTimeZone($spo['timezone']));
                $converted_to_date->setTimezone(new DateTimeZone($spo['timezone']));
                $converted_to_date_ = strtotime($converted_to_date->format('Y-m-d'));

                if ($converted_today_date_ > $converted_to_date_) {
                    $update_store_category = array(
                        'is_sponsored' => UNSPONSORED_TYPE,
                        'sponsored_position' => 0
                    );
                    $where_store_category = array(
                        'id_store' => $spo['id_store'],
                        'is_delete' => IS_NOT_DELETED_STATUS,
                        'id_category' => $spo['id_category'],
                        'id_sub_category' => $spo['id_sub_category']
                    );
                    $this->Common_model->master_update(tbl_store_category, $update_store_category, $where_store_category);
                }
            }
        }
    }

    /*
     * Update is_sponsored, sponsored_position fields with 0 value if duration is over. - For Mall
     */

    public function remove_sponsored_mall() {

        $select_sponsored = array(
            'table' => tbl_sponsored_log . ' sponsored_log',
            'fields' => array(
                'sponsored_log.from_date',
                'sponsored_log.to_date',
                'sponsored_log.position',
                'mall.id_mall',
                'mall.id_country',
                'country.timezone'
            ),
            'where' => array(
                'sponsored_log.is_delete' => IS_NOT_DELETED_STATUS,
                'mall.is_delete' => IS_NOT_DELETED_STATUS,
                'mall.status' => ACTIVE_STATUS,
                'mall.is_sponsored' => SPONSORED_TYPE
            ),
            'join' => array(
                array(
                    'table' => tbl_mall . ' as mall',
                    'condition' => 'mall.id_mall = sponsored_log.id_mall',
                    'join' => 'left'
                ),
                array(
                    'table' => tbl_country . ' as country',
                    'condition' => 'country.id_country = mall.id_country',
                    'join' => 'left'
                )
            ),
            'group_by' => array('sponsored_log.id_sponsored_log')
        );

        $sponsorsors = $this->Common_model->master_select($select_sponsored);

        if (isset($sponsorsors) && sizeof($sponsorsors) > 0) {
            foreach ($sponsorsors as $spo) {
                $date = date('Y-m-d H:i:s');
                $converted_today_date = new DateTime($date, new DateTimeZone($spo['timezone']));
                $converted_today_date->setTimezone(new DateTimeZone($spo['timezone']));
                $converted_today_date_ = strtotime($converted_today_date->format('Y-m-d'));

                $converted_to_date = new DateTime($spo['to_date'] . ' 00:00:00', new DateTimeZone($spo['timezone']));
                $converted_to_date->setTimezone(new DateTimeZone($spo['timezone']));
                $converted_to_date_ = strtotime($converted_to_date->format('Y-m-d'));

                if ($converted_today_date_ > $converted_to_date_) {
                    $update_mall = array(
                        'is_sponsored' => UNSPONSORED_TYPE,
                        'sponsored_position' => 0
                    );
                    $where_mall = array(
                        'id_mall' => $spo['id_mall'],
                        'is_delete' => IS_NOT_DELETED_STATUS
                    );
                    $this->Common_model->master_update(tbl_mall, $update_mall, $where_mall);
                }
            }
        }
    }

}
