<!DOCTYPE html>
<html lang="en">
    <head>
        <?php
        $this->load->view('Login/head');
        ?>        
        <script type="text/javascript" src="assets/user/js/core/app.js"></script>
        <noscript><META HTTP-EQUIV="Refresh" CONTENT="0; URL=js_disabled"></noscript>
        <style type="text/css">
            .login-cover {
                background-attachment: fixed;
            }
        </style>
        <!-- /theme JS files -->
    </head>
    <body class="login-container login-cover">        
        <!-- Page container -->
        <div class="page-container">
            <!-- Page content -->
            <div class="page-content">
                <?php echo $body; ?>
            </div>
            <!-- /page content -->
        </div>
        <!-- /page container -->
        <script type="text/javascript" src="assets/user/js/plugins/forms/validation/validate.min.js"></script>        
    </body>
</html>