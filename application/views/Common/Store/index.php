<div id="store_dttable_wrapper_row">
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
                                    <div class="row col-md-12">
                                        <div class="tbl-btn-wrap pull-right">
                                            <!--<a href="<?php echo $store_list_url; ?>" class="btn bg-teal-400 btn-labeled"><b><i class="icon-sync"></i></b>Refresh</a>-->
                                            <a href="<?php echo $add_store_url; ?>" class="btn btn-primary btn-labeled"><b><i class="icon-plus22"></i></b>Add Store</a>                                            
                                        </div>
                                    </div>

                                    <div class="table-responsive popular_list dt-first-col-mw nipl_table_listing col-md-12 mt-20">
                                        <table id="store_dttable" class="table table-striped datatable-basic custom_dt width-100-per">
                                            <thead>
                                                <tr>                                                                                                   
                                                    <th>Added Date</th>
                                                    <th>Store Name</th>
                                                    <th>Status</th>
                                                    <th>Mall</th>
                                                    <th>Category</th>
                                                    <th>Subcategory</th>
                                                    <th>Actions</th>
                                                    <th>Website</th>
                                                    <th>Facebook Page</th>     
                                                    <th>Store ID</th>
                                                    <th>Group</th>
                                                    <th>First Name</th>
                                                    <th>Last Name</th>
                                                    <th>Email</th>
                                                    <th>Contact No</th>
                                                    <th>Branch Name</th>
                                                    <th>Notes</th>
                                                </tr>
                                                <tr>                                                                                                       
                                                    <th>Added Date</th>
                                                    <th>Store Name</th>
                                                    <th>Status</th>
                                                    <th>Mall</th>
                                                    <th>Category</th>
                                                    <th>Subcategory</th>
                                                    <th>Actions</th>
                                                    <th>Website</th>
                                                    <th>Facebook Page</th> 
                                                    <th>Store ID</th>
                                                    <th>Group</th>
                                                    <th>First Name</th>
                                                    <th>Last Name</th>
                                                    <th>Email</th>
                                                    <th>Contact No</th>
                                                    <th>Branch Name</th>
                                                    <th>Notes</th>
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
$this->load->view('Common/Store/details_modal');
?>
<script type="text/javascript">
    var malls_list_arr = {
<?php
if (isset($malls_list) && sizeof($malls_list) > 0) {
    foreach ($malls_list as $list) {
        ?>
            '<?php echo $list['id_mall']; ?>': '<?php echo $list['mall_name']; ?>',
        <?php
    }
}
?>
    };
    var category_list_arr = {
<?php
if (isset($category_list) && sizeof($category_list) > 0) {
    foreach ($category_list as $list) {
        ?>
            '<?php echo $list['id_category']; ?>': '<?php echo $list['category_name']; ?>',
        <?php
    }
}
?>
    };
    var sub_category_list_arr = {
<?php
if (isset($sub_category_list) && sizeof($sub_category_list) > 0) {
    foreach ($sub_category_list as $list) {
        ?>
            '<?php echo $list['id_sub_category']; ?>': '<?php echo $list['sub_category_name']; ?>',
        <?php
    }
}
?>
    };</script>

<script type="text/javascript">
$(document).on('click', '.view_store_details', function() {
    var store_id = $(document).find(this).attr('data-id');
    displayElementBlock('loader');
    var urlGStoreDetailsURL = '<?php echo $store_details_url; ?>' + store_id;
    $.ajax({
        'method': 'GET',
        'url': urlGStoreDetailsURL,
        'success': function(response) {
            //                console.log(response);
            //                return false
            if (response !== '') {
                var obj = JSON.parse(response);
                if (obj.status === '1') {
                    $(document).find('#store_view_model').modal('show');
                    $(document).find('.details_view').html(obj.sub_view);
                    displayElementNone('loader');
                } else {
                    displayServerMsg('store_error_wrapper', 'store_error_msg', 'Something went wrong! please try again later.');
                    displayElementNone('loader');
                }
            } else {
                displayServerMsg('store_error_wrapper', 'store_error_msg', 'Something went wrong! please try again later.');
                displayElementNone('loader');
            }
        },
        'error': function() {
            displayServerMsg('store_error_wrapper', 'store_error_msg', 'Something went wrong! please try again later.');
            displayElementNone('loader');
        },
    });
});

$(document).ready(function() {
    var status_arr = {
        '-1' : "Not Verified",
        '0' : "Active",
        '1' : "Inactive"
    };
    // Setup - add a text input to each footer cell
    $('#store_dttable thead tr:eq(0) th').each(function() {
        var title = $(this).text();
        if (title !== 'Actions') {
            if (title === 'Added Date') {
                $(this).html('<input type="text" class="form-control daterange-basic-datatable" placeholder="' + title + '" />');
            } else {
                $(this).html('<input type="text" class="form-control" placeholder="' + title + '" />');
            }
        }
    });
    var buttonCommon = {
        exportOptions: {
            format: {
                body: function(data, row, column, node) {
                    // Strip $ from salary column to make it numeric
                    return column === 5 ?
                        data.replace(/[$,]/g, '') :
                        data;
                }
            }
        }
    };
    
    var table = $('#store_dttable').DataTable({
        "dom": '<"top"<"dttable_lenth_wrapper"fl>>rt<"bottom"pi><"clear">',
        "processing": true,
        "serverSide": true,
        "scrollX": true,
        "scrollCollapse": true,
//        "orderCellsTop": false,
//        "bFilter": false,
//        "searching": false,
//        "bInfo": false,
        "aaSorting": [
            [0, 'desc']
        ],
        "language": {
            search: '<span>Filter :</span> _INPUT_',
            lengthMenu: '<span>Show :</span> _MENU_',
            processing: "<div id='dt_loader'><i class='icon-spinner9 spinner fa-4x' style='z-index:10'></i></div>"
        },
        "columns": [{
                'data': 'store_created_date',
                "visible": true,
                "name": 'store.created_date',
                "render": function(data, type, full, meta) {
                    return get_dd_mm_yyyy_hh_min_DateTime(full.store_created_date, '-');
                }
            },
            {
                'data': 'store_store_name',
                "visible": true,
                "name": 'store.store_name',
            },
            {
                'data': 'store_status',
                "visible": true,
                "name": 'store.status',
                "render": function(data, type, full, meta) {
                    var status = '<span class="label label-success label-rounded">' + status_arr[full.store_status] + '</span>';
                    if (full.store_status === '1') {
                        status = '<span class="label label-danger label-rounded">' + status_arr[full.store_status] + '</span>';
                    } else if (full.store_status === '-1') {
                        status = '<span class="label label-info label-rounded">' + status_arr[full.store_status] + '</span>';
                    } else if (full.store_status === '0') {
                        status = '<span class="label label-success label-rounded">' + status_arr[full.store_status] + '</span>';
                    }
                    return status;
                }
            },
            {
                'data': 'mall_id_mall',
                "visible": true,
                "name": 'mall.id_mall',
                "render": function(data, type, full, meta) {
                    return full.mall_list;
                }
            },
            {
                'data': 'category_id_category',
                "visible": true,
                "name": 'category.id_category',
                "render": function(data, type, full, meta) {
                    return '';
                }
            },
            {
                'data': 'sub_category_id_sub_category',
                "visible": true,
                "name": 'sub_category.id_sub_category',
                "render": function(data, type, full, meta) {
                    return '';
                }
            },
            {
                "visible": true,
                "sortable": false,
                "searchable": false,
                "render": function(data, type, full, meta) {
                    var links = '';
                    links += '<a href="javascript:void(0);" target="_blank" title="View Details" data-id="' + full.store_id_store + '" class="btn btn-primary btn-xs tooltip-show margin-right-3 view_store_details" data-placement="top"><i class="icon-eye"></i></a>   ';
                    if (full.store_website !== '' && full.store_website !== undefined) {
                        links += '<a href="//' + full.store_website + '" target="_blank" title="Website" class="btn bg-brown btn-xs tooltip-show margin-right-3" data-placement="top"><i class="icon-earth"></i></a>   ';
                    }
                    if (full.store_facebook_page !== '' && full.store_facebook_page !== undefined) {
                        links += '<a href="//' + full.store_facebook_page + '" target="_blank" title="Facebook Page" class="btn bg-slate btn-xs tooltip-show margin-right-3" data-placement="top"><i class="icon-facebook"></i></a>   ';
                    }
                    links += '<a href="<?php echo $edit_store_url; ?>' + full.store_id_store + '" title="Update" class="btn bg-teal btn-xs  tooltip-show margin-right-3" data-placement="top"><i class="icon-pencil"></i></a>   ';
                    links += '<a href="<?php echo $report_url; ?>' + full.store_id_store + '" title="Report" class="btn bg-indigo btn-xs tooltip-show margin-right-3" data-placement="top"><i class="icon-clipboard3"></i></a>   ';
                    <?php if ($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE) { ?>
                    if (full.store_status == '<?php echo ACTIVE_STATUS; ?>')
                        links += '<a href="<?php echo $sponsored_store_url; ?>' + full.store_id_store + '" target="_blank" class="btn bg-pink btn-icon btn-xs tooltip-show margin-right-3" data-toggle="tooltip" data-placement="top" title="Sponsored"><i class=" icon-star-half"></i></a> ';
                    links += '<a href="javascript:void(0);" data-path="<?php echo $delete_store_url; ?>' + full.store_id_store + '" class="btn btn-danger btn-icon btn-xs tooltip-show margin-right-3" data-toggle="tooltip" data-placement="top" title="Delete" id="delete"><i class="icon-bin"></i></a>';
                    <?php } ?>
                    return links;
                }
            },
            {
                'data': 'store_website',
                "visible": false,
                "name": 'store.website',
            },
            {
                'data': 'store_facebook_page',
                "visible": false,
                "name": 'store.facebook_page',
            },
            {
                'data': 'store_id_store',
                "visible": false,
                "name": 'store.id_store',
            },
            {
                'data': 'store_group_text',
                "visible": false,
                "name": 'store.group_text',
            },
            {
                'data': 'user_first_name',
                "visible": false,
                "name": 'user.first_name',
            },
            {
                'data': 'user_last_name',
                "visible": false,
                "name": 'user.last_name',
            },
            {
                'data': 'user_email_id',
                "visible": false,
                "name": 'user.email_id',
            },
            {
                'data': 'user_mobile',
                "visible": false,
                "name": 'user.mobile',
            },
            {
                'data': 'store_location_branch_name',
                "visible": false,
                "name": 'store_location.branch_name',
            },
            {
                'data': 'store_note_text',
                "visible": false,
                "name": 'store.note_text',
            },
        ],
        dom: 'Bfrtip',
        buttons: [
            $.extend(true, {}, buttonCommon, {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [0, 1, 7, 8, 10, 11, 12, 13, 14, 15, 16]
                },
                text: 'Download'
            }),
            'pageLength'
        ],
        lengthMenu: [
            [ 10, 25, 50, 100, -1 ],
            [ '10 rows', '25 rows', '50 rows', '100 rows', 'Show all' ]
        ],
        initComplete: function() {
            var tableColumns = table.settings().init().columns;
            this.api().columns().every(function(index) {

                if (tableColumns[index].name == 'store.status' || tableColumns[index].name == 'mall.id_mall' || tableColumns[index].name == 'category.id_category' || tableColumns[index].name == 'sub_category.id_sub_category') {
                    var column = this;
                    var select = $('<select class="form-control"><option value="">Select</option></select>')
                        .appendTo($('#store_dttable_wrapper_row th:nth-child(' + (index + 1) + '):first').empty())
                        .on('change', function() {
                            
//                            var val = $.fn.dataTable.util.escapeRegex(
//                                $(this).val()
//                            );
                    
                            var val = $(this).val();
                    
                            column
                                .search(val ? val : '', true, false)
                                .draw();
                        });
                    if (tableColumns[index].name == 'store.status') {
                        for (var key in status_arr) {
                            if (status_arr.hasOwnProperty(key)) {
                                select.append('<option value="' + key + '">' + status_arr[key] + '</option>');
                            }
                        }
                    }

                    if (tableColumns[index].name == 'mall.id_mall') {
                        for (var key in malls_list_arr) {
                            if (malls_list_arr.hasOwnProperty(key)) {
                                select.append('<option value="' + key + '">' + malls_list_arr[key] + '</option>');
                            }
                        }
                    }

                    if (tableColumns[index].name == 'category.id_category') {
                        for (var key in category_list_arr) {
                            if (category_list_arr.hasOwnProperty(key)) {
                                select.append('<option value="' + key + '">' + category_list_arr[key] + '</option>');
                            }
                        }
                    }
                    if (tableColumns[index].name == 'sub_category.id_sub_category') {
                        for (var key in sub_category_list_arr) {
                            if (sub_category_list_arr.hasOwnProperty(key)) {
                                select.append('<option value="' + key + '">' + sub_category_list_arr[key] + '</option>');
                            }
                        }
                    }
                }
            });
        },
        'fnServerData': function(sSource, aoData, fnCallback) {

            var req_obj = {};
            aoData.forEach(function(data, key) {
                req_obj[data['name']] = data['value'];
            });
            req_obj['col_eq'] = ['store.status', 'mall.id_mall', 'category.id_category', 'sub_category.id_sub_category'];
            $.ajax({
                'dataType': 'json',
                'type': 'POST',
                'url': "<?php echo $filter_list_url; ?>",
                'data': req_obj,
                'success': function(data) {
                    fnCallback(data);
                }
            });
        },
        "fnDrawCallback": function() {
            var current_user = '<?= $this->loggedin_user_type ?>';
            var authenticate_user = '<?= COUNTRY_ADMIN_USER_TYPE ?>';
            if (current_user == authenticate_user) {
                table.buttons('.buttons-html5').enable();
            } else {
                table.buttons('.buttons-html5').disable();
                $('.buttons-html5').addClass('hide');
            }
        }
    });
    // Apply the search
    table.columns().every(function(index) {
        $('input[type="text"]', 'th:nth-child(' + (index + 1) + ')').on('keyup change', function() {
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
    
//    $(".custom_dt tr:first").hide();
//    $(".buttons-page-length").hide();
});
</script>