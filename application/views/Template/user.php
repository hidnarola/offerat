<!DOCTYPE html>
<html lang="en">
    <head>
        <?php
        $this->load->view('Template/Userpanel/head');
        ?>
        <!-- Core JS files -->
        <!-- /Core JS files -->
    </head>

    <body>
        <div id="site_wrapper">
            <!--            <div id="loader" class="display-none" style="padding-top: 350px;">
                            <i class="icon-spinner9 spinner fa-5x"></i>
                        </div>-->

            <!-- Main navbar -->
            <?php
            $this->load->view('Template/Userpanel/navbar');
            ?>
            <!-- /Main navbar -->

            <div class="page-container">
                <div class="page-content">

                    <!-- Main sidebar -->
                    <?php
                    $this->load->view('Template/Userpanel/sidebar');
                    ?>
                    <!-- /main sidebar -->
                    <div class="content-wrapper"> 

                        <?php
                        $controller = strtolower($this->uri->segment(2));
                        if ($controller != 'dashboard') {
                            ?>
                            <div class="page-header page-header-default">
                                <div class="page-header-content">
                                    <div class="page-title">
                                        <h4><span class="text-semibold"><?php echo $page_header ?></span></h4>
                                    </div>

                                    <?php
                                    if ($this->session->flashdata('success_msg')) {
                                        ?>
                                        <div class="alert alert-success alert-styled-right alert-arrow-right alert-bordered">
                                            <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                                            <?php echo $this->session->flashdata('success_msg') ?>
                                        </div>
                                        <?php
                                    }

                                    if ($this->session->flashdata('warning_msg')) {
                                        ?>
                                        <div class="alert alert-warning alert-styled-right alert-arrow-right alert-bordered">
                                            <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                                            <?php echo $this->session->flashdata('warning_msg') ?>
                                        </div>
                                        <?php
                                    }

                                    if ($this->session->flashdata('error_msg')) {
                                        ?>
                                        <div class="alert alert-danger alert-styled-right alert-arrow-right alert-bordered">
                                            <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                                            <?php echo $this->session->flashdata('error_msg') ?>
                                        </div>
                                        <?php
                                    }

                                    if ($this->session->flashdata('info_msg')) {
                                        ?>
                                        <div class="alert alert-info alert-styled-right alert-arrow-right alert-bordered">
                                            <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                                            <?php echo $this->session->flashdata('info_msg') ?>
                                        </div>
                                        <?php
                                    }

                                    if (!empty(validation_errors())) {
                                        ?>
                                        <div class="alert alert-danger alert-bordered">
                                            <?php
                                            echo validation_errors();
                                            ?>
                                        </div>        
                                        <?php
                                    }

                                    if (isset($image_errors)) {
                                        ?>
                                        <div class="alert alert-danger alert-bordered">
                                            <?php
                                            echo $image_errors;
                                            ?>
                                        </div>        
                                        <?php
                                    }

                                    if (isset($file_errors)) {
                                        ?>
                                        <div class="alert alert-danger alert-bordered">
                                            <?php
                                            echo $file_errors;
                                            ?>
                                        </div>        
                                        <?php
                                    }
                                    ?>
                                </div>
                                <?php if (isset($this->bread_crum)) { ?>
                                    <div class="breadcrumb-line">
                                        <ul class="breadcrumb">
                                            <?php
                                            $bread_crum_count = count($this->bread_crum);
                                            foreach ($this->bread_crum as $index => $bread_crum) {
                                                if (($bread_crum_count - 1) != $index && !empty($bread_crum['url'])) {
                                                    ?>
                                                    <li><a href="<?php echo $bread_crum['url'] ?>"><?php echo $bread_crum['title'] ?></a></li>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <li class="active"><?php echo $bread_crum['title'] ?></li>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>


                        <div class="content">
                            <?php echo $body; ?>
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
            <script type="text/javascript" src="assets/user/js/plugins/pickers/anytime.min.js"></script>
            <script type="text/javascript" src="assets/user/js/plugins/pickers/pickadate/picker.js"></script>
            <script type="text/javascript" src="assets/user/js/plugins/pickers/pickadate/picker.date.js"></script>
            <script type="text/javascript" src="assets/user/js/plugins/pickers/daterangepicker.js"></script>
            <!--Date Picker End-->

            <script type="text/javascript" src="assets/user/js/plugins/forms/inputs/maxlength.min.js"></script>

            <script type="text/javascript" src="assets/user/js/plugins/forms/validation/validate.min.js"></script>

            <script type="text/javascript" src="assets/user/js/plugins/uploaders/fileinput.min.js"></script>

            <!--Checkbox Start-->
            <script type="text/javascript" src="assets/user/js/plugins/forms/styling/uniform.min.js"></script>
            <!--<script type="text/javascript" src="https://momentjs.com/downloads/moment.min.js"></script>-->
<!--            <script type="text/javascript" src="https://momentjs.com/downloads/moment-timezone.min.js"></script>
            <script type="text/javascript" src="https://momentjs.com/downloads/moment-timezone-with-data.min.js"></script>
            <script type="text/javascript" src="https://www.malot.fr/bootstrap-datetimepicker/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js?t=20130302"></script>-->
            <script type="text/javascript" src="assets/user/js/moment-timezone.min.js"></script>
            <script type="text/javascript" src="assets/user/js/moment-timezone-with-data.min.js"></script>
            <script type="text/javascript" src="assets/user/js/bootstrap-datetimepicker.min.js"></script>
            <!--Checkbox End-->

            <script type="text/javascript" src="assets/user/js/custom.js"></script>
            <script type="text/javascript">

                function get_dd_mm_yyyy_Date(date, seperator) {
                    if (date === null) {
                        return '';
                    }
                    var d = new Date(date),
                            month = '' + (d.getMonth() + 1),
                            day = '' + d.getDate(),
                            year = d.getFullYear();
                    if (month.length < 2)
                        month = '0' + month;
                    if (day.length < 2)
                        day = '0' + day;
                    return day + seperator + month + seperator + year;
                }

                function get_dd_mm_yyyy_hh_min_DateTime(date, seperator) {
                    if (date === null) {
                        return '';
                    }
                    var date_ = moment.tz(moment.utc(date), "<?php echo $this->loggedin_user_country_data['timezone']; ?>");
                    return date_.format("DD-MM-YYYY HH:mm");
                }

                $('#expire_date_icon, #expire_date_time').click(function (e) {
                    $('#expire_date_time').AnyTime_noPicker().AnyTime_picker({format: "%d-%m-%Z %H:%i"}).focus();
                    e.preventDefault();
                });

                $('#broad_cast_icon, #broad_cast_date_time').click(function (e) {
                    $('#broad_cast_date_time').AnyTime_noPicker().AnyTime_picker({format: "%d-%m-%Z %H:%i"}).focus();
                    e.preventDefault();
                });
            </script>    

        </div>
    </body>
</html>
