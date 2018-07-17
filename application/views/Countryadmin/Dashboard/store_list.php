<div id="store_dttable_wrapper_row">
    <div class="col-md-12">
        <div class="panel panel-flat">            
            <form id="form" method="post">
                <div class="">                    
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
                                                    <th>Added Date</th>
                                                    <th>Store Name</th>                                                    
                                                    <th>Action</th>
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
<?php $this->load->view('Common/Store/details_modal'); ?>
<script type="text/javascript">
    $(document).on('click', '.view_store_details', function () {
        var store_id = $(document).find(this).attr('data-id');
        displayElementBlock('loader');
        var urlGStoreDetailsURL = '<?php echo $store_details_url; ?>' + store_id;
        $.ajax({
            'method': 'GET',
            'url': urlGStoreDetailsURL,
            'success': function (response) {
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

        var table = $('#store_dttable').DataTable({
            "dom": '<"top"<"dttable_lenth_wrapper">>rt<"bottom"pi><"clear">',
            "processing": true,
            "serverSide": true,
            "scrollX": true,
            "scrollCollapse": true,
            "orderCellsTop": false,
            "aaSorting": [[0, 'desc']],
            language: {
//                search: '<span>Filter :</span> _INPUT_',
                lengthMenu: '<span>Show :</span> _MENU_',
                processing: "<div id='dt_loader'><i class='icon-spinner9 spinner fa-4x' style='z-index:10'></i></div>"
            },
            "columns": [
                {
                    'data': 'store_created_date',
                    "visible": true,
                    "sortable": false,
                    "searchable": false,
                    "name": 'store.created_date',
                    "render": function (data, type, full, meta) {
                        return get_dd_mm_yyyy_hh_min_DateTime(full.store_created_date, '-');
                    }
                },
                {
                    'data': 'store_store_name',
                    "visible": true,
                    "sortable": false,
                    "searchable": false,
                    "name": 'store.store_name',
                },
                {
                    "visible": true,
                    "sortable": false,
                    "searchable": false,
                    "render": function (data, type, full, meta) {
                        var links = '';
                        links += '<a href="javascript:void(0);" target="_blank" title="View Details" data-id="' + full.store_id_store + '" class="btn btn-primary btn-xs tooltip-show margin-right-3 view_store_details" data-placement="top"><i class="icon-eye"></i></a>   ';                        
                        return links;
                    }
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
                    'url': "<?php echo $store_filter_list_url; ?>",
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