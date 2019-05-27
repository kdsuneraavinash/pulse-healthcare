'use strict';

let phsrcRegex = /^PHSRC\/[A-Z]+\/[0-9]+$/;
let emailRegex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
let faxRegex = /^\+?[0-9]+$/;
let accountRegex = /^(?=.{8,32}$)(?![_.])(?!.*[_.]{2})[a-zA-Z0-9._]+(?<![_.])$/;
let phoneNumberRegex = /^\d{10}$/;

/// Validate forms in the page
$(document).ready(function () {
    let forms = $('.needs-validation');

    let account = $("#account");
    let password = $("#password");
    let passwordRetype = $("#password_retype");
    let name = $("#name");
    let phsrc = $("#phsrc");
    let email = $("#email");
    let fax = $("#fax");
    let phoneNumber = $("#phone_number");
    let address = $("#address");
    let postal = $("#postal");

    account.on("input", function () {
        validateNotEmptyAndRegex(account, accountRegex);
    });
    password.on("input", function () {
        validateNotEmpty(password);
    });
    passwordRetype.on("input", function () {
        validateNotEmpty(passwordRetype);
        checkField(passwordRetype, passwordRetype.val() === password.val())
    });
    name.on("input", function () {
        validateNotEmpty(name);
    });
    phsrc.on("input", function () {
        validateNotEmptyAndRegex(phsrc, phsrcRegex);
    });
    email.on("input", function () {
        validateNotEmptyAndRegex(email, emailRegex);
    });
    fax.on("input", function () {
        validateEmptyOrRegex(fax, faxRegex);
    });
    phoneNumber.on("input", function () {
        validateNotEmptyAndRegex(phoneNumber, phoneNumberRegex);
    });
    address.on("input", function () {
        validateNotEmpty(address);
    });
    postal.on("input", function () {
        validateNotEmpty(postal);
    });

    Array.prototype.filter.call(forms, function (form) {
        form.addEventListener('submit', function () {
            if (validateNotEmptyAndRegex(account, accountRegex) &&
                validateNotEmpty(password) &&
                validateNotEmpty(passwordRetype) &&
                checkField(passwordRetype, passwordRetype.val() === password.val()) &&
                validateNotEmpty(name) &&
                validateNotEmptyAndRegex(phsrc, phsrcRegex) &&
                validateNotEmptyAndRegex(email, emailRegex) &&
                validateEmptyOrRegex(fax, faxRegex) &&
                validateNotEmptyAndRegex(phoneNumber, phoneNumberRegex) &&
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

function validateEmptyOrRegex(field, regex) {
    return checkField(field, field.val() === "" ||
        regex.test(field.val()));
}
