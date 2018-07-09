<div class="col-md-12">
    <form method="POST" action="" enctype="multipart/form-data" class="form-validate-jquery" name="frm_profile" id="frm_profile">
        <div class="row">
            <div class="col-xs-12">
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <h5 class="panel-title"><i class="icon-lock2 btn-icon"></i><?php echo $page_header ?></h5>
                        <div class="heading-elements">
                            <ul class="icons-list">
                                <li><a data-action="collapse"></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="col-xs-12">
                            <div class="col-md-12">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Current Password <span class="text-danger">*</span></label>
                                        <div>
                                            <input type="password" class="form-control" placeholder="Current Password" name="current_password" id="current_password" required="required">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>New Password <span class="text-danger">*</span></label>
                                        <div>
                                            <input type="password" class="form-control" placeholder="New Password" name="new_password" id="new_password" minlength="5" required="required">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Confirm Password <span class="text-danger">*</span></label>
                                        <div>
                                            <input type="password" class="form-control" placeholder="Confirm Password" minlength="5" equalTo="#new_password" name="confirm_password" id="confirm_password" required="required">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <a href="<?php echo $back_url; ?>" class="btn bg-grey-300 btn-labeled"><b><i class="icon-arrow-left13"></i></b>Dashboard</a>
                            <button type="submit" class="btn bg-teal btn-labeled btn-labeled-right"><b><i class="icon-arrow-right14"></i></b>Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
    $(function () {
        jqueryValidate();
    });
</script>