<!DOCTYPE html>
<html lang="en">
    <head>
        <?php
        $this->load->view('Template/head');
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
            $this->load->view('Template/navbar');
            ?>
            <!-- /Main navbar -->

            <div class="page-container">
                <div class="page-content">

                    <!-- Main sidebar -->
                    <?php
                    $this->load->view('Template/sidebar');
                    ?>
                    <!-- /main sidebar -->
                    <div class="content-wrapper">                       
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
        </div>
    </body>
</html>
