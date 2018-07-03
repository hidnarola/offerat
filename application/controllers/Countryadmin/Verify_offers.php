<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Verify_offers extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Common_model', '', TRUE);
        $this->data['title'] = $this->data['page_header'] = 'Verify Offers';
    }

    public function index() {

        $filter_list_url = 'country-admin/filter_offers';

        $this->data['filter_list_url'] = $filter_list_url;

        $this->template->load('user', 'Countryadmin/verify_offers', $this->data);
    }

    public function filter_offers() {

        $filter_array = $this->Common_model->create_datatable_request($this->input->post());

        $filter_array['fields'][] = tbl_store . '.id_store';
        $filter_array['fields'][] = 'offer_store_id';
        $filter_array['fields'][] = tbl_store . '.store_name';
        $filter_array['fields'][] = 'id_offer';
        $filter_array['fields'][] = 'group_concat(DATE_FORMAT(sales_trend.from_date, "%d-%b"), " To ", DATE_FORMAT(sales_trend.to_date, "%d-%b")) AS sales_from_to';
        $filter_array['fields'][] = 'offer_store_id1';
        $filter_array['fields'][] = 'expiry_time';
        $filter_array['fields'][] = 'if( date_format(broadcasting_time, "%d-%m-%Y") between date_format(stt_from_date, "%d-%m-%Y") AND date_format(stt_to_date, "%d-%m-%Y"), "Valid", "Invalid") AS validity';

        $filter_array['order_by'] = array(tbl_store . '.id_store' => 'DESC');
        $filter_array['group_by'] = array(tbl_store . '.id_store');

        $filter_array['join'][] = array(
            'table' => tbl_sales_trend . ' sales_trend',
            'condition' => 'sales_trend.id_store = ' . tbl_store . '.id_users AND sales_trend.is_delete = ' . IS_NOT_DELETED_STATUS,
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
	(date_format(o.broadcasting_time, "%d-%m-%Y") between date_format(stt.from_date, "%d-%m-%Y") AND date_format(stt.to_date, "%d-%m-%Y"))
	order by o.id_offer DESC) k2',
            'condition' => 'offer_store_id1 = ' . tbl_store . '.id_store',
            'join_type' => 'left',
        );

        $filter_records = $this->Common_model->get_filtered_records(tbl_store, $filter_array);
        $total_filter_records = $this->Common_model->get_filtered_records(tbl_store, $filter_array, 1);

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->Common_model->master_count(tbl_store),
            "recordsFiltered" => $total_filter_records,
            "data" => $filter_records,
        );
        echo json_encode($output);
    }

}
