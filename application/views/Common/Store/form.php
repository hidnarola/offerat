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
                            <fieldset class="content-group">
                                <legend class="text-bold">Store Info.</legend>
                                <div class="col-xs-12">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Store Name <span class="text-danger">*</span></label>
                                            <div>
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
                                            <label>Facebook Page</label>
                                            <div>
                                                <input type="text" class="form-control" name="facebook_page" id="facebook_page"  placeholder="Facebook Page URL" value="<?php echo (isset($store_details['facebook_page'])) ? $store_details['facebook_page'] : set_value('facebook_page'); ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Logo <span class="text-danger">*</span></label>
                                            <div>
                                                <input type="file" class="form-control file-input" name="store_logo" id="store_logo" required="required">
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
                                            <label>Telephone Number <span class="text-danger">*</span></label>
                                            <div>
                                                <input type="text" class="form-control" name="telephone" id="telephone"  placeholder="Telephone Number"  required="required" value="<?php echo (isset($store_details['telephone'])) ? $store_details['telephone'] : set_value('telephone'); ?>">                
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
                                                <label>Contact Person First Name <span class="text-danger">*</span></label>
                                                <div>
                                                    <input type="text" class="form-control" name="first_name" id="first_name"  placeholder="First Name"  required="required" value="<?php echo (isset($store_details['first_name'])) ? $store_details['first_name'] : set_value('first_name'); ?>">
                                                </div>
                                            </div>        
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Contact Person Last Name <span class="text-danger">*</span></label>
                                                <div>
                                                    <input type="text" class="form-control" name="last_name" id="last_name"  placeholder="Last Name"  required="required" value="<?php echo (isset($store_details['last_name'])) ? $store_details['last_name'] : set_value('last_name'); ?>">
                                                </div>
                                            </div>        
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Email Address <span class="text-danger">*</span></label>
                                                <div>
                                                    <input type="email" class="form-control" name="email_id" id="email_id"  placeholder="Email Address"  required="required" value="<?php echo (isset($store_details['email_id'])) ? $store_details['email_id'] : set_value('email_id'); ?>">
                                                </div>
                                            </div>
                                        </div> 
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Mobile Number <span class="text-danger">*</span></label>
                                                <div>
                                                    <input type="text" class="form-control" name="mobile" id="mobile"  placeholder="Mobile Number" required="required" value="<?php echo (isset($store_details['mobile'])) ? $store_details['mobile'] : set_value('mobile'); ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            <?php } ?>
                            <fieldset class="content-group">
                                <legend class="text-bold">Business</legend>
                                <div class="col-xs-12">                                                                
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <button id="category_selection_btn" type="button" class="pull-left margin-left-5 btn-primary labeled"><b><i class="icon-plus22"></i></b>Add More Category</button>
                                        </div>
                                    </div>
                                </div>                            
                                <div id="category_selection_wrapper" class="clear-float row_add_div">
                                    <?php
                                    if (isset($store_categories) && sizeof($store_categories) > 0) {
                                        foreach ($store_categories as $key => $cat) {
                                            pr($cat);
                                            ?>
                                            <div id="category_selection_block_<?php echo $key; ?>" data-clone-number="<?php echo $key; ?>" class="clear-float">
                                                <div class="col-xs-12 business_category_div">                                
                                                    <div class="col-md-3">
                                                        <div class="form-group">                                        
                                                            <div>
                                                                <select id="category_<?php echo $key; ?>" name="category_<?php echo $key; ?>" class="select category_selection_dropdown form-control" data-clone-number="<?php echo $key; ?>" required="required">
                                                                    <option value="">Select Category</option>
                                                                    <?php foreach ($category_list as $list) { ?>
                                                                        <option value="<?php echo $list['id_category']; ?>" <?php echo ($list['id_category'] == $cat['id_category']) ? 'selected=selected' : ''; ?>><?php echo $list['category_name']; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 sub_cat_section_<?php echo $key; ?>">
                                                        <div class="form-group">                                        
                                                            <div>
                                                                <?php
                                                                $select_sub_category = array(
                                                                    'table' => tbl_sub_category,
                                                                    'where' => array('status' => ACTIVE_STATUS, 'is_delete' => IS_NOT_DELETED_STATUS, 'id_sub_category')
                                                                );
                                                                
                                                                ?>
                                                                <select id="sub_category_<?php echo $key; ?>" name="sub_category_<?php echo $key; ?>" class="select sub_category_selection_dropdown form-control display-none" data-clone-number="<?php echo $key; ?>">
                                                                    <option value="">Select Sub Category</option>
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
                                                            <select id="sub_category_0" name="sub_category_0" class="select sub_category_selection_dropdown form-control display-none" data-clone-number="0">
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
                            </fieldset>
                            <fieldset class="content-group">
                                <legend class="text-bold">Branches</legend>
                                <div class="col-xs-12">
                                    <div class="col-md-3">
                                        <div class="form-group">                                        
                                            <div>
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
                                    </div> 
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <button id="mall_selection_btn" type="button" class="pull-left margin-left-5 btn-primary labeled"><b><i class="icon-plus22"></i></b>Add More Branch</button>
                                        </div>
                                    </div>
                                </div>
                                <div id="mall_selection_wrapper" class="clear-float row_add_div"> 
                                    <?php
                                    $latitude = '54.6960513';
                                    $longitude = '-113.7297772';
                                    ?>
                                    <div id="mall_selection_block_0" data-clone-number="0" class="clear-float">
                                        <div class="col-xs-12 business_category_div">                                
                                            <div class="col-md-3">
                                                <div class="form-group">                                        
                                                    <div>
                                                        <select id="mall_0" name="mall_0" class="select mall_selection_dropdown form-control" data-clone-number="0" required="required">
                                                            <option value="0">Only Shop</option>
                                                        </select>
                                                    </div>
                                                </div>        
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">                                        
                                                    <div>
                                                        <input type="text" data-latitude="latitude_0" data-longitude="longitude_0" required="required" data-type="googleMap" data-zoom="10" data-lat="<?php echo $latitude; ?>" data-lang="<?php echo $longitude; ?>" data-input_id="google_input_0" id="google_input_0" type="text" class="form-control" name="address_0"  placeholder="Location" aria-required="true" value="" data-clone-number="0">
                                                        <input type="hidden" class="form-control" data-type="latitude_0" name="latitude_0" id="latitude_0" value="<?php echo $latitude; ?>">
                                                        <input type="hidden" class="form-control" data-type="longitude_0" name="longitude_0" id="longitude_0" value="<?php echo $longitude; ?>">
                                                        <input type="hidden" class="form-control" name="street_0" id="street_0" value="">
                                                        <input type="hidden" class="form-control" name="street1_0" id="street1_0" value="">
                                                        <input type="hidden" class="form-control" name="city_0" id="city_0" value="">
                                                        <input type="hidden" class="form-control" name="state_0" id="state_0" value="">
                                                        <input type="hidden" class="form-control" name="zip_code_0" id="zip_code_0" value="">
                                                        <input type="hidden" class="form-control" name="place_id_0" id="place_id_0" value="">
                                                    </div>
                                                </div>        
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">                                        
                                                    <div>
                                                        <button type="button" class="btn btn-danger btn-icon mall_selection_remove_btn" id="mall_selection_remove_btn_0" character="" data-clone-number="0"><i class="icon-cross3"></i></button>
                                                    </div>
                                                </div>        
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset class="content-group">
                                <legend class="text-bold">Others</legend>
                                <div class="col-xs-12">
                                    <div class="col-md-3">
                                        <?php if ($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE) { ?>
                                            <label>Status <span class="text-danger">*</span></label>
                                            <div>                                            
                                                <select id="status" name="status" class="select form-control" data-clone-number="0" required="required">
                                                    <?php foreach ($status_list as $key => $list) { ?>
                                                        <option value="<?php echo $key; ?>"><?php echo $list; ?></option>
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
                                                    <span class="text-size-mini">  Yes, I agree with <a href="javascript:void(0);" target="_blank"><span>Terms And Conditions</span></a></span>            
                                                    <label id="terms_condition-error" class="validation-error-label" for="terms_condition"></label>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </fieldset>
                            <div class="text-right">
                                <input type="hidden" name="category_count" id="category_count" value="1">
                                <input type="hidden" name="location_count" id="location_count" value="1">
                                <a href="<?php echo $back_url ?>" class="btn bg-grey-300 btn-labeled"><b><i class="icon-arrow-left13"></i></b>Back</a>
                                <button type="submit" class="btn bg-teal btn-labeled btn-labeled-right"><b><i class="icon-arrow-right14"></i></b>Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript" src="assets/user/js/store.js"></script>
<script>
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

    function generatemallSelectionBlock(cloneNumber) {
        var html = '';
        html += '<div id="mall_selection_block_' + cloneNumber + '" data-clone-number="' + cloneNumber + '" class="clear-float">';
        html += '<div class="col-xs-12 business_category_div">';
        html += '<div class="col-sm-3">';
        html += '<div class="form-group">';
        html += '<div>';
        html += '<select id="mall_' + cloneNumber + '" name="mall_' + cloneNumber + '" class="select mall_selection_dropdown form-control" data-clone-number="' + cloneNumber + '" required="required">';
        html += '<option value="0">Only Shop</option>';
        html += '</select>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '<div class="col-sm-6">';
        html += '<input type="text" data-latitude="latitude_' + cloneNumber + '" data-longitude="longitude_' + cloneNumber + '" required="required" data-type="googleMap" data-zoom="10" data-lat="<?php echo $latitude; ?>" data-lang="<?php echo $longitude; ?>" data-input_id="google_input_' + cloneNumber + '" id="google_input_' + cloneNumber + '" type="text" class="form-control" name="address_' + cloneNumber + '"  placeholder="Location" aria-required="true" value="" data-clone-number="' + cloneNumber + '">';
        html += '<input type="hidden" class="form-control" data-type="latitude_' + cloneNumber + '" name="latitude_' + cloneNumber + '" id="latitude_' + cloneNumber + '" value="<?php echo $latitude; ?>">';
        html += '<input type="hidden" class="form-control" data-type="longitude_' + cloneNumber + '" name="longitude_' + cloneNumber + '" id="longitude_' + cloneNumber + '" value="<?php echo $longitude; ?>">';
        html += '<input type="hidden" class="form-control" name="street_' + cloneNumber + '" id="street_' + cloneNumber + '" value="">';
        html += '<input type="hidden" class="form-control" name="street1_' + cloneNumber + '" id="street1_' + cloneNumber + '" value="">';
        html += '<input type="hidden" class="form-control" name="city_' + cloneNumber + '" id="city_' + cloneNumber + '" value="">';
        html += '<input type="hidden" class="form-control" name="state_' + cloneNumber + '" id="state_' + cloneNumber + '" value="">';
        html += '<input type="hidden" class="form-control" name="zip_code_' + cloneNumber + '" id="zip_code_' + cloneNumber + '" value="">';
        html += '<input type="hidden" class="form-control" name="place_id_' + cloneNumber + '" id="place_id_' + cloneNumber + '" value="">';
        html += '</div>';
        html += '<div class="col-sm-3 product-selection-remove-prod-btn">';
        html += '<div class="form-group">';
        html += '<div>';
        html += '<button type="button" class="btn btn-danger btn-icon mall_selection_remove_btn" id="mall_selection_remove_btn_' + cloneNumber + '" character="" data-clone-number="' + cloneNumber + '"><i class="icon-cross3"></i></button>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        return html;
    }

    function initAutocomplete() {
        $('[data-type="googleMap"]').each(function () {
            var currentThis = $(this);
            var control_number = currentThis.data('clone-number');
            var input = document.getElementById($(this).data('input_id'));
            var searchBox = new google.maps.places.SearchBox(input);
            searchBox.addListener('places_changed', function () {
                var places = searchBox.getPlaces();
                if (places.length == 0) {
                    return;
                }
                places.forEach(function (place) {
                    if (typeof place.geometry !== 'undefined') {
                        $('[data-type="' + currentThis.data('latitude') + '"]').val(place.geometry.location.lat());
                        $('[data-type="' + currentThis.data('longitude') + '"]').val(place.geometry.location.lng());
                    } else {
                        //googleLocationIssuePrompt();
                    }
                });
                /**
                 * This code is used copy address when selection is done from auto complete
                 */

                fillInAddress(places, control_number);
            });
        });
    }

    function fillInAddress(place, control_number) {
        var componentForm = {
            street_number: 'long_name',
            route: 'long_name',
            sublocality_level_1: 'long_name',
            locality: 'long_name',
            administrative_area_level_1: 'short_name',
            postal_code: 'short_name',
        };
        var formFields = {
            street_number: 'street',
            route: 'street',
            sublocality_level_1: 'street1',
            locality: 'city',
            administrative_area_level_1: 'state',
            postal_code: 'zip_code',
            place_id: 'place_id',
        };
        fillInAddressComponents(place, componentForm, formFields, control_number);
    }

    function fillInAddressComponents(place, componentForm, formFields, control_number) {
        place = place[0];
//        console.log(place);
        for (var field in formFields) {
            document.getElementById(formFields[field] + '_' + control_number).value = '';
        }
        // Get each component of the address from the place details
        // and fill the corresponding field on the form.
        if (typeof place.address_components != 'undefined') {
            for (var i = 0; i < place.address_components.length; i++) {
                var addressType = place.address_components[i].types[0];
                if (formFields[addressType]) {
                    var val = place.address_components[i][componentForm[addressType]];
                    if (addressType === 'street_number' || addressType === 'route') {
                        document.getElementById(formFields[addressType] + '_' + control_number).value += ' ' + val;
                    } else {
                        document.getElementById(formFields[addressType] + '_' + control_number).value = val;
                    }

                    if (place.place_id != '') {
                        document.getElementById('place_id_' + control_number).value = place.place_id;
                    }
                }
            }
        }
    }
    $(document).find('.sub_cat_section_0').hide();
</script>
<script src="https://maps.googleapis.com/maps/api/js?libraries=geometry,places&key=<?php echo GOOGLE_API_KEY ?>&callback=initAutocomplete" async defer></script>