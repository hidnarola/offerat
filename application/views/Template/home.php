<!DOCTYPE html>
<html lang="en" class="home_page_html">
    <head>
        <?php
        $this->load->view('Template/head');
        ?>        
    </head>
    <body class="home_page_body">
        <div id="site_wrapper"> 
            <?php
            $uri_segment_1 = strtolower($this->uri->segment(1));
            ?>            
            <div class="page-container">
                <div class="page-content">
                    <div class="content-wrapper">                       
                        <div class="content">
                            <?php echo $body; ?>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
