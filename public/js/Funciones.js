$(function () {
    $('[data-toggle="tooltip"]').tooltip();
    ajaxCarrito();
});
$(document).ready(function () {
    $(".close").on("click", close);

    $("#search input").on("keypress", keyEnterLocationSearch);
    $("#search button").on("click", function () {
        redireccionar("#search input", null);
    });
    $('a[href^="#"]').click(function () {
        var target = $(this.hash);
        if (target.length == 0)
            target = $('a[name="' + this.hash.substr(1) + '"]');
        if (target.length == 0)
            target = $('html');
        $('html, body').animate({scrollTop: target.offset().top}, 500);
        return false;
    });
    var goTop = $('#goTop');
    $(window).scroll(function () {
        if ($(this).scrollTop() > 250) {
            goTop.fadeIn(500);
        } else {
            goTop.fadeOut(800);
        }
    });
    var cancion = "";
    var artist = "";
    column = "";
    row = "";
    color = "";
    tipes = "";
    tipo_select = "";
    /**
     * Seleccionar Version tipo
     */
    $(document).on("change", "#fbody .selectTipo", function () {
        var valor = $(this).val();
        var traks = valor.split('.mp3');
        row = $(this).parents("tr").index();
        tipo_select = $("option:selected", this).text();
        $(this).parents("tr").find("td:eq(0) i").attr("data-track", traks[0]);
        play(valor, "#fbody tr:eq(" + row + ") td:eq(0) i", true);
        CambiarMp3Play(valor, "#fbody tr:eq(" + row + ") td:eq(0) i");
    });
    /**
     * Flecha Pitch
     * 
     * */
    $num = 0;
//    $(document).on("click", "#fbody tr td .desplegar", function (e) {
//        $this = $(this);
//        row = $(this).parents("tr").index();
//        if ($this.hasClass("desplegar-open")) {
//            $(this).attr("class", "desplegar desplegar-close fa fa-caret-right fa-2x");
//            $(".desplegar").removeClass("desplegar-open").addClass("desplegar-close");
//            $(".desplegableTipos").hide();
//            $(".loader-status").hide();
//            $(".loader").hide();
//            $(".pitch-info").hide();
//            $("tr").removeClass("TipoCss");
//        } else {
//            $(".desplegableTipos").hide();
//            $(".pitch-info").hide();
//            $(".loader-status").hide();
//            $(".loader").hide();
//            $("#fbody tr:eq(" + row + ") td:eq(3) .pitch-info").show();
//            $("tr").removeClass("TipoCss");
//            $("#fbody tr td .desplegar").attr("class", "desplegar desplegar-close fa fa-caret-right fa-2x");
//            $(this).attr("class", "desplegar desplegar-open fa fa-caret-down fa-2x");
//            $(this).parents("tr").addClass("TipoCss");
//            $("#fbody tr:eq(" + row + ") td:eq(4) .desplegableTipos").slideDown();
//        }
//    });
    /**
     * Sumar y restar pitch
     */
    $(document).on("click", ".desplegableTipos .pitch .pitch-minus", function () {
        pitch_controls("minus", this);
    });
    $(document).on("click", ".desplegableTipos .pitch .pitch-plus", function () {
        pitch_controls("plus", this);
    });
    if ($("#carrito-resultado").length) {
//        GetCarritoResultado();
    }
});
function getTipo(e) {
    var data = $(e).text();
    return data;
}
/*Carrito*/
/*BTN CARRITO*/
//function ajaxMakeid() {
//$.ajax
//}
function addCart(MP3Demo, MP3Demo_pichado, tipo, precio, pich) {
    if (localStorage.getItem("carrito") == null) {
        var carrito = {
            'cart': [],
            'state': makeid(40)
        };
        carrito.cart.push(
                {
                    'MP3Demo': MP3Demo,
                    'MP3Demo_pichado': MP3Demo_pichado,
                    'pitch': pich,
                    'tipo': tipo,
                    'precio': precio
                }
        );
    } else {
        carrito = JSON.parse(localStorage.getItem('carrito'));
        carrito.cart.push(
                {
                    'MP3Demo': MP3Demo,
                    'MP3Demo_pichado': MP3Demo_pichado,
                    'pitch': pich,
                    'tipo': tipo,
                    'precio': precio
                }
        );
//        localStorage.setItem('carrito', JSON.stringify(carrito));
    }

// Converting the JSON string with JSON.stringify()
// then saving with localStorage in the name of session
    localStorage.setItem('carrito', JSON.stringify(carrito));
// Example of how to transform the String generated through 
// JSON.stringify() and saved in localStorage in JSON object again
    var restoredSession = JSON.parse(localStorage.getItem('carrito'));
// Now restoredSession variable contains the object that was saved
// in localStorage
//    console.log(restoredSession);
}
$(document).on("click", ".buybtn", function () {
    var $this = $(this);
    /*SI CONTIENE LA CLASE CART BLOQUED NO AÑADIRA MAS AL SHOPPING_CART*/
    if ($this.hasClass("cart-blocked")) {
        MensajeError("You already have this item in your cart", null, 4000);
    } else {
        var div = $(this);
        /*Posicion del carrito basketc */
        var carrito_posicion = $("#basketc").offset();
        /*Posicion del DIV tr el carrito*/
        var divs = $(this).closest('tr');
        var posicion = divs.position();
        var width = divs.css("width");
        var td1 = divs.find("td:eq(0)").css("width");
        var td2 = divs.find("td:eq(1)").css("width");
        var td3 = divs.find("td:eq(2)").css("width");
        var td4 = divs.find("td:eq(3)").css("width");
        var td5 = divs.find("td:eq(4)").css("width");
        var td6 = divs.find("td:eq(5)").css("width");
        /*Clonacion del tr del carrito*/
        var clone = divs.clone().addClass("clone");
        /*Añadimos estilos a la clonacion del tr clonado*/
        clone.css({
            "position": "absolute",
            "left": posicion.left,
            "top": posicion.top,
            "width": width
        });
        clone.find("td:eq(0)").css("width", td1);
        clone.find("td:eq(1)").css("width", td2);
        clone.find("td:eq(2)").css("width", td3);
        clone.find("td:eq(3)").css("width", td4);
        clone.find("td:eq(4)").css("width", td5);
        clone.find("td:eq(5)").css("width", td6);
        /*Insertamos despues del tr afectado*/
        divs.append(clone);
        $(".clone").animate({
            "top": carrito_posicion.top,
            "left": carrito_posicion.left,
            opacity: .5,
        }, 1000).animate({
            "opacity": 0,
        }, function () {
            $(this).remove();
        });
        var id = $this.data("carrito");
        /*Recogida de datos del carrito para Localstorage*/
        idOriginal = $this.data("carritoid");
        MP3Demo = $(this).parents("tr").find("td:eq(0) i").attr("data-track") + ".mp3";
        MP3Demo_pichado = $(this).parents("tr").find("td:eq(0) i").attr("data-pitch");
        if (MP3Demo_pichado == "undefined.mp3") {
            MP3Demo_pichado = false;
        }
        tipo = getTipo($(this).parents("tr").find("td:eq(3) select option:selected"));
        precio = $(this).parents("tr").find("td:eq(4) .buybtn").data("precio");
        pitch = $(this).parents("tr").find("td:eq(4) .pitch-result").html();
        /*Cambiar forma del Boton al ser enviado al Card*/
        var pathname = window.location.pathname;
        if (pathname == "/index.php" || pathname == "/index.php/") {
            $(this).addClass("addCart").html("ADDED");
        } else {
            $(this).addClass("addCart").html("Added to cart");
            $(this).parents("tr").find("td:eq(4) a").attr("style", "padding:0");

        }
        /*Bloquear Carrito cuando esta añadido*/
        $(this).addClass("cart-blocked");
        $(this).parents("tr").addClass("active-added");
        $(this).parents("tr").find("td:eq(3)").html("");
        $(this).parents("tr").find("td:eq(5)").html("");


//
//    alert(MP3Demo + " | " + MP3Demo_pichado + " | " + tipo + " | " + precio);
        /*Enviar a la funcion los datos del carrito*/
        addCart(MP3Demo, MP3Demo_pichado, tipo, precio, pitch);
        AjaxCarrito(getCarrito(), "html", function (data) {
            data = JSON.parse(data);
            MensajeError("ADDED TO CART", null, 4000, "alert-info");
            $("#carrito .items_count a b").html(data.count + " items");
            $("#carrito #totalCarrito").html(data.precio);
            if ($("#masDatos").length) {
                $(".precio_set").html("0.49&euro;");
                $(".precio_set").parents(".buybtn").attr("data-precio_set", 0.49);
            }
        });
    }
});
//                    localStorage.removeItem("cart","");
/*AJAX CARRITO*/

var AjaxCarrito = function (datos, type, successs) {
    $.ajax({
        url: _root_ + "shopping-cart/carritoajax/",
        type: 'POST',
        data: {
            datos: datos
        },
        dataType: type,
        beforeSend: function (xhr) {

        },
        success: function (data, textStatus, jqXHR) {
            successs(data);
        }, error: function (jqXHR, textStatus, errorThrown) {
        }
    });
};
ajaxCarrito();
/**
 * 
 * @returns {DOMString|String}
 */
function GetCarritoHTML(ajaxSuccess) {
    $.ajax({
        url: _root_ + "shopping-cart/CarritoHTML/",
        type: 'POST',
        data: {
            carrito: localStorage.getItem("carrito")
        },
        success: function (data, textStatus, jqXHR) {
            ajaxSuccess(data);
        }
    });
}
function ajaxCarrito() {
    AjaxCarrito(getCarrito(), "html", function (data) {
        data = JSON.parse(data);
        $("#carrito .items_count a b").html(data.count + " items");
        $("#carrito #totalCarrito").html(data.precio);
    });
}
function getCarrito() {
    if (localStorage.getItem("carrito") === undefined || localStorage.getItem("carrito") === null || localStorage.getItem("carrito") === "")
    {
        return "vacio";
    } else {
        return localStorage.getItem("carrito");
    }
}
function getCarritoJson() {
    carrito = JSON.parse(localStorage.getItem('carrito'));
    return carrito;
}
/*ID SESSION SIN LOGIN*/
/**
 * Session ID Crea una ID para cada usuario en su navegador
 * @returns {undefined}
 */
function sessionID() {
    if (localStorage.getItem("id") === undefined || localStorage.getItem("id") === null)
    {
        var id = makeid(35);
        localStorage.setItem("id", id);
    } else {
        localStorage.getItem("id");
    }
}
//sessionID();
function makeid(num)
{
    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    for (var i = 0; i < num; i++)
        text += possible.charAt(Math.floor(Math.random() * possible.length));
    return text;
}
/**
 * Comprobacion track
 * @param {type} $pitch
 * @param {type} $name
 * @param {type} $name_pitch
 * @returns {undefined}
 */
function ComprobacionTrack($pitch, $name, $name_pitch, $play) {
    if ($play === null || $play === undefined) {
        $play = true;
    } else {
        $play = false;
    }
    $.ajax({
        url: 'pitch_1.php',
        data: {
            pitch: $pitch,
            name: $name,
            name_pitch: $name_pitch
        },
        type: 'GET',
        beforeSend: function (xhr) {
        },
        success: function (data, textStatus, jqXHR) {
//            console.log("Compr: " + data);
            if (data == "existe" || data == 0) {
                if ($play) {
                    play($name_pitch, "#fbody tr:eq(" + row + ") td:eq(0) i", true);
                }
                CambiarMp3Play($name_pitch, "#fbody tr:eq(" + row + ") td:eq(0) i");
            } else {
                pause("#fbody tr:eq(" + row + ") td:eq(0) i");
                Pitch($pitch, $name, $name_pitch, $play);
            }
        }
    });
}

function Pitch($pitch, $name, $name_pitch, $play) {
    if ($play === null || $play === undefined) {
        $play = true;
    } else {
        $play = false;
    }
    $.ajax({
        url: 'pitch.php',
        data: {
            pitch: $pitch,
            name: $name,
            name_pitch: $name_pitch
        },
        type: 'GET',
        beforeSend: function (xhr) {
            $("#fbody tr:eq(" + row + ") td:eq(1) .loader").show().html('<div class="loader-text">Wait while pitching<div class="loadings">' +
                    '<div class="loadings-loader">' +
                    '   <div class="inTurnFadingTextG inTurnFadingTextG_1">.</div>' +
                    '   <div class="inTurnFadingTextG inTurnFadingTextG_2"> </div>' +
                    '   <div class="inTurnFadingTextG inTurnFadingTextG_3">.</div>' +
                    '   <div class="inTurnFadingTextG inTurnFadingTextG_4"> </div>' +
                    '   <div class="inTurnFadingTextG inTurnFadingTextG_5">.</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>'
                    );
            $("#fbody tr:eq(" + row + ") td:eq(2) .loader-status").show().html("<img class='loader3' style='width: 26px;' src='" + _root_img_ + "loader3.png'>");
        },
        success: function (data, textStatus, jqXHR) {
//            console.log("PITCH: " + data);
            if (textStatus == "success") {
                $("#fbody tr:eq(" + row + ") td:eq(1) .loader").html("");
                $("#fbody tr:eq(" + row + ") td:eq(2) .loader-status").html("");
                if ($play) {
                    play($name_pitch, "#fbody tr:eq(" + row + ") td:eq(0) i", true);
                }
                CambiarMp3Play($name_pitch, "#fbody tr:eq(" + row + ") td:eq(0) i");
            } else {
            }
        }
    });
}
function AjaxProceso() {

}

function pitch_controls(controls, e) {
    var pitch = $("#fbody tr:eq(" + row + ") td:eq(4) .desplegableTipos .pitch .pitch-result");
    var rows = $("#fbody tr:eq(" + row + ") td:eq(0) i").attr("data-track");
    var id = rows;
    if (pitch.html() >= 4) {
        var mayor = true;
    } else {
        if (pitch.html() <= -4) {
            var menor = true;
        }
    }

    if (controls == "plus") {
        if (mayor) {
            return;
        } else {
            var resultado = Number(pitch.html()) + 1;
            if (resultado == 0) {
                $id = id + ".mp3";
            } else {
                if (resultado > 0) {
                    $id = id + '_' + resultado + ".mp3";
                } else {
                    $id = id + "" + resultado + ".mp3";
                }
            }
        }
    } else {
        if (menor) {
            return;
        } else {
            var resultado = (pitch.html()) - 1;
            if (resultado == 0) {
                $id = id + ".mp3";
            } else {
                if (resultado > 0) {
                    $id = id + '_' + resultado + ".mp3";
                } else {
                    $id = id + "" + resultado + ".mp3";
                }
            }
        }
    }
    var pichado = $("#fbody tr:eq(" + row + ") td:eq(0) i").attr("data-pitch", $id);
    Number(pitch.html(resultado));
    ComprobacionTrack(resultado, id, $id);
}
function GetTypo(type) {
    var img = "";
    switch (type) {
        case "tipos_full":
            img = "FV";
            color = "#FFFFFF";
            break;
        case "tipos_vocals":
            img = "W B/V";
            color = "#000000";
            break;
        case "tipos_guitar":
            img = "WG";
            color = "orange";
            break;
        case "tipos_bass":
            img = "WB";
            color = "red";
            break;
    }
    return img;
}
function GetColorPlay() {
    var datos = $("#fbody tr:eq(" + row + ") td:eq(0)").css("color", color);
}
function GetTiposHover(cancion, artista, tipo)
{
//    alert(cancion + " | " + artista + " | " + type);
    var datos = $("#fbody tr:eq(" + row + ") td:eq(3)").html(GetTypo(tipo));
    $.ajax({
        url: "index/GetTipo",
        type: 'GET',
        dataType: 'json',
        data: {
            cancion: cancion,
            artista: artista,
            tipo: type,
        }, success: function (data, textStatus, jqXHR) {
            if (data.count == 0) {
                return;
            }
            play(data.tipo, "#fbody tr:eq(" + row + ") td:eq(0) i", true);
            CambiarMp3Play(data.tipo, "#fbody tr:eq(" + row + ") td:eq(0) i");
        }
    });
}
function CambiarMp3Play(cancion, ruta) {
    $(ruta).attr("onclick", "play('" + cancion + "',this);");
}
function CambiarMp3PlayTrack(ruta, cancion) {
    $(ruta).data("track", cancion);
}
function ComprovarTipo(cancion, artista) {
    $.ajax({
        url: "index/CountTipo",
        type: 'GET',
        data: {
            cancion: cancion,
            artista: artista,
            typo: tipes
        },
        beforeSend: function (xhr) {
//            $(".desplegableTipos").addClass("center").html('<img src="' + _rootImg_ + 'loader1.gif">');
        },
        success: function (data, textStatus, jqXHR) {
            $(".desplegableTipos").removeClass("center").html(data).slideDown();
        }
    });
}

/*PLAY*/
function play(cancion, e, igual) {
    if (igual == undefined) {
//    $(e).parents("tr").find("td:nth-child(0)").html("");
        if ($(e).hasClass('play fa fa-pause fa-2x')) {
            if ($(e).data("playcarrito") == true) {
                $(e).attr("class", "play fa fa-play-circle-o fa-2x");
            } else {
                $(e).attr("class", "play fa fa-play-circle fa-2x");
            }
            $('#player').attr("src", "");
            $("#fbody tr").removeClass("activePlay");
            /*CARRITO PLAY*/
            $("#carrito-results .carrito-fila").removeClass("activePlayCarrito");
            /*PLAY PAUSA*/
            $("#player")[0].pause();
        } else {
            /*PLAY*/
            $("#fbody tr").removeClass("activePlay");
            $("#fbody .play").attr("class", "play fa fa-play-circle fa-2x");
            /*CARRITO PLAY*/
            $("#carrito-results .carrito-fila").removeClass("activePlayCarrito");
            /*Carrito play*/
            $("#carrito-results .play").attr("class", "play fa fa-play-circle-o fa-2x");
            /*PLAY*/
            if ($(e).data("playcarrito") == true) {
                $(e).attr("class", "play fa fa-pause fa-2x");
            } else {
                $(e).attr("class", "play fa fa-pause fa-2x");
            }
            var ruta = _root_ + "demos/" + cancion;
            $('#player').attr("src", ruta);
            $(e).parents("tr").addClass("activePlay");
            /*Carrito PLAY*/
            $(e).parents(".carrito-fila").addClass("activePlayCarrito");
            /*Play*/
            $("#player")[0].play();
        }
    } else {
        $("#fbody .play").attr("class", "play fa fa-play-circle fa-2x");
        $("#fbody tr").removeClass("activePlay");
        $(e).attr("class", "play fa fa-pause fa-2x");
        var ruta = _root_ + "demos/" + cancion;
        $('#player').attr("src", ruta);
        $(e).parents("tr").addClass("activePlay");
        $("#player")[0].play();
    }
}
function pause(e) {
    $(e).attr("class", "play fa fa-play-circle fa-2x");
    $('#player').attr("src", "");
    $("#fbody tr").removeClass("activePlay");
    $("#player")[0].pause();
}
function close()
{
    $this = $(this);
    $parent = $this.parents(".parent-close");
    $parent.hide();
}


function keyEnterLocationSearch() {
    if (event.keyCode == 13) {
        var padre = $(this).val();
        padre = padre.toLowerCase().split(" ").join("-");
        if (!$(this).val() == 0) {
            window.location = _root_ + "search/query/" + padre + "/";
        }

    }
}
function redireccionar(id) {
    if (id == undefined || id == null) {
        padre = "";
    } else {
        padre = $(id).val();
    }
    padre = padre.toLowerCase().split(" ").join("-");
    window.location = _root_ + "search/query/" + padre + "/";
}
/*BUSCADOR*/
var search_count = -1;
$("#search input").on("keyup", function () {

    var search = $(this);
    var search_val = $(this).val();
    var tecla = (document.all) ? event.keyCode : event.which;
    var valor = $(this).val();
    var lista = $("#results_search ul");
    var listas = $("#results_search").fadeIn();
    var lista_count = lista.find("li").length;
    if (tecla == 40) {
        if (search_count >= lista_count - 1) {
            $("#results_search li:eq(" + lista_count + ")").addClass("activeSearch");
            return;
        }
        search_count++;
        $("#results_search li").removeClass("activeSearch");
        $("#results_search li:eq(" + search_count + ")").addClass("activeSearch");
        search.val($(".activeSearch .search-list-top a").data("search_track"));
    } else if (tecla == 38) {
        if (search_count <= 0) {
            $("#results_search li:eq(0)").addClass("activeSearch");
            return;
        }
        search_count--;
        $("#results_search li").removeClass("activeSearch");
        $("#results_search li:eq(" + search_count + ")").addClass("activeSearch");
        search.val($(".activeSearch .search-list-top a").data("search_track"));
    } else {
        if (search_val == "" || search_val == 0) {
            $("#results_search").html("");
        } else {
            $.ajax({
                url: _root_ + "search/getquery",
                type: 'GET',
                data: {
                    valor: search_val
                },
                beforeSend: function (xhr) {

                },
                success: function (data, textStatus, jqXHR) {
                    $("#results_search").html(data);
                    search_count = -1;
                },
                error: function (jqXHR, textStatus, errorThrown) {
//                                    alert("Error");
                }
            });
        }
    }
});
$(document).keyup(function (e) {
    if (e.keyCode == 27) { // escape key maps to keycode `27`
// <DO YOUR WORK HERE
        $("#search input").val("");
        $("#results_search").html("").hide();
    }
});
var MensajeError = function (mensaje, style, time, type) {

    //si no se envia la variable time, esta se define por defecto en 6 segundos
    if (time === undefined || time === null)
        time = 6000;
    if (style === undefined || time === null)
        style = null;
    if (type === undefined || time === null) {
        type = 'alert-danger';
    }
    //agregamos el div a nuestra pagina con la clase css Error anteriormente hecha
    $("body").append('<div class="alert ' + type + ' Error" role="alert" style="' + style + '"></div>');
    //asignamos el texto del error al div que creamos
    $(".Error").html(mensaje);
    //aqui procedemos a crear la animación para que el div se muestre y se oculte despues de cierto tiempo
    $(".Error")
            .animate({//seleccionamos el div
                opacity: 1 //aparece
            }, 500  //la animación se realiza medio segundo
                    , function () { //ejecutamos un callback con función anonima para desaparecer la notificación despues de 6 segundos
                        $(".Error").animate({//seleccionamos nuevamente el div
                            opacity: 0 //ocultamos

                        }, time   //tiempo de 6 segundos por defecto
                                , function () {
                                    //al final en el callback del ultimo evento eliminamos el div de la pagina.
                                    $(this).remove();
                                }
                        );
                    });
};
/*
 * Function SHOPPING-CART
 */
$num = 0;
$(document).on("click", ".desplegar-shop", function (e) {
    $this = $(this);
    row = $(this).parents(".carrito-fila").index();
    if ($this.hasClass("desplegar-open")) {
        $(".desplegar-shop").attr("class", "desplegar-shop desplegar-close fa fa-caret-right fa-2x");
        $(".botton-offer").attr("class", "botton-offer botton-offer-close");
        $(".desplegar-shop").removeClass("desplegar-open").addClass("desplegar-close");
        $(".desplegablesTipos").slideUp();
        $(".carrito-fila").removeClass("Fila-Carrito");
        $(".botton-offer span").html("SEE OFFERS");
    } else {
        $(".desplegablesTipos").slideUp();
        $(".carrito-fila").removeClass("Fila-Carrito");
        $(".botton-offer span").html("SEE OFFERS");
        $(".desplegar-shop").attr("class", "desplegar-shop desplegar-close fa fa-caret-right fa-2x");
        $(this).attr("class", "desplegar-shop desplegar-open fa fa-caret-down fa-2x");
        $(this).parents(".carrito-fila").addClass("Fila-Carrito");
        $(this).parents(".carrito-fila").find(".carrito-columna:eq(4) a").attr("class", "botton-offer botton-offer-open");
        $(this).parents(".carrito-fila").find(".carrito-columna:eq(4) a span").html("HIDE OFFERS");
        $("#carrito-results .left .carrito-fila:eq(" + row + ") .desplegablesTipos").slideDown();
    }
});
$(document).on("click", ".botton-offer", function (e) {
    $this = $(this);
    row = $(this).parents(".carrito-fila").index();
    if ($this.hasClass("botton-offer-open")) {
        $(".desplegar-shop").attr("class", "desplegar-shop desplegar-close fa fa-caret-right fa-2x");
        $(".botton-offer").attr("class", "botton-offer botton-offer-close");
        $(".desplegar-shop").removeClass("desplegar-open").addClass("desplegar-close");
        $(".desplegablesTipos").slideUp();
        $(".carrito-fila").removeClass("Fila-Carrito");
        $(".botton-offer span").html("SEE OFFERS");
    } else {
        $(".desplegablesTipos").slideUp();
        $(".carrito-fila").removeClass("Fila-Carrito");
        $(".botton-offer span").html("SEE OFFERS");
        $(".desplegar-shop").attr("class", "desplegar-shop desplegar-close fa fa-caret-right fa-2x");
        $(this).parents(".carrito-fila").find(".carrito-columna:eq(5) i").attr("class", "desplegar-shop desplegar-open fa fa-caret-down fa-2x");
        $(this).attr("class", "botton-offer botton-offer-open");
        $(this).parents(".carrito-fila").addClass("Fila-Carrito");
        $("span", this).html("HIDE OFFERS");
        $("#carrito-results .left .carrito-fila:eq(" + row + ") .desplegablesTipos").slideDown();
    }
});
$(document).on("click", ".remove-cart", function () {
    var id = $(this).data('id');
    $("i", this).attr("class", "fa fa-spinner fa-pulse");
    var json = getCarrito();
//    $(".carrito-order-body .carrito-fila:eq(" + row + ")").hide();
    $.ajax({
        url: _root_ + "shopping-cart/RemoveCart/",
        type: 'GET',
        dataType: 'html',
        data: {
            carrito: json,
            id: id
        },
        beforeSend: function (xhr) {
        },
        success: function (data, textStatus, jqXHR) {
            var ala = JSON.parse(data);
            localStorage.setItem('carrito', JSON.stringify(ala));
//                $("#carrito-resultado").html(data);
            ajaxCarrito();
            GetCarritoHTML(function (e) {
                $("#carrito-resultado").html(e);
            });
        },
        error: function (jqXHR, textStatus, errorThrown) {
        }
    });
});
/*GET OFFER*/
$(document).on("click", ".get-offer", function () {
    var fila = $(this).parents(".carrito-fila");
    $(this).parent().addClass("add-cart-version");
    var rows = $(this).data("fila");
    var a = $(this).text();
    var hashclass = $("span", this).hasClass("added-cart");
    if (a === "ADDED" || hashclass) {

    } else {
        $(this).html("<span class='added-cart'>ADDED</span>");
        var deletes = $(this).parents(".add-cart-version").find("span:nth-child(2)").addClass("remove-version");
        /*ADD rows*/
//        $(this).parents(".add-cart-version").find("span:nth-child(2)").data("namec",$)



        var cancion = $(this).data("mp3");
        var Version = $(this).parents(".Fila-Carrito").find(".carrito-columna:nth-child(2) .desplegablesTipos span:nth-child(" + (rows + 1) + ")").data("demomp3");
        var pitch = $(this).parents(".Fila-Carrito").find(".carrito-columna:nth-child(3) .desplegablesTipos .select-pitch:eq(" + (rows) + ") select").val();
        var precio = $(this).parents(".Fila-Carrito").find(".carrito-columna:nth-child(4) .desplegablesTipos .precio-tipo:eq(" + (rows) + ")").html();
//        alert("Cancion: " + cancion + " | " + "Version: " + Version + " | " + "pitch:" + pitch + " | " + "precio: " + precio);
        var precioFila = fila.find(".carrito-columna:eq(3) .carrito-columna_hor").html();
        precioFinal = parseFloat(precio) + parseFloat(precioFila);
        $final = precioFinal.toFixed(2);
        fila.find(".carrito-columna:eq(3) .carrito-columna_hor").html($final);
        $filai = fila.index();
        $(".carrito-order-body .carrito-fila:eq(" + $filai + ") .carrito-columna:eq(1) span").html($final);
        var totalTotal = $(".carrito-orden-extern_total span:eq(1)").html();
        totalTotal = parseFloat(totalTotal);
        precio = parseFloat(precio);
        $precioFinals = totalTotal + precio;
        $totalPrecio = $precioFinals.toFixed(2);
        $(".carrito-orden-extern_total span:eq(1)").html($totalPrecio);
        var tipos = {
            Version: {
                cancion: cancion,
                version: Version,
                pitch: pitch,
                precio: precio
            }
        };
        carrito = JSON.parse(localStorage.getItem('carrito'));
        version = carrito['cart'][row]['version'];

        if (version == undefined) {
            carrito['cart'][row]['version'] = [];
            carrito['cart'][row]['version'][rows] = tipos.Version;
        } else {
            carrito['cart'][row]['version'][rows] = tipos.Version;
        }
        localStorage.setItem('carrito', JSON.stringify(carrito));
        ajaxCarrito();

    }


});
$(document).on("change", ".carrito-columna_hor select", function () {

    var rows = $(this).parents(".carrito-fila").index();
    var valor = $(this).val();
    var mp3 = $(".carrito-fila:eq(" + rows + ") .play").data("idcart");
    var pitch = 0;
    if (valor == 0) {
        $id = mp3 + ".mp3";
    } else {
        if (valor > 0) {
            $id = mp3 + '_' + valor + ".mp3";
        } else {
            $id = mp3 + "" + valor + ".mp3";
        }
    }
    ComprobacionTrack(valor, mp3, $id, false);
    CambiarMp3Play($id, ".carrito-fila:eq(" + rows + ") .play");
    CambiarPitch(rows, $id, valor);
});
$(document).on("change", ".select-pitch select", function () {
    var valor = $(this).val();
    var fila = $(this).parents(".carrito-fila").index();
    var rows = $(this).data("fila");
    var cancion = $(".carrito-fila:eq(" + fila + ") .carrito-columna:eq(4) .desplegablesTipos .list-carrito div:eq(" + rows + ") span").data("mp3");
    CambiarPitchVersion(fila, cancion, valor);
});
$(document).on("click", ".remove-version", function (e) {
//    consoleLog(e);
    var fila = $(this).parents(".carrito-fila");
    var filaIndex = fila.index();
    var rows = $(this).data("fila");
    var id_row = $(this).data("namec");
    var cancion = $(this).data("mp3");
//    consoleLog(filaIndex + "| " + rows);
//    consoleLog(rows);
//    consoleLog(id_row);
    $(this).attr("class", "remove-version fa fa-spinner fa-pulse");
    var json = getCarrito();
    var ala = JSON.parse(json);
//    ala.cart.splice(1);
    delete ala['cart'][filaIndex]['version'][id_row];
    localStorage.setItem('carrito', JSON.stringify(ala));
    ajaxCarrito();
    GetCarritoHTML(function (e) {
        $("#carrito-resultado").html(e);
    });
    GetCarritoHTML(function (e) {
        $("#carrito-resultado").html(e);
    });
});
function consoleLog(e) {
    console.log(e);
}
function CambiarPitch(row, cancionPitch, pitch) {
    carrito = JSON.parse(localStorage.getItem('carrito'));
    carritos = carrito['cart'][row];
    carrito['cart'][row]['pitch'] = pitch;
    carrito['cart'][row]['MP3Demo_pichado'] = cancionPitch;
//    console.log(carrito);
    localStorage.setItem('carrito', JSON.stringify(carrito));
    ajaxCarrito();
}
function CambiarPitchVersion(row, cancionVersion, name) {
    carrito = JSON.parse(localStorage.getItem('carrito'));
    carritos = carrito['cart'][row];
    carritosVersion = carritos['version'];
    var element = 0;
    for (var i = 0; i < carritosVersion.length; i++) {
        if (carritosVersion[i]['cancion'] == cancionVersion) {
            carrito['cart'][row]['version'][i]['pitch'] = name;
            break;
        }
    }
    localStorage.setItem('carrito', JSON.stringify(carrito));
    ajaxCarrito();
}
$(document).on("click", ".ModalEbayTheme_header-tabs", function () {
    var dataOpen = $(this).data("modal");
    var dataid = $(this).data("modalid");
    if (dataOpen == "open") {
        return;
    } else {
        $(".ModalEbayTheme_header-tabs").removeClass("modal-active").data("modal", "close");
        $(this).addClass("modal-active").data("modal", "open");
        $(".ModalEbayTheme_body-cuerpo").removeClass("modal-active");
        $("#" + dataid).addClass("modal-active");
    }
});
/*Plugins*/
(function ($) {

    $.fn.greenify = function (options) {

        // This is the easiest way to have default options.
        var settings = $.extend({
            // These are the defaults.
            color: "#556b2f",
            backgroundColor: "white"
        }, options);

        // Greenify the collection based on the settings variable.
        return this.css({
            color: settings.color,
            backgroundColor: settings.backgroundColor
        });

    };

}(jQuery));
(function ($) {
    $.fn.validar = function (opciones) {
        var defaults = {
        };
        $.extend(defaults, opciones);
        $this = this;
        var $id = $this.attr("id");
        var btn = $("input[type='submit']", this).val();

        this.submit(function (e) {
            e.preventDefault();
            //Loading
            $("input[type='submit']", this).val("Loading...");
            var error = 0;
            var loader = false;
            $(".validate", this).each(function (i, elem) {
                if ($(elem).val() == "")
                {
                    $(elem).removeClass("ok");
                    $(elem).addClass("error");
                    $(".error:first").focus();
                    error++;
                } else {
                    $(elem).removeClass("error");
                    $(elem).addClass("ok");
                    error--;
                }

            });
            if ($(".error").length == 0)
            {
                $("input[type='submit']", this).val("Correct");
//                setTimeout(function () {
                $("#" + $id).submit();

//                }, 2000);

            } else {
                $("input[type='submit']", this).val("ERROR");
            }
        });
    };
}(jQuery));
$("#registroForm").on("submit", function (e) {
    e.preventDefault();
    var ser = $(this).serialize();
    var error = 0;
    $("#registroForm .form-group").find(".label-form-success").css("display", "none");
    $("#registroForm .form-group").find(".label-form-error").css("display", "none");
//    $(".g-recaptcha iframe").contents().find(".rc-anchor").attr("style", "");
    $(".validate", this).each(function (i, elem) {
        if ($(elem).val() == "")
        {
            $("#registroForm .form-group").eq(i).attr("class", "form-group has-error has-feedback").find(".label-form-error").html('Please fill all the fields').show();
            $("#registroForm .form-group").eq(i).find(".glyphicon").show().attr("class", "glyphicon glyphicon-remove form-control-feedback");
            $(elem).removeClass("ok");
            $(elem).addClass("error");
            $(".error:first").focus();
            error++;
        } else {
            $(elem).removeClass("error");
            $(elem).addClass("ok");
            $("#registroForm .form-group").eq(i).attr("class", "form-group has-success has-feedback").find(".label-form-success").html('Input success').attr("style", "visibility:hidden;display:block;");
            $("#registroForm .form-group").eq(i).find(".glyphicon").show().attr("class", "glyphicon glyphicon-ok form-control-feedback");

        }
    });
    $.ajax({
        url: _root_ + "usuarios/registro/ComprobarUsuario/",
        type: 'POST',
        dataType: 'json',
        data: ser,
        success: function (data, textStatus, jqXHR) {
            $data = data;
            if ($data.errorusuario == "false") {
                $("#inputReUsuario").removeClass("ok").addClass("error");
                $("#inputReUsuario").parent().find(".label-form-success").css("display", "none");
                $("#inputReUsuario").parent().attr("class", "form-group has-error has-feedback").find(".label-form-error").html('The user is already registered').show();
                $("#inputReUsuario").parent().find(".glyphicon").show().attr("class", "glyphicon glyphicon-remove form-control-feedback");
                error++;
            }
            if ($data.errorusuario == "false") {
                $("#inputReUsuario").removeClass("ok").addClass("error");
                $("#inputReUsuario").parent().find(".label-form-success").css("display", "none");
                $("#inputReUsuario").parent().attr("class", "form-group has-error has-feedback").find(".label-form-error").html('The user is already registered').show();
                $("#inputReUsuario").parent().find(".glyphicon").show().attr("class", "glyphicon glyphicon-remove form-control-feedback");
                error++;
            }
            if ($data.erroremail == "false" || $data.erroremailver == "false") {
                $("#inputReEmail").removeClass("ok").addClass("error");
                $("#inputReEmail").parent().find(".label-form-success").css("display", "none");
                $("#inputReEmail").parent().attr("class", "form-group has-error has-feedback").find(".label-form-error").html('The email is already registered').show();
                $("#inputReEmail").parent().find(".glyphicon").show().attr("class", "glyphicon glyphicon-remove form-control-feedback");
                error++;
            }
            if ($data.errorpass == "false") {
                $("#inputRePass").removeClass("ok").addClass("error");
                $("#inputReCoPass").removeClass("ok").addClass("error");
                $("#inputRePass").parent().find(".label-form-success").css("display", "none");
                $("#inputRePass").parent().attr("class", "form-group has-error has-feedback").find(".label-form-error").html('Wrong password').show();
                $("#inputRePass").parent().find(".glyphicon").show().attr("class", "glyphicon glyphicon-remove form-control-feedback");

                $("#inputReCoPass").parent().find(".label-form-success").css("display", "none");
                $("#inputReCoPass").parent().attr("class", "form-group has-error has-feedback").find(".label-form-error").html('Wrong password').show();
                $("#inputReCoPass").parent().find(".glyphicon").show().attr("class", "glyphicon glyphicon-remove form-control-feedback");
                error++;
            }

            if (error == 0)
            {
                MensajeError("Welcome to superbackings!", null, 4000, "alert-info");
                $("input[type='submit']", this).val("Correct");
                document.getElementById("registroForm").submit();

            } else {
                $("input[type='submit']", this).val("Volver a intentar");
                MensajeError("Singup error!", null, 4000);
            }

        }
    });
});
$("#loginForm").on("submit", function (e) {
    e.preventDefault();
    var ser = $(this).serialize();
    var error = 0;
    $("#loginForm .form-group").find(".label-form-success").css("display", "none");
    $("#loginForm .form-group").find(".label-form-error").css("display", "none");
    $(".validate", this).each(function (i, elem) {
        if ($(elem).val() == "")
        {
            $("#loginForm .form-group").eq(i).attr("class", "form-group has-error has-feedback").find(".label-form-error").html('Please fill all the fields').show();
            $("#loginForm .form-group").eq(i).find(".glyphicon").show().attr("class", "glyphicon glyphicon-remove form-control-feedback");
            $(elem).removeClass("ok");
            $(elem).addClass("error");
            $(".error:first").focus();
            error++;
        } else {
            $(elem).removeClass("error");
            $(elem).addClass("ok");
            $("#loginForm .form-group").eq(i).attr("class", "form-group has-success has-feedback").find(".label-form-success").html("ok").attr("style", "visibility:hidden;display:block;");
            $("#loginForm .form-group").eq(i).find(".glyphicon").show().attr("class", "glyphicon glyphicon-ok form-control-feedback");

        }

    });

    $.ajax({
        url: "/usuarios/login/ComprobarUsuario/",
        type: 'POST',
        dataType: 'json',
        data: ser,
        success: function (data, textStatus, jqXHR) {
            $data = data;
            if ($data.usuario == "false") {
                $("#inputLoUsuario").removeClass("ok").addClass("error");
                $("#inputLoPassword").removeClass("ok").addClass("error");
                $("#inputLoUsuario").parent().find(".label-form-success").css("display", "none");
                $("#inputLoUsuario").parent().attr("class", "form-group has-error has-feedback").find(".label-form-error").html("Wrong username or password").show();
                $("#inputLoUsuario").parent().find(".glyphicon").show().attr("class", "glyphicon glyphicon-remove form-control-feedback");
                $("#inputLoPassword").parent().find(".label-form-success").css("display", "none");
                $("#inputLoPassword").parent().attr("class", "form-group has-error has-feedback").find(".label-form-error").html("Wrong username or password").show();
                $("#inputLoPassword").parent().find(".glyphicon").show().attr("class", "glyphicon glyphicon-remove form-control-feedback");
                error++;
            }
            if (error == 0)
            {
                MensajeError("Welcome back!", null, 4000, "alert-info");
                $("input[type='submit']", this).val("Correct");
                document.getElementById("loginForm").submit();
            } else {
                $("input[type='submit']", this).val("Volver a intentar");
                MensajeError("Error Login!", null, 4000);
            }
        }
    });
});
/*
 * Boton Login y registro
 */
