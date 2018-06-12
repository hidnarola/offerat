<div class="row">
    <div class="col-md-12">
        <div class="panel panel-flat">
            <form id="form" method="post">
                <div class="panel-body">
                    <div class="tabbable">
                        <div class="tab-content">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row col-md-12 text-right">
                                        <div class="tbl-btn-wrap">                                                          
                                            <div class="form-group">
                                                <a href="<?php echo $add_url; ?>" class="btn bg-primary btn-labeled"><b><i class="icon-plus2"></i></b>
                                                    <?php 
                                                    if(isset($type) && $type == 'offers')
                                                        echo 'Add Offer';
                                                    elseif(isset($type) && $type == 'announcements')
                                                        echo 'Add Announcement'; 
                                                    ?>
                                                    
                                                </a>                                            
                                                <a href="<?php echo $list_url; ?>" class="btn bg-teal-400 btn-labeled"><b><i class="icon-sync"></i></b>Refresh</a>                    
                                            </div>
                                        </div>
                                    </div>

                                    <div class="table-responsive popular_list dt-first-col-mw nipl_table_listing col-md-12 mt-20">
                                        <table id="offer_dttable" class="table table-striped datatable-basic custom_dt width-100-per">
                                            <thead>

                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>

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