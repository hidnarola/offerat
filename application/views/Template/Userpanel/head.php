<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="viewport" content="width=device-width, initial-scale=1 maximum-scale=1.0, user-scalable=0">
<title><?php echo @$title; ?></title>
<base href="<?php echo base_url('user'); ?>">

<!-- Global stylesheets -->
<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
<link href="assets/user/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
<link href="assets/user/css/icons/fontawesome/styles.min.css" rel="stylesheet" type="text/css">
<link href="assets/user/css/bootstrap.css" rel="stylesheet" type="text/css">
<link href="assets/user/css/core.css" rel="stylesheet" type="text/css">
<link href="assets/user/css/components.css" rel="stylesheet" type="text/css">
<link href="assets/user/css/colors.css" rel="stylesheet" type="text/css">
<link href="assets/user/css/custom.css" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Muli:300,400,600,700,800,900" rel="stylesheet">
<!-- /global stylesheets -->
<noscript><META HTTP-EQUIV="Refresh" CONTENT="0; URL=js_disabled"></noscript>
<!-- Core JS files -->
<script type="text/javascript" src="assets/user/js/plugins/loaders/pace.min.js"></script>
<script type="text/javascript" src="assets/user/js/core/libraries/jquery.min.js"></script>
<script type="text/javascript" src="assets/user/js/core/libraries/bootstrap.min.js"></script>
<script type="text/javascript" src="assets/user/js/plugins/loaders/blockui.min.js"></script>
<!-- /core JS files -->


<link rel="stylesheet" href="assets/user/css/file_upload/style.css">
<link rel="stylesheet" href="assets/user/css/file_upload/jquery.fileupload.css">

<script src="assets/user/js/file_upload/vendor/jquery.ui.widget.js"></script>
<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
<script src="https://blueimp.github.io/JavaScript-Load-Image/js/load-image.all.min.js"></script>
<!-- The Canvas to Blob plugin is included for image resizing functionality -->
<script src="https://blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js"></script>
<!-- Bootstrap JS is not required, but included for the responsive demo navigation -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
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

<script type="text/javascript">
    var base_url = '<?php echo SITEURL; ?>';

    function image_not_found(sourceId) {
        var default_img_url = base_url + 'assets/user/images/site/default_missing_image.png';
        $(document).find(sourceId)[0].src = default_img_url;
        $(document).find(sourceId)[0].title = "Image Not Found";
    }

    function small_image_not_found(sourceId) {
        var default_img_url = base_url + 'assets/user/images/site/small_no_image.jpg';
        $(document).find(sourceId)[0].src = default_img_url;
        $(document).find(sourceId)[0].title = "Image Not Found";
    }
</script>