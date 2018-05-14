<div id="category_dttable_wrapper_row" class="row">
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
                                            <a href="super-admin/category" class="btn bg-teal-400 btn-labeled"><b><i class="icon-sync"></i></b>Refresh</a>
                                            <a href="super-admin/category/save" class="btn btn-primary btn-labeled"><b><i class="icon-plus22"></i></b>Add Category</a>                                            
                                        </div>
                                    </div>

                                    <div class="table-responsive popular_list dt-first-col-mw nipl_table_listing col-md-12 mt-20">
                                        <table id="category_dttable" class="table table-striped datatable-basic custom_dt width-100-per">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Sub-category Name</th>
                                                    <th>Logo</th>
                                                    <th>Status</th>                                                    
                                                    <th>Actions</th>
                                                </tr>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Category Name</th>
                                                    <th>Logo</th>
                                                    <th>Status</th>
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

<script type="text/javascript">
    $(document).ready(function () {
        var status_arr = {
            '1': "Active",
            '0': "Inactive",
        };

        // Setup - add a text input to each footer cell
        $('#category_dttable thead tr:eq(0) th').each(function () {
            var title = $(this).text();
            if (title !== 'Actions' && title !== 'Select') {
                $(this).html('<input type="text" class="form-control" placeholder="' + title + '" />');
            }
        });

        var table = $('#category_dttable').DataTable({
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
                    'data': 'sub_category_id_sub_category',
                    "visible": true,
                    "name": 'sub_category.id_sub_category'
                },
                {
                    'data': 'sub_category_sub_category_name',
                    "visible": true,
                    "name": 'sub_category.sub_category_name'
                },
                {
                    'data': 'sub_category_sub_category_logo',
                    "visible": true,
                    "searchable": false,
                    "orderable": false,
                    "name": 'sub_category.sub_category_logo',
                    "render": function (data, type, full, meta) {
                        if (full.sub_category_sub_category_logo != '')
                            var image_html = '<img height="20px" width="20px" alt="No Image" id="sub_category_image_display" src="<?php echo sub_category_img_path; ?>' + full.sub_category_sub_category_logo + '" />';
                        else
                            var image_html = '';
                        return image_html;
                    }
                },
                {
                    'data': 'sub_category_status',
                    "visible": true,
                    "name": 'sub_category.status',
                    "render": function (data, type, full, meta) {
                        var status = '<span class="label label-success label-rounded">' + status_arr[full.sub_category_status] + '</span>';
                        if (full.sub_category_status === '0') {
                            status = '<span class="label label-danger label-rounded">' + status_arr[full.sub_category_status] + '</span>';
                        }
                        return status;
                    }
                },
                {
                    "visible": true,
                    "sortable": false,
                    "searchable": false,
                    "render": function (data, type, full, meta) {
                        var links = '<a href="<?php echo base_url() ?>super-admin/sub-category/save/' + full.sub_category_id_sub_category + '" title="Update" class="btn btn-primary  btn-xs  tooltip-show margin-right-3" data-placement="top"><i class="icon-pencil"></i></a>   ';
                        links += '<a href="javascript:void(0);" class="btn btn-danger btn-icon btn-xs tooltip-show margin-right-3" data-toggle="tooltip" data-placement="top" title="Delete" data-path="<?php echo base_url(); ?>admin/banner/delete/' + full.sub_category_id_sub_category + '" id="delete"><i class="icon-bin"></i></a>';
                        return links;
                    }
                }
            ],
            initComplete: function () {
                var tableColumns = table.settings().init().columns;
                this.api().columns().every(function (index) {
                    if (tableColumns[index].name == 'sub_category.status') {
                        var column = this;
                        var select = $('<select class="form-control"><option value="">Select</option></select>')
                                .appendTo($('#category_dttable_wrapper_row th:nth-child(' + (index + 1) + '):first').empty())
                                .on('change', function () {
                                    var val = $.fn.dataTable.util.escapeRegex(
                                            $(this).val()
                                            );
                                    column
                                            .search(val ? val : '', true, false)
                                            .draw();
                                });
                        if (tableColumns[index].name == 'sub_category.status') {
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

                $.ajax({
                    'dataType': 'json',
                    'type': 'POST',
                    'url': "<?php echo base_url() . 'superadmin/subcategory/filter_sub_categories/' . $category_id ?>",
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