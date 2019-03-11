<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.min.css" />
<style>
    #map {
        height: 100%;
    }

    .panel-body .map-div {
        height: 490px;
    }
</style>
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
                    <div class="col-md-12 col-xs-12 map-div">
                        <div id="map"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.all.min.js"></script>
<script>
    var map;
    var store_locations = '<?php echo str_replace("'","\'",json_encode($store_locations)); ?>';
    store_locations = JSON.parse(store_locations);

    function initMap() {
        var iconBase = '<?= site_url("media/StoreLogo/") ?>';
        var i = 0;
        var default_icon = '<?= site_url("assets/user/images/store2.png") ?>';
        var map_icon = '';

        map = new google.maps.Map(document.getElementById('map'), {
            zoom: 2,
            center: new google.maps.LatLng(-33.91722, 151.23064),
        });

        // Create markers.
        $.each(store_locations, function (key, index) {
            var id_store_location = index.id_store_location;
            var mediaType;

            var icon = {
                url: default_icon, // url
                scaledSize: new google.maps.Size(32, 32), // scaled size
                origin: new google.maps.Point(0, 0), // origin
                anchor: new google.maps.Point(0, 0) // anchor
            };

            var marker = new google.maps.Marker({
                position: new google.maps.LatLng(parseFloat(index.latitude), parseFloat(index.longitude)),
                icon: icon,
                map: map,
                title: index.store_name,
                animation: google.maps.Animation.DROP,
                draggable: true
            });

            var contentString = '<div id="content">' +
                    '<h1 id="firstHeading" class="firstHeading">' + index.store_name + '</h1>' +
                    '<div id="bodyContent">' +
                    '<p><b>' + index.website + '</b></p>' +
                    '<p><b>' + index.telephone + '</p></b>' +
                    '</div>' +
                    '</div>';

            var infowindow = new google.maps.InfoWindow({
                content: contentString
            });

            // process multiple info windows
            (function (marker, i, id_store_location) {

                marker.addListener('click', function () {
                    infowindow.open(map, marker);
                });

                // On click event to change store logo.
                google.maps.event.addListener(marker, 'dragend', function () {
                    var latitude = marker.getPosition().lat();
                    var longitude = marker.getPosition().lng();

                    var data = {
                        latitude: latitude,
                        longitude: longitude,
                        id_store_location: id_store_location
                    };

                    $.ajax({
                        type: 'POST',
                        url: "<?= site_url('country-admin/malls/update_store_locations') ?>",
                        data: data,
                        success: function (response) {
                            if (response == true) {
                                swal({
                                    title: "Success!",
                                    text: "Store locations has been updated successfully.",
                                    type: "success"
                                }).then(function () {
                                    window.location.reload();
                                });
                            } else {
                                swal({
                                    title: "Error",
                                    text: "Store locations has not been updated.",
                                    type: "error"
                                });
                            }
                        }, error: function (jqXHR, textStatus, errorThrown) {
                            swal({
                                title: "Error",
                                text: "Something went wrong, Please try again.",
                                type: "error"
                            });
                        }
                    });
                });
            })(marker, i, id_store_location);

            i++;
        });
    }

    function toggleBounce() {
        if (marker.getAnimation() !== null) {
            marker.setAnimation(null);
        } else {
            marker.setAnimation(google.maps.Animation.BOUNCE);
        }
    }
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBREMF2gH26r6gNypcVlo_-PSU_qIh2Yu8&callback=initMap"></script>