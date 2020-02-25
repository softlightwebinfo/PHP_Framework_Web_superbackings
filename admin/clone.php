<?php

//// variables
//$ftp_server = "127.0.0.1";
//$ftp_user_name = "backtv";
//$ftp_user_pass = "servidores=x10";
//$destination_file = "demo/";
//$source_file = "demo/";
//
//// conexión
//$conn_id = ftp_connect($ftp_server);
//
//// logeo
//$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
//
//// conexión
//if ((!$conn_id) || (!$login_result)) {
//    echo "Conexión al FTP con errores!";
//    echo "Intentando conectar a $ftp_server for user $ftp_user_name";
//    exit;
//} else {
//    echo "Conectado a $ftp_server, for user $ftp_user_name";
//}
//$url = "www/demos/asubir/";
//$contents = ftp_nlist($conn_id, $url);
//
//if ($contents) {
////
//    foreach ($contents as $key => $value) {
//        if ($value == "." || $value == ".." || $value == "asubir") {
//            
//        } else {
//            if (ftp_get($conn_id, "../demos/asubir/$value", $url . "$value", FTP_BINARY)) {
//                echo "Se ha guardado satisfactoriamente en $local_file\n";
//            } else {
//                echo "Ha habido un problema\n";
//            }
//        }
//    }
//
//
//
//    echo "<pre>";
//    var_dump($contents);
//    echo "</pre>";
//} else {
//    echo "error";
//}
//
//// cerramos
//ftp_close($conn_id);
