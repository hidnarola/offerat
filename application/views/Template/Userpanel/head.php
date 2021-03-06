<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="viewport" content="width=device-width, initial-scale=1 maximum-scale=1.0, user-scalable=0">
<title><?php echo @$title; ?></title>
<base href="<?php echo base_url('user'); ?>">

<link rel="shortcut icon" type="image/png" href="<?= favicon_icon ?>" />

<!-- Global stylesheets -->
<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
<!--<link href="https://www.ama3.com/anytime/anytime.5.2.0.css" rel="stylesheet" type="text/css">-->
<link href="assets/user/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
<link href="assets/user/css/icons/fontawesome/styles.min.css" rel="stylesheet" type="text/css">
<link href="assets/user/css/bootstrap.css" rel="stylesheet" type="text/css">
<link href="assets/user/css/core.css" rel="stylesheet" type="text/css">
<link href="assets/user/css/components.css" rel="stylesheet" type="text/css">
<link href="assets/user/css/colors.css" rel="stylesheet" type="text/css">
<!--<link href="https://fonts.googleapis.com/css?family=Muli:300,400,600,700,800,900" rel="stylesheet">-->
<!-- /global stylesheets -->

<link rel="stylesheet" href="https://blueimp.github.io/Gallery/css/blueimp-gallery.min.css"> 
<link rel="stylesheet" href="assets/user/css/file_upload/jquery.fileupload.css">
<link rel="stylesheet" href="assets/user/css/file_upload/jquery.fileupload-ui.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css">

<noscript><link rel="stylesheet" href="assets/user/css/file_upload/jquery.fileupload-noscript.css"></noscript>
<noscript><link rel="stylesheet" href="assets/user/css/file_upload/jquery.fileupload-ui-noscript.css"></noscript>

<!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">-->
<!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">-->
<link href="assets/user/emojis_lib/css/emoji.css" rel="stylesheet">

<link href="assets/user/css/custom.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />

<noscript><META HTTP-EQUIV="Refresh" CONTENT="0; URL=js_disabled"></noscript>
<!-- Core JS files -->
<script type="text/javascript" src="assets/user/js/plugins/loaders/pace.min.js"></script>
<script type="text/javascript" src="assets/user/js/core/libraries/jquery.min.js"></script>
<script type="text/javascript" src="assets/user/js/core/libraries/bootstrap.min.js"></script>
<script type="text/javascript" src="assets/user/js/plugins/loaders/blockui.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script> 
<!-- /core JS files -->

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