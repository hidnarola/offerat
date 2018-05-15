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


<!-- Core JS files -->
<script type="text/javascript" src="assets/user/js/plugins/loaders/pace.min.js"></script>
<script type="text/javascript" src="assets/user/js/core/libraries/jquery.min.js"></script>
<script type="text/javascript" src="assets/user/js/core/libraries/bootstrap.min.js"></script>
<script type="text/javascript" src="assets/user/js/plugins/loaders/blockui.min.js"></script>
<!-- /core JS files -->
<script type="text/javascript">
    var base_url = '<?php echo SITEURL; ?>';

    function image_not_found(sourceId) {
        var default_img_url = base_url + 'assets/user/images/site/default_missing_image.png';        
        $(document).find(sourceId)[0].src = default_img_url;
        $(document).find(sourceId)[0].title = "Image Not Found";
    }
</script>