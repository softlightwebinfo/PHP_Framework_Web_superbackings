<?php

class pitchController extends Controller {

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
        $this->_view->titulo = "Pitch";
        $this->_view->titulo_page = "Pitch | Super Backing Tracks";
        $this->_view->meta_descripcion = "You can change the pitch of the songs entirely free of charge. Change the tone before buying. Listen to the demos by pitching and shifting online.";

//        $this->_view->setJs(array("ajax"));

        $this->_view->renderizar("index", 'search');
    }

}
