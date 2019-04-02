'use strict';

/// Validate forms in the page
$(document).ready(function () {
    let forms = $('.needs-validation');

    let fullName = $("#full_name");
    let slmcId = $("#slmc_id");
    let doctorCategory = $("#doctor_category");

    let resetAll = () =>{
        fullName.removeClass("is-invalid");
        slmcId.removeClass("is-invalid");
        doctorCategory.removeClass("is-invalid");
    };

    fullName.on("input", resetAll);
    slmcId.on("input", resetAll);
    doctorCategory.on("input", resetAll);

    Array.prototype.filter.call(forms, function (form) {
        form.addEventListener('submit', function () {
            if (validateNotEmpty(fullName) ||
                validateNotEmpty(slmcId) ||
                validateNotNull(doctorCategory)) {
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

function validateNotNull(field) {
    return checkField(field, field.val() !== "NONE");
}
