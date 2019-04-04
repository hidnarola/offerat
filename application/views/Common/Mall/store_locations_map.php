<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.min.css" />
<?php
if ($this->loggedin_user_type == STORE_OR_MALL_ADMIN_USER_TYPE) {
    $user_url_type = 'mall-store-user';
} else if ($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE) {
    $user_url_type = 'country-admin';
}
?>

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
    var store_locations = '<?php echo str_replace("'", "\'", json_encode($store_locations)); ?>';
    store_locations = JSON.parse(store_locations);

    function CoordMapType(tileSize) {
        this.tileSize = tileSize;
    }

    CoordMapType.prototype.getTile = function (coord, zoom, ownerDocument) {

        var div = ownerDocument.createElement('div');
        div.innerHTML = coord;
        div.style.width = this.tileSize.width + 'px';
        div.style.height = this.tileSize.height + 'px';
        div.style.fontSize = '0';
        div.style.borderStyle = 'solid';
        div.style.borderWidth = '1px';
        div.style.borderColor = '#AAAAAA';
        return div;
    };

    function initMap() {
        var iconBase = '<?= site_url("media/StoreLogo/") ?>';
        var i = 0;
        var default_icon = '<?= site_url("assets/user/images/store2.png") ?>';
        var map_icon = '';
        var map;
        var markers = [];

        map = new google.maps.Map(document.getElementById('map'), {
            zoom: 10,
            center: new google.maps.LatLng(-33.91722, 151.23064),
            mapTypeControl: false
        });

        //Hide all the Labels from map
        var emptyStyles = [
            {
                featureType: "all",
                elementType: "labels",
                stylers: [{visibility: "off"}]
            }
        ];
        map.setOptions({styles: emptyStyles});

        map.overlayMapTypes.insertAt(0, new CoordMapType(new google.maps.Size(86, 86)));

        function check_file_exists(image_name) {
            var result = $.ajax({
                url: '<?= site_url($user_url_type . '/malls/check_file_exists') ?>',
                type: 'POST',
                data: {
                    image_name: image_name
                },
                async: false,

            }).responseText;

            return result;
        }

        // Create markers.
        $.each(store_locations, function (key, index) {
            var id_store_location = index.id_store_location;
            var mediaType;

            if (index.store_logo != '') {
                map_icon = check_file_exists(index.store_logo);
            } else {
                map_icon = default_icon;
            }

            var icon = {
                url: map_icon, // url
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

            markers.push(marker);

            var floor_no = '<p>Floor No: <b>---</b></p>';
            if (index.store_floor_no != null) {
                floor_no = '<p>Floor No: <b>' + index.store_floor_no + '</b></p>';
            }

            var contentString = '<div id="content">' +
                    '<h1 id="firstHeading" class="firstHeading">' + index.store_name + '</h1>' +
                    '<div id="bodyContent">' +
                    floor_no + '</div></div>';

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
                        url: "<?= site_url($user_url_type . '/malls/update_store_locations') ?>",
                        data: data,
                        success: function (response) {
                        }
                    });
                });
            })(marker, i, id_store_location);

            i++;
        });

        var bounds = new google.maps.LatLngBounds();
        for (i = 0; i < markers.length; i++) {
            bounds.extend(markers[i].getPosition());
        }
        google.maps.event.addListenerOnce(map, 'bounds_changed', function (event) {
            this.setZoom(map.getZoom() - 1);

            if (this.getZoom() > 15) {
                this.setZoom(18);
            }
        });
        map.fitBounds(bounds);
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