'use strict';

let emailRegex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

/// Validate forms in the page
$(document).ready(function () {
    let forms = $('.needs-validation');

    let fullName = $("#full_name");
    let displayName = $("#display_name");
    let category = $("#category");
    let slmcId = $("#slmc_id");
    let email = $("#email");
    let phoneNumber = $("#phone_number");
    let nic = $("#nic");

    fullName.on("input", function () {
        validateNotEmpty(fullName);
    });
    displayName.on("input", function () {
        validateNotEmpty(displayName);
    });
    category.on("input", function () {
        validateNotEmpty(category);
    });
    slmcId.on("input", function () {
        validateNotEmpty(slmcId);
    });
    email.on("input", function () {
        validateNotEmptyAndRegex(email, emailRegex);
    });
    phoneNumber.on("input", function () {
        validateNotEmpty(phoneNumber);
    });
    nic.on("input", function () {
        validateNotEmpty(nic);
    });

    Array.prototype.filter.call(forms, function (form) {
        form.addEventListener('submit', function () {
            if (validateNotEmpty(fullName) &&
                validateNotEmpty(displayName) &&
                validateNotEmpty(category) &&
                validateNotEmpty(slmcId) &&
                validateNotEmpty(email, emailRegex) &&
                validateNotEmpty(phoneNumber) &&
                validateNotEmpty(nic)) {
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

function validateNotEmptyAndRegex(field, regex) {
    return checkField(field, field.val() !== "" &&
        regex.test(field.val()));
}

$(function () {
    $('[data-toggle="popover"]').popover()
});
