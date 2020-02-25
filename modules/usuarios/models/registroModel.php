<?php

class registroModel extends Model {

    public function __construct() {
        parent::__construct();
    }

    public function verificarUsuario($usuario) {
        $id = $this->_db->query(
                "SELECT * FROM usuarios WHERE usuario = '$usuario'"
        );
        return $id->fetch();
    }

    public function verificarEmail($email) {
        $id = $this->_db->query(
                "SELECT id FROM usuarios WHERE email = '$email'"
        );
        if ($id->fetch()) {
            return true;
        }
        return false;
    }

    public function ins_prueba($nombre, $usuario, $password, $email, $informed = false) {
        $random = rand(1782598471, 9999999999);
        $keysp = Hash::encrypt($password, HASH_KEY);
        $password = HASH::getHash(HASH_ALGORITMO, $password, HASH_KEY);
        $level = Session::getLevel(LEVEL);
        $this->_db->prepare(
                        "INSERT INTO usuarios(nombre,usuario,password,email,role,estado,fecha,codigo,remember,keysp) VALUES(:nombre,:usuario,:password,:email,:level,1,now(),:codigo,0,:keysp)"
                )
                ->execute(array(
                    ":nombre" => $nombre,
                    ":usuario" => $usuario,
                    ":password" => $password,
                    ":email" => $email,
                    ":level" => Session::getLevel(LEVEL),
                    ":codigo" => $random,
                    ":keysp" => $keysp
        ));
        $id = $this->_db->lastInsertId();
        $this->_db->prepare(
                        "INSERT INTO datosUsuarios(id) VALUES(:id)"
                )
                ->execute(array(
                    ":id" => $id,
        ));
        if ($informed) {
            $this->activarInformed($id);
        }
        Functions::CrearCarpeta(null, $usuario);
        Functions::CrearCarpeta(null, $usuario . "/custom_songs");
    }

    public function registrarUsuario($nombre, $usuario, $password, $email, $informed = false) {
        $random = rand(1782598471, 9999999999);
        $keysp = Hash::encrypt($password, HASH_KEY);
        $password = HASH::getHash(HASH_ALGORITMO, $password, HASH_KEY);
        $level = Session::getLevel(LEVEL);
        $this->_db->prepare(
                        "INSERT INTO usuarios(nombre,usuario,password,email,role,estado,fecha,codigo,remember,keysp) VALUES(:nombre,:usuario,:password,:email,:level,1,now(),:codigo,0,:keysp)"
                )
                ->execute(array(
                    ":nombre" => $nombre,
                    ":usuario" => $usuario,
                    ":password" => HASH::getHash(HASH_ALGORITMO, $password, HASH_KEY),
                    ":email" => $email,
                    ":level" => Session::getLevel(LEVEL),
                    ":codigo" => $random,
                    ":keysp" => $keysp
        ));
        $id = $this->_db->lastInsertId();
        $this->_db->prepare(
                        "INSERT INTO datosUsuarios(id) VALUES(:id)"
                )
                ->execute(array(
                    ":id" => $id,
        ));
        if ($informed) {
            $this->activarInformed($id);
        }
        Functions::CrearCarpeta(null, $usuario);
        Functions::CrearCarpeta(null, $usuario . "/custom_songs");
    }

    public function getUsuario($id, $codigo) {
        $usuario = $this->_db->query(
                "SELECT * FROM usuarios WHERE id='$id' and codigo='$codigo'"
        );
        return $usuario->fetch();
    }

    public function getUsuarioId($id) {
        $usuario = $this->_db->query(
                "SELECT * FROM datosUsuarios WHERE id='$id'"
        );
        return $usuario->fetch();
    }

    public function getUsuarioUp($usuario, $password) {
        $password = Hash::getHash("sha1", $password, HASH_KEY);
        $sql = "SELECT * FROM usuarios " .
                "WHERE usuario = '$usuario' " .
                "and password = '" . $password . "'";
        $datos = $this->_db->query($sql);
        $data = $datos->fetch(PDO::FETCH_ASSOC);
        return $data;
    }

    public function activarUsuario($id, $codigo) {
        $this->_db->query(
                "UPDATE usuarios SET estado=1 "
                . "WHERE id='$id' and codigo='$codigo'"
        );
    }

    public function activarEmail($id) {
        $this->_db->query(
                "UPDATE datosUsuarios SET confirmedEmail=1 "
                . "WHERE id='$id'"
        );
    }

    public function activarInformed($id) {
        $this->_db->prepare("UPDATE datosUsuarios SET informed=1 WHERE id=:idUsuario")
                ->execute(
                        array(
                            ":idUsuario" => $id,
                        )
        );
    }

}
