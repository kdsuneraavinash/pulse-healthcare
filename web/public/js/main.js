// Animations initialization
AOS.init();

$(function () {
    $('[data-toggle="popover"]').popover()
});

$(window).on('load', function () {
    let loader = $('#loader');
    loader.fadeOut(1000, function () {
        loader.addClass('loaded');
    });

});

function post(path, params, method) {
    method = method || "post";

    let form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", path);

    for (let key in params) {
        if (params.hasOwnProperty(key)) {
            let hiddenField = document.createElement("input");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", key);
            hiddenField.setAttribute("value", params[key]);
            form.appendChild(hiddenField);
        }
    }

    document.body.appendChild(form);
    form.submit();
}