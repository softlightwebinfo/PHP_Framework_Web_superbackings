<?php

class Promociones {

    private $con;

    public function __construct() {
        /* Conectar a una base de datos ODBC invocando al controlador */
        $dsn = "mysql:dbname=" . DB_NAME . ";host=" . DB_HOST . "";
        $usuario = DB_USER;
        $contraseña = DB_PASS;

        try {
            $this->con = new PDO($dsn, $usuario, $contraseña, array(
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES ' . DB_CHAR
            ));
        } catch (PDOException $e) {
            echo 'Falló la conexión: ' . $e->getMessage();
        }
    }

    public function get_promociones() {
        $datos = $this->con->query("SELECT * FROM codigoPromocional");
        return $datos->fetchall(PDO::FETCH_ASSOC);
    }

    public function get_promocion($codigo) {
        $datos = $this->con->query("SELECT * FROM codigoPromocional WHERE codigo='{$codigo}'");
        $p = $datos->fetchall(PDO::FETCH_ASSOC);
        return $p;
    }

    public function insert_promocion($codigo, $porciento) {
        $this->con->query("INSERT INTO codigoPromocional(codigo,porciento) VALUES('{$codigo}','{$porciento}')");
    }

}
