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
                                            <input type="hidden" name="id" id="id" value="<?php echo isset($country['id_country']) ? $country['id_country'] : set_value('id_country') ?>">
                                            <input type="text" class="form-control" placeholder="Name" name="country_name" id="country_name" required="required" value="<?php echo isset($country['country_name']) ? $country['country_name'] : set_value('country_name') ?>">
                                        </div>
                                    </div>        
                                </div> 
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="display-block">Status <span class="text-danger">*</span></label>
                                        <?php
                                        $status_options = array(
                                            '0' => 'Active',
                                            '1' => 'Inactive',
                                        );
                                        $status_selected = set_value('status');
                                        if (isset($country['status'])) {
                                            $status_selected = $country['status'];
                                        }
                                        echo form_dropdown('status', $status_options, $status_selected, 'class="form-control select"');
                                        ?>
                                    </div>       
                                </div>
                            </div>

                            <div class="col-xs-12">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Admin Email Id <span class="text-danger">*</span></label>
                                        <div>
                                            <input type="hidden" name="id_user" id="id_user" value="<?php echo isset($country['id_user']) ? $country['id_user'] : set_value('id') ?>">
                                            <input type="text" class="form-control" placeholder="Admin Email Id" name="email_id" id="email_id" value="<?php echo isset($country['email_id']) ? $country['email_id'] : set_value('email_id') ?>" required="required">
                                        </div>
                                    </div>        
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Admin Password</label>
                                        <div>
                                            <input type="password" class="form-control" placeholder="Admin Password" name="password" id="password" value="">
                                        </div>
                                    </div>        
                                </div>                             
                            </div>

                            <div class="col-xs-12">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Image <span class="text-danger">*</span> <span class="text-info text-size-mini">(Recommended Width and Height is 30 x 20 pixels respectively)</span></label>
                                        <div>
                                            <input type="file" name="country_flag" id="country_flag" class="form-control file-input">                                            
                                        </div>
                                    </div>

                                    <?php if (isset($country['country_flag']) && !empty($country['country_flag'])) { ?>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div>
                                                    <img class="img-responsive" onerror="image_not_found(image_0)" id="image_0" alt="Image Not Found" src="<?php echo country_img_path . $country['country_flag'] ?>" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12 checkbox checkbox-switch">
                                            <label>
                                                Delete Image?
                                                <input type="checkbox" data-off-color="danger" data-on-text="Yes" data-off-text="No" class="switch" name="delete_country_image" id="delete_country_image" />
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
<?php if (isset($country['country_flag']) && !empty($country['country_flag'])) { ?>
    <script type="text/javascript" src="assets/user/js/plugins/forms/styling/switch.min.js"></script>
    <script>
    $(".switch").bootstrapSwitch();
    </script>
<?php } ?>