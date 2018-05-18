
<!-- Password recovery -->
<form id="forgot_password_form" action="<?php // echo $post_url     ?>" method="POST" class="form-validate-jquery">
    <div class="panel panel-body login-form">
        <div class="text-center">                    
            <h5 class="content-group"><?php echo $page_header; ?></h5>
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
            <input type="email" name="email" class="form-control" placeholder="Your email" required="required">
            <div class="form-control-feedback">
                <i class="icon-mail5 text-muted"></i>
            </div>
        </div>
        <button type="submit" class="btn bg-blue btn-block">Reset password <i class="icon-arrow-right14 position-right"></i></button>                
    </div>
</form>
<!-- /password recovery -->    