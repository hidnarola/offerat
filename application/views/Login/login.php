
<!-- Simple login form -->
<form id="backend_login" action="" method="post" class="form-validate-jquery form_login_wrapper">
    <h5 class="content-group"><?php echo $page_header; ?></h5>
    <div class="panel panel-body login-form">
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
        <div id="login_email_wrapper" class="form-group has-feedback has-feedback-left">
            <input type="email" name="email" id="email" value="<?php echo set_value('email') ?>" class="form-control" placeholder="Email" required="required">                    
            <div class="form-control-feedback">
                <i class="icon-user text-muted"></i>
                <span class="text-danger">*</span>
            </div>
        </div>                
        <div class="form-group has-feedback has-feedback-left">
            <input type="password" name="password" id="password" class="form-control" placeholder="Password" required="required" autocomplete="off">
            <div class="form-control-feedback">
                <i class="icon-lock2 text-muted"></i>
                <span class="text-danger">*</span>
            </div>
        </div>
        <div class="form-group">
            <a href="reset-password" class="f_pass">Forgot password?</a>
            <button type="submit" class="btn btn-primary btn-block">Sign in <i class="icon-circle-right2 position-right"></i></button>
        </div>

    </div>
</form>
<!-- /simple login form -->



<script type="text/javascript">
//    $(function () {
//        jqueryValidate();
//    });
</script>