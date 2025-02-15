<?php

class aclModel extends Model {

    public function __construct() {
        parent::__construct();
    }

    public function getRole($roleID) {
        $role = $this->_db->query("SELECT * FROM roles WHERE id_role='{$roleID}'");
        return $role->fetch();
    }

    public function getRoles() {
        $role = $this->_db->query("SELECT * FROM roles");
        return $role->fetchAll();
    }

    public function getPermisosAll() {
        $permisos = $this->_db->query("SELECT * FROM permisos");
        $permisos = $permisos->fetchAll(PDO::FETCH_ASSOC);
        for ($i = 0; $i < count($permisos); $i++) {
            if ($permisos[$i]['keys'] == '') {
                continue;
            }
            $data[$permisos[$i]['keys']] = array(
                'key' => $permisos[$i]['keys'],
                'valor' => 'x',
                'nombre' => $permisos[$i]['permiso'],
                'id' => $permisos[$i]['id_permiso']
            );
        }
        return $data;
    }

    public function getPermisos() {
        $permisos = $this->_db->query("SELECT * FROM permisos");

        return $permisos->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPermisosRole($roleID) {
        $data = array();

        $permisos = $this->_db->query("SELECT * FROM permisos_role WHERE role='{$roleID}'");
        $permisos = $permisos->fetchAll(PDO::FETCH_ASSOC);

        for ($i = 0; $i < count($permisos); $i++) {
            $key = $this->getPermisoKey($permisos[$i]['permiso']);
            if ($key == '') {
                continue;
            }
            if ($permisos[$i]['valor'] == 1) {
                $v = 1;
            } else {
                $v = 0;
            }
            $data[$key] = array(
                'key' => $key,
                'valor' => $v,
                'nombre' => $this->getPermisoNombre($permisos[$i]['permiso']),
                'id' => $permisos[$i]['permiso']
            );
        }
        $data = array_merge($this->getPermisosAll(), $data);
        return $data;
    }

    public function eliminarPermisoRole($roleID, $permisoID) {
        $this->_db->query(
                "DELETE FROM permisos_role " .
                "WHERE permiso = {$permisoID} " .
                "AND role = {$roleID}"
        );
    }

    public function editarPermisoRole($roleID, $permisoID, $valor) {
        $this->_db->query(
                "replace into permisos_role set role = {$roleID}, permiso = {$permisoID}, valor = '{$valor}'"
        );
    }

    public function insertarRole($role) {
        $this->_db->query("INSERT INTO roles VALUES(null, '{$role}')");
    }

    public function getPermisoKey($permisoID) {
        $permisoID = (int) $permisoID;

        $key = $this->_db->query(
                "select `keys` from permisos " .
                "where id_permiso = {$permisoID}"
        );

        $key = $key->fetch();
        return $key['keys'];
    }

    public function getPermisoNombre($permisoID) {
        $permisoID = (int) $permisoID;

        $key = $this->_db->query(
                "select `permiso` from permisos " .
                "where id_permiso = {$permisoID}"
        );

        $key = $key->fetch();
        return $key['permiso'];
    }

    public function insertarPermiso($permiso, $llave) {
        $this->_db->query(
                "INSERT INTO permisos VALUES" .
                "(null, '{$permiso}', '{$llave}')"
        );
    }

}
