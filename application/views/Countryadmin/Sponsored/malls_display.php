<?php
$remove_values = array();
$id_sponsored_log = 0;
if (isset($mall_sponsored) && sizeof($mall_sponsored) > 0) {
    ?>
    <fieldset class="content-group">                                                                                                    
        <div class="clear-float">
            <div class="col-xs-12 business_category_div">                
                <div class="col-md-2">
                    <label>Position</label>
                </div>
                <div class="col-md-2">
                    <label>From - To Date</label>
                </div>                
                <div class="col-md-8 text-right">
                    <a href="<?php echo 'country-admin/malls/sponsored/' . $mall_id; ?>" target="_blank" class="btn btn-info btn-labeled"><b><i class="icon-star-half"></i></b>Edit Sponsored Details</a>
                    <a href="<?php echo 'country-admin/malls/save/' . $mall_id; ?>" target="_blank" class="btn btn-primary btn-labeled"><b><i class="icon-store2"></i></b>Edit Mall</a>
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
                                <option value="<?php echo $i; ?>" <?php echo ($mall_sponsored['position'] == $i) ? 'selected=selected' : ''; ?>><?php echo $i; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="icon-calendar22"></i></span>
                            <?php
                            if (isset($mall_sponsored['from_date']) && !empty($mall_sponsored['from_date']) && isset($mall_sponsored['to_date']) && !empty($mall_sponsored['to_date'])) {
                                $from_date = date_create($mall_sponsored['from_date']);
                                $from_date_text = date_format($from_date, "d-m-Y");
                                $to_date = date_create($mall_sponsored['to_date']);
                                $to_date_text = date_format($to_date, "d-m-Y");
                            } else {
                                $from_date_text = $to_date_text = '';                                
                            }
                            ?>

                            <input type="text" name="from_to_date" id="from_to_date" class="form-control daterange-from-to" value="<?php echo (!empty($from_date_text)) ? $from_date_text . ' - ' . $to_date_text : ''; ?>">
                        </div>
                    </div>
                </div>                    
            </div>
        </div>            
    </fieldset>    
<?php } else { ?>
    <fieldset class="content-group">                                                                                                    
        <div class="clear-float">
            <div class="col-xs-12 business_category_div">
                No results found.                                         
            </div>
        </div>                                    
    </fieldset>
<?php } ?>