<?php
$uri_segment_1 = strtolower($this->uri->segment(1));
$session_key = $uri_segment_1 . '_user';
$logged_user = $this->session->userdata($session_key);
?>
<div class="sidebar sidebar-main">
    <div class="sidebar-content">
        <!-- User menu -->
        <div class="sidebar-user">
            <div class="category-content">
                <div class="media">                    
                    <div class="media-body">
                        <a href="/dashboard"><span class="media-heading text-semibold"><i class="fa fa-user fa-3x margin-right-10"></i> <?php echo isset($logged_user) ? $logged_user['first_name'] . ' ' . $logged_user['last_name'] : 'Super Admin Panel' ?></span></a>
                    </div>
                </div>
            </div>
        </div>
        <!-- /user menu -->
        <!-- Main navigation -->
        <div class="sidebar-category sidebar-category-visible">
            <div class="category-content no-padding">
                <ul class="navigation navigation-main navigation-accordion">
                    <?php
                    $controller = $this->uri->segment(2);
                    ?>
                    <li <?php echo ($controller === 'dashboard') ? 'class="active"' : '' ?>><a href="<?php echo $uri_segment_1; ?>/dashboard"><i class="icon-home4"></i> <span>Dashboard</span></a></li>
                    <li <?php echo ($controller === 'country') ? 'class="active"' : '' ?>><a href="<?php echo $uri_segment_1; ?>/country"><i class="icon-flag7"></i> <span>Countries</span></a></li>                    
                    <li <?php echo (in_array($controller, array('category', 'sub-category'))) ? 'class="active"' : '' ?>><a href="<?php echo $uri_segment_1; ?>/category"><i class="icon-atom"></i> <span>Categories</span></a></li>                    
                </ul>
            </div>
        </div>
        <!-- /main navigation -->
    </div>
</div>