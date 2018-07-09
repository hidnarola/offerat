<div class="col-md-12">
    <form method="POST" action="<?php echo $post_url ?>" enctype="multipart/form-data" class="form-validate-jquery" name="manage_record">
        <div class="row">
            <div class="col-xs-12">
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <h5 class="panel-title"><a href="<?php echo $back_url ?>" title="Back"><i class="icon-arrow-left13 btn-icon"></i></a><?php echo $page_action ?></h5>
                        <div class="heading-elements">
                            <ul class="icons-list">
                                <li><a data-action="collapse"></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="col-xs-12">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Parent Category <span class="text-danger">*</span></label>
                                    <div>
                                        <span class="label border-left-primary label-striped"><?php echo $parent_category['category_name']; ?></span>                                                                                   
                                    </div>
                                </div>        
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Name <span class="text-danger">*</span></label>
                                    <div>
                                        <input type="hidden" name="id" id="id" value="<?php echo isset($sub_category['id_sub_category']) ? $sub_category['id_sub_category'] : set_value('id_sub_category') ?>">
                                        <input type="text" class="form-control" placeholder="Name" name="sub_category_name" id="sub_category_name" required="required" value="<?php echo isset($sub_category['sub_category_name']) ? $sub_category['sub_category_name'] : set_value('sub_category_name') ?>">
                                    </div>
                                </div>        
                            </div> 

                        </div>

                        <div class="col-xs-12">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Sort Order <span class="text-danger">*</span></label>
                                    <div>                                            
                                        <input type="number" min="0" class="form-control" placeholder="Sort Order" name="sort_order" id="sort_order" value="<?php echo isset($sub_category['sort_order']) ? $sub_category['sort_order'] : set_value('sort_order') ?>" required="required">
                                    </div>
                                </div>        
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="display-block">Status <span class="text-danger">*</span></label>                                        
                                    <select name="status" id="status" class="form-control select">
                                        <option value="0" <?php echo (isset($sub_category['status']) && $sub_category['status'] == '0' ) ? 'selected=selected' : ''; ?>>Active</option>
                                        <option value="1" <?php echo (isset($sub_category['status']) && $sub_category['status'] == '1' ) ? 'selected=selected' : ''; ?>>Inactive</option>
                                    </select>
                                </div>       
                            </div>                                                                
                        </div>
                        <div class="col-xs-12">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Image <span class="text-danger">*</span> <span class="text-info text-size-mini">(Recommended Width and Height is 100 x 100 pixels respectively)</span></label>
                                    <div>
                                        <input type="file" name="sub_category_logo" id="sub_category_logo" class="form-control file-inpucountryt  file-input">                                            
                                    </div>
                                </div>

                                <?php if (isset($sub_category['sub_category_logo']) && !empty($sub_category['sub_category_logo'])) { ?>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div>
                                                <img class="img-responsive" alt="Image Not Found" onerror="image_not_found(image_0)" id="image_0" src="<?php echo sub_category_img_path . $sub_category['sub_category_logo'] ?>" />
                                            </div>
                                        </div>
                                    </div>

                                    <!--                                        <div class="col-md-12 checkbox checkbox-switch">
                                                                                <label>
                                                                                    Delete Image?
                                                                                    <input type="checkbox" data-off-color="danger" data-on-text="Yes" data-off-text="No" class="switch" name="delete_sub_category_image" id="delete_sub_category_image" />
                                                                                </label>
                                                                            </div>-->
                                <?php } ?>
                            </div>                                                                
                        </div>

                        <div class="col-xs-12">                                
                            <div class="text-right">
                                <a href="<?php echo $back_url ?>" class="btn bg-grey-300 btn-labeled"><b><i class="icon-arrow-left13"></i></b>Back</a>
                                <button type="submit" class="btn bg-teal btn-labeled btn-labeled-right"><b><i class="icon-arrow-right14"></i></b>Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">
    $(function () {
        jqueryValidate();
    });
</script>
<?php if (isset($sub_category['sub_category_logo']) && !empty($sub_category['sub_category_logo'])) { ?>
    <script type="text/javascript" src="assets/user/js/plugins/forms/styling/switch.min.js"></script>
    <script>
        $(".switch").bootstrapSwitch();
    </script>
<?php } ?>