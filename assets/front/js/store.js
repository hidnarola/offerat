$(document).ready(function () {
    jqueryValidate();
//    $(document).find('#mall_selection_wrapper').hide();
//    $(document).on('change', '[data-type="reloadMap"]', initAutocomplete);
});

var categoryCloneNumber = 1;
var mallCloneNumber = 0;

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
//            $(document).find('#mall_selection_wrapper').show();
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
            console.log(typeof response + "==" + cloneNumber);
            if (response != '') {
                $(document).find('#sub_category_' + cloneNumber).html(response);
                $(document).find('.sub_cat_section_' + cloneNumber).show();
            } else {
                console.log("else part");
                $(document).find('#sub_category_' + cloneNumber).html('');
                $(document).find('.sub_cat_section_' + cloneNumber).hide();
            }
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

    if (cloneNumber == 0) {
        $(document).find('.map_div').removeClass('col-md-6');
        $(document).find('.map_div').addClass('col-md-12');
    }
    for (var i = 0; i < markers.length; i++) {
        if (markers[i].label == character) {
            //Remove the marker from Map                  
            markers[i].setMap(null);
            //Remove the marker from array.
            markers.splice(i, 1);
            return;
        }
    }
});