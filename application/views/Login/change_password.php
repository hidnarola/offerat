
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
        <div class="form-group">            
            <button type="submit" class="btn btn-primary btn-block"><?php echo $page_header; ?> <i class="icon-circle-right2 position-right"></i></button>
        </div>

    </div>
</form>
<!-- /simple login form -->



<script type="text/javascript">
//    $(function () {
//        jqueryValidate();
//    });
</script>