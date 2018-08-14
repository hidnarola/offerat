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
                        <?php if (isset($image_list) && sizeof($image_list) > 0) { ?>
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="col-md-4">
                                        <a href="<?php echo $edit_url; ?>" class="btn btn-info">Insert Image(s)</a>
                                    </div>
                                    <div class="col-md-4">
                                        <div id="reorderHelper" class="light_box" style="display:none;">1. Drag and drop to change the order of images.<br>2. Click SAVE when finished.</div>
                                    </div>
                                    <div class="col-md-4 text-right">
                                        <a href="javascript:void(0);" class="reorder_link" id="saveReorder">Change order of images </a>   
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if (isset($image_list) && sizeof($image_list) > 0) { ?>
                            <div class="gallery">
                                <ul class="reorder_ul reorder-photos-list">
                                    <?php
                                    foreach ($image_list as $list) {
                                        ?>
                                        <li id="image_li_<?php echo $list['id_offer_announcement_image']; ?>" class="ui-sortable-handle">
                                            <div class="checkbox img-checkbox">
                                                <!--styled-checkbox-1-->
                                                <?php if ($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE) { ?>
                                                    <input type="checkbox" name="delete_image_ids[]" id="delete_image_id<?php echo $list['id_offer_announcement_image']; ?>" class="checkbox" value="<?php echo $list['id_offer_announcement_image']; ?>" /><label for="delete_image_id<?php echo $list['id_offer_announcement_image']; ?>"></label>                                                        
                                                <?php } ?>
                                            </div>

                                            <a data-fancybox="gallery" href="<?php echo offer_media_path . $list['image_name']; ?>" style="float:none;" class="image_link">                            
                                                <img src="<?php echo offer_img_start_part . $list['image_name'] . offer_img_end_part; ?>" onerror="image_not_found(image_0)">
                                            </a>
                                        </li>
                                        <?php
                                    }
                                    ?>
                                </ul>
                            </div>
                            <?php
                        } else {
                            echo 'No Results Found.';
                        }
                        ?>
                        <?php if (isset($image_list) && sizeof($image_list) > 0) { ?>
                            <div class="text-right btn_end">
                                <div class="form-group">
                                    <div>                                    
                                        <a href="<?php echo @$back_url ?>" class="image_back_link btn bg-grey-300 btn-labeled"><b><i class="icon-arrow-left13"></i></b>Back</a>                                    
                                        <input type="hidden" id="checked_val" name="checked_val"/>
                                        <?php if ($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE) { ?>                                
                                            <input type="checkbox" class="styled-checkbox-1" name="select_all" id="select_all"  placeholder="" value="">
                                            <span class="text-size-mini">Check All</span>
                                            <button type="submit" id="delete_images" class="btn bg-danger btn-labeled btn-labeled-right"><b><i class="icon-arrow-right14"></i></b>Delete</button>
                                                    <?php } ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
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
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script language="javascript">
<?php if ($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE) { ?>
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
<?php } ?>

    $(document).on('click', '#delete_images', function () {
        var historySelectList = $('select#delete_image_id');
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

    $(document).ready(function () {
        $('.reorder_link').on('click', function () {
            $('ul.reorder-photos-list').sortable({tolerance: 'pointer'});
            $('.reorder_link').html('Save');
            $('.reorder_link').attr('id', 'saveReorder');
            $('#reorderHelper').slideDown('slow');
            $('.image_link').attr('href', 'javascript:void(0);');
            $('.image_link').css('cursor', 'move');
            $('#saveReorder').click(function (e) {
                if (!$('#saveReorder i').length) {
                    displayElementBlock('loader');
                    $('ul.reorder-photos-list').sortable('destroy');
                    $('#reorderHelper').html("Sorting Images - This could take a moment. Please don't navigate away from this page.").removeClass('light_box').addClass('notice notice_error');

                    var h = [];
                    $('ul.reorder-photos-list li').each(function () {
                        h.push($(this).attr('id').substr(9));
                    });

                    $.ajax({
                        type: 'POST',
                        url: '<?php echo $image_sort_order_url; ?>',
                        data: {ids: " " + h + ""},
                        success: function () {
//                            return false;
                            window.location.reload();
                        }
                    });
                    return false;
                }
                e.preventDefault();
            });
        });
    });
</script>