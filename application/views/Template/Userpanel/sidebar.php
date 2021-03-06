<?php
$uri_segment_1 = strtolower($this->uri->segment(1));
$logged_user_type = $this->session->userdata('loggedin_user_type');
$controller = $this->uri->segment(2);
$session_user_data = [];

if (!empty($_SESSION['loggedin_user_data'])) {
    $session_user_data = $_SESSION['loggedin_user_data'];
}
?>
<div class="sidebar sidebar-main">
    <div class="sidebar-content">
        <!-- User menu -->
        <div class="sidebar-user">
            <div class="category-content">
                <div class="media">                    
                    <div class="media-body">
                        <a href="<?php echo $uri_segment_1; ?>/dashboard">&nbsp;&nbsp;
                            <span class="media-heading text-semibold"><i class="fa fa-user fa-3x margin-right-10"></i> 
                                <?php
                                if ($logged_user_type == SUPER_ADMIN_USER_TYPE)
                                    echo 'Super Admin';
                                elseif ($logged_user_type == COUNTRY_ADMIN_USER_TYPE)
                                    echo 'Country Admin';
                                elseif ($logged_user_type == STORE_OR_MALL_ADMIN_USER_TYPE)
                                    echo (!empty($session_user_data) && !empty($session_user_data['first_name']) && !empty($session_user_data['last_name'])) ? $session_user_data['first_name'] . ' ' . $session_user_data['last_name'] : 'Admin';
                                ?>                            
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- /user menu -->
        <!-- Main navigation -->
        <div class="sidebar-category sidebar-category-visible">
            <div class="category-content no-padding">
                <?php
                if ($logged_user_type == SUPER_ADMIN_USER_TYPE) {
                    ?>
                    <ul class="navigation navigation-main navigation-accordion">
                        <li <?php echo ($controller === 'dashboard') ? 'class="active"' : '' ?>><a href="<?php echo $uri_segment_1; ?>/dashboard"><i class="icon-home4"></i> <span>Dashboard</span></a></li>
                        <li <?php echo ($controller === 'country') ? 'class="active"' : '' ?>><a href="<?php echo $uri_segment_1; ?>/country"><i class="icon-flag7"></i> <span>Countries</span></a></li>                    
                        <li <?php echo (in_array($controller, array('category', 'sub-category'))) ? 'class="active"' : '' ?>><a href="<?php echo $uri_segment_1; ?>/category"><i class="icon-atom"></i> <span>Categories</span></a></li>
                        <li <?php echo ($controller === 'change-password') ? 'class="active"' : '' ?>><a href="<?php echo $uri_segment_1; ?>/change-password"><i class="icon-lock2"></i> <span>Change Password</span></a></li>
                        <li <?php echo ($controller === 'change-information') ? 'class="active"' : '' ?>><a href="<?php echo $uri_segment_1; ?>/change-information"><i class="fa fa-user"></i> <span>Change Information</span></a></li>
                        <li <?php echo ($controller === 'terms-conditions') ? 'class="active"' : '' ?>><a href="<?php echo $uri_segment_1; ?>/terms-conditions"><i class="fa fa-files-o"></i> <span>Pages</span></a></li>
                    </ul>
                <?php } elseif ($logged_user_type == COUNTRY_ADMIN_USER_TYPE) { ?>
                    <ul class="navigation navigation-main navigation-accordion">
                        <li <?php echo ($controller === 'dashboard') ? 'class="active"' : '' ?>><a href="<?php echo $uri_segment_1; ?>/dashboard"><i class="icon-home4"></i> <span>Dashboard</span></a></li>
                        <li>
                            <a href="#"><i class="icon-bell2"></i> <span>Posts</span></a>
                            <ul>
                                <li <?php echo ($controller === 'notifications' && isset($notification_type) && $notification_type == 'offers') ? 'class="active"' : '' ?>><a href="<?php echo $uri_segment_1; ?>/notifications/offers"><img src="<?= base_url('assets/images/icons/offer.png') ?>" style="width: 20px;" alt="Offers" />&nbsp;&nbsp;&nbsp;&nbsp;<span>Offers</span></a></li>
                                <li <?php echo ($controller === 'notifications' && isset($notification_type) && $notification_type == 'announcements') ? 'class="active"' : '' ?>><a href="<?php echo $uri_segment_1; ?>/notifications/announcements"><img src="<?= base_url('assets/images/icons/announcement.png') ?>" style="width: 20px;" alt="Offers" />&nbsp;&nbsp;&nbsp;&nbsp;<span>Announcements</span></a></li>
                                <li <?php echo ($controller === 'notifications' && isset($notification_type) && $notification_type == 'catalogs') ? 'class="active"' : '' ?>><a href="<?php echo $uri_segment_1; ?>/notifications/catalogs"><i class="icon-book"></i> <span>Catalog</span></a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="#"><i class="icon-office"></i> <span>Stores / Malls</span></a>
                            <ul>
                                <li <?php echo (in_array($controller, array('stores', 'report'))) ? 'class="active"' : '' ?>><a href="<?php echo $uri_segment_1; ?>/stores"><i class="icon-store"></i> <span>Stores</span></a></li>
                                <li <?php echo (in_array($controller, array('malls', 'report'))) ? 'class="active"' : '' ?>><a href="<?php echo $uri_segment_1; ?>/malls"><i class="icon-store2"></i> <span>Malls</span></a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="#"><i class="icon-checkmark-circle2"></i> <span>Verify Offers</span></a>
                            <ul>
                                <li <?php echo ($controller === 'verify-store-offers') ? 'class="active"' : '' ?>><a href="<?php echo $uri_segment_1; ?>/verify-store-offers"><i class="icon-store"></i> <span>Stores</span></a></li>
                                <li <?php echo ($controller === 'verify-mall-offers') ? 'class="active"' : '' ?>><a href="<?php echo $uri_segment_1; ?>/verify-mall-offers"><i class="icon-store2"></i> <span>Malls</span></a></li>

                            </ul>
                        </li>
                        <li>
                            <a href="#"><i class="icon-star-half"></i> <span>Sponsored</span></a>
                            <ul>
                                <li <?php echo ($controller === 'sponsored') ? 'class="active"' : '' ?>><a href="<?php echo $uri_segment_1; ?>/sponsored/stores"><i class="icon-store"></i> <span>Stores</span></a></li>
                                <li <?php echo ($controller === 'sponsored') ? 'class="active"' : '' ?>><a href="<?php echo $uri_segment_1; ?>/sponsored/malls"><i class="icon-store2"></i> <span>Malls</span></a></li>
                            </ul>
                        </li>                        
                        <li <?php echo ($controller === 'change-password') ? 'class="active"' : '' ?>><a href="<?php echo $uri_segment_1; ?>/change-password"><i class="icon-lock2"></i> <span>Change Password</span></a></li>
                        <li <?php echo ($controller === 'change-information') ? 'class="active"' : '' ?>><a href="<?php echo $uri_segment_1; ?>/change-information"><i class="fa fa-user"></i> <span>Change Information</span></a></li>
                    </ul>
                <?php } elseif ($logged_user_type == STORE_OR_MALL_ADMIN_USER_TYPE) { ?>
                    <ul class="navigation navigation-main navigation-accordion">
                        <!--<li <?php echo ($controller === 'dashboard') ? 'class="active"' : '' ?>><a href="<?php echo $uri_segment_1; ?>/dashboard"><i class="icon-home4"></i> <span>Dashboard</span></a></li>-->               
                        <li>
                            <a href="#"><i class="fa fa-files-o"></i> <span>Posts</span></a>
                            <ul>
                                <li <?php echo ($controller === 'notifications' && isset($notification_type) && $notification_type == 'offers') ? 'class="active"' : '' ?>><a href="<?php echo $uri_segment_1; ?>/notifications/offers"><img src="<?= base_url('assets/images/icons/offer.png') ?>" style="width: 20px;" alt="Offers" />&nbsp;&nbsp;&nbsp;&nbsp; <span>Offers</span></a></li>
                                <li <?php echo ($controller === 'notifications' && isset($notification_type) && $notification_type == 'announcements') ? 'class="active"' : '' ?>><a href="<?php echo $uri_segment_1; ?>/notifications/announcements"><img src="<?= base_url('assets/images/icons/announcement.png') ?>" style="width: 20px;" alt="Offers" />&nbsp;&nbsp;&nbsp;&nbsp; <span>Announcements</span></a></li>
                                <li <?php echo ($controller === 'notifications' && isset($notification_type) && $notification_type == 'catalogs') ? 'class="active"' : '' ?>><a href="<?php echo $uri_segment_1; ?>/notifications/catalogs"><i class="icon-book"></i> <span>Catalog</span></a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="#"><i class="icon-office"></i> <span>Stores / Malls</span></a>
                            <ul>
                                <li <?php echo (in_array($controller, array('stores', 'report'))) ? 'class="active"' : '' ?>><a href="<?php echo $uri_segment_1; ?>/stores"><i class="icon-store"></i> <span>Stores</span></a></li>
                                <li <?php echo (in_array($controller, array('malls', 'report'))) ? 'class="active"' : '' ?>><a href="<?php echo $uri_segment_1; ?>/malls"><i class="icon-store2"></i> <span>Malls</span></a></li>
                            </ul>
                        </li>
                        <li <?php echo ($controller === 'change-password') ? 'class="active"' : '' ?>><a href="<?php echo $uri_segment_1; ?>/change-password"><i class="icon-lock2"></i> <span>Change Password</span></a></li>
                        <li <?php echo ($controller === 'change-information') ? 'class="active"' : '' ?>><a href="<?php echo $uri_segment_1; ?>/change-information"><i class="fa fa-user"></i> <span>Change Information</span></a></li>
                    </ul>
                <?php } ?>
            </div>
        </div>
        <!-- /main navigation -->
    </div>
</div>