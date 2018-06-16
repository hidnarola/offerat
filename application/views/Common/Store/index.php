<div id="country_dttable_wrapper_row" class="row">
    <div class="col-md-12">
        <div class="panel panel-flat">
            <form id="form" method="post">
                <div class="panel-body">
                    <div class="tabbable">
                        <div class="tab-content">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row col-md-12">
                                        <div class="tbl-btn-wrap pull-right">
                                            <a href="super-admin/country" class="btn bg-teal-400 btn-labeled"><b><i class="icon-sync"></i></b>Refresh</a>
                                            <a href="super-admin/country/save" class="btn btn-primary btn-labeled"><b><i class="icon-plus22"></i></b>Add Store</a>                                            
                                        </div>
                                    </div>

                                    <div class="table-responsive popular_list dt-first-col-mw nipl_table_listing col-md-12 mt-20">
                                        <table id="country_dttable" class="table table-striped datatable-basic custom_dt width-100-per">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Store Name</th>
                                                    <th>First Name</th>
                                                    <th>Last Name</th>
                                                    <th>Email Id</th>
                                                    <th>Telephone No.</th>
                                                    <th>Mobile No.</th>
                                                    <th>Actions</th>
                                                </tr>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Store Name</th>
                                                    <th>First Name</th>
                                                    <th>Last Name</th>
                                                    <th>Email Id</th>
                                                    <th>Telephone No.</th>
                                                    <th>Mobile No.</th>
                                                    <th>Actions</th>
                                                </tr>
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
<?php
$this->load->view('Common/delete_alert');
?>