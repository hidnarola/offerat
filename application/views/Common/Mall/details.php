<?php if (isset($mall_details)) { ?>
    <div class="panel-body details_section">
        <div class="row">
            <div class="col-md-4 col-sm-12 col-xs-12">
                <label>Mall Name : </label>
                <span class="text-semibold">
                    <?php echo (isset($mall_details['mall_name']) && !empty($mall_details['mall_name'])) ? $mall_details['mall_name'] : '-'; ?>
                </span>
            </div>
            <div class="col-md-4 col-sm-12 col-xs-12">
                <label>Status : </label>
                <span class="text-semibold">
                    <?php
                    if (isset($mall_details['mall_status'])) {
                        if ($mall_details['mall_status'] == IN_ACTIVE_STATUS)
                            echo '<span class="label label-danger label-rounded">Inactive</span>';
                        elseif ($mall_details['mall_status'] == NOT_VERIFIED_STATUS)
                            echo '<span class="label label-info label-rounded">Not Verified</span>';
                        elseif ($mall_details['mall_status'] == ACTIVE_STATUS)
                            echo '<span class="label label-success label-rounded">Active</span>';
                        else
                            echo '-';
                    } else {
                        echo '-';
                    }
                    ?>
                </span>
            </div>
            <div class="col-md-4 col-sm-12 col-xs-12">
                <label>Created On :</label>
                <span class="text-semibold">
                    <?php
                    $mall_created_date = date_create($mall_details['mall_created_date']);
                    $mall_created_date_text = date_format($mall_created_date, "d-m-Y H:i");
                    echo $mall_created_date_text;
                    ?>
                </span>
            </div>

            <div class="col-md-4 col-sm-12 col-xs-12">
                <label>Telephone No. : </label>
                <span class="text-semibold">
                    <?php echo (isset($mall_details['mall_telephone']) && !empty($mall_details['mall_telephone'])) ? $mall_details['mall_telephone'] : '-'; ?>
                </span>
            </div>
            <div class="col-md-4 col-sm-12 col-xs-12">
                <label>Website : </label>
                <span class="text-semibold">
                    <?php if (isset($mall_details['mall_website']) && !empty($mall_details['mall_website'])) { ?>
                        <a href="//<?php echo $mall_details['mall_website']; ?>"><?php echo $mall_details['mall_website']; ?></a>
                        <?php
                    } else {
                        echo '-';
                    }
                    ?>
                </span>
            </div>
            <div class="col-md-4 col-sm-12 col-xs-12">
                <label>Facebook Page : </label>
                <span class="text-semibold">
                    <?php if (isset($mall_details['mall_facebook_page']) && !empty($mall_details['mall_facebook_page'])) { ?>
                        <a href="//<?php echo $mall_details['mall_facebook_page']; ?>"><?php echo $mall_details['mall_facebook_page']; ?></a>
                        <?php
                    } else {
                        echo '-';
                    }
                    ?>
                </span>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-4 col-sm-12 col-xs-12">
                <label>Logo : </label>
                <?php if (isset($mall_details['mall_mall_logo']) && !empty($mall_details['mall_mall_logo'])) { ?>
                    <img src="<?php echo mall_img_path . $mall_details['mall_mall_logo']; ?>" onerror="image_not_found(image_0)" id="image_0" alt="Image Not Found">
                    <?php
                } else {
                    echo '-';
                }
                ?>
            </div>            
        </div>
        <hr>
        <div class="row">
            <div class="col-md-4 col-sm-12 col-xs-12">
                <label>Contact Person : </label>
                <span class="text-semibold">
                    <?php
                    echo (isset($mall_details['user_first_name']) && !empty($mall_details['user_first_name'])) ? $mall_details['user_first_name'] . ' ' . $mall_details['user_last_name'] : '-';
                    ?>
                </span>
            </div>

            <div class="col-md-4 col-sm-12 col-xs-12">
                <label>Email ID : </label>
                <span class="text-semibold">
                    <?php
                    echo (isset($mall_details['user_email_id']) && !empty($mall_details['user_email_id'])) ? $mall_details['user_email_id'] : '-';
                    ?>
                </span>
            </div>

            <div class="col-md-4 col-sm-12 col-xs-12">
                <label>Mobile Number : </label>
                <span class="text-semibold">
                    <?php
                    echo (isset($mall_details['user_mobile']) && !empty($mall_details['user_mobile'])) ? $mall_details['user_mobile'] : '-';
                    ?>
                </span>
            </div>
        </div>  
        <hr>        
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">                
                <h6 class="text-semibold">Mall Locations</h6>
                <ol class="list rounded-list">                        
                    <li>
                        <p>
                            <?php
                            $display_location = $mall_details['street'];
                            if (isset($mall_details['street1']) && !empty($mall_details['street1']))
                                $display_location .= ', ' . $mall_details['street1'];
                            if (isset($mall_details['city']) && !empty($mall_details['city']))
                                $display_location .= ', ' . $mall_details['city'];
                            if (isset($mall_details['state']) && !empty($mall_details['state']))
                                $display_location .= ', ' . $mall_details['state'];
                            if (isset($mall_details['country_name']) && !empty($mall_details['country_name']))
                                $display_location .= ', ' . $mall_details['country_name'];
                            echo $display_location;
                            ?>
                        </p>
                    </li>                                                            
                </ol>                
            </div>
        </div>
    </div>                    
<?php } ?>