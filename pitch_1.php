<?php

$pitch = $_GET['pitch'];
$name = $_GET['name'];
$fin = $_GET['name_pitch'];

//echo "pitch = $pitch | name=$name | fin = $fin";
/* Primera posicion, 1 Sentimos = 1 semitono,posicion final */
$ruta = 'demos-pitchando/' . 125 . '.txt';
$fullpath = 'demos/' . $fin;

if ($pitch != 0) {
//    $headers = get_headers($fullpath, 1);
//
//    if (preg_match('/404/', $headers[0])) {
//        echo "no-existe";
//    } else {
//        echo "existe";
//    }
    if(is_file($fullpath))
    {
        echo "existe";
    }else{
        echo "no-existe";
    }
} else {
    echo "0";
}
