<?php

class artistController extends Controller {

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

        $this->_view->titulo = "Artist";
        $this->_view->titulo_page = "Artist Page | Super Backing Tracks";
        $this->_view->renderizar("index", 'from-a-to-z');
    }

    public function query($data) {
        $data = str_replace("-", " ", $data);
        $this->_view->titulo_page = "Artist $data | Super Backing Tracks";
        $this->_view->titulo = "Artist: <span>$data</span>";

        $this->_view->Artist = $data;

        $this->_view->renderizar("index", 'artist');
    }

}
