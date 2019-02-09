function includeFooterAtBottom() {
    var main = $("main");
    var footerH = $("footer").height();
    var headerH = $("header").height();
    var additionalH = 40;
    var windowH = $(window).height();
    var mainH = main.height();

    var requiredH = windowH - (footerH + headerH + additionalH);

    console.log(windowH)

    if (mainH < requiredH) {
        main.height(requiredH);
    }
}

$(document).ready(includeFooterAtBottom);