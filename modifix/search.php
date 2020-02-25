<?php

$SERVER = $_SERVER['DOCUMENT_ROOT'] . "/modifix/";
ini_set('error_reporting', E_ALL);
require_once($SERVER . 'funciones/functionsPages.php');
$TRACKS->Paginador($_GET['valor'], "modifix/pages/tracks/?page=", 'datosTraks');
echo $TRACKS->LinksPagination;
