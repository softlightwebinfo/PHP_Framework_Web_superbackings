<?php

class linksController extends Controller {

    private $_model;
    private $_lib;

    public function __construct() {
        parent::__construct();
        $this->_view->contentfull = "width: calc(100% - 20px);box-sizing: border-box;";
        $this->_view->setJsPlugin(array('min/Funciones.min'));
    }

    public function index() {

        $this->_view->titulo = "Links";
        $this->_view->titulo_page = "Links | Super Backing Tracks";
        $this->_view->meta_descripcion = "This is our full song list, you will find more than 12.000 listed backing tracks for immediately download.";
//        $this->_view->setJs(array("ajax"));

        $this->_view->renderizar("index", 'links');
    }

    

}
