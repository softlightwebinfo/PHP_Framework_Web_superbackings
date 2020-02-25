<?php

class newreleasesController extends Controller {

    private $_model;
    private $_lib;

    public function __construct() {
        parent::__construct();
        $this->_model = $this->loadModel("full");
        $this->_view->contentfull = "width: calc(100% - 20px);box-sizing: border-box;";
        $this->_view->setJsPlugin(array('min/Funciones.min'));
        $this->_view->setJs(array("min/ajax.min"));
    }

    public function index() {

        $this->_view->titulo = "New releases";
        $this->_view->titulo_page = "New releases | Super Backing Tracks";
        $this->_view->meta_descripcion = "Checkout our new releases, every friday we add several brand new backing tracks for download.";
        $this->_view->Canciones = $this->_model;
        $this->_view->renderizar("index", 'new-releases');
    }

    public function getAjax() {
        if ($_GET['start'])
            $this->_view->Canciones = $this->_model->getScrollAjax($_GET['start'], 40, "WHERE tipo=''", "fecha_alta DESC", true);
    }

}
