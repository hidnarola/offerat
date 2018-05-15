$(document).find('[data-toggle="tooltip"]').tooltip();

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
