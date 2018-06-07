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
                <input type="text" class="form-control" name="website" id="website"  placeholder="Website URL" value="<?php echo set_value('website'); ?>">
            </div>

            <div class="form-group">
                <label class="control-label col-lg-2">Facebook Page</label>
                <input type="text" class="form-control" name="facebook_page" id="facebook_page"  placeholder="Facebook Page URL" value="<?php echo set_value('facebook_page'); ?>">
            </div>

            <div class="form-group">
                <label class="control-label col-lg-2">Logo <span class="text-danger">*</span></label>
                <input type="file" class="form-control file-input" name="store_logo" id="store_logo" required="required">
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

            <div class="form-group">
                <label class="control-label col-lg-2">Contact Number <span class="text-danger">*</span></label>
                <div class="width_50 first">
                    <input type="text" class="form-control" name="telephone" id="telephone"  placeholder="Contact Number"  required="required" value="<?php echo set_value('telephone'); ?>">
                </div>
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

                        <div class="col-md-5 sub_cat_section_0">
                            <select id="sub_category_0" name="sub_category_0" class="select sub_category_selection_dropdown form-control display-none" data-clone-number="0">
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
        <div class="col-lg-6 col_mobile_pad">
            <div class="form-group">
                <label class="control-label col-lg-2">Country <span class="text-danger">*</span></label>

                <?php if (isset($country_list) && sizeof($country_list) > 0) { ?>
                    <select class="form-control" name="id_country" id="id_country" required="required">                                            
                        <option value="">Select Country</option>
                        <?php foreach ($country_list as $list) { ?>
                            <option value="<?php echo $list['id_country']; ?>"><?php echo $list['country_name']; ?></option>
                        <?php } ?>
                    </select>
                <?php } ?>


            </div> 
        </div>
        <div id="mall_selection_wrapper" class="clear-float row_add_div">  
            <div class="clear-float">
                <div class="col-md-12 map_div">
                    <div id="map-canvas"></div>
                </div>
                <div class="col-md-6 business_location_div">
                    <?php
                    $latitude = '54.6960513';
                    $longitude = '-113.7297772';
                    $latitude = '';
                    $longitude = '';
                    ?>

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
<!--<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_API_KEY ?>&callback=initMap" async defer></script>-->

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

    function generatemallSelectionBlock(cloneNumber) {
        var html = '';
        html += '<div id="mall_selection_block_' + cloneNumber + '" data-clone-number="' + cloneNumber + '" class="clear-float">';
        html += '<div class="col-sm-12">';
        html += '<div class="col-sm-4">';
        html += '<div class="form-group">';
        html += '<div>';
        html += '<select id="mall_' + cloneNumber + '" name="mall_' + cloneNumber + '" class="select mall_selection_dropdown form-control" data-clone-number="' + cloneNumber + '" required="required">';
        html += '<option value="0">Only Shop</option>';
        html += '</select>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '<div class="col-sm-7">';
        html += '<span class="label label-flat border-success text-brown-600 label-icon" id="location_letter_' + cloneNumber + '"></span>';
        html += '<label class="label border-left-success label-striped location_label" id="location_' + cloneNumber + '"></label>';
        html += '<input type="hidden" data-latitude="latitude_' + cloneNumber + '" data-longitude="longitude_' + cloneNumber + '" required="required" data-type="googleMap" data-zoom="10" data-lat="<?php echo $latitude; ?>" data-lang="<?php echo $longitude; ?>" data-input_id="google_input_' + cloneNumber + '" id="google_input_' + cloneNumber + '" type="text" class="form-control" name="address_' + cloneNumber + '"  placeholder="Location" aria-required="true" value="" data-clone-number="' + cloneNumber + '">';
        html += '<input type="hidden" class="form-control" data-type="latitude_' + cloneNumber + '" name="latitude_' + cloneNumber + '" id="latitude_' + cloneNumber + '" value="<?php echo $latitude; ?>">';
        html += '<input type="hidden" class="form-control" data-type="longitude_' + cloneNumber + '" name="longitude_' + cloneNumber + '" id="longitude_' + cloneNumber + '" value="<?php echo $longitude; ?>">';
        html += '<input type="hidden" class="form-control" name="street_' + cloneNumber + '" id="street_' + cloneNumber + '" value="">';
        html += '<input type="hidden" class="form-control" name="street1_' + cloneNumber + '" id="street1_' + cloneNumber + '" value="">';
        html += '<input type="hidden" class="form-control" name="city_' + cloneNumber + '" id="city_' + cloneNumber + '" value="">';
        html += '<input type="hidden" class="form-control" name="state_' + cloneNumber + '" id="state_' + cloneNumber + '" value="">';
        html += '<input type="hidden" class="form-control" name="zip_code_' + cloneNumber + '" id="zip_code_' + cloneNumber + '" value="">';
        html += '<input type="hidden" class="form-control" name="place_id_' + cloneNumber + '" id="place_id_' + cloneNumber + '" value="">';
        html += '</div>';
        html += '<div class="col-sm-1 product-selection-remove-prod-btn">';
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

    var labels = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    var labelIndex = 0;
    var markers = [];
    var minZoomLevel = 17;

    var latitude = 20.593684;
    var longitude = 78.96288;
    var southWestLatitude = 12.97232;
    var southWestLongitude = 77.59480;
    var northEastLatitude = 12.89201;
    var northEastLongitude = 77.58905;

    function initMap() {
        console.log(latitude);
        var myLatlng = new google.maps.LatLng(latitude, longitude);

        var myOptions = {
            zoom: 4,
            center: new google.maps.LatLng(latitude, longitude)
        }
        var map = new google.maps.Map(document.getElementById('map-canvas'), myOptions);

        var geocoder = new google.maps.Geocoder();

        var southWest = new google.maps.LatLng(southWestLatitude, southWestLongitude);
        var northEast = new google.maps.LatLng(northEastLatitude, northEastLongitude);
        // Bounds for North America
        var strictBounds = new google.maps.LatLngBounds(southWest, northEast);

        // Listen for the dragend event
        google.maps.event.addListener(map, 'dragend', function () {
            if (strictBounds.contains(map.getCenter()))
                return;

            // We're out of bounds - Move the map back within the bounds
//
//            var c = map.getCenter(),
//                    x = c.lng(),
//                    y = c.lat(),
//                    maxX = strictBounds.getNorthEast().lng(),
//                    maxY = strictBounds.getNorthEast().lat(),
//                    minX = strictBounds.getSouthWest().lng(),
//                    minY = strictBounds.getSouthWest().lat();
//
//            if (x < minX)
//                x = minX;
//            if (x > maxX)
//                x = maxX;
//            if (y < minY)
//                y = minY;
//            if (y > maxY)
//                y = maxY;
//
//            map.setCenter(new google.maps.LatLng(y, x));
        });

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
                    $(document).find('.business_location_div').append(html);
                    mallCloneNumber++;
                    reInitializeSelect2Control();
                    $(document).find('#location_count').val(mallCloneNumber);
                    if (results[0]) {
                        var currect_char = addMarker(event.latLng, map);
                        
                        console.log(results[0]);
                        fillInAddress(results, currect_char);
                    }

                    $(document).find('.map_div').addClass('col-md-6');
                    $(document).find('.map_div').removeClass('col-md-12');
                }
            });
        });
    }

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
        markers.push(marker);
//            return current_character + '-' + (labelIndex - 1);
        return labelIndex - 1;
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
            var current_character = labels[labelIndex % labels.length - 1];
            $(document).find('#location_letter_' + control_number).text(current_character);
            $(document).find('#mall_selection_remove_btn_' + control_number).attr('character', current_character);
            $(document).find('#location_' + control_number).text(place.formatted_address);
            document.getElementById('google_input_' + control_number).value = place.formatted_address;
            document.getElementById('latitude_' + control_number).value = place.geometry.location.lat();
            document.getElementById('longitude_' + control_number).value = place.geometry.location.lng();
        }
    }
    $(document).find('.sub_cat_section_0').hide();
</script>
<script src="https://maps.googleapis.com/maps/api/js?libraries=geometry,places&key=<?php echo GOOGLE_API_KEY ?>&callback=initMap" async defer></script>
<!--<script src="https://maps.googleapis.com/maps/api/js?key=<?php // echo GOOGLE_API_KEY                                         ?>&libraries=places&callback=initAutocomplete" async defer></script>-->
