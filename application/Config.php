<?php

/*
 * ------------------------------------- *
 * www.interactivesweb.com | Rafael Gonzalez 
 * framework mvc basico 
 * Database.php
 * ------------------------------------- */
# Controlador por defecto de la aplicación
define("BASE_URL", "https://www.superbackings.com/");
define("BASE_URL_ADMIN", "https://www.superbackings.com/modifix/");
define("DEFAULT_CONTROLLER", "index");
define("DEFAULT_LAYOUT", "superbackings");
define("BASE_URL_IMG", BASE_URL . "views/layout/" . DEFAULT_LAYOUT . "/img/");
# Config de la aplicacion
define("APP_NAME", "Mi Framework");
define("APP_SLOGAN", "Mi primer framework PHP MVC...");
define("APP_COMPANY", "www.interactivesweb.com");
define("SESSION_TIME", 60);
define("HASH_ALGORITMO", "sha1");
define("HASH_KEY", "5673f53f0b4dc");
define("PASSWORD_RESET", "1234");
define("LEVEL", "usuario");
define("DEBUG", true);
define("MIN_CSS", 'min/');
define("MIN_JS", 'min/');
# Config del Framework
define("FRAMEWORK_VERSION", "1.00");
# Config de la BD
define("DB_HOST", "localhost");
define("DB_USER", "superb");
define("DB_PASS", "servidores=x10");
define("DB_NAME", "superb_new");
define("DB_CHAR", "utf8");
?>