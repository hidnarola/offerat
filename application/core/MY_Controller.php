<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        is_logged_in();

        $this->loggedin_user_type = $this->session->userdata('loggedin_user_type');
        $this->loggedin_user_data = $this->session->userdata('loggedin_user_data');

        $this->image_extensions_arr = array('jpeg', 'jpg', 'png');
        $this->video_extensions_arr = array('mp4', 'webm', 'ogg', 'ogv', 'wmv', 'vob', 'swf', 'mov', 'm4v', 'flv');
        $this->image_video_extensions_arr = array($this->image_extensions_arr, $this->video_extensions_arr);

        $this->image_types_arr = array('image/jpeg', 'image/jpg', 'image/png');
        $this->video_types_arr = array('video/mp4', 'video/webm', 'video/ogg', 'video/ogv', 'video/wmv', 'video/vob', 'video/swf', 'video/mov', 'video/m4v', 'video/flv');
        $this->image_video_types_arr = array_merge($this->image_types_arr, $this->video_types_arr);
    }

}
