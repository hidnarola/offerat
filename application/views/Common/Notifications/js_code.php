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
        var url = window.location.hostname === '<?php echo $upload_url; ?>',
                uploadButton = $('<button/>')
                .addClass('btn btn-primary')
                .prop('disabled', true)
                .text('Processing...')
                .on('click', function () {
                    var $this = $(this),
                            data = $this.data();
                    $this
                            .off('click')
                            .text('Abort')
                            .on('click', function () {
                                $this.remove();
                                data.abort();
                            });
                    data.submit().always(function () {
                        $this.remove();
                    });
                });
        $('#fileupload').fileupload({
            dropZone: $('#dropzone'),
            url: url,
            dataType: 'json',
            autoUpload: true,
            acceptFileTypes: /(\.|\/)(jpe?g|jpg|png)$/i,
            maxFileSize: 250000,
            maxNumberOfFiles: <?php echo $max_image_upload_count; ?>,
            // Enable image resizing, except for Android and Opera,
            // which actually support image resizing, but fail to
            // send Blob objects via XHR requests:
            disableImageResize: /Android(?!.*Chrome)|Opera/
                    .test(window.navigator.userAgent),
            previewMaxWidth: 100,
            previewMaxHeight: 100,
            previewCrop: true,
            messages: {
                maxNumberOfFiles: 'Sorry, You can upload <?php echo $max_image_upload_count; ?> Images,Please remove unneccessary files',
            },
            success: function (response) {

                $("#error_img").html("");
                var table_content = $(document).find(".table .table-striped").html();
                img_arr.push(window.btoa(response.files[0].name + "/" + response.files[0].width + "/" + response.files[0].height));
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
//            console.log('fileuploadprocessalways');
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
//            console.log('fileuploadprogressall');
        }).on('fileuploaddone', function (e, data) {
//            console.log('fileuploaddone');
//            console.log(data);
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
                $(document).find('#media_name').attr('required', 'required');
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

    $(function () {

//        jQuery(window).resize(function () {
        if (jQuery(window).width() <= 1024) {
            $(document).find('.desktop_view').prop("disabled", true);
            $(document).find('.desktop_view').hide();
            $(document).find('.responsive_view_status').prop("disabled", false);
            $(document).find('.responsive_view_status').show();
        } else {
            $(document).find('.responsive_view_status').prop("disabled", true);
            $(document).find('.responsive_view_status').hide();
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
</script>