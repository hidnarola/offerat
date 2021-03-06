$(document).ready(function () {
    jqueryValidate();
    errorMgsToDefault();
    $("#store_logo").on("change", function () {
        validate_logo('#store_logo');
    });
});

$(document).on('click', '#category_selection_btn', function () {
    var html = generatecategorySelectionBlock(categoryCloneNumber);
    $(document).find('#category_selection_wrapper').append(html);
    $(document).find('.sub_cat_section_' + categoryCloneNumber).hide();
    categoryCloneNumber++;
    reInitializeSelect2Control();
    $(document).find('#category_count').val(categoryCloneNumber);
});

$(document).on('click', '#sales_trend_btn', function () {
    var html = generateSalesTrendBlock(salesTrendNumber);
    $(document).find('#sales_trend_wrapper').append(html);
    salesTrendNumber++;
    $(document).find('#sales_trend_count').val(salesTrendNumber);
});

function reInitializeSelect2Control() {
    $('.select').select2({
        minimumResultsForSearch: Infinity,
    });
}

$(document).on('change', '.category_selection_dropdown', function () {
    var cloneNumber = $(this).data('cloneNumber');
    var sender = $(this);
    var categoryId = sender.val();

    $.ajax({
        method: 'POST',
        url: base_url + 'storeregistration/show_sub_category',
        data: {category_id: categoryId},
        success: function (response) {
            if (response != '') {
                $(document).find('#sub_category_' + cloneNumber).html('<option value="">Select Sub Category</option>');
                $(document).find('#sub_category_' + cloneNumber).append(response);
                $(document).find('#sub_category_' + cloneNumber).attr('required', 'required');
                $(document).find('.sub_cat_section_' + cloneNumber).show();
            } else {
                $(document).find('#sub_category_' + cloneNumber).html('');
                $(document).find('#sub_category_' + cloneNumber).removeAttr('required');
                $(document).find('.sub_cat_section_' + cloneNumber).hide();
            }
            $(document).find('#sub_category_' + cloneNumber).val("");
            $(document).find('#sub_category_' + cloneNumber).trigger('change');
        },
        error: function () {
            console.log("error occur");
        },
    });
});

$(document).on('click', '.category_selection_remove_btn', function () {
    var cloneNumber = $(this).data('cloneNumber');
    $(document).find('#category_selection_block_' + cloneNumber).remove();
    $(document).find('#category_count').val(cloneNumber);
});
$(document).on('click', '.location_remove_btn', function () {
    var cloneNumber = $(this).data('cloneNumber');
    var character = $(this).attr('character');
    $(document).find('#mall_selection_block_' + cloneNumber).remove();
    $(document).find('#location_block_' + cloneNumber).remove();
    $(document).find('#location_count').val(cloneNumber);
});
$(document).on('click', '.sales_trend_remove_btn', function () {
    var cloneNumber = $(this).data('cloneNumber');
    var character = $(this).attr('character');
    $(document).find('#sales_trend_block_' + cloneNumber).remove();
    $(document).find('#sales_trend_count').val(cloneNumber);
});
$(document).on('click', '.contact_number_remove_btn', function () {
    var cloneNumber = $(this).data('cloneNumber');
    $(document).find('#contact_number_block_' + cloneNumber).remove();
    $(document).find('#contact_no_count').val(cloneNumber);
});

function errorMgsToDefault() {
    var store_logo_error_wrapper = $('#store_logo_errors_wrapper');
    var store_logo_error = $('#store_logo_errors');
    store_logo_error_wrapper.addClass('display-none');
    store_logo_error.html('');
}

function validate_logo(control) {

    var store_logo_error_wrapper = $('#store_logo_errors_wrapper');
    var store_logo_error = $('#store_logo_errors');
    var is_valid = $('#is_valid');
    store_logo_error_wrapper.addClass('display-none');
    store_logo_error.html('');

    var fileUpload = $(control)[0];
    var FileUploadPath = fileUpload.value
    if (FileUploadPath != '') {
        if (typeof (fileUpload.files) != "undefined") {

            var Extension = FileUploadPath.substring(FileUploadPath.lastIndexOf('.') + 1).toLowerCase();

//            if (Extension == 'gif' || Extension == 'png' || Extension == 'jpeg' || Extension == 'jpg') {
            if (Extension == 'png' || Extension == 'jpeg' || Extension == 'jpg') {

                if (fileUpload.files && fileUpload.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                    }
                    reader.readAsDataURL(fileUpload.files[0]);
                    is_valid.val(1);
                }
                return true;
            } else {
                store_logo_error_wrapper.removeClass('display-none');
                store_logo_error.html('Store Logo Image should only PNG, JPG or JPEG file types.');
                is_valid.val(0);
                return false;
            }
        } else {
            store_logo_error_wrapper.removeClass('display-none');
            store_logo_error.html('Store Logo Image should only PNG, JPG or JPEG file types.');
            is_valid.val(0);
            return false;
        }
    } else {
        store_logo_error_wrapper.removeClass('display-none');
        store_logo_error.html('Store Logo Image should only PNG, JPG or JPEG file types.');
        is_valid.val(0);
        return false;
    }
}

$(document).on('keypress', '.number-only', function () {
    var ele = $(this)[0];
    var input_name = ele.name;
    var text = '';

    ele.onkeypress = function (e) {
        if (isNaN(this.value + "" + String.fromCharCode(e.charCode))) {
            $("#" + input_name + "-error").text('Please enter numeric value only');
            return false;
        } else {
            $("#" + input_name + "-error").text(text);
        }
    }
    ele.onpaste = function (e) {
        e.preventDefault();
    }

    if (this.value.length > 13) {
        return false;
    }
});

$(document).on('keypress', '.text-length', function () {
    if (this.value.length > 120) {
        return false;
    }
});