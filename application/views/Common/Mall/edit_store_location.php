<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.min.css" />

<div class="col-md-12">
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-flat">
                <div class="panel-heading">                            
                    <div class="heading-elements">
                        <ul class="icons-list">
                            <li><a data-action="collapse"></a></li>
                        </ul>
                    </div>
                </div>
                <div class="panel-body">
                    <?php if (isset($store_locations) && sizeof($store_locations) > 0) { ?>                                
                        <div class="col-md-12 col-xs-12">
                            <table class="table table-hover table-bordered table-responsive">
                                <thead>
                                    <tr>
                                        <th width="10%">#</th>
                                        <th width="50%">Store Name</th>
                                        <th width="40%">Store Floor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    foreach ($store_locations as $loc) {
                                        ?>
                                        <tr>
                                            <td><?= $i++ ?></td>
                                            <td><?= $loc['store_name'] ?></td>
                                            <td>
                                                <select class="form-control select_store_floor" data-id="<?= $loc['id_store_location'] ?>" >
                                                    <option value="">Select Store Floor</option>
                                                    <?php foreach ($store_floors as $floor) { ?>
                                                        <option value="<?= $floor ?>" <?= (!is_null($loc['store_floor_no']) && $loc['store_floor_no'] == $floor) ? 'selected' : '' ?> ><?= $floor ?></option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <?php
                    } else {
                        echo 'No Results Found.';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.all.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $(".select_store_floor").on('change', function () {
            var store_location_id = $(this).attr('data-id');
            var store_floor_no = $(this).find('option:selected').val();

            if (store_floor_no != '') {


                var data = {
                    store_location_id: store_location_id,
                    store_floor_no: store_floor_no
                };

                $.ajax({
                    url: '<?= site_url("country-admin/malls/update_store_floor_number") ?>',
                    type: 'POST',
                    data: data,
                    success: function (response) {
                        if (response == true) {
                            swal("Success!", "Store floor number has been changed successfully.", "success");
                        } else {
                            swal("Error!", "Something went wrong", "error");
                        }
                    }, error: function (jqXHR, exception) {
                        swal("Error!", "Something went wrong", "error");
                    }
                });
            } else {
                swal("Error!", "Please select any value first.", "error");
            }
        });
    });
</script>