<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Subcategory
        extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Common_model', '', TRUE);
        $this->data['page_header'] = 'Sub-category';
    }

    /*
     * Sub-Category List
     */

    public function index($category_id = NULL) {
        echo $category_id;
        if (!is_null($category_id)) {
            $this->data['page'] = 'sub_category_list_page';
            $this->data['page_header'] = 'Sub-categories';
            $this->data['category_id'] = $category_id;

            $this->template->load('user', 'Superadmin/Subcategory/index', $this->data);
        } else {
            redirect('super-admin/category');
        }
    }

    /**
     * Display and Filter Sub-categories
     */
    public function filter_sub_categories() {
        $filter_array = $this->Common_model->create_datatable_request($this->input->post());

        $filter_records = $this->Common_model->get_filtered_records(tbl_sub_category, $filter_array);
        $total_filter_records = $this->Common_model->get_filtered_records(tbl_sub_category, $filter_array, 1);

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->Common_model->master_count(tbl_country),
            "recordsFiltered" => $total_filter_records,
            "data" => $filter_records,
        );
        echo json_encode($output);
    }

    /**
     * Add / Edit Sub-Category
     * @param int $id : sub_category_id (Optional) to update Sub-Category Details
     */
    public function save($category_id = NULL, $id = null) {
        if (!is_null($category_id)) {
            if ($this->input->post()) {

                $validate_fields = array(
                    'category_id',
                    'sub_category_name',
                    'sort_order',
                    'status'
                );
                if ($this->_validate_form($validate_fields)) {

                    $id = $this->input->post('id', TRUE);
                    $date = date('Y-m-d h:i:s');
                    $image_name = '';
                    $do_category_image_has_error = false;
                    if (isset($_FILES['sub_category_logo'])) {

                        if (($_FILES['sub_category_logo']['size']) > 0) {

                            $image_path = dirname($_SERVER["SCRIPT_FILENAME"]) . '/' . sub_category_img_path;
                            if (!file_exists($image_path)) {
                                $this->Common_model->created_directory($image_path);
                            }
                            $supported_files = 'gif|jpg|png|jpeg';
                            $img_name = $this->Common_model->upload_image('sub_category_logo', $image_path, $supported_files);

                            if (empty($img_name)) {
                                $do_category_image_has_error = true;
                                $this->data['image_errors'] = $this->upload->display_errors();
                            } else {

                                $image_name = $img_name;
                                if (!empty($id)) {
                                    $this->Common_model->remove_image($id, 'sub_category_logo', tbl_category, sub_category_img_path, 'id_category');
                                }
                            }
                        } else {
                            if (!empty($_FILES['sub_category_logo']['tmp_name'])) {
                                $do_category_image_has_error = true;
                                $this->data['image_errors'] = 'Invalid File';
                            }
                        }
                    }

                    if (!$do_category_image_has_error) {
                        $sub_category_data = array(
                            'category_name' => $this->input->post('category_name', TRUE),
                            'sort_order' => $this->input->post('sort_order', TRUE),
                            'status' => $this->input->post('status', TRUE),
                            'created_date' => 0,
                            'modified_date' => 0,
                            'is_testdata' => (ENVIRONMENT !== 'production') ? 1 : 0
                        );

                        if (!empty($image_name))
                            $sub_category_data['sub_category_logo'] = $image_name;

                        if (!$id) {
                            // Insert
                            $sub_category_data['created_date'] = $date;
                            $category_id = $this->Common_model->master_save(tbl_category, $sub_category_data);
                            if ($category_id > 0) {
                                $this->session->set_flashdata('success_msg', "Category Added Successfully!");
                            }
                        } else {

                            if ($this->input->post('delete_category_image', TRUE) == 'on') {
                                $this->Common_model->remove_image($id, 'sub_category_logo', tbl_category, sub_category_img_path, 'id_category');
                            }

                            // Update
                            $sub_category_data['modified_date'] = $date;
                            $where = array('id_sub_category' => $id);
                            $is_updated = $this->Common_model->master_update(tbl_category, $sub_category_data, $where);
                            if ($is_updated) {
                                $this->session->set_flashdata('success_msg', "Category Updated Successfully!");
                            }
                        }
                        redirect('super-admin/category');
                    }
                }
            }

            $this->data['title'] = $this->data['page_action'] = 'Add Sub-category';
            $this->data['category'] = null;

            $this->data['back_url'] = 'super-admin/category';
            $this->data['post_url'] = 'super-admin/category/save/' . $id;

            $select_categories = array(
                'table' => tbl_category,
                'where' => array('is_delete' => IS_NOT_DELETED_STATUS)
            );
            $this->data['category_list'] = $this->Common_model->master_select($select_categories);

            if (isset($id) && $id > 0) {
                $where = array('id_sub_category' => $id);
                $select_data = array(
                    'table' => tbl_sub_category,
                    'where' => $where
                );
                $this->data['sub_category'] = $this->Common_model->master_single_select($select_data);



                if (!$this->data['sub_category']) {
                    redirect('super-admin/sub_category');
                }
                $this->data['title'] = $this->data['page_action'] = 'Update Sub-category';

                $this->bread_crum[] = array(
                    'url' => '',
                    'title' => 'Update Sub-category',
                );
            } else {
                $this->bread_crum[] = array(
                    'url' => '',
                    'title' => 'Add Sub-category',
                );
            }
            $this->template->load('user', 'Superadmin/Subcategory/form', $this->data);
        } else {
            redirect('super-admin/category');
        }
    }

    /**

     * @param array $validate_fields array of control names
     * @return boolean
     */
    function _validate_form($validate_fields) {
        $validation_rules = array();

        if (in_array('category_name', $validate_fields)) {
            $validation_rules[] = array(
                'field' => 'category_name',
                'label' => 'Category Name',
                'rules' => 'trim|required|min_length[2]|max_length[255]|htmlentities|callback_check_category_name',
            );
        }
        if (in_array('sort_order', $validate_fields)) {
            $validation_rules[] = array(
                'field' => 'sort_order',
                'label' => 'Sort Order',
                'rules' => 'trim|required|is_natural|htmlentities',
                'errors' => array(
                    'is_natural' => 'Sort Order should be 0 or positive number.',
                ),
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
     * Callback function to check category name exists or not
     * 
     * @param string $category_name : County Name
     * @return boolean
     */
    function check_category_name($category_name) {

        $select_data = array(
            'table' => tbl_category,
            'where' => array(
                'is_delete' => 0,
                'category_name' => $category_name
            )
        );

        if ($this->input->post('id', TRUE) != '' && $this->input->post('id', TRUE) > 0)
            $select_data['where_with_sign'] = array('id_category <> "' . $this->input->post('id', TRUE) . '"');

        $check_category_name = $this->Common_model->master_single_select($select_data);

        if (isset($check_category_name) && sizeof($check_category_name) > 0) {
            $this->form_validation->set_message('check_category_name', 'The {field} already exists.');
            return FALSE;
        } else
            return TRUE;
    }

    /**
     * Category List
     * 
     * @param array $select_data conditional array
     * @return array
     */
    function _get_categories($select_data) {
        $categories = $this->Common_model->master_select($categories);
        $categories_arr[''] = array('country_name' => 'Select Category');
        foreach ($categories as $category) {
            $categories_arr[$country['id']] = $category['country_name'];
        }
        return $categories_arr;
    }

}
