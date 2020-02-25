$(document).ready(function () {
    $(".modal-download-order").on("click", function (e) {
        var code = $(this).data("code");
        var order = $(this).data("order");
        var idUser = $(this).data("users");
        $("#order .modal-body").html("");
        $("#footerModalAction").html("");
        $.ajax({
            url: _root_ + "usuarios/panel/ModalAjax/",
            type: 'POST',
            data: {
                code: code,
                order: order,
                idUser: idUser,
            },
            beforeSend: function (xhr) {
                $("#order .modal-title").html("YOUR PURCHASE Nº " + order);
                $("#order .modal-body").html("LOADING...");
            },
            success: function (data, textStatus, jqXHR) {
                $("#order .modal-body").html(data);
            },
            error: function (jqXHR, textStatus, errorThrown) {
            }
        });
    });
    $(".modal-expired-view-order").on("click", function (e) {
        var code = $(this).data("code");
        var order = $(this).data("order");
        $("#order .modal-body").html("");
        $("#footerModalAction").html("");
        $.ajax({
            url: _root_ + "usuarios/panel/ModalAjaxExpired/",
            type: 'POST',
            data: {
                code: code
            },
            beforeSend: function (xhr) {
                $("#order .modal-title").html("YOUR PURCHASE Nº " + order);
                $("#order .modal-body").html("LOADING...");
            },
            success: function (data, textStatus, jqXHR) {
                $("#order .modal-body").html(data);
            },
            error: function (jqXHR, textStatus, errorThrown) {
            }
        });
    });
});


$(".btn-submit-update-ventas").on("click", function (e) {
    var code = $(this).data("code");
//    e.preventDefault();
    $.ajax({
        url: _root_ + "usuarios/panel/buyNowPanel/",
        type: 'POST',
        data: {
            code: code
        }, success: function (data, textStatus, jqXHR) {
        }
    });
});
$(document).on("click", ".btn-submit-reactivate-ventas", function (e) {
    var code = $(this).data("code");
    var precio = $(this).data("precio");
    $.ajax({
        url: _root_ + "usuarios/panel/reactivateBuyNowPanel/",
        type: 'POST',
        data: {
            code: code,
            precio: precio,
        },
        success: function (data, textStatus, jqXHR) {
        }
    });
});
//$(document).on("submit", ".download-agent", function (e) {
//    $data = $(this).data("download");
//    $order = $(this).data("order");
//    $name = $(this).data("name");
//    $.ajax({
//        url: _root_ + "download/",
//        dataType: 'html',
//        type: 'POST',
//        data: {
////            download: $data,
////            order: $order,
////            name: $name
//        }, success: function (data, textStatus, jqXHR) {
//            consoleLog(data);
//        }
//    });
//});

//function ajaxobj() {
//    try {
//        _ajaxobj = new ActiveXObject("Msxml2.XMLHTTP");
//    } catch (e) {
//        try {
//            _ajaxobj = new ActiveXObject("Microsoft.XMLHTTP");
//        } catch (E) {
//            _ajaxobj = false;
//        }
//    }
//    if (!_ajaxobj && typeof XMLHttpRequest != 'undefined') {
//        _ajaxobj = new XMLHttpRequest();
//    }
//    return _ajaxobj;
//}
//function prueba() {
//    ajax = ajaxobj();
//    ajax.open("GET", _root_ + "usuarios/panel/ModalAjax/", true);
//    ajax.onreadystatechange = function () {
//        if (ajax.readyState == 3) {
//// Mostramos porcentaje
//            var res = ajax.responseText;
//            res = res.split('-');
////            alert(res[res.length - 2]);
//            console.log(res[res.length - 2]);
//        } else if (ajax.readyState == 4) {
//// Fin
//            alert('FIN');
//        }
//    }
//// Enviamos algo para que funcione el proceso
//    ajax.send(null);
//} 
//prueba();
// for ($i = 0; $i < 10000000; $i++) {
//            if ($i % 10000 == 0)
//                echo ((int) $i / 100000) . '-';
//            flush();
//        }