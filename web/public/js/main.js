// Animations initialization
AOS.init();

// // Scroll Speed
// if (window.addEventListener) window.addEventListener('DOMMouseScroll', wheel, false);
// window.onmousewheel = document.onmousewheel = wheel;
//
// function wheel(event) {
//     var delta = 0;
//     if (event.wheelDelta) delta = event.wheelDelta / 120;
//     else if (event.detail) delta = -event.detail / 3;
//
//     handle(delta);
//     if (event.preventDefault) event.preventDefault();
//     event.returnValue = false;
// }
//
// function handle(delta) {
//     var time = 1000;
//     var distance = 300;
//
//     $('html, body').stop().animate({
//         scrollTop: $(window).scrollTop() - (distance * delta)
//     }, time );
// }


$(window).scroll(function() {
    if ($(document).scrollTop() > 100) {
        $('.navbar').addClass('color-change');
    } else {
        $('.navbar').removeClass('color-change');
    }
});

// Tool tips
$(function () {
    $('[data-toggle="popover"]').popover()
});

// Loader Animation
$(window).on('load', function () {
    let loader = $('#loader');
    loader.fadeOut(1000, function () {
        loader.addClass('loaded');
    });

});

// POST with js
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