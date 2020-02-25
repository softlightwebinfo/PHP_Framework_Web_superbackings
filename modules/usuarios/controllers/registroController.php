<?php

class registroController extends Controller {

    private $_registro;

    public function __construct() {
        parent::__construct();
        $this->_registro = $this->loadModel("registro");
        $this->_view->setJsPlugin(array('min/Funciones.min'));
//        $this->_view->setTemplate('test');
//        $this->_view->setWidgetOptions('menu-top',array('top','top',true));
    }

    public function index() {
        $page = Session::get("patchUrl");
        $page = substr($page, 1);

//        if (Session::get("autenticado")) {
//            $this->redireccionar();
//        }
        if ($this->getInt("enviar") == 1) {
            $this->_view->datos = $_POST;
            if (!$this->getPostParam("usuario")) {
                $this->_view->_error = "Debe introducir un nombre de usuario";
                $this->_view->renderizar("index", "registro");
                exit();
            }
            if ($this->_registro->verificarUsuario($this->getPostParam("usuario"))) {
                $this->_view->_error = "El usuario " . $this->getPostParam("usuario") . " ya existe!";
                $this->_view->renderizar("index", "registro");
                exit();
            }
            if (!$this->validarEmail($this->getPostParam("email"))) {
                $this->_view->_error = "La dirección de email es inválida";
                $this->_view->renderizar("index", "registro");
                exit();
            }
            if ($this->_registro->verificarEmail($this->getPostParam("email"))) {
                $this->_view->_error = "Esta dirección de correo ya esta registrada";
                $this->_view->renderizar("index", "registro");
                exit();
            }
            if (!$this->getPostParam("password")) {
                $this->_view->_error = "Debe introducir su contraseña";
                $this->_view->renderizar("index", "registro");
                exit();
            }
            if ($this->getPostParam("password") != $this->getPostParam("confirmar")) {
                $this->_view->_error = "Las contraseñas no coinciden!";
                $this->_view->renderizar("index", "registro");
                exit();
            }
            $this->_registro->ins_prueba("", $this->getPostParam("usuario"), $this->getPostParam("password"), $this->getPostParam("email"));

//
//            $this->_registro->registrarUsuario(
//                    "", $this->getPostParam("usuario"), $this->getPostParam("password"), $this->getPostParam("email")
//            );
//
            $usuario = $this->_registro->verificarUsuario($this->getPostParam("usuario"));
//
            if (!$usuario) {
                $this->_view->_error = "Error al registrar el usuario";
                $this->_view->renderizar("index", "registro");
                exit();
            }
            $row = $this->_registro->getUsuarioUp(
                    $this->getPostParam("usuario"), $this->getPostParam("password")
            );
            $to = $this->getPostParam("email");

            $subject = 'Welcome to superbackings.com';

            // Cabecera que especifica que es un HMTL
            $headers = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

// Cabeceras adicionales
//            $headers .= "From: \"" . "Superbackings" . "\" <" . "superbackingtracks@gmail.com" . ">\n";
            $headers .= "From: sales@superbackings.com" . "\r\n" . "Sender:sales@superbackings.com";
//            $cabeceras .= 'Cc: archivotarifas@example.com' . "\r\n";
//            $cabeceras .= 'Bcc: copiaoculta@example.com' . "\r\n";

            $cuerpo = '
<html> 
    <head> 
       <title>' . $subject . '</title>
    <style type="text/css">
        .designemail {
            font-family: Verdana, Geneva, sans-serif;
        }
        .designemail strong {
            color: #3D75C4;
        }
    </style>
    </head> 
    <body> 
    <p><img src="https://www.superbackings.com/views/layout/superbackings/img/logosuperemail.png" width="400" height="60" /></p>
    <p class="designemail"><strong>Hello</strong>, ' . $this->getPostParam('usuario') . ', Thank you for registering in <a href="https://superbackings.com">superbackings.com</a></p>
    <p class="designemail">Please click on the next link to confirm your email address. <a href="' . BASE_URL . 'index/' . $usuario['codigo'] . '"></a>' . BASE_URL . 'index/' . $usuario['codigo'] . '</p>
    <p class="designemail"><strong>Remember</strong> your music will be active to be downloaded only for 30 days. After this period you will need to buy them again at reduced price. </p>
    <p class="designemail">
    <form action="' . BASE_URL . 'usuarios/panel/">
      <input name="Click here to acces to your Customer panel" type="submit" class="designemail" id="Click here to acces to your Customer panel" value="Click here to acces to your Customer panel" />
    </p>
    <p class="designemail">We want you to have an incredible experience buying with us, please let us know how can we help you to find your music. </p>
    <p class="designemail"><strong>Remember, if you are loooking for a song you can not see listed online, we can make that song from scratch specially for you. <br>We make custom backing tracks with a brilliant sound. To request a custom backing track quote you can answer to this email. </strong></p>
    <p class="designemail">Best Regards</p>
    <p class="designemail">The superbackings Team<br>
    <a href="https://www.superbackings.com">www.superbackings.com</a></p>

            </body>
            </html>
            ';


            mail($to, $subject, $cuerpo, $headers);
            Session::set("autenticado", true);
            Session::set("level", $row['role']);
            Session::set("usuario", $row['usuario']);
            Session::set("id_usuario", $row['id']);
            Session::set("tiempo", time());

            $this->_view->datos = false;
            $this->_view->_mensaje = "Registro Completado, revise su email para activar su cuenta";
        }
        $this->redireccionar($page . "/");
        $this->_view->renderizar("index", "registro");
    }

    public function activar($id, $codigo) {
        if (!$this->filtrarInt($id) || !$this->filtrarInt($codigo)) {
            $this->_view->_error = "Esta cuenta no existe!";
            $this->_view->renderizar("activar", "registro");
            exit();
        }
        $row = $this->_registro->getUsuario(
                $this->filtrarInt($id), $this->filtrarInt($codigo)
        );
        if (!$row) {
            $this->_view->_error = "Esta cuenta no existe!";
            $this->_view->renderizar("activar", "registro");
            exit();
        }
        if ($row['estado'] == 1) {
            $this->_view->_error = "Esta cuenta ya ha sido activada!";
            $this->_view->renderizar("activar", "registro");
            exit();
        }
        $this->_registro->activarUsuario(
                $this->filtrarInt($id), $this->filtrarInt($codigo)
        );
        $row = $this->_registro->getUsuario(
                $this->filtrarInt($id), $this->filtrarInt($codigo)
        );
        if ($row['estado'] == 0) {
            $this->_view->_error = "Error al activar la cuenta, por favor intente mas tarde!";
            $this->_view->renderizar("activar", "registro");
            exit();
        }

        $this->_view->_mensaje = "Su cuenta ha sido activada";
        $this->_view->renderizar("activar", "registro");
    }

    public function activaremail($id) {
        if (!$this->filtrarInt($id)) {
            $this->_view->_error = "Esta cuenta no existe!";
            $this->_view->renderizar("activar", "registro");
            exit();
        }
        $row = $this->_registro->getUsuarioId(
                $this->filtrarInt($id)
        );
        if (!$row) {
            $this->_view->_error = "Esta cuenta no existe!";
            $this->_view->renderizar("activar", "registro");
            exit();
        }
        if ($row['confirmedEmail'] == 1) {
            $this->_view->_error = "Este email ya ha sido confirmado!";
            $this->_view->renderizar("activar", "registro");
            exit();
        }
        $this->_registro->activarEmail(
                $this->filtrarInt($id)
        );
        $row = $this->_registro->getUsuarioId(
                $this->filtrarInt($id)
        );
        if ($row['confirmedEmail'] == 0) {
            $this->_view->_error = "Error al activar la cuenta, por favor intente mas tarde!";
            $this->_view->renderizar("activar", "registro");
            exit();
        }

        $this->_view->_mensaje = "Su cuenta ha sido activada";
        $this->_view->renderizar("activar", "registro");
    }

    public function ComprobarUsuario() {
//        $lib = $this->getLibrary("class.recaptcha");
//        $cachaPublic = '6LeQfBoTAAAAAEhbkwAme3BAxPSMOyugB-Vbv1Gt';
//        $cachaPrivate = '6LeQfBoTAAAAAKTtIk6Qp51k3MWNpDxlfavB5ZjN';
//        $response = null;
//        // comprueba la clave secreta
//        $reCaptcha = new ReCaptcha($cachaPrivate);
//        if ($_POST["g-recaptcha-response"]) {
//            $response = $reCaptcha->verifyResponse(
//                    $_SERVER["REMOTE_ADDR"], $_POST["g-recaptcha-response"]
//            );
//        }
//        if ($response != null && $response->success) {
//            $datos['catcha'] = "true";
//        } else {
//            $datos['catcha'] = "false";
//        }
        if ($this->_registro->verificarUsuario($this->getAlphaNum("usuario"))) {
//            $datos['errorusuario'] = "El usuario " . $this->getAlphaNum("usuario") . " ya existe!";
            $datos['errorusuario'] = "false";
        } else {
            $datos['errorusuario'] = "true";
        }
        if (!$this->validarEmail($this->getPostParam("email"))) {
//            $datos['erroremail'] = "La dirección de email es inválida";
            $datos['erroremail'] = "false";
        } else {
            $datos['erroremail'] = "true";
        }
        if ($this->_registro->verificarEmail($this->getPostParam("email"))) {
//            $datos['erroremailver'] = "Esta dirección de correo ya esta registrada";
            $datos['erroremailver'] = "false";
        } else {
            $datos['erroremailver'] = "true";
        }
        if ($_POST['password'] == $_POST['confirmar']) {
            $datos['errorpass'] = "true";
        } else {
            $datos['errorpass'] = "false";
        }
        echo json_encode($datos);
    }

}
