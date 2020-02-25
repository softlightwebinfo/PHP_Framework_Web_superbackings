<?php

class loginModel extends Model {

    public function __construct() {
        parent::__construct();
    }

    public function getUsuario($usuario, $password) {
        $datos = $this->_db->query(
                "SELECT * FROM usuarios " .
                "WHERE email = '$usuario' " .
                "and password = '" . Hash::getHash(HASH_ALGORITMO, $password, HASH_KEY) . "'"
        );
        return $datos->fetch();
    }

}
