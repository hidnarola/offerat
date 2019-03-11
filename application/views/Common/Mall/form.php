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
                        <fieldset class="content-group">
                            <legend class="text-bold">Mall Info.</legend>
                            <div class="col-xs-12">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Mall Name <span class="text-danger">*</span></label>
                                        <div>
                                            <input type="hidden" class="form-control" name="mall_id" id="mall_id"  value="<?php echo (isset($mall_details['id_mall'])) ? $mall_details['id_mall'] : ''; ?>">
                                            <input type="text" class="form-control" name="mall_name" id="mall_name"  placeholder="Mall Name" required="required" value="<?php echo (isset($mall_details['mall_name'])) ? $mall_details['mall_name'] : set_value('mall_name'); ?>">
                                        </div>
                                    </div>        
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Website URL </label>
                                        <div>
                                            <input type="text" class="form-control" name="website" id="website"  placeholder="Website URL" value="<?php echo (isset($mall_details['website'])) ? $mall_details['website'] : set_value('website'); ?>">
                                        </div>
                                    </div>        
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Facebook Page <span class="text-danger">*</span></label>
                                        <div>
                                            <input type="text" class="form-control" name="facebook_page" id="facebook_page" required="required" placeholder="Facebook Page URL" value="<?php echo (isset($mall_details['facebook_page'])) ? $mall_details['facebook_page'] : set_value('facebook_page'); ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Logo </label>
                                        <div>
                                            <input type="file" class="form-control file-input" name="mall_logo" id="mall_logo">
                                            <input type="hidden" name="is_valid" id="is_valid" value="1">
                                            <div id="mall_logo_errors_wrapper" class="alert alert-danger alert-bordered display-none">
                                                <span id="mall_logo_errors"></span>
                                            </div>
                                            <label id="mall_logo-error" class="validation-error-label" for="mall_logo"></label>
                                        </div>                                            
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label> </label>
                                        <div>
                                            <?php
                                            if (isset($mall_details['mall_logo']) && !empty($mall_details['mall_logo'])) {
                                                $extension = explode('.', $mall_details['mall_logo']);
                                                if (isset($extension) && isset($extension[1]) && in_array($extension[1], $this->image_extensions_arr)) {
                                                    ?>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <div>                                                                    
                                                                <a href="<?php echo store_img_path . $mall_details['mall_logo']; ?>" class="btn btn-info btn-lg" target="_blank"><i class="icon-image5"></i> View Image</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <?php if ($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE) { ?>
                            <fieldset class="content-group">
                                <legend class="text-bold">Personal Info.</legend>
                                <div class="col-xs-12">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Contact Person First Name</label>
                                            <div>
                                                <input type="text" class="form-control" name="first_name" id="first_name"  placeholder="First Name" value="<?php echo (isset($mall_details['first_name'])) ? $mall_details['first_name'] : set_value('first_name'); ?>">
                                            </div>
                                        </div>        
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Contact Person Last Name</label>
                                            <div>
                                                <input type="text" class="form-control" name="last_name" id="last_name"  placeholder="Last Name"  value="<?php echo (isset($mall_details['last_name'])) ? $mall_details['last_name'] : set_value('last_name'); ?>">
                                            </div>
                                        </div>        
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Email Address</label>
                                            <div>
                                                <input type="email" class="form-control" name="email_id" id="email_id"  placeholder="Email Address"  value="<?php echo (isset($mall_details['email_id'])) ? $mall_details['email_id'] : set_value('email_id'); ?>">
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Mobile Number</label>
                                            <div>
                                                <input type="text" class="form-control" name="mobile" id="mobile"  placeholder="Mobile Number" value="<?php echo (isset($mall_details['mobile'])) ? $mall_details['mobile'] : set_value('mobile'); ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        <?php } ?>       
                        <?php if ($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE) { ?>
                            <fieldset class="content-group">
                                <legend class="text-bold">Location</legend>
                                <div class="col-xs-12">
                                    <div class="col-md-3 display-none">
                                        <div class="form-group">
                                            <div>
                                                <?php if (isset($country_list) && sizeof($country_list) > 0) { ?>
                                                    <select class="form-control select" name="id_country" id="id_country" required="required">
                                                        <option value="">Select Country</option>
                                                        <?php foreach ($country_list as $list) { ?>
                                                            <option value="<?php echo $list['id_country']; ?>" <?php echo ((isset($mall_details['id_country'])) && $mall_details['id_country'] == $list['id_country']) ? 'selected=selected' : ''; ?>><?php echo $list['country_name']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>                                     
                                </div>
                                <div id="mall_selection_wrapper" class="clear-float row_add_div">                                     
                                    <div id="mall_selection_block_0" class="clear-float">
                                        <div class="col-xs-12 business_category_div">                                                                            
                                            <div class="col-md-2">
                                                <div class="form-group">  
                                                    <label>Latitude <span class="text-danger">*</span></label>
                                                    <div>
                                                        <input type="text" class="form-control" name="latitude" id="latitude" required="required"  placeholder="Latitude" value="<?php echo (isset($mall_details['latitude'])) ? $mall_details['latitude'] : set_value('latitude'); ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group"> 
                                                    <label>Longitude <span class="text-danger">*</span></label>
                                                    <div>
                                                        <input type="text" class="form-control" name="longitude" id="longitude" required="required" placeholder="Longitude" value="<?php echo (isset($mall_details['longitude'])) ? $mall_details['longitude'] : set_value('longitude'); ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <?php if (!empty($mall_details)) { ?>
                                <fieldset>
                                    <legend class="text-bold">Stores Info</legend>
                                    <div class="col-xs-12 clear-float">           
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <a class="btn btn-primary" href="<?= site_url('country-admin/malls/store/edit/' . base64_encode($mall_details['id_mall'])) ?>">
                                                    <i class="fa fa-pencil"></i>&nbsp;&nbsp;Edit Store Floor No.
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <a href="<?= site_url('country-admin/malls/store/location/edit/' . base64_encode($mall_details['id_mall'])) ?>" class="btn btn-primary">
                                                    <i class="fa fa-pencil"></i>&nbsp;&nbsp;Edit Store Location
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                                <?php
                            }
                        }
                        ?>
                        <?php if ($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE) { ?>
                            <fieldset class="content-group">
                                <legend class="text-bold">Sales Trend</legend>  
                                <div class="col-xs-12">                                                                
                                    <div class="col-md-4"></div>               
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <button id="sales_trend_btn" type="button" class="pull-left margin-left-5 btn-primary labeled"><b><i class="icon-plus22"></i></b>Add More Sales Trend</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xs-12 business_category_div">

                                </div>
                                <div id="sales_trend_wrapper" class="clear-float row_add_div">
                                    <?php
                                    $salesTrendCloneNumber = 1;
                                    if (isset($sales_trends) && sizeof($sales_trends) > 0) {
                                        foreach ($sales_trends as $key => $trend) {
                                            $from_date = date_create($trend['from_date']);
                                            $from_date_text = date_format($from_date, "d-F");
                                            $to_date = date_create($trend['to_date']);
                                            $to_date_text = date_format($to_date, "d-F");
                                            ?>
                                            <div id="sales_trend_block_<?php echo $key; ?>" data-clone-number="<?php echo $key; ?>" class="clear-float">
                                                <div class="col-xs-12">
                                                    <div class="col-md-2">
                                                        <div class="form-group">                                        
                                                            <div>
                                                                <input type="text" class="form-control sales_trend_from_date" placeholder="From Date" data-clone-number="<?php echo $key; ?>" name="exist_from_date_<?php echo $trend['id_sales_trend']; ?>" id="from_date_<?php echo $key; ?>" value="<?php echo $from_date_text; ?>">
                                                            </div>
                                                        </div>        
                                                    </div>                                                
                                                    <div class="col-md-2">
                                                        <div class="form-group">                                        
                                                            <div>
                                                                <input type="text" class="form-control sales_trend_to_date" placeholder="To Date" data-clone-number="<?php echo $key; ?>" name="exist_to_date_<?php echo $trend['id_sales_trend']; ?>" id="to_date_<?php echo $key; ?>" value="<?php echo $to_date_text; ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">                                        
                                                            <div>
                                                                <button type="button" class="btn btn-danger btn-icon sales_trend_remove_btn" id="sales_trend_remove_btn_<?php echo $key; ?>" character="" data-clone-number="<?php echo $key; ?>"><i class="icon-cross3"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                            $salesTrendCloneNumber++;
                                        }
                                    } else {
                                        ?>
                                        <div id="sales_trend_block_0" data-clone-number="0" class="clear-float">
                                            <div class="col-xs-12">
                                                <div class="col-md-2">
                                                    <div class="form-group">                                        
                                                        <div>
                                                            <input type="text" class="form-control sales_trend_from_date" placeholder="From Date" data-clone-number="0" name="from_date_0" id="from_date_0" value="">
                                                        </div>
                                                    </div>        
                                                </div>                                                
                                                <div class="col-md-2">
                                                    <div class="form-group">                                        
                                                        <div>
                                                            <input type="text" class="form-control sales_trend_to_date" placeholder="To Date"  data-clone-number="0" name="to_date_0" id="to_date_0" value="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">                                        
                                                        <div>
                                                            <button type="button" class="btn btn-danger btn-icon sales_trend_remove_btn" id="sales_trend_remove_btn_0" character="" data-clone-number="0"><i class="icon-cross3"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>                                    
                            </fieldset>
                        <?php } ?>
                        <div class="text-right">
                            <input type="hidden" name="sales_trend_count" id="sales_trend_count" value="<?php echo @$salesTrendCloneNumber; ?>">
                            <a href="<?php echo $back_url ?>" class="btn bg-grey-300 btn-labeled"><b><i class="icon-arrow-left13"></i></b>Back</a>
                            <button type="submit" class="btn bg-teal btn-labeled btn-labeled-right"><b><i class="icon-arrow-right14"></i></b>Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript" src="assets/user/js/mall.js"></script>
<script>
    var salesTrendNumber = 1;
<?php if (@$salesTrendCloneNumber > 0) { ?>
        salesTrendNumber = '<?php echo @$salesTrendCloneNumber; ?>';
<?php } ?>
    $(document).on('click', '.sales_trend_from_date', function (e) {
        var cloneNumber = $(this).data('cloneNumber');
        $(document).find('#from_date_' + cloneNumber).AnyTime_noPicker().AnyTime_picker({format: "%d-%M"}).focus();
        e.preventDefault();
    });
    $(document).on('click', '.sales_trend_to_date', function (e) {
        var cloneNumber = $(this).data('cloneNumber');
        $(document).find('#to_date_' + cloneNumber).AnyTime_noPicker().AnyTime_picker({format: "%d-%M"}).focus();
        e.preventDefault();
    });
<?php
if (isset($mall_details)) {
    ?>
        $(document).find('#id_country').attr('disabled', 'disabled');
<?php } ?>

    function generateSalesTrendBlock(cloneNumber) {
        var html = '';
        html += '<div id="sales_trend_wrapper" class="clear-float row_add_div">';
        html += '<div id="sales_trend_block_' + cloneNumber + '" data-clone-number="' + cloneNumber + '" class="clear-float">';
        html += '<div class="col-xs-12">';
        html += '<div class="col-md-2">';
        html += '<div class="form-group">';
        html += '<div>';
        html += '<input type="text" class="form-control sales_trend_from_date" data-clone-number="' + cloneNumber + '" placeholder="From Date" name="from_date_' + cloneNumber + '" id="from_date_' + cloneNumber + '" value="">';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '<div class="col-md-2">';
        html += '<div class="form-group">';
        html += '<div>';
        html += '<input type="text" class="form-control sales_trend_to_date" data-clone-number="' + cloneNumber + '" placeholder="To Date" name="to_date_' + cloneNumber + '" id="to_date_' + cloneNumber + '" value="">';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '<div class="col-md-2">';
        html += '<div class="form-group">';
        html += '<div>';
        html += '<button type="button" class="btn btn-danger btn-icon sales_trend_remove_btn" id="sales_trend_remove_btn_' + cloneNumber + '" character="" data-clone-number="' + cloneNumber + '"><i class="icon-cross3"></i></button>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        return html;
    }
</script>