<?php

class faqController extends Controller {

    private $_model;
    private $_lib;

    public function __construct() {
        parent::__construct();
        $this->_model = $this->loadModel("full");
        $this->_view->contentfull = "width: calc(100% - 20px);box-sizing: border-box;";
        $this->_view->setJsPlugin(array('min/Funciones.min'));
    }

    public function index() {

        $this->_view->titulo = "FAQ";
        $this->_view->titulo_page = "FAQ | Super Backing Tracks";
        $this->_view->Canciones = $this->_model;
        $this->_view->renderizar("index", 'faq');
    }

}
