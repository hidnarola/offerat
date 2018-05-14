<div class="sidebar sidebar-main">
    <div class="sidebar-content">
        <!-- User menu -->
        <div class="sidebar-user">
            <div class="category-content">
                <div class="media">                    
                    <div class="media-body">
                        <a href="/dashboard"><span class="media-heading text-semibold"><i class="fa fa-user fa-3x margin-right-10"></i> <?php echo isset($logged_user) ? $logged_user['first_name'] . ' ' . $logged_user['last_name'] : 'Admin Panel' ?></span></a>
                    </div>
                    <div class="media-right media-middle">
                        <ul class="icons-list">
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle tooltip-show" title="Profile Settings" data-placement="right" data-toggle="dropdown"><i class="icon-cog3"></i></a>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li><a href="/profile"><i class="icon-vcard"></i> My Profile</a></li>
                                    <li><a href="/profile/change_password"><i class="icon-lock2"></i> Change Password</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- /user menu -->
    </div>
</div>