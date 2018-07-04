<div id="store_dttable_wrapper_row" class="row">

    <div class="col-md-12">
        <div class="panel panel-flat">            
            <form id="form" method="post">
                <div class="panel-body">
                    <div id="store_error_wrapper" class="alert alert-danger alert-bordered display-none">
                        <span id="store_error_msg"></span>
                    </div>
                    <div class="tabbable">
                        <div class="tab-content">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive popular_list dt-first-col-mw nipl_table_listing col-md-12 mt-20">
                                        <table id="store_dttable" class="table table-striped datatable-basic custom_dt width-100-per">
                                            <thead>
                                                <tr>
                                                    <th>Mall Name</th>
                                                    <th>From-To</th>
                                                    <th>Current Offers Exist</th>
                                                    <th>Validity</th>
                                                    <th>Mall ID</th>
                                                </tr>
                                                <tr>
                                                    <th>Mall Name</th>
                                                    <th>From-To</th>
                                                    <th>Current Offers Exist</th>
                                                    <th>Validity</th>
                                                    <th>Mall ID</th>
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
<script type="text/javascript">
    $(document).ready(function () {
        // Setup - add a text input to each footer cell
        $('#store_dttable thead tr:eq(0) th').each(function () {
            var title = $(this).text();
            if (title === 'Mall Name') {
                $(this).html('<input type="text" class="form-control" placeholder="' + title + '" />');
            }
        });
        var table = $('#store_dttable').DataTable({
            "dom": '<"top"<"dttable_lenth_wrapper"l>>rt<"bottom"pi><"clear">',
            "processing": true,
            "serverSide": true,
            "scrollX": true,
            "scrollCollapse": true,
            "orderCellsTop": false,
            "aaSorting": [[4, 'desc']],
            language: {
                search: '<span>Filter :</span> _INPUT_',
                lengthMenu: '<span>Show :</span> _MENU_',
                processing: "<div id='dt_loader'><i class='icon-spinner9 spinner fa-4x' style='z-index:10'></i></div>"
            },
            "columns": [
                {
                    'data': 'mall_mall_name',
                    "visible": true,
                    "name": 'mall.mall_name',
                },
                {
                    'data': 'sales_from_to',
                    "visible": true,
                    "name": 'mall.mall_name',
                    "render": function (data, type, full, meta) {
                        var data1 = full.sales_from_to;
                        if (data1 !== null)
                            return findAndReplace(data1, ",", "<br>")
                        else
                            return '';
                    }
                },
                {
                    'data': 'expiry_time',
                    "visible": true,
                    "name": 'expiry_time',
                    "render": function (data, type, full, meta) {
                        if (full.expiry_time !== '0000-00-00 00:00:00')
                            return get_dd_mm_yyyy_Date(full.expiry_time, '-');
                        else if (full.expiry_time === '0000-00-00 00:00:00')
                            return 'Yes';
                    }
                },
                {
                    'data': 'validity',
                    "visible": true,
                    "name": 'expiry_time',
                    "render": function (data, type, full, meta) {
                        var status = '<span class="label label-success label-rounded">Valid</span>';
                        if (full.validity === 'Invalid') {
                            status = '<span class="label label-danger label-rounded">Invalid</span>';
                        } else if (full.validity === 'No match found')
                            status = '<span class="label label-info label-rounded">No match found</span>';
                        return status;
                    }
                },
                {
                    'data': 'mall_id_mall',
                    "visible": false,
                    "name": 'mall.id_mall',
                },
            ],
            initComplete: function () {
                var tableColumns = table.settings().init().columns;
                this.api().columns().every(function (index) { });
            },
            'fnServerData': function (sSource, aoData, fnCallback) {

                var req_obj = {};
                aoData.forEach(function (data, key) {
                    req_obj[data['name']] = data['value'];
                });

                $.ajax({
                    'dataType': 'json',
                    'type': 'POST',
                    'url': "<?php echo $filter_list_url; ?>",
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