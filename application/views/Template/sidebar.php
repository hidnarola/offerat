<?php
$uri_segment_1 = strtolower($this->uri->segment(1));
$logged_user_type = $this->session->userdata('loggedin_user_type');
?>
<div class="sidebar sidebar-main">
    <div class="sidebar-content">
        <!-- User menu -->
        <div class="sidebar-user">
            <div class="category-content">
                <div class="media">                    
                    <div class="media-body">
                        <a href="/dashboard">
                            <span class="media-heading text-semibold"><i class="fa fa-user fa-3x margin-right-10"></i> 
                                <?php
                                if ($logged_user_type == SUPER_ADMIN_USER_TYPE)
                                    echo 'Super Admin';
                                elseif ($logged_user_type == COUNTRY_ADMIN_USER_TYPE)
                                    echo 'Country Admin';
                                elseif ($logged_user_type == STORE_OR_MALL_ADMIN_USER_TYPE)
                                    echo 'Mall / Store User';
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
                $controller = $this->uri->segment(2);
                if ($logged_user_type == SUPER_ADMIN_USER_TYPE) {
                    ?>
                    <ul class="navigation navigation-main navigation-accordion">
                        <li <?php echo ($controller === 'dashboard') ? 'class="active"' : '' ?>><a href="<?php echo $uri_segment_1; ?>/dashboard"><i class="icon-home4"></i> <span>Dashboard</span></a></li>
                        <li <?php echo ($controller === 'country') ? 'class="active"' : '' ?>><a href="<?php echo $uri_segment_1; ?>/country"><i class="icon-flag7"></i> <span>Countries</span></a></li>                    
                        <li <?php echo (in_array($controller, array('category', 'sub-category'))) ? 'class="active"' : '' ?>><a href="<?php echo $uri_segment_1; ?>/category"><i class="icon-atom"></i> <span>Categories</span></a></li>
                        <li <?php echo ($controller === 'change-password') ? 'class="active"' : '' ?>><a href="<?php echo $uri_segment_1; ?>/change-password"><i class="icon-lock2"></i> <span>Change Password</span></a></li>
                        <li <?php echo ($controller === 'change-information') ? 'class="active"' : '' ?>><a href="<?php echo $uri_segment_1; ?>/change-information"><i class="fa fa-user"></i> <span>Change Information</span></a></li>
                    </ul>
                <?php } elseif ($logged_user_type == COUNTRY_ADMIN_USER_TYPE) { ?>
                    <ul class="navigation navigation-main navigation-accordion">
                        <li <?php echo ($controller === 'dashboard') ? 'class="active"' : '' ?>><a href="<?php echo $uri_segment_1; ?>/dashboard"><i class="icon-home4"></i> <span>Dashboard</span></a></li>
                        <li <?php echo ($controller === 'notifications') ? 'class="active"' : '' ?>><a href="<?php echo $uri_segment_1; ?>/notifications"><i class="icon-bell2"></i> <span>Notifications</span></a></li>
                        <li <?php echo ($controller === 'change-password') ? 'class="active"' : '' ?>><a href="<?php echo $uri_segment_1; ?>/change-password"><i class="icon-lock2"></i> <span>Change Password</span></a></li>
                        <li <?php echo ($controller === 'change-information') ? 'class="active"' : '' ?>><a href="<?php echo $uri_segment_1; ?>/change-information"><i class="fa fa-user"></i> <span>Change Information</span></a></li>
                    </ul>
                <?php } elseif ($logged_user_type == STORE_OR_MALL_ADMIN_USER_TYPE) { ?>
                    <ul class="navigation navigation-main navigation-accordion">
                        <li <?php echo ($controller === 'dashboard') ? 'class="active"' : '' ?>><a href="<?php echo $uri_segment_1; ?>/dashboard"><i class="icon-home4"></i> <span>Dashboard</span></a></li>
                        <li <?php echo ($controller === 'notifications') ? 'class="active"' : '' ?>><a href="<?php echo $uri_segment_1; ?>/notifications"><i class="icon-bell2"></i> <span>Notifications</span></a></li>
                        <li <?php echo ($controller === 'change-password') ? 'class="active"' : '' ?>><a href="<?php echo $uri_segment_1; ?>/change-password"><i class="icon-lock2"></i> <span>Change Password</span></a></li>
                        <li <?php echo ($controller === 'change-information') ? 'class="active"' : '' ?>><a href="<?php echo $uri_segment_1; ?>/change-information"><i class="fa fa-user"></i> <span>Change Information</span></a></li>
                    </ul>
                <?php } ?>
            </div>
        </div>
        <!-- /main navigation -->
    </div>
</div>