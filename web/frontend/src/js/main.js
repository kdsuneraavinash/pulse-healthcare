function includeFooterAtBottom() {
    var main = $("main");
    var footerH = $("footer").height();
    var navbarH = $("nav").height() + 63;
    var windowH = $(window).height();
    var mainH = main.height();

    if (mainH < windowH - (footerH + navbarH)) {
        main.height(windowH - (footerH + navbarH));
    }else{
        main.height("auto");
    }
}

$(window).resize(includeFooterAtBottom);
$(document).ready(includeFooterAtBottom);