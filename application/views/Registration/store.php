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
                            <form class="form-horizontal" action="#">
                                <fieldset class="content-group">                                                
                                    <legend class="text-bold">Store Info.</legend>

                                    <div class="form-group">
                                        <label class="control-label col-lg-2">Store Name</label>
                                        <input type="text" class="form-control" name="store_name" id="store_name" placeholder="Store Name">
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
                                        <label class="control-label col-lg-2">Contact Person</label>
                                        <div class="width_50 first">
                                            <input type="text" class="form-control" name="first_name" id="first_name"  placeholder="First Name">
                                        </div>
                                        <div class="width_50 last">
                                            <input type="text" class="form-control" name="last_name" id="last_name"  placeholder="Last Name">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-lg-2">Email Address</label>
                                        <input type="text" class="form-control" name="email_id" id="email_id"  placeholder="Email Address">
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-lg-2">Telephone</label>
                                        <input type="text" class="form-control" name="telephone" id="telephone"  placeholder="Telephone Number">
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-lg-2">Mobile</label>
                                        <input type="text" class="form-control" name="mobile" id="mobile"  placeholder="Mobile Number">
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
                                                                    <select id="category_0" name="category[id][]" class="select category_selection_dropdown form-control" data-clone-number="0" required="required">
                                                                        <option value="">Select Category</option>
                                                                        <?php foreach ($category_list as $list) { ?>
                                                                            <option value="<?php echo $list['id_category']; ?>' + '"><?php echo $list['category_name']; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-5">
                                                            <select id="sub_category_0" name="sub_category[id][]" class="select sub_category_selection_dropdown form-control" data-clone-number="0" required="required">
                                                                <option value="">Select Subcategory</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <legend class="text-bold">Location</legend>

                                    <div class="form-group">
                                        <label class="control-label col-lg-2">Country</label>
                                        <div class="width_50 first">
                                            <?php if (isset($country_list) && sizeof($country_list) > 0) { ?>
                                                <select class="form-control" name="id_country" id="id_country">                                            
                                                    <option value="">Select Country</option>
                                                    <?php foreach ($country_list as $list) { ?>
                                                        <option value="<?php echo $list['id_country']; ?>"><?php echo $list['country_name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            <?php } ?>
                                        </div>
                                        <div class="width_50 last">
                                            <div class="add_desc">
                                                <button id="mall_selection_btn" type="button" class="pull-right margin-left-5 btn-primary labeled"><b><i class="icon-plus22"></i></b>Add More Category</button>
                                            </div> 
                                        </div>
                                        <div id="mall_selection_wrapper" class="clear-float row_add_div">  
                                            <div id="mall_selection_block_0" data-clone-number="0" class="clear-float">
                                                <div class="col-md-12 business_category_div">
                                                    <div class="col-md-5">
                                                        <div class="form-group">
                                                            <div>
                                                                <select id="category_0" name="mall[id][]" class="select mall_selection_dropdown form-control" data-clone-number="0" required="required">                                                                    
                                                                    <option value="">Only Shop</option>
                                                                    <?php foreach ($category_list as $list) { ?>
                                                                        <option value="<?php echo $list['id_category']; ?>"><?php echo $list['category_name']; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <input type="text" id="address_0" name="address[]" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group text-right">
                                        <input type="checkbox" class="styled-checkbox-1" name="send_verification_email">
                                        <span class="text-size-mini">  Yes, I agree with <a href="javascript:void(0);" target="_blank"><span>Terms And Conditions</span></a></span>
                                    </div>
                                    <div class="form-group">            
                                        <button type="submit" class="btn btn-primary btn-block submit_btn">Submit</button>
                                    </div>
                                </fieldset>
                            </form>
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
        </div>
    </body>
</html>

<script>

    var categoryCloneNumber = 1;
    var mallCloneNumber = 1;

    $(document).on('click', '#category_selection_btn', function () {
        var html = generatecategorySelectionBlock(categoryCloneNumber);
        $(document).find('#category_selection_wrapper').append(html);
        categoryCloneNumber++;
    });

    $(document).on('click', '#mall_selection_btn', function () {
        var html = generatemallSelectionBlock(mallCloneNumber);
        $(document).find('#mall_selection_wrapper').append(html);
        mallCloneNumber++;
    });

    $(document).on('click', '.category_selection_remove_btn', function () {
        var cloneNumber = $(this).data('cloneNumber');
        console.log("call" + cloneNumber);
        $(document).find('#category_selection_block_' + cloneNumber).remove();
    });

    $(document).on('click', '.mall_selection_remove_btn', function () {
        var cloneNumber = $(this).data('cloneNumber');
        $(document).find('#mall_selection_block_' + cloneNumber).remove();
    });

    function generatecategorySelectionBlock(cloneNumber) {
        var html = '';
        html += '<div id="category_selection_block_' + cloneNumber + '" data-clone-number="' + cloneNumber + '" class="clear-float">';
        html += '<div class="col-md-12 business_category_div">';
        html += '<div class="col-md-5">';
        html += '<div class="form-group">';
        html += '<div>';
        html += '<select id="category_' + cloneNumber + '" name="category[id][]" class="select category_selection_dropdown form-control" data-clone-number="' + cloneNumber + '" required="required">';
        html += '<option value="">Only Shop</option>';
<?php foreach ($category_list as $list) { ?>
            html += '<option value="' + '<?php echo $list['id_category']; ?>' + '">' + '<?php echo $list['category_name']; ?>' + '</option>';
<?php } ?>
        html += '</select>';
        html += '</div>';
        html += '</div>';
        html += '</div>';

        html += '<div class="col-md-5">';
        html += '<select id="sub_category_' + cloneNumber + '" name="sub_category[id][]" class="select sub_category_selection_dropdown form-control" data-clone-number="' + cloneNumber + '" required="required">';
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
        html += '<div class="col-md-5">';
        html += '<div class="form-group">';
        html += '<div>';
        html += '<select id="category_' + cloneNumber + '" name="category[id][]" class="select mall_selection_dropdown form-control" data-clone-number="' + cloneNumber + '" required="required">';
        html += '<option value="">Select Category</option>';
<?php foreach ($category_list as $list) { ?>
            html += '<option value="' + '<?php echo $list['id_category']; ?>' + '">' + '<?php echo $list['category_name']; ?>' + '</option>';
<?php } ?>
        html += '</select>';
        html += '</div>';
        html += '</div>';
        html += '</div>';

        html += '<div class="col-md-5">';
        html += '<input type="text" id="address_' + cloneNumber + '" name="address[]" class="form-control">';
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

</script>