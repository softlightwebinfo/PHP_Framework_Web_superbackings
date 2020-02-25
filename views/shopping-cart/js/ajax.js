$(document).on('ready', function () {
    $(document).on('submit', '.carrito-order-extern_checkout form', function (a) {
        a.preventDefault();
        localStorage.removeItem("carrito");
        var code = $("#checkout-paypal input[name='item_number']").val();
        $.ajax({
            url: "https://www.superbackings.com/shopping-cart/PrepareOrder/",
            type: 'POST',
            dataType: 'html',
            data: {
                code: code
            },
            beforeSend: function (xhr) {
                $("body").append("<div id='LoaderCargaCheckout'><div class='textoCarrito'>Your order is being created, please wait while we redirect you to Pay Pal ( if you pitched some tracks It could take some minutes )..</div><div class='loader-fixed loader3'><div class='loader-img'><img src='" + _root_img_ + "paypalLoader.png'></div></div></div>");
                $("#wrapperPage").addClass("BLUR");
            },
            success: function (data, textStatus, jqXHR) {
                document.getElementById("checkout-paypal").submit();
            },
            error: function (xhr, textStatus, thrownError) {
            }
        });
//        return false;
    });
});