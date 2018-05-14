<div class="content-wrapper">
    <!-- Content area -->
    <div class="content">
        <!-- Password recovery -->
        <form action="" method="POST" class="form-validate-jquery">
            <div class="panel panel-body login-form">
                <div class="text-center">                    
                    <h5 class="content-group"><?php echo $page_header ?></h5>
                </div>
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

                <div class="form-group has-feedback">
                    <input type="password" id="password" name="password" minlength="5" class="form-control" placeholder="New password" required="required" autocomplete="off">
                    <div class="form-control-feedback">
                        <i class="icon-user-lock text-muted"></i>
                    </div>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" id="confirm_password" name="confirm_password" minlength="5" equalTo="#password" class="form-control" placeholder="Confirm password" required="required" autocomplete="off">
                    <div class="form-control-feedback">
                        <i class="icon-user-lock text-muted"></i>
                    </div>
                </div>
                <button type="submit" class="btn bg-blue btn-block"><?php echo $page_header; ?> <i class="icon-arrow-right14 position-right"></i></button>
            </div>
        </form>
        <!-- /password recovery -->


        <!-- Footer -->
        <div class="footer text-muted text-center">
            &copy; <?php echo date('Y') ?>. <a href="<?php echo SITEURL; ?>"><?php echo SITENAME; ?></a>
        </div>
        <!-- /footer -->
    </div>
</div>