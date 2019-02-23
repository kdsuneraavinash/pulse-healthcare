// Animations initialization
AOS.init();


$(window).on('load', function () {
    let loader = $('#loader');
    loader.fadeOut(1000, function () {
        loader.addClass('loaded');
    });

});