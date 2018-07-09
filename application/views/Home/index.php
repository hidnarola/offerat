<div class="home_page">    
    <?php if ($this->session->flashdata('error_msg')) { ?>
        <div class="alert alert-danger alert-styled-right alert-bordered">
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
            <?php echo $this->session->flashdata('error_msg'); ?>
        </div>
    <?php } ?>

    <?php if (validation_errors()) { ?>
        <div class="alert alert-danger alert-styled-right alert-bordered">
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
            <?php echo validation_errors(); ?>
        </div>
    <?php } ?>

    <?php if ($this->session->flashdata('success_msg')) { ?>
        <div class="alert alert-success alert-styled-right alert-arrow-right alert-bordered">
            <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
            <?php echo $this->session->flashdata('success_msg') ?>
        </div>
    <?php } ?>
    <div class="header_cstm">
        <!--<h1 class="site_name"><?php //echo SITENAME;               ?></h1>-->
        <div class="svg_logo">             
            <?php $this->load->view('svg_html/home_page_logo'); ?>
        </div>
    </div>

    <ul class="ul_menu">        
        <li><a href="login" class="btn btn-primary link">Login</a></li>
        <li><a href="store-registration" class="btn btn-primary link">Add New Store</a></li>
        <li><a href="contact-us" class="btn btn-primary link">Contact Us</a></li>            
        <li><a href="about-us" class="btn btn-primary link">About Us</a><li>
    </ul>
    <ul class="ap_store_btn">
        <li><a href="javascript:void(0);"> <img src="assets/user/images/site/app_store.png"></a></li>
        <li><a href="javascript:void(0);"><img src="assets/user/images/site/gogle_lay.png"></a></li>
    </ul>
</div>
<script>
    $(document).find('.home_page_html').css('height', $(document).height());
    $(window).on("resize orientationchange", function (event) {
        $(document).find('.home_page_html').css('height', $(document).height());
    });
</script>