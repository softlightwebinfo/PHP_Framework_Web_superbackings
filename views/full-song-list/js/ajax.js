$(document).ready(function (e) {

    var Scrolls = function (e) {
        if ($(window).scrollTop() >= ($(document).height() - $(window).height()) * 0.9)
        {
            var tbody = $("#fbody");
            var start = tbody.children().length;
            console.log(start);
            if (!tbody.hasClass("ended")) {
                $("#loaderAjax").html("<span>Loading...<i class='fa fa-spinner fa-pulse'></i></span>");
                $.get("http://www.superbackings.com/full-song-list/getAjax", {'start': start}, function (res) {
                    if (res != "end") {
                        tbody.append(res);
                        $("#loaderAjax").html("");
                    } else {
                        if (!tbody.hasClass("ended")) {
                            alert("No more result to show");
                            tbody.addBack("ended");
                        }
                    }
                });
            }
        }
    };
    $(window).scroll(function () {
        Scrolls();
    });
});
