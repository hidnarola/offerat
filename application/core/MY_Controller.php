<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        is_logged_in();

        if ($_SERVER['HTTP_HOST'] == 'offerat.sale')
            $this->db->query('SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,"ONLY_FULL_GROUP_BY",""))');

        $this->loggedin_user_type = $this->session->userdata('loggedin_user_type');
        $this->loggedin_user_data = $this->session->userdata('loggedin_user_data');
        $this->loggedin_user_country_data = @$this->session->userdata('loggedin_user_country_data');
//        pr($this->loggedin_user_country_data);
        $this->image_extensions_arr = array('jpeg', 'jpg', 'png');
        $this->video_extensions_arr = array('mp4', 'webm', 'ogg', 'ogv', 'wmv', 'vob', 'swf', 'mov', 'm4v', 'flv');
        $this->image_video_extensions_arr = array($this->image_extensions_arr, $this->video_extensions_arr);

        $this->image_types_arr = array('image/jpeg', 'image/jpg', 'image/png');
        $this->video_types_arr = array('video/mp4', 'video/webm', 'video/ogg', 'video/ogv', 'video/wmv', 'video/vob', 'video/swf', 'video/mov', 'video/m4v', 'video/flv');
        $this->image_video_types_arr = array_merge($this->image_types_arr, $this->video_types_arr);
    }

    public function captcha_config() {
        return $config = array(
            'img_url' => base_url() . 'assets/uploads/captcha/',
            'img_path' => './assets/uploads/captcha/',
        );
    }

    public function get_captcha_images() {
        $this->removeUnusedCaptchaImages();

        $config = $this->captcha_config();
        $captcha = create_captcha($config);

        $this->session->unset_userdata('valuecaptchaCode');
        $this->session->set_userdata('valuecaptchaCode', $captcha['word']);

        return $captcha;
    }

    public function refresh() {
        $this->removeUnusedCaptchaImages();
        $config = $this->captcha_config();
        $captcha = create_captcha($config);
        $this->session->unset_userdata('valuecaptchaCode');
        $this->session->set_userdata('valuecaptchaCode', $captcha['word']);
        echo $captcha['image'];
    }

    public function removeUnusedCaptchaImages() {
        delete_files('./assets/uploads/captcha/', TRUE);
    }

}
