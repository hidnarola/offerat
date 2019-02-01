<div class="col-md-12">
    <form method="POST" action="<?php echo $post_url ?>" enctype="multipart/form-data" class="form-validate-jquery" name="manage_record">
        <div class="row">
            <div class="col-xs-12">
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <h5 class="panel-title"><a href="<?php echo $back_url ?>" title="Back"><i class="icon-arrow-left13 btn-icon"></i></a><?php echo $page_action ?></h5>
                        <div class="heading-elements">
                            <ul class="icons-list">
                                <li><a data-action="collapse"></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="col-xs-12">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Page Title <span class="text-danger">*</span></label>
                                    <div>
                                        <input type="hidden" name="id" id="id" value="<?php echo isset($terms_conditions['id']) ? $terms_conditions['id'] : set_value('id') ?>">
                                        <input type="text" class="form-control" placeholder="Name" name="page_title" id="page_title" required="required" value="<?php echo isset($terms_conditions['page_title']) ? $terms_conditions['page_title'] : set_value('page_title') ?>">
                                    </div>
                                </div>        
                            </div>
                            <?php if (!empty($terms_conditions['page_type'])) { ?>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Page Type</label>
                                        <div>
                                            <input type="text" readonly class="form-control" value="<?= (isset($terms_conditions['page_type'])) ? $terms_conditions['page_title'] : '' ?>" >
                                        </div>
                                    </div>        
                                </div>
                            <?php } ?>
                        </div>
                        <div class="col-xs-12">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="display-block">Page Content <span class="text-danger">*</span></label>                                        
                                    <textarea id="editor" placeholder="Page Content" name="page_content"><?= (isset($terms_conditions['page_content'])) ? $terms_conditions['page_content'] : '' ?></textarea>
                                </div>       
                            </div>
                        </div>

                        <div class="col-xs-12">                              
                            <div class="text-right">
                                <a href="<?php echo $back_url ?>" class="btn bg-grey-300 btn-labeled"><b><i class="icon-arrow-left13"></i></b>Back</a>
                                <button type="submit" class="btn bg-teal btn-labeled btn-labeled-right"><b><i class="icon-arrow-right14"></i></b>Save</button>
                            </div>
                        </div>                            
                    </div>
                </div>
            </div>
        </div>
    </form>    
</div>
<script src="assets/ckeditor/ckeditor.js" type="text/javascript"></script>
<script src="assets/ckeditor/samples/js/sample.js" type="text/javascript"></script>
<script type="text/javascript">
    $(function () {
        jqueryValidate();
        initSample();
    });
</script>