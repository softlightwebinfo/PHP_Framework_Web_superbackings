<?php

class fromatozController extends Controller {

    private $_model;
    private $_lib;

    public function __construct() {
        parent::__construct();
        $this->_model = $this->loadModel("full");
        $this->_view->contentfull = "width: calc(100% - 20px);box-sizing: border-box;";
    }

    public function index() {

        $this->_view->titulo = "From a to z";
        $this->_view->titulo_page = "From a to z | Super Backing Tracks";
        $this->_view->meta_descripcion = "A full backing tracks list from the A to the Z, to make the search more confortable for you. You can navigate across de titles and artists to find your music.";
        $this->_view->Canciones = $this->_model;
        $this->_view->setJsPlugin(array('min/Funciones.min'));
        $this->_view->renderizar("index", 'from-a-to-z');
    }

    public function getAjax() {
        if ($_GET['start'])
            $this->_view->Canciones = $this->_model->getScrollAjax($_GET['start'], 30, false, "artist_name");
    }

}
