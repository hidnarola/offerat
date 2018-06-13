<div class="row">
    <div class="col-md-12">
        <div class="panel panel-flat">
            <form id="form" method="post">
                <div class="panel-body">
                    <div class="tabbable">
                        <div class="tab-content">
                            <div class="row">
                                <div class="col-md-12">
                                    <ul class="nav nav-tabs">
                                        <li <?php echo (is_null($list_type)) ? 'class="active"' : ''; ?>><a href="<?php echo $list_type_url; ?>">Current</a></li>
                                        <li <?php echo (!is_null($list_type) && $list_type == 'upcoming') ? 'class="active"' : ''; ?>><a href="<?php echo $list_type_url; ?>upcoming">Upcoming</a></li>                                        
                                        <li <?php echo (!is_null($list_type) && $list_type == 'expired') ? 'class="active"' : ''; ?>><a href="<?php echo $list_type_url; ?>expired">Expired</a></li>
                                    </ul>
                                    <div class="tbl-btn-wrap text-right">                                                          
                                        <div class="form-group">
                                            <a href="<?php echo $add_url; ?>" class="btn bg-primary btn-labeled"><b><i class="icon-plus2"></i></b>
                                                <?php
                                                if (isset($notification_type) && $notification_type == 'offers')
                                                    echo 'Add Offer';
                                                elseif (isset($notification_type) && $notification_type == 'announcements')
                                                    echo 'Add Announcement';
                                                ?>

                                            </a>                                            
                                            <a href="<?php echo $list_url; ?>" class="btn bg-teal-400 btn-labeled"><b><i class="icon-sync"></i></b>Refresh</a>                    
                                        </div>
                                    </div>
                                    
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="highlighted-tab1">
                                            <?php $this->load->view('Common/Notifications/list'); ?>
                                        </div>
                                    </div>                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>