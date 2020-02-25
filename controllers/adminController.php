<?php

class adminController extends Controller {

    private $_admin;

    public function __construct() {
        parent::__construct();
        $this->_admin = $this->loadModel("admin");
    }

    public function index() {
        
    }

    public function GetUsuarios() {
        $id = $_GET['id'];
        $data = $_GET['data'];
        $columna = $_GET['columna'];
        switch ($columna) {
            case "nombre-apellido":
                $ap = substr_count($data, " ");
                echo $ap;
                if ($ap == 0) {
                    $this->_admin->UpdateUserName($id, "usuarios", "nombre", $data);
                    $this->_admin->UpdateUserName($id, "datosUsuarios", "apellido", "");
                } else {
                    $da = explode(" ", $data);
                    $con = count($da);
                    echo $con;
                    if ($con <= 1) {
                        $this->_admin->UpdateUserName($id, "usuarios", "nombre", $data);
                        $this->_admin->UpdateUserName($id, "datosUsuarios", "apellido", "");
                    } else {
                        $this->_admin->UpdateUserName($id, "usuarios", "nombre", $da[0]);
                        if ($con == 2) {
                            $this->_admin->UpdateUserName($id, "datosUsuarios", "apellido", $da[1]);
                        } else {
                            $this->_admin->UpdateUserName($id, "datosUsuarios", "apellido", $da[1] . " " . $da[2]);
                        }
                    }
                }
                break;
            case "nombre-usuario":
                $this->_admin->Update($id, "usuarios", "usuario", $data);
                break;
            case "nombre-email":
                $this->_admin->Update($id, "usuarios", "email", $data);
                break;
            default:
                break;
        }
    }

    public function resetearPass() {
        $id = $_GET['id'];
        $this->_admin->resetearPass($id);
    }

    public function EditTraks() {
        $id = $_GET['id'];
        $data = $_GET['data'];
        $columna = $_GET['columna'];
        switch ($columna) {
            case "name":
                $this->_admin->UpdateTracks($id, "name", $data);
                break;
            case "artist_name":
                $this->_admin->UpdateTracks($id, "artist_name", $data);
                break;
            case "tipo":
                $this->_admin->UpdateTracks($id, "tipo", $data);
                break;
            case "demo":
                $this->_admin->UpdateTracks($id, "demo", $data);
                break;
            case "precio":
                $this->_admin->UpdateTracks($id, "precio", $data);
                break;
            case "master":
                $this->_admin->UpdateTracks($id, "master", $data);
                break;
            case "descripcion":
                $this->_admin->UpdateTracks($id, "descripcion", $data);
                break;
            default:
                break;
        }
    }

    public function ViewTracks() {
        $cart = $_REQUEST['tracks'];
        $idVentas = $_REQUEST['idVentas'];
        $this->_admin->viewTraksModal($cart, $idVentas);
    }

    public function BloquedUsuarios($id, $bloq) {
        $this->_admin->BloquedUsuarios($id, $bloq);
    }

    public function activarOrden($id, $email, $usuario) {
        $this->_admin->ActivarOrden($id, $email, $usuario);
    }

    public function Search($param) {
        
    }

    public function generateCodePromocional() {
        echo $this->_admin->generateCodePromocional();
    }

}
