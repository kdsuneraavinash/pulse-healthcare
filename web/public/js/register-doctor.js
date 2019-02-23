'use strict';

/// Validate forms in the page
$(document).ready(function () {
    let forms = $('.needs-validation');

    Array.prototype.filter.call(forms, function (form) {
        form.addEventListener('submit', function (event) {
            let emailRegex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;


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

            let fullName = $("#full_name");
            let displayName = $("#display_name");
            let category = $("#category");
            let slmcId = $("#slmc_id");
            let email = $("#email");
            let phoneNumber = $("#phone_number");
            let nic = $("#nic");

            let fullNameValid = checkField(fullName, fullName.val() !== "");
            let displayNameValid = checkField(displayName, displayName.val() !== "");
            let categoryValid = checkField(category, category.val() !== "");
            let slmcIdValid = checkField(slmcId, slmcId.val() !== "");
            let emailValid = checkField(email, email.val() !== "" &&
                emailRegex.test(email.val()));
            let phoneNumberValid = checkField(phoneNumber, phoneNumber.val() !== "");
            let nicValid = checkField(nic, nic.val() !== "");

            if (fullNameValid && displayNameValid && categoryValid && slmcIdValid && emailValid && phoneNumberValid &&
                nicValid) {
                form.submit();
            }
        }, false);
    });
});

$(function () {
    $('[data-toggle="popover"]').popover()
});