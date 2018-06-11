<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Notifications
        extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Common_model', '', TRUE);
    }

    public function index() {
        $this->data['title'] = $this->data['page_header'] = 'Add Notification';

        $this->bread_crum[] = array(
            'url' => '',
            'title' => 'Notifications',
        );

//        $this->data['back_url'] = $user_dashboard;
        $this->template->load('user', 'Common/Notifications/index', $this->data);
    }

    public function save($notification_type = NULL, $id = null) {

        $this->data['title'] = $this->data['page_header'] = 'Add Notification';

        $this->bread_crum[] = array(
            'url' => '',
            'title' => 'Notifications',
        );

//        $this->data['back_url'] = $user_dashboard;
        $this->template->load('user', 'Common/Notifications/form', $this->data);
    }

}
