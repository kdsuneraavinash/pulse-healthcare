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
        let iframe = $("#content-iframe");

        let newLoc = window.location.origin + window.location.pathname + '/' + $(this).attr('id');

        if (iframe.attr("src") !== newLoc) {
            buttons.removeClass("active");
            buttons.removeClass("rounded");
            buttons.removeClass("red");
            buttons.removeClass("white");
            buttons.addClass("white");

            $(this).toggleClass("active");
            $(this).toggleClass("rounded");
            $(this).toggleClass("red");
            $(this).toggleClass("white");

            iframe.fadeOut(200,function(){
                iframe.attr('src', newLoc );
                setTimeout(function() {
                    iframe.fadeIn(200);
                }, 200);
            });
        }
    }
);


$("#content-iframe").on('load', function() {
    console.log("Loaded");
});

// Wallpaper dynamic set
$(document).ready(function () {
    let pattern = Trianglify({
        width: window.innerWidth,
        height: window.innerHeight,
        cell_size: 100,
        x_colors: ['#8467bf', '#0d80c7', '#163fc7']
    });

    let dataUrl = pattern.canvas().toDataURL();

    $(".sidebar-fixed").css("background-image", 'url(' + dataUrl + ')');
});
