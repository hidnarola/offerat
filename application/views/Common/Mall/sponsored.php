<div class="row">
    <div class="col-md-12">
        <form method="POST" action="" enctype="multipart/form-data" class="form-validate-jquery" name="manage_record" id="manage_record">
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-flat">
                        <div class="panel-heading">                            
                            <div class="heading-elements">
                                <ul class="icons-list">
                                    <li><a data-action="collapse"></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="panel-body">
                            <?php
                            $remove_values = array();
                            $id_sponsored_log = 0;
                            if (isset($mall_details) && sizeof($mall_details) > 0) {
                                ?>
                                <fieldset class="content-group">                                                                                                    
                                    <div class="clear-float">
                                        <div class="col-xs-12 business_category_div">
                                            <div class="col-md-2">
                                                <label>Position</label>
                                            </div>
                                            <div class="col-md-3">
                                                <label>From - To Date</label>
                                            </div>
                                        </div>
                                    </div>                                    
                                </fieldset>
                                <fieldset class="content-group">                                                                                                        
                                    <div class="clear-float">
                                        <div class="col-xs-12 business_category_div">                                                
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <select name="position" id="position" class="select form-control">
                                                        <option value="">Position</option>
                                                        <?php
                                                        for ($i = 1; $i <= 5; $i++) {
                                                            ?>
                                                            <option value="<?php echo $i; ?>" <?php echo ($mall_details['position'] == $i) ? 'selected=selected' : ''; ?>><?php echo $i; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="icon-calendar22"></i></span>
                                                        <?php
                                                        if (isset($mall_details['from_date']) && !empty($mall_details['from_date']) && isset($mall_details['to_date']) && !empty($mall_details['to_date'])) {
                                                            $from_date = date_create($mall_details['from_date']);
                                                            $from_date_text = date_format($from_date, "d-m-Y");
                                                            $to_date = date_create($mall_details['to_date']);
                                                            $to_date_text = date_format($to_date, "d-m-Y");
                                                        } else {
                                                            $from_date_text = $to_date_text = '';
                                                                $remove_values[] = 0;
                                                        }
                                                        ?>

                                                        <input type="text" name="from_to_date" id="from_to_date_0" class="form-control daterange-from-to" value="<?php echo (!empty($from_date_text)) ? $from_date_text . ' - ' . $to_date_text : ''; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <?php
                                                    if ((int) $mall_details['id_sponsored_log'] > 0) {
                                                        $id_sponsored_log++;
                                                        ?>
                                                        <div>
                                                            <button type="submit" id="delete_sponsored" class="btn bg-danger"><b></b>Delete</button>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    $this->session->set_userdata('remove_values', $remove_values);
                                    ?>
                                </fieldset>
                                <div class="text-right">
                                    <input type="hidden" id="checked_val" name="checked_val"/>
                                    <a href="<?php echo $back_url; ?>" class="btn bg-grey-300 btn-labeled"><b><i class="icon-arrow-left13"></i></b>Back</a>
                                    <button type="submit" class="btn bg-teal btn-labeled btn-labeled-right"><b><i class="icon-arrow-right14"></i></b>Save</button>
                                </div>
                            <?php } else { ?>
                            No results found.
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<?php
$this->load->view('Common/delete_alert');
$this->load->view('Common/message_alert');
?>
<script language="javascript">
    $(document).on('click', '#delete_sponsored', function () {
        $(document).find("#deleteConfirm").modal('show');
        $(document).on("click", ".yes_i_want_delete", function (e) {
            var val = $(this).val();
            if (val == 'yes') {
                $("#manage_record").attr('action', 'country-admin/malls/sponsored/delete/<?php echo $mall_id; ?>').submit();
            }
        });
        return false;

    });

<?php if ($id_sponsored_log > 0) { ?>
        $(document).find('.delete_section').show();
<?php } else { ?>
        $(document).find('.delete_section').hide();
<?php } ?>
</script>