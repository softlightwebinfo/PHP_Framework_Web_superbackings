<?php

class indexController extends usuariosController {

    private $_usuarios;

    public function __construct() {
        parent::__construct();
        $this->_usuarios = $this->loadModel("index");
        $this->_view->setJsPlugin(array('min/Funciones.min'));
    }

    public function index() {
        $this->_acl->acceso("admin_access");
        $this->_view->titulo = "Usuarios";
        $this->_view->usuarios = $this->_usuarios->getUsuarios();
        $this->_view->renderizar("index");
    }

    public function permisos($usuarioID) {
        $this->_acl->acceso("admin_access");
        $id = $this->filtrarInt($usuarioID);
        if (!$id) {
            $this->redireccionar('usuarios');
        }
        if ($this->getInt("guardar")) {
            $values = array_keys($_POST);
            $replace = array();
            $eliminar = array();
            for ($i = 0; $i < count($values); $i++) {
                if (substr($values[$i], 0, 5) == 'perm_') {
                    $permiso = (strlen($values[$i]) - 5);

                    if ($_POST[$values[$i]] == 'x') {
                        $eliminar[] = array(
                            'permiso' => substr($values[$i], -$permiso),
                            'valor' => $_POST[$values[$i]],
                            'usuario' => $id,
                        );
                    } else {
                        if ($_POST[$values[$i]] == 1) {
                            $v = 1;
                        } else {
                            $v = 0;
                        }
                        $replace[] = array(
                            'usuario' => $id,
                            'permiso' => substr($values[$i], -$permiso),
                            'valor' => $v
                        );
                    }
                }
            }
            for ($e = 0; $e < count($eliminar); $e++) {
                $this->_usuarios->eliminarPermiso(
                        $eliminar[$e]['usuario'], $eliminar[$e]['permiso']
                );
            }
            for ($i = 0; $i < count($replace); $i++) {
                $this->_usuarios->editarPermiso(
                        $replace[$i]['usuario'], $replace[$i]['permiso'], $replace[$i]['valor']
                );
            }
        }
        $permisosUsuario = $this->_usuarios->getPermisosUsuario($id);
        $permisosRole = $this->_usuarios->getPermisosRole($id);
        if (!$permisosUsuario || !$permisosRole) {
            $this->redireccionar('usuarios');
        }
        $this->_view->titulo = "Permisos de usuario";
        $this->_view->permisos = array_keys($permisosUsuario);
        $this->_view->usuario = $permisosUsuario;
        $this->_view->info = $this->_usuarios->getUsuario($id);
        $this->_view->role = $permisosRole;
        $this->_view->renderizar("permisos");
    }

}
