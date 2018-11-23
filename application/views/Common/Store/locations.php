<div class="col-md-12">
    <form method="POST" action="<?php echo $action_url; ?>" enctype="multipart/form-data" class="form-validate-jquery" name="manage_record" id="manage_record">
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
                        <?php if (isset($store_locations) && sizeof($store_locations) > 0) { ?>                                
                            <div class="col-md-12 col-xs-12 text-right">
                                <div class="form-group">
                                    <div>
                                        <input type="checkbox" class="styled-checkbox-1" name="select_all" id="select_all"  placeholder="" value="">
                                        <span class="text-size-mini">Check All</span>            
                                    </div>
                                </div>        
                            </div>
                            <div class="col-md-12 col-xs-12">
                                <div class="col-md-1 col-xs-2">
                                    <div class="form-group">
                                        &nbsp;
                                    </div>
                                </div>
                                <div class="col-md-1 col-xs-2">
                                    <div class="form-group">
                                        Branch / Mall
                                    </div>
                                </div>
                                <div class="col-md-2 col-xs-2">
                                    <div class="form-group">
                                        Email
                                    </div>
                                </div>
                                <div class="col-md-2 col-xs-2">
                                    <div class="form-group">
                                        Contact Number
                                    </div>
                                </div>
                                <div class="col-md-2 col-xs-2">
                                    <div class="form-group">
                                        Contact Number 1
                                    </div>
                                </div>
                                <div class="col-md-2 col-xs-2">
                                    <div class="form-group">
                                        Contact Number 2
                                    </div>
                                </div>
                                <div class="col-md-1 col-xs-2">
                                    <div class="form-group">
                                        Longitude
                                    </div>
                                </div>
                                <div class="col-md-1 col-xs-2">
                                    <div class="form-group">
                                        Latitude
                                    </div>
                                </div>
                            </div>
                            <?php
                            $placeholder = 'Branch Name / Mall';
                            $field_val = '';

                            foreach ($store_locations as $loc) {
                                ?>                            
                                <div class="col-md-12 col-xs-12">
                                    <div class="col-md-1 col-xs-2">
                                        <div class="form-group">                                                
                                            <div class="checkbox img-checkbox">
                                                <!--styled-checkbox-1-->
                                                <input type="checkbox" class="checkbox" name="delete_location_ids[]" id="delete_location_id<?php echo $loc['id_store_location']; ?>" placeholder="" value="<?php echo $loc['id_store_location']; ?>"><label for="delete_location_id<?php echo $loc['id_store_location']; ?>"></label>
                                            </div>
                                        </div>        
                                    </div>
                                    <div class="col-md-1 col-xs-5">
                                        <div class="form-group">                                            
                                            <div>
                                                <?php
                                                if (!empty($loc['mall_name'])) {
                                                    $placeholder = 'Mall';
                                                    $field_val = $loc['mall_name'];
                                                }

                                                if (!empty($loc['branch_name'])) {
                                                    $placeholder = 'Branch';
                                                    $field_val = $loc['branch_name'];
                                                }
                                                ?>
                                                <input type="text" class="form-control" placeholder="<?= $placeholder ?>"  value="<?= $field_val ?>" title="<?= $placeholder . ' - ' . $field_val ?>" readonly>
                                            </div>
                                        </div>        
                                    </div>
                                    <div class="col-md-2 col-xs-5">
                                        <div class="form-group">                                            
                                            <div>
                                                <input type="text" class="form-control" placeholder="Email"  value="<?php echo $loc['email']; ?>" readonly>
                                            </div>
                                        </div>
                                    </div>                                     
                                    <div class="col-md-2 col-xs-5">
                                        <div class="form-group">                                            
                                            <div>
                                                <input type="text" class="form-control" placeholder="Contact Number"  value="<?php echo $loc['contact_number']; ?>" readonly>
                                            </div>
                                        </div>
                                    </div>                                     
                                    <div class="col-md-2 col-xs-5">
                                        <div class="form-group">                                            
                                            <div>
                                                <input type="text" class="form-control" placeholder="Contact Number 1"  value="<?php echo $loc['contact_number_1']; ?>" readonly>
                                            </div>
                                        </div>
                                    </div>                                     
                                    <div class="col-md-2 col-xs-5">
                                        <div class="form-group">                                            
                                            <div>
                                                <input type="text" class="form-control" placeholder="Contact Number 2"  value="<?php echo $loc['contact_number_2']; ?>" readonly>
                                            </div>
                                        </div>
                                    </div>                                     
                                    <div class="col-md-1 col-xs-5">
                                        <div class="form-group">                                            
                                            <div>
                                                <input type="text" class="form-control" placeholder="Longitude"  value="<?php echo $loc['longitude']; ?>" readonly>
                                            </div>
                                        </div>
                                    </div>                                     
                                    <div class="col-md-1 col-xs-5">
                                        <div class="form-group">                                            
                                            <div>
                                                <input type="text" class="form-control" placeholder="Latitude"  value="<?php echo $loc['latitude']; ?>" readonly>
                                            </div>
                                        </div>        
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="text-right">
                                <input type="hidden" id="checked_val" name="checked_val"/>
                                <a href="<?php echo $back_url ?>" class="btn bg-grey-300 btn-labeled"><b><i class="icon-arrow-left13"></i></b>Back</a>
                                <button type="submit" id="delete_locations" class="btn bg-danger btn-labeled btn-labeled-right"><b><i class="icon-arrow-right14"></i></b>Delete</button>
                            </div>

                            <?php
                        } else {
                            echo 'No Results Found.';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </form>
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

    $(document).on('click', '#delete_locations', function () {
        var historySelectList = $('select#delete_location_id');
        var selectedValue = $('option:selected', historySelectList).val();

        var checkedValues = $('input:checkbox:checked').map(function () {
            return this.value;
        }).get();

        $('#checked_val').val(checkedValues);

        console.log(checkedValues);

        if ($('#checked_val').val() != '') {

            $(document).find("#deleteConfirm").modal('show');

            $(document).on("click", ".yes_i_want_delete", function (e) {
                var val = $(this).val();
                if (val == 'yes') {
                    $(document).find('#manage_record').submit();
                }
            });
            return false;
        } else {
            $("#message_popup").modal('show');
            $("#alert_message").html("Please select Record to Delete");
            return false;
        }

    });
</script>