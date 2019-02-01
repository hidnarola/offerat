<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class TermsCondition extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Common_model', '', TRUE);
        $this->data['title'] = $this->data['page_header'] = 'Pages';
        $this->bread_crum[] = array(
            'url' => base_url() . 'super-admin/terms-conditions',
            'title' => 'Pages',
        );
    }

    /**
     * Terms & Conditions List
     * */
    public function index() {
        $this->data['page'] = 'terms_conditions_list_page';
        $this->data['title'] = $this->data['page_header'] = 'Pages';

        $this->bread_crum[] = array(
            'url' => '',
            'title' => 'List',
        );

        $this->template->load('user', 'Superadmin/TermsCondition/index', $this->data);
    }

    /**
     * Display and Filter Countries
     */
    public function filter_terms_condition() {
        $filter_array = $this->Common_model->create_datatable_request($this->input->post());

        $filter_array['group_by'] = array(tbl_terms_conditions . '.id');
        $filter_array['order_by'] = array(tbl_terms_conditions . '.id' => 'DESC');
        $filter_array['where'] = array(tbl_terms_conditions . '.is_delete' => IS_NOT_DELETED_STATUS);

        $filter_records = $this->Common_model->get_filtered_records(tbl_terms_conditions, $filter_array);
        $total_filter_records = $this->Common_model->get_filtered_records(tbl_terms_conditions, $filter_array, 1);

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->Common_model->master_count(tbl_terms_conditions),
            "recordsFiltered" => $total_filter_records,
            "data" => $filter_records,
        );
        echo json_encode($output);
    }

    public function save($id = null) {
        $this->data['title'] = $this->data['page_action'] = 'Add Page';
        $this->data['terms_conditions'] = null;

        $this->data['back_url'] = 'super-admin/terms-conditions';
        $this->data['post_url'] = 'super-admin/terms-conditions/save/' . $id;

        $this->bread_crum[] = array(
            'url' => base_url() . $this->data['back_url'],
            'title' => 'List',
        );

        if ($this->input->post()) {
            $validate_fields = array(
                'page_title',
                'page_content',
            );

            if ($this->_validate_form($validate_fields)) {
                $page_data = [
                    'page_title' => $this->input->post('page_title'),
                    'page_content' => $this->input->post('page_content'),
                ];

                if (!empty($this->input->post('id'))) {
                    $id = $this->input->post('id', TRUE);
                    $where_data = array('id' => $id);
                    $is_updated = $this->Common_model->master_update(tbl_terms_conditions, $page_data, $where_data);

                    if ($is_updated) {
                        $this->session->set_flashdata('success_msg', "Page details has been updated successfully.");
                    } else {
                        $this->session->set_flashdata('success_msg', "Page details has been updated successfully.");
                    }
                }
                redirect('super-admin/terms-conditions');
            }
        }

        if (isset($id) && $id > 0) {
            $where = array('id' => $id);
            $select_data = array(
                'table' => tbl_terms_conditions,
                'where' => $where
            );
            $this->data['terms_conditions'] = $this->Common_model->master_single_select($select_data);

            if (!$this->data['terms_conditions']) {
                redirect('super-admin/terms-conditions');
            }

            $this->data['title'] = $this->data['page_action'] = 'Update Terms & Conditions';
            $this->bread_crum[] = array(
                'url' => '',
                'title' => 'Update Page',
            );
        } else {
            $this->bread_crum[] = array(
                'url' => '',
                'title' => 'Add Page',
            );
        }

        $this->template->load('user', 'Superadmin/TermsCondition/form', $this->data);
    }

    /**

     * @param array $validate_fields array of control names
     * @return boolean
     */
    function _validate_form($validate_fields) {
        $validation_rules = array();

        if (in_array('page_title', $validate_fields)) {
            $validation_rules[] = array(
                'field' => 'page_title',
                'label' => 'Page Title',
                'rules' => 'trim|required|max_length[255]',
            );
        }
        if (in_array('page_content', $validate_fields)) {
            $validation_rules[] = array(
                'field' => 'page_content',
                'label' => 'Page Content',
                'rules' => 'required',
            );
        }
        $this->form_validation->set_rules($validation_rules);

        return $this->form_validation->run();
    }

}
