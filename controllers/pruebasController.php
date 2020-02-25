<?php

class pruebasController extends Controller {

    private $_model;
    private $_lib;

    public function __construct() {
        parent::__construct();
        $this->_view->contentfull = "width: calc(100% - 20px);box-sizing: border-box;";
    }

    public function index() {

        $this->_view->titulo = "Pruebas";
        $this->_view->titulo_page = "Pruebas | Super Backing Tracks";
        
        $this->_view->renderizar("index", 'pruebas');
    }

}
