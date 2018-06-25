$(document).ready(function () {
    jqueryValidate();

    errorMgsToDefault();
    $("#store_logo").on("change", function () {
        validate_logo('#store_logo');
    });

});

var categoryCloneNumber = 1;

$(document).on('click', '#category_selection_btn', function () {
    var html = generatecategorySelectionBlock(categoryCloneNumber);
    $(document).find('#category_selection_wrapper').append(html);

    $(document).find('.sub_cat_section_' + categoryCloneNumber).hide();

    categoryCloneNumber++;
    reInitializeSelect2Control();
    $(document).find('#category_count').val(categoryCloneNumber);
});

function reInitializeSelect2Control() {
    $('.select').select2({
        minimumResultsForSearch: Infinity,
    });
}

$(document).on('change', '#id_country', function () {

    var sender = $(this);
    var countryId = sender.val();
    $(document).find('.mall_selection_dropdown').val(0);
    $(document).find('.mall_selection_dropdown').trigger('change');
    $.ajax({
        method: 'POST',
        url: base_url + 'storeregistration/show_mall',
        data: {country_id: countryId},
        success: function (response) {
            $(document).find('.mall_selection_dropdown').html(response);
        },
        error: function () {
            console.log("error occur");
        },
    });
});

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
$(document).on('click', '.mall_selection_remove_btn', function () {
    var cloneNumber = $(this).data('cloneNumber');
    var character = $(this).attr('character');
    $(document).find('#mall_selection_block_' + cloneNumber).remove();
    $(document).find('#location_count').val(cloneNumber);
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