<?php if (isset($notification_details)) { ?>
    <div class="panel-body details_section">
        <div class="row">
            <div class="col-md-4 col-sm-12 col-xs-12">
                <label>Post Type : </label>
                <span class="text-semibold">
                    <?php
                    if (isset($notification_details['type'])) {
                        if ($notification_details['type'] == OFFER_OFFER_TYPE)
                            echo '<span class="label label-info label-rounded">Offer</span>';
                        elseif ($notification_details['type'] == ANNOUNCEMENT_OFFER_TYPE)
                            echo '<span class="label label-success label-rounded">Announcement</span>';
                        elseif ($notification_details['type'] == CATALOG_OFFER_TYPE)
                            echo '<span class="label label-success label-rounded">Catalog</span>';
                    } else {
                        echo '-';
                    }
                    ?>
                </span>
            </div>                       
            <div class="col-md-4 col-sm-12 col-xs-12">
                <label>Content Type : </label>
                <span class="text-semibold">
                    <?php
                    if (isset($notification_details['offer_type'])) {
                        if ($notification_details['offer_type'] == IMAGE_OFFER_CONTENT_TYPE)
                            echo '<span class="label label-info label-rounded">Image</span>';
                        elseif ($notification_details['offer_type'] == VIDEO_OFFER_CONTENT_TYPE)
                            echo '<span class="label label-success label-rounded">Video</span>';
                        elseif ($notification_details['offer_type'] == TEXT_OFFER_CONTENT_TYPE)
                            echo '<span class="label label-primary label-rounded">Text</span>';
                    } else {
                        echo '-';
                    }
                    ?>
                </span>
            </div>                       
            <div class="col-md-4 col-sm-12 col-xs-12">
                <?php if ($notification_details['mall_name'] && !empty($notification_details['mall_name'])) { ?>
                    <label>Mall Name : </label>
                    <span class="text-semibold">
                        <?php echo $notification_details['mall_name']; ?>
                    </span>
                <?php } elseif ($notification_details['store_name'] && !empty($notification_details['store_name'])) { ?>
                    <label>Store Name : </label>
                    <span class="text-semibold">
                        <?php echo $notification_details['store_name']; ?>
                    </span>
                    <?php
                } else {
                    echo '';
                }
                ?>
            </div>                       
            <div class="col-md-4 col-sm-12 col-xs-12">
                <label>Post Date & Time : </label>
                <span class="text-semibold">
                    <?php
                    if (isset($notification_details['broadcasting_time']) && !empty($notification_details['broadcasting_time'])) {
                        $broadcasting_time = new DateTime($notification_details['broadcasting_time'], new DateTimeZone(date_default_timezone_get()));
                        $broadcasting_time->setTimezone(new DateTimeZone($this->loggedin_user_country_data['timezone']));
                        $broadcasting_time_text = $broadcasting_time->format('d-m-Y H:i');

                        echo $broadcasting_time_text;
                    } else
                        echo '-';
                    ?>
                </span>
            </div> 
            <?php if ($notification_details['type'] == OFFER_OFFER_TYPE) { ?>
                <div class="col-md-6 col-sm-12 col-xs-12">
                    <label>Expiration Time : </label>
                    <span class="text-semibold">
                        <?php
                        if ($notification_details['expire_time_type'] == EXPIRE_TIME_FIXED)
                            echo 'Fixed expire date / ';
                        elseif ($notification_details['expire_time_type'] == EXPIRE_TIME_LIMITED)
                            echo 'Limited Offer / ';

                        if (isset($notification_details['expiry_time']) && !empty($notification_details['expiry_time']) && $notification_details['expiry_time'] != '0000-00-00 00:00:00') {
                            $expiry_time = new DateTime($notification_details['expiry_time'], new DateTimeZone(date_default_timezone_get()));
                            $expiry_time->setTimezone(new DateTimeZone($this->loggedin_user_country_data['timezone']));
                            $expiry_time_text = $expiry_time->format('d-m-Y H:i');
                            echo $expiry_time_text;
                        } else
                            echo '-';
                        ?>
                    </span>
                </div>  
            <?php } ?>
        </div>        

        <?php if ($notification_details['offer_type'] == TEXT_OFFER_CONTENT_TYPE) { ?>
            <hr>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <label>Offer Text : </label>
                    <span class="text-semibold">
                        <?php echo $notification_details['content']; ?>
                    </span>
                </div>
            </div>
        <?php } ?>

        <?php if ($notification_details['type'] == OFFER_OFFER_TYPE) { ?>
            <hr>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <label>Push Notification Summary : </label>
                    <span class="text-semibold">
                        <?php echo $notification_details['push_message']; ?>
                    </span>
                </div>
            </div>
        <?php } ?>
        <hr>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <label>Optional Text : </label>
                <span class="text-semibold">
                    <?php echo $notification_details['expire_text']; ?>
                </span>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">                
                <?php
                $extension = explode('.', $notification_details['media_name']);
                if ($notification_details['offer_type'] == IMAGE_OFFER_CONTENT_TYPE) {
                    ?>
                    <div class="col-md-12">
                        <div class="form-group">
                            <div>                                                                    
                                <a href="<?php echo $images_list_url . $notification_details['id_offer']; ?>" class="btn btn-info btn-lg" target="_blank"><i class="icon-image5"></i> View Image(s)</a>
                            </div>
                        </div>
                    </div>
                <?php } elseif (isset($extension) && isset($extension[1]) && in_array($extension[1], $this->video_extensions_arr)) { ?>
                    <div class="col-md-12">
                        <div class="form-group">
                            <div>
                                <a href="<?php echo offer_media_path . $notification_details['media_name']; ?>" class="btn btn-info btn-lg" target="_blank"><i class="icon-video-camera2"></i> View Video</a>
                            </div>
                        </div>
                    </div>
                <?php }
                ?>
            </div>
        </div>
    </div>
<?php } ?>