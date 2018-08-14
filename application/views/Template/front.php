<!DOCTYPE html>
<html lang="en">
    <head>
        <?php
        $this->load->view('Template/Frontpanel/head');
        ?>
        <script>
            var base_url = '<?php echo SITEURL; ?>';
        </script>
    </head>
    <body>
        <div id="site_wrapper">
            <div id="site_wrapper">            
                <div class="panel panel-flat panel_reg ">
                    <div class="panel_top">
                        <a href="<?php echo SITEURL; ?>">
                            <?php $this->load->view('svg_html/top_header_logo'); ?>
                        </a>
                    </div>

                    <div class="panel-body">
                        <div class="form_body_wrapper">
                            <div class="form_head"><h2><?php echo @$sub_header; ?></h2></div>
                            <div class="reg_wrapper">
                                <?php if ($this->session->flashdata('error_msg')) { ?>
                                    <div class="alert alert-danger alert-styled-right alert-bordered">
                                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
                                        <?php echo $this->session->flashdata('error_msg'); ?>
                                    </div>
                                <?php } ?>

                                <?php if (validation_errors()) { ?>
                                    <div class="alert alert-danger alert-styled-right alert-bordered">
                                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
                                        <?php echo validation_errors(); ?>
                                    </div>
                                <?php } ?>

                                <?php if ($this->session->flashdata('success_msg')) { ?>
                                    <div class="alert alert-success alert-styled-right alert-arrow-right alert-bordered">
                                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                                        <?php echo $this->session->flashdata('success_msg') ?>
                                    </div>
                                <?php } ?>

                                <?php echo $body; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript" src="assets/user/js/plugins/forms/selects/select2.min.js"></script>
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
