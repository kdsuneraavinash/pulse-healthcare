$("#drawer-open").click(function (e) {
    e.preventDefault();
    $(".sidebar-fixed").animate({'marginLeft': '0'}, 200);
});


$("#drawer-close").click(function (e) {
    e.preventDefault();
    $(".sidebar-fixed").animate({'marginLeft': '-270px'}, 200);
});

$(".sidebar-button").click(
    function () {
        let buttons = $(".sidebar-button");
        buttons.removeClass("active");
        buttons.removeClass("rounded");
        buttons.removeClass("mb-0");
        buttons.removeClass("z-depth-2");

        $(this).toggleClass("active");
        $(this).toggleClass("rounded");
        $(this).toggleClass("mb-0");
        $(this).toggleClass("z-depth-2");

        $("#content-iframe").attr('src', window.location.origin + window.location.pathname + '/' + $(this).attr('id'));

        console.log();
    }
);