<?php Session::set("patchUrl", $_SERVER['REQUEST_URI']); ?>
<?php
if (MIN_JS != false) {
    $MIN_JS = MIN_JS;
    $MIN_JS_TIPO = ".min";
} else {
    $MIN_JS = "";
    $MIN_JS_TIPO = "";
}

if (MIN_CSS != false) {
    $MIN_CSS = MIN_CSS;
    $MIN_CSS_TIPO = ".min";
} else {
    $MIN_CSS = "";
    $MIN_CSS_TIPO = "";
}
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <title><?php if (isset($this->titulo_page)) echo $this->titulo_page; ?></title>
        <meta charset="utf-8">
        <link href="<?php echo $_layoutParams['ruta_css'] . $MIN_CSS . "bootstrap$MIN_CSS_TIPO.css"; ?>" rel="stylesheet" type="text/css">
        <link href="<?php echo $_layoutParams['ruta_css']; ?>estilos.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo $_layoutParams['ruta_css']; ?>prettyPhoto.css" rel="stylesheet" type="text/css" />
        <!--<meta name="description" content="Welcome to Superbackings.com the biggest backing tracks database for instant download"/>-->
        <meta name="description" content="<?= $this->meta_descripcion; ?>"/>

        <link rel="icon" type="image/png" href="<?php echo $_layoutParams['ruta_img']; ?>favicon.ico" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
        <link href="<?php echo $_layoutParams['ruta_css']; ?>nico.css" rel="stylesheet" type="text/css" />

        <style type="text/css">
            body{
                /*padding-top: 40px;*/
                padding-bottom: 40px;            
            }
        </style>
        <!-- javascript -->

        <script type="text/javascript" src="<?php echo $_layoutParams['root']; ?>public/js/jquery/jquery-2.2.1.min.js"></script>
        <script type="text/javascript" src="<?php echo $_layoutParams['ruta_js'] . $MIN_JS . "bootstrap$MIN_JS_TIPO.js"; ?>"></script>
        <script type="text/javascript" src="<?php echo $_layoutParams['ruta_js'] . $MIN_JS . "jquery.prettyPhoto$MIN_JS_TIPO.js"; ?>"></script>
        <script type="text/javascript" src="<?php echo $_layoutParams['ruta_js'] . $MIN_JS . "jquery.cycle.all$MIN_JS_TIPO.js"; ?>"></script>
        <script src='https://www.google.com/recaptcha/api.js?hl=EN'></script>

        <script type="text/javascript">
            var _root_ = '<?php echo $_layoutParams['root']; ?>';
            var _root_img_ = '<?php echo $_layoutParams['ruta_img']; ?>';

            $(function () {
                $("#play").click(function () {
                    $("#Slider").cycle('resume');
                    return false;
                });
                $("#pause").click(function () {
                    $("#Slider").cycle('pause');
                    return false;
                });
                /*shuffle*/
                $("#Slider").cycle({
                    fx: 'shuffle',
                    next: '#next',
                    prev: '#prev',
                    pager: '#pager',
                    timeout: 10000,
                    speed: 900,
                    pause: 1,
                });
            });
            var BrowserDetect = {
                init: function () {
                    this.browser = this.searchString(this.dataBrowser) || "An unknown browser";
                    this.version = this.searchVersion(navigator.userAgent)
                            || this.searchVersion(navigator.appVersion)
                            || "an unknown version";
                    this.OS = this.searchString(this.dataOS) || "an unknown OS";
                },
                searchString: function (data) {
                    for (var i = 0; i < data.length; i++) {
                        var dataString = data[i].string;
                        var dataProp = data[i].prop;
                        this.versionSearchString = data[i].versionSearch || data[i].identity;
                        if (dataString) {
                            if (dataString.indexOf(data[i].subString) != -1)
                                return data[i].identity;
                        } else if (dataProp)
                            return data[i].identity;
                    }
                },
                searchVersion: function (dataString) {
                    var index = dataString.indexOf(this.versionSearchString);
                    if (index == -1)
                        return;
                    return parseFloat(dataString.substring(index + this.versionSearchString.length + 1));
                },
                dataBrowser: [
                    {string: navigator.userAgent,
                        subString: "OmniWeb",
                        versionSearch: "OmniWeb/",
                        identity: "OmniWeb"
                    },
                    {
                        string: navigator.vendor,
                        subString: "Apple",
                        identity: "Safari"
                    },
                    {
                        prop: window.opera,
                        identity: "Opera"
                    },
                    {
                        string: navigator.vendor,
                        subString: "iCab",
                        identity: "iCab"
                    },
                    {
                        string: navigator.vendor,
                        subString: "KDE",
                        identity: "Konqueror"
                    },
                    {
                        string: navigator.userAgent,
                        subString: "Firefox",
                        identity: "Firefox"
                    },
                    {
                        string: navigator.vendor,
                        subString: "Camino",
                        identity: "Camino"
                    },
                    {// for newer Netscapes (6+) 
                        string: navigator.userAgent,
                        subString: "Netscape",
                        identity: "Netscape"
                    },
                    {
                        string: navigator.userAgent,
                        subString: "MSIE",
                        identity: "Explorer",
                        versionSearch: "MSIE"
                    },
                    {
                        string: navigator.userAgent,
                        subString: "Gecko",
                        identity: "Mozilla",
                        versionSearch: "rv"
                    },
                    {// for older Netscapes (4-) 
                        string: navigator.userAgent,
                        subString: "Mozilla",
                        identity: "Netscape",
                        versionSearch: "Mozilla"
                    }
                ],
                dataOS: [
                    {
                        string: navigator.platform,
                        subString: "Win",
                        identity: "Windows"
                    },
                    {
                        string: navigator.platform,
                        subString: "Mac",
                        identity: "Mac"
                    },
                    {
                        string: navigator.platform,
                        subString: "Linux",
                        identity: "Linux"
                    }
                ]

            };
            BrowserDetect.init();
            if (BrowserDetect.browser == "Firefox") {
                document.write("<link href='<?php echo $_layoutParams['ruta_css']; ?>firefox.css' rel='stylesheet' type='text / css' />");
            } else {
                if (BrowserDetect.browser == "Explorer") {
                    if (BrowserDetect.version >= 7) {
                        document.write("<LINK REL='stylesheet' HREF='estilo_ie7.css' TYPE='text/css'>");
                    } else {
                        document.write("<LINK REL='stylesheet' HREF='estilo_ie6.css' TYPE='text/css'>");
                    }
                } else {
                    if (BrowserDetect.browser == "Opera") {
                        if (BrowserDetect.version < 9) {
                            document.write("<LINK REL='stylesheet' HREF='estilo_opera.css' TYPE='text/css'>");
                        } else {
                            document.write("<LINK REL='stylesheet' HREF='estilo_opera9.css' TYPE='text/css'>");
                        }
                    } else {
//                        document.write("<LINK REL='stylesheet' HREF='estilo_otros.css' TYPE='text/css'>");
                    }

                    if (BrowserDetect.browser == "Safari") {
                        document.write("<link href='<?php echo $_layoutParams['ruta_css']; ?>safari.css' rel='stylesheet' type='text / css' />");

                    } else {
//                        document.write("<LINK REL='stylesheet' HREF='estilo_otros.css' TYPE='text/css'>");
                    }

                }
            }
        </script>

    </head>
    <body id="body">
        <!-- BEGIN ProvideSupport.com Visitor Monitoring Code -->
        <div id="ci7yK6" style="z-index:100;position:absolute"></div><div id="sd7yK6" style="display:none"></div><script type="text/javascript">var se7yK6 = document.createElement("script");
            se7yK6.type = "text/javascript";
            var se7yK6s = (location.protocol.indexOf("https") == 0 ? "https" : "http") + "://image.providesupport.com/js/13ysac9no0s401omwaoa6iobw9/safe-monitor.js?ps_h=7yK6&ps_t=" + new Date().getTime();
            setTimeout("se7yK6.src=se7yK6s;document.getElementById('sd7yK6').appendChild(se7yK6)", 1)</script><noscript><div style="display:inline"><a href="https://www.providesupport.com?monitor=13ysac9no0s401omwaoa6iobw9"><img src="https://image.providesupport.com/image/13ysac9no0s401omwaoa6iobw9.gif" style="border:0px" alt=""/></a></div></noscript>
        <!-- END ProvideSupport.com Visitor Monitoring Code -->
        <div id="wrapperPage">
            <?php
            if (Session::accesoViewEstrictoAdmin(array(1))) {
                ?>
                <div id="PanelAdminMenu">
                    <div class="navigation_holder nav_top_line " style="background: rgb(46, 144, 194);">
                        <div class="navigation_inside_container">
                            <div id="navigation" role="navigation" class="mobile_hide">
                                <div class="header-menu">
                                    <ul id="menu-navigation" class="menu">
                                        <li><a href="<?= BASE_URL_ADMIN ?>">Modifix</a></li>
                                        <li><a href="<?= BASE_URL_ADMIN ?>pages/usuarios/">Usuarios</a></li>
                                        <li><a href="<?= BASE_URL_ADMIN ?>pages/tracks/?page=1">Tracks</a></li>
                                        <li><a href="<?= BASE_URL_ADMIN ?>pages/ventas/?page=1">Ventas</a></li>
                                        <li><a href="<?= BASE_URL_ADMIN ?>pages/generate/">Generate</a></li>
                                        <li><a href="<?= BASE_URL_ADMIN; ?>pages/promociones/">Promociones</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
            } else {
                ?>
                <div class="promo-bar parent-close"  style="display: block;"> 
                    <a href="#" class="close">
                        <img src="<?= BASE_URL_IMG; ?>close-icon.png" alt="Close" width="20" height="20">
                    </a>
                    <span> Request a custom backing track quote   <a href="mailto:superbackingtracks@gmail.com?subject=Quote request" class="promobar-button">Click Here</a> 
                    </span>
                </div>
            <?php } ?>
            <?php if (isset($this->_widgets['top'])): ?>
                <?php
                foreach ($this->_widgets['top'] as $top):
                    echo $top;
                endforeach;
                ?>
            <?php endif; ?>

            <div class="header_holder">
                <div class="header_inside_container"> 
                    <div id="header_social_icons" class="mobile_hide">
                        <div id="panelUsuario">
                            <?php if (Session::get("autenticado")): ?>
                                <span>Welcome, </span>
                                <span><?= Session::get("usuario"); ?></span>
                                <span>[<a href="<?= BASE_URL . "usuarios/login/cerrar?page=" . $this->_request->get_controlador(); ?>">Logout</a></span>
                                <span> | </span>
                                <span><a href="<?= BASE_URL . "usuarios/panel/" ?>">Panel</a>]</span>
                            <?php else: ?>
                                <span>[<a data-toggle="modal" data-target="#login" id="btnLoginRegister" data-whatever="@getbootstrap">Login | Sign up</a></span>
                                <span> | </span>
                            <?php endif; ?>
                        </div>
                        <div id="carrito"> 
                            <div id="basketbox"> 
                                <div id="basketc"> 
                                    <table cellpadding="0" cellspacing="0" border="0">  
                                        <tbody>
                                            <tr> 
                                                <td><div style="font-size: 21px;margin-right: 7px;" class="fa fa-shopping-cart"></div></td>    
                                                <td class="items_count">          
                                                    Your shopping cart: <a href=""><b>0 items</b></a>       
                                                </td>                        
                                            </tr>                               
                                        </tbody>
                                    </table>     
                                </div>                 
                                <a href="<?= BASE_URL . "shopping-cart/" ?>" class="lnk_shopcart"></a>         
                                <div class="clear"></div>                     
                                <div id="basket-ajax">   
                                    <div class="sep">                       
                                        Total: <b id="totalCarrito">0</b>                  
                                    </div>                            
                                    <div class="sep">       
                                        <div class="confirm-basket no-float">      
                                            <?php if (!Session::get("autenticado")): ?>
                                                <a data-toggle="modal" data-target="#login" data-whatever="@getbootstrap"><img src="<?= BASE_URL_IMG ?>checkout_inactive.png"></a>
                                            <?php else: ?>
                                                <a href="<?= BASE_URL; ?>shopping-cart/"><img src="<?= BASE_URL_IMG ?>checkout_active.png"></a>
                                            <?php endif; ?>
                                        </div>                   
                                    </div>                         
                                </div>
                            </div>                  
                        </div>
                    </div>

                    <div id="site_logo">
                        <a href="<?= BASE_URL ?>index.php">
                            <img src="<?php echo $_layoutParams['ruta_img']; ?>logosuper.png" alt="Superbackings">
                        </a>
                        <h2>Superbackings</h2>

                    </div>     
                    <div class="clear"></div>       
                </div>
                <div class="clear"></div>
            </div>
            <div id="home_banner_container">

                <?php if (isset($this->_widgets['banner'])): ?>
                    <?php
                    foreach ($this->_widgets['banner'] as $wd):
                        echo $wd;
                    endforeach;
                    ?>
                <?php endif; ?>
            </div>
            <div id="wrapper">
                <div id="content">
                    <!-- CUERPO DE LA PAGINA -->
                    <div id="main" style="<?php if (isset($this->contentfull)) echo $this->contentfull; ?>" class="leftside beatstore">
                        <h1 id="content_header">
                            <?= $this->titulo; ?>
                            <?php if (isset($this->_widgets['titlebar'])): ?>
                                <?php
                                foreach ($this->_widgets['titlebar'] as $wd):
                                    echo $wd;
                                endforeach;
                                ?>
                            <?php endif; ?>
                        </h1> 
                        <div class="clear"></div>
                        <noscript><p>Para el correcto funcionamiento debe tener el soporte para javascript habilitado</p></noscript>

                        <?php if (isset($this->_error)): ?>
                            <div id="_errl" class="alert alert-error">
                                <a class="close" data-dismiss="alert">x</a>
                                <?php echo $this->_error; ?>
                            </div>
                        <?php endif; ?>

                        <?php if (isset($this->_mensaje)): ?>

                            <div id="_msgl" class="alert alert-success">
                                <a class="close" data-dismiss="alert">x</a>
                                <?php echo $this->_mensaje; ?>
                            </div>
                        <?php endif; ?>
                        <?php if (isset($this->_widgets['body'])): ?>
                            <?php
                            foreach ($this->_widgets['body'] as $wd):
                                echo $wd;
                            endforeach;
                            ?>
                        <?php endif; ?>
                        <?php include_once $this->_contenido; ?>
                    </div>
                    <!-- FIN CUERPO DE LA PAGINA -->
                    <!-- WIDGETS -->
                    <div id="right_side">
                        <div class="sidebar">
                            <?php if (isset($this->_widgets['sidebar'])): ?>
                                <?php
                                foreach ($this->_widgets['sidebar'] as $wd):
                                    echo $wd;
                                endforeach;
                                ?>
                            <?php endif; ?>

                        </div>
                    </div>
                    <!-- FIN WIDGETS -->
                </div>
                <div class="clear"></div>
                <div class="push"></div>

            </div>
            <div id="footer">
                <div class="widget_holder">
                    <?php if (isset($this->_widgets['footer'])): ?>
                        <?php foreach ($this->_widgets['footer'] as $wd): ?>
                            <div class="footer_widget">
                                <?= $wd; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <div class="clear"></div>
                </div>
                <div class="clear"></div>
            </div>
            <div id="footer_sub">
                <div class="widget_holder_sub">
                    <audio id="player" src="" loop></audio>
                    <div id="goTop"><a href="#body"><img src="<?php echo $_layoutParams['ruta_img']; ?>/gotop.png" title="Go Top"></a></div>
                </div>
            </div>
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="modalTitle">
                                Different versions for each song
                            </h4>
                        </div>
                        <div class="modal-body" id="modalBody">
                            <div class="buy-step">
                                <span class=" push">1</span> 
                                <b>Full Version ( FV ):</b> This is the full backing track with backing vocals ( if the original song  have ) and without backing vocals ( if the original song doesn’t have). The full version is the most complete version of the song without the lead vocal.
                            </div>
                            <div class="buy-step">
                                <span class=" push">2</span> 
                                <b>Without B/Vocals ( W BV ):</b>  Like the name tells, is the backing track without the backing vocals. Is just like the full version without the background singers.
                            </div>
                            <div class="buy-step">
                                <span class=" push">3</span> 
                                <b>Without guitars ( GT ):</b> Is the song for guitarists, the song do not have guitars, no lead and no rhythmic and do not have backing vocals neither.
                            </div>
                            <div class="buy-step">
                                <span class=" push">4</span> 
                                <b>Without Bass ( BS ):</b> Is the backing tracks without bass and without background vocals. Is for bass players who want to play all along the song.
                            </div>
                        </div>
                        <div class="modal-footer" id="modalFooter">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <!--<button type="button" class="btn btn-primary">Save changes</button>-->
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="login" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modalEby">
                            <div class="modalEbyWidth">
                                <div class="ModalEbayTheme">
                                    <div class="ModalEbayTheme-container">
                                        <div class="ModalEbayTheme-container-header">
                                            <div class="ModalEbayTheme_header-tabs  modal-active" data-modal="open" data-modalid="modal-identificate">
                                                <i class="fa fa-sign-in fa-2x"></i>
                                                <span>USER LOGIN</span>
                                            </div>
                                            <div class="ModalEbayTheme_header-tabs" data-modal="close" data-modalid="modal-registrate">
                                                <i class="fa fa-user-plus fa-2x"></i>
                                                <span>SIGN UP</span>
                                            </div>
                                        </div>
                                        <div class="ModalEbayTheme-container-body">
                                            <div class="ModalEbayTheme-container-body">
                                                <div class="ModalEbayTheme_body-cuerpo modal-active" id="modal-identificate">
                                                    <!--                                                    <div class="ModalEbayTheme-info">
                                                                                                            <span>Welcome back, login to access to your account.</span>
                                                                                                        </div>-->
                                                    <div class="ModalEbayTheme_body-cuerpo-form">
                                                        <form method="POST" action="<?php echo BASE_URL . "usuarios/login/index?page=" . $this->_request->get_controlador(); ?>" id="loginForm">
                                                            <input type="hidden" value="1" name="enviar">
                                                            <div class="form-group">
                                                                <label class="control-label label-form-success" style="display: none;" for="inputSuccess2"></label>
                                                                <label class="control-label label-form-error" style="display: none;" for="inputSuccess2"></label>
                                                                <input onfocus class="form-control validate"  id="inputLoUsuario" type="text" name="email" placeholder="email">
                                                                <span class="glyphicon glyphicon-ok form-control-feedback" style="display: none;" aria-hidden="true"></span>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="control-label label-form-success" style="display: none;" for="inputSuccess2"></label>
                                                                <label class="control-label label-form-error" style="display: none;" for="inputSuccess2"></label>
                                                                <input class="form-control validate" id="inputLoPassword" name="password" type="password" placeholder="Password">
                                                                <span class="glyphicon glyphicon-ok form-control-feedback" style="display: none;" aria-hidden="true"></span>
                                                            </div>
                                                            <div class="form-group">
                                                                <input class="btn btn-checkout btn-lg btn-block" type="submit" value="Login">
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="ModalEbayTheme_body_footer">
                                                                    <!--                                                                    <div class="checkbox checkbox-info left">
                                                                                                                                            <input id="checkbox1" class="styled" type="checkbox">
                                                                                                                                            <label for="checkbox1">
                                                                                                                                                Remember me
                                                                                                                                            </label>
                                                                                                                                        </div>
                                                                                                                                        <div class="right">
                                                                                                                                            <a href=""> Forgot your password?</a>
                                                                                                                                        </div>-->
                                                                </div>
                                                            </div>
                                                        </form>
                                                        <!--                                                        <div class="ModalEbayTheme-info_footer">
                                                                                                                    <span>¿Estás usando un dispositivo público o compartido? Quita la marca para proteger tu cuenta.<a href="">Más información</a></span>
                                                                                                                </div>-->
                                                    </div>
                                                </div>
                                                <div class="ModalEbayTheme_body-cuerpo" id="modal-registrate">
                                                    <div class="ModalEbayTheme_body-cuerpo-form">
                                                        <form method="POST" action="<?php echo BASE_URL . "usuarios/registro/index?page=" . $this->_request->get_controlador(); ?>" id="registroForm">
                                                            <input type="hidden" value="1" name="enviar">
                                                            <div class="form-group">
                                                                <label class="control-label label-form-success" style="display: none;" for="inputSuccess2"></label>
                                                                <label class="control-label label-form-error" style="display: none;" for="inputSuccess2"></label>
                                                                <input name="usuario" id="inputReUsuario" class="form-control validate" type="text" placeholder="Your username">
                                                                <span class="glyphicon glyphicon-ok form-control-feedback" style="display: none;" aria-hidden="true"></span>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label label-form-success" style="display: none;" for="inputSuccess2"></label>
                                                                <label class="control-label label-form-error" style="display: none;" for="inputSuccess2"></label>                                                                
                                                                <input  name="email" id="inputReEmail" class="form-control validate" type="email" placeholder="Your email">
                                                                <span class="glyphicon glyphicon-ok form-control-feedback" style="display: none;" aria-hidden="true"></span>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label label-form-success" style="display: none;" for="inputSuccess2"></label>
                                                                <label class="control-label label-form-error" style="display: none;" for="inputSuccess2"></label>                                                                
                                                                <input name="password" id="inputRePass" class="form-control validate"  type="password" placeholder="Password">
                                                                <span class="glyphicon glyphicon-ok form-control-feedback" style="display: none;" aria-hidden="true"></span>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label label-form-success" style="display: none;" for="inputSuccess2"></label>
                                                                <label class="control-label label-form-error" style="display: none;" for="inputSuccess2"></label>                                                                
                                                                <input name="confirmar" id="inputReCoPass" class="form-control validate" type="password" placeholder="Confirm Password">
                                                                <span class="glyphicon glyphicon-ok form-control-feedback" style="display: none;" aria-hidden="true"></span>
                                                            </div>
                                                            <!--                                                            <div class="form-group">
                                                                                                                            <div class="g-recaptcha" data-sitekey="6LeQfBoTAAAAAEhbkwAme3BAxPSMOyugB-Vbv1Gt"></div>
                                                                                                                        </div>-->

                                                            <div class="form-group">
                                                                <input class="btn btn-checkout btn-lg btn-block" value="Sign up" type="submit">
                                                            </div>
                                                            <div class="ModalEbayTheme-info_footer">
                                                                <span>You’ll get an email with your user and password details to access to your own customer panel to download your music. By registering to this website you agree to our company policy. You are very welcome and we are very happy to have you here.</span>
                                                            </div>
                                                            <div class="ModalEbayTheme-info_footer">
                                                                <div class="checkbox checkbox-info">
                                                                    <input id="checkbox2" name="informed" class="styled" type="checkbox">
                                                                    <label for="checkbox2">
                                                                        I want to be informed about new backing tracks releases
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="animate_cart"></div>

            <?php if (isset($_layoutParams['js_plugin']) && count($_layoutParams['js_plugin'])): ?>
                <?php foreach ($_layoutParams['js_plugin'] as $plg): ?>
                    <script src="<?php echo $plg; ?>" type="text/javascript"></script>
                <?php endforeach; ?>
            <?php endif; ?>
            <?php if (isset($_layoutParams['js']) && count($_layoutParams['js'])): ?>
                <?php foreach ($_layoutParams['js'] as $js): ?>
                    <script src="<?php echo $js; ?>" type="text/javascript"></script>
                <?php endforeach; ?>
            <?php endif; ?>
            <script>

            $(document).ready(function () {
                //                            /* Pretty Photo */ $("#Fader").prettyPhoto();
                //                            $(".gallery:first a[rel^='prettyPhoto']").prettyPhoto({animation_speed: 'normal', slideshow: 6000});
                //                            /* FAQ Toggle */ $(".toggle_container").hide();
                //                            $("h4.trigger").click(function () {
                //                                $(this).toggleClass("active").next().slideToggle("normal");
                //                                return false;
                //                            });
                /* easyFader */

                // $('#Fader img:gt(0)').hide();
            });
//                        if (localStorage.getItem("session")) {
//                        } else {
//                            $("body").attr("style", "display: none;");
//                        }
            </script>

    </body>
</html>