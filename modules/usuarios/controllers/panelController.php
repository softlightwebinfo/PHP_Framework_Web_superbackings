<?php

class panelController extends Controller {

    private $_panel;

    public function __construct() {
        parent::__construct();
        $this->_panel = $this->loadModel("panel");
        $this->_view->panel = $this->_panel;
        $this->_view->setJsPlugin(array('min/Funciones.min', 'plugins/min/Slider.min'));
        $this->_view->setJs(array('min/ajax.min'));
        $this->_view->contentfull = "width: calc(100% - 20px);box-sizing: border-box;";
    }

    public function index() {
        $page = $_GET['page'];
        $this->_view->titulo = Session::get("usuario") . ", your control panel";
        $this->_view->titulo_page = "Customer Panel | Super Backing Tracks";
        $this->redireccionar("usuarios/panel/download-orders/");

//        $this->_view->renderizar("index", "panel");
    }

    public function details() {
        $this->_acl->acceso("panel_details");
        $page = $_GET['page'];
        $this->_view->titulo = Session::get("usuario") . ", your control panel";
        $this->_view->titulo_page = "Customer Panel | Super Backing Tracks";
        $id = $this->_panel->GetUsuarioId(Session::get("usuario"));
        $this->_view->idUsuario = $id;
        $this->_view->panel = $this->_panel;
        if ($this->getInt("guardar") == 1) {

            if ($this->getSql("password") !== 0 || $this->getSql("password") !== "") {
                $this->_panel->editarPassword($this->filtrarInt($id['id']), $this->getSql("password"));
            }
            $this->_panel->editarDatosUsuarios($this->filtrarInt($id['id']), $this->getSql("nombre"), $this->getSql("apellido"), $this->getTexto("direccion"), $this->getPostParam("whatsapp"), $this->getPostParam("pais"), $this->getPostParam("ciudad"), $this->getPostParam("codigoPostal"), $this->getTexto("facebook"));
            $this->_view->_mensaje = "You have updated the data correctly";

//            $this->redireccionar("usuarios/panel/");
        }


        $this->_view->renderizar("details", "panel");
    }

    public function downloadorders($idUsuario = false) {
        $this->_view->idUsuario = $idUsuario;
        $this->_acl->acceso("panel_download");
        $page = $_GET['page'];
        $this->_view->titulo = Session::get("usuario") . ", your control panel";
        $this->_view->titulo_page = "Customer Panel | Super Backing Tracks";
        $this->_view->renderizar("download-orders", "panel");
    }

    public function customorders() {
        $this->_acl->acceso("panel_custom");
        $page = $_GET['page'];
        $this->_view->titulo = Session::get("usuario") . ", your control panel";
        $this->_view->titulo_page = "Customer Panel | Super Backing Tracks";
        $this->_view->renderizar("custom-orders", "panel");
    }

    public function ModalAjax() {
        $code = $_REQUEST['code'];
        $order = $_REQUEST['order'];
        $idUser = $_REQUEST['idUser'];
        $datos = $this->_panel->GetModalOrders($code, $order,$idUser);
    }

    public function ModalAjaxExpired() {
        $code = $_REQUEST['code'];
        $datos = $this->_panel->GetModalOrdersExpired($code);
    }

    public function buyNowPanel() {
        $code = $_REQUEST['code'];
        Session::set("carrito_paypal", $code);
//        print_r($_SESSION);
    }

    public function reactivateBuyNowPanel() {
        $code = $_REQUEST['code'];
        $precio = $_REQUEST['precio'];
        Session::set("carrito_paypal", $code);
        Session::set("carrito_paypal_reactivate", $precio);
    }

    public function downloadPanel() {
        $download = $_REQUEST['download'];
        $order = $_REQUEST['order'];
//        $datos = Functions::Download($download, $order);
    }

}
