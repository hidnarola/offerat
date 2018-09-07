<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Notifications extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Common_model', '', TRUE);
        $this->bread_crum[] = array(
            'url' => '',
            'title' => 'Posts',
        );

//        $this->load->library('UploadHandler');

        if (!in_array($this->loggedin_user_type, array(COUNTRY_ADMIN_USER_TYPE, STORE_OR_MALL_ADMIN_USER_TYPE)))
            override_404();
    }

    /*
     * Notifications List
     * @param String notification_type : 'offers', 'announcements'
     * @param String list_type : NULL, 'upcoming', 'expired' (all for Country Admin , Restrict expired for Store/Mall Admin)
     */

    public function index($notification_type = NULL, $list_type = NULL) {

        if (!is_null($notification_type) && in_array($notification_type, array('offers', 'announcements')) && (($this->loggedin_user_type == STORE_OR_MALL_ADMIN_USER_TYPE && in_array($list_type, array(NULL, 'upcoming'))) || ($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE && in_array($list_type, array(NULL, 'upcoming', 'expired'))))) {

            $list_url = '';
            $add_url = '';
            $delete_url = '';
            $list_type_url = '';
            $filter_list_url = '';
            $notification_details_url = '';

            $this->data['title'] = $this->data['page_header'] = ucfirst($notification_type);
            if ($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE) {
                $list_url = 'country-admin/notifications/' . $notification_type . '/' . $list_type;
                $add_url = 'country-admin/notifications/' . $notification_type . '/save';
                $delete_url = 'country-admin/notifications/' . $notification_type . '/delete/';
                $list_type_url = 'country-admin/notifications/' . $notification_type . '/';
                $filter_list_url = 'country-admin/filter_notifications/' . $notification_type . '/' . $list_type;
                $notification_details_url = 'country-admin/notifications/get_notification_details/';
            } elseif ($this->loggedin_user_type == STORE_OR_MALL_ADMIN_USER_TYPE) {
                $list_url = 'mall-store-user/notifications/' . $notification_type . '/' . $list_type;
                $add_url = 'mall-store-user/notifications/' . $notification_type . '/save';
                $delete_url = 'mall-store-user/notifications/' . $notification_type . '/delete/';
                $list_type_url = 'mall-store-user/notifications/' . $notification_type . '/';
                $filter_list_url = 'mall-store-user/filter_notifications/' . $notification_type . '/' . $list_type;
                $notification_details_url = 'mall-store-user/notifications/get_notification_details/';
            }

            $this->bread_crum[] = array(
                'url' => '',
                'title' => ucfirst($notification_type) . ' List',
            );

            $this->data['list_url'] = $list_url;
            $this->data['add_url'] = $add_url;
            $this->data['delete_url'] = $delete_url;
            $this->data['filter_list_url'] = $filter_list_url;
            $this->data['list_type_url'] = $list_type_url;
            $this->data['notification_details_url'] = $notification_details_url;

            $this->data['list_type'] = $list_type;
            $this->data['notification_type'] = $notification_type;

            $this->template->load('user', 'Common/Notifications/index', $this->data);
        } else {
            dashboard_redirect($this->loggedin_user_type);
        }
    }

    /**
     * Notifications Filter
     * @param String notification_type : 'offers', 'announcements'
     * @param String list_type : NULL, 'upcoming', 'expired' (all for Country Admin , Restrict expired for Store/Mall Admin)
     */
    public function filter_notifications($notification_type = NULL, $list_type = NULL) {

        if (!is_null($notification_type) && in_array($notification_type, array('offers', 'announcements')) && (($this->loggedin_user_type == STORE_OR_MALL_ADMIN_USER_TYPE && in_array($list_type, array(NULL, 'upcoming'))) || ($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE && in_array($list_type, array(NULL, 'upcoming', 'expired'))))) {

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
            $filter_array['fields'][] = 'DATE_FORMAT(CONVERT_TZ(' . tbl_offer_announcement . '.expiry_time,"' . $current_time_zone_offeset . '","' . $logged_in_country_zone_offset . '"),"%Y-%m-%d %H:%i") as offer_announcement_expiry_time';
//            $filter_array['fields'][] = 'DATE_FORMAT(' . tbl_offer_announcement . '.expiry_time,"%Y-%m-%d") as offer_announcement_expiry_time';

            $filter_array['order_by'] = array(tbl_offer_announcement . '.id_offer' => 'DESC');
            $filter_array['group_by'] = array(tbl_offer_announcement . '.id_offer');
            $filter_array['where'] = array(tbl_offer_announcement . '.is_delete' => IS_NOT_DELETED_STATUS);

            if ($notification_type == 'offers')
                $filter_array['where'][tbl_offer_announcement . '.type'] = OFFER_OFFER_TYPE;
            elseif ($notification_type == 'announcements')
                $filter_array['where'][tbl_offer_announcement . '.type'] = ANNOUNCEMENT_OFFER_TYPE;

            if ($notification_type == 'offers') {
                if (!is_null($list_type)) {
                    if ($list_type == 'upcoming')
                        $filter_array['where_with_sign'][] = '(DATE_FORMAT(CONVERT_TZ(' . tbl_offer_announcement . '.broadcasting_time,"' . $current_time_zone_offeset . '","' . $logged_in_country_zone_offset . '"), "%Y-%m-%d %H:%i:%s") > CONVERT_TZ("' . $current_time_zone_today_date_ . '","' . $current_time_zone_offeset . '","' . $logged_in_country_zone_offset . '"))';
                    elseif ($list_type == 'expired')
                        $filter_array['where_with_sign'][] = '(DATE_FORMAT(CONVERT_TZ(' . tbl_offer_announcement . '.broadcasting_time,"' . $current_time_zone_offeset . '","' . $logged_in_country_zone_offset . '"),"%Y-%m-%d %H:%i:%s")  < CONVERT_TZ("' . $current_time_zone_today_date_ . '","' . $current_time_zone_offeset . '","' . $logged_in_country_zone_offset . '") AND DATE_FORMAT(' . tbl_offer_announcement . '.expiry_time,"%Y-%m-%d %H:%i:%s") <> "0000-00-00 00:00:00")';
                    else
                        $filter_array['where_with_sign'][] = '(( DATE_FORMAT(CONVERT_TZ(' . tbl_offer_announcement . '.broadcasting_time,"' . $current_time_zone_offeset . '","' . $logged_in_country_zone_offset . '"), "%Y-%m-%d %H:%i:%s") < CONVERT_TZ("' . $current_time_zone_today_date_ . '","' . $current_time_zone_offeset . '","' . $logged_in_country_zone_offset . '") and DATE_FORMAT(' . tbl_offer_announcement . '.expiry_time,"%Y-%m-%d %H:%i:%s") = "0000-00-00 00:00:00") OR (CONVERT_TZ("' . $current_time_zone_today_date_ . '","' . $current_time_zone_offeset . '","' . $logged_in_country_zone_offset . '") BETWEEN DATE_FORMAT(CONVERT_TZ(' . tbl_offer_announcement . '.broadcasting_time,"' . $current_time_zone_offeset . '","' . $logged_in_country_zone_offset . '"),"%Y-%m-%d %H:%i:%s") and  DATE_FORMAT(CONVERT_TZ(' . tbl_offer_announcement . '.expiry_time,"' . $current_time_zone_offeset . '","' . $logged_in_country_zone_offset . '"),"%Y-%m-%d %H:%i:%s")))';
                } else
                    $filter_array['where_with_sign'][] = '(( DATE_FORMAT(CONVERT_TZ(' . tbl_offer_announcement . '.broadcasting_time,"' . $current_time_zone_offeset . '","' . $logged_in_country_zone_offset . '"), "%Y-%m-%d %H:%i:%s") < CONVERT_TZ("' . $current_time_zone_today_date_ . '","' . $current_time_zone_offeset . '","' . $logged_in_country_zone_offset . '") and DATE_FORMAT(' . tbl_offer_announcement . '.expiry_time,"%Y-%m-%d %H:%i:%s") = "0000-00-00 00:00:00") OR (CONVERT_TZ("' . $current_time_zone_today_date_ . '","' . $current_time_zone_offeset . '","' . $logged_in_country_zone_offset . '") BETWEEN DATE_FORMAT(CONVERT_TZ(' . tbl_offer_announcement . '.broadcasting_time,"' . $current_time_zone_offeset . '","' . $logged_in_country_zone_offset . '"),"%Y-%m-%d %H:%i:%s") and  DATE_FORMAT(CONVERT_TZ(' . tbl_offer_announcement . '.expiry_time,"' . $current_time_zone_offeset . '","' . $logged_in_country_zone_offset . '"),"%Y-%m-%d %H:%i:%s")))';
            }
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

            if ($this->loggedin_user_type == STORE_OR_MALL_ADMIN_USER_TYPE)
                $filter_array['where_with_sign'][] = '((FIND_IN_SET("' . $this->loggedin_user_data['user_id'] . '", store.id_users) <> 0) OR (FIND_IN_SET("' . $this->loggedin_user_data['user_id'] . '", mall.id_users) <> 0))';

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

    /*
     * Add / Edit Norification
     * @param string notification_type : 'offers', 'announcements'
     * @param int id : notification id
     */

    public function save($notification_type = NULL, $id = NULL, $list_type = NULL) {

        if (!is_null($notification_type) && in_array($notification_type, array('offers', 'announcements'))) {
            $image_count = 0;
            $max_image_upload_count = MAX_OFFER_IMAGE_UPLOAD;
            $m_name = $media_name = '';
            $media_extension = '';
            $media_video_name = '';
            $media_thumbnail = '';
            $media_width = 0;
            $media_height = 0;
            $back_url = '';
            $upload_url = '';
            $images_list_url = '';
            $remove_image_url = '';
            if ($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE) {
                $back_url = 'country-admin/notifications/' . $notification_type . '/' . $list_type;
                $upload_url = 'country-admin/upload/index';
                $images_list_url = 'country-admin/notifications/images/';
                $remove_image_url = 'country-admin/notifications/remove_image_uploaded';
            } elseif ($this->loggedin_user_type == STORE_OR_MALL_ADMIN_USER_TYPE) {
                $back_url = 'mall-store-user/notifications/' . $notification_type . '/' . $list_type;
                $upload_url = 'mall-store-user/upload/index';
                $images_list_url = 'mall-store-user/notifications/images/';
                $remove_image_url = 'mall-store-user/notifications/remove_image_uploaded';
                if (!is_null($id))
                    redirect($back_url);
            }

            $this->bread_crum[] = array(
                'url' => $back_url,
                'title' => ucfirst($notification_type) . ' List',
            );

            if (isset($id) && $id > 0) {
                $this->bread_crum[] = array(
                    'url' => '',
                    'title' => 'Edit ' . ucfirst($notification_type),
                );

                $this->data['title'] = $this->data['page_header'] = 'Edit ' . ucfirst($notification_type);

                $select_notification = array(
                    'table' => tbl_offer_announcement,
                    'where' => array(
                        'is_delete' => IS_NOT_DELETED_STATUS,
                        'id_offer' => $id
                    )
                );

                $notification_data = $this->Common_model->master_single_select($select_notification);
                if (isset($notification_data) && sizeof($notification_data) > 0) {
                    $this->data['notification_data'] = $notification_data;

                    $select_image = array(
                        'table' => tbl_offer_announcement_image,
                        'where' => array(
                            'is_delete' => IS_NOT_DELETED_STATUS,
                            'id_offer' => $id
                        )
                    );

                    $where_image = array('is_delete' => IS_NOT_DELETED_STATUS, 'id_offer' => $id);
                    $image_count = $this->Common_model->master_count(tbl_offer_announcement_image, $where_image);
                    $max_image_upload_count = $max_image_upload_count - $image_count;
                } else {
                    redirect($back_url);
                }
            } else {
                $this->bread_crum[] = array(
                    'url' => '',
                    'title' => 'Add ' . ucfirst($notification_type),
                );

                $this->data['title'] = $this->data['page_header'] = 'Add ' . ucfirst($notification_type);
            }

            if ($this->input->post()) {

                $validate_fields = array(
                    'store_mall_id',
                    'offer_type',
                    'broadcasting_time',
                    'expire_text'
                );

                if ($this->input->post('offer_type', TRUE) == TEXT_OFFER_CONTENT_TYPE)
                    $validate_fields[] = 'content';

                if ($this->input->post('offer_type', TRUE) == VIDEO_OFFER_CONTENT_TYPE)
                    $validate_fields[] = 'video_url';

                if ($notification_type == 'offers') {
                    $validate_fields[] = 'push_message';
                    $validate_fields[] = 'expiry_time';
                }

                if ($this->_validate_form($validate_fields, $id, $notification_type)) {

                    $uploaded_file_type = @$_FILES['media_name']['type'];
                    $video_types = $this->video_types_arr;
                    $do_notification_video_has_error = false;

                    if ($this->input->post('offer_type', TRUE) == VIDEO_OFFER_CONTENT_TYPE) {

                        if (($_FILES['media_name']['size']) > 0) {
                            $image_path = $_SERVER['DOCUMENT_ROOT'] . offer_media_path;
                            if (!file_exists($image_path)) {
                                $this->Common_model->created_directory($image_path);
                            }
                            $supported_files = 'mp4|webm|ogg|ogv|wmv|vob|swf|mov|m4v|flv';
                            $m_name = $this->Common_model->upload_video('media_name', $image_path, $supported_files);

                            if (empty($m_name)) {
                                $do_notification_video_has_error = true;
                                $this->data['image_errors'] = $this->upload->display_errors();
                            } else {
                                $media_thumbnail = $media_name = $m_name;

                                if (in_array($uploaded_file_type, $this->video_types_arr)) {

                                    $target_file = $_SERVER['DOCUMENT_ROOT'] . offer_media_path . $media_name;
                                    $media_thumbnail = time() . "_videoimg.jpg";
                                    $destination = $_SERVER['DOCUMENT_ROOT'] . offer_media_thumbnail_path . $media_thumbnail;
                                    $command = FFMPEG_PATH . '  -i ' . $target_file . "  -ss 00:00:1.435  -vframes 1 " . $destination;
                                    exec($command, $a, $b);
                                    list($width, $height) = getimagesize($destination);
                                    $media_width = $width;
                                    $media_height = $height;
                                }

                                if (!empty($media_name)) {
                                    $media_extension_text = explode('.', $media_name);
                                    $media_extension = $media_extension_text[1];
                                }
                            }
                        } else {
                            if (!empty($_FILES['media_name']['tmp_name'])) {
                                $do_notification_video_has_error = true;
                                $this->data['image_errors'] = 'Invalid File';
                            }
                        }
                    }

                    if (!$do_notification_video_has_error) {

                        $date = date('Y-m-d h:i:s');
                        $store_id = 0;
                        $mall_id = 0;
                        $store_mall_id = $this->input->post('store_mall_id', TRUE);
                        $store_mall_text = explode('_', $store_mall_id);
                        if ($store_mall_text[0] == 'store')
                            $store_id = $store_mall_text[1];
                        if ($store_mall_text[0] == 'mall')
                            $mall_id = $store_mall_text[1];

                        if ($this->input->post('expiry_time', TRUE) != '') {
                            $expiry_time = new DateTime($this->input->post('expiry_time', TRUE), new DateTimeZone($this->loggedin_user_country_data['timezone']));
                            $expiry_time->setTimezone(new DateTimeZone(date_default_timezone_get()));
                            $expiry_time_text = $expiry_time->format('Y-m-d 00:00:00');
                        } else
                            $expiry_time_text = '0000-00-00 00:00:00';

                        $broadcasting_time = new DateTime($this->input->post('broadcasting_time', TRUE), new DateTimeZone($this->loggedin_user_country_data['timezone']));
                        $broadcasting_time->setTimezone(new DateTimeZone(date_default_timezone_get()));
                        $broadcasting_time_text = $broadcasting_time->format('Y-m-d H:i:00');

                        $offer_type = $this->input->post('offer_type', TRUE);
                        $video_url = $this->input->post('video_url', TRUE);
                        $content = $this->input->post('content', TRUE);

                        if ($offer_type == IMAGE_OFFER_CONTENT_TYPE || $offer_type == VIDEO_OFFER_CONTENT_TYPE) {
                            $content = '';
                        }

                        $notification_data = array(
                            'type' => ($notification_type == 'offers') ? OFFER_OFFER_TYPE : ANNOUNCEMENT_OFFER_TYPE,
                            'offer_type' => $offer_type,
                            'id_mall' => $mall_id,
                            'id_store' => $store_id,
                            'video_url' => $video_url,
                            'content' => $content,
                            'broadcasting_time' => $broadcasting_time_text,
                            'expiry_time' => $expiry_time_text,
                            'expire_text' => $this->input->post('expire_text', TRUE)
                        );
                        if ($notification_type == 'offers')
                            $notification_data['expire_time_type'] = $this->input->post('expire_time_type', TRUE);

                        if ($offer_type == VIDEO_OFFER_CONTENT_TYPE) {
                            $notification_data['media_name'] = $media_name;
                            $notification_data['media_thumbnail'] = $media_thumbnail;
                            $notification_data['media_height'] = $media_height;
                            $notification_data['media_width'] = $media_width;
                        }

                        if ($notification_type == 'offers')
                            $notification_data['push_message'] = $this->input->post('push_message', TRUE);

                        if (isset($id) && $id > 0) {
                            $where = array('id_offer' => $id);
                            $notification_data['modified_date'] = $date;
                            $is_updated = $this->Common_model->master_update(tbl_offer_announcement, $notification_data, $where);
                            if ($is_updated) {
                                $this->upload_images($id, $date, $max_image_upload_count, $image_count + 1);
                                $this->session->set_flashdata('success_msg', ucfirst($notification_type) . ' Updated Successfully!');
                            }
                        } else {
                            $notification_data['created_date'] = $date;
                            $notification_data['is_testdata'] = (ENVIRONMENT !== 'production') ? 1 : 0;
                            $notification_data['is_delete'] = IS_NOT_DELETED_STATUS;

                            $notification_id = $this->Common_model->master_save(tbl_offer_announcement, $notification_data);
                            if ($notification_id > 0) {

                                $this->upload_images($notification_id, $date, $max_image_upload_count, 1);
                                $this->session->set_flashdata('success_msg', ucfirst($notification_type) . ' Added Successfully!');
                            }
                        }
                        redirect($back_url);
                    }
                }
            }

            $select_stores = array(
                'table' => tbl_store . ' store',
                'fields' => array('store.id_store', 'store.store_name'),
                'where' => array('store.status' => ACTIVE_STATUS, 'store.is_delete' => IS_NOT_DELETED_STATUS),
                'group_by' => array('store.id_store'),
                'join' => array(
                    array(
                        'table' => tbl_country . ' as country',
                        'condition' => tbl_country . '.id_country = ' . tbl_store . '.id_country',
                        'join' => 'left',
                    )
                )
            );
            if ($this->loggedin_user_type == STORE_OR_MALL_ADMIN_USER_TYPE)
                $select_stores['where_with_sign'] = array('FIND_IN_SET("' . $this->loggedin_user_data['user_id'] . '", store.id_users) <> 0');
            elseif ($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE) {
                $select_stores['where_with_sign'] = array('FIND_IN_SET("' . $this->loggedin_user_data['user_id'] . '", country.id_users) <> 0');
            }

            $stores_list = $this->Common_model->master_select($select_stores);

            $select_malls = array(
                'table' => tbl_mall . ' mall',
                'fields' => array('mall.id_mall', 'mall.mall_name'),
                'where' => array('mall.status' => ACTIVE_STATUS, 'mall.is_delete' => IS_NOT_DELETED_STATUS),
                'group_by' => array('mall.id_mall'),
                'join' => array(
                    array(
                        'table' => tbl_country . ' as country',
                        'condition' => tbl_country . '.id_country = ' . tbl_mall . '.id_country',
                        'join' => 'left',
                    )
                )
            );
            if ($this->loggedin_user_type == STORE_OR_MALL_ADMIN_USER_TYPE)
                $select_malls['where_with_sign'] = array('FIND_IN_SET("' . $this->loggedin_user_data['user_id'] . '", mall.id_users) <> 0');
            elseif ($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE) {
                $select_malls['where_with_sign'] = array('FIND_IN_SET("' . $this->loggedin_user_data['user_id'] . '", country.id_users) <> 0');
            }
            $malls_list = $this->Common_model->master_select($select_malls);
//            query();
            $this->data['stores_list'] = $stores_list;
            $this->data['malls_list'] = $malls_list;

            $this->data['notification_type'] = $notification_type;
            $this->data['back_url'] = $back_url;
            $this->data['upload_url'] = $upload_url;
            $this->data['images_list_url'] = $images_list_url;
            $this->data['remove_image_url'] = $remove_image_url;
            $this->data['max_image_upload_count'] = $max_image_upload_count;
            $this->template->load('user', 'Common/Notifications/form', $this->data);
        } else {
            dashboard_redirect($this->loggedin_user_type);
        }
    }

    function upload_images($notification_id = NULL, $date = NULL, $max_image_upload_count = NULL, $sort_order = NULL) {

        if ($this->input->post('offer_type', TRUE) == IMAGE_OFFER_CONTENT_TYPE && $this->input->post('uploaded_images_data', TRUE) != '') {

            $uploaded_images_data = explode(',', $this->input->post('uploaded_images_data', TRUE));
//            pr($uploaded_images_data);
//            pr($uploaded_images_data);
//            echo count($uploaded_images_data);
//            die();
            if (isset($uploaded_images_data) && sizeof($uploaded_images_data) > 0 && count($uploaded_images_data) <= $max_image_upload_count) {
                $image_data = array();
                foreach ($uploaded_images_data as $image) {
                    $file_name = base64_decode($image);
//                    $file_name = explode('/', $file_name);

                    list($width1, $height1) = getimagesize($_SERVER['DOCUMENT_ROOT'] . offer_media_path . $file_name);
                    $media_width = $width1;
                    $media_height = $height1;

                    $image_data[] = array(
                        'image_name' => $file_name,
                        'image_thumbnail' => $file_name,
                        'image_width' => $media_width,
                        'image_height' => $media_height,
                        'id_offer' => $notification_id,
                        'created_date' => $date,
                        'is_testdata' => (ENVIRONMENT !== 'production') ? 1 : 0,
                        'is_delete' => IS_NOT_DELETED_STATUS,
                        'sort_order' => $sort_order
                    );
                    $sort_order++;
                }
                if (isset($image_data) && sizeof($image_data) > 0)
                    $this->Common_model->master_save(tbl_offer_announcement_image, $image_data, 1);
            }
        }
    }

    /**

     * @param array $validate_fields array of control names
     * @return boolean
     */
    function _validate_form($validate_fields, $id = NULL, $notification_type = NULL) {
        $validation_rules = array();

        if (in_array('store_mall_id', $validate_fields)) {
            $validation_rules[] = array(
                'field' => 'store_mall_id',
                'label' => 'Store / Mall Selection',
                'rules' => 'trim|required|htmlentities',
            );
        }
        if (in_array('offer_type', $validate_fields)) {
            $validation_rules[] = array(
                'field' => 'offer_type',
                'label' => 'Content Type',
                'rules' => 'trim|required|htmlentities',
            );
        }

        if (in_array('expire_text', $validate_fields)) {
            $validation_rules[] = array(
                'field' => 'expire_text',
                'label' => 'Optional Text',
                'rules' => 'trim|max_length[1000]|htmlentities',
            );
        }

        $expiry_callback = '';
        if (is_null($id))
            $expiry_callback = '|callback_custom_expiry_time_check';
        if (in_array('expiry_time', $validate_fields)) {
            $validation_rules[] = array(
                'field' => 'expiry_time',
                'label' => 'Expiry Date and Time',
                'rules' => 'trim|htmlentities' . $expiry_callback,
            );
        }
        $broadcast_callback = '';
        if (is_null($id))
            $broadcast_callback = '|callback_custom_broadcast_time_check';
        if (in_array('broadcasting_time', $validate_fields)) {
            $validation_rules[] = array(
                'field' => 'broadcasting_time',
                'label' => 'Post Date & Time',
                'rules' => 'trim|required|htmlentities' . $broadcast_callback,
            );
        }

        if (in_array('content', $validate_fields)) {
            $validation_rules[] = array(
                'field' => 'content',
                'label' => 'Content',
                'rules' => 'trim|required|min_length[5]|max_length[255]|htmlentities',
            );
        }

        if (in_array('media_name', $validate_fields)) {
            $validation_rules[] = array(
                'field' => 'media_name',
                'label' => 'Video',
                'rules' => 'trim|callback_custom_notification_video[media_name]|htmlentities',
            );
        }

        if ($notification_type == 'offers') {
            if (in_array('push_message', $validate_fields)) {
                $validation_rules[] = array(
                    'field' => 'push_message',
                    'label' => 'Push Notification Summary',
                    'rules' => 'trim|required|min_length[5]|max_length[50]|htmlentities',
                );
            }
        }
        $this->form_validation->set_rules($validation_rules);

        return $this->form_validation->run();
    }

    function custom_notification_video($media_name, $video_control) {

        if ($_FILES[$video_control]['name'] != '' && $this->input->post('offer_type', TRUE) == VIDEO_OFFER_CONTENT_TYPE) {
            if (!in_array($_FILES[$video_control]['type'], array('video/mp4', 'video/webm', 'video/ogg', 'video/ogv', 'video/wmv', 'video/vob', 'video/swf', 'video/mov', 'video/m4v', 'video/flv'))) {
                $this->form_validation->set_message('custom_notification_video', 'The {field} contain invalid video type.');
                return FALSE;
            }
            if ($_FILES[$video_control]['error'] > 0) {
                $this->form_validation->set_message('custom_notification_video', 'The {field} contain invalid video.');
                return FALSE;
            }
            if ($_FILES[$video_control]['size'] <= 0) {
                $this->form_validation->set_message('custom_notification_video', 'The {field} contain invalid video size.');
                return FALSE;
            }
        } else {
            if ($this->input->post('id_offer', TRUE) == '') {
                $this->form_validation->set_message('custom_notification_video', 'The {field} field is required.');
                return FALSE;
            } else {
                return TRUE;
            }
        }

        return TRUE;
    }

    function custom_valid_url($url) {

        if (isset($url) && !empty($url)) {
            if (preg_match('%^((https?://)|(www\.))([a-z0-9-].?)+(:[0-9]+)?(/.*)?$%i', $url)) {
                return TRUE;
            } else {
                $this->form_validation->set_message('custom_valid_url', 'The {field} not a valid url.');
                return FALSE;
            }
        }
    }

    function custom_expiry_time_check($expire_time) {
//        return true;
        $expire_time = strtotime($expire_time);
        $today_time = strtotime(date('Y-m-d'));

        if (!empty($expire_time) && $expire_time > $today_time) {
            return TRUE;
        } else {
            if (empty($expire_time))
                return TRUE;
            else {
                $this->form_validation->set_message('custom_expiry_time_check', 'Expire Date & Time should be greater than Current Date & Time.');
                return FALSE;
            }
        }
    }

    function custom_broadcast_time_check($broadcast_time) {
//        return true;
        $broadcast_time = strtotime($broadcast_time);
        $today_time = strtotime(date('Y-m-d H:i'));

        if ($broadcast_time >= $today_time) {
            if ($this->input->post('expiry_time', TRUE) != '') {
                $expire_time = strtotime($this->input->post('expiry_time', TRUE));
                if ($expire_time <= $broadcast_time) {
                    $this->form_validation->set_message('custom_broadcast_time_check', 'Post Date & Time should be greater than Expire Date & Time.');
                    return FALSE;
                }
            }
            return TRUE;
        } else {
            $this->form_validation->set_message('custom_broadcast_time_check', 'Post Date & Time should be greater than Current Date & Time.');
            return FALSE;
        }
    }

    /*
     * Delete Notification
     * @param String $notification_type : 'offers', 'announcements'
     * @param int id : notification id
     * @param String list_type : blank , 'upcoming' , 'expired'
     */

    function delete($notification_type = NULL, $id = null, $list_type = NULL) {
        $can_delete = TRUE;
        if (!is_null($notification_type) && in_array($notification_type, array('offers', 'announcements')) && !is_null($id) && $id > 0) {

            $select_notification = array(
                'table' => tbl_offer_announcement,
                'where' => array(
                    'id_offer' => $id,
                    'is_delete' => IS_NOT_DELETED_STATUS
                )
            );

            $notification_details = $this->Common_model->master_single_select($select_notification);

            if (isset($notification_details) && sizeof($notification_details) > 0) {
                if ($this->loggedin_user_type == STORE_OR_MALL_ADMIN_USER_TYPE) {
                    if ($notification_details['expiry_time'] != '0000-00-00 00:00:00' && strtotime($notification_details['expiry_time']) < strtotime(date('Y-m-d H:i')))
                        $can_delete = FALSE;
                }

                if ($can_delete == TRUE) {
                    $update_data = array('is_delete' => IS_DELETED_STATUS);
                    $where_data = array('id_offer' => $id, 'is_delete' => IS_NOT_DELETED_STATUS);
                    $is_updated = $this->Common_model->master_update(tbl_offer_announcement, $update_data, $where_data);

                    if ($is_updated)
                        $this->session->set_flashdata('success_msg', ucfirst($notification_type) . ' deleted successfully.');
                    else
                        $this->session->set_flashdata('error_msg', 'Invalid request sent to delete ' . ucfirst($notification_type) . '. Please try again later.');
                } else
                    $this->session->set_flashdata('error_msg', 'Invalid request sent to delete ' . ucfirst($notification_type) . '. Please try again later.');
            } else {
                $this->session->set_flashdata('error_msg', 'Invalid request sent to delete ' . ucfirst($notification_type) . '. Please try again later.');
            }
            if ($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE)
                $list_url = 'country-admin/notifications/' . $notification_type . '/' . $list_type;
            elseif ($this->loggedin_user_type == STORE_OR_MALL_ADMIN_USER_TYPE)
                $list_url = 'mall-store-user/notifications/' . $notification_type . '/' . $list_type;
            else
                $list_url = '/';

            redirect($list_url);
        } else {
            override_404();
        }
    }

    public function get_notification_details($notification_id = NULL) {
        $response = array(
            'status' => '0',
            'sub_view' => '0',
        );
        if (isset($notification_id) && !empty($notification_id)) {

            $select_notification = array(
                'table' => tbl_offer_announcement . ' offer_announcement',
                'where' => array(
                    'offer_announcement.is_delete' => IS_NOT_DELETED_STATUS,
                    'offer_announcement.id_offer' => $notification_id
                ),
                'join' => array(
                    array(
                        'table' => tbl_mall . ' as mall',
                        'condition' => 'mall.id_mall = offer_announcement.id_mall',
                        'join' => 'left',
                    ),
                    array(
                        'table' => tbl_store . ' as store',
                        'condition' => 'store.id_store = offer_announcement.id_store',
                        'join' => 'left',
                    ),
                    array(
                        'table' => tbl_country . ' as country',
                        'condition' => '(country.id_country = store.id_country OR country.id_country = mall.id_country)',
                        'join' => 'left',
                    )
                )
            );
            $notification_details = $this->Common_model->master_single_select($select_notification);
            $this->data['notification_details'] = $notification_details;

            $images_list_url = '';
            if ($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE)
                $images_list_url = 'country-admin/notifications/images/';
            elseif ($this->loggedin_user_type == STORE_OR_MALL_ADMIN_USER_TYPE)
                $images_list_url = 'mall-store-user/notifications/images/';

            $this->data['images_list_url'] = $images_list_url;

            $html = $this->load->view('Common/Notifications/details', $this->data, TRUE);
            $response = array(
                'status' => '1',
                'sub_view' => $html
            );
        }
        echo json_encode($response);
    }

    //remove jquery uploaded images
    public function remove_image_uploaded() {

        $target_dir = $_SERVER['DOCUMENT_ROOT'];
        $array = explode(",", $this->input->post('all_data', TRUE));
        $not_to = $this->input->post('not_to_delete', TRUE);
        $result = array_diff($array, $not_to);
        foreach ($result as $ar) {
            @unlink($target_dir . offer_media_path . base64_decode($ar));
            @unlink($target_dir . offer_media_thumbnail_path . base64_decode($ar));
        }
    }

    /*
     * Display List of Images for Offer / Announcements
     * @param id : id_offer (Int)
     */

    public function images($id) {

        $image_sort_order_url = '';
        $this->data['title'] = $this->data['page_header'] = 'Image(s)';
        $this->data['action_url'] = 'country-admin/notifications/images/' . $id;

        if ($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE) {
            $image_sort_order_url = 'country-admin/notifications/update_images_sort_order/' . $id;
        } elseif ($this->loggedin_user_type == STORE_OR_MALL_ADMIN_USER_TYPE) {
            $image_sort_order_url = 'mall-store-user/notifications/update_images_sort_order/' . $id;
        }

        $select_image = array(
            'table' => tbl_offer_announcement_image . ' image',
            'where' => array(
                'offer.id_offer' => $id,
                'offer.is_delete' => IS_NOT_DELETED_STATUS,
                'image.is_delete' => IS_NOT_DELETED_STATUS,
                'offer.offer_type' => IMAGE_OFFER_CONTENT_TYPE
            ),
            'join' => array(
                array(
                    'table' => tbl_offer_announcement . ' as offer',
                    'condition' => 'offer.id_offer = image.id_offer',
                    'join' => 'left',
                )
            ),
            'group_by' => array('image.id_offer_announcement_image'),
            'order_by' => array('image.sort_order' => 'ASC')
        );

        $image_list = $this->Common_model->master_select($select_image);
        if (isset($image_list) && sizeof($image_list) > 0) {
            $back_url = '';
            $edit_url = '';
            $edit_label = '';
            $notification_type = '';
            if ($image_list[0]['type'] == OFFER_OFFER_TYPE) {
                $back_url = 'country-admin/notifications/offers/';
                $edit_url = 'country-admin/notifications/offers/save/' . $id;
                $edit_label = 'Edit Offer';
                $notification_type = 'offers';
            } elseif ($image_list[0]['type'] == ANNOUNCEMENT_OFFER_TYPE) {
                $back_url = 'country-admin/notifications/announcements/';
                $edit_url = 'country-admin/notifications/announcements/save/' . $id;
                $edit_label = 'Edit Announcement';
                $notification_type = 'announcements';
            }

            if ($this->input->post()) {
                $delete_image_ids = $this->input->post('delete_image_ids', TRUE);
                if (isset($delete_image_ids) && sizeof($delete_image_ids) > 0) {
                    $result = $this->db->query('UPDATE ' . tbl_offer_announcement_image . ' SET is_delete=' . IS_DELETED_STATUS .
                            ' WHERE is_delete = ' . IS_NOT_DELETED_STATUS . ' AND id_offer_announcement_image IN (' . implode(',', $delete_image_ids) . ') AND id_offer = ' . $id);
                }
                if ($result)
                    $this->session->set_flashdata('success_msg', 'Image(s) deleted successfully.');
                else
                    $this->session->set_flashdata('error_msg', 'Image(s) not deleted.');
                redirect('country-admin/notifications/images/' . $id);
            }

            $this->bread_crum[] = array(
                'url' => $back_url,
                'title' => 'List',
            );
            $this->bread_crum[] = array(
                'url' => $edit_url,
                'title' => $edit_label,
            );
            $this->bread_crum[] = array(
                'url' => '',
                'title' => 'Image(s)',
            );

            $this->data['back_url'] = $back_url;
            $this->data['edit_url'] = $edit_url;
            $this->data['image_list'] = $image_list;
            $this->data['notification_type'] = $notification_type;
            $this->data['image_sort_order_url'] = $image_sort_order_url;
        }
        $this->template->load('user', 'Common/Notifications/images', $this->data);
    }

    function update_images_sort_order($offer_id) {

        $ids = $this->input->post('ids', TRUE);
        if (isset($ids) && !empty($ids)) {
            $ids = explode(',', $ids);
            $count = 1;
            foreach ($ids as $id) {
                $update_data = array('sort_order' => $count);
                $where_data = array('id_offer_announcement_image' => $id, 'id_offer' => $offer_id, 'is_delete' => 0);
                $this->Common_model->master_update(tbl_offer_announcement_image, $update_data, $where_data);
//                query();
//                echo '<br>';
                $count++;
            }
            return true;
        }
    }

}
