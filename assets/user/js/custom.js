$(function () {

    $('[data-toggle="tooltip"]').tooltip();

    $('.simpleDatePicker').pickadate({
        selectMonths: true,
        selectYears: 4,
        showMonthsShort: true,
        formatSubmit: 'yyyy-mm-dd',
    });

    var yesterday = new Date((new Date()).valueOf() - 1000 * 60 * 60 * 24);
    $('.simpleDatePickerBlockPreviousDates').pickadate({
        selectMonths: true,
        selectYears: 4,
        showMonthsShort: true,
        formatSubmit: 'yyyy-mm-dd',
        disable: [
            {from: [0, 0, 0], to: yesterday}
        ],
    });

    $('.simpleDatePickerBlockFutureDates').pickadate({
        selectMonths: true,
        selectYears: 4,
//        selectYears: 150,
        showMonthsShort: true,
        formatSubmit: 'yyyy-mm-dd',
        max: new Date(),
    });

    $('.daterange-basic').daterangepicker({

        applyClass: 'bg-slate-600',
        cancelClass: 'btn-default',
        showDropdowns: false,
        selectYears: 0,
        autoUpdateInput: false,
        locale: {
            format: 'DD-MM-YYYY'
        }
    });

//    $('.daterange-basic').data('daterangepicker').setStartDate();
//    $('.daterange-basic').data('daterangepicker').setEndDate(null);

    $('.daterange-basic-datatable').daterangepicker({
        ranges: {
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')],
            'Last 3 Months': [moment().subtract('month', 3).startOf('month'), moment().subtract('month', 1).endOf('month')],
            'Last 6 Months': [moment().subtract('month', 6).startOf('month'), moment().subtract('month', 1).endOf('month')],
            'Last 12 Months': [moment().subtract('month', 12).startOf('month'), moment().subtract('month', 1).endOf('month')],
        },
        applyClass: 'bg-slate-600',
        cancelClass: 'btn-default',
        showDropdowns: true,
        autoUpdateInput: false,
        locale: {
            format: 'MM/DD/YYYY'
        }
    });

    $('.daterange-basic-datatable-left').daterangepicker({
        ranges: {
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')],
            'Last 3 Months': [moment().subtract('month', 3).startOf('month'), moment().subtract('month', 1).endOf('month')],
            'Last 6 Months': [moment().subtract('month', 6).startOf('month'), moment().subtract('month', 1).endOf('month')],
            'Last 12 Months': [moment().subtract('month', 12).startOf('month'), moment().subtract('month', 1).endOf('month')],
        },
        opens: 'left',
        applyClass: 'bg-slate-600',
        cancelClass: 'btn-default',
        showDropdowns: true,
        autoUpdateInput: false,
        locale: {
            format: 'MM/DD/YYYY'
        }
    });

    $('.daterange-basic-datatable, .daterange-basic-datatable-left').on('apply.daterangepicker', function (ev, picker) {
        $(this)
                .val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'))
                .trigger('change');
    });

    $('.daterange-basic-datatable, .daterange-basic-datatable-left').on('cancel.daterangepicker', function (ev, picker) {
        $(this)
                .val('')
                .trigger('change');
    });

    $('.daterange-basic-left').daterangepicker({
        opens: 'left',
        applyClass: 'bg-slate-600',
        cancelClass: 'btn-default',
        showDropdowns: true,
        locale: {
            format: 'YYYY/MM/DD'
        }
    });

    $('.daterange-init-1month').daterangepicker({
        ranges: {
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')],
            'Last 3 Months': [moment().subtract('month', 3).startOf('month'), moment().subtract('month', 1).endOf('month')],
            'Last 6 Months': [moment().subtract('month', 6).startOf('month'), moment().subtract('month', 1).endOf('month')],
            'Last 12 Months': [moment().subtract('month', 12).startOf('month'), moment().subtract('month', 1).endOf('month')],
        },
        opens: 'right',
        applyClass: 'btn-small bg-slate-600',
        cancelClass: 'btn-small btn-default',
        showDropdowns: true,
        locale: {
            format: 'MM/DD/YYYY'
        }
    });

    $('.file-input').fileinput({
        browseLabel: '',
        browseIcon: '<i class="icon-file-plus"></i>',
        removeIcon: '<i class="icon-cross3"></i>',
        showUpload: false,
        removeLabel: "",
        layoutTemplates: {
            icon: '<i class="icon-file-check"></i>'
        },
        initialCaption: "No file selected"
    });

    $('.select').select2({
        minimumResultsForSearch: Infinity,
    });

    $('.select-search').select2();

    $(".styled-checkbox-1").uniform({
        radioClass: 'choice'
    });

    /**
     * This method is used to add a select event on select 2 control
     * it is implemented because if user submit form and jquery validation show error message for control
     * then it will help to hide that validation message as user change its value.
     */
    $(document).find('.select, .select-search').on('select2:select', function () {
        $(this).valid();
    });

    /**
     * This method is used to add a change event on file types of control
     * it is implemented because if user submit form and jquery validation show error message for control
     * then it will help to hide that validation message as user change its value.
     */
    $(document).on('change', 'input[type="file"]', function () {
        $(this).valid();
    });

    $(".styled, .multiselect-container input").uniform({
        radioClass: 'choice'
    });

    $(document).find('[data-toggle="tooltip"]').tooltip();


});

function jqueryValidate() {
    /**
     * Form Validation
     */
    $('form').each(function () {
        $(this).validate({
            errorClass: 'validation-error-label',
            successClass: 'validation-valid-label',
            highlight: function (element, errorClass) {
                $(element).removeClass(errorClass);
            },
            unhighlight: function (element, errorClass) {
                $(element).removeClass(errorClass);
            },

            // Different components require proper error label placement
            errorPlacement: function (error, element) {
                // Styled checkboxes, radios, bootstrap switch
                if (element.parents('div').hasClass("checker") || element.parents('div').hasClass("choice") || element.parent().hasClass('bootstrap-switch-container')) {
                    if (element.parents('label').hasClass('checkbox-inline') || element.parents('label').hasClass('radio-inline')) {
                        error.appendTo(element.parent().parent().parent().parent());
                    } else {
                        error.appendTo(element.parent().parent().parent().parent().parent());
                    }
                }

                // Unstyled checkboxes, radios
                else if (element.parents('div').hasClass('checkbox') || element.parents('div').hasClass('radio')) {
                    error.appendTo(element.parent().parent().parent());
                }

                // Input with icons and Select2
                else if (element.parents('div').hasClass('has-feedback') || element.hasClass('select2-hidden-accessible')) {
                    error.appendTo(element.parent());
                }

                // Inline checkboxes, radios
                else if (element.parents('label').hasClass('checkbox-inline') || element.parents('label').hasClass('radio-inline')) {
                    error.appendTo(element.parent().parent());
                }

                // Input group, styled file input
                else if (element.parent().hasClass('uploader') || element.parents().hasClass('input-group')) {
                    error.appendTo(element.parent().parent());
                } else {
                    if (element.parent().hasClass('intl-tel-input')) {
                        error.appendTo(element.parent().parent());
                    } else {
                        error.insertAfter(element);
                    }
                }
            },
        });
    });
}

/***Confirmation Popup while delete any record ***/
$(document).on("click", "#delete", function (e) {
    var data_path = $(document).find(this).attr('data-path');
    $(document).find('.yes_i_want_delete').removeClass("btn-success");
    $(document).find('.yes_i_want_delete').addClass("btn-danger");
    $(document).find("#deleteConfirm").modal('show');
    $(document).on("click", ".yes_i_want_delete", function (e) {
        var val = $(this).val();
        if (val == 'yes') {
            window.location.href = data_path;
        }
    });
});

function displayServerMsg(wrapperId, msgControlId, msg, doShow, doFocusOnControl) {
    if (typeof doShow === 'undefined') {
        doShow = true;
    }
    if (typeof doFocusOnControl === 'undefined') {
        doFocusOnControl = true;
    }
    $(document).find('#' + msgControlId).html(msg);
    if (doShow) {
        displayElementBlock(wrapperId);
        if (doFocusOnControl) {
            focusToControl('#' + wrapperId);
        }
    } else {
        displayElementNone(wrapperId);
    }
}

function hideServerMsg(wrapperId, msgControlId) {
    displayServerMsg(wrapperId, msgControlId, '', false, false);
}

function displayElementBlock(elementId, doAddDisplayBlock) {
    if (typeof doAddDisplayBlock === undefined) {
        doAddDisplayBlock = false;
    }
    $(document).find('#' + elementId).removeClass('display-none');
    if (doAddDisplayBlock) {
        $(document).find('#' + elementId).addClass('display-block');
    }
}

function displayElementNone(elementId) {
    $(document).find('#' + elementId).addClass('display-none');
    $(document).find('#' + elementId).removeClass('display-block');
}

function focusToControl(divId) {
    var divOffsetTop = $(document).find(divId).offset().top;
    divOffsetTop -= 20;
    $('html, body, document, window').animate({scrollTop: divOffsetTop}, 'slow');
}

function removeJqueryValidationMsgs(formId) {
    $.each($(document).find(formId + ' .validation-error-label'), function () {
        $(this).html('');
    });
}