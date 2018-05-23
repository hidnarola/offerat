<form id="backend_login" action="" method="post" class="form-validate-jquery form_login_wrapper">
    <h5 class="content-group"><?php echo $page_header; ?></h5>
    <div class="panel panel-body login-form">
        <?php $this->load->view('Login/messages'); ?>
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
<script type="text/javascript">
    $(function () {
        jqueryValidate();
    });
</script>