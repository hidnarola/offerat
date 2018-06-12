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
    }

    public function index($type = NULL) {

        if (!is_null($type) && in_array($type, array('offers', 'announcements'))) {

            $list_url = '';
            $add_url = '';
            $this->data['title'] = $this->data['page_header'] = ucfirst($type);

            if ($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE) {
                $list_url = 'country-admin/notifications/' . $type;
                $add_url = 'country-admin/notifications/' . $type . '/save';
            } elseif ($this->loggedin_user_type == STORE_OR_MALL_ADMIN_USER_TYPE) {
                $list_url = 'mall-store-user/notifications/' . $type;
                $add_url = 'mall-store-user/notifications/' . $type . '/save';
            } elseif ($this->loggedin_user_type == SUPER_ADMIN_USER_TYPE) {
                redirect('super-admin/dashboard');
            } else {
                redirect('/');
            }

            $this->bread_crum[] = array(
                'url' => '',
                'title' => ucfirst($type) . ' List',
            );

            $this->data['list_url'] = $list_url;
            $this->data['add_url'] = $add_url;
            $this->data['type'] = $type;

//        $this->data['back_url'] = $user_dashboard;
            $this->template->load('user', 'Common/Notifications/index', $this->data);
        } else {
            dashboard_redirect($this->loggedin_user_type);
        }
    }

    public function save($type = NULL, $id = null) {
        
        if (!is_null($type) && in_array($type, array('offers', 'announcements'))) {

            if ($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE)
                $back_url = 'country-admin/notifications/' . $type;
            elseif ($this->loggedin_user_type == STORE_OR_MALL_ADMIN_USER_TYPE)
                $back_url = 'mall-store-user/notifications/' . $type;
            elseif ($this->loggedin_user_type == SUPER_ADMIN_USER_TYPE) {
                redirect('super-admin/dashboard');
            } else {
                redirect('/');
            }

            if ($this->input->post()) {
//                pr($_POST, 1);
//                pr($_FILES, 1);
                $validate_fields = array(
                    'store_mall_id',
                    'offer_type',
                    'expiry_time',
                    'broadcasting_time',
                    'content',
                    'media_name'
                );

                if ($type == 'offers')
                    $validate_fields[] = 'push_message';

                if ($this->_validate_form($validate_fields)) {

                    $uploaded_file_type = @$_FILES['media_name']['type'];
                    $image_types = array('image/jpeg', 'image/jpg', 'image/png');
                    $video_types = array('video/mp4', 'video/webm', 'video/ogg', 'video/ogv', 'video/wmv', 'video/vob', 'video/swf', 'video/mov', 'video/m4v', 'video/flv');

                    $do_notification_image_video_has_error = false;
                    $img_name = $image_name = '';
                    if (in_array($this->input->post('offer_type', TRUE), array(IMAGE_OFFER_CONTENT_TYPE, TEXT_OFFER_CONTENT_TYPE)) && isset($_FILES['media_name'])) {

                        if (($_FILES['media_name']['size']) > 0) {
                            $image_path = $_SERVER['DOCUMENT_ROOT'] . offer_media_path;
                            if (!file_exists($image_path)) {
                                $this->Common_model->created_directory($image_path);
                            }
                            $supported_files = 'gif|jpg|png|jpeg|mp4|webm|ogg|ogv|wmv|vob|swf|mov|m4v|flv';
                            $img_name = $this->Common_model->upload_image('media_name', $image_path, $supported_files);

                            if (empty($img_name)) {
                                $do_notification_image_video_has_error = true;
                                $this->data['image_errors'] = $this->upload->display_errors();
                            } else {
                                $image_name = $img_name;
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
                            'type' => ($type == OFFER_OFFER_TYPE) ? OFFER_OFFER_TYPE : ANNOUNCEMENT_OFFER_TYPE,
                            'offer_type' => (in_array($uploaded_file_type, $video_types)) ? VIDEO_OFFER_CONTENT_TYPE : ($this->input->post('offer_type', TRUE) == TEXT_OFFER_CONTENT_TYPE) ? TEXT_OFFER_CONTENT_TYPE : IMAGE_OFFER_CONTENT_TYPE,
                            'expiry_time' => $expiry_time_text,
                            'broadcasting_time' => $broadcasting_time_text,
                            'content' => ($this->input->post('offer_type', TRUE) == TEXT_OFFER_CONTENT_TYPE) ? $this->input->post('content', TRUE) : '',
                            'media_name' => ($this->input->post('offer_type', TRUE) == IMAGE_OFFER_CONTENT_TYPE) ? $image_name : '',
                            'id_mall' => $mall_id,
                            'id_store' => $store_id
                        );
                        if ($type == 'offers')
                            $notification_data['push_message'] = $this->input->post('push_message', TRUE);

                        if ($id) {
                            $where = array('id_offer' => $id);
                            $notification_data['modified_date'] = $date;
                            $is_updated = $this->Common_model->master_update(tbl_offer_announcement, $notification_data, $where);
                            if ($is_updated) {
                                $this->session->set_flashdata('success_msg', ucfirst($type) . ' Updated Successfully!');
                            }
                        } else {

                            $notification_data['created_date'] = date('Y-m-d h:i:s');
                            $notification_data['is_testdata'] = (ENVIRONMENT !== 'production') ? 1 : 0;
                            $notification_data['is_delete'] = IS_NOT_DELETED_STATUS;

                            $notification_id = $this->Common_model->master_save(tbl_offer_announcement, $notification_data);
                            if ($notification_id > 0)
                                $this->session->set_flashdata('success_msg', ucfirst($type) . ' Added Successfully!');
                        }

                        redirect($back_url);
                    }
                }
            }


            if (isset($id) && $id > 0) {
                
                $this->data['title'] = $this->data['page_header'] = 'Edit ' . ucfirst($type);
                
                $select_notification = array(
                    'table' => tbl_offer_announcement,
                    'where' => array(
                        'is_delete' => IS_NOT_DELETED_STATUS,
                        'id_offer' => $id
                    )
                );

                $notification_data = $this->Common_model->master_single_select($select_notification);
                $this->data['notification_data'] = $notification_data;
            } else {

                $this->bread_crum[] = array(
                    'url' => $back_url,
                    'title' => ucfirst($type) . ' List',
                );
                $this->data['title'] = $this->data['page_header'] = 'Add ' . ucfirst($type);
            }
            $select_stores = array(
                'table' => tbl_store,
                'fields' => array('id_store', 'store_name'),
                'where' => array('status' => ACTIVE_STATUS, 'is_delete' => IS_NOT_DELETED_STATUS)
            );
            if ($this->loggedin_user_type == STORE_OR_MALL_ADMIN_USER_TYPE)
                $select_stores['where_with_sign'] = array('FIND_IN_SET("' . $this->loggedin_user_data['user_id'] . '", id_users) <> 0');

            $stores_list = $this->Common_model->master_select($select_stores);

            $select_malls = array(
                'table' => tbl_mall,
                'fields' => array('id_mall', 'mall_name'),
                'where' => array('status' => ACTIVE_STATUS, 'is_delete' => IS_NOT_DELETED_STATUS)
            );
            if ($this->loggedin_user_type == STORE_OR_MALL_ADMIN_USER_TYPE)
                $select_malls['where_with_sign'] = array('FIND_IN_SET("' . $this->loggedin_user_data['user_id'] . '", id_users) <> 0');

            $malls_list = $this->Common_model->master_select($select_malls);

            $this->data['stores_list'] = $stores_list;
            $this->data['malls_list'] = $malls_list;

            $this->data['type'] = $type;
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
    function _validate_form($validate_fields) {
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
        if (in_array('expiry_time', $validate_fields)) {
            $validation_rules[] = array(
                'field' => 'expiry_time',
                'label' => 'Expire Date and Time',
                'rules' => 'trim|required|htmlentities',
            );
        }
        if (in_array('broadcasting_time', $validate_fields)) {
            $validation_rules[] = array(
                'field' => 'broadcasting_time',
                'label' => 'Broadcast Date & Time',
                'rules' => 'trim|required|htmlentities',
            );
        }

        if ($this->input->post('offer_type', TRUE) == TEXT_OFFER_CONTENT_TYPE) {
            if (in_array('content', $validate_fields)) {
                $validation_rules[] = array(
                    'field' => 'content',
                    'label' => 'Content',
                    'rules' => 'trim|required|min_length[5]|htmlentities',
                );
            }
        }
        if ($this->input->post('offer_type', TRUE) == IMAGE_OFFER_CONTENT_TYPE) {
            if (in_array('media_name', $validate_fields)) {
                $validation_rules[] = array(
                    'field' => 'media_name',
                    'label' => 'Image / Video',
                    'rules' => 'trim|callback_custom_notification_image_video[media_name]|htmlentities',
                );
            }
        }

        $this->form_validation->set_rules($validation_rules);

        return $this->form_validation->run();
    }

    function custom_notification_image_video($image, $image_video_control) {

        if ($_FILES[$image_video_control]['name'] != '') {
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

}
