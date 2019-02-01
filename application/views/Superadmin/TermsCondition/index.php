<div id="country_dttable_wrapper_row">
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
                                            <a href="super-admin/terms-conditions" class="btn bg-teal-400 btn-labeled"><b><i class="icon-sync"></i></b>Refresh</a>
                                            <!--<a href="super-admin/terms-condition/save" class="btn btn-primary btn-labeled"><b><i class="icon-plus22"></i></b>Add Country</a>-->                                            
                                        </div>
                                    </div>

                                    <div class="table-responsive popular_list dt-first-col-mw nipl_table_listing col-md-12 mt-20">
                                        <table id="country_dttable" class="table table-striped datatable-basic custom_dt width-100-per">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Page Title</th>
                                                    <th>URL</th>
                                                    <th>Page Type</th>
                                                    <th>Created At</th>
                                                    <th>Actions</th>
                                                </tr>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Page Title</th>
                                                    <th>URL</th>
                                                    <th>Page Type</th>
                                                    <th>Created At</th>
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

<script type="text/javascript">
    $(document).ready(function () {
        var status_arr = {
            '0': "Active",
            '1': "Inactive",
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
            "aaSorting": [[0, 'desc']],
            language: {
                search: '<span>Filter :</span> _INPUT_',
                lengthMenu: '<span>Show :</span> _MENU_',
                processing: "<div id='dt_loader'><i class='icon-spinner9 spinner fa-4x' style='z-index:10'></i></div>"
            },
            "columns": [
                {
                    'data': 'terms_conditions_id',
                    "visible": false,
                    "name": 'terms_conditions.id'
                },
                {
                    'data': 'terms_conditions_page_title',
                    "visible": true,
                    "name": 'terms_conditions.page_title',
                },
                {
                    'data': 'terms_conditions_url',
                    "visible": true,
                    "name": 'terms_conditions.url',
                },
                {
                    'data': 'terms_conditions_page_type',
                    "visible": true,
                    "name": 'terms_conditions.page_type',
                    "render": function (data, type, full, meta) {
                        var status = '';
                        if (data === 'Terms') {
                            status = '<span class="label label-info label-rounded">' + data + '</span>';
                        } else if (data === 'Privacy') {
                            status = '<span class="label label-primary label-rounded">' + data + '</span>';
                        }
                        return status;
                    }
                },
                {
                    'data': 'terms_conditions_created_date',
                    "visible": true,
                    "name": 'terms_conditions.created_date',
                },
                {
                    "visible": true,
                    "sortable": false,
                    "searchable": false,
                    "render": function (data, type, full, meta) {                        
                        var links = '<a href="<?php echo base_url() ?>super-admin/terms-conditions/edit/' + full.terms_conditions_id + '" title="Update" class="btn btn-primary btn-xs tooltip-show margin-right-3" data-placement="top"><i class="icon-pencil"></i></a>';
                        return links;
                    }
                }
            ],
            initComplete: function () {
                var tableColumns = table.settings().init().columns;
                this.api().columns().every(function (index) {
                    if (tableColumns[index].name == 'country.status') {
                        var column = this;
                        var select = $('<select class="form-control"><option value="">Select</option></select>')
                                .appendTo($('#country_dttable_wrapper_row th:nth-child(' + (index + 1) + '):first').empty())
                                .on('change', function () {
                                    var val = $.fn.dataTable.util.escapeRegex(
                                            $(this).val()
                                            );
                                    column
                                            .search(val ? val : '', true, false)
                                            .draw();
                                });
                        if (tableColumns[index].name == 'country.status') {
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
                    'url': "<?php echo base_url() . 'super-admin/terms-conditions/filter_terms_condition' ?>",
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