<?php if (isset($store_details)) { ?>
    <div class="panel-body details_section">
        <div class="row">
            <div class="col-md-4 col-sm-12 col-xs-12">
                <label>Store Name : </label>
                <span class="text-semibold">
                    <?php echo (isset($store_details['store_name']) && !empty($store_details['store_name'])) ? $store_details['store_name'] : '-'; ?>
                </span>
            </div>
            <div class="col-md-4 col-sm-12 col-xs-12">
                <label>Status : </label>
                <span class="text-semibold">
                    <?php
                    if (isset($store_details['store_status'])) {
                        if ($store_details['store_status'] == IN_ACTIVE_STATUS)
                            echo '<span class="label label-danger label-rounded">Inactive</span>';
                        elseif ($store_details['store_status'] == NOT_VERIFIED_STATUS)
                            echo '<span class="label label-info label-rounded">Not Verified</span>';
                        elseif ($store_details['store_status'] == ACTIVE_STATUS)
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
                    $store_created_date = date_create($store_details['store_created_date']);
                    $store_created_date_text = date_format($store_created_date, "d-m-Y H:i");
                    echo $store_created_date_text;
                    ?>
                </span>
            </div>

            <!--            <div class="col-md-4 col-sm-12 col-xs-12">
                            <label>Telephone No. : </label>
                            <span class="text-semibold">
            <?php // echo (isset($store_details['store_telephone']) && !empty($store_details['store_telephone'])) ? $store_details['store_telephone'] : '-'; ?>
                            </span>
                        </div>-->
            <div class="col-md-4 col-sm-12 col-xs-12">
                <label>Website : </label>
                <span class="text-semibold">
                    <?php if (isset($store_details['store_website']) && !empty($store_details['store_website'])) { ?>
                        <a href="//<?php echo $store_details['store_website']; ?>"><?php echo $store_details['store_website']; ?></a>
                    <?php } else echo '- '; ?>
                </span>
            </div>
            <div class="col-md-4 col-sm-12 col-xs-12">
                <label>Facebook Page : </label>
                <span class="text-semibold">
                    <?php if (isset($store_details['store_facebook_page']) && !empty($store_details['store_facebook_page'])) { ?>
                        <a href="//<?php echo $store_details['store_facebook_page']; ?>"><?php echo $store_details['store_facebook_page']; ?></a>
                    <?php } else echo '-'; ?>
                </span>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-4 col-sm-12 col-xs-12">
                <label>Logo : </label>
                <?php if (isset($store_details['store_store_logo']) && !empty($store_details['store_store_logo'])) { ?>
                    <img src="<?php echo store_img_path . $store_details['store_store_logo']; ?>" onerror="image_not_found(image_0)" id="image_0" alt="Image Not Found">
                <?php } else echo '-'; ?>
            </div>            
        </div>
        <hr>
        <div class="row">
            <div class="col-md-4 col-sm-12 col-xs-12">
                <label>Contact Person : </label>
                <span class="text-semibold">
                    <?php
                    echo (isset($store_details['user_first_name']) && !empty($store_details['user_first_name'])) ? $store_details['user_first_name'] . ' ' . $store_details['user_last_name'] : '-';
                    ?>
                </span>
            </div>

            <div class="col-md-4 col-sm-12 col-xs-12">
                <label>Email ID : </label>
                <span class="text-semibold">
                    <?php
                    echo (isset($store_details['user_email_id']) && !empty($store_details['user_email_id'])) ? $store_details['user_email_id'] : '-';
                    ?>
                </span>
            </div>

            <div class="col-md-4 col-sm-12 col-xs-12">
                <label>Mobile Number : </label>
                <span class="text-semibold">
                    <?php
                    echo (isset($store_details['user_mobile']) && !empty($store_details['user_mobile'])) ? $store_details['user_mobile'] : '-';
                    ?>
                </span>
            </div>
        </div>  
        <hr>
        <div class="row">
            <div class="col-md-6 col-sm-12 col-xs-12">                
                <h6 class="text-semibold">Business Categories : </h6>
                <?php if (isset($store_categories) && sizeof($store_categories) > 0) { ?>
                    <ul class="list">
                        <?php foreach ($store_categories as $cat) { ?>
                            <li>
                                <span class="label border-left-primary label-striped">
                                    <?php
                                    echo $cat['category_name'];
                                    echo (isset($cat['sub_category_name']) && !empty($cat['sub_category_name'])) ? ' <i class=" icon-arrow-right8"></i> ' . $cat['sub_category_name'] : '';
                                    ?>
                                </span>
                            </li>                                            
                        <?php } ?>
                    </ul>
                <?php
                } else {
                    echo 'No results found';
                }
                ?>
            </div>
        </div>                
    </div>                    
<?php } ?>