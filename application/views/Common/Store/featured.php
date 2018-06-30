<div class="row">
    <div class="col-md-12">
        <form method="POST" action="" enctype="multipart/form-data" class="form-validate-jquery" name="manage_record">
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-flat">
                        <div class="panel-heading">                            
                            <div class="heading-elements">
                                <ul class="icons-list">
                                    <li><a data-action="collapse"></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="panel-body">                                                            
                            <?php if (isset($store_categories) && sizeof($store_categories) > 0) { ?>
                                <fieldset class="content-group">                                
                                    <div id="category_selection_wrapper" class="clear-float row_add_div">
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
                                                                        <?php }
                                                                    } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 <?php echo ($cat['id_sub_category'] > 0) ? '' : 'display-none'; ?>">
                                                        <div class="form-group">                                        
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
                                                            <select name="position_<?php echo $key; ?>" id="position_<?php echo $key; ?>" class="form-control">
                                                                <option value="">Position</option>
                                                                <?php for ($i = 1; $i <= 5; $i++) { ?>
                                                                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
        <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <div class="input-group">
                                                                <span class="input-group-addon"><i class="icon-calendar22"></i></span>
                                                                <input type="text" name="from_to_date_<?php echo $key; ?>" id="from_to_date_<?php echo $key; ?>" class="form-control daterange-basic" value=""> 
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </fieldset>
                                <div class="text-right">
                                    <a href="<?php echo $back_url; ?>" class="btn bg-grey-300 btn-labeled"><b><i class="icon-arrow-left13"></i></b>Back</a>
                                    <button type="submit" class="btn bg-teal btn-labeled btn-labeled-right"><b><i class="icon-arrow-right14"></i></b>Save</button>
                                </div>
<?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>