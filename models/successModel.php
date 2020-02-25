<?php

class successModel extends Model {

    public function __construct() {

        parent::__construct();
    }

    public function InsertOrder($carrito, $idSession, $porcdescuento, $descuento, $total) {
        $code = $this->GetCodeVentas(Functions::generateRandomString(50));

        $this->_db->prepare("INSERT INTO ventas(id_usuario,arr_tracks,porcdescuento,descuento,total,fecha_init,id_code) VALUES(:id_usuario,:arrayCarrito,:porcdescuento,:descuento,:total,now(),:id_code)")
                ->execute(
                        array(
                            ":id_usuario" => $idSession,
                            ":arrayCarrito" => $carrito,
                            ":porcdescuento" => $porcdescuento,
                            ":descuento" => $descuento,
                            ":total" => $total,
                            ":id_code" => $code,
                        )
        );
        $_SESSION['carrito_paypal'] = $code;
    }

    public function GetUsuarioId($usuario) {
        $datos = $this->_db->query(
                "SELECT * FROM usuarios " .
                "WHERE usuario = '{$usuario}' "
        );
        return $datos->fetch(PDO::FETCH_ASSOC);
    }

    public function GetPrecioCarrito($carrito) {
        $precio = 0;
        for ($i = 0; $i < count($carrito->cart); $i++) {
            $precio += $carrito->cart[$i]->precio;
            foreach ($carrito->cart[$i]->version as $key => $value) {
                $precio += $value->precio;
            }
        }
        return $precio;
    }

    public function GetCodeVentas($codigos) {
        $datos = "SELECT * FROM ventas WHERE id_code ='{$codigos}'";
        $data = $this->_db->query($datos);
        $dato = $data->fetchAll(PDO::FETCH_ASSOC);
        $count = count($dato);
        if ($count == 0) {
            $codigo = $codigos;
        } else {
            $codigo = null;
            while ($count != 0) {
                $codigo = Functions::generateRandomString(50);
                $datos = "SELECT * FROM ventas WHERE id_code ='{$codigo}'";
                $data = $this->_db->query($datos);
                $dato = $data->fetchAll(PDO::FETCH_ASSOC);
                $count = count($dato);
            }
        }
        return $codigo;
    }

    public function UpdatePaypal($id_carrito, $payer_id, $payment_date, $payer_email, $payer_status, $payment_status) {
        if (empty($_SESSION['carrito_paypal_reactivate'])) {
            $reactivate = 0;
        } else {
            $reactivate = $_SESSION['carrito_paypal_reactivate'];
        }
        $this->_db->prepare("UPDATE ventas SET estado=:estado, payer_id=:payer_id, payment_date=:payment_date, payer_email=:payer_email, payer_status=:payer_status, payment_status=:payment_status,caducidad=0,totalReactivate=:reactivate WHERE id_code=:id_carrito")
                ->execute(
                        array(
                            ":id_carrito" => $id_carrito,
                            ":browser" => $browser,
                            ":estado" => "PAGADO",
                            ":payer_id" => $payer_id,
                            ":payment_date" => $payment_date,
                            ":payer_email" => $payer_email,
                            ":payer_status" => $payer_status,
                            ":payment_status" => $payment_status,
                            ":reactivate" => $reactivate
                        )
        );
        unset($_SESSION['carrito_paypal']);
        unset($_SESSION['carrito_paypal_reactivate']);
    }

}
