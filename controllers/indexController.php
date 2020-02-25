<?php

class indexController extends Controller {

    private $_model;
    private $_index;
    private $_lib;

    public function __construct() {
        parent::__construct();
        $this->_model = $this->loadModel("full");
        $this->_index = $this->loadModel("index");
        $this->_view->setJsPlugin(array('min/Funciones.min'));
//        $this->_view->setJsPlugin(array('min/Funciones.min'));
    }

    public function index() {
        $this->_view->titulo = "Latest 20 tracks";
        $this->_view->titulo_page = "Super Backing Tracks";
        $this->_view->meta_descripcion = "Welcome to Superbackings.com the biggest backing tracks database for instant download";
        $this->_view->Canciones = $this->_model;
        $exp = explode("/", $_GET['url']);
        $id = $exp[1];
        if ($exp[0] == "index") {
            if (!$this->filtrarInt($id)) {
                $this->_view->_error = "This account do not exist";
                $this->_view->renderizar("index", "inicio");
                exit();
            }
            $row = $this->_index->getUsuarioId(
                    $this->filtrarInt($id)
            );
            if (!$row) {
                $this->_view->_error = "This account do not exist";
                $this->_view->renderizar("index", "inicio");
                exit();
            }
            if ($row['confirmedEmail'] == 1) {
                $this->_view->_error = "This email is already confirmed";
                $this->_view->renderizar("index", "inicio");
                exit();
            }
            $this->_index->activarEmail(
                    $this->filtrarInt($row['id'])
            );
            $row = $this->_index->GetDatosUsuarios(
                    $this->filtrarInt($id)
            );
            if ($row['confirmedEmail'] == 0) {
                $this->_view->_error = "Ups! There was an error, please try again later.";
                $this->_view->renderizar("index", "inicio");
                exit();
            }

            $this->_view->_mensaje = "Your account has been activated";
            $this->_view->renderizar("index", 'inicio');
        }
        $this->_view->renderizar("index", 'inicio');
    }

    public function getemail() {
        $email = $_GET['email'];
        if (!$this->validarEmail($email)) {
            echo "Email not valid! Fix the email.";
        } else {
            $this->_model->InsertEmail($email);
            echo "Thanks! We’ll notify you!";
        }
    }

    public function GetTipo() {
        switch ($_GET['tipo']) {
            case "tipos_full":
                $tipop = "";
                break;
            case "tipos_vocals":
                $tipop = "WV";
                break;
            case "tipos_guitar":
                $tipop = "GT";
                break;
            case "tipos_bass":
                $tipop = "BASS";
                break;

            default:
                break;
        }
        if (isset($_GET['cancion']) and isset($_GET['artista'])) {
            $data = array();
            $dataa = $this->_model->getTipeVersion($_GET['cancion'], $_GET['artista'], $tipop);
            $data['count'] = count($dataa);
            $data['tipo'] = $dataa[0]['demo'];
            echo json_encode($data);
        }
    }

    public function CountTipo() {
        if (isset($_GET['artista']) and isset($_GET['cancion'])) {
            $tipo = $_GET['typo'];
            $dataa = $this->_model->getContentType($_GET['cancion'], $_GET['artista']);
            foreach ($dataa as $key => $value) {
                if ($value['tipo'] == ""):
                    ?>
                    <div><span class="tipos_full <?php if ($tipo == "FV") echo "actives"; ?>">Full Version</span></div>
                    <?php
                endif;
                if ($value['tipo'] == "WV"):
                    ?>
                    <div><span class="tipos_vocals">Without b/vocals</span></div>
                    <?php
                endif;

                if ($value['tipo'] == "GT"):
                    ?>
                    <div><span class="tipos_guitar">Without guitars</span></div>
                    <?php
                endif;

                if ($value['tipo'] == "BASS"):
                    ?>
                    <div><span class="tipos_bass">Without Bass</span></div>
                    <?php
                endif;
            }
        }
    }

}
