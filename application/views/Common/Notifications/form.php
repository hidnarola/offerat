<div class="col-md-12">
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
                <div class="panel-body panel_offer">
                    <form method="POST" action="" enctype="multipart/form-data" class="form-validate-jquery" name="frm_profile" id="frm_profile">
                    <!--<form method="POST" action="<?php echo $upload_url; ?>" enctype="multipart/form-data" class="form-validate-jquery" name="fileupload" id="fileupload">-->
                        <div class="col-xs-12">
                            <div class="">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="hidden" class="form-control" placeholder="" name="id_offer" id="id_offer" value="<?php echo (isset($notification_data['id_offer'])) ? $notification_data['id_offer'] : ''; ?>">
                                        <label>Select Store / Mall <span class="text-danger">*</span></label>
                                        <?php
                                        $store_id = 0;
                                        $mall_id = 0;
                                        $store_mall_id = $this->input->post('store_mall_id', TRUE);
                                        $store_mall_text = explode('_', $store_mall_id);
                                        if ($store_mall_text[0] == 'store')
                                            $store_id = $store_mall_text[1];
                                        if ($store_mall_text[0] == 'mall')
                                            $mall_id = $store_mall_text[1];
                                        ?>
                                        <select class="form-control select-search" id="store_mall_id" name="store_mall_id" required="required" data-live-search="true" >
                                            <option value="">Select Store / Mall</option>
                                            <?php if (isset($stores_list) && sizeof($stores_list) > 0) { ?>
                                                <optgroup label="Stores">
                                                    <?php foreach ($stores_list as $list) { ?>
                                                        <option value="store_<?php echo $list['id_store']; ?>" <?php echo (isset($notification_data) && isset($notification_data['id_store']) && $list['id_store'] == $notification_data['id_store']) ? 'selected=selected' : ''; ?> <?php echo ($list['id_store'] == $store_id) ? 'selected=selected' : ''; ?>><?php echo $list['store_name']; ?></option>
                                                    <?php } ?>
                                                </optgroup>
                                            <?php } ?>
                                            <?php if (isset($malls_list) && sizeof($malls_list) > 0) { ?>
                                                <optgroup label="Malls">
                                                    <?php foreach ($malls_list as $list) { ?>
                                                        <option value="mall_<?php echo $list['id_mall']; ?>" <?php echo (isset($notification_data) && isset($notification_data['id_mall']) && $list['id_mall'] == $notification_data['id_mall']) ? 'selected=selected' : ''; ?> <?php echo ($list['id_mall'] == $mall_id) ? 'selected=selected' : ''; ?>><?php echo $list['mall_name']; ?></option>
                                                    <?php } ?>
                                                </optgroup>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <?php if (isset($notification_data) && sizeof($notification_data) > 0) { ?>
                                        <div class="form-group">
                                            <label>Content Type <span class="text-danger">*</span></label>
                                            <div class="disabled">
                                                <div class="radio-inline">
                                                    <label>
                                                        <input type="radio" name="offer_type11" class="styled offer_type" required="required"  checked="checked" readonly="">
                                                        <?php 
                                                        if($notification_data['offer_type'] == IMAGE_OFFER_CONTENT_TYPE)
                                                            echo 'Image(s)';
                                                        elseif($notification_data['offer_type'] == VIDEO_OFFER_CONTENT_TYPE)
                                                            echo 'Video';
                                                        elseif($notification_data['offer_type'] == TEXT_OFFER_CONTENT_TYPE)
                                                            echo 'Text';
                                                        ?>                                                        
                                                    </label>
                                                </div>                                                
                                            </div>
                                            <input type="hidden" name="offer_type" id="offer_type" value="<?php echo $notification_data['offer_type']; ?>">
                                        </div>
                                    <?php } else { ?>
                                        <div class="form-group">
                                            <label>Content Type <span class="text-danger">*</span></label>
                                            <div>
                                                <div class="radio-inline">
                                                    <label>
                                                        <input type="radio" name="offer_type" class="styled offer_type" required="required"  value="<?php echo IMAGE_OFFER_CONTENT_TYPE; ?>" <?php echo (isset($notification_data) && isset($notification_data['offer_type']) && $notification_data['offer_type'] == IMAGE_OFFER_CONTENT_TYPE) ? 'checked="checked"' : (!isset($notification_data)) ? 'checked="checked"' : ''; ?>>
                                                        Image(s)
                                                    </label>
                                                </div>
                                                <div class="radio-inline">
                                                    <label>
                                                        <input type="radio" name="offer_type" class="styled offer_type" value="<?php echo VIDEO_OFFER_CONTENT_TYPE; ?>" <?php echo (isset($notification_data) && isset($notification_data['offer_type']) && $notification_data['offer_type'] == VIDEO_OFFER_CONTENT_TYPE) ? 'checked="checked"' : ''; ?>>
                                                        Video
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
                                    <?php } ?>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Broadcast Date & Time <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-default btn-icon" id="broad_cast_icon"><i class="icon-calendar3"></i></button>
                                            </span>
                                            <?php
//                                                $d = date('Y-m-d h:i');
//                                                $given = new DateTime($d, new DateTimeZone(date_default_timezone_get()));
//                                                echo $given->format("P") . '<br>';
//                                                echo date('Y-m-d h:i') . '<br>';
//                                                echo get_country_wise_date(date('Y-m-d h:i'), $this->loggedin_user_country_data['timezone']);
                                            $broadcasting_time = '';
                                            if (isset($notification_data) && isset($notification_data['broadcasting_time'])) {
                                                $broadcasting_time = date_create($notification_data['broadcasting_time']);
                                                $broadcasting_time = date_format($broadcasting_time, "Y-m-d H:i");
                                            } else {
                                                $broadcasting_time = date('d-m-Y H:i', strtotime('+2 minutes', strtotime(get_country_wise_date(date('d-m-Y H:i'), $this->loggedin_user_country_data['timezone']))));
                                            }
                                            ?>
                                            <input type="text" class="form-control" placeholder="Broadcast Date & Time" name="broadcasting_time" id="broad_cast_date_time" required="required" value="<?php echo $broadcasting_time; ?>">
                                        </div>                                            
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="col-xs-12">                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Expire Date & Time</label>
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-default btn-icon" id="expire_date_icon"><i class="icon-calendar3"></i></button>
                                        </span>                                                
                                        <?php
                                        $expiry_time = '';
                                        if (isset($notification_data) && isset($notification_data['expiry_time'])) {
                                            if ($notification_data['expiry_time'] == '0000-00-00 00:00:00') {
                                                $expiry_time = '';
                                            } else {
                                                $expiry_time = date_create($notification_data['expiry_time']);
                                                $expiry_time = date_format($expiry_time, "Y-m-d H:i");
                                            }
                                        } else {
                                            $expiry_time = date('d-m-Y H:i', strtotime('+10 minutes', strtotime(get_country_wise_date(date('d-m-Y H:i'), $this->loggedin_user_country_data['timezone']))));
                                        }
                                        ?>
                                        <input type="text" class="form-control" placeholder="Expire Date & Time" name="expiry_time" id="expire_date_time" value="<?php echo $expiry_time; ?>">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-default btn-icon" id="expire_date_delete_icon"><i class=" icon-bin"></i></button>
                                        </span> 
                                    </div>                                                                                        
                                </div>
                            </div>                                    
                            <div class="col-md-4">
                                <div class="form-group offer_text_section">
                                    <label>Offer Text <span class="text-danger">*</span></label>
                                    <div>
                                        <textarea class="form-control" rows="5" placeholder="Type here appears in the <?php echo ($notification_type == 'offers') ? 'offers' : 'announcement'; ?> section in mobile app" name="content" id="content"><?php echo (isset($notification_data['content'])) ? $notification_data['content'] : set_value('content'); ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group offer_video_section">
                                    <label>Upload Video <span class="text-danger">*</span></label>
                                    <div>
                                        <input type="file" class="form-control file-input" placeholder="" name="media_name" id="media_name" accept="video/*">
                                        <input type="hidden" name="is_valid" id="is_valid" value="1">
                                        <div id="media_errors_wrapper" class="alert alert-danger alert-bordered display-none">
                                            <span id="media_errors"></span>
                                        </div>
                                        <label id="media_name-error" class="validation-error-label" for="media_name" style=""></label>
                                    </div>
                                </div>
                                <div class="offer_video_section">
                                    <?php
                                    if (isset($notification_data['media_name']) && !empty($notification_data['media_name'])) {
                                        $extension = explode('.', $notification_data['media_name']);
                                        if (isset($extension) && isset($extension[1]) && in_array($extension[1], $this->video_extensions_arr)) {
                                            ?>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div>
                                                        <a href="<?php echo offer_media_path . $notification_data['media_name'] ?>" class="btn btn-info btn-lg" target="_blank"><i class="icon-video-camera2"></i> View Video</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                    }
                                    ?>
                                </div>

                                <?php if (isset($notification_type) && $notification_type == 'offers') { ?>
                                    <div class="responsive_view_status">
                                        <div class="form-group">
                                            <label>Push Notification Summary <span class="text-danger">*</span></label>
                                            <div>
                                                <input type="text" class="form-control" placeholder="Appears in push notification" name="push_message" id="push_message" required="required" value="<?php echo (isset($notification_data['push_message'])) ? $notification_data['push_message'] : set_value('push_message'); ?>">
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="form-group offer_image_section desktop_view">
                                    <label>Upload Image(s)<span class="text-danger">*</span></label>                                    
                                </div>                                    
                            </div>
                            <?php if (isset($notification_type) && $notification_type == 'offers') { ?>
                                <div class="col-md-4 desktop_view">
                                    <div class="form-group">
                                        <label>Push Notification Summary <span class="text-danger">*</span></label>
                                        <div>
                                            <input type="text" class="form-control" placeholder="Appears in push notification" name="push_message" id="push_message" required="required" value="<?php echo (isset($notification_data['push_message'])) ? $notification_data['push_message'] : set_value('push_message'); ?>">
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <!--<div class="form-group offer_image_section responsive_view_status"></div>  -->
                        </div>
                        <div class="text-right btn_end">
                            <a href="<?php echo $back_url; ?>" class="btn bg-grey-300 btn-labeled"><b><i class="icon-arrow-left13"></i></b>Back</a>
                            <button type="submit" id="offer_submit" name="offer_submit" class="btn bg-teal btn-labeled btn-labeled-right"><b><i class="icon-arrow-right14"></i></b>Save</button>
                            <input type="hidden" name="uploaded_images_data" id="uploaded_images_data">
                        </div>
                    </form>
                    <form method="POST" action="<?php echo $upload_url; ?>" enctype="multipart/form-data" class="form-validate-jquery" name="fileupload" id="fileupload">
                        <input type="hidden" name="uploaded_images_arr" id="uploaded_images_arr">
                        <div class="row fileupload-buttonbar">
                            <div class="col-md-4"></div>

                            <div class="col-md-4 upload_up">
                                <!-- The fileinput-button span is used to style the file input field as button -->
                                <span class="btn btn-success fileinput-button">
                                    <i class="glyphicon glyphicon-plus"></i>
                                    <span id="load_image_label">Load Image(s)</span>
                                    <input type="file" name="files[]" id="" multiple class="form-control" required="required">
                                </span>
                                <div class="upload-div" style="display:none;" id="update_div">
                                    <button type="button" class="delete">Delete</button>
                                    <input type="checkbox" class="toggle styled-checkbox-1">
                                </div>
                                <?php if (isset($notification_data) && $notification_data['offer_type'] == IMAGE_OFFER_CONTENT_TYPE) { ?>
                                    <a href="<?php echo $images_list_url . $notification_data['id_offer']; ?>" target="_blank" class="btn btn-primary">Images List</a>
                                <?php } ?>                                
                            </div>
                            <!-- The global progress state -->
                            <div class="col-lg-5 fileupload-progress fade display-none">
                                <!-- The global progress bar -->
                                <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                                    <div class="progress-bar progress-bar-success" style="width:0%;"></div>
                                </div>
                                <!-- The extended global progress state -->
                                <div class="progress-extended">&nbsp;</div>
                            </div>
                        </div>
                        <!-- The table listing the files available for upload/download -->
                        <table role="presentation" class="table table-striped"><tbody class="files"></tbody></table>
                <!--                        <span id="error_img"></span>
                                         The global progress bar 
                                        <div id="progress" class="progress">
                                            <div class="progress-bar progress-bar-success"></div>
                                        </div>
                                         The container for the uploaded files 
                                        <div id="files" class="files"></div>
                                        <br>    -->
                        <table role="presentation" class="table table-striped"><tbody class="files" id="table_image"></tbody></table>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('Common/Notifications/script'); ?>
<?php $this->load->view('Common/message_alert'); ?>
<?php $this->load->view('Common/Notifications/js_code'); ?>
