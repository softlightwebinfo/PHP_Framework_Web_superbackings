<?php

class fullsonglistController extends Controller {

    private $_model;
    private $_lib;

    public function __construct() {
        parent::__construct();
        $this->_model = $this->loadModel("full");
        $this->_view->contentfull = "width: calc(100% - 20px);box-sizing: border-box;";
        $this->_view->Canciones = $this->_model;
        $this->_view->setJsPlugin(array('min/Funciones.min'));
    }

    public function index() {

        $this->_view->titulo = "Full Song List";
        $this->_view->titulo_page = "Full song list | Super Backing Tracks";
        $this->_view->meta_descripcion = "This is our full song list, you will find more than 12.000 listed backing tracks for immediately download.";
//        $this->_view->setJs(array("ajax"));

        $this->_view->renderizar("index", 'full-song-list');
    }

    public function getAjax() {
        if ($_GET['start'])
            $this->_view->Canciones = $this->_model->getScrollAjaxJS($_GET['start'], 10);
    }

    public function query($data) {
        $this->_view->titulo_page = "Full Song List | Super Backing Tracks";
        $this->_view->titulo = "Full Song List";
//        $exp = explode("/", $_GET);
//        $this->_view->Search = $data;
        $_GET['page'] = $data;

        $this->_view->renderizar("index", 'full-song-list');
    }

}
