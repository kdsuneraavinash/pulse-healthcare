$("#drawer-open").click(function (e) {
    e.preventDefault();
    $(".sidebar-fixed").animate({'marginLeft': '0'}, 200);
});


$("#drawer-close").click(function (e) {
    e.preventDefault();
    $(".sidebar-fixed").animate({'marginLeft': '-270px'}, 200);
});

$(".sidebar-button").click(
    function (e) {
        let buttons = $(".sidebar-button");
        let iframe = $("#content-iframe");

        let newLoc = window.location.origin + window.location.pathname + '/' + $(this).attr('id');

        if (iframe.attr("src") !== newLoc) {
            buttons.removeClass("active");
            buttons.removeClass("rounded");
            buttons.removeClass("mb-0");
            buttons.removeClass("z-depth-2");
            buttons.removeClass("blue");
            buttons.addClass("bg-light");

            $(this).toggleClass("active");
            $(this).toggleClass("rounded");
            $(this).toggleClass("mb-0");
            $(this).toggleClass("z-depth-2");
            $(this).toggleClass("blue");
            $(this).removeClass("bg-light");

            iframe.fadeOut(200,function(){
                iframe.attr('src', newLoc );
                setTimeout(function() {
                    iframe.fadeIn(200);
                }, 200);
            });
        }
    }
);


$("#content-iframe").load(function() {
    console.log("Loaded");
});