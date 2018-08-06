<div class="col-md-12">

    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-flat">
                <div class="panel-heading">
                    <h5 class="panel-title"><i class="icon-bell2 btn-icon"></i><?php echo $page_header ?></h5>
                    <div class="heading-elements">
                        <ul class="icons-list">
                            <li><a data-action="collapse"></a></li>
                        </ul>
                    </div>
                </div>
                <div class="panel-body panel_offer">
                    <form method="POST" action="" enctype="multipart/form-data" class="form-validate-jquery" name="frm_profile" id="frm_profile">
                    <!--<form method="POST" action="<?php echo SITEURL . 'country-admin/upload/index'; ?>" enctype="multipart/form-data" class="form-validate-jquery" name="fileupload" id="fileupload">-->
                        <div class="col-xs-12">
                            <div class="">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="hidden" class="form-control" placeholder="" name="id_offer" id="id_offer" value="<?php echo (isset($notification_data['id_offer'])) ? $notification_data['id_offer'] : ''; ?>">
                                        <label>Select Store / Mall <span class="text-danger">*</span></label>
                                        <?php
                                        $store_id = 0;
                                        $mall_id = 0;
                                        $store_mall_id = $this->input->post('store_mall_id', TRUE);
                                        $store_mall_text = explode('_', $store_mall_id);
                                        if ($store_mall_text[0] == 'store')
                                            $store_id = $store_mall_text[1];
                                        if ($store_mall_text[0] == 'mall')
                                            $mall_id = $store_mall_text[1];
                                        ?>
                                        <select class="form-control select-search" id="store_mall_id" name="store_mall_id" required="required" data-live-search="true" >
                                            <option value="">Select Store / Mall</option>
                                            <?php if (isset($stores_list) && sizeof($stores_list) > 0) { ?>
                                                <optgroup label="Stores">
                                                    <?php foreach ($stores_list as $list) { ?>
                                                        <option value="store_<?php echo $list['id_store']; ?>" <?php echo (isset($notification_data) && isset($notification_data['id_store']) && $list['id_store'] == $notification_data['id_store']) ? 'selected=selected' : ''; ?> <?php echo ($list['id_store'] == $store_id) ? 'selected=selected' : ''; ?>><?php echo $list['store_name']; ?></option>
                                                    <?php } ?>
                                                </optgroup>
                                            <?php } ?>
                                            <?php if (isset($malls_list) && sizeof($malls_list) > 0) { ?>
                                                <optgroup label="Malls">
                                                    <?php foreach ($malls_list as $list) { ?>
                                                        <option value="mall_<?php echo $list['id_mall']; ?>" <?php echo (isset($notification_data) && isset($notification_data['id_mall']) && $list['id_mall'] == $notification_data['id_mall']) ? 'selected=selected' : ''; ?> <?php echo ($list['id_mall'] == $mall_id) ? 'selected=selected' : ''; ?>><?php echo $list['mall_name']; ?></option>
                                                    <?php } ?>
                                                </optgroup>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Content Type <span class="text-danger">*</span></label>
                                        <div>
                                            <div class="radio-inline">
                                                <label>
                                                    <input type="radio" name="offer_type" class="styled offer_type" required="required"  value="<?php echo IMAGE_OFFER_CONTENT_TYPE; ?>" <?php echo (isset($notification_data) && isset($notification_data['offer_type']) && $notification_data['offer_type'] == IMAGE_OFFER_CONTENT_TYPE) ? 'checked="checked"' : (!isset($notification_data)) ? 'checked="checked"' : ''; ?>>
                                                    Image(s)
                                                </label>
                                            </div>
                                            <div class="radio-inline">
                                                <label>
                                                    <input type="radio" name="offer_type" class="styled offer_type" value="<?php echo VIDEO_OFFER_CONTENT_TYPE; ?>" <?php echo (isset($notification_data) && isset($notification_data['offer_type']) && $notification_data['offer_type'] == VIDEO_OFFER_CONTENT_TYPE) ? 'checked="checked"' : ''; ?>>
                                                    Video
                                                </label>
                                            </div>
                                            <div class="radio-inline">
                                                <label>
                                                    <input type="radio" name="offer_type" class="styled offer_type" value="<?php echo TEXT_OFFER_CONTENT_TYPE; ?>" <?php echo (isset($notification_data) && isset($notification_data['offer_type']) && $notification_data['offer_type'] == TEXT_OFFER_CONTENT_TYPE) ? 'checked="checked"' : ''; ?>>
                                                    Text
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Broadcast Date & Time <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-default btn-icon" id="broad_cast_icon"><i class="icon-calendar3"></i></button>
                                            </span>
                                            <?php
//                                                $d = date('Y-m-d h:i');
//                                                $given = new DateTime($d, new DateTimeZone(date_default_timezone_get()));
//                                                echo $given->format("P") . '<br>';
//                                                echo date('Y-m-d h:i') . '<br>';
//                                                echo get_country_wise_date(date('Y-m-d h:i'), $this->loggedin_user_country_data['timezone']);
                                            $broadcasting_time = '';
                                            if (isset($notification_data) && isset($notification_data['broadcasting_time'])) {
                                                $broadcasting_time = date_create($notification_data['broadcasting_time']);
                                                $broadcasting_time = date_format($broadcasting_time, "Y-m-d H:i");
                                            } else {
                                                $broadcasting_time = date('d-m-Y H:i', strtotime('+2 minutes', strtotime(get_country_wise_date(date('d-m-Y H:i'), $this->loggedin_user_country_data['timezone']))));
                                            }
                                            ?>
                                            <input type="text" class="form-control" placeholder="Broadcast Date & Time" name="broadcasting_time" id="broad_cast_date_time" required="required" value="<?php echo $broadcasting_time; ?>">
                                        </div>                                            
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="col-xs-12">                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Expire Date & Time</label>
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-default btn-icon" id="expire_date_icon"><i class="icon-calendar3"></i></button>
                                        </span>                                                
                                        <?php
                                        $expiry_time = '';
                                        if (isset($notification_data) && isset($notification_data['expiry_time'])) {
                                            if ($notification_data['expiry_time'] == '0000-00-00 00:00:00') {
                                                $expiry_time = '';
                                            } else {
                                                $expiry_time = date_create($notification_data['expiry_time']);
                                                $expiry_time = date_format($expiry_time, "Y-m-d H:i");
                                            }
                                        } else {
                                            $expiry_time = date('d-m-Y H:i', strtotime('+10 minutes', strtotime(get_country_wise_date(date('d-m-Y H:i'), $this->loggedin_user_country_data['timezone']))));
                                        }
                                        ?>
                                        <input type="text" class="form-control" placeholder="Expire Date & Time" name="expiry_time" id="expire_date_time" value="<?php echo $expiry_time; ?>">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-default btn-icon" id="expire_date_delete_icon"><i class=" icon-bin"></i></button>
                                        </span> 
                                    </div>                                                                                        
                                </div>
                            </div>                                    
                            <div class="col-md-4">
                                <div class="form-group offer_text_section">
                                    <label>Offer Text <span class="text-danger">*</span></label>
                                    <div>
                                        <textarea class="form-control" rows="5" placeholder="Type here appears in the <?php echo ($notification_type == 'offers') ? 'offers' : 'announcement'; ?> section in mobile app" name="content" id="content"><?php echo (isset($notification_data['content'])) ? $notification_data['content'] : set_value('content'); ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group offer_video_section">
                                    <label>Video URL <span class="text-danger">*</span></label>
                                    <div>
                                        <input type="text" class="form-control" placeholder="Video URL" name="video_url" id="video_url" value="<?php echo (isset($notification_data['video_url'])) ? $notification_data['video_url'] : set_value('video_url'); ?>">
                                    </div>
                                </div>

                                <?php if (isset($notification_type) && $notification_type == 'offers') { ?>
                                    <div class="col-md-4 responsive_view_status">
                                        <div class="form-group">
                                            <label>Push Notification Summary <span class="text-danger">*</span></label>
                                            <div>
                                                <input type="text" class="form-control" placeholder="Appears in push notification" name="push_message" id="push_message" required="required" value="<?php echo (isset($notification_data['push_message'])) ? $notification_data['push_message'] : set_value('push_message'); ?>">
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>

                                <div class="form-group offer_image_section desktop_view">
                                    <label>Upload Image(s)<span class="text-danger">*</span></label>
                                    <div>
<!--                                            <input type="file" class="form-control file-input" placeholder="" name="media_name" id="media_name">
                                        <label id="media_name-error" class="validation-error-label" for="media_name" style=""></label>-->
                                    </div>
                                </div>                                    
                            </div>
                            <?php if (isset($notification_type) && $notification_type == 'offers') { ?>
                                <div class="col-md-4 desktop_view">
                                    <div class="form-group">
                                        <label>Push Notification Summary <span class="text-danger">*</span></label>
                                        <div>
                                            <input type="text" class="form-control" placeholder="Appears in push notification" name="push_message" id="push_message" required="required" value="<?php echo (isset($notification_data['push_message'])) ? $notification_data['push_message'] : set_value('push_message'); ?>">
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>   

                            <div class="form-group offer_image_section responsive_view_status">
                                <label>Upload Image(s)<span class="text-danger">*</span></label>
                                <div>
<!--                                            <input type="file" class="form-control file-input" placeholder="" name="media_name" id="media_name">
                                    <label id="media_name-error" class="validation-error-label" for="media_name" style=""></label>-->
                                </div>
                            </div>  
                        </div>        

                        <div class="text-right btn_end">
                            <a href="<?php echo $back_url; ?>" class="btn bg-grey-300 btn-labeled"><b><i class="icon-arrow-left13"></i></b>Back</a>
                            <button type="submit" id="offer_submit" name="offer_submit" class="btn bg-teal btn-labeled btn-labeled-right"><b><i class="icon-arrow-right14"></i></b>Save</button>
                            <input type="hidden" name="uploaded_images_data" id="uploaded_images_data">
                        </div>

                    </form>
                    <form method="POST" action="<?php echo SITEURL . 'country-admin/upload/index'; ?>" enctype="multipart/form-data" class="form-validate-jquery" name="fileupload" id="fileupload">
                        <input type="hidden" name="uploaded_images_arr" id="uploaded_images_arr">
                        <div class="row fileupload-buttonbar">
                            <div class="col-md-4"></div>
                            <div class="col-md-4 upload_up">
                                <!-- The fileinput-button span is used to style the file input field as button -->
                                <span class="btn btn-success fileinput-button">
                                    <i class="glyphicon glyphicon-plus"></i>
                                    <span>Upload Image(s) <span class="text-danger">*</span></span>
                                    <input type="file" name="files[]" id="" multiple class="form-control">
                                </span>
                                <div class="upload-div" style="display:none;" id="update_div">
                                    <button type="button" class="delete">Delete</button>
                                    <input type="checkbox" class="toggle styled-checkbox-1">
                                </div>
                            </div>
                            <!-- The global progress state -->
                            <div class="col-lg-5 fileupload-progress fade display-none">
                                <!-- The global progress bar -->
                                <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                                    <div class="progress-bar progress-bar-success" style="width:0%;"></div>
                                </div>
                                <!-- The extended global progress state -->
                                <div class="progress-extended">&nbsp;</div>
                            </div>
                        </div>
                        <!-- The table listing the files available for upload/download -->
                        <table role="presentation" class="table table-striped"><tbody class="files"></tbody></table>
                <!--                        <span id="error_img"></span>
                                         The global progress bar 
                                        <div id="progress" class="progress">
                                            <div class="progress-bar progress-bar-success"></div>
                                        </div>
                                         The container for the uploaded files 
                                        <div id="files" class="files"></div>
                                        <br>    -->
                        <table role="presentation" class="table table-striped"><tbody class="files" id="table_image"></tbody></table>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
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



<?php $this->load->view('Common/Notifications/script'); ?>
<?php $this->load->view('Common/message_alert'); ?>
<script>
    var img_arr = [];
    $(function () {
        jqueryValidate();
        $(document).find('.offer_text_section').hide();

<?php if (isset($notification_data) && isset($notification_data['offer_type']) && $notification_data['offer_type'] == TEXT_OFFER_CONTENT_TYPE) { ?>
            $(document).find('.offer_text_section').show();
            $(document).find('.offer_image_section').hide();
            $(document).find('.offer_video_section').hide();
            $(document).find('#content').attr('required', 'required');
            $(document).find('#media_name').removeAttr('required');
<?php } elseif (isset($notification_data) && isset($notification_data['offer_type']) && $notification_data['offer_type'] == IMAGE_OFFER_CONTENT_TYPE) { ?>
            $(document).find('.offer_text_section').hide();
            $(document).find('.offer_image_section').show();
            $(document).find('.offer_video_section').hide();
            $(document).find('#content').attr('required', 'required');
            $(document).find('#media_name').removeAttr('required');
<?php } elseif (isset($notification_data) && isset($notification_data['offer_type']) && $notification_data['offer_type'] == VIDEO_OFFER_CONTENT_TYPE) { ?>
            $(document).find('.offer_text_section').hide();
            $(document).find('.offer_image_section').hide();
            $(document).find('.offer_video_section').show();
            $(document).find('#content').attr('required', 'required');
            $(document).find('#media_name').removeAttr('required');
<?php } else { ?>
            $(document).find('.offer_text_section').hide();
            $(document).find('.offer_video_section').hide();
            $(document).find('.offer_image_section').show();
            //            $(document).find('#media_name').attr('required', 'required');
            $(document).find('#content').removeAttr('required');
<?php } ?>
    });

    $(document).find('.offer_type').change(function () {

        var offer_content_type = $(document).find(this).val();
        if (offer_content_type == '<?php echo IMAGE_OFFER_CONTENT_TYPE; ?>') {
            $(document).find('.offer_text_section').hide();
            $(document).find('.offer_video_section').hide();
            $(document).find('.offer_image_section').show();
<?php if (!isset($notification_data)) { ?>
                $(document).find('#media_name').attr('required', 'required');
                $(document).find('#content').removeAttr('required');
<?php } ?>
        } else if (offer_content_type == '<?php echo VIDEO_OFFER_CONTENT_TYPE; ?>') {
            $(document).find('.offer_text_section').hide();
            $(document).find('.offer_image_section').hide();
            $(document).find('.offer_video_section').show();
<?php if (!isset($notification_data)) { ?>
                $(document).find('#media_name').attr('required', 'required');
                $(document).find('#content').removeAttr('required');
<?php } ?>
        } else {
            $(document).find('.offer_text_section').show();
            $(document).find('.offer_image_section').hide();
            $(document).find('.offer_video_section').hide();
<?php if (!isset($notification_data)) { ?>
                $(document).find('#content').attr('required', 'required');
                $(document).find('#media_name').removeAttr('required');
<?php } ?>
        }
    });

    $(function () {
        'use strict';
        // Change this to the location of your server-side upload handler:
        //        var url = window.location.hostname === 'blueimp.github.io' ?
        //                '//jquery-file-upload.appspot.com/' : 'server/php/',
        var url = window.location.hostname === '<?php echo SITEURL . 'country-admin/upload/index'; ?>',
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
            maxNumberOfFiles: 50,
            // Enable image resizing, except for Android and Opera,
            // which actually support image resizing, but fail to
            // send Blob objects via XHR requests:
            disableImageResize: /Android(?!.*Chrome)|Opera/
                    .test(window.navigator.userAgent),
            previewMaxWidth: 100,
            previewMaxHeight: 100,
            previewCrop: true,
            messages: {
                maxNumberOfFiles: 'Sorry, You can upload 50 Images,Please remove unneccessary files',
            },
            success: function (response) {

                console.log('response');
                console.log(response);
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
//                console.log('fileuploadadd');
            });
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
            });
        }).on('fileuploaddestroy', function (e, data) {
            $("#error_img").html("");
            var index = img_arr.indexOf(data.context[0].id);
            if (index > -1) {
                img_arr.splice(index, 1);
                var url = "<?php echo base_url(); ?>country-admin/notifications/remove_image_uploaded";
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
        console.log($(document).find('#uploaded_images_arr').val());
        $(document).find('#uploaded_images_data').val($(document).find('#uploaded_images_arr').val());
        return true;
    });

    if ($(window).width() <= 1024) {
        console.log("if con");
        $(document).find('.desktop_view').prop("disabled", true);
        $(document).find('.desktop_view').hide();
        $(document).find('.responsive_view_status').prop("disabled", false);
        $(document).find('.responsive_view_status').show();
    } else {
        $(document).find('.responsive_view_status').prop("disabled", true);
        $(document).find('.responsive_view_status').hide();
        $(document).find('.desktop_view').prop("disabled", false);
        $(document).find('.desktop_view').show();
        console.log("else part");
    }
</script>