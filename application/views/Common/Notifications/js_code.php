<?php
$expire_time_display = date('d-m-Y 23:59', strtotime('+7 days', strtotime(get_country_wise_date(date('d-m-Y H:i'), $this->loggedin_user_country_data['timezone']))));
$limited_time_display = date('d-m-Y 23:59', strtotime('+1 month', strtotime(get_country_wise_date(date('d-m-Y H:i'), $this->loggedin_user_country_data['timezone']))));
?>

<script src="assets/user/emojis_lib/js/config.js"></script>
<script src="assets/user/emojis_lib/js/util.js"></script>
<script src="assets/user/emojis_lib/js/jquery.emojiarea.js"></script>
<script src="assets/user/emojis_lib/js/emoji-picker.js"></script>
<script>
    $(document).ready(function () {
        // Initializes and creates emoji set from sprite sheet
        window.emojiPicker = new EmojiPicker({
            emojiable_selector: '[data-emojiable=true]',
            assetsPath: 'assets/user/emojis_lib/img',
            popupButtonClasses: 'fa fa-smile-o'
        });
        // Finds all elements with `emojiable_selector` and converts them to rich emoji input fields
        // You may want to delay this step if you have dynamically created input fields that appear later in the loading process
        // It can be called as many times as necessary; previously converted input fields will not be converted again
        window.emojiPicker.discover();
    });
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<!-- The Templates plugin is included to render the upload/download listings -->
<script src="https://blueimp.github.io/JavaScript-Templates/js/tmpl.min.js"></script>
<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
<script src="https://blueimp.github.io/JavaScript-Load-Image/js/load-image.all.min.js"></script>
<!-- The Canvas to Blob plugin is included for image resizing functionality -->
<script src="https://blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js"></script>
<!-- blueimp Gallery script -->
<script src="https://blueimp.github.io/Gallery/js/jquery.blueimp-gallery.min.js"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->

<script src="assets/user/js/file_upload/jquery.iframe-transport.js"></script>
<!-- The basic File Upload plugin -->
<script src="assets/user/js/file_upload/jquery.fileupload.js"></script>
<!-- The File Upload processing plugin -->
<script src="assets/user/js/file_upload/jquery.fileupload-process.js"></script>
<!-- The File Upload image preview & resize plugin -->
<script src="assets/user/js/file_upload/jquery.fileupload-image.js"></script>
<!-- The File Upload audio preview plugin -->
<script src="assets/user/js/file_upload/jquery.fileupload-audio.js"></script>
<!-- The File Upload video preview plugin -->
<script src="assets/user/js/file_upload/jquery.fileupload-video.js"></script>
<!-- The File Upload validation plugin -->
<script src="assets/user/js/file_upload/jquery.fileupload-validate.js"></script>
<script src="assets/user/js/file_upload/jquery.fileupload-ui.js"></script>
<!-- The File Upload jQuery UI plugin -->
<script src="assets/user/js/file_upload/jquery.fileupload-jquery-ui.js"></script>
<!-- The main application script -->

<script type="text/javascript" src="assets/user/js/plugins/forms/styling/uniform.min.js"></script>
<!--<script src="assets/user/js/file_upload/main.js"></script>-->

<script>
    var img_arr = [];
    var file_uploaded_count = 0;
    $(document).find('#load_image_label').text('Load Image(s) / ' + file_uploaded_count + ' Images Added');
    $(function () {
        'use strict';
        // Change this to the location of your server-side upload handler:
        //        var url = window.location.hostname === 'blueimp.github.io' ?
        //                '//jquery-file-upload.appspot.com/' : 'server/php/',
        var url = window.location.hostname === '<?php echo $upload_url; ?>', uploadButton = $('<button/>')
                .addClass('btn btn-primary')
                .prop('disabled', true)
                .text('Processing...')
                .on('click', function () {
                    var $this = $(this),
                            data = $this.data();
                    $this.off('click').text('Abort').on('click', function () {
                        $this.remove();
                        data.abort();
                    });
                    data.submit().always(function () {
                        $this.remove();
                    });
                });

        $(document).bind('dragover', function (e) {
            var dropZones = $('#dropzone'),
                    timeout = window.dropZoneTimeout;
            if (timeout) {
                clearTimeout(timeout);
            } else {
                dropZones.addClass('in');
            }
            var hoveredDropZone = $(e.target).closest(dropZones);
            dropZones.not(hoveredDropZone).removeClass('hover');
            hoveredDropZone.addClass('hover');
            window.dropZoneTimeout = setTimeout(function () {
                window.dropZoneTimeout = null;
                dropZones.removeClass('in hover');
            }, 100);
        });

        $(document).bind('drop dragover', function (e) {
            e.preventDefault();
        });

        /*
         * @author HGA
         * @added 30-11-2018 11:33 AM
         * @param {type} b64Data
         * @param {type} contentType
         * @param {type} sliceSize
         * @returns {Blob}
         * @comment Convert Facebook Image URL to Blog files.
         */
        $(document).bind('drop', function (e) {
            var url = $(e.originalEvent.dataTransfer.getData('text/html')).filter('img').attr('src');

            if (url) {
                var xhr = new XMLHttpRequest();

                xhr.responseType = "arraybuffer";
                xhr.open("GET", url);

                xhr.onload = function () {
                    var base64, binary, bytes, mediaType;

                    bytes = new Uint8Array(xhr.response);
                    binary = [].map.call(bytes, function (byte) {
                        return String.fromCharCode(byte);
                    }).join('');

                    mediaType = xhr.getResponseHeader('content-type');

                    base64 = [
                        'data:',
                        mediaType ? mediaType + ';' : '',
                        'base64,',
                        btoa(binary)
                    ].join('');

                    var blob_file = b64toBlob(btoa(binary), mediaType);

                    if (blob_file) {
                        $('#fileupload').fileupload('add', {files: [blob_file]});
                    }
                };
                xhr.send();
            }
        });

        function b64toBlob(b64Data, contentType, sliceSize) {
            contentType = contentType || '';
            sliceSize = sliceSize || 512;

            var byteCharacters = atob(b64Data);
            var byteArrays = [];

            for (var offset = 0; offset < byteCharacters.length; offset += sliceSize) {
                var slice = byteCharacters.slice(offset, offset + sliceSize);
                var byteNumbers = new Array(slice.length);

                for (var i = 0; i < slice.length; i++) {
                    byteNumbers[i] = slice.charCodeAt(i);
                }

                var byteArray = new Uint8Array(byteNumbers);
                byteArrays.push(byteArray);
            }

            var blob = new Blob(byteArrays, {type: contentType});
            return blob;
        }

        $('#fileupload').fileupload({
            dropZone: $('#dropzone'),
            url: url,
            dataType: 'json',
            autoUpload: true,
            acceptFileTypes: /(\.|\/)(jpe?g|jpg|png)$/i,
            maxNumberOfFiles: <?php echo $max_image_upload_count; ?>,
            // Enable image resizing, except for Android and Opera,
            // which actually support image resizing, but fail to
            // send Blob objects via XHR requests:
            disableImageResize: /Android(?!.*Chrome)|Opera/
                    .test(window.navigator.userAgent),
            previewMaxWidth: 100,
            previewMaxHeight: 100,
            previewCrop: true,
            process: [
                {
                    action: 'load',
                    fileTypes: /^image\/(jpe?g|jpg|png)$/,
                    maxFileSize: 250000 // 20MB
                },
                {
                    action: 'resize',
                    maxWidth: 250,
                    maxHeight: 250,
                    minWidth: 100,
                    minHeight: 100
                },
                {
                    action: 'save'
                }
            ],
            messages: {
                maxNumberOfFiles: 'Sorry, You can upload <?php echo $max_image_upload_count; ?> Images,Please remove unneccessary files',
            },
            success: function (response) {
                $("#error_img").html("");
                var table_content = $(document).find(".table .table-striped").html();
                img_arr.push(window.btoa(response.files[0].name));
                $("#uploaded_images_arr").val(img_arr);
                if (table_content != '') {
                    $("#update_div").show();
                }
            },
            error: function (e) {
                //alert("here");
                console.log("Error");
                console.log(e);
            }
        }).on('fileuploadadd', function (e, data) {

            data.context = $('<div/>').appendTo('#files');
            $.each(data.files, function (index, file) {
                var node = $('<p/>')
                        .append($('<span/>').text(file.name));
                if (!index) {
                    node
                            .append('<br>')
                            .append(uploadButton.clone(true).data(data));
                }
//                node.appendTo(data.context);
                console.log('fileuploadadd');
                file_uploaded_count++;
            });
            $(document).find('#load_image_label').text('Load Image(s) / ' + file_uploaded_count + ' Images Added');
        }).on('fileuploadprocessalways', function (e, data) {
            var index = data.index,
                    file = data.files[index],
                    node = $(data.context.children()[index]);
            if (file.preview) {
                node
                        .prepend('<br>')
                        .prepend(file.preview);
            }
            if (file.error) {
                node
                        .append('<br>')
                        .append($('<span class="text-danger"/>').text(file.error));
            }
            if (index + 1 === data.files.length) {
                data.context.find('a')
                        .text('Cancel')
                        .prop('disabled', !!data.files.error);
            }
        }).on('fileuploadprogressall', function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#progress .progress-bar').css(
                    'width',
                    progress + '%'
                    );
        }).on('fileuploaddone', function (e, data) {

            $.each(data.result.files, function (index, file) {
                if (file.url) {
                    var link = $('<a>')
                            .attr('target', '_blank')
                            .prop('href', file.url);
                    $(data.context.children()[index])
                            .wrap(link);
                } else if (file.error) {
                    var error = $('<span class="text-danger"/>').text(file.error);
                    $(data.context.children()[index])
                            .append('<br>')
                            .append(error);
                }
            });
        }).on('fileuploadfail', function (e, data) {
            $.each(data.files, function (index) {
                var error = $('<span class="text-danger"/>').text('File upload failed.');
                $(data.context.children()[index])
                        .append('<br>')
                        .append(error);
                file_uploaded_count--;
            });
            $(document).find('#load_image_label').text('Load Image(s) / ' + file_uploaded_count + ' Images Added');
        }).on('fileuploaddestroy', function (e, data) {
            $("#error_img").html("");
            var index = img_arr.indexOf(data.context[0].id);
//            console.log(index);
            img_arr.splice(index, 1);
            file_uploaded_count--;
            $(document).find('#load_image_label').text('Load Image(s) / ' + file_uploaded_count + ' Images Added');
            if (index > -1) {
                var url = "<?php echo base_url() . $remove_image_url; ?>";
                $.post(url, {all_data: $("#uploaded_images_arr").val(), not_to_delete: img_arr}, function (response)
                { });
            }
            $("#uploaded_images_arr").val(img_arr);
            data.context.remove();
            var table_content = $(document).find("#table_image").html();
            if (table_content == '' || table_content == 'undefined') {
                $("#update_div").hide();
            }
            return false;
        }).prop('disabled', !$.support.fileInput)
                .parent().addClass($.support.fileInput ? undefined : 'disabled');
    });

    $(document).on('click', '#offer_submit', function () {
//        console.log($(document).find('#uploaded_images_arr').val());
        $(document).find('#uploaded_images_data').val($(document).find('#uploaded_images_arr').val());
        return true;
    });

    $(document).find('.offer_type').change(function () {

        var offer_content_type = $(document).find(this).val();
        if (offer_content_type == '<?php echo IMAGE_OFFER_CONTENT_TYPE; ?>') {
            $(document).find('.offer_text_section').hide();
            $(document).find('.offer_video_section').hide();
            $(document).find('.offer_image_section').show();
            $(document).find('#fileupload').show();
            $(document).find('.upload_up').show();
<?php if (!isset($notification_data)) { ?>
                responsive_view_status_text$(document).find('#media_name').attr('required', 'required');
                $(document).find('#content').removeAttr('required');
<?php } ?>
        } else if (offer_content_type == '<?php echo VIDEO_OFFER_CONTENT_TYPE; ?>') {
            $(document).find('.offer_text_section').hide();
            $(document).find('.offer_image_section').hide();
            $(document).find('#fileupload').hide();
            $(document).find('.upload_up').hide();
            $(document).find('.offer_video_section').show();
<?php if (!isset($notification_data)) { ?>
                $(document).find('#media_name').attr('required', 'required');
                $(document).find('#content').removeAttr('required');
<?php } ?>
        } else {
            $(document).find('.offer_text_section').show();
            $(document).find('.offer_image_section').hide();
            $(document).find('#fileupload').hide();
            $(document).find('.upload_up').hide();
            $(document).find('.offer_video_section').hide();
<?php if (!isset($notification_data)) { ?>
                $(document).find('#content').attr('required', 'required');
                $(document).find('#media_name').removeAttr('required');
<?php } ?>
        }
    });
    $(document).find('.video_file_type').change(function () {
        var type = $(this).val();
        if (type == "file") {
            $("#url_type").addClass('hide');
            $("#file_type").removeClass('hide');
        }
//        else if (type == "url") {
//            $("#file_type").addClass('hide');
//            $("#url_type").removeClass('hide');
//        }
    });

    $(function () {
//        jQuery(window).resize(function () {
        if (jQuery(window).width() <= 1024) {
            $(document).find('.desktop_view').prop("disabled", true);
            $(document).find('.desktop_view').hide();
            $(document).find('.desktop_view').remove();
//            $(document).find('.desktop_view_text').removeAttr('name').removeAttr('id');
            
            $(document).find('.responsive_view_status').prop("disabled", false);
            $(document).find('.responsive_view_status').show();
        } else {
            $(document).find('.responsive_view_status').prop("disabled", true);
            $(document).find('.responsive_view_status').hide();
            $(document).find('.responsive_view_status').remove();
            
//            $(document).find('.responsive_view_status_text').removeAttr('name').removeAttr('id');
            $(document).find('.desktop_view').prop("disabled", false);
            $(document).find('.desktop_view').show();
        }
//        });

        jqueryValidate();

//        $(document).find('.offer_text_section').hide();

<?php if (isset($notification_data) && isset($notification_data['offer_type']) && $notification_data['offer_type'] == TEXT_OFFER_CONTENT_TYPE) { ?>
            $(document).find('.offer_text_section').show();
            $(document).find('.offer_image_section').hide();
            $(document).find('.upload_up').hide();
            $(document).find('.offer_video_section').hide();
            $(document).find('#content').attr('required', 'required');
            $(document).find('#media_name').removeAttr('required');
<?php } elseif (isset($notification_data) && isset($notification_data['offer_type']) && $notification_data['offer_type'] == IMAGE_OFFER_CONTENT_TYPE) { ?>
            $(document).find('.offer_text_section').hide();
            $(document).find('.offer_image_section').show();
            $(document).find('.upload_up').show();
            $(document).find('.offer_video_section').hide();
            $(document).find('#content').attr('required', 'required');
            $(document).find('#media_name').removeAttr('required');
<?php } elseif (isset($notification_data) && isset($notification_data['offer_type']) && $notification_data['offer_type'] == VIDEO_OFFER_CONTENT_TYPE) { ?>
            $(document).find('.offer_text_section').hide();
            $(document).find('.offer_image_section').hide();
            $(document).find('.upload_up').hide();
            $(document).find('.offer_video_section').show();
            $(document).find('#content').attr('required', 'required');
            $(document).find('#media_name').removeAttr('required');
<?php } else { ?>
            $(document).find('.offer_text_section').hide();
            $(document).find('.offer_video_section').hide();
            $(document).find('.offer_image_section').show();
            $(document).find('.upload_up').show();
            //            $(document).find('#media_name').attr('required', 'required');
            $(document).find('#content').removeAttr('required');
<?php } ?>
    });

    $(document).ready(function () {
        jqueryValidate();
        errorMgsToDefault();
        $("#media_name").on("change", function () {
            validate_logo('#media_name');
        });
    });

    function errorMgsToDefault() {
        var media_errors_wrapper = $('#media_errors_wrapper');
        var media_errors = $('#media_errors');
        media_errors_wrapper.addClass('display-none');
        media_errors.html('');
    }

    function validate_logo(control) {

        var media_errors_wrapper = $('#media_errors_wrapper');
        var media_errors = $('#media_errors');
        var is_valid = $('#is_valid');
        media_errors_wrapper.addClass('display-none');
        media_errors.html('');

        var fileUpload = $(control)[0];
        var FileUploadPath = fileUpload.value
        if (FileUploadPath != '') {
            if (typeof (fileUpload.files) != "undefined") {

                var Extension = FileUploadPath.substring(FileUploadPath.lastIndexOf('.') + 1).toLowerCase();

                if (Extension == 'mp4' || Extension == 'webm' || Extension == 'ogg' || Extension == 'ogv' ||
                        Extension == 'wmv' || Extension == 'vob' || Extension == 'swf' || Extension == 'mov' ||
                        Extension == 'm4v' || Extension == 'flv') {

                    if (fileUpload.files && fileUpload.files[0]) {
                        var reader = new FileReader();
                        reader.onload = function (e) {
                        }
                        reader.readAsDataURL(fileUpload.files[0]);
                        is_valid.val(1);
                    }
                    return true;
                } else {
                    media_errors_wrapper.removeClass('display-none');
                    media_errors.html('Video should only mp4 / webm / ogg / ogv / wmv / vob / swf / mov / m4v / flv file types.');
                    is_valid.val(0);
                    return false;
                }
            } else {
                media_errors_wrapper.removeClass('display-none');
                media_errors.html('Video should only mp4 / webm / ogg / ogv / wmv / vob / swf / mov / m4v / flv file types.');
                is_valid.val(0);
                return false;
            }
        } else {
            media_errors_wrapper.removeClass('display-none');
            media_errors.html('Video should only mp4 / webm / ogg / ogv / wmv / vob / swf / mov / m4v / flv file types.');
            is_valid.val(0);
            return false;
        }
    }

    $(document).on('click', '#fixed_expire_date', function () {
        $(document).find('#expire_date_time').val('<?php echo $expire_time_display; ?>');
    });

    $(document).on('click', '#limited_expire_date', function () {
        $(document).find('#expire_date_time').val('<?php echo $limited_time_display; ?>');
    });

<?php if ($notification_type == 'offers') { ?>
        $(document).find('.upload_up').css('margin-top', '-247px');
<?php } ?>


    $('.styled, .multiselect-container input').uniform({
        radioClass: 'choice'
    });

    /*
     * @param {type} store_id, offer_id
     * @returns {undefined}
     */
    $(document).on("change", "#store_mall_id", function () {
        var store_id = $(this).find("option:selected").attr('data-id');

        if (store_id) {
            var selected_store_id = $(this).find("option:selected").val();
            //Get Last Posted Date
            var url = base_url + 'country-admin/notifications/store/posted_date/get';

            $.ajax({
                url: url,
                data: {
                    store_id: selected_store_id,
                },
                type: 'POST',
                success: function (response) {
                    if (response) {
                        $("#last_posted_date_div").removeClass('hide');
                        $("#last_posted_text").val(response);
                    }
                }
            });

            //For Category-Sub Category Drop-down
            storeCategory(store_id);
            $(document).find('.sub_category_id').multiselect('rebuild');
            $(document).find('.sub_category_id').multiselect('refresh');
        }
    });

    var store_id = $("#store_mall_id").find("option:selected").attr('data-id');
    if (store_id) {
        storeCategory(store_id);
    }

    function storeCategory(store_id) {
        var url = base_url + 'country-admin/notifications/store/category/get';
        var offer_id = '<?= (isset($notification_data) && $notification_data['id_offer']) ? $notification_data['id_offer'] : 0 ?>';
        $.ajax({
            url: url,
            data: {
                store_id: store_id,
                offer_id: offer_id
            },
            type: 'POST',
            success: function (response) {
                $('.sub_category_id').empty();
                if (response) {
                    $(document).find('.sub_category_id').append(response);
                    $('.sub_category_id').multiselect('rebuild');
                    $('.styled, .multiselect-container input').uniform({
                        radioClass: 'choice'
                    });
                }
            }
        });

        $.ajax({
            url: base_url + 'country-admin/notifications/store/offer/last-post-date/get',
            data: {
                store_id: store_id
            },
            type: 'POST',
            success: function (response) {
                response = jQuery.parseJSON(response);
                $("#text_last_posted_date").val(response.created_date);
            }
        });
    }

    $('.sub_category_id').multiselect({
        nonSelectedText: 'Select Category',
        enableFiltering: true,
        enableCaseInsensitiveFiltering: true,
        buttonWidth: '340px',
        onChange: function () {
            $.uniform.update();
        }
    });
</script>

<?php
$parent_cat = $sub_cat = [];
if (isset($offer_category_data)) {
    $parent_cat = array_column($offer_category_data, 'id_category');
    $sub_cat = array_column($offer_category_data, 'id_sub_category');
}
?>
