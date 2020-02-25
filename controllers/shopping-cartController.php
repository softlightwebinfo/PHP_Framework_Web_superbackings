<?php

class shoppingcartController extends Controller {

    private $_model;
    private $_lib;
    private $_orderPendent;

    public function __construct() {
        parent::__construct();
        $this->_model = $this->loadModel("full");
        $this->_view->contentfull = "width: calc(100% - 20px);box-sizing: border-box;";
        $this->_view->Canciones = $this->_model;
        $this->_orderPendent = $this->loadModel("shoppingcart");
        $this->_view->setJsPlugin(array('min/Funciones.min'));
    }

    public function index() {
        $this->_view->titulo = "YOUR SUPER CART";
        $this->_view->titulo_page = "Your shopping cart | Super Backing Tracks";
        $this->_view->localstorage = $localStorage = $this->_model;
        $this->_view->code = $this->_orderPendent->GetCodeVentas(Functions::generateRandomString(50));
        $this->_view->setJs(array('min/ajax.min'));
        $this->_view->renderizar("index", 'shopping-cart');
    }

    public function carritoajax() {
        $json = array();
        if (isset($_POST['datos'])) {
            $datos = $_POST['datos'];
            $datos = stripslashes($datos);
//            $datos = "[" . $datos . "]";
            $datos_array = json_decode($datos);
//            print_r($datos_array);
            Session::set("carrito", $datos_array);
        } else {
            
        }
        /* Convertir array carrito */
        if (count($datos_array->cart) == 0) {
            $json['precio'] = "0€";
            $json['count'] = 0;
        } else {
//    $arrayCarrito = explode(",", $datos);
            $json['count'] = count($datos_array->cart);
            $precio = 0;

//    for ($i = 0; $i < count($arrayCarrito); $i++) {
//        $sql = 'SELECT precio FROM tb_tracks WHERE id="' . $arrayCarrito[$i] . '"';
//        $query = $wpdb->get_row($sql);
//        $precio += $query->precio;
//    }
            for ($i = 0; $i < count($datos_array->cart); $i++) {
                $precio += $datos_array->cart[$i]->precio;
                foreach ($datos_array->cart[$i]->version as $key => $value) {
                    $precio += $value->precio;
                }
            }

            $json['precio'] = $precio . "€";
        }
        echo json_encode($json);
    }

    public function CarritoHTML() {
        $this->_model->Carrito();
    }

    public function GetCarritoResultado() {
        $carrito = ($_GET['carrito']);
        $datos = stripslashes($carrito);
//            $datos = "[" . $datos . "]";
        $datos_array = json_decode($datos);
        $_SESSION['cart'] = $datos_array;
    }

    public function GetCarritoResultado1() {
        $carrito = ($_GET['carrito']);
        $datos = stripslashes($carrito);
//            $datos = "[" . $datos . "]";
        $datos_array = json_decode($datos);
//        print_r($datos_array);
        foreach ($datos_array->cart as $key => $value) {
            $datos_BD = $this->_model->GetCheckoutDemo($value->MP3Demo);
            echo $key . "<br>";
            echo "Cancion: " . $datos_BD[0]['name'] . "<br>";
            echo "Artista: " . $datos_BD[0]['artist_name'] . "<br>";
            echo "Tipo: " . $value->tipo . "<br>";
            if ($value->MP3Demo_pichado == "") {
                $pitch = 'false';
            } else {
                $pitch = $value->MP3Demo_pichado;
            }
            echo "Cancion Pitch: " . $pitch . "<br>";
            echo "Precio: " . $datos_BD[0]['precio'] . " €<br>";
            echo "<hr>";
        }
    }

    public function RemoveCart() {
        $carrito = ($_GET['carrito']);
        $id = ($_GET['id']);
        $datos = stripslashes($carrito);
//            $datos = "[" . $datos . "]";
        $datos_array = json_decode($datos);
        unset($datos_array->cart[$id]);
        $datos_array = array_values($datos_array->cart);
        $datos = array("cart" => $datos_array, "state" => true);
        $_SESSION['carrito'] = $datos;
//        print_r($_SESSION['carrito']);
        echo json_encode($datos);
?>
        <?php

    }

    public function RemoveCartVersion() {
        $carrito = ($_GET['carrito']);
        $fila = ($_GET['fila']);
        $row = ($_GET['row']);
        $datos = stripslashes($carrito);
        $datos_array = json_decode($datos);
//        foreach ($datos_array->cart[$fila]->version as $key => $value) {
//            if ($value->cancion == $cancion) {
//                unset($datos_array->cart[$fila]->version[$key]);
//                break;
//            }
//        }
//        $datos_arrays = array_values($datos_array->cart[$fila]->version);
//        $datos_array->cart[$fila]->version = $datos_arrays;
        $datos = $datos_array;
        $_SESSION['carrito'] = $datos;
        echo json_encode($datos);
//        echo "<pre>";
//        print_r($datos_array);
//        echo "</pre>";
        ?>
        <?php

    }

    public function PrepareOrder() {
        $carrito = ($_SESSION['carrito']);
        $code = $_POST['code'];
        $id = $this->_orderPendent->GetUsuarioId(Session::get("usuario"));
        $json = json_encode($carrito);
        $this->_orderPendent->InsertOrder($json, $id['id'], 0, 0, $this->_orderPendent->GetPrecioCarrito($carrito), $code);
        $count = $this->_orderPendent->GetCountRegistros(Session::get("id_usuario"));
        $session = Session::get("usuario");
        $carpeta = $session . "/" . "order_" . $count;
        $order = "order_" . $count;
        if (Functions::CrearCarpeta(null, $carpeta)) {
            $files = $this->_orderPendent->PrepareOrderFiles($order);
            echo "true";
        } else {
            echo "false";
        }
        Session::destroy("carrito");
    }

}
