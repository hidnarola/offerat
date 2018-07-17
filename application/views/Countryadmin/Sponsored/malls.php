<div class="col-md-12">
    <div id="sponsored_error_wrapper" class="alert alert-danger alert-bordered display-none">
        <span id="sponsored_details_error_msg"></span>
    </div>
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
                        <fieldset class="content-group">                                                                                                    
                            <div class="col-md-3">
                                <?php if (isset($malls_list) && sizeof($malls_list) > 0) { ?>
                                    <select id="mall_id" name="mall_id" class="form-control select-search">
                                        <option class="">Select Mall</option>
                                        <?php foreach ($malls_list as $list) { ?>
                                            <option value="<?php echo $list['id_mall']; ?>"><?php echo $list['mall_name']; ?></option>
                                        <?php } ?>
                                    </select>
                                <?php } ?>
                            </div>                                            
                        </fieldset>
                        <div class="data-sponsored-display"></div>                            
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script language="javascript">
    $('#mall_id').change(function (e) {
        $(document).find('.data-sponsored-display').removeClass('panel panel-white');
        $(document).find('.data-sponsored-display').html('');
        var mall_id = $(this).val();
        if (mall_id > 0) {
            displayElementBlock('loader');
            var getData = '';
            $.ajax({
                'method': 'GET',
                'url': 'country-admin/sponsored/mall_details/' + mall_id,
                'success': function (response) {
                    if (response !== '') {
                        var obj = JSON.parse(response);
                        if (obj.status === '1') {
                            $(document).find('.data-sponsored-display').addClass('panel panel-white');
                            $(document).find('.data-sponsored-display').html(obj.sub_view);
                            $(document).find('.data-sponsored-display input, .data-sponsored-display select').attr('disabled', 'disabled');
                            displayElementNone('loader');
                        } else {
                            displayServerMsg('sponsored_error_wrapper', 'sponsored_details_error_msg', 'Something went wrong! please try again later.');
                            displayElementNone('loader');
                        }
                    } else {
                        displayServerMsg('sponsored_error_wrapper', 'sponsored_details_error_msg', 'Something went wrong! please try again later.');
                        displayElementNone('loader');
                    }
                },
                'error': function () {
                    displayServerMsg('sponsored_error_wrapper', 'sponsored_details_error_msg', 'Something went wrong! please try again later.');
                    displayElementNone('loader');
                },
            });
        }
    });
</script>