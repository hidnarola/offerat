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