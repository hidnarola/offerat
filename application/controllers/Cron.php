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
                $converted_today_date = new DateTime($date);
                $converted_today_date->setTimezone(new DateTimeZone($spo['timezone']));
                $converted_today_date_ = strtotime($converted_today_date->format('Y-m-d'));

                $converted_from_date = new DateTime($spo['from_date'] . ' 00:00:00');
                $converted_from_date->setTimezone(new DateTimeZone($spo['timezone']));
                $converted_from_date_ = strtotime($converted_from_date->format('Y-m-d'));

                $converted_to_date = new DateTime($spo['to_date'] . ' 00:00:00');
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
                $converted_today_date = new DateTime($date);
                $converted_today_date->setTimezone(new DateTimeZone($spo['timezone']));
                $converted_today_date_ = strtotime($converted_today_date->format('Y-m-d'));

                $converted_from_date = new DateTime($spo['from_date'] . ' 00:00:00');
                $converted_from_date->setTimezone(new DateTimeZone($spo['timezone']));
                $converted_from_date_ = strtotime($converted_from_date->format('Y-m-d'));

                $converted_to_date = new DateTime($spo['to_date'] . ' 00:00:00');
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
                $converted_today_date = new DateTime($date);
                $converted_today_date->setTimezone(new DateTimeZone($spo['timezone']));
                $converted_today_date_ = strtotime($converted_today_date->format('Y-m-d'));

                $converted_to_date = new DateTime($spo['to_date'] . ' 00:00:00');
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
                $converted_today_date = new DateTime($date);
                $converted_today_date->setTimezone(new DateTimeZone($spo['timezone']));
                $converted_today_date_ = strtotime($converted_today_date->format('Y-m-d'));

                $converted_to_date = new DateTime($spo['to_date'] . ' 00:00:00');
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

    //Send Push Notification
    public function push_notification() {

        $this->Common_model->master_save('test', array('id' => 1, 'created_date' => date('Y-m-d H:i:s')));
        $this->load->library('Push_notification');

        $currnt_datetime = date('Y-m-d H:i');

        $currnt_datetime = new DateTime($currnt_datetime);
        $currnt_datetime->setTimezone(new DateTimeZone(date_default_timezone_get()));
        $currnt_datetime = $currnt_datetime->format('Y-m-d H:i');
        $get_5_min_ago_datetime = date('Y-m-d H:i', strtotime("-5 minute"));
//        echo '<br>' . $currnt_datetime = $currnt_datetime->format('Y-m-d H:i');
//        echo '<br>' . $get_5_min_ago_datetime = date('Y-m-d H:i', strtotime("-5 minute"));

        $yesterday_date = date('Y-m-d', strtotime("-1 days"));
        $next_date = date('Y-m-d', strtotime("+1 days"));

        $select_offer = array(
            'table' => tbl_offer_announcement . ' offer_announcement',
            'fields' => array('offer_announcement.*', 'country.timezone', 'store.store_name', 'store.store_logo', 'mall.mall_name', 'mall.mall_logo'),
            'where' => array(
                'offer_announcement.is_delete' => IS_NOT_DELETED_STATUS,
                'offer_announcement.type' => OFFER_OFFER_TYPE
            ),
            'where_with_sign' => array(
                '(country.id_country = store.id_country OR country.id_country = mall.id_country)',
                '((store.is_delete = ' . IS_NOT_DELETED_STATUS . ' AND  store.status = ' . ACTIVE_STATUS . ')  OR (mall.is_delete = ' . IS_NOT_DELETED_STATUS . ' AND  mall.status = ' . ACTIVE_STATUS . '))',
                '(DATE_FORMAT(offer_announcement.broadcasting_time, "%Y-%m-%d") BETWEEN "' . $yesterday_date . '" AND "' . $next_date . '" )'
            ),
            'join' => array(
                array(
                    'table' => tbl_offer_announcement_image . ' as offer_announcement_image',
                    'condition' => 'offer_announcement_image.id_offer = offer_announcement.id_offer',
                    'join' => 'left',
                ),
                array(
                    'table' => tbl_store . ' as store',
                    'condition' => 'store.id_store = offer_announcement.id_store',
                    'join' => 'left',
                ),
                array(
                    'table' => tbl_mall . ' as mall',
                    'condition' => 'mall.id_mall = offer_announcement.id_mall',
                    'join' => 'left'
                ),
                array(
                    'table' => tbl_country . ' as country',
                    'condition' => '(country.id_country = store.id_country OR country.id_country = mall.id_country)',
                    'join' => 'left'
                )
            ),
            'group_by' => array('offer_announcement.id_offer'),
        );

        $offers_list = $this->Common_model->master_select($select_offer);
        
        if (isset($offers_list) && sizeof($offers_list) > 0) {
            foreach ($offers_list as $list) {

                $messageArray = array();

                $messageArray['id_offer'] = (int) $list['id_offer'];
                $messageArray['type'] = (int) $list['type'];
                $messageArray['offer_type'] = (int) $list['offer_type'];
                $messageArray['id_mall'] = (int) $list['id_mall'];
                $messageArray['id_store'] = (int) $list['id_store'];
                $messageArray['video_url'] = null;
                $messageArray['id_offer_announcement_image'] = (int) ($list['id_offer_announcement_image'] > 0) ? $list['id_offer_announcement_image'] : 0;

                $messageArray['push_message'] = $list['push_message'];
                $messageArray['media_height'] = ($list['offer_type'] == TEXT_OFFER_CONTENT_TYPE) ? 0 : (int) $list['media_height'];
                $messageArray['media_width'] = ($list['offer_type'] == TEXT_OFFER_CONTENT_TYPE) ? 0 : (int) $list['media_width'];
                $messageArray['content'] = $list['content'];
                $messageArray['broadcasting_time'] = $list['broadcasting_time'];
                $messageArray['expiry_time'] = $list['expiry_time'];
                $messageArray['view_count'] = (int) $list['view_count'];
                $messageArray['impression_count'] = (int) $list['impression_count'];
                $messageArray['created_date'] = $list['created_date'];
                $messageArray['modified_date'] = $list['modified_date'];
                $messageArray['is_testdata'] = (int) $list['is_testdata'];
                $messageArray['is_delete'] = (int) $list['is_delete'];

                $messageArray['media_name'] = $list['media_name'];
                $messageArray['media_thumbnail'] = $list['media_thumbnail'];

                if ($list['id_mall'] > 0) {

                    $messageArray['name'] = $list['mall_name'];
                    $messageArray['logo'] = $list['mall_logo'];
                } elseif ($list['id_store']) {
                    $messageArray['name'] = $list['store_name'];
                    $messageArray['logo'] = $list['store_logo'];
                }

                if ($list['offer_type'] == IMAGE_OFFER_CONTENT_TYPE) {
                    $select_image = array(
                        'table' => tbl_offer_announcement_image,
                        'where' => array(
                            'is_delete' => IS_NOT_DELETED_STATUS,
                            'id_offer' => $list['id_offer']
                        ),
                        'order_by' => array('sort_order' => 'ASC')
                    );
                    $image_data = $this->Common_model->master_single_select($select_image);
                    if (isset($image_data) && sizeof($image_data) > 0) {
                        $messageArray['media_name'] = $image_data['image_name'];
                        $messageArray['media_thumbnail'] = $image_data['image_thumbnail'];
                        $messageArray['media_height'] = $image_data['image_height'];
                        $messageArray['media_width'] = $image_data['image_width'];
                    }
                }

                if ($list['offer_type'] == VIDEO_OFFER_CONTENT_TYPE) {
                    $messageArray['media_name'] = $list['media_name'];
                    $messageArray['media_thumbnail'] = $list['media_thumbnail'];
                }

//                pr($messageArray);
//                echo '<br>';
//                die();
                $messageArray['action'] = 100;
                $messageArrayJson = json_encode($messageArray);

                $country_zone_today_date = new DateTime($currnt_datetime);
                $country_zone_today_date->setTimezone(new DateTimeZone($list['timezone']));
                $country_zone_today_date->format('Y-m-d H:i');
                $country_zone_today_date_ = strtotime($country_zone_today_date->format('Y-m-d H:i'));
//                echo '<br>' . $country_zone_today_date->format('Y-m-d H:i');
//                echo '<br>' . $country_zone_today_date_ = strtotime($country_zone_today_date->format('Y-m-d H:i'));

                $country_zone_broacast_time = new DateTime($list['broadcasting_time']);
                $country_zone_broacast_time->setTimezone(new DateTimeZone($list['timezone']));
                $country_zone_broacast_time->format('Y-m-d H:i');
                $country_zone_broacast_time_ = strtotime($country_zone_broacast_time->format('Y-m-d H:i'));

                $get_5_min_ago_datetime = date('Y-m-d H:i', strtotime("-5 minute"));
                $get_5_min_ago_datetime = new DateTime($get_5_min_ago_datetime);
                $get_5_min_ago_datetime->setTimezone(new DateTimeZone($list['timezone']));
                $get_5_min_ago_datetime->format('Y-m-d H:i');
                $get_5_min_ago_datetime_ = strtotime($get_5_min_ago_datetime->format('Y-m-d H:i'));

//                echo '<br>' . $country_zone_broacast_time->format('Y-m-d H:i');
//                echo '<br>' . $country_zone_broacast_time_ = strtotime($country_zone_broacast_time->format('Y-m-d H:i'));
//                echo '<br>' . strtotime($get_5_min_ago_datetime);
//                echo '<br>' . $country_zone_today_date_;
//                die();
//                echo $country_zone_broacast_time->format('Y-m-d H:i').'=====' . $get_5_min_ago_datetime->format('Y-m-d H:i').'======' . $country_zone_today_date->format('Y-m-d H:i');
//                echo '<br>';
                if ($country_zone_broacast_time_ > $get_5_min_ago_datetime_ && $country_zone_broacast_time_ <= $country_zone_today_date_) {
//                    echo 'messageArray';
//                    echo '<pre>';
//                    var_dump($messageArray);
//                    echo '<br>';
//                    die("hello");
                    $select_user = array(
                        'table' => tbl_user . ' user',
                        'where' => array(
                            'user.is_delete' => IS_NOT_DELETED_STATUS,
                            'user.user_type' => NORMAL_USER_TYPE,
                            'user.status' => ACTIVE_STATUS,
                            'favorite.is_delete' => IS_NOT_DELETED_STATUS,
                            'favorite.is_notification_enable' => NOTIFICATION_ENABLED
                        ),
                        'where_with_sign' => array(
                            '(favorite.id_store = ' . $list['id_store'] . ' OR favorite.id_mall = ' . $list['id_mall'] . ')',
                            'device_token != ""'
                        ),
                        'join' => array(
                            array(
                                'table' => tbl_favorite . ' as favorite',
                                'condition' => 'favorite.id_user = user.id_user',
                                'join' => 'left'
                            )
                        ),
                        'group_by' => array('user.id_user')
                    );

                    $users_list = $this->Common_model->master_select($select_user);
                    query();
                    pr($users_list, 1);
                    $user_group = array_chunk($users_list, 50);
//                pr($user_group, 1);
                    if (isset($user_group) && sizeof($user_group) > 0) {
                        foreach ($user_group as $key => $group) {
                            $android_device_token_ids = array();
                            if (isset($group) && sizeof($group) > 0) {
                                foreach ($group as $g) {
                                    if ($g['device_type'] == ANDROID_DEVICE_TYPE)
                                        $android_device_token_ids[] = $g['device_token'];
                                }
                            }
                            if (isset($android_device_token_ids) && sizeof($android_device_token_ids) > 0) {

                                $response = $this->push_notification->sendMessageToAndroidPhone(GOOGLE_PUSH_NOTIFICATION_API_KEY, $android_device_token_ids, $messageArrayJson);
                                $response = json_decode($response, true);
//                                echo 'rresponse';
//                                pr($response);
                                if (!empty($android_device_token_ids)) {
                                    $output['response'] = $response;
                                    $output['success_count'] = (int) $response['success'];
                                    $output['failure_count'] = (int) $response['failure'];
//                                    echo 'results';
//                                    pr($response['results']);
                                    if (isset($response['results']) && sizeof($response['results']) > 0) {
                                        $insert_offer_cron = array();
                                        foreach ($response['results'] as $key => $val) {
                                            $response_message = '';
                                            if (isset($val) && isset($val['error']))
                                                $response_message = $val['error'];
                                            elseif (isset($val) && isset($val['message_id']))
                                                $response_message = $val['message_id'];

                                            $insert_offer_cron[] = array(
                                                'id_offer' => $list['id_offer'],
                                                'device_token' => $android_device_token_ids[$key],
                                                'response' => $response_message,
                                                'created_date' => date('Y-m-d H:i:s')
                                            );
                                        }
                                        if (isset($insert_offer_cron) && sizeof($insert_offer_cron) > 0)
                                            $this->Common_model->master_save(tbl_offer_cron, $insert_offer_cron, TRUE);
                                    }
                                }
                            } elseif (isset($iphone_device_token_ids) && sizeof($iphone_device_token_ids) > 0) {
                                //iphone push notification
                            }
                        }
                    }
                }
            }
        }
    }

}
