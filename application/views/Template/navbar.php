<?php
$uri_segment_1 = strtolower($this->uri->segment(1));
?>
<!-- Main navbar -->
<div class="navbar navbar-inverse">
    <div class="navbar-header">
        <a class="navbar-brand" href="<?php echo $uri_segment_1; ?>/dashboard"><span class="logo-text"><?php echo SITENAME; ?></span></a>

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
                <a href="login/logout" class="btn bg-teal logout-btn"><i class="icon-switch2"></i> Logout</a>                    
            </li>
        </ul>
    </div>
</div>
<!-- /main navbar -->