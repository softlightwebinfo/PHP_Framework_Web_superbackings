<?php

class apiController extends Controller {

    private $_api;

    public function __construct() {
        parent::__construct();
//        $this->_api = $this->loadModel("API");
    }

    public function index() {
        $datos = array(
            'error' => 'false',
            'ok' => 'true',
        );

        echo json_encode($datos);
    }

    public function login() {
        echo "Ok";
    }

}
