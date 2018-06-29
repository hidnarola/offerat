<div class="row">
    <div class="col-md-12">
        <div class="panel panel-flat">            
            <div class="panel-body">
                <div id="notification_error_wrapper" class="alert alert-danger alert-bordered display-none">
                    <span id="notification_error_msg"></span>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="panel panel-body border-top-info">
                            <ul class="list-group"> 
                                <?php for ($i = 1; $i <= 5; $i++) { ?>
                                    <li class="list-group-item">
                                        Position <?php echo $i; ?> : 
                                        <span class="label border-left-info label-striped"><?php echo (@$proirity_list[$i] > 0) ? @$proirity_list[$i] : 0; ?> Users</span>
                                    </li> 
                                <?php } ?>
                            </ul>            
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="panel panel-body border-top-info">            
                            <ul class="list-group"> 
                                <li class="list-group-item">
                                    Number of users receiving push notifications from you :
                                    <span class="label border-left-info label-striped"><?php echo $enable_notification_count; ?></span>
                                </li> 
                                <li class="list-group-item">
                                    Users who has checked your store on last month : 
                                    <span class="label border-left-info label-striped"><?php echo $click_count; ?></span>
                                </li>                
                            </ul>
                        </div>
                    </div>    
                </div>
                <hr>
                <div class="tabbable">
                    <div class="tab-content">                            
                        <div class="row">
                            <div class="col-md-12">                                    
                                <div class="tab-content">
                                    <div class="tab-pane active" id="highlighted-tab1">
                                        <div class="table-responsive popular_list dt-first-col-mw nipl_table_listing col-md-12 mt-20">
                                            <table id="notification_dttable" class="table table-striped datatable-basic custom_dt width-100-per">
                                                <thead>
                                                    <tr>
                                                        <th>Added Date</th>
                                                        <th>Broadcast Date</th>
                                                        <th>Expiry Date</th>
                                                        <th>Notification Type</th>                                                                                                                        
                                                        <th>Impressions</th>
                                                        <th>Views</th>                                                            
                                                    </tr>
                                                    <tr>
                                                        <th>Added Date</th>
                                                        <th>Broadcast Date</th>
                                                        <th>Expiry Date</th>
                                                        <th>Notification Type</th>
                                                        <th>Impressions</th>
                                                        <th>Views</th>
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
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    $(document).ready(function () {

        var notification_type_arr = {
            '0': "Image",
            '1': "Video",
            '2': "Text",
        };

        // Setup - add a text input to each footer cell
        $('#notification_dttable thead tr:eq(0) th').each(function () {
            var title = $(this).text();
            if (title !== 'Actions') {
                if (title === 'Added Date' || title === 'Broadcast Date' || title === 'Expiry Date') {
                    $(this).html('<input type="text" class="form-control daterange-basic-datatable" placeholder="' + title + '" />');
                } else {
                    $(this).html('<input type="text" class="form-control" placeholder="' + title + '" />');
                }
            }
        });

        var table = $('#notification_dttable').DataTable({
            "dom": '<"top"<"dttable_lenth_wrapper"fl>>rt<"bottom"pi><"clear">',
            "processing": true,
            "serverSide": true,
            "scrollX": true,
            "scrollCollapse": true,
            "orderCellsTop": false,
            "aaSorting": [[0, 'desc']],
            language: {
                search: '<span>Filter :</span> _INPUT_',
                lengthMenu: '<span>Show :</span> _MENU_',
                processing: "<div id='dt_loader'><i class='icon-spinner9 spinner fa-4x' style='z-index:10'></i></div>"
            },
            "columns": [
                {
                    'data': 'offer_announcement_created_date',
                    "visible": true,
                    "name": 'offer_announcement.created_date',
                    "render": function (data, type, full, meta) {
                        return get_dd_mm_yyyy_hh_min_DateTime(full.offer_announcement_created_date, '-');
                    }
                },
                {
                    'data': 'offer_announcement_broadcasting_time',
                    "visible": true,
                    "name": 'offer_announcement.broadcasting_time',
                    "render": function (data, type, full, meta) {
                        return get_dd_mm_yyyy_hh_min_DateTime(full.offer_announcement_broadcasting_time, '-');
                    }
                },
                {
                    'data': 'offer_announcement_expiry_time',
                    "visible": true,
                    "name": 'offer_announcement.expiry_time',
                    "render": function (data, type, full, meta) {
                        return get_dd_mm_yyyy_hh_min_DateTime(full.offer_announcement_expiry_time, '-');
                    }
                },
                {
                    'data': 'offer_announcement_offer_type',
                    "visible": true,
                    "name": 'offer_announcement.offer_type',
                    "render": function (data, type, full, meta) {
                        var notification_type = notification_type_arr[full.offer_announcement_offer_type];
                        return notification_type;
                    }
                },
                {
                    'data': 'offer_announcement_impression_count',
                    "visible": true,
                    "name": 'offer_announcement.impression_count',
                },
                {
                    'data': 'offer_announcement_view_count',
                    "visible": true,
                    "name": 'offer_announcement.view_count',
                }
            ],
            initComplete: function () {
                var tableColumns = table.settings().init().columns;
                this.api().columns().every(function (index) {
                    if (tableColumns[index].name == 'offer_announcement.offer_type') {
                        var column = this;
                        var select = $('<select class="form-control"><option value="">Select</option></select>')
                                .appendTo($('th:nth-child(' + (index + 1) + '):first').empty())
                                .on('change', function () {
                                    var val = $.fn.dataTable.util.escapeRegex(
                                            $(this).val()
                                            );
                                    column
                                            .search(val ? val : '', true, false)
                                            .draw();
                                });
                        if (tableColumns[index].name == 'offer_announcement.offer_type') {
                            for (var key in notification_type_arr) {
                                if (notification_type_arr.hasOwnProperty(key)) {
                                    select.append('<option value="' + key + '">' + notification_type_arr[key] + '</option>');
                                }
                            }

                        }
                    }
                });
            },
            'fnServerData': function (sSource, aoData, fnCallback) {

                var req_obj = {};
                aoData.forEach(function (data, key) {
                    req_obj[data['name']] = data['value'];
                });

                req_obj['datatable_date_range'] = [
                    {'column': 'offer_announcement.created_date', 'filter_format': 'Y-m-d', 'range_deliminator': '-'},
                    {'column': 'offer_announcement.broadcasting_time', 'filter_format': 'Y-m-d', 'range_deliminator': '-'},
                    {'column': 'offer_announcement.expiry_time', 'filter_format': 'Y-m-d', 'range_deliminator': '-'},
                ];

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