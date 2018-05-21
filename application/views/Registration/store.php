<!DOCTYPE html>
<html lang="en">
    <head>
        <?php
        $this->load->view('Template/head');
        ?>
        <link href="assets/front/css/custom.css" rel="stylesheet" type="text/css">
        <!-- Core JS files -->
        <!-- /Core JS files -->
    </head>
    <body>

        <div id="site_wrapper">            
            <div class="panel panel-flat panel_reg ">
                <div class="panel_top">
                    <a href="<?php echo SITEURL; ?>">
                        <?php $this->load->view('svg_html/top_header_logo'); ?>
                    </a>
                </div>

                <div class="panel-body">
                    <div class="form_body_wrapper">
                        <div class="form_head"><h2>Add New Store</h2></div>
                        <div class="reg_wrapper">
                            <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
                                <fieldset class="content-group">                                                
                                    <legend class="text-bold">Store Info.</legend>

                                    <div class="form-group">                                        
                                        <label class="control-label col-lg-2">Store Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="store_name" id="store_name"  placeholder="Store Name" required="required">                                        
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-lg-2">Website URL</label>
                                        <input type="text" class="form-control" name="website" id="website"  placeholder="Website URL">
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-lg-2">Facebook Page</label>
                                        <input type="text" class="form-control" name="facebook_page" id="facebook_page"  placeholder="Facebook Page URL">
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-lg-2">Logo</label>
                                        <input type="file" class="form-control" name="store_logo" id="store_logo">
                                    </div>

                                    <legend class="text-bold">Personal Info.</legend>

                                    <div class="form-group">
                                        <label class="control-label col-lg-2">Contact Person <span class="text-danger">*</span></label>
                                        <div class="width_50 first">
                                            <input type="text" class="form-control" name="first_name" id="first_name"  placeholder="First Name"  required="required">
                                        </div>
                                        <div class="width_50 last">
                                            <input type="text" class="form-control" name="last_name" id="last_name"  placeholder="Last Name"  required="required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-lg-2">Email Address <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="email_id" id="email_id"  placeholder="Email Address"  required="required">
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-lg-2">Telephone <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="telephone" id="telephone"  placeholder="Telephone Number"  required="required">
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-lg-2">Mobile <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="mobile" id="mobile"  placeholder="Mobile Number"  required="required">
                                    </div>


                                    <legend class="text-bold">Business</legend>

                                    <div class="">
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
                                                            <select id="sub_category_0" name="sub_category_0" class="select sub_category_selection_dropdown form-control" data-clone-number="0" required="required">
                                                                <option value="">Select Sub Category</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <legend class="text-bold">Location</legend>

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
                                                <button id="mall_selection_btn" type="button" class="pull-right margin-left-5 btn-primary labeled"><b><i class="icon-plus22"></i></b>Add More Location</button>
                                            </div> 
                                        </div>
                                    </div> 
                                    <div id="mall_selection_wrapper" class="clear-float row_add_div">  
                                        <div id="mall_selection_block_0" data-clone-number="0" class="clear-float">

                                            <div class="col-md-12 business_category_div">

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <div>
                                                            <select id="mall_0" name="mall_0" class="select mall_selection_dropdown form-control" data-clone-number="0" required="required">
                                                                <option value="0">Only Shop</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-7">
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
                                                            <input data-latitude="latitude_0" data-longitude="longitude_0" required="required" data-type="googleMap" data-zoom="10" data-lat="<?php echo $latitude; ?>" data-lang="<?php echo $longitude; ?>" data-input_id="google_input_0" id="google_input_0" type="text" class="form-control" name="address_0"  placeholder="Address Line 1" aria-required="true" value="" data-clone-number="0" />
                                                            <input data-type="latitude_0" type="hidden" name="latitude_0" value="<?php echo $latitude; ?>">
                                                            <input data-type="longitude_0" type="hidden" name="longitude_0" value="<?php echo $longitude; ?>">
                                                            <input type="hidden" class="form-control" name="street1_0" id="street1_0" value="">                                                            
                                                            <input type="hidden" class="form-control" name="city_0" id="city_0" value="">                                                            
                                                            <input type="hidden" class="form-control" name="state_0" id="state_0" value="">                                                            
                                                            <input type="hidden" class="form-control" name="zip_code_0" id="zip_code_0" value="">
                                                            <input type="hidden" class="form-control" name="place_id_0" id="place_id_0" value="">

                                                            <span class="message_note"></span>
                                                            <span class="message_error1" id="address_error"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group checkbox_reg">
                                        <input type="checkbox" class="styled-checkbox-1" name="send_verification_email" required="required"/>
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
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </div>            

            <!--<script type="text/javascript" src="assets/user/js/core/app.js"></script>-->
    <script type="text/javascript" src="assets/user/js/plugins/forms/selects/select2.min.js"></script>
    <!--Data table Start-->

    <script type="text/javascript" src="assets/user/js/plugins/tables/datatables/datatables.min.js"></script>

    <script type="text/javascript" src="assets/user/js/plugins/tables/datatables/dataTables.fixedColumns.min.js"></script>
    <!--/Data table End-->

    <!--Date Picker Start-->
    <script type="text/javascript" src="assets/user/js/plugins/ui/moment/moment.min.js"></script>
    <script type="text/javascript" src="assets/user/js/plugins/pickers/pickadate/picker.js"></script>
    <script type="text/javascript" src="assets/user/js/plugins/pickers/pickadate/picker.date.js"></script>
    <script type="text/javascript" src="assets/user/js/plugins/pickers/daterangepicker.js"></script>
    <!--Date Picker End-->

    <script type="text/javascript" src="assets/user/js/plugins/forms/inputs/maxlength.min.js"></script>

    <script type="text/javascript" src="assets/user/js/plugins/forms/validation/validate.min.js"></script>

    <script type="text/javascript" src="assets/user/js/plugins/uploaders/fileinput.min.js"></script>

    <!--Checkbox Start-->
    <script type="text/javascript" src="assets/user/js/plugins/forms/styling/uniform.min.js"></script>
    <!--Checkbox End-->

    <script type="text/javascript" src="assets/front/js/custom.js"></script>

</body>
</html>
<script src="//maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_API_KEY ?>&libraries=places&callback=initAutocomplete" async defer></script>
<script>
    $(function () {
        jqueryValidate();
    });

    var categoryCloneNumber = 1;
    var mallCloneNumber = 1;

    $(document).on('click', '#category_selection_btn', function () {
        var html = generatecategorySelectionBlock(categoryCloneNumber);
        $(document).find('#category_selection_wrapper').append(html);
        categoryCloneNumber++;
        reInitializeSelect2Control();
        $(document).find('#category_count').val(categoryCloneNumber);
    });

    $(document).on('click', '#mall_selection_btn', function () {
        var html = generatemallSelectionBlock(mallCloneNumber);
        $(document).find('#mall_selection_wrapper').append(html);
        mallCloneNumber++;
        reInitializeSelect2Control();
        initAutocomplete();
        $(document).find('#location_count').val(mallCloneNumber);
    });

    $(document).on('click', '.category_selection_remove_btn', function () {
        var cloneNumber = $(this).data('cloneNumber');
        $(document).find('#category_selection_block_' + cloneNumber).remove();
        $(document).find('#category_count').val(cloneNumber);
    });

    $(document).on('click', '.mall_selection_remove_btn', function () {
        var cloneNumber = $(this).data('cloneNumber');
        $(document).find('#mall_selection_block_' + cloneNumber).remove();
        $(document).find('#location_count').val(cloneNumber);
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

        html += '<div class="col-md-5">';
        html += '<select id="sub_category_' + cloneNumber + '" name="sub_category_' + cloneNumber + '" class="select sub_category_selection_dropdown form-control" data-clone-number="' + cloneNumber + '" required="required">';
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
        html += '<input data-latitude="latitude_' + cloneNumber + '" data-longitude="longitude_' + cloneNumber + '" required="required" data-type="googleMap" data-zoom="10" data-lat="<?php echo $latitude; ?>" data-lang="<?php echo $longitude; ?>" data-input_id="google_input_' + cloneNumber + '" id="google_input_' + cloneNumber + '" type="text" class="form-control" name="address_' + cloneNumber + '"  placeholder="Address Line 1" aria-required="true" value="" data-clone-number="' + cloneNumber + '">';
        html += '<input data-type="latitude_' + cloneNumber + '" type="hidden" name="latitude[]" value="<?php echo $latitude; ?>">';
        html += '<input data-type="longitude_' + cloneNumber + '" type="hidden" name="longitude[]" value="<?php echo $longitude; ?>">';
        html += '<input type="hidden" class="form-control" name="street1_' + cloneNumber + '" id="street1_' + cloneNumber + '" value="">';
        html += '<input type="hidden" class="form-control" name="city_' + cloneNumber + '" id="city_' + cloneNumber + '" value="">';
        html += '<input type="hidden" class="form-control" name="state_' + cloneNumber + '" id="state_' + cloneNumber + '" value="">';
        html += '<input type="hidden" class="form-control" name="zip_code_' + cloneNumber + '" id="zip_code_' + cloneNumber + '" value="">';
        html += '<input type="hidden" class="form-control" name="place_id_' + cloneNumber + '" id="place_id_' + cloneNumber + '" value="">';
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

    $(document).on('change', '.category_selection_dropdown', function () {
        var cloneNumber = $(this).data('cloneNumber');
        var sender = $(this);
        var categoryId = sender.val();
        $.ajax({
            method: 'POST',
            url: '<?php echo SITEURL; ?>storeregistration/show_sub_category',
            data: {category_id: categoryId},
            success: function (response) {
                $(document).find('#sub_category_' + cloneNumber).val(0).trigger("change");
                $(document).find('#sub_category_' + cloneNumber).html(response);
            },
            error: function () {
                console.log("error occur");
            },
        });
    });

    $(document).on('change', '#id_country', function () {
        var sender = $(this);
        var countryId = sender.val();
        $.ajax({
            method: 'POST',
            url: '<?php echo SITEURL; ?>storeregistration/show_mall',
            data: {country_id: countryId},
            success: function (response) {
                $(document).find('.mall_selection_dropdown').html(response);
            },
            error: function () {
                console.log("error occur");
            },
        });

    });
    function reInitializeSelect2Control() {
        $('.select').select2({
            minimumResultsForSearch: Infinity,
        });
    }

    function initAutocomplete() {
        $('[data-type="googleMap"]').each(function () {
            var currentThis = $(this);
            var control_number = currentThis.data('clone-number');
            console.log(currentThis);
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
            street_number: 'google_input',
            route: 'google_input',
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
        console.log(place.place_id);
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

    //    function googleLocationIssuePrompt() {
    //        var title = 'Information';
    //        var data_message = 'Your entered address is not able to track on google so please enter your address manually.';
    //        $(document).find('#title_info').html(title);
    //        $(document).find('#info_message').html(data_message);
    //        $(document).find("#alert_info").modal('show');
    //    }

    $(document).ready(function () {
        $(document).on('change', '[data-type="reloadMap"]', initAutocomplete);
    });

</script>
