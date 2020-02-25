//$(function () {
//    $('.publicidad .publicidad-column .publicidad-item:first-child').show();
//    setInterval(function () {
//        $('.publicidad .publicidad-column .publicidad-item:first-child').fadeOut(0)
//                .next('div').fadeIn(1000)
//                .end().appendTo('.publicidad');
//    }, 4000);
//});
//(function ($) {
//    $.fn.slider = function () {
//        var count = this.find('.publicidad-item').length;
//        contar = 0;
//
//    };
//})(jQuery);
$(document).ready(function () {
//    function slider($id, time) {
//        //rotation speed and timer
//        var speed = 5000;
//        //grab the width and calculate left value
//        var item_width = $($id + ' .publicidad-item').outerWidth();
//        var left_value = item_width * (-1);
//
//        //move the last item before first item, just in case user click prev button
//        setInterval(function () {
//            $($id + ' .publicidad-item').removeClass("publicidad-active");
//            $($id + ' .publicidad-item:last').after($($id + ' .publicidad-item:first').addClass("publicidad-active"));
//        }, time);
//    }
    var publicidad1 = setInterval(function () {
        $('#publicidad-panel-1 .publicidad-item').removeClass("publicidad-active");
        $('#publicidad-panel-1 .publicidad-item:last').after($('#publicidad-panel-1 .publicidad-item:first').addClass("publicidad-active"));
    }, 4000);
    var publicidad2 = setInterval(function () {
        $('#publicidad-panel-2 .publicidad-item').removeClass("publicidad-active");
        $('#publicidad-panel-2 .publicidad-item:last').after($('#publicidad-panel-2 .publicidad-item:first').addClass("publicidad-active"));
    }, 3000);
    var publicidad3 = setInterval(function () {
        $('#publicidad-panel-3 .publicidad-item').removeClass("publicidad-active");
        $('#publicidad-panel-3 .publicidad-item:last').after($('#publicidad-panel-3 .publicidad-item:first').addClass("publicidad-active"));
    }, 5000);

//    $('#publicidad-panel-1').hover(
//            function () {
//                clearInterval(publicidad1);
//            },
//            function () {
//                publicidad2 = setInterval(function () {
//                    $('#publicidad-panel-1 .publicidad-item').removeClass("publicidad-active");
//                    $('#publicidad-panel-1 .publicidad-item:last').after($('#publicidad-panel-1 .publicidad-item:first').addClass("publicidad-active"));
//                }, 4000);
//            }
//    );
//    $('#publicidad-panel-2').hover(
//            function () {
//                clearInterval(publicidad2);
//            },
//            function () {
//                publicidad2 = setInterval(function () {
//                    $('#publicidad-panel-2 .publicidad-item').removeClass("publicidad-active");
//                    $('#publicidad-panel-2 .publicidad-item:last').after($('#publicidad-panel-2 .publicidad-item:first').addClass("publicidad-active"));
//                }, 3000);
//            }
//    );
//    $('#publicidad-panel-3').hover(
//            function () {
//                clearInterval(publicidad3);
//            },
//            function () {
//                publicidad3 = setInterval(function () {
//                    $('#publicidad-panel-3 .publicidad-item').removeClass("publicidad-active");
//                    $('#publicidad-panel-3 .publicidad-item:last').after($('#publicidad-panel-3 .publicidad-item:first').addClass("publicidad-active"));
//                }, 5000);
//            }
//    );
//    slider("#publicidad
//    -panel-1", 3000);
//    slider("#publicidad-panel-2", 2000);
//    slider("#publicidad-panel-3", 4000);

});