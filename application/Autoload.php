<?php

/*
 * -------------------------------------
 * www.interactivesweb.com | Rafa Gonzalez Muñoz
 * framework mvc basico
 * Registry.php
 * -------------------------------------
 */

function autoloadCore($class) {
    if (file_exists(APP_PATH . ucfirst(strtolower($class)) . ".php")) {
        include_once APP_PATH . ucfirst(strtolower($class)) . ".php";
    }
}

function autoloadLibs($libs) {
    if (file_exists(ROOT . 'libs' . DS . 'class.' . strtolower($libs) . ".php")) {
        include_once ROOT . 'libs' . DS . 'class.' . strtolower($libs) . ".php";
    }
}

spl_autoload_register("autoloadCore");
spl_autoload_register("autoloadLibs");
