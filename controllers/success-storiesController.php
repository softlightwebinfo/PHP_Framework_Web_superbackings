<?php

class successstoriesController extends Controller {

    private $_model;
    private $_lib;

    public function __construct() {
        parent::__construct();
        $this->_model = $this->loadModel("full");
        $this->_view->contentfull = "width: calc(100% - 20px);box-sizing: border-box;";
        $this->_view->setJsPlugin(array('min/Funciones.min'));
    }

    public function index() {
        $this->_view->titulo = "Success Stories";
        $this->_view->titulo_page = "Success Stories | Super Backing Tracks";
        $this->_view->meta_descripcion = "Why buying in our website, read about regular  customers who use our services all the time. Checkout why we’re one of the world´s top companies in the backing tracks industry.";
        $this->_view->Canciones = $this->_model;
        $this->_view->renderizar("index", 'success-stories');
    }

    public function getAjax() {
        if ($_GET['start'])
            $this->_view->Canciones = $this->_model->getScrollAjax($_GET['start'], 30, false, "artist_name");
    }

}
