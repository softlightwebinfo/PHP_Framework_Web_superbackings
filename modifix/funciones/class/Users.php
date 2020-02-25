<?php

/**
 * Description of Users
 *
 * @author rafa
 */
class Users extends modifix {

    protected $nombre;
    protected $email;

    public function __construct() {
        $this->nombre = Session::get("usuario");
    }

    public function GetUsers($id = "full") {
        if ($id == 'full') {
            $datos = $this->DB()->query(
                    "SELECT 
                    usuarios.*, 
                    datosUsuarios.* 
                 FROM 
                    usuarios 
                 LEFT JOIN 
                    datosUsuarios 
                 ON 
                    usuarios.id = datosUsuarios.id 
                 "
            );
        } else {
            $datos = $this->DB()->query(
                    "SELECT 
                    usuarios.*, 
                    datosUsuarios.* 
                 FROM 
                    usuarios 
                 LEFT JOIN 
                    datosUsuarios 
                 ON 
                    usuarios.id = datosUsuarios.id 
                 WHERE 
                    usuarios.id = '{$id}' "
            );
        }

        return $datos->fetchall(PDO::FETCH_ASSOC);
    }

    public function GetUser($id = "full") {
        if ($id == 'full') {
            $datos = $this->DB()->query(
                    "SELECT 
                    usuarios.*, 
                    datosUsuarios.* 
                 FROM 
                    usuarios 
                 LEFT JOIN 
                    datosUsuarios 
                 ON 
                    usuarios.id = datosUsuarios.id 
                 "
            );
        } else {
            $datos = $this->DB()->query(
                    "SELECT 
                    usuarios.*, 
                    datosUsuarios.* 
                 FROM 
                    usuarios 
                 LEFT JOIN 
                    datosUsuarios 
                 ON 
                    usuarios.id = datosUsuarios.id 
                 WHERE 
                    usuarios.id = '{$id}' "
            );
        }

        return $datos->fetch(PDO::FETCH_ASSOC);
    }

    function getNombre() {
        return $this->nombre;
    }

    function getEmail() {
        return $this->email;
    }

}
