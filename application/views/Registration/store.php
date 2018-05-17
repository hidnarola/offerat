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
                                        <div class="row">
                                            <button id="description_selection_cart_add_product_btn" type="button" class="pull-right margin-left-5 btn btn-primary btn-labeled"><b><i class="icon-plus22"></i></b>Add Other Description</button>                                                    
                                            <div id="description_selection_cart_products_selection_wrapper" class="clear-float">

                                            </div>
                                        </div>
                                    </div>


                                    <legend class="text-bold">Location</legend>
                                    <div class="form-group">
                                        <label class="control-label col-lg-2">Country</label>
                                        <select class="form-control" name="" id="">
                                        </select>

                                    </div>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <script type="text/javascript" src="assets/user/js/core/app.js"></script>
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

            <script type="text/javascript" src="assets/user/js/custom.js"></script>
        </div>
    </body>
</html>

<script>
    var descriptionCloneNumber = 0;

    $(document).on('click', '#description_selection_cart_add_product_btn', function () {
        var html = generateCartDescriptionSelectionBlock(descriptionCloneNumber);
        $(document).find('#description_selection_cart_products_selection_wrapper').append(html);
        descriptionCloneNumber++;
    });

//<editor-fold defaultstate="collapsed" desc="Static HTML generator functions">
    function generateCartDescriptionSelectionBlock(cloneNumber) {
        var html = '';
        html += '<div id="description_selection_cart_products_selection_block_' + cloneNumber + '" data-clone-number="' + cloneNumber + '" class="clear-float">';
        html += '<div class="col-md-12 business_category_div">';
        html += '<div class="col-md-5">';
        html += '<div class="form-group">';
        html += '<div>';
        html += '<select id="category_' + cloneNumber + '" name="category[id][]" class="select product_selection_dropdown form-control" data-clone-number="' + cloneNumber + '" required="required">';
        html += '<option value="">Select Category</option>';
<?php foreach ($category_list as $list) { ?>
            html += '<option value="' + '<?php echo $list['id_category']; ?>' + '">' + '<?php echo $list['category_name']; ?>' + '</option>';
<?php } ?>
        html += '</select>';
        html += '</div>';
        html += '</div>';
        html += '</div>';

        html += '<div class="col-md-5">';
        html += '<select id="sub_category_' + cloneNumber + '" name="sub_category[id][]" class="select product_selection_dropdown form-control" data-clone-number="' + cloneNumber + '" required="required">';
        html += '<option value="">Select Subcategory</option>';
        html += '</select>';
        html += '</div>';

        html += '<div class="col-md-1 product-selection-remove-prod-btn">';
        html += '<div class="form-group">';
        html += '<div>';
        html += '<button type="button" class="btn btn-danger btn-icon description_selection_cart_product_remove_btn" data-clone-number="' + cloneNumber + '"><i class="icon-cross3"></i></button>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '</div>';

        return html;
    }
//</editor-fold>
</script>