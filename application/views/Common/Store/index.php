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
                                    <div class="row col-md-12">
                                        <div class="tbl-btn-wrap pull-right">
                                            <a href="super-admin/country" class="btn bg-teal-400 btn-labeled"><b><i class="icon-sync"></i></b>Refresh</a>
                                            <a href="super-admin/country/save" class="btn btn-primary btn-labeled"><b><i class="icon-plus22"></i></b>Add Store</a>                                            
                                        </div>
                                    </div>

                                    <div class="table-responsive popular_list dt-first-col-mw nipl_table_listing col-md-12 mt-20">
                                        <table id="store_dttable" class="table table-striped datatable-basic custom_dt width-100-per">
                                            <thead>
                                                <tr>                                                                                                   
                                                    <th>Added Date</th>
                                                    <th>Store Name</th>
                                                    <th>Status</th>
                                                    <th>First Name</th>
                                                    <th>Last Name</th>
                                                    <th>Actions</th>
                                                    <th>Website</th>
                                                    <th>Facebook Page</th>     
                                                    <th>Store ID</th>     
                                                </tr>
                                                <tr>                                                                                                       
                                                    <th>Added Date</th>
                                                    <th>Store Name</th>
                                                    <th>Status</th>
                                                    <th>First Name</th>
                                                    <th>Last Name</th>                                                    
                                                    <th>Actions</th>
                                                    <th>Website</th>
                                                    <th>Facebook Page</th> 
                                                    <th>Store ID</th>     
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

    $(document).on('click', '.view_store_details', function () {
        var store_id = $(document).find(this).attr('data-id');

        displayElementBlock('loader');
        var urlGStoreDetailsURL = '<?php echo $store_details_url; ?>' + store_id;
        $.ajax({
            'method': 'GET',
            'url': urlGStoreDetailsURL,
            'success': function (response) {
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
            'error': function () {
                displayServerMsg('store_error_wrapper', 'store_error_msg', 'Something went wrong! please try again later.');
                displayElementNone('loader');
            },
        });
    });



    $(document).ready(function () {
        var status_arr = {
            '-1': "Not Verified",
            '0': "Active",
            '1': "Inactive"
        };

        // Setup - add a text input to each footer cell
        $('#store_dttable thead tr:eq(0) th').each(function () {
            var title = $(this).text();
            if (title !== 'Actions') {
                if (title === 'Added Date') {
                    $(this).html('<input type="text" class="form-control daterange-basic-datatable" placeholder="' + title + '" />');
                } else {
                    $(this).html('<input type="text" class="form-control" placeholder="' + title + '" />');
                }
            }
        });

        var table = $('#store_dttable').DataTable({
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
                    'data': 'store_created_date',
                    "visible": true,
                    "name": 'store.created_date',
                    "render": function (data, type, full, meta) {
                        return get_dd_mm_yyyy_hh_min_DateTime(full.store_created_date, '/');
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
                    "render": function (data, type, full, meta) {
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
                    'data': 'user_first_name',
                    "visible": true,
                    "name": 'user.first_name'
                },
                {
                    'data': 'user_last_name',
                    "visible": true,
                    "name": 'user.last_name',
                },
                {
                    "visible": true,
                    "sortable": false,
                    "searchable": false,
                    "render": function (data, type, full, meta) {
                        var links = '';
                        links += '<a href="javascript:void(0);" target="_blank" title="View Details" data-id="' + full.store_id_store + '" class="btn btn-primary btn-xs tooltip-show margin-right-3 view_store_details" data-placement="top"><i class="icon-eye"></i></a>   ';
                        if (full.store_website !== '' && full.store_website !== undefined) {
                            links += '<a href="//' + full.store_website + '" target="_blank" title="Website" class="btn bg-brown btn-xs tooltip-show margin-right-3" data-placement="top"><i class="icon-earth"></i></a>   ';
                        }
                        if (full.store_facebook_page !== '' && full.store_facebook_page !== undefined) {
                            links += '<a href="//' + full.store_facebook_page + '" target="_blank" title="Facebook Page" class="btn bg-slate btn-xs tooltip-show margin-right-3" data-placement="top"><i class="icon-facebook"></i></a>   ';
                        }
                        links += '<a href="<?php echo base_url() ?>super-admin/store/save/' + full.store_id_store + '" title="Update" class="btn bg-teal btn-xs  tooltip-show margin-right-3" data-placement="top"><i class="icon-pencil"></i></a>   ';
<?php if ($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE) { ?>
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
            ],
            initComplete: function () {
                var tableColumns = table.settings().init().columns;
                this.api().columns().every(function (index) {

                    if (tableColumns[index].name == 'store.status') {
                        var column = this;
                        var select = $('<select class="form-control"><option value="">Select</option></select>')
                                .appendTo($('#store_dttable_wrapper_row th:nth-child(' + (index + 1) + '):first').empty())
                                .on('change', function () {
                                    var val = $.fn.dataTable.util.escapeRegex(
                                            $(this).val()
                                            );
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
                    }
                });
            },
            'fnServerData': function (sSource, aoData, fnCallback) {

                var req_obj = {};
                aoData.forEach(function (data, key) {
                    req_obj[data['name']] = data['value'];
                });
                req_obj['col_eq'] = ['store.status'];
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