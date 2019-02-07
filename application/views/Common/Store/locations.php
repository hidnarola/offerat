<div class="col-md-12">
    <form method="POST" action="<?php echo $action_url; ?>" enctype="multipart/form-data" class="form-validate-jquery" name="manage_record" id="manage_record">
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
                            <div class="col-md-12 col-xs-12 text-right">
                                <div class="form-group">
                                    <div>
                                        <input type="checkbox" class="styled-checkbox-1" name="select_all" id="select_all"  placeholder="" value="">
                                        <span class="text-size-mini">Check All</span>            
                                    </div>
                                </div>        
                            </div>
                            <div class="col-md-12 col-xs-12">
                                <table class="table table-hover table-bordered table-responsive">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Branch</th>
                                            <th>Mall</th>
                                            <th>Latitude</th>
                                            <th>Longitude</th>
                                            <th>Contact 1</th>
                                            <th>Contact 2</th>
                                            <th>Contact 3</th>
                                            <th>Email</th>
                                            <th width='20%'>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($store_locations as $loc) { ?>
                                            <tr>
                                                <td>
                                                    <div class="checkbox">
                                                        <input type="checkbox" class="checkbox" name="delete_location_ids[]" id="delete_location_id<?php echo $loc['id_store_location']; ?>" placeholder="" value="<?php echo $loc['id_store_location']; ?>"><label for="delete_location_id<?php echo $loc['id_store_location']; ?>"></label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <?= (!empty($loc['branch_name'])) ? $loc['branch_name'] : '---' ?>
                                                </td>
                                                <td>
                                                    <?= (!empty($loc['mall_name'])) ? $loc['mall_name'] : '---' ?>
                                                </td>
                                                <td><?= (!empty($loc['latitude']) ? $loc['latitude'] : '---') ?></td>
                                                <td><?= (!empty($loc['longitude']) ? $loc['longitude'] : '---') ?></td>
                                                <td><?= (!empty($loc['contact_number']) ? $loc['contact_number'] : '---') ?></td>
                                                <td><?= (!empty($loc['contact_number_1']) ? $loc['contact_number_1'] : '---') ?></td>
                                                <td><?= (!empty($loc['contact_number_2']) ? $loc['contact_number_2'] : '---') ?></td>
                                                <td><?= (!empty($loc['email']) ? $loc['email'] : '---') ?></td>
                                                <td>
                                                    <a title="Edit Location" data-id="<?= $loc['id_store_location'] ?>" class="btn btn-info edit_location_button button-xs"><i class="fa fa-pencil"></i></a>&nbsp;
                                                    <a href="https://www.google.com/search?q=<?= $loc['latitude'].'+'.$loc['longitude'] ?>" target="_blank" title="Show Location" data-latitude="<?= $loc['latitude'] ?>" data-longitude="<?= $loc['longitude'] ?>" class="btn btn-primary button-xs"><i class="fa fa-location-arrow"></i></a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-12">&nbsp;</div>
                            <div class="col-md-12 text-right">
                                <input type="hidden" id="checked_val" name="checked_val"/>
                                <a href="<?php echo $back_url ?>" class="btn bg-grey-300 btn-labeled"><b><i class="icon-arrow-left13"></i></b>Back</a>
                                <button type="submit" id="delete_locations" class="btn bg-danger btn-labeled btn-labeled-right"><b><i class="icon-arrow-right14"></i></b>Delete</button>
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
    </form>
</div>

<div class="modal fade hide" id="store_location_modal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Location</h4>
            </div>
            <div class="modal-body">
                <div id="store-location-map" style="width: 100%; height: 350px;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade hide" id="edit_location_modal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Edit Branch</h4>
            </div>
            <div class="modal-body" id="edit_location_modal_body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php
$this->load->view('Common/delete_alert');
$this->load->view('Common/message_alert');
?>

<script language="javascript">
    var select_all = document.getElementById("select_all");
    var checkboxes = document.getElementsByClassName("checkbox");

    //select all checkboxes
    select_all.addEventListener("change", function (e) {
        for (i = 0; i < checkboxes.length; i++) {
            checkboxes[i].checked = select_all.checked;
        }
    });

    for (var i = 0; i < checkboxes.length; i++) {
        checkboxes[i].addEventListener('change', function (e) {
            if (this.checked == false) {
                select_all.checked = false;
            }
            if (document.querySelectorAll('.checkbox:checked').length == checkboxes.length) {
                select_all.checked = true;
            }
        });
    }

    $(document).on('click', '#delete_locations', function () {
        var historySelectList = $('select#delete_location_id');
        var selectedValue = $('option:selected', historySelectList).val();

        var checkedValues = $('input:checkbox:checked').map(function () {
            return this.value;
        }).get();

        $('#checked_val').val(checkedValues);

        if ($('#checked_val').val() != '') {
            $(document).find("#deleteConfirm").modal('show');
            $(document).on("click", ".yes_i_want_delete", function (e) {
                var val = $(this).val();
                if (val == 'yes') {
                    $(document).find('#manage_record').submit();
                }
            });
            return false;
        } else {
            $("#message_popup").modal('show');
            $("#alert_message").html("Please select Record to Delete");
            return false;
        }
    });


    $(document).ready(function () {
        // Edit Location
        $('.edit_location_button').click(function () {
            var id = $(this).attr('data-id');
            var url = base_url + 'country-admin/stores/get_ajax_store_location_data';

            $.ajax({
                url: url,
                type: 'POST',
                data: {id_store_location: id},
                dataType: 'HTML',
                success: function (data) {
                    $("#edit_location_modal_body").empty().html(data);
                    $("#edit_location_modal").removeClass("hide");
                    $("#edit_location_modal").modal('show');
                }
            });
        });

        //Show store location in map
        $(".show-location-on-map").click(function () {
            var latitude = $(this).attr('data-latitude');
            var longitude = $(this).attr('data-longitude');

            initMap(latitude, longitude);
            $("#store_location_modal").removeClass("hide");
            $("#store_location_modal").modal("show");
        });
    });

    function initMap(latitude, longitude) {
        latitude = parseFloat(latitude);
        longitude = parseFloat(longitude);

        var myLatLng = {lat: latitude, lng: longitude};

        var map = new google.maps.Map(document.getElementById('store-location-map'), {
            zoom: 7,
            center: myLatLng
        });

        var marker = new google.maps.Marker({
            position: myLatLng,
            animation: google.maps.Animation.DROP,
            map: map,
        });
    }
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=<?= GOOGLE_API_KEY ?>&callback=initMap"></script>