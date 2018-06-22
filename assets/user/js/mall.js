$(document).ready(function () {
    jqueryValidate();
    errorMgsToDefault();
    $("#mall_logo").on("change", function () {
        validate_logo('#mall_logo');
    });
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

$(document).on('click', '.sales_trend_remove_btn', function () {
    var cloneNumber = $(this).data('cloneNumber');
    var character = $(this).attr('character');
    $(document).find('#sales_trend_block_' + cloneNumber).remove();
    $(document).find('#sales_trend_count').val(cloneNumber);
});

function errorMgsToDefault() {
    var mall_logo_error_wrapper = $('#mall_logo_errors_wrapper');
    var mall_logo_error = $('#mall_logo_errors');
    mall_logo_error_wrapper.addClass('display-none');
    mall_logo_error.html('');
}

function validate_logo(control) {

    var mall_logo_error_wrapper = $('#mall_logo_errors_wrapper');
    var mall_logo_error = $('#mall_logo_errors');
    var is_valid = $('#is_valid');
    mall_logo_error_wrapper.addClass('display-none');
    mall_logo_error.html('');

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
                mall_logo_error_wrapper.removeClass('display-none');
                mall_logo_error.html('Mall Logo Image should only PNG, JPG or JPEG file types.');
                is_valid.val(0);
                return false;
            }
        } else {
            mall_logo_error_wrapper.removeClass('display-none');
            mall_logo_error.html('Mall Logo Image should only PNG, JPG or JPEG file types.');
            is_valid.val(0);
            return false;
        }
    } else {
        mall_logo_error_wrapper.removeClass('display-none');
        mall_logo_error.html('Mall Logo Image should only PNG, JPG or JPEG file types.');
        is_valid.val(0);
        return false;
    }
}