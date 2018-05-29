$(document).ready(function () {
    jqueryValidate();
//    $(document).on('change', '[data-type="reloadMap"]', initAutocomplete);
});

var categoryCloneNumber = 1;
var mallCloneNumber = 1;

$(document).on('click', '#category_selection_btn', function () {
    var html = generatecategorySelectionBlock(categoryCloneNumber);
    $(document).find('#category_selection_wrapper').append(html);
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
    $(document).find('#sub_category_' + cloneNumber).val("");
    $(document).find('#sub_category_' + cloneNumber).trigger('change');
    $.ajax({
        method: 'POST',
        url: base_url + 'storeregistration/show_sub_category',
        data: {category_id: categoryId},
        success: function (response) {
            $(document).find('#sub_category_' + cloneNumber).html(response);
        },
        error: function () {
            console.log("error occur");
        },
    });
});

$(document).on('click', '#mall_selection_btn', function () {

    var countryId = $(document).find('#id_country').val();
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
    var html = generatemallSelectionBlock(mallCloneNumber);
    $(document).find('#mall_selection_wrapper').append(html);
    mallCloneNumber++;
    reInitializeSelect2Control();
//    initAutocomplete();
    $(document).find('#location_count').val(mallCloneNumber);
});

$(document).on('click', '.category_selection_remove_btn', function () {
    var cloneNumber = $(this).data('cloneNumber');
    $(document).find('#category_selection_block_' + cloneNumber).remove();
    $(document).find('#category_count').val(cloneNumber);
});
$(document).on('click', '.mall_selection_remove_btn', function () {
    var cloneNumber = $(this).data('cloneNumber');
    $(document).find('#mall_selection_block_' + cloneNumber).remove();
    $(document).find('#location_count').val(cloneNumber);
});