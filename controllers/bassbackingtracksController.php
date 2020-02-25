<?php

class bassbackingtracksController extends Controller {

    private $_model;
    private $_index;
    private $_lib;

    public function __construct() {
        parent::__construct();
        $this->_model = $this->loadModel("full");
        $this->_index = $this->loadModel("index");
        $this->_view->setJsPlugin(array('min/Funciones.min'));
    }

    public function index() {
        $this->_view->titulo = "Backing Tracks for Bassist";
        $this->_view->titulo_page = "Bass backing tracks | Super Backing Tracks";
        $this->_view->meta_descripcion = "Welcome to Superbackings.com Download backing tracks for bassist";
        $this->_view->Canciones = $this->_model;
        $this->_view->renderizar("index", 'inicio');
    }

    

}
