<?php

class customtracksController extends Controller {

    private $_model;
    private $_lib;

    public function __construct() {
        parent::__construct();
        $this->_model = $this->loadModel("full");
        $this->_view->contentfull = "width: calc(100% - 20px);box-sizing: border-box;";
        $this->_view->setJsPlugin(array('min/Funciones.min'));
    }

    public function index() {

        $this->_view->titulo = "Custom Tracks";
        $this->_view->titulo_page = "Custom Tracks | Super Backing Tracks";
        $this->_view->meta_descripcion = "We do custom backing tracks on demand. If you need any song you can send the original reference to us to be quoted. Write us now.";
        $this->_view->Canciones = $this->_model;
        $this->_view->renderizar("index", 'custom-tracks');
    }

    public function getAjax() {
        if ($_GET['start'])
            $this->_view->Canciones = $this->_model->getScrollAjax($_GET['start'], 30, false, "artist_name");
    }

}
