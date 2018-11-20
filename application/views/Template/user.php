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
            <div id="loader" class="display-none">
                <i class="icon-spinner9 spinner fa-5x"></i>
            </div>
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
//                        if ($controller == 'dashboard') {
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
                        <?php // } ?>
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
            <script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
            <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
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
            <script type="text/javascript" src="assets/user/js/bootstrap-datetimepicker.min.js"></script>
            <!--Checkbox End-->

            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.2.5/jquery.fancybox.min.css" />
            <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.2.5/jquery.fancybox.min.js"></script>

            <script type="text/javascript" src="assets/user/js/custom.js"></script>
            <script type="text/javascript">
                $("#expire_date_delete_icon").click(function (e) {
                    $("#expire_date_time").val("").change();
                });
                $('#expire_date_icon, #expire_date_time').click(function (e) {
//                    $('#expire_date_time').AnyTime_noPicker().AnyTime_picker({format: "%d-%m-%Z"}).focus();
                    $('#expire_date_time').AnyTime_noPicker().AnyTime_picker({format: "%d-%m-%Z %H:%i"}).focus();
//                    $('#expire_date_time').AnyTime_noPicker().AnyTime_picker({format: "%d-%m-%Z 23:59"}).focus();
//                    $(document).find('.AnyTime-min1-btn, .AnyTime-min2-btn, .AnyTime-min3-btn, .AnyTime-min4-btn, .AnyTime-min5-btn, .AnyTime-min6-btn, .AnyTime-min7-btn, .AnyTime-min8-btn, .AnyTime-min9-btn ').remove()
                });
                $('#broad_cast_icon, #broad_cast_date_time').click(function (e) {
                    $('#broad_cast_date_time').AnyTime_noPicker().AnyTime_picker({format: "%d-%m-%Z %H:%i"}).focus();
//                    $(document).find('.AnyTime-min1-btn, .AnyTime-min2-btn, .AnyTime-min3-btn, .AnyTime-min4-btn, .AnyTime-min5-btn, .AnyTime-min6-btn, .AnyTime-min7-btn, .AnyTime-min8-btn, .AnyTime-min9-btn ').remove()
                    e.preventDefault();
                });

                $('.daterange-from-to').daterangepicker({
                    applyClass: 'bg-slate-600',
                    cancelClass: 'btn-default',
                    showDropdowns: false,
                    selectYears: 0,
                    locale: {
                        format: 'DD-MM-YYYY'
                    }
                });
                $('.daterange-from-to').on('cancel.daterangepicker', function (ev, picker) {
                    $(this).val('');
                });
                $('.daterange-from-to').on('hide.daterangepicker', function (ev, picker) {
                    var defaultValue = ev.currentTarget.defaultValue;
                    $(this).val(defaultValue);
                });

                $('.daterange-from-to').on('apply.daterangepicker', function (ev, picker) {
                    $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
                });

<?php
$remove_values = $this->session->userdata('remove_values');
if (isset($remove_values) && sizeof($remove_values) > 0) {
    foreach ($remove_values as $val) {
        ?>
                        $(document).find('#from_to_date_<?php echo $val; ?>').val("");
        <?php
    }
}
?>
            </script>
        </div>
    </body>
</html>
