<?php

class Common_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /**
     * Display Records Using Data table
     * 
     * @param string $table_name
     * @param array $filter_array array containing filter records
     * @param int $do_count if we want to count records
     * @return result
     */
    function get_filtered_records($table_name, $filter_array, $do_count = 0) {
        if (isset($filter_array['select'])) {
//            $this->db->select($table_name . '.id,' . $filter_array['select']);
            $this->db->select($filter_array['select']);
        }
        $length = $start = 0;

        if (count($filter_array) > 0) {
            $length = (isset($filter_array['length']) && $filter_array['length'] != '' && $filter_array['length'] > 0) ? $filter_array['length'] : 0;
            $start = (isset($filter_array['start']) && $filter_array['start'] != '') ? $filter_array['start'] : 0;
            $eq_cols = (isset($filter_array['col_eq']) && !empty($filter_array['col_eq'])) ? $filter_array['col_eq'] : array();

            if (isset($filter_array['column_search']) && !empty($filter_array['column_search'])) {
                foreach ($filter_array['column_search'] as $column => $value) {
                    $is_like_col = 1;
                    if (in_array($column, $eq_cols)) {
                        $is_like_col = 0;
                    }
                    if (strpos($column, '|') > -1) {
                        $cols = explode('|', $column);
                        $cols_count = count($cols);
                        foreach ($cols as $col_index => $col) {
                            if ($col_index === 0) {
                                $this->db->group_start();
                                ($is_like_col) ? $this->db->like($col, $value) : $this->db->where($col, $value);
                            } else {
                                ($is_like_col) ? $this->db->or_like($col, $value) : $this->db->or_where($col, $value);
                            }
                            if ($cols_count - 1 == $col_index) {
                                $this->db->group_end();
                            }
                        }
                    } else {
                        ($is_like_col) ? $this->db->like($column, $value) : $this->db->where($column, $value);
                    }
                }
            }

            if (isset($filter_array['column_or_search']) && !empty($filter_array['column_or_search'])) {
                $this->db->group_start();
                foreach ($filter_array['column_or_search'] as $column => $value) {
                    $is_like_col = 1;
                    if (in_array($column, $eq_cols)) {
                        $is_like_col = 0;
                    }
                    ($is_like_col) ? $this->db->or_like($column, $value) : $this->db->or_where($column, $value);
                }
                $this->db->group_end();
            }

            if (isset($filter_array['column_date_ranges']) && !empty($filter_array['column_date_ranges'])) {
                foreach ($filter_array['column_date_ranges'] as $range_data) {
                    if (!empty($range_data['column']) && !empty($range_data['start_date']) && !empty($range_data['end_date'])) {
                        $date_range_where_str = $range_data['column'] . ' BETWEEN \'' . $range_data['start_date'] . '\' AND \'' . $range_data['end_date'] . '\'';
                        $this->db->where($date_range_where_str);
                    }
                }
            }
            if (isset($filter_array['column_number_ranges']) && !empty($filter_array['column_number_ranges'])) {
                foreach ($filter_array['column_number_ranges'] as $range_data) {
                    if (!empty($range_data['column']) && !empty($range_data['min_value'])) {
                        $this->db->where($range_data['column'] . ' >= ' . (float) $range_data['min_value']);
                    }
                    if (!empty($range_data['column']) && !empty($range_data['max_value'])) {
                        $this->db->where($range_data['column'] . ' <= ' . (float) $range_data['max_value']);
                    }
                }
            }

            if (isset($filter_array['search']) && $filter_array['search'] != '') {
                $searchable_columns_count = count($filter_array['searchable_columns']);

                foreach ($filter_array['searchable_columns'] as $col_index => $column) {
                    if ($col_index === 0) {
                        $this->db->group_start();
                        $this->db->like($column, $filter_array['search']);
                    } else {
                        $this->db->or_like($column, $filter_array['search']);
                    }
                    if ($searchable_columns_count - 1 == $col_index) {
                        $this->db->group_end();
                    }
                }
            }

            if (isset($filter_array['order']) && !empty($filter_array['order'])) {
                foreach ($filter_array['order'] as $column => $value) {
                    if (strpos($column, '|') > -1) {
                        $column_arr = explode('|', $column);
                        foreach ($column_arr as $order_column) {
                            $this->db->order_by($order_column, $value);
                        }
                    } else {
                        $this->db->order_by($column, $value);
                    }
                }
            }

            if (isset($filter_array['custom_or_where']) && !empty($filter_array['custom_or_where'])) {

                foreach ($filter_array['custom_or_where'] as $column_name => $values) {
                    $values_count = count($values);
                    if ($values_count == 1) {
                        $this->db->where($column_name, $values[0]);
                    } else if ($values_count > 1) {
                        foreach ($values as $index => $value) {
                            if ($index === 0) {
                                $this->db->group_start();
                                $this->db->where($column_name, $value);
                            } else {
                                $this->db->or_where($column_name, $value);
                            }
                            if ($values_count - 1 == $index) {
                                $this->db->group_end();
                            }
                        }
                    }
                }
            }

            if (isset($filter_array['join']) && !empty($filter_array['join'])) {
                foreach ($filter_array['join'] as $index => $join_values) {
                    if (isset($join_values['select_columns'])) {
                        $this->db->select($join_values['select_columns']);
                    }
                    $join_values['join_type'] = isset($join_values['join_type']) ? $join_values['join_type'] : 'left';
                    $this->db->join($join_values['table'], $join_values['condition'], $join_values['join_type']);
                }
            }

            if (isset($filter_array['where']) && !empty($filter_array['where'])) {
                $condition = $filter_array['where'];
                $this->db->group_start();
                foreach ($condition as $key => $wh) {
                    $this->db->where($key, $wh);
                }
                $this->db->group_end();
            }

            if (isset($filter_array['or_where']) && !empty($filter_array['or_where'])) {
                $condition = $filter_array['or_where'];
                $this->db->group_start();
                foreach ($condition as $key => $wh) {
                    $this->db->or_where($key, $wh);
                }
                $this->db->group_end();
            }

            if (isset($filter_array['where_with_sign']) && !empty($filter_array['where_with_sign'])) {
                $condition = $filter_array['where_with_sign'];
                $this->db->group_start();
                foreach ($condition as $key => $wh) {
                    $this->db->where($wh);
                }
                $this->db->group_end();
            }

            if (isset($filter_array['or_where_with_sign']) && !empty($filter_array['or_where_with_sign'])) {
                $condition = $filter_array['or_where_with_sign'];
                $this->db->group_start();
                foreach ($condition as $key => $wh) {
                    $this->db->or_where($wh);
                }
                $this->db->group_end();
            }

            if (isset($filter_array['like']) && !empty($filter_array['like'])) {
                $condition = $filter_array['like'];
                $this->db->group_start();
                foreach ($condition as $key => $wh) {
                    $this->db->like($key, $wh);
                }
                $this->db->group_end();
            }

            if (isset($filter_array['like_with_sign']) && !empty($filter_array['like_with_sign'])) {
                $condition = $filter_array['like_with_sign'];
                $this->db->group_start();
                foreach ($condition as $key => $wh) {
                    $this->db->where($wh);
                }
                $this->db->group_end();
            }

            if (isset($filter_array['or_like']) && !empty($filter_array['or_like'])) {
                $condition = $filter_array['or_like'];
                $this->db->group_start();
                foreach ($condition as $key => $wh) {
                    $this->db->or_like($key, $wh);
                }
                $this->db->group_end();
            }

            if (isset($filter_array['or_like_with_sign']) && !empty($filter_array['or_like_with_sign'])) {
                $condition = $filter_array['or_like_with_sign'];
                $this->db->group_start();
                foreach ($condition as $key => $wh) {
                    $this->db->or_like($wh);
                }
                $this->db->group_end();
            }

            if (isset($filter_array['fields']) && !empty($filter_array['fields'])) {
                $this->db->select($filter_array['fields'], FALSE);
            }

            if (isset($filter_array['group_by']) && !empty($filter_array['group_by'])) {
                $this->db->group_by($filter_array['group_by']);
            }
        }

        if ($do_count) {
            $query = $this->db->get($table_name);
            return count($query->result_array());
        } else {
            if ($length != 0) {
                $this->db->limit($filter_array['length'], $filter_array['start']);
            }
            $query = $this->db->get($table_name);
//            echo $this->db->last_query();die;
            return $query->result_array();
        }
    }

    function create_datatable_request($post_data) {
        $filter_array = array();

        $filter_array['start'] = $post_data['start'];
        $filter_array['length'] = $post_data['length'];
        $filter_array['search'] = $post_data['search']['value'];
        $filter_array['col_eq'] = isset($post_data['col_eq']) ? $post_data['col_eq'] : array();
        $filter_array['extra_fields_select'] = isset($post_data['extra_fields_select']) ? $post_data['extra_fields_select'] : array();
        $datatable_number_range = (isset($post_data['column_number_ranges'])) ? $post_data['column_number_ranges'] : array();
        $datatable_date_range = (isset($post_data['datatable_date_range'])) ? $post_data['datatable_date_range'] : array();


        $column_search = array();
        $column_or_search = array();
        $columns = array();
        $column_date_ranges = array();
        $column_number_ranges = array();
        $filter_array['searchable_columns'] = array();
        $filter_array['select'] = '';

        $posted_column = $post_data['columns'];
        foreach ($posted_column as $col_index => $column) {

            if (!empty($datatable_number_range)) {
                foreach ($datatable_number_range as $number_range_data) {
                    if ($number_range_data['column'] == $column['name']) {
                        $do_include_column_in_search = FALSE;
                        $column_number_ranges[] = array(
                            'column' => $number_range_data['column'],
                            'min_value' => $number_range_data['min'],
                            'max_value' => $number_range_data['max'],
                        );
                    }
                }
            }

            if ($column['search']['value'] != '') {
                $do_include_column_in_search = TRUE;
                if (!empty($datatable_date_range)) {
                    foreach ($datatable_date_range as $date_range_data) {
                        if ($date_range_data['column'] == $column['name']) {
                            $do_include_column_in_search = FALSE;
                            $explode_dates = explode($date_range_data['range_deliminator'], $column['search']['value']);
                            if (isset($explode_dates[0]) && isset($explode_dates[1])) {
                                $column_date_ranges[] = array(
                                    'column' => $date_range_data['column'],
                                    'start_date' => date($date_range_data['filter_format'], strtotime(trim($explode_dates[0]))),
                                    'end_date' => date($date_range_data['filter_format'], strtotime(trim($explode_dates[1]))),
                                );
                            }
                        }
                    }
                }

                if (isset($post_data['extra_column_search']) && !empty($post_data['extra_column_search'])) {
                    foreach ($post_data['extra_column_search'] as $ext_col) {
                        if (isset($ext_col['column']) && !empty($ext_col['column'])) {
                            if ($ext_col['column'] == $column['name']) {
                                $do_include_column_in_search = FALSE;
                                foreach ($ext_col['search_columns'] as $col_srch) {
                                    $column_or_search[$col_srch] = $column['search']['value'];
                                }
                            }
                        }
                    }
                }

                if ($do_include_column_in_search) {
                    $column_search[$column['name']] = str_replace('\\', '', $column['search']['value']);
                }
            }

            if ($column['searchable'] == 'true') {
                if (strpos($column['name'], '|') > -1) {
                    $cols = explode('|', $column['name']);
                    foreach ($cols as $col) {
                        $filter_array['searchable_columns'][] = $col;
                    }
                } else {
                    $filter_array['searchable_columns'][] = $column['name'];
                }
            }

            if ($column['name'] != '') {
                if (strpos($column['name'], '|') > -1) {
                    $cols = explode('|', $column['name']);
                    $cols_str = implode(",' ',", $cols);
                    $columns[] = 'CONCAT(' . $cols_str . ')' . " as '" . str_replace('.', "_", $column['name']) . "'";
                } else {
                    $columns[] = $column['name'] . " as '" . str_replace('.', "_", $column['name']) . "'";
                }
            }
        }

        if (isset($post_data['extra_column_search']) && !empty($post_data['extra_column_search'])) {
            foreach ($post_data['extra_column_search'] as $ext_col) {
                foreach ($ext_col['search_columns'] as $col_srch) {
                    $filter_array['searchable_columns'][] = $col_srch;
                }
            }
        }

        if (isset($filter_array['extra_fields_select']) && !empty($filter_array['extra_fields_select'])) {
            foreach ($filter_array['extra_fields_select'] as $column) {
                $columns[] = $column . " as '" . str_replace('.', "_", $column) . "'";
            }
        }

        $filter_array['select'] = implode(',', $columns);

        if (isset($post_data['order'])) {
            $orders = $post_data['order'];
            foreach ($orders as $col_index => $order) {
                $filter_array['order'][$posted_column[$order['column']]['name']] = $order['dir'];
            }
        }

        $filter_array['column_search'] = $column_search;
        $filter_array['column_number_ranges'] = $column_number_ranges;
        $filter_array['column_date_ranges'] = $column_date_ranges;
        $filter_array['column_or_search'] = $column_or_search;

        return $filter_array;
    }

    function master_update($table, $data, $condition, $string = FALSE) {
        ini_set('max_execution_time', 18000);

        if ($string) {
            $result = $this->db->query('UPDATE `' . $table . '` SET ' . $data . ' WHERE ' . $condition);
            if ($result > 0)
                return $result;
        }
        else {
            $this->db->where($condition);
            $this->db->update($table, $data);
            if ($this->db->affected_rows() > 0)
                return TRUE;
        }

        return FALSE;
    }

    function master_update_batch($table, $data, $condition) {
        $this->db->where($condition);
        $this->db->update($table, $data);
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        }

        return FALSE;
    }

    function master_save($table, $fields, $is_batch = FALSE) {
        if ($is_batch) {
            $this->db->insert_batch($table, $fields);
            return true;
        } else {
            $this->db->insert($table, $fields);
            return $this->db->insert_id();
        }
    }

    function master_delete($table, $where, $with_sign = NULL) {

        if (!is_null($where)) {
            foreach ($where as $key => $wh) {
                if (!is_null($with_sign))
                    $this->db->where($wh);
                else
                    $this->db->where($key, $wh);
            }
        }

        $this->db->delete($table);
        return $this->db->affected_rows();
    }

    function master_select($array = NULL) {
        $list1 = $this->return_result($array);
        if (isset($list1) && sizeof($list1) > 0)
            return $list1->result_array();
        else
            return 0;
    }

    function master_single_select($array = NULL) {

        $list1 = $this->return_result($array);
        if (isset($list1) && sizeof($list1) > 0)
            return $list1->row_array();
        else
            return 0;
    }

    function master_select_result($array = NULL) {

        $list1 = $this->return_result($array);
        if (isset($list1) && sizeof($list1) > 0)
            return $list1->result();
        else
            return 0;
    }

    function master_count($table, $where = NULL, $with_sign = NULL, $where_str = NULL) {
        if (!is_null($where)) {
            foreach ($where as $key => $wh) {
                if (!is_null($with_sign))
                    $this->db->where($wh);
                else
                    $this->db->where($key, $wh);
            }
        }

        $list = $this->db->get($table);
        return $list->num_rows();
    }

    function master_count_array($array) {

        $list1 = $this->return_result($array);
        if (isset($list1) && sizeof($list1) > 0)
            return $list1->num_rows();
        else
            return 0;
    }

    function return_result($array = NULL) {
        $list = array();
        if (array_key_exists("where", $array)) {
            $condition = $array['where'];
            $this->db->group_start();
            foreach ($condition as $key => $wh) {
                $this->db->where($key, $wh);
            }
            $this->db->group_end();
        }

        if (array_key_exists("or_where", $array)) {
            $condition = $array['or_where'];
            $this->db->group_start();
            foreach ($condition as $key => $wh) {
                $this->db->or_where($key, $wh);
            }
            $this->db->group_end();
        }

        if (array_key_exists("where_with_sign", $array)) {
            $condition = $array['where_with_sign'];
            $this->db->group_start();
            foreach ($condition as $key => $wh) {
                $this->db->where($wh);
            }
            $this->db->group_end();
        }

        if (array_key_exists("or_where_with_sign", $array)) {
            $condition = $array['or_where_with_sign'];
            $this->db->group_start();
            foreach ($condition as $key => $wh) {
                $this->db->or_where($wh);
            }
            $this->db->group_end();
        }

        if (array_key_exists("like", $array)) {
            $condition = $array['like'];
            $this->db->group_start();
            foreach ($condition as $key => $wh) {
                $this->db->like($key, $wh);
            }
            $this->db->group_end();
        }

        if (array_key_exists("like_with_sign", $array)) {
            $condition = $array['like_with_sign'];
            $this->db->group_start();
            foreach ($condition as $key => $wh) {
                $this->db->where($wh);
            }
            $this->db->group_end();
        }

        if (array_key_exists("or_like", $array)) {
            $condition = $array['or_like'];
            $this->db->group_start();
            foreach ($condition as $key => $wh) {
                $this->db->or_like($key, $wh);
            }
            $this->db->group_end();
        }

        if (array_key_exists("or_like_with_sign", $array)) {
            $condition = $array['or_like_with_sign'];
            $this->db->group_start();
            foreach ($condition as $key => $wh) {
                $this->db->or_like($wh);
            }
            $this->db->group_end();
        }

        if (array_key_exists('fields', $array))
            $this->db->select($array['fields'], FALSE);

        if (array_key_exists('order_by', $array)) {
            $sort_arr = $array['order_by'];
            foreach ($sort_arr as $key1 => $by) {
                $this->db->order_by($key1, $by);
            }
        }

        if (array_key_exists('group_by', $array)) {
            $group_arr = $array['group_by'];
            foreach ($group_arr as $by) {
                $this->db->group_by($by);
            }
        }

        if (array_key_exists('having', $array)) {
            $having_arr = $array['having'];
            foreach ($having_arr as $key => $val) {
                $this->db->having($key, $by);
            }
        }

        if (array_key_exists('having_with_sign', $array)) {
            $having_sign_arr = $array['having_with_sign'];
            foreach ($having_sign_arr as $val) {
                $this->db->having($val);
            }
        }

        if (array_key_exists('limit', $array)) {
            if (!empty($array['start']))
                $this->db->limit($array['limit'], $array['start']);
            else
                $this->db->limit($array['limit']);
        }

        if (array_key_exists('search', $array) && !empty($array['search'])) {
            $wh_ere = '(' . $array['search'] . ')';
            $this->db->where($wh_ere);
        }

        if (array_key_exists('join', $array)) {
            foreach ($array['join'] as $key => $val) {
                if (isset($val['table']) && isset($val['condition']))
                    $this->db->join($val['table'], $val['condition'], (isset($val['join']) ? $val['join'] : 'left'));
            }
        }
        if (array_key_exists("table", $array))
            $list = $this->db->get($array['table']);

        return $list;
    }

    function upload_image($image_name, $image_path, $file_extensions = NULL) {
        $CI = & get_instance();
        $extension = substr(strrchr($_FILES[$image_name]['name'], '.'), 1);
        $randname = time() . '.' . $extension;

        if (in_array($extension, array('jpeg', 'jpg', 'png')))
            $randname = time() . '_image.' . $extension;
        elseif (in_array($extension, array('mp4', 'webm', 'ogg', 'ogv', 'wmv', 'vob', 'swf', 'mov', 'm4v', 'flv')))
            $randname = time() . '_video.' . $extension;

        if (is_null($file_extensions))
            $file_extensions = 'gif|jpg|png|jpeg|pdf';

        $config = array('upload_path' => $image_path,
            'allowed_types' => $file_extensions,
            'max_size' => "5120KB",
            'file_name' => $randname
        );
        #Load the upload library
        $CI->load->library('upload');
        $CI->upload->initialize($config);
        if ($CI->upload->do_upload($image_name)) {
            $img_data = $CI->upload->data();
            $imgname = $img_data['file_name'];
        } //if
        else {
            $imgname = '';
        }
        return $imgname;
    }

    function upload_file($file_name, $file_path, $file_extensions = NULL, $new_file_name = NULL) {
        $CI = & get_instance();
        $extension = substr(strrchr($_FILES[$file_name]['name'], '.'), 1);
        if (is_null($new_file_name))
            $new_file_name = time() . '.' . $extension;

        if (is_null($file_extensions))
            $file_extensions = 'xlsx';

        $config = array('upload_path' => $file_path,
            'allowed_types' => $file_extensions,
            'max_size' => "5120KB",
            'file_name' => $new_file_name
        );
        #Load the upload library
        $CI->load->library('upload');
        $CI->upload->initialize($config);
        if ($CI->upload->do_upload($file_name)) {
            $img_data = $CI->upload->data();
            $imgname = $img_data['file_name'];
        } //if
        else {
            $imgname = '';
        }
        return $imgname;
    }

    function remove_image($id, $field, $table_name, $directory_path, $id_field = 'id') {
        $CI = & get_instance();
        $CI->db->where($id_field, $id);
        $CI->db->select($field);
        $CI->db->from($table_name);
        $query = $CI->db->get();
        $result = $query->result();
        $logo = $result[0]->$field;
        if (!empty($logo)) {
            $CI->db->where($id_field, $id);
            $CI->db->update($table_name, array($field => NULL));
            $image_path = dirname($_SERVER["SCRIPT_FILENAME"]) . '/' . $directory_path . '/';
            $image = $image_path . $logo;
            if (file_exists($image)) {
                unlink($image);
            }
        }
    }

    function created_directory($path) {
        if (!is_dir($path)) { //create the folder if it's not already exists
            mkdir($path, 0777, TRUE);
        }
        return true;
    }

    public function crop_product_image($source, $destination, $width, $height) {
        $width = 0;

        //$destination = $source;
        $type = strtolower(pathinfo($source, PATHINFO_EXTENSION));
        $allowed_type = array('png', 'jpeg', 'gif', 'jpg');
        $return = 0;
        if (in_array(strtolower($type), $allowed_type)) {
            list($w, $h) = getimagesize($source);

            $ratio = $h / $height;
            $width = $w / $ratio;

            $sourceRatio = $w / $h;
            $targetRatio = $height / $width;

            if ($sourceRatio < $targetRatio) {
                $scale = $h / $height;
            } else {
                $scale = $w / $width;
            }

            $widthPadding = $heightPadding = 0;

            $handle = finfo_open(FILEINFO_MIME);
            $mime_type = finfo_file($handle, $source);
            $mime_type = mime_content_type($source);
            switch (strtolower($mime_type)) {
                case 'image/gif':
                    $img_r = imagecreatefromgif($source);
                    $function = 'imagejgif';
                    break;
                case 'image/png':
                    $img_r = imagecreatefrompng($source);
                    $function = 'imagepng';
                    break;
                case 'image/jpg':
                    $img_r = imagecreatefromjpeg($source);
                    $function = 'imagejpeg';
                    break;
                case 'image/jpeg':
                    $img_r = imagecreatefromjpeg($source);
                    $function = 'imagejpeg';
                    break;
            }

            $dst_r = ImageCreateTrueColor($width, $height);
            //set white background
            $white = imagecolorallocate($dst_r, 255, 255, 255);
            imagefill($dst_r, 0, 0, $white);
            imagecopyresampled($dst_r, $img_r, 0, 0, $widthPadding, $heightPadding, $width, $height, $w, $h);
            if ($function($dst_r, $destination)) {
                $return = 1;
            }
        }
    }

    function random_generate_code($length = NULL) {

//        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    function timezone_list($timezone = "") {
        $zones_array = array();
        $timestamp = time();
        foreach (timezone_identifiers_list() as $key => $zone) {
            date_default_timezone_set($zone);
            if ($zone == $timezone) {
                $zones_array[]['zone'] = $zone;
                $zones_array[]['diff_from_GMT'] = date('P', $timestamp);
            } else {
                $zones_array[$key]['zone'] = $zone;
                $diff_timezone = str_replace('+0', '+', date('P', $timestamp));
                $diff_timezone = str_replace(':00', '  ', $diff_timezone);
                $zones_array[$key]['diff_from_GMT'] = 'UTC/GMT ' . $diff_timezone;
            }
        }
        return $zones_array;
    }

}
