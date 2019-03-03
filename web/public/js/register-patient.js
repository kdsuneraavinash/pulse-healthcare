'use strict';

let emailRegex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

/// Validate forms in the page
$(document).ready(function () {
    let forms = $('.needs-validation');

    let name = $("#name");
    let nic = $("#nic");
    let email = $("#email");
    let phoneNumber = $("#phone_number");
    let address = $("#address");
    let postal = $("#postal");

    name.on("input", function () {
        validateNotEmpty(name);
    });
    nic.on("input", function () {
        validateNotEmpty(nic);
    });
    email.on("input", function () {
        validateNotEmptyAndRegex(email, emailRegex);
    });
    phoneNumber.on("input", function () {
        validateNotEmpty(phoneNumber);
    });
    address.on("input", function () {
        validateNotEmpty(address);
    });
    postal.on("input", function () {
        validateNotEmpty(postal);
    });

    Array.prototype.filter.call(forms, function (form) {
        form.addEventListener('submit', function () {
            if (validateNotEmpty(name) &&
                validateNotEmpty(nic) &&
                validateNotEmptyAndRegex(email, emailRegex) &&
                validateNotEmpty(phoneNumber) &&
                validateNotEmpty(address) &&
                validateNotEmpty(postal)) {
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
