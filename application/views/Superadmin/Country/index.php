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
                                            <a href="admin/countries" class="btn bg-teal-400 btn-labeled"><b><i class="icon-sync"></i></b>Refresh</a>
                                            <a href="admin/countries/save" class="btn btn-primary btn-labeled"><b><i class="icon-plus22"></i></b>Add Country</a>                                            
                                        </div>
                                    </div>

                                    <div class="table-responsive popular_list dt-first-col-mw nipl_table_listing col-md-12 mt-20">
                                        <table id="country_dttable" class="table table-striped datatable-basic custom_dt width-100-per">
                                            <thead>
                                                <tr>
                                                    <th>Country</th>
<!--                                                    <th>Admin Name</th>
                                                    <th>Admin Username</th>
                                                    <th>Flag</th>
                                                    <th>Status</th>                                                    
                                                    <th>Actions</th>-->
                                                </tr>
                                                <tr>
                                                    <th>Country</th>
<!--                                                    <th>Admin Name</th>
                                                    <th>Admin Username</th>
                                                    <th>Flag</th>
                                                    <th>Status</th>                                                    
                                                    <th>Actions</th>-->
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
//$this->load->view('Admin/Common/alert_confirmation');
//$this->load->view('Admin/Common/alert_information');
?>

<script type="text/javascript">
    $(document).ready(function () {        
        var status_arr = {
            '1': "Active",
            '0': "Inactive",
        };

        // Setup - add a text input to each footer cell
        $('#country_dttable thead tr:eq(0) th').each(function () {
            var title = $(this).text();
            if (title !== 'Actions' && title !== 'Select') {
                $(this).html('<input type="text" class="form-control" placeholder="' + title + '" />');
            }
        });

        var table = $('#country_dttable').DataTable({
            "dom": '<"top"<"dttable_lenth_wrapper"fl>>rt<"bottom"pi><"clear">',
            "processing": true,
            "serverSide": true,
            "scrollX": true,
            "scrollCollapse": true,
            "orderCellsTop": false,
//            "aaSorting": [[1, 'desc']],
//            "fixedColumns": {
//                leftColumns: 0,
//                rightColumns: 1
//            },
            language: {
                search: '<span>Filter :</span> _INPUT_',
                lengthMenu: '<span>Show :</span> _MENU_',
                processing: "<div id='dt_loader'><i class='icon-spinner9 spinner fa-4x' style='z-index:10'></i></div>"
            },
            "columns": [                
                {
                    'data': 'country_country_name',
                    "visible": true,
                    "name": 'country.country_name',
                }                
            ],
            initComplete: function () {
                var tableColumns = table.settings().init().columns;
                this.api().columns().every(function (index) {
                });
            },
            'fnServerData': function (sSource, aoData, fnCallback) {

                var req_obj = {};
                aoData.forEach(function (data, key) {
                    req_obj[data['name']] = data['value'];
                });

                $.ajax({
                    'dataType': 'json',
                    'type': 'POST',
                    'url': "<?php echo base_url() . 'superadmin/country/filter_countries' ?>",
                    'data': req_obj,
                    'success': function (data) {
                        fnCallback(data);
                    }
                });
            }
        });
        // Apply the search
        table.columns().every(function (index) {
            $('input[type="text"]', 'th:nth-child(' + (index + 1) + ')').on('keyup change', function () {
                table
                        .column(index)
                        .search(this.value)
                        .draw();
            });
        });
        $('.dataTables_length select').select2({
            minimumResultsForSearch: Infinity,
            width: 'auto'
        });
    });
</script>