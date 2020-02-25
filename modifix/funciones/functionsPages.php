<?php

$SERVER = $_SERVER['DOCUMENT_ROOT'] . "/modifix/";

if (file_exists($SERVER . "funciones/modifix.php")) {
    require_once $SERVER . 'funciones/modifix.php';
}
$ADMIN = new modifix();
spl_autoload_register(function ($nombre_clase) {
    include "class/" . $nombre_clase . '.php';
});
//$ADMIN->autoLoadClases($nombre_clasea);
/* * *************Validar usuario************** */
Session::accesoEstrictoAdmin(array('1'));
$USUARIOS = new Users();
$TRACKS = new Tracks();
$VENTAS = new Ventas();
$hash = new Hash();
//$DB = $ADMIN->_db;
