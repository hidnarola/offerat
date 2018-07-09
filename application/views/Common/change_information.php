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
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>First Name <span class="text-danger">*</span></label>
                                        <div>
                                            <input type="text" class="form-control" placeholder="First Name" name="first_name" id="first_name" required="required" value="<?php echo $user_details['first_name']; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Last Name <span class="text-danger">*</span></label>
                                        <div>
                                            <input type="text" class="form-control" placeholder="Last Name" name="last_name" id="last_name" required="required" value="<?php echo $user_details['last_name']; ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>                            
                        <div class="col-xs-12">
                            <div class="col-md-12">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email Address <span class="text-danger">*</span></label>
                                        <div>
                                            <input type="email" class="form-control" placeholder="Email Address" name="email_id" id="email_id" required="required" value="<?php echo $user_details['email_id']; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Mobile Number <span class="text-danger">*</span></label>
                                        <div>
                                            <input type="text" class="form-control" placeholder="Mobile Number" name="mobile" id="mobile" required="required" value="<?php echo $user_details['mobile']; ?>">
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
<?php $this->load->view('Common/message_alert'); ?>
<script>
    $(function () {
        jqueryValidate();
    });

<?php
$credetials = $this->session->userdata('change_credetials');
if (isset($credetials) && !empty($credetials)) {
    ?>
        var msg = '<?php echo $credetials; ?>';
        $(document).find('#alert_message').text(msg);
        $(document).find('#message_popup').modal('show');
    <?php
    $this->session->unset_userdata('change_credetials');
}
?>
</script>