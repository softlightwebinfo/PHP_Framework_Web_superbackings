<?php

class searchController extends Controller {

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
        $this->_view->titulo = "Search";
        $this->_view->titulo_page = "Search | Super Backing Tracks";
//        $this->_view->setJs(array("ajax"));

        $this->_view->renderizar("index", 'search');
    }

    public function query($data, $num) {
        if ($num == 0 or $num == "") {
            $num = 1;
        }
        if ($data == "" and $num == 1) {
            $num = 1;
            $data = "a";
            $this->_view->titulo_page = "Search | Super Backing Tracks";
            $this->_view->titulo = "Search";
        } else {

            $data = str_replace("-", " ", $data);
            $this->_view->titulo_page = "Search $data | Super Backing Tracks";
            $this->_view->titulo = "Search results: <span>$data</span>";
        }
        $exp = explode("/", $_GET);
        $this->_view->Search = $data;
        $_GET['page'] = $num;

        $this->_view->renderizar("index", 'search');
    }

    public function getAjax() {
        if ($_GET['start'])
            $this->_view->Canciones = $this->_model->getScrollAjax($_GET['start'], 30, false, "artist_name");
    }

    public function getQuery() {
        if (isset($_GET['valor'])) {
            $this->_model->getSearch();
        }
    }

}
