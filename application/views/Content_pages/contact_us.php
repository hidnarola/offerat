<div id="body_content">
    <div class="wrapper">
        <div class="innerdiv">
            <div class="panel-body">
                <div class="page_content pagecontent pagecontent-contact-us">                
                    <h1>Send us your Message </h1>                
                    <div class="contact_box">
                        <form name="contactform" id="contactform" method="post" >
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" value="<?php echo set_value('name'); ?>" placeholder="Name" required="required" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label>Email Address <span class="text-danger">*</span></label>
                                        <input type="text" name="email_id" value="<?php echo set_value('email_id'); ?>" placeholder="Email Address" required="required" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label>Contact Number </label>
                                        <input type="text" name="contact_number" value="<?php echo set_value('contact_number'); ?>" placeholder="Contact Number" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Message <span class="text-danger">*</span></label>
                                        <textarea name="message" placeholder="Message"  required class="form-control" rows="9"><?php echo set_value('message'); ?></textarea>
                                    </div>                                
                                </div> 
                            </div>
                            <div class="col-md-12">
                                <div class="form-group btn_center">
                                    <button type="submit" class="btn btn-primary btn-block submit_btn">Submit</button>
                                </div>
                            </div> 
                        </form>
                    </div>                            
                </div>

                </form> 
            </div>                
        </div>
    </div>
</div>
</div>
</div>    
<script>
    $(document).ready(function () {
        jqueryValidate();
    });
</script>