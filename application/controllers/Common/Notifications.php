<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Notifications extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Common_model', '', TRUE);

        $this->bread_crum[] = array(
            'url' => '',
            'title' => 'Notifications',
        );

        if (!in_array($this->loggedin_user_type, array(COUNTRY_ADMIN_USER_TYPE, STORE_OR_MALL_ADMIN_USER_TYPE)))
            redirect('/');
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

            $this->data['title'] = $this->data['page_header'] = ucfirst($notification_type);
            if ($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE) {
                $list_url = 'country-admin/notifications/' . $notification_type . '/' . $list_type;
                $add_url = 'country-admin/notifications/' . $notification_type . '/save';
                $delete_url = 'country-admin/notifications/' . $notification_type . '/delete/';
                $list_type_url = 'country-admin/notifications/' . $notification_type . '/';
                $filter_list_url = 'country-admin/filter_notifications/' . $notification_type . '/' . $list_type;
            } elseif ($this->loggedin_user_type == STORE_OR_MALL_ADMIN_USER_TYPE) {
                $list_url = 'mall-store-user/notifications/' . $notification_type . '/' . $list_type;
                $add_url = 'mall-store-user/notifications/' . $notification_type . '/save';
                $delete_url = 'mall-store-user/notifications/' . $notification_type . '/delete/';
                $list_type_url = 'mall-store-user/notifications/' . $notification_type . '/';
                $filter_list_url = 'mall-store-user/filter_notifications/' . $notification_type . '/' . $list_type;
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

            $filter_array = $this->Common_model->create_datatable_request($this->input->post());

            $filter_array['order_by'] = array(tbl_offer_announcement . '.id_offer' => 'DESC');
            $filter_array['group_by'] = array(tbl_offer_announcement . '.id_offer');
            $filter_array['where'] = array(tbl_offer_announcement . '.is_delete' => IS_NOT_DELETED_STATUS);

            if ($notification_type == 'offers')
                $filter_array['where'][tbl_offer_announcement . '.type'] = OFFER_OFFER_TYPE;
            elseif ($notification_type == 'announcements')
                $filter_array['where'][tbl_offer_announcement . '.type'] = ANNOUNCEMENT_OFFER_TYPE;

            if (!is_null($list_type)) {
                if ($list_type == 'upcoming')
                    $filter_array['where_with_sign'][] = '(DATE_FORMAT(' . tbl_offer_announcement . '.broadcasting_time, "%Y-%m-%d %H:%i:%s") > NOW())';
                elseif ($list_type == 'expired')
                    $filter_array['where_with_sign'][] = '(DATE_FORMAT(' . tbl_offer_announcement . '.expiry_time, "%Y-%m-%d %H:%i:%s") < NOW())';
                else
                    $filter_array['where_with_sign'][] = '(( NOW() < DATE_FORMAT(' . tbl_offer_announcement . '.broadcasting_time, "%Y-%m-%d %H:%i:%s") and DATE_FORMAT(' . tbl_offer_announcement . '.expiry_time, "%Y-%m-%d %H:%i:%s") = "00-00-0000 00:00:00") OR (NOW() BETWEEN ' . tbl_offer_announcement . '.broadcasting_time and ' . tbl_offer_announcement . '.expiry_time))';
            } else
                $filter_array['where_with_sign'][] = '((DATE_FORMAT(' . tbl_offer_announcement . '.broadcasting_time, "%Y-%m-%d %H:%i:%s") >= NOW() and DATE_FORMAT(' . tbl_offer_announcement . '.expiry_time, "%Y-%m-%d %H:%i:%s") = "00-00-0000 00:00:00") OR (NOW() BETWEEN ' . tbl_offer_announcement . '.broadcasting_time and ' . tbl_offer_announcement . '.expiry_time))';

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
                'table' => tbl_store_location . ' as store_location',
                'condition' => tbl_store_location . '.id_store = ' . tbl_store . '.id_store',
                'join_type' => 'left',
            );
            $filter_array['join'][] = array(
                'table' => tbl_place . ' as place',
                'condition' => tbl_place . '.id_place = ' . tbl_store_location . '.id_place',
                'join_type' => 'left',
            );

            if ($this->loggedin_user_type == STORE_OR_MALL_ADMIN_USER_TYPE)
                $filter_array['where_with_sign'][] = '((FIND_IN_SET("' . $this->loggedin_user_data['user_id'] . '", store.id_users) <> 0) OR (FIND_IN_SET("' . $this->loggedin_user_data['user_id'] . '", mall.id_users) <> 0))';
            if ($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE) {
                $filter_array['join'][] = array(
                    'table' => tbl_country . ' as country',
                    'condition' => '(country.id_country = mall.id_country OR country.id_country = place.id_country) AND FIND_IN_SET("' . $this->loggedin_user_data['user_id'] . '", country.id_users) <> 0 AND country.is_delete=' . IS_NOT_DELETED_STATUS,
                    'join_type' => 'left',
                );
                $filter_array['where_with_sign'][] = '((country.id_country = mall.id_country OR country.id_country = place.id_country) AND (FIND_IN_SET("' . $this->loggedin_user_data['user_id'] . '", country.id_users) <> 0 AND country.is_delete=' . IS_NOT_DELETED_STATUS . '))';
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

    /*
     * Add / Edit Norification
     * @param string notification_type : 'offers', 'announcements'
     * @param int id : notification id
     */

    public function save($notification_type = NULL, $id = NULL, $list_type = NULL) {

        if (!is_null($notification_type) && in_array($notification_type, array('offers', 'announcements'))) {
            $m_name = $media_name = '';
            $media_video_name = '';
            $media_width = 0;
            $media_height = 0;
            if ($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE)
                $back_url = 'country-admin/notifications/' . $notification_type . '/' . $list_type;
            elseif ($this->loggedin_user_type == STORE_OR_MALL_ADMIN_USER_TYPE) {
                $back_url = 'mall-store-user/notifications/' . $notification_type . '/' . $list_type;
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
                    $media_name = $notification_data['media_name'];
                    $media_thumbnail = $notification_data['media_thumbnail'];
                    $media_width = $notification_data['media_width'];
                    $media_height = $notification_data['media_height'];
                    $this->data['notification_data'] = $notification_data;
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
                    'expiry_time',
                    'broadcasting_time'
                );

                if ($this->input->post('offer_type') == IMAGE_OFFER_CONTENT_TYPE)
                    $validate_fields[] = 'media_name';
                if ($this->input->post('offer_type') == TEXT_OFFER_CONTENT_TYPE)
                    $validate_fields[] = 'content';

                if ($notification_type == 'offers')
                    $validate_fields[] = 'push_message';

                if ($this->_validate_form($validate_fields, $id)) {

                    $uploaded_file_type = @$_FILES['media_name']['type'];
                    $image_types = $this->image_types_arr;
                    $video_types = $this->video_types_arr;

                    $do_notification_image_video_has_error = false;

                    if (in_array($this->input->post('offer_type', TRUE), array(IMAGE_OFFER_CONTENT_TYPE, TEXT_OFFER_CONTENT_TYPE)) && isset($_FILES['media_name'])) {

                        if (($_FILES['media_name']['size']) > 0) {
                            $image_path = $_SERVER['DOCUMENT_ROOT'] . offer_media_path;
                            if (!file_exists($image_path)) {
                                $this->Common_model->created_directory($image_path);
                            }
                            $supported_files = 'gif|jpg|png|jpeg|mp4|webm|ogg|ogv|wmv|vob|swf|mov|m4v|flv';
                            $m_name = $this->Common_model->upload_image('media_name', $image_path, $supported_files);

                            if (empty($m_name)) {
                                $do_notification_image_video_has_error = true;
                                $this->data['image_errors'] = $this->upload->display_errors();
                            } else {
                                $media_thumbnail = $media_name = $m_name;
                                if (in_array($uploaded_file_type, $this->image_types_arr)) {
                                    list($width, $height) = getimagesize($_SERVER['DOCUMENT_ROOT'] . offer_media_path . $media_name);
                                    $media_width = $width;
                                    $media_height = $height;
                                    $target_file = $_SERVER['DOCUMENT_ROOT'] . offer_media_path . $media_name;
                                    $destination = $_SERVER['DOCUMENT_ROOT'] . offer_media_thumbnail_path . $media_name;
                                    $this->Common_model->crop_product_image($target_file, $destination, MEDIA_THUMB_IMAGE_WIDTH, MEDIA_THUMB_IMAGE_HEIGHT);
                                }

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
                            }
                        } else {
                            if (!empty($_FILES['media_name']['tmp_name'])) {
                                $do_notification_image_video_has_error = true;
                                $this->data['image_errors'] = 'Invalid File';
                            }
                        }
                    }
                    
                    if (!$do_notification_image_video_has_error) {

                        $date = date('Y-m-d h:i:s');
                        $store_id = 0;
                        $mall_id = 0;
                        $store_mall_id = $this->input->post('store_mall_id', TRUE);
                        $store_mall_text = explode('_', $store_mall_id);
                        if ($store_mall_text[0] == 'store')
                            $store_id = $store_mall_text[1];
                        if ($store_mall_text[0] == 'mall')
                            $mall_id = $store_mall_text[1];

                        $expiry_time = date_create($this->input->post('expiry_time', TRUE));
                        $expiry_time_text = date_format($expiry_time, "Y-m-d H:i:00");
                        $broadcasting_time = date_create($this->input->post('broadcasting_time', TRUE));
                        $broadcasting_time_text = date_format($broadcasting_time, "Y-m-d H:i:00");

                        $notification_data = array(
                            'type' => ($notification_type == 'offers') ? OFFER_OFFER_TYPE : ANNOUNCEMENT_OFFER_TYPE,
                            'offer_type' => (in_array($uploaded_file_type, $video_types)) ? VIDEO_OFFER_CONTENT_TYPE : ($this->input->post('offer_type', TRUE) == TEXT_OFFER_CONTENT_TYPE) ? TEXT_OFFER_CONTENT_TYPE : IMAGE_OFFER_CONTENT_TYPE,
                            'expiry_time' => $expiry_time_text,
                            'broadcasting_time' => $broadcasting_time_text,
                            'content' => ($this->input->post('offer_type', TRUE) == TEXT_OFFER_CONTENT_TYPE) ? $this->input->post('content', TRUE) : '',
                            'media_name' => ($this->input->post('offer_type', TRUE) == IMAGE_OFFER_CONTENT_TYPE) ? $media_name : '',
                            'media_thumbnail' => ($this->input->post('offer_type', TRUE) == IMAGE_OFFER_CONTENT_TYPE) ? $media_thumbnail : '',
                            'id_mall' => $mall_id,
                            'id_store' => $store_id,
                            'media_width' => ($this->input->post('offer_type', TRUE) == IMAGE_OFFER_CONTENT_TYPE) ? $media_width : 0,
                            'media_height' => (($this->input->post('offer_type', TRUE) == IMAGE_OFFER_CONTENT_TYPE)) ? $media_height : 0
                        );
                        if ($notification_type == 'offers')
                            $notification_data['push_message'] = $this->input->post('push_message', TRUE);

                        if ($id) {
                            $where = array('id_offer' => $id);
                            $notification_data['modified_date'] = $date;
                            $is_updated = $this->Common_model->master_update(tbl_offer_announcement, $notification_data, $where);
                            if ($is_updated) {
                                $this->session->set_flashdata('success_msg', ucfirst($notification_type) . ' Updated Successfully!');
                            }
                        } else {

                            $notification_data['created_date'] = date('Y-m-d h:i:s');
                            $notification_data['is_testdata'] = (ENVIRONMENT !== 'production') ? 1 : 0;
                            $notification_data['is_delete'] = IS_NOT_DELETED_STATUS;

                            $notification_id = $this->Common_model->master_save(tbl_offer_announcement, $notification_data);
                            if ($notification_id > 0)
                                $this->session->set_flashdata('success_msg', ucfirst($notification_type) . ' Added Successfully!');
                        }

                        redirect($back_url);
                    }
                }
            }

            $select_stores = array(
                'table' => tbl_store . ' store',
                'fields' => array('store.id_store', 'store.store_name'),
                'where' => array('store.status' => ACTIVE_STATUS, 'store.is_delete' => IS_NOT_DELETED_STATUS),
                'group_by' => array('store.id_store')
            );
            if ($this->loggedin_user_type == STORE_OR_MALL_ADMIN_USER_TYPE)
                $select_stores['where_with_sign'] = array('FIND_IN_SET("' . $this->loggedin_user_data['user_id'] . '", store.id_users) <> 0');
            elseif ($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE) {

                $select_stores['join'][] = array(
                    'table' => tbl_store_location . ' as store_location',
                    'condition' => tbl_store_location . '.id_store = ' . tbl_store . '.id_store',
                    'join' => 'left',
                );
                $select_stores['join'][] = array(
                    'table' => tbl_place . ' as place',
                    'condition' => tbl_place . '.id_place = ' . tbl_store_location . '.id_place',
                    'join' => 'left',
                );
                $select_stores['join'][] = array(
                    'table' => tbl_country . ' as country',
                    'condition' => tbl_country . '.id_country = ' . tbl_place . '.id_country',
                    'join' => 'left',
                );
                $select_stores['where_with_sign'] = array('FIND_IN_SET("' . $this->loggedin_user_data['user_id'] . '", country.id_users) <> 0');
            }


            $stores_list = $this->Common_model->master_select($select_stores);

            $select_malls = array(
                'table' => tbl_mall . ' mall',
                'fields' => array('mall.id_mall', 'mall.mall_name'),
                'where' => array('mall.status' => ACTIVE_STATUS, 'mall.is_delete' => IS_NOT_DELETED_STATUS),
                'group_by' => array('mall.id_mall')
            );
            if ($this->loggedin_user_type == STORE_OR_MALL_ADMIN_USER_TYPE)
                $select_malls['where_with_sign'] = array('FIND_IN_SET("' . $this->loggedin_user_data['user_id'] . '", mall.id_users) <> 0');
            elseif ($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE) {
                $select_malls['join'][] = array(
                    'table' => tbl_country . ' as country',
                    'condition' => tbl_country . '.id_country = ' . tbl_mall . '.id_country',
                    'join' => 'left',
                );
                $select_malls['where_with_sign'] = array('FIND_IN_SET("' . $this->loggedin_user_data['user_id'] . '", country.id_users) <> 0');
            }
            $malls_list = $this->Common_model->master_select($select_malls);
//            query();
            $this->data['stores_list'] = $stores_list;
            $this->data['malls_list'] = $malls_list;

            $this->data['notification_type'] = $notification_type;
            $this->data['back_url'] = $back_url;
            $this->template->load('user', 'Common/Notifications/form', $this->data);
        } else {
            dashboard_redirect($this->loggedin_user_type);
        }
    }

    /**

     * @param array $validate_fields array of control names
     * @return boolean
     */
    function _validate_form($validate_fields, $id = NULL) {
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
                'label' => 'Broadcast Date & Time',
                'rules' => 'trim|required|htmlentities' . $broadcast_callback,
            );
        }

        if (in_array('content', $validate_fields)) {
            $validation_rules[] = array(
                'field' => 'content',
                'label' => 'Content',
                'rules' => 'trim|required|min_length[5]|htmlentities',
            );
        }
        if (in_array('media_name', $validate_fields)) {
            $validation_rules[] = array(
                'field' => 'media_name',
                'label' => 'Image / Video',
                'rules' => 'trim|callback_custom_notification_image_video[media_name]|htmlentities',
            );
        }

        $this->form_validation->set_rules($validation_rules);

        return $this->form_validation->run();
    }

    function custom_notification_image_video($media_name, $image_video_control) {


        if ($_FILES[$image_video_control]['name'] != '' && $this->input->post('offer_type', TRUE) == IMAGE_OFFER_CONTENT_TYPE) {
//            if ($_FILES[$image_control]['type'] != 'image/jpeg' && $_FILES[$image_control]['type'] != 'image/jpg' && $_FILES[$image_control]['type'] != 'image/gif' && $_FILES[$image_control]['type'] != 'image/png') {
            if (!in_array($_FILES[$image_video_control]['type'], array('image/jpeg', 'image/jpg', 'image/png', 'video/mp4', 'video/webm', 'video/ogg', 'video/ogv', 'video/wmv', 'video/vob', 'video/swf', 'video/mov', 'video/m4v', 'video/flv'))) {
                $this->form_validation->set_message('custom_notification_image_video', 'The {field} contain invalid image/video type.');
                return FALSE;
            }
            if ($_FILES[$image_video_control]['error'] > 0) {
                $this->form_validation->set_message('custom_notification_image_video', 'The {field} contain invalid image/video.');
                return FALSE;
            }
            if ($_FILES[$image_video_control]['size'] <= 0) {
                $this->form_validation->set_message('custom_notification_image_video', 'The {field} contain invalid image size / video size.');
                return FALSE;
            }
        } else {
            $this->form_validation->set_message('custom_notification_image_video', 'The {field} field is required.');
            return FALSE;
        }

        return TRUE;
    }

    function custom_expiry_time_check($expire_time) {

        $expire_time = strtotime($expire_time);
        $today_time = strtotime(date('Y-m-d H:i'));

        if (!empty($expire_time) && $expire_time > $today_time) {
            return TRUE;
        } else {
            $this->form_validation->set_message('custom_expiry_time_check', 'Expire Date & Time should be greater than Current Date & Time.');
            return FALSE;
        }
    }

    function custom_broadcast_time_check($broadcast_time) {

        $broadcast_time = strtotime($broadcast_time);
        $today_time = strtotime(date('Y-m-d H:i'));

        if ($broadcast_time >= $today_time) {
            if ($this->input->post('expiry_time', TRUE) != '') {
                $expire_time = strtotime($this->input->post('expiry_time', TRUE));
                if ($expire_time <= $broadcast_time) {
                    $this->form_validation->set_message('custom_broadcast_time_check', 'Broadcast Date & Time should be greater than Expire Date & Time.');
                    return FALSE;
                }
            }
            return TRUE;
        } else {
            $this->form_validation->set_message('custom_broadcast_time_check', 'Broadcast Date & Time should be greater than Current Date & Time.');
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
            dashboard_redirect($this->loggedin_user_type);
        }
    }

}
