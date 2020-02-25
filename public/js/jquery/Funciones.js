$(document).ready(function () {
    $(".close").on("click", close);
    $("#search input").on("keypress", keyEnterLocationSearch);
    $("#search button").on("click", function () {
        redireccionar("#search input", null);
    });
    $('[data-toggle="tooltip"]').tooltip();
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
        row = $(this).parents("tr").index();
        tipo_select = $("option:selected", this).text();
        play(valor, "#fbody tr:eq(" + row + ") td:eq(0) i", true);
        CambiarMp3Play(valor, "#fbody tr:eq(" + row + ") td:eq(0) i");
    });
    /**
     * Flecha Pitch
     * 
     * */
    $num = 0;
    $(document).on("click", "#fbody tr td .desplegar", function (e) {
        $this = $(this);
        row = $(this).parents("tr").index();
        if ($this.hasClass("desplegar-open")) {
            $(this).attr("class", "desplegar desplegar-close fa fa-caret-right fa-2x");
            $(".desplegar").removeClass("desplegar-open").addClass("desplegar-close");
            $(".desplegableTipos").hide();
            $(".pitch-info").hide();
            $("tr").removeClass("TipoCss");
        } else {
            $(".desplegableTipos").hide();
            $(".pitch-info").hide();
            $("#fbody tr:eq(" + row + ") td:eq(3) .pitch-info").show();
            $("tr").removeClass("TipoCss");
            $("#fbody tr td .desplegar").attr("class", "desplegar desplegar-close fa fa-caret-right fa-2x");
            $(this).attr("class", "desplegar desplegar-open fa fa-caret-down fa-2x");
            $(this).parents("tr").addClass("TipoCss");
            $("#fbody tr:eq(" + row + ") td:eq(4) .desplegableTipos").slideDown();
        }
    });
    /**
     * Sumar y restar pitch
     */
    $(document).on("click", ".desplegableTipos .pitch .pitch-minus", function () {
        pitch_controls("minus", this);
    });
    $(document).on("click", ".desplegableTipos .pitch .pitch-plus", function () {
        pitch_controls("plus", this);
    });
});
function getTipo(e) {
    var data = $(e).text();
    return data;
}
/*Carrito*/
/*BTN CARRITO*/
function addCart(idOriginal, id_OriginalMP3, tipo, tipo_Mp3, precio) {
    if (localStorage.getItem("carrito") == null) {
        var carrito = {
            'cart': [],
            'state': true
        };
    } else {
        carrito = JSON.parse(localStorage.getItem('carrito'));
        carrito.cart.push({'name': 'screenAasas', 'width': 450, 'height': 250});
        localStorage.setItem('carrito', JSON.stringify(carrito));
    }

// Converting the JSON string with JSON.stringify()
// then saving with localStorage in the name of session
    localStorage.setItem('carrito', JSON.stringify(carrito));

// Example of how to transform the String generated through 
// JSON.stringify() and saved in localStorage in JSON object again
    var restoredSession = JSON.parse(localStorage.getItem('carrito'));

// Now restoredSession variable contains the object that was saved
// in localStorage
    console.log(restoredSession);
}
addCart();
$(document).on("click", ".buybtn", function () {
    var $this = $(this);
    var id = $this.data("carrito");
//    var tipo = $this.parents("tr").find("td:eq(4) select").html();
    $(this).css({
        "color": "#081F35"
    }).html("Added cart");
    /*idOriginal*/
    idOriginal = $this.data("carritoid");
    /*Demo de la idOriginal*/
    id_OriginalMP3 = $(this).parents("tr").find("td:eq(0) i").data("track") + ".mp3";
    tipo = getTipo($(this).parents("tr").find("td:eq(4) select option:selected"));
    alert(tipo);
    addCart(idOriginal, id_OriginalMP3);





//    if (localStorage.getItem("cart") == null) {
//        localStorage.setItem("cart", JSON.stringify(id));
//    } else {
//        if ($("#masDatos").length) {
//            $(".precio_set").html("0.49&euro;");
//            $(".precio_set").parents(".buybtn").attr("data-precio_set", 0.49);
//            alert(ids['id']);
//
////                localStorage.setItem("cart", localStorage.getItem("cart") + "," + JSON.stringify(id));
//        } else {
//            localStorage.setItem("cart", localStorage.getItem("cart") + "," + JSON.stringify(id));
//        }
//
//    }
//    AjaxCarrito(getCarrito(), "json", function (data) {
//        MensajeError("Added to cart...");
//        $("#carrito .items_count a b").html(data.count + " items");
//        $("#carrito #totalCarrito").html(data.precio + " &euro;");
//        if ($("#masDatos").length) {
//            $(".precio_set").html("0.49&euro;");
//            $(".precio_set").parents(".buybtn").attr("data-precio_set", 0.49);
//        }
//    });
});
//                    localStorage.removeItem("cart","");
$("#result").html("<p></p></p></p>");
/*AJAX CARRITO*/

var AjaxCarrito = function (datos, type, successs) {

    $.ajax({
        url: _root_ + "checkout/carritoajax/",
        type: 'GET',
        data: {
            datos: datos
        },
        dataType: type,
        beforeSend: function (xhr) {

        },
        success: function (data, textStatus, jqXHR) {
            successs(data);
        }, error: function (jqXHR, textStatus, errorThrown) {
            alert('error');
        }
    });
};
AjaxCarrito(getCarrito(), "json", function (data) {
    $("#carrito .items_count a b").html(data.count + " items");
    $("#carrito #totalCarrito").html(data.precio + " &euro;");
});
/*GET CARRITO*/
function getCarrito() {
    if (localStorage.getItem("cart") === undefined || localStorage.getItem("cart") === null)
    {
        return "vacio";
    } else {
        return localStorage.getItem("cart");
    }
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
function ComprobacionTrack($pitch, $name, $name_pitch) {
    $.ajax({
        url: _root_ + 'pitch_1.php',
        data: {
            pitch: $pitch,
            name: $name,
            name_pitch: $name_pitch
        },
        type: 'GET',
        beforeSend: function (xhr) {
        },
        success: function (data, textStatus, jqXHR) {
            if (data == "existe" || data == 0) {
                play($name_pitch, "#fbody tr:eq(" + row + ") td:eq(0) i", true);
                CambiarMp3Play($name_pitch, "#fbody tr:eq(" + row + ") td:eq(0) i");
            } else {
                pause("#fbody tr:eq(" + row + ") td:eq(0) i");
                Pitch($pitch, $name, $name_pitch);
            }
        }
    });
}

function Pitch($pitch, $name, $name_pitch) {
    $.ajax({
        url: _root_ + 'pitch.php',
        data: {
            pitch: $pitch,
            name: $name,
            name_pitch: $name_pitch
        },
        type: 'GET',
        beforeSend: function (xhr) {
            $("#fbody tr:eq(" + row + ") td:eq(1) .loader").show().html("Wait while pitching");
            $("#fbody tr:eq(" + row + ") td:eq(2) .loader-status").show().html("000%");
        },
        success: function (data, textStatus, jqXHR) {
            if (textStatus == "success") {
                $("#fbody tr:eq(" + row + ") td:eq(1) .loader").html("");
                play($name_pitch, "#fbody tr:eq(" + row + ") td:eq(0) i", true);
                CambiarMp3Play($name_pitch, "#fbody tr:eq(" + row + ") td:eq(0) i");
            } else {
                alert("Error");
            }
        }
    });
}
function AjaxProceso() {

}

function pitch_controls(controls, e) {
    var pitch = $("#fbody tr:eq(" + row + ") td:eq(4) .desplegableTipos .pitch .pitch-result");
    var rows = $("#fbody tr:eq(" + row + ") td:eq(0) i").data("track");
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
        url: _root_ + "index/GetTipo",
        type: 'GET',
        dataType: 'json',
        data: {
            cancion: cancion,
            artista: artista,
            tipo: type,
        }, success: function (data, textStatus, jqXHR) {
            if (data.count == 0) {
                alert("Error no track");
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
function ComprovarTipo(cancion, artista) {
    $.ajax({
        url: _root_ + "index/CountTipo",
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
            $(e).attr("class", "play fa fa-play-circle fa-2x");
            $('#player').attr("src", "");
            $("#fbody tr").removeClass("activePlay");
            $("#player")[0].pause();
        } else {
            $("#fbody tr").removeClass("activePlay");
            $("#fbody .play").attr("class", "play fa fa-play-circle fa-2x");
            $(e).attr("class", "play fa fa-pause fa-2x");
            var ruta = "http://backingtracks.tv/demos/" + cancion;
            $('#player').attr("src", ruta);
            $(e).parents("tr").addClass("activePlay");
            $("#player")[0].play();
        }
    } else {
        $("#fbody .play").attr("class", "play fa fa-play-circle fa-2x");
        $("#fbody tr").removeClass("activePlay");
        $(e).attr("class", "play fa fa-pause fa-2x");
        var ruta = "http://backingtracks.tv/demos/" + cancion;
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
            window.location = "http://www.superbackings.com/search/query/" + padre + "/";
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
    window.location = "http://www.superbackings.com/search/query/" + padre + "/";
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
                url: "http://www.superbackings.com/search/getquery",
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
var MensajeError = function (mensaje, style, time) {

    //si no se envia la variable time, esta se define por defecto en 6 segundos
    if (time === undefined)
        time = 6000;
    if (style === undefined)
        style = null;

    //agregamos el div a nuestra pagina con la clase css Error anteriormente hecha
    $("body").append("<div class='Error' style='" + style + "'></div>");

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
}