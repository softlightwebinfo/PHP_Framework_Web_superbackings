<?php
/*
 * -------------------------------------
 * www.interactivesweb.com | Rafael Gonzalez Muñoz
 * framework mvc basico
 * index.php
 * -------------------------------------
 */
ini_set("display_errors", 0);
# Es mostrar la forma en la que se separan las carpetas. Por ejemplo. En Windows es \ en Linux es /
define('DS', DIRECTORY_SEPARATOR);
# La ruta raiz de la aplicación
define('ROOT', realpath(dirname(__FILE__)) . DS);
# Definimos el directorio de las aplicaciones
define('APP_PATH', ROOT . 'application' . DS);
# Incluimos los archivos base de la aplicacion
try {
    require_once APP_PATH . 'Autoload.php';
    require_once APP_PATH . 'Config.php';
    Session::init();
    $registry = Registry::getInstancia();
    $registry->_request = new Request();
    $registry->_db = new Database(DB_HOST, DB_NAME, DB_USER, DB_PASS, DB_CHAR);
    $registry->_db2 = new Database(DB_HOST, DB_NAME, DB_USER, DB_PASS, DB_CHAR);
    $registry->_acl = new ACL();
    Bootstrap::run($registry->_request);
} catch (Exception $ex) {
    $ex = $ex->getMessage();
    ?>
    <META HTTP-EQUIV="REFRESH" CONTENT="0;URL=<?= BASE_URL . "error/access/404/" ?>">
    <?php
}
?>
