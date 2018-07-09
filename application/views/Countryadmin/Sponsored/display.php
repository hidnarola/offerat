<?php
$remove_values = array();
$id_sponsored_log = 0;
if (isset($store_categories) && sizeof($store_categories) > 0) {
    ?>
    <fieldset class="content-group">                                                                                                    
        <div class="clear-float">
            <div class="col-xs-12 business_category_div">
                <div class="col-md-2">
                    <label>Category</label>
                </div>
                <div class="col-md-2">
                    <label>Sub-category</label>
                </div>
                <div class="col-md-2">
                    <label>Position</label>
                </div>
                <div class="col-md-2">
                    <label>From - To Date</label>
                </div>                
                <div class="col-md-4">
                    <a href="<?php echo 'country-admin/stores/sponsored/' . $store_id; ?>" target="_blank" class="btn btn-info btn-labeled"><b><i class="icon-star-half"></i></b>Edit Sponsored Details</a>
                    <a href="<?php echo 'country-admin/stores/save/' . $store_id; ?>" target="_blank" class="btn btn-primary btn-labeled"><b><i class="icon-store"></i></b>Edit Store</a>
                </div>                
            </div>
        </div>                                    
    </fieldset>
    <fieldset class="content-group">                                                                    
        <?php
        foreach ($store_categories as $key => $cat) {
            ?>
            <div class="clear-float">
                <div class="col-xs-12 business_category_div">
                    <div class="col-md-2">
                        <div class="form-group">
                            <div>
                                <select id="category_<?php echo $key; ?>" name="category_<?php echo $key; ?>" class="select form-control" required="required">                                                                    
                                    <?php foreach ($category_list as $list) { ?>
                                        <?php if ($list['id_category'] == $cat['id_category']) { ?>
                                            <option value="<?php echo $list['id_category']; ?>"><?php echo $list['category_name']; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group <?php echo ($cat['id_sub_category'] > 0) ? '' : 'display-none'; ?>">                                        
                            <div>
                                <?php
                                $select_sub_category = array(
                                    'table' => tbl_sub_category,
                                    'where' => array('status' => ACTIVE_STATUS, 'is_delete' => IS_NOT_DELETED_STATUS, 'id_sub_category' => $cat['id_sub_category'])
                                );

                                $sub_category_list = $this->Common_model->master_select($select_sub_category);
                                ?>
                                <select id="sub_category_<?php echo $key; ?>" name="sub_category_<?php echo $key; ?>" class="select form-control">
                                    <?php if (isset($sub_category_list) && sizeof($sub_category_list) > 0) { ?>                                                                        
                                        <?php foreach ($sub_category_list as $list) { ?>
                                            <?php if ($list['id_sub_category'] == $cat['id_sub_category']) { ?>
                                                <option value="<?php echo $list['id_sub_category']; ?>" ><?php echo $list['sub_category_name']; ?></option>
                                                <?php
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <select name="position_<?php echo $key; ?>" id="position_<?php echo $key; ?>" class="select form-control">
                                <option value="">Position</option>
                                <?php
                                for ($i = 1; $i <= 5; $i++) {
                                    ?>
                                    <option value="<?php echo $i; ?>" <?php echo ($cat['position'] == $i) ? 'selected=selected' : ''; ?>><?php echo $i; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="icon-calendar22"></i></span>
                                <?php
                                if (isset($cat['from_date']) && !empty($cat['from_date']) && isset($cat['to_date']) && !empty($cat['to_date'])) {
                                    $from_date = date_create($cat['from_date']);
                                    $from_date_text = date_format($from_date, "d-m-Y");
                                    $to_date = date_create($cat['to_date']);
                                    $to_date_text = date_format($to_date, "d-m-Y");
                                } else {
                                    $from_date_text = $to_date_text = '';
                                    $remove_values[] = $key;
                                }
                                ?>

                                <input type="text" name="from_to_date_<?php echo $key; ?>" id="from_to_date_<?php echo $key; ?>" class="form-control daterange-from-to" value="<?php echo (!empty($from_date_text)) ? $from_date_text . ' - ' . $to_date_text : ''; ?>">
                            </div>
                        </div>
                    </div>                    
                </div>
            </div>
            <?php
        }
        ?>
    </fieldset>    
<?php } else { ?>
    <fieldset class="content-group">                                                                                                    
        <div class="clear-float">
            <div class="col-xs-12 business_category_div">
                No results found.                                         
            </div>
        </div>                                    
    </fieldset>
<?php } ?>