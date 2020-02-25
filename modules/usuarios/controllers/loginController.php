<?php

class loginController extends Controller {

    private $_login;

    public function __construct() {
        parent::__construct();
        $this->_login = $this->loadModel("login");
        $this->_view->contentfull = "width: calc(100% - 20px);box-sizing: border-box;";
        $this->_view->setJsPlugin(array('min/Funciones.min'));
    }

    public function index() {
        $page = Session::get("patchUrl");
        $page = substr($page, 1);

        $this->_view->titulo = "Sign in";
        $this->_view->titulo_page = "Sign in | Super Backing Tracks";

        if ($this->getInt("enviar") == 1) {
            $this->_view->datos = $_POST;
            if (!$this->getPostParam("email")) {
                $this->_view->_error = "Wrong email";
                $this->_view->renderizar("index", "login");
                exit();
            }
            if (!$this->getPostParam("password")) {
                $this->_view->_error = "Wrong password";
                $this->_view->renderizar("index", "login");
                exit();
            }
            $row = $this->_login->getUsuario(
                    $this->getPostParam("email"), $this->getPostParam("password")
            );
            if (!$row) {
                $this->_view->_error = "Wrong email or password";
                $this->_view->renderizar("index", "login");
                exit();
            }
            if ($row["estado"] != 1) {
                $this->_view->_error = "Wrong email or password";
                $this->_view->renderizar("index", "login");
                exit();
            }
            Session::set("autenticado", true);
            Session::set("level", $row['role']);
            Session::set("usuario", $row['usuario']);
            Session::set("id_usuario", $row['id']);
            Session::set("tiempo", time());
            if ($this->getInt("usuariosLogin") == 1) {
                $this->redireccionar("usuarios/panel");
            } else {
                $this->redireccionar($page);
            }
        } else {
            if (Session::get("autenticado")) {
                $this->redireccionar();
            }
        }

        $this->_view->renderizar("index", "usuarios");
    }

    public function ComprobarUsuario() {
        $row = $this->_login->getUsuario(
                $this->getPostParam("email"), $this->getPostParam("password")
        );
        if (!$row) {
            $datos['usuario'] = "false";
        } else {
            $datos['usuario'] = "true";
        }
        echo json_encode($datos);
    }

    public function cerrar() {
        $page = $_GET['page'];
        $page = substr($page, 1);
        Session::destroy(array('autenticado', 'level', 'usuario', 'id_usuario', 'tiempo'));
        $this->redireccionar("index.php" . "/");
    }

}
