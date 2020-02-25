<?php

class downloadController extends Controller {

    private $_model;
    private $_lib;

    public function __construct() {
        parent::__construct();
        $this->_view->contentfull = "width: calc(100% - 20px);box-sizing: border-box;";
    }

    public function index() {
        $download = $_POST['download'];
        $order = $_POST['order'];
        $name = $_POST['name'];
        $artist = $_POST['artist_name'];
        $version = $_POST['tipo'];
        $pitch = $_POST['pitch'];
        if (Session::get("level") == 1) {
            if ($_POST['user'] != null) {
                $usuario = $_POST['user'];
            } else {
                $usuario = Session::get("usuario");
            }
        } else {
            $usuario = Session::get("usuario");
        }

        $name = str_replace(" ", "-", $name);
        $name = str_replace(",", "-", $name);
        $artist = ucfirst(str_replace(" ", "-", $artist));
        $version = ucfirst(str_replace(" ", "-", $version));

        $nameFull = ucfirst($name) . "_" . ucfirst($artist) . "_" . $version . "_" . $pitch;
        $this->_view->titulo = "Download Code";
        $this->_view->titulo_page = "Pitch | Super Backing Tracks";
//        $usuario = Session::get("usuario");
        $filename = "https://www.superbackings.com/modules/usuarios/ftp/$usuario/$order/$download";
        $filename_safari = "modules/usuarios/ftp/$usuario/$order/$download";
        if (is_file($filename_safari)) {
            $size = filesize($filename_safari);
            header("Content-Transfer-Encoding: binary");
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header("Content-type: application/force-download");
            header("Content-Disposition: attachment; filename=$nameFull");
            header("Content-Length: $size");
            ob_clean();
            flush();
            readfile("$filename_safari");
            exit;
//            $this->_view->renderizar("index", 'download');
        } else {
//            echo "File does not exists";
        }
    }

}
