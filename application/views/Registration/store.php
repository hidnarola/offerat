<form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
    <fieldset class="content-group">                                                
        <legend class="text-bold">Store Info.</legend>

        <div class="form-group">                                        
            <label class="control-label col-lg-2">Store Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="store_name" id="store_name"  placeholder="Store Name" required="required" value="<?php echo set_value('store_name'); ?>">
        </div>

        <div class="form-group">
            <label class="control-label col-lg-2">Website URL</label>
            <input type="text" class="form-control" name="website" id="website"  placeholder="Website URL" value="<?php echo set_value('website'); ?>">
        </div>

        <div class="form-group">
            <label class="control-label col-lg-2">Facebook Page</label>
            <input type="text" class="form-control" name="facebook_page" id="facebook_page"  placeholder="Facebook Page URL" value="<?php echo set_value('facebook_page'); ?>">
        </div>

        <div class="form-group">
            <label class="control-label col-lg-2">Logo</label>
            <input type="file" class="form-control file-input" name="store_logo" id="store_logo" required="required">
        </div>

        <legend class="text-bold">Personal Info.</legend>

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

        <div class="form-group">
            <label class="control-label col-lg-2">Contact Number <span class="text-danger">*</span></label>
            <div class="width_50 first">
                <input type="text" class="form-control" name="telephone" id="telephone"  placeholder="Contact Number"  required="required" value="<?php echo set_value('telephone'); ?>">
            </div>
        </div>

        <legend class="text-bold">Business</legend>


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

                        <div class="col-md-5">
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
        </div>


        <legend class="text-bold">Branches</legend>

        <div class="form-group">
            <label class="control-label col-lg-2">Country <span class="text-danger">*</span></label>
            <div class="width_50 first">
                <?php if (isset($country_list) && sizeof($country_list) > 0) { ?>
                    <select class="form-control" name="id_country" id="id_country" required="required">                                            
                        <option value="">Select Country</option>
                        <?php foreach ($country_list as $list) { ?>
                            <option value="<?php echo $list['id_country']; ?>"><?php echo $list['country_name']; ?></option>
                        <?php } ?>
                    </select>
                <?php } ?>
            </div>
            <div class="width_50 last">
                <div class="add_desc">
                    <button id="mall_selection_btn" type="button" class="pull-right margin-left-5 btn-primary labeled"><b><i class="icon-plus22"></i></b>Add More Branch</button>
                </div> 
            </div>
        </div> 
        <div id="mall_selection_wrapper" class="clear-float row_add_div">  
            <div id="mall_selection_block_0" data-clone-number="0" class="clear-float">
                <div class="col-md-6">
                    <div id="map-canvas"></div>
                </div>
                <div class="col-md-6 business_category_div">

                    <!--                    <div class="col-md-1">
                                            <div class="form-group">
                                                <div>
                                                    <select id="mall_0" name="mall_0" class="select mall_selection_dropdown form-control" data-clone-number="0" required="required">
                                                        <option value="0">Only Shop</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                    <?php
                    $latitude = '54.6960513';
                    $longitude = '-113.7297772';
                    ?>
                                            <div class="form-group">
                    <?php
                    $latitude = '';
                    $longitude = '';
                    ?>                                                            
                                                <div>
                                                    <input data-latitude="latitude_0" data-longitude="longitude_0" required="required" data-type="googleMap" data-zoom="10" data-lat="<?php echo $latitude; ?>" data-lang="<?php echo $longitude; ?>" data-input_id="google_input_0" id="google_input_0" type="text" class="form-control" name="address_0"  placeholder="Location" aria-required="true" value="" data-clone-number="0" />
                                                    <input data-type="latitude_0" type="hidden" name="latitude_0" value="<?php echo $latitude; ?>">
                                                    <input data-type="longitude_0" type="hidden" name="longitude_0" value="<?php echo $longitude; ?>">
                                                    <input type="text" class="form-control" name="street1_0" id="street1_0" value="">                                                            
                                                    <input type="text" class="form-control" name="city_0" id="city_0" value="">                                                            
                                                    <input type="text" class="form-control" name="state_0" id="state_0" value="">                                                            
                                                    <input type="text" class="form-control" name="zip_code_0" id="zip_code_0" value="">
                                                    <input type="text" class="form-control" name="place_id_0" id="place_id_0" value="">
                    
                                                    <span class="message_note"></span>
                                                    <span class="message_error1" id="address_error"></span>
                                                </div>                                                        
                                            </div>
                                        </div>
                    
                                        <div class="col-md-1 product-selection-remove-prod-btn">
                                            <div class="form-group"><div><button type="button" class="btn btn-danger btn-icon mall_selection_remove_btn" data-clone-number="0"><i class="icon-cross3"></i></button>
                                                </div>
                                            </div>
                                        </div>-->
                </div>
            </div>
        </div>
        <div class="form-group checkbox_reg">
            <input type="checkbox" class="styled-checkbox-1" name="terms_condition" required="required"/>
            <span class="text-size-mini">  Yes, I agree with <a href="javascript:void(0);" target="_blank"><span>Terms And Conditions</span></a></span>
            <label id="send_verification_email-error" class="validation-error-label" for="send_verification_email"></label>
        </div>
        <div class="form-group btn_center">            
            <input type="hidden" name="category_count" id="category_count" value="0">
            <input type="hidden" name="location_count" id="location_count" value="0">
            <button type="submit" class="btn btn-primary btn-block submit_btn">Submit</button>
        </div>
    </fieldset>
</form>
<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_API_KEY ?>&callback=initMap" async defer></script>
<script type="text/javascript" src="assets/front/js/store.js"></script>
<script>
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
        html += '<div class="col-md-5">';
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

    function generatemallSelectionBlock(cloneNumber) {
        var html = '';
        html += '<div id="mall_selection_block_' + cloneNumber + '" data-clone-number="' + cloneNumber + '" class="clear-float">';
        html += '<div class="col-md-12 business_category_div">';
        html += '<div class="col-md-4">';
        html += '<div class="form-group">';
        html += '<div>';
        html += '<select id="mall_' + cloneNumber + '" name="mall_' + cloneNumber + '" class="select mall_selection_dropdown form-control" data-clone-number="' + cloneNumber + '" required="required">';
        html += '<option value="0">Only Shop</option>';
        html += '</select>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '<div class="col-md-7">';
        html += '<input data-latitude="latitude_' + cloneNumber + '" data-longitude="longitude_' + cloneNumber + '" required="required" data-type="googleMap" data-zoom="10" data-lat="<?php echo $latitude; ?>" data-lang="<?php echo $longitude; ?>" data-input_id="google_input_' + cloneNumber + '" id="google_input_' + cloneNumber + '" type="text" class="form-control" name="address_' + cloneNumber + '"  placeholder="Location" aria-required="true" value="" data-clone-number="' + cloneNumber + '">';
        html += '<input data-type="latitude_' + cloneNumber + '" type="hidden" name="latitude_' + cloneNumber + '" value="<?php echo $latitude; ?>">';
        html += '<input data-type="longitude_' + cloneNumber + '" type="hidden" name="longitude_' + cloneNumber + '" value="<?php echo $longitude; ?>">';
        html += '<input type="text" class="form-control" name="street1_' + cloneNumber + '" id="street1_' + cloneNumber + '" value="">';
        html += '<input type="text" class="form-control" name="city_' + cloneNumber + '" id="city_' + cloneNumber + '" value="">';
        html += '<input type="text" class="form-control" name="state_' + cloneNumber + '" id="state_' + cloneNumber + '" value="">';
        html += '<input type="text" class="form-control" name="zip_code_' + cloneNumber + '" id="zip_code_' + cloneNumber + '" value="">';
        html += '<input type="text" class="form-control" name="place_id_' + cloneNumber + '" id="place_id_' + cloneNumber + '" value="">';
        html += '</div>';
        html += '<div class="col-md-1 product-selection-remove-prod-btn">';
        html += '<div class="form-group">';
        html += '<div>';
        html += '<button type="button" class="btn btn-danger btn-icon mall_selection_remove_btn" data-clone-number="' + cloneNumber + '"><i class="icon-cross3"></i></button>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        return html;
    }

    var labels = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    var labelIndex = 0;

    function initMap() {
        var myLatlng = new google.maps.LatLng(-25.363, 131.044);
        var myOptions = {
            zoom: 13,
            center: myLatlng
        }
        var map = new google.maps.Map(document.getElementById('map-canvas'), myOptions);
        var geocoder = new google.maps.Geocoder();

        google.maps.event.addListener(map, 'click', function (event) {
            geocoder.geocode({
                'latLng': event.latLng
            }, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {


                    var countryId = $(document).find('#id_country').val();
                    $.ajax({
                        method: 'POST',
                        url: base_url + 'storeregistration/show_mall',
                        data: {country_id: countryId},
                        success: function (response) {
                            $(document).find('.mall_selection_dropdown').html(response);
                        },
                        error: function () {
                            console.log("error occur");
                        },
                    });
                    var html = generatemallSelectionBlock(mallCloneNumber);
                    $(document).find('#mall_selection_wrapper').append(html);
                    mallCloneNumber++;

                    reInitializeSelect2Control();
//    initAutocomplete();
                    $(document).find('#location_count').val(mallCloneNumber);

                    if (results[0]) {
                        var currect_char = addMarker(event.latLng, map);
                        var latitude = results[0].geometry.location.lat();
                        var longitude = results[0].geometry.location.lng();
                        var place = results[0];
                        fillInAddress(results, currect_char);
                    }
                }
            });
        });

        // Adds a marker to the map.
        function addMarker(location, map) {
            // Add the marker at the clicked location, and add the next-available label
            // from the array of alphabetical characters.
            var current_character = labels[labelIndex++ % labels.length]
            var marker = new google.maps.Marker({
                position: location,
                label: current_character,
                map: map
            });
//            return current_character + '-' + (labelIndex - 1);
            return labelIndex - 1;
        }
    }

    function fillInAddress(place, control_number) {
        var componentForm = {
//            street_number: 'long_name',
//            route: 'long_name',
            sublocality_level_1: 'long_name',
            locality: 'long_name',
            administrative_area_level_1: 'short_name',
            postal_code: 'short_name',
        };
        var formFields = {
//            street_number: 'google_input',
//            route: 'google_input',
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
        console.log(place);
        for (var field in formFields) {
//            document.getElementById(formFields[field] + '_' + control_number).value = '';
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
                    } else if (addressType !== 'locality') {
                        console.log(addressType);
                        document.getElementById(formFields[addressType] + '_' + control_number).value = val;
                    }

                    if (place.place_id != '') {
                        console.log(place.place_id);
                        document.getElementById('place_id_' + control_number).value = place.place_id;
                    }
                }
            }
        }
    }
</script>
<!--<script src="https://maps.googleapis.com/maps/api/js?key=<?php // echo GOOGLE_API_KEY                          ?>&libraries=places&callback=initAutocomplete" async defer></script>-->
