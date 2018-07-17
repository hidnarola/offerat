<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Common_model', '', TRUE);

        if (!in_array($this->loggedin_user_type, array(COUNTRY_ADMIN_USER_TYPE, STORE_OR_MALL_ADMIN_USER_TYPE)))
            override_404();
    }

    /*
     * To get report for Mall / Store
     * @param location_type : mall , store
     * @param location_id : id_mall or id_store
     */

    public function index($location_type = NULL, $location_id = NULL) {

        if (!is_null($location_type) && !is_null($location_id) && in_array($location_type, array('store', 'mall')) && $location_id > 0) {

            $notification_details_url = '';

            if ($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE) {
                if ($location_type == 'store')
                    $back_url = 'country-admin/stores';
                else
                    $back_url = 'country-admin/malls';
                $notification_details_url = 'country-admin/notifications/get_notification_details/';
            } elseif ($this->loggedin_user_type == STORE_OR_MALL_ADMIN_USER_TYPE) {
                if ($location_type == 'store')
                    $back_url = 'mall-store-user/stores';
                else
                    $back_url = 'mall-store-user/malls';
                $notification_details_url = 'mall-store-user/notifications/get_notification_details/';
            }

            $this->bread_crum[] = array(
                'url' => $back_url,
                'title' => ' List',
            );

            if ($location_type == 'store') {
                $select_location = array(
                    'table' => tbl_store,
                    'fields' => array('store_name AS location_name', 'id_store AS location_id'),
                    'where' => array(
                        'is_delete' => IS_NOT_DELETED_STATUS,
                        'id_country' => $this->loggedin_user_country_data['id_country'],
                        'id_store' => $location_id
                    )
                );
                if ($this->loggedin_user_type == STORE_OR_MALL_ADMIN_USER_TYPE)
                    $select_location['where']['id_users'] = $this->loggedin_user_data['user_id'];
            } elseif ($location_type == 'mall') {
                $select_location = array(
                    'table' => tbl_mall,
                    'fields' => array('mall_name AS location_name', 'id_mall AS location_id'),
                    'where' => array(
                        'is_delete' => IS_NOT_DELETED_STATUS,
                        'id_country' => $this->loggedin_user_country_data['id_country'],
                        'id_mall' => $location_id
                    )
                );
                if ($this->loggedin_user_type == STORE_OR_MALL_ADMIN_USER_TYPE)
                    $select_location['where']['id_users'] = $this->loggedin_user_data['user_id'];
            }

            $location = $this->Common_model->master_single_select($select_location);

            $this->bread_crum[] = array(
                'url' => 'country-admin/stores/save/' . $location['location_id'],
                'title' => 'Edit ' . $location['location_name'],
            );

            $this->bread_crum[] = array(
                'url' => '',
                'title' => 'Report - ' . $location['location_name'],
            );

            if (isset($location) && sizeof($location) > 0) {
                $select_store_or_mall_favories = array(
                    'table' => tbl_favorite . ' favorite',
                    'fields' => array('COUNT(id_favorite) favorite_count'),
                    'where' => array(
                        'favorite.is_delete' => IS_NOT_DELETED_STATUS,
                        'favorite.is_notification_enable' => NOTIFICATION_ENABLED
                    ),
                );
                if ($location_type == 'store') {
                    $select_store_or_mall_favories['where']['favorite.id_store'] = $location_id;
                } elseif ($location_type == 'mall') {
                    $select_store_or_mall_favories['where']['favorite.id_mall'] = $location_id;
                }

                $enable_notification_count = $this->Common_model->master_single_select($select_store_or_mall_favories);

                if (isset($enable_notification_count) && sizeof($enable_notification_count) > 0)
                    $this->data['enable_notification_count'] = $enable_notification_count['favorite_count'] . ' Users';
                else
                    $this->data['enable_notification_count'] = '0 User';

                $first_day_of_previous_month = new DateTime("first day of last month");
                $last_day_of_previous_month = new DateTime("last day of last month");

                $select_store_mall_count = array(
                    'table' => tbl_click_store,
                    'where' => array(
                        'from_date' => $first_day_of_previous_month->format('Y-m-d'),
                        'to_date' => $last_day_of_previous_month->format('Y-m-d')
                    )
                );
                if ($location_type == 'store') {
                    $select_store_mall_count['where']['id_store'] = $location_id;
                } elseif ($location_type == 'mall') {
                    $select_store_mall_count['where']['id_mall'] = $location_id;
                }

                $click_count = $this->Common_model->master_single_select($select_store_mall_count);
                if (isset($click_count) && sizeof($click_count) > 0)
                    $this->data['click_count'] = $click_count['counter'] . ' Clicks';
                else
                    $this->data['click_count'] = '0 Click';

                $select_priority = array(
                    'table' => tbl_favorite,
                    'fields' => array('count(id_favorite) as priority_count', 'position_priority'),
                    'where' => array('is_delete' => IS_NOT_DELETED_STATUS,),
                    'group_by' => array('position_priority'),
                    'order_by' => array('position_priority' => 'ASC')
                );
                if ($location_type == 'store') {
                    $select_priority['where']['id_store'] = $location_id;
                } elseif ($location_type == 'mall') {
                    $select_priority['where']['id_mall'] = $location_id;
                }

                $priorities = $this->Common_model->master_select($select_priority);
                $proirity_list = array();
                if (isset($priorities) && sizeof($priorities) > 0) {
                    foreach ($priorities as $pri) {
                        $proirity_list[$pri['position_priority']] = $pri['priority_count'];
                    }
                }

                $this->data['proirity_list'] = $proirity_list;
                $this->data['title'] = $this->data['page_header'] = 'Report - ' . $location['location_name'];

                $filter_list_url = '';
                if ($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE) {
                    $filter_list_url = 'country-admin/report/push_notifications/' . $location_type . '/' . $location_id;
                } elseif ($this->loggedin_user_type == STORE_OR_MALL_ADMIN_USER_TYPE) {
                    $filter_list_url = 'mall-store-user/report/push_notifications/' . $location_type . '/' . $location_id;
                }
                $this->data['filter_list_url'] = $filter_list_url;
                $this->data['notification_details_url'] = $notification_details_url;
                $this->template->load('user', 'Common/Report/index', $this->data);
            } else {
                dashboard_redirect($this->loggedin_user_type);
            }
        } else {
            override_404();
        }
    }

    /*
     * Filter Details
     * @param location_type : mall , store
     * @param location_id : id_mall or id_store
     */

    public function filter_notifications($location_type = NULL, $location_id = NULL) {

        if (!is_null($location_type) && !is_null($location_id) && in_array($location_type, array('store', 'mall')) && $location_id > 0) {

            $date = date('Y-m-d h:i:s');
            $current_time_zone_today_date = new DateTime($date, new DateTimeZone(date_default_timezone_get()));
            $current_time_zone_today_date->setTimezone(new DateTimeZone(date_default_timezone_get()));
            $current_time_zone_today_date_ = $current_time_zone_today_date->format('Y-m-d H:i:s');
            $current_time_zone_offeset = $current_time_zone_today_date->format('P');
            $logged_in_country_zone_today_date = new DateTime($date, new DateTimeZone($this->loggedin_user_country_data['timezone']));
            $logged_in_country_zone_today_date->setTimezone(new DateTimeZone($this->loggedin_user_country_data['timezone']));
            $logged_in_country_zone_today_date_ = $logged_in_country_zone_today_date->format('Y-m-d H:i:s');
            $logged_in_country_zone_offset = $logged_in_country_zone_today_date->format('P');

            $filter_array = $this->Common_model->create_datatable_request($this->input->post());

            $filter_array['fields'][] = 'DATE_FORMAT(CONVERT_TZ(' . tbl_offer_announcement . '.created_date,"' . $current_time_zone_offeset . '","' . $logged_in_country_zone_offset . '"),"%Y-%m-%d %H:%i") as offer_announcement_created_date';
            $filter_array['fields'][] = 'DATE_FORMAT(CONVERT_TZ(' . tbl_offer_announcement . '.broadcasting_time,"' . $current_time_zone_offeset . '","' . $logged_in_country_zone_offset . '"),"%Y-%m-%d %H:%i") as offer_announcement_broadcasting_time';
            $filter_array['fields'][] = 'DATE_FORMAT(CONVERT_TZ(' . tbl_offer_announcement . '.expiry_time,"' . $current_time_zone_offeset . '","' . $logged_in_country_zone_offset . '"),"%Y-%m-%d %H:%i") as offer_announcement_expiry_time';

            $filter_array['order_by'] = array(tbl_offer_announcement . '.id_offer' => 'DESC');
            $filter_array['group_by'] = array(tbl_offer_announcement . '.id_offer');
            $filter_array['where'] = array(tbl_offer_announcement . '.is_delete' => IS_NOT_DELETED_STATUS);

            if ($location_type == 'mall') {
                $filter_array['join'][] = array(
                    'table' => tbl_mall . ' as mall',
                    'condition' => tbl_mall . '.id_mall = ' . tbl_offer_announcement . '.id_mall',
                    'join_type' => 'left'
                );
                $filter_array['where']['mall.id_mall'] = $location_id;
                $filter_array['where']['mall.id_country'] = $this->loggedin_user_country_data['id_country'];
                $filter_array['where']['mall.is_delete'] = IS_NOT_DELETED_STATUS;
            } elseif ($location_type == 'store') {
                $filter_array['join'][] = array(
                    'table' => tbl_store . ' as store',
                    'condition' => tbl_store . '.id_store = ' . tbl_offer_announcement . '.id_store',
                    'join_type' => 'left'
                );
                $filter_array['where']['store.id_store'] = $location_id;
                $filter_array['where']['store.id_country'] = $this->loggedin_user_country_data['id_country'];
                $filter_array['where']['store.is_delete'] = IS_NOT_DELETED_STATUS;
            }

            $filter_records = $this->Common_model->get_filtered_records(tbl_offer_announcement, $filter_array);
//            query();
            $total_filter_records = $this->Common_model->get_filtered_records(tbl_offer_announcement, $filter_array, 1);

            $output = array(
                "draw" => $this->input->post('draw'),
                "recordsTotal" => $this->Common_model->master_count(tbl_offer_announcement),
                "recordsFiltered" => $total_filter_records,
                "data" => $filter_records,
            );
            echo json_encode($output);
        }
    }

}
