var ratio = 16 / 9;
!function (elems, header) {
    if (!header)
        return;
    for (var i = 0; i < elems.length; i++) {
        if (!elems[i])
            continue;
        elems[i].style.height = "calc(100vh - " + header.offsetHeight + "px)";
    }
}
(document.querySelectorAll("#video-box,#current_play_video_id, #comments-box"), document.querySelector("#header"));
!function (es, r) {
    for (var i = 0; i < es.length; i++) {
        !function (e) {
            centerBlock(e, r);
            window.onresize = function () {
                //centerBlock(e, r);
            };
        }(es[i]);
    }
}(document.querySelectorAll(".center-block"), ratio);

function centerBlock(e, r) {
    var p = e.parentNode;
    var pw = p.clientWidth;
    
    var ph = p.clientHeight;
    if (pw / ph > r) {
        e.style.height = ph + "px";
        e.style.width = ph * r + "px";
    } else {
        e.style.width = pw + "px";
        e.style.height = pw / r + "px";
    }
     if( $('#embed_video_youtube').length ){
        $("#embed_video_youtube").css('width', e.style.width);
        $("#embed_video_youtube").css('height', e.style.height);    
     }
    
}

!function (e, d) {
    fix();
    window.onresize = fix;
    function fix() {
        var k = 0;
        var p = e.parentNode;
        var gp = e.parentNode.parentNode;
        var eh = e.offsetHeight;
        var dh = d.offsetHeight;
        var interval = setInterval(function () {
            if (!elementInViewport(e)) {
                gp.style.height = "calc(100vh - " + dh + "px - " + eh + "px)";
            } else {
                gp.style.height = "calc(100vh - " + dh + "px)";
            }
            centerBlock(p, ratio);
            if (elementInViewport(e) || k > 20) {
                clearInterval(interval);
            }
            k++;
                    console.log();
        }, 100);
    }
}
(document.querySelector("#video-box .bottom-panel, #video_player_wrapper, #current_play_video_id"), document.querySelector("#header"));

function elementInViewport(e) {
    var top = e.offsetTop;
    var left = e.offsetLeft;
    var width = e.offsetWidth;
    var height = e.offsetHeight;
    while (e.offsetParent) {
        e = e.offsetParent;
        top += e.offsetTop;
        left += e.offsetLeft;
    }

    return (
            top >= window.pageYOffset &&
            left >= window.pageXOffset &&
            (top + height) <= (window.pageYOffset + window.innerHeight) &&
            (left + width) <= (window.pageXOffset + window.innerWidth)
            );
}

var autoHideMenuTimer;
var sidebarIsClose = false;
autoHideMenuTimer = setTimeout(function () {
    $("#left_sidebar").hide(50);
    $("#show_side_bar_btn_hover").show();
    $("#show_side_bar_btn_blur").hide();
    sidebarIsClose = true;
}, 5000);
$(".center-block").on({
    mouseenter: function () {
        if (sidebarIsClose) {
            $("#show_side_bar_btn_blur").hide();
            $("#show_side_bar_btn_hover").show();
        }
    }
});
$(".center-block").on({
    mouseleave: function () {
        if (sidebarIsClose) {
            $("#show_side_bar_btn_blur").show();
            $("#show_side_bar_btn_hover").hide();
        }
    }
});
$(".center-block").on({
    mousemove: function () {
        clearTimeout(autoHideMenuTimer);
        autoHideMenuTimer = setTimeout(function () {
            $("#left_sidebar").hide(50);
            $("#show_side_bar_btn_hover").show();
            $("#show_side_bar_btn_blur").hide();
            sidebarIsClose = true;
        }, 5000);
    }

});
function manualHideSidebar() {
    $("#show_side_bar_btn_blur").hide();
    $("#show_side_bar_btn_hover").show();
    $("#left_sidebar").hide(50);
    sidebarIsClose = true;
}
function manualShowSidebar() {
    $("#show_side_bar_btn_blur").hide();
    $("#show_side_bar_btn_hover").hide();
    $("#left_sidebar").show(50);
    sidebarIsClose = false;
}