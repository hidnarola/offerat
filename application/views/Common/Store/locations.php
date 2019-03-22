<?php
if ($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE) {
    $user_url_prefix = 'country-admin';
} else if ($this->loggedin_user_type == STORE_OR_MALL_ADMIN_USER_TYPE) {
    $user_url_prefix = 'mall-store-user';
}
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.min.css" />
<link rel="stylesheet" href="<?= site_url('assets/front/css/store_branch_location.css') ?>" />

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
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Branch</th>
                                                <th>Mall</th>
                                                <th>Latitude</th>
                                                <th>Longitude</th>
                                                <th>Contact 1</th>
                                                <th>Email</th>
                                                <th width='15%'>Action</th>
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
                                                    <td><?= (!empty($loc['email']) ? $loc['email'] : '---') ?></td>
                                                    <td>
                                                        <a title="Edit Location" data-id="<?= $loc['id_store_location'] ?>" class="btn btn-info edit_location_button button-xs"><i class="fa fa-pencil"></i></a>
                                                        <a href="https://www.google.com/search?q=<?= $loc['latitude'] . '+' . $loc['longitude'] ?>" target="_blank" title="Show Location" data-latitude="<?= $loc['latitude'] ?>" data-longitude="<?= $loc['longitude'] ?>" class="btn btn-primary button-xs"><i class="fa fa-location-arrow"></i></a>
                                                        <?php if ($loc['location_type'] == 1 && !empty($loc['branch_name'])) { ?>
                                                            <button type="button" class="btn btn-warning show_branch_location" 
                                                                    data-storelocationid="<?= $loc['id_store_location'] ?>"
                                                                    data-latitude="<?= $loc['latitude'] ?>"
                                                                    data-longitude="<?= $loc['longitude'] ?>">
                                                                <i class="fa fa-map-marker"></i>
                                                            </button>
                                                        <?php } else { ?> 
                                                            <button type="button" class="btn btn-warning" disabled ><i class="fa fa-map-marker"></i></button>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
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

<div class="modal fade hide" id="edit_branch_location_modal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Edit Store Location</h4>
            </div>
            <div class="modal-body">
                <div id="place-search-input"></div>
                <div id="map"></div>

                <input type="hidden" id="current_branch_latitude" />
                <input type="hidden" id="current_branch_longitude" />
                <input type="hidden" id="id_store_location" />
            </div>
            <div class="modal-footer">
                <button type="button" id="save_branch_location" class="btn btn-info">Save</button>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.all.min.js"></script>
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
            var url = base_url + '<?= $user_url_prefix ?>/stores/get_ajax_store_location_data';

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

        // Edit Location
        $('#save_branch_location').click(function () {
            var current_branch_latitude = $("#current_branch_latitude").val();
            var current_branch_longitude = $("#current_branch_longitude").val();
            var id_store_location = $("#id_store_location").val();

            if (current_branch_latitude == '' && current_branch_longitude == '') {
                swal({
                    title: "Error!",
                    text: "Store branch location not found. Please select any location for store branch.",
                    type: "error"
                });
                return false;
            }

            var data = {
                current_branch_latitude: current_branch_latitude,
                current_branch_longitude: current_branch_longitude,
                id_store_location: id_store_location
            };

            var url = base_url + '<?= $user_url_prefix ?>/stores/branch/location/save';
            $.ajax({
                url: url,
                type: 'POST',
                data: data,
                success: function (response) {
                    swal({
                        title: "Success!",
                        text: "Store branch locations has been updated successfully.",
                        type: "success"
                    }).then(function () {
                        window.location.reload();
                    });
                }
            });
        });

        //Chnage and store branch location
        $(".show_branch_location").click(function () {
            var id_store_location = $(this).attr('data-storelocationid');
            var latitude = parseFloat($(this).attr('data-latitude'));
            var longitude = parseFloat($(this).attr('data-longitude'));

            $("#edit_branch_location_modal").removeClass('hide');

            $("#place-search-input").empty();
            $("#place-search-input").html('<input id="pac-input" class="controls" type="text" placeholder="Search Box">');

            $("#map").empty();
            initMap(latitude, longitude);

            $("#id_store_location").val(id_store_location);
            $("#edit_branch_location_modal").modal('show');
        });
    });

    function initMap(latitude, longitude) {
        var is_address_found = false;
        var branchLatLng = {lat: -25.363, lng: 131.044};

        if (latitude != '' && longitude != '') {
            branchLatLng = {lat: latitude, lng: longitude};
        }

        var map = new google.maps.Map(document.getElementById('map'), {
            center: branchLatLng,
            zoom: 15,
            mapTypeId: 'roadmap',
        });

        var marker = new google.maps.Marker({
            position: branchLatLng,
            map: map,
            draggable: true,
        });

        get_location_of_dragend_marker(marker);

        // Create the search box and link it to the UI element.
        var input = document.getElementById('pac-input');

        var searchBox = new google.maps.places.SearchBox(input);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

        // Bias the SearchBox results towards current map's viewport.
        map.addListener('bounds_changed', function () {
            searchBox.setBounds(map.getBounds());
        });

        var markers = [];
        // Listen for the event fired when the user selects a prediction and retrieve
        // more details for that place.
        searchBox.addListener('places_changed', function () {
            var places = searchBox.getPlaces();

            if (places.length == 0) {
                return;
            } else {
                if (marker && marker.setMap) {
                    marker.setMap(null);
                }
            }

            // Clear out the old markers.
            markers.forEach(function (marker) {
                marker.setMap(null);
            });

            markers = [];

            // For each place, get the icon, name and location.
            var bounds = new google.maps.LatLngBounds();
            places.forEach(function (place) {
                if (place.address_components) {
                    if (!place.geometry) {
                        swal({
                            title: "Error!",
                            text: "Your searched place contains no location. Please try again.",
                            type: "error"
                        });
                        return;
                    }

                    var icon = {
                        url: place.icon,
                        size: new google.maps.Size(71, 71),
                        origin: new google.maps.Point(0, 0),
                        anchor: new google.maps.Point(17, 34),
                        scaledSize: new google.maps.Size(25, 25)
                    };

                    var current_marker = new google.maps.Marker({
                        map: map,
                        icon: icon,
                        title: place.name,
                        position: place.geometry.location,
                        draggable: true,
                    })

                    // Create a marker for each place.
                    markers.push(current_marker);
                    get_location_of_dragend_marker(current_marker);

                    if (place.geometry.viewport) {
                        // Only geocodes have viewport.
                        bounds.union(place.geometry.viewport);
                    } else {
                        bounds.extend(place.geometry.location);
                    }

                    var branch_location = place.geometry.location;
                    var branch_latitude = branch_location.lat();
                    var branch_longitude = branch_location.lng();

                    save_branch_locations(branch_latitude, branch_longitude);

                    is_address_found = true;
                }
            });
            map.fitBounds(bounds);

            if (is_address_found == false) {
                swal({
                    title: "Error!",
                    text: "Your searched place contains no location. Please try again.",
                    type: "error"
                });
                return;
            }
        });
    }

    function get_location_of_dragend_marker(marker) {
        google.maps.event.addListener(marker, 'dragend', function (marker) {
            var latLng = marker.latLng;
            var branch_latitude = latLng.lat();
            var branch_longitude = latLng.lng();

            save_branch_locations(branch_latitude, branch_longitude);
        });
    }

    function save_branch_locations(latitude, longitude) {
        $("#current_branch_latitude").val(latitude);
        $("#current_branch_longitude").val(longitude);
    }
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=<?= GOOGLE_API_KEY ?>&libraries=places" async defer></script>