'use strict';

/// Validate forms in the page
$(document).ready(function () {
    let forms = $('.needs-validation');

    let prevPsw = $("#prev");
    let newPsw = $("#new");
    let retypePsw = $("#retype");

    prevPsw.on("input", function () {
        validateNotEmpty(prevPsw);
    });
    newPsw.on("input", function () {
        validateNotEmpty(newPsw);
    });
    retypePsw.on("input", function () {
        validateNotEmpty(retypePsw);
        validateSameAs(retypePsw, newPsw);
    });

    Array.prototype.filter.call(forms, function (form) {
        form.addEventListener('submit', function () {
            if (validateNotEmpty(prevPsw) &&
                validateNotEmpty(newPsw) &&
                validateNotEmpty(retypePsw) &&
                validateSameAs(retypePsw, newPsw)) {
                form.submit();
            }
        }, false);
    });
});

function checkField(field, conditions) {
    field.removeClass("is-valid");
    field.removeClass("is-invalid");
    if (conditions) {
        field.addClass("is-valid");
        return true;
    } else {
        field.addClass("is-invalid");
        event.preventDefault();
        event.stopPropagation();
        return false;
    }
}

function validateNotEmpty(field) {
    return checkField(field, field.val() !== "");
}

function validateSameAs(field1, field2) {
    return checkField(field1, field1.val() === field2.val());
}
