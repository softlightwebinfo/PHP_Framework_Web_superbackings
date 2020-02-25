
<!DOCTYPE html>
<html>
    <head>
        <title>Posicionamiento con HTML , JavaScript y Google Maps</title>
        <meta charset="UTF-8">
        <script type='text/javascript' src="jquery-2.2.2.min.js"></script>

        <style type="text/css">
            html, body {
                height: 100%;
                margin: 0;
                padding: 0;
            }
            #mapa_content {
                height: 100%;
            }
            @media print {
                html, body {
                    height: auto;
                }
                #mapa_content {
                    height: 650px;
                }
            </style>
            <style>
                #googleMap
                {
                    position: fixed !important;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0 !important;
                    height: 100% !important;
                }
            </style>
            <script type="text/javascript">
                function obtainGeolocation() {
                    //obtener la posición actual y llamar a la función  "localitation" cuando tiene éxito
                    window.navigator.geolocation.getCurrentPosition(localitation);
                }
                var x, y;
                function localitation(geo) {
                    // En consola nos devuelve el Geoposition object con los datos nuestros
                    var latitude = geo.coords.latitude;
                    var longitude = geo.coords.longitude;
//                    document.body.innerHTML = " Latitud:" + latitude + " ------ Longitud:" + longitude + ""
                    x = latitude;
                    y = longitude;
                    var myLatLng = {lat: x, lng: y};
                    // Crear un objeto de mapa y especifique el elemento DOM para su visualización.
                    var map = new google.maps.Map(document.getElementById('googleMap'), {
                        center: myLatLng,
                        scrollwheel: true,
                        mapTypeId: google.maps.MapTypeId.HYBRID,
                        zoom: 4,
                    });
                    // Create the search box and link it to the UI element.
                    var input = document.getElementById('pac-input');
                    var searchBox = new google.maps.places.SearchBox(input);
                    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

                    // Bias the SearchBox results towards current map's viewport.
                    map.addListener('bounds_changed', function () {
                        searchBox.setBounds(map.getBounds());
                    });

                    // Crear un marcador y establecer su posición
                    var infowindow = new google.maps.InfoWindow({
                        content: ''
                    });
                    var marcadores = [{
                            position: {
                                lat: x,
                                lng: y
                            },
                            title: "Hola estoy en mi direccion",
                            contenido: "Hola, ¡estoy en mi direccion!",
//                             image: {
//                                url: 'src/maps/icon-caminata.png',
//                                // This marker is 20 pixels wide by 32 pixels high.
//                                size: new google.maps.Size(30, 30),
//                                // The origin for this image is (0, 0).
//                                origin: new google.maps.Point(0, 0),
//                                // The anchor for this image is the base of the flagpole at (0, 32).
//                                anchor: new google.maps.Point(0, 32)
//                            }
                        }, {
                            position: {
                                lat: 37.400165,
                                lng: -5.993729
                            },
                            contenido: "Hola, ¡estoy en Sevilla!",
                        }, {
                            position: {
                                lat: 37.266949,
                                lng: -6.949539
                            },
                            contenido: "<div id=\"content\">" + "<h2>Huelva</h2>" + "<div id=\"bodyContent\">" + "<p><b>Huelva</b>, es una ciudad y municipio español, capital de la provincia que lleva su nombre, situado en la comunidad autónoma de Andalucía. Se encuentra localizada en la denominada «<em>Tierra llana</em>», en la confluencia de los ríos Tinto y Odiel, perteneciendo a la «<em>Cuenca del Guadiana</em>»,1 y según datos del INE poseía a 1 de enero de 2010 una población de 149.310 habitantes, y 240.000 en su área metropolitana según el POTA. Es capital de provincia desde 18332 con rango de ciudad desde 1876.</p>" + "<p>Fuente: <a href=\"http://es.wikipedia.org/wiki/Huelva\">" + "Wikipedia</a>.</p>" + "</div>" + "</div>"
                        }];
                    //Mostramos los datos de los marcadores en el mapa
                    for (var i = 0, j = marcadores.length; i < j; i++) {
                        var contenido = marcadores[i].contenido;
                        var marker = new google.maps.Marker({
                            position: new google.maps.LatLng(marcadores[i].position.lat, marcadores[i].position.lng),
                            map: map,
                            animation: google.maps.Animation.DROP,
                            icon: marcadores[i].image,
                        });
                        (function (marker, contenido) {
                            google.maps.event.addListener(marker, 'click', function () {
                                infowindow.setContent(contenido);
                                infowindow.open(map, marker);
                            });
                        })(marker, contenido);
                    }
                }
                //llamando la funcion inicial para ver trabajar la API
                obtainGeolocation();

            </script>
        </head>
        <body>
            <input id="pac-input" class="controls" type="text" placeholder="Search Box">
            <div id="googleMap"></div>  <!-- Div contenedor del mapa -->

            <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAMMNj6hdJntm1GQhDjdnwpgR5NBs_JrRg">
            </script>
        </body>
    </html>