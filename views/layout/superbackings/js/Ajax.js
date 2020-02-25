if (ajax == null) {
    ajax = ajax_load;
} else {
    ajax = ajax;
}
var tbody = $("#fbody");
var contar = tbody.children().length;
$(window).scroll(function () {
    if (ajax && ajax_load) {
        if ($(window).scrollTop() >= ($(document).height() - $(window).height()) * 0.9)
        {
            var tbody = $("#fbody");
            var start = tbody.children().length;
            if (!tbody.hasClass("ended")) {
                $.get("http://www.superbackings.com/full-song-list/", {'start': start}, function (res) {
                    if (res != "end") {
                        tbody.append(res);
                    } else {
                        if (!tbody.hasClass("ended")) {
                            alert("No more result to show");
                            tbody.addBack("ended");
                        }
                    }
                });
            }
        }
    }
});

