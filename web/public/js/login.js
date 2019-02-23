'use strict';

/// Validate forms in the page
$(document).ready(function () {
    let forms = $('.needs-validation');

    let account = $("#account");
    let password = $("#password");

    account.on("input", function () {
        validateNotEmpty(account);
    });
    password.on("input", function () {
        validateNotEmpty(password);
    });

    Array.prototype.filter.call(forms, function (form) {
        form.addEventListener('submit', function () {
            if (validateNotEmpty(account) &&
                validateNotEmpty(password)) {
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
