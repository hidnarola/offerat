<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Content_pages extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Common_model', '', TRUE);
        $this->load->model('Email_template_model', '', TRUE);
    }

    public function contact_us() {
        $this->data['title'] = $this->data['page'] = $this->data['page_header'] = $this->data['sub_header'] = 'Contact Us';

        if ($this->input->post()) {
            $captcha_insert = $this->input->post('captcha');
            $contain_sess_captcha = $this->session->userdata('valuecaptchaCode');

            if ($captcha_insert === $contain_sess_captcha) {
                $validate_fields = array(
                    'name',
                    'email_id',
                    'contact_number',
                    'message'
                );

                if ($this->_validate_contact_us_form($validate_fields)) {
                    $name = $this->input->post('name', TRUE);
                    $email_id = $this->input->post('email_id', TRUE);
                    $contact_number = $this->input->post('contact_number', TRUE);
                    $message = $this->input->post('message', TRUE);

                    $send_message = "
                            <html>
                                <head>
                                    <title>HTML email</title>
                                </head>
                                <body>
                                    <table>
                                        <tr>
                                            <th align='left'>Name</th>
                                            <td>" . $name . "</td>
                                        </tr>
                                        <tr>
                                            <th align='left'>Email</th>
                                            <td>" . $email_id . "</td>
                                        </tr>
                                        <tr>
                                            <th align='left'>Phone Number</th>
                                            <td>" . $contact_number . "</td>
                                        </tr>
                                        <tr>
                                            <th align='left'>Message</th>
                                            <td>" . $message . "</td>
                                        </tr>
                                    </table>
                                </body>
                            </html>
                            ";
                    $response = $this->Email_template_model->send_email(NULL, site_info_email, 'Offerat | Contact US', $send_message);
                    if (isset($response) && $response == 'yes') {
                        $this->session->set_flashdata('success_msg', 'Message sent successfully');
                    } else
                        $this->session->set_flashdata('error_msg', 'Message not sent');

                    redirect('contact-us');
                }
            } else {
                $this->session->set_flashdata('error_msg', 'Please enter valid captcha code.');
                redirect('contact-us');
            }
        }

        $captcha_image = $this->get_captcha_images();
        $this->data['captchaImg'] = $captcha_image['image'];

        $this->template->load('front', 'Content_pages/contact_us', $this->data);
    }

    public function about_us() {

        $this->data['title'] = $this->data['page'] = $this->data['page_header'] = $this->data['sub_header'] = 'About Us';

        $this->template->load('front', 'Content_pages/about_us', $this->data);
    }

    public function terms_conditions() {
        $this->data['title'] = $this->data['page'] = $this->data['page_header'] = $this->data['sub_header'] = 'Terms & Conditions';

        $page_select = array(
            'table' => tbl_terms_conditions,
            'where' => array('is_delete' => 0, 'page_type' => 'Privacy'),
        );
        $this->data['pages'] = $this->Common_model->master_select($page_select);

        $this->template->load('front', 'Content_pages/terms_conditions', $this->data);
    }

    /*
      Validation for Contact US form
     */

    function _validate_contact_us_form($validate_fields) {
        $validation_rules = array();

        if (in_array('name', $validate_fields)) {
            $validation_rules[] = array(
                'field' => 'name',
                'label' => 'Name',
                'rules' => 'trim|required|min_length[2]|max_length[255]|htmlentities',
            );
        }
        if (in_array('email_id', $validate_fields)) {
            $validation_rules[] = array(
                'field' => 'email_id',
                'label' => 'Email',
                'rules' => 'trim|required|min_length[3]|max_length[255]|valid_email|htmlentities'
            );
        }
        if (in_array('contact_number', $validate_fields)) {
            $validation_rules[] = array(
                'field' => 'contact_number',
                'label' => 'Contact Number',
                'rules' => 'trim|max_length[30]|htmlentities',
            );
        }
        if (in_array('message', $validate_fields)) {
            $validation_rules[] = array(
                'field' => 'message',
                'label' => 'Message',
                'rules' => 'trim|min_length[10]|max_length[255]|htmlentities',
            );
        }

        $this->form_validation->set_rules($validation_rules);
        return $this->form_validation->run();
    }

    public function refresh_captcha() {
        return $this->refresh();
    }

}
