<form id="backend_login" action="" method="post" class="form-validate-jquery form_login_wrapper">
    <h5 class="content-group"><?php echo $page_header; ?></h5>
    <div class="panel panel-body login-form">
        <?php $this->load->view('Login/messages'); ?>
        <p>To reset your password, Please enter the email associated with your Offerat account.</p>
        <div id="login_email_wrapper" class="form-group has-feedback has-feedback-left">
            <input type="email" name="email" id="email" value="<?php echo set_value('email') ?>" class="form-control" placeholder="Email" required="required">                    
            <div class="form-control-feedback">
                <i class="icon-user text-muted"></i>
                <span class="text-danger">*</span>
            </div>
        </div>                        
        <div class="form-group">     
            <a href="login" class="f_pass">I already know my password</a>
            <button type="submit" class="btn btn-primary btn-block">Reset password <i class="icon-circle-right2 position-right"></i></button>
        </div>

    </div>
</form>
<script type="text/javascript">
    $(function () {
        jqueryValidate();
    });
</script>