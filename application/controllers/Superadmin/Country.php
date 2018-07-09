<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Country extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Common_model', '', TRUE);
        $this->data['title'] = $this->data['page_header'] = 'Country';
        $this->bread_crum[] = array(
            'url' => base_url() . 'super-admin/country',
            'title' => 'Countries',
        );
    }

    /*
     * Country List
     */

    public function index() {

        $this->data['page'] = 'country_list_page';
        $this->data['title'] = $this->data['page_header'] = 'Countries';

        $this->bread_crum[] = array(
            'url' => '',
            'title' => 'List',
        );

        $this->template->load('user', 'Superadmin/Country/index', $this->data);
    }

    /**
     * Display and Filter Countries
     */
    public function filter_countries() {
        $filter_array = $this->Common_model->create_datatable_request($this->input->post());

        $filter_array['group_by'] = array(tbl_country . '.id_country');
        $filter_array['order_by'] = array(tbl_country . '.id_country' => 'DESC');
        $filter_array['where'] = array(tbl_country . '.is_delete' => IS_NOT_DELETED_STATUS);

        $filter_array['join'][] = array(
            'table' => tbl_user . ' as user',
            'condition' => tbl_user . '.id_user = ' . tbl_country . '.id_users',
            'join_type' => 'left',
        );

        $filter_records = $this->Common_model->get_filtered_records(tbl_country, $filter_array);
        $total_filter_records = $this->Common_model->get_filtered_records(tbl_country, $filter_array, 1);

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->Common_model->master_count(tbl_country),
            "recordsFiltered" => $total_filter_records,
            "data" => $filter_records,
        );
        echo json_encode($output);
    }

    /**
     * Add / Edit Country
     * @param int $id : country_id (Optional) to update Country Details
     */
    public function save($id = null) {
        if ($this->input->post()) {

            $validate_fields = array(
                'country_name',
                'timezone',
                'email_id',
                'status'
            );
            if ($this->input->post('password', TRUE) != "") {
                $validate_fields[] = 'password';
            }

            if ($this->_validate_form($validate_fields)) {

                $id = $this->input->post('id', TRUE);
                $date = date('Y-m-d h:i:s');
                $image_name = '';
                $user_id = 0;

                $do_flag_image_has_error = false;
                if (isset($_FILES['country_flag'])) {

                    if (($_FILES['country_flag']['size']) > 0) {
//                        echo $image_path = dirname($_SERVER["SCRIPT_FILENAME"]) . '/' . country_img_path;
                        $image_path = $_SERVER['DOCUMENT_ROOT'] . country_img_path;

                        if (!file_exists($image_path)) {
                            $this->Common_model->created_directory($image_path);
                        }
                        $supported_files = 'gif|jpg|png|jpeg';
                        $img_name = $this->Common_model->upload_image('country_flag', $image_path, $supported_files);

                        if (empty($img_name)) {
                            $do_flag_image_has_error = true;
                            $this->data['image_errors'] = $this->upload->display_errors();
                        } else {

                            $image_name = $img_name;
                            if (!empty($id)) {
                                $this->Common_model->remove_image($id, 'country_flag', tbl_country, country_img_path, 'id_country');
                            }
                        }
                    } else {
                        if (!empty($_FILES['country_flag']['tmp_name'])) {
                            $do_flag_image_has_error = true;
                            $this->data['image_errors'] = 'Invalid File';
                        }
                    }
                }

                //check email id exist or not
                $user_select = array(
                    'table' => tbl_user,
                    'where' => array('email_id' => $this->input->post('email_id', TRUE))
                );
                $user_details = $this->Common_model->master_single_select($user_select);

                if (isset($user_details) && sizeof($user_details) > 0) {
                    $user_data = array(
                        'user_type' => COUNTRY_ADMIN_USER_TYPE,
                        'status' => $this->input->post('status', TRUE),
                        'modified_date' => $date
                    );

                    if ($this->input->post('password', TRUE) != '')
                        $user_data['password'] = md5($this->input->post('password', TRUE));

                    $where_data = array('id_user' => $user_details['id_user']);
                    $this->Common_model->master_update(tbl_user, $user_data, $where_data);

                    $user_id = $user_details['id_user'];
                } else {
                    $user_data = array(
                        'email_id' => $this->input->post('email_id', TRUE),
                        'password' => md5($this->input->post('password', TRUE)),
                        'user_type' => COUNTRY_ADMIN_USER_TYPE,
                        'status' => $this->input->post('status', TRUE),
                        'created_date' => $date,
                        'is_testdata' => (ENVIRONMENT !== 'production') ? 1 : 0
                    );
                    $user_id = $this->Common_model->master_save(tbl_user, $user_data);
                }


                if (!$do_flag_image_has_error) {
                    $country_data = array(
                        'country_name' => $this->input->post('country_name', TRUE),
                        'timezone' => $this->input->post('timezone', TRUE),
                        'id_users' => $user_id,
                        'status' => $this->input->post('status', TRUE),
                        'modified_date' => 0,
                        'modified_by' => 0,
                        'is_testdata' => (ENVIRONMENT !== 'production') ? 1 : 0
                    );

                    if (!empty($image_name))
                        $country_data['country_flag'] = $image_name;

                    if (!$id) {
                        // Insert
                        $country_data['created_date'] = $date;
                        $country_data['created_by'] = (isset($this->logged_user['id'])) ? ($this->logged_user['id']) : 0;

                        $country_id = $this->Common_model->master_save(tbl_country, $country_data);
                        if ($country_id > 0) {
                            $this->session->set_flashdata('success_msg', "Country Added Successfully!");
                        }
                    } else {

                        if ($this->input->post('delete_country_image', TRUE) == 'on') {
                            $this->Common_model->remove_image($id, 'country_flag', tbl_country, country_img_path, 'id_country');
                        }

                        // Update
                        $country_data['modified_date'] = $date;
                        $country_data['modified_by'] = isset($this->logged_user['id']) ? ($this->logged_user['id']) : 0;

                        $where = array('id_country' => $id);
                        $is_updated = $this->Common_model->master_update(tbl_country, $country_data, $where);
                        if ($is_updated) {
                            $this->session->set_flashdata('success_msg', "Country Updated Successfully!");
                        }
                    }
                    redirect('super-admin/country');
                }
            }
        }

        $this->data['title'] = $this->data['page_action'] = 'Add Country';
        $this->data['country'] = null;

        $this->data['back_url'] = 'super-admin/country';
        $this->data['post_url'] = 'super-admin/country/save/' . $id;

        $this->bread_crum[] = array(
            'url' => base_url() . $this->data['back_url'],
            'title' => 'List',
        );

        if (isset($id) && $id > 0) {
            $where = array('id_country' => $id);
            $select_data = array(
                'table' => tbl_country,
                'where' => $where,
                'join' => array(
                    array(
                        'table' => tbl_user . ' user',
                        'condition' => 'user.id_user = ' . tbl_country . '.id_users',
                        'left'
                    )
                )
            );
            $this->data['country'] = $this->Common_model->master_single_select($select_data);
            if (!$this->data['country']) {
                redirect('super-admin/country');
            }
            $this->data['title'] = $this->data['page_action'] = 'Update Country';

            $this->bread_crum[] = array(
                'url' => '',
                'title' => 'Update Country',
            );
        } else {
            $this->bread_crum[] = array(
                'url' => '',
                'title' => 'Add Country',
            );
        }

        $time_zone_list = $this->Common_model->timezone_list();
        $this->data['time_zone_list'] = $time_zone_list;
        $this->template->load('user', 'Superadmin/Country/form', $this->data);
    }

    /**

     * @param array $validate_fields array of control names
     * @return boolean
     */
    function _validate_form($validate_fields) {
        $validation_rules = array();

        if (in_array('country_name', $validate_fields)) {
            $validation_rules[] = array(
                'field' => 'country_name',
                'label' => 'Country Name',
                'rules' => 'trim|required|min_length[2]|max_length[255]|htmlentities|callback_check_country_name',
            );
        }
        if (in_array('timezone', $validate_fields)) {
            $validation_rules[] = array(
                'field' => 'timezone',
                'label' => 'Timezone',
                'rules' => 'trim|required|htmlentities',
            );
        }
        if (in_array('email_id', $validate_fields)) {
            $validation_rules[] = array(
                'field' => 'email_id',
                'label' => 'Admin Email Id',
                'rules' => 'trim|required|min_length[3]|max_length[100]|htmlentities',
            );
        }
        if (in_array('password', $validate_fields)) {
            $validation_rules[] = array(
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'trim|required|min_length[5]|max_length[20]|htmlentities',
            );
        }
        if (in_array('status', $validate_fields)) {
            $validation_rules[] = array(
                'field' => 'status',
                'label' => 'Status',
                'rules' => 'trim|required|htmlentities',
            );
        }

        $this->form_validation->set_rules($validation_rules);

        return $this->form_validation->run();
    }

    /**
     * Callback function to check country name exists or not
     * 
     * @param string $country_name : County Name
     * @return boolean
     */
    function check_country_name($country_name) {

        $select_data = array(
            'table' => tbl_country,
            'where' => array(
                'is_delete' => IS_NOT_DELETED_STATUS,
                'country_name' => $country_name
            )
        );

        if ($this->input->post('id', TRUE) != '' && $this->input->post('id', TRUE) > 0)
            $select_data['where_with_sign'] = array('id_country <> "' . $this->input->post('id', TRUE) . '"');

        $check_country_name = $this->Common_model->master_single_select($select_data);

        if (isset($check_country_name) && sizeof($check_country_name) > 0) {
            $this->form_validation->set_message('check_country_name', 'The {field} already exists.');
            return FALSE;
        } else
            return TRUE;
    }

    /*
     * Update country is_delete field
     */

    function delete($country_id = NULL) {

        if (!is_null($country_id) && $country_id > 0) {
            $mall_data = array(
                'table' => tbl_mall,
                'where' => array(
                    'is_delete' => IS_NOT_DELETED_STATUS,
                    'id_country' => $country_id
                )
            );
            $check_mall_country = $this->Common_model->master_single_select($mall_data);
            $store_data = array(
                'table' => tbl_store,
                'where' => array(
                    'is_delete' => IS_NOT_DELETED_STATUS,
                    'id_country' => $country_id
                )
            );
            $check_store_country = $this->Common_model->master_single_select($store_data);

            if ((isset($check_mall_country) && sizeof($check_mall_country) > 0 ) || (isset($check_store_country) && sizeof($check_store_country) > 0 ))
                $this->session->set_flashdata('error_msg', 'You can not delete this Country, Mall OR Store is using this Country.');
            else {
                $update_data = array('is_delete' => IS_DELETED_STATUS);
                $where_data = array(
                    'id_country' => $country_id,
                    'is_delete' => IS_NOT_DELETED_STATUS
                );
                $is_updated = $this->Common_model->master_update(tbl_country, $update_data, $where_data);

                if ($is_updated)
                    $this->session->set_flashdata('success_msg', 'Country deleted successfully.');
                else
                    $this->session->set_flashdata('error_msg', 'Invalid request sent to delete Country. Please try again later.');
            }
        } else
            $this->session->set_flashdata('error_msg', 'Invalid request sent to delete Country. Please try again later.');

        redirect('super-admin/country');
    }

}
