<div class="row">
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
                                        <label>Name <span class="text-danger">*</span></label>
                                        <div>
                                            <input type="hidden" name="id" id="id" value="<?php echo isset($category['id_category']) ? $category['id_category'] : set_value('id_category') ?>">
                                            <input type="text" class="form-control" placeholder="Name" name="category_name" id="category_name" required="required" value="<?php echo isset($category['category_name']) ? $category['category_name'] : set_value('category_name') ?>">
                                        </div>
                                    </div>        
                                </div> 
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Sort Order <span class="text-danger">*</span></label>
                                        <div>                                            
                                            <input type="number" min="0" class="form-control" placeholder="Sort Order" name="sort_order" id="sort_order" value="<?php echo isset($category['sort_order']) ? $category['sort_order'] : set_value('sort_order') ?>" required="required">
                                        </div>
                                    </div>        
                                </div>
                            </div>

                            <div class="col-xs-12">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="display-block">Status <span class="text-danger">*</span></label>
                                        <?php
                                        $status_options = array(
                                            '0' => 'Active',
                                            '1' => 'Inactive',
                                        );
                                        $status_selected = set_value('status');
                                        if (isset($category['status'])) {
                                            $status_selected = $category['status'];
                                        }
                                        echo form_dropdown('status', $status_options, $status_selected, 'class="form-control select"');
                                        ?>
                                    </div>       
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Image <span class="text-danger">*</span> <span class="text-info text-size-mini">(Recommended Width and Height is 100 x 100 pixels respectively)</span></label>
                                        <div>
                                            <input type="file" name="category_logo" id="category_logo" class="form-control file-inpucountryt">                                            
                                        </div>
                                    </div>

                                    <?php if (isset($category['category_logo']) && !empty($category['category_logo'])) { ?>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div>
                                                    <img class="img-responsive" alt="Image Not Found" onerror="image_not_found(image_0)" id="image_0" src="<?php echo category_img_path . $category['category_logo'] ?>" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12 checkbox checkbox-switch">
                                            <label>
                                                Delete Image?
                                                <input type="checkbox" data-off-color="danger" data-on-text="Yes" data-off-text="No" class="switch" name="delete_category_image" id="delete_category_image" />
                                            </label>
                                        </div>
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
</div>
<script type="text/javascript">
    $(function () {
        jqueryValidate();
    });
</script>
<?php if (isset($category['category_logo']) && !empty($category['category_logo'])) { ?>
    <script type="text/javascript" src="assets/user/js/plugins/forms/styling/switch.min.js"></script>
    <script>
    $(".switch").bootstrapSwitch();
    </script>
<?php } ?>