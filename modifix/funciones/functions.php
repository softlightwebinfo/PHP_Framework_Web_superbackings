<?php
if (file_exists("funciones/modifix.php")) {
    require_once 'funciones/modifix.php';
} else {
    require_once '../../funciones/modifix.php';
}
$ADMIN = new modifix();
$ADMIN->autoLoadClases($nombre_clase);
$USUARIOS = new Users();
$VENTAS = new Ventas();
/* * *************Validar usuario************** */
Session::accesoEstrictoAdmin(array('1'));
?>