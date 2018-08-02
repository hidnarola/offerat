<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Verify_offers extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Common_model', '', TRUE);
        $this->data['title'] = $this->data['page_header'] = 'Verify Offers';
    }

    /*
     * Store Listing with Details
     */

    public function stores() {

        $this->data['title'] = $this->data['page_header'] = 'Verify Store Offers';
        $filter_list_url = 'country-admin/filter_store_offers';

        $this->data['filter_list_url'] = $filter_list_url;

        $this->template->load('user', 'Countryadmin/Verify_offers/stores', $this->data);
    }

    /*
     * Filter Store Offer Relatred Data
     */

    public function filter_store_offers() {

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

        $filter_array['fields'][] = tbl_store . '.id_store';
        $filter_array['fields'][] = 'offer_store_id';
        $filter_array['fields'][] = tbl_store . '.store_name';
        $filter_array['fields'][] = 'id_offer';
        $filter_array['fields'][] = 'GROUP_CONCAT(DATE_FORMAT(sales_trend.from_date, "%d-%b"), " To ", DATE_FORMAT(sales_trend.to_date, "%d-%b")) AS sales_from_to';
        $filter_array['fields'][] = 'offer_store_id1';
        $filter_array['fields'][] = 'if(stt_from_date IS NULL, "No match found", if( DATE_FORMAT(CONVERT_TZ(broadcasting_time,"' . $current_time_zone_offeset . '","' . $logged_in_country_zone_offset . '"),"%d-%m") BETWEEN DATE_FORMAT(stt_from_date, "%d-%m") AND DATE_FORMAT(stt_to_date, "%d-%m"), "Valid", "Invalid")) AS validity';
//        $filter_array['fields'][] = 'if( DATE_FORMAT(broadcasting_time, "%d-%m-%Y") BETWEEN DATE_FORMAT(stt_from_date, "%d-%m-%Y") AND DATE_FORMAT(stt_to_date, "%d-%m-%Y"), "Valid", "Invalid") AS validity';

        $filter_array['order_by'] = array(tbl_store . '.id_store' => 'DESC');
        $filter_array['group_by'] = array(tbl_store . '.id_store');

        $filter_array['join'][] = array(
            'table' => tbl_sales_trend . ' sales_trend',
            'condition' => 'sales_trend.id_store = ' . tbl_store . '.id_store AND sales_trend.is_delete = ' . IS_NOT_DELETED_STATUS,
            'join_type' => 'left',
        );

        $filter_array['join'][] = array(
            'table' => '(select o.id_offer, o.broadcasting_time, o.expiry_time, o.id_store offer_store_id 
	from offer_announcement o 
	where 
	o.id_offer = (select max(o1.id_offer) from offer_announcement o1 where o.id_store = o1.id_store AND is_delete = 0 group by o1.id_store)
	group by o.id_store 	
	order by o.id_offer DESC ) k1',
            'condition' => 'offer_store_id = ' . tbl_store . '.id_store',
            'join_type' => 'left',
        );
        $filter_array['join'][] = array(
            'table' => '(select o.id_store offer_store_id1, stt.from_date stt_from_date, stt.to_date stt_to_date
	from offer_announcement o , sales_trend stt 	
	where 
	o.id_offer = (select max(o1.id_offer) from offer_announcement o1 where o.id_store = o1.id_store AND o1.is_delete = 0 group by o1.id_store) AND 
	stt.id_store = o.id_store AND
	stt.is_delete = 0 AND 
	(DATE_FORMAT(CONVERT_TZ(o.broadcasting_time,"' . $current_time_zone_offeset . '","' . $logged_in_country_zone_offset . '"),"%d-%m") BETWEEN DATE_FORMAT(stt.from_date, "%d-%m") AND DATE_FORMAT(stt.to_date, "%d-%m"))
        group by o.id_offer
	order by o.id_offer DESC) k2',
            'condition' => 'offer_store_id1 = ' . tbl_store . '.id_store',
            'join_type' => 'left',
        );

        $filter_array['where'] = array(
            tbl_store . '.is_delete' => IS_NOT_DELETED_STATUS,
            tbl_store . '.id_country' => $this->loggedin_user_country_data['id_country']
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

    /*
     * Mall Listing with Details
     */

    public function malls() {

        $this->data['title'] = $this->data['page_header'] = 'Verify Mall Offers';
        $filter_list_url = 'country-admin/filter_mall_offers';

        $this->data['filter_list_url'] = $filter_list_url;

        $this->template->load('user', 'Countryadmin/Verify_offers/malls', $this->data);
    }

    /*
     * Filter Mall Offer Relatred Data
     */

    public function filter_mall_offers() {

        $filter_array = $this->Common_model->create_datatable_request($this->input->post());

        $date = date('Y-m-d h:i:s');
        $current_time_zone_today_date = new DateTime($date, new DateTimeZone(date_default_timezone_get()));
        $current_time_zone_today_date->setTimezone(new DateTimeZone(date_default_timezone_get()));
        $current_time_zone_today_date_ = $current_time_zone_today_date->format('Y-m-d H:i:s');
        $current_time_zone_offeset = $current_time_zone_today_date->format('P');
        $logged_in_country_zone_today_date = new DateTime($date, new DateTimeZone($this->loggedin_user_country_data['timezone']));
        $logged_in_country_zone_today_date->setTimezone(new DateTimeZone($this->loggedin_user_country_data['timezone']));
        $logged_in_country_zone_today_date_ = $logged_in_country_zone_today_date->format('Y-m-d H:i:s');
        $logged_in_country_zone_offset = $logged_in_country_zone_today_date->format('P');

        $filter_array['fields'][] = tbl_mall . '.id_mall';
        $filter_array['fields'][] = 'offer_mall_id';
        $filter_array['fields'][] = tbl_mall . '.mall_name';
        $filter_array['fields'][] = 'id_offer';
        $filter_array['fields'][] = 'GROUP_CONCAT(DATE_FORMAT(sales_trend.from_date, "%d-%b"), " To ", DATE_FORMAT(sales_trend.to_date, "%d-%b")) AS sales_from_to';
        $filter_array['fields'][] = 'offer_mall_id1';
        $filter_array['fields'][] = 'if(stt_from_date IS NULL, "No match found", if( DATE_FORMAT(CONVERT_TZ(broadcasting_time,"' . $current_time_zone_offeset . '","' . $logged_in_country_zone_offset . '"),"%d-%m") BETWEEN DATE_FORMAT(stt_from_date, "%d-%m") AND DATE_FORMAT(stt_to_date, "%d-%m"), "Valid", "Invalid")) AS validity';

        $filter_array['order_by'] = array(tbl_mall . '.id_mall' => 'DESC');
        $filter_array['group_by'] = array(tbl_mall . '.id_mall');

        $filter_array['join'][] = array(
            'table' => tbl_sales_trend . ' sales_trend',
            'condition' => 'sales_trend.id_mall = ' . tbl_mall . '.id_mall AND sales_trend.is_delete = ' . IS_NOT_DELETED_STATUS,
            'join_type' => 'left',
        );

        $filter_array['join'][] = array(
            'table' => '(select o.id_offer, o.broadcasting_time, o.expiry_time, o.id_mall offer_mall_id 
	from offer_announcement o 
	where 
	o.id_offer = (select max(o1.id_offer) from offer_announcement o1 where o.id_mall = o1.id_mall AND is_delete = 0 group by o1.id_mall)
	group by o.id_mall 	
	order by o.id_offer DESC ) k1',
            'condition' => 'offer_mall_id = ' . tbl_mall . '.id_mall',
            'join_type' => 'left',
        );
        $filter_array['join'][] = array(
            'table' => '(select o.id_mall offer_mall_id1, stt.from_date stt_from_date, stt.to_date stt_to_date
	from offer_announcement o , sales_trend stt 	
	where 
	o.id_offer = (select max(o1.id_offer) from offer_announcement o1 where o.id_mall = o1.id_mall AND o1.is_delete = 0 group by o1.id_mall) AND 
	stt.id_mall = o.id_mall AND
	stt.is_delete = 0 AND 
	(DATE_FORMAT(CONVERT_TZ(o.broadcasting_time,"' . $current_time_zone_offeset . '","' . $logged_in_country_zone_offset . '"),"%d-%m") BETWEEN DATE_FORMAT(stt.from_date, "%d-%m") AND DATE_FORMAT(stt.to_date, "%d-%m"))
        group by o.id_offer
	order by o.id_offer DESC) k2',
            'condition' => 'offer_mall_id1 = ' . tbl_mall . '.id_mall',
            'join_type' => 'left',
        );

        $filter_array['where'] = array(
            tbl_mall . '.is_delete' => IS_NOT_DELETED_STATUS,
            tbl_mall . '.id_country' => $this->loggedin_user_country_data['id_country']
        );

        $filter_records = $this->Common_model->get_filtered_records(tbl_mall, $filter_array);
//        query();
        $total_filter_records = $this->Common_model->get_filtered_records(tbl_mall, $filter_array, 1);

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->Common_model->master_count(tbl_mall),
            "recordsFiltered" => $total_filter_records,
            "data" => $filter_records,
        );
        echo json_encode($output);
    }

}
