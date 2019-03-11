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
    var store_locations = '<?php echo json_encode($store_locations); ?>';
    store_locations = jQuery.parseJSON(store_locations);

    function initMap() {
        var iconBase = '<?= site_url("media/StoreLogo/") ?>';
        var i = 0;
        var default_icon = '<?= site_url("assets/user/images/store2.png") ?>';
        var map_icon = '';

        map = new google.maps.Map(document.getElementById('map'), {
            zoom: 2,
            center: new google.maps.LatLng(-33.91722, 151.23064),
            mapTypeId: 'roadmap',
        });

        function check_file_exists(image_name) {
            var result = $.ajax({
                url: '<?= site_url('country-admin/malls/check_file_exists') ?>',
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
            var store_id = index.id_store;
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
            });

            // process multiple info windows
            (function (marker, i, store_id) {

                // On click event to change store logo.
                google.maps.event.addListener(marker, 'click', function () {
                    (async function getImage() {
                        const {value: file} = await Swal.fire({
                            title: 'Select image',
                            input: 'file',
                            inputAttributes: {
                                'accept': 'image/*',
                                'aria-label': 'Change Store icon'
                            }
                        });

                        if (file) {
                            const reader = new FileReader;
                            reader.onload = (e) => {
                                var base64_image = e.target.result;
                                var base64_image_type = base64_image.split(';')[0].split('/')[1];

                                var allowed_file_format = ['jpg', 'png', 'jpeg'];

                                if (base64_image && jQuery.inArray(base64_image_type, allowed_file_format) != '-1') {
                                    var data = {
                                        store_id: store_id,
                                        encrypted_image: btoa(base64_image)
                                    };

                                    $.ajax({
                                        type: 'POST',
                                        url: "<?= site_url('country-admin/malls/upload_store_image') ?>",
                                        data: data,
                                        success: function (response) {
                                            if (response == true) {
                                                swal({
                                                    title: "Success!",
                                                    text: "Store icon has been updated successfully.",
                                                    type: "success"
                                                }).then(function () {
                                                    window.location.reload();
                                                });
                                            } else {
                                                swal({
                                                    title: "Error",
                                                    text: "Store icon has not been updated.",
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
                                } else {
                                    swal({
                                        title: "Error",
                                        text: "Image extension must be in a jpg,png,jpeg format",
                                        type: "error"
                                    });
                                }
                            }
                            reader.readAsDataURL(file);
                        }
                    })();
                });
            })(marker, i, store_id);

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