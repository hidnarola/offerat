<form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
    <fieldset class="content-group">                                                
        <legend class="text-bold">Store Info.</legend>
        <div class="form_grp_inline">
            <div class="form-group">                                        
                <label class="control-label col-lg-2">Store Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="store_name" id="store_name"  placeholder="Store Name" required="required" value="<?php echo set_value('store_name'); ?>">
            </div>            
            <div class="form-group">
                <label class="control-label col-lg-2">Website URL</label>
                <input type="text" class="form-control" name="website" id="website"  placeholder="Example www.store.com" value="<?php echo set_value('website'); ?>">
            </div>
        </div>
        <div class="form_grp_inline">
            <div class="form-group">
                <label class="control-label col-lg-2">Facebook Page <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="facebook_page" id="facebook_page" required="required" placeholder="Example www.facebook.com/store/" value="<?php echo set_value('facebook_page'); ?>">
            </div>
            <div class="form-group upload-store-logo-div">
                <label class="control-label col-lg-2">Logo</label>
                <input type="file" class="form-control file-input" name="store_logo" id="store_logo">
                <input type="hidden" name="is_valid" id="is_valid" value="1">
                <div id="store_logo_errors_wrapper" class="alert alert-danger alert-bordered display-none">
                    <span id="store_logo_errors"></span>
                </div>
                <label id="store_logo-error" class="validation-error-label" for="store_logo"></label>
            </div>
        </div>

        <legend class="text-bold">Personal Info.</legend>
        <div class="form_grp_inline">
            <div class="form-group">
                <label class="control-label col-lg-2">Contact Person <span class="text-danger">*</span></label>
                <div class="width_50 first">
                    <input type="text" class="form-control" name="first_name" id="first_name"  placeholder="First Name"  required="required" value="<?php echo set_value('first_name'); ?>">
                </div>
                <div class="width_50 last">
                    <input type="text" class="form-control" name="last_name" id="last_name"  placeholder="Last Name"  required="required" value="<?php echo set_value('last_name'); ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-lg-2">Email Address <span class="text-danger">*</span></label>
                <input type="email" class="form-control" name="email_id" id="email_id"  placeholder="Email Address"  required="required" value="<?php echo set_value('email_id'); ?>">
            </div>
        </div>
        <div class="form_grp_inline">
            <div class="form-group">
                <label class="control-label col-lg-2">Contact Number  <span class="text-danger">*</span></label>                
                <input type="text" class="form-control" name="telephone" id="telephone" required  placeholder="Contact Number" value="<?php echo set_value('telephone'); ?>">                
            </div>            
        </div>

        <!--        <legend class="text-bold">Business</legend>
        
                <div class="add_desc">
                    <button id="category_selection_btn" type="button" class="pull-right margin-left-5 btn-primary labeled"><b><i class="icon-plus22"></i></b>Add More Category</button>
                </div>
                <div class="row">
                    <div id="category_selection_wrapper" class="clear-float row_add_div">  
                        <div id="category_selection_block_0" data-clone-number="0" class="clear-float">
                            <div class="col-md-12 business_category_div">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <div>
                                            <select id="category_0" name="category_0" class="select category_selection_dropdown form-control" data-clone-number="0" required="required">
                                                <option value="">Select Category</option>
        <?php foreach ($category_list as $list) { ?>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <option value="<?php echo $list['id_category']; ?>"><?php echo $list['category_name']; ?></option>
        <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5 sub_cat_section_0">
                                    <select id="sub_category_0" name="sub_category_0" class="select sub_category_selection_dropdown form-control" data-clone-number="0">
                                        <option value="">Select Sub Category</option>
                                    </select>
                                </div>
                                <div class="col-md-1 product-selection-remove-prod-btn">
                                    <div class="form-group">
                                        <div>
                                            <button type="button" class="btn btn-danger btn-icon category_selection_remove_btn" data-clone-number="0"><i class="icon-cross3"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>-->

        <legend class="text-bold">Location</legend>
        <div class="col-lg-6 col_mobile_pad">
            <div class="form-group">
                <label class="control-label col-lg-2">Country <span class="text-danger">*</span></label>

                <?php if (isset($country_list) && sizeof($country_list) > 0) { ?>
                    <select class="form-control select" name="id_country" id="id_country" required="required">                                            
                        <option value="">Select Country</option>
                        <?php foreach ($country_list as $list) { ?>
                            <option value="<?php echo $list['id_country']; ?>"><?php echo $list['country_name']; ?></option>
                        <?php } ?>
                    </select>
                <?php } ?>
            </div> 
        </div>
        <div class="col-lg-6 col_mobile_pad">            
        </div>

        <div id="mall_selection_wrapper" class="clear-float row_add_div">  

        </div>
        <div class="form-group checkbox_reg">            
            <input type="checkbox" class="styled-checkbox-1" id="terms_condition" name="terms_condition" required="required"/>                
            <span class="text-size-mini">  Yes, I agree with <a href="<?= base_url('tc') ?>" target="_blank"><span>Terms And Conditions</span></a></span>            
            <label id="terms_condition-error" class="validation-error-label" for="terms_condition"></label>
        </div>
        <div class="col-md-12 mb-20 mt-10">
            <div class="col-md-4">&nbsp;</div>
            <div class="col-md-8 mb-10">
                <div class="g-recaptcha" data-sitekey="<?= GOOGLE_CAPTCHA_KEY ?>"></div>
            </div>
        </div>
        <div class="form-group btn_center col-md-12 submit-button-margin" >            
            <input type="hidden" name="category_count" id="category_count" value="1">
            <input type="hidden" name="location_count" id="location_count" value="1">
            <button type="submit" class="btn btn-primary btn-block submit_btn">Submit</button>
        </div>
    </fieldset>
</form>
<!--<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_API_KEY ?>&callback=initMap" async defer></script>-->

<script type="text/javascript" src="assets/front/js/store.js"></script>
<script>
    $(document).ready(function () {
        setInterval(function () {
            $(".file-caption-name").text('Load square logo');
        }, 10);
    });

    function generatecategorySelectionBlock(cloneNumber) {
        var html = '';
        html += '<div id="category_selection_block_' + cloneNumber + '" data-clone-number="' + cloneNumber + '" class="clear-float">';
        html += '<div class="col-md-12 business_category_div">';
        html += '<div class="col-md-5">';
        html += '<div class="form-group">';
        html += '<div>';
        html += '<select id="category_' + cloneNumber + '" name="category_' + cloneNumber + '" class="select category_selection_dropdown form-control" data-clone-number="' + cloneNumber + '" required="required">';
        html += '<option value="">Select Category</option>';
<?php foreach ($category_list as $list) { ?>
            html += '<option value="' + '<?php echo $list['id_category']; ?>' + '">' + '<?php echo $list['category_name']; ?>' + '</option>';
<?php } ?>
        html += '</select>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '<div class="col-md-5 sub_cat_section_' + cloneNumber + '">';
        html += '<select id="sub_category_' + cloneNumber + '" name="sub_category_' + cloneNumber + '" class="select sub_category_selection_dropdown form-control" data-clone-number="' + cloneNumber + '">';
        html += '<option value="">Select Subcategory</option>';
        html += '</select>';
        html += '</div>';
        html += '<div class="col-md-1 product-selection-remove-prod-btn">';
        html += '<div class="form-group">';
        html += '<div>';
        html += '<button type="button" class="btn btn-danger btn-icon category_selection_remove_btn" data-clone-number="' + cloneNumber + '"><i class="icon-cross3"></i></button>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        return html;
    }
</script>
