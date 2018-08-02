<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Common_model', '', TRUE);
        $this->data['title'] = $this->data['page_header'] = 'Dashboard';
    }

    /*
     * Dashboard
     */

    public function index() {
        $this->data['page'] = 'dashboard_page';
        $this->data['page_header'] = 'Dashboard';

        $this->bread_crum[] = array(
            'url' => '',
            'title' => 'Dashboard',
        );

        $store_list_url = 'country-admin/stores';
        $store_filter_list_url = 'country-admin/dashboard/filter_stores';
        $store_details_url = 'country-admin/stores/get_store_details/';
        $edit_store_url = 'country-admin/stores/save/';

        $list_url = 'country-admin/notifications/';
        $list_type_url = 'country-admin/notifications/';
        $filter_list_url = 'country-admin/dashboard/filter_notifications/';
        $notification_details_url = 'country-admin/notifications/get_notification_details/';

        $this->data['store_list_url'] = $store_list_url;
        $this->data['store_filter_list_url'] = $store_filter_list_url;
        $this->data['store_details_url'] = $store_details_url;
        $this->data['edit_store_url'] = $edit_store_url;

        $this->data['filter_list_url'] = $filter_list_url;
        $this->data['list_type_url'] = $list_type_url;
        $this->data['notification_details_url'] = $notification_details_url;

        $this->template->load('user', 'Countryadmin/Dashboard/index', $this->data);
    }

    /*
     * Filter Stores
     */

    public function filter_stores() {

        $filter_array = $this->Common_model->create_datatable_request($this->input->post());

        $date = date('Y-m-d h:i:s');
        $current_time_zone_today_date = new DateTime($date);
        $current_time_zone_today_date->setTimezone(new DateTimeZone(date_default_timezone_get()));
        $current_time_zone_today_date_ = $current_time_zone_today_date->format('Y-m-d H:i:s');
        $current_time_zone_offeset = $current_time_zone_today_date->format('P');
        $logged_in_country_zone_today_date = new DateTime($date);
        $logged_in_country_zone_today_date->setTimezone(new DateTimeZone($this->loggedin_user_country_data['timezone']));
        $logged_in_country_zone_today_date_ = $logged_in_country_zone_today_date->format('Y-m-d H:i:s');
        $logged_in_country_zone_offset = $logged_in_country_zone_today_date->format('P');

        $filter_array['fields'][] = 'DATE_FORMAT(CONVERT_TZ(' . tbl_store . '.created_date,"' . $current_time_zone_offeset . '","' . $logged_in_country_zone_offset . '"),"%Y-%m-%d %H:%i") as store_created_date';
        $filter_array['order_by'] = array(tbl_store . '.id_store' => 'DESC');
        $filter_array['group_by'] = array(tbl_store . '.id_store');
        $filter_array['where'] = array(
            tbl_store . '.is_delete' => IS_NOT_DELETED_STATUS,
            tbl_store . '.status' => NOT_VERIFIED_STATUS,
            tbl_user . '.is_delete' => IS_NOT_DELETED_STATUS,
            tbl_country . '.is_delete' => IS_NOT_DELETED_STATUS,
            tbl_user . '.status' => ACTIVE_STATUS,
        );

        $filter_array['where_with_sign'][] = 'country.id_country =  ' . $this->loggedin_user_country_data['id_country'];
        $filter_array['where_with_sign'][] = 'country.id_country = store.id_country';

        $filter_array['join'][] = array(
            'table' => tbl_user . ' as user',
            'condition' => tbl_user . '.id_user = ' . tbl_store . '.id_users',
            'join_type' => 'left',
        );
        $filter_array['join'][] = array(
            'table' => tbl_country . ' as country',
            'condition' => tbl_country . '.id_country = ' . tbl_store . '.id_country',
            'join_type' => 'left',
        );

        $filter_records = $this->Common_model->get_filtered_records(tbl_store, $filter_array);
//        query();
        $total_filter_records = $this->Common_model->get_filtered_records(tbl_store, $filter_array, 1);

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->Common_model->master_count(tbl_store),
            "recordsFiltered" => $total_filter_records,
            "data" => $filter_records,
        );
        echo json_encode($output);
    }

    /**
     * Notifications Filter     
     */
    public function filter_notifications() {

        $date = date('Y-m-d h:i:s');
        $current_time_zone_today_date = new DateTime($date);
        $current_time_zone_today_date->setTimezone(new DateTimeZone(date_default_timezone_get()));
        $current_time_zone_today_date_ = $current_time_zone_today_date->format('Y-m-d H:i:s');
        $current_time_zone_offeset = $current_time_zone_today_date->format('P');
        $logged_in_country_zone_today_date = new DateTime($date);
        $logged_in_country_zone_today_date->setTimezone(new DateTimeZone($this->loggedin_user_country_data['timezone']));
        $logged_in_country_zone_today_date_ = $logged_in_country_zone_today_date->format('Y-m-d H:i:s');
        $logged_in_country_zone_offset = $logged_in_country_zone_today_date->format('P');

        $filter_array = $this->Common_model->create_datatable_request($this->input->post());

        $filter_array['fields'][] = 'DATE_FORMAT(CONVERT_TZ(' . tbl_offer_announcement . '.created_date,"' . $current_time_zone_offeset . '","' . $logged_in_country_zone_offset . '"),"%Y-%m-%d %H:%i") as offer_announcement_created_date';
        $filter_array['fields'][] = 'DATE_FORMAT(CONVERT_TZ(' . tbl_offer_announcement . '.broadcasting_time,"' . $current_time_zone_offeset . '","' . $logged_in_country_zone_offset . '"),"%Y-%m-%d %H:%i") as offer_announcement_broadcasting_time';

        $filter_array['order_by'] = array(tbl_offer_announcement . '.id_offer' => 'DESC');
        $filter_array['group_by'] = array(tbl_offer_announcement . '.id_offer');
        $filter_array['where'] = array(
            tbl_offer_announcement . '.is_delete' => IS_NOT_DELETED_STATUS,
            tbl_offer_announcement . '.expiry_time' => '0000-00-00 00:00:00'
        );

        $filter_array['join'][] = array(
            'table' => tbl_mall . ' as mall',
            'condition' => tbl_mall . '.id_mall = ' . tbl_offer_announcement . '.id_mall',
            'join_type' => 'left',
        );
        $filter_array['join'][] = array(
            'table' => tbl_store . ' as store',
            'condition' => tbl_store . '.id_store = ' . tbl_offer_announcement . '.id_store',
            'join_type' => 'left',
        );
        $filter_array['join'][] = array(
            'table' => tbl_country . ' as country',
            'condition' => '(country.id_country = store.id_country OR country.id_country = mall.id_country)',
            'join' => 'left',
        );
        $filter_array['where_with_sign'][] = '(country.id_country = mall.id_country OR country.id_country = store.id_country)';

        if ($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE)
            $filter_array['where_with_sign'][] = '(FIND_IN_SET("' . $this->loggedin_user_data['user_id'] . '", country.id_users) <> 0)';

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
