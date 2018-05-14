<?php
$uri_segment_1 = strtolower($this->uri->segment(1));
?>
<!-- Main navbar -->
<div class="navbar navbar-inverse">
    <div class="navbar-header">
        <a class="navbar-brand" href="<?php echo $uri_segment_1;?>/dashboard"><span class="logo-text"><?php echo SITENAME; ?></span></a>

        <ul class="nav navbar-nav visible-xs-block">
            <li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
            <li><a class="sidebar-mobile-main-toggle"><i class="icon-paragraph-justify3"></i></a></li>
        </ul>
    </div>

    <div class="navbar-collapse collapse" id="navbar-mobile">
        <ul class="nav navbar-nav">
            <li><a class="sidebar-control sidebar-main-toggle hidden-xs"><i class="icon-paragraph-justify3"></i></a></li>
        </ul>

        <ul class="nav navbar-nav navbar-right">

            <li class="dropdown dropdown-user">
                <a class="dropdown-toggle" data-toggle="dropdown">                    
                    <?php
                    $session_key = $uri_segment_1 . '_user';
                    $logged_user = $this->session->userdata($session_key);
                    ?>
                    <span><i class="fa fa-user margin-right-10"></i> <?php echo $logged_user['first_name'] . ' ' . $logged_user['last_name'] ?></span>
                    <i class="caret"></i>
                </a>

                <ul class="dropdown-menu dropdown-menu-right">
                    <li><a href="<?php echo $uri_segment_1;?>/profile"><i class="icon-vcard"></i> My Profile</a></li>
                    <li><a href="<?php echo $uri_segment_1;?>/profile/change_password"><i class="icon-lock2"></i> Change Password</a></li>
                    <li class="divider"></li>
                    <li><a href="<?php echo $uri_segment_1;?>/logout"><i class="icon-switch2"></i> Logout</a></li>
                </ul>
            </li>
        </ul>
    </div>
</div>
<!-- /main navbar -->