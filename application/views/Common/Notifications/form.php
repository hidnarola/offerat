<div class="row">
    <div class="col-md-12">
        <form method="POST" action="" enctype="multipart/form-data" class="form-validate-jquery" name="frm_profile" id="frm_profile">
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-flat">
                        <div class="panel-heading">
                            <h5 class="panel-title"><i class="icon-bell2 btn-icon"></i><?php echo $page_header ?></h5>
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
                                            <label>Select Store / Mall <span class="text-danger">*</span></label>
                                            <select class="form-control select-search" id="store_mall_id" name="store_mall_id" required="required" data-live-search="true" >
                                                <option value="">Select Store / Mall</option>
                                                <?php if (isset($stores_list) && sizeof($stores_list) > 0) { ?>
                                                    <optgroup label="Stores">
                                                        <?php foreach ($stores_list as $list) { ?>
                                                            <option value="store_<?php echo $list['id_store']; ?>" <?php echo (isset($notification_data) && isset($notification_data['id_store']) && $list['id_store'] == $notification_data['id_store']) ? 'selected=selected' : ''; ?>><?php echo $list['store_name']; ?></option>
                                                        <?php } ?>
                                                    </optgroup>
                                                <?php } ?>
                                                <?php if (isset($malls_list) && sizeof($malls_list) > 0) { ?>
                                                    <optgroup label="Malls">
                                                        <?php foreach ($malls_list as $list) { ?>
                                                            <option value="mall_<?php echo $list['id_mall']; ?>" <?php echo (isset($notification_data) && isset($notification_data['id_mall']) && $list['id_mall'] == $notification_data['id_mall']) ? 'selected=selected' : ''; ?>><?php echo $list['mall_name']; ?></option>
                                                        <?php } ?>
                                                    </optgroup>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Content Type <span class="text-danger">*</span></label>
                                            <div>
                                                <div class="radio-inline">
                                                    <label>
                                                        <input type="radio" name="offer_type" class="styled offer_type" required="required"  value="<?php echo IMAGE_OFFER_CONTENT_TYPE; ?>" <?php echo (isset($notification_data) && isset($notification_data['offer_type']) && $notification_data['offer_type'] == IMAGE_OFFER_CONTENT_TYPE) ? 'checked="checked"' : ''; ?>>
                                                        Image / Video
                                                    </label>
                                                </div>
                                                <div class="radio-inline">
                                                    <label>
                                                        <input type="radio" name="offer_type" class="styled offer_type" value="<?php echo TEXT_OFFER_CONTENT_TYPE; ?>" <?php echo (isset($notification_data) && isset($notification_data['offer_type']) && $notification_data['offer_type'] == TEXT_OFFER_CONTENT_TYPE) ? 'checked="checked"' : ''; ?>>
                                                        Text
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Expire Date & Time <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn btn-default btn-icon" id="expire_date_icon"><i class="icon-calendar3"></i></button>
                                                </span>                                                
                                                <?php
                                                $expiry_time = '';
                                                if (isset($notification_data) && isset($notification_data['expiry_time'])) {
                                                    $expiry_time = date_create($notification_data['expiry_time']);
                                                    $expiry_time = date_format($expiry_time, "Y-m-d H:i");
                                                }
                                                ?>
                                                <input type="text" class="form-control" placeholder="Expire Date & Time" name="expiry_time" id="expire_date_time" required="required" readonly="readonly" value="<?php echo (!empty($expiry_time)) ? $expiry_time : set_value('expiry_time'); ?>">
                                            </div>                                            
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="col-xs-12">
                                <div class="col-md-12">                                    
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Broadcast Date & Time <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn btn-default btn-icon" id="broad_cast_icon"><i class="icon-calendar3"></i></button>
                                                </span>
                                                <?php
                                                $broadcasting_time = '';
                                                if (isset($notification_data) && isset($notification_data['broadcasting_time'])) {
                                                    $broadcasting_time = date_create($notification_data['broadcasting_time']);
                                                    $broadcasting_time = date_format($broadcasting_time, "Y-m-d H:i");
                                                }
                                                ?>
                                                <input type="text" class="form-control" placeholder="Broadcast Date & Time" name="broadcasting_time" id="broad_cast_date_time" required="required" readonly="readonly" value="<?php echo $broadcasting_time; ?>">
                                            </div>                                            
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group offer_text_section">
                                            <label>Offer Text <span class="text-danger">*</span></label>
                                            <div>
                                                <textarea class="form-control" rows="5" placeholder="Type here appears in the offers section in mobile app" name="content" id="content"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group offer_image_video_section">
                                            <label>Upload Image / Video <span class="text-danger">*</span></label>
                                            <div>
                                                <input type="file" class="form-control file-input" placeholder="Appears in push notification" name="media_name" id="media_name">
                                                <label id="media_name-error" class="validation-error-label" for="media_name" style=""></label>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if (isset($type) && $type == 'offers') { ?>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Push Notification Summary <span class="text-danger">*</span></label>
                                                <div>
                                                    <input type="text" class="form-control" placeholder="Appears in push notification" name="push_message" id="push_message" required="required">
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="text-right">
                                <a href="<?php echo $back_url; ?>" class="btn bg-grey-300 btn-labeled"><b><i class="icon-arrow-left13"></i></b>Back</a>
                                <button type="submit" class="btn bg-teal btn-labeled btn-labeled-right"><b><i class="icon-arrow-right14"></i></b>Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<?php $this->load->view('Common/message_alert'); ?>
<script>
    $(function () {
        jqueryValidate();
        $(document).find('.offer_text_section').hide();

<?php if (isset($notification_data) && isset($notification_data['offer_type']) && $notification_data['offer_type'] == TEXT_OFFER_CONTENT_TYPE) { ?>
            $(document).find('.offer_text_section').show();
            $(document).find('.offer_image_video_section').hide();
            $(document).find('#content').attr('required', 'required');
            $(document).find('#media_name').removeAttr('required');
<?php } else { ?>
            $(document).find('.offer_text_section').hide();
            $(document).find('.offer_image_video_section').show();
            $(document).find('#media_name').attr('required', 'required');
            $(document).find('#content').removeAttr('required');
<?php } ?>
    });

    $(document).find('.offer_type').change(function () {
        var offer_content_type = $(document).find(this).val();
        if (offer_content_type == '<?php echo IMAGE_OFFER_CONTENT_TYPE; ?>') {
            $(document).find('.offer_text_section').hide();
            $(document).find('.offer_image_video_section').show();
            $(document).find('#media_name').attr('required', 'required');
            $(document).find('#content').removeAttr('required');
        } else {
            $(document).find('.offer_text_section').show();
            $(document).find('.offer_image_video_section').hide();
            $(document).find('#content').attr('required', 'required');
            $(document).find('#media_name').removeAttr('required');
        }


    });


</script>