'use strict';

/// Validate forms in the page
(function () {
    window.addEventListener('load', function () {
        var forms = $('.needs-validation');
        Array.prototype.filter.call(forms, function (form) {
            form.addEventListener('submit', function (event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                } else {
                    form.submit();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();
