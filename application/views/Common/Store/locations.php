<div class="row">
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
                                <?php
                                foreach ($store_locations as $loc) {
                                    ?>                            
                                    <div class="col-md-3 col-xs-12">
                                        <div class="col-md-2 col-xs-2">
                                            <div class="form-group">                                                
                                                <div>
                                                    <!--styled-checkbox-1-->
                                                    <input type="checkbox" class="checkbox" name="delete_location_ids[]" id="delete_location_id" placeholder="" value="<?php echo $loc['id_store_location']; ?>">
                                                </div>
                                            </div>        
                                        </div>
                                        <div class="col-md-4 col-xs-5">
                                            <div class="form-group">                                            
                                                <div>
                                                    <input type="text" class="form-control" placeholder="Latitude"  value="<?php echo $loc['latitude']; ?>" readonly>
                                                </div>
                                            </div>        
                                        </div>
                                        <div class="col-md-4 col-xs-5">
                                            <div class="form-group">                                            
                                                <div>
                                                    <input type="text" class="form-control" placeholder="Longitude"  value="<?php echo $loc['longitude']; ?>" readonly>
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