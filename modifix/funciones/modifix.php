<?php

/**
 * Description of modifix
 *
 * @author rafa
 */
class modifix {

    public $_db;

    public function __construct() {
        $this->GET_COSTANTES();
        $this->GET_APPLICATION();
    }

    public function autoLoadClases($nombre_clase) {
        spl_autoload_register(function ($nombre_clase) {
            include "class/" . $nombre_clase . '.php';
        });
    }

    public function GETURL() {
        return "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    }

    public function GET_ROOT($url = null) {
        if ($url == null) {
            return $_SERVER['DOCUMENT_ROOT'];
        } else {
            return $_SERVER['DOCUMENT_ROOT'] . "/" . $url;
        }
    }

    public function GET_METODOS() {
        Session::init();
//        $this->_db = new Database(DB_HOST, DB_NAME, DB_USER, DB_PASS, DB_CHAR);
    }

    public function GET_COSTANTES() {
        
    }

    /**
     * Base de datos Mysql_conect
     * @return type
     */
    public function BD() {
        return mysql_connect(DB_HOST, DB_USER, DB_PASS) or die(mysql_error());
    }

    /**
     * BASE DE DATOS NEW PDO
     * @param type $return
     * @return \PDO
     */
    public static function DB($return = false) {
        /* Conectar a una base de datos ODBC invocando al controlador */
        $dsn = "mysql:dbname=" . DB_NAME . ";host=" . DB_HOST . "";
        $usuario = DB_USER;
        $contraseña = DB_PASS;

        try {
            return new PDO($dsn, $usuario, $contraseña, array(
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES ' . DB_CHAR
            ));
        } catch (PDOException $e) {
            echo 'Falló la conexión: ' . $e->getMessage();
        }
    }

    public function GET_APPLICATION($APP = null) {
        include $this->GET_ROOT("application/Config.php");
        include $this->GET_ROOT("application/Database.php");
        include $this->GET_ROOT("application/Functions.php");
        include $this->GET_ROOT("application/Session.php");
        $this->GET_METODOS();
    }

    public static function SETFTP_CONECTION() {
        // variables
        $ftp_server = "67.23.247.176";
        $ftp_user_name = "archkzt";
        $ftp_user_pass = "yhwh=777";
// establecer una conexión o finalizarla

        $conn_id = ftp_connect($ftp_server) or die("No se pudo conectar a $ftp_server");

// intentar iniciar sesión
        if (@ftp_login($conn_id, $ftp_user_name, $ftp_user_pass)) {
            return true;
        } else {
            return false;
        }

// cerrar la conexión ftp
        ftp_close($conn_id);
    }

}

?>