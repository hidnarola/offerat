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
                            <legend class="text-bold">Store Info.</legend>
                            <div class="col-xs-12">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Store Name <span class="text-danger">*</span></label>
                                        <div>
                                            <input type="hidden" class="form-control" name="store_id" id="store_id"  value="<?php echo (isset($store_details['id_store'])) ? $store_details['id_store'] : ''; ?>">
                                            <input type="text" class="form-control" name="store_name" id="store_name"  placeholder="Store Name" required="required" value="<?php echo (isset($store_details['store_name'])) ? $store_details['store_name'] : set_value('store_name'); ?>">
                                        </div>
                                    </div>        
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Website URL </label>
                                        <div>
                                            <input type="text" class="form-control" name="website" id="website"  placeholder="Website URL" value="<?php echo (isset($store_details['website'])) ? $store_details['website'] : set_value('website'); ?>">
                                        </div>
                                    </div>        
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Facebook Page <span class="text-danger">*</span></label>
                                        <div>
                                            <input type="text" class="form-control" name="facebook_page" id="facebook_page" required="required" placeholder="Facebook Page URL" value="<?php echo (isset($store_details['facebook_page'])) ? $store_details['facebook_page'] : set_value('facebook_page'); ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Logo</label>
                                        <div>
                                            <input type="file" class="form-control file-input" name="store_logo" id="store_logo">
                                            <input type="hidden" name="is_valid" id="is_valid" value="1">
                                            <div id="store_logo_errors_wrapper" class="alert alert-danger alert-bordered display-none">
                                                <span id="store_logo_errors"></span>
                                            </div>
                                            <label id="store_logo-error" class="validation-error-label" for="store_logo"></label>
                                        </div>                                            
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Group</label>
                                        <div>
                                            <input type="text" class="form-control text-length" name="group_text" id="group_text"  placeholder="Group that the store belong to" value="<?php echo (isset($store_details['group_text'])) ? $store_details['group_text'] : set_value('group_text'); ?>">
                                        </div>
                                    </div>        
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Notes</label>
                                        <div>
                                            <input type="text" class="form-control text-length" name="note_text" id="note_text"  placeholder="Notes" value="<?php echo (isset($store_details['note_text'])) ? $store_details['note_text'] : set_value('note_text'); ?>">
                                        </div>
                                    </div>        
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label> </label>
                                        <div>
                                            <?php
                                            if (isset($store_details['store_logo']) && !empty($store_details['store_logo'])) {
                                                $extension = explode('.', $store_details['store_logo']);
                                                if (isset($extension) && isset($extension[1]) && in_array($extension[1], $this->image_extensions_arr)) {
                                                    ?>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <div>                                                                    
                                                                <a href="<?php echo store_img_path . $store_details['store_logo'] ?>" class="btn btn-info btn-lg" target="_blank"><i class="icon-image5"></i> View Image</a>
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
                                                <input type="text" class="form-control" name="first_name" id="first_name"  placeholder="First Name" value="<?php echo (isset($store_details['first_name'])) ? $store_details['first_name'] : set_value('first_name'); ?>">
                                            </div>
                                        </div>        
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Contact Person Last Name</label>
                                            <div>
                                                <input type="text" class="form-control" name="last_name" id="last_name"  placeholder="Last Name"  value="<?php echo (isset($store_details['last_name'])) ? $store_details['last_name'] : set_value('last_name'); ?>">
                                            </div>
                                        </div>        
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Email Address</label>
                                            <div>
                                                <input type="email" class="form-control" name="email_id" id="email_id"  placeholder="Email Address"  value="<?php echo (isset($store_details['email_id'])) ? $store_details['email_id'] : set_value('email_id'); ?>">
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Contact Number</label>
                                            <div>
                                                <input type="text" class="form-control number-only" name="mobile" id="mobile"  placeholder="Contact Number" value="<?php echo (isset($store_details['mobile'])) ? $store_details['mobile'] : set_value('mobile'); ?>">
                                                <label id="mobile-error" class="validation-error-label"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        <?php } ?>
                        <fieldset class="content-group">
                            <legend class="text-bold">Business</legend>
                            <div id="category_selection_wrapper" class="clear-float row_add_div">
                                <?php
                                $categoryCloneNumber = 1;
                                if (isset($store_categories) && sizeof($store_categories) > 0) {
                                    foreach ($store_categories as $key => $cat) {
                                        ?>
                                        <div id="category_selection_block_<?php echo $key; ?>" data-clone-number="<?php echo $key; ?>" class="clear-float">
                                            <div class="col-xs-12 business_category_div">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <div>
                                                            <select id="category_<?php echo $key; ?>" name="exist_category_<?php echo $cat['id_store_category']; ?>" class="select category_selection_dropdown form-control" data-clone-number="<?php echo $key; ?>" required="required">
                                                                <option value="">Select Category</option>
                                                                <?php foreach ($category_list as $list) { ?>
                                                                    <option value="<?php echo $list['id_category']; ?>" <?php echo ($list['id_category'] == $cat['id_category']) ? 'selected=selected' : ''; ?>><?php echo $list['category_name']; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 sub_cat_section_<?php echo $key; ?> <?php echo ($cat['id_sub_category'] > 0) ? '' : 'display-none'; ?>">
                                                    <div class="form-group">                                        
                                                        <div>
                                                            <?php
                                                            $select_sub_category = array(
                                                                'table' => tbl_sub_category,
                                                                'where' => array('status' => ACTIVE_STATUS, 'is_delete' => IS_NOT_DELETED_STATUS, 'id_sub_category' => $cat['id_sub_category'])
                                                            );

                                                            $sub_category_list = $this->Common_model->master_select($select_sub_category);
                                                            ?>
                                                            <select id="sub_category_<?php echo $key; ?>" name="exist_sub_category_<?php echo $cat['id_store_category']; ?>" class="select sub_category_selection_dropdown form-control" data-clone-number="<?php echo $key; ?>">
                                                                <?php if (isset($sub_category_list) && sizeof($sub_category_list) > 0) { ?>
                                                                    <option value="">Select Sub Category</option>
                                                                    <?php foreach ($sub_category_list as $list) { ?>
                                                                        <option value="<?php echo $list['id_sub_category']; ?>" <?php echo ($list['id_sub_category'] == $cat['id_sub_category']) ? 'selected=selected' : ''; ?>><?php echo $list['sub_category_name']; ?></option>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </select>        
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <div>
                                                            <button type="button" class="btn btn-danger btn-icon category_selection_remove_btn" data-clone-number="<?php echo $key; ?>"><i class="icon-cross3"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                        $categoryCloneNumber++;
                                    }
                                } else {
                                    ?>
                                    <div id="category_selection_block_0" data-clone-number="0" class="clear-float">
                                        <div class="col-xs-12 business_category_div">                                
                                            <div class="col-md-3">
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
                                            <div class="col-md-3 sub_cat_section_0">
                                                <div class="form-group">                                        
                                                    <div>
                                                        <select id="sub_category_0" name="sub_category_0" class="select sub_category_selection_dropdown form-control" data-clone-number="0">
                                                            <option value="">Select Sub Category</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <div>
                                                        <button type="button" class="btn btn-danger btn-icon category_selection_remove_btn" data-clone-number="0"><i class="icon-cross3"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>

                            <div class="col-xs-12">                                                                
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <button id="category_selection_btn" type="button" class="pull-left margin-left-5 btn-primary labeled"><b><i class="icon-plus22"></i></b>Add More Category</button>
                                    </div>
                                </div>
                            </div>
                        </fieldset>                            
                        <?php
                        $storeLocationCloneNumber = 1;
                        if ($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE) {
                            ?>
                            <fieldset class="content-group">
                                <legend class="text-bold">Locations</legend>
                                <div class="col-xs-12">
                                    <div class="col-md-3 display-none">
                                        <div class="form-group">
                                            <div>
                                                <?php if (isset($country_list) && sizeof($country_list) > 0) { ?>
                                                    <select class="form-control select" name="id_country" id="id_country" required="required">
                                                        <option value="">Select Country</option>
                                                        <?php foreach ($country_list as $list) { ?>
                                                            <option value="<?php echo $list['id_country']; ?>" <?php echo ((isset($store_details['id_country'])) && $store_details['id_country'] == $list['id_country']) ? 'selected=selected' : ''; ?>><?php echo $list['country_name']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div> 
                                </div>
                                <div id="location_wrapper" class="clear-float row_add_div"> 
                                    <?php
                                    $latitude = '54.6960513';
                                    $longitude = '-113.7297772';

                                    if (isset($store_locations) && sizeof($store_locations) > 0) {
                                        foreach ($store_locations as $key => $loc) {
                                            ?>
                                            <div id="location_block_<?php echo $key; ?>" data-clone-number="<?php echo $key; ?>" class="clear-float">
                                                <div class="col-xs-12 business_category_div">

                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <div>
                                                                <input type="text" class="form-control" name="latitude_<?php echo $loc['id_store_location']; ?>" id="latitude_<?php echo $key; ?>" placeholder="Latitude" value="<?php echo set_value('latitude_0'); ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">                                                         
                                                            <div>
                                                                <input type="text" class="form-control" name="longitude_<?php echo $loc['id_store_location']; ?>" id="longitude_<?php echo $key; ?>" placeholder="Longitude" value="<?php echo set_value('longitude_0'); ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">                                        
                                                            <div>
                                                                <button type="button" class="btn btn-danger btn-icon location_remove_btn" id="location_remove_btn_<?php echo $key; ?>" character="" data-clone-number="<?php echo $key; ?>"><i class="icon-cross3"></i></button>
                                                            </div>
                                                        </div>        
                                                    </div>

                                                </div>
                                            </div>
                                            <?php
                                            $storeLocationCloneNumber++;
                                        }
                                    } else {
                                        ?>
                                        <div id="location_block_0" data-clone-number="0" class="clear-float">
                                            <div class="col-xs-12 business_category_div">                                                                                
                                                <!--<div class="col-md-1">
                                                    <div class="form-group">
                                                        <div>
                                                            <input type="text" class="form-control text-length" name="branch_0" id="branch_0" placeholder="Branch" value="<?php echo set_value('branch_0'); ?>">
                                                        </div>
                                                    </div>
                                                </div>-->
                                                <div class="col-md-1">
                                                    <div class="form-group">
                                                        <label for="offer_type">
                                                            <div class="checkbox">
                                                                <span>
                                                                    <input type="checkbox" id="is_mall_0" data-id="0" class="select-city-mall" value="1" checked />
                                                                    <input type="hidden" name="is_mall_0" id="input_is_mall_0" value="1" />
                                                                </span>
                                                            </div>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <div>
                                                            <div id="city_div_0" class="hide">
                                                                <input type="text" class="form-control" name="location_city_0" id="location_city_0" placeholder="City" value="<?php echo set_value('location_city_0'); ?>">
                                                            </div>
                                                            <div id="mall_div_0">
                                                                <select class="form-control select-search" id="location_mall_id_0" name="location_mall_id_0" data-placeholder="Select Mall" required="required" data-live-search="true" >
                                                                    <?php
                                                                    if (isset($malls_list) && sizeof($malls_list) > 0) {
                                                                        foreach ($malls_list as $list) {
                                                                            ?>
                                                                            <option data-id="<?= $list['id_mall']; ?>" value="<?= $list['id_mall']; ?>" <?php echo (isset($notification_data) && isset($notification_data['id_mall']) && $list['id_mall'] == $notification_data['id_mall']) ? 'selected=selected' : ''; ?>><?= $list['mall_name']; ?></option>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <div class="form-group">
                                                        <div>
                                                            <input type="text" class="form-control hide" name="latitude_0" id="latitude_0" placeholder="Latitude" value="<?php echo set_value('latitude_0'); ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <div class="form-group">                                                         
                                                        <div>
                                                            <input type="text" class="form-control hide" name="longitude_0" id="longitude_0" placeholder="Longitude" value="<?php echo set_value('longitude_0'); ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <div class="form-group">                                                         
                                                        <div>
                                                            <input type="text" class="form-control number-only" name="telephone_0" id="telephone_0" placeholder="Telephone 1" value="<?php echo set_value('telephone_0'); ?>">
                                                            <label id="telephone_0-error" class="validation-error-label"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <div class="form-group">                                                         
                                                        <div>
                                                            <input type="text" class="form-control number-only" name="telephoneA_0" id="telephoneA_0" placeholder="Telephone 2" value="<?php echo set_value('telephoneA_0'); ?>">
                                                            <label id="telephone_0-error" class="validation-error-label"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <div class="form-group">                                                         
                                                        <div>
                                                            <input type="text" class="form-control number-only" name="telephoneB_0" id="telephoneB_0" placeholder="Telephone 3" value="<?php echo set_value('telephoneB_0'); ?>">
                                                            <label id="telephone_0-error" class="validation-error-label"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">                                                         
                                                        <div>
                                                            <input type="email" class="form-control" name="email_0" id="email_0" placeholder="Email" value="<?php echo set_value('email_0'); ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <div class="form-group">                                        
                                                        <div>
                                                            <button type="button" class="btn btn-danger btn-icon location_remove_btn" id="location_remove_btn_0" character="" data-clone-number="0"><i class="icon-cross3"></i></button>
                                                        </div>
                                                    </div>        
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>

                                <div class="col-xs-12">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <button id="location_btn" type="button" class="pull-left margin-left-5 btn-primary labeled"><b><i class="icon-plus22"></i></b>Add More Branch</button>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset class="content-group">
                                <div class="col-md-12">
                                    <div class="col-md-12">
                                        <label>Contact Number</label>
                                    </div>

                                    <div id="contact_number_wrapper" class="clear-float row_add_div">
                                        <?php
                                        $contactCloneNumber = 1;

                                        if (isset($store_contacts) && count($store_contacts) > 0) {
                                            foreach ($store_contacts as $contact_key => $contact) {
                                                ?>
                                                <div id="contact_number_block_<?= $contact_key ?>" data-clone-number="<?= $contact_key ?>" class="clear-float col-md-4">
                                                    <div class="business_category_div">                                                                                
                                                        <div class="col-md-10">
                                                            <div class="form-group">
                                                                <div>
                                                                    <input type="hidden" name="existed_store_contact_id_<?= $contact['id_store_contact'] ?>" id="existed_store_contact_id_<?= $contact['id_store_contact'] ?>" value="<?php echo $contact['id_store_contact']; ?>">
                                                                    <input type="text" class="form-control number-only" name="existed_store_contact_number_<?= $contact['id_store_contact'] ?>" id="existed_store_contact_number_<?= $contact['id_store_contact'] ?>" placeholder="Telephone" value="<?php echo $contact['contact_number']; ?>">
                                                                    <label id="existed_store_contact_number_<?= $contact['id_store_contact'] ?>-error" class="validation-error-label"></label>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <div class="form-group">                                        
                                                                <div>
                                                                    <button type="button" class="btn btn-danger btn-icon contact_number_remove_btn" id="contact_remove_btn_<?= $contact_key ?>" character="" data-clone-number="<?= $contact_key ?>"><i class="icon-cross3"></i></button>
                                                                </div>
                                                            </div>        
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                                $contactCloneNumber++;
                                            }
                                        } else {
                                            ?> 
                                            <div id="contact_number_block_0" data-clone-number="0" class="clear-float col-md-4 ">
                                                <div class="business_category_div">                                                                                
                                                    <div class="col-md-10">
                                                        <div class="form-group">
                                                            <div>
                                                                <input type="text" class="form-control number-only" name="contact_number_0" id="contact_number_0" placeholder="Telephone" value="<?php echo set_value('contact_number_0'); ?>">
                                                                <label id="contact_number_0-error" class="validation-error-label"></label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <div class="form-group">                                        
                                                            <div>
                                                                <button type="button" class="btn btn-danger btn-icon contact_number_remove_btn" id="contact_remove_btn_0" character="" data-clone-number="0"><i class="icon-cross3"></i></button>
                                                            </div>
                                                        </div>        
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <button id="add_contact_number_btn" type="button" class="pull-left margin-left-5 btn-primary labeled"><b><i class="icon-plus22"></i></b>Add More Number</button>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset class="content-group">     
                                <div class="col-xs-12">
                                    <div class="col-md-3">
                                        <label>Contact Email</label>
                                        <div class="col-xs-12">
                                            <div class="form-group">
                                                <div>
                                                    <input type="email" class="form-control" name="contact_email" id="contact_email" placeholder="Contact Email" value="<?php echo (isset($store_details['contact_email'])) ? $store_details['contact_email'] : set_value('contact_email'); ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>     
                            <fieldset class="content-group">     
                                <div class="col-xs-12">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Upload City Branches From Excel</label>
                                            <div>                                                
                                                <input type="file" class="form-control file-input" name="location_excel" id="location_excel">
                                            </div>
                                        </div>        
                                    </div>
                                    <div class="col-md-6">
                                        <?php if (isset($store_details)) { ?>
                                            <div class="form-group">                                                
                                                <label><br></label>
                                                <div>
                                                    <a href="<?php echo $download_locations_url; ?>" class="btn bg-brown">Export To Excel</a>
                                                    <a href="<?php echo 'country-admin/stores/locations/' . $store_details['id_store']; ?>" target="_blank" class="btn bg-teal">Locations List</a>
                                                </div>
                                            </div>        
                                        <?php } ?>
                                    </div>
                                    <div class="col-md-3 text-right">
                                        <div class="form-group">                                                
                                            <label><br></label>
                                            <div>                                                        
                                                <a href="<?php echo $download_locations_format_url; ?>" target="_blank" class="btn bg-teal">Download File Format</a>
                                            </div>
                                        </div>        
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset class="content-group">     
                                <div class="col-xs-12">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Upload Mall Branches From Excel</label>
                                            <div>                                                
                                                <input type="file" class="form-control file-input" name="mall_location_excel" id="mall_location_excel">
                                            </div>
                                        </div>        
                                    </div>
                                    <div class="col-md-6">
                                        <?php if (isset($store_details)) { ?>
                                            <div class="form-group">                                                
                                                <label><br></label>
                                                <div>
                                                    <a href="" class="btn bg-brown">Export To Excel</a>
                                                </div>
                                            </div>        
                                        <?php } ?>
                                    </div>
                                    <div class="col-md-3 text-right">
                                        <div class="form-group">                                                
                                            <label><br></label>
                                            <div>                                                        
                                                <a href="<?php echo $download_mall_format_url; ?>" target="_blank" class="btn bg-teal">Download File Format</a>
                                            </div>
                                        </div>        
                                    </div>
                                </div>
                            </fieldset>
                        <?php } ?>

                        <fieldset class="content-group hide">
                            <legend class="text-bold">Mall Presence</legend>
                            <div class="col-xs-12">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <div>
                                            <?php if (isset($malls_list) && sizeof($malls_list) > 0) { ?>
                                                <select class="form-control select " multiple name="id_malls[]" id="id_malls">                                                        
                                                    <?php foreach ($malls_list as $list) { ?>
                                                        <option value="<?php echo $list['id_mall']; ?>" <?php echo (isset($store_malls_list) && in_array($list['id_mall'], $store_malls_list)) ? 'selected=selected' : ''; ?>><?php echo $list['mall_name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>                                    
                        </fieldset>
                        <?php if ($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE) { ?>
                            <fieldset class="content-group">
                                <legend class="text-bold">Sales Trend</legend>  
                                <div class="col-xs-12">                                                                                                        
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
                                                            <input type="text" class="form-control sales_trend_to_date"  placeholder="To Date"  data-clone-number="0" name="to_date_0" id="to_date_0" value="">
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
                        <?php if (($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE) || (!isset($store_details) && $this->loggedin_user_type == STORE_OR_MALL_ADMIN_USER_TYPE)) { ?>
                            <fieldset class="content-group">
                                <legend class="text-bold">Others</legend>
                                <div class="col-xs-12">
                                    <div class="col-md-3">
                                        <?php if ($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE) { ?>
                                            <label>Status <span class="text-danger">*</span></label>
                                            <div>                                            
                                                <select id="status" name="status" class="select form-control" data-clone-number="0" required="required">
                                                    <?php foreach ($status_list as $key => $list) { ?>
                                                        <option value="<?php echo $key; ?>" <?php echo (isset($store_details['store_status']) && $key == $store_details['store_status']) ? 'selected=selected' : ''; ?>><?php echo $list; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="col-md-6"></div>
                                    <div class="col-md-3">
                                        <?php if ($this->loggedin_user_type == STORE_OR_MALL_ADMIN_USER_TYPE) { ?>
                                            <div class="form-group pull-right"> 
                                                <label></label>
                                                <div>
                                                    <input type="checkbox" class="styled-checkbox-1" name="terms_condition" required="required"/>                
                                                    <span class="text-size-mini">  Yes, I agree with <a href="terms-and-conditions" target="_blank"><span>Terms And Conditions</span></a></span>
                                                    <label id="terms_condition-error" class="validation-error-label" for="terms_condition"></label>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </fieldset>
                        <?php } ?>
                        <div class="text-right">
                            <input type="hidden" name="category_count" id="category_count" value="<?php echo $categoryCloneNumber; ?>">
                            <input type="hidden" name="location_count" id="location_count" value="<?php echo $storeLocationCloneNumber; ?>">
                            <input type="hidden" name="contact_no_count" id="contact_no_count" value="<?php echo $contactCloneNumber; ?>">
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
<script type="text/javascript" src="assets/user/js/store.js"></script>
<script>
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
    var categoryCloneNumber = 1;
    var locationCloneNumber = 1;
    var salesTrendNumber = 1;
    var contactCloneNumber = 1;
<?php if ($categoryCloneNumber > 0) { ?>
        categoryCloneNumber = '<?php echo $categoryCloneNumber; ?>';
<?php } if ($storeLocationCloneNumber > 0) { ?>
        locationCloneNumber = '<?php echo $storeLocationCloneNumber; ?>';
    <?php
}
if (@$salesTrendCloneNumber > 0) {
    ?>
        salesTrendNumber = '<?php echo @$salesTrendCloneNumber; ?>';
    <?php
}
if (isset($store_details)) {
    ?>
        $(document).find('#id_country').attr('disabled', 'disabled');
<?php } ?>

<?php if ($contactCloneNumber > 0) { ?>
        contactCloneNumber = '<?= $contactCloneNumber; ?>';
<?php } ?>

    $(document).on('click', '#location_btn', function () {

        var countryId = '<?php echo @$country_id ?>';
        //        console.log(countryId);
        $.ajax({
            method: 'POST',
            url: base_url + 'storeregistration/show_mall',
            data: {country_id: countryId}, success: function (response) {
                $(document).find('.mall_selection_dropdown').html(response);
            },
            error: function () {
                console.log("error occur");
            },
        });
        var html = generateLocationBlock(locationCloneNumber);
        $(document).find('#location_wrapper').append(html);
        locationCloneNumber++;
        reInitializeSelect2Control();
        $(document).find('#location_count').val(locationCloneNumber);
    });

    $(document).on('click', '.select-city-mall', function () {
        var box_value = 0;
        var column_no = $(this).attr('data-id');

        if ($(this).is(':checked')) {
            box_value = 1;
            $("#city_div_" + column_no).addClass('hide');
            $("#mall_div_" + column_no).removeClass('hide');
            
            $("#latitude_" + column_no).addClass('hide');
            $("#longitude_" + column_no).addClass('hide');
        } else {
            box_value = 0;
            $("#mall_div_" + column_no).addClass('hide');
            $("#city_div_" + column_no).removeClass('hide');
            
            $("#latitude_" + column_no).removeClass('hide');
            $("#longitude_" + column_no).removeClass('hide');
        }

        $(this).val(box_value);
        $("#input_is_mall_"+column_no).val(box_value);
    });

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

    function generatecategorySelectionBlock(cloneNumber) {
        var html = '';
        html += '<div id="category_selection_block_' + cloneNumber + '" data-clone-number="' + cloneNumber + '" class="clear-float">';
        html += '<div class="col-xs-12 business_category_div">';
        html += '<div class="col-md-3">';
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
        html += '<div class="col-md-3 sub_cat_section_' + cloneNumber + '">';
        html += '<select id="sub_category_' + cloneNumber + '" name="sub_category_' + cloneNumber + '" class="select sub_category_selection_dropdown form-control" data-clone-number="' + cloneNumber + '">';
        html += '<option value="">Select Subcategory</option>';
        html += '</select>';
        html += '</div>';
        html += '<div class="col-md-4 product-selection-remove-prod-btn">';
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

    function generateLocationBlock(cloneNumber) {
        var html = '';
        html += '<div id="location_block_' + cloneNumber + '" data-clone-number="' + cloneNumber + '" class="clear-float">';
        html += '<div class="col-xs-12 business_category_div">';

        html += '<div class="col-md-1">';
        html += '<div class="form-group">';
        html += '<label for="is_mall_' + cloneNumber + '">';
        html += '<div class="checkbox">';
        html += '<span>';
        html += '<input type="checkbox" id="is_mall_' + cloneNumber + '" data-id="' + cloneNumber + '" checked class="styled select-city-mall" value="1" />';
        html += '<input type="hidden" name="is_mall_' + cloneNumber + '" id="input_is_mall_' + cloneNumber + '" value="1" />';
        html += '</span>';
        html += '</div>';
        html += '</label>';
        html += '</div>';
        html += '</div>';

        html += '<div class="col-md-2">';
        html += '<div class="form-group">';
        html += '<div>';
        html += '<div id="city_div_' + cloneNumber + '" class="hide">';
        html += '<input type="text" class="form-control" name="location_city_' + cloneNumber + '" id="location_city_' + cloneNumber + '" placeholder="City" value="">';
        html += '</div>';
        html += '<div id="mall_div_' + cloneNumber + '">';
        html += '<select class="form-control select-search" id="location_mall_id_' + cloneNumber + '" name="location_mall_id_' + cloneNumber + '" data-placeholder="Select Mall" required="required" data-live-search="true" >';
<?php
if (isset($malls_list) && sizeof($malls_list) > 0) {
    foreach ($malls_list as $list) {
        ?>
                html += '<option data-id="<?= $list['id_mall']; ?>" value="<?= $list['id_mall']; ?>" ><?= $list['mall_name']; ?></option>';
        <?php
    }
}
?>
        html += '</select>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '</div>';

        html += '<div class="col-md-1">';
        html += '<div class="form-group">';
        html += '<div>';
        html += '<input type="text" class="form-control hide" name="latitude_' + cloneNumber + '" id="latitude_' + cloneNumber + '" placeholder="Latitude" value="">';
        html += '</div>';
        html += '</div>';
        html += '</div>';

        html += '<div class="col-md-1">';
        html += '<div class="form-group">';
        html += '<div>';
        html += '<input type="text" class="form-control hide" name="longitude_' + cloneNumber + '" id="longitude_' + cloneNumber + '" placeholder="Longitude" value="">';
        html += '</div>';
        html += '</div>';
        html += '</div>';

        html += '<div class="col-md-1">';
        html += '<div class="form-group">';
        html += '<div>';
        html += '<input type="text" class="form-control number-only" name="telephone_' + cloneNumber + '" id="telephone_' + cloneNumber + '" placeholder="Telephone 1" value="">';
        html += '<label id="telephone_' + cloneNumber + '-error" class="validation-error-label"></label>';
        html += '</div>';
        html += '</div>';
        html += '</div>';

        html += '<div class="col-md-1">';
        html += '<div class="form-group">';
        html += '<div>';
        html += '<input type="text" class="form-control number-only" name="telephoneA_' + cloneNumber + '" id="telephoneA_' + cloneNumber + '" placeholder="Telephone 2" value="">';
        html += '<label id="telephoneA_' + cloneNumber + '-error" class="validation-error-label"></label>';
        html += '</div>';
        html += '</div>';
        html += '</div>';

        html += '<div class="col-md-1">';
        html += '<div class="form-group">';
        html += '<div>';
        html += '<input type="text" class="form-control number-only" name="telephoneB_' + cloneNumber + '" id="telephoneB_' + cloneNumber + '" placeholder="Telephone 3" value="">';
        html += '<label id="telephoneB_' + cloneNumber + '-error" class="validation-error-label"></label>';
        html += '</div>';
        html += '</div>';
        html += '</div>';

        html += '<div class="col-md-2">';
        html += '<div class="form-group">';
        html += '<div>';
        html += '<input type="email" class="form-control" name="email_' + cloneNumber + '" id="email_' + cloneNumber + '" placeholder="Email" value="">';
        html += '</div>';
        html += '</div>';
        html += '</div>';

        html += '<div class="col-md-1">';
        html += '<div class="form-group">';
        html += '<div>';
        html += '<button type="button" class="btn btn-danger btn-icon location_remove_btn" id="location_remove_btn_' + cloneNumber + '" character="" data-clone-number="' + cloneNumber + '"><i class="icon-cross3"></i></button>';
        html += '</div>';
        html += '</div>';
        html += '</div>';

        html += '</div>';
        html += '</div>';
        return html;
    }

    $(document).on('click', '#add_contact_number_btn', function () {
        var html = generateContactsBlock(contactCloneNumber);

        $(document).find('#contact_number_wrapper').append(html);
        contactCloneNumber++;
        $(document).find('#contact_no_count').val(contactCloneNumber);
    });

    function generateContactsBlock(cloneNumber) {
        var html = '';
        html += '<div id="contact_number_block_' + cloneNumber + '" data-clone-number="' + cloneNumber + '" class="clear-float col-md-4">';
        html += '<div class=" business_category_div">';
        html += '<div class="col-md-10">';
        html += '<div class="form-group">';
        html += '<div>';
        html += '<input type="text" class="form-control number-only" name="contact_number_' + cloneNumber + '" id="contact_number_' + cloneNumber + '" placeholder="Telephone" value="">';
        html += '<label id="contact_number_' + cloneNumber + '-error" class="validation-error-label"></label>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '<div class="col-md-2">';
        html += '<div class="form-group">';
        html += '<div>';
        html += '<button type="button" class="btn btn-danger btn-icon contact_number_remove_btn" id="contact_remove_btn_' + cloneNumber + '" character="" data-clone-number="' + cloneNumber + '"><i class="icon-cross3"></i></button>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        return html;
    }

//    $(document).on('change', '.select-city-mall', function () {
//        var checked_val = $(this).find('option:selected').val();
//        var column_no = $(this).attr('data-id');
//
//        if (checked_val == 0) {
//            $("#mall_div_" + column_no).addClass('hide');
//            $("#city_div_" + column_no).removeClass('hide');
//        } else if (checked_val == 1) {
//            $("#city_div_" + column_no).addClass('hide');
//            $("#mall_div_" + column_no).removeClass('hide');
//        }
//    });
</script>