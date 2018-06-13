<div class="table-responsive popular_list dt-first-col-mw nipl_table_listing col-md-12 mt-20">
    <table id="notification_dttable" class="table table-striped datatable-basic custom_dt width-100-per">
        <thead>
            <tr>
                <th>Date</th>
                <th>Broadcast Date</th>
                <th>Expiry Date</th>
                <th>Notification Type</th>
                <th>Mall</th>
                <th>Store</th>                
                <th>Actions</th>
            </tr>
            <tr>
                <th>Date</th>
                <th>Broadcast Date</th>
                <th>Expiry Date</th>
                <th>Notification Type</th>
                <th>Mall</th>
                <th>Store</th>                
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>

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
                if (title === 'Date' || title === 'Broadcast Date' || title === 'Expiry Date') {
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
                        return get_dd_mm_yyyy_Date(full.offer_announcement_expiry_time, '-');
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
                    'data': 'mall_mall_name',
                    "visible": true,
                    "name": 'mall.mall_name',
                },
                {
                    'data': 'store_store_name',
                    "visible": true,
                    "name": 'store.store_name',
                },
                {
                    "sortable": false,
                    "searchable": false,
                    'data': 'offer_announcement_id_offer',
                    "visible": true,
                    "name": 'offer_announcement.id_offer',
//                    "render": function (data, type, full, meta) {
//                        
//                        var delete_url = 'admin/company/delete/';                        
//                        if (full.company_company_type == '1') {
//                            update_url += 'professional_office';
//                            delete_url += 'professional_office';                            
//                        }
//                        var links = '<a href="' + update_url + '/' + full.id + '" title="Update" class="text-primary-400 tooltip-show margin-right-3" data-placement="top"><i class="icon-pencil"></i></a>';                                                
//                        links += '<a href="javascript:void(0);" class="text-danger-400 tooltip-show margin-right-3" data-toggle="tooltip" data-placement="top" title="Delete" data-path="<?php echo base_url(); ?>' + delete_url + '/' + full.id + '" id="delete"><i class="icon-bin"></i></a>';
//                        
//                        return links;
//                    }
                },
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