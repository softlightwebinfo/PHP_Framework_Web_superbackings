<?php

class contactController extends Controller {

    private $_model;
    private $_lib;

    public function __construct() {
        parent::__construct();
        $this->_model = $this->loadModel("full");
        $this->_view->contentfull = "width: calc(100% - 20px);box-sizing: border-box;";
        $this->_view->setJsPlugin(array('min/Funciones.min'));
    }

    public function index() {

        $this->_view->titulo = "Contact";
        $this->_view->titulo_page = "Contact | Super Backing Tracks";
        $this->_view->meta_descripcion = "If you have any question, please contact us. We’re here to help you to find your music.";
        $this->_view->Canciones = $this->_model;
        $this->_view->renderizar("index", 'contact');
    }

    public function getAjax() {
        if ($_GET['start'])
            $this->_view->Canciones = $this->_model->getScrollAjax($_GET['start'], 30, false, "artist_name");
    }

}
