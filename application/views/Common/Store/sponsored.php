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
                            if (isset($store_categories) && sizeof($store_categories) > 0) {
                                ?>
                                <fieldset class="content-group">                                                                                                    
                                    <div class="clear-float">
                                        <div class="col-xs-12 business_category_div">
                                            <div class="col-md-2">
                                                <label>Category</label>
                                            </div>
                                            <div class="col-md-2">
                                                <label>Sub-category</label>
                                            </div>
                                            <div class="col-md-2">
                                                <label>Position</label>
                                            </div>
                                            <div class="col-md-3">
                                                <label>From - To Date</label>
                                            </div>
                                            <div class="col-md-3 delete_section">
                                                <input type="checkbox" class="styled-checkbox-1" name="select_all" id="select_all" placeholder="" value="">
                                                <span class="text-size-mini">Check All</span>
                                                <button type="submit" id="delete_sponsored" class="btn bg-danger"><b></b>Delete</button>
                                            </div>
                                        </div>
                                    </div>                                    
                                </fieldset>
                                <fieldset class="content-group">                                                                    
                                    <?php
                                    foreach ($store_categories as $key => $cat) {
                                        ?>
                                        <div class="clear-float">
                                            <div class="col-xs-12 business_category_div">
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <div>
                                                            <select id="category_<?php echo $key; ?>" name="category_<?php echo $key; ?>" class="select form-control" required="required">                                                                    
                                                                <?php foreach ($category_list as $list) { ?>
                                                                    <?php if ($list['id_category'] == $cat['id_category']) { ?>
                                                                        <option value="<?php echo $list['id_category']; ?>"><?php echo $list['category_name']; ?></option>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group <?php echo ($cat['id_sub_category'] > 0) ? '' : 'display-none'; ?>">                                        
                                                        <div>
                                                            <?php
                                                            $select_sub_category = array(
                                                                'table' => tbl_sub_category,
                                                                'where' => array('status' => ACTIVE_STATUS, 'is_delete' => IS_NOT_DELETED_STATUS, 'id_sub_category' => $cat['id_sub_category'])
                                                            );

                                                            $sub_category_list = $this->Common_model->master_select($select_sub_category);
                                                            ?>
                                                            <select id="sub_category_<?php echo $key; ?>" name="sub_category_<?php echo $key; ?>" class="select form-control">
                                                                <?php if (isset($sub_category_list) && sizeof($sub_category_list) > 0) { ?>                                                                        
                                                                    <?php foreach ($sub_category_list as $list) { ?>
                                                                        <?php if ($list['id_sub_category'] == $cat['id_sub_category']) { ?>
                                                                            <option value="<?php echo $list['id_sub_category']; ?>" ><?php echo $list['sub_category_name']; ?></option>
                                                                            <?php
                                                                        }
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <select name="position_<?php echo $key; ?>" id="position_<?php echo $key; ?>" class="select form-control">
                                                            <option value="">Position</option>
                                                            <?php
                                                            for ($i = 1; $i <= 5; $i++) {
                                                                ?>
                                                                <option value="<?php echo $i; ?>" <?php echo ($cat['position'] == $i) ? 'selected=selected' : ''; ?>><?php echo $i; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="icon-calendar22"></i></span>
                                                            <?php
                                                            if (isset($cat['from_date']) && !empty($cat['from_date']) && isset($cat['to_date']) && !empty($cat['to_date'])) {
                                                                $from_date = date_create($cat['from_date']);
                                                                $from_date_text = date_format($from_date, "d-m-Y");
                                                                $to_date = date_create($cat['to_date']);
                                                                $to_date_text = date_format($to_date, "d-m-Y");
                                                            } else {
                                                                $from_date_text = $to_date_text = '';
                                                                $remove_values[] = $key;
                                                            }
                                                            ?>

                                                            <input type="text" name="from_to_date_<?php echo $key; ?>" id="from_to_date_<?php echo $key; ?>" class="form-control daterange-from-to" value="<?php echo (!empty($from_date_text)) ? $from_date_text . ' - ' . $to_date_text : ''; ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <?php
                                                        if ((int) $cat['id_sponsored_log'] > 0) {
                                                            $id_sponsored_log++;
                                                            ?>
                                                            <div>
                                                                <input type="checkbox" class="checkbox" name="delete_sponsored_ids[]" id="delete_sponsored_id" placeholder="" value="<?php echo $cat['id_sponsored_log']; ?>">                                                                
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }
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
    var select_all = document.getElementById("select_all");
    var checkboxes = document.getElementsByClassName("checkbox");

    //select all checkboxes
    select_all.addEventListener("change", function (e) {
        for (i = 0; i < checkboxes.length; i++) {
            checkboxes[i].checked = select_all.checked;
        }
    });

    for (var i = 0; i < checkboxes.length; i++) {
        checkboxes[i].addEventListener('change', function (e) {
            if (this.checked == false) {
                select_all.checked = false;
            }
            if (document.querySelectorAll('.checkbox:checked').length == checkboxes.length) {
                select_all.checked = true;
            }
        });
    }

    $(document).on('click', '#delete_sponsored', function () {
        var historySelectList = $('select#delete_sponsored_id');

        var selectedValue = $('option:selected', historySelectList).val();

        var checkedValues = $('input:checkbox:checked').map(function () {
            return this.value;
        }).get();

        $('#checked_val').val(checkedValues);

        if ($('#checked_val').val() != '') {
            $(document).find("#deleteConfirm").modal('show');
            $(document).on("click", ".yes_i_want_delete", function (e) {
                var val = $(this).val();
                if (val == 'yes') {
                    $("#manage_record").attr('action', 'country-admin/stores/sponsored/delete/<?php echo $store_id; ?>').submit();
//                    $(document).find('#manage_record').submit();
                }
            });
            return false;
        } else {
            $("#message_popup").modal('show');
            $("#alert_message").html("Please select Record to Delete");
            return false;
        }
    });

<?php if ($id_sponsored_log > 0) { ?>
        $(document).find('.delete_section').show();
<?php } else { ?>
        $(document).find('.delete_section').hide();
<?php } ?>
</script>